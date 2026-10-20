<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <span class="w-11 h-11 rounded-2xl bg-cyan-600 text-white flex items-center justify-center text-lg font-bold shadow-md shadow-cyan-600/20">
                <i class="fas fa-hand-holding"></i>
            </span>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Walk-in Self-Sample Intake</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Register walk-in beneficiaries and collect soil samples directly at the laboratory</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('lab.dashboard') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl shadow-xs transition active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Lab Overview</span>
            </a>
        </div>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center justify-between text-xs font-medium animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center justify-between text-xs font-medium animate-fade-in">
            <div class="flex items-center gap-2">
                <i class="fas fa-circle-exclamation text-rose-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($showForm)
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/70 to-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-700 flex items-center justify-center text-xs font-bold">
                        <i class="fas fa-file-invoice"></i>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Direct Intake Registration</h3>
                        <p class="text-[11px] text-gray-400">Immediate laboratory check-in and QR ticket assignment</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6">
                {{-- Fast Lookup Hero Bar --}}
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200/80">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">
                        Lookup Beneficiary by Mobile Number
                    </label>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 max-w-xl">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-phone text-xs"></i>
                            </div>
                            <input wire:model.blur="phone" type="text" placeholder="10-digit mobile number..."
                                   class="w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 py-2.5 text-sm font-mono text-gray-900 placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>
                        <button type="button" wire:click="lookupFarmer"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-cyan-700 hover:bg-cyan-800 rounded-xl shadow-xs transition active:scale-[0.98]">
                            <i class="fas fa-search text-xs"></i>
                            <span>Lookup Beneficiary</span>
                        </button>
                    </div>
                    @if($foundFarmer)
                        <div class="mt-2.5 inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                            <span>Existing Beneficiary Found: <strong>{{ $foundFarmer->name }}</strong> (Aadhaar: {{ $foundFarmer->aadhar_no ?? 'N/A' }})</span>
                        </div>
                    @endif
                </div>

                {{-- Section 1: Beneficiary Details --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-user text-cyan-600"></i>
                        1. Beneficiary Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Beneficiary Name <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="name" type="text" placeholder="Full legal name"
                                   class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('name') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="phone" type="text" placeholder="+91 9876543210"
                                   class="w-full rounded-xl border {{ $errors->has('phone') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('phone') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Parentage / Guardian
                            </label>
                            <input wire:model="parentage" type="text" placeholder="Father or Husband name"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Aadhaar Number
                            </label>
                            <input wire:model="aadhar" type="text" placeholder="12-digit Aadhaar number"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                State <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="stateId"
                                    class="w-full rounded-xl border {{ $errors->has('stateId') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select State —</option>
                                @foreach($selfStates as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('stateId') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                District <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="districtId"
                                    class="w-full rounded-xl border {{ $errors->has('districtId') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select District —</option>
                                @foreach($selfDistricts as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('districtId') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Zone / Block
                            </label>
                            <select wire:model.live="zoneId"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Zone —</option>
                                @foreach($selfZones as $z)
                                    <option value="{{ $z->id }}">{{ $z->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Panchayat
                            </label>
                            <select wire:model.live="panchayatId"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Panchayat —</option>
                                @foreach($selfPanchayats as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Village
                            </label>
                            <select wire:model="villageId"
                                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Village —</option>
                                @foreach($selfVillages as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Plot & Crop Specifications --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
                        <i class="fas fa-map-marked-alt text-cyan-600"></i>
                        2. Field Plot & Crop Specifications
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Plot Name <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="plotName" type="text" placeholder="e.g. Main Apple Orchard"
                                   class="w-full rounded-xl border {{ $errors->has('plotName') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('plotName') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Khasra / Survey No <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="khasraNo" type="text" placeholder="Survey / Khasra number"
                                   class="w-full rounded-xl border {{ $errors->has('khasraNo') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('khasraNo') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Area in Kanals <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="areaKanals" type="number" step="0.01" placeholder="0.00"
                                   class="w-full rounded-xl border {{ $errors->has('areaKanals') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm font-mono text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('areaKanals') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Soil Type Classification <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="soilTypeId"
                                    class="w-full rounded-xl border {{ $errors->has('soilTypeId') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Soil Type —</option>
                                @foreach($selfSoilTypes as $st)
                                    <option value="{{ $st->id }}">{{ $st->name }}</option>
                                @endforeach
                            </select>
                            @error('soilTypeId') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Target Crop <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="cropId"
                                    class="w-full rounded-xl border {{ $errors->has('cropId') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Crop —</option>
                                @foreach($selfCrops as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('cropId') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Crop / Plant Age in Years <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="ageOfPlants" type="number" step="0.1" placeholder="e.g. 5"
                                   class="w-full rounded-xl border {{ $errors->has('ageOfPlants') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm font-mono text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('ageOfPlants') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" wire:click="resetForm"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Reset Form
                    </button>
                    <button type="button" wire:click="save"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Commit Intake & Generate Sample ID</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Intake Success Screen --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-8 text-center max-w-xl mx-auto space-y-4">
            <span class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-sm">
                <i class="fas fa-check"></i>
            </span>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Walk-in Sample Registered Successfully!</h3>
                <p class="text-xs text-gray-500 mt-1">
                    The sample has been logged into the laboratory system and is queued for testing.
                </p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-4">
                <button wire:click="resetForm"
                        class="inline-flex items-center gap-1.5 px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <i class="fas fa-plus text-xs"></i>
                    <span>Collect Another Sample</span>
                </button>
                <a href="{{ route('lab.dashboard') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                    <i class="fas fa-arrow-left text-xs text-gray-400"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </div>
    @endif
</div>
