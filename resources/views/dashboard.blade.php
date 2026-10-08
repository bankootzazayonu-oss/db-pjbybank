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
                    <a href="{{ route('activities.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs sm:text-sm transition">
                        <span>➕</span> เสนอภาพยนตร์ใหม่
                    </a>
                </div>
            </div>

            {{-- 4 Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- จำนวนหนัง --}}
                <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">ภาพยนตร์ทั้งหมด</p>
                    <p class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $totalMovies }}</p>
                    <p class="text-[11px] text-emerald-400 mt-1 font-medium">เรื่องที่ได้รับการอนุมัติ</p>
                </div>

                {{-- จำนวนรีวิว --}}
                <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">รีวิวจากสมาชิก</p>
                    <p class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $totalReviews }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">บทวิจารณ์ทั้งหมด</p>
                </div>

                {{-- คะแนนเฉลี่ย --}}
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

                {{-- หมวดหมู่อันดับ 1 --}}
                <div class="bg-slate-900 rounded-xl border border-slate-800 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">หมวดหมู่คะแนนสูงสุด</p>
                    <p class="text-xl sm:text-2xl font-black text-white mt-1 truncate">
                        {{ $topGenres->first()?->name ?? 'ไม่มีข้อมูล' }}
                    </p>
                    <p class="text-[11px] text-amber-400 mt-1 font-semibold">
                        {{ $topGenres->first() ? '⭐ ' . number_format($topGenres->first()->average_rating, 1) . ' / 10' : '-' }}
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
                        <span>🏆</span> 5 อันดับหมวดหมู่ที่มีคะแนนรีวิวเฉลี่ยสูงสุด
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">
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
                                    <span>⭐</span>
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
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight flex items-center gap-2">
                        <span>🍿</span> คลังภาพยนตร์ทั้งหมด
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">คลิกที่ภาพยนตร์เพื่อดูเรื่องย่อและอ่านรีวิวทั้งหมด</p>
                </div>

                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-800 text-slate-300">
                    {{ $movies->count() }} เรื่อง
                </span>
            </div>

            {{-- Grid หนัง --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-5">
                @forelse($movies as $movie)
                    @php
                        $reviewCount = $movie->reviews ? $movie->reviews->count() : 0;
                        $avgRating = $reviewCount > 0 ? round($movie->reviews->avg('rating'), 1) : 0;
                    @endphp

                    <a href="{{ route('activities.show', $movie->id) }}" 
                       class="group bg-slate-900 rounded-xl border border-slate-800 overflow-hidden flex flex-col hover:border-slate-700 transition duration-200">
                        
                        {{-- Poster --}}
                        <div class="relative aspect-[2/3] bg-slate-950 overflow-hidden">
                            @if($movie->image)
                                <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}"
                                     alt="{{ $movie->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-slate-900">
                                    <span class="text-3xl">🎬</span>
                                    <span class="text-[10px] mt-1 text-slate-500">ไม่มีรูป</span>
                                </div>
                            @endif

                            {{-- Year --}}
                            <div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-slate-950/80 text-[10px] text-slate-300 font-medium">
                                {{ $movie->year }}
                            </div>

                            {{-- Rating --}}
                            <div class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-slate-950/85 backdrop-blur text-[11px] font-bold text-amber-400 flex items-center gap-1 border border-white/10 shadow">
                                <span>⭐</span>
                                <span class="text-white">{{ $avgRating > 0 ? number_format($avgRating, 1) : '-' }}</span>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="p-3 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-100 group-hover:text-rose-400 transition line-clamp-1 leading-snug">
                                    {{ $movie->name }}
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                </p>
                            </div>

                            <div class="mt-3 pt-2 border-t border-slate-800 flex items-center justify-between text-[11px] text-slate-500">
                                <span>💬 {{ $reviewCount }} รีวิว</span>
                                <span class="text-rose-400 font-semibold group-hover:underline">ดูรีวิว</span>
                            </div>
                        </div>

                    </a>
                @empty
                    <div class="col-span-full text-center py-12 bg-slate-900/40 rounded-xl border border-dashed border-slate-800">
                        <span class="text-3xl block mb-2">🍿</span>
                        <p class="text-slate-300 font-medium text-sm">ยังไม่มีภาพยนตร์ในคลังหลัก</p>
                        <p class="text-xs text-slate-500 mt-0.5">ภาพยนตร์ที่ผ่านการอนุมัติแล้วจะแสดงขึ้นที่นี่</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
