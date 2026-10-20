<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                <span>Roles & Permissions</span>
            </h2>
            <p class="text-sm text-gray-500 mt-1">Manage system role designations, access tiers, and granular action authorization.</p>
        </div>
        <div>
            @if(!$showForm)
                <button wire:click="create" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add System Role
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
                    <i class="fas {{ $roleId ? 'fa-edit' : 'fa-plus' }}"></i>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $roleId ? 'Edit Role: ' . $name : 'Define New System Role' }}</h3>
                    <p class="text-xs text-gray-500">Assign role name and authorization capabilities</p>
                </div>
            </div>
            <button type="button" wire:click="cancel" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-xl hover:bg-gray-100 transition" title="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form wire:submit="save" class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">
                        Role Designation Name <span class="text-rose-500">*</span>
                    </label>
                    <input wire:model="name" type="text" placeholder="e.g. Quality Analyst"
                           class="w-full max-w-md rounded-xl border {{ $errors->has('name') ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200 bg-gray-50' }} px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                    @error('name') <p class="text-xs text-rose-600 font-medium mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 pb-2 border-b border-gray-100">
                        Assignable Permissions
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($allPermissions as $perm)
                            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200/80 bg-gray-50/50 hover:bg-white hover:border-emerald-300 transition cursor-pointer select-none">
                                <input type="checkbox" wire:model="permissions" value="{{ $perm->name }}"
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                <span class="text-xs font-semibold text-gray-800">{{ $perm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-100">
                    <button type="button" wire:click="cancel" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $roleId ? 'Update Role' : 'Create Role' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Roles Directory Table --}}
    <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="font-bold text-gray-900 text-sm">System Roles</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                    {{ count($roles) }} configured
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 w-56">Role Designation</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500">Associated Permissions</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 text-right w-36">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($roles as $role)
                        @php
                            $roleStyle = match($role->name) {
                                'Admin' => 'bg-rose-50 text-rose-700 border-rose-200',
                                'Lab Manager' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Lab Technician' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'Field Official' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default => 'bg-gray-50 text-gray-700 border-gray-200',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition group">
                            <td class="px-6 py-4 font-medium">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border {{ $roleStyle }} shadow-2xs">
                                    <i class="fas fa-shield-alt text-[10px]"></i>
                                    {{ $role->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($role->permissions->count())
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($role->permissions as $p)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[11px] font-medium bg-gray-100 text-gray-700 border border-gray-200/60">
                                                {{ $p->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">No explicit permissions assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button wire:click="edit({{ $role->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 hover:border-gray-300 rounded-lg shadow-xs transition active:scale-[0.98]" title="Edit Permissions">
                                        <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    @if($role->name !== 'Admin')
                                        <button wire:click="delete({{ $role->id }})" wire:confirm="Delete role '{{ $role->name }}'? Users assigned this role will lose its permissions." class="inline-flex items-center p-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 hover:bg-rose-50 rounded-lg transition active:scale-[0.98]" title="Delete Role">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>