<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ✏️ แก้ไขข้อมูลภาพยนตร์
        </h2>
    </x-slot>

    <div class="py-12 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            
            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6 text-sm shadow-md">
                    <ul class="list-disc pl-5 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ชื่อภาพยนตร์/ซีรีส์ *</label>
                    <input type="text" name="name" value="{{ old('name', $activity->name) }}" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ปีที่เข้าฉาย (ค.ศ.) *</label>
                    <input type="number" name="year" value="{{ old('year', $activity->year) }}" required class="w-full md:w-1/2 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="mb-4">
    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
        หมวดหมู่ภาพยนตร์ *
    </label>

    <select
        name="type_id"
        required
        class="w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="">-- เลือกหมวดหมู่ --</option>

        @foreach($types as $type)
            <option
                value="{{ $type->id }}"
                {{ $activity->type_id == $type->id ? 'selected' : '' }}
            >
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>


{{-- ผู้กำกับ --}}
<div class="mb-5">
    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
        ผู้กำกับ
    </label>

    <select
        name="director_id"
        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="">-- ไม่ระบุผู้กำกับ --</option>

        @foreach($directors as $director)
            <option
                value="{{ $director->id }}"
                {{ old('director_id', $activity->director_id) == $director->id ? 'selected' : '' }}
            >
                {{ $director->name }}
            </option>
        @endforeach
    </select>
</div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">เรื่องย่อ / คำอธิบาย *</label>
                    <textarea name="review" rows="4" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('review', $activity->review) }}</textarea>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">อัปโหลดโปสเตอร์ใหม่ <span class="text-gray-400 font-normal">(ถ้าไม่เปลี่ยน ไม่ต้องอัปโหลด)</span></label>
                    <input type="file" name="image" accept="image/*" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-md shadow transition">
                        💾 บันทึกการแก้ไข
                    </button>
                    <a href="{{ route('my.movies') }}" class="flex-none bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-md shadow transition text-center">
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>