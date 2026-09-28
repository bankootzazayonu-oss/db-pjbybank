<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🔍 ค้นหาหนังจากฐานข้อมูลโลก (TMDB)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- แจ้งเตือนเมื่อนำเข้าสำเร็จ หรือ ซ้ำ -->
            @if(session('success'))
                <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- กล่องค้นหา -->
            <form action="{{ route('admin.movies.search') }}" method="GET" class="mb-8 flex gap-4">
                <input type="text" name="query" value="{{ $query ?? '' }}" placeholder="พิมพ์ชื่อหนังที่ต้องการหา (เช่น Avatar, สัปเหร่อ)..." required 
                       class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm text-lg py-3">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-md transition">
                    ค้นหา
                </button>
            </form>

            <!-- แสดงผลลัพธ์แบบ Grid -->
            @if(isset($movies) && count($movies) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($movies as $movie)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 hover:scale-105 transition duration-300">
                            <!-- รูปโปสเตอร์ -->
                            @if(!empty($movie['poster_path']))
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}" class="w-full h-80 object-cover">
                            @else
                                <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">🎬 ไม่มีรูปโปสเตอร์</div>
                            @endif
                            
                            <!-- รายละเอียด -->
                            <div class="p-5">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate mb-1" title="{{ $movie['title'] }}">
                                    {{ $movie['title'] }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    ปีที่ฉาย: {{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : 'ไม่ระบุ' }}
                                </p>
                                
                                <!-- ฟอร์มนำเข้าข้อมูล -->
                                <form action="{{ route('admin.movies.import') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="title" value="{{ $movie['title'] }}">
                                    <input type="hidden" name="year" value="{{ !empty($movie['release_date']) ? substr($movie['release_date'], 0, 4) : '' }}">
                                    <input type="hidden" name="overview" value="{{ $movie['overview'] ?? 'ไม่มีเรื่องย่อ' }}">
                                    <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                                    
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md text-sm transition flex justify-center items-center gap-2 shadow-md">
                                        📥 + นำเข้าสู่ระบบเรา
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(isset($query))
                <div class="text-center py-10 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg">
                    ไม่พบข้อมูลหนังชื่อ "{{ $query }}"
                </div>
            @endif

        </div>
    </div>
</x-app-layout>