<x-layouts::auth :title="__('Email verification')">
    <div class="mt-3 d-flex flex-column gap-3">
        <div class="text-center text-secondary small">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success small text-center mb-0" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="d-flex flex-column align-items-center gap-2 mt-2">
            <form method="POST" action="{{ route('verification.send') }}" class="w-100">
                @csrf
                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    <i class="bi bi-send me-1"></i> {{ __('Resend verification email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-100 text-center">
                @csrf
                <button type="submit" class="btn btn-link text-secondary text-decoration-none small" data-test="logout-button">
                    <i class="bi bi-box-arrow-right me-1"></i> {{ __('Log out') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
