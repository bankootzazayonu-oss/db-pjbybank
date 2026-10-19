<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>เสนอภาพยนตร์ใหม่ - {{ config('app.name', 'CineReview') }}</title>

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
                        🎬 เสนอภาพยนตร์ใหม่เข้าระบบ
                    </h2>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>กลับแดชบอร์ด
                    </a>
                </div>

                <div class="alert alert-info d-flex align-items-start gap-3 mb-4 bg-primary bg-opacity-10 border-primary border-opacity-25 text-light">
                    <i class="bi bi-robot fs-3 text-primary"></i>
                    <div>
                        <h5 class="fw-bold text-primary mb-1">ฟีเจอร์ผู้ช่วยอัจฉริยะ (Auto-Fill)</h5>
                        <p class="small text-secondary mb-0">พิมพ์ชื่อภาพยนตร์ภาษาอังกฤษ แล้วกดปุ่ม "ดึงข้อมูลจาก TMDB" ระบบจะค้นหาและเติมข้อมูลให้คุณอัตโนมัติ!</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4">
                        <ul class="mb-0 ps-3 fw-medium small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="tmdb_id" name="tmdb_id">
                    <input type="hidden" id="original_title" name="original_title">

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">ชื่อภาพยนตร์/ซีรีส์ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text" id="movie_name" name="name" value="{{ old('name') }}" required 
                                   placeholder="เช่น Inception, Avatar..."
                                   class="form-control bg-dark border-secondary text-light">
                            
                            <button type="button" onclick="fetchTMDB()" class="btn btn-primary d-flex align-items-center gap-1.5 fw-semibold px-4">
                                <i class="bi bi-cloud-arrow-down"></i>
                                <span>ดึงข้อมูล</span>
                            </button>
                        </div>
                    </div>

                    <div id="tmdb_results" class="d-none mb-4">
                        <h5 class="fw-bold text-light small mb-3">
                            🎬 เลือกภาพยนตร์ที่ต้องการ
                        </h5>

                        <div id="tmdb_results_list" class="row row-cols-1 row-cols-md-2 g-3">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">หมวดหมู่ <span class="text-danger">*</span></label>
                        <select name="type_id" required class="form-select bg-dark border-secondary text-light">
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach ($types as $type)
                                <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @php
                        $selectedPlatforms = array_map('strval', old('platforms', []));
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
                            <small class="text-secondary d-block">ยังไม่มีรายการแพลตฟอร์มในระบบ (สามารถเพิ่มได้ในหน้าแอดมิน)</small>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">ปีที่เข้าฉาย (ค.ศ.) <span class="text-danger">*</span></label>
                        <input type="number" id="movie_year" name="year" value="{{ old('year', date('Y')) }}" required 
                               class="form-control bg-dark border-secondary text-light" style="max-width: 240px;">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">เรื่องย่อ / คำอธิบาย <span class="text-danger">*</span></label>
                        <textarea id="movie_review" name="review" rows="5" required 
                                  placeholder="พิมพ์เรื่องย่อ หรือกดดึงข้อมูลจาก TMDB..."
                                  class="form-control bg-dark border-secondary text-light">{{ old('review') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-light small">โปสเตอร์ภาพยนตร์ <span class="text-secondary fw-normal">(อัปโหลดเอง หรือใช้จาก API)</span></label>
                        
                        <input type="hidden" id="api_image" name="api_image">
                        
                        <div id="poster_preview_container" class="d-none mb-3">
                            <p class="text-success small fw-bold mb-1"><i class="bi bi-check-circle-fill me-1"></i>ดึงรูปภาพจาก TMDB สำเร็จ</p>
                            <img id="poster_preview_img" src="" class="rounded shadow border border-secondary" style="height: 160px; object-fit: cover;">
                        </div>

                        <input type="file" name="image" accept="image/*" class="form-control bg-dark border-secondary text-light">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow">
                        ส่งข้อมูลให้แอดมินตรวจสอบ
                    </button>
                </form>
            </div>
        </div>
    </main>

    <footer class="border-top border-secondary bg-dark py-4 mt-auto">
        <div class="container text-center text-secondary small">
            <div>&copy; {{ date('Y') }} CineReview. บันทึกและรีวิวภาพยนตร์</div>
        </div>
    </footer>

    <script>
        async function fetchTMDB() {
            const query = document.getElementById('movie_name').value.trim();

            if (!query) {
                alert('กรุณาพิมพ์ชื่อภาพยนตร์ก่อนค้นหา');
                return;
            }

            const resultsContainer = document.getElementById('tmdb_results');
            const resultsList = document.getElementById('tmdb_results_list');

            resultsContainer.classList.remove('d-none');

            resultsList.innerHTML = `
                <div class="col-12 text-center text-secondary py-4">
                    <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                    กำลังค้นหาจาก TMDB...
                </div>
            `;

            try {
                const response = await fetch(
                    `{{ route('activities.tmdb_search') }}?query=${encodeURIComponent(query)}`
                );

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'ไม่สามารถค้นหา TMDB ได้');
                }

                if (!data.results || data.results.length === 0) {
                    resultsList.innerHTML = `
                        <div class="col-12 text-center text-danger py-4">
                            ไม่พบภาพยนตร์ที่ค้นหา
                        </div>
                    `;
                    return;
                }

                resultsList.innerHTML = '';

                data.results.slice(0, 8).forEach(movie => {
                    const year = movie.release_date
                        ? movie.release_date.substring(0, 4)
                        : 'ไม่ทราบปี';

                    const poster = movie.poster_path
                        ? `https://image.tmdb.org/t/p/w300${movie.poster_path}`
                        : 'https://via.placeholder.com/300x450?text=No+Poster';

                    const col = document.createElement('div');
                    col.className = 'col';

                    col.innerHTML = `
                        <div class="card bg-dark border-secondary p-3 h-100 d-flex flex-row gap-3">
                            <img
                                src="${poster}"
                                class="rounded object-fit-cover flex-shrink-0"
                                style="width: 70px; height: 100px;"
                                alt="${escapeHtml(movie.title || 'Poster')}"
                            >

                            <div class="flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="fw-bold text-white mb-1">
                                        ${escapeHtml(movie.title || 'ไม่ทราบชื่อ')}
                                    </h6>

                                    <small class="text-secondary d-block">
                                        ปี: ${year}
                                    </small>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm fw-semibold mt-2 align-self-start"
                                    onclick='selectTMDBMovie(${JSON.stringify(movie).replace(/'/g, "&#39;")})'
                                >
                                    เลือกเรื่องนี้
                                </button>
                            </div>
                        </div>
                    `;

                    resultsList.appendChild(col);
                });

            } catch (error) {
                console.error(error);

                resultsList.innerHTML = `
                    <div class="col-12 text-center text-danger py-4">
                        ${escapeHtml(error.message)}
                    </div>
                `;
            }
        }

        function selectTMDBMovie(movie) {
            const year = movie.release_date
                ? movie.release_date.substring(0, 4)
                : '';

            document.getElementById('tmdb_id').value = movie.id || '';
            document.getElementById('original_title').value = movie.original_title || '';
            document.getElementById('movie_name').value = movie.title || '';
            document.getElementById('movie_year').value = year;
            document.getElementById('movie_review').value = movie.overview || 'ไม่มีเรื่องย่อสำหรับภาพยนตร์เรื่องนี้';

            if (movie.poster_path) {
                const imgUrl = 'https://image.tmdb.org/t/p/w500' + movie.poster_path;
                document.getElementById('api_image').value = imgUrl;
                document.getElementById('poster_preview_img').src = imgUrl;
                document.getElementById('poster_preview_container').classList.remove('d-none');
            }

            document.getElementById('tmdb_results').classList.add('d-none');

            alert(`เลือก "${movie.title}" เรียบร้อยแล้ว\nกรุณาตรวจสอบข้อมูลก่อนส่ง`);
        }

        function escapeHtml(value) {
            const div = document.createElement('div');
            div.textContent = value ?? '';
            return div.innerHTML;
        }
    </script>
</body>
</html>

