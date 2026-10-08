<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineReview - บันทึก รีวิว และจัดอันดับภาพยนตร์</title>

    <!-- Google Fonts: Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0b0f17] text-slate-200 min-h-screen antialiased selection:bg-rose-500 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="border-b border-slate-800/80 bg-[#0b0f17]/95 backdrop-blur sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <span class="w-9 h-9 rounded-lg bg-rose-600 text-white flex items-center justify-center font-black text-lg shadow-sm">
                    🎬
                </span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-lg font-black tracking-tight text-white group-hover:text-rose-400 transition">CineReview</span>
                    <span class="text-xs text-slate-500 font-medium">คลังรีวิวหนัง</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('leaderboard') }}" class="text-xs sm:text-sm font-semibold text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800/80 transition">
                    🏆 10 อันดับยอดนิยม
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-xs sm:text-sm font-semibold bg-rose-600 hover:bg-rose-500 text-white px-3.5 py-1.5 rounded-lg transition">
                            เข้าสู่แดชบอร์ด →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-medium text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition">
                            เข้าสู่ระบบ
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-xs sm:text-sm font-semibold bg-rose-600 hover:bg-rose-500 text-white px-3.5 py-1.5 rounded-lg transition">
                                สมัครสมาชิก
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Compact & Clean Hero Header -->
    <section class="border-b border-slate-800/80 bg-slate-900/40 py-7 sm:py-9">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
            
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                ค้นหาและรีวิวภาพยนตร์
            </h1>

            <p class="mt-1.5 text-xs sm:text-sm text-slate-400 font-light">
                บันทึกเรื่องที่ดู แบ่งปันมุมมองกับเพื่อนคอหนัง และสร้างกระดานจัดอันดับ Tier List
            </p>

            <!-- Search Form -->
            <form action="{{ route('home') }}" method="GET" class="mt-5 max-w-xl mx-auto">
                <div class="relative flex items-center">
                    <input type="text" 
                           name="q" 
                           value="{{ $search ?? '' }}" 
                           placeholder="พิมพ์ชื่อภาพยนตร์ที่ต้องการค้นหา..." 
                           class="w-full bg-slate-950 border border-slate-700/80 focus:border-rose-500 text-white placeholder:text-slate-500 rounded-xl pl-10 pr-24 py-2.5 text-xs sm:text-sm focus:outline-none transition shadow-sm">
                    
                    <span class="absolute left-3 text-slate-400 text-sm">🔍</span>

                    <button type="submit" class="absolute right-1.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold px-3.5 py-1.5 rounded-lg transition">
                        ค้นหา
                    </button>
                </div>
            </form>

            <!-- Quick Genre Filter Chips -->
            @if($types->count() > 0)
                <div class="mt-4 flex flex-wrap justify-center items-center gap-1.5">
                    <a href="{{ route('home') }}" 
                       class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition {{ empty($typeId) && empty($search) ? 'bg-white text-slate-900 border-white font-bold' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white hover:border-slate-700' }}">
                        ทั้งหมด
                    </a>
                    @foreach($types as $type)
                        <a href="{{ route('home', ['type' => $type->id]) }}" 
                           class="text-[11px] font-medium px-2.5 py-1 rounded-lg border transition {{ ($typeId == $type->id) ? 'bg-rose-600 text-white border-rose-600 font-bold' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white hover:border-slate-700' }}">
                            {{ $type->name }}
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    <!-- Main Content: Movie Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- Grid Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>🍿</span> 
                    @if($search)
                        ผลการค้นหาสำหรับ "{{ $search }}"
                    @elseif($typeId)
                        หมวดหมู่: {{ $types->firstWhere('id', $typeId)?->name ?? 'ที่เลือก' }}
                    @else
                        ภาพยนตร์ล่าสุดที่เปิดให้รีวิว
                    @endif
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">เลือกภาพยนตร์เพื่ออ่านบทวิจารณ์ หรือร่วมแสดงความคิดเห็น</p>
            </div>

            @if($search || $typeId)
                <a href="{{ route('home') }}" class="text-xs font-semibold text-rose-400 hover:text-rose-300 transition">
                    ล้างตัวกรอง ✕
                </a>
            @else
                <a href="{{ route('leaderboard') }}" class="text-xs font-semibold text-slate-400 hover:text-white transition">
                    ดู 10 อันดับคะแนนสูงสุด →
                </a>
            @endif
        </div>

        <!-- Movie Cards -->
        @if($featuredMovies->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-5">
                @foreach($featuredMovies as $movie)
                    <div class="group bg-slate-900 rounded-xl border border-slate-800 overflow-hidden flex flex-col hover:border-slate-700 transition duration-200">
                        
                        <!-- Poster -->
                        <div class="relative aspect-[2/3] bg-slate-950 overflow-hidden">
                            @if($movie->image)
                                <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                     alt="{{ $movie->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-slate-900">
                                    <span class="text-3xl">🎬</span>
                                    <span class="text-[10px] mt-1 text-slate-500">ไม่มีรูป</span>
                                </div>
                            @endif

                            <!-- Floating Rating -->
                            <div class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-slate-950/85 backdrop-blur text-[11px] font-bold text-amber-400 flex items-center gap-1 border border-white/10 shadow">
                                <span>⭐</span>
                                <span class="text-white">{{ $movie->reviews_avg_rating ? number_format($movie->reviews_avg_rating, 1) : '-' }}</span>
                            </div>

                            <!-- Year -->
                            <div class="absolute bottom-2 left-2 px-1.5 py-0.5 rounded bg-slate-950/80 text-[10px] text-slate-300 font-medium">
                                {{ $movie->year }}
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-3 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-100 group-hover:text-rose-400 transition line-clamp-1 leading-snug">
                                    {{ $movie->name }}
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    {{ $movie->type ? $movie->type->name : 'ทั่วไป' }}
                                </p>
                            </div>

                            <div class="mt-3 pt-2 border-t border-slate-800 flex items-center justify-between text-[11px]">
                                <span class="text-slate-500">💬 {{ $movie->reviews_count }} รีวิว</span>
                                <a href="{{ route('activities.show', $movie->id) }}" class="text-rose-400 font-semibold hover:underline">
                                    ดูรีวิว
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-slate-900/40 rounded-xl border border-slate-800">
                <span class="text-4xl block mb-2">🔍</span>
                <p class="text-slate-300 font-medium text-sm">ไม่พบภาพยนตร์ที่ตรงกับเงื่อนไข</p>
                <a href="{{ route('home') }}" class="inline-block mt-3 text-xs text-rose-400 hover:underline">
                    ดูภาพยนตร์ทั้งหมด
                </a>
            </div>
        @endif

        <!-- Recent Real Community Reviews -->
        @if($recentReviews->count() > 0)
            <section class="mt-16 pt-10 border-t border-slate-800">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-white flex items-center gap-2">
                            <span>💬</span> รีวิวล่าสุดจากสมาชิก
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">ความเห็นสดๆ ร้อนๆ จากคนดูหนังในคอมมูนิตี้</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($recentReviews as $review)
                        <div class="bg-slate-900/80 p-4 rounded-xl border border-slate-800 flex flex-col justify-between hover:border-slate-700 transition">
                            <div>
                                <!-- Author & Rating -->
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-800 text-slate-200 flex items-center justify-center text-xs font-bold border border-slate-700">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-white leading-none">{{ $review->user->name ?? 'สมาชิก' }}</p>
                                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-amber-400">
                                        ⭐ {{ $review->rating }}/10
                                    </span>
                                </div>

                                <!-- Movie Link -->
                                <a href="{{ route('activities.show', $review->activity_id) }}" class="text-xs font-bold text-rose-400 hover:underline block mb-2 truncate">
                                    🎬 {{ $review->activity->name ?? 'ภาพยนตร์' }}
                                </a>

                                <!-- Comment Snippet -->
                                <p class="text-xs text-slate-300 font-light leading-relaxed line-clamp-3">
                                    {{ $review->comment }}
                                </p>
                            </div>

                            <div class="mt-4 pt-2.5 border-t border-slate-800/80 text-right">
                                <a href="{{ route('activities.show', $review->activity_id) }}" class="text-[11px] font-semibold text-slate-400 hover:text-white transition">
                                    อ่านรีวิวเต็ม →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 mt-16 py-8 text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© 2026 CineReview. โปรเจกต์วิชา Database & Web Application</p>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="{{ route('leaderboard') }}" class="hover:text-white transition">จัดอันดับ</a>
                <span>•</span>
                <a href="{{ route('login') }}" class="hover:text-white transition">เข้าสู่ระบบ</a>
                <span>•</span>
                <a href="{{ route('register') }}" class="hover:text-white transition">สมัครสมาชิก</a>
            </div>
        </div>
    </footer>

</body>
</html>
