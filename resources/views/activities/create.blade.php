<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ➕ เสนอภาพยนตร์ใหม่เข้าระบบ
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            
            <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm">
                ภาพยนตร์ที่คุณเสนอจะถูกส่งให้แอดมินตรวจสอบก่อนแสดงผลบนหน้าเว็บไซต์จริง เพื่อป้องกันข้อมูลซ้ำซ้อนและคำหยาบคาย
            </p>

            <!-- แสดง Error ตัวแดงถ้าระบบตีกลับ -->
            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6 text-sm shadow-md">
                    <ul class="list-disc pl-5 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 🚨 แท็กฟอร์มหลักที่ถูกต้อง ต้องมี enctype แบบนี้ -->
            <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ชื่อภาพยนตร์/ซีรีส์ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                           placeholder="เช่น Avengers: Endgame"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ปีที่เข้าฉาย (ค.ศ.) <span class="text-red-500">*</span></label>
                    <input type="number" name="year" value="{{ old('year', date('Y')) }}" required 
                           class="w-full md:w-1/2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">เรื่องย่อ / คำอธิบาย <span class="text-red-500">*</span></label>
                    <textarea name="review" rows="4" required 
                              placeholder="พิมพ์เรื่องย่อสั้นๆ หรือความน่าสนใจของภาพยนตร์เรื่องนี้..."
                              class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('review') }}</textarea>
                </div>

                <!-- 🚨 ช่องอัปโหลดรูปที่จัดโครงสร้างถูกต้อง -->
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">อัปโหลดโปสเตอร์ภาพยนตร์ <span class="text-gray-400 font-normal">(ไม่บังคับ)</span></label>
                    <input type="file" name="image" accept="image/*"
                           class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-md shadow transition">
                    ส่งข้อมูลให้แอดมินตรวจสอบ
                </button>
            </form>
        </div>
    </div>
</x-app-layout>