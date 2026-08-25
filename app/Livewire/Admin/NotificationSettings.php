<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Crypt;

class NotificationSettings extends Component
{
    public array $triggers = [];
    public string $serviceAccountJson = '';
    public ?string $storedServiceAccount = null;
    public ?string $storedProjectId = null;
    public ?string $storedEmail = null;

    private array $defaults = [
        'sample_reported' => [
            'label' => 'Sample Reported',
            'desc' => 'Sent to the field official who collected the sample when testing is complete.',
            'message' => 'Sample {sample_code} has been tested and the report is ready.',
            'vars' => '{sample_code}, {farmer_name}',
        ],
        'new_announcement' => [
            'label' => 'New Announcement',
            'desc' => 'Sent to all field officials when a new announcement is published.',
            'message' => 'New announcement: {announcement}',
            'vars' => '{announcement}',
        ],
        'sample_request' => [
            'label' => 'Sample Request Submitted',
            'desc' => 'Sent to field officials in the zone when someone requests sample collection.',
            'message' => '{farmer_name} ({farmer_phone}) requested sample collection in your zone.',
            'vars' => '{farmer_name}, {farmer_phone}',
        ],
        'target_allotment' => [
            'label' => 'New Target Allotment',
            'desc' => 'Sent to field officials in the zone when a new target is set.',
            'message' => 'New target set for {zone_name}: {target_count} samples by {end_date}.',
            'vars' => '{zone_name}, {target_count}, {start_date}, {end_date}',
        ],
    ];

    public function mount()
    {
        $stored = json_decode(setting('notification_triggers', '{}'), true) ?: [];

        foreach ($this->defaults as $key => $cfg) {
            $this->triggers[$key] = [
                'enabled' => $stored[$key]['enabled'] ?? ($key === 'sample_reported'),
                'message' => $stored[$key]['message'] ?? $cfg['message'],
            ];
        }

        $raw = setting('notification_credentials', '');
        $creds = $raw ? json_decode($raw, true) : [];

        $encrypted = $creds['service_account_json'] ?? null;
        if ($encrypted && str_starts_with($encrypted, 'encrypted:')) {
            $this->storedServiceAccount = '••••••••';
            try {
                $decrypted = Crypt::decryptString(substr($encrypted, 10));
                $sa = json_decode($decrypted, true);
                $this->storedProjectId = $sa['project_id'] ?? null;
                $this->storedEmail = $sa['client_email'] ?? null;
            } catch (\Exception) {
                $this->storedProjectId = null;
                $this->storedEmail = null;
            }
        }
    }

    public function getDefaultsProperty()
    {
        return $this->defaults;
    }

    public function save()
    {
        $this->validate([
            'triggers.*.enabled' => 'boolean',
            'triggers.*.message' => 'required|string|max:1000',
        ]);

        set_setting('notification_triggers', json_encode($this->triggers));

        $creds = [];
        if ($this->serviceAccountJson) {
            $sa = json_decode($this->serviceAccountJson, true);
            if (!$sa || empty($sa['client_email']) || empty($sa['private_key']) || empty($sa['project_id'])) {
                session()->flash('error', 'Invalid service account JSON. Must contain client_email, private_key, and project_id.');
                return;
            }
            $creds['service_account_json'] = 'encrypted:' . Crypt::encryptString($this->serviceAccountJson);
        } elseif ($this->storedServiceAccount) {
            $raw = setting('notification_credentials', '');
            $existing = $raw ? json_decode($raw, true) : [];
            $creds['service_account_json'] = $existing['service_account_json'] ?? '';
        }

        set_setting('notification_credentials', json_encode($creds));

        session()->flash('success', 'Notification settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.notification-settings', [
            'defaults' => $this->defaults,
        ])->layout('layouts.app');
    }
}
