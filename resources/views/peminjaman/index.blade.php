<x-layout>
    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <!-- SCRIPT WAJIB DI ATAS AGAR AMAN DARI HTMX -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.peminjamanIndex = function() {
            return {
                showDeleteModal: false,
                deleteUrl: '',
                showSortModal: false,
                showSortDropdown: false,
                showFilesModal: false,
                selectedFiles: [],
                selectedItems: [],
                allSelected: false,
                toggleSelectAll() {
                    this.allSelected = !this.allSelected;
                    if (this.allSelected) {
                        this.selectedItems = {{ json_encode($peminjaman->pluck('id')) }};
                    } else {
                        this.selectedItems = [];
                    }
                }
            };
        };

        window.confirmComplete = function(formId) {
            Swal.fire({
                title: 'Selesaikan Peminjaman?',
                text: "Arsip ini akan ditandai sebagai 'Sudah Dikembalikan' dan tersedia kembali di database.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: 'Ya, Selesaikan',
                cancelButtonText: 'Batal',
                customClass: { cancelButton: 'text-gray-700 font-bold' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        };
    </script>

    <div x-data="peminjamanIndex()" class="bg-gray-50 min-h-screen pb-20">

        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
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
                     <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Daftar Peminjaman</h2>
                     <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Kelola data peminjaman arsip.</p>
                </div>
                <div>
                    <a href="/peminjaman/create"
                        class="group bg-white text-[#e92027] hover:bg-gray-50 px-8 py-3 rounded-full font-bold shadow-2xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40 border border-white/20">
                        <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                             <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span>TAMBAH PEMINJAMAN</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-8 -mt-20 relative z-10 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white rounded-xl p-5 shadow-sm flex flex-col items-center justify-center text-center h-28 border-b-4 border-[#e92027] hover:-translate-y-1 transition duration-300">
                    <p class="text-gray-500 font-bold text-[10px] uppercase tracking-widest mb-1">Total Arsip</p>
                    <p class="text-4xl font-extrabold text-[#e92027]">{{ $totalPeminjaman }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm flex flex-col items-center justify-center text-center h-28 border-b-4 border-[#e92027] hover:-translate-y-1 transition duration-300">
                    <p class="text-gray-500 font-bold text-[10px] uppercase tracking-widest mb-1">Sedang Dipinjam</p>
                    <p class="text-4xl font-extrabold text-[#e92027]">{{ $masihDipinjam }}</p>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-sm flex flex-col items-center justify-center text-center h-28 border-b-4 border-gray-400 hover:-translate-y-1 transition duration-300">
                    <p class="text-gray-500 font-bold text-[10px] uppercase tracking-widest mb-1">Sudah Kembali</p>
                    <p class="text-4xl font-extrabold text-gray-600">{{ $sudahDikembalikan }}</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-8 mb-6">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 animate-fade-in-down shadow-sm">
                    <div class="bg-green-100 p-2 rounded-full text-green-600"><i class="fas fa-check text-sm"></i></div>
                    <p class="text-sm font-bold text-green-800 flex-1">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <!-- Toolbar (Search & Alpine Filters) -->
            <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mb-6">
                <form action="/peminjaman" method="GET" class="flex flex-col lg:flex-row gap-4 justify-between items-center" hx-disable>

                    <!-- Search Box -->
                    <div class="relative w-full lg:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" placeholder="Cari peminjam, berkas, atau isi..."
                               class="w-full py-3 pl-12 pr-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027] text-sm font-medium shadow-sm transition">
                    </div>

                    <div class="flex flex-wrap gap-3 w-full lg:w-auto items-center">
                        @php
                            $chevron = '<svg class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none transition-transform duration-200" :class="open ? \'rotate-180\' : \'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                        @endphp

                        <!-- Status Filter -->
                        <div x-data="{ open: false }" class="relative flex-grow sm:flex-none min-w-[160px]">
                            @php
                                $statusLabel = request('status') ? (request('status') == 'All' ? 'Semua Status' : request('status')) : 'Semua Status';
                            @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-gray-50 hover:bg-red-50 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all truncate shadow-sm">
                                {{ $statusLabel }} {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                @foreach(['All' => 'Semua Status', 'Sedang Dipinjam' => 'Sedang Dipinjam', 'Sudah Dikembalikan' => 'Sudah Dikembalikan'] as $val => $lbl)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('status') == $val || (request('status') == '' && $val == 'All') ? 'bg-red-50 text-[#e92027] font-bold' : 'hover:bg-gray-50' }}">
                                    <input type="radio" name="status" value="{{ $val }}" onchange="this.form.submit()" class="hidden" {{ request('status') == $val || (request('status') == '' && $val == 'All') ? 'checked' : '' }}> {{ $lbl }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Hak Akses Filter -->
                        <div x-data="{ open: false }" class="relative flex-grow sm:flex-none min-w-[160px]">
                            @php
                                $keamananLabel = request('keamanan') ? (request('keamanan') == 'All' ? 'Semua Akses' : request('keamanan')) : 'Semua Akses';
                            @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-gray-50 hover:bg-red-50 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all truncate shadow-sm">
                                {{ $keamananLabel }} {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                @foreach(['All' => 'Semua Akses', 'Biasa' => 'Biasa', 'Terbatas' => 'Terbatas', 'Rahasia' => 'Rahasia'] as $val => $lbl)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('keamanan') == $val || (request('keamanan') == '' && $val == 'All') ? 'bg-red-50 text-[#e92027] font-bold' : 'hover:bg-gray-50' }}">
                                    <input type="radio" name="keamanan" value="{{ $val }}" onchange="this.form.submit()" class="hidden" {{ request('keamanan') == $val || (request('keamanan') == '' && $val == 'All') ? 'checked' : '' }}> {{ $lbl }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Jenis Media Filter -->
                        <div x-data="{ open: false }" class="relative flex-grow sm:flex-none min-w-[160px]">
                            @php
                                $mediaLabel = request('media') ? (request('media') == 'All' ? 'Semua Media' : request('media')) : 'Semua Media';
                            @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-gray-50 hover:bg-red-50 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all truncate shadow-sm">
                                {{ $mediaLabel }} {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                @foreach(['All' => 'Semua Media', 'Hardfile' => 'Hardfile', 'Softfile' => 'Softfile'] as $val => $lbl)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('media') == $val || (request('media') == '' && $val == 'All') ? 'bg-red-50 text-[#e92027] font-bold' : 'hover:bg-gray-50' }}">
                                    <input type="radio" name="media" value="{{ $val }}" onchange="this.form.submit()" class="hidden" {{ request('media') == $val || (request('media') == '' && $val == 'All') ? 'checked' : '' }}> {{ $lbl }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Buttons Group -->
                        <div class="flex items-center gap-2 flex-grow sm:flex-none">
                            @if(request()->hasAny(['search', 'status', 'keamanan', 'media']))
                                <a href="/peminjaman" class="flex items-center justify-center px-4 py-3 bg-red-50 text-[#e92027] rounded-xl text-sm font-bold shadow-sm whitespace-nowrap hover:bg-[#e92027] hover:text-white transition">Reset</a>
                            @endif

                            <div class="relative" x-data="{ showSortDropdown: false }">
                                <button type="button" @click="showSortDropdown = !showSortDropdown" @click.away="showSortDropdown = false" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                                    <i class="fas fa-sort-amount-down text-gray-400"></i> Urutkan
                                </button>
                                <div x-show="showSortDropdown" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden" x-transition.opacity>
                                    <ul class="text-xs font-medium text-gray-600">
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'latest_added']) }}" class="block px-4 py-3 hover:bg-red-50 hover:text-[#c41820] {{ request('sort', 'latest_added') == 'latest_added' ? 'bg-red-50 text-[#c41820] font-bold' : '' }}">Terbaru Ditambahkan</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest_added']) }}" class="block px-4 py-3 hover:bg-red-50 hover:text-[#c41820] {{ request('sort') == 'oldest_added' ? 'bg-red-50 text-[#c41820] font-bold' : '' }}">Terlama Ditambahkan</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'latest_date']) }}" class="block px-4 py-3 hover:bg-red-50 hover:text-[#c41820] {{ request('sort') == 'latest_date' ? 'bg-red-50 text-[#c41820] font-bold' : '' }}">Tanggal Pinjam Terbaru</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest_date']) }}" class="block px-4 py-3 hover:bg-red-50 hover:text-[#c41820] {{ request('sort') == 'oldest_date' ? 'bg-red-50 text-[#c41820] font-bold' : '' }}">Tanggal Pinjam Terlama</a></li>
                                    </ul>
                                </div>
                            </div>

                            <a href="/peminjaman/export?{{ http_build_query(request()->all()) }}" target="_blank" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition shadow-sm flex items-center gap-2">
                                <i class="fas fa-file-excel text-green-600"></i> Export
                            </a>

                            <!-- Tombol Hapus Terpilih -->
                            <button type="button" x-show="selectedItems.length > 0" x-cloak @click="document.getElementById('bulk-delete-form').submit()" class="px-4 py-3 bg-[#e92027] border border-[#e92027] rounded-xl text-sm font-bold text-white hover:bg-[#c41820] transition shadow-sm flex items-center gap-2 animate-fade-in">
                                <i class="fas fa-trash-alt"></i> Hapus (<span x-text="selectedItems.length"></span>)
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            {{-- Hidden Form for Bulk Delete dengan hx-disable --}}
            <form id="bulk-delete-form" action="/peminjaman/bulk-delete" method="POST" class="hidden" hx-disable>
                @csrf
                <template x-for="id in selectedItems">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
            </form>

            <!-- TAMPILAN HP (CARDS) -->
            <div class="block lg:hidden space-y-4">
                @forelse($peminjaman as $detail)
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative">
                        <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                            <div>
                                <div class="font-extrabold text-gray-900 text-lg">{{ $detail->peminjaman->nama_peminjam }}</div>
                                <div class="text-[11px] text-gray-500">{{ \Carbon\Carbon::parse($detail->peminjaman->tanggal_pinjam)->format('d M Y') }}</div>
                            </div>
                            <div class="text-right flex flex-col items-end gap-1">
                                @if($detail->peminjaman->status == 'Sedang Dipinjam')
                                    <span class="inline-flex px-2 py-1 rounded-md text-[9px] font-bold text-[#c41820] bg-red-50 border border-red-200 uppercase tracking-wider">Dipinjam</span>
                                @else
                                    <span class="inline-flex px-2 py-1 rounded-md text-[9px] font-bold text-green-700 bg-green-50 border border-green-200 uppercase tracking-wider">Kembali</span>
                                @endif
                                <input type="checkbox" :value="{{ $detail->id }}" x-model="selectedItems" class="rounded border-gray-300 text-[#e92027] focus:ring-[#e92027] mt-1">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100 mb-3">
                            <div>
                                <span class="text-[10px] text-gray-500 font-bold block uppercase mb-1">Arsip</span>
                                <div class="text-xs font-bold text-[#e92027] truncate">{{ $detail->arsip ? $detail->arsip->nama_berkas : $detail->nama_arsip }}</div>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-500 font-bold block uppercase mb-1">Detail Media</span>
                                <div class="text-xs font-bold text-gray-800">{{ $detail->jenis_arsip }} - {{ ($detail->arsip && $detail->arsip->no_box) ? $detail->arsip->no_box : ($detail->no_box ?? '-') }}</div>
                            </div>
                        </div>
                        <div class="pt-2 flex justify-between items-center mt-2">
                            <div class="flex gap-2">
                                @if($detail->peminjaman->bukti_peminjaman)
                                    @php $files = is_array(json_decode($detail->peminjaman->bukti_peminjaman)) ? json_decode($detail->peminjaman->bukti_peminjaman) : [$detail->peminjaman->bukti_peminjaman]; @endphp
                                    <button @click="showFilesModal = true; selectedFiles = {{ json_encode($files) }}" class="p-2 text-white bg-blue-600 rounded-lg text-xs font-bold border border-blue-700"><i class="fas fa-file-pdf"></i> Bukti</button>
                                @endif
                            </div>
                            <div class="flex gap-1">
                                @if($detail->peminjaman->status == 'Sedang Dipinjam')
                                    {{-- PERBAIKAN: Form Selesaikan dengan hx-disable --}}
                                    <form id="complete-m-{{ $detail->peminjaman->id }}" action="/peminjaman/{{ $detail->peminjaman->id }}/complete" method="POST" hx-disable>
                                        @csrf @method('PATCH')
                                        <button type="button" onclick="confirmComplete('complete-m-{{ $detail->peminjaman->id }}')" class="p-2 bg-white text-green-600 rounded-lg shadow-sm border border-gray-200"><i class="fas fa-check text-xs"></i></button>
                                    </form>
                                @endif
                                <a href="/peminjaman/{{ $detail->peminjaman->id }}/edit" class="p-2 bg-white text-amber-500 rounded-lg shadow-sm border border-gray-200"><i class="fas fa-pen text-xs"></i></a>
                                <button @click="showDeleteModal = true; deleteUrl = '/peminjaman/{{ $detail->peminjaman->id }}'" class="p-2 bg-white text-[#e92027] rounded-lg shadow-sm border border-gray-200"><i class="fas fa-trash-alt text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center bg-white rounded-2xl border border-gray-200"><i class="fas fa-file-invoice text-4xl mb-3 text-gray-300"></i><p class="text-sm text-gray-400">Belum ada data peminjaman.</p></div>
                @endforelse
            </div>

            <!-- TAMPILAN LAPTOP (TABLE) -->
            <div class="hidden lg:block bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                <table class="w-full text-left table-fixed">
                    <thead>
                        <tr class="bg-[#e92027] text-white uppercase text-[10px] tracking-wider border-b border-red-900/20">
                            <th class="px-4 py-4 w-12 text-center">
                                <input type="checkbox" @click="toggleSelectAll()" x-model="allSelected" class="rounded border-none text-[#e92027] focus:ring-0 cursor-pointer w-4 h-4">
                            </th>
                            <th class="px-4 py-4 w-56 border-r border-red-900/20">Info Peminjam</th>
                            <th class="px-4 py-4 border-r border-red-900/20">Detail Arsip</th>
                            <th class="px-4 py-4 w-32 border-r border-red-900/20 text-center">Tgl Pinjam & Bukti</th>
                            <th class="px-4 py-4 w-28 text-center border-r border-red-900/20">Status</th>
                            <th class="px-4 py-4 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($peminjaman as $detail)
                            <tr class="hover:bg-red-50/30 transition duration-150 group">
                                <td class="px-4 py-4 text-center border-r border-gray-100">
                                    <input type="checkbox" :value="{{ $detail->id }}" x-model="selectedItems" class="rounded border-gray-300 text-[#e92027] focus:ring-[#e92027] cursor-pointer w-4 h-4">
                                </td>
                                <td class="px-4 py-4 border-r border-gray-100">
                                    <div class="font-bold text-gray-800 text-sm truncate">{{ $detail->peminjaman->nama_peminjam }}</div>
                                    <div class="text-[10px] font-mono text-gray-500">{{ $detail->peminjaman->nip }}</div>
                                    <div class="mt-1 text-[10px] bg-gray-100 text-gray-600 inline-block px-2 py-0.5 rounded truncate max-w-full">{{ $detail->peminjaman->jabatan_peminjam }} - {{ $detail->peminjaman->unit_peminjam }}</div>
                                </td>
                                <td class="px-4 py-4 border-r border-gray-100">
                                    <div class="font-bold text-[#e92027] text-sm truncate">{{ $detail->arsip ? $detail->arsip->nama_berkas : $detail->nama_arsip }}</div>
                                    <div class="mt-1 flex gap-2 items-center text-[10px]">
                                        <span class="font-bold border border-gray-200 px-1.5 py-0.5 rounded">{{ $detail->jenis_arsip }}</span>
                                        <span class="text-gray-500 font-mono">Box: {{ ($detail->arsip && $detail->arsip->no_box) ? $detail->arsip->no_box : ($detail->no_box ?? '-') }}</span>
                                    </div>
                                    @if($detail->peminjaman->keperluan)
                                        <div class="mt-1 text-[10px] text-gray-400 italic truncate" title="{{ $detail->peminjaman->keperluan }}">Ket: {{ $detail->peminjaman->keperluan }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center border-r border-gray-100">
                                    <div class="text-xs font-bold text-gray-600 mb-2">{{ \Carbon\Carbon::parse($detail->peminjaman->tanggal_pinjam)->format('d M Y') }}</div>
                                    @if($detail->peminjaman->bukti_peminjaman)
                                        @php $files = is_array(json_decode($detail->peminjaman->bukti_peminjaman)) ? json_decode($detail->peminjaman->bukti_peminjaman) : [$detail->peminjaman->bukti_peminjaman]; @endphp
                                        <button @click="showFilesModal = true; selectedFiles = {{ json_encode($files) }}" class="px-2 py-1 bg-red-50 text-[#c41820] text-[10px] font-bold rounded border border-red-200 hover:bg-[#e92027] hover:text-white transition"><i class="fas fa-file-pdf"></i> Lihat File</button>
                                    @else
                                        <span class="text-gray-300 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center border-r border-gray-100">
                                    @if($detail->peminjaman->status == 'Sedang Dipinjam')
                                        <span class="inline-flex px-2 py-1 rounded-md text-[9px] font-bold text-[#c41820] bg-red-50 border border-red-200 uppercase tracking-wider">Dipinjam</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded-md text-[9px] font-bold text-green-700 bg-green-50 border border-green-200 uppercase tracking-wider">Kembali</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="flex justify-center items-center gap-1.5">
                                        @if($detail->peminjaman->status == 'Sedang Dipinjam')
                                            {{-- PERBAIKAN: Form Selesaikan dengan hx-disable --}}
                                            <form id="complete-d-{{ $detail->peminjaman->id }}" action="/peminjaman/{{ $detail->peminjaman->id }}/complete" method="POST" hx-disable>
                                                @csrf @method('PATCH')
                                                <button type="button" onclick="confirmComplete('complete-d-{{ $detail->peminjaman->id }}')" class="w-8 h-8 bg-white text-green-600 rounded-lg hover:bg-green-50 transition shadow-sm border border-gray-200" title="Selesaikan"><i class="fas fa-check text-xs"></i></button>
                                            </form>
                                        @else
                                            <button disabled class="w-8 h-8 bg-gray-50 text-gray-300 rounded-lg border border-gray-100"><i class="fas fa-check text-xs"></i></button>
                                        @endif
                                        <a href="/peminjaman/{{ $detail->peminjaman->id }}/edit" class="w-8 h-8 flex items-center justify-center bg-white text-amber-500 rounded-lg hover:bg-amber-50 transition shadow-sm border border-gray-200" title="Edit"><i class="fas fa-pen text-xs"></i></a>
                                        <button @click="showDeleteModal = true; deleteUrl = '/peminjaman/{{ $detail->peminjaman->id }}'" class="w-8 h-8 bg-white text-[#e92027] rounded-lg hover:bg-red-50 transition shadow-sm border border-gray-200" title="Hapus"><i class="fas fa-trash-alt text-xs"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-16 text-center text-gray-400 bg-gray-50/50"><i class="fas fa-file-invoice text-4xl mb-3 text-gray-300"></i><br>Tidak ada data arsip peminjaman.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 mb-10">{{ $peminjaman->links() }}</div>
        </div>

        {{-- Files Modal --}}
        <div x-show="showFilesModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div @click.away="showFilesModal = false" class="bg-white rounded-3xl shadow-2xl overflow-hidden max-w-lg w-full flex flex-col relative">
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-file-pdf text-[#e92027]"></i> Bukti Peminjaman</h3>
                    <button @click="showFilesModal = false" class="w-8 h-8 rounded-full bg-white text-gray-400 hover:text-[#e92027] flex items-center justify-center shadow-sm"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6 overflow-y-auto flex-1 bg-white grid gap-3 max-h-[60vh]">
                    <template x-for="(file, index) in selectedFiles" :key="index">
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-2xl hover:border-red-100 transition group">
                            <div class="flex items-center gap-4 overflow-hidden">
                                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center text-[#e92027]"><i class="fas fa-file-pdf text-lg"></i></div>
                                <span x-text="file.split('/').pop()" class="text-xs truncate font-bold text-gray-700 max-w-[200px]"></span>
                            </div>
                            <a :href="`{{ asset('storage') }}/${file}`" target="_blank" class="px-4 py-2 text-[10px] font-bold text-white bg-[#e92027] rounded-xl hover:bg-[#c41820] shadow-md transition">Lihat / Download</a>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Delete Modal (Form Hapus dengan hx-disable) --}}
        <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="showDeleteModal = false" class="bg-white rounded-[2rem] w-full max-w-sm p-8 text-center relative overflow-hidden shadow-2xl">
                <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-[#e92027] shadow-sm animate-bounce"><i class="fas fa-trash-alt text-3xl"></i></div>
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Hapus Transaksi?</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">Data peminjaman beserta detail arsipnya akan dihapus permanen dari sistem.</p>
                <div class="flex flex-col gap-3">
                    <form :action="deleteUrl" method="POST" class="w-full" hx-disable>
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-3.5 bg-[#e92027] text-white rounded-xl text-sm font-bold hover:bg-[#c41820] shadow-md transform hover:scale-[1.02] transition">Ya, Hapus Sekarang</button>
                    </form>
                    <button @click="showDeleteModal = false" class="w-full py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition">Batalkan</button>
                </div>
            </div>
        </div>

    </div>
</x-layout>
