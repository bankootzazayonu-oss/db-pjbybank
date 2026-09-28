<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            กระดาน: <span class="text-indigo-400">{{ $collection->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 font-bold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6 font-bold">{{ session('error') }}</div>
        @endif

        <!-- แผงเพิ่มหนังเข้ากระดาน -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 mb-8">
            <form action="{{ route('collections.add', $collection->id) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                @csrf
                <div class="flex-1 w-full">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">เลือกภาพยนตร์ (เฉพาะที่อนุมัติแล้ว)</label>
                    <select name="activity_id" required class="w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                        <option value="">-- ค้นหาและเลือกหนัง --</option>
                        @foreach($allMovies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->name }} ({{ $movie->year }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">จัดระดับ (Tier)</label>
                    <select name="tier_rank" required class="w-full bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-md">
                        <option value="S">S - โคตรเทพ</option>
                        <option value="A">A - ดีเยี่ยม</option>
                        <option value="B">B - สนุกดี</option>
                        <option value="C">C - ดูเพลินๆ</option>
                        <option value="D">D - เฉยๆ/ผิดหวัง</option>
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md">
                    ➕ เพิ่มลงกระดาน
                </button>
            </form>
        </div>

        <!-- ตาราง Tier List -->
        @php
            $tiers = [
                'S' => ['color' => 'bg-red-500', 'text' => 'S'],
                'A' => ['color' => 'bg-orange-400', 'text' => 'A'],
                'B' => ['color' => 'bg-yellow-400', 'text' => 'B'],
                'C' => ['color' => 'bg-green-400', 'text' => 'C'],
                'D' => ['color' => 'bg-gray-400', 'text' => 'D'],
            ];
        @endphp

        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-2xl border border-gray-800">
            @foreach($tiers as $key => $tier)
                <div class="flex border-b border-gray-800 min-h-[120px]">
                    <!-- ป้ายตัวอักษร S A B C D -->
                    <div class="{{ $tier['color'] }} w-24 flex-shrink-0 flex items-center justify-center border-r border-gray-900 shadow-inner">
                        <span class="text-4xl font-black text-white drop-shadow-md">{{ $tier['text'] }}</span>
                    </div>
                    
                    <!-- โซนวางโปสเตอร์หนัง -->
                    <div class="flex-1 p-4 flex flex-wrap gap-4 bg-gray-800">
                        @if(isset($items[$key]))
                            @foreach($items[$key] as $item)
                                <div class="relative group w-24 h-36 bg-gray-700 rounded-md overflow-hidden border border-gray-600">
                                    <!-- รูปหนัง -->
                                    @if($item->movie->image)
                                        @if(\Illuminate\Support\Str::startsWith($item->movie->image, ['http://', 'https://']))
                                            <img src="{{ $item->movie->image }}" alt="Poster" class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('storage/' . $item->movie->image) }}" alt="Poster" class="w-full h-full object-cover">
                                        @endif
                                    @else
                                        <img src="https://via.placeholder.com/150x220?text=No+Image" alt="Poster" class="w-full h-full object-cover">
                                    @endif
                                    
                                    <!-- ปุ่มแก้ไข/ลบ ที่จะโผล่ตอนเอาเมาส์ชี้ (Hover) -->
                                    <div class="absolute inset-0 bg-black/80 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                        <p class="text-xs text-white font-bold mb-2 text-center px-1 truncate w-full">{{ $item->movie->name }}</p>
                                        
                                        <!-- ฟอร์มเปลี่ยนแรงก์ -->
                                        <form action="{{ route('collections.updateRank', $item->id) }}" method="POST" class="w-full px-2 mb-1">
                                            @csrf @method('PUT')
                                            <select name="tier_rank" onchange="this.form.submit()" class="w-full text-xs p-1 bg-gray-900 text-white border-0 rounded cursor-pointer">
                                                <option value="" disabled selected>ย้ายไป...</option>
                                                <option value="S">Tier S</option>
                                                <option value="A">Tier A</option>
                                                <option value="B">Tier B</option>
                                                <option value="C">Tier C</option>
                                                <option value="D">Tier D</option>
                                            </select>
                                        </form>

                                        <!-- ฟอร์มลบ -->
                                        <form action="{{ route('collections.destroyItem', $item->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" onclick="return confirm('นำออกจากกระดาน?')" class="text-xs bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded">ลบทิ้ง</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>