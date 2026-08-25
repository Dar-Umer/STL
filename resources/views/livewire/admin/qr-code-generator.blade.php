<div>
    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body py-3 border-bottom"><h6 class="mb-0"><i class="fas fa-qrcode"></i> Generate QR Codes</h6></div>
                <div class="card-body">
                    <form wire:submit="generate">
                        @if(auth()->user()->hasRole('Admin'))
                        <div class="form-group">
                            <label>Lab</label>
                            <select wire:model="labId" class="form-control rounded-pill">
                                <option value="">Select Lab</option>
                                @foreach($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                @endforeach
                            </select>
                            @error('labId') <span class="text-danger"><br>{{ $message }}</span> @enderror
                        </div>
                        @endif

                        @if((auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Lab Manager')) && $zones->count())
                        <div class="form-group">
                            <label>Zone</label>
                            <select wire:model.live="zoneId" class="form-control rounded-pill">
                                <option value="">Select Zone</option>
                                @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" @if($zone->code === 'N/A') disabled class="text-muted" @endif>{{ $zone->name }} ({{ $zone->code ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            @error('zoneId') <span class="text-danger"><br>{{ $message }}</span> @enderror
                            <small class="text-muted">Select a zone to set the QR code prefix automatically</small>
                        </div>
                        @endif

                        <div class="form-group">
                            <label>Prefix</label>
                            @if(auth()->user()->hasRole('Admin'))
                            <input wire:model="prefix" class="form-control rounded-pill" placeholder="e.g. A, B, CSTL" maxlength="5">
                            <small class="text-muted">Max 5 characters</small>
                            @else
                            <input wire:model="prefix" class="form-control rounded-pill bg-light" style="cursor:not-allowed" readonly tabindex="-1">
                            <small class="text-muted">Zone code — auto-assigned</small>
                            @if($prefix === 'N/A')
                            <div class="text-danger small mt-1"><i class="fas fa-exclamation-circle"></i> No zone code assigned. Contact admin.</div>
                            @endif
                            @endif
                            @error('prefix') <span class="text-danger"><br>{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Number of QR Codes</label>
                            <input wire:model="count" type="number" class="form-control rounded-pill" min="1" max="500">
                            @error('count') <span class="text-danger"><br>{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-success btn-block rounded-pill shadow-sm" wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="fas fa-magic"></i> Generate & Print</span>
                                <span wire:loading><i class="fas fa-spinner fa-spin"></i> Generating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-lg">
                <div class="card-body py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-th"></i> QR Codes</h6>
                    <div>
                        @if(auth()->user()->hasRole('Admin'))
                        <select wire:model.live="labId" class="form-control form-control-sm rounded-pill d-inline-block" style="width:140px">
                            <option value="">All Labs</option>
                            @foreach($labs as $lab)
                            <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        <select wire:model.live="filter" class="form-control form-control-sm rounded-pill d-inline-block" style="width:130px">
                            <option value="">All Prefixes</option>
                            @foreach($prefixes as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($codes->count())
                <div class="card-header py-2 bg-light">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="custom-control custom-checkbox mr-2">
                            <input type="checkbox" class="custom-control-input" id="selectAll" wire:model.live="selectAll">
                            <label class="custom-control-label" for="selectAll">Select All</label>
                        </div>
                        <span class="text-muted small mr-2" style="font-size:13px">
                            {{ count($selectedIds) }} selected
                        </span>
                        @if(count($selectedIds))
                        <button type="button" class="btn btn-sm btn-primary" wire:click="printSelected">
                            <i class="fas fa-print"></i> Print Selected
                        </button>
                        @endif
                        @if(auth()->user()->hasRole('Admin') && count($selectedIds))
                        <button type="button" class="btn btn-sm btn-danger" wire:click="deleteSelected" onclick="return confirm('Delete {{ count($selectedIds) }} QR codes? This cannot be undone.')">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                        @endif
                    </div>
                </div>
                @endif

                <div class="card-body">
                    @if($codes->count())
                    <div class="row">
                        @foreach($codes as $code)
                        <div class="col-4 col-md-3 col-lg-2 mb-3">
                            <div class="card card-outline card-{{ $code->is_used ? 'danger' : 'success' }} h-100 text-center p-2 mb-0 position-relative" style="border-width:2px">
                                <div class="custom-control custom-checkbox position-absolute" style="top:4px;left:4px;z-index:2">
                                    <input type="checkbox" class="custom-control-input" id="code-{{ $code->id }}" value="{{ $code->id }}" wire:model.live="selectedIds">
                                    <label class="custom-control-label" for="code-{{ $code->id }}"></label>
                                </div>
                                <div class="d-flex justify-content-center align-items-center" style="min-height:60px">
                                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(60)->errorCorrection('H')->generate($code->code) !!}
                                </div>
                                <span class="d-block font-weight-bold font-monospace mt-1" style="font-size:13px">{{ $code->code }}</span>
                                <div class="mt-1">
                                    @if($code->is_used)
                                        <span class="badge badge-danger"><i class="fas fa-check-circle"></i> Used</span>
                                    @else
                                        <span class="badge badge-success"><i class="fas fa-circle"></i> Available</span>
                                    @endif
                                </div>
                                <a href="{{ route(auth()->user()->hasRole('Admin') ? 'admin.qr-codes.print' : 'field.qr-codes.print', ['prefix' => $code->prefix] + (auth()->user()->hasRole('Admin') ? ['lab_id' => $code->lab_id] : [])) }}" class="btn btn-sm btn-outline-secondary btn-block mt-1" target="_blank" title="Print all {{ $code->prefix }} codes">
                                    <i class="fas fa-print"></i> Print
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted text-center py-4 mb-0">No QR codes generated yet.</p>
                    @endif
                </div>
                @if($codes->hasPages())
                <div class="card-footer">
                    {{ $codes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>