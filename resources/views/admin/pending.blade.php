<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white flex items-center gap-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            รายการภาพยนตร์รอการอนุมัติ (แอดมิน)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium p-4 rounded-xl mb-6 shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 font-medium p-4 rounded-xl mb-6 shadow-sm">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($movies as $movie)
                <div class="bg-slate-900 rounded-xl overflow-hidden border border-slate-800 flex flex-col sm:flex-row shadow-sm">
                    
                    <div class="w-full sm:w-44 flex-shrink-0 bg-slate-950 aspect-[2/3] sm:aspect-auto sm:h-full relative border-b sm:border-b-0 sm:border-r border-slate-800">
                        @if($movie->image)
                            <img src="{{ \Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                 alt="{{ $movie->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 min-h-[180px]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M17 3v18"/><path d="M3 7h4"/><path d="M3 13h4"/><path d="M3 17h4"/><path d="M17 7h4"/><path d="M17 13h4"/><path d="M17 17h4"/></svg>
                                <span class="text-[10px] text-slate-500">ไม่มีรูปภาพ</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <h3 class="font-bold text-lg text-white leading-snug">{{ $movie->name }} ({{ $movie->year }})</h3>
                                <span class="bg-indigo-600 text-white text-xs font-bold px-2.5 py-1 rounded-md whitespace-nowrap shadow-sm">
                                    {{ $movie->type ? $movie->type->name : 'ไม่ระบุหมวด' }}
                                </span>
                            </div>
                            
                            <p class="text-xs text-slate-400 mb-3">
                                เสนอโดย: <span class="font-semibold text-slate-300">{{ $movie->user->name ?? 'ไม่ระบุ' }}</span> 
                                ({{ $movie->created_at->diffForHumans() }})
                            </p>
                            
                            <p class="text-sm text-slate-300 line-clamp-3 leading-relaxed mb-4">
                                {{ $movie->review ?: 'ไม่มีเรื่องย่อสำหรับภาพยนตร์เรื่องนี้' }}
                            </p>
                        </div>

                        
                        <div class="grid grid-cols-2 gap-3 pt-4 border-t border-slate-800">

                            <form action="{{ route('admin.movies.approve', $movie->id) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    onclick="return confirm('ยืนยันการอนุมัติภาพยนตร์เรื่องนี้?')"
                                    class="w-full flex items-center justify-center gap-1.5 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 font-medium py-2 px-3 rounded-lg text-sm transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    อนุมัติ
                                </button>
                            </form>

                            <form action="{{ route('admin.movies.reject', $movie->id) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    onclick="return confirm('ต้องการปฏิเสธภาพยนตร์เรื่องนี้หรือไม่?')"
                                    class="w-full flex items-center justify-center gap-1.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 font-medium py-2 px-3 rounded-lg text-sm transition"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    ปฏิเสธ
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center py-16 bg-slate-900/40 rounded-xl border border-dashed border-slate-800 flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 text-slate-600"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <p class="text-slate-300 font-medium">ไม่มีรายการรออนุมัติ</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>