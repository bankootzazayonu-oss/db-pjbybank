<?php

use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    use PasswordValidationRules;

    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => $this->currentPasswordRules(),
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary border-opacity-25">
                <h5 class="modal-title text-light fw-bold" id="confirmUserDeletionModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>{{ __('Are you sure you want to delete your account?') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" wire:submit="deleteUser">
                <div class="modal-body text-light">
                    <p class="text-secondary small mb-3">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>
                    <div class="mb-3">
                        <label for="delete_password" class="form-label small fw-semibold text-light">{{ __('Password') }}</label>
                        <input type="password" id="delete_password" wire:model="password" class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror" placeholder="••••••••" required />
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-secondary border-opacity-25">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger" data-test="confirm-delete-user-button">
                        <i class="bi bi-trash3 me-1"></i> {{ __('Delete account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
