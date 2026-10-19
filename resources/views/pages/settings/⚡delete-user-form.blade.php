<?php

use Livewire\Component;

new class extends Component {}; ?>

<section class="mt-5 pt-4 border-top border-secondary border-opacity-25">
    <div class="mb-3">
        <h4 class="h5 fw-bold text-danger mb-1">{{ __('Delete account') }}</h4>
        <p class="text-secondary small mb-3">{{ __('Delete your account and all of its resources') }}</p>
    </div>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal" data-test="delete-user-button">
        <i class="bi bi-trash3 me-1"></i> {{ __('Delete account') }}
    </button>

    <livewire:pages::settings.delete-user-modal />
</section>
