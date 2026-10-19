<x-layouts::auth :title="__('Forgot password')">
    <div class="d-flex flex-column gap-4">
        <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="d-flex flex-column gap-3">
            @csrf

            <div>
                <label for="email" class="form-label small fw-semibold text-light">{{ __('Email address') }}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="email@example.com"
                />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" data-test="email-password-reset-link-button">
                <i class="bi bi-envelope me-1"></i> {{ __('Email password reset link') }}
            </button>
        </form>

        <div class="text-center small text-secondary">
            <span>{{ __('Or, return to') }}</span>
            <a href="{{ route('login') }}" class="text-info text-decoration-none fw-semibold ms-1" wire:navigate>{{ __('log in') }}</a>
        </div>
    </div>
</x-layouts::auth>
