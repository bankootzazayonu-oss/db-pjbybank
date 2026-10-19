<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>รายการรออนุมัติ (แอดมิน) - {{ config('app.name', 'CineReview') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-light min-vh-100 d-flex flex-column">

    @include('layouts.navigation')

    <div class="bg-dark bg-opacity-75 border-bottom border-secondary py-3">
        <div class="container d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <h2 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-shield-check text-primary"></i>
                รายการภาพยนตร์รอการอนุมัติ (แอดมิน)
            </h2>
        </div>
    </div>

    <main class="flex-grow-1 py-5">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4 shadow-sm">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <div class="row row-cols-1 row-cols-lg-2 g-4">
                @forelse($movies as $movie)
                    <div class="col">
                        <div class="card bg-dark border-secondary rounded-4 shadow-sm h-100 overflow-hidden">
                            <div class="row g-0 h-100">
                                <div class="col-sm-4 bg-black position-relative" style="min-height: 200px;">
                                    @if($movie->image)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                             alt="{{ $movie->name }}" 
                                             class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary p-3 text-center">
                                            <i class="bi bi-film fs-2 mb-1"></i>
                                            <span class="small">ไม่มีรูปภาพ</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-sm-8 p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                                            <h3 class="h6 fw-bold text-white mb-0 text-truncate" title="{{ $movie->name }}">
                                                {{ $movie->name }}
                                            </h3>
                                            <span class="badge bg-secondary bg-opacity-50 text-light px-2 py-0.5 small">
                                                {{ $movie->year }}
                                            </span>
                                        </div>

                                        <p class="small text-secondary mb-2">
                                            {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                        </p>

                                        <p class="text-secondary small text-truncate-3 mb-3">
                                            {{ $movie->review ?: 'ไม่มีเรื่องย่อสำหรับภาพยนตร์เรื่องนี้' }}
                                        </p>
                                    </div>

                                    <div class="row g-2 pt-3 border-top border-secondary">
                                        <div class="col-6">
                                            <form action="{{ route('admin.movies.approve', $movie->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('ยืนยันการอนุมัติภาพยนตร์เรื่องนี้?')"
                                                        class="btn btn-outline-success btn-sm w-100 d-flex align-items-center justify-content-center gap-1.5 fw-semibold">
                                                    <i class="bi bi-check-lg"></i>
                                                    <span>อนุมัติ</span>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="col-6">
                                            <form action="{{ route('admin.movies.reject', $movie->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        onclick="return confirm('ต้องการปฏิเสธภาพยนตร์เรื่องนี้หรือไม่?')"
                                                        class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1.5 fw-semibold">
                                                    <i class="bi bi-x-lg"></i>
                                                    <span>ปฏิเสธ</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 bg-dark border border-secondary border-dashed rounded-4">
                        <i class="bi bi-shield-check fs-1 text-secondary mb-2 d-block"></i>
                        <p class="text-light fw-medium mb-0">ไม่มีรายการรออนุมัติ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <footer class="border-top border-secondary bg-dark py-4 mt-auto">
        <div class="container text-center text-secondary small">
            <div>&copy; {{ date('Y') }} CineReview. บันทึกและรีวิวภาพยนตร์</div>
        </div>
    </footer>
</body>
</html>

