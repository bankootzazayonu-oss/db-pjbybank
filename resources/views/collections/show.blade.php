<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h2 class="font-bold text-xl text-white leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>
                กระดาน: <span class="text-indigo-400">{{ $collection->name }}</span>
            </h2>

            <div class="flex items-center gap-3">
                <a href="{{ route('collections.index') }}"
                   class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-xl transition">
                    ← กลับไป Tier Lists
                </a>

                <button x-data @click="$dispatch('open-settings-modal')"
                        class="text-xs font-semibold bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/20 text-indigo-400 py-1.5 px-3.5 rounded-xl flex items-center gap-1.5 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    ตั้งค่าชื่อระดับ
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl font-semibold shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl font-semibold shadow-lg">
                {{ session('error') }}
            </div>
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

        <div class="bg-slate-900/60 p-6 rounded-3xl shadow-xl border border-slate-800/90 mb-8 z-40 relative backdrop-blur-sm">
            <form action="{{ route('collections.store_tmdb', $collection->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-start md:items-end">
                @csrf
                
                <div x-data="tmdbSearch()" class="flex-1 w-full relative">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">ค้นหาภาพยนตร์จาก TMDB ทั่วโลก</label>
                    <div class="relative flex items-center">
                        <input type="text" x-model="query" @input.debounce.500ms="search()" autocomplete="off" placeholder="พิมพ์ชื่อหนังภาษาไทย หรืออังกฤษ..." 
                               class="w-full bg-slate-950 border border-slate-800 text-white placeholder-slate-500 rounded-xl pl-10 focus:border-indigo-500 focus:ring-0 text-sm py-2.5 transition">
                        <span class="absolute left-3 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                    </div>

                    <div x-show="results.length > 0 && !selected" @click.away="results = []" style="display: none;" class="absolute z-50 w-full mt-2 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden">
                        <template x-for="movie in results" :key="movie.id">
                            <div @click="selectMovie(movie)" class="flex items-center gap-3 p-3 hover:bg-indigo-500/10 cursor-pointer border-b border-slate-800/60 last:border-0 transition">
                                <img :src="movie.poster_path ? 'https://image.tmdb.org/t/p/w92' + movie.poster_path : 'https://via.placeholder.com/45x68/0f172a/64748b?text=No+Img'" class="w-10 h-14 object-cover rounded shadow-sm border border-slate-800">
                                <div>
                                    <p class="text-sm font-bold text-white line-clamp-1" x-text="movie.title"></p>
                                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="movie.release_date ? movie.release_date.substring(0,4) : 'ไม่ระบุปี'"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="selected" style="display: none;" class="mt-3 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img :src="selectedPoster" class="w-10 h-14 object-cover rounded shadow-sm border border-slate-800">
                            <div>
                                <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-wider mb-0.5 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                    เลือกเรื่องนี้แล้ว
                                </p>
                                <p class="text-sm text-white font-bold line-clamp-1" x-text="selectedTitle"></p>
                            </div>
                        </div>
                        <button type="button" @click="clearSelection()" class="text-xs font-semibold bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 px-3 py-1.5 rounded-lg transition shadow-sm">
                            เปลี่ยนเรื่อง
                        </button>
                    </div>
                    <input type="hidden" name="tmdb_id" :value="selectedId" required>
                </div>

                <div class="w-full md:w-48">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">จัดระดับ (Tier)</label>
                    <select name="tier_rank" required class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl focus:border-indigo-500 focus:ring-0 text-sm py-2.5 transition">
                        <option value="S">Tier S - {{ $labels['S'] }}</option>
                        <option value="A">Tier A - {{ $labels['A'] }}</option>
                        <option value="B">Tier B - {{ $labels['B'] }}</option>
                        <option value="C">Tier C - {{ $labels['C'] }}</option>
                        <option value="D">Tier D - {{ $labels['D'] }}</option>
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-2.5 px-6 rounded-xl transition shadow-lg shadow-indigo-950/40 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                    เพิ่มลงกระดาน
                </button>
            </form>
        </div>

        @php
            $tiers = [
                'S' => ['color' => 'bg-rose-500', 'text' => $labels['S']],
                'A' => ['color' => 'bg-amber-500', 'text' => $labels['A']],
                'B' => ['color' => 'bg-yellow-400', 'text' => $labels['B']],
                'C' => ['color' => 'bg-emerald-400', 'text' => $labels['C']],
                'D' => ['color' => 'bg-slate-400', 'text' => $labels['D']],
            ];
        @endphp

        <div class="bg-slate-950 rounded-3xl overflow-hidden shadow-2xl border border-slate-800">
            @foreach ($tiers as $key => $tier)
                <div class="flex border-b border-slate-800/60 last:border-0 min-h-[120px]">
                    <div class="{{ $tier['color'] }} w-24 sm:w-32 flex-shrink-0 flex items-center justify-center shadow-inner p-2 sm:p-4 text-center break-words relative overflow-hidden">
                        <span class="text-xl sm:text-2xl font-black text-slate-900 drop-shadow-sm leading-tight relative z-10">{{ $tier['text'] }}</span>
                    </div>
                    <div class="flex-1 p-3 sm:p-5 flex flex-wrap gap-3 sm:gap-4 bg-slate-900/50">
                        @if(isset($items[$key]))
                            @foreach ($items[$key] as $item)
                                <div class="relative group w-20 sm:w-24 aspect-[2/3] bg-slate-900 rounded-lg overflow-hidden border border-slate-700 hover:border-indigo-500 transition duration-200">
                                    @if($item->movie->image)
                                        @if(\Illuminate\Support\Str::startsWith($item->movie->image, ['http://', 'https://']))
                                            <img src="{{ $item->movie->image }}" alt="Poster" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/' . $item->movie->image) }}" alt="Poster" class="w-full h-full object-cover">
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-900 text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute inset-0 bg-slate-950/85 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200 backdrop-blur-sm">
                                        <p class="text-[10px] sm:text-xs text-white font-bold mb-2 text-center px-1 w-full line-clamp-2 leading-snug">{{ $item->movie->name }}</p>
                                        
                                        <a href="{{ route('activities.show', $item->movie->id) }}"
                                           class="text-[10px] sm:text-xs font-semibold bg-indigo-600 hover:bg-indigo-500 text-white py-1 sm:py-1.5 px-3 rounded-lg mb-1.5 flex items-center gap-1 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            ดูหนัง
                                        </a>

                                        <form action="{{ route('collections.updateRank', $item->id) }}" method="POST" class="w-full px-2 mb-1.5">
                                            @csrf @method('PUT')
                                            <select name="tier_rank" onchange="this.form.submit()" class="w-full text-[9px] sm:text-[10px] font-medium p-1 bg-slate-800 text-white border border-slate-700 rounded cursor-pointer text-center focus:ring-0">
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
                                            <button type="submit" onclick="return confirm('นำออกจากกระดาน?')" class="text-[9px] sm:text-[10px] font-semibold text-rose-400 hover:text-rose-300 hover:underline transition">นำออก</button>
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
                <div x-show="showSettingsModal" @click="showSettingsModal = false" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showSettingsModal" class="inline-block align-bottom bg-slate-900 border border-slate-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full relative z-50">
                    <form action="{{ route('collections.updateLabels', $collection->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="px-6 pt-6 pb-6">
                            <h3 class="text-lg leading-6 font-bold text-white mb-2 flex items-center gap-2" id="modal-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                ตั้งค่าชื่อระดับ (Custom Tier Names)
                            </h3>
                            <p class="text-xs text-slate-400 mb-6">เปลี่ยนชื่อระดับแต่ละขั้นให้เป็นสไตล์ของคุณเอง (แนะนำไม่เกิน 15 ตัวอักษร)</p>
                            
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-rose-500 text-slate-900 font-black flex items-center justify-center">S</span>
                                    <input type="text" name="s_label" value="{{ $labels['S'] }}" required class="flex-1 bg-slate-950 border border-slate-800 text-white rounded-xl focus:border-indigo-500 text-sm">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-amber-500 text-slate-900 font-black flex items-center justify-center">A</span>
                                    <input type="text" name="a_label" value="{{ $labels['A'] }}" required class="flex-1 bg-slate-950 border border-slate-800 text-white rounded-xl focus:border-indigo-500 text-sm">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-yellow-400 text-slate-900 font-black flex items-center justify-center">B</span>
                                    <input type="text" name="b_label" value="{{ $labels['B'] }}" required class="flex-1 bg-slate-950 border border-slate-800 text-white rounded-xl focus:border-indigo-500 text-sm">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-emerald-400 text-slate-900 font-black flex items-center justify-center">C</span>
                                    <input type="text" name="c_label" value="{{ $labels['C'] }}" required class="flex-1 bg-slate-950 border border-slate-800 text-white rounded-xl focus:border-indigo-500 text-sm">
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-slate-400 text-slate-900 font-black flex items-center justify-center">D</span>
                                    <input type="text" name="d_label" value="{{ $labels['D'] }}" required class="flex-1 bg-slate-950 border border-slate-800 text-white rounded-xl focus:border-indigo-500 text-sm">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-900/50 px-6 py-4 border-t border-slate-800 flex justify-end gap-3">
                            <button type="button" @click="showSettingsModal = false" class="inline-flex justify-center rounded-xl border border-slate-700 px-4 py-2 bg-slate-800 text-sm font-semibold text-slate-300 hover:bg-slate-700 hover:text-white transition">
                                ยกเลิก
                            </button>
                            <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-semibold text-white hover:bg-indigo-500 transition">
                                บันทึกการตั้งค่า
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