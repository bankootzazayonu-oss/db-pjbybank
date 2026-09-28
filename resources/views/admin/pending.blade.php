<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🛡️ รายการภาพยนตร์รอการอนุมัติ (แอดมิน)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($movies as $movie)
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md border border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-lg text-white mb-2">{{ $movie->name }} ({{ $movie->year }})</h3>
                    <p class="text-sm text-gray-400 mb-4 line-clamp-3">{{ $movie->review }}</p>
                    
                    <form action="{{ route('admin.movies.approve', $movie->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">
                            ✅ อนุมัติให้แสดงหน้าเว็บ
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500">ไม่มีรายการรออนุมัติ</div>
            @endforelse
        </div>
    </div>
</x-app-layout>