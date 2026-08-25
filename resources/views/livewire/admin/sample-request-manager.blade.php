<div>
    <div class="card border-0 shadow-sm rounded-lg">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <div>
                <i class="fas fa-clipboard-list text-primary mr-2 fa-lg"></i>
                <h5 class="card-title mb-0 font-weight-bold d-inline">Sample Collection Requests</h5>
            </div>
            <span class="badge badge-success">{{ \App\Models\SampleRequest::where('status', 'pending')->count() }} pending</span>
        </div>
        <div class="card-body p-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <input wire:model.live="search" type="text" class="form-control form-control-sm rounded-pill" placeholder="Search by ID, name, or phone...">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="statusFilter" class="form-control form-control-sm rounded-pill">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="assigned">Assigned</option>
                        <option value="collected">Collected</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Request ID</th>
                            <th>Farmer</th>
                            <th>Phone</th>
                            <th>Zone</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td><strong class="text-primary">{{ $req->request_id }}</strong></td>
                            <td>{{ $req->farmer_name }}</td>
                            <td>{{ $req->farmer_phone }}</td>
                            <td>{{ $req->zone->name ?? '-' }}</td>
                            <td>
                                @php $colors = ['pending' => 'warning', 'assigned' => 'info', 'collected' => 'success', 'cancelled' => 'secondary']; @endphp
                                <span class="badge badge-{{ $colors[$req->status] ?? 'light' }}">{{ ucfirst($req->status) }}</span>
                                @if($req->soilSample)
                                <a href="{{ route('lab.testing', $req->soilSample->id) }}" class="ml-1 small" title="View Sample">#{{ $req->soilSample->sample_code }}</a>
                                @endif
                            </td>
                            <td>{{ $req->fieldOfficial->name ?? '-' }}</td>
                            <td style="font-size:.78rem;">{{ $req->created_at->format('d M Y') }}</td>
                            <td>
                                @if(in_array($req->status, ['pending', 'assigned']))
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill dropdown-toggle" type="button" data-toggle="dropdown">
                                        Assign
                                    </button>
                                    <div class="dropdown-menu">
                                        @foreach($fieldOfficials as $official)
                                        <button class="dropdown-item small" wire:click="assignOfficer({{ $req->id }}, {{ $official->id }})">
                                            <i class="fas fa-user mr-1"></i> {{ $official->name }}
                                        </button>
                                        @endforeach
                                        @if($req->field_official_id)
                                        <div class="dropdown-divider"></div>
                                        <button class="dropdown-item small text-secondary" wire:click="assignOfficer({{ $req->id }}, null)">Unassign</button>
                                        @endif
                                    </div>
                                </div>
                                <button wire:click="cancelRequest({{ $req->id }})" wire:confirm="Are you sure you want to cancel this request?" class="btn btn-sm btn-outline-danger rounded-pill ml-1"><i class="fas fa-ban"></i></button>
                                @elseif($req->status === 'cancelled')
                                <span class="text-muted small">—</span>
                                @elseif($req->status === 'collected')
                                <a href="{{ $req->soilSample ? route('lab.testing', $req->soilSample->id) : '#' }}" class="btn btn-sm btn-outline-success rounded-pill"><i class="fas fa-eye"></i></a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
