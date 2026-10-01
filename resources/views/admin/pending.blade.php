<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🛡️ รายการภาพยนตร์รอการอนุมัติ (แอดมิน)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('error') }}</div>
        @endif

        <!-- ปรับเป็น Grid 2 คอลัมน์เพื่อความกว้างที่พอดี -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($movies as $movie)
                <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md border border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row">
                    
                    <!-- โซนโปสเตอร์แนวตั้ง สัดส่วนมาตรฐาน 2:3 ไม่โดนตัดหัว -->
                    <div class="w-full sm:w-44 flex-shrink-0 bg-gray-900 aspect-[2/3] sm:aspect-auto sm:h-full relative">
                        @if($movie->image)
                            <img src="{{ \Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                 alt="{{ $movie->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 min-h-[180px]">
                                <span class="text-3xl mb-1">🎬</span>
                                <span class="text-xs">ไม่มีรูปภาพ</span>
                            </div>
                        @endif
                    </div>

                    <!-- รายละเอียดและปุ่มจัดการ -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white leading-snug">{{ $movie->name }} ({{ $movie->year }})</h3>
                                <span class="bg-indigo-600 text-white text-xs font-bold px-2.5 py-1 rounded-full whitespace-nowrap shadow-sm">
                                    {{ $movie->type ? $movie->type->name : 'ไม่ระบุหมวด' }}
                                </span>
                            </div>
                            
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                เสนอโดย: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $movie->user->name ?? 'ไม่ระบุ' }}</span> 
                                ({{ $movie->created_at->diffForHumans() }})
                            </p>
                            
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed mb-4">
                                {{ $movie->review }}
                            </p>
                        </div>

                        <!-- ปุ่มจัดการ -->
                        <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <form action="{{ route('admin.movies.approve', $movie->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('ยืนยันการอนุมัติภาพยนตร์เรื่องนี้?')" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-3 rounded-md text-sm transition">
                                    ✅ อนุมัติ
                                </button>
                            </form>

                            <form action="{{ route('activities.destroy', $movie->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('ต้องการปัดตกและลบรายการนี้ใช่หรือไม่?')" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-md text-sm transition">
                                    🗑️ ปัดตก
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <span class="text-4xl block mb-2">🎉</span>
                    <p class="text-gray-500 dark:text-gray-400 font-bold">ไม่มีรายการรออนุมัติ</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>