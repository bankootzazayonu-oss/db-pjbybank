<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                กระดาน: <span class="text-indigo-400">{{ $collection->name }}</span>
            </h2>
            <button x-data @click="$dispatch('open-settings-modal')" class="text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-1 px-3 rounded shadow transition">
                ⚙️ ตั้งค่าชื่อระดับ
            </button>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 font-bold shadow-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6 font-bold shadow-md">{{ session('error') }}</div>
        @endif

        @php
            $labels =$collection->tier_labels ?? [
                'S' => 'S',
                'A' => 'A',
                'B' => 'B',
                'C' => 'C',
                'D' => 'D'
            ];
        @endphp

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 mb-8 z-40 relative">
            <form action="{{ route('collections.store_tmdb', $collection->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-start md:items-end">
                @csrf
                
                <div x-data="tmdbSearch()" class="flex-1 w-full relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ค้นหาภาพยนตร์จาก TMDB ทั่วโลก 🌍</label>
                    <div class="relative">
                        <input type="text" x-model="query" @input.debounce.500ms="search()" autocomplete="off" placeholder="พิมพ์ชื่อหนังภาษาไทย หรืออังกฤษ..." 
                               class="w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500 pl-10">
                        <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                    </div>

                    <div x-show="results.length > 0 && !selected" @click.away="results = []" style="display: none;" class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-2xl max-h-64 overflow-y-auto">
                        <template x-for="movie in results" :key="movie.id">
                            <div @click="selectMovie(movie)" class="flex items-center gap-3 p-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/50 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0 transition">
                                <img :src="movie.poster_path ? 'https://image.tmdb.org/t/p/w92' + movie.poster_path : 'https://via.placeholder.com/45x68?text=No+Img'" class="w-10 h-14 object-cover rounded shadow-sm">
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="movie.title"></p>
                                    <p class="text-xs text-gray-500" x-text="movie.release_date ? movie.release_date.substring(0,4) : 'ไม่ระบุปี'"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="selected" style="display: none;" class="mt-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img :src="selectedPoster" class="w-10 h-14 object-cover rounded shadow-sm">
                            <div>
                                <p class="text-xs font-bold text-green-700 dark:text-green-400 uppercase tracking-wide">✅ เลือกเรื่องนี้แล้ว</p>
                                <p class="text-sm text-gray-900 dark:text-white font-bold" x-text="selectedTitle"></p>
                            </div>
                        </div>
                        <button type="button" @click="clearSelection()" class="text-xs bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1.5 rounded transition font-bold shadow-sm">
                            เปลี่ยนเรื่อง
                        </button>
                    </div>
                    <input type="hidden" name="tmdb_id" :value="selectedId" required>
                </div>

                <div class="w-full md:w-56">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">จัดระดับ (Tier)</label>
                    <select name="tier_rank" required class="w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                        <option value="S">Tier S - {{ $labels['S'] }}</option>
                        <option value="A">Tier A - {{ $labels['A'] }}</option>
                        <option value="B">Tier B - {{ $labels['B'] }}</option>
                        <option value="C">Tier C - {{ $labels['C'] }}</option>
                        <option value="D">Tier D - {{ $labels['D'] }}</option>
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md transition shadow-md">
                    ➕ เพิ่มลงกระดาน
                </button>
            </form>
        </div>

        @php
            $tiers = [
                'S' => ['color' => 'bg-red-500', 'text' => $labels['S']],
                'A' => ['color' => 'bg-orange-400', 'text' => $labels['A']],
                'B' => ['color' => 'bg-yellow-400', 'text' => $labels['B']],
                'C' => ['color' => 'bg-green-400', 'text' => $labels['C']],
                'D' => ['color' => 'bg-gray-400', 'text' => $labels['D']],
            ];
        @endphp

        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-2xl border border-gray-800">
            @foreach ($tiers as $key => $tier)
                <div class="flex border-b border-gray-800 min-h-[120px]">
                    <div class="{{ $tier['color'] }} w-32 flex-shrink-0 flex items-center justify-center border-r border-gray-900 shadow-inner p-2 text-center break-words">
                        <span class="text-xl md:text-2xl font-black text-white drop-shadow-md leading-tight">{{ $tier['text'] }}</span>
                    </div>
                    <div class="flex-1 p-4 flex flex-wrap gap-4 bg-gray-800">
                        @if(isset($items[$key]))
                            @foreach ($items[$key] as $item)
                                <div class="relative group w-24 h-36 bg-gray-700 rounded-md overflow-hidden border border-gray-600">
                                    @if($item->movie->image)
                                        @if(\Illuminate\Support\Str::startsWith($item->movie->image, ['http://', 'https://']))
                                            <img src="{{ $item->movie->image }}" alt="Poster" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/' . $item->movie->image) }}" alt="Poster" class="w-full h-full object-cover">
                                        @endif
                                    @else
                                        <img src="https://via.placeholder.com/150x220?text=No+Image" alt="Poster" class="w-full h-full object-cover">
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                        <p class="text-xs text-white font-bold mb-2 text-center px-1 truncate w-full">{{ $item->movie->name }}</p>
                                        <form action="{{ route('collections.updateRank', $item->id) }}" method="POST" class="w-full px-2 mb-1">
                                            @csrf @method('PUT')
                                            <select name="tier_rank" onchange="this.form.submit()" class="w-full text-[10px] p-1 bg-gray-900 text-white border-0 rounded cursor-pointer truncate">
                                                <option value="" disabled selected>ย้ายไป...</option>
                                                <option value="S">S - {{ Str::limit($labels['S'], 10) }}</option>
                                                <option value="A">A - {{ Str::limit($labels['A'], 10) }}</option>
                                                <option value="B">B - {{ Str::limit($labels['B'], 10) }}</option>
                                                <option value="C">C - {{ Str::limit($labels['C'], 10) }}</option>
                                                <option value="D">D - {{ Str::limit($labels['D'], 10) }}</option>
                                            </select>
                                        </form>
                                        <form action="{{ route('collections.destroyItem', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('นำออกจากกระดาน?')" class="text-xs bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded">ลบทิ้ง</button>
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
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" role="dialog" aria-modal="true">
             
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showSettingsModal" @click="showSettingsModal = false" class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showSettingsModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-50">
                    <form action="{{ route('collections.updateLabels', $collection->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white mb-4" id="modal-title">
                                ⚙️ ตั้งค่าชื่อระดับ (Custom Tier Names)
                            </h3>
                            <p class="text-sm text-gray-500 mb-4">เปลี่ยนชื่อระดับแต่ละขั้นให้เป็นสไตล์ของคุณเอง (แนะนำไม่เกิน 15 ตัวอักษร)</p>
                            
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded bg-red-500 text-white font-black flex items-center justify-center">S</span>
                                    <input type="text" name="s_label" value="{{ $labels['S'] }}" required class="flex-1 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded bg-orange-400 text-white font-black flex items-center justify-center">A</span>
                                    <input type="text" name="a_label" value="{{ $labels['A'] }}" required class="flex-1 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded bg-yellow-400 text-white font-black flex items-center justify-center">B</span>
                                    <input type="text" name="b_label" value="{{ $labels['B'] }}" required class="flex-1 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded bg-green-400 text-white font-black flex items-center justify-center">C</span>
                                    <input type="text" name="c_label" value="{{ $labels['C'] }}" required class="flex-1 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded bg-gray-400 text-white font-black flex items-center justify-center">D</span>
                                    <input type="text" name="d_label" value="{{ $labels['D'] }}" required class="flex-1 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-600">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm transition">
                                บันทึกการตั้งค่า
                            </button>
                            <button type="button" @click="showSettingsModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                                ยกเลิก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

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
            this.selectedPoster = movie.poster_path ? 'https://image.tmdb.org/t/p/w92' + movie.poster_path : 'https://via.placeholder.com/45x68?text=No+Img';
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