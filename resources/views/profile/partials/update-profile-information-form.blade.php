<section>
    <header class="mb-4">
        <h2 class="h5 fw-bold text-white mb-1">
            {{ __('Profile Information') }}
        </h2>

        <p class="text-secondary small mb-0">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label text-light small fw-bold">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" class="form-control bg-dark border-secondary text-light @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="form-label text-light small fw-bold">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control bg-dark border-secondary text-light @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-secondary mb-1">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link text-primary text-decoration-none p-0 small">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success small py-2 px-3 mb-0">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary fw-semibold px-4">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-success small fw-medium mb-0"
                ><i class="bi bi-check-circle-fill me-1"></i>{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
