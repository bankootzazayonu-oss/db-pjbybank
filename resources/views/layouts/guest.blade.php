<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CineReview') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Prompt:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-dark text-light min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
        <div class="container d-flex flex-column align-items-center">
            <div class="mb-4 text-center">
                <a href="/" class="text-decoration-none">
                    <img src="{{ asset('images/logo.png') }}" class="rounded-3 shadow" style="height: 52px; width: auto; object-fit: contain;" alt="CineReview Logo">
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

