<?php

use App\Concerns\ProfileValidationRules;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty('email') || $user->isDirty('Email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Session::flash('status', 'profile-updated');
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return ! Auth::user() instanceof MustVerifyEmail
            || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }

}; ?>

<section class="w-100">
    @include('partials.settings-heading')

    <h2 class="visually-hidden">{{ __('Profile settings') }}</h2>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show small mb-3" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ __('Profile updated.') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form wire:submit="updateProfileInformation" class="my-4 w-100 d-flex flex-column gap-3">
            <div>
                <label class="form-label text-light small fw-bold mb-1">{{ __('Name') }}</label>
                <input wire:model="name" type="text" required autofocus autocomplete="name" class="form-control bg-dark border-secondary text-light">
            </div>

            <div>
                <label class="form-label text-light small fw-bold mb-1">{{ __('Email') }}</label>
                <input wire:model="email" type="email" required autocomplete="email" class="form-control bg-dark border-secondary text-light">

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <div class="mt-3 text-secondary small">
                            {{ __('Your email address is unverified.') }}

                            <a href="#" class="small text-decoration-underline" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </a>
                        </div>

                        @if (session('status') === 'verification-link-sent')
                            <div class="mt-2 fw-medium text-success small">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-end mt-2">
                <button type="submit" class="btn btn-primary px-4 py-2" data-test="update-profile-button">
                    {{ __('Save') }}
                </button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
