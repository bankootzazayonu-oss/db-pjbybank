<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="font-bold text-lg sm:text-xl text-white flex items-center gap-2.5 flex-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400 shrink-0"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                จัดการหมวดหมู่ภาพยนตร์ (Admin)
            </h2>
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('success') }}</div>
        @endif
        
        
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold p-4 rounded-lg mb-6 shadow-md">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 font-bold p-4 rounded-lg mb-6 shadow-md">{{ $errors->first() }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-slate-900/90 p-6 rounded-2xl shadow-xl border border-slate-800 backdrop-blur-sm h-fit">
                <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    เพิ่มหมวดหมู่ใหม่
                </h3>
                <form action="{{ route('admin.types.store') }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">ชื่อหมวดหมู่</label>
                        <input type="text" name="name" required placeholder="เช่น Action, Comedy, Sci-Fi..." 
                               class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-indigo-500 transition">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-950/40 transition">
                        บันทึกข้อมูล
                    </button>
                </form>
            </div>

            
            <div class="md:col-span-2 bg-slate-900/90 p-6 rounded-2xl shadow-xl border border-slate-800 backdrop-blur-sm overflow-hidden">
                <h3 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg>
                    รายการหมวดหมู่ทั้งหมด
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead>
                            <tr class="border-b border-slate-800/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 pl-3 w-16">ID</th>
                                <th class="pb-3">ชื่อหมวดหมู่</th>
                                <th class="pb-3 text-right pr-3 w-48">การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($types as $type)
                            
                            <tr x-data="{ editTypeMode: false }" class="hover:bg-slate-800/40 transition">
                                <td class="py-3.5 pl-3 text-slate-500 font-mono text-xs">{{ $type->id }}</td>
                                
                                <td class="py-3.5">
                                    
                                    <div x-show="!editTypeMode" class="font-bold text-white">
                                        {{ $type->name }}
                                    </div>

                                    
                                    <form x-show="editTypeMode" style="display: none;" action="{{ route('admin.types.update', $type->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $type->name }}" required
                                               class="bg-slate-950 border border-indigo-500/80 text-white rounded-lg px-2.5 py-1 text-xs focus:outline-none w-full max-w-xs">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg transition shadow">
                                            บันทึก
                                        </button>
                                        <button type="button" @click="editTypeMode = false" class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-medium px-2 py-1 rounded-lg transition">
                                            ยกเลิก
                                        </button>
                                    </form>
                                </td>

                                <td class="py-3.5 text-right pr-3">
                                    
                                    <div x-show="!editTypeMode" class="flex justify-end items-center gap-1.5">
                                        
                                        <button type="button" @click="editTypeMode = true" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-slate-800 transition flex items-center gap-1.5 text-xs font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            แก้ไข
                                        </button>

                                        
                                        <form action="{{ route('admin.types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('ยืนยันการลบหมวดหมู่นี้?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition flex items-center gap-1.5 text-xs font-semibold">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                ลบ
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-slate-600"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                                    <p class="font-medium text-slate-400 text-sm">ยังไม่มีข้อมูลหมวดหมู่ในระบบ</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>