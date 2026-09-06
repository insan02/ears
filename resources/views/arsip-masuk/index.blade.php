<x-layout>
    <div class="bg-gray-50 min-h-screen pb-20">
        <!-- Background Header -->
        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-24 md:pb-32 pt-12 md:pt-16 px-4 md:px-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 mb-8 rounded-b-[2rem] md:rounded-b-[3rem] shadow-2xl relative overflow-hidden">
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
                     <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Daftar Arsip Masuk</h2>
                     <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Kelola dan monitor seluruh arsip masuk dengan mudah dan efisien.</p>
                </div>
                <a href="{{ route('arsip-masuk.create') }}" class="group bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-full font-bold shadow-xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40 border border-white/20">
                    <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                        <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-sm md:text-base">TAMBAH ARSIP</span>
                </a>
            </div>
        </div>

        <!-- Floating Card Container -->
        <div class="max-w-7xl mx-auto px-4 -mt-12 md:-mt-20 relative z-20 mb-12">

            <!-- BLOK PESAN ALERT -->
            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-[#e92027] p-4 rounded-r-lg shadow-sm font-bold text-[#c41820] flex items-center animate-pulse">
                    <i class="fas fa-exclamation-triangle mr-3 text-xl"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm font-bold text-green-800 flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden border border-gray-100 min-h-[400px] flex flex-col">

                <!-- Filters & Toolbar -->
                <div class="p-4 md:p-6 border-b border-gray-100 bg-white flex flex-col xl:flex-row gap-4 justify-between items-center relative z-30">

                    <!-- Search & Filters Form -->
                    <form action="{{ route('arsip-masuk.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full xl:w-auto items-center">

                        <!-- Search Box -->
                        <div class="relative w-full md:w-80 group">
                             <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027] transition-colors pointer-events-none">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                             </span>
                             <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, unit..." onchange="this.form.submit()"
                                class="w-full py-3 pl-12 pr-4 bg-gray-50 border border-gray-200 rounded-xl md:rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#e92027] focus:bg-white focus:border-transparent text-sm font-medium transition-all shadow-sm">
                        </div>

                        <!-- Dropdowns -->
                        <div class="flex gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 hide-scrollbar">
                            <select name="unit_asal" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#e92027] outline-none block px-4 py-2.5 cursor-pointer hover:bg-gray-50 transition-all shadow-sm min-w-[140px]">
                                <option value="">Semua Unit</option>
                                @foreach($unitAsalOptions as $unit)
                                    <option value="{{ $unit }}" {{ request('unit_asal') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                @endforeach
                            </select>

                            <select name="year" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#e92027] outline-none block px-4 py-2.5 cursor-pointer hover:bg-gray-50 transition-all shadow-sm min-w-[120px]">
                                <option value="">Semua Tahun</option>
                                 @foreach($yearOptions as $year)
                                     <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                 @endforeach
                            </select>

                             <!-- Reset Filter -->
                             @if(request('search') || request('unit_asal') || request('year'))
                                <a href="{{ route('arsip-masuk.index') }}" class="flex items-center px-4 py-2.5 bg-red-50 text-[#e92027] rounded-xl text-sm font-bold hover:bg-red-100 transition shadow-sm whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Reset
                                </a>
                            @endif
                        </div>
                    </form>

                     <!-- Action Buttons: Print/Export -->
                    <div class="flex gap-2 w-full xl:w-auto justify-end">
                        <button onclick="submitExport('excel')" class="flex items-center justify-center gap-1.5 w-full md:w-auto px-3 md:px-4 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-xl text-sm font-bold hover:bg-green-100 hover:shadow-md transition-all active:scale-95">
                            <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Excel</span>
                        </button>
                        <button onclick="submitExport('pdf')" class="flex items-center justify-center gap-1.5 w-full md:w-auto px-3 md:px-4 py-2.5 bg-red-50 text-[#c41820] border border-red-200 rounded-xl text-sm font-bold hover:bg-red-100 hover:shadow-md transition-all active:scale-95">
                            <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">PDF</span>
                        </button>
                        <button onclick="submitExport('print')" class="flex items-center justify-center gap-1.5 w-full md:w-auto px-3 md:px-4 py-2.5 bg-gray-50 text-gray-700 border border-gray-200 rounded-xl text-sm font-bold hover:bg-gray-100 hover:shadow-md transition-all active:scale-95">
                            <i class="fas fa-print"></i> <span class="hidden sm:inline">Print</span>
                        </button>
                   </div>
                </div>

                <div class="p-6 flex-grow bg-gray-50/30">

                    <!-- ============================================== -->
                    <!-- TAMPILAN MOBILE: KARTU (Tampil hanya di layar HP) -->
                    <!-- ============================================== -->
                    <div class="block lg:hidden space-y-4">
                        @forelse($arsipMasuk as $item)
                            <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative">
                                <!-- Unit Asal & Badge Box -->
                                <div class="flex justify-between items-start mb-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-gray-100 text-gray-800 border border-gray-200 uppercase tracking-wide">
                                        {{ $item->unit_asal }}
                                    </span>
                                    <span class="bg-red-50 text-[#e92027] py-1 px-2.5 rounded-full text-[10px] font-bold border border-red-100 flex items-center gap-1">
                                        <i class="fas fa-box text-[#e92027]"></i> {{ $item->jumlah_box_masuk }} Box
                                    </span>
                                </div>

                                <!-- Nomor Berita Acara -->
                                <h3 class="font-extrabold text-gray-900 text-lg mb-4">
                                    <span class="text-xs text-gray-400 block font-normal mb-0.5">No. Berita Acara</span>
                                    {{ $item->nomor_berita_acara }}
                                </h3>

                                <!-- Detail Info -->
                                <div class="grid grid-cols-2 gap-3 mb-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                    <div>
                                        <span class="text-[10px] text-gray-500 font-bold block uppercase mb-1">Tanggal Terima</span>
                                        <div class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                                            <i class="far fa-calendar-alt text-[#e92027]"></i>
                                            {{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d M Y') }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-500 font-bold block uppercase mb-1">Penerima</span>
                                        <div class="text-xs font-semibold text-gray-800 flex items-center gap-1.5">
                                            <i class="far fa-user text-blue-500"></i>
                                            {{ $item->penerima->nama ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Aksi -->
                                <div class="pt-3 border-t border-gray-100 flex gap-2 justify-end">
                                    @if(auth()->user()->role === 'admin' || auth()->id() == $item->user_penerima)

                                        {{-- Cek Edit Terkunci --}}
                                        @if(!$item->is_completed)
                                            <a href="{{ route('arsip-masuk.edit', $item->id) }}" class="flex-1 text-center py-2 bg-amber-50 text-amber-600 text-xs font-bold rounded-lg border border-amber-100 hover:bg-amber-100 transition">
                                                Edit
                                            </a>
                                        @else
                                            <button type="button" disabled class="flex-1 w-full py-2 bg-gray-50 text-gray-400 text-xs font-bold rounded-lg border border-gray-200 cursor-not-allowed" title="Terkunci (Sudah di E-Arsip)">
                                                Edit Terkunci
                                            </button>
                                        @endif

                                        {{-- Cek Hapus Terkunci --}}
                                        @if($item->log_aktivitas_count == 0)
                                        <form action="{{ route('arsip-masuk.destroy', $item->id) }}" method="POST" class="flex-1 flex">
    @csrf @method('DELETE')
    <!-- Tambahkan onclick="confirmDelete(this)" dan hapus class="delete-btn" -->
    <button type="button" onclick="confirmDelete(this)" class="w-full py-2 bg-red-50 text-[#e92027] text-xs font-bold rounded-lg border border-red-100 hover:bg-red-100 transition">
        Hapus
    </button>
</form>
                                        @else
                                        <button type="button" disabled class="flex-1 w-full py-2 bg-gray-50 text-gray-400 text-xs font-bold rounded-lg border border-gray-200 cursor-not-allowed" title="Terkunci (Sedang Dikerjakan)">
                                            Hapus Terkunci
                                        </button>
                                        @endif

                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center bg-white rounded-2xl border border-gray-200">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p class="text-sm font-medium text-gray-400">Belum ada data arsip masuk.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- ================================================== -->
                    <!-- TAMPILAN DESKTOP: TABEL (Tampil di Laptop/Tablet) -->
                    <!-- ================================================== -->
                    <div class="hidden lg:block overflow-hidden rounded-2xl border border-gray-200 shadow-sm bg-white">
                        <table class="min-w-full w-full bg-white text-left whitespace-nowrap">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 border-b border-gray-200 text-sm">
                                    <th class="py-4 px-6 font-bold uppercase tracking-wider">No Berita Acara</th>
                                    <th class="py-4 px-6 font-bold uppercase tracking-wider">Unit Asal</th>
                                    <th class="py-4 px-6 font-bold uppercase tracking-wider text-center">Tanggal Terima</th>
                                    <th class="py-4 px-6 font-bold uppercase tracking-wider text-center">Jumlah Box</th>
                                    <th class="py-4 px-6 font-bold uppercase tracking-wider text-center">Penerima</th>
                                    <th class="py-4 px-6 font-bold uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($arsipMasuk as $item)
                                <tr class="hover:bg-red-50/50 transition duration-200 group text-sm">
                                    <td class="py-4 px-6 font-bold text-gray-800 border-l-4 border-transparent group-hover:border-[#e92027] transition-all">
                                        {{ $item->nomor_berita_acara }}
                                    </td>
                                    <td class="py-4 px-6 text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 border border-gray-200 text-gray-800">
                                            {{ $item->unit_asal }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 text-center">
                                        {{ \Carbon\Carbon::parse($item->tanggal_terima)->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="bg-red-50 text-[#e92027] py-1 px-3 rounded-full text-xs font-bold border border-red-100 shadow-sm">
                                            {{ $item->jumlah_box_masuk }} Box
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-gray-600 text-center font-medium">
                                        {{ $item->penerima->nama ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if(auth()->user()->role === 'admin' || auth()->id() == $item->user_penerima)

                                                {{-- Tombol Edit --}}
                                                @if(!$item->is_completed)
                                                    <a href="{{ route('arsip-masuk.edit', $item->id) }}" class="p-2 text-amber-500 hover:text-amber-700 hover:bg-amber-50 border border-transparent hover:border-amber-200 rounded-lg transition-colors" title="Edit">
                                                        <i class="fas fa-pen text-xs"></i>
                                                    </a>
                                                @else
                                                    <button type="button" disabled class="p-2 text-gray-400 bg-gray-50 border border-transparent rounded-lg cursor-not-allowed" title="Edit Terkunci (Sudah di E-Arsip)">
                                                        <i class="fas fa-lock text-xs"></i>
                                                    </button>
                                                @endif

                                                {{-- Tombol Hapus --}}
                                                @if($item->log_aktivitas_count == 0)
                                                    <form action="{{ route('arsip-masuk.destroy', $item->id) }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <!-- Tambahkan onclick="confirmDelete(this)" dan hapus class="delete-btn" -->
    <button type="button" onclick="confirmDelete(this)" class="p-2 text-[#e92027] hover:text-[#a0131a] hover:bg-red-50 border border-transparent hover:border-red-200 rounded-lg transition-colors" title="Hapus">
        <i class="fas fa-trash-alt text-xs"></i>
    </button>
</form>
                                                @else
                                                    <button type="button" disabled class="p-2 text-gray-400 bg-gray-50 border border-transparent rounded-lg cursor-not-allowed" title="Hapus Terkunci (Sedang Dikerjakan)">
                                                        <i class="fas fa-lock text-xs"></i>
                                                    </button>
                                                @endif

                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                            <p class="text-sm font-medium">Belum ada data arsip masuk ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($arsipMasuk->hasPages())
                <div class="p-4 md:p-6 border-t border-gray-100 bg-white">
                    {{ $arsipMasuk->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Export Form -->
    <form id="export-form" action="{{ route('arsip-masuk.export') }}" method="POST" target="_blank" class="hidden">
        @csrf
        <input type="hidden" name="type" id="export-type">
        <input type="hidden" name="ids" id="export-ids">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="unit_asal" value="{{ request('unit_asal') }}">
        <input type="hidden" name="penerima" value="{{ request('penerima') }}">
        <input type="hidden" name="year" value="{{ request('year') }}">
    </form>

    <!-- Scripts -->
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi Hapus yang kebal terhadap AJAX / Refresh Parsial
        function confirmDelete(buttonElement) {
            const form = buttonElement.closest('form');
            Swal.fire({
                title: 'Hapus Arsip Masuk?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e92027',
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: { cancelButton: 'text-gray-700 font-bold' }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }

        // Fungsi Export Excel / PDF / Print
        function submitExport(type) {
            document.getElementById('export-type').value = type;
            document.getElementById('export-ids').value = JSON.stringify([]);
            document.getElementById('export-form').submit();
        }
    </script>
</x-layout>
