<div>
    <style>
        .fm-section{background:#fff;border-radius:10px;border:1px solid #e2e6ea;margin-bottom:14px;overflow:hidden;transition:box-shadow .15s;}
        .fm-section:hover{box-shadow:0 2px 12px rgba(0,0,0,.04);}
        .fm-section .fm-header{padding:11px 16px;cursor:pointer;display:flex;align-items:center;gap:10px;user-select:none;background:#f8f9fb;border-left:3px solid #018840;transition:background .15s;}
        .fm-section .fm-header:hover{background:#eef0f2;}
        .fm-badge{width:24px;height:24px;border-radius:50%;background:#018840;color:#fff;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;flex-shrink:0;}
        .fm-title{font-size:.8rem;font-weight:700;color:#1a2c3e;flex:1;}
        .fm-toggle{color:#94a3b8;font-size:.68rem;transition:transform .2s;}
        .fm-section.fm-collapsed .fm-toggle{transform:rotate(-90deg);}
        .fm-section.fm-collapsed .fm-body{display:none;}
        .fm-body{padding:16px 16px 12px;border-top:1px solid #eef0f2;}
        .fm-body .fm-subhead{font-size:.75rem;font-weight:700;color:#475569;padding-bottom:8px;margin-bottom:10px;border-bottom:1px dashed #e2e6ea;display:flex;align-items:center;gap:6px;}
        .fm-save-bar{position:sticky;bottom:0;background:rgba(255,255,255,.92);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid #e2e6ea;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;border-radius:10px;margin-top:20px;z-index:99;box-shadow:0 -4px 20px rgba(0,0,0,.04);}
        .fm-label{font-size:.72rem;font-weight:600;color:#475569;margin-bottom:2px;}
        .fm-input{font-size:.82rem;border-radius:8px;border:1px solid #e2e6ea;background:#fff;padding:7px 12px;transition:border-color .15s,box-shadow .15s;}
        .fm-input:focus{border-color:#018840;box-shadow:0 0 0 3px rgba(1,136,64,.12);outline:0;}
        .fm-textarea{font-size:.82rem;border-radius:8px;border:1px solid #e2e6ea;background:#fff;padding:7px 12px;transition:border-color .15s,box-shadow .15s;}
        .fm-textarea:focus{border-color:#018840;box-shadow:0 0 0 3px rgba(1,136,64,.12);outline:0;}
        .fm-btn-sm{border-radius:8px;font-size:.76rem;padding:4px 12px;}
        .fm-btn-outline-primary{color:#018840;border:1px solid #018840;background:transparent;border-radius:8px;font-size:.76rem;padding:4px 12px;cursor:pointer;}
        .fm-btn-outline-primary:hover{background:#018840;color:#fff;}
        .fm-btn-outline-danger{color:#dc3545;border:1px solid #dc3545;background:transparent;border-radius:8px;font-size:.76rem;padding:4px 12px;cursor:pointer;}
        .fm-btn-outline-danger:hover{background:#dc3545;color:#fff;}
        .header-bar{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#fff;border:1px solid #e2e6ea;border-radius:10px;margin-bottom:16px;}
        .header-bar h5{margin:0;font-weight:700;font-size:1rem;display:flex;align-items:center;gap:8px;}
        .header-bar .section-count{font-size:.7rem;color:#94a3b8;font-weight:400;}
        .fm-body .row{margin-bottom:6px;}
        .fm-body .form-group{margin-bottom:10px;}
        .fm-input-file{font-size:.82rem;}
        .fm-input-file .custom-file-label{border-radius:8px;border:1px solid #e2e6ea;padding:7px 12px;font-size:.82rem;}
        .fm-input-file .custom-file-input:focus~.custom-file-label{border-color:#018840;box-shadow:0 0 0 3px rgba(1,136,64,.12);}
        .fm-photo-preview{max-height:64px;max-width:120px;border-radius:6px;border:1px solid #e2e6ea;}
        @media(max-width:767px){.header-bar{flex-wrap:wrap;gap:10px;}.header-bar .btn-group{width:100%;}.header-bar .btn-group .btn{flex:1;}}
    </style>

    <div class="header-bar">
        <div>
            <h5><i class="fas fa-home text-primary mr-1"></i> Front Page Manager <span class="section-count">— 12 sections</span></h5>
        </div>
        <div class="btn-group btn-group-sm">
            <button type="button" id="fmExpandAll" class="btn btn-outline-secondary rounded-pill px-3"><i class="fas fa-expand-alt mr-1"></i> Expand All</button>
            <button type="button" id="fmCollapseAll" class="btn btn-outline-secondary rounded-pill px-3 ml-2"><i class="fas fa-compress-alt mr-1"></i> Collapse All</button>
        </div>
    </div>

    <form wire:submit="save">
        @if(session()->has('success'))
            <div class="alert alert-success py-2 rounded-lg mb-3 small"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>
        @endif

        {{-- SECTION 1: HEADER --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">1</span><span class="fm-title"><i class="fas fa-header mr-1"></i> Header — Government Branding</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label class="fm-label">Ministry / Government Name</label><input wire:model="headerMinistry" type="text" class="form-control fm-input" placeholder="Government of Jammu & Kashmir (UT)"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label class="fm-label">Department (English)</label><input wire:model="headerDeptEn" type="text" class="form-control fm-input" placeholder="Horticulture Department"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Department (Urdu)</label><input wire:model="headerDeptUr" type="text" class="form-control fm-input" placeholder="محکمہ باغبانی" dir="rtl"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Department (Hindi)</label><input wire:model="headerDeptHi" type="text" class="form-control fm-input" placeholder="बागवानी विभाग, जम्मू एवं कश्मीर"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fm-label">Emblem Image</label>
                            <div class="custom-file fm-input-file">
                                <input wire:model="headerEmblem" type="file" class="custom-file-input" id="headerEmblemUpload" accept="image/png,image/jpg,image/jpeg,image/svg+xml">
                                <label class="custom-file-label" for="headerEmblemUpload">{{ $headerEmblem ? $headerEmblem->getClientOriginalName() : ($headerEmblemPath ? basename($headerEmblemPath) : 'Choose image...') }}</label>
                            </div>
                            <small class="text-muted">PNG/JPG/SVG, max 1MB.</small>
                            @error('headerEmblem') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <div class="mt-2 d-flex align-items-center gap-2" style="gap:8px;">
                                @if($headerEmblemPath && !$headerEmblem)
                                <img src="{{ asset('storage/' . $headerEmblemPath) }}" class="fm-photo-preview mr-2">
                                <button type="button" wire:click="removeHeaderEmblem" class="fm-btn-outline-danger"><i class="fas fa-trash mr-1"></i> Remove</button>
                                @endif
                                @if($headerEmblem)
                                <img src="{{ $headerEmblem->temporaryUrl() }}" class="fm-photo-preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="fm-label">Flag Image</label>
                            <div class="custom-file fm-input-file">
                                <input wire:model="headerFlag" type="file" class="custom-file-input" id="headerFlagUpload" accept="image/png,image/jpg,image/jpeg,image/svg+xml">
                                <label class="custom-file-label" for="headerFlagUpload">{{ $headerFlag ? $headerFlag->getClientOriginalName() : ($headerFlagPath ? basename($headerFlagPath) : 'Choose image...') }}</label>
                            </div>
                            <small class="text-muted">PNG/JPG/SVG, max 1MB.</small>
                            @error('headerFlag') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <div class="mt-2 d-flex align-items-center gap-2" style="gap:8px;">
                                @if($headerFlagPath && !$headerFlag)
                                <img src="{{ asset('storage/' . $headerFlagPath) }}" class="fm-photo-preview mr-2">
                                <button type="button" wire:click="removeHeaderFlag" class="fm-btn-outline-danger"><i class="fas fa-trash mr-1"></i> Remove</button>
                                @endif
                                @if($headerFlag)
                                <img src="{{ $headerFlag->temporaryUrl() }}" class="fm-photo-preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 2: NOTICE BAR --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">2</span><span class="fm-title"><i class="fas fa-bullhorn mr-1"></i> Announcement / Notice Bar</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group"><label class="fm-label">Label</label><input wire:model="noticeLabel" type="text" class="form-control fm-input" placeholder="Notice"></div>
                    </div>
                    <div class="col-md-9">
                        <label class="fm-label">Notices</label>
                        @foreach($notices as $index => $notice)
                        <div class="input-group input-group-sm mb-2">
                            <input wire:model="notices.{{ $index }}" type="text" class="form-control fm-input" placeholder="Announcement text...">
                            <div class="input-group-append ml-1">
                                <button wire:click="removeNotice({{ $index }})" class="btn btn-outline-danger fm-btn-sm" type="button" title="Remove">&times;</button>
                            </div>
                        </div>
                        @endforeach
                        <button wire:click="addNotice" class="fm-btn-outline-primary mt-1" type="button"><i class="fas fa-plus mr-1"></i> Add Notice</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: HERO BANNER --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">3</span><span class="fm-title"><i class="fas fa-image mr-1"></i> Hero Banner</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group"><label class="fm-label">Title</label><input wire:model="heroTitle" type="text" class="form-control fm-input" placeholder="Main heading on the banner"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Button Text</label><input wire:model="heroBtnText" type="text" class="form-control fm-input" placeholder="Download Your Report"></div>
                    </div>
                </div>
                <div class="form-group"><label class="fm-label">Subtitle</label><textarea wire:model="heroSubtitle" class="form-control fm-textarea" rows="2" placeholder="Descriptive text below the heading"></textarea></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-0"><label class="fm-label">Button URL</label><input wire:model="heroBtnUrl" type="text" class="form-control fm-input" placeholder="/track-report"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="fm-label">Hero Image 1</label>
                            <div class="custom-file fm-input-file">
                                <input wire:model="heroPhoto" type="file" class="custom-file-input" id="heroPhotoUpload" accept="image/png,image/jpg,image/jpeg">
                                <label class="custom-file-label" for="heroPhotoUpload">{{ $heroPhoto ? $heroPhoto->getClientOriginalName() : ($heroPhotoPath ? basename($heroPhotoPath) : 'Choose image...') }}</label>
                            </div>
                            <small class="text-muted">PNG/JPG, max 2MB. 480×280 recommended.</small>
                            @error('heroPhoto') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <div class="mt-2 d-flex align-items-center gap-2" style="gap:8px;">
                                @if($heroPhotoPath && !$heroPhoto)
                                <img src="{{ asset('storage/' . $heroPhotoPath) }}" class="fm-photo-preview mr-2">
                                <button type="button" wire:click="removeHeroPhoto" class="fm-btn-outline-danger"><i class="fas fa-trash mr-1"></i> Remove</button>
                                @endif
                                @if($heroPhoto)
                                <img src="{{ $heroPhoto->temporaryUrl() }}" class="fm-photo-preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="fm-label">Hero Image 2</label>
                            <div class="custom-file fm-input-file">
                                <input wire:model="heroPhoto2" type="file" class="custom-file-input" id="heroPhotoUpload2" accept="image/png,image/jpg,image/jpeg">
                                <label class="custom-file-label" for="heroPhotoUpload2">{{ $heroPhoto2 ? $heroPhoto2->getClientOriginalName() : ($heroPhotoPath2 ? basename($heroPhotoPath2) : 'Choose image...') }}</label>
                            </div>
                            <small class="text-muted">PNG/JPG, max 2MB.</small>
                            @error('heroPhoto2') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <div class="mt-2 d-flex align-items-center gap-2" style="gap:8px;">
                                @if($heroPhotoPath2 && !$heroPhoto2)
                                <img src="{{ asset('storage/' . $heroPhotoPath2) }}" class="fm-photo-preview mr-2">
                                <button type="button" wire:click="removeHeroPhoto2" class="fm-btn-outline-danger"><i class="fas fa-trash mr-1"></i> Remove</button>
                                @endif
                                @if($heroPhoto2)
                                <img src="{{ $heroPhoto2->temporaryUrl() }}" class="fm-photo-preview">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="fm-label">Hero Image 3</label>
                            <div class="custom-file fm-input-file">
                                <input wire:model="heroPhoto3" type="file" class="custom-file-input" id="heroPhotoUpload3" accept="image/png,image/jpg,image/jpeg">
                                <label class="custom-file-label" for="heroPhotoUpload3">{{ $heroPhoto3 ? $heroPhoto3->getClientOriginalName() : ($heroPhotoPath3 ? basename($heroPhotoPath3) : 'Choose image...') }}</label>
                            </div>
                            <small class="text-muted">PNG/JPG, max 2MB.</small>
                            @error('heroPhoto3') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <div class="mt-2 d-flex align-items-center gap-2" style="gap:8px;">
                                @if($heroPhotoPath3 && !$heroPhoto3)
                                <img src="{{ asset('storage/' . $heroPhotoPath3) }}" class="fm-photo-preview mr-2">
                                <button type="button" wire:click="removeHeroPhoto3" class="fm-btn-outline-danger"><i class="fas fa-trash mr-1"></i> Remove</button>
                                @endif
                                @if($heroPhoto3)
                                <img src="{{ $heroPhoto3->temporaryUrl() }}" class="fm-photo-preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 4: CHO --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">4</span><span class="fm-title"><i class="fas fa-user-tie mr-1"></i> Chief Horticulture Officer — Pulwama</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Designation Title</label><input wire:model="choTitle" type="text" class="form-control fm-input" placeholder="Chief Horticulture Officer"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Officer Name</label><input wire:model="choName" type="text" class="form-control fm-input" placeholder="Shri. Name"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Post / District</label><input wire:model="choPost" type="text" class="form-control fm-input" placeholder="Pulwama District"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="fm-label">Photo</label>
                            <div class="custom-file fm-input-file">
                                <input wire:model="choPhoto" type="file" class="custom-file-input" id="choPhotoUpload" accept="image/png,image/jpg,image/jpeg">
                                <label class="custom-file-label" for="choPhotoUpload">{{ $choPhoto ? $choPhoto->getClientOriginalName() : ($choPhotoPath ? basename($choPhotoPath) : 'Choose image...') }}</label>
                            </div>
                            <small class="text-muted">PNG/JPG, max 1MB.</small>
                            @error('choPhoto') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <div class="mt-2 d-flex align-items-center gap-2" style="gap:8px;">
                                @if($choPhotoPath && !$choPhoto)
                                <img src="{{ asset('storage/' . $choPhotoPath) }}" class="fm-photo-preview mr-2">
                                <button type="button" wire:click="removeChoPhoto" class="fm-btn-outline-danger"><i class="fas fa-trash mr-1"></i> Remove</button>
                                @endif
                                @if($choPhoto)
                                <img src="{{ $choPhoto->temporaryUrl() }}" class="fm-photo-preview">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group"><label class="fm-label">Description / Message</label><textarea wire:model="choDescription" class="form-control fm-textarea" rows="3" placeholder="Message from the CHO"></textarea></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Phone</label><input wire:model="choPhone" type="text" class="form-control fm-input" placeholder="+91-XXXX-XXXXXX"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Email</label><input wire:model="choEmail" type="email" class="form-control fm-input" placeholder="cho.pulwama@jk.gov.in"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-0"><label class="fm-label">Office Address</label><input wire:model="choAddress" type="text" class="form-control fm-input" placeholder="Mini Secretariat, Pulwama"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 5: UPDATES + SERVICES --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">5</span><span class="fm-title"><i class="fas fa-layer-group mr-1"></i> Main Content — Updates &amp; Services</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                {{-- Sub: Updates --}}
                <div class="fm-subhead"><i class="fas fa-clock" style="color:#00897b;"></i> Latest Updates &amp; Orders</div>
                @foreach($updates as $uIndex => $update)
                <div class="row mb-2 align-items-end {{ !$loop->last ? 'pb-2' : '' }}" style="border-bottom:{{ !$loop->last ? '1px solid #eef0f2' : 'none' }}">
                    <div class="col-md-7">
                        <div class="form-group mb-1"><label class="fm-label">Update {{ $uIndex + 1 }} — Text</label><input wire:model="updates.{{ $uIndex }}.text" type="text" class="form-control fm-input" placeholder="Update description"></div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-1"><label class="fm-label">Date</label><input wire:model="updates.{{ $uIndex }}.date" type="text" class="form-control fm-input" placeholder="e.g. 10 Jun"></div>
                    </div>
                    <div class="col-md-2">
                        <button wire:click="removeUpdate({{ $uIndex }})" class="fm-btn-outline-danger mb-1" type="button" title="Remove">&times;</button>
                    </div>
                </div>
                @endforeach
                <button wire:click="addUpdate" class="fm-btn-outline-primary mt-1" type="button"><i class="fas fa-plus mr-1"></i> Add Update</button>

                <hr class="my-3" style="border-top:1px solid #e2e6ea;">

                {{-- Sub: Services --}}
                <div class="fm-subhead"><i class="fas fa-th-large" style="color:#00acc1;"></i> Service Cards (6 cards)</div>
                @for($si = 1; $si <= 6; $si++)
                <div class="mb-2 pb-2 {{ $si < 6 ? 'border-bottom' : '' }}" style="border-bottom-color:#eef0f2;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-1"><label class="fm-label">Card {{ $si }} Title</label><input wire:model="{{ 'feature'.$si.'Title' }}" type="text" class="form-control fm-input"></div>
                        </div>
                        @if($si > 4)
                        <div class="col-md-3">
                            <div class="form-group mb-1"><label class="fm-label">Icon (FA)</label><input wire:model="{{ 'feature'.$si.'Icon' }}" type="text" class="form-control fm-input" placeholder="fa-leaf"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-1"><label class="fm-label">URL</label><input wire:model="{{ 'feature'.$si.'Url' }}" type="text" class="form-control fm-input" placeholder="# or /url"></div>
                        </div>
                        @endif
                    </div>
                    <div class="form-group mb-0"><label class="fm-label">Card {{ $si }} Text</label><textarea wire:model="{{ 'feature'.$si.'Text' }}" class="form-control fm-textarea" rows="1"></textarea></div>
                </div>
                @endfor
            </div>
        </div>

        {{-- SECTION 6: VMO --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">6</span><span class="fm-title"><i class="fas fa-bullseye mr-1"></i> Vision, Mission &amp; Objectives</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="form-group"><label class="fm-label">Vision</label><textarea wire:model="visionText" class="form-control fm-textarea" rows="2" placeholder="Vision statement"></textarea></div>
                <div class="form-group"><label class="fm-label">Mission</label><textarea wire:model="missionText" class="form-control fm-textarea" rows="2" placeholder="Mission statement"></textarea></div>
                <div class="form-group mb-0"><label class="fm-label">Objectives</label><textarea wire:model="objectivesText" class="form-control fm-textarea" rows="2" placeholder="Key objectives"></textarea></div>
            </div>
        </div>

        {{-- SECTION 7: FACTS --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">7</span><span class="fm-title"><i class="fas fa-chart-bar mr-1"></i> Horticulture Facts (4 stats)</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                @for($fi = 1; $fi <= 4; $fi++)
                <div class="row mb-1 {{ $fi < 4 ? 'border-bottom pb-1' : '' }}" style="border-bottom-color:#eef0f2;">
                    <div class="col-md-6">
                        <div class="form-group mb-1"><label class="fm-label">Fact {{ $fi }} — Value</label><input wire:model="{{ 'fact'.$fi.'Value' }}" type="text" class="form-control fm-input" placeholder="e.g. 14×"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-1"><label class="fm-label">Fact {{ $fi }} — Label</label><input wire:model="{{ 'fact'.$fi.'Label' }}" type="text" class="form-control fm-input" placeholder="e.g. Growth Since 1972"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- SECTION 8: CTA --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">8</span><span class="fm-title"><i class="fas fa-search mr-1"></i> Call-to-Action — Track Report</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group"><label class="fm-label">Icon</label><input wire:model="ctaIcon" type="text" class="form-control fm-input" placeholder="fa-search"></div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group"><label class="fm-label">Title</label><input wire:model="ctaTitle" type="text" class="form-control fm-input" placeholder="Track Your Soil Report"></div>
                    </div>
                </div>
                <div class="form-group"><label class="fm-label">Subtitle</label><textarea wire:model="ctaSubtitle" class="form-control fm-textarea" rows="2" placeholder="Enter your registered mobile number..."></textarea></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-0"><label class="fm-label">Button Text</label><input wire:model="ctaBtnText" type="text" class="form-control fm-input" placeholder="Go to Report Tracking"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0"><label class="fm-label">Button URL</label><input wire:model="ctaBtnUrl" type="text" class="form-control fm-input" placeholder="/track-report"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 9: INFO RIBBON --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">9</span><span class="fm-title"><i class="fas fa-info-circle mr-1"></i> Info Ribbon (4 items — above footer)</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                @for($ri = 1; $ri <= 4; $ri++)
                <div class="row mb-1 {{ $ri < 4 ? 'border-bottom pb-1' : '' }}" style="border-bottom-color:#eef0f2;">
                    <div class="col-md-4">
                        <div class="form-group mb-1"><label class="fm-label">Item {{ $ri }} — Icon</label><input wire:model="{{ 'ribbon'.$ri.'Icon' }}" type="text" class="form-control fm-input" placeholder="fa-hand-holding-heart"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group mb-1"><label class="fm-label">Item {{ $ri }} — Label</label><input wire:model="{{ 'ribbon'.$ri.'Label' }}" type="text" class="form-control fm-input" placeholder="Free Soil Testing"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- SECTION 10: FOOTER --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">10</span><span class="fm-title"><i class="fas fa-gavel mr-1"></i> Footer</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="form-group"><label class="fm-label">About Text</label><textarea wire:model="footerAbout" class="form-control fm-textarea" rows="2" placeholder="About the department / portal"></textarea></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label class="fm-label">Copyright Text</label><input wire:model="footerCopyright" type="text" class="form-control fm-input" placeholder="© 2026 Soil Lab — ..."></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label class="fm-label">Disclaimer</label><input wire:model="footerDisclaimer" type="text" class="form-control fm-input" placeholder="This site is for information purposes only..."></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Credit Text</label><input wire:model="footerCreditText" type="text" class="form-control fm-input" placeholder="Designed and Developed by..."></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Credit URL</label><input wire:model="footerCreditUrl" type="text" class="form-control fm-input" placeholder="https://..."></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Helpline Number</label><input wire:model="frontHelpline" type="text" class="form-control fm-input" placeholder="e.g. 1800-180-1551"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="fm-label">Soil Lab Phone</label><input wire:model="labPhone" type="text" class="form-control fm-input" placeholder="+91-XXXX-XXXXXX"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-0"><label class="fm-label">Working Hours</label><input wire:model="frontWorkingHours" type="text" class="form-control fm-input" placeholder="e.g. Mon-Fri 9AM-5PM"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 11: META / OG --}}
        <div class="fm-section">
            <div class="fm-header"><span class="fm-badge">11</span><span class="fm-title"><i class="fas fa-share-alt mr-1"></i> Meta Tags &amp; Share Image</span><span class="fm-toggle"><i class="fas fa-chevron-down"></i></span></div>
            <div class="fm-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label class="fm-label">Meta Title</label><input wire:model="metaTitle" type="text" class="form-control fm-input" placeholder="SEO / browser tab title"></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label class="fm-label">Meta Keywords</label><input wire:model="metaKeywords" type="text" class="form-control fm-input" placeholder="soil, testing, agriculture, ..."></div>
                    </div>
                </div>
                <div class="form-group"><label class="fm-label">Meta Description</label><textarea wire:model="metaDescription" class="form-control fm-textarea" rows="2" placeholder="Brief description for search engines"></textarea></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="fm-label">Share Image (OG) — for WhatsApp / Facebook</label>
                            <div class="custom-file fm-input-file">
                                <input wire:model="ogImage" type="file" class="custom-file-input" id="ogImageUpload" accept="image/png,image/jpg,image/jpeg">
                                <label class="custom-file-label" for="ogImageUpload">{{ $ogImage ? $ogImage->getClientOriginalName() : ($ogImagePath ? basename($ogImagePath) : 'Choose image...') }}</label>
                            </div>
                            <small class="text-muted">PNG/JPG, max 2MB. 1200×630 recommended.</small>
                            @error('ogImage') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <div class="mt-2 d-flex align-items-center gap-2" style="gap:8px;">
                                @if($ogImagePath && !$ogImage)
                                <img src="{{ asset('storage/' . $ogImagePath) }}" class="fm-photo-preview mr-2">
                                <button type="button" wire:click="removeOgImage" class="fm-btn-outline-danger"><i class="fas fa-trash mr-1"></i> Remove</button>
                                @endif
                                @if($ogImage)
                                <img src="{{ $ogImage->temporaryUrl() }}" class="fm-photo-preview">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SAVE BAR --}}
        <div class="fm-save-bar">
            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Changes apply immediately to the public home page.</small>
            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm"><i class="fas fa-save mr-1"></i> Save Front Page</button>
        </div>
    </form>

    <script>
        document.querySelectorAll('.fm-header').forEach(function(h) {
            h.addEventListener('click', function() {
                this.closest('.fm-section').classList.toggle('fm-collapsed');
            });
        });
        document.getElementById('fmExpandAll').addEventListener('click', function() {
            document.querySelectorAll('.fm-section').forEach(function(el) { el.classList.remove('fm-collapsed'); });
        });
        document.getElementById('fmCollapseAll').addEventListener('click', function() {
            document.querySelectorAll('.fm-section').forEach(function(el) { el.classList.add('fm-collapsed'); });
        });
    </script>
</div>