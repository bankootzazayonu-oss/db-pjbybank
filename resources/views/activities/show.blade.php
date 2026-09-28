<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🎬 {{ $movie->name }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-bold">
                &larr; กลับหน้าแกลลอรี
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- ส่วนแสดงรายละเอียดหนัง -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-8">
                <div class="md:flex">
                    <!-- รูปโปสเตอร์ซ้ายมือ -->
                    <div class="md:shrink-0">
                        @if(!empty($movie->image))
                            <img src="{{ $movie->image }}" alt="{{ $movie->name }}" class="h-96 w-full object-cover md:w-80">
                        @else
                            <div class="h-96 w-full md:w-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">ไม่มีรูปโปสเตอร์</div>
                        @endif
                    </div>
                    
                    <!-- ข้อมูลขวามือ -->
                    <div class="p-8 w-full">
                        <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold mb-1">ปีที่ฉาย: {{ $movie->year ?? 'ไม่ระบุ' }}</div>
                        <h1 class="block mt-1 text-3xl leading-tight font-bold text-black dark:text-white mb-4">
                            {{ $movie->name }}
                        </h1>
                        <p class="mt-2 text-gray-500 dark:text-gray-300 leading-relaxed bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border dark:border-gray-700">
                            {{ $movie->review ?? 'ยังไม่มีเรื่องย่อสำหรับภาพยนตร์เรื่องนี้' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- แจ้งเตือนเมื่อรีวิวสำเร็จ -->
            @if(session('success'))
                <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ส่วนเขียนรีวิว -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 p-8 mb-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">✍️ เขียนรีวิวของคุณ</h3>
                
                <form action="{{ route('reviews.store', $movie->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">ให้คะแนน (1-10 ดาว) ⭐</label>
                        <select name="rating" required class="w-full md:w-1/3 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                            <option value="">-- เลือกคะแนน --</option>
                            @for($i = 10; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} ดาว</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">ความรู้สึกหลังดูจบ</label>
                        <textarea name="comment" rows="4" required placeholder="เขียนความรู้สึกของคุณที่นี่..." 
                                  class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm p-3"></textarea>
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" name="is_spoiler" id="is_spoiler" value="1" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer">
                        <label for="is_spoiler" class="ml-2 text-red-600 font-bold cursor-pointer">
                            ⚠️ เนื้อหานี้มีการสปอยล์ (เปิดเผยเนื้อหาสำคัญ)
                        </label>
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-md shadow-md transition">
                        ส่งรีวิว
                    </button>
                </form>
            </div>

            <!-- ส่วนแสดงคอมเมนต์ทั้งหมด -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">💬 รีวิวจากผู้ชม ({{ $movie->reviews->count() }})</h3>
                
                @forelse($movie->reviews as $review)
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700 last:border-0 last:mb-0 last:pb-0">
                        <div class="flex justify-between items-center mb-2">
                            <div class="font-bold text-indigo-600 dark:text-indigo-400">
                                👤 {{ $review->user->name ?? 'ผู้ใช้งาน' }}
                            </div>
                            <div class="text-yellow-500 font-bold bg-yellow-100 dark:bg-yellow-900/30 px-3 py-1 rounded-full text-sm">
                                ⭐ {{ $review->rating }} / 10
                            </div>
                        </div>

                        <!-- เช็กว่าสปอยล์ไหม -->
                        @if($review->is_spoiler)
                            <details class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 rounded-lg cursor-pointer">
                                <summary class="text-red-600 font-bold text-sm outline-none">⚠️ รีวิวนี้มีสปอยล์ (คลิกเพื่ออ่าน)</summary>
                                <p class="mt-3 text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $review->comment }}</p>
                            </details>
                        @else
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $review->comment }}</p>
                        @endif
                        
                        <div class="text-xs text-gray-400 mt-2">
                            โพสต์เมื่อ: {{ $review->created_at->diffForHumans() }}
                        </div>
                        <!-- ปุ่มและการตอบกลับ -->
<div class="mt-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
    <!-- แสดงคอมเมนต์ย่อย -->
    @foreach($review->replies as $reply)
        <div class="mb-3 bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg">
            <div class="flex items-center justify-between mb-1">
                <span class="font-bold text-sm text-indigo-500">↳ {{ $reply->user->name }}</span>
                <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reply->message }}</p>
        </div>
    @endforeach

    <!-- ฟอร์มพิมพ์ตอบกลับ -->
    @auth
        <form action="{{ route('replies.store', $review->id) }}" method="POST" class="mt-3 flex gap-2">
            @csrf
            <input type="text" name="message" required placeholder="ตอบกลับความเห็นนี้..." 
                   class="flex-1 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md p-2">
            <button type="submit" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-md text-sm font-bold transition">
                ตอบ
            </button>
        </form>
    @endauth
</div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 py-6">
                        ยังไม่มีรีวิวสำหรับภาพยนตร์เรื่องนี้ เป็นคนแรกที่รีวิวสิ!
                    </div>
                @endforelse
            </div>