<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🏆 กระดานจัดอันดับของฉัน (My Tier Lists)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 font-bold shadow-md">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- ฟอร์มสร้างกระดาน -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 h-fit">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">➕ สร้างกระดานใหม่</h3>
                <form action="{{ route('collections.store') }}" method="POST">
                    @csrf
                    <input type="text" name="name" required placeholder="เช่น หนังซอมบี้ห้ามพลาด" class="w-full mb-4 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-md transition">สร้างเลย</button>
                </form>
            </div>

            <!-- แสดงกระดานที่มีอยู่ -->
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($collections as $collection)
                    <a href="{{ route('collections.show', $collection->id) }}" class="block bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 hover:border-indigo-500 transition group">
                        <div class="text-indigo-500 mb-2">
                            <svg class="w-8 h-8 group-hover:scale-110 transition" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm1 4h10v2H5V7zm0 4h10v2H5v-2z"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $collection->name }}</h4>
                        <p class="text-sm text-gray-500 mt-1">อัปเดตล่าสุด: {{ $collection->updated_at->diffForHumans() }}</p>
                    </a>
                @empty
                    <div class="col-span-2 text-center text-gray-500 py-10 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-600">
                        ยังไม่มีกระดานจัดอันดับ ลองสร้างทางซ้ายมือดูสิ!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>