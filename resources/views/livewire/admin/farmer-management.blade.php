<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>Farmer Directory</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Registered agricultural beneficiaries, farm locations, and contact records.</p>
        </div>
        <div>
            @if(!$showForm)
                <button wire:click="create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Register Farmer
                </button>
            @endif
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

    {{-- Form Card --}}
    @if($showForm)
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-xs">
                    <i class="fas {{ $farmerId ? 'fa-edit' : 'fa-user-plus' }}"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $farmerId ? 'Edit Farmer: ' . $name : 'Register New Farmer' }}</h3>
                    <p class="text-xs text-gray-500">Beneficiary profile and geographic location assignment</p>
                </div>
            </div>
            <button type="button" wire:click="cancel" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition" title="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        1. Personal & Contact Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Farmer Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="name" type="text" placeholder="e.g. Ghulam Mohammad Lone"
                                   class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('name') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Parentage / Guardian Name
                            </label>
                            <input wire:model="parentage" type="text" placeholder="Father or guardian's name"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="phone" type="text" placeholder="+91 9876543210"
                                   class="w-full rounded-xl border {{ $errors->has('phone') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('phone') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="lg:col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Aadhaar Card Number <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="aadhar_no" type="text" maxlength="12" placeholder="12-digit Aadhaar number"
                                   class="w-full rounded-xl border {{ $errors->has('aadhar_no') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('aadhar_no') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        2. Laboratory & Geographical Assignment
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @if($isLabManager ?? false)
                            <input type="hidden" wire:model="lab_id">
                        @else
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Assigned Laboratory <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="lab_id" class="w-full rounded-xl border {{ $errors->has('lab_id') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                    <option value="">— Select Lab —</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                    @endforeach
                                </select>
                                @error('lab_id') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                State / UT <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="state_id" class="w-full rounded-xl border {{ $errors->has('state_id') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select State —</option>
                                @foreach($states as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('state_id') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                District <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model.live="district_id" class="w-full rounded-xl border {{ $errors->has('district_id') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select District —</option>
                                @foreach($this->districts as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Zone / Block
                            </label>
                            <select wire:model.live="zone_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Zone —</option>
                                @foreach($this->zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Panchayat
                            </label>
                            <select wire:model.live="panchayat_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Panchayat —</option>
                                @foreach($this->panchayats as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Village
                            </label>
                            <select wire:model="village_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Village —</option>
                                @foreach($this->villages as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $farmerId ? 'Update Farmer' : 'Register Farmer' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Search & Statistics Bar --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="w-full sm:max-w-md relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, phone, Aadhaar..."
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                {{ $farmers->total() }} beneficiaries registered
            </span>
        </div>
    </div>

    {{-- Farmer Directory Card --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Contact</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Assigned Lab</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Geographic Location</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($farmers as $farmer)
                        <tr class="hover:bg-gray-50/70 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs flex-shrink-0">
                                        {{ strtoupper(substr($farmer->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-semibold text-gray-900 block group-hover:text-emerald-700 transition">{{ $farmer->name }}</span>
                                        @if($farmer->parentage)
                                            <span class="text-xs text-gray-500">S/o {{ $farmer->parentage }}</span>
                                        @endif
                                        @if($farmer->aadhar_no)
                                            <span class="text-[11px] font-mono text-gray-400 block mt-0.5">UID: {{ substr($farmer->aadhar_no, 0, 4) . ' ' . substr($farmer->aadhar_no, 4, 4) . ' ' . substr($farmer->aadhar_no, 8) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-gray-700 font-medium font-mono text-xs">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $farmer->phone }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($farmer->lab)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $farmer->lab->code }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <div class="font-medium text-gray-900">{{ $farmer->district?->name ?? '—' }}</div>
                                @if($farmer->zone || $farmer->village)
                                    <div class="text-gray-400 text-[11px] mt-0.5">{{ $farmer->zone?->name }} {{ $farmer->village ? '· ' . $farmer->village->name : '' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="edit({{ $farmer->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 rounded-lg shadow-xs transition active:scale-[0.98]" title="Edit Farmer">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl mb-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900">No farmers found</h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">Try adjusting your search criteria or register a new beneficiary using the button above.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($farmers->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $farmers->links() }}
        </div>
        @endif
    </div>
</div>