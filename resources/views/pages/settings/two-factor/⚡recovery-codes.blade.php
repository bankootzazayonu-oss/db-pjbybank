<?php

use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public array $recoveryCodes = [];

    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $generateNewRecoveryCodes(auth()->user());

        $this->loadRecoveryCodes();
    }

    private function loadRecoveryCodes(): void
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication() && $user->two_factor_recovery_codes) {
            try {
                $this->recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            } catch (Exception) {
                $this->addError('recoveryCodes', 'Failed to load recovery codes');

                $this->recoveryCodes = [];
            }
        }
    }
}; ?>

<div
    class="card bg-dark border-secondary p-4 my-3"
    wire:cloak
    x-data="{ showRecoveryCodes: false }"
>
    <div class="d-flex flex-column gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock text-info fs-5"></i>
            <h5 class="fw-bold text-light mb-0">{{ __('2FA recovery codes') }}</h5>
        </div>
        <p class="text-secondary small mb-0">
            {{ __('Recovery codes let you regain access if you lose your 2FA device. Store them in a secure password manager.') }}
        </p>
    </div>

    <div>
        <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center justify-content-between">
            <button
                type="button"
                class="btn btn-primary btn-sm"
                x-show="!showRecoveryCodes"
                @click="showRecoveryCodes = true;"
                aria-expanded="false"
                aria-controls="recovery-codes-section"
            >
                <i class="bi bi-eye me-1"></i> {{ __('View recovery codes') }}
            </button>

            <button
                type="button"
                class="btn btn-outline-secondary btn-sm"
                x-show="showRecoveryCodes"
                @click="showRecoveryCodes = false"
                aria-expanded="true"
                aria-controls="recovery-codes-section"
            >
                <i class="bi bi-eye-slash me-1"></i> {{ __('Hide recovery codes') }}
            </button>

            @if (filled($recoveryCodes))
                <button
                    type="button"
                    class="btn btn-outline-info btn-sm"
                    x-show="showRecoveryCodes"
                    wire:click="regenerateRecoveryCodes"
                >
                    <i class="bi bi-arrow-repeat me-1"></i> {{ __('Regenerate codes') }}
                </button>
            @endif
        </div>

        <div
            x-show="showRecoveryCodes"
            x-transition
            id="recovery-codes-section"
            class="position-relative overflow-hidden"
            x-bind:aria-hidden="!showRecoveryCodes"
        >
            <div class="mt-3 d-flex flex-column gap-3">
                @error('recoveryCodes')
                    <div class="alert alert-danger small mb-0">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ $message }}
                    </div>
                @enderror

                @if (filled($recoveryCodes))
                    <div
                        class="p-3 font-monospace small rounded bg-body-tertiary border border-secondary border-opacity-25 d-flex flex-column gap-1"
                        role="list"
                        aria-label="{{ __('Recovery codes') }}"
                    >
                        @foreach($recoveryCodes as $code)
                            <div
                                role="listitem"
                                class="user-select-all text-light"
                                wire:loading.class="opacity-50"
                            >
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <div class="text-secondary small">
                        {{ __('Each recovery code can be used once to access your account and will be removed after use. If you need more, click Regenerate codes above.') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
