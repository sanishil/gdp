<?php

namespace App\Http\Controllers;

use App\Enums\TicketCategory;
use App\Models\SupportTicket;
use App\Rules\Recaptcha;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * The Help page: anyone, signed in or not, can raise a ticket (e.g. a payment or refund problem) with a screenshot.
 * Admins read and resolve them in the admin portal.
 */
class HelpController extends Controller
{
    public function create(): View
    {
        return view('help', ['categories' => TicketCategory::cases()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'name' => Str::squish((string) $request->input('name')),
            'transaction_id' => Str::squish((string) $request->input('transaction_id')) ?: null,
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'category' => ['required', Rule::enum(TicketCategory::class)],
            'transaction_id' => [
                Rule::requiredIf(fn (): bool => TicketCategory::tryFrom((string) $request->input('category'))?->needsTransactionId() ?? false),
                'nullable', 'string', 'max:100',
            ],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
            'screenshot' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'g-recaptcha-response' => [new Recaptcha($request->ip())],
        ], [
            'name.required' => 'Enter your name.',
            'name.min' => 'That name is too short.',
            'email.required' => 'Enter your email address, so we can reply.',
            'email.email' => 'Enter a valid email address.',
            'category.required' => 'Choose what the problem is about.',
            'category.enum' => 'Choose what the problem is about.',
            'transaction_id.required' => 'Enter the transaction ID (UTR) from your payment app.',
            'message.required' => 'Tell us what happened.',
            'message.min' => 'Please add a little more detail.',
            'message.max' => 'Keep it under 2,000 characters.',
            'screenshot.file' => 'Choose an image.',
            'screenshot.mimes' => 'Use a JPG, PNG or WebP image.',
            'screenshot.max' => 'That image is larger than 4 MB.',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => $request->user()?->id,
            'name' => $validated['name'],
            'email' => Str::lower($validated['email']),
            'category' => $validated['category'],
            'transaction_id' => $validated['transaction_id'],
            'message' => trim($validated['message']),
            'screenshot_path' => $request->file('screenshot')?->store(SupportTicket::SCREENSHOT_FOLDER, 'local'),
        ]);

        return redirect()->route('help')->with('notice', "Ticket {$ticket->reference} raised. We'll reply to {$ticket->email}.");
    }
}
