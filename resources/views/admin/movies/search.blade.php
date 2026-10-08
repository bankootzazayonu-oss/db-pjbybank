<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-black text-xl text-white tracking-tight flex items-center gap-2">
                <span>🔍</span> นำเข้าภาพยนตร์จากฐานข้อมูลโลก (TMDB)
            </h1>
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-xl transition">
                ← กลับหน้าคลังหนัง
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- แจ้งเตือนเมื่อนำเข้าสำเร็จ หรือ ซ้ำ -->
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl font-semibold shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-2xl font-semibold shadow-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- กล่องค้นหา -->
        <div class="bg-slate-900 rounded-2xl border border-slate-800 p-6 shadow-xl">
            <form action="{{ route('admin.movies.search') }}" method="GET" class="max-w-2xl mx-auto">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">
                    ค้นหาชื่อภาพยนตร์จาก The Movie Database (TMDB)
                </label>
                <div class="relative flex items-center gap-2">
                    <input type="text" 
                           name="query" 
                           value="{{ $query ?? '' }}" 
                           placeholder="พิมพ์ชื่อหนังภาษาไทยหรืออังกฤษ เช่น Avatar, Resident Evil, สัปเหร่อ..." 
                           required 
                           class="w-full bg-slate-950 border border-slate-700/80 focus:border-rose-500 text-white placeholder:text-slate-500 rounded-xl px-4 py-3 text-sm focus:outline-none transition shadow-sm">
                    <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white font-bold py-3 px-6 rounded-xl text-sm transition flex-shrink-0 shadow-lg shadow-rose-950/40">
                        🔍 ค้นหา
                    </button>
                </div>
            </form>
        </div>

        <!-- แสดงผลลัพธ์แบบ Grid -->
        @if(isset($movies) && count($movies) > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-white">
                        ผลการค้นหาสำหรับ "{{ $query }}" (พบ {{ count($movies) }} เรื่อง)
                    </h2>
                    <span class="text-xs text-slate-400">เลือกประเภทแล้วกดนำเข้าได้ทันที</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($movies as $movie)
                        @php
                            $isImported = in_array($movie['id'], $existingTmdbIds ?? []) 
                                || in_array(strtolower(trim($movie['title'])), $existingNames ?? [])
                                || (!empty($movie['original_title']) && in_array(strtolower(trim($movie['original_title'])), $existingNames ?? []));
                            $year = !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : 'ไม่ระบุ';
                        @endphp

                        <div class="bg-slate-900 rounded-xl border {{ $isImported ? 'border-emerald-500/40 bg-slate-900/50' : 'border-slate-800 hover:border-slate-700' }} overflow-hidden flex flex-col justify-between shadow-lg transition duration-200">
                            
                            <div>
                                <!-- รูปโปสเตอร์ -->
                                <div class="relative aspect-[2/3] bg-slate-950 overflow-hidden">
                                    @if(!empty($movie['poster_path']))
                                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" 
                                             alt="{{ $movie['title'] }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-slate-950">
                                            <span class="text-4xl mb-1">🎬</span>
                                            <span class="text-xs">ไม่มีรูปโปสเตอร์</span>
                                        </div>
                                    @endif

                                    <!-- ป้ายปี -->
                                    <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded bg-slate-950/80 backdrop-blur text-[11px] text-slate-300 font-medium border border-white/10">
                                        {{ $year }}
                                    </div>

                                    <!-- ป้ายเรตติ้ง TMDB -->
                                    @if(!empty($movie['vote_average']))
                                        <div class="absolute top-2 right-2 px-2 py-0.5 rounded bg-slate-950/80 backdrop-blur text-[11px] font-bold text-amber-400 border border-white/10 flex items-center gap-1 shadow">
                                            <span>⭐</span>
                                            <span class="text-white">{{ number_format($movie['vote_average'], 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- รายละเอียด -->
                                <div class="p-4">
                                    <h3 class="font-bold text-sm text-white truncate mb-0.5" title="{{ $movie['title'] }}">
                                        {{ $movie['title'] }}
                                    </h3>
                                    @if(!empty($movie['original_title']) && $movie['original_title'] !== $movie['title'])
                                        <p class="text-[11px] text-slate-400 truncate font-normal mb-2 italic" title="{{ $movie['original_title'] }}">
                                            {{ $movie['original_title'] }}
                                        </p>
                                    @else
                                        <div class="mb-2"></div>
                                    @endif
                                    
                                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed font-light mb-3">
                                        {{ !empty($movie['overview']) ? $movie['overview'] : 'ไม่มีเรื่องย่อจาก TMDB' }}
                                    </p>
                                </div>
                            </div>

                            <!-- ส่วนจัดการนำเข้า -->
                            <div class="p-4 pt-0">
                                @if($isImported)
                                    <div class="w-full py-2.5 px-3 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold text-center flex items-center justify-center gap-1.5">
                                        <span>✓</span> มีในคลังภาพยนตร์แล้ว
                                    </div>
                                @else
                                    <form action="{{ route('admin.movies.import') }}" method="POST" class="space-y-2.5">
                                        @csrf
                                        <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                        <input type="hidden" name="title" value="{{ $movie['title'] }}">
                                        <input type="hidden" name="original_title" value="{{ $movie['original_title'] ?? '' }}">
                                        <input type="hidden" name="year" value="{{ $year != 'ไม่ระบุ' ? $year : date('Y') }}">
                                        <input type="hidden" name="overview" value="{{ $movie['overview'] ?? '' }}">
                                        <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">

                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-400 mb-1">
                                                📁 เลือกหมวดหมู่ภาพยนตร์:
                                            </label>
                                            <select name="type_id" required 
                                                    class="w-full rounded-lg border border-slate-700 bg-slate-950 text-slate-200 text-xs py-2 px-2.5 focus:border-rose-500 focus:outline-none transition">
                                                <option value="">-- เลือกประเภทหนัง --</option>
                                                @foreach($types as $type)
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- 📺 เลือกช่องทางการรับชม (Platforms) -->
                                        @if(!empty($platforms) && $platforms->isNotEmpty())
                                            <div class="pt-0.5" x-data="{ selectedPlatforms: [] }">
                                                <label class="block text-[11px] font-semibold text-slate-400 mb-1 flex items-center justify-between">
                                                    <span>📺 ช่องทางรับชม (ถ้ามี):</span>
                                                    <span class="text-[10px] text-slate-500">คลิกเลือกได้หลายช่องทาง</span>
                                                </label>
                                                <div class="flex flex-wrap gap-1.5 max-h-28 overflow-y-auto pr-0.5 p-1 rounded-lg bg-slate-950/70 border border-slate-800/80">
                                                    @foreach($platforms as $platform)
                                                        <label class="cursor-pointer select-none">
                                                            <input type="checkbox" name="platforms[]" value="{{ $platform->id }}" x-model="selectedPlatforms" class="sr-only">
                                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[11px] font-medium border transition cursor-pointer select-none"
                                                                  :class="selectedPlatforms.includes('{{ $platform->id }}')
                                                                    ? 'bg-rose-600 text-white border-rose-500 shadow-sm font-semibold'
                                                                    : 'bg-slate-900 border-slate-700/80 text-slate-300 hover:border-slate-500 hover:text-white'">
                                                                <span class="w-3 h-3 rounded flex items-center justify-center text-[9px] font-black transition"
                                                                      :class="selectedPlatforms.includes('{{ $platform->id }}') ? 'bg-white text-rose-600' : 'bg-slate-800 text-transparent'">
                                                                    ✓
                                                                </span>
                                                                <span>{{ $platform->name }}</span>
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <button type="submit" 
                                                class="w-full bg-rose-600 hover:bg-rose-500 active:scale-[0.98] text-white font-bold py-2.5 px-4 rounded-lg text-xs flex items-center justify-center gap-2 shadow-md hover:shadow-rose-950/50 transition duration-150">
                                            <span>📥</span> นำเข้าสู่ระบบทันที
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @elseif(isset($query))
            <div class="text-center py-16 text-slate-500 bg-slate-900 rounded-2xl border border-slate-800">
                <span class="text-4xl block mb-2">🎬</span>
                <p class="font-medium text-slate-300 text-sm">ไม่พบข้อมูลหนังชื่อ "{{ $query }}" ใน TMDB</p>
                <p class="text-xs text-slate-500 mt-1">ลองค้นหาด้วยชื่อภาษาอังกฤษ หรือตรวจสอบตัวสะกดอีกครั้ง</p>
            </div>
        @endif

    </div>
</x-app-layout>