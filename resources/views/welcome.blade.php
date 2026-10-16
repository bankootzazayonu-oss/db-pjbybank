<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineReview - บันทึก รีวิว และจัดอันดับภาพยนตร์</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-dark text-light min-vh-100 d-flex flex-column">

    <header class="border-bottom border-secondary bg-dark sticky-top py-2">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo.png') }}" class="rounded-3 shadow" style="height: 44px; width: auto; object-fit: contain;" alt="CineReview Logo" />
                <span class="d-none d-sm-inline small text-secondary fw-semibold">คลังรีวิวหนัง</span>
            </a>

            <nav class="d-flex align-items-center gap-2">
                <a href="{{ route('leaderboard') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5 text-light border-secondary">
                    <i class="bi bi-trophy text-warning"></i>
                    <span>10 อันดับยอดนิยม</span>
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm fw-semibold">
                            เข้าสู่แดชบอร์ด →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                            เข้าสู่ระบบ
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm fw-semibold">
                                สมัครสมาชิก
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <section class="border-bottom border-secondary bg-dark bg-opacity-50 py-5 text-center">
        <div class="container" style="max-width: 768px;">
            <h1 class="display-6 fw-bold text-white mb-2">
                ค้นหาและรีวิวภาพยนตร์
            </h1>

            <p class="text-secondary small mb-4">
                บันทึกเรื่องที่ดู แบ่งปันมุมมองกับเพื่อนคอหนัง และสร้างกระดานจัดอันดับ Tier List
            </p>

            <form action="{{ route('home') }}" method="GET" class="mx-auto mb-4" style="max-width: 540px;">
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="q" 
                           value="{{ $search ?? '' }}" 
                           placeholder="พิมพ์ชื่อภาพยนตร์ที่ต้องการค้นหา..." 
                           class="form-control bg-dark border-secondary text-light">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        ค้นหา
                    </button>
                </div>
            </form>

            @if($types->count() > 0)
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-1">
                    <a href="{{ route('home') }}" 
                       class="btn btn-sm {{ empty($typeId) && empty($search) ? 'btn-light fw-bold text-dark' : 'btn-outline-secondary text-light' }} rounded-pill px-3 py-1 small">
                        ทั้งหมด
                    </a>
                    @foreach($types as $type)
                        <a href="{{ route('home', ['type' => $type->id]) }}" 
                           class="btn btn-sm {{ ($typeId == $type->id) ? 'btn-primary fw-bold text-white' : 'btn-outline-secondary text-light' }} rounded-pill px-3 py-1 small">
                            {{ $type->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <main class="container py-5 flex-grow-1">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h2 class="h4 fw-bold text-white mb-1 d-flex align-items-center gap-2">
                    <i class="bi bi-film text-primary"></i>
                    @if($search)
                        ผลการค้นหาสำหรับ "{{ $search }}"
                    @elseif($typeId)
                        หมวดหมู่: {{ $types->firstWhere('id', $typeId)?->name ?? 'ที่เลือก' }}
                    @else
                        ภาพยนตร์ล่าสุดที่เปิดให้รีวิว
                    @endif
                </h2>
                <small class="text-secondary">เลือกภาพยนตร์เพื่ออ่านบทวิจารณ์ หรือร่วมแสดงความคิดเห็น</small>
            </div>

            @if($search || $typeId)
                <a href="{{ route('home') }}" class="btn btn-outline-danger btn-sm">
                    ล้างตัวกรอง ✕
                </a>
            @else
                <a href="{{ route('leaderboard') }}" class="btn btn-outline-secondary btn-sm text-light">
                    ดู 10 อันดับคะแนนสูงสุด →
                </a>
            @endif
        </div>

        @if($featuredMovies->count() > 0)
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3 g-md-4">
                @foreach($featuredMovies as $movie)
                    <div class="col">
                        <div class="card bg-dark border-secondary h-100 overflow-hidden shadow-sm">
                            <div class="position-relative bg-black poster-ratio overflow-hidden">
                                @if($movie->image)
                                    <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                         alt="{{ $movie->name }}" 
                                         class="w-100 h-100 object-fit-cover"
                                         loading="lazy">
                                @else
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary bg-dark">
                                        <i class="bi bi-film fs-2 text-secondary"></i>
                                        <span class="small mt-1 text-secondary">ไม่มีรูป</span>
                                    </div>
                                @endif

                                <div class="position-absolute top-0 end-0 m-2 px-2 py-0.5 rounded bg-dark bg-opacity-75 border border-secondary small fw-bold text-warning d-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-star-fill"></i>
                                    <span class="text-light">{{ $movie->reviews_avg_rating ? number_format($movie->reviews_avg_rating, 1) : '-' }}</span>
                                </div>

                                <div class="position-absolute bottom-0 start-0 m-2 px-1.5 py-0.5 rounded bg-dark bg-opacity-75 small text-light fw-medium">
                                    {{ $movie->year }}
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

                                <div class="mt-3 pt-2 border-top border-secondary d-flex align-items-center justify-content-between small">
                                    <span class="text-secondary d-flex align-items-center gap-1">
                                        <i class="bi bi-chat-left-text"></i>
                                        {{ $movie->reviews_count }} รีวิว
                                    </span>
                                    <a href="{{ route('activities.show', $movie->id) }}" class="text-primary fw-semibold text-decoration-none">
                                        ดูรีวิว
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 bg-dark border border-secondary rounded-4 my-4">
                <i class="bi bi-search fs-1 text-secondary mb-3 d-block"></i>
                <p class="text-light fw-medium mb-2">ไม่พบภาพยนตร์ที่ตรงกับเงื่อนไข</p>
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                    ดูภาพยนตร์ทั้งหมด
                </a>
            </div>
        @endif

        @if($recentReviews->count() > 0)
            <section class="mt-5 pt-5 border-top border-secondary">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-chat-quote-fill text-primary"></i>
                            รีวิวล่าสุดจากสมาชิก
                        </h2>
                        <small class="text-secondary">ความเห็นสดๆ ร้อนๆ จากคนดูหนังในคอมมูนิตี้</small>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @foreach($recentReviews as $review)
                        <div class="col">
                            <div class="card bg-dark border-secondary h-100 p-4 shadow-sm d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-secondary bg-opacity-25 text-light d-flex align-items-center justify-content-center fw-bold border border-secondary" style="width: 32px; height: 32px; font-size: 13px;">
                                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-light small lh-1">{{ $review->user->name ?? 'สมาชิก' }}</div>
                                                <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-warning text-dark fw-bold d-flex align-items-center gap-1">
                                            <i class="bi bi-star-fill"></i>
                                            {{ $review->rating }}/10
                                        </span>
                                    </div>

                                    <a href="{{ route('activities.show', $review->activity_id) }}" class="fw-bold text-primary text-decoration-none small d-flex align-items-center gap-1 mb-2 text-truncate">
                                        <i class="bi bi-film"></i>
                                        <span>{{ $review->activity->name ?? 'ภาพยนตร์' }}</span>
                                    </a>

                                    <p class="text-light small text-truncate-3 fw-light mb-0">
                                        {{ $review->comment }}
                                    </p>
                                </div>

                                <div class="mt-3 pt-3 border-top border-secondary text-end">
                                    <a href="{{ route('activities.show', $review->activity_id) }}" class="btn btn-outline-secondary btn-sm text-light small py-1 px-3">
                                        อ่านรีวิวเต็ม →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <footer class="border-top border-secondary py-4 bg-dark text-secondary small mt-auto">
        <div class="container d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3">
            <p class="mb-0">© 2026 CineReview. โปรเจกต์วิชา Database & Web Application</p>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('leaderboard') }}" class="text-secondary text-decoration-none">จัดอันดับ</a>
                <span>•</span>
                <a href="{{ route('login') }}" class="text-secondary text-decoration-none">เข้าสู่ระบบ</a>
                <span>•</span>
                <a href="{{ route('register') }}" class="text-secondary text-decoration-none">สมัครสมาชิก</a>
            </div>
        </div>
    </footer>

</body>
</html>
