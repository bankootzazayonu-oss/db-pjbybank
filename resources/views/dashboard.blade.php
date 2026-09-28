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
                <a href="{{ route('activities.show', $movie->id) }}" class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:scale-105 hover:shadow-xl transition duration-300 border border-gray-200 dark:border-gray-700 block group relative">
                    
                    <!-- โซนรูปโปสเตอร์ -->
                    <div class="h-64 bg-gray-100 dark:bg-gray-700 relative">
                       @if($movie->image)
                            @if(\Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']))
                                <img src="{{ $movie->image }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('storage/' . $movie->image) }}" class="w-full h-full object-cover">
                            @endif
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                <span class="text-4xl mb-2">🎬</span>
                                <span class="text-xs">ไม่มีรูปภาพ</span>
                            </div>
                        @endif
                        
                        <!-- ป้ายปีที่ฉาย -->
                        <div class="absolute top-2 right-2 bg-indigo-600 text-white text-xs font-black px-2 py-1 rounded-md opacity-90 shadow-md">
                            {{ $movie->year }}
                        </div>
                    </div>

                    <!-- โซนข้อความ -->
                    <div class="p-4">
                        <h3 class="text-gray-900 dark:text-white font-bold text-sm truncate group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition">
                            {{ $movie->name }}
                        </h3>
                        <div class="flex justify-between items-center mt-2">
                            <p class="text-gray-500 dark:text-gray-400 text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                {{ $movie->type ? $movie->type->name : 'ไม่ระบุหมวดหมู่' }}
                            </p>
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