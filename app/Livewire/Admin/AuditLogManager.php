<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AuditLogManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $eventFilter = '';
    public string $typeFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public ?int $expandedId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'eventFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingEventFilter(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function toggleExpand(int $id): void
    {
        $this->expandedId = $this->expandedId === $id ? null : $id;
    }

    public function clearLogs(): void
    {
        AuditLog::query()->delete();
        session()->flash('success', 'All audit logs have been cleared.');
    }

    public function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $logs = $this->buildQuery()->orderBy('created_at', 'desc')->limit(10000)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit-log-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Time', 'User', 'Event', 'Entity', 'Old Values', 'New Values', 'IP Address', 'URL']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at?->format('Y-m-d H:i:s'),
                    $log->user?->name ?? 'System',
                    $log->event,
                    $log->auditable_label,
                    $log->old_values ? json_encode($log->old_values) : '',
                    $log->new_values ? json_encode($log->new_values) : '',
                    $log->ip_address,
                    $log->url,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function buildQuery()
    {
        $query = AuditLog::with('user');

        if ($this->search) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('auditable_type', 'like', "%{$s}%")
                    ->orWhere('ip_address', 'like', "%{$s}%")
                    ->orWhereHas('user', function ($q2) use ($s) {
                        $q2->where('name', 'like', "%{$s}%");
                    });
            });
        }

        if ($this->eventFilter) {
            $query->where('event', $this->eventFilter);
        }

        if ($this->typeFilter) {
            $query->where('auditable_type', $this->typeFilter);
        }

        if ($this->dateFrom) {
            $query->where('created_at', '>=', Carbon::parse($this->dateFrom)->startOfDay());
        }

        if ($this->dateTo) {
            $query->where('created_at', '<=', Carbon::parse($this->dateTo)->endOfDay());
        }

        return $query;
    }

    public function render()
    {
        $logs = $this->buildQuery()->orderBy('created_at', 'desc')->paginate(25);

        $users = User::orderBy('name')->get();

        $types = AuditLog::selectRaw('DISTINCT auditable_type')
            ->orderBy('auditable_type')
            ->pluck('auditable_type');

        $eventCounts = AuditLog::selectRaw('event, COUNT(*) as count')
            ->groupBy('event')
            ->pluck('count', 'event');

        return view('livewire.admin.audit-log-manager', [
            'logs' => $logs,
            'users' => $users,
            'types' => $types,
            'eventCounts' => $eventCounts,
        ])->layout('layouts.app');
    }
}
