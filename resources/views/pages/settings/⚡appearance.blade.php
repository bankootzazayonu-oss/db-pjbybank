<?php

use Livewire\Component;
use Livewire\Attributes\Title;

new #[Title('Appearance settings')] class extends Component {

}; ?>

<section class="w-100">
    @include('partials.settings-heading')

    <h2 class="visually-hidden">{{ __('Appearance settings') }}</h2>

    <x-pages::settings.layout :heading="__('Appearance')" :subheading="__('Update the appearance settings for your account')">
        <div class="btn-group w-100" role="group">
            <input type="radio" class="btn-check" name="appearance" id="app-light" autocomplete="off" value="light">
            <label class="btn btn-outline-secondary" for="app-light"><i class="bi bi-sun me-1"></i>{{ __('Light') }}</label>

            <input type="radio" class="btn-check" name="appearance" id="app-dark" autocomplete="off" value="dark" checked>
            <label class="btn btn-outline-secondary" for="app-dark"><i class="bi bi-moon me-1"></i>{{ __('Dark') }}</label>

            <input type="radio" class="btn-check" name="appearance" id="app-system" autocomplete="off" value="system">
            <label class="btn btn-outline-secondary" for="app-system"><i class="bi bi-display me-1"></i>{{ __('System') }}</label>
        </div>
    </x-pages::settings.layout>
</section>
