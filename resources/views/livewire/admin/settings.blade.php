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
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-palette"></i>
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
                                <button type="button" wire:click="removeLogo" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-rose-600 bg-white border border-rose-200 hover:bg-rose-50 rounded-lg transition">
                                    <i class="fas fa-trash text-[10px]"></i> Remove
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
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-barcode"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Laboratory Evaluation &amp; Sequential Lab Codes</h3>
                        <p class="text-xs text-gray-500">Auto-incrementing internal tracking codes assigned when samples enter the lab</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Live Preview:</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-mono font-bold bg-emerald-100 text-emerald-900 border border-emerald-300 shadow-2xs">
                        <i class="fas fa-tag text-[10px] text-emerald-600"></i>
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
                    <i class="fas fa-info-circle text-emerald-600 mt-0.5"></i>
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
                    <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-clock"></i>
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
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-bold">
                        <i class="fas fa-shield-alt"></i>
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
