<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>เข้าสู่ระบบ - {{ config('app.name', 'CineReview') }}</title>

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
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-white mb-1">เข้าสู่ระบบ</h4>
                        <small class="text-secondary">ยินดีต้อนรับกลับสู่ CineReview</small>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success small mb-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label text-light small fw-bold">อีเมล (Email)</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="form-control bg-dark border-secondary text-light @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label text-light small fw-bold">รหัสผ่าน (Password)</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="form-control bg-dark border-secondary text-light @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input id="remember_me" type="checkbox" class="form-check-input bg-dark border-secondary" name="remember">
                            <label for="remember_me" class="form-check-label text-secondary small">จดจำฉันไว้ในระบบ</label>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            @if (Route::has('password.request'))
                                <a class="small text-secondary text-decoration-none" href="{{ route('password.request') }}">
                                    ลืมรหัสผ่าน?
                                </a>
                            @else
                                <span></span>
                            @endif

                            <button type="submit" class="btn btn-primary fw-semibold px-4">
                                เข้าสู่ระบบ
                            </button>
                        </div>

                        @if (Route::has('register'))
                            <div class="text-center pt-3 border-top border-secondary">
                                <small class="text-secondary">ยังไม่มีบัญชีผู้ใช้? <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold">สมัครสมาชิก</a></small>
                            </div>
                        @endif
                    </form>
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
