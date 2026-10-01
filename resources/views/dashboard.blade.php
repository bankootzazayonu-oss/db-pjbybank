<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🍿 คลังภาพยนตร์ทั้งหมด
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Grid แสดงหนัง -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($movies as $movie)
                
                <!-- 🟢 ดึงข้อมูลรีวิวมาคำนวณ (ใช้ Error Control เผื่อไม่มี Relation) -->
                @php
                    $reviewCount = $movie->reviews ? $movie->reviews->count() : 0;
                    $avgRating = $reviewCount > 0 ? round($movie->reviews->avg('rating'), 1) : 0;
                @endphp

                <a href="{{ route('activities.show', $movie->id) }}" class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 border border-gray-200 dark:border-gray-700 block group relative flex flex-col h-full">
                    
                    <!-- โซนรูปโปสเตอร์ -->
                    <!-- 🟢 เพิ่ม overflow-hidden เพื่อกักรูปตอนซูม -->
                    <div class="h-64 bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                       @if($movie->image)
                            @if(\Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']))
                                <!-- 🟢 เพิ่ม group-hover:scale-110 ให้รูปซูมเข้าตอนเอาเมาส์ชี้ -->
                                <img src="{{ $movie->image }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                            @else
                                <img src="{{ asset('storage/' . $movie->image) }}" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                            @endif
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 transform group-hover:scale-110 transition duration-500">
                                <span class="text-4xl mb-2">🎬</span>
                                <span class="text-xs">ไม่มีรูปภาพ</span>
                            </div>
                        @endif
                        
                        <!-- ป้ายปีที่ฉาย -->
                        <div class="absolute top-2 right-2 bg-indigo-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-md backdrop-blur-sm bg-opacity-90">
                            ปี {{ $movie->year }}
                        </div>
                    </div>

                    <!-- โซนข้อความ (ใช้ flex-grow ดันให้ส่วนล่างสุดติดขอบเสมอ) -->
                    <div class="p-4 flex flex-col flex-grow">
                        <!-- ชื่อหนัง -->
                        <h3 class="text-gray-900 dark:text-white font-bold text-sm line-clamp-2 leading-tight group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition mb-2">
                            {{ $movie->name }}
                        </h3>
                        
                        <!-- ดันเนื้อหาส่วนนี้ลงไปล่างสุดของการ์ด -->
                        <div class="mt-auto space-y-2">
                            <!-- 🟢 โซนคะแนนรีวิว -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 {{ $avgRating > 0 ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
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

                            <!-- หมวดหมู่ -->
                            <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="inline-block text-xs font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 px-2 py-0.5 rounded-full">
                                    {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-16 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-600">
                    <span class="text-5xl block mb-4">🍿</span>
                    ยังไม่มีภาพยนตร์ในคลังหลัก (รอแอดมินอนุมัติ)
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>