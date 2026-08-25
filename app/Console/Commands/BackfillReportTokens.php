<?php

namespace App\Console\Commands;

use App\Models\SoilSample;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class BackfillReportTokens extends Command
{
    protected $signature = 'backfill:report-tokens';
    protected $description = 'Generate report tokens for existing samples without one';

    public function handle()
    {
        $count = SoilSample::whereNull('report_token')->count();
        SoilSample::whereNull('report_token')->each(function ($sample) {
            $sample->update(['report_token' => Str::random(32)]);
        });
        $this->info("Backfilled {$count} samples.");
    }
}
