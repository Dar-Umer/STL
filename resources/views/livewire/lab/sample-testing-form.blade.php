<div>
    <script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('openPdf', ({ url }) => {
            window.open(url, '_blank');
        });
    });
    </script>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold"><i class="fas fa-flask text-primary mr-2"></i> Testing: <span class="text-primary">{{ $sample->sample_code }}</span></h4>
            <p class="text-muted mb-0 small">Enter test results and generate recommendations.</p>
        </div>
        <div>
            <a href="{{ route('lab.testing-list') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="fas fa-arrow-left mr-1"></i> Back to Testing List
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mr-3" style="width:36px;height:36px;">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0 font-weight-bold">Sample Information</h5>
                    <small class="text-muted">Details for {{ $sample->sample_code }}</small>
                </div>
            </div>
            <span class="badge badge-pill badge-info px-3 py-2">{{ $sample->status->label() }}</span>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 mb-2"><strong>Farmer:</strong> <span class="text-dark">{{ $sample->farmer->name ?? 'N/A' }}</span></div>
                <div class="col-md-3 mb-2"><strong>Plot:</strong> <span class="text-dark">{{ $sample->plot->plot_name ?? 'N/A' }}</span></div>
                <div class="col-md-3 mb-2"><strong>Collected:</strong> <span class="text-dark">{{ $sample->collected_at?->format('d/m/Y') }}</span></div>
                <div class="col-md-3 mb-2"><strong>Received:</strong> <span class="text-dark">{{ $sample->received_at?->format('d/m/Y') }}</span></div>
            </div>

            <form wire:submit="save">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light small text-uppercase text-muted">
                            <tr>
                                <th class="border-top-0 font-weight-bold py-3" style="width:40px">#</th>
                                <th class="border-top-0 font-weight-bold py-3">Parameter</th>
                                <th class="border-top-0 font-weight-bold py-3" style="width:70px">Unit</th>
                                <th class="border-top-0 font-weight-bold py-3" style="width:120px">Range</th>
                                <th class="border-top-0 font-weight-bold py-3" style="width:150px">Value</th>
                                <th class="border-top-0 font-weight-bold py-3" style="width:160px">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parameters as $param)
                            <tr class="border-bottom align-middle">
                                <td class="py-3 text-muted">{{ $param->order_column }}</td>
                                <td class="py-3 font-weight-bold">{{ $param->name }}</td>
                                <td class="py-3 text-muted small">{{ $param->unit }}</td>
                                <td class="py-3">
                                    @if($param->min_range !== null || $param->max_range !== null)
                                        <small class="text-muted">{{ $param->range }}</small>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <input wire:model.live="values.{{ $param->id }}" type="number" step="0.01"
                                            class="form-control form-control-sm @error('values.'.$param->id) is-invalid @enderror rounded-pill"
                                            style="width:110px"
                                            @if($param->min_range !== null) min="{{ $param->min_range }}" @endif
                                            @if($param->max_range !== null) max="{{ $param->max_range }}" @endif>
                                        @if(isset($rangeErrors[$param->id]))
                                        <i class="fas fa-exclamation-triangle text-warning ml-2" title="{{ $rangeErrors[$param->id] }}"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <select wire:model="ratings.{{ $param->id }}" class="form-control form-control-sm rounded-pill" style="width:130px">
                                            <option value="">— Auto —</option>
                                            <option value="Low">{{ $param->getRatingLabel('low') }}</option>
                                            <option value="Medium">{{ $param->getRatingLabel('medium') }}</option>
                                            <option value="High">{{ $param->getRatingLabel('high') }}</option>
                                        </select>
                                        @if($ratings[$param->id] ?? null)
                                        @php $tier = strtolower($ratings[$param->id]); @endphp
                                        <span class="ml-2 d-inline-block rounded-circle" style="width:14px;height:14px;background:{{ $param->getRatingColor($tier) }};border:1px solid rgba(0,0,0,.1)"></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-4">
                    <label class="font-weight-bold">Recommendations</label>
                    <textarea wire:model="recommendations" class="form-control rounded-lg" rows="3" placeholder="Enter recommendations for the farmer..."></textarea>
                    <button type="button" wire:click="generateRecommendations" class="btn btn-sm btn-info rounded-pill px-3 mt-2 shadow-sm">
                        <i class="fas fa-magic mr-1"></i> Auto-Generate
                    </button>
                </div>

                <div class="mt-4">
                    <button type="submit" onclick="return confirm('This will mark the sample as REPORTED and CANNOT be undone. Continue?');"
                        class="btn btn-success rounded-pill px-4 shadow-sm font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Results
                    </button>
                    <a href="{{ route('samples.index') }}" class="btn btn-outline-secondary rounded-pill px-4 ml-2">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .align-middle td, .align-middle th { vertical-align: middle !important; }
        .table th { border-top: 0; }
    </style>
</div>
