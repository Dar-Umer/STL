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

    public string $labNumberPrefix = '';
    public int $labNumberNext = 1001;
    public int $labNumberPadding = 0;
    public bool $labNumberIncludeYear = false;

    // SMTP / Email Settings
    public string $mailMailer = 'smtp';
    public string $mailHost = '';
    public int $mailPort = 587;
    public string $mailUsername = '';
    public string $mailPassword = '';
    public string $mailEncryption = 'tls';
    public string $mailFromAddress = '';
    public string $mailFromName = '';
    public string $testEmailRecipient = '';
    public ?string $testEmailStatus = null;
    public ?string $testEmailMessage = null;

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
        $this->labNumberPrefix = setting('lab_number_prefix', '');
        $this->labNumberNext = (int) setting('lab_number_next', 1001);
        $this->labNumberPadding = (int) setting('lab_number_padding', 0);
        $this->labNumberIncludeYear = (bool) setting('lab_number_include_year', false);

        // SMTP
        $this->mailMailer = setting('mail_mailer', 'smtp');
        $this->mailHost = setting('mail_host', '');
        $this->mailPort = (int) setting('mail_port', 587);
        $this->mailUsername = setting('mail_username', '');
        $this->mailPassword = setting('mail_password', '');
        $this->mailEncryption = setting('mail_encryption', 'tls');
        $this->mailFromAddress = setting('mail_from_address', '');
        $this->mailFromName = setting('mail_from_name', '');
    }

    public function getLabNumberPreviewProperty(): string
    {
        $prefix = trim($this->labNumberPrefix);
        $padding = max(0, (int) $this->labNumberPadding);
        $numStr = $padding > 0 ? str_pad((string) max(1, $this->labNumberNext), $padding, '0', STR_PAD_LEFT) : (string) max(1, $this->labNumberNext);
        $yearPart = $this->labNumberIncludeYear ? date('Y') . '-' : '';
        return "{$prefix}{$yearPart}{$numStr}";
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
        $this->validate([
            'overdueDays' => 'integer|min:1|max:365',
            'labNumberPrefix' => 'nullable|string|max:20',
            'labNumberNext' => 'required|integer|min:1',
            'labNumberPadding' => 'nullable|integer|min:0|max:10',
        ]);
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
        set_setting('lab_number_prefix', trim($this->labNumberPrefix));
        set_setting('lab_number_next', $this->labNumberNext);
        set_setting('lab_number_padding', (int) $this->labNumberPadding);
        set_setting('lab_number_include_year', $this->labNumberIncludeYear ? '1' : '0');

        // Save SMTP settings
        set_setting('mail_mailer', $this->mailMailer ?: 'smtp');
        set_setting('mail_host', trim($this->mailHost));
        set_setting('mail_port', (int) $this->mailPort);
        set_setting('mail_username', trim($this->mailUsername));
        set_setting('mail_password', $this->mailPassword);
        set_setting('mail_encryption', $this->mailEncryption);
        set_setting('mail_from_address', trim($this->mailFromAddress));
        set_setting('mail_from_name', trim($this->mailFromName));

        session()->flash('success', 'Settings saved successfully.');
        return redirect()->route('admin.settings');
    }

    public function sendTestEmail()
    {
        $this->validate([
            'testEmailRecipient' => 'required|email',
        ]);

        $this->testEmailStatus = null;
        $this->testEmailMessage = null;

        try {
            // Apply current form values dynamically
            $encryption = $this->mailEncryption === 'none' || empty($this->mailEncryption) ? null : $this->mailEncryption;
            config([
                'mail.default' => $this->mailMailer ?: 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => trim($this->mailHost),
                'mail.mailers.smtp.port' => (int) $this->mailPort,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.username' => trim($this->mailUsername),
                'mail.mailers.smtp.password' => $this->mailPassword,
                'mail.from.address' => trim($this->mailFromAddress) ?: config('mail.from.address', 'noreply@soillab.gov.in'),
                'mail.from.name' => trim($this->mailFromName) ?: ($this->appName ?: 'Soil Testing Laboratory'),
            ]);

            \Illuminate\Support\Facades\Mail::raw(
                "This is a test email sent from {$this->appName} to verify your SMTP mail configuration.\n\nDate: " . now()->toDayDateTimeString() . "\nHost: {$this->mailHost}:{$this->mailPort}\nEncryption: {$this->mailEncryption}\nFrom: {$this->mailFromAddress}\n\nIf you received this message, your email gateway is properly configured!",
                function ($message) {
                    $fromAddress = trim($this->mailFromAddress) ?: config('mail.from.address', 'noreply@soillab.gov.in');
                    $fromName = trim($this->mailFromName) ?: ($this->appName ?: 'Soil Testing Laboratory');
                    $message->to($this->testEmailRecipient)
                        ->subject("SMTP Test Message - {$fromName}");
                }
            );

            $this->testEmailStatus = 'success';
            $this->testEmailMessage = "Test email dispatched successfully to {$this->testEmailRecipient}. Check your inbox / spam folder.";
        } catch (\Throwable $e) {
            $this->testEmailStatus = 'error';
            $this->testEmailMessage = "Failed to send test email: " . $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
