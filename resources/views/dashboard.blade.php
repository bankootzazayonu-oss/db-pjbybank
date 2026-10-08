<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- ==========================================
             Header & Quick Stats
        ========================================== --}}
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span>📊</span> แดชบอร์ดภาพรวม
                    </h1>
                    <p class="text-sm text-slate-400 mt-1">
                        สถิติภาพยนตร์ คะแนนรีวิว และคลังภาพยนตร์ทั้งหมดที่เปิดให้บริการ
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('activities.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-semibold text-sm shadow-lg shadow-rose-950/40 transition hover:scale-[1.02]">
                        <span>➕</span> เสนอหนังใหม่
                    </a>
                </div>
            </div>

            {{-- 4 Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- จำนวนหนัง --}}
                <div class="bg-slate-900/60 rounded-2xl border border-slate-800/80 p-5 shadow-lg hover:border-slate-700 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">ภาพยนตร์ทั้งหมด</p>
                            <p class="text-3xl font-black text-white mt-2">{{ $totalMovies }}</p>
                            <p class="text-xs text-emerald-400 mt-1 font-medium">✓ ผ่านการอนุมัติแล้ว</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-2xl shadow-inner">
                            🎬
                        </div>
                    </div>
                </div>

                {{-- จำนวนรีวิว --}}
                <div class="bg-slate-900/60 rounded-2xl border border-slate-800/80 p-5 shadow-lg hover:border-slate-700 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">รีวิวทั้งหมด</p>
                            <p class="text-3xl font-black text-white mt-2">{{ $totalReviews }}</p>
                            <p class="text-xs text-slate-400 mt-1">จากสมาชิกชุมชน</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-2xl shadow-inner">
                            💬
                        </div>
                    </div>
                </div>

                {{-- คะแนนเฉลี่ย --}}
                <div class="bg-slate-900/60 rounded-2xl border border-slate-800/80 p-5 shadow-lg hover:border-slate-700 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">คะแนนเฉลี่ยรวม</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl font-black text-amber-400">
                                    {{ $overallAverageRating !== null ? number_format($overallAverageRating, 1) : '0.0' }}
                                </span>
                                <span class="text-sm text-slate-400">/ 10</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">จากคะแนนรีวิวทุกเรื่อง</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl shadow-inner">
                            ⭐
                        </div>
                    </div>
                </div>

                {{-- Genre อันดับ 1 --}}
                <div class="bg-slate-900/60 rounded-2xl border border-slate-800/80 p-5 shadow-lg hover:border-slate-700 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">หมวดหมู่ยอดนิยมอันดับ 1</p>
                            <p class="text-xl font-black text-white mt-2 truncate max-w-[150px]">
                                {{ $topGenres->first()?->name ?? 'ยังไม่มีข้อมูล' }}
                            </p>
                            <p class="text-xs text-amber-400 mt-1 font-medium">
                                {{ $topGenres->first() ? '⭐ ' . number_format($topGenres->first()->average_rating, 1) . ' / 10' : '-' }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-2xl shadow-inner">
                            🏆
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ==========================================
             Top 5 Genre จัดอันดับตามคะแนน
        ========================================== --}}
        <div class="bg-slate-900/60 rounded-2xl border border-slate-800/80 p-6 shadow-lg">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span>🏆</span> 5 อันดับหมวดหมู่ภาพยนตร์ยอดเยี่ยม
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">
                        คำนวณและจัดอันดับตามคะแนนรีวิวเฉลี่ยและจำนวนรีวิวของผู้ใช้
                    </p>
                </div>
            </div>

            @if($topGenres->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                    @foreach($topGenres as $index => $genre)
                        <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/90 flex flex-col justify-between hover:border-slate-700 transition">
                            <div class="flex items-center justify-between mb-3">
                                <span class="w-7 h-7 rounded-lg {{ $index === 0 ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : ($index === 1 ? 'bg-slate-400/20 text-slate-300 border border-slate-400/30' : 'bg-slate-800 text-slate-400') }} font-black flex items-center justify-center text-xs">
                                    #{{ $index + 1 }}
                                </span>
                                <span class="text-xs text-slate-400">{{ $genre->review_count }} รีวิว</span>
                            </div>

                            <div>
                                <h3 class="font-bold text-white text-base truncate">{{ $genre->name }}</h3>
                                <div class="mt-2 flex items-center gap-1.5 text-amber-400 font-extrabold text-sm">
                                    <span>⭐</span>
                                    <span>{{ number_format($genre->average_rating, 1) }}</span>
                                    <span class="text-[10px] text-slate-400 font-normal">/ 10</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-500 text-sm">
                    ยังไม่มีข้อมูลรีวิวเพียงพอสำหรับการจัดอันดับหมวดหมู่
                </div>
            @endif
        </div>

        {{-- ==========================================
             คลังภาพยนตร์ทั้งหมด
        ========================================== --}}
        <div>
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-black text-white tracking-tight flex items-center gap-2">
                        <span>🍿</span> คลังภาพยนตร์ทั้งหมด
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">คลิกที่ภาพยนตร์เพื่อดูเรื่องย่อและอ่านรีวิวทั้งหมด</p>
                </div>

                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-slate-300">
                    {{ $movies->count() }} เรื่องในระบบ
                </span>
            </div>

            {{-- Grid หนัง --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @forelse($movies as $movie)
                    @php
                        $reviewCount = $movie->reviews ? $movie->reviews->count() : 0;
                        $avgRating = $reviewCount > 0 ? round($movie->reviews->avg('rating'), 1) : 0;
                    @endphp

                    <a href="{{ route('activities.show', $movie->id) }}" 
                       class="group bg-slate-900/60 rounded-2xl border border-slate-800/80 overflow-hidden flex flex-col hover:border-slate-700 hover:shadow-2xl hover:shadow-slate-950 transition duration-300">
                        
                        {{-- Poster Container --}}
                        <div class="relative aspect-[2/3] bg-slate-950 overflow-hidden">
                            @if($movie->image)
                                <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}"
                                     alt="{{ $movie->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-slate-900">
                                    <span class="text-4xl">🎬</span>
                                    <span class="text-[11px] mt-2">ไม่มีรูปภาพ</span>
                                </div>
                            @endif

                            {{-- Year Badge (Top-Left) --}}
                            <div class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded-lg bg-slate-950/80 backdrop-blur-md border border-white/10 text-[11px] font-bold text-slate-300 shadow">
                                {{ $movie->year }}
                            </div>

                            {{-- Rating Badge (Top-Right) --}}
                            <div class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded-lg bg-slate-950/80 backdrop-blur-md border border-white/10 flex items-center gap-1 shadow">
                                <span class="text-amber-400 text-xs">⭐</span>
                                <span class="text-xs font-bold {{ $avgRating > 0 ? 'text-white' : 'text-slate-400' }}">
                                    {{ $avgRating > 0 ? number_format($avgRating, 1) : '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Card Details --}}
                        <div class="p-3.5 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-100 group-hover:text-rose-400 transition line-clamp-1 leading-snug">
                                    {{ $movie->name }}
                                </h3>
                                <p class="text-[11px] text-slate-400 mt-1 line-clamp-1">
                                    {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                </p>
                            </div>

                            <div class="mt-3 pt-2.5 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-slate-400">
                                <span>💬 {{ $reviewCount }} รีวิว</span>
                                <span class="text-rose-400 font-semibold group-hover:translate-x-0.5 transition duration-200">ดูรีวิว →</span>
                            </div>
                        </div>

                    </a>
                @empty
                    <div class="col-span-full text-center py-16 bg-slate-900/40 rounded-2xl border border-dashed border-slate-800">
                        <span class="text-5xl block mb-3">🍿</span>
                        <p class="text-slate-300 font-medium">ยังไม่มีภาพยนตร์ในคลังหลัก</p>
                        <p class="text-xs text-slate-500 mt-1">ภาพยนตร์ที่ผ่านการอนุมัติแล้วจะแสดงขึ้นที่นี่</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
