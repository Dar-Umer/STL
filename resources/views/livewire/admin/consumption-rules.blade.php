<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            <a href="{{ route('admin.inventory') }}" class="text-decoration-none mr-2">&larr;</a>
            Consumption Rules — {{ $chemical->name }}
            <small class="text-muted">({{ $chemical->sku }}, {{ $chemical->unit }})</small>
        </h5>
        <button class="btn btn-success rounded-pill shadow-sm" wire:click="save">
            <i class="fas fa-save"></i> Save Rules
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <p class="text-muted mb-3">
                Enable parameters and set how much of <strong>{{ $chemical->name }}</strong> is consumed per test.
            </p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th style="width:50px">Enable</th>
                            <th>Parameter</th>
                            <th>Unit</th>
                            <th style="width:200px">Consumption per Test ({{ $chemical->unit }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($parameters as $param)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input"
                                           wire:model="rules.{{ $param->id }}.enabled"
                                           id="param-{{ $param->id }}">
                                </td>
                                <td>
                                    <label for="param-{{ $param->id }}" class="mb-0">{{ $param->name }}</label>
                                </td>
                                <td>{{ $param->unit }}</td>
                                <td>
                                    <input type="number" step="0.01" min="0"
                                           class="form-control rounded-pill"
                                           wire:model="rules.{{ $param->id }}.amount"
                                           @if(!$rules[$param->id]['enabled']) disabled @endif>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
