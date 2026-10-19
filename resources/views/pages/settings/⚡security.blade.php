<?php

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Title;
use Livewire\Component;

use Laravel\Passkeys\Actions\DeletePasskey;
use Livewire\Attributes\Locked;

use Livewire\Attributes\On;

new #[Title('Security settings')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $canManageTwoFactor;

    public bool $twoFactorEnabled;

    public bool $requiresConfirmation;

    #[Locked]
    public bool $canManagePasskeys;

    #[Locked]
    public array $passkeys = [];

    public bool $showDeleteModal = false;

    #[Locked]
    public ?int $deletingPasskeyId = null;

    #[Locked]
    public string $deletingPasskeyName = '';

    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $this->canManageTwoFactor = Features::canManageTwoFactorAuthentication();

        if ($this->canManageTwoFactor) {
            if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
                $disableTwoFactorAuthentication(auth()->user());
            }

            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
            $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
        }

        $this->canManagePasskeys = Features::canManagePasskeys();

        if ($this->canManagePasskeys) {
            $this->loadPasskeys();
        }
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('status', 'password-updated');
    }

    public function loadPasskeys(): void
    {
        $this->passkeys = auth()->user()->passkeys()
            ->select(['id', 'name', 'credential', 'created_at', 'last_used_at'])
            ->latest()
            ->get()
            ->map(fn ($passkey) => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'authenticator' => $passkey->authenticator,
                'created_at_diff' => $passkey->created_at->diffForHumans(),
                'last_used_at_diff' => $passkey->last_used_at?->diffForHumans(),
            ])
            ->toArray();
    }

    public function confirmDelete(int $passkeyId): void
    {
        $passkey = auth()->user()->passkeys()->findOrFail($passkeyId);

        $this->deletingPasskeyId = $passkey->id;
        $this->deletingPasskeyName = $passkey->name;
        $this->showDeleteModal = true;
    }

    public function deletePasskey(DeletePasskey $deletePasskey): void
    {
        if (! $this->deletingPasskeyId) {
            return;
        }

        $passkey = auth()->user()->passkeys()->findOrFail($this->deletingPasskeyId);

        $deletePasskey(auth()->user(), $passkey);

        $this->closeDeleteModal();
        $this->loadPasskeys();
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingPasskeyId = null;
        $this->deletingPasskeyName = '';
    }

    #[On('two-factor-enabled')]
    public function onTwoFactorEnabled(): void
    {
        $this->twoFactorEnabled = true;
    }

    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());

        $this->twoFactorEnabled = false;
    }

}; ?>

