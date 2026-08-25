<div>
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body py-3 border-bottom"><h6 class="mb-0">My Profile</h6></div>
        <div class="card-body">
            <form wire:submit="save">
                <div class="form-group">
                    <label>Name</label>
                    <input wire:model="name" type="text" class="form-control rounded-pill">
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input wire:model="email" type="email" class="form-control rounded-pill" disabled>
                </div>
                <hr>
                <h6>Change Password</h6>
                <div class="form-group">
                    <label>Current Password</label>
                    <input wire:model="currentPassword" type="password" class="form-control rounded-pill">
                    @error('currentPassword') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input wire:model="newPassword" type="password" class="form-control rounded-pill">
                    @error('newPassword') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input wire:model="newPasswordConfirmation" type="password" class="form-control rounded-pill">
                </div>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fas fa-save mr-1"></i> Save</button>
            </form>
        </div>
    </div>
</div>
