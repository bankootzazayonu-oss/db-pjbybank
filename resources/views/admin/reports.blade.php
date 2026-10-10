<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="font-bold text-lg sm:text-xl text-white flex items-center gap-2.5 flex-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-400 shrink-0"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                จัดการรายงานคอมเมนต์ไม่เหมาะสม (แอดมิน)
            </h2>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-medium p-4 rounded-xl mb-6 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-slate-900 rounded-xl shadow-lg border border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-800 bg-rose-500/5">
                <h3 class="text-lg font-bold text-rose-400 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    รายการแจ้งความ ({{ $reports->count() }} รายการ)
                </h3>
                <p class="text-sm text-rose-500/70 mt-1">โปรดพิจารณาและจัดการคอมเมนต์ที่ผู้ใช้งานกดรายงานเข้ามา</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/50 text-slate-400 text-xs uppercase tracking-wider">
                            <th class="p-4 font-bold border-b border-slate-800 w-1/4">ผู้ถูกรายงาน (เจ้าของคอมเมนต์)</th>
                            <th class="p-4 font-bold border-b border-slate-800 w-1/3">ข้อความที่ถูกรายงาน</th>
                            <th class="p-4 font-bold border-b border-slate-800">ผู้แจ้งรายงาน</th>
                            <th class="p-4 font-bold border-b border-slate-800 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800 text-sm">
                        @forelse($reports as $report)
                            @if($report->review)
                                <tr class="hover:bg-slate-800/40 transition">
                                    
                                    <td class="p-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 font-bold text-xs shadow-sm">
                                                {{ strtoupper(substr($report->review->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-white block">{{ $report->review->user->name ?? 'Unknown' }}</span>
                                                <span class="text-xs text-slate-500">รีวิวเรื่อง: <a href="{{ route('activities.show', $report->review->activity_id) }}" class="text-indigo-400 hover:underline" target="_blank">{{ $report->review->activity->name ?? 'ไม่ทราบชื่อหนัง' }}</a></span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <div class="bg-slate-950 p-3 rounded-lg border-l-2 border-rose-500 text-sm text-slate-300 whitespace-pre-line shadow-inner">
                                            {{ $report->review->comment }}
                                        </div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <div class="text-sm">
                                            <p class="font-bold text-slate-300 flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                {{ $report->user->name ?? 'ผู้ใช้ทั่วไป' }}
                                            </p>
                                            <p class="text-[11px] text-slate-500 mt-1">แจ้งเมื่อ: {{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4">
                                        <div class="flex flex-col gap-2">
                                            <form action="{{ route('admin.reviews.destroy', $report->review->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('ลบคอมเมนต์นี้ทิ้งถาวรเลยหรือไม่?')" class="w-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 text-xs font-medium py-2 px-3 rounded-lg shadow-sm transition flex justify-center items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                    แบนคอมเมนต์
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.reports.dismiss', $report->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('ปัดตกรีพอร์ตนี้ (เก็บคอมเมนต์ไว้เหมือนเดิม)?')" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-xs font-medium py-2 px-3 rounded-lg shadow-sm transition flex justify-center items-center gap-1.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                    ปัดตกรีพอร์ต
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="p-16 text-center text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-slate-600"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                    <p class="font-bold text-lg text-slate-300">ความสงบสุขบังเกิด</p>
                                    <p class="text-sm mt-1 text-slate-500">ยังไม่มีใครแจ้งรายงานคอมเมนต์เข้ามาเลยครับ!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>