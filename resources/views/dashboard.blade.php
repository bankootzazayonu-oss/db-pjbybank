<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- ==========================================
             Header & Quick Actions
        ========================================== --}}
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                        แดชบอร์ดชุมชนภาพยนตร์
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1">
                        สรุปสถิติบทวิจารณ์ คะแนนเฉลี่ย และคลังภาพยนตร์ที่เปิดให้รีวิว
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('activities.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs sm:text-sm transition shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        เสนอภาพยนตร์ใหม่
                    </a>
                </div>
            </div>

            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                
                <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">ภาพยนตร์ทั้งหมด</p>
                    <p class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $totalMovies }}</p>
                    <p class="text-[11px] text-emerald-400 mt-1 font-medium">เรื่องที่ได้รับการอนุมัติ</p>
                </div>

                
                <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">รีวิวจากสมาชิก</p>
                    <p class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $totalReviews }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">บทวิจารณ์ทั้งหมด</p>
                </div>

                
                <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">คะแนนเฉลี่ยรวม</p>
                    <div class="flex items-baseline gap-1 mt-1">
                        <span class="text-2xl sm:text-3xl font-black text-amber-400">
                            {{ $overallAverageRating !== null ? number_format($overallAverageRating, 1) : '0.0' }}
                        </span>
                        <span class="text-xs text-slate-500">/ 10</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">คำนวณจากรีวิวทุกเรื่อง</p>
                </div>

                
                <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">หมวดหมู่คะแนนสูงสุด</p>
                    <p class="text-xl sm:text-2xl font-black text-white mt-1 truncate">
                        {{ $topGenres->first()?->name ?? 'ไม่มีข้อมูล' }}
                    </p>
                    <p class="text-[11px] text-amber-400 mt-1 font-semibold flex items-center gap-1">
                        @if($topGenres->first())
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            {{ number_format($topGenres->first()->average_rating, 1) }} / 10
                        @else
                            -
                        @endif
                    </p>
                </div>

            </div>
        </div>

        {{-- ==========================================
             Top 5 Genre
        ========================================== --}}
        <div class="bg-slate-900 rounded-xl border border-slate-800 p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-400"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        5 อันดับหมวดหมู่ที่มีคะแนนรีวิวเฉลี่ยสูงสุด
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        คำนวณจากค่าเฉลี่ยคะแนนของผู้ใช้งานจริง (SQL Aggregation)
                    </p>
                </div>
            </div>

            @if($topGenres->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    @foreach($topGenres as $index => $genre)
                        <div class="p-3.5 rounded-lg bg-slate-950 border border-slate-800/80 flex flex-col justify-between hover:border-slate-700 transition">
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-6 h-6 rounded-md bg-slate-800 text-slate-300 font-bold flex items-center justify-center text-xs">
                                    #{{ $index + 1 }}
                                </span>
                                <span class="text-[11px] text-slate-500">{{ $genre->review_count }} รีวิว</span>
                            </div>

                            <div>
                                <h3 class="font-bold text-white text-sm truncate">{{ $genre->name }}</h3>
                                <div class="mt-1 flex items-center gap-1 text-amber-400 font-bold text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span>{{ number_format($genre->average_rating, 1) }}</span>
                                    <span class="text-[10px] text-slate-500 font-normal">/ 10</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-slate-500 text-xs">
                    ยังไม่มีข้อมูลรีวิวเพียงพอสำหรับการจัดอันดับหมวดหมู่
                </div>
            @endif
        </div>

        {{-- ==========================================
             คลังภาพยนตร์ทั้งหมด
        ========================================== --}}
        <div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M17 3v18"/><path d="M3 7h4"/><path d="M3 13h4"/><path d="M3 17h4"/><path d="M17 7h4"/><path d="M17 13h4"/><path d="M17 17h4"/></svg>
                        คลังภาพยนตร์ทั้งหมด
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">
                        @if($search)
                            ผลการค้นหาสำหรับ "{{ $search }}" (พบ {{ $movies->count() }} เรื่อง)
                        @elseif($typeId)
                            หมวดหมู่: {{ $types->firstWhere('id', $typeId)?->name ?? 'ที่เลือก' }} (พบ {{ $movies->count() }} เรื่อง)
                        @else
                            คลิกที่ภาพยนตร์เพื่อดูเรื่องย่อและอ่านรีวิวทั้งหมด (มี {{ $movies->count() }} เรื่อง)
                        @endif
                    </p>
                </div>

                <!-- Search form on dashboard -->
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2 max-w-sm w-full">
                    @if($typeId)
                        <input type="hidden" name="type" value="{{ $typeId }}">
                    @endif
                    <div class="relative flex-1">
                        <input type="text" 
                               name="q" 
                               value="{{ $search ?? '' }}" 
                               placeholder="ค้นหาชื่อภาพยนตร์..." 
                               class="w-full bg-slate-950 border border-slate-700/80 focus:border-indigo-500 text-white placeholder:text-slate-500 rounded-lg pl-8 pr-3 py-1.5 text-xs focus:outline-none transition">
                        <span class="absolute left-2.5 top-1.5 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition flex-shrink-0">
                        ค้นหา
                    </button>
                    @if($search || $typeId)
                        <a href="{{ route('dashboard') }}" class="text-xs text-slate-400 hover:text-white px-2 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 transition flex-shrink-0" title="ล้างตัวกรอง">
                            ✕ ล้าง
                        </a>
                    @endif
                </form>
            </div>

            <!-- Quick Genre Filter Chips on Dashboard -->
            @if(isset($types) && $types->count() > 0)
                <div class="mb-5 flex flex-wrap items-center gap-1.5">
                    <span class="text-xs text-slate-500 mr-1 font-medium">หมวดหมู่:</span>
                    <a href="{{ route('dashboard', $search ? ['q' => $search] : []) }}" 
                       class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition {{ empty($typeId) ? 'bg-slate-200 text-slate-900 border-slate-200 font-bold' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white hover:border-slate-700' }}">
                        ทั้งหมด
                    </a>
                    @foreach($types as $type)
                        <a href="{{ route('dashboard', array_merge($search ? ['q' => $search] : [], ['type' => $type->id])) }}" 
                           class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition {{ ($typeId == $type->id) ? 'bg-indigo-600 text-white border-indigo-600 font-bold' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white hover:border-slate-700' }}">
                            {{ $type->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-5">
                @forelse($movies as $movie)
                    @php
                        $reviewCount = $movie->reviews ? $movie->reviews->count() : 0;
                        $avgRating = $reviewCount > 0 ? round($movie->reviews->avg('rating'), 1) : 0;
                    @endphp

                    <a href="{{ route('activities.show', $movie->id) }}" 
                       class="group bg-slate-900 rounded-xl border border-slate-800 overflow-hidden flex flex-col hover:border-slate-700 transition duration-200">
                        
                        
                        <div class="relative aspect-[2/3] bg-slate-950 overflow-hidden">
                            @if($movie->image)
                                <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}"
                                     alt="{{ $movie->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-slate-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M17 3v18"/><path d="M3 7h4"/><path d="M3 13h4"/><path d="M3 17h4"/><path d="M17 7h4"/><path d="M17 13h4"/><path d="M17 17h4"/></svg>
                                    <span class="text-[10px] text-slate-500">ไม่มีรูป</span>
                                </div>
                            @endif

                            
                            <div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-slate-950/80 text-[10px] text-slate-300 font-medium">
                                {{ $movie->year }}
                            </div>

                            
                            <div class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-slate-950/85 backdrop-blur text-[11px] font-bold text-amber-400 flex items-center gap-1 border border-white/10 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                <span class="text-white">{{ $avgRating > 0 ? number_format($avgRating, 1) : '-' }}</span>
                            </div>
                        </div>

                        
                        <div class="p-3 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-100 group-hover:text-indigo-400 transition line-clamp-1 leading-snug" title="{{ $movie->name }}">
                                    {{ $movie->name }}
                                </h3>
                                @if(!empty($movie->original_title) && $movie->original_title !== $movie->name)
                                    <p class="text-[11px] text-slate-400 font-normal truncate italic" title="{{ $movie->original_title }}">
                                        {{ $movie->original_title }}
                                    </p>
                                @endif
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                </p>
                            </div>

                            <div class="mt-3 pt-2 border-t border-slate-800 flex items-center justify-between text-[11px] text-slate-500">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                    {{ $reviewCount }} รีวิว
                                </span>
                                <span class="text-indigo-400 font-medium group-hover:underline">ดูรีวิว</span>
                            </div>
                        </div>

                    </a>
                @empty
                    <div class="col-span-full text-center py-12 bg-slate-900/40 rounded-xl border border-dashed border-slate-800 flex flex-col items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 text-slate-600"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M17 3v18"/><path d="M3 7h4"/><path d="M3 13h4"/><path d="M3 17h4"/><path d="M17 7h4"/><path d="M17 13h4"/><path d="M17 17h4"/></svg>
                        <p class="text-slate-300 font-medium text-sm">ยังไม่มีภาพยนตร์ในคลังหลัก</p>
                        <p class="text-xs text-slate-500 mt-1">ภาพยนตร์ที่ผ่านการอนุมัติแล้วจะแสดงขึ้นที่นี่</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
