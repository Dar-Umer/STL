@php
    $user = auth()->user();
    $roleName = $user->getRoleNames()->first() ?? 'User';
@endphp

<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-id-card text-success mr-2"></i>My Profile
            </h4>
            <p class="text-muted small mb-0">Manage your account credentials, contact information, and password.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-lg d-flex align-items-center mb-4">
        <i class="fas fa-check-circle fa-lg mr-3 text-success"></i>
        <div class="flex-grow-1">
            <span class="font-weight-medium">{{ session('success') }}</span>
        </div>
        <button type="button" class="close text-success" data-dismiss="alert">&times;</button>
    </div>
    @endif

    <div class="row">
        {{-- Left: User Overview Card --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle shadow-sm" 
                         style="width: 88px; height: 88px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; font-size: 2.2rem; font-weight: 700;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    
                    <h5 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    
                    <div class="mb-3">
                        <span class="badge badge-success px-3 py-1 font-weight-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            {{ $roleName }}
                        </span>
                        @if($user->is_active)
                            <span class="badge badge-light border text-success ml-1">
                                <i class="fas fa-circle mr-1" style="font-size: 0.55rem;"></i> Active
                            </span>
                        @endif
                    </div>

                    <div class="border-top pt-3 text-left">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small"><i class="fas fa-phone mr-2 text-secondary"></i> Phone</span>
                            <span class="font-weight-medium small text-dark">{{ $user->phone ?: 'Not provided' }}</span>
                        </div>
                        @if($user->lab)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small"><i class="fas fa-flask mr-2 text-info"></i> Assigned Lab</span>
                            <span class="font-weight-medium small text-dark">{{ $user->lab->name }}</span>
                        </div>
                        @endif
                        @if($user->zone)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted small"><i class="fas fa-map-marker-alt mr-2 text-danger"></i> Zone</span>
                            <span class="font-weight-medium small text-dark">{{ $user->zone->name }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted small"><i class="fas fa-calendar-alt mr-2 text-primary"></i> Joined</span>
                            <span class="font-weight-medium small text-dark">{{ $user->created_at?->format('d M Y') ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Profile Edit Form --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-user-edit mr-2 text-primary"></i>Edit Profile Details
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form wire:submit="save">
                        {{-- Personal Information --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted font-weight-bold mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                Personal Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Full Name <span class="text-danger">*</span></label>
                                    <input wire:model="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="Enter your full name">
                                    @error('name') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Email Address</label>
                                    <input wire:model="email" type="email" class="form-control rounded-pill bg-light" disabled>
                                    <small class="text-muted" style="font-size: 0.72rem;">Email address cannot be modified directly.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Phone Number</label>
                                    <input wire:model="phone" type="text" class="form-control rounded-pill @error('phone') is-invalid @enderror" placeholder="e.g. +91 9876543210">
                                    @error('phone') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="border-light my-4">

                        {{-- Security / Change Password --}}
                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="text-uppercase text-muted font-weight-bold mb-0" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                    Security & Password
                                </h6>
                                <span class="badge badge-light border text-muted">Leave blank to keep unchanged</span>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="small font-weight-bold text-dark">Current Password</label>
                                    <input wire:model="currentPassword" type="password" class="form-control rounded-pill @error('currentPassword') is-invalid @enderror" placeholder="Enter your current password">
                                    @error('currentPassword') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                                    <small class="text-muted d-block" style="font-size: 0.72rem;">Required only when changing your password.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">New Password</label>
                                    <input wire:model="newPassword" type="password" class="form-control rounded-pill @error('newPassword') is-invalid @enderror" placeholder="Min. 8 characters">
                                    @error('newPassword') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-dark">Confirm New Password</label>
                                    <input wire:model="newPasswordConfirmation" type="password" class="form-control rounded-pill" placeholder="Repeat new password">
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                            <button type="submit" wire:loading.attr="disabled" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="fas fa-save mr-1" wire:loading.remove wire:target="save"></i>
                                <i class="fas fa-spinner fa-spin mr-1" wire:loading wire:target="save"></i>
                                <span>Save Changes</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
