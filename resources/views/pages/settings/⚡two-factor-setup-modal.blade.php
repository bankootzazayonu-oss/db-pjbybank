<?php

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showVerificationStep = false;

    public bool $setupComplete = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    public function mount(bool $requiresConfirmation): void
    {
        $this->requiresConfirmation = $requiresConfirmation;
    }

    #[On('start-two-factor-setup')]
    public function startTwoFactorSetup(): void
    {
        $enableTwoFactorAuthentication = app(EnableTwoFactorAuthentication::class);
        $enableTwoFactorAuthentication(auth()->user());

        $this->loadSetupData();
    }

    private function loadSetupData(): void
    {
        $user = auth()->user()?->fresh();

        try {
            if (! $user || ! $user->two_factor_secret) {
                throw new Exception('Two-factor setup secret is not available.');
            }

            $this->qrCodeSvg = $user->twoFactorQrCodeSvg();
            $this->manualSetupKey = decrypt($user->two_factor_secret);
        } catch (Exception) {
            $this->addError('setupData', 'Failed to fetch setup data.');

            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;

            $this->resetErrorBag();

            return;
        }

        $this->closeModal();
        $this->dispatch('two-factor-enabled');
    }

    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        $this->validate();

        $confirmTwoFactorAuthentication(auth()->user(), $this->code);

        $this->setupComplete = true;

        $this->closeModal();

        $this->dispatch('two-factor-enabled');
    }

    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');

        $this->resetErrorBag();
    }

    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showVerificationStep',
            'setupComplete',
        );

        $this->resetErrorBag();
    }

    #[Computed]
    public function modalConfig(): array
    {
        if ($this->setupComplete) {
            return [
                'title' => __('Two-factor authentication enabled'),
                'description' => __('Two-factor authentication is now enabled. Scan the QR code or enter the setup key in your authenticator app.'),
                'buttonText' => __('Close'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verify authentication code'),
                'description' => __('Enter the 6-digit code from your authenticator app.'),
                'buttonText' => __('Continue'),
            ];
        }

        return [
            'title' => __('Enable two-factor authentication'),
            'description' => __('To finish enabling two-factor authentication, scan the QR code or enter the setup key in your authenticator app.'),
            'buttonText' => __('Continue'),
        ];
    }
}; ?>

<div class="modal fade" id="twoFactorSetupModal" tabindex="-1" aria-labelledby="twoFactorSetupModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary border-opacity-25">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle p-2 bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="bi bi-qr-code text-info"></i>
                    </div>
                    <h5 class="modal-title text-light fw-bold" id="twoFactorSetupModalLabel">
                        {{ $this->modalConfig['title'] }}
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" wire:click="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-light">
                <p class="text-secondary small mb-3">
                    {{ $this->modalConfig['description'] }}
                </p>

                @if ($showVerificationStep)
                    <div class="d-flex flex-column gap-3">
                        <div class="text-center my-3">
                            <label for="two_factor_code" class="form-label small fw-semibold text-secondary">{{ __('6-Digit Code') }}</label>
                            <input
                                id="two_factor_code"
                                type="text"
                                wire:model="code"
                                maxlength="6"
                                class="form-control form-control-lg text-center font-monospace bg-dark text-light border-secondary mx-auto @error('code') is-invalid @enderror"
                                style="letter-spacing: 0.4em; max-width: 220px; font-size: 1.5rem;"
                                placeholder="000000"
                                autofocus
                            />
                            @error('code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button
                                type="button"
                                class="btn btn-outline-secondary flex-fill"
                                wire:click="resetVerification"
                            >
                                {{ __('Back') }}
                            </button>

                            <button
                                type="button"
                                class="btn btn-primary flex-fill"
                                wire:click="confirmTwoFactor"
                                {{ strlen($code) < 6 ? 'disabled' : '' }}
                            >
                                {{ __('Confirm') }}
                            </button>
                        </div>
                    </div>
                @else
                    @error('setupData')
                        <div class="alert alert-danger small mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ $message }}
                        </div>
                    @enderror

                    <div class="d-flex justify-content-center my-3">
                        <div class="position-relative overflow-hidden border border-secondary rounded p-2 bg-white" style="width: 240px; height: 240px;">
                            @empty($qrCodeSvg)
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark">
                                    <div class="spinner-border text-info" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    {!! $qrCodeSvg !!}
                                </div>
                            @endempty
                        </div>
                    </div>

                    <div class="mb-3">
                        <button
                            type="button"
                            class="btn btn-primary w-100"
                            wire:click="showVerificationIfNecessary"
                            {{ $errors->has('setupData') ? 'disabled' : '' }}
                        >
                            {{ $this->modalConfig['buttonText'] }}
                        </button>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <div class="position-relative text-center my-2">
                            <hr class="m-0 border-secondary opacity-25">
                            <span class="position-absolute top-50 start-50 translate-middle px-2 small bg-dark text-secondary">
                                {{ __('or, enter the code manually') }}
                            </span>
                        </div>

                        <div
                            x-data="{
                                copied: false,
                                async copy() {
                                    try {
                                        await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                        this.copied = true;
                                        setTimeout(() => this.copied = false, 1500);
                                    } catch (e) {
                                        console.warn('Could not copy');
                                    }
                                }
                            }"
                        >
                            <div class="input-group">
                                @empty($manualSetupKey)
                                    <div class="d-flex align-items-center justify-content-center w-100 p-2 bg-dark">
                                        <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                    </div>
                                @else
                                    <input
                                        type="text"
                                        readonly
                                        value="{{ $manualSetupKey }}"
                                        class="form-control bg-dark text-light border-secondary font-monospace small"
                                    />

                                    <button
                                        type="button"
                                        @click="copy()"
                                        class="btn btn-outline-secondary"
                                        title="{{ __('Copy key') }}"
                                    >
                                        <i class="bi bi-clipboard" x-show="!copied"></i>
                                        <i class="bi bi-check-lg text-success" x-show="copied"></i>
                                    </button>
                                @endempty
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
