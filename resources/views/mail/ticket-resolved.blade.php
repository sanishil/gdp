<x-mail::message>
Hello {{ $ticket->name }},

Thank you for contacting us. Your help ticket **{{ $ticket->reference }}** ({{ $ticket->category->label() }}) has been resolved.

**Our remarks**

<x-mail::panel>
{!! nl2br(e($ticket->resolution_remarks)) !!}
</x-mail::panel>

@if ($ticket->transaction_id)
Transaction ID: {{ $ticket->transaction_id }}
@endif

If the problem is still there, or you have another question, you can raise a new ticket any time.

<x-mail::button :url="route('help')">
Get help
</x-mail::button>

Thank you for your patience, and for using the assignment cover page service. We're glad to help.

{{ config('app.name') }}<br>
Gomati District Polytechnic<br>
Fulkumari Part, Tripura 799013
</x-mail::message>
