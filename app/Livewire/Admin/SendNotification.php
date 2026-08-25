<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Location;
use App\Models\User;
use App\Services\NotificationService;

class SendNotification extends Component
{
    public ?int $zoneId = null;
    public ?int $userId = null;
    public string $title = '';
    public string $body = '';

    public function getZonesProperty()
    {
        return Location::where('type', 'zone')->orderBy('name')->get();
    }

    public function getUsersProperty()
    {
        $query = User::role('Field Official')->where('is_active', true)->orderBy('name');
        if ($this->zoneId) {
            $query->where('zone_id', $this->zoneId);
        }
        return $query->get();
    }

    public function updatedZoneId()
    {
        $this->userId = null;
    }

    public function send()
    {
        $this->validate([
            'zoneId' => 'nullable|exists:locations,id',
            'userId' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
        ]);

        NotificationService::send(
            $this->userId,
            'manual',
            $this->title,
            $this->body,
        );

        session()->flash('success', 'Notification sent successfully.');

        $this->reset(['zoneId', 'userId', 'title', 'body']);
    }

    public function render()
    {
        return view('livewire.admin.send-notification', [
            'zones' => $this->zones,
            'users' => $this->users,
        ])->layout('layouts.app');
    }
}
