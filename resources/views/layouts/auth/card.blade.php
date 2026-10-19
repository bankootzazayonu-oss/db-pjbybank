<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-dark text-light min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
        <div class="container d-flex flex-column align-items-center">
            <div class="mb-4 text-center">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <x-app-logo-icon style="width: 48px; height: 48px;" class="text-light" />
                </a>
            </div>

            <div class="card bg-dark border-secondary shadow-lg w-100" style="max-width: 480px; border-radius: 1rem;">
                <div class="card-body p-4 p-sm-5">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </body>
</html>
