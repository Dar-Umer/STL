<?php

namespace Database\Seeders;

use App\Models\Crop;
use App\Models\SoilSample;
use App\Models\SoilTestParameter;
use App\Models\RecommendationRule;
use App\Services\RecommendationEngine;
use Illuminate\Database\Seeder;

class RecommendationRuleSeeder extends Seeder
{
    public function run(): void
    {
        $params = SoilTestParameter::whereIn('name', ['pH', 'EC', 'OC', 'N', 'P', 'K', 'S', 'Zn', 'Fe', 'Cu', 'Mn', 'Bo'])
            ->get()
            ->keyBy('name');

        if ($params->count() < 4) {
            $this->command->error('Required core parameters (pH, N, P, K) not found.');
            return;
        }

        $appleCrop = Crop::where('name', 'Apple')->first();
        $appleId = $appleCrop ? $appleCrop->id : 11;

        $pH   = $params['pH']->id ?? 1;
        $N    = $params['N']->id ?? 4;
        $P    = $params['P']->id ?? 5;
        $K    = $params['K']->id ?? 6;
        $OC   = $params['OC']->id ?? 3;

        // Truncate or refresh rules to remove old contradictory field crop rules
        RecommendationRule::truncate();

        $rules = [
            // =========================================================================
            // 🍎 APPLE ORCHARDS IN KASHMIR (SKUAST-K & J&K HORTICULTURE GUIDELINES)
            // =========================================================================

            // --- Baseline Orchard Nutrition ---
            [
                'name' => 'Apple - Baseline Orchard Care',
                'description' => 'Annual winter Farm Yard Manure (FYM) and basin moisture maintenance',
                'priority' => 100,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                ],
                'recommendation' => "Apply well-decomposed Farm Yard Manure (FYM) @ 15-20 kg/tree (or vermicompost @ 8-10 kg/tree) annually in November-December during winter dormancy. Incorporate in a 15-20 cm deep circular trench along the canopy drip line (1.5-2m from trunk). Ensure adequate soil moisture during green-tip, bud-burst, and post-fruit set stages.",
            ],

            // --- Soil Reaction (pH) ---
            [
                'name' => 'Apple - pH Low (Acidic Soil)',
                'description' => 'Soil pH is acidic (< 6.0)',
                'priority' => 90,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $pH, 'rating' => 'Low'],
                ],
                'recommendation' => "Soil reaction is acidic (pH < 6.0), which limits phosphorus, calcium, and magnesium uptake. Apply agricultural lime or dolomite @ 2.5-3.0 kg/tree (or 2.5 qtl/kanal) broadcast uniformly across the tree basin before winter snowfall. Avoid acid-forming fertilizers (ammonium sulphate); prefer Urea or Calcium Ammonium Nitrate (CAN).",
            ],
            [
                'name' => 'Apple - pH High (Alkaline Karewa Soil)',
                'description' => 'Soil pH is alkaline (> 7.5)',
                'priority' => 90,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $pH, 'rating' => 'High'],
                ],
                'recommendation' => "Soil reaction is alkaline (pH > 7.5), common in Karewa soils of Kashmir, inducing lime-induced iron and zinc chlorosis. Apply agricultural gypsum @ 1.0-1.5 kg/tree or elemental sulphur @ 200-250 g/tree mixed with moist compost in the basin during winter dormancy. Avoid excessive liming and unacidified DAP broadcasting.",
            ],

            // --- Available Nitrogen (N) ---
            [
                'name' => 'Apple - Nitrogen Low',
                'description' => 'Nitrogen is deficient (< 280 kg/ha)',
                'priority' => 80,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $N, 'rating' => 'Low'],
                ],
                'recommendation' => "Available Nitrogen is low (< 280 kg/ha). Apply Urea @ 450-500 g/tree/year (or CAN @ 800 g/tree) in two split applications: 60% at green tip / bud-burst (March) and 40% post-fruit set (May/June). Supplement with 20-25 kg well-rotted FYM to improve organic nitrogen reserves and prevent leaf pale yellowing.",
            ],
            [
                'name' => 'Apple - Nitrogen Medium',
                'description' => 'Nitrogen is in the optimal range (280 - 420 kg/ha)',
                'priority' => 80,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $N, 'rating' => 'Medium'],
                ],
                'recommendation' => "Available Nitrogen is optimal (280-420 kg/ha). Maintain standard maintenance dose of Urea @ 350-400 g/tree/year split into two equal applications: 50% at spring bud-burst and 50% at petal-fall stage.",
            ],
            [
                'name' => 'Apple - Nitrogen High',
                'description' => 'Nitrogen is in excess (> 420 kg/ha)',
                'priority' => 80,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $N, 'rating' => 'High'],
                ],
                'recommendation' => "Available Nitrogen is high (> 420 kg/ha). Excess N stimulates vigorous vegetative shoots at the expense of fruit buds, delays red color development, and elevates bitter pit and scab risk. Skip or reduce spring Urea by 50% and avoid late-season nitrogenous top-dressing.",
            ],

            // --- Available Phosphorus (P) ---
            [
                'name' => 'Apple - Phosphorus Low',
                'description' => 'Phosphorus is deficient (< 15 kg/ha)',
                'priority' => 70,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $P, 'rating' => 'Low'],
                ],
                'recommendation' => "Available Phosphorus is deficient (< 15 kg/ha). Apply Single Super Phosphate (SSP) @ 1.2-1.5 kg/tree or DAP @ 450-500 g/tree in a circular trench along the drip line during winter dormancy (Dec-Feb). SSP is preferred in neutral/calcareous soils as it also provides sulphur and calcium.",
            ],
            [
                'name' => 'Apple - Phosphorus Medium',
                'description' => 'Phosphorus is optimal (15 - 30 kg/ha)',
                'priority' => 70,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $P, 'rating' => 'Medium'],
                ],
                'recommendation' => "Available Phosphorus is adequate (15-30 kg/ha). Apply maintenance dose of DAP @ 200-250 g/tree (or SSP @ 500-600 g/tree) during winter dormancy along with Farm Yard Manure.",
            ],
            [
                'name' => 'Apple - Phosphorus High',
                'description' => 'Phosphorus is abundant (> 30 kg/ha)',
                'priority' => 70,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $P, 'rating' => 'High'],
                ],
                'recommendation' => "Available Phosphorus is abundant (> 30 kg/ha). Omit chemical phosphorus fertilizers (DAP/SSP) for this season. Excessive soil P inhibits zinc, iron, and copper root uptake.",
            ],

            // --- Available Potassium (K) ---
            [
                'name' => 'Apple - Potassium Low',
                'description' => 'Potassium is deficient (< 140 kg/ha)',
                'priority' => 60,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $K, 'rating' => 'Low'],
                ],
                'recommendation' => "Available Potassium is low (< 140 kg/ha). Apple is a heavy potassium feeder; K governs fruit enlargement, sugar synthesis, crispness, and red blush. Apply Muriate of Potash (MOP) @ 500-600 g/tree (or SOP @ 600-700 g/tree) in winter dormancy. Follow with 2 foliar sprays of Potassium Sulphate (0.5%) during June-July fruit sizing.",
            ],
            [
                'name' => 'Apple - Potassium Medium',
                'description' => 'Potassium is optimal (140 - 280 kg/ha)',
                'priority' => 60,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $K, 'rating' => 'Medium'],
                ],
                'recommendation' => "Available Potassium is adequate (140-280 kg/ha). Apply maintenance dose of MOP @ 300-350 g/tree (or SOP @ 400 g/tree) as a basal winter dressing. Ensure balanced soil moisture during June-July fruit cell expansion.",
            ],
            [
                'name' => 'Apple - Potassium High',
                'description' => 'Potassium is high (> 280 kg/ha)',
                'priority' => 60,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $K, 'rating' => 'High'],
                ],
                'recommendation' => "Available Potassium is high (> 280 kg/ha). Reduce MOP application to 150 g/tree. Excessive potassium suppresses calcium uptake, increasing bitter pit risk. Apply 2-3 foliar sprays of Calcium Nitrate @ 0.5% at 15-day intervals during fruit sizing.",
            ],

            // --- Organic Carbon (OC) for Apple ---
            [
                'name' => 'Apple - Organic Carbon Low',
                'description' => 'Soil organic carbon is low (< 0.5%)',
                'priority' => 50,
                'conditions' => [
                    ['type' => 'crop_is', 'crop_id' => $appleId],
                    ['type' => 'parameter_rating', 'parameter_id' => $OC, 'rating' => 'Low'],
                ],
                'recommendation' => "Soil organic carbon is low (< 0.5%). Increase organic inputs by applying 25-30 kg FYM or vermicompost per tree basin. Practice straw/grass mulching along tree basins to conserve moisture, enhance earthworm activity, and build organic matter.",
            ],

            // =========================================================================
            // 🌾 GENERAL FALLBACK RULES (Only evaluated if crop is not Apple)
            // =========================================================================
            [
                'name' => 'General - pH Low',
                'description' => 'Soil pH is acidic',
                'priority' => 10,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $pH, 'rating' => 'Low'],
                ],
                'recommendation' => "Soil pH is low (acidic). Apply agricultural limestone / dolomite @ 2-3 qtl/ha to raise pH. Avoid acid-forming fertilizers.",
            ],
            [
                'name' => 'General - pH High',
                'description' => 'Soil pH is alkaline',
                'priority' => 10,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $pH, 'rating' => 'High'],
                ],
                'recommendation' => "Soil pH is high (alkaline). Apply agricultural gypsum @ 3-5 qtl/ha or elemental sulphur @ 100-150 kg/ha to improve nutrient availability.",
            ],
            [
                'name' => 'General - Nitrogen Low',
                'description' => 'Nitrogen is deficient',
                'priority' => 8,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $N, 'rating' => 'Low'],
                ],
                'recommendation' => "Available Nitrogen is low. Apply recommended dose of Urea in split applications along with well-rotted FYM.",
            ],
            [
                'name' => 'General - Phosphorus Low',
                'description' => 'Phosphorus is deficient',
                'priority' => 7,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $P, 'rating' => 'Low'],
                ],
                'recommendation' => "Available Phosphorus is low. Apply DAP or Single Super Phosphate (SSP) as basal placement near the root zone.",
            ],
            [
                'name' => 'General - Potassium Low',
                'description' => 'Potassium is deficient',
                'priority' => 6,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $K, 'rating' => 'Low'],
                ],
                'recommendation' => "Available Potassium is low. Apply Muriate of Potash (MOP) as basal dressing to improve crop resilience and yield.",
            ],
            [
                'name' => 'General - Soil Maintenance',
                'description' => 'General soil fertility maintenance',
                'priority' => 1,
                'conditions' => [],
                'recommendation' => "Apply well-decomposed Farm Yard Manure (FYM) @ 10-15 t/ha annually. Practice balanced N-P-K fertilization and regular soil testing.",
            ],
        ];

        foreach ($rules as $rule) {
            RecommendationRule::create([
                'name' => $rule['name'],
                'description' => $rule['description'],
                'conditions' => $rule['conditions'],
                'recommendation' => $rule['recommendation'],
                'priority' => $rule['priority'],
                'is_active' => true,
            ]);
        }

        $this->command->info('Seeded ' . count($rules) . ' accurate Kashmir Apple and core soil health recommendation rules.');

        // Automatically refresh existing samples with results so they immediately reflect the accurate rules
        $engine = app(RecommendationEngine::class);
        $samples = SoilSample::with(['results.parameter', 'plot.crop'])->has('results')->get();
        foreach ($samples as $sample) {
            $sample->update([
                'recommendations' => $engine->generate($sample),
            ]);
        }
        $this->command->info('Refreshed recommendations for ' . $samples->count() . ' soil samples.');
    }
}
