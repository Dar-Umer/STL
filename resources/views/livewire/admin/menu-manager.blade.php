<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <span class="w-2.5 h-6 rounded-full bg-emerald-500"></span>
                Portal Menu &amp; Navigation Manager
            </h1>
            <p class="text-xs text-gray-500 mt-1 font-medium">Reorganize public header links, dropdown sub-items, and footer navigation resources</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between text-sm shadow-xs">
        <div class="flex items-center gap-2.5">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Navigation Menu Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Top Header Navigation</h2>
                        <p class="text-xs text-gray-400">Primary navbar items and multi-level sub-menus</p>
                    </div>
                </div>
                <button type="button" wire:click="addItem" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-dashed border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Menu Item
                </button>
            </div>

            <div class="p-6 space-y-4">
                @forelse($menuItems as $mIndex => $item)
                <div class="bg-gray-50/70 border border-gray-200/80 rounded-2xl p-4 transition hover:border-gray-300" wire:key="item-{{ $mIndex }}">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                        <div class="md:col-span-4">
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Menu Label</label>
                            <input wire:model="menuItems.{{ $mIndex }}.label" type="text" placeholder="e.g. Services" class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
                        </div>

                        <div class="md:col-span-5">
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Target URL</label>
                            <input wire:model="menuItems.{{ $mIndex }}.url" type="text" placeholder="/ or https://..." class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs font-mono text-gray-900 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-100 transition">
                        </div>

                        <div class="md:col-span-1 flex flex-col items-center">
                            <label class="block text-[11px] font-semibold uppercase tracking-wider text-gray-500 mb-1">Active</label>
                            <input wire:model="menuItems.{{ $mIndex }}.active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer mt-1">
                        </div>

                        <div class="md:col-span-2 flex items-center justify-end gap-1 pt-4 md:pt-0">
                            <button type="button" wire:click="moveItemUp({{ $mIndex }})" class="p-1.5 rounded-lg border border-gray-200 bg-white text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition disabled:opacity-30 disabled:cursor-not-allowed" title="Move Up" {{ $loop->first ? 'disabled' : '' }}>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button type="button" wire:click="moveItemDown({{ $mIndex }})" class="p-1.5 rounded-lg border border-gray-200 bg-white text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition disabled:opacity-30 disabled:cursor-not-allowed" title="Move Down" {{ $loop->last ? 'disabled' : '' }}>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <button type="button" wire:click="addChild({{ $mIndex }})" class="p-1.5 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition" title="Add Sub-item">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            </button>
                            <button type="button" wire:click="removeItem({{ $mIndex }})" onclick="return confirm('Remove this menu item and its children?')" class="p-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 transition" title="Delete">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Children Sub-items --}}
                    @if(!empty($item['children']))
                    <div class="mt-3 pl-6 border-l-2 border-emerald-200 space-y-2">
                        @foreach($item['children'] as $cIndex => $child)
                        <div class="bg-white border border-gray-200 rounded-xl p-3 grid grid-cols-1 md:grid-cols-12 gap-3 items-center" wire:key="item-{{ $mIndex }}-child-{{ $cIndex }}">
                            <div class="md:col-span-4">
                                <input wire:model="menuItems.{{ $mIndex }}.children.{{ $cIndex }}.label" type="text" placeholder="Sub-item Label" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs text-gray-900 focus:outline-none focus:border-emerald-500">
                            </div>
                            <div class="md:col-span-5">
                                <input wire:model="menuItems.{{ $mIndex }}.children.{{ $cIndex }}.url" type="text" placeholder="/sub-page" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-mono text-gray-900 focus:outline-none focus:border-emerald-500">
                            </div>
                            <div class="md:col-span-1 flex items-center justify-center">
                                <input wire:model="menuItems.{{ $mIndex }}.children.{{ $cIndex }}.active" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            </div>
                            <div class="md:col-span-2 flex items-center justify-end gap-1">
                                <button type="button" wire:click="moveChildUp({{ $mIndex }}, {{ $cIndex }})" class="p-1 rounded text-gray-400 hover:text-gray-700 disabled:opacity-20" {{ $loop->first ? 'disabled' : '' }}>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                </button>
                                <button type="button" wire:click="moveChildDown({{ $mIndex }}, {{ $cIndex }})" class="p-1 rounded text-gray-400 hover:text-gray-700 disabled:opacity-20" {{ $loop->last ? 'disabled' : '' }}>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <button type="button" wire:click="removeChild({{ $mIndex }}, {{ $cIndex }})" onclick="return confirm('Remove sub-item?')" class="p-1 rounded text-rose-500 hover:text-rose-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-8 text-gray-400 text-xs">
                    No top-level menu items defined. Click "Add Menu Item" above to build the navigation hierarchy.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Footer Links Card --}}
        <div class="bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50/70 to-white">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Footer Important Links</h2>
                        <p class="text-xs text-gray-400">Government portals, portals of record, and external references</p>
                    </div>
                </div>
                <button type="button" wire:click="addFooterLink" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-dashed border-emerald-300 text-emerald-700 hover:bg-emerald-50 text-xs font-semibold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Link
                </button>
            </div>

            <div class="p-6 space-y-3">
                @forelse($footerLinks as $fIndex => $link)
                <div class="bg-gray-50/70 border border-gray-200/80 rounded-2xl p-3 flex items-center gap-3" wire:key="footer-{{ $fIndex }}">
                    <div class="w-1/3">
                        <input wire:model="footerLinks.{{ $fIndex }}.label" type="text" placeholder="e.g. J&K Agriculture Dept" class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-500">
                    </div>
                    <div class="flex-1">
                        <input wire:model="footerLinks.{{ $fIndex }}.url" type="text" placeholder="https://..." class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-xs font-mono text-gray-900 focus:outline-none focus:border-emerald-500">
                    </div>
                    <button type="button" wire:click="removeFooterLink({{ $fIndex }})" onclick="return confirm('Remove footer link?')" class="p-2 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Remove Link">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
                @empty
                <div class="text-center py-6 text-gray-400 text-xs">
                    No footer links configured. Click "Add Link" to publish resource shortcuts.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Floating Action Bar --}}
        <div class="sticky bottom-4 bg-white/95 backdrop-blur-md rounded-2xl p-4 border border-gray-200 shadow-lg flex items-center justify-between z-20">
            <p class="text-xs text-gray-500 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Changes propagate immediately to the public portal header and footer.
            </p>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-xs shadow-emerald-600/20 transition active:scale-[0.98]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Navigation Changes
            </button>
        </div>
    </form>
</div>