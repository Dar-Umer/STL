<?php

namespace App\Livewire\Public;

use App\Models\Location;
use App\Models\SampleRequest;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class SampleRequestForm extends Component
{
    public $farmerName = '';
    public $farmerPhone = '';
    public $aadharNo = '';
    public $farmerAddress = '';

    public $stateId = '';
    public $districtId = '';
    public $zoneId = '';
    public $panchayatId = '';
    public $villageId = '';

    public $submitted = false;
    public $requestId = '';
    public $error = '';

    public $honeypot = '';
    public $mountedAt;

    public $recaptchaToken = '';
    public $recaptchaEnabled = true;

    public function mount()
    {
        $this->mountedAt = now()->timestamp;
        $this->recaptchaEnabled = (bool) setting('recaptcha_enabled', true);
        session()->put('sample_form_token', Str::random(32));
        session()->put('sample_form_expires', now()->addMinutes(30)->timestamp);
    }

    public function getRecaptchaSiteKeyProperty()
    {
        return setting('recaptcha_site_key') ?: '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
    }

    public function getRecaptchaSecretKeyProperty()
    {
        return setting('recaptcha_secret_key') ?: '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
    }

    public function getStatesProperty()
    {
        return Location::where('type', 'state')->orderBy('name')->get();
    }

    public function getDistrictsProperty()
    {
        return $this->stateId ? Location::where('type', 'district')->where('parent_id', $this->stateId)->orderBy('name')->get() : collect();
    }

    public function getZonesProperty()
    {
        return $this->districtId ? Location::where('type', 'zone')->where('parent_id', $this->districtId)->orderBy('name')->get() : collect();
    }

    public function getPanchayatsProperty()
    {
        return $this->zoneId ? Location::where('type', 'panchayat')->where('parent_id', $this->zoneId)->orderBy('name')->get() : collect();
    }

    public function getVillagesProperty()
    {
        return $this->panchayatId ? Location::where('type', 'village')->where('parent_id', $this->panchayatId)->orderBy('name')->get() : collect();
    }

    public function updatedStateId($value)
    {
        $this->districtId = $this->zoneId = $this->panchayatId = $this->villageId = '';
    }

    public function updatedDistrictId($value)
    {
        $this->zoneId = $this->panchayatId = $this->villageId = '';
    }

    public function updatedZoneId($value)
    {
        $this->panchayatId = $this->villageId = '';
    }

    public function updatedPanchayatId($value)
    {
        $this->villageId = '';
    }

    public function rules()
    {
        return [
            'farmerName' => ['required', 'string', 'min:2', 'max:255'],
            'farmerPhone' => ['required', 'string', 'digits:10', 'regex:/^[6-9]\d{9}$/'],
            'aadharNo' => ['required', 'string', 'digits:12', 'regex:/^[0-9]{12}$/'],
            'stateId' => ['required', 'exists:locations,id'],
            'districtId' => ['required', 'exists:locations,id'],
            'zoneId' => ['required', 'exists:locations,id'],
            'panchayatId' => ['required', 'exists:locations,id'],
            'villageId' => ['required', 'exists:locations,id'],
            'farmerAddress' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'farmerName.required' => 'Please enter your full name.',
            'farmerName.min' => 'Full name must be at least 2 characters.',
            'farmerPhone.required' => 'Please enter your mobile number.',
            'farmerPhone.digits' => 'Mobile number must be exactly 10 digits.',
            'farmerPhone.regex' => 'Mobile number must start with 6, 7, 8, or 9.',
            'aadharNo.required' => 'Please enter your Aadhaar number.',
            'aadharNo.digits' => 'Aadhaar number must be exactly 12 digits.',
            'aadharNo.regex' => 'Aadhaar number must contain only numbers.',
            'stateId.required' => 'Please select your state.',
            'stateId.exists' => 'Selected state is invalid.',
            'districtId.required' => 'Please select your district.',
            'districtId.exists' => 'Selected district is invalid.',
            'zoneId.required' => 'Please select your zone.',
            'zoneId.exists' => 'Selected zone is invalid.',
            'panchayatId.required' => 'Please select your panchayat.',
            'panchayatId.exists' => 'Selected panchayat is invalid.',
            'villageId.required' => 'Please select your village.',
            'villageId.exists' => 'Selected village is invalid.',
            'farmerAddress.required' => 'Please enter your landmark or local address.',
        ];
    }

    public function sanitizeInput($value)
    {
        return strip_tags(trim($value));
    }

    public function hasUrls($value)
    {
        return preg_match('/https?:\/\/|www\./i', $value);
    }

    public function submit()
    {
        $this->error = '';

        // ---- Honeypot ----
        if ($this->honeypot !== '') {
            $this->submitted = true;
            $this->requestId = 'REQ-000000';
            return;
        }

        // ---- Form expiration ----
        $token = session()->get('sample_form_token');
        $expires = session()->get('sample_form_expires');
        if (!$token || !$expires || now()->timestamp > $expires) {
            $this->error = 'Form session expired. Please reload the page.';
            return;
        }

        // ---- Submit speed check (< 3s = likely bot) ----
        if ($this->mountedAt && (now()->timestamp - $this->mountedAt) < 3) {
            $this->error = 'Please take your time filling the form.';
            return;
        }

        // ---- Rate limit: 3 per hour per IP ----
        $rateKey = 'sample-request|' . request()->ip();
        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            $seconds = RateLimiter::availableIn($rateKey);
            $this->error = 'Too many requests. Please try again in ' . ceil($seconds / 60) . ' minute(s).';
            return;
        }

        // ---- Input sanitization ----
        $this->farmerName = $this->sanitizeInput($this->farmerName);
        $this->farmerPhone = $this->sanitizeInput($this->farmerPhone);
        $this->aadharNo = preg_replace('/\D/', '', $this->aadharNo);
        $this->farmerAddress = $this->sanitizeInput($this->farmerAddress);

        // ---- Block URLs in name/address ----
        if ($this->hasUrls($this->farmerName) || $this->hasUrls($this->farmerAddress)) {
            $this->error = 'Remove URLs from your input.';
            return;
        }

        // ---- Validation ----
        $this->validate();

        // ---- reCAPTCHA Verification ----
        if ($this->recaptchaEnabled) {
            if (empty($this->recaptchaToken)) {
                $this->addError('recaptchaToken', 'Please complete the reCAPTCHA verification.');
                return;
            }

            try {
                $verifyResponse = Http::asForm()->timeout(10)->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $this->recaptchaSecretKey,
                    'response' => $this->recaptchaToken,
                    'remoteip' => request()->ip(),
                ]);

                if (!($verifyResponse->json()['success'] ?? false)) {
                    $this->addError('recaptchaToken', 'reCAPTCHA verification failed. Please try again.');
                    $this->recaptchaToken = '';
                    $this->dispatch('reset-recaptcha');
                    return;
                }
            } catch (\Exception $e) {
                report($e);
            }
        }

        // ---- Phone cooldown + duplicate detection ----
        $existing = SampleRequest::where('farmer_phone', $this->farmerPhone)
            ->where('status', 'pending')
            ->exists();
        if ($existing) {
            $this->error = 'A pending request already exists for this phone number.';
            return;
        }

        $existingAadhar = SampleRequest::where('aadhar_no', $this->aadharNo)
            ->where('status', 'pending')
            ->exists();
        if ($existingAadhar) {
            $this->error = 'A pending request already exists for this Aadhaar number.';
            return;
        }

        $duplicate = SampleRequest::where('farmer_name', $this->farmerName)
            ->where('farmer_phone', $this->farmerPhone)
            ->where('zone_id', $this->zoneId)
            ->where('status', 'pending')
            ->exists();
        if ($duplicate) {
            $this->error = 'A similar request was already submitted.';
            return;
        }

        // ---- Zone cap (max 50 pending per zone) ----
        $pendingCount = SampleRequest::where('zone_id', $this->zoneId)
            ->whereIn('status', ['pending', 'assigned'])
            ->count();
        if ($pendingCount >= 50) {
            $this->error = 'This zone has reached the maximum number of pending requests. Please try again later.';
            return;
        }

        // ---- Build address from selected locations ----
        $parts = [];
        if ($this->villageId) $parts[] = Location::find($this->villageId)?->name;
        if ($this->panchayatId) $parts[] = Location::find($this->panchayatId)?->name;
        if ($this->zoneId) $parts[] = Location::find($this->zoneId)?->name;
        if ($this->districtId) $parts[] = Location::find($this->districtId)?->name;
        if ($this->stateId) $parts[] = Location::find($this->stateId)?->name;
        $address = implode(', ', array_filter($parts));
        if ($this->farmerAddress) {
            $address = $this->farmerAddress . ', ' . $address;
        }

        $request = SampleRequest::create([
            'request_id' => SampleRequest::generateRequestId(),
            'farmer_name' => $this->farmerName,
            'farmer_phone' => $this->farmerPhone,
            'aadhar_no' => $this->aadharNo,
            'farmer_address' => $address ?: null,
            'zone_id' => $this->zoneId,
            'status' => 'pending',
            'submitter_ip' => request()->ip(),
            'submitter_ua' => mb_substr(request()->userAgent() ?? '', 0, 500),
        ]);

        session()->forget(['sample_form_token', 'sample_form_expires']);
        $this->dispatch('reset-recaptcha');

        // ---- Rate limiter hit ----
        RateLimiter::hit($rateKey, 3600);

        // Notify field officials in this zone
        $triggers = json_decode(setting('notification_triggers', '{}'), true);
        if ($triggers['sample_request']['enabled'] ?? false) {
            $body = resolve_notification_message(
                $triggers['sample_request']['message'] ?? '{farmer_name} ({farmer_phone}) requested sample collection in your zone.',
                ['farmer_name' => $this->farmerName, 'farmer_phone' => $this->farmerPhone]
            );
            NotificationService::sendToZone($this->zoneId, 'sample_request', 'New Sample Collection Request', $body, [
                'request_id' => $request->request_id,
                'sample_request_id' => (string) $request->id,
            ]);
        }

        $this->requestId = $request->request_id;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.public.sample-request-form')
            ->layout('layouts.public', ['title' => 'Request Sample Collection']);
    }
}
