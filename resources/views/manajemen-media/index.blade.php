<x-layout>
    <!-- Tambahkan variabel isDeleting: false di sini -->
    <div x-data="{ showDeleteModal: false, deleteUrl: '', isDeleting: false }" class="bg-gray-50 min-h-screen pb-20">

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

             <div class="absolute top-0 right-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 z-0 pointer-events-none mix-blend-overlay">
                 <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0L24 12L12 24L0 12L12 0Z" /></svg>
             </div>

             <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-center md:text-left relative z-10 gap-6">
                <div>
                     <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Manajemen Media</h2>
                     <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Kelola konten media dan informasi yang tampil di landing page.</p>
                </div>
                <div>
                    <a href="{{ route('manajemen-media.create') }}"
                        class="group bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-full font-bold shadow-xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40">
                        <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                             <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm md:text-base">TAMBAH BERITA</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Container --}}
        <div class="max-w-7xl mx-auto px-4 -mt-12 md:-mt-20 relative z-20 mb-12">

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                    <div class="bg-green-100 p-2 rounded-full text-green-600"><i class="fas fa-check"></i></div>
                    <p class="text-sm font-bold text-green-800 flex-1">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden border border-gray-100 min-h-[400px] flex flex-col">

                {{-- Toolbar / Alert Area --}}
                <div class="p-4 md:p-6 border-b border-gray-100 bg-white flex flex-col md:flex-row gap-4 justify-between items-center relative z-30">
                    <div class="relative w-full md:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027] transition-colors pointer-events-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <form action="{{ route('manajemen-media.index') }}" method="GET" class="w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul berita..." class="w-full py-3 pl-12 {{ request('search') ? 'pr-12' : 'pr-4' }} bg-gray-50 border border-gray-200 rounded-xl md:rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#e92027] focus:bg-white focus:border-transparent text-sm font-medium transition-all shadow-sm">
                        </form>
                        @if(request('search'))
                            <a href="{{ route('manajemen-media.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-[#e92027] transition-colors" title="Reset Pencarian">
                                <svg class="w-5 h-5 bg-gray-100 hover:bg-red-100 rounded-full p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- TAMPILAN MOBILE: CARD LIST --}}
                <div class="md:hidden flex flex-col p-4 gap-4 bg-gray-50">
                    @forelse($media as $item)
                        @php
                            $gambarList = json_decode($item->gambar, true);
                            if(!is_array($gambarList)) {
                                $gambarList = $item->gambar ? [$item->gambar] : [];
                            }
                        @endphp
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative">
                            <!-- Mobile Alpine Carousel (Proporsi Utuh) -->
                            <div x-data="{ active: 0, imgs: {{ Js::from($gambarList) }} }" class="relative h-48 w-full rounded-xl overflow-hidden shadow-sm border border-gray-800 mb-4 bg-slate-900 flex items-center justify-center">
                                <template x-for="(img, idx) in imgs" :key="idx">
                                    <img x-show="active === idx"
                                         :src="'{{ asset('storage') }}/' + img"
                                         class="max-h-full max-w-full w-auto h-auto object-contain select-none">
                                </template>
                                <template x-if="imgs.length > 1">
                                    <div>
                                        <button @click="active = active === 0 ? imgs.length - 1 : active - 1" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full p-1.5 hover:bg-[#e92027] transition z-10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                                        </button>
                                        <button @click="active = active === imgs.length - 1 ? 0 : active + 1" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full p-1.5 hover:bg-[#e92027] transition z-10">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                        </button>
                                        <div class="absolute bottom-2 right-2 bg-black/70 text-white text-[10px] font-bold px-2 py-0.5 rounded z-10" x-text="(active + 1) + '/' + imgs.length"></div>
                                    </div>
                                </template>
                            </div>

                            <div class="mb-3">
                                <span class="inline-block bg-gray-100 text-gray-700 py-1 px-2.5 rounded-md text-[10px] font-bold border border-gray-200 mb-2">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </span>
                                <h3 class="font-extrabold text-gray-800 text-base leading-tight">{{ $item->judul }}</h3>
                                <p class="text-xs text-gray-500 mt-2 line-clamp-3 leading-relaxed">{{ $item->deskripsi }}</p>
                            </div>

                            {{-- Action Buttons for Mobile --}}
                            <div class="flex gap-2 pt-3 border-t border-gray-50">
                                <a href="{{ route('manajemen-media.edit', $item->id) }}" class="flex-1 py-2 bg-amber-50 text-amber-600 text-xs font-bold rounded-xl border border-amber-100 hover:bg-amber-100 flex items-center justify-center gap-1.5 transition">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <button @click="showDeleteModal = true; deleteUrl = '{{ route('manajemen-media.destroy', $item->id) }}'; isDeleting = false" class="flex-1 py-2 bg-red-50 text-[#e92027] text-xs font-bold rounded-xl border border-red-100 hover:bg-red-100 flex items-center justify-center gap-1.5 transition">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 italic text-sm">
                            <i class="fas fa-newspaper text-2xl mb-2 text-gray-300 block"></i>
                            Belum ada berita ditemukan.
                        </div>
                    @endforelse
                </div>

                {{-- TAMPILAN DESKTOP: TABEL --}}
                <div class="hidden md:block flex-grow overflow-x-auto w-full pb-4">
                    <table class="min-w-full w-full bg-white text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-40">Galeri</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[200px]">Judul</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-36">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[300px]">Deskripsi</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($media as $item)
                                @php
                                    $gambarList = json_decode($item->gambar, true);
                                    if(!is_array($gambarList)) {
                                        $gambarList = $item->gambar ? [$item->gambar] : [];
                                    }
                                @endphp
                                <tr class="hover:bg-red-50/30 transition duration-200 group">
                                    <td class="py-4 px-6 text-center">
                                        <!-- ALPINE MINI CAROUSEL TABEL (Proporsi Utuh) -->
                                        <div x-data="{ active: 0, imgs: {{ Js::from($gambarList) }} }" class="relative h-20 w-32 md:h-24 md:w-36 rounded-xl overflow-hidden shadow-sm border border-gray-800 mx-auto bg-slate-900 flex items-center justify-center">
                                            <!-- Render Images Utuh -->
                                            <template x-for="(img, idx) in imgs" :key="idx">
                                                <img x-show="active === idx"
                                                     :src="'{{ asset('storage') }}/' + img"
                                                     class="max-h-full max-w-full w-auto h-auto object-contain select-none">
                                            </template>

                                            <!-- Panah Kiri Kanan -->
                                            <template x-if="imgs.length > 1">
                                                <div>
                                                    <button @click="active = active === 0 ? imgs.length - 1 : active - 1" class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full p-1 hover:bg-[#e92027] transition z-10">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                                                    </button>
                                                    <button @click="active = active === imgs.length - 1 ? 0 : active + 1" class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/50 text-white rounded-full p-1 hover:bg-[#e92027] transition z-10">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                                    </button>
                                                    <div class="absolute bottom-1 right-2 bg-black/70 text-white text-[9px] font-semibold px-1.5 rounded z-10" x-text="(active + 1) + '/' + imgs.length"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-gray-800 text-sm whitespace-normal line-clamp-2">
                                        {{ $item->judul }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 text-center">
                                        <span class="bg-gray-100 text-gray-700 py-1 px-3 rounded-full text-[11px] font-semibold border border-gray-200">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 text-xs whitespace-normal line-clamp-2 max-w-xs">
                                        {{ $item->deskripsi }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('manajemen-media.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-white text-amber-500 rounded-lg border border-gray-200 hover:border-amber-300">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('manajemen-media.destroy', $item->id) }}'; isDeleting = false" class="w-8 h-8 flex items-center justify-center bg-white text-[#e92027] rounded-lg border border-gray-200 hover:border-red-300">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/50 text-sm">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fas fa-newspaper text-3xl mb-2 text-gray-300"></i>
                                            Belum ada data berita ditemukan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Custom --}}
                @if($media->hasPages())
                <div class="p-4 md:p-6 border-t border-gray-100 bg-white md:bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-xs md:text-sm text-gray-500 font-medium text-center md:text-left">
                        Menampilkan <span class="font-bold text-gray-800">{{ $media->firstItem() }}</span> -
                        <span class="font-bold text-gray-800">{{ $media->lastItem() }}</span>
                        dari <span class="font-bold text-gray-800">{{ $media->total() }}</span> berita
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                        @if ($media->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed"><i class="fas fa-chevron-left text-xs"></i></span>
                        @else
                            <a href="{{ $media->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-[#e92027] hover:bg-red-50 hover:border-red-200 transition shadow-sm"><i class="fas fa-chevron-left text-xs"></i></a>
                        @endif

                        @php
                            $links = $media->linkCollection()->toArray();
                            array_shift($links); array_pop($links);
                        @endphp
                        @foreach ($links as $link)
                            @if ($link['url'] == null)
                                <span class="w-8 h-8 flex items-center justify-center text-gray-400 text-sm font-bold">{{ $link['label'] }}</span>
                            @elseif ($link['active'])
                                <span class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#e92027] text-white font-bold text-sm shadow-md">{{ $link['label'] }}</span>
                            @else
                                <a href="{{ link['url'] }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-[#e92027] transition text-sm font-bold shadow-sm">{{ $link['label'] }}</a>
                            @endif
                        @endforeach

                        @if ($media->hasMorePages())
                            <a href="{{ $media->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-[#e92027] hover:bg-red-50 hover:border-red-200 transition shadow-sm"><i class="fas fa-chevron-right text-xs"></i></a>
                        @else
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed"><i class="fas fa-chevron-right text-xs"></i></span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Delete Modal Responsif --}}
        <div x-show="showDeleteModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="!isDeleting && (showDeleteModal = false)"
                x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl w-full max-w-sm p-6 md:p-8 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#e92027]"></div>
                <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-[#e92027] shadow-sm animate-bounce">
                    <i class="fas fa-trash-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Hapus Berita?</h3>
                <p class="text-gray-500 mb-8 text-sm md:text-base leading-relaxed">Data berita dan gambar akan dihapus permanen dari sistem.</p>
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
