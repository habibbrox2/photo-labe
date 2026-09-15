@extends('admin.layouts.app')
@section('page-title', 'Hero Slides')

@section('content')
<div x-data="heroSlideSortable({ reorderUrl: '{{ route('admin.hero-slides.reorder') }}', csrf: '{{ csrf_token() }}' })">
    <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-500">{{ $slides->count() }} {{ $slides->count() === 1 ? 'slide' : 'slides' }}</p>
            @if($slides->count() > 1)
                <p class="text-xs text-gray-400 mt-0.5">Drag the handle beside a row to change the order the slides appear on the homepage. The new order saves automatically.</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <span class="sr-only" role="status" aria-live="polite" x-text="announcement"></span>
            <span class="text-xs font-medium text-gray-400" x-show="status === 'saving'" x-cloak>Saving…</span>
            <span class="text-xs font-medium text-green-600" x-show="status === 'saved'" x-cloak>Order saved</span>
            <span class="text-xs font-medium text-red-600" x-show="status === 'error'" x-cloak x-text="errorText"></span>
            <a href="{{ route('admin.hero-slides.create') }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">+ Add Slide</a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200">
        <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="pl-4 pr-2 py-3 text-left text-xs font-semibold text-gray-500 uppercase w-16" title="Order">Order</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">ID</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Image</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Headline / Caption</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Link</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100"
                   x-on:dragover="onDragOver($event)"
                   x-on:drop="onDrop($event)">
                @forelse($slides as $slide)
                    <tr class="hover:bg-gray-50 transition-colors" data-slide-id="{{ $slide->id }}">
                        <td class="pl-4 pr-2 py-3 align-middle">
                            <div class="flex items-center gap-1.5">
                                <span class="w-5 text-xs font-mono text-gray-400" data-position>{{ $loop->iteration }}</span>
                                <span draggable="true"
                                      tabindex="0"
                                      role="button"
                                      title="Drag to reorder, or focus and use ↑ / ↓"
                                      aria-label="Reorder slide {{ $loop->iteration }} of {{ $slides->count() }}"
                                      x-on:dragstart="startDrag($event)"
                                      x-on:dragend="endDrag($event)"
                                      x-on:keydown.arrow-up.prevent="moveBy($event, -1)"
                                      x-on:keydown.arrow-down.prevent="moveBy($event, 1)"
                                      class="px-1 py-1 rounded cursor-grab active:cursor-grabbing text-gray-300 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:text-gray-600">
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                                        <circle cx="7" cy="5" r="1.5"/><circle cx="13" cy="5" r="1.5"/>
                                        <circle cx="7" cy="10" r="1.5"/><circle cx="13" cy="10" r="1.5"/>
                                        <circle cx="7" cy="15" r="1.5"/><circle cx="13" cy="15" r="1.5"/>
                                    </svg>
                                </span>
                            </div>
                        </td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-400">#{{ $slide->id }}</td>
                        <td class="px-5 py-3">
                            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->headline ?? 'Slide' }}" class="h-12 w-20 rounded-lg object-cover border border-gray-200 pointer-events-none">
                        </td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-900">{{ $slide->headline ?? '—' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $slide->caption_label ?? '' }}{{ $slide->caption_label && $slide->caption_text ? ' · ' : '' }}{{ $slide->caption_text ?? '' }}
                            </p>
                        </td>
                        <td class="px-5 py-3 text-gray-500 max-w-[180px] truncate">{{ $slide->link_url ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $slide->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">Edit</a>
                                <form method="POST" action="{{ route('admin.hero-slides.destroy', $slide) }}" data-confirm="Delete this slide?" data-confirm-label="Delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><x-empty-state icon="image" title="No hero slides yet" description="Add homepage hero images with headlines, captions, and links."><a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary btn-sm">Add a slide</a></x-empty-state></td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        /**
         * Drag & drop ordering for the hero slide table.
         *
         * The rows are server-rendered and simply moved inside the DOM as you drag —
         * the visible order is the source of truth, so the drop is instant and no
         * re-render is needed. On drop the whole id list is PATCHed to the server,
         * which renumbers sort_order from 1.
         */
        Alpine.data('heroSlideSortable', (config = {}) => ({
            reorderUrl: config.reorderUrl || '',
            csrf: config.csrf || '',

            status: '',        // '' | 'saving' | 'saved' | 'error'
            errorText: '',
            announcement: '',
            saving: false,
            queued: false,
            dragRow: null,
            savedOrder: [],

            init() {
                this.savedOrder = this.order();
            },

            rows() {
                return Array.from(this.$root.querySelectorAll('tbody tr[data-slide-id]'));
            },

            order() {
                return this.rows().map((row) => Number(row.dataset.slideId));
            },

            startDrag(event) {
                const row = event.target.closest('tr[data-slide-id]');
                if (!row) return;

                this.dragRow = row;
                row.classList.add('opacity-40');

                event.dataTransfer.effectAllowed = 'move';
                // Firefox only starts a drag when the payload is set.
                event.dataTransfer.setData('text/plain', row.dataset.slideId);

                // Preview the whole row rather than the little handle.
                if (event.dataTransfer.setDragImage) {
                    event.dataTransfer.setDragImage(row, 24, Math.round(row.offsetHeight / 2));
                }
            },

            onDragOver(event) {
                if (!this.dragRow) return;

                event.preventDefault();
                event.dataTransfer.dropEffect = 'move';

                const over = event.target.closest('tr[data-slide-id]');
                if (!over || over === this.dragRow) return;

                const rect = over.getBoundingClientRect();
                const insertAfter = event.clientY > rect.top + rect.height / 2;

                over.parentNode.insertBefore(this.dragRow, insertAfter ? over.nextSibling : over);
                this.refreshPositions();
            },

            onDrop(event) {
                if (!this.dragRow) return;

                event.preventDefault();
                this.commit();
            },

            endDrag() {
                if (!this.dragRow) return;

                this.dragRow.classList.remove('opacity-40');
                this.dragRow = null;
                this.refreshPositions();

                // Also covers a drag released outside the table, where no drop fires.
                this.commit();
            },

            // Arrow keys on a focused handle: same reorder path, no pointer needed.
            moveBy(event, delta) {
                const row = event.target.closest('tr[data-slide-id]');
                const handle = event.target.closest('[draggable]');
                if (!row) return;

                const sibling = delta < 0 ? row.previousElementSibling : row.nextElementSibling;
                if (!sibling) return;

                row.parentNode.insertBefore(row, delta < 0 ? sibling : sibling.nextElementSibling);

                // Reordering re-parents the row, which drops focus to <body> — restore it
                // before anything else, or the next arrow press would go nowhere.
                if (handle) handle.focus();

                const position = this.refreshPositions();

                this.announce('Slide moved to position ' + position + ' of ' + this.rows().length + '.');
                this.commit();

                return false;
            },

            refreshPositions() {
                const total = this.rows().length;
                let focused = 0;

                this.rows().forEach((row, index) => {
                    const label = row.querySelector('[data-position]');
                    if (label) label.textContent = index + 1;

                    const handle = row.querySelector('[draggable]');
                    if (handle) {
                        handle.setAttribute('aria-label', 'Reorder slide ' + (index + 1) + ' of ' + total);
                    }

                    if (handle === document.activeElement) focused = index + 1;
                });

                return focused;
            },

            announce(message) {
                this.announcement = message;
            },

            async commit() {
                // Never overlap requests: remember that another change is waiting and
                // send it once the current one settles.
                if (this.saving) {
                    this.queued = true;

                    return;
                }

                const order = this.order();
                if (!order.length || order.join(',') === this.savedOrder.join(',')) return;

                this.saving = true;
                this.status = 'saving';
                this.errorText = '';

                try {
                    const response = await fetch(this.reorderUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrf,
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ order }),
                    });

                    if (!response.ok) throw new Error('Request failed with status ' + response.status);

                    const data = await response.json().catch(() => ({}));
                    this.savedOrder = (Array.isArray(data.order) ? data.order : order).map(Number);
                    this.status = 'saved';

                    clearTimeout(this._statusTimer);
                    this._statusTimer = setTimeout(() => {
                        if (this.status === 'saved') this.status = '';
                    }, 2500);
                } catch (error) {
                    // Put the rows back the way the server still has them.
                    this.status = 'error';
                    this.errorText = 'Could not save the new order — reverted.';
                    this.restore(this.savedOrder);
                } finally {
                    this.saving = false;

                    if (this.queued) {
                        this.queued = false;
                        this.commit();
                    }
                }
            },

            restore(order) {
                const tbody = this.$root.querySelector('tbody');
                if (!tbody) return;

                // Rebuilding the rows blurs whatever was focused, so remember the row
                // first — otherwise a failed keyboard move strands the user on <body>.
                const focusedRow = document.activeElement?.closest?.('tr[data-slide-id]');
                const focusedId = focusedRow ? Number(focusedRow.dataset.slideId) : null;

                order.forEach((id) => {
                    const row = tbody.querySelector('tr[data-slide-id="' + id + '"]');
                    if (row) tbody.appendChild(row);
                });

                if (focusedId !== null) {
                    tbody.querySelector('tr[data-slide-id="' + focusedId + '"] [draggable]')?.focus();
                }

                this.refreshPositions();
            },
        }));
    });
</script>
@endpush
