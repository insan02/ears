@if(isset($printMode) && $printMode)
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cetak Data Arsip - PT Semen Padang</title>
        @vite(['resources/css/app.css'])
        <style>
            @media print, screen {
                body { background-color: white !important; font-family: sans-serif; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; margin: 0; padding: 20px; }
                .print-header { border-bottom: 3px solid #8B0000; margin-bottom: 20px; padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between; }
                table { width: 100%; table-layout: fixed; border-collapse: collapse; border: 1px solid #000; font-size: 8.5pt; }
                thead tr { background-color: #fce4e4 !important; color: #8B0000 !important; }
                /* PERBAIKAN: Menghapus word-wrap: break-word dari th, td agar huruf tidak terpotong-potong */
                th, td { border: 1px solid #444 !important; padding: 4px 6px; vertical-align: top; }
                th { text-transform: uppercase; font-weight: bold; text-align: center; background-color: #fce4e4 !important; }
                th:first-child, td:first-child, th:last-child, td:last-child { display: none; }
                .rounded-lg, .rounded-full, .bg-red-50, .bg-green-100 { background: none !important; border: none !important; color: black !important; padding: 0 !important; font-weight: normal; }
                button, a { text-decoration: none; color: black; pointer-events: none; }
                svg { display: none; }
            }
            @page { size: landscape; margin: 10mm; }
        </style>
    </head>
    <body onload="window.print()">
        <div class="print-header">
            <img src="{{ asset('images/logo-sp.png') }}" alt="Logo" style="height: 80px; width: auto;">
            <div style="text-align: center; flex: 1;">
                <h1 style="font-size: 24px; font-weight: bold; color: #8B0000; text-transform: uppercase; margin: 0;">PT Semen Padang</h1>
                <h2 style="font-size: 18px; font-weight: bold; margin: 5px 0;">Daftar Arsip Dokumen</h2>
                <p style="font-size: 12px; color: #666; margin: 0;">Indarung, Padang 25237, Sumatera Barat</p>
            </div>
            <div style="width: 80px;"></div>
        </div>
        <div id="arsip-table-container">
            @include('arsip.partials.table')
        </div>
    </body>
    </html>
@else
<x-layout>
    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="bg-gray-50 min-h-screen pb-20">
        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
             <div class="absolute inset-0 z-0 opacity-40">
                  <svg class="absolute w-full h-full" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                     <defs><linearGradient id="polyGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#580000;stop-opacity:0.3" /><stop offset="100%" style="stop-color:#000000;stop-opacity:0.4" /></linearGradient></defs>
                     <path fill="url(#polyGrad)" d="M0 0 L1000 0 L1000 500 L0 300 Z" /><path fill="#000000" opacity="0.1" d="M-100 0 L500 0 L200 600 L-100 400 Z" />
                 </svg>
             </div>
             <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-6">
                <div class="text-center md:text-left">
                     <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Daftar Arsip</h2>
                     <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Kelola dan monitor seluruh dokumen arsip perusahaan.</p>
                </div>
                <div>
                    <a href="{{ route('arsip.create') }}" class="group bg-white text-[#e92027] hover:bg-gray-50 px-8 py-3 rounded-full font-bold shadow-2xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40">
    <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors"><i class="fas fa-plus"></i></div>
    <span>TAMBAH ARSIP</span>
</a>
                </div>
            </div>
        </div>

        <div x-data="{ showImportModal: false }" class="max-w-7xl mx-auto px-4 md:px-6 -mt-20 relative z-20 mb-12">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 animate-fade-in-down shadow-sm">
                    <div class="bg-green-100 p-2 rounded-full text-green-600"><i class="fas fa-check text-sm"></i></div>
                    <p class="text-sm font-bold text-green-800 flex-1">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-xl flex items-center gap-3 animate-fade-in-down shadow-sm">
                    <div class="bg-red-100 p-2 rounded-full text-red-600"><i class="fas fa-exclamation-triangle text-sm"></i></div>
                    <p class="text-sm font-bold text-red-800 flex-1">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-4 md:p-6 mb-8">
                <form id="filterForm" action="/arsip" method="GET" class="flex flex-col xl:flex-row gap-4 justify-between items-center">

                    <div class="relative w-full xl:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" placeholder="Cari nama, kode, isi..." value="{{ request('search') }}" onchange="this.form.submit()"
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-[#e92027] focus:ring-2 focus:ring-red-100 transition shadow-sm text-sm font-medium">
                    </div>

                    <div class="flex flex-wrap gap-3 w-full xl:w-auto items-center">
                        @php $chevron = '<i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>'; @endphp

                        <div x-data="{ open: false }" class="relative flex-grow sm:flex-none min-w-[150px]">
                            @php $tndLabel = request('filter_status') ? request('filter_status') : 'Semua Status'; @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full bg-gray-50 hover:bg-red-50 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold truncate transition-all">{{ $tndLabel }} {!! $chevron !!}</button>
                            <div x-show="open" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                @foreach(['' => 'Semua Status', 'Permanen' => 'Permanen', 'Musnah' => 'Musnah', 'Dinilai Kembali' => 'Dinilai Kembali'] as $val => $lbl)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50"><input type="radio" name="filter_status" value="{{ $val }}" onchange="this.form.submit()" class="hidden" {{ request('filter_status') == $val ? 'checked' : '' }}> {{ $lbl }}</label>
                                @endforeach
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative flex-grow sm:flex-none min-w-[140px]">
                            @php $aksLabel = request('filter_hak_akses') ? request('filter_hak_akses') : 'Semua Akses'; @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full bg-gray-50 hover:bg-red-50 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold truncate transition-all">{{ $aksLabel }} {!! $chevron !!}</button>
                            <div x-show="open" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                @foreach(['' => 'Semua Akses', 'Biasa' => 'Biasa', 'Terbatas' => 'Terbatas', 'Rahasia' => 'Rahasia', 'Sangat Rahasia' => 'Sangat Rahasia'] as $val => $lbl)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50"><input type="radio" name="filter_hak_akses" value="{{ $val }}" onchange="this.form.submit()" class="hidden" {{ request('filter_hak_akses') == $val ? 'checked' : '' }}> {{ $lbl }}</label>
                                @endforeach
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative flex-grow sm:flex-none min-w-[130px]">
                            @php $thnLabel = request('filter_tahun') ? request('filter_tahun') : 'Semua Tahun'; @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full bg-gray-50 hover:bg-red-50 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold truncate transition-all">{{ $thnLabel }} {!! $chevron !!}</button>
                            <div x-show="open" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50"><input type="radio" name="filter_tahun" value="" onchange="this.form.submit()" class="hidden" {{ request('filter_tahun') == '' ? 'checked' : '' }}> Semua Tahun</label>
                                @foreach($availableYears as $year)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50"><input type="radio" name="filter_tahun" value="{{ $year }}" onchange="this.form.submit()" class="hidden" {{ request('filter_tahun') == $year ? 'checked' : '' }}> {{ $year }}</label>
                                @endforeach
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="relative flex-grow sm:flex-none min-w-[130px]">
                            @php $boxLabel = request('filter_box') ? request('filter_box') : 'Semua Box'; @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full bg-gray-50 hover:bg-red-50 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold truncate transition-all">{{ $boxLabel }} {!! $chevron !!}</button>
                            <div x-show="open" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50"><input type="radio" name="filter_box" value="" onchange="this.form.submit()" class="hidden" {{ request('filter_box') == '' ? 'checked' : '' }}> Semua Box</label>
                                @foreach($availableBoxes as $box)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer hover:bg-gray-50"><input type="radio" name="filter_box" value="{{ $box }}" onchange="this.form.submit()" class="hidden" {{ request('filter_box') == $box ? 'checked' : '' }}> {{ $box }}</label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-grow sm:flex-none">
                            @if(request()->hasAny(['search', 'filter_status', 'filter_hak_akses', 'filter_tahun', 'filter_box']))
                                <a href="/arsip" class="flex items-center justify-center px-4 py-3 bg-red-50 text-[#e92027] rounded-xl text-sm font-bold shadow-sm whitespace-nowrap hover:bg-[#e92027] hover:text-white transition">Reset</a>
                            @endif

                            <div class="relative" x-data="{ showSortDropdown: false }">
                                <button type="button" @click="showSortDropdown = !showSortDropdown" @click.away="showSortDropdown = false" class="px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition shadow-sm flex items-center gap-2"><i class="fas fa-sort-amount-down text-gray-400"></i> Urutkan</button>
                                <div x-show="showSortDropdown" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                    <ul class="text-xs font-medium text-gray-600">
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" class="block px-4 py-3 hover:bg-red-50">Input Terbaru</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" class="block px-4 py-3 hover:bg-red-50">Input Terlama</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['sort' => 'year_desc']) }}" class="block px-4 py-3 hover:bg-red-50">Tahun (Terbaru - Lama)</a></li>
                                    </ul>
                                </div>
                            </div>

                            <button type="button" @click="showImportModal = true" class="px-4 py-3 bg-green-50 text-green-700 rounded-xl font-bold flex items-center gap-2 hover:bg-green-100 transition shadow-sm border border-green-200"><i class="fas fa-upload"></i> Import</button>

                            <button type="button" onclick="submitExport('excel')" class="px-4 py-3 bg-green-600 text-white rounded-xl font-bold flex items-center gap-2 hover:bg-green-700 transition shadow-sm border border-green-600"><i class="fas fa-file-excel"></i> Export</button>
                            <a href="{{ request()->fullUrlWithQuery(['print' => 'true']) }}" target="_blank" class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold flex items-center gap-2 hover:bg-gray-200 transition shadow-sm border border-gray-200"><i class="fas fa-print"></i> Print</a>
                        </div>
                    </div>
                </form>
            </div>

            <form id="export-form" action="/arsip/export" method="POST" target="_blank" class="hidden">
                @csrf
                <input type="hidden" name="type" id="export-type">
                <input type="hidden" name="ids" id="export-ids">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="filter_status" value="{{ request('filter_status') }}">
                <input type="hidden" name="filter_hak_akses" value="{{ request('filter_hak_akses') }}">
                <input type="hidden" name="filter_tahun" value="{{ request('filter_tahun') }}">
            </form>

            <div id="arsip-table-container">
                @include('arsip.partials.table')
            </div>

            <div class="mt-8 mb-12 flex justify-center w-full">
    @if ($arsips instanceof \Illuminate\Pagination\LengthAwarePaginator && $arsips->hasPages())
        @php $paginator = $arsips->appends(request()->query()); @endphp
        <nav class="flex items-center gap-1 md:gap-2 bg-white p-1.5 md:p-2 rounded-2xl border border-gray-200 shadow-sm max-w-full overflow-x-auto hide-scrollbar">

            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 md:px-4 py-2 rounded-xl text-gray-300 bg-gray-50 cursor-not-allowed text-xs md:text-sm font-bold flex items-center gap-2">
                    <i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Prev</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 md:px-4 py-2 rounded-xl text-gray-600 hover:bg-red-50 hover:text-[#e92027] transition-colors text-xs md:text-sm font-bold flex items-center gap-2">
                    <i class="fas fa-chevron-left"></i> <span class="hidden sm:inline">Prev</span>
                </a>
            @endif

            {{-- Angka Pagination --}}
            @foreach ($paginator->linkCollection() as $link)
                {{-- Lewati label tombol Next/Prev bawaan sistem --}}
                @if (str_contains($link['label'], 'Previous') || str_contains($link['label'], 'Next') || str_contains($link['label'], '&laquo;') || str_contains($link['label'], '&raquo;'))
                    @continue
                @endif

                @if ($link['url'] === null)
                    {{-- Pemisah 3 Titik --}}
                    <span class="px-2 py-2 text-gray-400 text-sm font-bold">...</span>
                @elseif ($link['active'])
                    {{-- Halaman Aktif --}}
                    <span class="px-3 md:px-4 py-2 rounded-xl bg-[#e92027] text-white font-extrabold text-xs md:text-sm shadow-md">{{ $link['label'] }}</span>
                @else
                    {{-- Halaman Tersedia --}}
                    <a href="{{ $link['url'] }}" class="px-3 md:px-4 py-2 rounded-xl text-gray-600 hover:bg-red-50 hover:text-[#e92027] transition-colors text-xs md:text-sm font-bold">{{ $link['label'] }}</a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 md:px-4 py-2 rounded-xl text-gray-600 hover:bg-red-50 hover:text-[#e92027] transition-colors text-xs md:text-sm font-bold flex items-center gap-2">
                    <span class="hidden sm:inline">Next</span> <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span class="px-3 md:px-4 py-2 rounded-xl text-gray-300 bg-gray-50 cursor-not-allowed text-xs md:text-sm font-bold flex items-center gap-2">
                    <span class="hidden sm:inline">Next</span> <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </nav>
    @endif
</div>

            {{-- MODAL IMPORT EXCEL --}}
            <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div @click="showImportModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                    <div class="relative inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border-t-8 border-green-600">
                        <form action="{{ route('arsip.import.process') }}" method="POST" enctype="multipart/form-data" onsubmit="tampilkanAnimasiLoading(event)" hx-disable>
                            @csrf
                            <div class="bg-white px-6 pt-6 pb-6">
                                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                                    <div class="bg-green-100 p-3 rounded-full text-green-600"><i class="fas fa-file-excel text-xl"></i></div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900">Import Data Arsip</h3>
                                        <p class="text-xs text-gray-500 font-medium">Unggah file template (.xlsx / .csv)</p>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <input type="file" name="file" required accept=".xlsx, .xls, .csv" class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer border-2 border-dashed border-gray-200 rounded-2xl p-4 transition-colors hover:border-green-300"/>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 text-yellow-800 text-xs leading-relaxed font-medium">
                                    <span class="font-bold block mb-1 uppercase text-[10px] tracking-wider text-yellow-600">Sangat Direkomendasikan:</span>
                                    Untuk data arsip berjumlah masif (di atas 10.000 baris), disarankan menyimpan file Excel sebagai format <b>.CSV (Comma delimited)</b> terlebih dahulu.
                                </div>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                                <button type="button" @click="showImportModal = false" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50">Batal</button>
                                <button type="submit" class="px-6 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold shadow-md hover:bg-green-700">Import Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            let typingTimer;
            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(() => this.form.submit(), 1000);
                });
                searchInput.addEventListener('keydown', () => clearTimeout(typingTimer));
            }
        });

        function tampilkanAnimasiLoading(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const importId = Date.now().toString();
            formData.append('import_id', importId);

            Swal.fire({
                title: 'Mengekstrak Data...',
                html: `
                    <div class="mt-2 text-sm text-gray-600">
                        <p class="mb-2">Sedang menyimpan data ke sistem:</p>
                        <h2 id="progress-text" class="text-4xl font-black text-[#e92027] mb-4">0 <span class="text-sm font-bold text-gray-500 uppercase">Baris</span></h2>
                        <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs font-bold uppercase text-left">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Mohon JANGAN tutup atau refresh halaman ini!
                        </div>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                    const progressInterval = setInterval(() => {
                        fetch('/arsip/import/progress?id=' + importId)
                            .then(res => res.json())
                            .then(data => {
                                const progressEl = document.getElementById('progress-text');
                                if (progressEl && data.processed > 0) {
                                    progressEl.innerHTML = data.processed.toLocaleString('id-ID') + ' <span class="text-sm font-bold text-gray-500 uppercase">Baris</span>';
                                }
                            });
                    }, 2000);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        clearInterval(progressInterval);
                        if (data.success) {
                            Swal.fire({
                                icon: 'success', title: 'Selesai!', text: 'Seluruh data arsip berhasil disimpan.',
                                showConfirmButton: false, timer: 2000
                            }).then(() => { window.location.reload(); });
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat import data.', 'error');
                        }
                    }).catch(error => {
                        clearInterval(progressInterval);
                        Swal.fire('Error', 'Gagal terhubung ke server atau waktu proses habis.', 'error');
                    });
                }
            });
        }

        function submitExport(type) {
            const checkedBoxes = document.querySelectorAll('input[name="selected_arsip[]"]:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            document.getElementById('export-type').value = type;
            document.getElementById('export-ids').value = JSON.stringify(ids);
            document.getElementById('export-form').submit();
        }

        function toggleAll(source) {
            checkboxes = document.querySelectorAll('input[name="selected_arsip[]"]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</x-layout>
@endif
