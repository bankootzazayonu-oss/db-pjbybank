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

<flux:modal
    name="two-factor-setup-modal"
    @close="closeModal"
>
    <div class="d-flex flex-column gap-3">
        <div class="d-flex flex-column align-items-center gap-2">
            <div class="rounded-circle p-2 bg-body-secondary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <flux:icon.qr-code class="size-6 text-primary"/>
            </div>

            <div class="text-center">
                <flux:heading size="lg">{{ $this->modalConfig['title'] }}</flux:heading>
                <flux:text>{{ $this->modalConfig['description'] }}</flux:text>
            </div>
        </div>

        @if ($showVerificationStep)
            <div class="d-flex flex-column gap-3">
                <div
                    class="d-flex flex-column align-items-center gap-2 justify-content-center"
                    x-data
                    x-init="$nextTick(() => $el.querySelector('input')?.focus())"
                >
                    <flux:otp
                        name="code"
                        wire:model="code"
                        length="6"
                        label="OTP Code"
                        label:sr-only
                        class="mx-auto"
                    />
                </div>

                <div class="d-flex align-items-center gap-2">
                    <flux:button
                        variant="outline"
                        class="flex-fill"
                        wire:click="resetVerification"
                    >
                        {{ __('Back') }}
                    </flux:button>

                    <flux:button
                        variant="primary"
                        class="flex-fill"
                        wire:click="confirmTwoFactor"
                        x-bind:disabled="$wire.code.length < 6"
                    >
                        {{ __('Confirm') }}
                    </flux:button>
                </div>
            </div>
        @else
            @error('setupData')
                <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}"/>
            @enderror

            <div class="d-flex justify-content-center">
                <div class="position-relative overflow-hidden border rounded p-2" style="width: 256px; height: 256px;">
                    @empty($qrCodeSvg)
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-body-secondary">
                            <flux:icon.loading/>
                        </div>
                    @else
                        <div x-data class="d-flex align-items-center justify-content-center h-100 p-2">
                            <div
                                class="bg-white p-3 rounded"
                                :style="($flux.appearance === 'dark' || ($flux.appearance === 'system' && $flux.dark)) ? 'filter: invert(1) brightness(1.5)' : ''"
                            >
                                {!! $qrCodeSvg !!}
                            </div>
                        </div>
                    @endempty
                </div>
            </div>

            <div>
                <flux:button
                    :disabled="$errors->has('setupData')"
                    variant="primary"
                    class="w-100"
                    wire:click="showVerificationIfNecessary"
                >
                    {{ $this->modalConfig['buttonText'] }}
                </flux:button>
            </div>

            <div class="d-flex flex-column gap-3">
                <div class="position-relative text-center my-2">
                    <hr class="m-0 text-secondary opacity-25">
                    <span class="position-absolute top-50 start-50 translate-middle px-2 small bg-body text-secondary">
                        {{ __('or, enter the code manually') }}
                    </span>
                </div>

                <div
                    class="d-flex align-items-center"
                    x-data="{
                        copied: false,
                        async copy() {
                            try {
                                await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                this.copied = true;
                                setTimeout(() => this.copied = false, 1500);
                            } catch (e) {
                                console.warn('Could not copy to clipboard');
                            }
                        }
                    }"
                >
                    <div class="input-group">
                        @empty($manualSetupKey)
                            <div class="d-flex align-items-center justify-content-center w-100 p-2 bg-body-secondary">
                                <flux:icon.loading variant="mini"/>
                            </div>
                        @else
                            <input
                                type="text"
                                readonly
                                value="{{ $manualSetupKey }}"
                                class="form-control font-monospace"
                            />

                            <button
                                type="button"
                                @click="copy()"
                                class="btn btn-outline-secondary"
                            >
                                <flux:icon.document-duplicate x-show="!copied" variant="outline"></flux:icon>
                                <flux:icon.check
                                    x-show="copied"
                                    variant="solid"
                                    class="text-success"
                                ></flux:icon>
                            </button>
                        @endempty
                    </div>
                </div>
            </div>
        @endif
    </div>
</flux:modal>

