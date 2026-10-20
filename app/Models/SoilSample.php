<?php

namespace App\Models;

use App\Enums\SampleStatus;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SoilSample extends Model
{
    use Auditable;
    protected $fillable = [
        'farmer_id', 'plot_id', 'sample_code', 'lab_number', 'evaluation_batch_id',
        'source', 'qr_code_path', 'collected_by', 'collected_at', 'status',
        'received_at', 'reported_at', 'analyzed_by', 'analyzed_at', 'recommendations',
        'evaluation_remarks', 'lab_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($sample) {
            $sample->report_token = static::generateReportToken();
        });
    }

    protected static function generateReportToken(): string
    {
        $token = \Illuminate\Support\Str::random(32);
        while (static::where('report_token', $token)->exists()) {
            $token = \Illuminate\Support\Str::random(32);
        }
        return $token;
    }

    protected function casts(): array
    {
        return [
            'collected_at' => 'datetime',
            'received_at' => 'datetime',
            'reported_at' => 'datetime',
            'analyzed_at' => 'datetime',
        ];
    }

    public function getStatusAttribute($value): SampleStatus
    {
        return SampleStatus::tryFrom($value) ?? SampleStatus::Collected;
    }

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = $value instanceof SampleStatus ? $value->value : $value;
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function analyst()
    {
        return $this->belongsTo(User::class, 'analyzed_by');
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function results()
    {
        return $this->hasMany(SoilSampleResult::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(SampleStatusHistory::class);
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    public function evaluationBatch()
    {
        return $this->belongsTo(EvaluationBatch::class, 'evaluation_batch_id');
    }

    public function scopeByLab($query, $labId = null)
    {
        if ($labId) {
            return $query->where('lab_id', $labId);
        }
        return $query;
    }

    public function consumeChemicals(): void
    {
        $this->load('results.parameter.consumptionRules.labChemical');

        foreach ($this->results as $result) {
            foreach ($result->parameter->consumptionRules as $rule) {
                $chemical = $rule->labChemical;
                if (!$chemical || !$chemical->is_active) continue;

                $before = $chemical->current_stock;
                $consumed = $rule->consumption_per_test;
                $after = $before - $consumed;

                \DB::transaction(function () use ($chemical, $before, $after, $consumed, $rule) {
                    $chemical->decrement('current_stock', $consumed);
                    $chemical->stockMovements()->create([
                        'type' => 'out',
                        'quantity' => $consumed,
                        'balance_before' => $before,
                        'balance_after' => $after,
                        'reference_type' => SoilSample::class,
                        'reference_id' => $this->id,
                        'notes' => "Consumed for {$this->sample_code} — {$rule->parameter->name}",
                        'created_by' => $this->analyzed_by ?? auth()->id(),
                    ]);
                });
            }
        }
    }
}
