{{-- The required "I agree to the terms" box. Pair it with <x-terms-modal /> on the same page, outside the form. --}}
<div {{ $attributes->class('form-check') }}>
    <input id="terms" name="terms" type="checkbox" value="1" @class(['form-check-input', 'is-invalid' => $errors->has('terms')]) required @checked(old('terms'))>
    <label class="form-check-label small" for="terms">
        I have read and agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#terms-modal">terms and conditions</a>
    </label>
    <x-field-error for="terms">You need to accept the terms and conditions.</x-field-error>
</div>
