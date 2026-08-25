<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'parentage' => $this->parentage,
            'aadhar_no' => $this->aadhar_no,
            'state' => $this->whenLoaded('state', fn() => new LocationResource($this->state)),
            'district' => $this->whenLoaded('district', fn() => new LocationResource($this->district)),
            'zone' => $this->whenLoaded('zone', fn() => new LocationResource($this->zone)),
            'panchayat' => $this->whenLoaded('panchayat', fn() => new LocationResource($this->panchayat)),
            'village' => $this->whenLoaded('village', fn() => new LocationResource($this->village)),
            'plot_count' => $this->whenLoaded('plots', fn() => $this->plots->count()),
            'created_at' => $this->created_at,
        ];
    }
}
