<x-layout>
    <!-- Background Header -->
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
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
         
         <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-6">
            <div class="text-center md:text-left">
                 <h2 class="text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Monitoring Kinerja</h2>
                 <p class="text-red-50 text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Pantau progres dan aktivitas pengarsipan karyawan secara real-time.</p>
            </div>
            <a href="{{ route('monitoring.create') }}" class="group bg-white text-[#e92027] hover:bg-gray-50 px-8 py-3 rounded-full font-bold shadow-2xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40 border border-white/20">
                <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                    <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <span>ISI FORMULIR</span>
            </a>
        </div>
    </div>

    <!-- Floating Card Container -->
    <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 mb-12">
        
        <!-- Stats Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-6">
            <!-- Card 1: Total -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-32 hover:-translate-y-1 transition-transform duration-300 group">
                <h2 class="text-[#a0131a] font-bold text-xs mb-1 uppercase tracking-wider group-hover:text-[#e92027]">Total Arsip</h2>
                <p class="text-3xl font-black text-gray-800">{{ $total }}</p>
            </div>
            <!-- Card 2: Bulan Ini -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-32 hover:-translate-y-1 transition-transform duration-300 group">
                <h2 class="text-[#a0131a] font-bold text-xs mb-1 uppercase tracking-wider group-hover:text-[#e92027]">Bulan Ini</h2>
                <p class="text-3xl font-black text-gray-800">{{ $bulanIni }}</p>
            </div>
            <!-- Card 3: Pemilahan -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-32 hover:-translate-y-1 transition-transform duration-300 group">
                <h3 class="text-orange-700 font-bold text-xs mb-1 uppercase tracking-wider group-hover:text-orange-600">Pemilahan</h3>
                <p class="text-3xl font-black text-gray-800">{{ $pemilahan }}</p>
            </div>
            <!-- Card 4: Pendataan -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-32 hover:-translate-y-1 transition-transform duration-300 group">
                <h3 class="text-orange-700 font-bold text-xs mb-1 uppercase tracking-wider group-hover:text-blue-600">Pendataan</h3>
                <p class="text-3xl font-black text-gray-800">{{ $pendataan }}</p>
            </div>
            <!-- Card 5: Pelabelan -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-32 hover:-translate-y-1 transition-transform duration-300 group">
                <h3 class="text-orange-700 font-bold text-xs mb-1 uppercase tracking-wider group-hover:text-indigo-600">Pelabelan</h3>
                <p class="text-3xl font-black text-gray-800">{{ $pelabelan }}</p>
            </div>
            <!-- Card 6: Alih Media -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-32 hover:-translate-y-1 transition-transform duration-300 group">
                <h3 class="text-orange-700 font-bold text-xs mb-1 uppercase tracking-wider group-hover:text-purple-600">Alih Media</h3>
                <p class="text-3xl font-black text-gray-800">{{ $alihMedia }}</p>
            </div>
            <!-- Card 7: Input E-Arsip -->
            <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-32 hover:-translate-y-1 transition-transform duration-300 group">
                <h3 class="text-orange-700 font-bold text-xs mb-1 uppercase tracking-wider group-hover:text-emerald-600">E-Arsip</h3>
                <p class="text-3xl font-black text-gray-800">{{ $inputEArsip }}</p>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 min-h-[600px] flex flex-col">
            
            <!-- Filters & Toolbar -->
            <div class="p-6 border-b border-gray-100 bg-white flex flex-col lg:flex-row gap-4 justify-between items-center sticky top-0 z-30">
                <!-- Search & Filters -->
                <div class="flex flex-col md:flex-row gap-4 w-full justify-between items-center">
                    <!-- Search -->
                    <div class="relative w-full md:w-96 group">
                         <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027] transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                         </span>
                         <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Cari aktivitas..." class="w-full py-3 pl-12 pr-4 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027] focus:bg-white focus:border-transparent text-sm font-medium transition-all shadow-sm hover:shadow-md hover:border-red-300 filter-input">
                    </div>

                    <!-- Dropdowns -->
                    <div class="flex gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 hide-scrollbar scroll-smooth">
                        <div class="relative">
                            <select id="picFilter" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#e92027] focus:border-transparent block pl-5 pr-10 py-3 filter-input cursor-pointer hover:bg-gray-50 hover:border-red-300 hover:shadow-md transition-all shadow-sm min-w-[150px] appearance-none">
                                <option value="">Semua PIC</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('pic') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <select id="tahapanFilter" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#e92027] focus:border-transparent block pl-5 pr-10 py-3 filter-input cursor-pointer hover:bg-gray-50 hover:border-red-300 hover:shadow-md transition-all shadow-sm min-w-[170px] appearance-none">
                                <option value="">Semua Tahapan</option>
                                <option value="Pemilahan" {{ request('tahapan') == 'Pemilahan' ? 'selected' : '' }}>Pemilahan</option>
                                <option value="Pendataan" {{ request('tahapan') == 'Pendataan' ? 'selected' : '' }}>Pendataan</option>
                                <option value="Pelabelan" {{ request('tahapan') == 'Pelabelan' ? 'selected' : '' }}>Pelabelan</option>
                                <option value="Alih Media" {{ request('tahapan') == 'Alih Media' ? 'selected' : '' }}>Alih Media</option>
                                <option value="Input E-Arsip" {{ request('tahapan') == 'Input E-Arsip' ? 'selected' : '' }}>Input E-Arsip</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                         
                         <!-- Reset -->
                         @if(request('search') || request('pic') || request('tahapan'))
                            <a href="{{ route('monitoring.index') }}" class="flex items-center px-5 py-3 bg-red-50 text-[#e92027] rounded-xl text-sm font-bold hover:bg-red-100 transition shadow-sm whitespace-nowrap hover:shadow-md">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="p-6 flex-grow overflow-x-auto">
                <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
                    <table class="w-full text-sm text-center border-collapse">
                        <thead>
                            <tr class="bg-[#e92027] text-white">
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider first:rounded-tl-xl text-center">PIC</th>
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider text-center">Tahapan</th>
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider text-center">Tanggal</th>
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider text-center">No. Berita Acara</th>
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider text-center">Unit Kerja</th>
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider text-center">Jumlah Selesai</th>
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider text-center">Keterangan</th>
                                <th class="py-5 px-6 font-bold text-xs uppercase tracking-wider text-center last:rounded-tr-xl">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="monitoringTableBody" class="divide-y divide-gray-100 bg-white">
                            @forelse($monitoring as $index => $item)
                            <tr class="hover:bg-red-50/50 transition duration-200 group">
                                <td class="py-4 px-6 font-semibold text-gray-800 border-l-4 border-transparent group-hover:border-[#e92027] transition-all text-center">
                                    {{ $item->user->nama ?? '-' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <form id="advance-form-{{ $item->id }}" action="{{ route('monitoring.advance-stage', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" onclick="confirmAdvance('advance-form-{{ $item->id }}', '{{ $item->tahapan }}', '{{ $item->status_kerja }}')"
                                            class="px-4 py-1.5 rounded-full text-xs font-extrabold shadow-sm transition-all duration-200 border transform 
                                            {{ $item->tahapan == 'Pemilahan' ? 'bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100 hover:scale-105' : '' }}
                                            {{ $item->tahapan == 'Pendataan' ? 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100 hover:scale-105' : '' }}
                                            {{ $item->tahapan == 'Pelabelan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100 hover:scale-105' : '' }}
                                            {{ $item->tahapan == 'Alih Media' ? 'bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100 hover:scale-105' : '' }}
                                            {{ $item->tahapan == 'Input E-Arsip' ? ($item->status_kerja == 'Selesai' ? 'bg-emerald-600 text-white border-emerald-600 cursor-default' : 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100 hover:scale-105') : '' }}"
                                            title="{{ $item->status_kerja == 'Selesai' ? 'Tahapan Selesai' : 'Klik untuk lanjut ke tahapan berikutnya' }}"
                                            {{ ($item->tahapan == 'Input E-Arsip' && $item->status_kerja == 'Selesai') ? 'disabled' : '' }}
                                        >
                                            {{ $item->tahapan == 'Input E-Arsip' && $item->status_kerja == 'Selesai' ? '✓ UPDATE SELESAI' : $item->tahapan }}
                                        </button>
                                    </form>
                                </td>
                                <td class="py-4 px-6 text-gray-600 text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal_kerja)->format('d/m/Y') }}
                                </td>
                                <td class="py-4 px-6 text-gray-800 font-medium text-center">
                                    {{ $item->nba }}
                                </td>
                                <td class="py-4 px-6 text-gray-600 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $item->unit_kerja }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="text-sm font-bold text-gray-800">{{ $item->jumlah_box_selesai }}</span>
                                        @if(auth()->user()->role === 'admin' || auth()->id() == $item->user_id)
                                            @if($item->status_kerja != 'Selesai')
                                            <button type="button" onclick="openProgressModal({{ $item->id }})" class="p-1 text-[#e92027] hover:text-[#a0131a] hover:bg-red-50 rounded-full transition-colors" title="Tambah Progress">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-500 text-xs italic text-center max-w-[200px] truncate">
                                    {{ $item->keterangan ?: '-' }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        @if(auth()->user()->role === 'admin' || auth()->id() == $item->user_id)
                                            @if($item->status_kerja != 'Selesai')
                                            <a href="{{ route('monitoring.edit', $item->id) }}" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <button type="button" onclick="openHistory({{ $item->id }})" class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-colors" title="Riwayat">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                            <form id="delete-form-{{ $item->id }}" action="{{ route('monitoring.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-form-{{ $item->id }}')" class="p-2 text-[#e92027] hover:text-[#a0131a] hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-xs text-gray-400 font-medium italic">Locked</span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        <p class="text-lg font-medium">Belum ada data monitoring</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>

    <!-- History Modal -->
    <div id="historyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeHistoryModal()">
                <div class="absolute inset-0 bg-white "></div>
                <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('{{ asset('images/SuperGrafis.png') }}');"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full relative z-50 border border-gray-100">
                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2" id="modal-title">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        Riwayat Perubahan
                    </h3>
                    <button onclick="closeHistoryModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 py-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase rounded-l-lg">Tanggal</th>
                                <th scope="col" class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Oleh</th>
                                <th scope="col" class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Tahapan</th>
                                <th scope="col" class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase rounded-r-lg">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50" id="historyTableBody">
                            <!-- Content will be loaded here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse border-t border-gray-100">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm transition-all" onclick="closeHistoryModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Modal -->
    <div id="progressModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeProgressModal()">
                <div class="absolute inset-0 bg-white "></div>
                <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('{{ asset('images/SuperGrafis.png') }}');"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50 border border-gray-100">
                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2" id="modal-title">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        Update Progress
                    </h3>
                    <button onclick="closeProgressModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 py-6 bg-white">
                    <form id="progressForm" onsubmit="submitProgress(event)">
                        <input type="hidden" id="progressId">
                        
                        <div class="space-y-5">
                            <div>
                                <label for="jumlah_tambahan" class="block text-sm font-bold text-gray-700 mb-1">Jumlah Box Selesai</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <input type="number" name="jumlah_tambahan" id="jumlah_tambahan" class="focus:ring-[#e92027] focus:border-[#e92027] block w-full pl-10 sm:text-sm border-gray-300 rounded-xl py-3 transition-shadow focus:shadow-md" placeholder="Contoh: 5" required min="1">
                                </div>
                                <p class="mt-1.5 text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Jumlah ini akan ditambahkan ke total progress saat ini.
                                </p>
                            </div>

                            <div>
                                <label for="tanggal_baru" class="block text-sm font-bold text-gray-700 mb-1">Tanggal Pengerjaan</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <input type="date" name="tanggal_baru" id="tanggal_baru" class="focus:ring-[#e92027] focus:border-[#e92027] block w-full pl-10 sm:text-sm border-gray-300 rounded-xl py-3 transition-shadow focus:shadow-md cursor-pointer" required value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                            <button type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm transition-all" onclick="closeProgressModal()">
                                Batal
                            </button>
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-[#e92027] text-base font-medium text-white hover:bg-[#a0131a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm transition-all shadow-red-200">
                                Simpan Progress
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SEARCH & FILTER LOGIC
            const searchInput = document.getElementById('searchInput');
            const picFilter = document.getElementById('picFilter');
            const tahapanFilter = document.getElementById('tahapanFilter');
            const tableBody = document.getElementById('monitoringTableBody');
            let timeout = null;

            function fetchData() {
                const query = searchInput.value;
                const pic = picFilter.value;
                const tahapan = tahapanFilter.value;

                const params = new URLSearchParams();
                if(query) params.append('search', query);
                if(pic) params.append('pic', pic);
                if(tahapan) params.append('tahapan', tahapan);

                const url = `{{ route('monitoring.index') }}?${params.toString()}`;
                
                window.history.pushState({path: url}, '', url);
                
                tableBody.classList.add('opacity-50');

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTbody = doc.getElementById('monitoringTableBody');
                    
                    if (newTbody) {
                        tableBody.innerHTML = newTbody.innerHTML;
                        // No need to re-attach listeners as we use inline onclick
                    }
                    tableBody.classList.remove('opacity-50');
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.classList.remove('opacity-50');
                });
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(fetchData, 400);
            });

            picFilter.addEventListener('change', fetchData);
            tahapanFilter.addEventListener('change', fetchData);
        });

        // GLOBAL FUNCTIONS FOR INLINE ONCLICK HANDLERS

        // 1. DELETE Action
        window.confirmDelete = function(formId) {
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e92027',
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    cancelButton: 'text-gray-700 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // 2. ADVANCE STAGE Action
        window.confirmAdvance = function(formId, currentStage, status) {
            // Prevent if already completed
            if(status === 'Selesai') return;

            let nextStage = '';
            let confirmTitle = 'Lanjutkan Tahapan?';
            let confirmText = '';

            if(currentStage === 'Pemilahan') nextStage = 'Pendataan';
            else if(currentStage === 'Pendataan') nextStage = 'Pelabelan';
            else if(currentStage === 'Pelabelan') nextStage = 'Input E-Arsip';
            else if(currentStage === 'Input E-Arsip') {
                nextStage = 'SELESAI';
                confirmTitle = 'Selesaikan Input E-Arsip?';
            }

            if(nextStage !== 'SELESAI') {
                confirmText = `Ubah status dari ${currentStage} ke ${nextStage}?`;
            } else {
                    confirmText = 'Status akan diubah menjadi SELESAI dan data akan dikunci (tidak bisa diedit lagi).';
            }
            
            if (!nextStage) return; 

            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: currentStage === 'Input E-Arsip' ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: currentStage === 'Input E-Arsip' ? '#10b981' : '#e92027',
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: currentStage === 'Input E-Arsip' ? 'Ya, Selesaikan' : 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                customClass: {
                    cancelButton: 'text-gray-700 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // 3. HISTORY Action
        window.openHistory = function(id) {
            const modal = document.getElementById('historyModal');
            const tableBody = document.getElementById('historyTableBody');
            
            if (!tableBody || !modal) return;

            tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4">Memuat data...</td></tr>';
            modal.classList.remove('hidden');

            fetch(`/monitoring/${id}/history`)
                .then(response => response.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    if (data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">Belum ada riwayat perubahan.</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        const date = new Date(item.created_at).toLocaleString('id-ID');
                        const jumlah = item.jumlah_tambahan > 0 
                            ? `<span class="font-bold text-gray-800">${item.jumlah_box_selesai}</span> <span class="text-green-600 font-bold ml-1">+${item.jumlah_tambahan}</span>`
                            : `<span class="font-bold text-gray-800">${item.jumlah_box_selesai}</span>`;

                        const row = `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.user ? item.user.nama : '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.tahapan}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">${jumlah}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-red-500">Gagal memuat data.</td></tr>';
                });
        }
        
        window.closeHistoryModal = function() {
            document.getElementById('historyModal').classList.add('hidden');
        }

        // 4. PROGRESS Action
        window.openProgressModal = function(id) {
            document.getElementById('progressId').value = id;
            document.getElementById('progressModal').classList.remove('hidden');
        }

        window.closeProgressModal = function() {
            document.getElementById('progressModal').classList.add('hidden');
            document.getElementById('progressForm').reset();
            document.getElementById('tanggal_baru').value = new Date().toISOString().split('T')[0];
        }

        window.submitProgress = function(e) {
            e.preventDefault();
            const id = document.getElementById('progressId').value;
            const jumlah = document.getElementById('jumlah_tambahan').value;
            const tanggal = document.getElementById('tanggal_baru').value;

            if (!jumlah || !tanggal) {
                Swal.fire('Error', 'Mohon lengkapi semua field', 'error');
                return;
            }

            fetch(`/monitoring/${id}/progress`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    jumlah_tambahan: jumlah,
                    tanggal_baru: tanggal
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeProgressModal();
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Refresh page to show updated cumulative total
                        window.location.reload(); 
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            });
        }
    </script>
</x-layout>
