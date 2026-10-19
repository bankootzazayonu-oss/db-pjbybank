<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-dark text-light min-vh-100">
        <div class="container-fluid min-vh-100 p-0">
            <div class="row g-0 min-vh-100">
                <div class="col-lg-6 bg-black d-none d-lg-flex flex-column justify-content-between p-5 border-end border-secondary position-relative">
                    <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none text-light fs-5 fw-bold" wire:navigate>
                        <x-app-logo-icon style="width: 32px; height: 32px;" class="text-light" />
                        <span>{{ config('app.name', 'CineReview') }}</span>
                    </a>

                    @php
                        [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                    @endphp

                    <div class="mt-auto">
                        <blockquote class="blockquote text-light">
                            <p class="fs-4 fst-italic">&ldquo;{{ trim($message) }}&rdquo;</p>
                            <footer class="blockquote-footer text-secondary">{{ trim($author) }}</footer>
                        </blockquote>
                    </div>
                </div>

                <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-md-5">
                    <div class="w-100" style="max-width: 400px;">
                        <div class="d-lg-none text-center mb-4">
                            <a href="{{ route('home') }}" class="text-decoration-none" wire:navigate>
                                <x-app-logo-icon style="width: 44px; height: 44px;" class="text-light" />
                            </a>
                        </div>
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
