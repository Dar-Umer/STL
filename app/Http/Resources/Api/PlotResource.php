<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'farmer_id' => $this->farmer_id,
            'plot_name' => $this->plot_name,
            'khasra_no' => $this->khasra_no,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'area_kanals' => $this->area_kanals,
            'variety' => $this->variety,
            'age_of_plants' => $this->age_of_plants,
            'soil_type' => $this->whenLoaded('soilType', fn() => [
                'id' => $this->soilType?->id,
                'name' => $this->soilType?->name,
            ]),
            'soil_color' => $this->whenLoaded('soilColor', fn() => [
                'id' => $this->soilColor?->id,
                'name' => $this->soilColor?->name,
            ]),
            'crop' => $this->whenLoaded('crop', fn() => [
                'id' => $this->crop?->id,
                'name' => $this->crop?->name,
            ]),
            'zone' => $this->whenLoaded('zone', fn() => new LocationResource($this->zone)),
            'village' => $this->whenLoaded('village', fn() => new LocationResource($this->village)),
        ];
    }
}
