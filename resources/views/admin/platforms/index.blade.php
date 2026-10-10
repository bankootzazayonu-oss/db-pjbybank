<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="font-bold text-lg sm:text-xl text-white flex items-center gap-2.5 flex-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400 shrink-0"><rect width="20" height="15" x="2" y="7" rx="2" ry="2"/><polyline points="17 2 12 7 7 2"/></svg>
                จัดการแพลตฟอร์มรับชม (Streaming Platforms)
            </h2>
            <span class="text-xs text-slate-400 bg-slate-900 border border-slate-800 px-3 py-1.5 rounded-lg shrink-0 w-fit">
                สำหรับผูกช่องทางการดูให้กับภาพยนตร์
            </span>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        
        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl mb-6 font-semibold flex items-center gap-2 text-sm shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-xl mb-6 font-semibold flex items-center gap-2 text-sm shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-4 rounded-xl mb-6 font-semibold text-sm shadow-md">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            
            <div class="lg:col-span-4 bg-slate-900/90 border border-slate-800 p-6 rounded-2xl shadow-xl backdrop-blur-sm sticky top-24">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    <div>
                        <h3 class="font-bold text-white text-base">เพิ่มแพลตฟอร์มใหม่</h3>
                        <p class="text-xs text-slate-400">ระบุชื่อช่องทางสำหรับดูภาพยนตร์</p>
                    </div>
                </div>

                <form action="{{ route('admin.platforms.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            ชื่อแพลตฟอร์ม <span class="text-rose-400">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               placeholder="เช่น Netflix, Disney+ Hotstar, Prime Video..." 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">
                            ไอคอน / ลิงก์โลโก้ <span class="text-slate-500 font-normal">(ถ้ามี ไม่บังคับ)</span>
                        </label>
                        <input type="text" 
                               name="logo" 
                               value="{{ old('logo') }}" 
                               placeholder="เช่น https://... หรือเว้นว่างไว้" 
                               class="w-full bg-slate-950 border border-slate-800 rounded-xl px-3.5 py-2.5 text-sm text-white placeholder:text-slate-600 focus:outline-none focus:border-indigo-500 transition">
                    </div>

                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-500 active:scale-[0.98] text-white font-bold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-indigo-950/40 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        บันทึกแพลตฟอร์ม
                    </button>
                </form>

                
                <div class="mt-6 pt-5 border-t border-slate-800/80">
                    <p class="text-[11px] text-slate-400 leading-relaxed font-light flex items-start gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-400 mt-0.5 flex-shrink-0"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        <span>แพลตฟอร์มที่เพิ่มที่นี่ จะไปแสดงเป็นตัวเลือกให้ User และ Admin ติ๊กเลือกตอนเพิ่ม/แก้ไขภาพยนตร์ทันที</span>
                    </p>
                </div>
            </div>

            
            <div class="lg:col-span-8 bg-slate-900/90 border border-slate-800 p-6 rounded-2xl shadow-xl backdrop-blur-sm">
                
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-800">
                    <div>
                        <h3 class="font-bold text-white text-base flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-400"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/></svg>
                            รายการแพลตฟอร์มทั้งหมด 
                            <span class="text-xs bg-slate-800 text-slate-300 px-2 py-0.5 rounded-full font-normal">
                                {{ $platforms->count() }} ช่องทาง
                            </span>
                        </h3>
                    </div>

                    
                    <form action="{{ route('admin.platforms.index') }}" method="GET" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" 
                                   name="q" 
                                   value="{{ $search ?? '' }}" 
                                   placeholder="ค้นหาชื่อแพลตฟอร์ม..." 
                                   class="bg-slate-950 border border-slate-800 rounded-lg pl-8 pr-3 py-1.5 text-xs text-white placeholder:text-slate-600 focus:outline-none focus:border-indigo-500 transition w-48 sm:w-56">
                            <span class="absolute left-2.5 top-1.5 text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </span>
                        </div>
                        @if($search)
                            <a href="{{ route('admin.platforms.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">ล้าง</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-800/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="pb-3 pl-3 w-16">#</th>
                                <th class="pb-3">ชื่อแพลตฟอร์ม</th>
                                <th class="pb-3 text-center w-36">จำนวนหนัง</th>
                                <th class="pb-3 text-right pr-3 w-40">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 text-sm">
                            @forelse($platforms as $index => $platform)
                                <tr x-data="{ isEditing: false, newName: '{{ addslashes($platform->name) }}' }" 
                                    class="hover:bg-slate-800/40 transition">
                                    
                                    <td class="py-3.5 pl-3 text-xs text-slate-500 font-mono">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="py-3.5 pr-3">
                                        
                                        <div x-show="!isEditing" class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-lg bg-slate-950 border border-slate-800 flex items-center justify-center text-xs font-bold text-indigo-400 flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="15" x="2" y="7" rx="2" ry="2"/><polyline points="17 2 12 7 7 2"/></svg>
                                            </div>
                                            <div>
                                                <span class="font-bold text-white text-sm">
                                                    {{ $platform->name }}
                                                </span>
                                            </div>
                                        </div>

                                        
                                        <form x-show="isEditing" 
                                              style="display: none;" 
                                              action="{{ route('admin.platforms.update', $platform->id) }}" 
                                              method="POST" 
                                              class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" 
                                                   name="name" 
                                                   x-model="newName" 
                                                   required 
                                                   class="bg-slate-950 border border-indigo-500/80 rounded-lg px-2.5 py-1 text-xs text-white focus:outline-none w-48">
                                            <button type="submit" 
                                                    class="bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold px-2.5 py-1 rounded-lg transition shadow">
                                                บันทึก
                                            </button>
                                            <button type="button" 
                                                    @click="isEditing = false" 
                                                    class="bg-slate-800 hover:bg-slate-700 text-slate-300 text-[11px] font-medium px-2 py-1 rounded-lg transition">
                                                ยกเลิก
                                            </button>
                                        </form>
                                    </td>

                                    
                                    <td class="py-3.5 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $platform->activities_count > 0 ? 'bg-indigo-500/10 text-indigo-300 border border-indigo-500/20' : 'bg-slate-800 text-slate-500' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19.82 2c-.78 0-1.52.2-2.17.55L8 8.35C4.21 10.21 1 12 1 16c0 3.86 3.14 7 7 7 3.96 0 6.64-2.81 8.54-6.6l5.77-11.53c.48-1 .69-2.07.69-3.13 0-1.5-.78-2.74-2.18-2.74z"/></svg>
                                            <span>{{ $platform->activities_count }} เรื่อง</span>
                                        </span>
                                    </td>

                                    
                                    <td class="py-3.5 text-right pr-3">
                                        <div x-show="!isEditing" class="flex items-center justify-end gap-1.5">
                                            
                                            <button type="button" 
                                                    @click="isEditing = true" 
                                                    class="p-1.5 rounded-lg text-slate-400 hover:text-amber-400 hover:bg-slate-800 transition" 
                                                    title="แก้ไขชื่อ">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                            </button>

                                            
                                            <form action="{{ route('admin.platforms.destroy', $platform->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('ยืนยันที่จะลบแพลตฟอร์ม &quot;{{ addslashes($platform->name) }}&quot; หรือไม่? (จะตัดการเชื่อมต่อกับหนัง {{ $platform->activities_count }} เรื่องอัตโนมัติ)');" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition" 
                                                        title="ลบแพลตฟอร์ม">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-slate-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-3 text-slate-600"><rect width="20" height="15" x="2" y="7" rx="2" ry="2"/><polyline points="17 2 12 7 7 2"/></svg>
                                        <p class="font-medium text-slate-400 text-sm">ยังไม่มีข้อมูลแพลตฟอร์ม</p>
                                        <p class="text-xs text-slate-600 mt-1">สามารถเพิ่มแพลตฟอร์ม เช่น Netflix, Disney+, Prime Video ได้จากฟอร์มด้านซ้าย</p>
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
