<div>
    <style>
        .mm-section{background:#fff;border-radius:10px;border:1px solid #e2e6ea;margin-bottom:16px;overflow:hidden;}
        .mm-section .mm-head{padding:11px 16px;background:#f8f9fb;border-left:3px solid #018840;border-bottom:1px solid #eef0f2;font-size:.8rem;font-weight:700;color:#1a2c3e;display:flex;align-items:center;gap:8px;}
        .mm-body{padding:16px;}
        .mm-item{background:#fafbfc;border:1px solid #eef0f2;border-radius:8px;padding:10px 12px;margin-bottom:8px;}
        .mm-item:hover{border-color:#e2e6ea;}
        .mm-item .mm-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
        .mm-item .mm-row > *{flex:1;min-width:80px;}
        .mm-item .mm-row .mm-url{flex:2;}
        .mm-item .mm-row .mm-actions{flex:0 0 auto;display:flex;gap:4px;min-width:auto;}
        .mm-item .mm-children{margin-top:8px;padding-left:24px;border-left:2px dashed #e2e6ea;}
        .mm-item .mm-children .mm-child{background:#fff;border:1px solid #eef0f2;border-radius:6px;padding:8px 10px;margin-bottom:6px;}
        .mm-item .mm-children .mm-child:hover{border-color:#e2e6ea;}
        .mm-label{font-size:.7rem;font-weight:600;color:#64748b;display:block;margin-bottom:1px;}
        .mm-inp{font-size:.8rem;border-radius:6px;border:1px solid #e2e6ea;background:#fff;padding:5px 10px;width:100%;transition:border-color .15s,box-shadow .15s;}
        .mm-inp:focus{border-color:#018840;box-shadow:0 0 0 3px rgba(1,136,64,.12);outline:0;}
        .mm-inp-sm{font-size:.78rem;padding:4px 8px;}
        .mm-btn{display:inline-flex;align-items:center;justify-content:center;border-radius:6px;font-size:.72rem;padding:4px 10px;cursor:pointer;border:1px solid transparent;transition:all .15s;text-decoration:none;}
        .mm-btn-primary{color:#018840;border-color:#018840;background:transparent;}
        .mm-btn-primary:hover{color:#fff;background:#018840;}
        .mm-btn-danger{color:#dc3545;border-color:#dc3545;background:transparent;}
        .mm-btn-danger:hover{color:#fff;background:#dc3545;}
        .mm-btn-outline{color:#64748b;border-color:#d1d5db;background:transparent;}
        .mm-btn-outline:hover{color:#1a2c3e;border-color:#94a3b8;}
        .mm-btn-icon{min-width:26px;height:26px;padding:0;font-size:.65rem;}
        .mm-check{width:16px;height:16px;cursor:pointer;accent-color:#018840;}
        .mm-header-bar{display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#fff;border:1px solid #e2e6ea;border-radius:10px;margin-bottom:16px;}
        .mm-header-bar h5{margin:0;font-weight:700;font-size:1rem;display:flex;align-items:center;gap:8px;}
        @media(max-width:767px){.mm-item .mm-row{flex-direction:column;align-items:stretch;}.mm-item .mm-row .mm-actions{justify-content:flex-end;}}
    </style>

    <div class="mm-header-bar">
        <h5><i class="fas fa-bars text-primary mr-1"></i> Menu Manager</h5>
    </div>

    <form wire:submit="save">
        @if(session()->has('success'))
            <div class="alert alert-success py-2 rounded-lg mb-3 small"><i class="fas fa-check-circle mr-1"></i> {{ session('success') }}</div>
        @endif

        {{-- NAVIGATION MENU --}}
        <div class="mm-section">
            <div class="mm-head"><i class="fas fa-sitemap" style="color:#018840;"></i> Navigation Menu — Top Level Items</div>
            <div class="mm-body">
                @if(empty($menuItems))
                <p class="text-muted small mb-3">No menu items yet. Click "Add Menu Item" to create the first one.</p>
                @endif

                @foreach($menuItems as $mIndex => $item)
                <div class="mm-item" wire:key="item-{{ $mIndex }}">
                    <div class="mm-row">
                        <div>
                            <span class="mm-label">Label</span>
                            <input wire:model="menuItems.{{ $mIndex }}.label" type="text" class="mm-inp" placeholder="Home">
                        </div>
                        <div class="mm-url">
                            <span class="mm-label">URL</span>
                            <input wire:model="menuItems.{{ $mIndex }}.url" type="text" class="mm-inp" placeholder="/ or https://...">
                        </div>
                        <div style="flex:0 0 auto;min-width:60px;">
                            <span class="mm-label">Active</span>
                            <input wire:model="menuItems.{{ $mIndex }}.active" type="checkbox" class="mm-check">
                        </div>
                        <div class="mm-actions">
                            <button type="button" wire:click="moveItemUp({{ $mIndex }})" class="mm-btn mm-btn-outline mm-btn-icon" title="Move up" {{ $loop->first ? 'disabled style=opacity:.3' : '' }}><i class="fas fa-chevron-up"></i></button>
                            <button type="button" wire:click="moveItemDown({{ $mIndex }})" class="mm-btn mm-btn-outline mm-btn-icon" title="Move down" {{ $loop->last ? 'disabled style=opacity:.3' : '' }}><i class="fas fa-chevron-down"></i></button>
                            <button type="button" wire:click="addChild({{ $mIndex }})" class="mm-btn mm-btn-primary mm-btn-icon" title="Add child"><i class="fas fa-plus"></i></button>
                            <button type="button" wire:click="removeItem({{ $mIndex }})" class="mm-btn mm-btn-danger mm-btn-icon" title="Remove" onclick="return confirm('Remove this item?')"><i class="fas fa-times"></i></button>
                        </div>
                    </div>

                    {{-- Children --}}
                    @if(!empty($item['children']))
                    <div class="mm-children">
                        @foreach($item['children'] as $cIndex => $child)
                        <div class="mm-child" wire:key="item-{{ $mIndex }}-child-{{ $cIndex }}">
                            <div class="mm-row">
                                <div>
                                    <span class="mm-label">Label</span>
                                    <input wire:model="menuItems.{{ $mIndex }}.children.{{ $cIndex }}.label" type="text" class="mm-inp mm-inp-sm" placeholder="Sub Item">
                                </div>
                                <div class="mm-url">
                                    <span class="mm-label">URL</span>
                                    <input wire:model="menuItems.{{ $mIndex }}.children.{{ $cIndex }}.url" type="text" class="mm-inp mm-inp-sm" placeholder="/sub-page">
                                </div>
                                <div style="flex:0 0 auto;min-width:50px;">
                                    <span class="mm-label">Active</span>
                                    <input wire:model="menuItems.{{ $mIndex }}.children.{{ $cIndex }}.active" type="checkbox" class="mm-check">
                                </div>
                                <div class="mm-actions">
                                    <button type="button" wire:click="moveChildUp({{ $mIndex }}, {{ $cIndex }})" class="mm-btn mm-btn-outline mm-btn-icon" title="Move up" {{ $loop->first ? 'disabled style=opacity:.3' : '' }}><i class="fas fa-chevron-up"></i></button>
                                    <button type="button" wire:click="moveChildDown({{ $mIndex }}, {{ $cIndex }})" class="mm-btn mm-btn-outline mm-btn-icon" title="Move down" {{ $loop->last ? 'disabled style=opacity:.3' : '' }}><i class="fas fa-chevron-down"></i></button>
                                    <button type="button" wire:click="removeChild({{ $mIndex }}, {{ $cIndex }})" class="mm-btn mm-btn-danger mm-btn-icon" title="Remove" onclick="return confirm('Remove this child?')"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach

                <button type="button" wire:click="addItem" class="mm-btn mm-btn-primary mt-2"><i class="fas fa-plus mr-1"></i> Add Menu Item</button>
            </div>
        </div>

        {{-- FOOTER LINKS --}}
        <div class="mm-section">
            <div class="mm-head"><i class="fas fa-external-link-alt" style="color:#018840;"></i> Footer — Important Links</div>
            <div class="mm-body">
                @if(empty($footerLinks))
                <p class="text-muted small mb-3">No footer links yet. Click "Add Link" to create the first one.</p>
                @endif

                @foreach($footerLinks as $fIndex => $link)
                <div class="mm-item" wire:key="footer-{{ $fIndex }}">
                    <div class="mm-row">
                        <div>
                            <span class="mm-label">Label</span>
                            <input wire:model="footerLinks.{{ $fIndex }}.label" type="text" class="mm-inp" placeholder="Link name">
                        </div>
                        <div class="mm-url">
                            <span class="mm-label">URL</span>
                            <input wire:model="footerLinks.{{ $fIndex }}.url" type="text" class="mm-inp" placeholder="https://...">
                        </div>
                        <div class="mm-actions">
                            <button type="button" wire:click="removeFooterLink({{ $fIndex }})" class="mm-btn mm-btn-danger mm-btn-icon" title="Remove" onclick="return confirm('Remove this link?')"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
                @endforeach

                <button type="button" wire:click="addFooterLink" class="mm-btn mm-btn-primary mt-2"><i class="fas fa-plus mr-1"></i> Add Link</button>
            </div>
        </div>

        {{-- SAVE --}}
        <div style="position:sticky;bottom:0;background:rgba(255,255,255,.92);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid #e2e6ea;padding:12px 20px;display:flex;align-items:center;justify-content:space-between;border-radius:10px;margin-top:20px;z-index:99;box-shadow:0 -4px 20px rgba(0,0,0,.04);">
            <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Changes apply immediately to the public home page.</small>
            <button type="submit" class="btn btn-primary rounded-pill px-5 shadow-sm"><i class="fas fa-save mr-1"></i> Save Menu</button>
        </div>
    </form>
</div>