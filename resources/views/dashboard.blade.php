<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🍿 คลังภาพยนตร์ทั้งหมดในระบบ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- แสดงผลลัพธ์แบบ Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($movies as $movie)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:scale-105 transition duration-300">
                        <!-- รูปโปสเตอร์ -->
                        @if(!empty($movie->image))
                            <img src="{{ $movie->image }}" alt="{{ $movie->name }}" class="w-full h-80 object-cover">
                        @else
                            <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">🎬 ไม่มีรูปโปสเตอร์</div>
                        @endif
                        
                        <!-- รายละเอียด -->
                        <div class="p-5">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate mb-1" title="{{ $movie->name }}">
                                {{ $movie->name }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                ปีที่ฉาย: {{ $movie->year ?? 'ไม่ระบุ' }}
                            </p>
                            
                            <!-- ปุ่มเข้าไปดูรีวิว (เดี๋ยวเราทำหน้ารีวิวต่อใน EP หน้า) -->
                            <a href="{{ route('activities.show', $movie->id) }}" class="block text-center w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md text-sm transition shadow-md">
    ⭐ ดูรายละเอียด & รีวิว
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- กรณีที่ยังไม่มีหนังในระบบเลย -->
                    <div class="col-span-full text-center py-10 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <p class="text-gray-500 dark:text-gray-400 text-lg">ยังไม่มีภาพยนตร์ในระบบ</p>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.movies.search') }}" class="text-indigo-500 hover:text-indigo-400 mt-2 inline-block font-bold">
                                + ไปค้นหาและนำเข้าภาพยนตร์เลย
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>