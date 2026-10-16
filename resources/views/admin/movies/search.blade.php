<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>นำเข้าภาพยนตร์ TMDB (แอดมิน) - {{ config('app.name', 'CineReview') }}</title>

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
            <h1 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-search text-primary"></i>
                นำเข้าภาพยนตร์จากฐานข้อมูลโลก (TMDB)
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm text-light">
                ← กลับหน้าคลังหนัง
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

            <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg mb-5" style="max-width: 680px; margin: 0 auto;">
                <form action="{{ route('admin.movies.search') }}" method="GET">
                    <label class="form-label text-secondary small fw-bold text-uppercase d-block text-center mb-2">
                        ค้นหาชื่อภาพยนตร์จาก The Movie Database (TMDB)
                    </label>
                    <div class="input-group">
                        <input type="text" 
                               name="query" 
                               value="{{ $query ?? '' }}" 
                               placeholder="พิมพ์ชื่อหนังภาษาไทยหรืออังกฤษ เช่น Avatar, Resident Evil, สัปเหร่อ..." 
                               required 
                               class="form-control bg-dark border-secondary text-light py-2 px-3">
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-1.5 fw-semibold px-4">
                            <i class="bi bi-search"></i>
                            <span>ค้นหา</span>
                        </button>
                    </div>
                </form>
            </div>

            @if(isset($movies) && count($movies) > 0)
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h2 class="h6 fw-bold text-white mb-0">
                            ผลการค้นหาสำหรับ "{{ $query }}" (พบ {{ count($movies) }} เรื่อง)
                        </h2>
                        <small class="text-secondary">เลือกประเภทแล้วกดนำเข้าได้ทันที</small>
                    </div>

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach($movies as $movie)
                            @php
                                $poster = $movie['poster_path'] 
                                    ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path'] 
                                    : null;
                                $year = !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : 'ไม่ระบุ';
                                $exists = in_array($movie['id'], $existingTmdbIds);
                            @endphp

                            <div class="col">
                                <div class="card bg-dark border-secondary h-100 rounded-4 shadow-sm overflow-hidden d-flex flex-column justify-content-between">
                                    <div class="position-relative bg-black poster-ratio overflow-hidden">
                                        @if($poster)
                                            <img src="{{ $poster }}" alt="{{ $movie['title'] }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary p-3 text-center">
                                                <i class="bi bi-film fs-1 mb-2"></i>
                                                <span class="small">ไม่มีภาพโปสเตอร์</span>
                                            </div>
                                        @endif

                                        <div class="position-absolute bottom-0 start-0 m-2 px-2 py-0.5 rounded bg-dark bg-opacity-75 small text-light fw-medium">
                                            {{ $year }}
                                        </div>

                                        @if(!empty($movie['vote_average']))
                                            <div class="position-absolute top-0 end-0 m-2 px-2 py-0.5 rounded bg-dark bg-opacity-75 border border-secondary small fw-bold text-warning d-flex align-items-center gap-1">
                                                <i class="bi bi-star-fill"></i>
                                                <span class="text-light">{{ number_format($movie['vote_average'], 1) }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <h3 class="h6 fw-bold text-white mb-1 text-truncate" title="{{ $movie['title'] }}">
                                                {{ $movie['title'] }}
                                            </h3>
                                            @if(!empty($movie['original_title']) && $movie['original_title'] !== $movie['title'])
                                                <p class="small text-secondary text-truncate fst-italic mb-2" title="{{ $movie['original_title'] }}">
                                                    {{ $movie['original_title'] }}
                                                </p>
                                            @endif
                                            <p class="text-secondary small text-truncate-3 mb-3">
                                                {{ $movie['overview'] ?: 'ไม่มีเรื่องย่อ' }}
                                            </p>
                                        </div>

                                        @if($exists)
                                            <div class="text-center py-2 bg-secondary bg-opacity-10 border border-secondary rounded-3 text-success fw-bold small">
                                                <i class="bi bi-check-circle-fill me-1"></i>นำเข้าแล้ว
                                            </div>
                                        @else
                                            <form action="{{ route('admin.movies.import') }}" method="POST" x-data="{ selectedPlatforms: [] }">
                                                @csrf
                                                <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                                <input type="hidden" name="name" value="{{ $movie['title'] }}">
                                                <input type="hidden" name="original_title" value="{{ $movie['original_title'] ?? '' }}">
                                                <input type="hidden" name="year" value="{{ $year !== 'ไม่ระบุ' ? $year : date('Y') }}">
                                                <input type="hidden" name="review" value="{{ $movie['overview'] ?? 'ไม่มีเรื่องย่อ' }}">
                                                <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">

                                                <div class="mb-2">
                                                    <select name="type_id" required class="form-select form-select-sm bg-black border-secondary text-light">
                                                        <option value="">-- เลือกประเภทภาพยนตร์ --</option>
                                                        @foreach($types as $type)
                                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                @if(isset($platforms) && $platforms->isNotEmpty())
                                                    <div class="mb-2">
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($platforms as $platform)
                                                                <label class="user-select-none cursor-pointer mb-0">
                                                                    <input type="checkbox" name="platforms[]" value="{{ $platform->id }}" x-model="selectedPlatforms" class="d-none">
                                                                    <span class="badge py-1 px-2 border"
                                                                          :class="selectedPlatforms.includes('{{ $platform->id }}')
                                                                            ? 'bg-primary text-white border-primary'
                                                                            : 'bg-dark text-secondary border-secondary'">
                                                                        <i class="bi" :class="selectedPlatforms.includes('{{ $platform->id }}') ? 'bi-check-circle-fill' : 'bi-circle'"></i>
                                                                        {{ $platform->name }}
                                                                    </span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold d-flex align-items-center justify-content-center gap-1.5 shadow-sm mt-1">
                                                    <i class="bi bi-cloud-arrow-down-fill"></i>
                                                    <span>นำเข้าสู่ระบบทันที</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif(isset($query))
                <div class="card bg-dark border-secondary rounded-4 p-5 text-center my-4">
                    <i class="bi bi-film fs-1 text-secondary mb-2 d-block"></i>
                    <p class="text-light fw-medium mb-1">ไม่พบข้อมูลหนังชื่อ "{{ $query }}" ใน TMDB</p>
                    <small class="text-secondary">ลองค้นหาด้วยชื่อภาษาอังกฤษ หรือตรวจสอบตัวสะกดอีกครั้ง</small>
                </div>
            @endif
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
