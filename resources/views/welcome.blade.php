<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CineReview - คอมมูนิตี้รีวิวและจัด Tier List ภาพยนตร์</title>

    <!-- Google Fonts: Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen antialiased selection:bg-rose-500 selection:text-white">

    <!-- Ambient Glow Top -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 bg-gradient-to-b from-indigo-900/20 via-rose-900/10 to-transparent blur-3xl pointer-events-none -z-10"></div>

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 backdrop-blur-md bg-slate-950/80 border-b border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-600 via-purple-600 to-indigo-500 flex items-center justify-center text-xl shadow-lg shadow-rose-950/50 group-hover:scale-105 transition duration-200">
                    🎬
                </span>
                <div>
                    <span class="text-lg font-bold tracking-tight text-white group-hover:text-rose-400 transition">CineReview</span>
                    <span class="hidden sm:inline-block text-[11px] px-2 py-0.5 ml-1.5 rounded-full bg-rose-500/10 text-rose-400 border border-rose-500/20 font-medium">Community</span>
                </div>
            </a>

            <!-- Nav Actions -->
            <nav class="flex items-center gap-3">
                <a href="{{ route('leaderboard') }}" class="text-sm font-medium text-slate-300 hover:text-white px-3 py-2 rounded-lg hover:bg-slate-800/60 transition">
                    🏆 จัดอันดับ
                </a>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-xl shadow-lg shadow-rose-900/30 transition hover:scale-[1.02]">
                            เข้าสู่แดชบอร์ด →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white px-3 py-2 rounded-lg hover:bg-slate-800/60 transition">
                            เข้าสู่ระบบ
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center text-sm font-semibold bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white px-4 py-2 rounded-xl shadow-lg shadow-rose-900/30 transition hover:scale-[1.02]">
                                สมัครสมาชิก
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-16 pb-20 overflow-hidden">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            
            <!-- Pill Tag -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/80 border border-slate-800 text-xs text-rose-400 font-medium mb-6 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                พื้นที่แลกเปลี่ยนมุมมองคนรักหนัง & จัด Tier List ในแบบคุณ
            </div>

            <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-white leading-tight">
                รวมทุกเสียงวิจารณ์ <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-rose-400 via-fuchsia-300 to-indigo-400 bg-clip-text text-transparent">
                    จัดอันดับภาพยนตร์ที่ใช่สำหรับคุณ
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-400 max-w-2xl mx-auto font-light leading-relaxed">
                สำรวจคลังภาพยนตร์ อ่านรีวิวจริงใจจากเพื่อนคอหนัง และสร้างกระดานจัดอันดับ Tier List ส่วนตัวได้ง่ายๆ ด้วยข้อมูลครบครันระดับสากล
            </p>

            <!-- CTA Buttons -->
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-rose-600 to-indigo-600 hover:from-rose-500 hover:to-indigo-500 text-white font-semibold shadow-xl shadow-rose-950/40 transition hover:scale-[1.02]">
                    <span>🏆 ดู 10 อันดับหนังยอดนิยม</span>
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 font-semibold transition hover:scale-[1.02]">
                        <span>🍿 ไปยังคลังหนัง</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-800 font-semibold transition hover:scale-[1.02]">
                        <span>🔑 เริ่มเขียนรีวิวของคุณ</span>
                    </a>
                @endauth
            </div>

            <!-- Stats Bar -->
            <div class="mt-14 pt-8 border-t border-slate-800/80 grid grid-cols-2 md:grid-cols-3 gap-6 max-w-2xl mx-auto">
                <div class="p-3">
                    <p class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $totalMovies }}</p>
                    <p class="text-xs text-slate-400 mt-1 uppercase tracking-wider">ภาพยนตร์ในคลัง</p>
                </div>
                <div class="p-3">
                    <p class="text-2xl sm:text-3xl font-extrabold text-rose-400 tracking-tight">{{ $totalReviews }}</p>
                    <p class="text-xs text-slate-400 mt-1 uppercase tracking-wider">รีวิวจากชุมชน</p>
                </div>
                <div class="p-3 col-span-2 md:col-span-1">
                    <p class="text-2xl sm:text-3xl font-extrabold text-amber-400 tracking-tight">100%</p>
                    <p class="text-xs text-slate-400 mt-1 uppercase tracking-wider">ระบบคัดกรองโปร่งใส</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Featured Movies Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-white flex items-center gap-2">
                    <span class="text-rose-500">🍿</span> ภาพยนตร์ล่าสุดในระบบ
                </h2>
                <p class="text-sm text-slate-400 mt-1">อัปเดตและผ่านการรับรองจากทีมดูแลชุมชนแล้ว</p>
            </div>
            <a href="{{ route('leaderboard') }}" class="text-sm font-medium text-rose-400 hover:text-rose-300 transition">
                ดูอันดับคะแนนสูงสุด →
            </a>
        </div>

        @if($featuredMovies->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-6">
                @foreach($featuredMovies as $movie)
                    <div class="group bg-slate-900/60 rounded-2xl border border-slate-800/90 overflow-hidden flex flex-col hover:border-slate-700 hover:shadow-2xl hover:shadow-slate-950 transition duration-300">
                        <!-- Poster Container -->
                        <div class="relative aspect-[2/3] overflow-hidden bg-slate-950">
                            @if($movie->image)
                                <img src="{{ Str::startsWith($movie->image, ['http://', 'https://']) ? $movie->image : asset('storage/' . $movie->image) }}" 
                                     alt="{{ $movie->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-600 bg-slate-900">
                                    <span class="text-4xl">🎬</span>
                                    <span class="text-xs mt-2">ไม่มีภาพโปสเตอร์</span>
                                </div>
                            @endif

                            <!-- Floating Rating Badge -->
                            <div class="absolute top-3 right-3 px-2.5 py-1 rounded-xl bg-slate-950/80 backdrop-blur-md border border-white/10 flex items-center gap-1.5 shadow-lg">
                                <span class="text-amber-400 text-xs">⭐</span>
                                <span class="text-xs font-bold text-white">
                                    {{ $movie->reviews_avg_rating ? number_format($movie->reviews_avg_rating, 1) : 'ใหม่' }}
                                </span>
                            </div>

                            <!-- Year Chip -->
                            <div class="absolute bottom-3 left-3 px-2 py-0.5 rounded-lg bg-slate-950/80 backdrop-blur-md border border-white/10 text-[11px] font-medium text-slate-300">
                                {{ $movie->year }}
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-4 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-bold text-slate-100 group-hover:text-rose-400 transition line-clamp-1 text-base">
                                    {{ $movie->name }}
                                </h3>
                                <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $movie->review }}
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                                <span class="text-slate-400">
                                    💬 {{ $movie->reviews_count }} รีวิว
                                </span>
                                @auth
                                    <a href="{{ route('activities.show', $movie->id) }}" class="text-rose-400 font-semibold hover:text-rose-300 transition">
                                        ดูรายละเอียด →
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="text-rose-400 font-semibold hover:text-rose-300 transition">
                                        เข้าสู่ระบบเพื่อรีวิว →
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-slate-900/40 rounded-2xl border border-slate-800">
                <span class="text-4xl">🎬</span>
                <p class="text-slate-400 mt-3">ยังไม่มีภาพยนตร์ในคลัง</p>
            </div>
        @endif
    </section>

    <!-- Highlights Features -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-slate-800/80">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/80 hover:border-rose-500/30 transition">
                <div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-2xl mb-4">
                    ⭐
                </div>
                <h3 class="text-lg font-bold text-white mb-2">รีวิวและคะแนนจริงใจ</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    ระบบรีวิว 1-10 พร้อมระบบตอบกลับแลกเปลี่ยนความเห็น และรายงานสแปมเพื่อสร้างสังคมคอหนังที่มีคุณภาพ
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/80 hover:border-indigo-500/30 transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center text-2xl mb-4">
                    🏆
                </div>
                <h3 class="text-lg font-bold text-white mb-2">จัด Tier List ตามใจชอบ</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    สร้างกระดานจัดอันดับระดับ S, A, B, C, D พร้อมตั้งชื่อระดับสุดสร้างสรรค์ บันทึกและแชร์ให้เพื่อนดูได้ทันที
                </p>
            </div>

            <div class="p-6 rounded-2xl bg-slate-900/40 border border-slate-800/80 hover:border-emerald-500/30 transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl mb-4">
                    ⚡
                </div>
                <h3 class="text-lg font-bold text-white mb-2">เชื่อมต่อฐานข้อมูล TMDB</h3>
                <p class="text-sm text-slate-400 leading-relaxed">
                    ค้นหาและนำเข้าข้อมูลภาพยนตร์ โปสเตอร์ความละเอียดสูง และเรื่องย่ออย่างแม่นยำ พร้อมระบบป้องกันข้อมูลซ้ำซ้อน
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-800/80 py-8 bg-slate-950/80 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p>© 2026 CineReview Project. พัฒนาสำหรับวิชา Database & Web Application</p>
            <div class="flex items-center gap-4 text-slate-400">
                <a href="{{ route('leaderboard') }}" class="hover:text-white transition">Leaderboard</a>
                <span>•</span>
                <a href="{{ route('login') }}" class="hover:text-white transition">เข้าสู่ระบบ</a>
            </div>
        </div>
    </footer>

</body>
</html>
