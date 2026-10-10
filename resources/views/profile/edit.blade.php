<x-app-layout>
    <x-slot name="header">
        <h1 class="font-bold text-xl text-white tracking-tight flex items-center gap-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            โปรไฟล์และประวัติการใช้งาน
        </h1>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ==========================================
                 สรุปข้อมูลผู้ใช้
            ========================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                
                <div class="p-6 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">หนังที่ฉันรีวิว</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $reviews->count() }}</p>
                        <p class="text-xs text-rose-400 mt-1">บทวิจารณ์</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                </div>

                
                <div class="p-6 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">Tier List ของฉัน</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $collections->count() }}</p>
                        <p class="text-xs text-indigo-400 mt-1">กระดานจัดอันดับ</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg>
                    </div>
                </div>

                
                <div class="p-6 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-slate-400 font-medium">หนังที่เสนอเข้าระบบ</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $submittedMovies->count() }}</p>
                        <p class="text-xs text-emerald-400 mt-1">คำขอเสนอหนัง</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                </div>

            </div>


            {{-- ==========================================
                 หนังที่ฉันรีวิว
            ========================================== --}}
            <div class="p-6 md:p-8 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl backdrop-blur-sm">
                <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-400"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    หนังที่ฉันรีวิว ({{ $reviews->count() }})
                </h2>

                @if($reviews->count() > 0)
                    <div class="space-y-3">
                        @foreach($reviews as $review)
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-950/70 border border-slate-800/90 hover:border-slate-700 transition">
                                <div>
                                    <div class="font-bold text-white text-base">
                                        {{ $review->activity->name ?? 'ไม่พบชื่อภาพยนตร์' }}
                                    </div>
                                    <div class="text-xs text-amber-400 mt-1 font-semibold flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        {{ $review->rating }} / 10 ดาว
                                        <span class="text-slate-500 ml-2 font-normal">({{ $review->created_at->diffForHumans() }})</span>
                                    </div>
                                </div>

                                @if($review->activity)
                                    <a href="{{ route('activities.show', $review->activity_id) }}"
                                       class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-indigo-600 text-slate-200 hover:text-white transition">
                                        ดูหนัง →
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm py-4">คุณยังไม่เคยเขียนรีวิวภาพยนตร์</p>
                @endif
            </div>


            {{-- ==========================================
                 Tier List ของฉัน
            ========================================== --}}
            <div class="p-6 md:p-8 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl backdrop-blur-sm">
                <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg>
                    Tier List ของฉัน ({{ $collections->count() }})
                </h2>

                @if($collections->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($collections as $collection)
                            <a href="{{ route('collections.show', $collection->id) }}"
                               class="block p-5 rounded-2xl bg-slate-950/70 border border-slate-800/90 hover:border-indigo-500/50 hover:shadow-lg transition group">
                                <div class="font-bold text-white text-base group-hover:text-indigo-400 transition">
                                    {{ $collection->name }}
                                </div>
                                <div class="text-xs text-slate-400 mt-2 flex items-center justify-between">
                                    <span>อัปเดต: {{ $collection->updated_at->diffForHumans() }}</span>
                                    <span class="text-indigo-400 font-semibold group-hover:translate-x-1 transition">เปิดดู →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm py-4">คุณยังไม่มีกระดานจัดอันดับ Tier List</p>
                @endif
            </div>


            {{-- ==========================================
                 หนังที่ฉันเสนอเข้าระบบ
            ========================================== --}}
            <div class="p-6 md:p-8 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl backdrop-blur-sm">
                <h2 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-400"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    หนังที่ฉันเสนอเข้าระบบ ({{ $submittedMovies->count() }})
                </h2>

                @if($submittedMovies->count() > 0)
                    <div class="space-y-3">
                        @foreach($submittedMovies as $movie)
                            <div class="flex items-center justify-between gap-4 p-4 rounded-2xl bg-slate-950/70 border border-slate-800/90">
                                <div>
                                    <div class="font-bold text-white text-base">
                                        {{ $movie->name }}
                                    </div>
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        ปี {{ $movie->year }} • {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                    </div>
                                </div>

                                
                                @if($movie->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                        อนุมัติแล้ว
                                    </span>
                                @elseif($movie->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        ปฏิเสธแล้ว
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-semibold whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        รอตรวจสอบ
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm py-4">คุณยังไม่มีภาพยนตร์ที่เสนอเข้าระบบ</p>
                @endif
            </div>


            {{-- ==========================================
                 ตั้งค่า Profile บัญชี
            ========================================== --}}
            <div class="p-6 md:p-8 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            
            <div class="p-6 md:p-8 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            
            <div class="p-6 md:p-8 bg-slate-900/60 border border-slate-800/90 rounded-3xl shadow-xl backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
