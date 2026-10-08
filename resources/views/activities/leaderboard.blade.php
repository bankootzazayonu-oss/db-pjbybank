<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-black text-xl text-white tracking-tight flex items-center gap-2">
                <span>🏆</span> 10 อันดับภาพยนตร์ยอดเยี่ยม (Leaderboard)
            </h1>
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-xl transition">
                ← กลับหน้าคลังหนัง
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="text-center max-w-xl mx-auto mb-8">
            <p class="text-xs uppercase tracking-wider text-rose-400 font-bold">Community Rankings</p>
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
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4 text-center">
                                    @if($index === 0)
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/40 font-black text-base shadow-sm">
                                            🥇 1
                                        </span>
                                    @elseif($index === 1)
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-300/20 text-slate-200 border border-slate-400/40 font-black text-base shadow-sm">
                                            🥈 2
                                        </span>
                                    @elseif($index === 2)
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-amber-700/20 text-amber-500 border border-amber-600/40 font-black text-base shadow-sm">
                                            🥉 3
                                        </span>
                                    @else
                                        <span class="text-slate-500 font-bold text-sm">
                                            #{{ $index + 1 }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 rounded-lg bg-slate-950 overflow-hidden flex-shrink-0 border border-slate-800">
                                            @if($movie->image)
                                                <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                                     alt="{{ $movie->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-xs text-slate-600">🎬</div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('activities.show', $movie->id) }}" class="font-bold text-white hover:text-rose-400 transition text-base block">
                                                {{ $movie->name }}
                                            </a>
                                            <span class="text-xs text-slate-400">ปี {{ $movie->year }} • {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-400 font-black text-sm">
                                        <span>⭐</span>
                                        <span>{{ number_format($movie->reviews_avg_rating, 1) }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-slate-400 text-xs">
                                    {{ $movie->reviews_count }} คน
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('activities.show', $movie->id) }}" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-rose-600 text-slate-200 hover:text-white transition">
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