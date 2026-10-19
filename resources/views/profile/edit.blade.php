<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>โปรไฟล์ของฉัน - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-person-circle text-primary"></i>
                โปรไฟล์และประวัติการใช้งาน
            </h1>
        </div>
    </div>

    <main class="flex-grow-1">

    <div class="container py-5">
        <div class="d-flex flex-column gap-5">

            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card bg-dark border-secondary rounded-4 p-4 shadow-sm h-100 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <small class="text-uppercase text-secondary fw-semibold">หนังที่ฉันรีวิว</small>
                            <div class="h2 fw-bold text-white my-1">{{ $reviews->count() }}</div>
                            <small class="text-danger fw-semibold">บทวิจารณ์</small>
                        </div>
                        <div class="rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 text-danger d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-chat-quote-fill fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card bg-dark border-secondary rounded-4 p-4 shadow-sm h-100 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <small class="text-uppercase text-secondary fw-semibold">Tier List ของฉัน</small>
                            <div class="h2 fw-bold text-white my-1">{{ $collections->count() }}</div>
                            <small class="text-primary fw-semibold">กระดานจัดอันดับ</small>
                        </div>
                        <div class="rounded-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-card-list fs-4"></i>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card bg-dark border-secondary rounded-4 p-4 shadow-sm h-100 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <small class="text-uppercase text-secondary fw-semibold">หนังที่เสนอเข้าระบบ</small>
                            <div class="h2 fw-bold text-white my-1">{{ $submittedMovies->count() }}</div>
                            <small class="text-success fw-semibold">คำขอเสนอหนัง</small>
                        </div>
                        <div class="rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-film fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <h2 class="h5 fw-bold text-white mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-chat-quote-fill text-danger"></i>
                    หนังที่ฉันรีวิว ({{ $reviews->count() }})
                </h2>

                @if($reviews->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($reviews as $review)
                            <div class="card bg-black border-secondary p-3 rounded-3 shadow-sm d-flex flex-row justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-white fs-6">
                                        {{ $review->activity->name ?? 'ไม่พบชื่อภาพยนตร์' }}
                                    </div>
                                    <div class="small text-warning fw-semibold mt-1 d-flex align-items-center gap-1">
                                        <i class="bi bi-star-fill"></i>
                                        <span>{{ $review->rating }} / 10 ดาว</span>
                                        <span class="text-secondary fw-normal ms-2">({{ $review->created_at->diffForHumans() }})</span>
                                    </div>
                                </div>

                                @if($review->activity)
                                    <a href="{{ route('activities.show', $review->activity_id) }}" class="btn btn-outline-primary btn-sm py-1 px-3 fw-semibold">
                                        ดูหนัง →
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary small mb-0">คุณยังไม่เคยเขียนรีวิวภาพยนตร์</p>
                @endif
            </div>

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <h2 class="h5 fw-bold text-white mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-card-list text-primary"></i>
                    Tier List ของฉัน ({{ $collections->count() }})
                </h2>

                @if($collections->count() > 0)
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @foreach($collections as $collection)
                            <div class="col">
                                <a href="{{ route('collections.show', $collection->id) }}" class="card bg-black border-secondary p-4 rounded-3 text-decoration-none shadow-sm h-100">
                                    <div class="fw-bold text-white fs-6 text-truncate mb-2">
                                        {{ $collection->name }}
                                    </div>
                                    <div class="small text-secondary d-flex justify-content-between align-items-center">
                                        <span>อัปเดต: {{ $collection->updated_at->diffForHumans() }}</span>
                                        <span class="text-primary fw-semibold">เปิดดู →</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary small mb-0">คุณยังไม่มีกระดานจัดอันดับ Tier List</p>
                @endif
            </div>

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <h2 class="h5 fw-bold text-white mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-film text-success"></i>
                    หนังที่ฉันเสนอเข้าระบบ ({{ $submittedMovies->count() }})
                </h2>

                @if($submittedMovies->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($submittedMovies as $movie)
                            <div class="card bg-black border-secondary p-3 rounded-3 shadow-sm d-flex flex-row justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold text-white fs-6">
                                        {{ $movie->name }}
                                    </div>
                                    <small class="text-secondary">
                                        ปี {{ $movie->year }} • {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                    </small>
                                </div>

                                <div>
                                    @if($movie->status === 'approved')
                                        <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-1.5 rounded-pill small">
                                            <i class="bi bi-check-circle-fill me-1"></i>อนุมัติแล้ว
                                        </span>
                                    @elseif($movie->status === 'rejected')
                                        <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 px-3 py-1.5 rounded-pill small">
                                            <i class="bi bi-x-circle-fill me-1"></i>ปฏิเสธแล้ว
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-3 py-1.5 rounded-pill small">
                                            <i class="bi bi-clock-fill me-1"></i>รอตรวจสอบ
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-secondary small mb-0">คุณยังไม่มีภาพยนตร์ที่เสนอเข้าระบบ</p>
                @endif
            </div>

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <div style="max-width: 640px;">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <div style="max-width: 640px;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <div style="max-width: 640px;">
                    @include('profile.partials.delete-user-form')
                </div>
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

