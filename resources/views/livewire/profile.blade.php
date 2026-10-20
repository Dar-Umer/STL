@php
    $user = auth()->user();
    $roleName = $user->getRoleNames()->first() ?? 'User';
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                User Account &amp; Profile Settings
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Manage your personal details, operational contacts, and authentication security</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left: User Overview Card --}}
        <div class="lg:col-span-4">
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 p-6 flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center font-bold text-3xl shadow-sm mb-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>

                <div class="mt-3 flex items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">
                        {{ $roleName }}
                    </span>
                    @if($user->is_active)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-emerald-700 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Active
                    </span>
                    @endif
                </div>

                <div class="w-full border-t border-gray-100 mt-6 pt-5 space-y-3 text-left text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-400 font-medium">Contact Phone</span>
                        <span class="font-semibold text-gray-800">{{ $user->phone ?: 'Not provided' }}</span>
                    </div>

                    @if($user->lab)
                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-400 font-medium">Assigned Lab</span>
                        <span class="font-semibold text-gray-800">{{ $user->lab->name }}</span>
                    </div>
                    @endif

                    @if($user->zone)
                    <div class="flex items-center justify-between py-1 border-b border-gray-50">
                        <span class="text-gray-400 font-medium">Jurisdiction</span>
                        <span class="font-semibold text-gray-800">{{ $user->zone->name }}</span>
                    </div>
                    @endif

                    <div class="flex items-center justify-between py-1">
                        <span class="text-gray-400 font-medium">Joined System</span>
                        <span class="font-semibold text-gray-800">{{ $user->created_at?->format('d M Y') ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Edit Profile Form Card --}}
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Account Credentials &amp; Credentials</h2>
                    </div>
                </div>

                <form wire:submit="save" class="p-6 space-y-6">
                    {{-- Personal Information Section --}}
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Personal Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Full Name <span class="text-rose-500">*</span></label>
                                <input wire:model="name" type="text" placeholder="Enter your full name" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('name') border-rose-300 ring-2 ring-rose-100 @enderror">
                                @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Email Address</label>
                                <input wire:model="email" type="email" disabled class="w-full rounded-xl border border-gray-200 bg-gray-100 px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                                <p class="text-[11px] text-gray-400 mt-1">Email is locked for audit identity reasons.</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Phone Number</label>
                                <input wire:model="phone" type="text" placeholder="e.g. +91 9876543210" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('phone') border-rose-300 ring-2 ring-rose-100 @enderror">
                                @error('phone') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Security & Password Section --}}
                    <div class="border-t border-gray-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Change Password</h3>
                            <span class="text-[11px] text-gray-400">Leave blank if keeping existing password</span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Current Password</label>
                                <input wire:model="currentPassword" type="password" placeholder="Verify current credentials" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('currentPassword') border-rose-300 ring-2 ring-rose-100 @enderror">
                                @error('currentPassword') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">New Password</label>
                                    <input wire:model="newPassword" type="password" placeholder="Min. 8 characters" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition @error('newPassword') border-rose-300 ring-2 ring-rose-100 @enderror">
                                    @error('newPassword') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Confirm New Password</label>
                                    <input wire:model="newPasswordConfirmation" type="password" placeholder="Repeat new password" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5 flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]" wire:loading.attr="disabled" wire:target="save">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove wire:target="save"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24" wire:loading wire:target="save"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Save Profile Changes</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
