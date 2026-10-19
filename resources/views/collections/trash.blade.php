<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ถังขยะของฉัน - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-trash-fill text-danger"></i>
                ถังขยะของฉัน (My Trash)
            </h1>
            <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary btn-sm text-light">
                ← กลับหน้า Tier Lists
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

            <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
                <p class="text-secondary small mb-4 d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-warning"></i>
                    กระดานจัดอันดับที่ถูกลบไปแล้ว หากลบถาวรจะไม่สามารถกู้คืนได้อีก
                </p>

                <div class="d-flex flex-column gap-3">
                    @forelse($collections as $collection)
                        <div class="card bg-black border-secondary p-3 rounded-3 shadow-sm d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                            <div>
                                <h3 class="h6 fw-bold text-white mb-1">{{ $collection->name }}</h3>
                                <small class="text-secondary">
                                    ลบเมื่อ: {{ $collection->deleted_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <form action="{{ route('collections.restore', $collection->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm d-flex align-items-center gap-1.5 fw-semibold">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                        <span>กู้คืน</span>
                                    </button>
                                </form>
                                <form action="{{ route('collections.forceDelete', $collection->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบกระดานนี้ถาวร? (ไม่สามารถกู้คืนได้อีก)');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1.5 fw-semibold">
                                        <i class="bi bi-trash"></i>
                                        <span>ลบถาวร</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 border border-secondary border-dashed rounded-3">
                            <i class="bi bi-trash fs-1 text-secondary mb-2 d-block"></i>
                            <p class="text-secondary mb-0">ไม่มีข้อมูลในถังขยะ</p>
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
</body>
</html>

