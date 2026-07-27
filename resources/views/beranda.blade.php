<x-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div x-data="{ activeTab: '{{ request()->query('active_tab', 'arsip') }}', mounted: false }"
         x-init="setTimeout(() => mounted = true, 100)"
         class="pb-20 bg-gray-50 min-h-screen">

        <!-- Background Header -->
        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-28 pt-12 md:pb-32 md:pt-16 px-6 md:px-8 -mt-4 -mx-4 md:-mt-6 md:-mx-6 mb-8 rounded-b-[2rem] md:rounded-b-[3rem] shadow-2xl relative overflow-hidden">
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
                    <path fill="#580000" opacity="0.2" d="M800 0 L1400 0 L1400 400 L600 600 Z" />
                    <path fill="url(#polyGrad)" opacity="0.3" d="M500 600 L1200 600 L800 200 Z" />
                </svg>
            </div>

            <!-- Ornamental Icon -->
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
             class="mt-6 md:mt-8 container mx-auto px-0 md:px-4"
             style="display: none;"
        >

            {{-- 1. TAB PEMINJAMAN --}}
            <div x-show="activeTab === 'peminjaman'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

                {{-- STATS SUMMARY ROW --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6 mb-8 px-4 md:px-0">
                    <!-- Card 1: Total Transaksi -->
                    <div class="bg-white rounded-xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col items-center justify-center text-center h-28 border-b-4 border-gray-400 hover:-translate-y-1 transition duration-300">
                        <p class="text-gray-500 font-bold text-[10px] md:text-xs uppercase tracking-widest mb-1">Total Transaksi</p>
                        <p class="text-3xl md:text-4xl font-extrabold text-gray-600">{{ $dipinjam + $kembali }}</p>
                    </div>

                    <!-- Card 2: Sedang Dipinjam -->
                    <div class="bg-white rounded-xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col items-center justify-center text-center h-28 border-b-4 border-[#e92027] hover:-translate-y-1 transition duration-300">
                        <p class="text-gray-500 font-bold text-[10px] md:text-xs uppercase tracking-widest mb-1">Sedang Dipinjam</p>
                        <p class="text-3xl md:text-4xl font-extrabold text-[#e92027]">{{ $dipinjam }}</p>
                    </div>

                    <!-- Card 3: Sudah Kembali -->
                    <div class="bg-white rounded-xl p-5 shadow-[0_4px_20px_rgb(0,0,0,0.03)] flex flex-col items-center justify-center text-center h-28 border-b-4 border-red-300 hover:-translate-y-1 transition duration-300">
                        <p class="text-gray-500 font-bold text-[10px] md:text-xs uppercase tracking-widest mb-1">Sudah Kembali</p>
                        <p class="text-3xl md:text-4xl font-extrabold text-red-300">{{ $kembali }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 mb-6 px-4 md:px-0">
                    <div class="w-1.5 h-6 md:h-8 bg-[#e92027] rounded-full"></div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Analisis Peminjaman</h2>
                </div>

                {{-- CHART ROW 1: Donut & Pie --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 px-4 md:px-0">
                    <!-- 1. RASIO STATUS (Donut) -->
                    <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-xl border border-gray-100/50 flex flex-col relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 z-10 relative">
                            <div>
                                <h3 class="text-gray-800 font-bold text-base md:text-lg">Rasio Status</h3>
                                <p class="text-xs text-gray-400">Dipinjam vs Kembali</p>
                            </div>
                            <div class="bg-red-50 p-2 rounded-lg text-[#e92027]"><span class="text-lg">📉</span></div>
                        </div>

                        <div class="relative h-48 md:h-52 w-full flex justify-center items-center z-10">
                            <canvas id="statusChart"></canvas>
                        </div>

                        {{-- Decorative Blob --}}
                        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-red-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
                    </div>

                    <!-- 2. MEDIA ARSIP (Pie) -->
                    <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-xl border border-gray-100/50 flex flex-col relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
                            <h3 class="text-gray-800 font-bold text-base md:text-lg flex items-center gap-2"><span class="text-blue-500">💿</span> Media Arsip</h3>
                            <div class="bg-blue-50 p-2 rounded-lg text-blue-600"><span class="text-lg">📊</span></div>
                        </div>
                        <div class="relative h-48 md:h-52 w-full flex justify-center items-center">
                            <canvas id="mediaChart"></canvas>
                        </div>
                        <div class="mt-4 text-center">
                            <span class="text-xs font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-200">Fisik (Hardfile) vs Digital (Softfile)</span>
                        </div>
                    </div>
                </div>

                <!-- 3. TREN BULANAN (Bar) -->
                <div class="px-4 md:px-0">
                    <div class="bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl shadow-xl border border-gray-100/50 flex flex-col relative overflow-hidden mb-6">
                        <div class="flex justify-between items-center mb-6 z-10 relative">
                            <div>
                                <h3 class="text-gray-800 font-bold text-base md:text-lg">Tren Bulanan</h3>
                                <p class="text-xs text-gray-400">Aktivitas Tahun {{ date('Y') }}</p>
                            </div>
                            <div class="bg-red-50 p-2 rounded-lg text-[#e92027]"><span class="text-lg">📈</span></div>
                        </div>

                        <div class="relative h-56 md:h-64 w-full z-10">
                            <canvas id="trenChart"></canvas>
                        </div>

                        {{-- Decorative Blob --}}
                        <div class="absolute -top-10 -left-10 w-32 h-32 bg-red-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
                    </div>
                </div>

                <div class="flex justify-center mb-12 mt-8 px-4 md:px-0">
                    <a href="/peminjaman" class="inline-flex items-center justify-center px-6 md:px-8 py-3.5 md:py-4 text-sm md:text-base font-bold text-white bg-[#e92027] rounded-2xl shadow-lg hover:bg-[#c41820] hover:shadow-xl transition-all transform hover:-translate-y-1">
                        <span>Kelola Data Peminjaman</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- 2. TAB ARSIP --}}
            <div x-show="activeTab === 'arsip'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 style="display: none;">

                {{-- Row 1: Klasifikasi & Media --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8 px-4 md:px-0">
                    {{-- Chart Klasifikasi --}}
                    <div class="lg:col-span-2 bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Klasifikasi Arsip</h3>
                                <p class="text-xs text-gray-500">Berdasarkan kategori utama</p>
                            </div>
                            <div class="bg-red-50 text-red-600 rounded-lg p-2 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative">
                            <canvas id="arsipKlasifikasiChart"></canvas>
                        </div>
                    </div>

                    {{-- Chart Media --}}
                    <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Jenis Media</h3>
                                <p class="text-xs text-gray-500">Distribusi media fisik/digital</p>
                            </div>
                            <div class="bg-blue-50 text-blue-600 rounded-lg p-2 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative">
                            <canvas id="arsipMediaChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Row 2: Tahun & Status --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8 px-4 md:px-0">
                     {{-- Chart Status --}}
                     <div class="bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Status Akhir</h3>
                                <p class="text-xs text-gray-500">Tindakan penyusutan</p>
                            </div>
                            <div class="bg-green-50 text-green-600 rounded-lg p-2 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative">
                            <canvas id="arsipStatusChart"></canvas>
                        </div>
                    </div>

                    {{-- Chart Tahun --}}
                    <div class="md:col-span-2 bg-white p-4 md:p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h3 class="font-bold text-gray-800 text-base md:text-lg">Volume Arsip per Tahun</h3>
                                <p class="text-xs text-gray-500">Tren jumlah arsip berdasarkan tahun dokumen</p>
                            </div>
                            <div class="bg-purple-50 text-purple-600 rounded-lg p-2 hidden sm:block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        <div class="h-56 md:h-64 relative">
                            <canvas id="arsipTahunChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. TAB MONITORING KARYAWAN --}}
            <div x-show="activeTab === 'karyawan'"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 style="display: none;">

                <!-- Filter Toolbar Responsif -->
                <!-- Filter Toolbar Responsif (Custom Dropdown) -->
                <div class="mb-6 bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mx-4 md:mx-0">

                    <div class="flex items-center gap-2 mb-3 lg:hidden text-gray-700 font-bold text-sm border-b border-gray-50 pb-2">
                        <svg class="w-4 h-4 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter Data Monitoring
                    </div>

                    <form action="{{ route('beranda') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        <input type="hidden" name="active_tab" value="karyawan">

                        @php
                            $chevron = '<svg class="w-4 h-4 text-gray-500 absolute right-4 top-1/2 transform -translate-y-1/2 pointer-events-none transition-transform duration-200" :class="open ? \'rotate-180\' : \'\'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
                        @endphp

                        <!-- 1. Custom PIC Filter -->
                        <div x-data="{ open: false }" class="relative w-full">
                            @php
                                $selectedPic = request('pic') ? $allPics->firstWhere('id', request('pic')) : null;
                                $picLabel = $selectedPic ? $selectedPic->nama : 'Semua PIC';
                            @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all truncate"
                                    :class="open ? 'border-[#e92027] ring-2 ring-[#e92027]/20 bg-white' : ''">
                                {{ $picLabel }}
                                {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" x-transition.opacity.duration.200ms
                                 class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('pic') == '' ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="pic" value="" onchange="this.form.submit()" class="hidden" {{ request('pic') == '' ? 'checked' : '' }}>
                                    Semua PIC
                                </label>
                                @foreach($allPics as $p)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('pic') == $p->id ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="pic" value="{{ $p->id }}" onchange="this.form.submit()" class="hidden" {{ request('pic') == $p->id ? 'checked' : '' }}>
                                    {{ $p->nama }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 2. Custom Unit Kerja Filter -->
                        <div x-data="{ open: false }" class="relative w-full">
                            @php
                                $unitLabel = request('unit_kerja') ? request('unit_kerja') : 'Semua Unit Kerja';
                            @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all truncate"
                                    :class="open ? 'border-[#e92027] ring-2 ring-[#e92027]/20 bg-white' : ''">
                                {{ $unitLabel }}
                                {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" x-transition.opacity.duration.200ms
                                 class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('unit_kerja') == '' ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="unit_kerja" value="" onchange="this.form.submit()" class="hidden" {{ request('unit_kerja') == '' ? 'checked' : '' }}>
                                    Semua Unit Kerja
                                </label>
                                @foreach($allUnits as $unit)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors truncate {{ request('unit_kerja') == $unit->nama_unit ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="unit_kerja" value="{{ $unit->nama_unit }}" onchange="this.form.submit()" class="hidden" {{ request('unit_kerja') == $unit->nama_unit ? 'checked' : '' }}>
                                    {{ $unit->nama_unit }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 3. Custom Bulan Filter -->
                        <div x-data="{ open: false }" class="relative w-full">
                            @php
                                $bulanLabel = request('bulan') ? DateTime::createFromFormat('!m', request('bulan'))->format('F') : 'Semua Bulan';
                            @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all truncate"
                                    :class="open ? 'border-[#e92027] ring-2 ring-[#e92027]/20 bg-white' : ''">
                                {{ $bulanLabel }}
                                {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" x-transition.opacity.duration.200ms
                                 class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('bulan') == '' ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="bulan" value="" onchange="this.form.submit()" class="hidden" {{ request('bulan') == '' ? 'checked' : '' }}>
                                    Semua Bulan
                                </label>
                                @foreach(range(1,12) as $m)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('bulan') == $m ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="bulan" value="{{ $m }}" onchange="this.form.submit()" class="hidden" {{ request('bulan') == $m ? 'checked' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- 4. Custom Minggu Filter -->
                        <div x-data="{ open: false }" class="relative w-full">
                            @php
                                $mingguLabel = request('minggu') ? 'Minggu Ke-' . request('minggu') : 'Semua Minggu';
                            @endphp
                            <button type="button" @click="open = !open" @click.outside="open = false"
                                    class="w-full bg-gray-50 hover:bg-gray-100 border border-gray-200 text-left pl-4 pr-10 py-3 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all truncate"
                                    :class="open ? 'border-[#e92027] ring-2 ring-[#e92027]/20 bg-white' : ''">
                                {{ $mingguLabel }}
                                {!! $chevron !!}
                            </button>
                            <div x-show="open" style="display: none;" x-transition.opacity.duration.200ms
                                 class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl py-1 max-h-60 overflow-y-auto top-full left-0">

                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('minggu') == '' ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="minggu" value="" onchange="this.form.submit()" class="hidden" {{ request('minggu') == '' ? 'checked' : '' }}>
                                    Semua Minggu
                                </label>

                                <!-- Label Pembatas -->
                                <div class="px-4 py-2 bg-gray-100/50 text-gray-400 font-bold text-[11px] uppercase tracking-widest pointer-events-none">
                                    -- Minggu Ke --
                                </div>

                                @foreach(range(1, 5) as $w)
                                <label class="block px-4 py-2.5 text-sm cursor-pointer transition-colors {{ request('minggu') == $w ? 'bg-[#e92027] text-white font-bold' : 'text-gray-700 hover:bg-red-50 hover:text-[#e92027]' }}">
                                    <input type="radio" name="minggu" value="{{ $w }}" onchange="this.form.submit()" class="hidden" {{ request('minggu') == $w ? 'checked' : '' }}>
                                    Minggu Ke-{{ $w }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                         <!-- 5. Tombol Reset -->
                        @if(request()->hasAny(['pic', 'unit_kerja', 'bulan', 'minggu']))
                        <div class="relative w-full sm:col-span-2 lg:col-span-1">
                            <a href="{{ route('beranda') }}?active_tab=karyawan" class="w-full h-full flex items-center justify-center px-4 py-3 bg-red-50 text-[#e92027] hover:bg-[#e92027] hover:text-white rounded-xl text-sm font-bold transition-all shadow-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reset Filter
                            </a>
                        </div>
                        @endif
                    </form>
                </div>

                <!-- KPI Cards Responsive -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3 md:gap-4 mb-6 px-4 md:px-0">
                    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center col-span-2 sm:col-span-1 xl:col-span-1">
                        <p class="text-2xl md:text-3xl font-black text-gray-800">{{ $totalBox }}</p>
                        <p class="text-[10px] md:text-xs text-gray-500 font-medium">Total Box Arsip</p>
                    </div>
                    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 text-center sm:text-left">
                        <p class="text-[10px] md:text-xs text-gray-500 uppercase font-semibold">Arsip Masuk</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $bulanIniArsip }}</p>
                    </div>
                    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 text-center sm:text-left">
                        <p class="text-[10px] md:text-xs text-gray-500 uppercase font-semibold">Pemilahan</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $pemilahan }}</p>
                    </div>
                    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 text-center sm:text-left">
                        <p class="text-[10px] md:text-xs text-gray-500 uppercase font-semibold">Pendataan</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $pendataan }}</p>
                    </div>
                    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 text-center sm:text-left">
                        <p class="text-[10px] md:text-xs text-gray-500 uppercase font-semibold">Pelabelan</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $pelabelan }}</p>
                    </div>
                    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 text-center sm:text-left">
                        <p class="text-[10px] md:text-xs text-gray-500 uppercase font-semibold">Alih Media</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $alihMedia ?? 0 }}</p>
                    </div>
                    <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-100 text-center sm:text-left">
                        <p class="text-[10px] md:text-xs text-gray-500 uppercase font-semibold">Input E-Arsip</p>
                        <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $inputEArsip }}</p>
                    </div>
                </div>

                <!-- Row 1: Tahapan & Rata-rata Aktivitas -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8 px-4 md:px-0">
                    <!-- Left: Tahapan Pengarsipan (Horizontal Bar) -->
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 h-full">
                        <h3 class="font-bold text-gray-800 mb-4 border-l-4 border-[#e92027] pl-3 text-sm md:text-base">Tahapan Pengarsipan</h3>
                        <div class="relative h-64 md:h-80 w-full">
                            <canvas id="tahapanChart"></canvas>
                        </div>
                    </div>

                    <!-- Right: Rata-rata Aktivitas PIC (Bar Chart) -->
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
                        <div class="mb-2">
                            <h3 class="font-bold text-gray-800 text-sm md:text-base">Rata-rata Aktivitas PIC</h3>
                            <p class="text-[10px] md:text-xs text-gray-500">Persentase penyelesaian seluruh tahapan</p>
                        </div>
                        <div id="activityChart" class="flex-1 w-full min-h-[250px] md:min-h-[300px]"></div>
                    </div>
                </div>

                <!-- Row 2: Tren & Unit Kerja -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8 px-4 md:px-0">
                    <!-- Left: Trend Arsip Masuk (Line) -->
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-4 border-l-4 border-[#e92027] pl-3 text-sm md:text-base">Tren Arsip Masuk (Tahun {{ date('Y') }})</h3>
                        <div class="relative h-56 md:h-72 w-full">
                            <canvas id="arsipBulananChart"></canvas>
                        </div>
                    </div>

                    <!-- Right: Arsip per Unit (Donut) -->
                    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200">
                        <h3 class="font-bold text-gray-800 mb-4 border-l-4 border-[#e92027] pl-3 text-sm md:text-base">Arsip per Unit Kerja (Top 10)</h3>
                        <div class="relative h-56 md:h-72 w-full flex justify-center">
                            <canvas id="arsipUnitChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Alih Media Chart -->
                <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 mb-6 md:mb-8 mx-4 md:mx-0">
                    <h3 class="font-bold text-gray-800 mb-4 border-l-4 border-[#e92027] pl-3 text-sm md:text-base">Aktivitas Alih Media per PIC</h3>
                    <div class="relative h-56 md:h-72 w-full">
                        <canvas id="alihMediaChart"></canvas>
                    </div>
                </div>

                <!-- Row 4: Pemilahan Arsip Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col mb-6 md:mb-8 mx-4 md:mx-0">
                    <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 text-sm md:text-base">Pemilahan Arsip</h3>
                        <span class="text-[10px] md:text-xs font-semibold bg-gray-200 text-gray-600 px-2 py-1 rounded">Target vs Realisasi</span>
                    </div>
                    <div class="overflow-x-auto p-0 w-full">
                        <table class="w-full text-xs md:text-sm text-left min-w-[500px]">
                            <thead class="bg-[#e92027] text-white text-[10px] md:text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3">PIC</th>
                                    <th class="px-4 py-3 text-center"># Target</th>
                                    <th class="px-4 py-3 text-center"># Selesai</th>
                                    <th class="px-4 py-3 text-center">% Progress</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pemilahanStats as $stat)
                                <tr class="hover:bg-red-50 transition-colors">
                                    <td class="px-4 py-2 font-medium text-gray-800">{{ $stat->user->nama ?? 'Unknown' }}</td>
                                    <td class="px-4 py-2 text-center text-gray-600 font-mono">{{ $stat->total_target }}</td>
                                    <td class="px-4 py-2 text-center text-gray-800 font-bold font-mono">{{ $stat->total_selesai }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-12 md:w-16 h-1.5 md:h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-[#e92027]" style="width: {{ $stat->persentase }}%"></div>
                                            </div>
                                            <span class="text-[10px] md:text-xs font-bold">{{ $stat->persentase }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <!-- Total Row -->
                                <tr class="bg-[#e92027] text-white font-bold">
                                    <td class="px-4 py-2">Total</td>
                                    <td class="px-4 py-2 text-center">{{ $pemilahanStats->sum('total_target') }}</td>
                                    <td class="px-4 py-2 text-center">{{ $pemilahanStats->sum('total_selesai') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        @php
                                            $grandTotalTarget = $pemilahanStats->sum('total_target');
                                            $grandTotalSelesai = $pemilahanStats->sum('total_selesai');
                                            $grandPercent = $grandTotalTarget > 0 ? round(($grandTotalSelesai / $grandTotalTarget) * 100) : 0;
                                        @endphp
                                        {{ $grandPercent }}%
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BOTTOM ROW: Detailed Matrix -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col mb-8 mx-4 md:mx-0">
                    <div class="px-4 py-3 md:px-6 md:py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-bold text-gray-800 text-sm md:text-base">Detail Aktivitas Pengarsipan</h3>
                    </div>
                    <!-- WRAPPER OVERFLOW UNTUK TABEL PANJANG -->
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-xs md:text-sm text-left border-collapse min-w-[1000px]">
                            <thead>
                                <!-- Top Header: Activities -->
                                <tr class="bg-[#e92027] text-white text-[10px] md:text-xs uppercase">
                                    <th class="px-4 py-3 border-r border-[#c41820] w-32 md:w-48">Key Activity</th>
                                    <th class="px-4 py-3 border-r border-[#c41820] w-32 md:w-48"></th>
                                    @foreach($tableStages as $stage)
                                        <th colspan="3" class="px-4 py-3 text-center border-r border-[#c41820]">{{ $stage }}</th>
                                    @endforeach
                                    <th class="px-4 py-3 text-center border-r border-[#c41820] bg-emerald-900">Done</th>
                                </tr>
                                <!-- Sub Header: Metrics -->
                                <tr class="bg-[#c41820] text-white text-[9px] md:text-[10px] uppercase tracking-wider">
                                    <th class="px-4 py-2 border-r border-[#a0131a]">PIC</th>
                                    <th class="px-4 py-2 border-r border-[#a0131a]">Unit Kerja</th>
                                    @foreach($tableStages as $stage)
                                        <th class="px-2 py-2 text-center w-12 md:w-16 bg-[#b91c1c] border-r border-[#a0131a] text-red-100"># Krj</th>
                                        <th class="px-2 py-2 text-center w-12 md:w-16 bg-[#b91c1c] border-r border-[#a0131a] text-yellow-200"># Tgt</th>
                                        <th class="px-2 py-2 text-center w-12 md:w-16 bg-[#b91c1c] border-r border-[#a0131a] text-green-200">%</th>
                                    @endforeach
                                    <th class="px-2 py-2 text-center w-12 md:w-16 bg-[#b91c1c] border-r border-[#a0131a] text-white">✓</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($matrixData as $row)
                                <tr class="hover:bg-red-50 transition-colors group">
                                    <td class="px-4 py-3 font-semibold text-gray-800 border-r border-gray-100 group-hover:bg-red-100 transition-colors truncate max-w-[150px]">
                                        {{ $row['user']->nama }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-600 border-r border-gray-100 group-hover:bg-red-50 transition-colors truncate max-w-[150px]">
                                        {{ $row['unit_kerja'] }}
                                    </td>
                                    @foreach($tableStages as $stage)
                                        @php $data = $row[$stage]; @endphp
                                        <td class="px-2 py-3 text-center border-r border-gray-50 {{ $data['selesai'] > 0 ? 'text-[#e92027] font-bold bg-red-50/50' : 'text-gray-300' }}">
                                            {{ $data['selesai'] > 0 ? $data['selesai'] : '-' }}
                                        </td>
                                        <td class="px-2 py-3 text-center border-r border-gray-50 {{ $data['target'] > 0 ? 'text-gray-700 font-mono' : 'text-gray-300' }}">
                                            {{ $data['target'] > 0 ? $data['target'] : '-' }}
                                        </td>
                                        <td class="px-2 py-3 text-center border-r border-gray-100">
                                            @if($data['target'] > 0)
                                                <span class="{{ $data['progress'] == 100 ? 'text-green-600 font-bold' : ($data['progress'] > 50 ? 'text-blue-600' : 'text-orange-500') }}">
                                                    {{ $data['progress'] }}%
                                                </span>
                                            @else
                                                <span class="text-gray-200">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                        @if(isset($row['Input E-Arsip']) && $row['Input E-Arsip']['progress'] == 100)
                                            <span class="inline-flex items-center justify-center w-5 h-5 md:w-6 md:h-6 bg-emerald-100 text-emerald-600 rounded-full font-bold shadow-sm text-xs md:text-sm">
                                                ✓
                                            </span>
                                        @else
                                            <span class="text-gray-200">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ 3 + count($tableStages) * 3 }}" class="px-6 py-8 text-center text-gray-400">
                                        Belum ada data aktivitas monitoring.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ApexCharts Script -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartStats = @json($chartStats);

            if(chartStats && chartStats.length > 0) {
                const categories = chartStats.map(s => s.user ? s.user.nama : 'Unknown');
                const seriesData = chartStats.map(s => s.persentase);

                var options = {
                    series: [{
                        name: 'Penyelesaian',
                        data: seriesData
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        toolbar: { show: false },
                        fontFamily: 'inherit'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: false,
                            columnWidth: '55%',
                            distributed: true,
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) { return val + "%"; },
                        offsetY: -20,
                        style: { fontSize: '12px', colors: ["#334155"] }
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: categories,
                        labels: {
                            style: { fontSize: '10px' },
                            rotate: -45,
                            trim: true
                        }
                    },
                    yaxis: {
                        max: 100,
                        title: { text: 'Persentase (%)' }
                    },
                    grid: { strokeDashArray: 4 },
                    theme: { mode: 'light' },
                    colors: ['#e92027', '#c41820', '#ef4444', '#f87171', '#fca5a5'] // Red shades matching Semen Padang
                };

                var chart = new ApexCharts(document.querySelector("#activityChart"), options);
                chart.render();
            } else {
                document.querySelector("#activityChart").innerHTML = '<div class="flex items-center justify-center h-full text-gray-400 text-sm">Belum ada data visualisasi</div>';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. STATUS (DONUT) ---
            const ctxStatus = document.getElementById('statusChart');
            if (ctxStatus) {
                new Chart(ctxStatus.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Dipinjam', 'Kembali'],
                        datasets: [{
                            data: [{{ $dipinjam }}, {{ $kembali }}],
                            backgroundColor: ['#e92027', '#fca5a5'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // --- 2. TREN (STACKED BAR) ---
            const ctxTren = document.getElementById('trenChart');
            if (ctxTren) {
                new Chart(ctxTren.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [
                            {
                                label: 'Dipinjam',
                                data: @json($dataDipinjam),
                                backgroundColor: ['#e92027'],
                                borderRadius: 2,
                            },
                            {
                                label: 'Kembali',
                                data: @json($dataKembali),
                                backgroundColor: ['#fca5a5'],
                                borderRadius: 2,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { stacked: true, grid: { display: false }, ticks: { font: { size: 10 } } },
                            y: { stacked: true, beginAtZero: true, grid: { borderDash: [4, 4] }, ticks: { font: { size: 10 } } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }

            // --- 4. MEDIA (PIE) ---
            const ctxMedia = document.getElementById('mediaChart');
            if (ctxMedia) {
                new Chart(ctxMedia.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: ['Hardfile', 'Softfile'],
                        datasets: [{
                            data: [{{ $mediaHardfile }}, {{ $mediaSoftfile }}],
                            backgroundColor: ['#e92027', '#fc8181'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { boxWidth: 10, padding: 10, font: { family: 'Montserrat', size: 10 } }
                            }
                        }
                    }
                });
            }

            // --- 3. CHART TAHAPAN PENGARSIPAN (Horizontal Bar) ---
            const ctxTahapan = document.getElementById('tahapanChart');
            if (ctxTahapan) {
                new Chart(ctxTahapan.getContext('2d'), {
                    type: 'bar', // Forcing horizontal
                    data: {
                        labels: @json($tahapanChartData['labels']),
                        datasets: [{
                            label: 'Jumlah Box',
                            data: @json($tahapanChartData['data']),
                            backgroundColor: ['#e92027', '#b91c1c', '#ef4444', '#fca5a5'],
                            borderRadius: 4
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Convert to Horizontal Bar
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) { return context.parsed.x + ' Box'; }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4], color: '#f3f4f6' },
                                ticks: { font: { size: 10 } }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }

            // --- 4. CHART ARSIP MASUK PER BULAN (Line Chart) ---
            const ctxBulanan = document.getElementById('arsipBulananChart');
            if (ctxBulanan) {
                new Chart(ctxBulanan.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                        datasets: [{
                            label: 'Jumlah Arsip Masuk',
                            data: @json($arsipBulananData),
                            borderColor: '#e92027',
                            backgroundColor: 'rgba(233, 32, 39, 0.05)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                            y: { grid: { borderDash: [4, 4] }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }

            // === ARSIP DASHBOARD CHARTS ===

            // 1. KLASIFIKASI (Bar)
            const ctxKlasifikasi = document.getElementById('arsipKlasifikasiChart');
            if (ctxKlasifikasi) {
                new Chart(ctxKlasifikasi.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($arsipKlasifikasiChart['labels']),
                        datasets: [{
                            label: 'Jumlah Berkas',
                            data: @json($arsipKlasifikasiChart['data']),
                            backgroundColor: '#e92027',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [4, 4] }, ticks: { font: { size: 10 } } },
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                        }
                    }
                });
            }

            // 2. MEDIA (Doughnut)
            const ctxMediaArsip = document.getElementById('arsipMediaChart');
            if (ctxMediaArsip) {
                new Chart(ctxMediaArsip.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($arsipMediaChart['labels']),
                        datasets: [{
                            data: @json($arsipMediaChart['data']),
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '60%',
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, font: { size: 10 } } }
                        }
                    }
                });
            }

            // 3. TAHUN (Line)
            const ctxTahun = document.getElementById('arsipTahunChart');
            if (ctxTahun) {
                new Chart(ctxTahun.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($arsipTahunChart['labels']),
                        datasets: [{
                            label: 'Volume Arsip',
                            data: @json($arsipTahunChart['data']),
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3,
                            pointHoverRadius: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4], color: '#f3f4f6' },
                                ticks: { stepSize: 1, font: { size: 10 } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }

            // 4. CHART ARSIP PER UNIT KERJA (Donut Chart)
            const ctxUnit = document.getElementById('arsipUnitChart');
            if (ctxUnit) {
                new Chart(ctxUnit.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($arsipUnitChart['labels']),
                        datasets: [{
                            data: @json($arsipUnitChart['data']),
                            backgroundColor: [
                                '#e92027', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6',
                                '#ec4899', '#06b6d4', '#f97316', '#6366f1', '#84cc16'
                            ],
                            borderWidth: 1,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '55%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { boxWidth: 10, usePointStyle: true, font: { size: 9 } }
                            }
                        }
                    }
                });
            }

            // 5. CHART ALIH MEDIA (Vertical Bar)
            const ctxAlihMedia = document.getElementById('alihMediaChart');
            if (ctxAlihMedia) {
                new Chart(ctxAlihMedia.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($alihMediaChart['labels']),
                        datasets: [{
                            label: 'Jumlah Box Selesai',
                            data: @json($alihMediaChart['data']),
                            backgroundColor: '#e92027',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) { return context.parsed.y + ' Box'; }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [4, 4], color: '#f3f4f6' },
                                title: { display: true, text: 'Jumlah Box', font: { size: 10 } },
                                ticks: { font: { size: 10 } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layout>
