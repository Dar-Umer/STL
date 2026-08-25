<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-clipboard-list mr-2"></i>Recommendation Rules</h4>
        @if(!$showForm)
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> New Rule</button>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($showForm)
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Rule Name <span class="text-danger">*</span></label>
                            <input wire:model="name" type="text" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Priority</label>
                            <input wire:model="priority" type="number" class="form-control @error('priority') is-invalid @enderror" min="0">
                            @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="custom-control custom-switch mt-2">
                                <input wire:model="is_active" type="checkbox" class="custom-control-input" id="activeSwitch">
                                <label class="custom-control-label" for="activeSwitch">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description (optional)</label>
                    <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="2"></textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr>
                <h6>Conditions <small class="text-muted">(all conditions must match)</small></h6>
                @error('conditions') <div class="text-danger small">{{ $message }}</div> @enderror

                @foreach($conditions as $index => $cond)
                <div class="card bg-light mb-2">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Condition {{ $index + 1 }}</strong>
                            <button type="button" wire:click="removeCondition({{ $index }})" class="btn btn-sm btn-outline-danger rounded-pill"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="small">Type</label>
                                    <select wire:model.live="conditions.{{ $index }}.type" class="form-control form-control-sm">
                                        <option value="parameter_value">Parameter Value</option>
                                        <option value="parameter_rating">Parameter Rating</option>
                                        <option value="plant_age">Plant Age</option>
                                        <option value="crop_is">Crop Is</option>
                                    </select>
                                </div>
                            </div>

                            @if(in_array($cond['type'] ?? '', ['parameter_value', 'parameter_rating']))
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="small">Parameter</label>
                                    <select wire:model="conditions.{{ $index }}.parameter_id" class="form-control form-control-sm">
                                        <option value="">Select</option>
                                        @foreach($parameters as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->unit }})</option>
                                        @endforeach
                                    </select>
                                    @error('conditions.'.$index.'.parameter_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            @endif

                            @if(in_array($cond['type'] ?? '', ['parameter_value', 'plant_age']))
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="small">Operator</label>
                                    <select wire:model="conditions.{{ $index }}.operator" class="form-control form-control-sm">
                                        <option value="gte">&ge; (greater or equal)</option>
                                        <option value="gt">&gt; (greater than)</option>
                                        <option value="lte">&le; (less or equal)</option>
                                        <option value="lt">&lt; (less than)</option>
                                        <option value="eq">= (equal)</option>
                                        <option value="between">Between</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="small">Value</label>
                                    <input wire:model="conditions.{{ $index }}.value" type="number" step="0.01" class="form-control form-control-sm" placeholder="e.g. 5.5">
                                    @error('conditions.'.$index.'.value') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            @if(($cond['operator'] ?? '') === 'between')
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="small">Max Value</label>
                                    <input wire:model="conditions.{{ $index }}.value_max" type="number" step="0.01" class="form-control form-control-sm" placeholder="Max">
                                </div>
                            </div>
                            @endif
                            @endif

                            @if(($cond['type'] ?? '') === 'parameter_rating')
                            <div class="col-md-2">
                                <div class="form-group mb-0">
                                    <label class="small">Rating</label>
                                    <select wire:model="conditions.{{ $index }}.rating" class="form-control form-control-sm">
                                        <option value="Low">Low</option>
                                        <option value="Medium">Medium</option>
                                        <option value="High">High</option>
                                    </select>
                                    @error('conditions.'.$index.'.rating') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            @endif

                            @if(($cond['type'] ?? '') === 'crop_is')
                            <div class="col-md-4">
                                <div class="form-group mb-0">
                                    <label class="small">Crop</label>
                                    <select wire:model="conditions.{{ $index }}.crop_id" class="form-control form-control-sm">
                                        <option value="">Select Crop</option>
                                        @foreach($crops as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('conditions.'.$index.'.crop_id') <div class="text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <button type="button" wire:click="addCondition" class="btn btn-sm btn-outline-primary rounded-pill px-3 mb-3"><i class="fas fa-plus"></i> Add Condition</button>

                <hr>
                <div class="form-group">
                    <label>Recommendation Text <span class="text-danger">*</span></label>
                    <textarea wire:model="recommendation" class="form-control @error('recommendation') is-invalid @enderror" rows="4" placeholder="Advice to show when conditions match..."></textarea>
                    @error('recommendation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="button" wire:click="cancel" class="btn btn-secondary rounded-pill px-3">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fas fa-save"></i> Save Rule</button>
                </div>
            </form>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th>Priority</th>
                            <th>Name</th>
                            <th>Conditions</th>
                            <th>Recommendation</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rules as $rule)
                        <tr>
                            <td>{{ $rule->priority }}</td>
                            <td><strong>{{ $rule->name }}</strong>
                                @if($rule->description)
                                <br><small class="text-muted">{{ $rule->description }}</small>
                                @endif
                            </td>
                            <td><small>{{ count($rule->conditions ?? []) }} condition(s)</small></td>
                            <td><small class="text-muted">{{ Str::limit($rule->recommendation, 80) }}</small></td>
                            <td>
                                <div class="custom-control custom-switch">
                                    <input wire:click="toggleActive({{ $rule->id }})" type="checkbox" class="custom-control-input" id="active{{ $rule->id }}" {{ $rule->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active{{ $rule->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <button wire:click="edit({{ $rule->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $rule->id }})" onclick="return confirm('Delete this rule?')" class="btn btn-sm btn-danger rounded-pill px-3"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">No rules yet. Click "New Rule" to create one.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $rules->links() }}
            @endif
        </div>
    </div>
</div>
