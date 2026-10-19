<x-layouts::auth :title="__('Reset password')">
    <div class="d-flex flex-column gap-4">
        <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="d-flex flex-column gap-3">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div>
                <label for="email" class="form-label small fw-semibold text-light">{{ __('Email') }}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror"
                    value="{{ request('email') }}"
                    required
                    autocomplete="email"
                    placeholder="email@example.com"
                />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label small fw-semibold text-light">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="form-label small fw-semibold text-light">{{ __('Confirm password') }}</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="form-control bg-dark text-light border-secondary @error('password_confirmation') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" data-test="reset-password-button">
                <i class="bi bi-arrow-repeat me-1"></i> {{ __('Reset password') }}
            </button>
        </form>
    </div>
</x-layouts::auth>
