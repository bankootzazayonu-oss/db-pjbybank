<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>กระดานจัดอันดับของฉัน - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-card-list text-primary"></i>
                กระดานจัดอันดับของฉัน (My Tier Lists)
            </h1>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('collections.trash') }}" class="btn btn-outline-secondary btn-sm text-light d-flex align-items-center gap-1.5">
                    <i class="bi bi-trash text-danger"></i>
                    <span>ถังขยะ</span>
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm text-light">
                    ← กลับหน้าคลังหนัง
                </a>
            </div>
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

            <div class="row g-4 align-items-start">
                
                <div class="col-md-4">
                    <div class="card bg-dark border-secondary border-dashed rounded-4 p-4 shadow-sm position-sticky" style="top: 80px;">
                        <h2 class="h6 fw-bold text-white mb-1 d-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle text-primary"></i>
                            สร้างกระดานใหม่
                        </h2>
                        <p class="small text-secondary mb-3">กำหนดหัวข้อ เช่น "หนังโนแลนในดวงใจ" หรือ "หนังซอมบี้ห้ามพลาด"</p>

                        <form action="{{ route('collections.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" name="name" required placeholder="พิมพ์ชื่อกระดาน..." 
                                       class="form-control bg-dark border-secondary text-light">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                สร้างกระดานเลย
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="row row-cols-1 row-cols-sm-2 g-3">
                        @forelse($collections as $collection)
                            <div class="col">
                                <div x-data="{ editCollectionMode: false }" class="card bg-dark border-secondary rounded-4 p-4 shadow-sm h-100 position-relative">
                                    
                                    <div x-show="!editCollectionMode">
                                        <div class="position-absolute top-0 end-0 m-3 d-flex gap-1 z-3">
                                            <button type="button" @click="editCollectionMode = true" class="btn btn-outline-secondary btn-sm py-0 px-1.5 text-light" title="แก้ไขชื่อกระดาน">
                                                <i class="bi bi-pencil" style="font-size: 11px;"></i>
                                            </button>
                                            <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบกระดานนี้? (หนังที่จัดอันดับไว้จะหายไปทั้งหมด)');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-1.5" title="ลบกระดาน">
                                                    <i class="bi bi-trash" style="font-size: 11px;"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <a href="{{ route('collections.show', $collection->id) }}" class="text-decoration-none d-block">
                                            <div class="rounded bg-primary bg-opacity-25 text-primary d-flex align-items-center justify-content-center mb-3" style="width: 40px; height: 40px;">
                                                <i class="bi bi-card-list fs-5"></i>
                                            </div>
                                            <h3 class="h6 fw-bold text-white text-truncate pe-5 mb-1">{{ $collection->name }}</h3>
                                            <small class="text-secondary d-block">อัปเดต: {{ $collection->updated_at->diffForHumans() }}</small>
                                            
                                            <div class="mt-3 text-primary small fw-semibold d-flex align-items-center gap-1">
                                                <span>เปิดกระดานจัดอันดับ</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </div>
                                        </a>
                                    </div>

                                    <div x-show="editCollectionMode" style="display: none;">
                                        <form action="{{ route('collections.update', $collection->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <label class="form-label text-secondary small fw-bold mb-1">แก้ไขชื่อกระดาน:</label>
                                            <input type="text" name="name" value="{{ $collection->name }}" required class="form-control form-control-sm bg-dark border-secondary text-light mb-3">
                                            
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                                                    บันทึก
                                                </button>
                                                <button type="button" @click="editCollectionMode = false" class="btn btn-secondary btn-sm px-3">
                                                    ยกเลิก
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 bg-dark border border-secondary border-dashed rounded-4">
                                <i class="bi bi-card-list fs-1 text-secondary mb-2 d-block"></i>
                                <p class="text-light fw-medium mb-1">คุณยังไม่มีกระดานจัดอันดับ</p>
                                <small class="text-secondary">สร้างกระดานแรกของคุณจากฟอร์มด้านซ้าย</small>
                            </div>
                        @endforelse
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
