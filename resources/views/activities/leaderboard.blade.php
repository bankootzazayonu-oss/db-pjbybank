<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>10 อันดับภาพยนตร์ยอดเยี่ยม - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-trophy-fill text-warning"></i>
                10 อันดับภาพยนตร์ยอดเยี่ยม (Leaderboard)
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm text-light">
                ← กลับหน้าคลังหนัง
            </a>
        </div>
    </div>

    <main class="flex-grow-1 py-5">
        <div class="container" style="max-width: 960px;">
            <div class="text-center mx-auto mb-4" style="max-width: 600px;">
                <small class="text-uppercase text-primary fw-bold">Community Rankings</small>
                <h2 class="display-6 fw-bold text-white mt-1">ภาพยนตร์ขวัญใจมหาชน</h2>
                <p class="text-secondary small">จัดอันดับโดยคำนวณจากคะแนนเฉลี่ยรีวิวของสมาชิกในระบบ</p>
            </div>

            <div class="card bg-dark border-secondary rounded-4 shadow-lg overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 text-light">
                        <thead class="table-dark border-bottom border-secondary text-secondary small text-uppercase">
                            <tr>
                                <th class="py-3 px-4 text-center" style="width: 80px;">อันดับ</th>
                                <th class="py-3 px-4">ภาพยนตร์</th>
                                <th class="py-3 px-4 text-center">คะแนนเฉลี่ย</th>
                                <th class="py-3 px-4 text-center">จำนวนรีวิว</th>
                                <th class="py-3 px-4 text-center">แอ็กชัน</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topMovies as $index => $movie)
                                <tr>
                                    <td class="py-3 px-4 text-center">
                                        @if($index === 0)
                                            <span class="badge rounded-circle bg-warning text-dark fw-bold fs-6 p-2" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                1
                                            </span>
                                        @elseif($index === 1)
                                            <span class="badge rounded-circle bg-secondary text-light fw-bold fs-6 p-2" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                2
                                            </span>
                                        @elseif($index === 2)
                                            <span class="badge rounded-circle text-white fw-bold fs-6 p-2" style="background-color: #cd7f32; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                3
                                            </span>
                                        @else
                                            <span class="text-secondary fw-semibold">
                                                {{ $index + 1 }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded overflow-hidden bg-black flex-shrink-0 border border-secondary" style="width: 48px; height: 68px;">
                                                @if($movie->image)
                                                    <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                                         alt="{{ $movie->name }}" 
                                                         class="w-100 h-100 object-fit-cover">
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                                        <i class="bi bi-film"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('activities.show', $movie->id) }}" class="fw-bold text-white text-decoration-none d-block">
                                                    {{ $movie->name }}
                                                </a>
                                                <small class="text-secondary">ปี {{ $movie->year }} • {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1.5 fw-bold small">
                                            <i class="bi bi-star-fill me-1"></i>
                                            {{ number_format($movie->reviews_avg_rating, 1) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center text-secondary small fw-medium">
                                        {{ $movie->reviews_count }} คน
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('activities.show', $movie->id) }}" class="btn btn-outline-primary btn-sm py-1 px-3">
                                            ดูรีวิว
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center text-secondary">
                                        ยังไม่มีข้อมูลภาพยนตร์ที่มีรีวิวในระบบ
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
</body>
</html>

