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
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'digits:10', 'regex:/^[6-9]\d{9}$/', 'unique:farmers,phone'],
            'parentage' => ['required', 'string', 'max:255'],
            'aadhar_no' => ['required', 'string', 'digits:12', 'regex:/^[0-9]{12}$/', 'unique:farmers,aadhar_no'],
            'state_id' => ['required', 'exists:locations,id'],
            'district_id' => ['required', 'exists:locations,id'],
            'zone_id' => ['nullable', 'exists:locations,id'],
            'panchayat_id' => ['nullable', 'exists:locations,id'],
            'village_id' => ['nullable', 'exists:locations,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'aadhar_no.required' => 'Aadhaar number is required.',
            'aadhar_no.digits' => 'Aadhaar number must be exactly 12 digits.',
            'aadhar_no.regex' => 'Aadhaar number must contain only numbers.',
            'aadhar_no.unique' => 'This Aadhaar number is already registered to another farmer.',
            'phone.unique' => 'This phone number is already registered to another farmer.',
        ];
    }
}
