<?php

namespace App\Livewire\Admin;

use App\Models\Lab;
use App\Models\QrCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class QrCodeGenerator extends Component
{
    use AuthorizesRequests, WithPagination;

    public $prefix = 'A';
    public $count = 20;
    public $filter = '';
    public $selectedIds = [];
    public $selectAll = false;
    public $labId = '';
    public $zoneId = '';

    public function mount()
    {
        $user = auth()->user();
        if ($user->hasRole('Lab Manager')) {
            $this->labId = $user->lab_id;
            $this->zoneId = $user->zone_id;
            $this->prefix = $user->zone?->code ?? 'N/A';
        } elseif (!$user->hasRole('Admin')) {
            $this->prefix = $user->zone?->code ?? 'N/A';
        }
    }

    public function updatedZoneId($value)
    {
        if ($value) {
            $zone = \App\Models\Location::find($value);
            $this->prefix = $zone?->code ?? 'N/A';
        }
    }

    protected function rules()
    {
        return [
            'prefix' => 'required|alpha|max:5',
            'count' => 'required|integer|min:1|max:500',
        ];
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $q = QrCode::orderBy('prefix')->orderBy('sequence');
            $this->applyRoleScope($q);
            if ($this->filter) {
                $q->where('prefix', $this->filter);
            }
            $this->selectedIds = $q->pluck('id')->toArray();
        } else {
            $this->selectedIds = [];
        }
    }

    protected function applyRoleScope($query)
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            if ($this->labId) {
                $query->where('lab_id', $this->labId);
            }
        } elseif ($user->hasRole('Lab Manager')) {
            if ($this->labId) {
                $query->where('lab_id', $this->labId);
            }
        } else {
            $query->where('created_by', $user->id);
        }

        return $query;
    }

    public function printSelected()
    {
        if (empty($this->selectedIds)) return;

        $ids = implode(',', $this->selectedIds);
        $routeName = auth()->user()->hasRole('Admin') ? 'admin.qr-codes.print-selected' : 'field.qr-codes.print-selected';
        return redirect()->route($routeName, ['ids' => $ids]);
    }

    public function deleteSelected()
    {
        $this->authorizeAdmin();
        if (empty($this->selectedIds)) return;

        $count = count($this->selectedIds);
        QrCode::whereIn('id', $this->selectedIds)->delete();
        $this->selectedIds = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} QR codes deleted successfully.");
    }

    protected function authorizeAdmin()
    {
        if (!auth()->user()->hasRole('Admin')) {
            abort(403);
        }
    }

    public function render()
    {
        $user = auth()->user();

        $query = QrCode::orderBy('prefix')->orderBy('sequence');
        $this->applyRoleScope($query);
        if ($this->filter) {
            $query->where('prefix', $this->filter);
        }
        $codes = $query->paginate(24);

        $prefixQuery = QrCode::select('prefix')->distinct();
        $this->applyRoleScope($prefixQuery);
        $prefixes = $prefixQuery->pluck('prefix');

        $labs = ($user->hasRole('Admin') || $user->hasRole('Lab Manager')) ? Lab::orderBy('name')->get() : collect();

        $zones = ($user->hasRole('Admin') || $user->hasRole('Lab Manager'))
            ? \App\Models\Location::where('type', 'zone')->orderBy('name')->get()
            : collect();

        return view('livewire.admin.qr-code-generator', [
            'codes' => $codes,
            'prefixes' => $prefixes,
            'labs' => $labs,
            'zones' => $zones,
        ])->layout('layouts.app');
    }

    public function generate()
    {
        $this->validate();

        $user = auth()->user();

        if (!$user->hasRole('Admin')) {
            if (!$user->lab_id) {
                session()->flash('error', 'You are not assigned to any lab.');
                return;
            }
            if (!$user->hasRole('Lab Manager') && $this->prefix === 'N/A') {
                session()->flash('error', 'Your zone does not have a code assigned. Contact admin to set a zone code.');
                return;
            }
        } elseif (!$this->labId) {
            session()->flash('error', 'Please select a lab.');
            return;
        }

        $labId = $user->hasRole('Admin')
            ? (int) $this->labId
            : $user->lab_id;

        $maxSeq = QrCode::where('prefix', $this->prefix)
            ->where('lab_id', $labId)
            ->max('sequence') ?? 0;
        $startFrom = $maxSeq + 1;

        $codes = [];
        for ($i = 0; $i < $this->count; $i++) {
            $seq = $startFrom + $i;
            $codes[] = [
                'code' => $this->prefix . $seq,
                'prefix' => $this->prefix,
                'sequence' => $seq,
                'lab_id' => $labId,
                'created_by' => $user->id,
            ];
        }
        QrCode::insert($codes);
        $endSeq = $startFrom + $this->count - 1;
        $routeName = $user->hasRole('Admin') ? 'admin.qr-codes.print' : 'field.qr-codes.print';
        return redirect()->route($routeName, [
            'prefix' => $this->prefix,
            'start' => $startFrom,
            'end' => $endSeq,
        ]);
    }
}
