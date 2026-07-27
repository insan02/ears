<x-layout>
    <div x-data="{ showDeleteModal: false, deleteUrl: '' }" class="bg-gray-50 min-h-screen pb-20">

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
            <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden border border-gray-100 min-h-[400px] flex flex-col">

                {{-- Toolbar / Alert Area --}}
                <div class="p-4 md:p-6 border-b border-gray-100 bg-white flex flex-col md:flex-row gap-4 justify-between items-center relative z-30">

                    <!-- Search Box -->
                    <div class="relative w-full md:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027] transition-colors pointer-events-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>

                        <form action="{{ route('manajemen-media.index') }}" method="GET" class="w-full">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari judul berita..."
                                class="w-full py-3 pl-12 {{ request('search') ? 'pr-12' : 'pr-4' }} bg-gray-50 border border-gray-200 rounded-xl md:rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#e92027] focus:bg-white focus:border-transparent text-sm font-medium transition-all shadow-sm">
                        </form>

                        @if(request('search'))
                            <a href="{{ route('manajemen-media.index') }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-[#e92027] transition-colors" title="Reset Pencarian">
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

                {{-- Table Container --}}
                <div class="flex-grow overflow-x-auto w-full">
                    <table class="min-w-full w-full bg-white text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-28">Gambar</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[200px]">Judul</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-36">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider min-w-[300px]">Deskripsi</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-32">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($media as $item)
                                <tr class="hover:bg-red-50/30 transition duration-200 group">
                                    <td class="py-4 px-6 text-center">
                                        <div class="h-14 w-20 md:h-16 md:w-24 rounded-lg overflow-hidden shadow-sm border border-gray-200 mx-auto group-hover:border-red-200 transition-colors">
                                            <img src="{{ asset($item->gambar) }}" alt="{{ $item->judul }}"
                                                class="h-full w-full object-cover transform group-hover:scale-105 transition duration-500">
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
                                            <a href="{{ route('manajemen-media.edit', $item->id) }}"
                                                class="w-8 h-8 flex items-center justify-center bg-white text-amber-500 rounded-lg hover:bg-amber-50 transition shadow-sm border border-gray-200 hover:border-amber-300" title="Edit">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <button @click="showDeleteModal = true; deleteUrl = '{{ route('manajemen-media.destroy', $item->id) }}'"
                                                class="w-8 h-8 flex items-center justify-center bg-white text-[#e92027] rounded-lg hover:bg-red-50 transition shadow-sm border border-gray-200 hover:border-red-300" title="Hapus">
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

                {{-- Pagination --}}
                @if($media->hasPages())
                <div class="p-4 md:p-6 border-t border-gray-100 bg-gray-50">
                    {{ $media->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- Delete Modal Responsif --}}
        <div x-show="showDeleteModal" style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="showDeleteModal = false"
                x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                class="bg-white rounded-3xl w-full max-w-sm p-6 md:p-8 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#e92027]"></div>
                <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-[#e92027] shadow-sm animate-bounce">
                    <i class="fas fa-trash-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Hapus Berita?</h3>
                <p class="text-gray-500 mb-8 text-sm md:text-base leading-relaxed">Data berita dan gambar akan dihapus permanen dari sistem.</p>
                <div class="flex flex-col gap-3">
                    <form :action="deleteUrl" method="POST" class="w-full">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3.5 bg-[#e92027] text-white rounded-xl text-sm font-bold hover:bg-[#c41820] shadow-lg transform hover:-translate-y-0.5 transition">
                            Ya, Hapus Sekarang
                        </button>
                    </form>
                    <button @click="showDeleteModal = false" type="button"
                        class="w-full py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition">
                        Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layout>
