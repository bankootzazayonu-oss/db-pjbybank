<x-layouts::auth :title="__('Two-factor authentication')">
    <div class="d-flex flex-column gap-4">
        <div
            class="position-relative w-100"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                focusOtp() {
                    this.$nextTick(() => this.$refs.code?.focus());
                },
                init() {
                    if (! this.showRecoveryInput) {
                        this.focusOtp();
                    }
                },
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;

                    this.code = '';
                    this.recovery_code = '';

                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : this.focusOtp();
                    });
                },
            }"
        >
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    :title="__('Authentication code')"
                    :description="__('Enter the 6-digit authentication code from your authenticator application.')"
                />
            </div>

            <div x-show="showRecoveryInput">
                <x-auth-header
                    :title="__('Recovery code')"
                    :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}">
                @csrf

                <div class="d-flex flex-column gap-3 text-center">
                    <div x-show="!showRecoveryInput">
                        <div class="my-3">
                            <input
                                type="text"
                                name="code"
                                x-ref="code"
                                x-model="code"
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
                    </div>

                    <div x-show="showRecoveryInput">
                        <div class="my-3 text-start">
                            <label for="recovery_code" class="form-label small fw-semibold text-light">{{ __('Recovery code') }}</label>
                            <input
                                type="text"
                                id="recovery_code"
                                name="recovery_code"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                                class="form-control bg-dark text-light border-secondary @error('recovery_code') is-invalid @enderror"
                                placeholder="•••••-•••••"
                            />
                            @error('recovery_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100 py-2 fw-semibold"
                    >
                        <i class="bi bi-shield-check me-1"></i> {{ __('Continue') }}
                    </button>
                </div>

                <div class="mt-4 text-center small">
                    <span class="text-secondary">{{ __('or you can') }}</span>
                    <div class="d-inline fw-medium">
                        <a href="javascript:void(0)" class="text-info text-decoration-none fw-semibold ms-1" x-show="!showRecoveryInput" @click="toggleInput()">{{ __('login using a recovery code') }}</a>
                        <a href="javascript:void(0)" class="text-info text-decoration-none fw-semibold ms-1" x-show="showRecoveryInput" @click="toggleInput()">{{ __('login using an authentication code') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts::auth>
