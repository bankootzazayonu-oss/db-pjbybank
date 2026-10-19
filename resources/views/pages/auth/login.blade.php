<x-layouts::auth :title="__('Log in')">
    <div class="d-flex flex-column gap-4">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="d-flex flex-column gap-3">
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
                    autocomplete="email"
                    placeholder="email@example.com"
                />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="position-relative">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label for="password" class="form-label small fw-semibold text-light mb-0">{{ __('Password') }}</label>
                    @if (Route::has('password.request'))
                        <a class="small text-decoration-none text-info" href="{{ route('password.request') }}" wire:navigate>
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
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

            <div class="form-check">
                <input class="form-check-input bg-dark border-secondary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small text-secondary" for="remember">
                    {{ __('Remember me') }}
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" data-test="login-button">
                <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('Log in') }}
            </button>
        </form>

        <div class="text-center small text-secondary">
            <span>{{ __('Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}" class="text-info text-decoration-none fw-semibold ms-1" wire:navigate>{{ __('Sign up') }}</a>
        </div>
    </div>
</x-layouts::auth>
