@php
    $user = auth()->user();
    $notice = session('notice');
@endphp

<x-layouts.auth title="Help">
    <h1 class="h3 font-display mb-4">Help</h1>

    @if ($notice)
        <div class="alert alert-success small" role="status"><i class="fa-solid fa-circle-check me-1" aria-hidden="true"></i> {{ $notice }}</div>
    @endif

    <form method="POST" action="{{ route('help') }}" enctype="multipart/form-data" class="vstack gap-3" novalidate>
        @csrf
        <div>
            <label class="form-label" for="name">Full name</label>
            <input id="name" name="name" @class(['form-control', 'is-invalid' => $errors->has('name')]) autocomplete="name" required minlength="2" maxlength="100" value="{{ old('name', $user?->name) }}">
            <x-field-error for="name">Enter your name.</x-field-error>
        </div>

        <div>
            <label class="form-label" for="email">Email</label>
            <input id="email" name="email" type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) autocomplete="email" required maxlength="255" value="{{ old('email', $user?->email) }}">
            <x-field-error for="email">Enter your email address, so we can reply.</x-field-error>
        </div>

        <div>
            <label class="form-label" for="category">What's it about?</label>
            <select id="category" name="category" @class(['form-select', 'is-invalid' => $errors->has('category')]) required>
                <option value="">Choose…</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->value }}" @selected(old('category') === $category->value) @if ($category->needsTransactionId()) data-needs-transaction @endif>{{ $category->label() }}</option>
                @endforeach
            </select>
            <x-field-error for="category">Choose what the problem is about.</x-field-error>
        </div>

        <div>
            <label class="form-label" for="transaction_id">Transaction ID (UTR) <span class="text-body-secondary fw-normal" data-transaction-hint>— for payments and refunds</span></label>
            <input id="transaction_id" name="transaction_id" @class(['form-control font-monospace', 'is-invalid' => $errors->has('transaction_id')]) maxlength="100" autocomplete="off" value="{{ old('transaction_id') }}">
            <x-field-error for="transaction_id">Enter the transaction ID (UTR) from your payment app.</x-field-error>
        </div>

        <div>
            <label class="form-label" for="message">What happened?</label>
            <textarea id="message" name="message" rows="5" @class(['form-control', 'is-invalid' => $errors->has('message')]) required minlength="10" maxlength="2000" placeholder="E.g. I paid ₹10 at 4:15 pm but the PDF didn't download.">{{ old('message') }}</textarea>
            <x-field-error for="message">Tell us what happened (at least 10 characters).</x-field-error>
        </div>

        <div>
            <label class="form-label" for="screenshot">Screenshot <span class="text-body-secondary fw-normal">— optional</span></label>
            <input id="screenshot" name="screenshot" type="file" accept="image/jpeg,image/png,image/webp" @class(['form-control', 'is-invalid' => $errors->has('screenshot')])>
            <div class="form-text">The payment screen or the error you saw. JPG, PNG or WebP, up to 4 MB.</div>
            <x-field-error for="screenshot">Use a JPG, PNG or WebP image up to 4 MB.</x-field-error>
        </div>

        <x-recaptcha />

        <button type="submit" class="btn btn-success w-100 mt-2" data-busy="Sending…"><i class="fa-solid fa-paper-plane me-1" aria-hidden="true"></i> Raise ticket</button>
    </form>

    <p class="small text-center mt-4 mb-0">
        Refunds follow our <a href="#" data-bs-toggle="modal" data-bs-target="#terms-modal">terms and conditions</a>.
    </p>

    <x-terms-modal />
</x-layouts.auth>
