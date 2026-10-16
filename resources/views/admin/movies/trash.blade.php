<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ถังขยะส่วนกลาง (แอดมิน) - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-trash-fill text-danger"></i>
                ถังขยะส่วนกลาง (System Trash)
            </h1>
            <a href="{{ route('admin.movies.pending') }}" class="btn btn-outline-secondary btn-sm text-light">
                ← กลับหน้ารออนุมัติ
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

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <p class="text-secondary small mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                    ภาพยนตร์ที่ถูกลบออกจากระบบ หากเลือกลบถาวร ข้อมูลและรูปภาพจะถูกลบออกจากเซิร์ฟเวอร์โดยสมบูรณ์
                </p>

                <div class="d-flex flex-column gap-3 mb-4">
                    @forelse($movies as $movie)
                        <div class="card bg-black border-secondary p-3 rounded-3 shadow-sm d-flex flex-column flex-sm-row gap-3">
                            <div class="rounded overflow-hidden bg-dark flex-shrink-0 border border-secondary" style="width: 90px; height: 130px;">
                                @if($movie->image)
                                    <img src="{{ Str::startsWith($movie->image, 'http') ? $movie->image : asset('storage/' . $movie->image) }}" alt="{{ $movie->name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary">
                                        <i class="bi bi-film fs-2"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                <h3 class="h6 fw-bold text-white mb-1">
                                    {{ $movie->name }} <span class="text-secondary fw-normal">({{ $movie->year }})</span>
                                </h3>
                                @if($movie->original_title)
                                    <p class="small text-secondary fst-italic mb-2">{{ $movie->original_title }}</p>
                                @endif
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary">
                                        หมวดหมู่: {{ $movie->type ? $movie->type->name : '-' }}
                                    </span>
                                    <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary">
                                        ผู้ลบ/ระบบ: {{ $movie->deleted_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex flex-sm-column justify-content-center gap-2" style="min-width: 120px;">
                                <form action="{{ route('admin.movies.restore', $movie->id) }}" method="POST" class="w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm w-100 d-flex align-items-center justify-content-center gap-1.5 fw-semibold">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <span>กู้คืน</span>
                                    </button>
                                </form>
                                <form action="{{ route('admin.movies.forceDelete', $movie->id) }}" method="POST" class="w-100" onsubmit="return confirm('คำเตือน: คุณต้องการลบภาพยนตร์เรื่องนี้ออกจากฐานข้อมูลอย่างถาวรใช่หรือไม่? (ไม่สามารถกู้คืนได้ และรูปภาพจะถูกลบ)');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1.5 fw-semibold">
                                        <i class="bi bi-trash"></i>
                                        <span>ลบถาวร</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 border border-secondary border-dashed rounded-3">
                            <i class="bi bi-trash fs-1 text-secondary mb-2 d-block"></i>
                            <p class="text-secondary mb-0">ไม่มีภาพยนตร์ในถังขยะ</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $movies->links() }}
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
