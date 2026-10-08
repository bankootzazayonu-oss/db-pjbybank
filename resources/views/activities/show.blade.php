<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <h1 class="font-black text-xl text-white tracking-tight flex items-center gap-2">
                <span>🎬</span> {{ $movie->name }} <span class="text-slate-400 font-normal">({{ $movie->year }})</span>
            </h1>
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-xl transition">
                ← กลับหน้าคลังหนัง
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- แจ้งเตือนสถานะ -->
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

        <!-- ================= ส่วนแสดงรายละเอียดหนัง ================= -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            
            <!-- ฝั่งซ้าย: โปสเตอร์ -->
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
                            <span class="text-6xl mb-3">🎬</span>
                            <span class="text-sm">ไม่มีรูปภาพ</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- ฝั่งขวา: ข้อมูลหนัง -->
            <div class="md:col-span-8 lg:col-span-8 flex flex-col">
                <div class="bg-slate-900/60 p-6 md:p-8 rounded-3xl shadow-xl border border-slate-800/90 h-full flex flex-col justify-between backdrop-blur-sm">
                    
                    <div>
                        <!-- ป้าย Tag ข้อมูลพื้นฐาน -->
                        <div class="flex flex-wrap items-center gap-2.5 mb-5">
                            <span class="bg-rose-500/15 border border-rose-500/30 text-rose-400 text-xs font-semibold px-3 py-1 rounded-full">
                                📁 {{ $movie->type ? $movie->type->name : 'ไม่ระบุหมวดหมู่' }}
                            </span>
                            <span class="bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold px-3 py-1 rounded-full">
                                📅 ปี {{ $movie->year }}
                            </span>
                            @if($movie->hours > 0)
                                <span class="bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold px-3 py-1 rounded-full">
                                    ⏱️ {{ $movie->hours }} ชั่วโมง
                                </span>
                            @endif
                        </div>
                        
                        <h2 class="text-3xl md:text-5xl font-black text-white mb-6 leading-tight tracking-tight">
                            {{ $movie->name }}
                        </h2>
                        
                        <div class="mb-8">
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">เรื่องย่อ / Synopsis</h3>
                            <p class="text-slate-300 leading-relaxed whitespace-pre-line text-base sm:text-lg font-light">
                                {{ $movie->review }}
                            </p>
                        </div>
                    </div>

                    <!-- คะแนนเฉลี่ย -->
                    <div class="mt-6 pt-6 border-t border-slate-800/80 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4 bg-slate-950/80 px-5 py-3.5 rounded-2xl border border-slate-800">
                            <span class="text-4xl text-amber-400">⭐</span>
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

                        <!-- ผู้กำกับถ้ามี -->
                        @if($movie->director)
                            <div class="text-right">
                                <p class="text-xs text-slate-400 uppercase tracking-wider">ผู้กำกับ</p>
                                <p class="text-base font-bold text-slate-200 mt-0.5">{{ $movie->director->name }}</p>
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- ================= ส่วนเขียนรีวิว ================= -->
        <div class="bg-slate-900/60 p-6 md:p-8 rounded-3xl shadow-xl border border-slate-800/90 backdrop-blur-sm">
            <h3 class="text-xl font-bold text-white mb-5 flex items-center gap-2">
                <span>✍️</span> เขียนรีวิวของคุณ
            </h3>
            
            @if($userReview)
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-2xl flex items-center justify-between gap-4">
                    <div>
                        <p class="font-bold">✅ คุณได้รีวิวและให้คะแนนภาพยนตร์เรื่องนี้แล้ว</p>
                        <p class="text-sm text-emerald-300/80 mt-0.5">ให้คะแนนไว้: ⭐ {{ $userReview->rating }} / 10 ดาว</p>
                    </div>
                    <span class="text-xs bg-emerald-500/20 px-3 py-1 rounded-full font-semibold">บันทึกแล้ว</span>
                </div>
            @else
                <form action="{{ route('reviews.store', $movie->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">ให้คะแนน (1-10 ดาว) ⭐</label>
                            <select name="rating" required class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 text-white rounded-xl px-4 py-2.5 text-sm">
                                <option value="">-- เลือกคะแนน --</option>
                                @for($i = 10; $i >= 1; $i--)
                                    <option value="{{ $i }}">⭐ {{ $i }} / 10</option>
                                @endfor
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">ความรู้สึกหลังดูจบ</label>
                            <textarea name="comment" rows="3" required placeholder="พิมพ์ความรู้สึก จุดเด่น หรือข้อคิดเห็นของคุณ..." class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 text-white rounded-xl px-4 py-2.5 text-sm placeholder:text-slate-600"></textarea>
                        </div>
                    </div>

                    <div class="mb-5 flex items-center">
                        <input type="checkbox" id="is_spoiler" name="is_spoiler" value="1" class="w-4 h-4 rounded text-rose-600 bg-slate-950 border-slate-800 focus:ring-rose-500 focus:ring-offset-slate-900">
                        <label for="is_spoiler" class="ml-2 text-xs font-semibold text-rose-400">
                            ⚠️ เนื้อหารีวิวนี้มีการสปอยล์ (เปิดเผยเนื้อหาสำคัญ)
                        </label>
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-semibold text-sm py-2.5 px-6 rounded-xl shadow-lg shadow-rose-950/40 transition hover:scale-[1.02]">
                        ส่งบทวิจารณ์
                    </button>
                </form>
            @endif
        </div>

        <!-- ================= ส่วนแสดงคอมเมนต์ทั้งหมด ================= -->
        <div class="bg-slate-900/60 p-6 md:p-8 rounded-3xl shadow-xl border border-slate-800/90 backdrop-blur-sm">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <span>💬</span> รีวิวจากผู้ชม ({{ $movie->reviews->count() }})
            </h3>
            
            <div class="space-y-6">
                @forelse($movie->reviews as $review)
                    <div class="bg-slate-950/70 p-5 sm:p-6 rounded-2xl border border-slate-800/90 relative group">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 to-indigo-600 flex items-center justify-center text-white font-bold shadow">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-100 text-sm">{{ $review->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <!-- คะแนน & ปุ่มรายงาน -->
                            <div class="flex flex-col items-end gap-2">
                                <div class="bg-amber-500/10 border border-amber-500/20 text-amber-400 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                    ⭐ {{ $review->rating }} / 10
                                </div>
                                
                                @if(Auth::check() && Auth::id() !== $review->user_id)
                                    <form action="{{ route('reviews.report', $review->id) ?? '#' }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('ยืนยันการรายงานคอมเมนต์นี้ว่าไม่เหมาะสม?')" class="text-xs text-slate-500 hover:text-rose-400 transition opacity-0 group-hover:opacity-100 flex items-center gap-1">
                                            🚩 <span>รายงาน</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- ส่วนเนื้อหาคอมเมนต์ & ระบบแก้ไข -->
                        <div x-data="{ editMode: false }">

                            <!-- โหมดปกติ -->
                            <div x-show="!editMode">
                                @if($review->is_spoiler)
                                    <details class="group bg-rose-950/20 border border-rose-900/40 rounded-xl p-3 my-2">
                                        <summary class="cursor-pointer text-xs font-bold text-rose-400 list-none flex items-center gap-2">
                                            <span>▶</span> ⚠️ รีวิวนี้มีสปอยล์ (คลิกเพื่อเปิดอ่าน)
                                        </summary>
                                        <p class="mt-3 text-slate-300 text-sm leading-relaxed border-t border-rose-900/40 pt-3 whitespace-pre-line font-light">
                                            {{ $review->comment }}
                                        </p>
                                    </details>
                                @else
                                    <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line my-2 font-light">
                                        {{ $review->comment }}
                                    </p>
                                @endif

                                <!-- ปุ่มแก้ไข / ลบ -->
                                @if(Auth::check() && Auth::id() === $review->user_id)
                                    <div class="flex items-center gap-3 mt-3 pt-2.5 border-t border-slate-800/80">
                                        <button type="button" @click="editMode = true" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center gap-1 transition">
                                            ✏️ แก้ไขรีวิว
                                        </button>

                                        <form action="{{ route('reviews.user_destroy', $review->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคอมเมนต์นี้ของคุณทิ้ง?')" class="text-xs text-rose-400 hover:text-rose-300 font-semibold flex items-center gap-1 transition">
                                                🗑️ ลบรีวิว
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>

                            <!-- โหมดแก้ไขข้อความ -->
                            <div x-show="editMode" style="display: none;" class="mt-3 bg-slate-900 p-4 rounded-xl border border-indigo-500/40">
                                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="block text-xs font-bold text-slate-400 mb-1">แก้ไขข้อความรีวิวของคุณ:</label>
                                    <textarea name="comment" rows="3" required class="w-full bg-slate-950 text-white border-slate-800 rounded-xl focus:border-rose-500 text-sm mb-3">{{ $review->comment }}</textarea>
                                    
                                    <div class="flex items-center gap-2">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition">
                                            บันทึก
                                        </button>
                                        <button type="button" @click="editMode = false" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold py-1.5 px-3 rounded-lg transition">
                                            ยกเลิก
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        
                        <!-- ================= โซนตอบกลับ (Reply on Comment) ================= -->
                        <div class="ml-2 sm:ml-8 mt-5 pl-4 border-l-2 border-slate-800 space-y-3">
                            
                            <!-- ลูปแสดงคอมเมนต์ย่อย -->
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
                                        
                                        @if(Auth::check() && Auth::id() === $reply->user_id)
                                            <div class="flex items-center gap-3 mt-2 pt-1.5 border-t border-slate-800">
                                                <button type="button" @click="editReplyMode = true" class="text-[11px] text-indigo-400 hover:text-indigo-300 font-semibold transition">
                                                    แก้ไข
                                                </button>
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
                                            <input type="text" name="message" value="{{ $reply->message }}" required class="flex-1 text-xs bg-slate-950 border-slate-800 focus:border-rose-500 rounded-lg px-3 py-1.5 text-white transition">
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold py-1 px-3 rounded-lg transition">
                                                บันทึก
                                            </button>
                                            <button type="button" @click="editReplyMode = false" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold py-1 px-3 rounded-lg transition">
                                                ยกเลิก
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endforeach

                            <!-- ฟอร์มพิมพ์ตอบกลับ -->
                            @auth
                                <form action="{{ route('replies.store', $review->id) }}" method="POST" class="mt-3 flex gap-2">
                                    @csrf
                                    <input type="text" name="message" required placeholder="ตอบกลับความคิดเห็นนี้..." class="flex-1 text-xs bg-slate-900 border border-slate-800 focus:border-rose-500 rounded-xl px-4 py-2 text-white placeholder:text-slate-600 transition">
                                    <button type="submit" class="bg-slate-800 hover:bg-rose-600 text-slate-200 hover:text-white text-xs font-semibold py-2 px-4 rounded-xl transition">
                                        ตอบกลับ
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-slate-500 mt-2">
                                    <a href="{{ route('login') }}" class="text-rose-400 font-semibold hover:underline">เข้าสู่ระบบ</a> เพื่อร่วมพูดคุย
                                </p>
                            @endauth
                        </div>

                    </div>
                @empty
                    <div class="text-center py-12 bg-slate-950/40 rounded-2xl border border-dashed border-slate-800">
                        <span class="text-4xl block mb-2">👻</span>
                        <p class="text-slate-300 font-semibold text-sm">ยังไม่มีรีวิวสำหรับภาพยนตร์เรื่องนี้</p>
                        <p class="text-xs text-slate-500 mt-1">เป็นคนแรกที่แบ่งปันความรู้สึกของคุณสิ!</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>