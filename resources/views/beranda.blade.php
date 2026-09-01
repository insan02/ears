<x-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        [x-cloak] { display: none !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div x-data="{ activeTab: '{{ request()->query('active_tab', 'arsip') }}', mounted: false }"
         x-init="setTimeout(() => mounted = true, 100)"
         class="pb-20 bg-gray-50 min-h-screen">

        <!-- Background Header -->
        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-28 pt-12 md:pb-32 md:pt-16 px-6 md:px-8 -mt-4 -mx-4 md:-mt-6 md:-mx-6 mb-8 rounded-b-[2rem] md:rounded-b-[3rem] shadow-2xl relative overflow-hidden">
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
                    <path fill="#580000" opacity="0.2" d="M800 0 L1400 0 L1400 400 L600 600 Z" />
                    <path fill="url(#polyGrad)" opacity="0.3" d="M500 600 L1200 600 L800 200 Z" />
                </svg>
            </div>
            <div class="absolute top-0 right-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 z-0 pointer-events-none mix-blend-overlay">
                <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0L24 12L12 24L0 12L12 0Z" /></svg>
            </div>

            <div class="max-w-7xl mx-auto flex flex-col justify-center items-center text-center relative z-10 gap-4">
                 <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-1 drop-shadow-md">Dashboard Record Center</h2>
                 <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Ringkasan statistik dan aktivitas kearsipan PT Semen Padang.</p>
            </div>
        </div>

        <!-- TABS NAVIGASI -->
        <div class="container mx-auto relative z-20 -mt-12 md:-mt-10 px-4 md:px-0">
            <div x-show="mounted"
                 x-transition:enter="transition ease-out duration-700 delay-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="flex flex-col sm:flex-row justify-center gap-2 md:gap-4 max-w-4xl mx-auto"
                 style="display: none;"
            >
                <button @click="activeTab = 'arsip'; setTimeout(() => window.dispatchEvent(new Event('resize')), 350)"
                    :class="activeTab === 'arsip' ? 'bg-[#e92027] text-white ring-2 ring-[#e92027] shadow-lg md:scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm hover:shadow-md'"
                    class="flex-1 py-3 px-4 md:px-6 rounded-xl font-bold text-sm md:text-base transition-all duration-300 text-center border border-gray-100">
                    Arsip
                </button>

                <button @click="activeTab = 'peminjaman'; setTimeout(() => window.dispatchEvent(new Event('resize')), 350)"
                    :class="activeTab === 'peminjaman' ? 'bg-[#e92027] text-white ring-2 ring-[#e92027] shadow-lg md:scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm hover:shadow-md'"
                    class="flex-1 py-3 px-4 md:px-6 rounded-xl font-bold text-sm md:text-base transition-all duration-300 text-center border border-gray-100">
                    Peminjaman
                </button>

                <button @click="activeTab = 'karyawan'; setTimeout(() => window.dispatchEvent(new Event('resize')), 350)"
                    :class="activeTab === 'karyawan' ? 'bg-[#e92027] text-white ring-2 ring-[#e92027] shadow-lg md:scale-105' : 'bg-white text-gray-600 hover:bg-gray-50 shadow-sm hover:shadow-md'"
                    class="flex-1 py-3 px-4 md:px-6 rounded-xl font-bold text-sm md:text-base transition-all duration-300 text-center border border-gray-100">
                    Monitoring Karyawan
                </button>
            </div>
        </div>

        <!-- MAIN CONTENT WRAPPER -->
        <div x-show="mounted"
             x-transition:enter="transition ease-out duration-700 delay-500"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-6 md:mt-8 container mx-auto px-4"
             style="display: none;"
        >

            {{-- ========================================================== --}}
            {{-- 1. TAB PEMINJAMAN                                          --}}
            {{-- ========================================================== --}}
            <div x-show="activeTab === 'peminjaman'" x-transition.opacity style="display: none;">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8">
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center h-28 border-b-4 border-gray-400 hover:-translate-y-1 transition duration-300">
                        <p class="text-gray-500 font-bold text-[10px] md:text-xs uppercase tracking-widest mb-1">Total Transaksi</p>
                        <p class="text-3xl md:text-4xl font-extrabold text-gray-600">{{ $dipinjam + $kembali }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center h-28 border-b-4 border-[#e92027] hover:-translate-y-1 transition duration-300">
                        <p class="text-gray-500 font-bold text-[10px] md:text-xs uppercase tracking-widest mb-1">Sedang Dipinjam</p>
                        <p class="text-3xl md:text-4xl font-extrabold text-[#e92027]">{{ $dipinjam }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center h-28 border-b-4 border-red-300 hover:-translate-y-1 transition duration-300">
                        <p class="text-gray-500 font-bold text-[10px] md:text-xs uppercase tracking-widest mb-1">Sudah Kembali</p>
                        <p class="text-3xl md:text-4xl font-extrabold text-red-300">{{ $kembali }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                    <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 z-10">
                            <div>
                                <h3 class="text-gray-800 font-bold text-base md:text-lg">Rasio Status</h3>
                                <p class="text-xs text-gray-400">Dipinjam vs Kembali</p>
                            </div>
                            <div class="bg-red-50 p-2 rounded-lg text-[#e92027]"><span class="text-lg">📉</span></div>
                        </div>
                        <div class="relative h-48 md:h-52 w-full flex justify-center items-center z-10"><canvas id="statusChart"></canvas></div>
                    </div>

                    <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-3">
                            <div>
                                <h3 class="text-gray-800 font-bold text-base md:text-lg">Media Arsip</h3>
                                <p class="text-xs text-gray-400">Fisik vs Digital</p>
                            </div>
                            <div class="bg-blue-50 p-2 rounded-lg text-blue-600"><span class="text-lg">📊</span></div>
                        </div>
                        <div class="relative h-48 md:h-52 w-full flex justify-center items-center"><canvas id="mediaChart"></canvas></div>
                    </div>
                </div>

                <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden mb-8">
                    <div class="flex justify-between items-center mb-6 z-10">
                        <div>
                            <h3 class="text-gray-800 font-bold text-base md:text-lg">Tren Bulanan</h3>
                            <p class="text-xs text-gray-400">Aktivitas Tahun {{ date('Y') }}</p>
                        </div>
                        <div class="bg-red-50 p-2 rounded-lg text-[#e92027]"><span class="text-lg">📈</span></div>
                    </div>
                    <div class="relative h-56 md:h-64 w-full z-10"><canvas id="trenChart"></canvas></div>
                </div>
            </div>

            {{-- ========================================================== --}}
            {{-- 2. TAB ARSIP                                               --}}
            {{-- ========================================================== --}}
            <div x-show="activeTab === 'arsip'" x-transition.opacity style="display: none;">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                    <div class="lg:col-span-2 bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Klasifikasi Arsip</h3>
                                <p class="text-xs text-gray-500">Berdasarkan kategori utama</p>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative"><canvas id="arsipKlasifikasiChart"></canvas></div>
                    </div>
                    <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Jenis Media</h3>
                                <p class="text-xs text-gray-500">Fisik vs Digital</p>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative"><canvas id="arsipMediaChart"></canvas></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                     <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Status Akhir</h3>
                                <p class="text-xs text-gray-500">Tindakan penyusutan</p>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative"><canvas id="arsipStatusChart"></canvas></div>
                    </div>
                    <div class="md:col-span-2 bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Volume Arsip per Tahun</h3>
                                <p class="text-xs text-gray-500">Tren jumlah arsip</p>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative"><canvas id="arsipTahunChart"></canvas></div>
                    </div>
                </div>
            </div>

            {{-- ========================================================== --}}
            {{-- 3. TAB MONITORING KARYAWAN (LEADERBOARD PER TAHAPAN)       --}}
            {{-- ========================================================== --}}
            <div x-show="activeTab === 'karyawan'" x-transition.opacity style="display: none;">

                <!-- Filter Toolbar Responsif -->
                <div class="mb-6 bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mx-4 md:mx-0">
                    <div class="flex items-center gap-2 mb-3 lg:hidden text-gray-700 font-bold text-sm border-b border-gray-50 pb-2">
                        <svg class="w-4 h-4 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg> Filter Kinerja
                    </div>

                    <form action="{{ route('beranda') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <input type="hidden" name="active_tab" value="karyawan">
                        @php $chevron = '<svg class="w-4 h-4 text-gray-500 absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none transition-transform duration-200" :class="open ? \'rotate-180\' : \'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>'; @endphp

                        <!-- Unit Kerja Filter -->
                        <div x-data="{ open: false }" class="relative w-full">
                            @php $unitLabel = request('unit_kerja') ? request('unit_kerja') : 'Semua Unit Kerja'; @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027] transition-all truncate">
                                <i class="fas fa-building text-gray-400 mr-1"></i> {{ $unitLabel }} {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors hover:bg-gray-50"><input type="radio" name="unit_kerja" value="" onchange="this.form.submit()" class="hidden"> Semua Unit Kerja</label>
                                @foreach($allUnits as $unit)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors truncate hover:bg-gray-50"><input type="radio" name="unit_kerja" value="{{ $unit->nama_unit }}" onchange="this.form.submit()" class="hidden"> {{ $unit->nama_unit }}</label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bulan Filter -->
                        <div x-data="{ open: false }" class="relative w-full">
                            @php $bulanLabel = request('bulan') ? DateTime::createFromFormat('!m', request('bulan'))->format('F') : 'Semua Bulan'; @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027] transition-all truncate">
                                <i class="far fa-calendar-alt text-gray-400 mr-1"></i> {{ $bulanLabel }} {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors hover:bg-gray-50"><input type="radio" name="bulan" value="" onchange="this.form.submit()" class="hidden"> Semua Bulan</label>
                                @foreach(range(1,12) as $m)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors hover:bg-gray-50"><input type="radio" name="bulan" value="{{ $m }}" onchange="this.form.submit()" class="hidden"> {{ DateTime::createFromFormat('!m', $m)->format('F') }}</label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Minggu Filter -->
                        <div x-data="{ open: false }" class="relative w-full">
                            @php $mingguLabel = request('minggu') ? 'Minggu Ke-' . request('minggu') : 'Semua Minggu'; @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027] transition-all truncate">
                                <i class="fas fa-calendar-week text-gray-400 mr-1"></i> {{ $mingguLabel }} {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors hover:bg-gray-50"><input type="radio" name="minggu" value="" onchange="this.form.submit()" class="hidden"> Semua Minggu</label>
                                @foreach(range(1, 5) as $w)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors hover:bg-gray-50"><input type="radio" name="minggu" value="{{ $w }}" onchange="this.form.submit()" class="hidden"> Minggu Ke-{{ $w }}</label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Reset Filter -->
                        @if(request()->hasAny(['unit_kerja', 'bulan', 'minggu']))
                        <div class="relative w-full">
                            <a href="{{ route('beranda') }}?active_tab=karyawan" class="w-full h-full flex items-center justify-center px-4 py-3 bg-red-50 text-[#e92027] hover:bg-[#e92027] hover:text-white rounded-xl text-sm font-bold transition-all shadow-sm">
                                <i class="fas fa-times mr-2"></i> Reset Filter
                            </a>
                        </div>
                        @endif
                    </form>
                </div>

                <!-- KPI CARDS SUMMARY -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6 md:mb-8 px-4 md:px-0">
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center flex flex-col justify-center col-span-2 sm:col-span-1 lg:col-span-1">
                        <div class="text-xs text-gray-500 font-semibold uppercase mb-1">Total Arsip</div>
                        <div class="text-2xl font-black text-gray-800">{{ $totalBox }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center flex flex-col justify-center">
                        <div class="text-xs text-gray-500 font-semibold uppercase mb-1">Pemilahan</div>
                        <div class="text-2xl font-black text-[#e92027]">{{ $pemilahan }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center flex flex-col justify-center">
                        <div class="text-xs text-gray-500 font-semibold uppercase mb-1">Pendataan</div>
                        <div class="text-2xl font-black text-[#e92027]">{{ $pendataan }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center flex flex-col justify-center">
                        <div class="text-xs text-gray-500 font-semibold uppercase mb-1">Pelabelan</div>
                        <div class="text-2xl font-black text-[#e92027]">{{ $pelabelan }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center flex flex-col justify-center">
                        <div class="text-xs text-gray-500 font-semibold uppercase mb-1">Alih Media</div>
                        <div class="text-2xl font-black text-[#e92027]">{{ $alihMedia ?? 0 }}</div>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center flex flex-col justify-center">
                        <div class="text-xs text-gray-500 font-semibold uppercase mb-1">Input E-Arsip</div>
                        <div class="text-2xl font-black text-[#e92027]">{{ $inputEArsip }}</div>
                    </div>
                </div>

                <!-- Row 1: Charts (Tahapan & Unit) -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8 mx-4 md:mx-0">
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-1 text-sm md:text-base border-l-4 border-[#e92027] pl-3">Volume per Tahapan Pengarsipan</h3>
                        <p class="text-[10px] text-gray-400 pl-4 mb-4">Total pencapaian seluruh staf</p>
                        <div class="relative h-64 md:h-72 w-full"><canvas id="tahapanChart"></canvas></div>
                    </div>
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-1 text-sm md:text-base border-l-4 border-[#e92027] pl-3">Sebaran Arsip per Unit (Top 10)</h3>
                        <p class="text-[10px] text-gray-400 pl-4 mb-4">Asal dokumen yang dikerjakan</p>
                        <div class="relative h-64 md:h-72 w-full flex justify-center"><canvas id="arsipUnitChart"></canvas></div>
                    </div>
                </div>

                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 mb-8 mx-4 md:mx-0">
                    <h3 class="font-bold text-gray-800 mb-1 text-sm md:text-base border-l-4 border-[#e92027] pl-3">Tren Arsip Masuk (Tahun {{ date('Y') }})</h3>
                    <p class="text-[10px] text-gray-400 pl-4 mb-4">Volume dokumen yang diserahkan ke Record Center</p>
                    <div class="relative h-56 md:h-72 w-full"><canvas id="arsipBulananChart"></canvas></div>
                </div>

                <!-- ======================================================= -->
                <!-- ROW 3: LEADERBOARD PER TAHAPAN (ALPINE JS TABS)         -->
                <!-- ======================================================= -->
                <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-200 flex flex-col mb-8 mx-4 md:mx-0 overflow-hidden" x-data="{ stageTab: 'Pemilahan' }">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                        <div class="bg-white p-2 rounded shadow-sm text-[#e92027]"><i class="fas fa-medal"></i></div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm md:text-base">Kinerja Staf</h3>
                            <p class="text-[10px] md:text-xs text-gray-500">Produktivitas penyelesaian tugas berdasarkan tahapan</p>
                        </div>
                    </div>

                    <!-- Alpine Tabs Header -->
                    <div class="flex overflow-x-auto border-b border-gray-200 hide-scrollbar bg-white">
                        @foreach(['Pemilahan', 'Pendataan', 'Pelabelan', 'Alih Media', 'Input E-Arsip'] as $stage)
                        <button @click="stageTab = '{{ $stage }}'"
                                :class="stageTab === '{{ $stage }}' ? 'border-[#e92027] text-[#e92027] bg-red-50/50' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                                class="flex-1 min-w-[120px] py-4 px-4 font-bold text-xs md:text-sm border-b-[3px] transition-colors whitespace-nowrap focus:outline-none flex flex-col items-center justify-center gap-1">
                            <span>{{ $stage }}</span>
                        </button>
                        @endforeach
                    </div>

                    <!-- Alpine Tabs Content -->
                    <div class="p-0">
                        @foreach(['Pemilahan', 'Pendataan', 'Pelabelan', 'Alih Media', 'Input E-Arsip'] as $stage)
                        <div x-show="stageTab === '{{ $stage }}'" x-cloak class="w-full overflow-x-auto">
                            <table class="w-full text-xs md:text-sm text-left min-w-[500px]">
                                <thead class="bg-gray-50 text-gray-500 text-[10px] md:text-xs uppercase">
                                    <tr>
                                        <th class="px-6 py-3 font-bold w-12 text-center">No</th>
                                        <th class="px-6 py-3 font-bold">Nama Staf (PIC)</th>
                                        <th class="px-6 py-3 font-bold text-center">Total {{ $stage == 'Alih Media' ? 'Lembar' : 'Box' }}</th>
                                        <th class="px-6 py-3 font-bold w-1/3 md:w-2/5 text-center">Visualisasi Kinerja</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($performancePerStage[$stage] as $index => $stat)
                                    <tr class="hover:bg-red-50/50 transition-colors group">
                                        <td class="px-6 py-4 text-center font-bold text-gray-400 group-hover:text-[#e92027]">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-800 flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-[#e92027] font-bold border border-red-100 shadow-sm">
                                                {{ substr($stat->user->nama ?? 'U', 0, 1) }}
                                            </div>
                                            {{ $stat->user->nama ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 text-center text-[#e92027] font-black text-lg">
                                            {{ number_format($stat->total_selesai, 0, ',', '.') }}
                                            <span class="text-[9px] font-medium text-gray-500 uppercase ml-0.5">{{ $stage == 'Alih Media' ? 'Lbr' : 'Box' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-grow bg-gray-100 rounded-full h-2 md:h-2.5 overflow-hidden">
                                                    <div class="bg-[#e92027] h-full rounded-full transition-all duration-1000" style="width: {{ $stat->persentase_visual }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <i class="fas fa-medal text-4xl text-gray-200 mb-3"></i>
                                            <p class="text-gray-400 font-medium">Belum ada staf yang mencatat progress di tahap {{ $stage }}.</p>
                                        </td>
                                    </tr>
                                    @endforelse

                                    <!-- Baris Total Bawah -->
                                    @if($performancePerStage[$stage]->count() > 0)
                                    <tr class="bg-gray-50 border-t-2 border-gray-200">
                                        <td colspan="2" class="px-6 py-4 text-right font-bold text-gray-600 uppercase text-xs">Total</td>
                                        <td class="px-6 py-4 text-center font-black text-gray-900 text-xl">
                                            {{ number_format($performancePerStage[$stage]->sum('total_selesai'), 0, ',', '.') }}
                                            <span class="text-[9px] font-medium text-gray-500 uppercase ml-0.5">{{ $stage == 'Alih Media' ? 'Lbr' : 'Box' }}</span>
                                        </td>
                                        <td></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ApexCharts Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // GLOBAL CONFIG CHART.JS
            Chart.defaults.font.family = "'Montserrat', sans-serif";
            Chart.defaults.color = '#64748b';

            // ==========================================
            // CHART.JS: PEMINJAMAN TAB
            // ==========================================
            const ctxStatus = document.getElementById('statusChart');
            if (ctxStatus) {
                new Chart(ctxStatus.getContext('2d'), {
                    type: 'doughnut',
                    data: { labels: ['Dipinjam', 'Kembali'], datasets: [{ data: [{{ $dipinjam }}, {{ $kembali }}], backgroundColor: ['#e92027', '#fca5a5'], borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } }
                });
            }

            const ctxMediaP = document.getElementById('mediaChart');
            if (ctxMediaP) {
                new Chart(ctxMediaP.getContext('2d'), {
                    type: 'pie',
                    data: { labels: ['Hardfile', 'Softfile'], datasets: [{ data: [{{ $mediaHardfile }}, {{ $mediaSoftfile }}], backgroundColor: ['#e92027', '#fc8181'], borderWidth: 2, borderColor: '#fff' }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right', labels: { boxWidth: 10, font: { size: 10 } } } } }
                });
            }

            const ctxTren = document.getElementById('trenChart');
            if (ctxTren) {
                new Chart(ctxTren.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [
                            { label: 'Dipinjam', data: @json($dataDipinjam), backgroundColor: '#e92027', borderRadius: 3 },
                            { label: 'Kembali', data: @json($dataKembali), backgroundColor: '#fca5a5', borderRadius: 3 }
                        ]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, grid: { borderDash: [4, 4] } } }, plugins: { legend: { display: false } } }
                });
            }

            // ==========================================
            // CHART.JS: ARSIP TAB
            // ==========================================
            const ctxKlasifikasi = document.getElementById('arsipKlasifikasiChart');
            if (ctxKlasifikasi) {
                new Chart(ctxKlasifikasi.getContext('2d'), {
                    type: 'bar',
                    data: { labels: @json($arsipKlasifikasiChart['labels']), datasets: [{ label: 'Jumlah', data: @json($arsipKlasifikasiChart['data']), backgroundColor: '#e92027', borderRadius: 4 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { borderDash: [4, 4] } } } }
                });
            }

            const ctxMediaA = document.getElementById('arsipMediaChart');
            if (ctxMediaA) {
                new Chart(ctxMediaA.getContext('2d'), {
                    type: 'doughnut',
                    data: { labels: @json($arsipMediaChart['labels']), datasets: [{ data: @json($arsipMediaChart['data']), backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'], borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, font: { size: 10 } } } } }
                });
            }

            const ctxTahun = document.getElementById('arsipTahunChart');
            if (ctxTahun) {
                new Chart(ctxTahun.getContext('2d'), {
                    type: 'line',
                    data: { labels: @json($arsipTahunChart['labels']), datasets: [{ label: 'Volume', data: @json($arsipTahunChart['data']), borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.1)', fill: true, tension: 0.4 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { borderDash: [4, 4] } } } }
                });
            }

            const ctxStatusA = document.getElementById('arsipStatusChart');
            if (ctxStatusA) {
                new Chart(ctxStatusA.getContext('2d'), {
                    type: 'doughnut',
                    data: { labels: @json($arsipStatusChart['labels']), datasets: [{ data: @json($arsipStatusChart['data']), backgroundColor: ['#10b981', '#ef4444', '#f59e0b'], borderWidth: 0 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, font: { size: 10 } } } } }
                });
            }

            // ==========================================
            // CHART.JS: KARYAWAN TAB
            // ==========================================
            const ctxTahapan = document.getElementById('tahapanChart');
            if (ctxTahapan) {
                new Chart(ctxTahapan.getContext('2d'), {
                    type: 'bar',
                    data: { labels: @json($tahapanChartData['labels']), datasets: [{ data: @json($tahapanChartData['data']), backgroundColor: ['#e92027', '#b91c1c', '#ef4444', '#fca5a5', '#10b981'], borderRadius: 4 }] },
                    options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { borderDash: [4, 4] } }, y: { grid: { display: false } } } }
                });
            }

            const ctxBulanan = document.getElementById('arsipBulananChart');
            if (ctxBulanan) {
                new Chart(ctxBulanan.getContext('2d'), {
                    type: 'line',
                    data: { labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'], datasets: [{ data: @json($arsipBulananData), borderColor: '#e92027', backgroundColor: 'rgba(233,32,39,0.05)', fill: true, tension: 0.4 }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { grid: { borderDash: [4, 4] } } } }
                });
            }

            const ctxUnit = document.getElementById('arsipUnitChart');
            if (ctxUnit) {
                new Chart(ctxUnit.getContext('2d'), {
                    type: 'doughnut',
                    data: { labels: @json($arsipUnitChart['labels']), datasets: [{ data: @json($arsipUnitChart['data']), backgroundColor: ['#e92027', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#f97316'], borderWidth: 1, borderColor: '#fff' }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '55%', plugins: { legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8, font: { size: 9 } } } } }
                });
            }
        });
    </script>
</x-layout>
