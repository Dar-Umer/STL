<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-chart-bar text-primary mr-2"></i>Soil Test Parameters
            </h4>
            <p class="text-muted small mb-0">Configure chemical & physical test parameters, optimal ranges, color ratings, and lab assignments.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex align-items-center">
            <button wire:click="$toggle('showInactive')" class="btn btn-sm rounded-pill px-3 mr-2 {{ $showInactive ? 'btn-warning' : 'btn-outline-secondary' }}">
                <i class="fas fa-eye mr-1"></i> {{ $showInactive ? 'Hiding Inactive' : 'Show Inactive' }}
            </button>
            @if(!$showForm)
                <button wire:click="create" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> Add Parameter
                </button>
            @endif
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

    {{-- Form Card --}}
    @if($showForm)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 font-weight-bold text-dark">
                <i class="fas {{ $paramId ? 'fa-edit text-warning' : 'fa-plus text-success' }} mr-2"></i>
                {{ $paramId ? 'Edit Parameter: ' . $name : 'Define New Test Parameter' }}
            </h6>
            <button type="button" wire:click="cancel" class="close text-muted" aria-label="Close">&times;</button>
        </div>
        <div class="card-body p-4">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="small font-weight-bold text-dark">Parameter Name <span class="text-danger">*</span></label>
                        <input wire:model="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" placeholder="e.g. Available Nitrogen (N)">
                        @error('name') <small class="text-danger font-weight-medium">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-dark">Measurement Unit</label>
                        <input wire:model="unit" type="text" class="form-control rounded-pill" placeholder="e.g. kg/ha, ppm, %">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-dark">Display Order</label>
                        <input wire:model="order_column" type="number" class="form-control rounded-pill" placeholder="1">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="small font-weight-bold text-dark">Active</label>
                        <select wire:model="is_active" class="form-control rounded-pill">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="small font-weight-bold text-dark">Testing Method / Technique</label>
                        <input wire:model="method" type="text" class="form-control rounded-pill" placeholder="e.g. Alkaline Permanganate Method">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-dark">Min Range</label>
                        <input wire:model="min_range" type="number" step="0.01" class="form-control rounded-pill" placeholder="0">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="small font-weight-bold text-dark">Max Range</label>
                        <input wire:model="max_range" type="number" step="0.01" class="form-control rounded-pill" placeholder="1000">
                    </div>
                </div>

                {{-- Rating Thresholds & Labels --}}
                <div class="p-3 rounded-lg bg-light border mb-3">
                    <h6 class="font-weight-bold text-dark mb-3" style="font-size: 0.85rem;">
                        <i class="fas fa-sliders-h mr-1 text-primary"></i> Rating Thresholds, Labels & Indicator Colors
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">Low Max Threshold (&le;)</label>
                            <input wire:model="rating_low_max" type="number" step="0.01" class="form-control form-control-sm rounded-pill" placeholder="e.g. 280">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">Medium Max Threshold (&le;)</label>
                            <input wire:model="rating_medium_max" type="number" step="0.01" class="form-control form-control-sm rounded-pill" placeholder="e.g. 560">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">High Min Threshold (&ge;)</label>
                            <input wire:model="rating_high_min" type="number" step="0.01" class="form-control form-control-sm rounded-pill" placeholder="e.g. 560">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">Low Label</label>
                            <input wire:model="rating_label_low" type="text" class="form-control form-control-sm rounded-pill" placeholder="Deficient / Low">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">Medium Label</label>
                            <input wire:model="rating_label_medium" type="text" class="form-control form-control-sm rounded-pill" placeholder="Sufficient / Medium">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">High Label</label>
                            <input wire:model="rating_label_high" type="text" class="form-control form-control-sm rounded-pill" placeholder="High / Excess">
                        </div>

                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">Low Indicator Color</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-1 bg-white border-right-0 rounded-left" style="border-radius: 9999px 0 0 9999px;">
                                        <input wire:model="rating_color_low" type="color" style="width:24px;height:24px;border:none;cursor:pointer;background:transparent;">
                                    </span>
                                </div>
                                <input wire:model="rating_color_low" type="text" class="form-control form-control-sm border-left-0" style="border-radius: 0 9999px 9999px 0;">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">Medium Indicator Color</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-1 bg-white border-right-0 rounded-left" style="border-radius: 9999px 0 0 9999px;">
                                        <input wire:model="rating_color_medium" type="color" style="width:24px;height:24px;border:none;cursor:pointer;background:transparent;">
                                    </span>
                                </div>
                                <input wire:model="rating_color_medium" type="text" class="form-control form-control-sm border-left-0" style="border-radius: 0 9999px 9999px 0;">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted">High Indicator Color</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text p-1 bg-white border-right-0 rounded-left" style="border-radius: 9999px 0 0 9999px;">
                                        <input wire:model="rating_color_high" type="color" style="width:24px;height:24px;border:none;cursor:pointer;background:transparent;">
                                    </span>
                                </div>
                                <input wire:model="rating_color_high" type="text" class="form-control form-control-sm border-left-0" style="border-radius: 0 9999px 9999px 0;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Lab Assignments --}}
                <div class="mb-3">
                    <label class="small font-weight-bold text-dark">Assign to Testing Laboratories</label>
                    <select wire:model="selectedLabs" multiple class="form-control rounded-lg" style="min-height: 90px;">
                        @foreach($labs as $lab)
                            <option value="{{ $lab->id }}" class="py-1">{{ $lab->name }} ({{ $lab->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex align-items-center justify-content-end pt-3 border-top">
                    <button type="button" wire:click="cancel" class="btn btn-outline-secondary rounded-pill px-4 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> {{ $paramId ? 'Update Parameter' : 'Save Parameter' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Parameters Table Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="font-weight-bold text-dark mr-2"><i class="fas fa-list text-muted mr-2"></i>Parameter Catalog</span>
                <span class="badge badge-light border text-muted">{{ count($parameters) }} total</span>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Parameter</th>
                        <th>Unit</th>
                        <th>Expected Range</th>
                        <th>Classification Ratings</th>
                        <th>Assigned Labs</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parameters as $param)
                        <tr>
                            <td class="text-muted font-weight-bold">{{ $param->order_column }}</td>
                            <td>
                                <span class="font-weight-bold text-dark d-block">{{ $param->name }}</span>
                                @if($param->method)
                                    <small class="text-muted">{{ $param->method }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light border text-dark">{{ $param->unit ?: '—' }}</span>
                            </td>
                            <td class="small text-muted">
                                {{ $param->min_range }} &ndash; {{ $param->max_range }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center mb-1" style="gap: 4px;">
                                    <span class="badge" style="background: {{ $param->getRatingColor('low') }}; color: #fff; font-size: 0.7rem; padding: 2px 8px;">
                                        {{ $param->getRatingLabel('low') }}
                                    </span>
                                    <span class="badge" style="background: {{ $param->getRatingColor('medium') }}; color: #fff; font-size: 0.7rem; padding: 2px 8px;">
                                        {{ $param->getRatingLabel('medium') }}
                                    </span>
                                    <span class="badge" style="background: {{ $param->getRatingColor('high') }}; color: #fff; font-size: 0.7rem; padding: 2px 8px;">
                                        {{ $param->getRatingLabel('high') }}
                                    </span>
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">
                                    L&le;{{ $param->rating_low_max }} &bull; M&le;{{ $param->rating_medium_max }} &bull; H&ge;{{ $param->rating_high_min }}
                                </small>
                            </td>
                            <td>
                                @if($param->relationLoaded('labs') && $param->labs->count())
                                    <div class="d-flex flex-wrap" style="gap: 3px; max-width: 220px;">
                                        @foreach($param->labs as $lab)
                                            <span class="badge badge-light border text-info px-2 py-1" style="font-size: 0.7rem;">
                                                {{ $lab->code }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">All labs</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($param->is_active)
                                    <span class="badge badge-success px-3 py-1">Active</span>
                                @else
                                    <span class="badge badge-danger px-3 py-1">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button wire:click="edit({{ $param->id }})" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-none mr-1" title="Edit Parameter">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button wire:click="delete({{ $param->id }})" wire:confirm="Delete this parameter? Existing results will be preserved." class="btn btn-sm btn-outline-danger rounded-pill px-2 shadow-none" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-chart-bar fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark">No test parameters found</h6>
                                <p class="small text-muted mb-0">Click "Add Parameter" to configure soil chemical parameters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>