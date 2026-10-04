<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('จัดการผู้กำกับภาพยนตร์') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- แสดงข้อความสำเร็จ --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- แสดง Error --}}
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- =========================
                     เพิ่มผู้กำกับ
                ========================== --}}
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    เพิ่มผู้กำกับ
                </h3>

                <form action="{{ route('admin.directors.store') }}"
                      method="POST"
                      class="mb-8 flex gap-4">
                    @csrf

                    <input
                        type="text"
                        name="name"
                        placeholder="ชื่อผู้กำกับ เช่น Christopher Nolan"
                        required
                        maxlength="255"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full max-w-md"
                    >

                    <button
                        type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition"
                    >
                        + เพิ่มผู้กำกับ
                    </button>
                </form>


                {{-- =========================
                     รายการผู้กำกับ
                ========================== --}}
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    รายชื่อผู้กำกับ
                </h3>

                <div class="relative overflow-x-auto rounded-lg border dark:border-gray-700">

                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    ID
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    ชื่อผู้กำกับ
                                </th>

                                <th scope="col" class="px-6 py-3">
                                    จัดการ
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($directors as $director)

                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

                                    {{-- ID --}}
                                    <td class="px-6 py-4">
                                        {{ $director->id }}
                                    </td>


                                    {{-- ชื่อผู้กำกับ --}}
                                    <td class="px-6 py-4">

                                        <form
                                            action="{{ route('admin.directors.update', $director->id) }}"
                                            method="POST"
                                            class="flex items-center gap-3"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="text"
                                                name="name"
                                                value="{{ $director->name }}"
                                                required
                                                maxlength="255"
                                                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full max-w-md"
                                            >

                                            <button
                                                type="submit"
                                                class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold"
                                            >
                                                ✏️ แก้ไข
                                            </button>
                                        </form>

                                    </td>


                                    {{-- ลบ --}}
                                    <td class="px-6 py-4">

                                        <form
                                            action="{{ route('admin.directors.destroy', $director->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('แน่ใจหรือไม่ที่จะลบผู้กำกับคนนี้?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-500 hover:text-red-700 font-bold"
                                            >
                                                🗑️ ลบ
                                            </button>
                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="3"
                                        class="px-6 py-8 text-center text-gray-500"
                                    >
                                        ยังไม่มีข้อมูลผู้กำกับ
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