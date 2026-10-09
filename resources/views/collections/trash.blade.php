<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-bold text-xl text-white flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-400"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                ถังขยะของฉัน (My Trash)
            </h1>
            <a href="{{ route('collections.index') }}" class="text-xs font-medium bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-lg transition">
                ← กลับหน้า Tier Lists
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl font-medium shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-xl backdrop-blur-xl">
            <p class="text-slate-400 text-sm mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                กระดานจัดอันดับที่ถูกลบไปแล้ว หากลบถาวรจะไม่สามารถกู้คืนได้อีก
            </p>

            <div class="space-y-4">
                @forelse($collections as $collection)
                    <div class="bg-slate-950/80 border border-slate-800/80 p-5 rounded-2xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:border-slate-700 transition">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1">{{ $collection->name }}</h3>
                            <p class="text-xs text-slate-500">
                                ลบเมื่อ: {{ $collection->deleted_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <form action="{{ route('collections.restore', $collection->id) }}" method="POST" class="flex-1 sm:flex-none">
                                @csrf
                                <button type="submit" class="w-full justify-center px-4 py-2 bg-emerald-600/20 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-xl text-sm font-semibold transition border border-emerald-500/30 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                    กู้คืน
                                </button>
                            </form>
                            <form action="{{ route('collections.forceDelete', $collection->id) }}" method="POST" class="flex-1 sm:flex-none" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบกระดานนี้ถาวร? (ไม่สามารถกู้คืนได้อีก)');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full justify-center px-4 py-2 bg-rose-600/20 text-rose-400 hover:bg-rose-500 hover:text-white rounded-xl text-sm font-semibold transition border border-rose-500/30 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    ลบถาวร
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 px-4 border-2 border-dashed border-slate-800 rounded-2xl">
                        <div class="mx-auto w-16 h-16 mb-4 bg-slate-900 rounded-full flex items-center justify-center border border-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        </div>
                        <p class="text-slate-400 font-medium">ไม่มีข้อมูลในถังขยะ</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