<section class="w-100">
    @include('partials.settings-heading')

    <h2 class="visually-hidden">{{ __('Security settings') }}</h2>

    <x-pages::settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show small mb-4" role="alert">
                <i class="bi bi-check-circle me-1"></i> {{ __('Password updated successfully.') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form wire:submit="updatePassword" class="d-flex flex-column gap-3">
            <div>
                <label for="current_password" class="form-label small fw-semibold text-light">{{ __('Current password') }}</label>
                <input
                    type="password"
                    id="current_password"
                    wire:model="current_password"
                    class="form-control bg-dark text-light border-secondary @error('current_password') is-invalid @enderror"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label small fw-semibold text-light">{{ __('New password') }}</label>
                <input
                    type="password"
                    id="password"
                    wire:model="password"
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
                    type="password"
                    id="password_confirmation"
                    wire:model="password_confirmation"
                    class="form-control bg-dark text-light border-secondary @error('password_confirmation') is-invalid @enderror"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                />
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn btn-primary" data-test="update-password-button">
                    <i class="bi bi-check2 me-1"></i> {{ __('Save') }}
                </button>
            </div>
        </form>

        @if ($canManageTwoFactor)
            <section class="mt-5 pt-4 border-top border-secondary border-opacity-25">
                <h4 class="h5 fw-bold text-light mb-1">{{ __('Two-factor authentication') }}</h4>
                <p class="text-secondary small mb-3">{{ __('Manage your two-factor authentication settings') }}</p>

                <div class="d-flex flex-column w-100 mx-auto gap-3 small" wire:cloak>
                    @if ($twoFactorEnabled)
                        <div class="d-flex flex-column gap-3">
                            <p class="text-secondary mb-0">
                                {{ __('You will be prompted for a secure, random pin during login, which you can retrieve from the TOTP-supported application on your phone.') }}
                            </p>

                            <div class="d-flex justify-content-start">
                                <button
                                    type="button"
                                    class="btn btn-danger"
                                    wire:click="disable"
                                >
                                    <i class="bi bi-shield-x me-1"></i> {{ __('Disable 2FA') }}
                                </button>
                            </div>

                            <livewire:pages::settings.two-factor.recovery-codes :$requiresConfirmation />
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            <p class="text-secondary mb-0">
                                {{ __('When you enable two-factor authentication, you will be prompted for a secure pin during login. This pin can be retrieved from a TOTP-supported application on your phone.') }}
                            </p>

                            <div>
                                <button
                                    type="button"
                                    class="btn btn-primary"
                                    wire:click="$dispatch('start-two-factor-setup')"
                                    data-bs-toggle="modal"
                                    data-bs-target="#twoFactorSetupModal"
                                >
                                    <i class="bi bi-shield-lock me-1"></i> {{ __('Enable 2FA') }}
                                </button>
                            </div>

                            <livewire:pages::settings.two-factor-setup-modal :requires-confirmation="$requiresConfirmation" />
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @if ($canManagePasskeys)
            <section class="mt-5 pt-4 border-top border-secondary border-opacity-25">
                <h4 class="h5 fw-bold text-light mb-1">{{ __('Passkeys') }}</h4>
                <p class="text-secondary small mb-3">{{ __('Manage your passkeys for passwordless sign-in') }}</p>

                <div class="mt-3 d-flex flex-column w-100 mx-auto gap-3 small" wire:cloak>
                    <div class="card bg-dark border-secondary overflow-hidden">
                        @forelse ($passkeys as $passkey)
                            <div class="d-flex align-items-center justify-content-between p-3 {{ ! $loop->last ? 'border-bottom border-secondary border-opacity-25' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex rounded-3 bg-secondary bg-opacity-25 align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="bi bi-key-fill text-info fs-5"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2">
                                            <p class="fw-medium text-light mb-0">{{ $passkey['name'] }}</p>
                                            @if ($passkey['authenticator'])
                                                <span class="badge bg-secondary">{{ $passkey['authenticator'] }}</span>
                                            @endif
                                        </div>
                                        <p class="text-secondary small mb-0">
                                            {{ __('Added :time', ['time' => $passkey['created_at_diff']]) }}
                                            @if ($passkey['last_used_at_diff'])
                                                <span class="opacity-50 mx-1">/</span>
                                                {{ __('Last used :time', ['time' => $passkey['last_used_at_diff']]) }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    wire:click="confirmDelete({{ $passkey['id'] }})"
                                    title="{{ __('Remove passkey') }}"
                                >
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        @empty
                            <div class="p-4 text-center">
                                <div class="mx-auto mb-3 d-flex rounded-4 bg-secondary bg-opacity-25 align-items-center justify-content-center" style="width: 56px; height: 56px;">
                                    <i class="bi bi-key text-secondary fs-2"></i>
                                </div>
                                <p class="fw-medium text-light mb-1">{{ __('No passkeys yet') }}</p>
                                <div class="small text-secondary">{{ __('Add a passkey to sign in without a password') }}</div>
                            </div>
                        @endforelse
                    </div>

                    <x-passkey-registration />
                </div>
            </section>
        @endif
    </x-pages::settings.layout>

    @if ($showDeleteModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.6);" role="dialog">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark border-secondary">
                    <div class="modal-header border-secondary border-opacity-25">
                        <h5 class="modal-title text-light fw-bold">
                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>{{ __('Remove passkey') }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeDeleteModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-light">
                        <p class="text-secondary mb-0">
                            {{ __('Are you sure you want to remove the passkey ":name"? You will no longer be able to use it to sign in.', ['name' => $deletingPasskeyName]) }}
                        </p>
                    </div>
                    <div class="modal-footer border-secondary border-opacity-25">
                        <button type="button" class="btn btn-secondary" wire:click="closeDeleteModal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-danger" wire:click="deletePasskey">
                            <i class="bi bi-trash3 me-1"></i> {{ __('Remove passkey') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
