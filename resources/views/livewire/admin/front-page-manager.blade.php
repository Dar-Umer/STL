<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Public Portal Content &amp; Sections
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Manage landing page branding, hero carousel, official banners, statistics, and footer disclaimers</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="fmExpandAll" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium text-xs rounded-xl shadow-xs transition">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                Expand All
            </button>
            <button type="button" id="fmCollapseAll" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium text-xs rounded-xl shadow-xs transition">
                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/></svg>
                Collapse All
            </button>
        </div>
    </div>

    @if(session()->has('success'))
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

    <form wire:submit="save" class="space-y-4">
        {{-- SECTION 1: HEADER --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">1</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Header &amp; Government Branding</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Ministry / Government Name</label>
                        <input wire:model="headerMinistry" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition" placeholder="Government of Jammu & Kashmir (UT)">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Department (English)</label>
                        <input wire:model="headerDeptEn" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition" placeholder="Horticulture Department">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Department (Urdu)</label>
                        <input wire:model="headerDeptUr" type="text" dir="rtl" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition" placeholder="محکمہ باغبانی">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Department (Hindi)</label>
                        <input wire:model="headerDeptHi" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition" placeholder="बागवानी विभाग, जम्मू एवं कश्मीर">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Emblem Image</label>
                        <input wire:model="headerEmblem" type="file" accept="image/png,image/jpg,image/jpeg,image/svg+xml" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-1">PNG/JPG/SVG, max 1MB.</p>
                        @error('headerEmblem') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        <div class="mt-2 flex items-center gap-2">
                            @if($headerEmblemPath && !$headerEmblem)
                                <img src="{{ asset('storage/' . $headerEmblemPath) }}" class="h-10 rounded-lg border border-gray-200 p-1">
                                <button type="button" wire:click="removeHeaderEmblem" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">Remove</button>
                            @endif
                            @if($headerEmblem)
                                <img src="{{ $headerEmblem->temporaryUrl() }}" class="h-10 rounded-lg border border-gray-200 p-1">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-50">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Flag Image</label>
                        <input wire:model="headerFlag" type="file" accept="image/png,image/jpg,image/jpeg,image/svg+xml" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-1">PNG/JPG/SVG, max 1MB.</p>
                        @error('headerFlag') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                        <div class="mt-2 flex items-center gap-2">
                            @if($headerFlagPath && !$headerFlag)
                                <img src="{{ asset('storage/' . $headerFlagPath) }}" class="h-10 rounded-lg border border-gray-200 p-1">
                                <button type="button" wire:click="removeHeaderFlag" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">Remove</button>
                            @endif
                            @if($headerFlag)
                                <img src="{{ $headerFlag->temporaryUrl() }}" class="h-10 rounded-lg border border-gray-200 p-1">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: NOTICE BAR --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">2</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Announcement &amp; Notice Bar</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Badge Label</label>
                        <input wire:model="noticeLabel" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Notice">
                    </div>
                    <div class="md:col-span-9 space-y-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Active Notices Ticker</label>
                        @foreach($notices as $index => $notice)
                        <div class="flex items-center gap-2">
                            <input wire:model="notices.{{ $index }}" type="text" class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Announcement text...">
                            <button type="button" wire:click="removeNotice({{ $index }})" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition" title="Remove">&times;</button>
                        </div>
                        @endforeach
                        <button type="button" wire:click="addNotice" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-dashed border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold transition">
                            <i class="fas fa-plus text-[10px]"></i> Add Notice
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: HERO BANNER --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">3</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Hero Banner &amp; Visual Showcase</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-8">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Hero Title</label>
                        <input wire:model="heroTitle" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Main heading on the banner">
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Button Text</label>
                        <input wire:model="heroBtnText" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Download Your Report">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Subtitle</label>
                    <textarea wire:model="heroSubtitle" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Descriptive text below heading"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Button URL</label>
                        <input wire:model="heroBtnUrl" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="/track-report">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Hero Image 1</label>
                        <input wire:model="heroPhoto" type="file" accept="image/png,image/jpg,image/jpeg" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <div class="mt-2 flex items-center gap-2">
                            @if($heroPhotoPath && !$heroPhoto)
                                <img src="{{ asset('storage/' . $heroPhotoPath) }}" class="h-10 rounded-lg border border-gray-200 p-1">
                                <button type="button" wire:click="removeHeroPhoto" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">Remove</button>
                            @endif
                            @if($heroPhoto)
                                <img src="{{ $heroPhoto->temporaryUrl() }}" class="h-10 rounded-lg border border-gray-200 p-1">
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-gray-50">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Hero Image 2</label>
                        <input wire:model="heroPhoto2" type="file" accept="image/png,image/jpg,image/jpeg" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <div class="mt-2 flex items-center gap-2">
                            @if($heroPhotoPath2 && !$heroPhoto2)
                                <img src="{{ asset('storage/' . $heroPhotoPath2) }}" class="h-10 rounded-lg border border-gray-200 p-1">
                                <button type="button" wire:click="removeHeroPhoto2" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">Remove</button>
                            @endif
                            @if($heroPhoto2)
                                <img src="{{ $heroPhoto2->temporaryUrl() }}" class="h-10 rounded-lg border border-gray-200 p-1">
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Hero Image 3</label>
                        <input wire:model="heroPhoto3" type="file" accept="image/png,image/jpg,image/jpeg" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <div class="mt-2 flex items-center gap-2">
                            @if($heroPhotoPath3 && !$heroPhoto3)
                                <img src="{{ asset('storage/' . $heroPhotoPath3) }}" class="h-10 rounded-lg border border-gray-200 p-1">
                                <button type="button" wire:click="removeHeroPhoto3" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">Remove</button>
                            @endif
                            @if($heroPhoto3)
                                <img src="{{ $heroPhoto3->temporaryUrl() }}" class="h-10 rounded-lg border border-gray-200 p-1">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: CHO --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">4</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Chief Horticulture Officer Message</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Designation Title</label>
                        <input wire:model="choTitle" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Chief Horticulture Officer">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Officer Name</label>
                        <input wire:model="choName" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Shri. Name">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Post / District</label>
                        <input wire:model="choPost" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Pulwama District">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Officer Photo</label>
                        <input wire:model="choPhoto" type="file" accept="image/png,image/jpg,image/jpeg" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                        <div class="mt-2 flex items-center gap-2">
                            @if($choPhotoPath && !$choPhoto)
                                <img src="{{ asset('storage/' . $choPhotoPath) }}" class="h-10 rounded-lg border border-gray-200 p-1">
                                <button type="button" wire:click="removeChoPhoto" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">Remove</button>
                            @endif
                            @if($choPhoto)
                                <img src="{{ $choPhoto->temporaryUrl() }}" class="h-10 rounded-lg border border-gray-200 p-1">
                            @endif
                        </div>
                    </div>
                    <div class="md:col-span-8">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Official Message</label>
                        <textarea wire:model="choDescription" rows="3" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Message from the CHO"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Phone</label>
                        <input wire:model="choPhone" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="+91-XXXX-XXXXXX">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Email</label>
                        <input wire:model="choEmail" type="email" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="cho.pulwama@jk.gov.in">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Office Address</label>
                        <input wire:model="choAddress" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Mini Secretariat, Pulwama">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 5: UPDATES & SERVICES --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">5</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Latest Orders, Updates &amp; Services</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-6">
                <div>
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Noticeboard Updates &amp; Orders</h3>
                    <div class="space-y-2">
                        @foreach($updates as $uIndex => $update)
                        <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-xl border border-gray-200/80">
                            <input wire:model="updates.{{ $uIndex }}.text" type="text" class="flex-1 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-900 focus:outline-none focus:border-emerald-500" placeholder="Update description">
                            <input wire:model="updates.{{ $uIndex }}.date" type="text" class="w-32 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-900 focus:outline-none focus:border-emerald-500" placeholder="e.g. 10 Jun">
                            <button type="button" wire:click="removeUpdate({{ $uIndex }})" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">&times;</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" wire:click="addUpdate" class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-dashed border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold transition">
                        <i class="fas fa-plus text-[10px]"></i> Add Update
                    </button>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Featured Services (6 Cards)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @for($si = 1; $si <= 6; $si++)
                        <div class="bg-gray-50/70 p-3.5 rounded-xl border border-gray-200 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-800">Card {{ $si }}</span>
                                @if($si > 4)
                                <div class="flex items-center gap-2">
                                    <input wire:model="{{ 'feature'.$si.'Icon' }}" type="text" class="w-24 rounded-lg border border-gray-200 bg-white px-2 py-1 text-[11px]" placeholder="fa-leaf">
                                    <input wire:model="{{ 'feature'.$si.'Url' }}" type="text" class="w-28 rounded-lg border border-gray-200 bg-white px-2 py-1 text-[11px]" placeholder="/url">
                                </div>
                                @endif
                            </div>
                            <input wire:model="{{ 'feature'.$si.'Title' }}" type="text" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-900 focus:outline-none focus:border-emerald-500" placeholder="Title">
                            <textarea wire:model="{{ 'feature'.$si.'Text' }}" rows="2" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:border-emerald-500" placeholder="Card summary description"></textarea>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 6: VMO --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">6</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Vision, Mission &amp; Objectives</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Vision Statement</label>
                    <textarea wire:model="visionText" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Mission Statement</label>
                    <textarea wire:model="missionText" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Objectives &amp; Strategic Goals</label>
                    <textarea wire:model="objectivesText" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition"></textarea>
                </div>
            </div>
        </div>

        {{-- SECTION 7: FACTS --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">7</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Horticulture Statistics &amp; Fast Facts</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @for($fi = 1; $fi <= 4; $fi++)
                    <div class="bg-gray-50/80 p-3.5 rounded-xl border border-gray-200 space-y-2">
                        <span class="text-xs font-bold text-emerald-700 uppercase">Fact #{{ $fi }}</span>
                        <input wire:model="{{ 'fact'.$fi.'Value' }}" type="text" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-bold text-gray-900 focus:outline-none focus:border-emerald-500" placeholder="Value (e.g. 14x)">
                        <input wire:model="{{ 'fact'.$fi.'Label' }}" type="text" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:border-emerald-500" placeholder="Label">
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- SECTION 8: CTA --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">8</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Call-To-Action (Track Report Banner)</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Icon Class</label>
                        <input wire:model="ctaIcon" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="fa-search">
                    </div>
                    <div class="md:col-span-9">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">CTA Heading</label>
                        <input wire:model="ctaTitle" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="Track Your Soil Report">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">CTA Subtitle</label>
                    <textarea wire:model="ctaSubtitle" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Button Text</label>
                        <input wire:model="ctaBtnText" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Button Destination URL</label>
                        <input wire:model="ctaBtnUrl" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 9: INFO RIBBON --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">9</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Features Ribbon (4 Items)</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @for($ri = 1; $ri <= 4; $ri++)
                    <div class="bg-gray-50/80 p-3.5 rounded-xl border border-gray-200 space-y-2">
                        <span class="text-xs font-bold text-emerald-700 uppercase">Ribbon #{{ $ri }}</span>
                        <input wire:model="{{ 'ribbon'.$ri.'Icon' }}" type="text" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-900 focus:outline-none focus:border-emerald-500" placeholder="Icon (fa-heart)">
                        <input wire:model="{{ 'ribbon'.$ri.'Label' }}" type="text" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-700 focus:outline-none focus:border-emerald-500" placeholder="Label">
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- SECTION 10: FOOTER --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">10</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Footer Branding &amp; Contact Details</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">About Text</label>
                    <textarea wire:model="footerAbout" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Copyright Notice</label>
                        <input wire:model="footerCopyright" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Disclaimer</label>
                        <input wire:model="footerDisclaimer" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Credit Text</label>
                        <input wire:model="footerCreditText" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Credit URL</label>
                        <input wire:model="footerCreditUrl" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Helpline Phone</label>
                        <input wire:model="frontHelpline" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Soil Lab Helpline</label>
                        <input wire:model="labPhone" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Working Hours Note</label>
                    <input wire:model="frontWorkingHours" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition">
                </div>
            </div>
        </div>

        {{-- SECTION 11: META / SEO --}}
        <div class="fm-sec bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="fm-head px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white cursor-pointer select-none">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">11</span>
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">SEO Metadata &amp; Social Share Image</h2>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200 fm-icon"></i>
            </div>
            <div class="fm-content p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Meta Title</label>
                        <input wire:model="metaTitle" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="SEO browser title">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Meta Keywords</label>
                        <input wire:model="metaKeywords" type="text" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition" placeholder="soil, testing, agriculture...">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Meta Description</label>
                    <textarea wire:model="metaDescription" rows="2" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">Share Image (OG Banner for WhatsApp / Social Media)</label>
                    <input wire:model="ogImage" type="file" accept="image/png,image/jpg,image/jpeg" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    <p class="text-[10px] text-gray-400 mt-1">PNG/JPG, max 2MB. 1200×630 recommended.</p>
                    @error('ogImage') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    <div class="mt-2 flex items-center gap-2">
                        @if($ogImagePath && !$ogImage)
                            <img src="{{ asset('storage/' . $ogImagePath) }}" class="h-10 rounded-lg border border-gray-200 p-1">
                            <button type="button" wire:click="removeOgImage" class="px-2.5 py-1 text-[11px] font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition">Remove</button>
                        @endif
                        @if($ogImage)
                            <img src="{{ $ogImage->temporaryUrl() }}" class="h-10 rounded-lg border border-gray-200 p-1">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- STICKY SAVE BAR --}}
        <div class="sticky bottom-4 z-40 bg-white/95 backdrop-blur-md rounded-2xl shadow-lg border border-gray-200 p-4 flex items-center justify-between gap-4">
            <div class="text-xs text-gray-500 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Changes apply live immediately to the public portal home page.</span>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Front Page Content
            </button>
        </div>
    </form>

    <script>
        document.querySelectorAll('.fm-head').forEach(function(h) {
            h.addEventListener('click', function() {
                var sec = this.closest('.fm-sec');
                var body = sec.querySelector('.fm-content');
                var icon = this.querySelector('.fm-icon');
                if (body.style.display === 'none') {
                    body.style.display = 'block';
                    if (icon) icon.classList.remove('-rotate-90');
                } else {
                    body.style.display = 'none';
                    if (icon) icon.classList.add('-rotate-90');
                }
            });
        });
        document.getElementById('fmExpandAll').addEventListener('click', function() {
            document.querySelectorAll('.fm-content').forEach(function(el) { el.style.display = 'block'; });
            document.querySelectorAll('.fm-icon').forEach(function(el) { el.classList.remove('-rotate-90'); });
        });
        document.getElementById('fmCollapseAll').addEventListener('click', function() {
            document.querySelectorAll('.fm-content').forEach(function(el) { el.style.display = 'none'; });
            document.querySelectorAll('.fm-icon').forEach(function(el) { el.classList.add('-rotate-90'); });
        });
    </script>
</div>