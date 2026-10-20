<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>{{ $isLabManager ? 'Lab Personnel & Field Staff' : 'User Accounts' }}</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                @if($isLabManager)
                    Manage laboratory technicians and field collection officials assigned to <strong>{{ $managerLab?->name ?? 'your laboratory' }}</strong>.
                @else
                    Manage system users, laboratory personnel, field staff, and their role access.
                @endif
            </p>
        </div>
        <div>
            @if(!$showForm)
                <button wire:click="create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    <span>{{ $isLabManager ? 'Add Lab/Field Staff' : 'Add New User' }}</span>
                </button>
            @endif
        </div>
    </div>

    {{-- Success Notification --}}
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

    {{-- Error Notification --}}
    @if(session('error'))
    <div class="rounded-2xl bg-rose-50 border border-rose-200/80 p-4 flex items-center justify-between text-rose-800 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </span>
            <span class="text-sm font-semibold">{{ session('error') }}</span>
        </div>
        <button type="button" @click="$el.closest('div').remove()" class="text-rose-500 hover:text-rose-700 p-1 rounded-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- User Create / Edit Form Card --}}
    @if($showForm)
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden transition-all">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold shadow-xs">
                    <i class="fas {{ $userId ? 'fa-user-edit' : 'fa-user-plus' }}"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $userId ? 'Edit User: ' . $name : ($isLabManager ? 'Register New Lab / Field Staff' : 'Create New User Account') }}</h3>
                    <p class="text-xs text-gray-500">Configure profile details, role permissions, and operational assignment</p>
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
                        1. Identity &amp; Credentials
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="name" type="text" placeholder="e.g. Zahoor Ahmad"
                                   class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('name') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input wire:model="email" type="email" placeholder="user@soillab.gov.in"
                                   class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('email') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Password {{ $userId ? '(leave blank to keep current)' : '*' }}
                            </label>
                            <input wire:model="password" type="password" placeholder="{{ $userId ? '••••••••' : 'Min. 6 characters' }}"
                                   class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('password') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Phone Number
                            </label>
                            <input wire:model="phone" type="text" placeholder="+91 9876543210"
                                   class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                            @error('phone') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Role Assignment <span class="text-rose-500">*</span>
                            </label>
                            <select wire:model="role" class="w-full rounded-xl border {{ $errors->has('role') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">— Select Role —</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                            @if($isLabManager)
                                <p class="text-[11px] text-gray-400 mt-1">Managers can only manage Lab Technicians &amp; Field Officials</p>
                            @endif
                            @error('role') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Account Access Status
                            </label>
                            <select wire:model="is_active" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="1">Active Account</option>
                                <option value="0">Suspended / Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        2. Assignment (Lab &amp; Geographic Field Zone)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @if($isLabManager)
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Assigned Facility
                                </label>
                                <div class="px-4 py-2.5 bg-emerald-50/50 border border-emerald-200/80 rounded-xl text-sm font-semibold text-emerald-900 flex items-center justify-between">
                                    <span>{{ $managerLab?->name ?? 'Assigned Lab' }}</span>
                                    <span class="text-xs font-mono font-normal text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Code: {{ $managerLab?->code ?? '—' }}</span>
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1">Personnel are automatically attached to your laboratory facility</p>
                            </div>
                        @else
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                    Assigned Laboratory
                                </label>
                                <select wire:model="lab_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                    <option value="">No Lab Assigned</option>
                                    @foreach($labs as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->name }} ({{ $lab->code }})</option>
                                    @endforeach
                                </select>
                                <p class="text-[11px] text-gray-400 mt-1">For Lab Managers &amp; Testing Technicians</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                                Assigned Horticulture Zone
                            </label>
                            <select wire:model="zone_id" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                <option value="">No Zone Assigned</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1">Required for Field Collection Officials</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $userId ? 'Update User' : 'Save User' }}</span>
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
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name, email, phone..."
                   class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                {{ $users->total() }} {{ $isLabManager ? 'subordinate staff members' : 'accounts registered' }}
            </span>
        </div>
    </div>

    {{-- User Directory Table --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Personnel / User</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Contact Details</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">System Role</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Assigned Facility / Zone</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-center">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                        @php
                            $primaryRole = $u->roles->first()?->name ?? 'User';
                            $roleBadgeClass = match($primaryRole) {
                                'Admin' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'Lab Manager' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Lab Technician' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'Field Official' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                            };
                            $isSelf = auth()->id() === $u->id;
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs flex-shrink-0 shadow-xs">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-semibold text-gray-900 block group-hover:text-emerald-700 transition">
                                            {{ $u->name }}
                                            @if($isSelf)
                                                <span class="text-[10px] font-normal px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 ml-1">You</span>
                                            @endif
                                        </span>
                                        <span class="text-[11px] text-gray-400">ID: #{{ $u->id }} &bull; Added {{ $u->created_at?->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-medium text-gray-800">{{ $u->email }}</div>
                                @if($u->phone)
                                    <div class="text-[11px] text-gray-500 font-mono mt-0.5">{{ $u->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleBadgeClass }}">
                                    {{ $primaryRole }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if($u->lab)
                                    <span class="font-medium text-gray-800 block">{{ $u->lab->name }}</span>
                                    <span class="text-gray-400 text-[11px]">Lab Code: {{ $u->lab->code }}</span>
                                @elseif($u->zone)
                                    <span class="font-medium text-gray-800 block">{{ $u->zone->name }}</span>
                                    <span class="text-gray-400 text-[11px]">Field Zone</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($u->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Edit Button --}}
                                    <button wire:click="edit({{ $u->id }})" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 rounded-lg shadow-xs transition active:scale-[0.98]" 
                                            title="Edit Profile">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>

                                    {{-- Suspend / Activate Toggle Button --}}
                                    @if(!$isSelf)
                                        <button wire:click="toggleActive({{ $u->id }})" 
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg border transition active:scale-[0.98] {{ $u->is_active ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}" 
                                                title="{{ $u->is_active ? 'Suspend Account' : 'Activate Account' }}">
                                            <i class="fas {{ $u->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                    @endif

                                    {{-- Delete Button --}}
                                    @if(!$isSelf)
                                        <button wire:click="delete({{ $u->id }})" 
                                                wire:confirm="Are you sure you want to permanently delete user '{{ $u->name }}'?"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition active:scale-[0.98]" 
                                                title="Delete User">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center text-xl mb-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="text-sm font-semibold text-gray-900">
                                    {{ $isLabManager ? 'No subordinate personnel found' : 'No users found' }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1 max-w-sm mx-auto">
                                    {{ $isLabManager ? 'You can add laboratory technicians and field collection officials using the button above.' : 'Try adjusting your search criteria or register a new user.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>