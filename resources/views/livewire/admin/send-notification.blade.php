<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold"><i class="fas fa-bell text-primary mr-2"></i> Send Notification</h4>
            <p class="text-muted mb-0 small">Send a one-time notification to a field official.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body py-3 border-bottom">
            <h6 class="mb-0"><i class="fas fa-pen mr-2"></i> Compose Notification</h6>
        </div>
        <div class="card-body p-4">
            <form wire:submit="send">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Zone <small class="text-muted">(optional filter)</small></label>
                            <select wire:model.live="zoneId" class="form-control rounded-pill border-0 bg-light">
                                <option value="">All Zones</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="small font-weight-bold text-secondary">Field Official <span class="text-danger">*</span></label>
                            <select wire:model="userId" class="form-control rounded-pill border-0 bg-light">
                                <option value="">Select Field Official</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}){{ $user->zone_id ? ' — ' . ($user->zone?->name ?? '') : '' }}</option>
                                @endforeach
                            </select>
                            @error('userId') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-secondary">Title <span class="text-danger">*</span></label>
                    <input wire:model="title" type="text" class="form-control rounded-pill border-0 bg-light" placeholder="e.g. New Report Available" maxlength="255">
                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-secondary">Message <span class="text-danger">*</span></label>
                    <textarea wire:model="body" class="form-control border-0 bg-light rounded" rows="5" placeholder="Enter notification message..." maxlength="5000"></textarea>
                    <small class="text-muted">{{ strlen($body) }} / 5000 characters</small>
                    @error('body') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <hr class="border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> The notification will appear in the recipient's in-app notification list.</small>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="fas fa-paper-plane mr-1"></i> Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
