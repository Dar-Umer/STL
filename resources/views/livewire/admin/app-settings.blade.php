<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Mobile App Configuration
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Manage Flutter mobile app versioning, field operating hours, announcements, and collection protocols</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 p-1 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Section 1: Mobile App Versioning & Release Enforcement --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fas fa-mobile-alt text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Mobile Release &amp; Versioning</h2>
                        <p class="text-xs text-gray-400">Control minimum client version and update redirection for the Flutter app</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Target Version Number <span class="text-rose-500">*</span>
                        </label>
                        <input wire:model="appVersion" type="text" placeholder="e.g. 1.2.0"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-mono text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Minimum version required for field official synchronization</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Download / Store URL
                        </label>
                        <input wire:model="appUpdateUrl" type="url" placeholder="https://play.google.com/store/apps/..."
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Direct APK or Play Store link served when updates are required</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Forced Update Policy
                        </label>
                        <div class="p-3.5 rounded-xl border {{ $forceUpdate ? 'bg-rose-50/50 border-rose-200' : 'bg-emerald-50/50 border-emerald-200' }} transition">
                            <label class="flex items-center gap-3 cursor-pointer select-none">
                                <input wire:model="forceUpdate" type="checkbox"
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <div>
                                    <span class="text-xs font-bold {{ $forceUpdate ? 'text-rose-700' : 'text-emerald-700' }}">
                                        {{ $forceUpdate ? 'Enforce Immediate Update' : 'Optional Update Recommended' }}
                                    </span>
                                    <p class="text-[10px] text-gray-500 mt-0.5">
                                        {{ $forceUpdate ? 'Blocks old versions from syncing' : 'Allows older versions to operate' }}
                                    </p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Collection Protocols & Field Guide --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fas fa-clipboard-list text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Field Collection Protocol Guide</h2>
                        <p class="text-xs text-gray-400">Step-by-step soil sampling guidelines displayed directly inside the mobile app</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Sampling Guide Instructions (Markdown Supported)
                    </label>
                    <textarea wire:model="collectionGuide" rows="6"
                              class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 font-mono placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition"
                              placeholder="1. Divide the agricultural holding into uniform sampling units&#10;2. Collect V-shaped sub-samples at 15-20cm depth&#10;3. Mix composite sample thoroughly on clean plastic sheet&#10;4. Scan barcode bag tag and confirm GPS coordinates"></textarea>
                    <p class="text-[11px] text-gray-400 mt-1">Field officials can review these standardized SOP instructions offline in the mobile app.</p>
                </div>
            </div>
        </div>

        {{-- Section 3: Field App Announcements Feed --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fas fa-bullhorn text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Field Noticeboard &amp; Broadcasts</h2>
                        <p class="text-xs text-gray-400">Announcements displayed on the mobile app home screen banner</p>
                    </div>
                </div>
                <button type="button" wire:click="addAppAnnouncement"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-dashed border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Notice
                </button>
            </div>

            <div class="p-6 space-y-3">
                @forelse($appAnnouncements as $index => $announcement)
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <input wire:model="appAnnouncements.{{ $index }}" type="text" placeholder="Enter broadcast announcement text..."
                               class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        <button type="button" wire:click="removeAppAnnouncement({{ $index }})"
                                class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Remove notice">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-6 border border-dashed border-gray-200 rounded-xl bg-gray-50/50">
                        <p class="text-xs text-gray-400">No active announcements. Click "Add Notice" to broadcast messages to field teams.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Section 4: Operational Working Hours & Quota Days --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Field Operational Working Hours</h2>
                        <p class="text-xs text-gray-400">Enforce official duty hours for GPS-tagged sample collection</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div class="p-4 rounded-xl border {{ $workingHoursEnabled ? 'bg-emerald-50/50 border-emerald-200' : 'bg-gray-50 border-gray-200' }} transition">
                    <label class="flex items-center gap-3 cursor-pointer select-none">
                        <input wire:model.live="workingHoursEnabled" type="checkbox"
                               class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                        <div>
                            <span class="text-xs font-bold {{ $workingHoursEnabled ? 'text-emerald-800' : 'text-gray-700' }}">
                                {{ $workingHoursEnabled ? 'Operational Hours Restriction Active' : 'No Restrictions — Collection Allowed 24/7' }}
                            </span>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                {{ $workingHoursEnabled ? 'Field sample collection is disabled outside configured shift hours' : 'Field officials can record sample entries anytime' }}
                            </p>
                        </div>
                    </label>
                </div>

                @if($workingHoursEnabled)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Shift Start Time</label>
                        <input wire:model="workStartTime" type="time"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Shift End Time</label>
                        <input wire:model="workEndTime" type="time"
                               class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-2">Active Field Duty Days</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $days = [
                                    0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed',
                                    4 => 'Thu', 5 => 'Fri', 6 => 'Sat'
                                ];
                            @endphp
                            @foreach($days as $num => $label)
                                <button type="button" wire:click="toggleWorkingDay({{ $num }})"
                                        class="px-4 py-2 rounded-xl text-xs font-bold transition active:scale-95 {{ in_array($num, $workingDays) ? 'bg-emerald-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-gray-400 mt-2">Days highlighted in emerald permit active sample collection on mobile clients.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Commit Actions --}}
        <div class="flex items-center justify-between p-4 bg-white rounded-2xl shadow-xs border border-gray-100">
            <span class="text-xs text-gray-400 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Updates take effect immediately on all connected Flutter app instances.
            </span>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save App Settings
            </button>
        </div>
    </form>
</div>
