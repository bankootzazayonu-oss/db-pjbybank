<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-bold text-xl text-white flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-400"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                ถังขยะส่วนกลาง (System Trash)
            </h1>
            <a href="{{ route('admin.movies.pending') }}" class="text-xs font-medium bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-lg transition">
                ← กลับหน้ารออนุมัติ
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl font-medium shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl font-medium shadow-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-xl backdrop-blur-xl">
            <p class="text-slate-400 text-sm mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                ภาพยนตร์ที่ถูกลบออกจากระบบ หากเลือกลบถาวร ข้อมูลและรูปภาพจะถูกลบออกจากเซิร์ฟเวอร์โดยสมบูรณ์
            </p>

            <div class="space-y-4">
                @forelse($movies as $movie)
                    <div class="bg-slate-950/80 border border-slate-800/80 p-5 rounded-2xl flex flex-col sm:flex-row gap-6 hover:border-slate-700 transition">
                        <!-- Poster -->
                        <div class="w-24 h-36 shrink-0 rounded-xl overflow-hidden bg-slate-900 shadow-md">
                            @if($movie->image)
                                <img src="{{ Str::startsWith($movie->image, 'http') ? $movie->image : asset('storage/' . $movie->image) }}" alt="{{ $movie->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 space-y-2">
                            <h3 class="text-xl font-bold text-white leading-tight">
                                {{ $movie->name }} <span class="text-slate-400 font-normal">({{ $movie->year }})</span>
                            </h3>
                            @if($movie->original_title)
                                <p class="text-sm text-slate-400 font-medium">{{ $movie->original_title }}</p>
                            @endif
                            <div class="flex flex-wrap gap-2 pt-1">
                                <span class="px-2 py-1 bg-slate-800/50 text-slate-300 text-xs rounded-md border border-slate-700/50">
                                    หมวดหมู่: {{ $movie->type ? $movie->type->name : '-' }}
                                </span>
                                <span class="px-2 py-1 bg-slate-800/50 text-slate-300 text-xs rounded-md border border-slate-700/50">
                                    ผู้ลบ/ระบบ: {{ $movie->deleted_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-2 min-w-[120px] justify-center">
                            <form action="{{ route('admin.movies.restore', $movie->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full justify-center px-4 py-2.5 bg-emerald-600/20 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-xl text-sm font-semibold transition border border-emerald-500/30 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                    กู้คืน
                                </button>
                            </form>
                            <form action="{{ route('admin.movies.forceDelete', $movie->id) }}" method="POST" onsubmit="return confirm('คำเตือน: คุณต้องการลบภาพยนตร์เรื่องนี้ออกจากฐานข้อมูลอย่างถาวรใช่หรือไม่? (ไม่สามารถกู้คืนได้ และรูปภาพจะถูกลบ)');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full justify-center px-4 py-2.5 bg-rose-600/20 text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl text-sm font-semibold transition border border-rose-500/30 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    ลบถาวร
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 px-4 border-2 border-dashed border-slate-800 rounded-2xl">
                        <div class="mx-auto w-16 h-16 mb-4 bg-slate-900 rounded-full flex items-center justify-center border border-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </div>
                        <p class="text-slate-400 font-medium">ไม่มีภาพยนตร์ในถังขยะ</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-6">
                {{ $movies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
