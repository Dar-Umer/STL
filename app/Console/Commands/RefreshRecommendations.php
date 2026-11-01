<?php

namespace App\Console\Commands;

use App\Models\SoilSample;
use App\Services\RecommendationEngine;
use Illuminate\Console\Command;

class RefreshRecommendations extends Command
{
    protected $signature = 'recommendations:refresh';
    protected $description = 'Re-evaluate and update recommendation text for all soil samples with test results';

    public function handle(RecommendationEngine $engine)
    {
        $samples = SoilSample::with(['results.parameter', 'plot.crop'])->has('results')->get();
        $this->info("Found {$samples->count()} samples with test results. Updating recommendations...");

        foreach ($samples as $sample) {
            $sample->update([
                'recommendations' => $engine->generate($sample),
            ]);
        }

        $this->info("Successfully refreshed recommendations for {$samples->count()} samples.");
        return Command::SUCCESS;
    }
}
