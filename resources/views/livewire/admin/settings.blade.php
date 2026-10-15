<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-sliders-h text-primary mr-2"></i>System Settings
            </h4>
            <p class="text-muted small mb-0">Manage global portal branding, maintenance mode, testing thresholds, and external services.</p>
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <i class="fas fa-cog text-primary mr-2"></i>
            <span class="font-weight-bold text-dark">Portal Configuration & Identity</span>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                {{-- Portal Identity --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Portal / Application Name <span class="text-danger">*</span></label>
                        <input wire:model="appName" type="text" class="form-control rounded-pill @error('appName') is-invalid @enderror" placeholder="Soil Lab">
                        <small class="text-muted">Used in page title, sidebar logo, and document exports.</small>
                        @error('appName') <small class="text-danger font-weight-medium d-block">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Application Tagline / Description</label>
                        <input wire:model="appDescription" type="text" class="form-control rounded-pill" placeholder="Soil Testing & Health Management System">
                        <small class="text-muted">Subtitle displayed on the portal login screen and reports.</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Brand Logo</label>
                        <div class="custom-file">
                            <input wire:model="logo" type="file" class="custom-file-input" id="logoUpload" accept="image/png,image/jpg,image/jpeg,image/svg+xml">
                            <label class="custom-file-label rounded-pill" for="logoUpload">
                                {{ $logo ? $logo->getClientOriginalName() : ($logoPath ? basename($logoPath) : 'Choose image...') }}
                            </label>
                        </div>
                        <small class="text-muted">PNG, JPG or SVG format. Recommended transparent background (Max 1MB).</small>
                        @error('logo') <small class="text-danger d-block font-weight-medium">{{ $message }}</small> @enderror
                        @if($logoPath && !$logo)
                            <div class="mt-2 d-flex align-items-center p-2 rounded bg-light border">
                                <img src="{{ asset('storage/' . $logoPath) }}" style="max-height:44px;max-width:140px;object-fit:contain;" class="mr-3">
                                <button type="button" wire:click="removeLogo" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="fas fa-trash mr-1"></i> Remove Logo
                                </button>
                            </div>
                        @endif
                        @if($logo)
                            <div class="mt-2 d-flex align-items-center p-2 rounded bg-light border">
                                <img src="{{ $logo->temporaryUrl() }}" style="max-height:44px;max-width:140px;object-fit:contain;" class="mr-2">
                                <span class="badge badge-success">New image ready to save</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">System Access State</label>
                        <div class="p-3 rounded-lg border {{ $maintenanceMode ? 'bg-light-danger border-danger' : 'bg-light-success border-success' }}" style="background: {{ $maintenanceMode ? '#fef2f2' : '#f0fdf4' }};">
                            <div class="custom-control custom-switch">
                                <input wire:model="maintenanceMode" type="checkbox" class="custom-control-input" id="maintenanceSwitch">
                                <label class="custom-control-label font-weight-bold {{ $maintenanceMode ? 'text-danger' : 'text-success' }}" for="maintenanceSwitch">
                                    {{ $maintenanceMode ? 'Maintenance Mode Active — users cannot access the system' : 'Live / Operational — all users can access normally' }}
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1">When enabled, regular users and farmers are shown a friendly maintenance screen.</small>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="small font-weight-bold text-dark">Report Footer Notice</label>
                    <textarea wire:model="reportFooter" class="form-control rounded-lg" rows="3" placeholder="Optional disclaimer or advisory text printed at the bottom of generated PDF soil test reports"></textarea>
                    <small class="text-muted">Appears at the footer of official soil test report certificates.</small>
                </div>

                <hr class="border-light my-4">

                {{-- Operational Thresholds --}}
                <div class="mb-4">
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-clock text-warning mr-2"></i>Operational Thresholds
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Overdue Sample Threshold (Days)</label>
                            <input wire:model="overdueDays" type="number" min="1" max="365" class="form-control rounded-pill @error('overdueDays') is-invalid @enderror" placeholder="2">
                            <small class="text-muted">Samples received in laboratory exceeding this duration without testing are flagged on dashboards.</small>
                            @error('overdueDays') <small class="text-danger font-weight-medium d-block">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-light my-4">

                {{-- Security & reCAPTCHA --}}
                <div class="mb-4">
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fas fa-shield-alt text-info mr-2"></i>Security & Verification
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Google reCAPTCHA v2</label>
                            <div class="p-3 rounded-lg border bg-light">
                                <div class="custom-control custom-switch">
                                    <input wire:model="recaptchaEnabled" type="checkbox" class="custom-control-input" id="recaptchaSwitch">
                                    <label class="custom-control-label font-weight-bold {{ $recaptchaEnabled ? 'text-primary' : 'text-muted' }}" for="recaptchaSwitch">
                                        {{ $recaptchaEnabled ? 'Enabled on Login & Public Forms' : 'Disabled' }}
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">Protects against automated bot requests (production only).</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Site Key</label>
                            <input wire:model="recaptchaSiteKey" type="text" class="form-control rounded-pill" placeholder="6Lc...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="small font-weight-bold text-dark">Secret Key</label>
                            <input wire:model="recaptchaSecretKey" type="text" class="form-control rounded-pill" placeholder="6Lc...">
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 border-top">
                    <small class="text-muted mb-2 mb-md-0"><i class="fas fa-info-circle mr-1"></i> Changes take effect immediately upon saving.</small>
                    <button type="submit" wire:loading.attr="disabled" class="btn btn-primary rounded-pill px-5 shadow-sm font-weight-medium">
                        <i class="fas fa-save mr-1" wire:loading.remove wire:target="save"></i>
                        <i class="fas fa-spinner fa-spin mr-1" wire:loading wire:target="save"></i>
                        <span>Save Settings</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
