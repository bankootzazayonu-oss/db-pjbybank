<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ==========================================
                 สรุปข้อมูลผู้ใช้
            ========================================== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- จำนวนรีวิว --}}
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        🎬 หนังที่รีวิว
                    </div>

                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $reviews->count() }}
                    </div>
                </div>

                {{-- จำนวน Tier List --}}
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        🏆 Tier List
                    </div>

                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $collections->count() }}
                    </div>
                </div>

                {{-- จำนวนหนังที่เสนอ --}}
                <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        📋 หนังที่เสนอ
                    </div>

                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $submittedMovies->count() }}
                    </div>
                </div>

            </div>


            {{-- ==========================================
                 หนังที่ฉันรีวิว
            ========================================== --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    🎬 หนังที่ฉันรีวิว
                </h3>

                @if($reviews->count() > 0)

                    <div class="space-y-3">

                        @foreach($reviews as $review)

                            <div class="flex items-center justify-between p-4 rounded-lg bg-gray-50 dark:bg-gray-700">

                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-white">
                                        {{ $review->activity->name ?? 'ไม่พบชื่อภาพยนตร์' }}
                                    </div>

                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        ⭐ {{ $review->rating }}/10
                                    </div>
                                </div>

                                @if($review->activity)
                                    <a
                                        href="{{ route('activities.show', $review->activity_id) }}"
                                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400"
                                    >
                                        ดูหนัง →
                                    </a>
                                @endif

                            </div>

                        @endforeach

                    </div>

                @else

                    <p class="text-gray-500 dark:text-gray-400">
                        คุณยังไม่มีรีวิว
                    </p>

                @endif

            </div>


            {{-- ==========================================
                 Tier List ของฉัน
            ========================================== --}}
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    🏆 Tier List ของฉัน
                </h3>

                @if($collections->count() > 0)

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        @foreach($collections as $collection)

                            <a
                                href="{{ route('collections.show', $collection->id) }}"
                                class="block p-4 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                            >

                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $collection->name }}
                                </div>

                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    เปิด Tier List →
                                </div>

                            </a>

                        @endforeach

                    </div>

                @else

                    <p class="text-gray-500 dark:text-gray-400">
                        คุณยังไม่มี Tier List
                    </p>

                @endif

            </div>


            {{-- ==========================================
                 หนังที่ฉันเสนอเข้าระบบ
            ========================================== --}}
          
<div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
        📋 หนังที่ฉันเสนอเข้าระบบ
    </h3>

    @if($submittedMovies->count() > 0)

        <div class="space-y-3">

            @foreach($submittedMovies as $movie)

                <div class="flex items-center justify-between gap-4 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">

                    <div>
                        <div class="font-semibold text-gray-900 dark:text-white">
                            {{ $movie->name }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            ปี {{ $movie->year }}
                        </div>
                    </div>

                    {{-- สถานะ --}}
                    @if($movie->status === 'approved')

                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 text-sm font-semibold whitespace-nowrap">
                            🟢 อนุมัติแล้ว
                        </span>

                    @elseif($movie->status === 'rejected')

                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 text-sm font-semibold whitespace-nowrap">
                            🔴 ปฏิเสธแล้ว
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300 text-sm font-semibold whitespace-nowrap">
                            🟡 รอตรวจสอบ
                        </span>

                    @endif

                </div>

            @endforeach

        </div>

    @else

        <p class="text-gray-500 dark:text-gray-400">
            คุณยังไม่มีภาพยนตร์ที่เสนอเข้าระบบ
        </p>

    @endif

</div>


            {{-- ==========================================
                 ตั้งค่า Profile เดิมของ Laravel
            ========================================== --}}

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>


            {{-- เปลี่ยนรหัสผ่าน --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>


            {{-- ลบบัญชี --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
