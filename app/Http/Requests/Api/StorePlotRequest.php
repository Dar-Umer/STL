<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePlotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'farmer_id' => 'required|exists:farmers,id',
            'plot_name' => 'required|string|min:2|max:255',
            'khasra_no' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'soil_type_id' => 'nullable|exists:soil_types,id',
            'soil_color_id' => 'nullable|exists:soil_colors,id',
            'area_kanals' => 'nullable|numeric|min:0',
            'crop_id' => 'nullable|exists:crops,id',
            'variety' => 'nullable|string|max:255',
            'age_of_plants' => 'nullable|numeric|min:0',
        ];
    }
}
