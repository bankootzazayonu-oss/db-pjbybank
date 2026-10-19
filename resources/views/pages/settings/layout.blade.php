<div class="row g-4 align-items-start">
    <div class="col-md-3">
        <div class="list-group list-group-flush border border-secondary rounded-3 overflow-hidden shadow-sm">
            <a href="{{ route('profile.edit') }}" wire:navigate class="list-group-item list-group-item-action bg-dark text-light border-secondary {{ request()->routeIs('profile.edit') ? 'active bg-primary' : '' }}">
                <i class="bi bi-person me-2"></i>{{ __('Profile') }}
            </a>
            <a href="{{ route('security.edit') }}" wire:navigate class="list-group-item list-group-item-action bg-dark text-light border-secondary {{ request()->routeIs('security.edit') ? 'active bg-primary' : '' }}">
                <i class="bi bi-shield-lock me-2"></i>{{ __('Security') }}
            </a>
            <a href="{{ route('appearance.edit') }}" wire:navigate class="list-group-item list-group-item-action bg-dark text-light border-secondary {{ request()->routeIs('appearance.edit') ? 'active bg-primary' : '' }}">
                <i class="bi bi-palette me-2"></i>{{ __('Appearance') }}
            </a>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card bg-dark border-secondary p-4 rounded-4 shadow-sm">
            <h4 class="fw-bold text-white mb-1">{{ $heading ?? '' }}</h4>
            <p class="text-secondary small mb-4">{{ $subheading ?? '' }}</p>

            <div class="w-100" style="max-width: 600px;">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
