@php
    $openCount = $tickets->where('status', \App\Enums\TicketStatus::Open)->count();
    $resolveErrors = $errors->getBag('resolveTicket');

    /* After failed remarks, reopen the dialog pointed at the same ticket. */
    $resolveAction = $resolveErrors->any() && old('ticket_id')
        ? route('admin.tickets.resolve', old('ticket_id'))
        : route('admin.tickets');
@endphp

<x-layouts.portal :portal="$portal" title="Help tickets">
    <x-page-header title="Help tickets" subtitle="Raised from the Help page. Resolving a ticket emails your remarks to the person who raised it." />

    <x-table-card id="tickets-table" title="{{ $openCount }} open {{ \Illuminate\Support\Str::plural('ticket', $openCount) }}" :count="$tickets->count()" placeholder="Search name, email or transaction ID">
        <x-slot:actions>
            @if ($tickets->isNotEmpty())
                <button type="button" class="btn btn-info" data-export-table="tickets-table" data-export-name="help-tickets">
                    <i class="fa-solid fa-file-csv me-1" aria-hidden="true"></i> Export CSV
                </button>
            @endif
        </x-slot:actions>
        <x-slot:filters>
            <select class="form-select form-select-sm tw:sm:w-auto" aria-label="Issue type" data-table-filter="tickets-table" data-filter-key="category">
                <option value="">All issues</option>
                @foreach ($categories as $category)
                    <option>{{ $category->label() }}</option>
                @endforeach
            </select>
        </x-slot:filters>

        <thead>
            <tr><th>Ticket</th><th>Raised by</th><th>Issue</th><th>Transaction ID</th><th>Message</th><th>Status</th><th>Raised</th><th data-export-skip><span class="visually-hidden">Actions</span></th></tr>
        </thead>
        <tbody>
            @forelse ($tickets as $ticket)
                @php($isOpen = $ticket->status === \App\Enums\TicketStatus::Open)
                @php($record = [
                    'reference' => $ticket->reference,
                    'name' => $ticket->name,
                    'email' => $ticket->email,
                    'category' => $ticket->category->label(),
                    'transaction_id' => $ticket->transaction_id ?? '—',
                    'message' => $ticket->message,
                    'ticket_id' => $ticket->id,
                    'resolution' => $ticket->resolution_remarks ?? '',
                ])
                <tr data-category="{{ $ticket->category->label() }}">
                    <td class="font-monospace">{{ $ticket->reference }}</td>
                    <td>
                        <span class="fw-semibold d-block">{{ $ticket->name }}</span>
                        <a href="mailto:{{ $ticket->email }}?subject={{ rawurlencode('Re: help ticket '.$ticket->reference) }}" class="small text-break">{{ $ticket->email }}</a>
                    </td>
                    <td>{{ $ticket->category->label() }}</td>
                    <td class="font-monospace">{{ $ticket->transaction_id ?? '—' }}</td>
                    <td class="tw:max-w-xs" data-export="{{ $ticket->message }}"><span class="d-block text-truncate">{{ $ticket->message }}</span></td>
                    <td class="text-center" data-export="{{ $ticket->status->label() }}"><span @class(['pill', 'pill-warning' => $isOpen, 'pill-success' => ! $isOpen])>{{ $ticket->status->label() }}</span></td>
                    <td class="text-body-secondary" data-export="{{ $ticket->created_at->toDateTimeString() }}" title="{{ $ticket->created_at->format('j M Y, g:i a') }}">{{ $ticket->created_at->diffForHumans() }}</td>
                    <x-row-actions :record="$record" :label="'ticket '.$ticket->reference" delete="delete-ticket" :delete-action="route('admin.tickets.destroy', $ticket)">
                        <button type="button" class="btn btn-sm btn-icon btn-icon-info" data-bs-toggle="modal" data-bs-target="#view-ticket" data-record="{{ json_encode($record) }}" aria-label="Read ticket {{ $ticket->reference }}" title="Read">
                            <i class="fa-solid fa-eye" aria-hidden="true"></i>
                        </button>
                        @if ($ticket->screenshot_path)
                            <a href="{{ route('admin.tickets.screenshot', $ticket) }}" target="_blank" rel="noopener" class="btn btn-sm btn-icon btn-icon-info" aria-label="Open the screenshot for ticket {{ $ticket->reference }}" title="Screenshot">
                                <i class="fa-solid fa-image" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if ($isOpen)
                            <button type="button" class="btn btn-sm btn-icon btn-icon-warning" data-bs-toggle="modal" data-bs-target="#resolve-ticket" data-record="{{ json_encode($record) }}" data-action="{{ route('admin.tickets.resolve', $ticket) }}" aria-label="Mark ticket {{ $ticket->reference }} resolved" title="Mark resolved">
                                <i class="fa-solid fa-check" aria-hidden="true"></i>
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-icon btn-icon-warning" data-bs-toggle="modal" data-bs-target="#reopen-ticket" data-record="{{ json_encode($record) }}" data-action="{{ route('admin.tickets.reopen', $ticket) }}" aria-label="Open ticket {{ $ticket->reference }} again" title="Open again">
                                <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                            </button>
                        @endif
                    </x-row-actions>
                </tr>
            @empty
                <x-empty-state colspan="8" title="No help tickets yet" icon="fa-circle-question">Tickets raised from the Help page show up here.</x-empty-state>
            @endforelse
        </tbody>
    </x-table-card>

    {{-- Read-only: just shows the whole ticket. --}}
    <div class="modal fade" id="view-ticket" tabindex="-1" aria-labelledby="view-ticket-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5 font-display" id="view-ticket-title">Ticket <span data-record-text="reference"></span></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row small mb-3">
                        <dt class="col-4">From</dt><dd class="col-8"><span data-record-text="name"></span><br><span class="text-break" data-record-text="email"></span></dd>
                        <dt class="col-4">Issue</dt><dd class="col-8" data-record-text="category"></dd>
                        <dt class="col-4">Transaction ID</dt><dd class="col-8 font-monospace" data-record-text="transaction_id"></dd>
                    </dl>
                    <p class="mb-0 tw:whitespace-pre-line" data-record-text="message"></p>
                    <div class="border-top pt-3 mt-3" data-ticket-resolution>
                        <p class="small fw-semibold mb-1">Remarks sent when resolved</p>
                        <p class="small mb-0 tw:whitespace-pre-line" data-record-text="resolution"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <x-modal id="resolve-ticket" title="Resolve this ticket" submit="Resolve and send email" busy="Sending…" icon="fa-paper-plane" :action="$resolveAction" :show-on-load="$resolveErrors->any()">
        <input type="hidden" name="ticket_id" value="{{ $resolveErrors->any() ? old('ticket_id') : '' }}">
        <p class="mb-0">Ticket <strong data-record-text="reference"></strong> from <span data-record-text="name"></span> (<span class="text-break" data-record-text="email"></span>) moves to resolved. They'll get an email with your remarks and a thank-you.</p>
        <div>
            <label class="form-label" for="resolve_remarks">Remarks</label>
            <textarea id="resolve_remarks" name="remarks" rows="4" @class(['form-control', 'is-invalid' => $resolveErrors->has('remarks')]) required minlength="5" maxlength="2000" placeholder="E.g. Your payment was confirmed and ₹10 has been refunded (after GST) to your UPI account.">{{ $resolveErrors->any() ? old('remarks') : '' }}</textarea>
            <x-field-error for="remarks" bag="resolveTicket">Write what was done, so the person knows.</x-field-error>
        </div>
    </x-modal>

    <x-modal id="reopen-ticket" title="Open this ticket again?" submit="Open again" busy="Saving…" variant="warning" icon="fa-rotate-left" :action="route('admin.tickets')">
        <p class="mb-0">Ticket <strong data-record-text="reference"></strong> from <span data-record-text="name"></span> goes back to open, and its remarks are cleared. No email is sent.</p>
    </x-modal>

    <x-modal id="delete-ticket" title="Delete this ticket?" submit="Delete" busy="Deleting…" danger :action="route('admin.tickets')" method="DELETE">
        <p class="mb-0">Ticket <strong data-record-text="reference"></strong> from <span data-record-text="name"></span> and its screenshot will be deleted for good.</p>
    </x-modal>
</x-layouts.portal>
