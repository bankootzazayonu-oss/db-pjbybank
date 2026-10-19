<nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom border-secondary sticky-top py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center me-3" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" class="rounded-3 shadow" style="height: 44px; width: auto; object-fit: contain;" alt="CineReview Logo">
        </a>

        <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-1">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active text-primary fw-semibold' : 'text-light' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-film me-1"></i>คลังภาพยนตร์
                        </a>
                    </li>
                @endauth

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('leaderboard') ? 'active text-primary fw-semibold' : 'text-light' }}" href="{{ route('leaderboard') }}">
                        <i class="bi bi-trophy me-1 text-warning"></i>จัดอันดับ
                    </a>
                </li>

                @auth
                    @if(Auth::user()->role !== 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('activities.create') ? 'active text-primary fw-semibold' : 'text-light' }}" href="{{ route('activities.create') }}">
                                <i class="bi bi-plus-circle me-1"></i>เสนอหนังใหม่
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('my.movies') ? 'active text-primary fw-semibold' : 'text-light' }}" href="{{ route('my.movies') }}">
                                <i class="bi bi-collection-play me-1"></i>หนังที่ฉันเสนอ
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('collections.index') ? 'active text-primary fw-semibold' : 'text-light' }}" href="{{ route('collections.index') }}">
                            <i class="bi bi-card-list me-1"></i>จัดเทียร์ลิสต์
                        </a>
                    </li>

                    @if(Auth::user()->role === 'admin')
                        <li class="nav-item dropdown">
                            <button class="nav-link dropdown-toggle btn btn-outline-secondary btn-sm text-light border-secondary px-2 ms-lg-2 mt-2 mt-lg-0" type="button" id="adminMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-gear-fill text-warning me-1"></i>จัดการระบบ
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark border-secondary shadow" aria-labelledby="adminMenuDropdown">
                                <li><h6 class="dropdown-header text-uppercase text-secondary small">ตรวจสอบข้อมูล</h6></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.movies.pending') }}">
                                        <span><i class="bi bi-clock-history text-warning me-2"></i>รออนุมัติ</span>
                                        @php $pendingCount = \App\Models\Activity::where('Approval_Status', 'pending')->count(); @endphp
                                        @if($pendingCount > 0)
                                            <span class="badge bg-warning text-dark">{{ $pendingCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.reports') }}">
                                        <span><i class="bi bi-flag-fill text-danger me-2"></i>รายงานปัญหา</span>
                                        @php
                                            $totalReports = \App\Models\CommentReport::count();
                                        @endphp
                                        @if($totalReports > 0)
                                            <span class="badge bg-danger">{{ $totalReports }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><h6 class="dropdown-header text-uppercase text-secondary small">จัดการข้อมูลหลัก</h6></li>
                                <li><a class="dropdown-item" href="{{ route('admin.movies.search') }}"><i class="bi bi-cloud-arrow-down text-info me-2"></i>นำเข้าจาก TMDB</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.types.index') }}"><i class="bi bi-tags text-primary me-2"></i>ประเภทภาพยนตร์</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.platforms.index') }}"><i class="bi bi-tv text-success me-2"></i>ช่องทางรับชม</a></li>
                                <li><hr class="dropdown-divider border-secondary"></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('admin.movies.trash') }}"><i class="bi bi-trash3 me-2"></i>ถังขยะภาพยนตร์</a></li>
                            </ul>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center gap-2">
                @auth
                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle btn btn-link text-decoration-none d-flex align-items-center gap-2 text-light p-1 rounded border-0" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-primary bg-opacity-25 border border-primary text-primary d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 34px; height: 34px; pointer-events: none;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="d-flex flex-column text-start d-none d-sm-inline" style="pointer-events: none;">
                                <span class="small fw-semibold text-white">{{ Auth::user()->name }}</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">{{ Auth::user()->role === 'admin' ? 'ผู้ดูแลระบบ' : 'สมาชิก' }}</span>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-secondary shadow" aria-labelledby="userMenuDropdown">
                            <li>
                                <div class="px-3 py-2 border-bottom border-secondary">
                                    <div class="small fw-bold text-white">{{ Auth::user()->name }}</div>
                                    <div class="small text-secondary text-truncate">{{ Auth::user()->email }}</div>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>โปรไฟล์ของฉัน</a></li>
                            <li><a class="dropdown-item" href="{{ route('collections.trash') }}"><i class="bi bi-trash3 me-2"></i>ถังขยะเทียร์ลิสต์</a></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">เข้าสู่ระบบ</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 fw-semibold">สมัครสมาชิก</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>
