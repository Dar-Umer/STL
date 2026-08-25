<div>
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <i class="fas fa-cog text-primary mr-2 fa-lg"></i>
            <h5 class="card-title mb-0 font-weight-bold">Application Settings</h5>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Application Name</label>
                            <input wire:model="appName" type="text" class="form-control form-control-lg border-0 bg-light rounded-pill">
                            <small class="text-muted">Used in browser title and sidebar brand</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Application Description</label>
                            <input wire:model="appDescription" type="text" class="form-control form-control-lg border-0 bg-light rounded-pill" placeholder="Brief description of this system">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Logo</label>
                            <div class="custom-file">
                                <input wire:model="logo" type="file" class="custom-file-input" id="logoUpload" accept="image/png,image/jpg,image/jpeg,image/svg+xml">
                                <label class="custom-file-label border-0 bg-light rounded-pill" for="logoUpload">
                                    {{ $logo ? $logo->getClientOriginalName() : ($logoPath ? basename($logoPath) : 'Choose image...') }}
                                </label>
                            </div>
                            <small class="text-muted">PNG, JPG or SVG. Max 1MB.</small>
                            @error('logo') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            @if($logoPath && !$logo)
                                <div class="mt-2 d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $logoPath) }}" style="max-height:48px;max-width:120px" class="border rounded p-1 mr-2">
                                    <button type="button" wire:click="removeLogo" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fas fa-trash mr-1"></i> Remove</button>
                                </div>
                            @endif
                            @if($logo)
                                <div class="mt-2 d-flex align-items-center">
                                    <img src="{{ $logo->temporaryUrl() }}" style="max-height:48px;max-width:120px" class="border rounded p-1">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Maintenance Mode</label>
                            <div class="d-flex align-items-center mt-2">
                                <div class="custom-control custom-switch">
                                    <input wire:model="maintenanceMode" type="checkbox" class="custom-control-input" id="maintenanceSwitch">
                                    <label class="custom-control-label font-weight-bold {{ $maintenanceMode ? 'text-danger' : 'text-success' }}" for="maintenanceSwitch">
                                        {{ $maintenanceMode ? 'Enabled — users cannot access the system' : 'Disabled — normal operation' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-secondary">Report Footer Text</label>
                    <textarea wire:model="reportFooter" class="form-control border-0 bg-light rounded" rows="3" placeholder="Optional footer text on PDF reports"></textarea>
                </div>

                <hr class="border-light">
                <h6 class="font-weight-bold text-info mb-3"><i class="fas fa-clock mr-2"></i> Overdue Sample Threshold</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Overdue After (Days)</label>
                            <input wire:model="overdueDays" type="number" min="1" max="365" class="form-control form-control-lg border-0 bg-light rounded-pill" placeholder="2">
                            <small class="text-muted">Samples in 'received' status exceeding this many days are flagged overdue on the dashboard.</small>
                            @error('overdueDays') <small class="text-danger d-block">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-light">
                <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-shield-alt mr-2"></i> Google reCAPTCHA</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Enable reCAPTCHA</label>
                            <div class="d-flex align-items-center mt-2">
                                <div class="custom-control custom-switch">
                                    <input wire:model="recaptchaEnabled" type="checkbox" class="custom-control-input" id="recaptchaSwitch">
                                    <label class="custom-control-label font-weight-bold {{ $recaptchaEnabled ? 'text-danger' : 'text-success' }}" for="recaptchaSwitch">
                                        {{ $recaptchaEnabled ? 'Enabled — shown on Login & Track pages (production only)' : 'Disabled' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Site Key</label>
                            <input wire:model="recaptchaSiteKey" type="text" class="form-control form-control-lg border-0 bg-light rounded-pill" placeholder="Google reCAPTCHA Site Key">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Secret Key</label>
                            <input wire:model="recaptchaSecretKey" type="text" class="form-control form-control-lg border-0 bg-light rounded-pill" placeholder="Google reCAPTCHA Secret Key">
                        </div>
                    </div>
                </div>

                <hr class="border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Settings are cached and apply immediately.</small>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
