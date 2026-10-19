<x-layouts::auth :title="__('Register')">
    <div class="d-flex flex-column gap-4">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="d-flex flex-column gap-3">
            @csrf
            
            <div>
                <label for="name" class="form-label small fw-semibold text-light">{{ __('Name') }}</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="{{ __('Full name') }}"
                />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="email" class="form-label small fw-semibold text-light">{{ __('Email address') }}</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
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

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" data-test="register-user-button">
                <i class="bi bi-person-plus me-1"></i> {{ __('Create account') }}
            </button>
        </form>

        <div class="text-center small text-secondary">
            <span>{{ __('Already have an account?') }}</span>
            <a href="{{ route('login') }}" class="text-info text-decoration-none fw-semibold ms-1" wire:navigate>{{ __('Log in') }}</a>
        </div>
    </div>
</x-layouts::auth>
