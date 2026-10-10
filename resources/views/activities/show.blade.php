<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h1 class="font-bold text-xl text-white tracking-tight flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>
                {{ $movie->name }} <span class="text-slate-400 font-normal">({{ $movie->year }})</span>
            </h1>
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-xl transition">
                ← กลับหน้าคลังหนัง
            </a>
        </div>
    </x-slot>

        <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        
        @if(Auth::check() && Auth::user()->role === 'admin')
        <div class="bg-indigo-900/20 border border-indigo-500/30 rounded-2xl p-4 flex flex-wrap items-center justify-between gap-4 backdrop-blur-sm">
            <div class="flex items-center gap-2 text-indigo-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span class="text-sm font-bold uppercase tracking-wider">Admin Controls</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('activities.edit', $movie->id) }}" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    แก้ไขภาพยนตร์
                </a>
                <form action="{{ route('activities.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบภาพยนตร์เรื่องนี้เข้าถังขยะส่วนกลาง?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 bg-rose-600/20 text-rose-400 hover:bg-rose-500 hover:text-white border border-rose-500/30 px-4 py-2 rounded-xl text-sm font-semibold transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        ลบภาพยนตร์
                    </button>
                </form>
            </div>
        </div>
        @endif
        
        
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

        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            
            
            <div class="md:col-span-4 lg:col-span-4">
                <div class="rounded-3xl shadow-2xl border border-slate-800 overflow-hidden aspect-[2/3] bg-slate-950 sticky top-24">
                    @if($movie->image)
                        @if(\Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']))
                            <img src="{{ $movie->image }}" alt="{{ $movie->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('storage/' . $movie->image) }}" alt="{{ $movie->name }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-4 text-slate-500"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 3v18"/><path d="M3 7.5h4"/><path d="M3 12h18"/><path d="M3 16.5h4"/><path d="M17 3v18"/><path d="M17 7.5h4"/><path d="M17 16.5h4"/></svg>
                            <span class="text-sm font-medium text-slate-400">ไม่มีรูปภาพ</span>
                        </div>
                    @endif
                </div>
            </div>
            
            
            <div class="md:col-span-8 lg:col-span-8 flex flex-col">
                <div class="bg-slate-900/60 p-6 md:p-8 rounded-3xl shadow-xl border border-slate-800/90 h-full flex flex-col justify-between backdrop-blur-sm">
                    
                    <div>
                        
                        <div class="flex flex-wrap items-center gap-2.5 mb-5">
                            <span class="bg-indigo-500/15 border border-indigo-500/30 text-indigo-400 text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                                {{ $movie->type ? $movie->type->name : 'ไม่ระบุหมวดหมู่' }}
                            </span>
                            <span class="bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                ปี {{ $movie->year }}
                            </span>
                            @if($movie->hours > 0)
                                <span class="bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $movie->hours }} ชั่วโมง
                                </span>
                            @endif
                        </div>
                        
                        <h2 class="text-3xl md:text-5xl font-black text-white mb-2 leading-tight tracking-tight">
                            {{ $movie->name }}
                        </h2>
                        @if(!empty($movie->original_title) && $movie->original_title !== $movie->name)
                            <p class="text-base sm:text-lg text-slate-400 font-medium italic mb-6">
                                {{ $movie->original_title }}
                            </p>
                        @else
                            <div class="mb-6"></div>
                        @endif
                        
                        <div class="mb-6">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">เรื่องย่อ / Synopsis</h3>
                            <p class="text-slate-300 leading-relaxed whitespace-pre-line text-base sm:text-lg font-light">
                                {{ $movie->review }}
                            </p>
                        </div>

                        
                        @if($movie->platforms->isNotEmpty())
                            <div class="mb-6 pt-4 border-t border-slate-800/60">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="15" x="2" y="7" rx="2" ry="2"/><polyline points="17 2 12 7 7 2"/></svg>
                                    รับชมได้ที่ / Available On
                                </h3>
                                <div class="flex flex-wrap items-center gap-2">
                                    @foreach($movie->platforms as $platform)
                                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-slate-950 border border-slate-700/80 hover:border-indigo-500/50 text-slate-200 text-xs font-semibold shadow-sm transition">
                                            <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-sm shadow-indigo-500/50"></span>
                                            <span>{{ $platform->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    
                    <div class="mt-6 pt-6 border-t border-slate-800/80 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4 bg-slate-950/80 px-5 py-3.5 rounded-2xl border border-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="text-amber-400"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <div>
                                <div class="text-[11px] text-amber-400 font-bold uppercase tracking-wider">คะแนนรีวิวเฉลี่ย</div>
                                <div class="flex items-baseline gap-1.5 mt-0.5">
                                    <span class="text-3xl font-black text-white leading-none">
                                        {{ $movie->reviews->count() > 0 ? number_format($movie->reviews->avg('rating'), 1) : 'N/A' }} 
                                    </span>
                                    <span class="text-sm font-medium text-slate-500">/ 10</span>
                                    <span class="text-xs text-slate-400 ml-2">({{ $movie->reviews->count() }} รีวิว)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        
        <div class="bg-slate-900/60 p-6 md:p-8 rounded-3xl shadow-xl border border-slate-800/90 backdrop-blur-sm">
            <h3 class="text-xl font-bold text-white mb-5 flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                เขียนรีวิวของคุณ
            </h3>
            
            @if($userReview)
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl flex items-center justify-between gap-4">
                    <div>
                        <p class="font-bold flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            คุณได้รีวิวและให้คะแนนภาพยนตร์เรื่องนี้แล้ว
                        </p>
                        <p class="text-sm text-emerald-300/80 mt-1 flex items-center gap-1">ให้คะแนนไว้: 
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            {{ $userReview->rating }} / 10 ดาว
                        </p>
                    </div>
                    <span class="text-xs bg-emerald-500/20 px-3 py-1 rounded-full font-semibold">บันทึกแล้ว</span>
                </div>
            @else
                <form action="{{ route('reviews.store', $movie->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                ให้คะแนน (1-10 ดาว)
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" stroke="none" class="text-amber-400"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </label>
                            <select name="rating" required class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 text-white rounded-xl px-4 py-2.5 text-sm">
                                <option value="">-- เลือกคะแนน --</option>
                                @for($i = 10; $i >= 1; $i--)
                                    <option value="{{ $i }}">{{ $i }} / 10</option>
                                @endfor
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">ความรู้สึกหลังดูจบ</label>
                            <textarea name="comment" rows="3" required placeholder="พิมพ์ความรู้สึก จุดเด่น หรือข้อคิดเห็นของคุณ..." class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 text-white rounded-xl px-4 py-2.5 text-sm placeholder:text-slate-600"></textarea>
                        </div>
                    </div>

                    <div class="mb-5 flex items-center">
                        <input type="checkbox" id="is_spoiler" name="is_spoiler" value="1" class="w-4 h-4 rounded text-indigo-600 bg-slate-950 border-slate-800 focus:ring-indigo-500 focus:ring-offset-slate-900">
                        <label for="is_spoiler" class="ml-2 text-xs font-semibold text-amber-500 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                            เนื้อหารีวิวนี้มีการสปอยล์ (เปิดเผยเนื้อหาสำคัญ)
                        </label>
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm py-2.5 px-6 rounded-xl shadow-lg shadow-indigo-950/40 transition hover:scale-[1.02]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        ส่งบทวิจารณ์
                    </button>
                </form>
            @endif
        </div>

        
        <div class="bg-slate-900/60 p-6 md:p-8 rounded-3xl shadow-xl border border-slate-800/90 backdrop-blur-sm">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                รีวิวจากผู้ชม ({{ $movie->reviews->count() }})
            </h3>
            
            <div class="space-y-6">
                @forelse($movie->reviews as $review)
                    <div class="bg-slate-950/70 p-5 sm:p-6 rounded-2xl border border-slate-800/90 relative group">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-300 font-bold shadow-sm">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-100 text-sm">{{ $review->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            
                            <div class="flex flex-col items-end gap-2">
                                <div class="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold px-3 py-1 rounded-lg shadow-sm flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    {{ $review->rating }} / 10
                                </div>
                                
                                @if(Auth::check() && Auth::id() !== $review->user_id)
                                    <form action="{{ route('reviews.report', $review->id) ?? '#' }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('ยืนยันการรายงานคอมเมนต์นี้ว่าไม่เหมาะสม?')" class="text-xs text-slate-500 hover:text-rose-400 transition opacity-0 group-hover:opacity-100 flex items-center gap-1 font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                                            รายงาน
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        
                        <div x-data="{ editMode: false }">

                            
                            <div x-show="!editMode">
                                @if($review->is_spoiler)
                                    <details class="group bg-amber-500/5 border border-amber-500/20 rounded-xl p-3 my-2">
                                        <summary class="cursor-pointer text-xs font-bold text-amber-500 list-none flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-open:rotate-90 transition-transform"><path d="m9 18 6-6-6-6"/></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" x2="12" y1="9" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>
                                            รีวิวนี้มีสปอยล์ (คลิกเพื่อเปิดอ่าน)
                                        </summary>
                                        <p class="mt-3 text-slate-300 text-sm leading-relaxed border-t border-amber-500/20 pt-3 whitespace-pre-line font-light">
                                            {{ $review->comment }}
                                        </p>
                                    </details>
                                @else
                                    <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line my-2 font-light">
                                        {{ $review->comment }}
                                    </p>
                                @endif

                                
                                @if(Auth::check() && (Auth::id() === $review->user_id || Auth::user()->role === 'admin'))
                                    <div class="flex items-center gap-4 mt-4 pt-3 border-t border-slate-800/80">
                                        @if(Auth::id() === $review->user_id)
                                        <button type="button" @click="editMode = true" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1.5 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            แก้ไขรีวิว
                                        </button>
                                        @endif

                                        <form action="{{ route('reviews.user_destroy', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคอมเมนต์นี้ทิ้ง?')" class="text-xs text-rose-400 hover:text-rose-300 font-semibold flex items-center gap-1.5 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                ลบรีวิว
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            
                            <div x-show="editMode" style="display: none;" class="mt-3 bg-slate-900 p-4 rounded-xl border border-indigo-500/40">
                                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="block text-xs font-bold text-slate-400 mb-2">แก้ไขข้อความรีวิวของคุณ:</label>
                                    <textarea name="comment" rows="3" required class="w-full bg-slate-950 text-white border-slate-800 rounded-xl focus:border-indigo-500 text-sm mb-3">{{ $review->comment }}</textarea>
                                    
                                    <div class="flex items-center gap-2">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold py-1.5 px-4 rounded-lg transition shadow">
                                            บันทึก
                                        </button>
                                        <button type="button" @click="editMode = false" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-medium py-1.5 px-4 rounded-lg transition">
                                            ยกเลิก
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        
                        
                        <div class="ml-2 sm:ml-8 mt-5 pl-4 border-l-2 border-slate-800 space-y-3">
                            
                            
                            @foreach($review->replies as $reply)
                                <div x-data="{ editReplyMode: false }" class="bg-slate-900/80 rounded-xl p-3 border border-slate-800/80">
                                    
                                    <div x-show="!editReplyMode">
                                        <div class="flex justify-between items-start mb-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-xs text-slate-200">{{ $reply->user->name }}</span>
                                                <span class="text-[10px] text-slate-500">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-300 whitespace-pre-line font-light">{{ $reply->message }}</p>
                                        
                                        @if(Auth::check() && (Auth::id() === $reply->user_id || Auth::user()->role === 'admin'))
                                            <div class="flex items-center gap-3 mt-2 pt-1.5 border-t border-slate-800">
                                                @if(Auth::id() === $reply->user_id)
                                                <button type="button" @click="editReplyMode = true" class="text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold transition">
                                                    แก้ไข
                                                </button>
                                                @endif
                                                <form action="{{ route('replies.destroy', $reply->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('ลบการตอบกลับนี้ใช่หรือไม่?')" class="text-[11px] text-rose-400 hover:text-rose-300 font-semibold transition">
                                                        ลบ
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    <div x-show="editReplyMode" style="display: none;" class="mt-2">
                                        <form action="{{ route('replies.update', $reply->id) }}" method="POST" class="flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="message" value="{{ $reply->message }}" required class="flex-1 text-xs bg-slate-950 border-slate-800 focus:border-indigo-500 rounded-lg px-3 py-1.5 text-white transition">
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-semibold py-1 px-3 rounded-lg transition shadow">
                                                บันทึก
                                            </button>
                                            <button type="button" @click="editReplyMode = false" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-medium py-1 px-3 rounded-lg transition">
                                                ยกเลิก
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach

                            
                            @auth
                                <form action="{{ route('replies.store', $review->id) }}" method="POST" class="mt-3 flex gap-2">
                                    @csrf
                                    <input type="text" name="message" required placeholder="ตอบกลับความคิดเห็นนี้..." class="flex-1 text-xs bg-slate-900 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2 text-white placeholder:text-slate-500 transition focus:outline-none">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold py-2 px-4 rounded-xl transition shadow-sm">
                                        ตอบกลับ
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-slate-500 mt-2">
                                    <a href="{{ route('login') }}" class="text-indigo-400 font-semibold hover:underline">เข้าสู่ระบบ</a> เพื่อร่วมพูดคุย
                                </p>
                            @endauth
                        </div>

                    </div>
                @empty
                    <div class="text-center py-12 bg-slate-950/40 rounded-2xl border border-dashed border-slate-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-slate-600"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                        <p class="text-slate-300 font-semibold text-sm">ยังไม่มีรีวิวสำหรับภาพยนตร์เรื่องนี้</p>
                        <p class="text-xs text-slate-500 mt-1">เป็นคนแรกที่แบ่งปันความรู้สึกของคุณสิ!</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>