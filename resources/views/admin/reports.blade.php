<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🚩 จัดการรายงานคอมเมนต์ไม่เหมาะสม (แอดมิน)
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="bg-green-500 text-white font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('success') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-red-50 dark:bg-red-900/10">
                <h3 class="text-lg font-black text-red-700 dark:text-red-400">🚨 รายการแจ้งความ ({{ $reports->count() }} รายการ)</h3>
                <p class="text-sm text-red-500 mt-1">โปรดพิจารณาและจัดการคอมเมนต์ที่ผู้ใช้งานกดรายงานเข้ามา</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider">
                            <th class="p-4 font-bold border-b border-gray-200 dark:border-gray-700 w-1/4">ผู้ถูกรายงาน (เจ้าของคอมเมนต์)</th>
                            <th class="p-4 font-bold border-b border-gray-200 dark:border-gray-700 w-1/3">ข้อความที่ถูกรายงาน</th>
                            <th class="p-4 font-bold border-b border-gray-200 dark:border-gray-700">ผู้แจ้งรายงาน</th>
                            <th class="p-4 font-bold border-b border-gray-200 dark:border-gray-700 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reports as $report)
                            <!-- ตรวจสอบว่าคอมเมนต์ยังอยู่หรือไม่ (เผื่อโดนลบไปแล้ว) -->
                            @if($report->review)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    
                                    <!-- ข้อมูลคนพิมพ์คอมเมนต์ -->
                                    <td class="p-4">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white font-bold text-xs shadow">
                                                {{ strtoupper(substr($report->review->user->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-gray-900 dark:text-white block">{{ $report->review->user->name ?? 'Unknown' }}</span>
                                                <span class="text-xs text-gray-500">รีวิวเรื่อง: <a href="{{ route('activities.show', $report->review->activity_id) }}" class="text-indigo-500 hover:underline" target="_blank">{{ $report->review->activity->name ?? 'ไม่ทราบชื่อหนัง' }}</a></span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- เนื้อหาคอมเมนต์ที่เป็นปัญหา -->
                                    <td class="p-4">
                                        <div class="bg-gray-100 dark:bg-gray-900 p-3 rounded border-l-4 border-red-500 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                            {{ $report->review->comment }}
                                        </div>
                                    </td>
                                    
                                    <!-- คนที่กดแจ้งรีพอร์ตเข้ามา -->
                                    <td class="p-4">
                                        <div class="text-sm">
                                            <p class="font-bold text-gray-700 dark:text-gray-300">🕵️ {{ $report->user->name ?? 'ผู้ใช้ทั่วไป' }}</p>
                                            <p class="text-xs text-gray-400 mt-1">แจ้งเมื่อ: {{ $report->created_at->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    
                                    <!-- ปุ่มกด Action -->
                                    <td class="p-4">
                                        <div class="flex flex-col gap-2">
                                            <!-- ลบคอมเมนต์ทิ้งเลย -->
                                            <form action="{{ route('admin.reviews.destroy', $report->review->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('ลบคอมเมนต์นี้ทิ้งถาวรเลยหรือไม่?')" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-3 rounded shadow transition flex justify-center items-center gap-1">
                                                    🗑️ แบนคอมเมนต์
                                                </button>
                                            </form>
                                            
                                            <!-- ปัดตก (คนแจ้งมั่ว) -->
                                            <form action="{{ route('admin.reports.dismiss', $report->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('ปัดตกรีพอร์ตนี้ (เก็บคอมเมนต์ไว้เหมือนเดิม)?')" class="w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-xs font-bold py-2 px-3 rounded shadow transition flex justify-center items-center gap-1">
                                                    ✅ ปัดตกรีพอร์ต
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-gray-500">
                                    <span class="text-5xl block mb-4">✨</span>
                                    <p class="font-bold text-xl text-gray-700 dark:text-gray-300">ความสงบสุขบังเกิด</p>
                                    <p class="text-sm mt-2">ยังไม่มีใครแจ้งรายงานคอมเมนต์เข้ามาเลยครับ!</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>