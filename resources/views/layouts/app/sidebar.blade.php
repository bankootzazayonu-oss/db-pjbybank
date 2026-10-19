<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-vh-100 bg-dark text-light d-flex flex-column">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary sticky-top py-2">
            <div class="container-fluid px-3">
                <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

                <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#appSidebarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="appSidebarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-primary fw-semibold' : 'text-light' }}" href="{{ route('dashboard') }}" wire:navigate>
                                <i class="bi bi-grid me-1"></i>{{ __('Dashboard') }}
                            </a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-2">
                        <x-desktop-user-menu />
                    </div>
                </div>
            </div>
        </nav>

        <main class="container py-4 flex-grow-1">
            {{ $slot }}
        </main>
    </body>
</html>
