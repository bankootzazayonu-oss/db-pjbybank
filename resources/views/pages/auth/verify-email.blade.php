<x-layouts::auth :title="__('Email verification')">
    <div class="mt-3 d-flex flex-column gap-3">
        <div class="text-center text-secondary small">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="text-center fw-medium text-success small">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="d-flex flex-column align-items-center gap-2 mt-2">
            <form method="POST" action="{{ route('verification.send') }}" class="w-100">
                @csrf
                <flux:button type="submit" variant="primary" class="w-100">
                    {{ __('Resend verification email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-100 text-center">
                @csrf
                <flux:button variant="ghost" type="submit" class="small" data-test="logout-button">
                    {{ __('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
