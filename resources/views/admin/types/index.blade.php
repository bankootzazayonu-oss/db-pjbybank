<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('จัดการหมวดหมู่ภาพยนตร์') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- ฟอร์มเพิ่มหมวดหมู่ -->
                <form action="{{ route('admin.types.store') }}" method="POST" class="mb-6 flex gap-4">
                    @csrf
                    <input type="text" name="name" placeholder="ชื่อหมวดหมู่ใหม่ (เช่น Action, Drama)..." required 
                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full max-w-md">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition">
                        + เพิ่มหมวดหมู่
                    </button>
                </form>

                <!-- ตารางแสดงหมวดหมู่ -->
                <div class="relative overflow-x-auto rounded-lg border dark:border-gray-700">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">ชื่อหมวดหมู่</th>
                                <th scope="col" class="px-6 py-3">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($types as $type)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">{{ $type->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $type->name }}</td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('แน่ใจหรือไม่ที่จะลบหมวดหมู่นี้?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold">🗑️ ลบ</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">ยังไม่มีข้อมูลหมวดหมู่</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>