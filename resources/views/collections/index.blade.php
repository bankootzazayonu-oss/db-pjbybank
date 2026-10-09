<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-bold text-xl text-white flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
                กระดานจัดอันดับของฉัน (My Tier Lists)
            </h1>
            <div class="flex items-center gap-3">
                <a href="{{ route('collections.trash') }}" class="text-xs font-medium bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white py-1.5 px-3.5 rounded-lg transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    ถังขยะ
                </a>
                <a href="{{ route('dashboard') }}" class="text-xs font-medium bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-lg transition">
                    ← กลับหน้าคลังหนัง
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-2xl font-semibold shadow-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- ฟอร์มสร้างกระดาน (ซ้าย) -->
            <div class="bg-slate-900/30 p-6 rounded-2xl shadow-sm border border-dashed border-slate-700 h-fit">
                <h2 class="text-base font-bold text-white mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    สร้างกระดานใหม่
                </h2>
                <p class="text-xs text-slate-400 mb-5 leading-relaxed">กำหนดหัวข้อ เช่น "หนังโนแลนในดวงใจ" หรือ "หนังซอมบี้ห้ามพลาด"</p>

                <form action="{{ route('collections.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="name" required placeholder="พิมพ์ชื่อกระดาน..." 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-white rounded-lg px-4 py-2.5 text-sm placeholder:text-slate-600 transition">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-medium text-sm py-2.5 rounded-lg shadow-sm transition">
                        สร้างกระดานเลย
                    </button>
                </form>
            </div>

            <!-- แสดงกระดานที่มีอยู่ (ขวา) -->
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($collections as $collection)
                    <div x-data="{ editCollectionMode: false }" class="bg-slate-900/60 p-6 rounded-2xl shadow-sm border border-slate-800 hover:border-slate-700 transition group relative">
                        
                        <!-- โหมด 1: แสดงผลปกติ -->
                        <div x-show="!editCollectionMode">
                            
                            <!-- แถบปุ่มจัดการ -->
                            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition flex gap-1.5 z-10">
                                <button type="button" @click="editCollectionMode = true" class="text-slate-400 hover:text-white bg-slate-950 border border-slate-700 hover:border-slate-500 px-2 py-1.5 rounded-md text-xs transition" title="แก้ไขชื่อกระดาน">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบกระดานนี้? (หนังที่จัดอันดับไว้จะหายไปทั้งหมด)');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-rose-400 bg-slate-950 border border-slate-700 hover:border-rose-900 px-2 py-1.5 rounded-md text-xs transition" title="ลบกระดาน">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </form>
                            </div>

                            <!-- ลิงก์คลิกเข้ากระดาน -->
                            <a href="{{ route('collections.show', $collection->id) }}" class="block mt-1">
                                <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-4 group-hover:scale-105 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
                                </div>
                                <h3 class="text-base font-bold text-white pr-14 truncate group-hover:text-indigo-400 transition">{{ $collection->name }}</h3>
                                <p class="text-xs text-slate-400 mt-1">อัปเดต: {{ $collection->updated_at->diffForHumans() }}</p>
                                <div class="mt-4 flex items-center gap-1.5 text-xs font-medium text-indigo-400 group-hover:translate-x-1 transition">
                                    เปิดกระดานจัดอันดับ 
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                                </div>
                            </a>

                        </div>

                        <!-- โหมด 2: ฟอร์มแก้ไขชื่อกระดาน -->
                        <div x-show="editCollectionMode" style="display: none;">
                            <form action="{{ route('collections.update', $collection->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label class="block text-xs font-bold text-slate-400 mb-1">แก้ไขชื่อกระดาน:</label>
                                <input type="text" name="name" value="{{ $collection->name }}" required class="w-full text-sm bg-slate-950 border border-slate-800 focus:border-rose-500 text-white rounded-xl px-3 py-2 mb-3">
                                
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition">
                                        บันทึก
                                    </button>
                                    <button type="button" @click="editCollectionMode = false" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold py-1.5 px-3 rounded-lg transition">
                                        ยกเลิก
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="col-span-2 text-center text-slate-500 py-16 bg-slate-900/40 rounded-2xl border border-dashed border-slate-800 flex flex-col items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-4 text-slate-600"><path d="M3 6h18"/><path d="M3 12h18"/><path d="M3 18h18"/></svg>
                        <p class="text-slate-300 font-medium text-sm">ยังไม่มีกระดานจัดอันดับ</p>
                        <p class="text-xs text-slate-500 mt-1">สร้างกระดานแรกของคุณจากฟอร์มด้านซ้ายมือได้เลย</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>