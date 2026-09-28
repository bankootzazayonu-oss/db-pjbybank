<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">🏆 10 อันดับภาพยนตร์ยอดเยี่ยม</h2>
    </x-slot>

    <div class="py-12 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <table class="w-full text-left text-gray-700 dark:text-gray-300">
                <thead class="bg-gray-100 dark:bg-gray-900 font-bold uppercase text-sm">
                    <tr>
                        <th class="px-6 py-4">อันดับ</th>
                        <th class="px-6 py-4">ภาพยนตร์</th>
                        <th class="px-6 py-4 text-center">คะแนนเฉลี่ย</th>
                        <th class="px-6 py-4 text-center">จำนวนรีวิว</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($topMovies as $index => $movie)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                            <td class="px-6 py-4 text-2xl font-black text-indigo-500">#{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('activities.show', $movie->id) }}" class="font-bold hover:text-indigo-400 hover:underline">
                                    {{ $movie->name }} ({{ $movie->year }})
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center text-yellow-500 font-bold text-lg">
                                ⭐ {{ number_format($movie->reviews_avg_rating, 1) }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $movie->reviews_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>