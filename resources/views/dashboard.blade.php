<x-app-layout>
    {{-- ==========================================
     Dashboard Statistics
========================================== --}}
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- หัวข้อ --}}
        <div class="mb-6">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white">
                📊 สถิติภาพรวม
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                ภาพรวมข้อมูลภาพยนตร์และรีวิวในระบบ
            </p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- จำนวนหนัง --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            ภาพยนตร์ทั้งหมด
                        </p>

                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">
                            {{ $totalMovies }}
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            เรื่องที่อนุมัติแล้ว
                        </p>
                    </div>

                    <div class="text-4xl">
                        🎬
                    </div>
                </div>
            </div>

            {{-- จำนวนรีวิว --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            รีวิวทั้งหมด
                        </p>

                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">
                            {{ $totalReviews }}
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            รีวิวจากผู้ใช้
                        </p>
                    </div>

                    <div class="text-4xl">
                        ⭐
                    </div>
                </div>
            </div>

            {{-- คะแนนเฉลี่ย --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            คะแนนเฉลี่ย
                        </p>

                        <p class="text-3xl font-black text-gray-900 dark:text-white mt-2">
                            {{ $overallAverageRating !== null ? number_format($overallAverageRating, 1) : '0.0' }}
                            <span class="text-lg text-gray-400">/10</span>
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            คะแนนจากรีวิวทั้งหมด
                        </p>
                    </div>

                    <div class="text-4xl">
                        📊
                    </div>
                </div>
            </div>

            {{-- Genre --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Genre อันดับ 1
                        </p>

                        <p class="text-2xl font-black text-gray-900 dark:text-white mt-2">
                            {{ $topGenres->first()?->name ?? 'ยังไม่มีข้อมูล' }}
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            คะแนนเฉลี่ย
                            {{ $topGenres->first()
                                ? number_format($topGenres->first()->average_rating, 1) . '/10'
                                : '-' }}
                        </p>
                    </div>

                    <div class="text-4xl">
                        🏆
                    </div>
                </div>
            </div>

        </div>

        {{-- Top 5 Genre --}}
        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        🏆 Top 5 Genre
                    </h3>

                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        จัดอันดับตามคะแนนเฉลี่ยของรีวิว
                    </p>
                </div>
            </div>

            @if($topGenres->count() > 0)

                <div class="space-y-3">

                    @foreach($topGenres as $index => $genre)

                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/50 rounded-lg px-4 py-3">

                            <div class="flex items-center gap-3">

                                <span class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 font-black flex items-center justify-center text-sm">
                                    {{ $index + 1 }}
                                </span>

                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ $genre->name }}
                                    </p>

                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $genre->review_count }} รีวิว
                                    </p>
                                </div>

                            </div>

                            <div class="text-right">
                                <p class="font-black text-yellow-500">
                                    ⭐ {{ number_format($genre->average_rating, 1) }}
                                </p>

                                <p class="text-[10px] text-gray-400">
                                    / 10
                                </p>
                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="text-center py-8 text-gray-400">
                    ยังไม่มีข้อมูลรีวิวสำหรับจัดอันดับ Genre
                </div>

            @endif

        </div>

    </div>
</div>
{{-- ==========================================
         คลังภาพยนตร์
    ========================================== --}}
    <div class="mt-8">

        <div class="flex items-center justify-between mb-5">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                🍿 คลังภาพยนตร์ทั้งหมด
            </h3>

            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ $movies->count() }} เรื่อง
            </span>
        </div>


        {{-- Grid หนัง --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">

            @forelse($movies as $movie)

                @php
                    $reviewCount = $movie->reviews ? $movie->reviews->count() : 0;

                    $avgRating = $reviewCount > 0
                        ? round($movie->reviews->avg('rating'), 1)
                        : 0;
                @endphp


                {{-- การ์ดหนัง --}}
                <a
                    href="{{ route('activities.show', $movie->id) }}"
                    class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 block group relative flex flex-col h-full"
                >

                    {{-- Poster --}}
                    <div class="h-64 bg-gray-100 dark:bg-gray-700 relative overflow-hidden">

                        @if($movie->image)

                            @if(\Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']))

                                <img
                                    src="{{ $movie->image }}"
                                    alt="{{ $movie->name }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                                >

                            @else

                                <img
                                    src="{{ asset('storage/' . $movie->image) }}"
                                    alt="{{ $movie->name }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                                >

                            @endif

                        @else

                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <span class="text-4xl mb-2">🎬</span>
                                <span class="text-xs">ไม่มีรูปภาพ</span>
                            </div>

                        @endif


                        {{-- ปี --}}
                        <div class="absolute top-2 right-2 bg-indigo-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-md">
                            {{ $movie->year }}
                        </div>

                    </div>


                    {{-- ข้อมูลหนัง --}}
                    <div class="p-4 flex flex-col flex-grow">

                        {{-- ชื่อหนัง --}}
                        <h3 class="text-gray-900 dark:text-white font-bold text-sm line-clamp-2 leading-tight group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition mb-3">
                            {{ $movie->name }}
                        </h3>


                        <div class="mt-auto space-y-2">

                            {{-- คะแนน --}}
                            <div class="flex items-center justify-between">

                                <div class="flex items-center gap-1">

                                    <span class="text-yellow-400">
                                        ⭐
                                    </span>

                                    <span class="text-xs font-bold {{ $avgRating > 0 ? 'text-gray-700 dark:text-gray-300' : 'text-gray-400' }}">
                                        {{ $avgRating > 0 ? number_format($avgRating, 1) : 'ไม่มีคะแนน' }}
                                    </span>

                                </div>


                                @if($reviewCount > 0)

                                    <span class="text-[10px] text-gray-500 dark:text-gray-400">
                                        👤 {{ $reviewCount }} รีวิว
                                    </span>

                                @endif

                            </div>


                            {{-- Genre --}}
                            <div class="pt-2 border-t border-gray-100 dark:border-gray-700">

                                <span class="inline-block text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full">
                                    {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                </span>

                            </div>

                        </div>

                    </div>

                </a>

            @empty

                <div class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">

                    <span class="text-5xl block mb-4">
                        🍿
                    </span>

                    <p class="text-gray-500 dark:text-gray-400">
                        ยังไม่มีภาพยนตร์ในคลังหลัก
                    </p>

                    <p class="text-sm text-gray-400 mt-1">
                        ภาพยนตร์ที่ได้รับการอนุมัติจะแสดงที่นี่
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>
</x-app-layout>
