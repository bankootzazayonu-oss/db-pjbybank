<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="font-black text-xl text-white tracking-tight flex items-center gap-2">
                <span>🎖️</span> กระดานจัดอันดับของฉัน (My Tier Lists)
            </h1>
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white py-1.5 px-3.5 rounded-xl transition">
                ← กลับหน้าคลังหนัง
            </a>
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
            <div class="bg-slate-900/60 p-6 rounded-3xl shadow-xl border border-slate-800/90 h-fit backdrop-blur-sm">
                <h2 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                    <span>➕</span> สร้างกระดานใหม่
                </h2>
                <p class="text-xs text-slate-400 mb-4">กำหนดหัวข้อ เช่น "หนังโนแลนในดวงใจ" หรือ "หนังซอมบี้ห้ามพลาด"</p>

                <form action="{{ route('collections.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="name" required placeholder="พิมพ์ชื่อกระดาน..." 
                               class="w-full bg-slate-950 border border-slate-800 focus:border-rose-500 text-white rounded-xl px-4 py-2.5 text-sm placeholder:text-slate-600">
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-semibold text-sm py-2.5 rounded-xl shadow-lg shadow-rose-950/40 transition hover:scale-[1.01]">
                        สร้างกระดานเลย
                    </button>
                </form>
            </div>

            <!-- แสดงกระดานที่มีอยู่ (ขวา) -->
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse($collections as $collection)
                    <div x-data="{ editCollectionMode: false }" class="bg-slate-900/60 p-6 rounded-3xl shadow-xl border border-slate-800/90 hover:border-slate-700 transition group relative backdrop-blur-sm">
                        
                        <!-- โหมด 1: แสดงผลปกติ -->
                        <div x-show="!editCollectionMode">
                            
                            <!-- แถบปุ่มจัดการ -->
                            <div class="absolute top-5 right-5 opacity-0 group-hover:opacity-100 transition flex gap-1.5 z-10">
                                <button type="button" @click="editCollectionMode = true" class="text-indigo-400 hover:text-white font-semibold bg-indigo-500/20 border border-indigo-500/30 px-2.5 py-1 rounded-lg text-xs transition">
                                    ✏️ แก้ไข
                                </button>
                                <form action="{{ route('collections.destroy', $collection->id) }}" method="POST" onsubmit="return confirm('ยืนยันลบกระดานนี้? (หนังที่จัดอันดับไว้จะหายไปทั้งหมด)');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-white font-semibold bg-rose-500/20 border border-rose-500/30 px-2.5 py-1 rounded-lg text-xs transition">
                                        🗑️ ลบ
                                    </button>
                                </form>
                            </div>

                            <!-- ลิงก์คลิกเข้ากระดาน -->
                            <a href="{{ route('collections.show', $collection->id) }}" class="block">
                                <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-xl mb-3 group-hover:scale-105 transition">
                                    🎖️
                                </div>
                                <h3 class="text-lg font-bold text-white pr-14 truncate group-hover:text-rose-400 transition">{{ $collection->name }}</h3>
                                <p class="text-xs text-slate-400 mt-1">อัปเดต: {{ $collection->updated_at->diffForHumans() }}</p>
                                <span class="inline-block mt-4 text-xs font-semibold text-rose-400 group-hover:translate-x-1 transition">
                                    เปิดกระดานจัดอันดับ →
                                </span>
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
                    <div class="col-span-2 text-center text-slate-500 py-16 bg-slate-900/40 rounded-3xl border border-dashed border-slate-800">
                        <span class="text-4xl block mb-2">🎖️</span>
                        <p class="text-slate-300 font-semibold text-sm">ยังไม่มีกระดานจัดอันดับ</p>
                        <p class="text-xs text-slate-500 mt-1">สร้างกระดานแรกของคุณจากฟอร์มด้านซ้ายมือได้เลย</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>