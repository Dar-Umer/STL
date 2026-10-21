<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>System Settings</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Manage global portal branding, maintenance status, testing thresholds, and external services.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="rounded-2xl bg-emerald-50 border border-emerald-200/80 p-4 flex items-center justify-between text-emerald-800 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </span>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
        <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Section 1: Portal Identity & Branding --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/70 to-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 8 8 0 018-8 8 8 0 018 8 4 4 0 01-4 4h-2a2 2 0 00-2 2 2 2 0 01-2 2H7z"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/><circle cx="15.5" cy="8.5" r="1.5" fill="currentColor"/><circle cx="12" cy="5.5" r="1.5" fill="currentColor"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Portal Identity & Branding</h3>
                        <p class="text-xs text-gray-500">Application name, tagline, and official logo graphics</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Portal / Application Name <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model="appName" type="text" placeholder="Soil Lab"
                               class="w-full rounded-xl border {{ $errors->has('appName') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Appears in header, sidebar title, and generated PDF certificates.</p>
                        @error('appName') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Application Tagline / Subtitle
                        </label>
                        <input wire:model="appDescription" type="text" placeholder="Soil Testing & Health Management System"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Subtitle shown on login screen and export headers.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-3 border-t border-gray-100">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Brand Logo
                        </label>
                        <div class="flex items-center gap-3">
                            <input wire:model="logo" type="file" id="logoUpload" accept="image/png,image/jpg,image/jpeg,image/svg+xml"
                                   class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">PNG, JPG, or SVG. Max 1MB. Transparent background recommended.</p>
                        @error('logo') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror

                        @if($logoPath && !$logo)
                            <div class="mt-3 flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-200">
                                <img src="{{ asset('storage/' . $logoPath) }}" class="max-h-10 max-w-[140px] object-contain">
                                <button type="button" wire:click="removeLogo" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium text-rose-600 bg-white border border-rose-200 hover:bg-rose-50 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Remove</span>
                                </button>
                            </div>
                        @endif

                        @if($logo)
                            <div class="mt-3 flex items-center gap-3 p-3 rounded-xl bg-emerald-50/60 border border-emerald-200">
                                <img src="{{ $logo->temporaryUrl() }}" class="max-h-10 max-w-[140px] object-contain">
                                <span class="text-xs font-semibold text-emerald-800">New logo ready to save</span>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            System Access State
                        </label>
                        <div class="p-4 rounded-xl border {{ $maintenanceMode ? 'bg-rose-50/60 border-rose-200' : 'bg-emerald-50/60 border-emerald-200' }} transition">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input wire:model="maintenanceMode" type="checkbox"
                                       class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <div>
                                    <span class="text-xs font-bold {{ $maintenanceMode ? 'text-rose-800' : 'text-emerald-900' }}">
                                        {{ $maintenanceMode ? 'Maintenance Mode Active — Users cannot access the system' : 'Live / Operational — Portal is accessible normally' }}
                                    </span>
                                    <p class="text-[11px] text-gray-500 mt-0.5">When active, field workers and non-admin staff see a maintenance notice.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Official Report Footer Advisory Notice
                    </label>
                    <textarea wire:model="reportFooter" rows="2" placeholder="Optional disclaimer or advisory text printed at the bottom of generated PDF soil test reports..."
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition"></textarea>
                    <p class="text-[11px] text-gray-400 mt-1">Appears at the footer of official soil test report certificates.</p>
                </div>
            </div>
        </div>

        {{-- Section 2: Laboratory Evaluation & Sequential Lab Codes --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/70 to-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Laboratory Evaluation &amp; Sequential Lab Codes</h3>
                        <p class="text-xs text-gray-500">Auto-incrementing internal tracking codes assigned when samples enter the lab</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Live Preview:</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-mono font-bold bg-emerald-100 text-emerald-900 border border-emerald-300 shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        <span>{{ $this->labNumberPreview }}</span>
                    </span>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Lab Code Prefix <span class="text-xs font-normal text-gray-400 lowercase">(optional)</span>
                        </label>
                        <input wire:model.live="labNumberPrefix" type="text" placeholder="None (numeric only)"
                               class="w-full rounded-xl border {{ $errors->has('labNumberPrefix') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 font-mono focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Leave empty to use pure numbers (e.g. <code>1001</code>).</p>
                        @error('labNumberPrefix') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Next Auto Sequence No. <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model.live="labNumberNext" type="number" min="1" placeholder="1001"
                               class="w-full rounded-xl border {{ $errors->has('labNumberNext') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 font-mono focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Increments automatically on every sample intake.</p>
                        @error('labNumberNext') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Minimum Digits (Padding) <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model.live="labNumberPadding" type="number" min="1" max="8" placeholder="4"
                               class="w-full rounded-xl border {{ $errors->has('labNumberPadding') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 font-mono focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">E.g., 4 yields <code>1001</code>, 5 yields <code>01001</code>.</p>
                        @error('labNumberPadding') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Include Year In Code
                        </label>
                        <div class="pt-2">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input wire:model.live="labNumberIncludeYear" type="checkbox"
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="text-xs font-semibold text-gray-800">
                                    {{ $labNumberIncludeYear ? 'Year included (e.g. EV-2026-1001)' : 'Standard numeric sequence' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-xs text-gray-600 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <strong>Why is this number needed?</strong>
                        <p class="mt-0.5 text-gray-500">
                            When samples arrive at the lab, technicians use this internal <strong>Evaluation Code</strong> on test tubes, titration flasks, and bench analysis sheets to track testing without exposing sensitive farmer details during benchwork.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Operational Thresholds & Security --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Operational Thresholds --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/70 to-white flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Operational Thresholds</h3>
                        <p class="text-xs text-gray-500">Testing SLAs and queue turnaround alerts</p>
                    </div>
                </div>
                <div class="p-6">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Overdue Sample Threshold (Days)
                        </label>
                        <input wire:model="overdueDays" type="number" min="1" max="365" placeholder="2"
                               class="w-full rounded-xl border {{ $errors->has('overdueDays') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Samples in laboratory queue exceeding this duration without testing are flagged overdue.</p>
                        @error('overdueDays') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Security & reCAPTCHA --}}
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/70 to-white flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Security & Bot Protection</h3>
                        <p class="text-xs text-gray-500">Google reCAPTCHA v2 verification</p>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <div class="p-3 rounded-xl border bg-gray-50/70 border-gray-200">
                        <label class="flex items-center gap-3 cursor-pointer select-none">
                            <input wire:model="recaptchaEnabled" type="checkbox"
                                   class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                            <div>
                                <span class="text-xs font-bold text-gray-800">
                                    {{ $recaptchaEnabled ? 'reCAPTCHA Active on Login & Public Forms' : 'reCAPTCHA Disabled' }}
                                </span>
                                <p class="text-[11px] text-gray-400 mt-0.5">Recommended in public cloud production deployments.</p>
                            </div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Site Key</label>
                            <input wire:model="recaptchaSiteKey" type="text" placeholder="6Lc..."
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-900 font-mono focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Secret Key</label>
                            <input wire:model="recaptchaSecretKey" type="text" placeholder="6Lc..."
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-900 font-mono focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 4: Email & SMTP Gateway Configuration --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/70 to-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Email &amp; SMTP Gateway Configuration</h3>
                        <p class="text-xs text-gray-500">Outbound mail gateway used for delivering Password Reset OTPs and official reports</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    <span>OTP Delivery Gateway</span>
                </span>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Mail Gateway Driver
                        </label>
                        <select wire:model="mailMailer" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="smtp">SMTP (Recommended for Gmail, SES, Mailgun, etc.)</option>
                            <option value="sendmail">Sendmail</option>
                            <option value="log">Log Driver (Testing - logs to storage/logs)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            SMTP Server Host
                        </label>
                        <input wire:model="mailHost" type="text" placeholder="e.g. smtp.gmail.com"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 font-mono focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Hostname or IP of outbound mail server.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            SMTP Server Port
                        </label>
                        <input wire:model="mailPort" type="number" placeholder="587"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 font-mono focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">587 for TLS, 465 for SSL, or 2525 for sandbox.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            SMTP Username
                        </label>
                        <input wire:model="mailUsername" type="text" placeholder="username or account email"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            SMTP Password / App Password
                        </label>
                        <input wire:model="mailPassword" type="password" placeholder="••••••••••••"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Encryption Protocol
                        </label>
                        <select wire:model="mailEncryption" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="tls">TLS (Port 587 - Standard)</option>
                            <option value="ssl">SSL (Port 465)</option>
                            <option value="none">None / Unencrypted</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            From Sender Email Address
                        </label>
                        <input wire:model="mailFromAddress" type="email" placeholder="noreply@soillab.gov.in"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            From Sender Display Name
                        </label>
                        <input wire:model="mailFromName" type="text" placeholder="Soil Testing Laboratory Notifications"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    </div>
                </div>

                {{-- Test Email Dispatch Box --}}
                <div class="mt-4 p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span>Test Outbound SMTP Delivery</span>
                        </span>
                        <span class="text-[11px] text-gray-400">Verifies your SMTP credentials immediately</span>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2.5">
                        <input wire:model="testEmailRecipient" type="email" placeholder="Enter recipient email (e.g. admin@soillab.com)"
                               class="flex-1 rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-blue-500 transition">
                        <button type="button" wire:click="sendTestEmail" wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-xs">
                            <span wire:loading.remove wire:target="sendTestEmail" class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                <span>Send Test Email</span>
                            </span>
                            <span wire:loading wire:target="sendTestEmail" class="flex items-center gap-1.5">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                <span>Sending...</span>
                            </span>
                        </button>
                    </div>

                    @if($testEmailStatus === 'success')
                        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-800 flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $testEmailMessage }}</span>
                        </div>
                    @elseif($testEmailStatus === 'error')
                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs font-medium text-rose-800 flex items-start gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <strong>Delivery Error:</strong>
                                <p class="mt-0.5 font-mono text-[11px]">{{ $testEmailMessage }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Action Bar --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <span class="text-xs text-gray-500 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Changes apply instantly across all laboratory, field official and public modules.
            </span>
            <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Settings
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
