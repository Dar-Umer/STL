<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'phone' => 'required|string|digits:10|regex:/^[6789]/unique:farmers,phone',
            'parentage' => 'required|string|max:255',
            'aadhar_no' => 'required|string|digits:12',
            'state_id' => 'required|exists:locations,id',
            'district_id' => 'required|exists:locations,id',
            'zone_id' => 'nullable|exists:locations,id',
            'panchayat_id' => 'nullable|exists:locations,id',
            'village_id' => 'nullable|exists:locations,id',
        ];
    }
}
