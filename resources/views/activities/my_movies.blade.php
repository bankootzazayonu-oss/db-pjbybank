<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📂 ภาพยนตร์ที่ฉันเสนอ
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('error') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-gray-700 dark:text-gray-300">
                    <thead class="bg-gray-100 dark:bg-gray-900 font-bold uppercase text-sm">
                        <tr>
                            <th class="px-6 py-4">ชื่อภาพยนตร์</th>
                            <th class="px-6 py-4">ปีที่ฉาย</th>
                            <th class="px-6 py-4 text-center">สถานะ</th>
                            <th class="px-6 py-4 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($movies as $movie)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                <td class="px-6 py-4 font-bold text-indigo-500">{{ $movie->name }}</td>
                                <td class="px-6 py-4">{{ $movie->year }}</td>
                                <td class="px-6 py-4 text-center">
   @if($movie->status === 'approved')

    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
        ✅ อนุมัติแล้ว
    </span>

@elseif($movie->status === 'rejected')

    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
        ❌ ถูกปฏิเสธ
    </span>

@else

    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">
        ⏳ รอตรวจสอบ
    </span>

@endif
</td>
                               <td class="px-6 py-4 text-center">
    <div class="flex flex-wrap justify-center gap-2">

        {{-- Admin แก้ได้ทุกสถานะ --}}
        @if(auth()->user()->role === 'admin')

            <a
                href="{{ route('activities.edit', $movie->id) }}"
                class="text-blue-700 hover:text-blue-900 font-bold text-sm bg-blue-100 px-3 py-2 rounded-md"
            >
                ✏️ แก้ไข
            </a>

        {{-- User แก้ได้เฉพาะ Pending / Rejected --}}
        @elseif(in_array($movie->status, ['pending', 'rejected']))

            <a
                href="{{ route('activities.edit', $movie->id) }}"
                class="text-blue-700 hover:text-blue-900 font-bold text-sm bg-blue-100 px-3 py-2 rounded-md"
            >
                ✏️ แก้ไข
            </a>

        @else

            <span class="text-gray-400 text-sm italic">
                🔒 ล็อกการแก้ไข
            </span>

        @endif


        {{-- Approved เท่านั้นที่ดู / รีวิวได้ --}}
        @if($movie->status === 'approved')

            <a
                href="{{ route('activities.show', $movie->id) }}"
                class="text-green-700 hover:text-green-900 font-bold text-sm bg-green-100 px-3 py-2 rounded-md"
            >
                🎬 ดู / รีวิว
            </a>

        @endif

    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">คุณยังไม่เคยเสนอภาพยนตร์เข้าสู่ระบบ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>