@assets
@vite('resources/js/passkeys.js')
@endassets

<div
    x-data="{
        supported: false,
        showForm: false,
        name: '',
        loading: false,
        error: null,
        updateSupport() {
            this.supported = Boolean(window.Passkeys?.isSupported());
        },
        getDefaultPasskeyName() {
            const ua = navigator.userAgent;

            const browser = [
                { pattern: /Edg|Edge/, name: 'Edge' },
                { pattern: /OPR|Opera|OPiOS/, name: 'Opera' },
                { pattern: /Firefox|FxiOS/, name: 'Firefox' },
                { pattern: /Chrome|CriOS/, name: 'Chrome' },
                { pattern: /Safari/, name: 'Safari' },
            ].find(({ pattern }) => pattern.test(ua))?.name;

            const os = [
                { pattern: /iPhone/, name: 'iPhone' },
                { pattern: /iPad|Macintosh(?=.*Mobile)/, name: 'iPad' },
                { pattern: /Android/, name: 'Android' },
                { pattern: /Mac/, name: 'Mac' },
                { pattern: /Windows/, name: 'Windows' },
            ].find(({ pattern }) => pattern.test(ua))?.name;

            return [browser, os].filter(Boolean).join(' on ') || '';
        },
        init() {
            this.name = this.getDefaultPasskeyName();
            this.updateSupport();

            window.addEventListener('passkeys:ready', () => this.updateSupport(), { once: true });
        },
        async register() {
            if (!this.name.trim()) return;

            this.loading = true;
            this.error = null;

            try {
                await window.Passkeys.register({ name: this.name });
                this.name = '';
                this.showForm = false;
                await $wire.loadPasskeys();
            } catch (e) {
                if (e.constructor?.name !== 'UserCancelledError') {
                    this.error = e.message;
                }
            } finally {
                this.loading = false;
            }
        },
        cancel() {
            this.showForm = false;
            this.name = '';
            this.error = null;
        },
    }"
>
    <template x-if="!supported">
        <div class="text-secondary small">{{ __('Passkeys are not supported in this browser.') }}</div>
    </template>

    <template x-if="supported && !showForm">
        <div>
            <button
                type="button"
                class="btn btn-primary d-inline-flex align-items-center gap-1.5"
                x-on:click="showForm = true"
            >
                <i class="bi bi-plus-lg"></i>
                <span>{{ __('Add passkey') }}</span>
            </button>
        </div>
    </template>

    <template x-if="supported && showForm">
        <div class="card bg-dark border-secondary p-3 mb-3">
            <div class="d-flex flex-column gap-2">
                <div>
                    <label class="form-label text-light small fw-bold mb-1">{{ __('Passkey name') }}</label>
                    <input
                        type="text"
                        class="form-control bg-dark border-secondary text-light"
                        x-model="name"
                        placeholder="{{ __('e.g., MacBook Pro, iPhone') }}"
                        x-on:keydown.enter.prevent="register()"
                        x-ref="passkeyNameInput"
                        x-init="$nextTick(() => $refs.passkeyNameInput?.focus())"
                    />
                </div>
                <div class="small text-secondary">{{ __('Give this passkey a name to help you identify it later.') }}</div>

                <p x-show="error" x-text="error" x-cloak class="small text-danger mb-0"></p>

                <div class="d-flex gap-2 mt-2">
                    <button
                        type="button"
                        class="btn btn-primary"
                        x-on:click="register()"
                        x-bind:disabled="loading || !name.trim()"
                    >
                        <span x-show="!loading">{{ __('Register passkey') }}</span>
                        <span x-show="loading" x-cloak>{{ __('Registering...') }}</span>
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        x-on:click="cancel()"
                    >
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
