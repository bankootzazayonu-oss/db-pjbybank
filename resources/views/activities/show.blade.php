<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $movie->name }} ({{ $movie->year }}) - {{ config('app.name', 'CineReview') }}</title>

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
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h1 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-film text-primary"></i>
                <span>{{ $movie->name }}</span>
                <span class="text-secondary fw-normal">({{ $movie->year }})</span>
            </h1>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm text-light">
                ← กลับหน้าคลังหนัง
            </a>
        </div>
    </div>

    <main class="flex-grow-1">

    <div class="container py-5">
        
        @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="alert alert-dark border-primary border-opacity-50 d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 rounded-3 p-3">
                <div class="d-flex align-items-center gap-2 text-primary fw-bold small text-uppercase">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                    <span>Admin Controls</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('activities.edit', $movie->id) }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1.5 fw-semibold">
                        <i class="bi bi-pencil-square"></i>
                        <span>แก้ไขภาพยนตร์</span>
                    </a>
                    <form action="{{ route('activities.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบภาพยนตร์เรื่องนี้เข้าถังขยะส่วนกลาง?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1.5 fw-semibold">
                            <i class="bi bi-trash"></i>
                            <span>ลบภาพยนตร์</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif
        
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

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card bg-black border-secondary rounded-4 shadow-lg overflow-hidden poster-ratio position-sticky" style="top: 80px;">
                    @if($movie->image)
                        @if(\Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']))
                            <img src="{{ $movie->image }}" alt="{{ $movie->name }}" class="w-100 h-100 object-fit-cover">
                        @else
                            <img src="{{ asset('storage/' . $movie->image) }}" alt="{{ $movie->name }}" class="w-100 h-100 object-fit-cover">
                        @endif
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-secondary bg-dark">
                            <i class="bi bi-film fs-1 mb-2"></i>
                            <span class="small">ไม่มีรูปภาพ</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-50 px-3 py-1.5 rounded-pill small">
                                <i class="bi bi-tag-fill me-1"></i>
                                {{ $movie->type ? $movie->type->name : 'ไม่ระบุหมวดหมู่' }}
                            </span>
                            <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary px-3 py-1.5 rounded-pill small">
                                <i class="bi bi-calendar-event me-1"></i>
                                ปี {{ $movie->year }}
                            </span>
                            @if($movie->hours > 0)
                                <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary px-3 py-1.5 rounded-pill small">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $movie->hours }} ชั่วโมง
                                </span>
                            @endif

                            @if(Auth::check())
                                <form action="{{ route('activities.report', $movie->id) }}" method="POST" class="ms-auto">
                                    @csrf
                                    <button type="submit" onclick="return confirm('ยืนยันการรายงานหนังเรื่องนี้ว่าข้อมูลผิดพลาดหรือไม่เหมาะสม?')" class="btn btn-outline-danger btn-sm py-1 px-2.5 small d-flex align-items-center gap-1">
                                        <i class="bi bi-flag"></i>
                                        <span>รายงานหนัง</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <h1 class="display-5 fw-bold text-white mb-1">
                            {{ $movie->name }}
                        </h1>
                        @if(!empty($movie->original_title) && $movie->original_title !== $movie->name)
                            <p class="fs-5 text-secondary fst-italic mb-4">
                                {{ $movie->original_title }}
                            </p>
                        @else
                            <div class="mb-4"></div>
                        @endif
                        
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary fw-bold small mb-2">เรื่องย่อ / Synopsis</h6>
                            <p class="text-light leading-relaxed whitespace-pre-line fs-6 fw-light mb-0">
                                {{ $movie->review }}
                            </p>
                        </div>

                        @if($movie->platforms->isNotEmpty())
                            <div class="mb-4 pt-3 border-top border-secondary">
                                <h6 class="text-uppercase text-secondary fw-bold small mb-2 d-flex align-items-center gap-1.5">
                                    <i class="bi bi-tv"></i>
                                    <span>รับชมได้ที่ / Available On</span>
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($movie->platforms as $platform)
                                        <span class="badge bg-black border border-secondary text-light px-3 py-2 rounded-pill small d-flex align-items-center gap-1.5">
                                            <span class="badge rounded-circle bg-primary p-1"> </span>
                                            <span>{{ $platform->name }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-top border-secondary">
                        <div class="p-3 bg-black border border-secondary rounded-3 d-inline-flex align-items-center gap-3">
                            <i class="bi bi-star-fill text-warning fs-1"></i>
                            <div>
                                <small class="text-uppercase text-warning fw-bold d-block">คะแนนรีวิวเฉลี่ย</small>
                                <div class="d-flex align-items-baseline gap-1">
                                    <span class="h3 fw-bold text-white mb-0">
                                        {{ $movie->reviews->count() > 0 ? number_format($movie->reviews->avg('rating'), 1) : 'N/A' }}
                                    </span>
                                    <span class="small text-secondary">/ 10</span>
                                    <small class="text-secondary ms-2">({{ $movie->reviews->count() }} รีวิว)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg mb-5">
            <h3 class="h5 fw-bold text-white mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-pencil-fill text-primary"></i>
                เขียนรีวิวของคุณ
            </h3>
            
            @if($userReview)
                <div class="alert alert-success d-flex align-items-center justify-content-between p-3 rounded-3 mb-0">
                    <div>
                        <div class="fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>คุณได้รีวิวและให้คะแนนภาพยนตร์เรื่องนี้แล้ว</span>
                        </div>
                        <small class="text-success-emphasis d-flex align-items-center gap-1 mt-1">
                            ให้คะแนนไว้: 
                            <i class="bi bi-star-fill text-warning"></i>
                            {{ $userReview->rating }} / 10 ดาว
                        </small>
                    </div>
                    <span class="badge bg-success small">บันทึกแล้ว</span>
                </div>
            @else
                <form action="{{ route('reviews.store', $movie->id) }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label text-light small fw-bold text-uppercase">
                                ให้คะแนน (1-10 ดาว) <i class="bi bi-star-fill text-warning ms-1"></i>
                            </label>
                            <select name="rating" required class="form-select bg-dark border-secondary text-light">
                                <option value="">-- เลือกคะแนน --</option>
                                @for($i = 10; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} / 10</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-md-9">
                            <label class="form-label text-light small fw-bold text-uppercase">ความรู้สึกหลังดูจบ</label>
                            <textarea name="comment" rows="3" required placeholder="พิมพ์ความรู้สึก จุดเด่น หรือข้อคิดเห็นของคุณ..." class="form-control bg-dark border-secondary text-light"></textarea>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" id="is_spoiler" name="is_spoiler" value="1" class="form-check-input bg-dark border-secondary">
                        <label for="is_spoiler" class="form-check-label text-warning small fw-semibold">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            เนื้อหารีวิวนี้มีการสปอยล์ (เปิดเผยเนื้อหาสำคัญ)
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 fw-semibold px-4 py-2 shadow">
                        <i class="bi bi-send-fill"></i>
                        <span>ส่งบทวิจารณ์</span>
                    </button>
                </form>
            @endif
        </div>

        <div class="card bg-dark border-secondary rounded-4 p-4 p-md-5 shadow-lg">
            <h3 class="h5 fw-bold text-white mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-chat-left-text-fill text-primary"></i>
                รีวิวจากผู้ชม ({{ $movie->reviews->count() }})
            </h3>
            
            <div class="d-flex flex-column gap-4">
                @forelse($movie->reviews as $review)
                    <div class="card bg-black border-secondary p-4 rounded-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-secondary bg-opacity-25 text-light d-flex align-items-center justify-content-center fw-bold border border-secondary" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-light">{{ $review->user->name }}</div>
                                    <small class="text-secondary">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column align-items-end gap-1">
                                <span class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-3 py-1.5 fw-bold small">
                                    <i class="bi bi-star-fill me-1"></i>
                                    {{ $review->rating }} / 10
                                </span>
                                
                                @if(Auth::check() && Auth::id() !== $review->user_id)
                                    <form action="{{ route('reviews.report', $review->id) ?? '#' }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('ยืนยันการรายงานคอมเมนต์นี้ว่าไม่เหมาะสม?')" class="btn btn-link text-secondary text-decoration-none p-0 small">
                                            <i class="bi bi-flag me-1"></i>รายงาน
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div x-data="{ editMode: false }">
                            <div x-show="!editMode">
                                @if($review->is_spoiler)
                                    <details class="bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 p-3 my-2">
                                        <summary class="cursor-pointer small fw-bold text-warning user-select-none">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            รีวิวนี้มีสปอยล์ (คลิกเพื่อเปิดอ่าน)
                                        </summary>
                                        <p class="mt-2 text-light small border-top border-warning border-opacity-25 pt-2 whitespace-pre-line mb-0">
                                            {{ $review->comment }}
                                        </p>
                                    </details>
                                @else
                                    <p class="text-light small whitespace-pre-line my-2 fw-light">
                                        {{ $review->comment }}
                                    </p>
                                @endif

                                @if(Auth::check() && (Auth::id() === $review->user_id || Auth::user()->role === 'admin'))
                                    <div class="d-flex align-items-center gap-3 mt-3 pt-2 border-top border-secondary">
                                        @if(Auth::id() === $review->user_id)
                                            <button type="button" @click="editMode = true" class="btn btn-link text-primary text-decoration-none p-0 small fw-semibold">
                                                <i class="bi bi-pencil me-1"></i>แก้ไขรีวิว
                                            </button>
                                        @endif

                                        <form action="{{ route('reviews.user_destroy', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคอมเมนต์นี้ทิ้ง?')" class="btn btn-link text-danger text-decoration-none p-0 small fw-semibold">
                                                <i class="bi bi-trash me-1"></i>ลบรีวิว
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <div x-show="editMode" style="display: none;" class="mt-3 p-3 bg-dark border border-primary rounded-3">
                                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="form-label text-light small fw-bold mb-1">แก้ไขข้อความรีวิวของคุณ:</label>
                                    <textarea name="comment" rows="3" required class="form-control bg-dark border-secondary text-light small mb-3">{{ $review->comment }}</textarea>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                                            บันทึก
                                        </button>
                                        <button type="button" @click="editMode = false" class="btn btn-secondary btn-sm px-3">
                                            ยกเลิก
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="ms-3 ms-md-4 mt-3 ps-3 border-start border-secondary d-flex flex-column gap-2">
                            @foreach($review->replies as $reply)
                                <div x-data="{ editReplyMode: false }" class="p-3 bg-dark border border-secondary rounded-3">
                                    <div x-show="!editReplyMode">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold small text-light">{{ $reply->user->name }}</span>
                                                <small class="text-secondary">{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <p class="small text-light mb-0 whitespace-pre-line fw-light">{{ $reply->message }}</p>
                                        
                                        @if(Auth::check() && (Auth::id() === $reply->user_id || Auth::user()->role === 'admin'))
                                            <div class="d-flex align-items-center gap-2 mt-2 pt-1 border-top border-secondary">
                                                @if(Auth::id() === $reply->user_id)
                                                    <button type="button" @click="editReplyMode = true" class="btn btn-link text-primary text-decoration-none p-0 small">
                                                        แก้ไข
                                                    </button>
                                                @endif
                                                <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('ลบการตอบกลับนี้ใช่หรือไม่?')" class="btn btn-link text-danger text-decoration-none p-0 small">
                                                        ลบ
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <div x-show="editReplyMode" style="display: none;">
                                        <form action="{{ route('replies.update', $reply->id) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="message" value="{{ $reply->message }}" required class="form-control form-control-sm bg-dark border-secondary text-light">
                                            <button type="submit" class="btn btn-primary btn-sm px-3">บันทึก</button>
                                            <button type="button" @click="editReplyMode = false" class="btn btn-secondary btn-sm px-3">ยกเลิก</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach

                            @auth
                                <form action="{{ route('replies.store', $review->id) }}" method="POST" class="mt-2 d-flex gap-2">
                                    @csrf
                                    <input type="text" name="message" required placeholder="ตอบกลับความคิดเห็นนี้..." class="form-control form-control-sm bg-dark border-secondary text-light">
                                    <button type="submit" class="btn btn-primary btn-sm px-3 text-nowrap">
                                        ตอบกลับ
                                    </button>
                                </form>
                            @else
                                <small class="text-secondary mt-1">
                                    <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">เข้าสู่ระบบ</a> เพื่อร่วมพูดคุย
                                </small>
                            @endauth
                        </div>

                    </div>
                @empty
                    <div class="text-center py-5 bg-black border border-secondary border-dashed rounded-3">
                        <i class="bi bi-chat-square-dots fs-1 text-secondary mb-2 d-block"></i>
                        <p class="text-light fw-medium mb-1">ยังไม่มีรีวิวสำหรับภาพยนตร์เรื่องนี้</p>
                        <small class="text-secondary">เป็นคนแรกที่แบ่งปันความรู้สึกของคุณสิ!</small>
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

