<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>จัดการรายงานหนัง (แอดมิน) - {{ config('app.name', 'CineReview') }}</title>

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
                <i class="bi bi-flag-fill text-danger"></i>
                จัดการรายงานหนัง (แอดมิน)
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

            <div class="card bg-dark border-secondary rounded-4 shadow-lg overflow-hidden">
                <div class="card-header bg-danger bg-opacity-10 border-bottom border-secondary p-4">
                    <h3 class="h6 fw-bold text-danger d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        รายการแจ้งปัญหาหนัง ({{ $reports->count() }} รายการ)
                    </h3>
                    <small class="text-secondary">โปรดพิจารณาและจัดการหนังที่ผู้ใช้งานกดรายงานเข้ามา</small>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 text-light">
                        <thead class="table-dark border-bottom border-secondary text-secondary small text-uppercase">
                            <tr>
                                <th class="py-3 px-4" style="width: 25%;">หนังที่ถูกรายงาน</th>
                                <th class="py-3 px-4" style="width: 35%;">รายละเอียดการแจ้ง</th>
                                <th class="py-3 px-4">ผู้แจ้งรายงาน</th>
                                <th class="py-3 px-4 text-center" style="width: 20%;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="fw-bold text-white mb-1">
                                            @if($report->activity)
                                                <a href="{{ route('activities.show', $report->activity_id) }}" class="text-primary text-decoration-none" target="_blank">{{ $report->activity->name }}</a>
                                                @if($report->activity->trashed())
                                                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger border-opacity-50 small ms-1">ลบเข้าถังขยะแล้ว</span>
                                                @endif
                                            @else
                                                <span class="text-secondary fst-italic">ไม่พบข้อมูลหนัง (ถูกลบไปแล้ว)</span>
                                            @endif
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="p-3 bg-black border-start border-danger border-3 rounded-2 small text-light whitespace-pre-line">
                                            {{ $report->reason }}
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="small">
                                            <div class="fw-bold text-light d-flex align-items-center gap-1.5">
                                                <i class="bi bi-person text-secondary"></i>
                                                <span>{{ $report->user->name ?? 'ผู้ใช้ทั่วไป' }}</span>
                                            </div>
                                            <small class="text-secondary">แจ้งเมื่อ: {{ $report->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex flex-column gap-2">
                                            @if($report->activity && !$report->activity->trashed())
                                                <form action="{{ route('activities.destroy', $report->activity_id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('ลบหนังเรื่องนี้ทิ้งเลยหรือไม่?')" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1.5 fw-semibold">
                                                        <i class="bi bi-trash"></i>
                                                        <span>ลบหนังเรื่องนี้</span>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.reports.dismiss', $report->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('ปัดตกรีพอร์ตนี้ (เก็บหนังไว้เหมือนเดิม)?')" class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center gap-1.5">
                                                    <i class="bi bi-check-lg"></i>
                                                    <span>ปัดตกรีพอร์ต</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-5 text-center text-secondary">
                                        <i class="bi bi-check-circle fs-1 text-secondary mb-2 d-block"></i>
                                        <p class="fw-bold text-light mb-0">ยังไม่มีแจ้งรายงานหนังเข้ามา</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

