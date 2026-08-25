<?php

namespace App\Livewire\Admin;

use App\Models\SampleRequest;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SampleRequestManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $fieldOfficials = [];

    public function mount()
    {
        $this->fieldOfficials = User::role('Field Official')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function assignOfficer($requestId, $officerId)
    {
        $request = SampleRequest::findOrFail($requestId);
        $request->update([
            'field_official_id' => $officerId ?: null,
            'status' => $officerId ? 'assigned' : 'pending',
        ]);
    }

    public function cancelRequest($requestId)
    {
        $request = SampleRequest::findOrFail($requestId);
        $request->update(['status' => 'cancelled']);
    }

    public function render()
    {
        $query = SampleRequest::with(['zone', 'fieldOfficial', 'soilSample']);

        if ($this->search) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('request_id', 'like', "%{$s}%")
                  ->orWhere('farmer_name', 'like', "%{$s}%")
                  ->orWhere('farmer_phone', 'like', "%{$s}%");
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.admin.sample-request-manager', [
            'requests' => $requests,
        ]);
    }
}
