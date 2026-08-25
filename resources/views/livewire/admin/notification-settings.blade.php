<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold"><i class="fas fa-fire text-primary mr-2"></i> Notification Settings</h4>
            <p class="text-muted mb-0 small">Configure automatic push notifications sent via Firebase to field officials' Android devices.</p>
        </div>
    </div>

    <form wire:submit="save">
        <div class="card border-0 shadow-sm rounded-lg mb-4">
            <div class="card-body py-3 border-bottom">
                <h6 class="mb-0"><i class="fas fa-toggle-on text-primary mr-2"></i> Auto-Notification Triggers</h6>
            </div>
            <div class="card-body p-4">
                @foreach($defaults as $key => $cfg)
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="mr-3 flex-grow-1">
                            <span class="font-weight-bold">{{ $cfg['label'] }}</span>
                            <p class="mb-1 text-muted small">{{ $cfg['desc'] }}</p>
                            <div class="form-group mb-0">
                                <textarea wire:model="triggers.{{ $key }}.message" class="form-control border-0 bg-light rounded" rows="2" placeholder="{{ $cfg['message'] }}"></textarea>
                                <small class="text-muted">Available variables: <code class="bg-light px-1 rounded">{{ $cfg['vars'] }}</code></small>
                            </div>
                        </div>
                        <div class="custom-control custom-switch mt-1">
                            <input wire:model="triggers.{{ $key }}.enabled" type="checkbox" class="custom-control-input" id="switch-{{ $key }}">
                            <label class="custom-control-label" for="switch-{{ $key }}"></label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-lg mb-4">
            <div class="card-body py-3 border-bottom">
                <h6 class="mb-0"><i class="fab fa-google text-danger mr-2"></i> Firebase Cloud Messaging (FCM v1)</h6>
            </div>
            <div class="card-body p-4">
                @if($storedServiceAccount)
                <div class="alert alert-info py-2 mb-3 small">
                    <i class="fas fa-check-circle mr-1"></i>
                    Service account configured:
                    @if($storedProjectId)<strong>Project:</strong> {{ $storedProjectId }}@endif
                    @if($storedEmail)&middot; <strong>Email:</strong> {{ $storedEmail }}@endif
                    <br>Paste a new JSON below to replace it.
                </div>
                @endif
                <div class="form-group">
                    <label class="small font-weight-bold text-secondary">Firebase Service Account JSON</label>
                    <textarea wire:model="serviceAccountJson" class="form-control border-0 bg-light rounded" rows="8" placeholder='Paste the contents of your Firebase service account JSON file here...'></textarea>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle mr-1"></i>
                        Go to Firebase Console &rarr; Project Settings &rarr; Service Accounts &rarr; "Generate new private key".
                        Paste the entire JSON content above. Stored encrypted.
                    </small>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm">
                <i class="fas fa-save mr-1"></i> Save Settings
            </button>
        </div>
    </form>
</div>
