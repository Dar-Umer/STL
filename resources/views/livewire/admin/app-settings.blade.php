<div>
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <i class="fas fa-mobile-alt text-info mr-2 fa-lg"></i>
            <h5 class="card-title mb-0 font-weight-bold">App Settings</h5>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-pill py-2 small" role="alert">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                <h6 class="font-weight-bold text-info mb-3"><i class="fas fa-mobile-alt mr-2"></i> Mobile App Version</h6>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">App Version</label>
                            <input wire:model="appVersion" type="text" class="form-control form-control-lg border-0 bg-light rounded-pill" placeholder="e.g. 1.0.0">
                            <small class="text-muted">Minimum required version for Flutter app</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">App Update URL</label>
                            <input wire:model="appUpdateUrl" type="url" class="form-control form-control-lg border-0 bg-light rounded-pill" placeholder="https://example.com/update">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Force Update</label>
                            <div class="d-flex align-items-center mt-2">
                                <div class="custom-control custom-switch">
                                    <input wire:model="forceUpdate" type="checkbox" class="custom-control-input" id="forceUpdateSwitch">
                                    <label class="custom-control-label font-weight-bold {{ $forceUpdate ? 'text-danger' : 'text-success' }}" for="forceUpdateSwitch">
                                        {{ $forceUpdate ? 'Enabled — old versions blocked' : 'Disabled — users may keep old version' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-light">

                <h6 class="font-weight-bold text-info mb-3"><i class="fas fa-clipboard-list mr-2"></i> Sample Collection Guide</h6>
                <div class="form-group">
                    <label class="small font-weight-bold text-secondary">Guide Text (Markdown-style, shown in Field Official app)</label>
                    <textarea wire:model="collectionGuide" class="form-control border-0 bg-light rounded" rows="6" placeholder="Step 1: Enter the farmer details&#10;Step 2: Select or create the plot&#10;Step 3: Scan the QR code&#10;Step 4: Confirm collection"></textarea>
                </div>

                <hr class="border-light">

                <h6 class="font-weight-bold text-info mb-3"><i class="fas fa-bullhorn mr-2"></i> Field App Announcements</h6>
                <div class="form-group">
                    <label class="small font-weight-bold text-secondary">Announcements (shown in Field Official mobile app)</label>
                    @foreach($appAnnouncements as $index => $announcement)
                    <div class="input-group input-group-sm mb-2">
                        <input wire:model="appAnnouncements.{{ $index }}" type="text" class="form-control border-0 bg-light rounded-pill" placeholder="Announcement text...">
                        <div class="input-group-append ml-1">
                            <button wire:click="removeAppAnnouncement({{ $index }})" class="btn btn-outline-danger btn-sm rounded-pill" type="button" title="Remove">&times;</button>
                        </div>
                    </div>
                    @endforeach
                    <button wire:click="addAppAnnouncement" type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 mt-1"><i class="fas fa-plus mr-1"></i> Add Announcement</button>
                </div>

                <hr class="border-light">

                <h6 class="font-weight-bold text-info mb-3"><i class="fas fa-clock mr-2"></i> Working Hours</h6>
                <div class="form-group">
                    <div class="d-flex align-items-center mb-3">
                        <div class="custom-control custom-switch">
                            <input wire:model="workingHoursEnabled" type="checkbox" class="custom-control-input" id="workingHoursSwitch">
                            <label class="custom-control-label font-weight-bold {{ $workingHoursEnabled ? 'text-success' : 'text-secondary' }}" for="workingHoursSwitch">
                                {{ $workingHoursEnabled ? 'Enabled — sample collection restricted to working hours' : 'Disabled — collection available anytime' }}
                            </label>
                        </div>
                    </div>
                </div>
                @if($workingHoursEnabled)
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Start Time</label>
                            <input wire:model="workStartTime" type="time" class="form-control form-control-lg border-0 bg-light rounded-pill">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">End Time</label>
                            <input wire:model="workEndTime" type="time" class="form-control form-control-lg border-0 bg-light rounded-pill">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="small font-weight-bold text-secondary mb-2">Working Days</label>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $days = [
                                0 => 'Sun', 1 => 'Mon', 2 => 'Tue', 3 => 'Wed',
                                4 => 'Thu', 5 => 'Fri', 6 => 'Sat'
                            ];
                        @endphp
                        @foreach($days as $num => $label)
                            <button type="button"
                                wire:click="toggleWorkingDay({{ $num }})"
                                class="btn btn-sm rounded-pill px-3 font-weight-bold {{ in_array($num, $workingDays) ? 'btn-success' : 'btn-outline-secondary' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    <small class="text-muted mt-1 d-block">Sample collection is disabled outside these hours.</small>
                </div>
                @endif

                <hr class="border-light">
                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Changes apply immediately.</small>
                    <button type="submit" class="btn btn-info rounded-pill px-5 shadow-sm font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save App Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
