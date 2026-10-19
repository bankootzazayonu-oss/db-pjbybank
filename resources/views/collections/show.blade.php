<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $collection->name }} - {{ config('app.name', 'CineReview') }}</title>

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
            <h2 class="h5 fw-bold text-white d-flex align-items-center gap-2 mb-0">
                <i class="bi bi-card-list text-primary"></i>
                <span>กระดาน:</span>
                <span class="text-primary">{{ $collection->name }}</span>
            </h2>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary btn-sm text-light">
                    ← กลับไป Tier Lists
                </a>

                <button x-data @click="$dispatch('open-settings-modal')" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1.5 fw-semibold">
                    <i class="bi bi-sliders"></i>
                    <span>ตั้งค่าชื่อระดับ</span>
                </button>
            </div>
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

        @php
            $defaultLabels = [
                'S' => 'S',
                'A' => 'A',
                'B' => 'B',
                'C' => 'C',
                'D' => 'D'
            ];
            $labels = array_merge($defaultLabels, is_array($collection->tier_labels) ? $collection->tier_labels : []);
        @endphp

        <div class="card bg-dark border-secondary rounded-4 p-4 shadow-lg mb-5 position-relative z-3">
            <form action="{{ route('collections.store_tmdb', $collection->id) }}" method="POST" class="row g-3 align-items-end">
                @csrf
                
                <div x-data="tmdbSearch()" class="col-md-7 position-relative">
                    <label class="form-label text-light small fw-bold text-uppercase mb-1">ค้นหาภาพยนตร์จาก TMDB ทั่วโลก</label>
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-secondary">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" x-model="query" @input.debounce.500ms="search()" autocomplete="off" placeholder="พิมพ์ชื่อหนังภาษาไทย หรืออังกฤษ..." 
                               class="form-control bg-dark border-secondary text-light">
                    </div>

                    <div x-show="results.length > 0 && !selected" @click.away="results = []" style="display: none;" class="position-absolute z-3 w-100 mt-1 dropdown-menu show bg-dark border-secondary shadow-lg overflow-hidden p-0">
                        <template x-for="movie in results" :key="movie.id">
                            <div @click="selectMovie(movie)" class="d-flex align-items-center gap-3 p-2 border-bottom border-secondary dropdown-item text-light cursor-pointer">
                                <img :src="movie.poster_path ? 'https://image.tmdb.org/t/p/w92' + movie.poster_path : 'https://via.placeholder.com/45x68/0f172a/64748b?text=No+Img'" class="rounded border border-secondary object-fit-cover" style="width: 40px; height: 56px;">
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-white small text-truncate" x-text="movie.title"></div>
                                    <small class="text-secondary" x-text="movie.release_date ? movie.release_date.substring(0,4) : 'ไม่ระบุปี'"></small>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="selected" style="display: none;" class="mt-2 p-2 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img :src="selectedPoster" class="rounded border border-secondary object-fit-cover" style="width: 36px; height: 50px;">
                            <div>
                                <small class="text-success fw-bold d-block text-uppercase" style="font-size: 10px;">
                                    <i class="bi bi-check-circle-fill me-1"></i>เลือกเรื่องนี้แล้ว
                                </small>
                                <span class="fw-bold text-white small text-truncate d-block" x-text="selectedTitle"></span>
                            </div>
                        </div>
                        <button type="button" @click="clearSelection()" class="btn btn-outline-danger btn-sm py-1 px-2.5 small">
                            เปลี่ยนเรื่อง
                        </button>
                    </div>
                    <input type="hidden" name="tmdb_id" :value="selectedId" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-light small fw-bold text-uppercase mb-1">จัดระดับ (Tier)</label>
                    <select name="tier_rank" required class="form-select bg-dark border-secondary text-light">
                        <option value="S">Tier S - {{ $labels['S'] }}</option>
                        <option value="A">Tier A - {{ $labels['A'] }}</option>
                        <option value="B">Tier B - {{ $labels['B'] }}</option>
                        <option value="C">Tier C - {{ $labels['C'] }}</option>
                        <option value="D">Tier D - {{ $labels['D'] }}</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold d-flex align-items-center justify-content-center gap-1.5 py-2">
                        <i class="bi bi-plus-lg"></i>
                        <span>เพิ่มลงกระดาน</span>
                    </button>
                </div>
            </form>
        </div>

        @php
            $tiers = [
                'S' => ['color' => '#dc3545', 'textColor' => '#ffffff', 'text' => $labels['S']],
                'A' => ['color' => '#fd7e14', 'textColor' => '#ffffff', 'text' => $labels['A']],
                'B' => ['color' => '#ffc107', 'textColor' => '#000000', 'text' => $labels['B']],
                'C' => ['color' => '#198754', 'textColor' => '#ffffff', 'text' => $labels['C']],
                'D' => ['color' => '#6c757d', 'textColor' => '#ffffff', 'text' => $labels['D']],
            ];
        @endphp

        <div class="card bg-black border-secondary rounded-4 overflow-hidden shadow-2xl mb-4">
            @foreach ($tiers as $key => $tier)
                <div class="d-flex border-bottom border-secondary border-opacity-50 last:border-0" style="min-height: 130px;">
                    <div class="d-flex align-items-center justify-content-center text-center p-3 flex-shrink-0" 
                         style="background-color: {{ $tier['color'] }}; color: {{ $tier['textColor'] }}; width: 110px;">
                        <span class="fs-4 fw-black text-break lh-sm">{{ $tier['text'] }}</span>
                    </div>

                    <div class="flex-grow-1 p-3 d-flex flex-wrap gap-3 bg-dark bg-opacity-75 align-items-center">
                        @if(isset($items[$key]))
                            @foreach ($items[$key] as $item)
                                <div class="position-relative rounded-3 overflow-hidden border border-secondary shadow-sm" style="width: 84px; height: 126px;">
                                    @if($item->movie->image)
                                        @if(\Illuminate\Support\Str::startsWith($item->movie->image, ['http://', 'https://']))
                                            <img src="{{ $item->movie->image }}" alt="Poster" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <img src="{{ asset('storage/' . $item->movie->image) }}" alt="Poster" class="w-100 h-100 object-fit-cover">
                                        @endif
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-dark text-secondary">
                                            <i class="bi bi-film"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-90 d-flex flex-column align-items-center justify-content-center p-1 text-center opacity-0 hover-opacity-100 transition"
                                         style="transition: opacity 0.2s ease-in-out;"
                                         onmouseenter="this.style.opacity='1'"
                                         onmouseleave="this.style.opacity='0'">
                                        <small class="fw-bold text-white text-truncate-2 mb-1" style="font-size: 10px;">{{ $item->movie->name }}</small>
                                        
                                        <a href="{{ route('activities.show', $item->movie->id) }}"
                                           class="btn btn-primary btn-sm py-0 px-2 fw-semibold mb-1" style="font-size: 10px;">
                                            ดูหนัง
                                        </a>

                                        <form action="{{ route('collections.updateRank', $item->id) }}" method="POST" class="w-100 mb-1">
                                            @csrf @method('PUT')
                                            <select name="tier_rank" onchange="this.form.submit()" class="form-select form-select-sm bg-dark text-light border-secondary text-center p-0" style="font-size: 9px; height: 22px;">
                                                <option value="" disabled selected>ย้ายไป...</option>
                                                <option value="S">S - {{ Str::limit($labels['S'], 8) }}</option>
                                                <option value="A">A - {{ Str::limit($labels['A'], 8) }}</option>
                                                <option value="B">B - {{ Str::limit($labels['B'], 8) }}</option>
                                                <option value="C">C - {{ Str::limit($labels['C'], 8) }}</option>
                                                <option value="D">D - {{ Str::limit($labels['D'], 8) }}</option>
                                            </select>
                                        </form>

                                        <form action="{{ route('collections.destroyItem', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('นำออกจากกระดาน?')" class="btn btn-link text-danger text-decoration-none p-0" style="font-size: 10px;">
                                                นำออก
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div x-data="{ showSettingsModal: false }" 
             @open-settings-modal.window="showSettingsModal = true"
             x-show="showSettingsModal" 
             style="display: none;" 
             class="modal fade show d-block" 
             style="background-color: rgba(0,0,0,0.7);"
             tabindex="-1"
             role="dialog" 
             aria-modal="true">
             
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-light border-secondary shadow-lg rounded-4 overflow-hidden">
                    <form action="{{ route('collections.updateLabels', $collection->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2">
                                <i class="bi bi-sliders text-primary"></i>
                                ตั้งค่าชื่อระดับ (Custom Tier Names)
                            </h5>
                            <button type="button" @click="showSettingsModal = false" class="btn-close btn-close-white" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <p class="small text-secondary mb-3">เปลี่ยนชื่อระดับแต่ละขั้นให้เป็นสไตล์ของคุณเอง (แนะนำไม่เกิน 15 ตัวอักษร)</p>
                            
                            <div class="d-flex flex-column gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge text-white fw-bold d-flex align-items-center justify-content-center rounded" style="background-color: #dc3545; width: 36px; height: 36px; font-size: 16px;">S</span>
                                    <input type="text" name="s_label" value="{{ $labels['S'] }}" required class="form-control bg-dark border-secondary text-light">
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge text-white fw-bold d-flex align-items-center justify-content-center rounded" style="background-color: #fd7e14; width: 36px; height: 36px; font-size: 16px;">A</span>
                                    <input type="text" name="a_label" value="{{ $labels['A'] }}" required class="form-control bg-dark border-secondary text-light">
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge text-dark fw-bold d-flex align-items-center justify-content-center rounded" style="background-color: #ffc107; width: 36px; height: 36px; font-size: 16px;">B</span>
                                    <input type="text" name="b_label" value="{{ $labels['B'] }}" required class="form-control bg-dark border-secondary text-light">
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge text-white fw-bold d-flex align-items-center justify-content-center rounded" style="background-color: #198754; width: 36px; height: 36px; font-size: 16px;">C</span>
                                    <input type="text" name="c_label" value="{{ $labels['C'] }}" required class="form-control bg-dark border-secondary text-light">
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge text-white fw-bold d-flex align-items-center justify-content-center rounded" style="background-color: #6c757d; width: 36px; height: 36px; font-size: 16px;">D</span>
                                    <input type="text" name="d_label" value="{{ $labels['D'] }}" required class="form-control bg-dark border-secondary text-light">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-secondary">
                            <button type="button" @click="showSettingsModal = false" class="btn btn-secondary btn-sm px-3">
                                ยกเลิก
                            </button>
                            <button type="submit" class="btn btn-primary btn-sm px-3 fw-semibold">
                                บันทึกการตั้งค่า
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </main>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tmdbSearch', () => ({
        query: '',
        results: [],
        selected: false,
        selectedId: '',
        selectedTitle: '',
        selectedPoster: '',

        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            try {
                const response = await fetch(`/tmdb/search-json?query=${encodeURIComponent(this.query)}`);
                const data = await response.json();
                this.results = data.slice(0, 5);
            } catch (error) {
                console.error('Error fetching TMDB:', error);
            }
        },

        selectMovie(movie) {
            this.selected = true;
            this.selectedId = movie.id;
            this.selectedTitle = movie.title;
            this.selectedPoster = movie.poster_path ? 'https://image.tmdb.org/t/p/w92' + movie.poster_path : 'https://via.placeholder.com/45x68/0f172a/64748b?text=No+Img';
            this.results = []; 
            this.query = '';
        },

        clearSelection() {
            this.selected = false;
            this.selectedId = '';
            this.selectedTitle = '';
            this.selectedPoster = '';
        }
    }))
})
</script>

    <footer class="border-top border-secondary bg-dark py-4 mt-auto">
        <div class="container text-center text-secondary small">
            <div>&copy; {{ date('Y') }} CineReview. บันทึกและรีวิวภาพยนตร์</div>
        </div>
    </footer>
</body>
</html>

