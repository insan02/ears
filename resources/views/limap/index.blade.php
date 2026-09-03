<x-layout>
    <!-- Tambahkan Wrapper Alpine.js di sini -->
    <div x-data="{ showDeleteModal: false, deleteUrl: '', isDeleting: false }">

        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-6">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Daftar 5P</h2>
                    <p class="text-red-50 text-sm md:text-base font-light opacity-95">Informasi visual pemilahan, penataan, pembersihan, penjagaan, dan pendisiplinan.</p>
                </div>
                <div class="flex flex-wrap gap-2 justify-center">
                    @if(Auth::check() && Auth::user()->role == 'admin')
                        <a href="{{ route('limap.create') }}" class="bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-xl font-extrabold shadow-xl flex items-center gap-2 transition">
                            <i class="fas fa-plus-circle"></i> Tambah Data 5P
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 mb-12">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <div class="bg-green-100 p-2 rounded-full text-green-600"><i class="fas fa-check"></i></div>
                    <p class="text-sm font-bold text-green-800 flex-1">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <!-- TAMPILAN MOBILE (CARDS KHUSUS HP) -->
            <div class="block lg:hidden space-y-4">
                @forelse($data as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden group">
                        <div class="mb-3 relative z-10">
                            <span class="text-[10px] font-bold text-[#e92027] uppercase tracking-wider bg-red-50 px-2 py-1 rounded border border-red-100">PIC Area</span>
                            <h3 class="font-extrabold text-lg text-gray-800 mt-1">{{ $item->pic ?: 'Belum Ditentukan' }}</h3>
                        </div>
                        <div class="text-xs text-gray-500 mb-4 relative z-10 leading-relaxed">
                            <b>Status:</b> Galeri foto dan dokumen PDF Kaizen tersedia.
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-100 relative z-10">
                            <a href="{{ route('limap.show', $item->id) }}" class="text-xs font-bold text-[#e92027] hover:text-[#c41820] flex items-center gap-1">Detail <i class="fas fa-arrow-right"></i></a>
                            @if(Auth::check() && Auth::user()->role == 'admin')
                                <div class="flex gap-2">
                                    <a href="{{ route('limap.edit', $item->id) }}" class="w-7 h-7 flex items-center justify-center bg-gray-50 text-amber-500 rounded-md border border-gray-200"><i class="fas fa-pen text-[10px]"></i></a>
                                    <!-- Tombol Hapus Mobile (Panggil Alpine Modal) -->
                                    <button @click="showDeleteModal = true; deleteUrl = '{{ route('limap.destroy', $item->id) }}'; isDeleting = false" class="w-7 h-7 flex items-center justify-center bg-gray-50 text-[#e92027] rounded-md border border-gray-200 hover:bg-red-50">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center bg-white rounded-2xl border border-gray-100"><i class="fas fa-flask text-3xl mb-3 text-gray-300"></i><p class="text-sm text-gray-400 font-medium">Belum ada data 5P.</p></div>
                @endforelse
            </div>

            <!-- TAMPILAN DESKTOP (TABEL FIT SCREEN LAPTOP) -->
            <div class="hidden lg:block bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse table-fixed">
                    <thead>
                        <tr class="bg-[#e92027] text-white uppercase tracking-wider text-xs">
                            <th class="py-4 px-4 font-bold text-center w-[10%] border-r border-red-900/20">No</th>
                            <th class="py-4 px-6 font-bold w-[35%] border-r border-red-900/20">PIC Area</th>
                            <th class="py-4 px-6 font-bold w-[30%] border-r border-red-900/20">Keterangan Data</th>
                            <th class="py-4 px-4 font-bold text-center w-[25%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($data as $index => $item)
                            <tr class="hover:bg-red-50/40 transition duration-200 group">
                                <td class="py-4 px-4 text-center font-bold text-gray-500 border-r border-gray-100 align-middle">{{ $index + 1 }}</td>
                                <td class="py-4 px-6 border-r border-gray-100 align-middle">
                                    <div class="font-extrabold text-gray-900">{{ $item->pic ?: 'Belum Ditentukan' }}</div>
                                </td>
                                <td class="py-4 px-6 border-r border-gray-100 align-middle">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                        <i class="fas fa-images mr-1.5"></i> Galeri & PDF Aktif
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center align-middle">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('limap.show', $item->id) }}" class="px-4 py-2 bg-red-50 text-[#e92027] font-bold rounded-lg border border-red-200 hover:bg-[#e92027] hover:text-white transition flex items-center gap-2 text-xs shadow-sm">
                                            <i class="fas fa-external-link-alt"></i> Detail
                                        </a>

                                        @if(Auth::check() && Auth::user()->role == 'admin')
                                            <a href="{{ route('limap.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-white text-amber-500 rounded-lg hover:bg-amber-50 border border-gray-200 transition shadow-sm" title="Edit">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <!-- Tombol Hapus Desktop (Panggil Alpine Modal) -->
                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('limap.destroy', $item->id) }}'; isDeleting = false" class="w-8 h-8 flex items-center justify-center bg-white text-[#e92027] rounded-lg hover:bg-red-50 border border-gray-200 transition shadow-sm" title="Hapus">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-16 text-center bg-gray-50/50">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-flask text-4xl mb-3 text-gray-300"></i>
                                        <span class="text-gray-400 font-medium">Belum ada data 5P yang ditambahkan.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Delete Modal Responsif (Dengan Animasi Menghapus) --}}
        <div x-show="showDeleteModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="!isDeleting && (showDeleteModal = false)"
                x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl w-full max-w-sm p-6 md:p-8 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#e92027]"></div>
                <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-[#e92027] shadow-sm animate-bounce">
                    <i class="fas fa-trash-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Hapus Data 5P?</h3>
                <p class="text-gray-500 mb-8 text-sm md:text-base leading-relaxed">Semua data, foto galeri, dan PDF Kaizen di data ini akan dihapus secara permanen!</p>
                <div class="flex flex-col gap-3">

                    <!-- Form Delete (Terlindungi dari HTMX & Menjalankan Animasi) -->
                    <form :action="deleteUrl" method="POST" class="w-full" hx-disable @submit="isDeleting = true">
                        @csrf @method('DELETE')

                        <button type="submit" :disabled="isDeleting"
                            class="w-full py-3.5 bg-[#e92027] text-white rounded-xl text-sm font-bold shadow-lg transition flex justify-center items-center gap-2"
                            :class="isDeleting ? 'opacity-70 cursor-wait' : 'hover:bg-[#c41820] transform hover:-translate-y-0.5'">

                            <span x-show="!isDeleting">Ya, Hapus Sekarang</span>
                            <span x-show="isDeleting" style="display: none;">
                                <i class="fas fa-circle-notch fa-spin"></i> Menghapus...
                            </span>
                        </button>
                    </form>

                    <button @click="showDeleteModal = false" type="button" :disabled="isDeleting"
                        class="w-full py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 transition"
                        :class="isDeleting ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 hover:text-gray-800'">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-layout>
