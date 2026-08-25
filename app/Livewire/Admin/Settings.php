<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Settings extends Component
{
    use WithFileUploads;

    public string $appName;
    public string $appDescription;
    public string $reportFooter;
    public bool $maintenanceMode = false;
    public $logo;
    public ?string $logoPath = null;

    public bool $recaptchaEnabled = false;
    public string $recaptchaSiteKey = '';
    public string $recaptchaSecretKey = '';
    public int $overdueDays = 2;

    public function mount()
    {
        $this->appName = setting('app_name', config('app.name'));
        $this->appDescription = setting('app_description', '');
        $this->reportFooter = setting('report_footer', '');
        $this->maintenanceMode = setting('maintenance_mode', false);
        $this->logoPath = setting('logo_path', '');
        $this->recaptchaEnabled = setting('recaptcha_enabled', false);
        $this->recaptchaSiteKey = setting('recaptcha_site_key', '');
        $this->recaptchaSecretKey = setting('recaptcha_secret_key', '');
        $this->overdueDays = (int) setting('overdue_days', 2);
    }

    public function updatedLogo()
    {
        $this->validate(['logo' => 'image|mimes:png,jpg,jpeg,svg|max:1024']);
    }

    public function removeLogo()
    {
        if ($this->logoPath) {
            Storage::disk('public')->delete($this->logoPath);
        }
        $this->logoPath = '';
        $this->logo = null;
        forget_setting('logo_path');
        \App\Models\Setting::where('key', 'logo_path')->delete();
    }

    public function save()
    {
        $this->validate(['overdueDays' => 'integer|min:1|max:365']);
        if ($this->logo) {
            $this->validate(['logo' => 'image|mimes:png,jpg,jpeg,svg|max:1024']);
            $path = $this->logo->store('logos', 'public');
            if ($this->logoPath) {
                Storage::disk('public')->delete($this->logoPath);
            }
            $this->logoPath = $path;
        }

        set_setting('app_name', $this->appName);
        set_setting('app_description', $this->appDescription);
        set_setting('report_footer', $this->reportFooter);
        set_setting('maintenance_mode', $this->maintenanceMode);
        set_setting('logo_path', $this->logoPath);
        set_setting('recaptcha_enabled', $this->recaptchaEnabled);
        set_setting('recaptcha_site_key', $this->recaptchaSiteKey);
        set_setting('recaptcha_secret_key', $this->recaptchaSecretKey);
        set_setting('overdue_days', $this->overdueDays);

        session()->flash('success', 'Settings saved successfully.');
        return redirect()->route('admin.settings');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
