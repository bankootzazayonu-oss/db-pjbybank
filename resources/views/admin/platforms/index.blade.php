<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>จัดการแพลตฟอร์มรับชม (แอดมิน) - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-tv-fill text-primary"></i>
                จัดการแพลตฟอร์มรับชม (Streaming Platforms)
            </h2>
            <span class="badge bg-dark border border-secondary text-secondary small py-2 px-3">
                สำหรับผูกช่องทางการดูให้กับภาพยนตร์
            </span>
        </div>
    </div>

    <main class="flex-grow-1">

    <div class="container py-5">
        
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
            
            <div class="col-lg-4">
                <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg position-sticky" style="top: 80px;">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom border-secondary">
                        <i class="bi bi-plus-circle-fill text-primary fs-5"></i>
                        <div>
                            <h3 class="h6 fw-bold text-white mb-0">เพิ่มแพลตฟอร์มใหม่</h3>
                            <small class="text-secondary">ระบุชื่อช่องทางสำหรับดูภาพยนตร์</small>
                        </div>
                    </div>

                    <form action="{{ route('admin.platforms.store') }}" method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <div>
                            <label class="form-label text-light small fw-bold mb-1">
                                ชื่อแพลตฟอร์ม <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   placeholder="เช่น Netflix, Disney+ Hotstar, Prime Video..." 
                                   class="form-control bg-dark border-secondary text-light">
                        </div>

                        <div>
                            <label class="form-label text-light small fw-bold mb-1">
                                ไอคอน / ลิงก์โลโก้ <span class="text-secondary fw-normal">(ถ้ามี ไม่บังคับ)</span>
                            </label>
                            <input type="text" 
                                   name="logo" 
                                   value="{{ old('logo') }}" 
                                   placeholder="เช่น https://... หรือเว้นว่างไว้" 
                                   class="form-control bg-dark border-secondary text-light">
                        </div>

                        <button type="submit" class="btn btn-primary fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm py-2">
                            <i class="bi bi-save"></i>
                            <span>บันทึกแพลตฟอร์ม</span>
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-top border-secondary">
                        <small class="text-secondary d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle text-warning fs-6"></i>
                            <span>แพลตฟอร์มที่เพิ่มที่นี่ จะไปแสดงเป็นตัวเลือกให้ User และ Admin ติ๊กเลือกตอนเพิ่ม/แก้ไขภาพยนตร์ทันที</span>
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg">
                    
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mb-4 pb-3 border-bottom border-secondary">
                        <div>
                            <h3 class="h6 fw-bold text-white mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-list-check text-primary"></i>
                                <span>รายการแพลตฟอร์มทั้งหมด</span>
                                <span class="badge bg-secondary rounded-pill small">
                                    {{ $platforms->count() }} ช่องทาง
                                </span>
                            </h3>
                        </div>

                        <form action="{{ route('admin.platforms.index') }}" method="GET" class="d-flex align-items-center gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-dark border-secondary text-secondary">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" 
                                       name="q" 
                                       value="{{ $search ?? '' }}" 
                                       placeholder="ค้นหาชื่อแพลตฟอร์ม..." 
                                       class="form-control bg-dark border-secondary text-light">
                            </div>
                            @if($search)
                                <a href="{{ route('admin.platforms.index') }}" class="btn btn-outline-secondary btn-sm">ล้าง</a>
                            @endif
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0 text-light">
                            <thead class="table-dark border-bottom border-secondary text-secondary small text-uppercase">
                                <tr>
                                    <th class="py-3 px-3" style="width: 60px;">#</th>
                                    <th class="py-3 px-3">ชื่อแพลตฟอร์ม</th>
                                    <th class="py-3 px-3 text-center" style="width: 140px;">จำนวนหนัง</th>
                                    <th class="py-3 px-3 text-end" style="width: 140px;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($platforms as $index => $platform)
                                    <tr x-data="{ isEditing: false, newName: '{{ addslashes($platform->name) }}' }">
                                        
                                        <td class="py-3 px-3 text-secondary small font-monospace">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="py-3 px-3">
                                            <div x-show="!isEditing" class="d-flex align-items-center gap-2">
                                                <div class="rounded bg-black border border-secondary d-flex align-items-center justify-content-center text-primary" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-tv"></i>
                                                </div>
                                                <span class="fw-bold text-white small">
                                                    {{ $platform->name }}
                                                </span>
                                            </div>

                                            <form x-show="isEditing" 
                                                  style="display: none;" 
                                                  action="{{ route('admin.platforms.update', $platform->id) }}" 
                                                  method="POST" 
                                                  class="d-flex align-items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" 
                                                       name="name" 
                                                       x-model="newName" 
                                                       required 
                                                       class="form-control form-control-sm bg-dark border-primary text-light" style="max-width: 200px;">
                                                <button type="submit" class="btn btn-success btn-sm py-1 px-2 fw-semibold">
                                                    บันทึก
                                                </button>
                                                <button type="button" @click="isEditing = false" class="btn btn-secondary btn-sm py-1 px-2">
                                                    ยกเลิก
                                                </button>
                                            </form>
                                        </td>

                                        <td class="py-3 px-3 text-center">
                                            <span class="badge {{ $platform->activities_count > 0 ? 'bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50' : 'bg-secondary bg-opacity-25 text-secondary' }} px-2.5 py-1 rounded-pill small">
                                                {{ $platform->activities_count }} เรื่อง
                                            </span>
                                        </td>

                                        <td class="py-3 px-3 text-end">
                                            <div x-show="!isEditing" class="d-flex align-items-center justify-content-end gap-1">
                                                <button type="button" 
                                                        @click="isEditing = true" 
                                                        class="btn btn-outline-warning btn-sm py-1 px-2" 
                                                        title="แก้ไขชื่อ">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <form action="{{ route('admin.platforms.destroy', $platform->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('ยืนยันที่จะลบแพลตฟอร์ม &quot;{{ addslashes($platform->name) }}&quot; หรือไม่? (จะตัดการเชื่อมต่อกับหนัง {{ $platform->activities_count }} เรื่องอัตโนมัติ)');" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm py-1 px-2" title="ลบแพลตฟอร์ม">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-5 text-center text-secondary">
                                            <i class="bi bi-tv fs-1 text-secondary mb-2 d-block"></i>
                                            <p class="text-light fw-medium mb-1">ยังไม่มีข้อมูลแพลตฟอร์ม</p>
                                            <small class="text-secondary">สามารถเพิ่มแพลตฟอร์ม เช่น Netflix, Disney+, Prime Video ได้จากฟอร์มด้านซ้าย</small>
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
