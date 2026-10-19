<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-dark text-light min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
        <div class="container d-flex flex-column align-items-center" style="max-width: 440px;">
            <div class="mb-4 text-center">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <x-app-logo-icon style="width: 44px; height: 44px;" class="text-light" />
                </a>
            </div>
            <div class="w-100">
                {{ $slot }}
            </div>
        </div>

    </body>
</html>
