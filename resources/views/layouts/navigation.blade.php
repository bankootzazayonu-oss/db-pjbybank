<nav x-data="{ open: false }" class="bg-slate-950/90 backdrop-blur-md border-b border-slate-800/80 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-6 sm:flex items-center whitespace-nowrap overflow-x-auto">
                    {{-- เมนูสำหรับทุกคน --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        🍿 คลังภาพยนตร์
                    </x-nav-link>
                    
                    <x-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">
                        🏆 จัดอันดับ
                    </x-nav-link>

                    {{-- เมนูสำหรับ User ทั่วไป --}}
                    @if(Auth::user()->role !== 'admin')
                        <x-nav-link :href="route('activities.create')" :active="request()->routeIs('activities.create')">
                            ➕ เสนอหนังใหม่
                        </x-nav-link>

                        <x-nav-link :href="route('my.movies')" :active="request()->routeIs('my.movies')">
                            📂 หนังที่ฉันเสนอ
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('collections.index')" :active="request()->routeIs('collections.index')">
                        🎖️ จัดเทียร์ลิสต์
                    </x-nav-link>

                    <!-- เมนูเฉพาะ Admin -->
                    @if(Auth::user()->role === 'admin')
                        <div class="h-4 w-px bg-slate-800 mx-1"></div>

                        <x-nav-link :href="route('admin.movies.pending')" :active="request()->routeIs('admin.movies.pending')">
                            🛡️ รออนุมัติ
                        </x-nav-link>

                        <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">
                            🚩 จัดการรีพอร์ต
                        </x-nav-link>

                        <x-nav-link :href="route('admin.movies.search')" :active="request()->routeIs('admin.movies.search')">
                            🔍 นำเข้าหนัง TMDB
                        </x-nav-link>

                        <x-nav-link :href="route('admin.types.index')" :active="request()->routeIs('admin.types.index')">
                            📁 จัดการหมวดหมู่
                        </x-nav-link>

                        <x-nav-link :href="route('admin.platforms.index')" :active="request()->routeIs('admin.platforms.*')">
                            📺 จัดการแพลตฟอร์ม
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-slate-800 rounded-xl text-sm font-medium text-slate-300 bg-slate-900/80 hover:bg-slate-800 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <span class="w-2 h-2 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-amber-400' : 'bg-emerald-400' }}"></span>
                            <div>{{ Auth::user()->name }}</div>
                            @if(Auth::user()->role === 'admin')
                                <span class="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-400 border border-amber-500/30">Admin</span>
                            @endif

                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            👤 โปรไฟล์ของฉัน
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-rose-400 hover:text-rose-300">
                                🚪 ออกจากระบบ
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-b border-slate-800">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                🍿 คลังภาพยนตร์
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('leaderboard')" :active="request()->routeIs('leaderboard')">
                🏆 จัดอันดับ
            </x-responsive-nav-link>

            @if(Auth::user()->role !== 'admin')
                <x-responsive-nav-link :href="route('activities.create')" :active="request()->routeIs('activities.create')">
                    ➕ เสนอหนังใหม่
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('my.movies')" :active="request()->routeIs('my.movies')">
                    📂 หนังที่ฉันเสนอ
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('collections.index')" :active="request()->routeIs('collections.index')">
                🎖️ จัดเทียร์ลิสต์
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <div class="pt-2 border-t border-slate-800">
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider px-3 py-1 font-bold">Admin Section</p>
                    <x-responsive-nav-link :href="route('admin.movies.pending')" :active="request()->routeIs('admin.movies.pending')">
                        🛡️ รออนุมัติ
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')">
                        🚩 จัดการรีพอร์ต
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.movies.search')" :active="request()->routeIs('admin.movies.search')">
                        🔍 นำเข้าหนัง TMDB
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.types.index')" :active="request()->routeIs('admin.types.index')">
                        📁 จัดการหมวดหมู่
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.platforms.index')" :active="request()->routeIs('admin.platforms.*')">
                        📺 จัดการแพลตฟอร์ม
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-slate-800 px-4">
            <div class="flex items-center gap-3 px-3">
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    👤 โปรไฟล์ของฉัน
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-rose-400">
                        🚪 ออกจากระบบ
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
