<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-bold text-xl text-white flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-400"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                10 อันดับภาพยนตร์ยอดเยี่ยม (Leaderboard)
            </h1>
            <a href="{{ route('dashboard') }}" class="text-xs font-medium bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-lg transition">
                ← กลับหน้าคลังหนัง
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="text-center max-w-xl mx-auto mb-8">
            <p class="text-xs uppercase tracking-wider text-indigo-400 font-bold">Community Rankings</p>
            <h2 class="text-3xl font-black text-white mt-1">ภาพยนตร์ขวัญใจมหาชน</h2>
            <p class="text-xs text-slate-400 mt-2">จัดอันดับโดยคำนวณจากคะแนนเฉลี่ยรีวิวของสมาชิกในระบบ</p>
        </div>

        <div class="bg-slate-900/60 rounded-3xl shadow-2xl overflow-hidden border border-slate-800/90 backdrop-blur-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-slate-300">
                    <thead class="bg-slate-950/80 font-bold uppercase text-[11px] tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4 text-center w-24">อันดับ</th>
                            <th class="px-6 py-4">ภาพยนตร์</th>
                            <th class="px-6 py-4 text-center">คะแนนเฉลี่ย</th>
                            <th class="px-6 py-4 text-center">จำนวนรีวิว</th>
                            <th class="px-6 py-4 text-center">แอ็กชัน</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70 text-sm">
                        @forelse($topMovies as $index => $movie)
                            <tr class="hover:bg-slate-800/40 transition group">
                                <td class="px-6 py-4 text-center">
                                    @if($index === 0)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-500/10 text-amber-500 border border-amber-500/30 font-bold text-sm shadow-sm">
                                            1
                                        </span>
                                    @elseif($index === 1)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-400/10 text-slate-300 border border-slate-400/30 font-bold text-sm shadow-sm">
                                            2
                                        </span>
                                    @elseif($index === 2)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700/10 text-amber-600 border border-amber-700/30 font-bold text-sm shadow-sm">
                                            3
                                        </span>
                                    @else
                                        <span class="text-slate-500 font-medium text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 rounded-md bg-slate-950 overflow-hidden flex-shrink-0 border border-slate-800">
                                            @if($movie->image)
                                                <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                                     alt="{{ $movie->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M17 3v18"/><path d="M3 7h4"/><path d="M3 13h4"/><path d="M3 17h4"/><path d="M17 7h4"/><path d="M17 13h4"/><path d="M17 17h4"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('activities.show', $movie->id) }}" class="font-bold text-white group-hover:text-indigo-400 transition text-base block">
                                                {{ $movie->name }}
                                            </a>
                                            <span class="text-xs text-slate-400">ปี {{ $movie->year }} • {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-500/10 border border-amber-500/20 text-amber-400 font-bold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        <span>{{ number_format($movie->reviews_avg_rating, 1) }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-slate-400 text-xs">
                                    {{ $movie->reviews_count }} คน
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('activities.show', $movie->id) }}" class="text-xs font-medium px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-200 hover:text-white transition">
                                        ดูรีวิว
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    ยังไม่มีข้อมูลภาพยนตร์ที่มีรีวิวในระบบ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>