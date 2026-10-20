<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Notification Triggers &amp; FCM
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Configure automated event triggers and Firebase Cloud Messaging v1 integration credentials</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Auto-Notification Triggers Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Automated Notification Triggers</h2>
                        <p class="text-xs text-gray-400">Custom messages dispatched automatically on lifecycle milestones</p>
                    </div>
                </div>
            </div>

            <div class="p-6 divide-y divide-gray-100">
                @foreach($defaults as $key => $cfg)
                <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start justify-between gap-4">
                    <div class="flex-1 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-gray-900 text-sm">{{ $cfg['label'] }}</span>
                            <span class="font-mono text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-500 uppercase">{{ $key }}</span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $cfg['desc'] }}</p>
                        
                        <div class="pt-1">
                            <textarea wire:model="triggers.{{ $key }}.message" rows="2" placeholder="{{ $cfg['message'] }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition"></textarea>
                            <p class="text-[11px] text-gray-400 mt-1">
                                Available variables: <code class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">{{ $cfg['vars'] }}</code>
                            </p>
                        </div>
                    </div>

                    <div class="pt-1">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input wire:model="triggers.{{ $key }}.enabled" type="checkbox" id="switch-{{ $key }}" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Firebase FCM v1 Configuration Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Firebase Cloud Messaging (FCM v1 HTTP API)</h2>
                        <p class="text-xs text-gray-400">Enterprise encrypted service account credentials</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-4">
                @if($storedServiceAccount)
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <div>
                        <p class="font-bold">Service account active &amp; verified</p>
                        <p class="mt-0.5">
                            @if($storedProjectId)<span>Project ID: <strong class="font-mono">{{ $storedProjectId }}</strong></span>@endif
                            @if($storedEmail)<span class="ml-2">&middot; Email: <strong class="font-mono">{{ $storedEmail }}</strong></span>@endif
                        </p>
                        <p class="text-[11px] text-blue-700 mt-1">To replace the existing key, paste a new service account JSON below.</p>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Service Account JSON Key File</label>
                    <textarea wire:model="serviceAccountJson" rows="7" placeholder="Paste the complete contents of your service-account-credentials.json here..." class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 font-mono text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition"></textarea>
                    <p class="text-xs text-gray-400 mt-1.5">
                        Firebase Console &rarr; Project Settings &rarr; Service Accounts &rarr; "Generate New Private Key". Stored AES-256 encrypted.
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Trigger &amp; FCM Settings
                </button>
            </div>
        </div>
    </form>
</div>
