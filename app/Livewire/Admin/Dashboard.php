<?php

namespace App\Livewire\Admin;

use App\Models\Farmer;
use App\Models\Lab;
use App\Models\SoilSample;
use App\Models\Target;
use App\Models\User;
use App\Models\Location;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    use AuthorizesRequests;

    public int $totalLabs;
    public int $totalUsers;
    public int $totalFarmers;
    public int $totalSamples;
    public int $samplesCollected;
    public int $samplesReceived;
    public int $samplesTesting;
    public int $samplesReported;
    public int $queueCount;
    public int $samplesThisMonth;
    public int $samplesLastMonth;
    public array $monthlyLabels = [];
    public array $monthlyCounts = [];
    public $recentSamples;
    public $zoneTargets;
    public int $totalTargetCount = 0;
    public int $totalAchievedCount = 0;

    public int $adminCount = 0;
    public int $labManagerCount = 0;
    public int $fieldOfficialCount = 0;
    public int $labTechnicianCount = 0;
    public int $farmerCount = 0;

    public $labStats;
    public $zoneStats;

    public function mount()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $this->totalLabs = Lab::count();
        $this->totalUsers = User::count();
        $this->totalFarmers = Farmer::count();
        $this->totalSamples = SoilSample::count();

        $this->adminCount = User::role('Admin')->count();
        $this->labManagerCount = User::role('Lab Manager')->count();
        $this->fieldOfficialCount = User::role('Field Official')->count();
        $this->labTechnicianCount = User::role('Lab Technician')->count();
        $this->farmerCount = User::role('Farmer')->count();

        $this->samplesCollected = SoilSample::where('status', 'collected')->count();
        $this->samplesReceived = SoilSample::where('status', 'received')->count();
        $this->samplesTesting = SoilSample::where('status', 'testing')->count();
        $this->samplesReported = SoilSample::where('status', 'reported')->count();

        $this->queueCount = SoilSample::whereIn('status', ['collected', 'received'])->count();

        $this->samplesThisMonth = SoilSample::whereMonth('created_at', now()->month)->count();
        $this->samplesLastMonth = SoilSample::whereMonth('created_at', now()->subMonth()->month)->count();

        $this->monthlyLabels = [];
        $this->monthlyCounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $this->monthlyLabels[] = $month->format('M Y');
            $this->monthlyCounts[] = SoilSample::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)->count();
        }

        $this->labStats = Lab::withCount([
            'soilSamples',
            'soilSamples as collected_count' => fn($q) => $q->where('status', 'collected'),
            'soilSamples as received_count' => fn($q) => $q->where('status', 'received'),
            'soilSamples as testing_count' => fn($q) => $q->where('status', 'testing'),
            'soilSamples as reported_count' => fn($q) => $q->where('status', 'reported'),
            'farmers',
            'users',
        ])->orderBy('soil_samples_count', 'desc')->get();

        $this->loadZoneStats();

        $this->zoneTargets = Target::with('zone')->active()->orderBy('zone_id')->get();
        $this->totalTargetCount = $this->zoneTargets->sum('target_count');
        $this->totalAchievedCount = $this->zoneTargets->sum(fn($t) => $t->achievedCount());

        $this->recentSamples = SoilSample::with(['farmer.zone', 'plot', 'lab'])
            ->latest()->take(10)->get();
    }

    protected function loadZoneStats()
    {
        $zones = Location::where('type', 'zone')
            ->withCount(['fieldOfficials'])
            ->get()
            ->keyBy('id');

        $stats = SoilSample::selectRaw('farmers.zone_id, soil_samples.status, COUNT(*) as total')
            ->join('farmers', 'soil_samples.farmer_id', '=', 'farmers.id')
            ->whereIn('farmers.zone_id', $zones->keys())
            ->groupBy('farmers.zone_id', 'soil_samples.status')
            ->get()
            ->groupBy('zone_id');

        $this->zoneStats = $zones->map(function ($zone) use ($stats) {
            $zoneStats = $stats->get($zone->id, collect());
            $zone->samples_count = $zoneStats->sum('total');
            $zone->collected_count = $zoneStats->where('status', 'collected')->sum('total');
            $zone->received_count = $zoneStats->where('status', 'received')->sum('total');
            $zone->testing_count = $zoneStats->where('status', 'testing')->sum('total');
            $zone->reported_count = $zoneStats->where('status', 'reported')->sum('total');
            return $zone;
        })->sortByDesc('samples_count')->values();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('layouts.app');
    }
}
