<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>จัดการหมวดหมู่ภาพยนตร์ (แอดมิน) - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-tags-fill text-primary"></i>
                จัดการหมวดหมู่ภาพยนตร์ (Admin)
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

            @if($errors->any())
                <div class="alert alert-danger mb-4 shadow-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="row g-4 align-items-start">
                
                <div class="col-md-4">
                    <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg position-sticky" style="top: 80px;">
                        <h3 class="h6 fw-bold text-white mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-plus-circle-fill text-primary"></i>
                            เพิ่มหมวดหมู่ใหม่
                        </h3>
                        <form action="{{ route('admin.types.store') }}" method="POST" class="d-flex flex-column gap-3">
                            @csrf
                            <div>
                                <label class="form-label text-light small fw-bold mb-1">ชื่อหมวดหมู่</label>
                                <input type="text" name="name" required placeholder="เช่น Action, Comedy, Sci-Fi..." 
                                       class="form-control bg-dark border-secondary text-light">
                            </div>
                            <button type="submit" class="btn btn-primary fw-bold shadow-sm py-2">
                                บันทึกข้อมูล
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg">
                        <h3 class="h6 fw-bold text-white mb-3 d-flex align-items-center gap-2 pb-2 border-bottom border-secondary">
                            <i class="bi bi-list-task text-primary"></i>
                            รายการหมวดหมู่ทั้งหมด
                        </h3>
                        <div class="table-responsive">
                            <table class="table table-dark table-hover align-middle mb-0 text-light">
                                <thead class="table-dark border-bottom border-secondary text-secondary small text-uppercase">
                                    <tr>
                                        <th class="py-3 px-3" style="width: 70px;">ID</th>
                                        <th class="py-3 px-3">ชื่อหมวดหมู่</th>
                                        <th class="py-3 px-3 text-end" style="width: 160px;">การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($types as $type)
                                        <tr x-data="{ editTypeMode: false }">
                                            <td class="py-3 px-3 text-secondary font-monospace small">{{ $type->id }}</td>
                                            
                                            <td class="py-3 px-3">
                                                <div x-show="!editTypeMode" class="fw-bold text-white small">
                                                    {{ $type->name }}
                                                </div>

                                                <form x-show="editTypeMode" style="display: none;" action="{{ route('admin.types.update', $type->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="name" value="{{ $type->name }}" required
                                                           class="form-control form-control-sm bg-dark border-primary text-light" style="max-width: 220px;">
                                                    <button type="submit" class="btn btn-success btn-sm py-1 px-2 fw-semibold">
                                                        บันทึก
                                                    </button>
                                                    <button type="button" @click="editTypeMode = false" class="btn btn-secondary btn-sm py-1 px-2">
                                                        ยกเลิก
                                                    </button>
                                                </form>
                                            </td>

                                            <td class="py-3 px-3 text-end">
                                                <div x-show="!editTypeMode" class="d-flex justify-content-end align-items-center gap-1">
                                                    <button type="button" @click="editTypeMode = true" class="btn btn-outline-warning btn-sm py-1 px-2.5 small fw-semibold">
                                                        <i class="bi bi-pencil me-1"></i>แก้ไข
                                                    </button>

                                                    <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบหมวดหมู่นี้?');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2.5 small fw-semibold">
                                                            <i class="bi bi-trash me-1"></i>ลบ
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-5 text-center text-secondary">
                                                ยังไม่มีข้อมูลหมวดหมู่
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
