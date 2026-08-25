<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreSampleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'farmer_id' => 'required|exists:farmers,id',
            'plot_id' => 'required|exists:plots,id',
            'qr_code' => 'required|string',
            'collected_at' => 'required|date',
        ];
    }
}
