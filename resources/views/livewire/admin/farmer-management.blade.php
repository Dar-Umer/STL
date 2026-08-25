<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-tractor mr-2"></i>Farmers</h4>
        <button wire:click="create" class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus"></i> Add Farmer</button>
    </div>

    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-body">
            @if($showForm)
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label>Name</label><input wire:model="name" class="form-control rounded-pill">@error('name') <span class="text-danger">{{ $message }}</span> @enderror</div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Parentage</label><input wire:model="parentage" class="form-control rounded-pill"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Phone</label><input wire:model="phone" class="form-control rounded-pill">@error('phone') <span class="text-danger">{{ $message }}</span> @enderror</div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Aadhar No</label><input wire:model="aadhar_no" class="form-control rounded-pill">@error('aadhar_no') <span class="text-danger">{{ $message }}</span> @enderror</div>
                    </div>
                    @if($isLabManager ?? false)
                    <input type="hidden" wire:model="lab_id">
                    @else
                    <div class="col-md-4">
                        <div class="form-group"><label>Lab</label>
                            <select wire:model="lab_id" class="form-control rounded-pill">
                                <option value="">Select Lab</option>
                                @foreach($labs as $lab)
                                <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                                @endforeach
                            </select>
                            @error('lab_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <div class="form-group"><label>State</label>
                            <select wire:model.live="state_id" class="form-control rounded-pill">
                                <option value="">Select State</option>
                                @foreach($states as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('state_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>District</label>
                            <select wire:model.live="district_id" class="form-control rounded-pill">
                                <option value="">Select District</option>
                                @foreach($this->districts as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Zone</label>
                            <select wire:model.live="zone_id" class="form-control rounded-pill">
                                <option value="">Select Zone</option>
                                @foreach($this->zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Panchayat</label>
                            <select wire:model.live="panchayat_id" class="form-control rounded-pill">
                                <option value="">Select Panchayat</option>
                                @foreach($this->panchayats as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label>Village</label>
                            <select wire:model="village_id" class="form-control rounded-pill">
                                <option value="">Select Village</option>
                                @foreach($this->villages as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Save</button>
                <button type="button" wire:click="cancel" class="btn btn-secondary rounded-pill px-3">Cancel</button>
            </form>
            <hr>
            @endif

            <div class="mb-3"><input wire:model.live="search" placeholder="Search farmers..." class="form-control rounded-pill"></div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr class="small text-uppercase text-muted">
                            <th>Name</th><th>Phone</th><th>Lab</th><th>Location</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($farmers as $farmer)
                        <tr>
                            <td>{{ $farmer->name }}<br><small>{{ $farmer->parentage }}</small></td>
                            <td>{{ $farmer->phone }}</td>
                            <td>{{ $farmer->lab?->code }}</td>
                            <td>{{ $farmer->state?->name }} > {{ $farmer->district?->name }} > {{ $farmer->zone?->name }} > {{ $farmer->panchayat?->name }} > {{ $farmer->village?->name }}</td>
                            <td><button wire:click="edit({{ $farmer->id }})" class="btn btn-sm btn-info rounded-pill px-3 shadow-sm"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">{{ $farmers->links() }}</div>
        </div>
    </div>
</div>