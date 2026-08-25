<?php

namespace App\Livewire\Admin;

use App\Models\Location;
use App\Models\Target;
use App\Services\NotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ZoneTargetManager extends Component
{
    use AuthorizesRequests, WithPagination;

    public $showForm = false;
    public $targetId;
    public $zone_id, $target_count, $start_date, $end_date;
    public $search = '';

    protected function rules()
    {
        return [
            'zone_id' => 'required|exists:locations,id',
            'target_count' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function render()
    {
        $this->authorize('manage-targets');
        $zones = Location::where('type', 'zone')->orderBy('name')->get();

        $targets = Target::with('zone', 'creator')
            ->when($this->search, function ($q) {
                $q->whereHas('zone', function ($q2) {
                    $q2->where('name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.zone-target-manager', [
            'targets' => $targets,
            'zones' => $zones,
        ])->layout('layouts.app');
    }

    public function create()
    {
        $this->reset(['targetId', 'zone_id', 'target_count', 'start_date', 'end_date']);
        $this->showForm = true;
    }

    public function edit($id)
    {
        $target = Target::findOrFail($id);
        $this->targetId = $target->id;
        $this->zone_id = $target->zone_id;
        $this->target_count = $target->target_count;
        $this->start_date = $target->start_date->format('Y-m-d');
        $this->end_date = $target->end_date->format('Y-m-d');
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $overlap = Target::where('zone_id', $this->zone_id)
            ->where(function ($q) {
                $q->whereBetween('start_date', [$this->start_date, $this->end_date])
                  ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                  ->orWhere(function ($q2) {
                      $q2->where('start_date', '<=', $this->start_date)
                         ->where('end_date', '>=', $this->end_date);
                  });
            });
        if ($this->targetId) {
            $overlap->where('id', '!=', $this->targetId);
        }
        if ($overlap->exists()) {
            $this->addError('start_date', 'A target already exists for this zone in the given date range.');
            return;
        }

        $data = [
            'zone_id' => $this->zone_id,
            'target_count' => $this->target_count,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];

        $zone = Location::find($this->zone_id);

        if ($this->targetId) {
            $target = Target::findOrFail($this->targetId);
            $target->update($data);
            session()->flash('success', 'Target updated successfully.');
        } else {
            $data['created_by'] = auth()->id();
            Target::create($data);
            session()->flash('success', 'Target created successfully.');
        }

        $triggers = json_decode(setting('notification_triggers', '{}'), true);
        if ($triggers['target_allotment']['enabled'] ?? false) {
            $body = resolve_notification_message(
                $triggers['target_allotment']['message'] ?? 'New target set for {zone_name}: {target_count} samples by {end_date}.',
                [
                    'zone_name' => $zone?->name ?? 'Unknown',
                    'target_count' => (string) $this->target_count,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                ]
            );
            NotificationService::sendToZone($this->zone_id, 'target_allotment', 'New Target Allotment', $body, [
                'zone_id' => (string) $this->zone_id,
                'target_count' => (string) $this->target_count,
            ]);
        }

        $this->reset();
    }

    public function delete($id)
    {
        Target::findOrFail($id)->delete();
        session()->flash('success', 'Target deleted successfully.');
    }

    public function cancel()
    {
        $this->reset();
    }
}
