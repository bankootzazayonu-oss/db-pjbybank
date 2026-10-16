<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ภาพยนตร์ที่ฉันเสนอ - {{ config('app.name', 'CineReview') }}</title>

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
        <div class="container d-flex align-items-center justify-content-between">
            <h1 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-collection-play-fill text-primary"></i>
                ภาพยนตร์ที่ฉันเสนอ (My Submitted Movies)
            </h1>
            <a href="{{ route('activities.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1.5 fw-semibold shadow-sm">
                <i class="bi bi-plus-circle"></i>
                <span>เสนอเรื่องใหม่</span>
            </a>
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

            <div class="card bg-dark border-secondary rounded-4 shadow-lg overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 text-light">
                        <thead class="table-dark border-bottom border-secondary text-secondary small text-uppercase">
                            <tr>
                                <th class="py-3 px-4">ชื่อภาพยนตร์</th>
                                <th class="py-3 px-4 text-center">ปีที่ฉาย</th>
                                <th class="py-3 px-4 text-center">สถานะการตรวจสอบ</th>
                                <th class="py-3 px-4 text-center">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movies as $movie)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="fw-bold text-white fs-6">
                                            {{ $movie->name }}
                                        </div>
                                        <small class="text-secondary">
                                            {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                        </small>
                                    </td>
                                    <td class="py-3 px-4 text-center text-secondary fw-medium">
                                        {{ $movie->year }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        @if($movie->status === 'approved')
                                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-1 rounded-pill small">
                                                <i class="bi bi-check-circle-fill me-1"></i>อนุมัติแล้ว
                                            </span>
                                        @elseif($movie->status === 'rejected')
                                            <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-3 py-1 rounded-pill small">
                                                <i class="bi bi-x-circle-fill me-1"></i>ถูกปฏิเสธ
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-3 py-1 rounded-pill small">
                                                <i class="bi bi-clock-fill me-1"></i>รอตรวจสอบ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            @if(auth()->user()->role === 'admin' || in_array($movie->status, ['pending', 'rejected']))
                                                <a href="{{ route('activities.edit', $movie->id) }}"
                                                   class="btn btn-outline-secondary btn-sm py-1 px-3 text-light">
                                                    ✏️ แก้ไข
                                                </a>
                                            @else
                                                <small class="text-secondary fst-italic">
                                                    🔒 ล็อกการแก้ไข
                                                </small>
                                            @endif

                                            @if($movie->status === 'approved')
                                                <a href="{{ route('activities.show', $movie->id) }}"
                                                   class="btn btn-success btn-sm py-1 px-3 fw-semibold">
                                                    🎬 ดู / รีวิว
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-center text-secondary">
                                        คุณยังไม่เคยเสนอภาพยนตร์เข้าสู่ระบบ
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="border-top border-secondary bg-dark py-4 mt-auto">
        <div class="container text-center text-secondary small">
            <div>&copy; {{ date('Y') }} CineReview. บันทึกและรีวิวภาพยนตร์</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
