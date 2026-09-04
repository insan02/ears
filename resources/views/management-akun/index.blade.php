<x-layout>
    <!-- Tambahkan isDeleting: false -->
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

             <!-- Ornamental Icon -->
             <div class="absolute top-0 right-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 z-0 pointer-events-none mix-blend-overlay">
                 <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0L24 12L12 24L0 12L12 0Z" /></svg>
             </div>

             <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-center md:text-left relative z-10 gap-6">
                <div>
                     <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Manajemen Akun</h2>
                     <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Kelola daftar pengguna sistem dan hak akses.</p>
                </div>
                <div>
                    <a href="{{ route('management-akun.create') }}"
                        class="group bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-full font-bold shadow-xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40">
                        <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                             <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-sm md:text-base">TAMBAH PENGGUNA</span>
                    </a>
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
                     <!-- Search -->
                    <div class="relative w-full md:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027] transition-colors pointer-events-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>

                        <form action="{{ route('management-akun.index') }}" method="GET" class="w-full">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama, email, role..."
                                class="w-full py-3 pl-12 {{ request('search') ? 'pr-12' : 'pr-4' }} bg-gray-50 border border-gray-200 rounded-xl md:rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#e92027] focus:bg-white focus:border-transparent text-sm font-medium transition-all shadow-sm">
                        </form>

                        @if(request('search'))
                            <a href="{{ route('management-akun.index') }}"
                               class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-[#e92027] transition-colors" title="Reset Pencarian">
                                <svg class="w-5 h-5 bg-gray-100 hover:bg-red-100 rounded-full p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
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
                <div class="hidden md:block flex-grow overflow-x-auto">
                    <table class="min-w-full w-full bg-white text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 border-b border-gray-200">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-14 text-center">No</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama Pengguna</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Role</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($users as $index => $user)
                                <tr class="hover:bg-red-50/30 transition duration-150 {{ !$user->is_active ? 'opacity-70 bg-gray-50' : '' }}">
                                    <td class="px-6 py-4 text-gray-500 text-center text-xs font-bold">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 font-bold text-sm">
                                        {{ $user->nama }}
                                        @if($user->id === auth()->id())
                                            <span class="ml-2 px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600">Anda</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">{{ $user->email }}</td>

                                    <td class="px-6 py-4 text-center">
                                        @if($user->role == 'admin')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-[#e92027] border border-red-100">
                                                <i class="fas fa-shield-alt mr-1.5 text-[10px]"></i> Admin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                                <i class="fas fa-user mr-1.5 text-[10px]"></i> Karyawan
                                            </span>
                                        @endif
                                    </td>

                                    <!-- KOLOM STATUS BARU -->
                                    <td class="px-6 py-4 text-center">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-300">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">

                                            <!-- Tombol Edit -->
                                            <a href="{{ route('management-akun.edit', $user->id) }}" class="w-8 h-8 flex items-center justify-center bg-white text-amber-500 rounded-lg hover:bg-amber-50 transition shadow-sm border border-gray-200 hover:border-amber-300" title="Edit">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>

                                            @if($user->id !== auth()->id())

                                                <!-- Tombol Nonaktifkan/Aktifkan -->
                                                <form action="{{ route('management-akun.toggle-status', $user->id) }}" method="POST" class="inline" hx-disable>
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="w-8 h-8 flex items-center justify-center bg-white rounded-lg transition shadow-sm border border-gray-200
                                                        {{ $user->is_active ? 'text-gray-500 hover:bg-gray-100 hover:border-gray-400' : 'text-green-600 hover:bg-green-50 hover:border-green-300' }}"
                                                        title="{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}">
                                                        <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} text-xs"></i>
                                                    </button>
                                                </form>

                                                <!-- Tombol Hapus (Murni) -->
                                                <button @click="showDeleteModal = true; deleteUrl = '{{ route('management-akun.destroy', $user->id) }}'; isDeleting = false" class="w-8 h-8 flex items-center justify-center bg-white text-[#e92027] rounded-lg hover:bg-red-50 transition shadow-sm border border-gray-200 hover:border-red-300" title="Hapus Permanen">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            <!-- ... baris empty tetap sama ... -->
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic bg-gray-50/50 text-sm">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fas fa-users-slash text-3xl mb-2 text-gray-300"></i>
                                            Tidak ada data pengguna ditemukan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- TAMPILAN HP: CARD LIST --}}
                <div class="md:hidden flex flex-col p-4 gap-4 bg-gray-50">
                    @forelse($users as $user)
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 relative">
                            <div class="flex justify-between items-start mb-3">
                                <div class="pr-4">
                                    <h3 class="font-bold text-gray-800 text-base leading-tight">
                                        {{ $user->nama }}
                                        @if($user->id === auth()->id())
                                            <span class="inline-block ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600">Anda</span>
                                        @endif
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
                                </div>
                                <div>
                                    @if($user->role == 'admin')
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-red-50 text-[#e92027] border border-red-100 whitespace-nowrap">
                                            <i class="fas fa-shield-alt mr-1"></i> Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-gray-100 text-gray-600 border border-gray-200 whitespace-nowrap">
                                            <i class="fas fa-user mr-1"></i> Karyawan
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Action Buttons for Mobile --}}
                            <div class="flex gap-2 pt-3 border-t border-gray-50">
                                <a href="{{ route('management-akun.edit', $user->id) }}" class="flex-1 py-2 bg-amber-50 text-amber-600 text-xs font-bold rounded-xl border border-amber-100 hover:bg-amber-100 flex items-center justify-center gap-1.5 transition">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                @if($user->id !== auth()->id())
                                <button @click="showDeleteModal = true; deleteUrl = '{{ route('management-akun.destroy', $user->id) }}'; isDeleting = false" class="flex-1 py-2 bg-red-50 text-[#e92027] text-xs font-bold rounded-xl border border-red-100 hover:bg-red-100 flex items-center justify-center gap-1.5 transition">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400 italic text-sm">
                            <i class="fas fa-users-slash text-2xl mb-2 text-gray-300 block"></i>
                            Tidak ada data pengguna ditemukan.
                        </div>
                    @endforelse
                </div>

                {{-- Pagination Custom --}}
                @if($users->hasPages())
                <div class="p-4 md:p-6 border-t border-gray-100 bg-white md:bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">

                    <!-- Info Data -->
                    <div class="text-xs md:text-sm text-gray-500 font-medium text-center md:text-left">
                        Menampilkan <span class="font-bold text-gray-800">{{ $users->firstItem() }}</span> -
                        <span class="font-bold text-gray-800">{{ $users->lastItem() }}</span>
                        dari <span class="font-bold text-gray-800">{{ $users->total() }}</span> data
                    </div>

                    <!-- Kotak Angka Pagination -->
                    <div class="flex flex-wrap items-center justify-center gap-1.5">

                        {{-- Tombol Previous --}}
                        @if ($users->onFirstPage())
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-300 bg-gray-50 cursor-not-allowed">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-[#e92027] hover:bg-red-50 hover:border-red-200 transition shadow-sm">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </a>
                        @endif

                        {{-- Logika Angka Pagination --}}
                        @php
                            $links = $users->linkCollection()->toArray();
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
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-[#e92027] hover:bg-red-50 hover:border-red-200 transition shadow-sm">
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
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Hapus Pengguna?</h3>
                <p class="text-gray-500 mb-8 text-sm md:text-base leading-relaxed">Akun pengguna ini akan dihapus permanen dari sistem.</p>
                <div class="flex flex-col gap-3">

                    <!-- TAMBAHKAN hx-disable dan @submit="isDeleting = true" DI SINI -->
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
