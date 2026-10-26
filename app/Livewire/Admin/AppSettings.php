<?php

namespace App\Livewire\Admin;

use App\Services\AuditLogger;
use App\Services\NotificationService;
use Livewire\Component;

class AppSettings extends Component
{
    public string $appVersion;
    public string $appUpdateUrl;
    public bool $forceUpdate = false;
    public string $collectionGuide;
    public array $appAnnouncements = [];
    public string $workStartTime = '09:00';
    public string $workEndTime = '17:00';
    public array $workingDays = [1, 2, 3, 4, 5];
    public bool $workingHoursEnabled = false;

    // Covering Letter & Dispatch Template Defaults
    public string $coveringLetterHeader;
    public string $coveringLetterAddressedTo;
    public string $coveringLetterSubject;
    public string $coveringLetterBody;
    public string $coveringLetterHdoTitle;

    public function mount()
    {
        $this->appVersion = setting('app_version', '1.0.0');
        $this->appUpdateUrl = setting('app_update_url', '');
        $this->forceUpdate = setting('force_update', false);
        $this->collectionGuide = setting('front.collection_guide', '');
        $this->appAnnouncements = json_decode(setting('front.app_announcements', '[]'), true) ?: [];
        $this->workStartTime = setting('app.work_start_time', '09:00');
        $this->workEndTime = setting('app.work_end_time', '17:00');
        $this->workingDays = json_decode(setting('app.working_days', '[1,2,3,4,5]'), true) ?: [1, 2, 3, 4, 5];
        $this->workingHoursEnabled = (bool) setting('app.working_hours_enabled', false);

        $defaultBodyText = "Sir / Madam,\n\n" .
            "Enclosed please find physical soil samples duly collected, processed, barcode-tagged, and packaged by the field extension team of {ZONE} under the Annual Soil Testing & Soil Health Management Programme.\n\n" .
            "The samples have been collected as per standard composite sampling protocols from various orchardists / farmers across the Panchayats and Villages of {ZONE}. A comprehensive descriptive schedule of the dispatched samples, farmer profiles, parentage, residence, and QR identification codes is cataloged in the schedule below.\n\n" .
            "You are kindly requested to accept the physical samples, perform the required chemical / nutrient parameter analysis (including pH, EC, Organic Carbon, Macro and Micro nutrients), and expedite the test reports for issuing Soil Health Cards to the concerned orchardists.";

        $this->coveringLetterHeader = setting('covering_letter.header_template', 'OFFICE OF THE HORTICULTURE DEVELOPMENT OFFICER, {ZONE}');
        $this->coveringLetterAddressedTo = setting('covering_letter.addressed_to_template', "The Incharge / Testing Officer,\n{LAB},\n{DISTRICT}, Jammu & Kashmir.");
        $this->coveringLetterSubject = setting('covering_letter.subject_template', 'Transmission of physical soil samples collected from {ZONE} for laboratory testing and issuance of Soil Health Cards.');
        $this->coveringLetterBody = setting('covering_letter.body_template', $defaultBodyText);
        $this->coveringLetterHdoTitle = setting('covering_letter.hdo_title_template', "Horticulture Development Officer (HDO)\n{ZONE}");
    }

    public function addAppAnnouncement()
    {
        $this->appAnnouncements[] = '';
    }

    public function removeAppAnnouncement($index)
    {
        unset($this->appAnnouncements[$index]);
        $this->appAnnouncements = array_values($this->appAnnouncements);
    }

    public function toggleWorkingDay($day)
    {
        $key = array_search($day, $this->workingDays);
        if ($key !== false) {
            unset($this->workingDays[$key]);
            $this->workingDays = array_values($this->workingDays);
        } else {
            $this->workingDays[] = $day;
            sort($this->workingDays);
        }
    }

    public function save()
    {
        $old = [
            'app_version' => setting('app_version', '1.0.0'),
            'app_update_url' => setting('app_update_url', ''),
            'force_update' => setting('force_update', false),
            'collection_guide' => setting('front.collection_guide', ''),
            'work_start_time' => setting('app.work_start_time', '09:00'),
            'work_end_time' => setting('app.work_end_time', '17:00'),
            'working_days' => setting('app.working_days', '[1,2,3,4,5]'),
            'working_hours_enabled' => setting('app.working_hours_enabled', false),
        ];

        $new = [
            'app_version' => $this->appVersion,
            'app_update_url' => $this->appUpdateUrl,
            'force_update' => $this->forceUpdate,
            'collection_guide' => $this->collectionGuide,
            'work_start_time' => $this->workStartTime,
            'work_end_time' => $this->workEndTime,
            'working_days' => json_encode($this->workingDays),
            'working_hours_enabled' => $this->workingHoursEnabled ? '1' : '0',
        ];

        $changed = [];
        foreach ($new as $key => $value) {
            if ((string)($old[$key] ?? '') !== (string)$value) {
                $changed[$key] = ['old' => $old[$key] ?? '', 'new' => $value];
            }
        }

        set_setting('app_version', $this->appVersion);
        set_setting('app_update_url', $this->appUpdateUrl);
        set_setting('force_update', $this->forceUpdate);
        set_setting('front.collection_guide', $this->collectionGuide);
        $announcements = array_values(array_filter($this->appAnnouncements));
        set_setting('front.app_announcements', json_encode($announcements));
        set_setting('app.work_start_time', $this->workStartTime);
        set_setting('app.work_end_time', $this->workEndTime);
        set_setting('app.working_days', json_encode($this->workingDays));
        set_setting('app.working_hours_enabled', $this->workingHoursEnabled ? '1' : '0');

        set_setting('covering_letter.header_template', $this->coveringLetterHeader);
        set_setting('covering_letter.addressed_to_template', $this->coveringLetterAddressedTo);
        set_setting('covering_letter.subject_template', $this->coveringLetterSubject);
        set_setting('covering_letter.body_template', $this->coveringLetterBody);
        set_setting('covering_letter.hdo_title_template', $this->coveringLetterHdoTitle);

        $triggers = json_decode(setting('notification_triggers', '{}'), true);
        if (!empty($announcements) && ($triggers['new_announcement']['enabled'] ?? false)) {
            $latest = end($announcements);
            $body = resolve_notification_message(
                $triggers['new_announcement']['message'] ?? 'New announcement: {announcement}',
                ['announcement' => $latest]
            );
            NotificationService::sendToRole('Field Official', 'announcement', 'New Announcement', $body);
        }

        if (!empty($changed)) {
            AuditLogger::logSettingsChange(
                array_map(fn($v) => $v['old'], $changed),
                array_map(fn($v) => $v['new'], $changed)
            );
        }

        session()->flash('success', 'App settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.app-settings')->layout('layouts.app');
    }
}
