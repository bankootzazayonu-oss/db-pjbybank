<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-black text-xl text-white tracking-tight flex items-center gap-2">
                <span>📂</span> ภาพยนตร์ที่ฉันเสนอ (My Submitted Movies)
            </h1>
            <a href="{{ route('activities.create') }}" class="text-xs font-semibold bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white py-1.5 px-3.5 rounded-xl shadow-lg shadow-rose-950/40 transition">
                ➕ เสนอเรื่องใหม่
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl font-semibold shadow-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-2xl font-semibold shadow-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-slate-900/60 rounded-3xl shadow-2xl overflow-hidden border border-slate-800/90 backdrop-blur-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-slate-300">
                    <thead class="bg-slate-950/80 font-bold uppercase text-[11px] tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">ชื่อภาพยนตร์</th>
                            <th class="px-6 py-4 text-center">ปีที่ฉาย</th>
                            <th class="px-6 py-4 text-center">สถานะการตรวจสอบ</th>
                            <th class="px-6 py-4 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70 text-sm">
                        @forelse($movies as $movie)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white text-base">
                                        {{ $movie->name }}
                                    </div>
                                    <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">
                                        {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center text-slate-300 font-medium">
                                    {{ $movie->year }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($movie->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-3 py-1 rounded-full text-xs font-semibold">
                                            <span>●</span> อนุมัติแล้ว
                                        </span>
                                    @elseif($movie->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 px-3 py-1 rounded-full text-xs font-semibold">
                                            <span>●</span> ถูกปฏิเสธ
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 px-3 py-1 rounded-full text-xs font-semibold">
                                            <span>●</span> รอตรวจสอบ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-wrap justify-center items-center gap-2">
                                        {{-- Admin แก้ได้ทุกสถานะ --}}
                                        @if(auth()->user()->role === 'admin')
                                            <a href="{{ route('activities.edit', $movie->id) }}"
                                               class="text-indigo-400 hover:text-white font-semibold text-xs bg-slate-800 hover:bg-indigo-600 px-3 py-1.5 rounded-xl transition">
                                                ✏️ แก้ไข
                                            </a>
                                        {{-- User แก้ได้เฉพาะ Pending / Rejected --}}
                                        @elseif(in_array($movie->status, ['pending', 'rejected']))
                                            <a href="{{ route('activities.edit', $movie->id) }}"
                                               class="text-indigo-400 hover:text-white font-semibold text-xs bg-slate-800 hover:bg-indigo-600 px-3 py-1.5 rounded-xl transition">
                                                ✏️ แก้ไข
                                            </a>
                                        @else
                                            <span class="text-slate-500 text-xs italic">
                                                🔒 ล็อกการแก้ไข
                                            </span>
                                        @endif

                                        {{-- Approved เท่านั้นที่ดู / รีวิวได้ --}}
                                        @if($movie->status === 'approved')
                                            <a href="{{ route('activities.show', $movie->id) }}"
                                               class="text-white font-semibold text-xs bg-emerald-600 hover:bg-emerald-500 px-3 py-1.5 rounded-xl shadow transition">
                                                🎬 ดู / รีวิว
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                    คุณยังไม่เคยเสนอภาพยนตร์เข้าสู่ระบบ
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>