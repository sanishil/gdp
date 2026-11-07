<?php

namespace App\Http\Controllers\Portal;

use App\Enums\TicketCategory;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Mail\TicketResolvedMail;
use App\Models\SupportTicket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Help tickets in the admin portal (/admin/tickets): read them, open the screenshot, resolve them (the remarks are
 * emailed to the person with a thank-you) or open them again, and delete them (with their screenshot).
 */
class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        return view('portal.tickets', [
            'portal' => $request->user()->role->value,
            'tickets' => SupportTicket::orderByRaw('status = ? desc', [TicketStatus::Open->value])->latest()->get(),
            'categories' => TicketCategory::cases(),
        ]);
    }

    /**
     * Screenshots are on the private disk, so they're only reachable through this admin-only route.
     */
    public function showScreenshot(SupportTicket $ticket): StreamedResponse
    {
        abort_unless($ticket->screenshot_path && Storage::disk('local')->exists($ticket->screenshot_path), 404);

        return Storage::disk('local')->response($ticket->screenshot_path, headers: [
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    /**
     * The ticket stays open if the email can't be sent, so the person is never left without a reply.
     */
    public function resolve(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validateWithBag('resolveTicket', [
            'remarks' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'remarks.required' => 'Write what was done, so the person knows.',
            'remarks.min' => 'Please add a little more detail.',
            'remarks.max' => 'Keep it under 2,000 characters.',
        ]);

        try {
            DB::transaction(function () use ($ticket, $validated): void {
                $ticket->forceFill([
                    'status' => TicketStatus::Resolved,
                    'resolution_remarks' => trim($validated['remarks']),
                    'resolved_at' => now(),
                ])->save();

                Mail::to($ticket->email)->send(new TicketResolvedMail($ticket));
            });
        } catch (TransportExceptionInterface $exception) {
            report($exception);
            $ticket->refresh();

            return redirect()->route('admin.tickets')->with('error', "We couldn't email {$ticket->email} just now, so ticket {$ticket->reference} is still open. Please try again.");
        }

        return redirect()->route('admin.tickets')->with('status', "Ticket {$ticket->reference} resolved. Your remarks were emailed to {$ticket->email}.");
    }

    public function reopen(SupportTicket $ticket): RedirectResponse
    {
        $ticket->forceFill(['status' => TicketStatus::Open, 'resolution_remarks' => null, 'resolved_at' => null])->save();

        return redirect()->route('admin.tickets')->with('status', "Ticket {$ticket->reference} is open again.");
    }

    public function destroy(SupportTicket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()->route('admin.tickets')->with('status', "Ticket {$ticket->reference} deleted.");
    }
}
