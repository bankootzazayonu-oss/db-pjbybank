<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📁 จัดการหมวดหมู่ภาพยนตร์ (Admin)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- แจ้งเตือนข้อความต่างๆ -->
        @if(session('success'))
            <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- ส่วนที่ 1: ฟอร์มเพิ่มหมวดหมู่ (ซ้าย) -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 h-fit">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">➕ เพิ่มหมวดหมู่ใหม่</h3>
                <form action="{{ route('admin.types.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">ชื่อหมวดหมู่</label>
                        <input type="text" name="name" required placeholder="เช่น Action, Comedy, Sci-Fi..." 
                               class="w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition">
                        บันทึกข้อมูล
                    </button>
                </form>
            </div>

            <!-- ส่วนที่ 2: ตารางแสดงหมวดหมู่ (ขวา) -->
            <div class="md:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">📋 รายการหมวดหมู่ทั้งหมด</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-700 dark:text-gray-300">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400">
                                <th class="pb-3 pl-2">ID</th>
                                <th class="pb-3">ชื่อหมวดหมู่</th>
                                <th class="pb-3 text-right pr-2">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($types as $type)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="py-4 pl-2">{{ $type->id }}</td>
                                <td class="py-4 font-bold text-gray-900 dark:text-white">{{ $type->name }}</td>
                                <td class="py-4 text-right pr-2">
                                    <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบหมวดหมู่นี้? หนังในหมวดนี้อาจได้รับผลกระทบ');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-400 font-bold bg-red-100 dark:bg-red-900/30 px-3 py-1 rounded-md text-sm transition">
                                            🗑️ ลบ
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500">ยังไม่มีข้อมูลหมวดหมู่ในระบบ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>