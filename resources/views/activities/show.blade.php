<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🎬 {{ $movie->name }} ({{ $movie->year }})
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-1 px-3 rounded transition">
                ← กลับหน้าแรก
            </a>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- แจ้งเตือนสถานะ -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 font-bold shadow-md">{{ session('success') }}</div>
        @endif

        <!-- ส่วนแสดงรายละเอียดหนัง (แบบ Grid 2 คอลัมน์) -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-8">
            
            <!-- ฝั่งซ้าย: โปสเตอร์ -->
            <div class="md:col-span-4 lg:col-span-3">
                <div class="rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden aspect-[2/3] bg-gray-900 sticky top-6">
                    @if($movie->image)
                        @if(\Illuminate\Support\Str::startsWith($movie->image, ['http://', 'https://']))
                            <img src="{{ $movie->image }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('storage/' . $movie->image) }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-500">
                            <span class="text-4xl mb-2">🎬</span>
                            <span>ไม่มีรูปภาพ</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- ฝั่งขวา: ข้อมูลหนัง -->
            <div class="md:col-span-8 lg:col-span-9 flex flex-col">
                <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 h-full flex flex-col">
                    
                    <!-- ป้าย Tag ข้อมูลพื้นฐาน -->
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="bg-indigo-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">📁 {{ $movie->type ? $movie->type->name : 'ไม่ระบุหมวดหมู่' }}</span>
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">⏱️ {{ $movie->hours }} ชั่วโมง</span>
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">📅 ปี {{ $movie->year }}</span>
                    </div>
                    
                    <h3 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-6 leading-tight">{{ $movie->name }}</h3>
                    
                    <div class="mb-8 flex-1">
                        <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2">เรื่องย่อ / Synopsis</h4>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line text-lg">{{ $movie->review }}</p>
                    </div>

                    <!-- คะแนนเฉลี่ย -->
                    <div class="mt-auto inline-flex items-center gap-4 bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl border border-yellow-200 dark:border-yellow-700/30 w-fit">
                        <div class="text-4xl drop-shadow-sm">⭐</div>
                        <div>
                            <div class="text-xs text-yellow-600 dark:text-yellow-500 font-bold uppercase tracking-wider mb-1">คะแนนรีวิวเฉลี่ย</div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-3xl font-black text-gray-900 dark:text-white leading-none">
                                    {{ $movie->reviews->count() > 0 ? number_format($movie->reviews->avg('rating'), 1) : 'N/A' }} 
                                </span>
                                <span class="text-sm font-normal text-gray-500">/ 10</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <!-- ส่วนเขียนรีวิว -->
        <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mb-8">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">✍️ เขียนรีวิวของคุณ</h3>
            
            @if($userReview)
                <div class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 p-4 rounded-lg border border-green-200 dark:border-green-800/50">
                    ✅ คุณได้รีวิวและให้คะแนนภาพยนตร์เรื่องนี้ไปแล้ว (ให้ไว้ {{ $userReview->rating }} ดาว)
                </div>
            @else
                <form action="{{ route('reviews.store', $movie->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ให้คะแนน (1-10 ดาว) ⭐</label>
                        <select name="rating" required class="w-full md:w-1/3 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                            <option value="">-- เลือกคะแนน --</option>
                            @for($i = 10; $i >= 1; $i--)
                                <option value="{{ $i }}">⭐ {{ $i }} / 10</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ความรู้สึกหลังดูจบ</label>
                        <textarea name="comment" rows="4" required placeholder="เขียนความรู้สึกของคุณที่นี่..." class="w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md"></textarea>
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" id="is_spoiler" name="is_spoiler" value="1" class="w-5 h-5 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_spoiler" class="ml-2 text-sm font-bold text-red-600 dark:text-red-400">⚠️ เนื้อหานี้มีการสปอยล์ (เปิดเผยเนื้อหาสำคัญ)</label>
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md transition">
                        ส่งรีวิว
                    </button>
                </form>
            @endif
        </div>

        <!-- ================= ส่วนแสดงคอมเมนต์ทั้งหมด (เพิ่ม Reply & Report) ================= -->
        <div class="bg-white dark:bg-gray-800 p-6 md:p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">💬 รีวิวจากผู้ชม ({{ $movie->reviews->count() }})</h3>
            
            <div class="space-y-6">
                @forelse($movie->reviews as $review)
                    <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-lg border border-gray-100 dark:border-gray-700 relative group">
                        
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold shadow-inner">
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-indigo-600 dark:text-indigo-400">{{ $review->user->name }}</p>
                                    <p class="text-xs text-gray-500">โพสต์เมื่อ: {{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            
                            <!-- คะแนน และ ปุ่มรายงาน (Report) -->
                            <div class="flex flex-col items-end gap-2">
                                <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                    ⭐ {{ $review->rating }} / 10
                                </div>
                                
                                <!-- ปุ่มรายงาน (โผล่ตอน Hover) -->
                                @if(Auth::check() && Auth::id() !== $review->user_id)
                                    <form action="{{ route('reviews.report', $review->id) ?? '#' }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('ยืนยันการรายงานคอมเมนต์นี้ว่าไม่เหมาะสม?')" class="text-xs text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100 flex items-center gap-1">
                                            🚩 <span>รายงาน</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- เช็กสปอยล์ (ใช้โค้ดเดิมของคุณที่ออกแบบมาดีแล้ว) -->
                        @if($review->is_spoiler)
                            <details class="group bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800/50 rounded-md p-3">
                                <summary class="cursor-pointer text-sm font-bold text-red-600 dark:text-red-400 list-none flex items-center gap-2">
                                    <span>▶</span> ⚠️ รีวิวนี้มีสปอยล์ (คลิกเพื่ออ่าน)
                                </summary>
                                <p class="mt-3 text-gray-700 dark:text-gray-300 text-sm leading-relaxed border-t border-red-200 dark:border-red-800/50 pt-3 whitespace-pre-line">
                                    {{ $review->comment }}
                                </p>
                            </details>
                        @else
                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line mt-2">
                                {{ $review->comment }}
                            </p>
                        @endif
                        
                        <!-- ================= โซนตอบกลับ (Reply on Comment) ================= -->
                        <div class="ml-4 md:ml-10 mt-5 pl-4 border-l-2 border-indigo-200 dark:border-indigo-900/50 space-y-3">
                            
                            <!-- ลูปแสดงคอมเมนต์ย่อย -->
                            @foreach($review->replies as $reply)
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-100 dark:border-gray-700 shadow-sm">
                                    <div class="flex justify-between items-start mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-gray-900 dark:text-gray-200">{{ $reply->user->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $reply->message }}</p>
                                </div>
                            @endforeach

                            <!-- ฟอร์มพิมพ์ตอบกลับ -->
                            @auth
                                <form action="{{ route('replies.store', $review->id) }}" method="POST" class="mt-3 flex gap-2">
                                    @csrf
                                    <input type="text" name="message" required placeholder="ตอบกลับความคิดเห็นนี้..." class="flex-1 text-sm bg-gray-100 dark:bg-gray-900 border-gray-200 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg px-4 py-2 text-gray-900 dark:text-white transition">
                                    <button type="submit" class="bg-gray-200 hover:bg-indigo-600 text-gray-700 hover:text-white text-sm font-bold py-2 px-4 rounded-lg transition">
                                        ส่ง
                                    </button>
                                </form>
                            @else
                                <p class="text-xs text-gray-500 mt-2"><a href="{{ route('login') }}" class="text-indigo-500 font-bold hover:underline">เข้าสู่ระบบ</a> เพื่อร่วมพูดคุย</p>
                            @endauth
                        </div>
                        <!-- ================= จบโซนตอบกลับ ================= -->

                    </div>
                @empty
                    <div class="text-center text-gray-500 py-12 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
                        <span class="text-5xl block mb-3">👻</span>
                        <p class="text-gray-500 font-bold">ยังไม่มีรีวิวสำหรับภาพยนตร์เรื่องนี้</p>
                        <p class="text-sm text-gray-400 mt-1">เป็นคนแรกที่แบ่งปันความรู้สึกของคุณสิ!</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>