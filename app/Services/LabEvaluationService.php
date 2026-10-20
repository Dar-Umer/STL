<?php

namespace App\Services;

use App\Models\EvaluationBatch;
use App\Models\SampleStatusHistory;
use App\Models\SoilSample;
use Illuminate\Support\Facades\DB;

class LabEvaluationService
{
    /**
     * Format a lab sequence number according to admin settings.
     */
    public static function formatLabNumber(int $num): string
    {
        $prefix = trim((string) setting('lab_number_prefix', ''));
        $padding = (int) setting('lab_number_padding', 0);
        $includeYear = (bool) setting('lab_number_include_year', false);

        $numStr = $padding > 0 ? str_pad((string) $num, $padding, '0', STR_PAD_LEFT) : (string) $num;
        $yearPart = $includeYear ? date('Y') . '-' : '';

        return "{$prefix}{$yearPart}{$numStr}";
    }

    /**
     * Preview what the next lab number will look like based on settings.
     */
    public static function peekNextLabNumber(): string
    {
        $next = (int) setting('lab_number_next', 1001);
        return self::formatLabNumber($next);
    }

    /**
     * Allocate a given number of sequential, unique lab numbers atomically.
     *
     * @param int $count
     * @return array<int, string>
     */
    public static function allocateLabNumbers(int $count): array
    {
        if ($count <= 0) {
            return [];
        }

        return DB::transaction(function () use ($count) {
            $currentNext = (int) setting('lab_number_next', 1001);
            $codes = [];
            $num = $currentNext;

            for ($i = 0; $i < $count; $i++) {
                do {
                    $candidate = self::formatLabNumber($num);
                    $exists = SoilSample::where('lab_number', $candidate)->exists();
                    if ($exists) {
                        $num++;
                    }
                } while ($exists);

                $codes[] = $candidate;
                $num++;
            }

            set_setting('lab_number_next', $num);

            return $codes;
        });
    }

    /**
     * Receive an array of samples into a new Evaluation Batch with auto-incremented lab numbers.
     *
     * @param array<int> $sampleIds
     * @param int|null $labId
     * @param int|null $userId
     * @param string|null $notes
     * @return EvaluationBatch
     */
    public static function receiveSamplesIntoBatch(array $sampleIds, ?int $labId = null, ?int $userId = null, ?string $notes = null): EvaluationBatch
    {
        return DB::transaction(function () use ($sampleIds, $labId, $userId, $notes) {
            $samples = SoilSample::whereIn('id', $sampleIds)->lockForUpdate()->get();

            if ($samples->isEmpty()) {
                throw new \InvalidArgumentException('No eligible samples found to receive.');
            }

            $count = $samples->count();
            $labCodes = self::allocateLabNumbers($count);

            // Generate unique batch code: EVB-YYYYMMDD-###
            $datePrefix = date('Ymd');
            $todayCount = EvaluationBatch::whereDate('created_at', today())->count();
            $batchCode = 'EVB-' . $datePrefix . '-' . str_pad((string) ($todayCount + 1), 3, '0', STR_PAD_LEFT);
            while (EvaluationBatch::where('batch_code', $batchCode)->exists()) {
                $todayCount++;
                $batchCode = 'EVB-' . $datePrefix . '-' . str_pad((string) ($todayCount + 1), 3, '0', STR_PAD_LEFT);
            }

            $batch = EvaluationBatch::create([
                'batch_code' => $batchCode,
                'lab_id' => $labId ?? $samples->first()->lab_id ?? auth()->user()?->lab_id,
                'received_by' => $userId ?? auth()->id(),
                'sample_count' => $count,
                'lab_number_from' => $labCodes[0] ?? null,
                'lab_number_to' => $labCodes[$count - 1] ?? null,
                'notes' => $notes,
            ]);

            $now = now();
            foreach ($samples as $index => $sample) {
                $oldStatus = $sample->status->value;
                $assignedCode = $labCodes[$index];

                $sample->update([
                    'lab_number' => $assignedCode,
                    'evaluation_batch_id' => $batch->id,
                    'status' => 'received',
                    'received_at' => $now,
                ]);

                SampleStatusHistory::create([
                    'soil_sample_id' => $sample->id,
                    'from_status' => $oldStatus,
                    'to_status' => 'received',
                    'changed_by' => $userId ?? auth()->id(),
                ]);
            }

            return $batch;
        });
    }

    /**
     * Backfill lab numbers for existing received or reported samples that don't have one.
     */
    public static function backfillMissingLabNumbers(): int
    {
        $samples = SoilSample::whereNull('lab_number')
            ->whereIn('status', ['received', 'testing', 'reported'])
            ->orderBy('id', 'asc')
            ->get();

        if ($samples->isEmpty()) {
            return 0;
        }

        return DB::transaction(function () use ($samples) {
            $codes = self::allocateLabNumbers($samples->count());

            // Create a legacy evaluation batch for backfilled records
            $batch = EvaluationBatch::create([
                'batch_code' => 'EVB-LEGACY-' . date('Ymd'),
                'lab_id' => $samples->first()->lab_id,
                'received_by' => auth()->id() ?? 1,
                'sample_count' => $samples->count(),
                'lab_number_from' => $codes[0] ?? null,
                'lab_number_to' => $codes[count($codes) - 1] ?? null,
                'notes' => 'Auto-generated legacy evaluation batch for existing samples.',
            ]);

            foreach ($samples as $i => $sample) {
                $sample->update([
                    'lab_number' => $codes[$i],
                    'evaluation_batch_id' => $batch->id,
                ]);
            }

            return $samples->count();
        });
    }
}
