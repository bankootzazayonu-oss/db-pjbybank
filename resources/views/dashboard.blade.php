<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>แดชบอร์ด - {{ config('app.name', 'CineReview') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-light min-vh-100 d-flex flex-column">

    @include('layouts.navigation')

    <main class="flex-grow-1 py-4">
        <div class="container">

            <div class="mb-5">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4">
                    <div>
                        <h1 class="h3 fw-bold text-white mb-1">
                            แดชบอร์ดชุมชนภาพยนตร์
                        </h1>
                        <p class="text-secondary small mb-0">
                            สรุปสถิติบทวิจารณ์ คะแนนเฉลี่ย และคลังภาพยนตร์ที่เปิดให้รีวิว
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('activities.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold shadow-sm">
                            <i class="bi bi-plus-circle"></i>
                            <span>เสนอภาพยนตร์ใหม่</span>
                        </a>
                    </div>
                </div>

                <div class="row row-cols-2 row-cols-lg-4 g-3">
                    <div class="col">
                        <div class="card bg-dark border-secondary p-3 h-100 shadow-sm">
                            <small class="text-uppercase text-secondary fw-semibold">ภาพยนตร์ทั้งหมด</small>
                            <div class="h2 fw-bold text-white my-1">{{ $totalMovies }}</div>
                            <small class="text-success fw-medium">เรื่องที่ได้รับการอนุมัติ</small>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card bg-dark border-secondary p-3 h-100 shadow-sm">
                            <small class="text-uppercase text-secondary fw-semibold">รีวิวจากสมาชิก</small>
                            <div class="h2 fw-bold text-white my-1">{{ $totalReviews }}</div>
                            <small class="text-secondary">บทวิจารณ์ทั้งหมด</small>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card bg-dark border-secondary p-3 h-100 shadow-sm">
                            <small class="text-uppercase text-secondary fw-semibold">คะแนนเฉลี่ยรวม</small>
                            <div class="d-flex align-items-baseline gap-1 my-1">
                                <span class="h2 fw-bold text-warning mb-0">
                                    {{ $overallAverageRating !== null ? number_format($overallAverageRating, 1) : '0.0' }}
                                </span>
                                <span class="small text-secondary">/ 10</span>
                            </div>
                            <small class="text-secondary">คำนวณจากรีวิวทุกเรื่อง</small>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card bg-dark border-secondary p-3 h-100 shadow-sm">
                            <small class="text-uppercase text-secondary fw-semibold">หมวดหมู่คะแนนสูงสุด</small>
                            <div class="h4 fw-bold text-white my-1 text-truncate">
                                {{ $topGenres->first()?->name ?? 'ไม่มีข้อมูล' }}
                            </div>
                            <small class="text-warning fw-semibold d-flex align-items-center gap-1">
                                @if($topGenres->first())
                                    <i class="bi bi-star-fill"></i>
                                    <span>{{ number_format($topGenres->first()->average_rating, 1) }} / 10</span>
                                @else
                                    -
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-dark border-secondary p-4 rounded-4 shadow-sm mb-5">
                <div class="mb-3">
                    <h2 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-trophy-fill text-warning"></i>
                        5 อันดับหมวดหมู่ที่มีคะแนนรีวิวเฉลี่ยสูงสุด
                    </h2>
                    <small class="text-secondary">คำนวณจากค่าเฉลี่ยคะแนนของผู้ใช้งานจริง (SQL Aggregation)</small>
                </div>

                @if($topGenres->count() > 0)
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3">
                        @foreach($topGenres as $index => $genre)
                            <div class="col">
                                <div class="card bg-black border-secondary p-3 h-100 d-flex flex-column justify-content-between">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-secondary bg-opacity-50 text-light fw-bold">
                                            #{{ $index + 1 }}
                                        </span>
                                        <small class="text-secondary">{{ $genre->review_count }} รีวิว</small>
                                    </div>
                                    <div>
                                        <h3 class="h6 fw-bold text-white text-truncate mb-1">{{ $genre->name }}</h3>
                                        <div class="d-flex align-items-center gap-1 text-warning fw-bold small">
                                            <i class="bi bi-star-fill"></i>
                                            <span>{{ number_format($genre->average_rating, 1) }}</span>
                                            <span class="text-secondary fw-normal">/ 10</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-secondary small">
                        ยังไม่มีข้อมูลรีวิวเพียงพอสำหรับการจัดอันดับหมวดหมู่
                    </div>
                @endif
            </div>

            <div>
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                    <div>
                        <h2 class="h4 fw-bold text-white d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-film text-primary"></i>
                            คลังภาพยนตร์ทั้งหมด
                        </h2>
                        <small class="text-secondary">
                            @if($search)
                                ผลการค้นหาสำหรับ "{{ $search }}" (พบ {{ $movies->count() }} เรื่อง)
                            @elseif($typeId)
                                หมวดหมู่: {{ $types->firstWhere('id', $typeId)?->name ?? 'ที่เลือก' }} (พบ {{ $movies->count() }} เรื่อง)
                            @else
                                คลิกที่ภาพยนตร์เพื่อดูเรื่องย่อและอ่านรีวิวทั้งหมด (มี {{ $movies->count() }} เรื่อง)
                            @endif
                        </small>
                    </div>

                    <form action="{{ route('dashboard') }}" method="GET" class="d-flex align-items-center gap-2" style="max-width: 380px;">
                        @if($typeId)
                            <input type="hidden" name="type" value="{{ $typeId }}">
                        @endif
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark border-secondary text-secondary">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="q" 
                                   value="{{ $search ?? '' }}" 
                                   placeholder="ค้นหาชื่อภาพยนตร์..." 
                                   class="form-control bg-dark border-secondary text-light">
                            <button type="submit" class="btn btn-primary fw-semibold px-3">
                                ค้นหา
                            </button>
                            @if($search || $typeId)
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary" title="ล้างตัวกรอง">
                                    ✕ ล้าง
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if(isset($types) && $types->count() > 0)
                    <div class="d-flex flex-wrap align-items-center gap-1 mb-4">
                        <small class="text-secondary me-2 fw-medium">หมวดหมู่:</small>
                        <a href="{{ route('dashboard', $search ? ['q' => $search] : []) }}" 
                           class="btn btn-sm {{ empty($typeId) ? 'btn-light fw-bold text-dark' : 'btn-outline-secondary text-light' }} rounded-pill px-3 py-0.5 small">
                            ทั้งหมด
                        </a>
                        @foreach($types as $type)
                            <a href="{{ route('dashboard', array_merge($search ? ['q' => $search] : [], ['type' => $type->id])) }}" 
                               class="btn btn-sm {{ ($typeId == $type->id) ? 'btn-primary fw-bold text-white' : 'btn-outline-secondary text-light' }} rounded-pill px-3 py-0.5 small">
                                {{ $type->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 g-md-4">
                    @forelse($movies as $movie)
                        @php
                            $reviewCount = $movie->reviews ? $movie->reviews->count() : 0;
                            $avgRating = $reviewCount > 0 ? round($movie->reviews->avg('rating'), 1) : 0;
                        @endphp

                        <div class="col">
                            <a href="{{ route('activities.show', $movie->id) }}" class="text-decoration-none">
                                <div class="card bg-dark border-secondary h-100 overflow-hidden shadow-sm">
                                    <div class="position-relative bg-black poster-ratio overflow-hidden">
                                        @if($movie->image)
                                            <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}"
                                                 alt="{{ $movie->name }}"
                                                 class="w-100 h-100 object-fit-cover"
                                                 loading="lazy">
                                        @else
                                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary bg-dark">
                                                <i class="bi bi-film fs-2 text-secondary mb-1"></i>
                                                <span class="small text-secondary">ไม่มีรูป</span>
                                            </div>
                                        @endif

                                        <div class="position-absolute bottom-0 start-0 m-2 px-1.5 py-0.5 rounded bg-dark bg-opacity-75 small text-light fw-medium">
                                            {{ $movie->year }}
                                        </div>

                                        <div class="position-absolute top-0 end-0 m-2 px-2 py-0.5 rounded bg-dark bg-opacity-75 border border-secondary small fw-bold text-warning d-flex align-items-center gap-1 shadow-sm">
                                            <i class="bi bi-star-fill"></i>
                                            <span class="text-light">{{ $avgRating > 0 ? number_format($avgRating, 1) : '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="card-body p-3 d-flex flex-column justify-content-between">
                                        <div>
                                            <h3 class="h6 fw-bold text-light mb-1 text-truncate" title="{{ $movie->name }}">
                                                {{ $movie->name }}
                                            </h3>
                                            @if(!empty($movie->original_title) && $movie->original_title !== $movie->name)
                                                <p class="small text-secondary text-truncate fst-italic mb-1" title="{{ $movie->original_title }}">
                                                    {{ $movie->original_title }}
                                                </p>
                                            @endif
                                            <p class="small text-secondary mb-0">
                                                {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                            </p>
                                        </div>

                                        <div class="mt-3 pt-2 border-top border-secondary d-flex align-items-center justify-content-between small text-secondary">
                                            <span class="d-flex align-items-center gap-1">
                                                <i class="bi bi-chat-left-text"></i>
                                                {{ $reviewCount }} รีวิว
                                            </span>
                                            <span class="text-primary fw-medium">ดูรีวิว</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 bg-dark border border-secondary border-dashed rounded-4">
                            <i class="bi bi-film fs-1 text-secondary mb-2 d-block"></i>
                            <p class="text-light fw-medium mb-1">ยังไม่มีภาพยนตร์ในคลังหลัก</p>
                            <small class="text-secondary">ภาพยนตร์ที่ผ่านการอนุมัติแล้วจะแสดงขึ้นที่นี่</small>
                        </div>
                    @endforelse
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
