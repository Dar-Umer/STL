<?php

namespace App\Services;

use App\Models\SoilSample;
use App\Models\RecommendationRule;

class RecommendationEngine
{
    public function generate(SoilSample $sample): string
    {
        $sample->loadMissing(['results.parameter', 'plot.crop']);
        $rules = RecommendationRule::active()->ordered()->get();

        $matched = [];

        foreach ($rules as $rule) {
            if ($this->evaluateConditions($rule->conditions, $sample)) {
                $matched[] = $rule->recommendation;
            }
        }

        return implode("\n\n", $matched);
    }

    protected function evaluateConditions(array $conditions, SoilSample $sample): bool
    {
        if (empty($conditions)) {
            return true;
        }

        foreach ($conditions as $condition) {
            if (!$this->evaluateCondition((object) $condition, $sample)) {
                return false;
            }
        }

        return true;
    }

    protected function evaluateCondition(object $condition, SoilSample $sample): bool
    {
        return match ($condition->type ?? '') {
            'parameter_value' => $this->checkParameterValue($condition, $sample),
            'parameter_rating' => $this->checkParameterRating($condition, $sample),
            'plant_age' => $this->checkPlantAge($condition, $sample),
            'crop_is' => $this->checkCrop($condition, $sample),
            default => false,
        };
    }

    protected function checkParameterValue(object $condition, SoilSample $sample): bool
    {
        $result = $sample->results->firstWhere('parameter_id', $condition->parameter_id ?? 0);
        if (!$result || $result->value === null) {
            return false;
        }

        $value = (float) $result->value;
        $target = (float) ($condition->value ?? 0);

        return match ($condition->operator ?? 'eq') {
            'lt' => $value < $target,
            'lte' => $value <= $target,
            'eq' => abs($value - $target) < 0.001,
            'gte' => $value >= $target,
            'gt' => $value > $target,
            'between' => $value >= $target && $value <= (float) ($condition->value_max ?? $target),
            default => false,
        };
    }

    protected function checkParameterRating(object $condition, SoilSample $sample): bool
    {
        $result = $sample->results->firstWhere('parameter_id', $condition->parameter_id ?? 0);
        if (!$result || !$result->rating) {
            return false;
        }

        return strtolower($result->rating) === strtolower($condition->rating ?? '');
    }

    protected function checkPlantAge(object $condition, SoilSample $sample): bool
    {
        $plantAge = $sample->plot?->age_of_plants;
        if ($plantAge === null) {
            return false;
        }

        $value = (int) $plantAge;
        $target = (int) ($condition->value ?? 0);

        return match ($condition->operator ?? 'eq') {
            'lt' => $value < $target,
            'lte' => $value <= $target,
            'eq' => $value === $target,
            'gte' => $value >= $target,
            'gt' => $value > $target,
            default => false,
        };
    }

    protected function checkCrop(object $condition, SoilSample $sample): bool
    {
        $cropId = $sample->plot?->crop_id;
        if ($cropId === null) {
            return false;
        }

        return (int) $cropId === (int) ($condition->crop_id ?? 0);
    }
}
