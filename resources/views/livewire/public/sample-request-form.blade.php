<div>
    <style>
        .track-header{background:linear-gradient(135deg,#0a4b2a 0%,#018840 100%);padding:16px 0;border-bottom:3px solid #f3b229;}
        .track-header h1{color:#fff;font-size:1rem;font-weight:800;margin:0;}
        .track-header .back-link{color:rgba(255,255,255,.7);font-size:.8rem;text-decoration:none;}
        .track-header .back-link:hover{color:#fff;}
        .srf-card{background:#fff;border-radius:12px;border:1px solid #e2e6ea;padding:32px;max-width:620px;margin:40px auto;box-shadow:0 4px 24px rgba(0,0,0,.06);}
        .srf-card h2{font-size:1.15rem;font-weight:800;color:#0a3d22;margin-bottom:4px;}
        .srf-card .srf-sub{color:#64748b;font-size:.82rem;margin-bottom:20px;}
        .srf-card .srf-field{margin-bottom:14px;}
        .srf-card .srf-field label{font-size:.72rem;font-weight:600;color:#475569;display:block;margin-bottom:2px;}
        .srf-card .srf-field input,.srf-card .srf-field select{width:100%;padding:9px 14px;font-size:.85rem;border:1px solid #e2e6ea;border-radius:8px;background:#fff;transition:border-color .15s,box-shadow .15s;}
        .srf-card .srf-field input:focus,.srf-card .srf-field select:focus{border-color:#018840;box-shadow:0 0 0 3px rgba(1,136,64,.12);outline:0;}
        .srf-card .srf-field input.is-invalid,.srf-card .srf-field select.is-invalid{border-color:#dc3545;background-color:#fff8f8;}
        .srf-card .srf-field input.is-invalid:focus,.srf-card .srf-field select.is-invalid:focus{border-color:#dc3545;box-shadow:0 0 0 3px rgba(220,53,69,.15);}
        .srf-card .srf-submit{width:100%;padding:10px;background:linear-gradient(135deg,#018840,#0a5a2a);color:#fff;border:none;border-radius:8px;font-size:.9rem;font-weight:700;cursor:pointer;transition:opacity .15s;}
        .srf-card .srf-submit:hover{opacity:.9;}
        .srf-card .srf-submit:disabled{opacity:.5;cursor:not-allowed;}
        .srf-card .srf-error{color:#dc3545;font-size:.78rem;margin-top:2px;}
        .srf-success{text-align:center;padding:24px 0;}
        .srf-success .srf-check{width:64px;height:64px;border-radius:50%;background:#e8f5e9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:1.8rem;color:#018840;}
        .srf-success .srf-req-id{background:#f0fff4;border:2px dashed #018840;border-radius:10px;padding:14px;font-size:1.3rem;font-weight:800;color:#0a3d22;letter-spacing:1px;margin:12px 0;display:inline-block;}
        .srf-success .srf-msg{color:#475569;font-size:.82rem;}
        .srf-success .srf-back{display:inline-block;margin-top:16px;color:#018840;font-size:.82rem;text-decoration:none;font-weight:600;}
        .srf-success .srf-back:hover{text-decoration:underline;}
        .srf-footer{background:#072b17;padding:24px 0;color:rgba(255,255,255,.55);font-size:.75rem;text-align:center;}
        .srf-hp{position:absolute;left:-9999px;opacity:0;height:0;overflow:hidden;}
    </style>

    <div class="track-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-clipboard-list mr-2"></i> Request Sample Collection</h1>
            <a href="{{ route('home') }}" class="back-link"><i class="fas fa-arrow-left mr-1"></i> Back to Home</a>
        </div>
    </div>

    <div class="container">
        <div class="srf-card">
            @if($submitted)
                <div class="srf-success">
                    <div class="srf-check"><i class="fas fa-check"></i></div>
                    <h2>Request Submitted!</h2>
                    <p class="srf-msg">Your sample collection request has been sent to your zone's field team. Please save your request ID for tracking.</p>
                    <div class="srf-req-id">{{ $requestId }}</div>
                    <p class="srf-msg" style="margin-top:8px;">A field official will contact you at <strong>{{ $farmerPhone }}</strong> to schedule the collection.</p>
                    <a href="{{ route('home') }}" class="srf-back"><i class="fas fa-arrow-left mr-1"></i> Return to Home</a>
                </div>
            @else
                <h2><i class="fas fa-hand-holding-heart text-success mr-1"></i> Request Sample Collection</h2>
                <p class="srf-sub">Fill in your details and location below. A field official from your zone will contact you to collect a soil sample.</p>

                @if($error)
                <div class="alert alert-danger py-2 small">{{ $error }}</div>
                @endif

                <form wire:submit="submit">
                    <div class="srf-hp">
                        <input wire:model="honeypot" type="text" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="srf-field">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <input wire:model="farmerName" type="text" placeholder="e.g. Ghulam Mohammad" class="{{ $errors->has('farmerName') ? 'is-invalid' : '' }}">
                        @error('farmerName') <div class="srf-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 srf-field">
                            <label>Phone Number (10 digits) <span class="text-danger">*</span></label>
                            <input wire:model="farmerPhone" type="tel" maxlength="10" placeholder="e.g. 9419123456" class="{{ $errors->has('farmerPhone') ? 'is-invalid' : '' }}">
                            @error('farmerPhone') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 srf-field">
                            <label>Aadhaar Number (12 digits) <span class="text-danger">*</span></label>
                            <input wire:model="aadharNo" type="text" maxlength="12" placeholder="e.g. 123456789012" class="{{ $errors->has('aadharNo') ? 'is-invalid' : '' }}">
                            @error('aadharNo') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 srf-field">
                            <label>State <span class="text-danger">*</span></label>
                            <select wire:model.live="stateId" class="{{ $errors->has('stateId') ? 'is-invalid' : '' }}">
                                <option value="">— Select State —</option>
                                @foreach($this->states as $state)
                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('stateId') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 srf-field">
                            <label>District <span class="text-danger">*</span></label>
                            <select wire:model.live="districtId" class="{{ $errors->has('districtId') ? 'is-invalid' : '' }}">
                                <option value="">— Select District —</option>
                                @foreach($this->districts as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('districtId') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 srf-field">
                            <label>Zone <span class="text-danger">*</span></label>
                            <select wire:model.live="zoneId" class="{{ $errors->has('zoneId') ? 'is-invalid' : '' }}">
                                <option value="">— Select Zone —</option>
                                @foreach($this->zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                            @error('zoneId') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 srf-field">
                            <label>Panchayat <span class="text-danger">*</span></label>
                            <select wire:model.live="panchayatId" class="{{ $errors->has('panchayatId') ? 'is-invalid' : '' }}">
                                <option value="">— Select Panchayat —</option>
                                @foreach($this->panchayats as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('panchayatId') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 srf-field">
                            <label>Village <span class="text-danger">*</span></label>
                            <select wire:model.live="villageId" class="{{ $errors->has('villageId') ? 'is-invalid' : '' }}">
                                <option value="">— Select Village —</option>
                                @foreach($this->villages as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @error('villageId') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 srf-field">
                            <label>Landmark / Details <span class="text-danger">*</span></label>
                            <input wire:model="farmerAddress" type="text" placeholder="e.g. Near mosque, house no." class="{{ $errors->has('farmerAddress') ? 'is-invalid' : '' }}">
                            @error('farmerAddress') <div class="srf-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    @if($recaptchaEnabled)
                    <div class="srf-field my-3 text-center" wire:ignore>
                        <div class="g-recaptcha d-inline-block"
                             data-sitekey="{{ $this->recaptchaSiteKey }}"
                             data-callback="onRecaptchaSuccess"
                             data-expired-callback="onRecaptchaExpired"></div>
                    </div>
                    @error('recaptchaToken')
                        <div id="recaptcha-error" class="srf-error text-center mb-3 font-weight-bold">{{ $message }}</div>
                    @enderror
                    @endif

                    <button type="submit" class="srf-submit" onclick="if(typeof grecaptcha !== 'undefined'){ const t = grecaptcha.getResponse(); if(t){ @this.set('recaptchaToken', t); } }" wire:loading.attr="disabled">
                        <span wire:loading.remove><i class="fas fa-paper-plane mr-1"></i> Submit Request</span>
                        <span wire:loading><i class="fas fa-spinner fa-spin mr-1"></i> Submitting...</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if($recaptchaEnabled)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        window.onRecaptchaSuccess = function(token) {
            const err = document.getElementById('recaptcha-error');
            if (err) err.style.display = 'none';
            if (window.Livewire) {
                @this.set('recaptchaToken', token);
            }
        };
        window.onRecaptchaExpired = function() {
            if (window.Livewire) {
                @this.set('recaptchaToken', '');
            }
        };
        document.addEventListener('livewire:init', () => {
            Livewire.on('reset-recaptcha', () => {
                if (typeof grecaptcha !== 'undefined') {
                    try { grecaptcha.reset(); } catch (e) {}
                }
            });
        });
    </script>
    @endif

    <footer class="srf-footer">
        <div class="container">
            &copy; {{ date('Y') }} {{ setting('app_name', 'Soil Lab') }} &mdash; Government of Jammu &amp; Kashmir (UT)
        </div>
    </footer>
</div>
