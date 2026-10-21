<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <span class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-emerald-500/20">
                <i class="fas fa-tractor"></i>
            </span>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Field Sample Collection</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">3-step field workflow to register farmer, assign plot, and scan container barcode</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('samples.index') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span>Sample Catalog</span>
            </a>
        </div>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-base flex-shrink-0">
                    <i class="fas fa-check"></i>
                </span>
                <div>
                    <h4 class="font-bold text-sm text-emerald-900">Sample Registered Successfully!</h4>
                    <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
            <a href="{{ route('field.collect-sample') }}"
               class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-emerald-600 text-white hover:bg-emerald-700 font-bold text-xs rounded-xl shadow-xs transition active:scale-[0.98]">
                <i class="fas fa-plus text-xs"></i>
                <span>Collect Another Sample</span>
            </a>
        </div>
        <script>
            (function() {
                try {
                    const audio = new Audio("{{ asset('sounds/success_chime.wav') }}");
                    audio.play().catch(function() {});
                } catch(e) {}
            })();
        </script>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl flex items-center gap-3 shadow-xs">
            <span class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center text-xs flex-shrink-0">
                <i class="fas fa-exclamation-triangle"></i>
            </span>
            <span class="text-xs font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(!session('success'))
        {{-- Admin Lab Selector --}}
        @if(auth()->user()->hasRole('Admin'))
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                        <i class="fas fa-building-columns"></i>
                    </span>
                    <div>
                        <span class="text-xs font-bold text-gray-900 block">Operating Laboratory</span>
                        <span class="text-[11px] text-gray-400">Select target laboratory destination for collected sample</span>
                    </div>
                </div>
                <select wire:model.live="labId"
                        class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-xs font-medium text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition min-w-[220px]">
                    <option value="">— Select Laboratory —</option>
                    @foreach($labs as $l)
                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- No Active Target Block --}}
        @if($hasNoActiveTarget)
            <div class="bg-amber-50 border border-amber-200 text-amber-900 p-4 rounded-2xl flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-base flex-shrink-0">
                    <i class="fas fa-triangle-exclamation"></i>
                </span>
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-wide text-amber-900">No Active Target Established</h4>
                    <p class="text-xs text-amber-700 mt-0.5">No active collection quota is assigned for your zone. Sample submission is temporarily paused. Contact your administrator.</p>
                </div>
            </div>
        @endif

        {{-- Zone Target Quota Progress Banner --}}
        @if($zoneTarget)
            @php
                $targetCount = $zoneTarget->target_count;
                $achieved = $zoneTarget->achievedCount();
                $remaining = $zoneTarget->remainingCount();
                $pct = $zoneTarget->progressPercent();
                $barBg = $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-rose-500');
            @endphp
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs font-bold">
                            <i class="fas fa-bullseye"></i>
                        </span>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Your Zone Target</span>
                            <h4 class="text-sm font-bold text-gray-900">{{ $zoneTarget->zone->name ?? 'N/A' }}</h4>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-mono">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase">Target</span>
                            <strong class="text-gray-900">{{ number_format($targetCount) }}</strong>
                        </div>
                        <div class="border-l border-gray-200 pl-4">
                            <span class="text-gray-400 block text-[10px] uppercase">Achieved</span>
                            <strong class="text-emerald-600 font-bold">{{ number_format($achieved) }}</strong>
                        </div>
                        <div class="border-l border-gray-200 pl-4">
                            <span class="text-gray-400 block text-[10px] uppercase">Remaining</span>
                            <strong class="{{ $remaining > 0 ? 'text-gray-700' : 'text-emerald-600' }}">{{ number_format($remaining) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="h-2.5 rounded-full {{ $barBg }} transition-all duration-500" style="width: {{ min($pct, 100) }}%"></div>
                    </div>
                    <span class="text-xs font-bold text-gray-700 w-12 text-right">{{ $pct }}%</span>
                </div>
            </div>
        @endif

        {{-- 3-Step Wizard Navigation Stepper --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4">
            <div class="flex items-center justify-between max-w-xl mx-auto">
                {{-- Step 1 --}}
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all {{ $step > 1 ? 'bg-emerald-600 text-white' : ($step === 1 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-gray-100 text-gray-400') }}">
                        @if($step > 1) <i class="fas fa-check"></i> @else 1 @endif
                    </span>
                    <span class="text-xs font-bold {{ $step >= 1 ? 'text-gray-900' : 'text-gray-400' }} hidden sm:inline">
                        Beneficiary
                    </span>
                </div>

                <div class="flex-1 h-0.5 mx-3 {{ $step > 1 ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>

                {{-- Step 2 --}}
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all {{ $step > 2 ? 'bg-emerald-600 text-white' : ($step === 2 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-gray-100 text-gray-400') }}">
                        @if($step > 2) <i class="fas fa-check"></i> @else 2 @endif
                    </span>
                    <span class="text-xs font-bold {{ $step >= 2 ? 'text-gray-900' : 'text-gray-400' }} hidden sm:inline">
                        Field Plot
                    </span>
                </div>

                <div class="flex-1 h-0.5 mx-3 {{ $step > 2 ? 'bg-emerald-500' : 'bg-gray-200' }}"></div>

                {{-- Step 3 --}}
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold transition-all {{ $step === 3 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-gray-100 text-gray-400' }}">
                        3
                    </span>
                    <span class="text-xs font-bold {{ $step === 3 ? 'text-gray-900' : 'text-gray-400' }} hidden sm:inline">
                        Sample QR
                    </span>
                </div>
            </div>
        </div>

        {{-- ===== STEP 1: BENEFICIARY SELECTION ===== --}}
        @if($step === 1)
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Step 1: Select or Register Beneficiary</h3>
                        <p class="text-xs text-gray-500">Search existing farmer database by name or mobile number</p>
                    </div>
                </div>

                {{-- Beneficiary Search Input --}}
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input wire:model.live="search" type="text" placeholder="Search farmer by full name or phone number..." autofocus
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-11 pr-10 py-3 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    @if(strlen($search))
                        <button type="button" wire:click="$set('search', '')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif
                </div>

                {{-- Search Results List --}}
                @if(strlen($search) >= 1 && $farmers->count())
                    <div class="space-y-2 max-h-72 overflow-y-auto divide-y divide-gray-50 border border-gray-100 rounded-2xl p-2 bg-gray-50/40">
                        @foreach($farmers as $farmer)
                            <div wire:click.prevent="selectFarmer({{ $farmer->id }})"
                                 class="p-3.5 rounded-xl cursor-pointer transition flex items-center justify-between {{ $selectedFarmerId === $farmer->id ? 'bg-emerald-600 text-white shadow-sm' : 'hover:bg-white bg-transparent text-gray-900' }}">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs {{ $selectedFarmerId === $farmer->id ? 'bg-white text-emerald-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ strtoupper(substr($farmer->name, 0, 1)) }}
                                    </span>
                                    <div>
                                        <h4 class="text-xs font-bold">{{ $farmer->name }}</h4>
                                        <p class="text-[11px] {{ $selectedFarmerId === $farmer->id ? 'text-emerald-100' : 'text-gray-400' }}">
                                            <i class="fas fa-phone mr-1 text-[10px]"></i>{{ $farmer->phone }}
                                            @if($farmer->parentage) &middot; S/O {{ $farmer->parentage }} @endif
                                        </p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $selectedFarmerId === $farmer->id ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $farmer->plots_count ?? 0 }} plots
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- No results found prompt --}}
                @if(strlen($search) >= 1 && !$farmers->count() && !$showFarmerForm)
                    <div class="text-center py-8 border border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                        <span class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-xl mb-3">
                            <i class="fas fa-user-slash"></i>
                        </span>
                        <h4 class="text-sm font-semibold text-gray-700">No beneficiary found matching "{{ $search }}"</h4>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Register this farmer now to proceed with sample collection</p>
                        <button type="button" wire:click="$set('showFarmerForm', true)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition active:scale-[0.98]">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Register New Beneficiary</span>
                        </button>
                    </div>
                @endif

                {{-- Selected Beneficiary Card --}}
                @if($selectedFarmer && !$showFarmerForm)
                    <div class="bg-emerald-50/60 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">
                                <i class="fas fa-check"></i>
                            </span>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Selected Beneficiary</span>
                                <h4 class="text-sm font-bold text-gray-900">{{ $selectedFarmer->name }}</h4>
                                <p class="text-xs text-gray-500 font-mono">{{ $selectedFarmer->phone }} @if($selectedFarmer->parentage) &middot; S/O {{ $selectedFarmer->parentage }} @endif</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="$set('selectedFarmerId', null)"
                                    class="px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-900 bg-white border border-gray-200 rounded-xl transition">
                                Change
                            </button>
                            <button type="button" wire:click="editFarmer({{ $selectedFarmer->id }})"
                                    class="px-3 py-1.5 text-xs font-medium text-emerald-700 hover:bg-emerald-100 bg-white border border-emerald-200 rounded-xl transition">
                                Edit
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Register / Edit Farmer Subform --}}
                @if($showFarmerForm && (!$selectedFarmerId || $editingFarmerId))
                    <div class="border border-emerald-100 rounded-2xl p-5 bg-gradient-to-b from-emerald-50/30 to-white space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-2">
                                <i class="fas fa-user-plus"></i>
                                {{ $editingFarmerId ? 'Edit Beneficiary Details' : 'Register New Beneficiary' }}
                            </h4>
                            <button type="button" wire:click="$set('showFarmerForm', false)" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>

                        <form wire:submit="saveFarmer" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Farmer Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input wire:model="newFarmerName" type="text" placeholder="Full name"
                                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                    @error('newFarmerName') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Parentage
                                    </label>
                                    <input wire:model="newFarmerParentage" type="text" placeholder="Father/Husband name"
                                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Phone Number <span class="text-rose-500">*</span>
                                    </label>
                                    <input wire:model="newFarmerPhone" type="text" placeholder="Mobile number"
                                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                    @error('newFarmerPhone') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Aadhaar Number <span class="text-rose-500">*</span>
                                    </label>
                                    <input wire:model="newFarmerAadhar" type="text" maxlength="12" placeholder="12-digit Aadhaar number"
                                           class="w-full rounded-xl border {{ $errors->has('newFarmerAadhar') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-white' }} px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                    @error('newFarmerAadhar') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        State <span class="text-rose-500">*</span>
                                    </label>
                                    <select wire:model.live="newFarmerStateId"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        <option value="">— Select State —</option>
                                        @foreach($states as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        District <span class="text-rose-500">*</span>
                                    </label>
                                    <select wire:model.live="newFarmerDistrictId"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        <option value="">— Select District —</option>
                                        @foreach($this->newDistricts as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Zone / Block
                                    </label>
                                    <select wire:model.live="newFarmerZoneId"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        <option value="">— Select Zone —</option>
                                        @foreach($this->newZones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Panchayat
                                    </label>
                                    <select wire:model.live="newFarmerPanchayatId"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        <option value="">— Select Panchayat —</option>
                                        @foreach($this->newPanchayats as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Village
                                    </label>
                                    <select wire:model="newFarmerVillageId"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        <option value="">— Select Village —</option>
                                        @foreach($this->newVillages as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                                <button type="button" wire:click="$set('showFarmerForm', false)"
                                        class="px-4 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition active:scale-[0.98]">
                                    <i class="fas fa-save text-xs"></i>
                                    <span>{{ $editingFarmerId ? 'Update Beneficiary' : 'Save & Select Beneficiary' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== STEP 2: PLOT SELECTION ===== --}}
        @if($step === 2)
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 space-y-5">
                {{-- Active Beneficiary Header --}}
                @if($selectedFarmer)
                    <div class="bg-emerald-50/60 border border-emerald-200 rounded-2xl p-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">
                                {{ strtoupper(substr($selectedFarmer->name, 0, 1)) }}
                            </span>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Beneficiary</span>
                                <h4 class="text-sm font-bold text-gray-900">{{ $selectedFarmer->name }}</h4>
                                <p class="text-xs text-gray-500 font-mono">{{ $selectedFarmer->phone }}</p>
                            </div>
                        </div>
                        <button type="button" wire:click="previousStep"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                            <i class="fas fa-arrow-left text-[10px]"></i>
                            <span>Change Beneficiary</span>
                        </button>
                    </div>
                @endif

                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Step 2: Select Field Plot</h3>
                        <p class="text-xs text-gray-500">Choose the agricultural plot being sampled or add a new survey plot</p>
                    </div>
                    @if(!$showPlotForm)
                        <button type="button" wire:click="$set('showPlotForm', true)"
                                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition active:scale-[0.98]">
                            <i class="fas fa-plus text-xs"></i>
                            <span>Add New Plot</span>
                        </button>
                    @endif
                </div>

                {{-- Existing Plots Grid --}}
                @if($plots->count())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($plots as $plot)
                            <div wire:click="selectPlot({{ $plot->id }})"
                                 class="p-4 rounded-2xl border transition cursor-pointer relative {{ $selectedPlotId === $plot->id ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20' : 'border-gray-200 bg-white hover:border-emerald-400 hover:shadow-xs' }}">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs {{ $selectedPlotId === $plot->id ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-500' }}">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-900">{{ $plot->plot_name }}</h4>
                                            <p class="text-[11px] text-gray-400">
                                                {{ $plot->khasra_no ? 'Khasra: ' . $plot->khasra_no : '' }}
                                                {{ $plot->area_kanals ? ' · ' . $plot->area_kanals . ' Kanals' : '' }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($selectedPlotId === $plot->id)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                            <i class="fas fa-check text-[9px]"></i> Selected
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-gray-100 text-[10px]">
                                    @if($plot->crop)
                                        <span class="px-2 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fas fa-seedling mr-0.5"></i> {{ $plot->crop->name }}
                                        </span>
                                    @endif
                                    @if($plot->soilType)
                                        <span class="px-2 py-0.5 rounded-full font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="fas fa-droplet mr-0.5"></i> {{ $plot->soilType->name }}
                                        </span>
                                    @endif
                                    @if($plot->age_of_plants)
                                        <span class="px-2 py-0.5 rounded-full font-semibold bg-gray-100 text-gray-600">
                                            {{ $plot->age_of_plants }} yrs
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 border border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
                        <span class="w-12 h-12 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mx-auto text-xl mb-3">
                            <i class="fas fa-map"></i>
                        </span>
                        <h4 class="text-sm font-semibold text-gray-700">No plots recorded for this beneficiary</h4>
                        <p class="text-xs text-gray-400 mt-1 mb-4">Add the agricultural plot to be sampled</p>
                        <button type="button" wire:click="$set('showPlotForm', true)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition active:scale-[0.98]">
                            <i class="fas fa-plus text-xs"></i>
                            <span>Add First Plot</span>
                        </button>
                    </div>
                @endif

                {{-- Add / Edit Plot Subform --}}
                @if($showPlotForm)
                    <div class="border border-emerald-100 rounded-2xl p-5 bg-gradient-to-b from-emerald-50/30 to-white space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-2">
                                <i class="fas fa-plus-circle"></i>
                                {{ $editingPlotId ? 'Edit Plot Information' : 'Add New Agricultural Plot' }}
                            </h4>
                            <button type="button" wire:click="$set('showPlotForm', false)" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>

                        <form wire:submit="savePlot" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Plot Name <span class="text-rose-500">*</span>
                                    </label>
                                    <input wire:model="newPlotName" type="text" placeholder="e.g. Upper Apple Orchard"
                                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                    @error('newPlotName') <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Khasra / Survey Number
                                    </label>
                                    <input wire:model="newPlotKhasra" type="text" placeholder="Survey / Khasra number"
                                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Area (Kanals)
                                    </label>
                                    <input wire:model="newPlotArea" type="number" step="0.01" placeholder="0.00"
                                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs font-mono text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Soil Type Classification
                                    </label>
                                    <select wire:model="newPlotSoilTypeId"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        <option value="">— Select Soil Type —</option>
                                        @foreach($soilTypes as $st)
                                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Target Crop
                                    </label>
                                    <select wire:model="newPlotCropId"
                                            class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                        <option value="">— Select Crop —</option>
                                        @foreach($crops as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                        Plant / Tree Age (Years)
                                    </label>
                                    <input wire:model="newPlotAge" type="number" step="0.1" placeholder="e.g. 5"
                                           class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs font-mono text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                                <button type="button" wire:click="$set('showPlotForm', false)"
                                        class="px-4 py-2 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition active:scale-[0.98]">
                                    <i class="fas fa-save text-xs"></i>
                                    <span>{{ $editingPlotId ? 'Update Plot' : 'Save & Select Plot' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== STEP 3: CONTAINER & QR SCANNER ===== --}}
        @if($step === 3)
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 space-y-6">
                {{-- Beneficiary & Plot Context Bar --}}
                @if($selectedFarmer && $selectedPlot)
                    <div class="bg-emerald-50/60 border border-emerald-200 rounded-2xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm font-bold">
                                <i class="fas fa-flask"></i>
                            </span>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $selectedFarmer->name }} &middot; {{ $selectedPlot->plot_name }}</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $selectedPlot->crop?->name ?? 'General Crop' }} &middot;
                                    {{ $selectedPlot->area_kanals ? $selectedPlot->area_kanals . ' Kanals' : '' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="previousStep"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                            <i class="fas fa-arrow-left text-[10px]"></i>
                            <span>Change Plot</span>
                        </button>
                    </div>
                @endif

                <div class="pb-3 border-b border-gray-100">
                    <h3 class="text-base font-bold text-gray-900">Step 3: Container Identification & QR Code</h3>
                    <p class="text-xs text-gray-500">Scan container barcode or enter the generated QR string to bind this sample</p>
                </div>

                {{-- QR Scanner Card --}}
                <div class="bg-gray-900 rounded-2xl p-6 text-white space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg bg-white/10 text-emerald-400 flex items-center justify-center text-xs">
                                <i class="fas fa-qrcode"></i>
                            </span>
                            <h4 class="text-sm font-bold">Container Barcode / QR Tag</h4>
                        </div>
                        <span class="text-xs text-gray-400">Required</span>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                        <div class="relative flex-1">
                            <input wire:model.live="qr_code" type="text" id="qr_input"
                                   placeholder="Scan or type container barcode..."
                                   class="w-full rounded-xl border border-gray-700 bg-gray-800 px-4 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition">
                        </div>

                        <button type="button" id="scannerToggleBtn" onclick="toggleScanner()"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition active:scale-[0.98]">
                            <i class="fas fa-camera"></i>
                            <span>Scan with Camera</span>
                        </button>

                        <button type="button" wire:click="validateQr"
                                class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-bold text-gray-900 bg-white hover:bg-gray-100 rounded-xl shadow-xs transition active:scale-[0.98]">
                            <i class="fas fa-check text-emerald-600"></i>
                            <span>Validate Tag</span>
                        </button>
                    </div>

                    <div wire:ignore id="qr-reader" style="display:none; max-width:280px; margin-top:12px; background:#fff; border-radius:12px; padding:8px;"></div>

                    <div>
                        @if($qrValid === true)
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white">
                                <i class="fas fa-check-circle"></i>
                                <span>Valid Container QR &mdash; Verified Available</span>
                            </div>
                        @endif
                        @if($qrValid === false)
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500 text-white">
                                <i class="fas fa-times-circle"></i>
                                <span>{{ $qrError }}</span>
                            </div>
                        @endif
                        @error('qr_code')
                            <p class="text-xs text-rose-400 font-medium mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Collection Metadata --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if(auth()->user()->hasRole('Admin'))
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Collection Timestamp
                            </label>
                            <input wire:model="collected_at" type="datetime-local"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-xs text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('collected_at') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div class="{{ auth()->user()->hasRole('Admin') ? '' : 'sm:col-span-2' }}">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                            Sample Code Identifier
                        </label>
                        <div class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-xs text-gray-500 font-mono flex items-center justify-between">
                            <span>Auto-generated by laboratory system upon save</span>
                            <i class="fas fa-lock text-gray-400 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Wizard Action Footer --}}
        <div class="flex items-center justify-between pt-2">
            @if($step > 1)
                <button type="button" wire:click="previousStep"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                    <i class="fas fa-arrow-left text-[10px]"></i>
                    <span>Previous Step</span>
                </button>
            @else
                <div></div>
            @endif

            <span class="text-xs text-gray-400 font-semibold">Step {{ $step }} of 3</span>

            @if($step < 3)
                <button type="button" wire:click="nextStep"
                        @if($step === 1 && !$selectedFarmerId) disabled @endif
                        @if($step === 2 && !$selectedPlotId) disabled @endif
                        class="inline-flex items-center gap-1.5 px-6 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <span>Continue to Step {{ $step + 1 }}</span>
                    <i class="fas fa-arrow-right text-[10px]"></i>
                </button>
            @else
                <button type="button" wire:click="save"
                        @if($qrValid !== true) disabled @endif
                        class="inline-flex items-center gap-2 px-6 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <i class="fas fa-check text-xs"></i>
                    <span>Commit & Register Sample</span>
                </button>
            @endif
        </div>
    @endif

    <script src="https://unpkg.com/html5-qrcode" defer></script>
    <script>
    let beepCtx = null;
    function beep() {
        try {
            if (!beepCtx) {
                beepCtx = new (window.AudioContext || window.webkitAudioContext)();
            }
            if (beepCtx.state === 'suspended') {
                beepCtx.resume();
            }
            const osc = beepCtx.createOscillator();
            const gain = beepCtx.createGain();
            osc.connect(gain);
            gain.connect(beepCtx.destination);
            osc.frequency.value = 1200;
            gain.gain.value = 0.15;
            osc.start(beepCtx.currentTime);
            osc.stop(beepCtx.currentTime + 0.12);
        } catch(e) {}
    }

    document.addEventListener('livewire:initialized', () => {
        let html5QrCode = null;
        let scannerActive = false;

        window.toggleScanner = function() {
            const container = document.getElementById('qr-reader');
            const btn = document.getElementById('scannerToggleBtn');

            if (!container || !btn) return;

            if (scannerActive) {
                if (html5QrCode) { html5QrCode.stop().catch(() => {}); }
                html5QrCode = null;
                container.style.display = 'none';
                scannerActive = false;
                btn.className = 'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition active:scale-[0.98]';
                btn.innerHTML = '<i class="fas fa-camera"></i> <span>Scan with Camera</span>';
                return;
            }

            container.style.display = 'block';
            btn.className = 'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition active:scale-[0.98]';
            btn.innerHTML = '<i class="fas fa-stop"></i> <span>Stop Scanner</span>';

            html5QrCode = new Html5Qrcode('qr-reader');

            html5QrCode.start(
                { facingMode: 'environment' },
                {
                    fps: 20,
                    qrbox: { width: 280, height: 280 },
                    aspectRatio: 1.333,
                    disableFlip: true,
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                },
                (decodedText) => {
                    html5QrCode.stop().catch(() => {});
                    html5QrCode = null;
                    scannerActive = false;
                    container.style.display = 'none';
                    btn.className = 'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition active:scale-[0.98]';
                    btn.innerHTML = '<i class="fas fa-camera"></i> <span>Scan with Camera</span>';

                    beep();

                    const inp = document.getElementById('qr_input');
                    if (inp) {
                        inp.value = decodedText;
                        inp.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                }
            ).catch((err) => {
                container.style.display = 'none';
                btn.className = 'inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-xs font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl transition active:scale-[0.98]';
                btn.innerHTML = '<i class="fas fa-camera"></i> <span>Scan with Camera</span>';
                scannerActive = false;
            });

            scannerActive = true;
        };

        window.addEventListener('beforeunload', () => {
            if (html5QrCode) html5QrCode.stop().catch(() => {});
        });
    });
    </script>
</div>