<?php

namespace Database\Seeders;

use App\Models\SoilTestParameter;
use App\Models\RecommendationRule;
use Illuminate\Database\Seeder;

class RecommendationRuleSeeder extends Seeder
{
    public function run(): void
    {
        $params = SoilTestParameter::whereIn('name', ['pH', 'EC', 'OC', 'N', 'P', 'K', 'S', 'Zn', 'Fe', 'Cu', 'Mn', 'Bo'])
            ->get()
            ->keyBy('name');

        if ($params->count() < 12) {
            $this->command->error('Only ' . $params->count() . '/12 parameters found. Run ParameterSeeder first.');
            return;
        }

        $pH   = $params['pH']->id;
        $N    = $params['N']->id;
        $P    = $params['P']->id;
        $K    = $params['K']->id;
        $OC   = $params['OC']->id;
        $Zn   = $params['Zn']->id;
        $Fe   = $params['Fe']->id;
        $Mn   = $params['Mn']->id;
        $Bo   = $params['Bo']->id;
        $S    = $params['S']->id;
        $Cu   = $params['Cu']->id;

        $rules = [
            // ── pH ──
            [
                'name' => 'pH Low',
                'description' => 'Soil pH is too acidic',
                'priority' => 1,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $pH, 'rating' => 'Low'],
                ],
                'recommendation' => "Soil pH is low (acidic). This reduces nutrient availability and can harm root function.\n\nApply dolomitic limestone @ 2–4 qtl/ha to raise pH. Re-test pH after 3 months. Avoid acid-forming fertilizers like ammonium sulphate until pH normalises.",
            ],
            [
                'name' => 'pH High',
                'description' => 'Soil pH is too alkaline',
                'priority' => 1,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $pH, 'rating' => 'High'],
                ],
                'recommendation' => "Soil pH is high (alkaline). High pH locks out iron, zinc, and manganese, causing chlorosis.\n\nApply elemental sulphur @ 100–150 kg/ha or gypsum @ 5 qtl/ha to lower pH. Use acid-forming fertilizers (ammonium sulphate). For severe cases, repeat sulphur application after 3 months.",
            ],

            // ── Nitrogen ──
            [
                'name' => 'N Low',
                'description' => 'Nitrogen is deficient',
                'priority' => 2,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $N, 'rating' => 'Low'],
                ],
                'recommendation' => "Nitrogen is low. Deficiency causes pale green leaves, stunted growth, and reduced yield.\n\nApply Urea @ 300–400 kg/ha in 2 split doses — at sowing and at tillering/branching. Incorporate well-decomposed FYM @ 10–15 t/ha to improve N retention. Consider green manuring with legumes.",
            ],
            [
                'name' => 'N High',
                'description' => 'Nitrogen is excessive',
                'priority' => 2,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $N, 'rating' => 'High'],
                ],
                'recommendation' => "Nitrogen is high. Excessive N causes lodging, delayed maturity, increased pest susceptibility, and nitrogen pollution.\n\nReduce or skip the next N application. Avoid urea top-dressing. Balance with adequate potassium. If waterlogged, improve drainage to reduce denitrification losses.",
            ],

            // ── Phosphorus ──
            [
                'name' => 'P Low',
                'description' => 'Phosphorus is deficient',
                'priority' => 3,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $P, 'rating' => 'Low'],
                ],
                'recommendation' => "Phosphorus is low. P is critical for root growth, flowering, and energy transfer.\n\nApply DAP @ 150–200 kg/ha or SSP @ 400–500 kg/ha at sowing. Band-apply near the root zone for better efficiency. In acidic soils, liming improves P availability.",
            ],
            [
                'name' => 'P Medium',
                'description' => 'Phosphorus is moderate — maintenance dose needed',
                'priority' => 3,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $P, 'rating' => 'Medium'],
                ],
                'recommendation' => "Phosphorus is at a maintenance level. Apply SSP @ 200–300 kg/ha at sowing to maintain soil P status. Avoid excess phosphorus as it interferes with zinc and iron uptake.",
            ],

            // ── Potassium ──
            [
                'name' => 'K Low',
                'description' => 'Potassium is deficient',
                'priority' => 4,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $K, 'rating' => 'Low'],
                ],
                'recommendation' => "Potassium is low. K deficiency causes marginal leaf scorch, weak stems, and poor grain filling.\n\nApply MOP @ 100–150 kg/ha. Split into basal dose and top-dress at vegetative stage. K improves drought tolerance and disease resistance.",
            ],
            [
                'name' => 'K Medium',
                'description' => 'Potassium is moderate — maintenance dose needed',
                'priority' => 4,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $K, 'rating' => 'Medium'],
                ],
                'recommendation' => "Potassium is at a maintenance level. Apply MOP @ 50–80 kg/ha as basal dose. Maintain adequate N:K ratio for balanced crop growth.",
            ],

            // ── Organic Carbon ──
            [
                'name' => 'OC Low',
                'description' => 'Soil organic carbon is low',
                'priority' => 5,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $OC, 'rating' => 'Low'],
                ],
                'recommendation' => "Organic carbon is low. Low OC indicates poor soil health, low microbial activity, and poor water-holding capacity.\n\nApply well-decomposed FYM @ 15–20 t/ha or vermicompost @ 5–8 t/ha. Practice mulching with crop residues. Green manuring with legumes (cowpea, dhaincha) during fallow period is highly beneficial. Avoid burning crop residues.",
            ],

            // ── Sulphur ──
            [
                'name' => 'S Low',
                'description' => 'Sulphur is deficient',
                'priority' => 6,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $S, 'rating' => 'Low'],
                ],
                'recommendation' => "Sulphur is low. S is essential for protein synthesis and oil content in crops.\n\nApply elemental sulphur @ 200–300 kg/ha or gypsum @ 250–300 kg/ha. For quick correction, foliar spray of zinc sulphur @ 0.5% at vegetative stage.",
            ],

            // ── Zinc ──
            [
                'name' => 'Zn Low',
                'description' => 'Zinc is deficient',
                'priority' => 7,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Zn, 'rating' => 'Low'],
                ],
                'recommendation' => "Zinc is low. Zn deficiency causes small leaves, internode shortening, and reduced grain quality.\n\nApply zinc sulphate @ 25 kg/ha to soil once in 3 years. For immediate correction, spray ZnSO₄ @ 0.5% + lime @ 0.25% at vegetative stage.",
            ],
            [
                'name' => 'Zn Medium',
                'description' => 'Zinc is moderate — preventive spray recommended',
                'priority' => 7,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Zn, 'rating' => 'Medium'],
                ],
                'recommendation' => "Zinc is at a preventive level. Foliar spray of ZnSO₄ @ 0.3% at critical growth stages is recommended to maintain adequate Zn status.",
            ],

            // ── Iron ──
            [
                'name' => 'Fe Low',
                'description' => 'Iron is deficient',
                'priority' => 8,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Fe, 'rating' => 'Low'],
                ],
                'recommendation' => "Iron is low. Fe deficiency causes interveinal chlorosis of young leaves, reducing photosynthesis.\n\nApply FeSO₄ @ 50–100 kg/ha mixed with FYM. For active chlorosis, spray FeSO₄ @ 0.5% + citric acid @ 0.05%. Use chelated iron (Fe-EDDHA) in calcareous soils.",
            ],
            [
                'name' => 'Fe Medium',
                'description' => 'Iron is moderate — preventive spray recommended',
                'priority' => 8,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Fe, 'rating' => 'Medium'],
                ],
                'recommendation' => "Iron is at a preventive level. Foliar spray of FeSO₄ @ 0.3% at vegetative stage helps maintain adequate iron status, especially in high-pH soils.",
            ],

            // ── Copper ──
            [
                'name' => 'Cu Low',
                'description' => 'Copper is deficient',
                'priority' => 9,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Cu, 'rating' => 'Low'],
                ],
                'recommendation' => "Copper is low. Cu deficiency causes wilting/twisting of young leaves and poor grain set.\n\nApply copper sulphate @ 10–20 kg/ha to soil once in 4–5 years. Foliar spray of CuSO₄ @ 0.2–0.3% at tillering/branching stage for quick correction.",
            ],
            [
                'name' => 'Cu Medium',
                'description' => 'Copper is moderate',
                'priority' => 9,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Cu, 'rating' => 'Medium'],
                ],
                'recommendation' => "Copper is at a preventive level. A light foliar spray of CuSO₄ @ 0.2% at flowering stage is recommended for high-yielding varieties.",
            ],

            // ── Manganese ──
            [
                'name' => 'Mn Low',
                'description' => 'Manganese is deficient',
                'priority' => 10,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Mn, 'rating' => 'Low'],
                ],
                'recommendation' => "Manganese is low. Mn deficiency causes interveinal chlorosis and necrotic spots on young leaves.\n\nApply MnSO₄ @ 50–100 kg/ha to soil or spray MnSO₄ @ 0.5% at vegetative stage. Mn is essential for photosynthesis and enzyme activation.",
            ],

            // ── Boron ──
            [
                'name' => 'B Low',
                'description' => 'Boron is deficient',
                'priority' => 11,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Bo, 'rating' => 'Low'],
                ],
                'recommendation' => "Boron is low. B deficiency causes hollow stem, cracked fruits, and poor seed set.\n\nApply Borax @ 10–15 kg/ha to soil or spray Solubor @ 10 g/10 L at flowering. Avoid excess — the gap between deficiency and toxicity is narrow.",
            ],
            [
                'name' => 'B Medium',
                'description' => 'Boron is moderate — preventive spray recommended',
                'priority' => 11,
                'conditions' => [
                    ['type' => 'parameter_rating', 'parameter_id' => $Bo, 'rating' => 'Medium'],
                ],
                'recommendation' => "Boron is at a preventive level. Foliar spray of Solubor @ 5 g/10 L at flowering stage is recommended for crops sensitive to boron deficiency (mustard, sunflower, groundnut).",
            ],

            // ── General (always matches) ──
            [
                'name' => 'General Soil Health Maintenance',
                'description' => 'Baseline recommendations for all soils',
                'priority' => 100,
                'conditions' => [],
                'recommendation' => "General soil health recommendations:\n1. Apply well-decomposed FYM @ 10–15 t/ha annually to maintain organic matter.\n2. Practice crop rotation — include legumes to fix atmospheric nitrogen.\n3. Avoid over-tillage; consider zero-till or reduced-till where feasible.\n4. Maintain proper drainage to prevent waterlogging and nutrient losses.\n5. Test soil every season to track changes and adjust fertiliser plans.\n6. Use balanced fertilisation — avoid over-reliance on any single nutrient.",
            ],
        ];

        foreach ($rules as $rule) {
            RecommendationRule::updateOrCreate(
                ['name' => $rule['name']],
                [
                    'description' => $rule['description'],
                    'conditions' => $rule['conditions'],
                    'recommendation' => $rule['recommendation'],
                    'priority' => $rule['priority'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Seeded ' . count($rules) . ' universal recommendation rules.');
    }
}
