<x-layout>
    <div id="print-header" class="hidden mb-8 border-b-2 border-[#e92027] pb-4">
        <div class="flex items-center justify-between px-8">
            <img src="{{ asset('images/logo-sp.png') }}" alt="Logo" class="h-20 w-auto">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-[#c41820] uppercase">PT Semen Padang</h1>
                <h2 class="text-xl font-bold text-gray-800">Data Arsip Musnah</h2>
            </div>
            <div class="w-20"></div>
        </div>
    </div>

    <div class="bg-gray-50 min-h-screen pb-20">
        <div class="bg-gradient-to-br from-gray-800 via-gray-700 to-gray-900 text-white pb-24 md:pb-32 pt-12 md:pt-16 px-4 md:px-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 mb-8 rounded-b-[2rem] md:rounded-b-[3rem] shadow-2xl relative overflow-hidden print:hidden">
             <div class="absolute inset-0 z-0 opacity-40">
                  <svg class="absolute w-full h-full" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                     <defs><linearGradient id="polyGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#000000;stop-opacity:0.3" /><stop offset="100%" style="stop-color:#555555;stop-opacity:0.4" /></linearGradient></defs>
                     <path fill="url(#polyGrad)" d="M0 0 L1000 0 L1000 500 L0 300 Z" /><path fill="#000000" opacity="0.1" d="M-100 0 L500 0 L200 600 L-100 400 Z" />
                 </svg>
             </div>
             <div class="absolute top-0 right-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 z-0 pointer-events-none mix-blend-overlay">
                 <svg width="400" height="400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" /></svg>
             </div>
             <div class="max-w-7xl mx-auto flex flex-col justify-center text-center relative z-10 gap-4">
                 <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Data Arsip Musnah</h2>
                 <p class="text-gray-300 text-sm md:text-base font-light opacity-95 max-w-lg mx-auto leading-relaxed drop-shadow-sm">Daftar arsip yang telah dimusnahkan dan dihapus secara permanen dari daftar utama.</p>
             </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 -mt-12 md:-mt-20 relative z-20 mb-12 print:mt-0 print:px-0">
            <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden border border-gray-100 min-h-[400px] flex flex-col print:shadow-none print:border-0 print:p-0">

                <div class="p-4 md:p-6 border-b border-gray-100 bg-white flex justify-between items-center relative z-30 print:hidden">
                    <div class="relative w-full md:w-96 group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-gray-700 transition-colors pointer-events-none"><i class="fas fa-search"></i></span>
                        <form action="{{ route('arsip.musnah') }}" method="GET" class="w-full">
                            <input type="text" name="search" placeholder="Cari nama berkas, kode, uraian..." value="{{ request('search') }}"
                                class="w-full py-3 pl-12 pr-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-800 text-sm font-medium transition-all shadow-sm">
                        </form>
                    </div>
                </div>

                <!-- TAMPILAN MOBILE -->
                <div class="block xl:hidden space-y-4 p-4 bg-gray-50/50 flex-grow">
                    @forelse ($arsips as $arsip)
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative">
                            <div class="mb-3 border-b border-gray-100 pb-3">
                                <!-- PENGGUNAAN OPTIONAL CHAINING AGAR TIDAK ERROR JIKA KLASIFIKASI KOSONG -->
                                <span class="inline-block bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded border border-red-200 mb-1">{{ optional($arsip->klasifikasi)->kode_klasifikasi ?? '-' }}</span>
                                <div class="font-bold text-gray-900 text-sm leading-relaxed">{{ $arsip->nama_berkas ?? '-' }}</div>
                            </div>
                            <div class="text-xs text-gray-600 mb-3 whitespace-normal leading-relaxed">{{ $arsip->isi ?? '-' }}</div>
                            <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100 mb-3 text-[11px]">
                                <div><span class="text-gray-400 font-bold uppercase block">Tahun</span><span class="font-bold text-gray-800">{{ $arsip->tahun ?? '-' }}</span></div>
                                <div><span class="text-gray-400 font-bold uppercase block">Box</span><span class="font-mono font-bold text-gray-600">{{ $arsip->no_box ?? '-' }}</span></div>
                                <div class="col-span-2"><span class="text-gray-400 font-bold uppercase block">Tgl Musnah</span><span class="font-bold text-red-600">{{ \Carbon\Carbon::parse($arsip->deleted_at)->format('d M Y H:i') }}</span></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center bg-white rounded-2xl border border-gray-200"><i class="fas fa-fire text-4xl mb-3 text-gray-300"></i><p class="text-sm text-gray-400">Belum ada arsip dimusnahkan.</p></div>
                    @endforelse
                </div>

                <!-- TAMPILAN DESKTOP -->
                <div class="hidden xl:block flex-grow w-full p-6">
                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm bg-white w-full">
                        <table class="w-full text-left border-collapse table-fixed bg-white">
                            <thead>
                                <tr class="bg-gray-800 text-white uppercase tracking-wider text-[9px]">
                                    <th class="py-3 px-2 font-bold text-center w-[4%] border-r border-gray-700">No</th>
                                    <th class="py-3 px-2 font-bold text-center w-[6%] border-r border-gray-700">Kode</th>
                                    <th class="py-3 px-3 font-bold w-[20%] border-r border-gray-700">Nama Berkas</th>
                                    <th class="py-3 px-3 font-bold w-[23%] border-r border-gray-700">Uraian</th>
                                    <th class="py-3 px-2 font-bold text-center w-[5%] border-r border-gray-700">Thn</th>
                                    <th class="py-3 px-2 font-bold text-center w-[8%] border-r border-gray-700">Tgl Masuk</th>
                                    <th class="py-3 px-2 font-bold text-center w-[4%] border-r border-gray-700">Jml</th>
                                    <th class="py-3 px-2 font-bold text-center w-[6%] border-r border-gray-700">Box</th>
                                    <th class="py-3 px-2 font-bold text-center w-[12%] border-r border-gray-700">Status</th>
                                    <th class="py-3 px-2 font-bold text-center w-[12%]">Tgl Musnah</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-[10px] divide-y divide-gray-100">
                                @forelse($arsips as $index => $arsip)
                                    <tr class="hover:bg-gray-50 transition duration-200">
                                        <td class="py-2 px-2 text-center font-bold border-r border-gray-100 align-top whitespace-nowrap">{{ $arsips->firstItem() + $index }}</td>
                                        <td class="py-2 px-2 text-center border-r border-gray-100 font-bold text-gray-700 align-top">
                                            <!-- PENGGUNAAN OPTIONAL CHAINING -->
                                            {{ optional($arsip->klasifikasi)->kode_klasifikasi ?? '-' }}
                                        </td>
                                        <td class="py-2 px-3 font-bold text-gray-800 border-r border-gray-100 align-top whitespace-normal break-words">
                                            {{ $arsip->nama_berkas ?? '-' }}
                                        </td>
                                        <td class="py-2 px-3 text-gray-600 border-r border-gray-100 align-top whitespace-normal break-words leading-relaxed">
                                            {{ $arsip->isi ?? '-' }}
                                        </td>
                                        <td class="py-2 px-2 text-center font-medium border-r border-gray-100 align-top whitespace-nowrap">{{ $arsip->tahun ?? '-' }}</td>
                                        <td class="py-2 px-2 text-center border-r border-gray-100 align-top whitespace-nowrap">
                                            {{ $arsip->tanggal_masuk ? \Carbon\Carbon::parse($arsip->tanggal_masuk)->format('d/m/y') : '-' }}
                                        </td>
                                        <td class="py-2 px-2 text-center font-bold border-r border-gray-100 align-top whitespace-nowrap">{{ $arsip->jumlah ?? '-' }}</td>
                                        <td class="py-2 px-2 text-center font-mono font-bold text-gray-700 border-r border-gray-100 align-top whitespace-nowrap">
                                            {{ $arsip->no_box ?? '-' }}
                                        </td>
                                        <td class="py-2 px-2 text-center border-r border-gray-100 align-top">
                                            <span class="font-bold uppercase text-red-600 block">
                                                {{ $arsip->tindakan_akhir ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-2 px-2 text-center text-[#e92027] font-mono font-bold bg-red-50/50 align-top whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($arsip->deleted_at)->format('d/m/y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="py-12 text-center text-gray-400 bg-gray-50/50"><i class="fas fa-fire text-4xl mb-3 text-gray-300"></i><br><span class="text-sm font-bold">Belum ada arsip dimusnahkan.</span></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($arsips instanceof \Illuminate\Pagination\LengthAwarePaginator && $arsips->hasPages())
    @php $paginator = $arsips->appends(request()->query()); @endphp
    <div class="p-4 md:p-6 border-t border-gray-100 bg-gray-50 print:hidden flex justify-center">
        <nav class="flex items-center gap-1 md:gap-2 bg-white p-1.5 md:p-2 rounded-2xl border border-gray-200 shadow-sm max-w-full overflow-x-auto hide-scrollbar">

            @if ($paginator->onFirstPage())
                <span class="px-3 md:px-4 py-2 rounded-xl text-gray-300 bg-gray-50 cursor-not-allowed text-xs md:text-sm font-bold"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 md:px-4 py-2 rounded-xl text-gray-600 hover:bg-red-50 hover:text-[#e92027] transition-colors text-xs md:text-sm font-bold"><i class="fas fa-chevron-left"></i></a>
            @endif

            @foreach ($paginator->linkCollection() as $link)
                @if (str_contains($link['label'], 'Previous') || str_contains($link['label'], 'Next') || str_contains($link['label'], '&laquo;') || str_contains($link['label'], '&raquo;'))
                    @continue
                @endif

                @if ($link['url'] === null)
                    <span class="px-2 py-2 text-gray-400 text-sm font-bold">...</span>
                @elseif ($link['active'])
                    <span class="px-3 md:px-4 py-2 rounded-xl bg-gray-800 text-white font-extrabold text-xs md:text-sm shadow-md">{{ $link['label'] }}</span>
                @else
                    <a href="{{ $link['url'] }}" class="px-3 md:px-4 py-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-colors text-xs md:text-sm font-bold">{{ $link['label'] }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 md:px-4 py-2 rounded-xl text-gray-600 hover:bg-red-50 hover:text-[#e92027] transition-colors text-xs md:text-sm font-bold"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="px-3 md:px-4 py-2 rounded-xl text-gray-300 bg-gray-50 cursor-not-allowed text-xs md:text-sm font-bold"><i class="fas fa-chevron-right"></i></span>
            @endif

        </nav>
    </div>
@endif
            </div>
        </div>
    </div>
</x-layout>
