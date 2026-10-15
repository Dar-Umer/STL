<div class="container-fluid py-2">
    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="mb-1 font-weight-bold" style="color: #0f172a;">
                <i class="fas fa-hand-holding-heart text-primary mr-2"></i>Sample Collection Requests
            </h4>
            <p class="text-muted small mb-0">Public farmer requests for soil sampling, assignment to field officials, and status tracking.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <span class="badge badge-warning px-3 py-2 font-weight-bold">
                <i class="fas fa-clock mr-1"></i> {{ \App\Models\SampleRequest::where('status', 'pending')->count() }} Pending Requests
            </span>
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

    {{-- Requests Table Card --}}
    <div class="card border-0 shadow-sm">
        {{-- Filters Bar --}}
        <div class="card-header bg-white py-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-5 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0 rounded-left" style="border-radius: 9999px 0 0 9999px;">
                                <i class="fas fa-search text-muted small"></i>
                            </span>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text" 
                               class="form-control bg-light border-left-0" 
                               style="border-radius: 0 9999px 9999px 0;"
                               placeholder="Search by request ID, farmer name, phone...">
                    </div>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select wire:model.live="statusFilter" class="form-control rounded-pill bg-light border-0">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="assigned">Assigned</option>
                        <option value="collected">Collected</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Farmer Details</th>
                        <th>Contact Number</th>
                        <th>Zone / District</th>
                        <th>Request Status</th>
                        <th>Assigned Official</th>
                        <th>Date Submitted</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        @php
                            $statusBadgeClass = match($req->status) {
                                'collected' => 'badge-success',
                                'assigned' => 'badge-primary',
                                'cancelled' => 'badge-secondary',
                                default => 'badge-warning',
                            };
                        @endphp
                        <tr>
                            <td>
                                <span class="font-weight-bold text-dark font-monospace" style="letter-spacing: 0.05em;">
                                    {{ $req->request_id }}
                                </span>
                            </td>
                            <td>
                                <span class="font-weight-bold text-dark d-block">{{ $req->farmer_name }}</span>
                            </td>
                            <td>
                                <span class="small text-dark font-weight-medium">
                                    <i class="fas fa-phone-alt text-muted mr-1" style="font-size: 0.7rem;"></i>{{ $req->farmer_phone }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-dark">
                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>{{ $req->zone->name ?? '—' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $statusBadgeClass }} px-3 py-1">
                                    {{ ucfirst($req->status) }}
                                </span>
                                @if($req->soilSample)
                                    <a href="{{ route('lab.testing', $req->soilSample->id) }}" class="badge badge-light border text-info ml-1" title="View Associated Sample">
                                        #{{ $req->soilSample->sample_code }}
                                    </a>
                                @endif
                            </td>
                            <td class="small">
                                @if($req->fieldOfficial)
                                    <i class="fas fa-user-check text-success mr-1"></i>{{ $req->fieldOfficial->name }}
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $req->created_at->format('d M Y') }}
                            </td>
                            <td class="text-right">
                                @if(in_array($req->status, ['pending', 'assigned']))
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 dropdown-toggle shadow-none" type="button" data-toggle="dropdown">
                                        <i class="fas fa-user-plus mr-1"></i> Assign
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow-sm p-2" style="min-width: 200px;">
                                        <h6 class="dropdown-header px-2 py-1 small text-muted font-weight-bold">Assign Field Official</h6>
                                        @foreach($fieldOfficials as $fo)
                                            <a class="dropdown-item rounded py-1 px-2 small" href="#" wire:click.prevent="assign({{ $req->id }}, {{ $fo->id }})">
                                                <i class="fas fa-hard-hat text-info mr-2"></i>{{ $fo->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <button wire:click="cancelRequest({{ $req->id }})" class="btn btn-sm btn-outline-danger rounded-pill px-2 shadow-none ml-1" title="Cancel Request">
                                    <i class="fas fa-times"></i>
                                </button>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-hand-holding-heart fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                <h6 class="font-weight-bold text-dark">No sample requests found</h6>
                                <p class="small text-muted mb-0">Public sample collection requests will appear here as farmers submit them.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>
