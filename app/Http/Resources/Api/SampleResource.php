<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SampleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sample_code' => $this->sample_code,
            'lab_number' => $this->lab_number,
            'batch_no' => $this->dispatchBatch?->batch_no,
            'dispatch_batch_id' => $this->dispatch_batch_id,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'qr_code' => $this->qr_code_path,
            'lab_id' => $this->lab_id,
            'collected_at' => $this->collected_at,
            'received_at' => $this->received_at,
            'analyzed_at' => $this->analyzed_at,
            'reported_at' => $this->reported_at,
            'farmer' => $this->whenLoaded('farmer', fn() => new FarmerResource($this->farmer)),
            'plot' => $this->whenLoaded('plot', fn() => new PlotResource($this->plot)),
            'collector' => $this->whenLoaded('collector', fn() => [
                'id' => $this->collector?->id,
                'name' => $this->collector?->name,
            ]),
            'lab' => $this->whenLoaded('lab', fn() => [
                'id' => $this->lab?->id,
                'name' => $this->lab?->name,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
