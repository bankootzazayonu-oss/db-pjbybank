<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>แก้ไขข้อมูลภาพยนตร์ - {{ config('app.name', 'CineReview') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-light min-vh-100 d-flex flex-column">

    @include('layouts.navigation')

    <main class="flex-grow-1 py-5">
        <div class="container" style="max-width: 800px;">
            <div class="card bg-dark border-secondary shadow-lg p-4 p-md-5 rounded-4">
                
                <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary">
                    <h2 class="h4 fw-bold text-white mb-0">
                        ✏️ แก้ไขข้อมูลภาพยนตร์
                    </h2>
                    <a href="{{ route('my.movies') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>กลับไปรายการหนัง
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4">
                        <ul class="mb-0 ps-3 fw-medium small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">ชื่อภาพยนตร์/ซีรีส์ <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $activity->name) }}" required 
                               class="form-control bg-dark border-secondary text-light">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">ปีที่เข้าฉาย (ค.ศ.) <span class="text-danger">*</span></label>
                        <input type="number" name="year" value="{{ old('year', $activity->year) }}" required 
                               class="form-control bg-dark border-secondary text-light" style="max-width: 240px;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">หมวดหมู่ภาพยนตร์ <span class="text-danger">*</span></label>
                        <select name="type_id" required class="form-select bg-dark border-secondary text-light">
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ $activity->type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $selectedPlatforms = array_map('strval', old('platforms', $activity->platforms->pluck('id')->toArray()));
                    @endphp
                    <div class="mb-4" x-data="{ selected: {{ json_encode($selectedPlatforms) }} }">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold text-light small mb-0">
                                📺 ช่องทางการรับชม (Streaming Platforms)
                            </label>
                            <small class="text-secondary">คลิกเลือกเพื่อเปิด/ปิด</small>
                        </div>

                        @if($platforms->isNotEmpty())
                            <div class="p-3 rounded-3 bg-black border border-secondary d-flex flex-wrap gap-2">
                                @foreach($platforms as $platform)
                                    <label class="user-select-none cursor-pointer mb-0">
                                        <input type="checkbox" 
                                               name="platforms[]" 
                                               value="{{ $platform->id }}"
                                               x-model="selected"
                                               class="d-none">
                                        <span class="btn btn-sm d-inline-flex align-items-center gap-1.5 rounded-pill"
                                              :class="selected.includes('{{ $platform->id }}') 
                                                ? 'btn-primary text-white fw-bold shadow-sm' 
                                                : 'btn-outline-secondary text-light'">
                                            <i class="bi" :class="selected.includes('{{ $platform->id }}') ? 'bi-check-circle-fill' : 'bi-circle'"></i>
                                            <span>{{ $platform->name }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <small class="text-secondary d-block">ยังไม่มีรายการแพลตฟอร์มในระบบ</small>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">เรื่องย่อ / คำอธิบาย <span class="text-danger">*</span></label>
                        <textarea name="review" rows="4" required 
                                  class="form-control bg-dark border-secondary text-light">{{ old('review', $activity->review) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">อัปโหลดโปสเตอร์ใหม่ <span class="text-secondary fw-normal">(ถ้าไม่เปลี่ยน ไม่ต้องอัปโหลด)</span></label>
                        <input type="file" name="image" accept="image/*" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 py-2.5 fw-bold shadow">
                            💾 บันทึกการแก้ไข
                        </button>
                        <a href="{{ route('my.movies') }}" class="btn btn-secondary px-4 py-2.5 fw-semibold">
                            ยกเลิก
                        </a>
                    </div>
                </form>
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

