<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ➕ เสนอภาพยนตร์ใหม่เข้าระบบ
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            
            <div class="bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4 mb-6 flex items-start gap-3">
                <span class="text-2xl">💡</span>
                <div>
                    <h4 class="font-bold text-indigo-800 dark:text-indigo-300">ฟีเจอร์ผู้ช่วยอัจฉริยะ (Auto-Fill)</h4>
                    <p class="text-indigo-600 dark:text-indigo-400 text-sm mt-1">พิมพ์ชื่อภาพยนตร์ภาษาอังกฤษ แล้วกดปุ่ม "ดึงข้อมูลจาก TMDB" ระบบจะค้นหาและเติมข้อมูลให้คุณอัตโนมัติ!</p>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6 text-sm shadow-md">
                    <ul class="list-disc pl-5 font-bold">
                        @foreach($errors->all() as$error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ชื่อภาพยนตร์/ซีรีส์ <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" id="movie_name" name="name" value="{{ old('name') }}" required 
                               placeholder="เช่น Inception, Avatar..."
                               class="flex-1 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        
                        <button type="button" onclick="fetchTMDB()" class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold px-4 py-2 rounded-md shadow transition flex items-center gap-2">
                            <span>🔍 ดึงข้อมูล</span>
                        </button>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">หมวดหมู่ <span class="text-red-500">*</span></label>
                    <select name="type_id" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- เลือกหมวดหมู่ --</option>
                        @foreach($types as$type)
                            <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ปีที่เข้าฉาย (ค.ศ.) <span class="text-red-500">*</span></label>
                    <input type="number" id="movie_year" name="year" value="{{ old('year', date('Y')) }}" required 
                           class="w-full md:w-1/2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">เรื่องย่อ / คำอธิบาย <span class="text-red-500">*</span></label>
                    <textarea id="movie_review" name="review" rows="5" required 
                              placeholder="พิมพ์เรื่องย่อ หรือกดดึงข้อมูลจาก TMDB..."
                              class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('review') }}</textarea>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">โปสเตอร์ภาพยนตร์ <span class="text-gray-400 font-normal">(อัปโหลดเอง หรือใช้จาก API)</span></label>
                    
                    <input type="hidden" id="api_image" name="api_image">
                    
                    <div id="poster_preview_container" class="hidden mb-3">
                        <p class="text-xs text-green-500 font-bold mb-1">✅ ดึงรูปภาพจาก TMDB สำเร็จ</p>
                        <img id="poster_preview_img" src="" class="h-40 rounded shadow-md border border-gray-600">
                    </div>

                    <input type="file" name="image" accept="image/*"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-md shadow transition">
                    ส่งข้อมูลให้แอดมินตรวจสอบ
                </button>
            </form>
        </div>
    </div>

    <!-- Script สำหรับดึงข้อมูล TMDB -->
    <script>
        async function fetchTMDB() {
            const query = document.getElementById('movie_name').value;
            if(!query) {
                alert('กรุณาพิมพ์ชื่อภาพยนตร์ในช่องก่อนกดค้นหาครับ');
                return;
            }

            // 🟢 เอา API Key ของคุณมาใส่ตรงนี้ 🟢
            const apiKey = '176ba27a57b132784892dc6b4c517753'; 
            
            try {
                const res = await fetch(`https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&language=th-TH&query=${query}`);
                const data = await res.json();

                if(data.results && data.results.length > 0) {
                    const movie = data.results[0]; 
                    
                    if(movie.release_date) document.getElementById('movie_year').value = movie.release_date.substring(0, 4);
                    if(movie.overview) {
                        document.getElementById('movie_review').value = movie.overview;
                    } else {
                        document.getElementById('movie_review').value = "ไม่มีเรื่องย่อภาษาไทยสำหรับภาพยนตร์เรื่องนี้";
                    }

                    if(movie.poster_path) {
                        const imgUrl = 'https://image.tmdb.org/t/p/w500' + movie.poster_path;
                        document.getElementById('api_image').value = imgUrl; 
                        document.getElementById('poster_preview_img').src = imgUrl; 
                        document.getElementById('poster_preview_container').classList.remove('hidden');
                    }
                    alert('ดึงข้อมูลสำเร็จ! กรุณาตรวจสอบความถูกต้องก่อนกดส่ง');
                } else {
                    alert('ไม่พบข้อมูลภาพยนตร์เรื่องนี้ในระบบ TMDB ครับ ลองเปลี่ยนคำค้นหาดูนะ');
                }
            } catch (error) {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อกับ TMDB');
                console.error(error);
            }
        }
    </script>
</x-app-layout>