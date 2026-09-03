<x-layout>
    <!-- Tambahkan isDeleting: false -->
    <div x-data="{
        showDeleteModal: false,
        deleteUrl: '',
        isDeleting: false,
        showEditModal: false,
        isEdit: false,
        isSaving: false,
        modalTitle: '',
        formAction: '',
        formData: {
            nama_unit: '',
            keterangan: ''
        }
    }" class="bg-gray-50 min-h-screen pb-20">

        {{-- Header Section --}}
        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-24 md:pb-32 pt-12 md:pt-16 px-4 md:px-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 mb-8 rounded-b-[2rem] md:rounded-b-[3rem] shadow-2xl relative overflow-hidden">
             <!-- Polygon Pattern Overlay -->
             <div class="absolute inset-0 z-0 opacity-40">
                  <svg class="absolute w-full h-full" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                     <defs>
                         <linearGradient id="polyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                             <stop offset="0%" style="stop-color:#580000;stop-opacity:0.3" />
                             <stop offset="100%" style="stop-color:#000000;stop-opacity:0.4" />
                         </linearGradient>
                     </defs>
                     <path fill="url(#polyGrad)" d="M0 0 L1000 0 L1000 500 L0 300 Z" />
                     <path fill="#000000" opacity="0.1" d="M-100 0 L500 0 L200 600 L-100 400 Z" />
                 </svg>
             </div>

             <!-- Ornamental Icon -->
             <div class="absolute top-0 right-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 z-0 pointer-events-none mix-blend-overlay">
                 <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0L24 12L12 24L0 12L12 0Z" /></svg>
             </div>

             <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-center md:text-left relative z-10 gap-6">
                <div>
                     <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Manajemen Unit</h2>
                     <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Kelola daftar unit kerja dan departemen perusahaan.</p>
                </div>
                <div>
                    <button @click="
                        showEditModal = true;
                        isEdit = false;
                        modalTitle = 'Tambah Unit Baru';
                        formAction = '{{ route('manajemen-unit.store') }}';
                        formData.nama_unit = '';
                        formData.keterangan = '';
                    "
                        class="group bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-full font-bold shadow-xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40">
                        <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                             <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm md:text-base">TAMBAH UNIT</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Main Container --}}
        <div class="max-w-7xl mx-auto px-4 -mt-12 md:-mt-20 relative z-20 mb-12">

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-700 p-4 rounded-r shadow-sm flex items-start">
                    <div class="flex-shrink-0"><i class="fas fa-exclamation-circle text-red-700 mt-0.5"></i></div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Tindakan Ditolak!</h3>
                        <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden border border-gray-100 min-h-[400px] flex flex-col">

                {{-- Toolbar & Filters --}}
                <div class="p-4 md:p-6 border-b border-gray-100 bg-white flex flex-col md:flex-row gap-4 justify-between items-center relative z-30">
                     <!-- Search Bar -->
                     <div class="relative w-full md:w-96 group">
                          <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027] transition-colors pointer-events-none">
                             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                          </span>
                          <form action="{{ route('manajemen-unit.index') }}" method="GET" class="w-full">
                             <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama unit kerja..." maxlength="50" class="w-full py-3 pl-12 {{ request('search') ? 'pr-12' : 'pr-4' }} bg-gray-50 border border-gray-200 rounded-xl md:rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#e92027] focus:bg-white focus:border-transparent text-sm font-medium transition-all shadow-sm">
                          </form>
                          <!-- Tombol Reset Pencarian -->
                          @if(request('search'))
                              <a href="{{ route('manajemen-unit.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-[#e92027] transition-colors" title="Reset Pencarian">
                                  <svg class="w-5 h-5 bg-gray-100 hover:bg-red-100 rounded-full p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                              </a>
                          @endif
                     </div>

                     @if(session('success'))
                        <div class="flex items-center gap-2 bg-green-50 text-green-700 px-4 py-2 rounded-xl text-sm font-bold border border-green-200 w-full md:w-auto justify-between md:justify-start">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="hover:text-green-900"><i class="fas fa-times"></i></button>
                        </div>
                     @endif
                </div>

                {{-- TAMPILAN LAPTOP: TABEL --}}
                <div class="hidden md:block flex-grow overflow-x-auto w-full">
                    <table class="min-w-full w-full bg-white text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-14 text-center">No</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[200px]">Nama Unit</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[300px]">Keterangan</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($units as $index => $unit)
                                <tr class="hover:bg-red-50/30 transition duration-150 group">
                                    <td class="px-6 py-4 text-gray-500 text-center text-xs font-bold">
                                        {{ $units->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 font-bold text-sm">{{ $unit->nama_unit }}</td>
                                    <td class="px-6 py-4 text-gray-600 text-xs whitespace-normal line-clamp-2">
                                        {{ $unit->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <!-- Tombol Edit yang Diperbaiki -->
                                            <button
                                                data-nama="{{ $unit->nama_unit }}"
                                                data-keterangan="{{ $unit->keterangan }}"
                                                @click="
                                                    showEditModal = true;
                                                    isEdit = true;
                                                    modalTitle = 'Edit Unit';
                                                    formAction = '{{ route('manajemen-unit.update', $unit->id) }}';
                                                    formData.nama_unit = $event.currentTarget.dataset.nama;
                                                    formData.keterangan = $event.currentTarget.dataset.keterangan;
                                                "
                                                class="w-8 h-8 flex items-center justify-center bg-white text-amber-500 rounded-lg hover:bg-amber-50 transition shadow-sm border border-gray-200 hover:border-amber-300" title="Edit">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>

                                            <!-- Reset isDeleting ke false saat membuka modal -->
                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('manajemen-unit.destroy', $unit->id) }}'; isDeleting = false"
                                                class="w-8 h-8 flex items-center justify-center bg-white text-[#e92027] rounded-lg hover:bg-red-50 transition shadow-sm border border-gray-200 hover:border-red-300" title="Hapus">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/50 text-sm">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fas fa-building text-3xl mb-2 text-gray-300"></i>
                                            Tidak ada data unit kerja ditemukan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- TAMPILAN HP: CARD LIST --}}
                <div class="md:hidden flex flex-col p-4 gap-4 bg-gray-50">
                    @forelse($units as $unit)
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative">
                            <div class="mb-3">
                                <h3 class="font-bold text-gray-800 text-base leading-tight">{{ $unit->nama_unit }}</h3>
                                <p class="text-xs text-gray-500 mt-2 line-clamp-3 leading-relaxed">{{ $unit->keterangan ?? 'Tidak ada keterangan tambahan.' }}</p>
                            </div>

                            {{-- Action Buttons for Mobile --}}
                            <div class="flex gap-2 pt-3 border-t border-gray-50">
                                <!-- Tombol Edit Mobile yang Diperbaiki -->
                                <button
                                    data-nama="{{ $unit->nama_unit }}"
                                    data-keterangan="{{ $unit->keterangan }}"
                                    @click="
                                        showEditModal = true;
                                        isEdit = true;
                                        modalTitle = 'Edit Unit';
                                        formAction = '{{ route('manajemen-unit.update', $unit->id) }}';
                                        formData.nama_unit = $event.currentTarget.dataset.nama;
                                        formData.keterangan = $event.currentTarget.dataset.keterangan;
                                    "
                                    class="flex-1 py-2 bg-amber-50 text-amber-600 text-xs font-bold rounded-xl border border-amber-100 hover:bg-amber-100 flex items-center justify-center gap-1.5 transition">
                                    <i class="fas fa-pen"></i> Edit
                                </button>

                                <!-- Reset isDeleting ke false saat membuka modal -->
                                <button @click="showDeleteModal = true; deleteUrl = '{{ route('manajemen-unit.destroy', $unit->id) }}'; isDeleting = false" class="flex-1 py-2 bg-red-50 text-[#e92027] text-xs font-bold rounded-xl border border-red-100 hover:bg-red-100 flex items-center justify-center gap-1.5 transition">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 italic text-sm">
                            <i class="fas fa-building text-2xl mb-2 text-gray-300 block"></i>
                            Tidak ada data unit kerja ditemukan.
                        </div>
                    @endforelse
                </div>

                {{-- Pagination Custom Berangka (Tampil di Laptop & HP) --}}
                @if ($units->hasPages())
                <div class="p-4 md:p-6 border-t border-gray-100 bg-white md:bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">

                    <!-- Info Data -->
                    <div class="text-xs md:text-sm text-gray-500 font-medium text-center md:text-left">
                        Menampilkan <span class="font-bold text-gray-800">{{ $units->firstItem() }}</span> -
                        <span class="font-bold text-gray-800">{{ $units->lastItem() }}</span>
                        dari <span class="font-bold text-gray-800">{{ $units->total() }}</span> data
                    </div>

                    <!-- Kotak Angka Pagination -->
                    <div class="flex flex-wrap items-center justify-center gap-1.5">

                        {{-- Tombol Previous --}}
                        @if ($units->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $units->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-[#e92027] hover:bg-red-50 hover:border-red-200 transition shadow-sm">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </a>
                        @endif

                        {{-- Logika Angka Pagination --}}
                        @php
                            $links = $units->linkCollection()->toArray();
                            array_shift($links);
                            array_pop($links);
                        @endphp

                        @foreach ($links as $link)
                            @if ($link['url'] == null)
                                <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-sm font-bold">{{ $link['label'] }}</span>
                            @elseif ($link['active'])
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#e92027] text-white font-bold text-sm shadow-md">{{ $link['label'] }}</span>
                            @else
                                <a href="{{ $link['url'] }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-[#e92027] transition text-sm font-bold shadow-sm">{{ $link['label'] }}</a>
                            @endif
                        @endforeach

                        {{-- Tombol Next --}}
                        @if ($units->hasMorePages())
                            <a href="{{ $units->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-[#e92027] hover:bg-red-50 hover:border-red-200 transition shadow-sm">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </span>
                        @endif

                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Add/Edit Modal Responsif --}}
        <div x-show="showEditModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="showEditModal = false"
                x-show="showEditModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl w-full max-w-md p-6 md:p-8 relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#e92027]"></div>

                <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-3">
                    <h3 class="text-lg md:text-xl font-extrabold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-building text-[#e92027]"></i> <span x-text="modalTitle"></span>
                    </h3>
                    <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-red-500 transition"><i class="fas fa-times text-lg"></i></button>
                </div>

                <form :action="formAction" method="POST" class="flex flex-col gap-5" hx-disable @submit="isSaving = true">
                    @csrf

                    <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Unit Kerja <span class="text-red-600">*</span></label>
                        <input type="text" name="nama_unit" x-model="formData.nama_unit" required maxlength="50"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" x-model="formData.keterangan" rows="3" maxlength="50" placeholder="Tuliskan deskripsi unit kerja..."
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm resize-none"></textarea>
                    </div>

                    <div class="flex flex-col-reverse md:flex-row gap-3 mt-4 pt-4 border-t border-gray-100">
                        <button type="button" @click="showEditModal = false" :disabled="isSaving"
                            class="transition" :class="isSaving ? 'text-gray-300 cursor-not-allowed' : 'text-gray-400 hover:text-red-500'">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                                                <button type="submit" :disabled="isSaving"
                            class="w-full md:w-auto flex-1 py-3.5 bg-[#e92027] text-white rounded-xl text-sm font-bold shadow-md transition flex justify-center items-center gap-2"
                            :class="isSaving ? 'opacity-70 cursor-wait' : 'hover:bg-[#c41820] transform hover:-translate-y-0.5'">

                            <!-- Teks saat kondisi normal -->
                            <span x-show="!isSaving">Simpan Data</span>

                            <!-- Teks & Animasi saat kondisi loading/menyimpan -->
                            <span x-show="isSaving" style="display: none;">
                                <i class="fas fa-circle-notch fa-spin"></i> Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
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
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Hapus Unit Kerja?</h3>
                <p class="text-gray-500 mb-8 text-sm md:text-base leading-relaxed">Unit ini akan dihapus permanen dari sistem.</p>
                <div class="flex flex-col gap-3">

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
