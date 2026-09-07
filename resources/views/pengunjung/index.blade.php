<x-layout>
    <style>
        /* Pola dinding halus */
        .wall-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>

    <div x-data="{ showDeleteModal: false, deleteUrl: '' }" class="min-h-screen wall-pattern p-4 md:p-8">

        <div class="max-w-7xl mx-auto bg-linear-to-r from-red-900 to-red-800 px-6 py-6 rounded-xl shadow-lg mb-8 flex flex-col md:flex-row items-center justify-between gap-4 relative overflow-hidden">

            <div class="relative z-10 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-wide">
                    Dinding Tamu
                </h1>
                <p class="text-red-100 text-sm mt-1">
                    Jejak kenangan dari para pengunjung Arsip Semen Padang.
                </p>
            </div>

            <a href="{{ route('pengunjung.create') }}" class="relative z-10 bg-white text-red-900 font-bold px-6 py-2.5 rounded-full shadow-md hover:bg-red-50 transition transform hover:-translate-y-0.5 flex items-center gap-2 group">
                <svg class="w-5 h-5 text-red-700 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Isi Buku Tamu</span>
            </a>

            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl"></div>
        </div>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="max-w-7xl mx-auto mb-8 bg-white border-l-4 border-red-800 text-gray-700 p-4 rounded-r shadow-md flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 p-1 rounded-full text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-red-800">&times;</button>
        </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">

            @forelse($pengunjung as $index => $tamu)
                @php
                    // Palet Warna Kalem (Putih, Pink, Krem Merah, Abu)
                    $colors = ['bg-white', 'bg-pink-50', 'bg-red-50', 'bg-stone-50'];
                    $rotations = ['rotate-1', '-rotate-1', 'rotate-2', '-rotate-2', 'rotate-0'];

                    $randomColor = $colors[$index % 4];
                    $randomRotate = $rotations[$index % 5];
                @endphp

                <div class="relative group {{ $randomRotate }} hover:rotate-0 hover:scale-105 transition-all duration-300 ease-out">
                    <div class="{{ $randomColor }} p-6 pt-8 shadow-md hover:shadow-xl h-full flex flex-col relative overflow-hidden transition-colors border border-gray-100/50 rounded-xl">

                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -mt-2">
                            <div class="w-4 h-4 rounded-full bg-red-800 shadow-md border-2 border-red-900 z-20 relative"></div>
                            <div class="w-1 h-8 bg-gray-400 absolute top-2 left-1/2 -translate-x-1/2 -z-10 opacity-30"></div>
                        </div>

                        <div class="flex-1 text-gray-700 leading-relaxed text-base font-medium mb-6 wrap-break-word tracking-wide">
                            "{{ $tamu->pesan_kesan }}"
                        </div>

                        <div class="border-t border-gray-200/60 pt-3 mt-auto flex justify-between items-end">
                            <div class="overflow-hidden">
                                <h3 class="font-bold text-red-900 text-sm uppercase tracking-wide truncate pr-2">{{ $tamu->nama }}</h3>
                                <div class="text-[11px] text-gray-500 font-medium truncate">
                                    {{ $tamu->asal_instansi }}
                                </div>
                            </div>

                            <button
                                @click="showDeleteModal = true; deleteUrl = '{{ route('pengunjung.destroy', $tamu->id) }}'"
                                class="text-gray-300 hover:text-red-600 transition p-1 opacity-0 group-hover:opacity-100"
                                title="Hapus Pesan">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>

                        <div class="absolute top-3 right-3 text-[10px] text-gray-400 font-mono">
                            {{ $tamu->created_at->format('d/m/y') }}
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-block p-6 rounded-full bg-white shadow-sm mb-4 border border-gray-100">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-600">Belum ada pesan</h3>
                    <p class="text-gray-400 mt-2">Jadilah yang pertama menulis di sini.</p>
                </div>
            @endforelse

        </div>

        <div x-show="showDeleteModal" style="display: none;"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
             x-transition.opacity>
            <div @click.away="showDeleteModal = false"
                 class="bg-white rounded-xl shadow-2xl w-full max-w-sm overflow-hidden transform transition-all scale-100">
                <div class="bg-red-50 p-6 flex flex-col items-center text-center border-b border-red-100">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-600 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Lepas Pesan?</h3>
                    <p class="text-sm text-gray-500 mt-1">Pesan ini akan dihapus permanen dari dinding.</p>
                </div>
                <div class="p-4 bg-white flex justify-center gap-3">
                    <button @click="showDeleteModal = false" class="px-5 py-2.5 bg-white text-gray-700 text-sm font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-5 py-2.5 bg-red-800 text-white text-sm font-bold rounded-lg hover:bg-red-900 shadow-md transition transform hover:scale-105">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-layout>
