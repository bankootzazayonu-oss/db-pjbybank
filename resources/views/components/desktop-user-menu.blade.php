<div class="dropdown">
    <button class="btn btn-dark border-secondary w-100 d-flex align-items-center justify-content-between p-2 dropdown-toggle" type="button" id="sidebarMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-test="sidebar-menu-button">
        <div class="d-flex align-items-center gap-2 overflow-hidden" style="pointer-events: none;">
            <div class="rounded-circle bg-primary bg-opacity-25 text-primary border border-primary d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 13px;">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="text-start overflow-hidden">
                <div class="small fw-bold text-white text-truncate">{{ auth()->user()->name }}</div>
                <div class="text-secondary small text-truncate" style="font-size: 11px;">{{ auth()->user()->email }}</div>
            </div>
        </div>
    </button>

    <ul class="dropdown-menu dropdown-menu-dark border-secondary shadow w-100 mt-1" aria-labelledby="sidebarMenuDropdown">
        <li>
            <div class="px-3 py-2 border-bottom border-secondary">
                <div class="small fw-bold text-white">{{ auth()->user()->name }}</div>
                <div class="small text-secondary text-truncate">{{ auth()->user()->email }}</div>
            </div>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                <i class="bi bi-gear"></i>
                <span>{{ __('Settings') }}</span>
            </a>
        </li>
        <li><hr class="dropdown-divider border-secondary"></li>
        <li>
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2" data-test="logout-button">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>{{ __('Log out') }}</span>
                </button>
            </form>
        </li>
    </ul>
</div>
