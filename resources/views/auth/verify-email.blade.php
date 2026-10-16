<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ยืนยันอีเมล - {{ config('app.name', 'CineReview') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-light min-vh-100 d-flex flex-column justify-content-center py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
                
                <div class="text-center mb-4">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" class="rounded-3 shadow" style="height: 52px; width: auto; object-fit: contain;" alt="CineReview Logo">
                    </a>
                </div>

                <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg">
                    <div class="text-center mb-3">
                        <i class="bi bi-envelope-check text-primary" style="font-size: 2.5rem;"></i>
                        <h4 class="fw-bold text-white mt-2 mb-1">ยืนยันที่อยู่อีเมล</h4>
                    </div>

                    <div class="mb-4 small text-secondary text-center">
                        {{ __('ขอบคุณสำหรับการสมัครสมาชิก! ก่อนเริ่มต้นใช้งาน โปรดยืนยันที่อยู่อีเมลของคุณโดยคลิกลิงก์ที่เราเพิ่งส่งไป หากไม่ได้รับอีเมล เรายินดีที่จะส่งให้อีกครั้ง') }}
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success small mb-4">
                            {{ __('ลิงก์ยืนยันใหม่ได้ถูกส่งไปยังที่อยู่อีเมลที่คุณระบุไว้ระหว่างการลงทะเบียนแล้ว') }}
                        </div>
                    @endif

                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary fw-semibold px-3">
                                ส่งอีเมลยืนยันอีกครั้ง
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                ออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="small text-secondary text-decoration-none">
                        ← กลับสู่หน้าแรก
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
