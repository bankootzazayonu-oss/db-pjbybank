<x-layouts::auth :title="__('Confirm password')">
    <div class="d-flex flex-column gap-4">
        <x-auth-header
            :title="__('Confirm password')"
            :description="__('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify
            options-route="passkey.confirm-options"
            submit-route="passkey.confirm"
            :label="__('Confirm with passkey')"
            :loading-label="__('Confirming...')"
            :separator="__('Or confirm with password')"
        />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="d-flex flex-column gap-3">
            @csrf

            <div>
                <label for="password" class="form-label small fw-semibold text-light">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" data-test="confirm-password-button">
                <i class="bi bi-shield-check me-1"></i> {{ __('Confirm') }}
            </button>
        </form>
    </div>
</x-layouts::auth>
