<x-layout>
    {{-- Print Header (Visible only in Print) --}}
    <div id="print-header" class="hidden mb-8 border-b-2 border-[#e92027] pb-4">
        <div class="flex items-center justify-between px-8">
            <img src="{{ asset('images/logo-semen-padang.png') }}" alt="Logo" class="h-20 w-auto">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-[#c41820] uppercase">PT Semen Padang</h1>
                <h2 class="text-xl font-bold text-gray-800">Data Arsip Musnah</h2>
                <p class="text-sm text-gray-600">Indarung, Padang 25237, Sumatera Barat</p>
            </div>
            <div class="w-20"></div> {{-- Spacer --}}
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen pb-20">
        {{-- Header Section --}}
        <div class="bg-gradient-to-br from-gray-800 via-gray-700 to-gray-900 text-white pb-24 md:pb-32 pt-12 md:pt-16 px-4 md:px-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 mb-8 rounded-b-[2rem] md:rounded-b-[3rem] shadow-2xl relative overflow-hidden print:hidden">
             <!-- Polygon Pattern Overlay -->
             <div class="absolute inset-0 z-0 opacity-40">
                  <svg class="absolute w-full h-full" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                     <defs>
                         <linearGradient id="polyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                             <stop offset="0%" style="stop-color:#000000;stop-opacity:0.3" />
                             <stop offset="100%" style="stop-color:#555555;stop-opacity:0.4" />
                         </linearGradient>
                     </defs>
                     <path fill="url(#polyGrad)" d="M0 0 L1000 0 L1000 500 L0 300 Z" />
                     <path fill="#000000" opacity="0.1" d="M-100 0 L500 0 L200 600 L-100 400 Z" />
                     <path fill="#333333" opacity="0.2" d="M800 0 L1400 0 L1400 400 L600 600 Z" />
                     <path fill="url(#polyGrad)" opacity="0.3" d="M500 600 L1200 600 L800 200 Z" />
                 </svg>
             </div>

             <!-- Ornamental Icon -->
             <div class="absolute top-0 right-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 z-0 pointer-events-none mix-blend-overlay">
                 <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" /></svg>
             </div>

             <div class="max-w-7xl mx-auto flex flex-col justify-center text-center relative z-10 gap-4">
                 <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Data Arsip Musnah</h2>
                 <p class="text-gray-300 text-sm md:text-base font-light opacity-95 max-w-lg mx-auto leading-relaxed drop-shadow-sm">Daftar arsip yang telah dimusnahkan dan dihapus secara permanen dari daftar utama.</p>
             </div>
        </div>

        {{-- Content Card --}}
        <div class="max-w-7xl mx-auto px-4 -mt-12 md:-mt-20 relative z-20 mb-12 print:mt-0 print:px-0">
            <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden border border-gray-100 min-h-[400px] flex flex-col print:shadow-none print:border-0 print:p-0">

                {{-- Toolbar (Search) --}}
                <div class="p-4 md:p-6 border-b border-gray-100 bg-white flex justify-between items-center relative z-30 print:hidden">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-gray-700 transition-colors pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>

                        <form id="filterForm" action="{{ route('arsip.musnah') }}" method="GET" class="w-full">
                            <input type="text" name="search" placeholder="Cari nama berkas, kode, urain..." value="{{ request('search') }}"
                                class="w-full py-3 pl-12 {{ request('search') ? 'pr-12' : 'pr-4' }} bg-gray-50 border border-gray-200 rounded-xl md:rounded-2xl focus:outline-none focus:ring-2 focus:ring-gray-800 focus:bg-white focus:border-transparent text-sm font-medium transition-all shadow-sm text-gray-700">
                        </form>

                        <!-- Tombol Reset Pencarian -->
                        @if(request('search'))
                            <a href="{{ route('arsip.musnah') }}" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-700 transition-colors" title="Reset Pencarian">
                                <svg class="w-5 h-5 bg-gray-200 hover:bg-gray-300 rounded-full p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Table Container --}}
                <div class="flex-grow overflow-x-auto w-full">
                    <table class="min-w-full w-full text-left border-collapse whitespace-nowrap bg-white">
                        <thead>
                            <tr class="bg-gray-800 text-white uppercase tracking-wider text-[10px] md:text-xs">
                                <th class="py-4 px-4 font-bold text-center w-14">No</th>
                                <th class="py-4 px-4 font-bold">Kode</th>
                                <th class="py-4 px-4 font-bold min-w-[200px]">Nama Berkas</th>
                                <th class="py-4 px-4 font-bold min-w-[250px]">Uraian</th>
                                <th class="py-4 px-4 font-bold text-center">Tahun</th>
                                <th class="py-4 px-4 font-bold text-center">Tgl Masuk</th>
                                <th class="py-4 px-4 font-bold text-center">Jml</th>
                                <th class="py-4 px-4 font-bold text-center">Box</th>
                                <th class="py-4 px-4 font-bold text-center">Ket</th>
                                <th class="py-4 px-4 font-bold text-center">Dimusnahkan Pada</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-xs md:text-sm font-medium divide-y divide-gray-100">
                            @forelse($arsips as $index => $arsip)
                                <tr class="hover:bg-red-50/50 transition duration-200">
                                    <td class="py-4 px-4 text-center">{{ $arsips->firstItem() + $index }}</td>
                                    <td class="py-4 px-4">
                                        <span class="bg-red-100 text-red-700 py-1 px-2.5 rounded-full text-[10px] md:text-xs font-bold border border-red-200">
                                            {{ $arsip->klasifikasi->kode_klasifikasi ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-gray-800 whitespace-normal line-clamp-2">
                                        {{ $arsip->nama_berkas }}
                                    </td>
                                    <td class="py-4 px-4 text-gray-500 whitespace-normal line-clamp-2" title="{{ $arsip->isi }}">
                                        {{ Str::limit($arsip->isi, 60) }}
                                    </td>
                                    <td class="py-4 px-4 text-center">{{ $arsip->tahun }}</td>
                                    <td class="py-4 px-4 text-center">
                                        {{ $arsip->tanggal_masuk ? \Carbon\Carbon::parse($arsip->tanggal_masuk)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-4 px-4 text-center">{{ $arsip->jumlah }}</td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="bg-gray-100 text-gray-700 py-1 px-2 rounded font-bold border border-gray-200">{{ $arsip->no_box }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        <span class="bg-purple-100 text-purple-700 py-1 px-3 rounded-full text-[10px] md:text-xs font-bold border border-purple-200">
                                            {{ $arsip->tindakan_akhir }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-center text-gray-500 font-mono text-xs">
                                        {{ \Carbon\Carbon::parse($arsip->deleted_at)->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-gray-400 bg-gray-50/50">
                                        <div class="flex flex-col items-center gap-3">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            <span class="font-medium text-sm">Belum ada arsip yang dimusnahkan.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($arsips->hasPages())
                <div class="p-4 md:p-6 border-t border-gray-100 bg-gray-50 print:hidden">
                    {{ $arsips->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
