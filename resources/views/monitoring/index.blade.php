<x-layout>
    <div class="bg-gray-50 min-h-screen pb-20">
        <!-- Background Header -->
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
                     <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Monitoring Kinerja</h2>
                     <p class="text-red-50 text-sm md:text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Pantau progres dan aktivitas pengarsipan staf secara real-time.</p>
                </div>
                <a href="{{ route('monitoring.create') }}" class="group bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-full font-bold shadow-xl flex items-center gap-3 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-red-900/40">
                    <div class="bg-red-50 p-1.5 rounded-full group-hover:bg-red-100 transition-colors">
                        <svg class="w-5 h-5 text-[#e92027]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <span class="text-sm md:text-base">ISI FORMULIR</span>
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 mb-12">

            @if(session('error'))
                <div class="mb-6 bg-red-100 border-l-4 border-[#e92027] p-4 rounded-r-lg shadow-sm font-bold text-[#c41820] animate-pulse">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm font-bold text-green-800">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm font-bold text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i> {{ session('info') }}
                </div>
            @endif

            <!-- Stats Row -->
            <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4 mb-6">
                <!-- Card 1: Total -->
                <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-28 hover:-translate-y-1 transition-transform duration-300 group">
                    <h2 class="text-[#a0131a] font-bold text-[10px] md:text-xs mb-1 uppercase tracking-wider group-hover:text-[#e92027]">Total Arsip</h2>
                    <p class="text-2xl md:text-3xl font-black text-gray-800">{{ $total }}</p>
                </div>
                <!-- Card 3: Pemilahan -->
                <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-28 hover:-translate-y-1 transition-transform duration-300 group">
                    <h3 class="text-orange-700 font-bold text-[10px] md:text-xs mb-1 uppercase tracking-wider group-hover:text-orange-600">Pemilahan</h3>
                    <p class="text-2xl md:text-3xl font-black text-gray-800">{{ $pemilahan }}</p>
                </div>
                <!-- Card 4: Pendataan -->
                <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-28 hover:-translate-y-1 transition-transform duration-300 group">
                    <h3 class="text-blue-700 font-bold text-[10px] md:text-xs mb-1 uppercase tracking-wider group-hover:text-blue-600">Pendataan</h3>
                    <p class="text-2xl md:text-3xl font-black text-gray-800">{{ $pendataan }}</p>
                </div>
                <!-- Card 5: Pelabelan -->
                <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-28 hover:-translate-y-1 transition-transform duration-300 group">
                    <h3 class="text-indigo-700 font-bold text-[10px] md:text-xs mb-1 uppercase tracking-wider group-hover:text-indigo-600">Pelabelan</h3>
                    <p class="text-2xl md:text-3xl font-black text-gray-800">{{ $pelabelan }}</p>
                </div>
                <!-- Card 6: Alih Media -->
                <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-28 hover:-translate-y-1 transition-transform duration-300 group">
                    <h3 class="text-purple-700 font-bold text-[10px] md:text-xs mb-1 uppercase tracking-wider group-hover:text-purple-600">Alih Media</h3>
                    <p class="text-2xl md:text-3xl font-black text-gray-800">{{ $alihMedia }}</p>
                </div>
                <!-- Card 7: Input E-Arsip -->
                <div class="bg-white p-4 rounded-2xl shadow-lg border border-red-100 flex flex-col items-center justify-center h-28 hover:-translate-y-1 transition-transform duration-300 group">
                    <h3 class="text-emerald-700 font-bold text-[10px] md:text-xs mb-1 uppercase tracking-wider group-hover:text-emerald-600">E-Arsip</h3>
                    <p class="text-2xl md:text-3xl font-black text-gray-800">{{ $inputEArsip }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 min-h-[600px] flex flex-col">

                <!-- Filters & Toolbar -->
                <div class="p-4 md:p-6 border-b border-gray-100 bg-white flex flex-col lg:flex-row gap-4 justify-between items-center sticky top-0 z-30">
                    <form action="{{ route('monitoring.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 w-full justify-between items-center">
                        <div class="relative w-full md:w-96 group">
                             <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#e92027]">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                             </span>
                             <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" placeholder="Cari aktivitas..." class="w-full py-3 pl-12 pr-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027] focus:bg-white text-sm font-medium shadow-sm">
                        </div>

                        <div class="flex gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 hide-scrollbar scroll-smooth">
                            <select name="pic" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#e92027] outline-none block px-4 py-2.5 shadow-sm min-w-[150px]">
                                <option value="">Semua PIC</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('pic') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                                @endforeach
                            </select>

                            <select name="tahapan" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-[#e92027] outline-none block px-4 py-2.5 shadow-sm min-w-[170px]">
                                <option value="">Semua Tahapan</option>
                                <option value="Pemilahan" {{ request('tahapan') == 'Pemilahan' ? 'selected' : '' }}>Pemilahan</option>
                                <option value="Pendataan" {{ request('tahapan') == 'Pendataan' ? 'selected' : '' }}>Pendataan</option>
                                <option value="Pelabelan" {{ request('tahapan') == 'Pelabelan' ? 'selected' : '' }}>Pelabelan</option>
                                <option value="Alih Media" {{ request('tahapan') == 'Alih Media' ? 'selected' : '' }}>Alih Media</option>
                                <option value="Input E-Arsip" {{ request('tahapan') == 'Input E-Arsip' ? 'selected' : '' }}>Input E-Arsip</option>
                            </select>

                            @if(request('search') || request('pic') || request('tahapan'))
                                <a href="{{ route('monitoring.index') }}" class="flex items-center px-4 py-2 bg-red-50 text-[#e92027] rounded-xl text-sm font-bold shadow-sm whitespace-nowrap">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- ============================================== -->
                <!-- TAMPILAN MOBILE: KARTU (Tampil di Layar HP)    -->
                <!-- ============================================== -->
                <div class="block lg:hidden p-4 bg-gray-50/50 space-y-4 flex-grow">
                    @forelse($monitoring as $item)
                        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm relative">
                            <!-- Header Info -->
                            <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                                <div>
                                    <div class="font-extrabold text-gray-900 text-lg">{{ $item->user->nama ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_kerja)->format('d/m/Y') }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold bg-gray-100 text-gray-800 border border-gray-200">
                                        NBA: {{ $item->nba ?: '-' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Box & Progress Info -->
                            <div class="grid grid-cols-2 gap-3 mb-4 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <div>
                                    <span class="text-[10px] text-gray-500 font-bold block uppercase mb-1">Tahapan</span>
                                    <div class="text-xs font-bold {{ $item->status_kerja == 'Selesai' ? 'text-emerald-600' : 'text-[#e92027]' }}">
                                        {{ $item->tahapan }}
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-500 font-bold block uppercase mb-1">Progress Box</span>
                                    <div class="text-xs font-bold text-gray-800">
                                        {{ $item->jumlah_box_selesai }} / {{ $item->jumlah_box }} Box
                                    </div>
                                </div>
                            </div>

                            <!-- Tindakan Mobile -->
                            <div class="pt-3 flex flex-wrap gap-2 justify-between items-center border-t border-gray-100 mt-2">
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="openHistory({{ $item->id }})" class="p-2 text-purple-600 bg-purple-50 rounded-lg text-xs font-bold hover:bg-purple-100 border border-purple-100">
                                        <i class="fas fa-history"></i> Riwayat
                                    </button>

                                    <!-- TOMBOL TAMBAH PROGRESS HANYA UNTUK USER YBS / ADMIN -->
                                    @if(Auth::user()->role === 'admin' || Auth::id() == $item->user_id)
                                        @if($item->status_kerja != 'Selesai' && $item->status_kerja != 'Menunggu E-Arsip')
                                            <button type="button" onclick="openProgressModal({{ $item->id }})" class="p-2 text-blue-600 bg-blue-50 rounded-lg text-xs font-bold hover:bg-blue-100 border border-blue-100">
                                                <i class="fas fa-plus"></i> Progress
                                            </button>
                                        @endif
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 mt-2 w-full">
                                    <form id="advance-form-m-{{ $item->id }}" action="{{ route('monitoring.advance-stage', $item->id) }}" method="POST" class="flex-grow">
                                        @csrf @method('PATCH')
                                        @if($item->status_kerja == 'Selesai')
                                            <button type="button" disabled class="w-full px-3 py-2 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-lg border border-emerald-200 cursor-not-allowed">
                                                <i class="fas fa-check mr-1"></i> SELESAI
                                            </button>
                                        @elseif($item->status_kerja == 'Menunggu E-Arsip')
                                            <button type="button" disabled class="w-full px-3 py-2 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-lg border border-gray-200 cursor-not-allowed">
                                                <i class="fas fa-clock mr-1"></i> MENUNGGU E-ARSIP
                                            </button>
                                        @else
                                            <!-- TOMBOL LANJUT TAHAP HANYA UNTUK USER YBS / ADMIN -->
                                            @if(Auth::user()->role === 'admin' || Auth::id() == $item->user_id)
                                                <button type="button" onclick="confirmAdvance('advance-form-m-{{ $item->id }}', '{{ $item->tahapan }}')" class="w-full px-3 py-2 bg-[#e92027] text-white text-[10px] font-bold rounded-lg hover:bg-[#c41820]">
                                                    Lanjut Tahap <i class="fas fa-arrow-right ml-1"></i>
                                                </button>
                                            @else
                                                <button type="button" disabled class="w-full px-3 py-2 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed">
                                                    (Hanya PIC)
                                                </button>
                                            @endif
                                        @endif
                                    </form>

                                    <!-- HANYA ADMIN YANG BISA EDIT/HAPUS -->
                                    @if(Auth::user()->role === 'admin')
                                        <div class="flex border-l border-gray-200 pl-2 ml-1">
                                            <a href="{{ route('monitoring.edit', $item->id) }}" class="p-2.5 text-amber-500 hover:text-amber-700 hover:bg-amber-50 rounded-lg transition-colors">
                                                <i class="fas fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('monitoring.destroy', $item->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete('delete-m-{{ $item->id }}')" id="delete-m-{{ $item->id }}" class="p-2.5 text-[#e92027] hover:text-[#a0131a] hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center bg-white rounded-2xl border border-gray-200">
                            <i class="fas fa-clipboard-list text-4xl mb-3 text-gray-300"></i>
                            <p class="text-sm font-medium text-gray-400">Belum ada data monitoring.</p>
                        </div>
                    @endforelse
                </div>

                <!-- ============================================== -->
                <!-- TAMPILAN DESKTOP: TABEL (Layar Laptop)         -->
                <!-- ============================================== -->
                <div class="hidden lg:block p-6 flex-grow overflow-x-auto">
                    <div class="overflow-hidden rounded-2xl border border-gray-200 shadow-sm">
                        <table class="w-full text-sm text-center border-collapse">
                            <thead>
                                <tr class="bg-[#e92027] text-white">
                                    <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-center w-40">Aksi Tahapan</th>
                                    <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider">PIC</th>
                                    <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-center">Tanggal</th>
                                    <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-center">No. BA / Unit</th>
                                    <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-center">Progress Box</th>
                                    <th class="py-4 px-6 font-bold text-xs uppercase tracking-wider text-center w-32">Aksi Tambahan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($monitoring as $item)
                                <tr class="hover:bg-red-50/50 transition duration-200 group">
                                    <!-- Kolom Aksi Tahapan -->
                                    <td class="py-4 px-4 text-center">
                                        <form id="advance-form-d-{{ $item->id }}" action="{{ route('monitoring.advance-stage', $item->id) }}" method="POST" class="inline-block w-full">
                                            @csrf @method('PATCH')
                                            @if($item->status_kerja == 'Selesai')
                                                <button type="button" disabled class="w-full px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-emerald-600 text-white border border-emerald-600 cursor-not-allowed shadow-sm">
                                                    <i class="fas fa-check"></i> SELESAI
                                                </button>
                                            @elseif($item->status_kerja == 'Menunggu E-Arsip')
                                                <button type="button" disabled class="w-full px-3 py-1.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 border border-gray-200 cursor-not-allowed shadow-sm">
                                                    <i class="fas fa-clock"></i> MENUNGGU
                                                </button>
                                            @else
                                                @if(Auth::user()->role === 'admin' || Auth::id() == $item->user_id)
                                                    <button type="button" onclick="confirmAdvance('advance-form-d-{{ $item->id }}', '{{ $item->tahapan }}')"
                                                        class="w-full px-3 py-1.5 rounded-full text-[10px] font-extrabold shadow-sm transition-all border transform hover:scale-105
                                                        {{ $item->tahapan == 'Pemilahan' ? 'bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100' : '' }}
                                                        {{ $item->tahapan == 'Pendataan' ? 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100' : '' }}
                                                        {{ $item->tahapan == 'Pelabelan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100' : '' }}
                                                        {{ $item->tahapan == 'Input E-Arsip' ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : '' }}">
                                                        {{ $item->tahapan }} <i class="fas fa-arrow-right ml-1"></i>
                                                    </button>
                                                @else
                                                    <button type="button" disabled class="w-full px-3 py-1.5 rounded-full text-[10px] font-bold bg-gray-50 text-gray-400 border border-gray-200 cursor-not-allowed shadow-sm">
                                                        {{ $item->tahapan }}
                                                    </button>
                                                @endif
                                            @endif
                                        </form>
                                    </td>

                                    <td class="py-4 px-6 font-bold text-gray-800">{{ $item->user->nama ?? '-' }}</td>
                                    <td class="py-4 px-6 text-gray-600 text-center">{{ \Carbon\Carbon::parse($item->tanggal_kerja)->format('d/m/Y') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="font-bold text-gray-800">{{ $item->nba ?: '-' }}</div>
                                        <div class="text-[10px] text-gray-400 mt-1 uppercase">{{ $item->unit_kerja }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="font-bold text-gray-800 text-lg">{{ $item->jumlah_box_selesai }} <span class="text-xs text-gray-400 font-normal">/ {{ $item->jumlah_box }}</span></div>
                                    </td>

                                    <td class="py-4 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1">

                                            <!-- PROGRESS HANYA UNTUK USER YBS / ADMIN -->
                                            @if(Auth::user()->role === 'admin' || Auth::id() == $item->user_id)
                                                @if($item->status_kerja != 'Selesai' && $item->status_kerja != 'Menunggu E-Arsip')
                                                <button type="button" onclick="openProgressModal({{ $item->id }})" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition" title="Tambah Progress">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                @endif
                                            @endif

                                            <button type="button" onclick="openHistory({{ $item->id }})" class="p-1.5 text-purple-600 hover:bg-purple-50 rounded-md transition" title="Riwayat">
                                                <i class="fas fa-history"></i>
                                            </button>

                                            <!-- HANYA ADMIN BISA EDIT/HAPUS -->
                                            @if(Auth::user()->role === 'admin')
                                                <a href="{{ route('monitoring.edit', $item->id) }}" class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-md transition"><i class="fas fa-pen"></i></a>
                                                <form action="{{ route('monitoring.destroy', $item->id) }}" method="POST" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete('delete-d-{{ $item->id }}')" id="delete-d-{{ $item->id }}" class="p-1.5 text-[#e92027] hover:bg-red-50 rounded-md transition"><i class="fas fa-trash-alt"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-400">Belum ada data monitoring.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($monitoring->hasPages())
                <div class="p-4 md:p-6 border-t border-gray-100 bg-white">
                    {{ $monitoring->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal History -->
    <div id="historyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeHistoryModal()">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full relative z-50 border border-gray-100">
                <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
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
                                <th class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase rounded-l-lg">Tanggal</th>
                                <th class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Oleh</th>
                                <th class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase">Tahapan</th>
                                <th class="px-4 py-3 text-xs font-bold tracking-wider text-left text-gray-500 uppercase rounded-r-lg">Jumlah Box</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50" id="historyTableBody">
                            <!-- Diisi oleh AJAX -->
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse border-t border-gray-100">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto sm:text-sm transition-all shadow-sm" onclick="closeHistoryModal()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Progress -->
    <div id="progressModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" onclick="closeProgressModal()">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full relative z-50">
                <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#e92027] flex items-center gap-2">
                        <i class="fas fa-plus-circle"></i> Tambah Progress
                    </h3>
                    <button onclick="closeProgressModal()" class="text-gray-400 hover:text-red-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="px-6 py-6">
                    <form id="progressForm" onsubmit="submitProgress(event)">
                        <input type="hidden" id="progressId">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Berapa Box Selesai Ditambahkan?</label>
                                <input type="number" id="jumlah_tambahan" class="w-full border-gray-300 rounded-xl py-3 px-4 focus:ring-[#e92027] focus:border-[#e92027] bg-gray-50 border outline-none shadow-sm" required min="1" placeholder="Contoh: 5">
                                <p class="text-[11px] text-gray-500 mt-1.5"><i class="fas fa-info-circle"></i> Angka ini akan ditambahkan ke total saat ini.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pengerjaan Baru</label>
                                <input type="date" id="tanggal_baru" class="w-full border-gray-300 rounded-xl py-3 px-4 focus:ring-[#e92027] focus:border-[#e92027] bg-gray-50 border outline-none cursor-pointer shadow-sm" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-5">
                            <button type="button" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50" onclick="closeProgressModal()">Batal</button>
                            <button type="submit" class="px-8 py-2.5 bg-[#e92027] text-white rounded-xl font-bold shadow-md hover:bg-[#c41820] hover:-translate-y-0.5 transition-transform">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // HAPUS DATA CONFIRMATION
        function confirmDelete(btnId) {
            const form = document.getElementById(btnId).closest('form');
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e92027',
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: { cancelButton: 'text-gray-700 font-bold' }
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        }

        // LANJUT TAHAP CONFIRMATION
        window.confirmAdvance = function(formId, currentStage) {
            let nextStage = '', confirmTitle = 'Lanjutkan Tahapan?', confirmText = '';

            if(currentStage === 'Pemilahan') nextStage = 'Pendataan';
            else if(currentStage === 'Pendataan') nextStage = 'Pelabelan';
            else if(currentStage === 'Pelabelan') { nextStage = 'MENUNGGU'; confirmTitle = 'Selesaikan Pelabelan?'; confirmText = 'Tugas ini akan terkunci sebagai (Menunggu E-Arsip) sampai staf terkait mengerjakannya.'; }
            else if(currentStage === 'Input E-Arsip') { nextStage = 'SELESAI'; confirmTitle = 'Selesaikan Input E-Arsip?'; confirmText = 'Tugas ini dan seluruh staf lain pada Berita Acara yang sama akan ditandai SELESAI.'; }

            if (!nextStage) return;

            if(nextStage !== 'SELESAI' && nextStage !== 'MENUNGGU') {
                confirmText = `Data akan dipindahkan dari ${currentStage} ke tahap ${nextStage}.`;
            }

            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: (currentStage === 'Input E-Arsip' || currentStage === 'Pelabelan') ? 'warning' : 'question',
                showCancelButton: true,
                confirmButtonColor: (currentStage === 'Input E-Arsip' || currentStage === 'Pelabelan') ? '#10b981' : '#e92027',
                cancelButtonColor: '#E5E7EB',
                confirmButtonText: (currentStage === 'Input E-Arsip' || currentStage === 'Pelabelan') ? 'Ya, Selesaikan' : 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                customClass: { cancelButton: 'text-gray-700 font-bold' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById(formId).submit();
            });
        }

        // HISTORY MODAL LOGIC
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
                        tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">Belum ada riwayat.</td></tr>';
                        return;
                    }

                    data.forEach(item => {
                        const date = new Date(item.created_at).toLocaleString('id-ID', {
                            day: '2-digit', month: 'short', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });

                        // AMBIL HANYA SELISIH (jumlah_tambahan)
                        let tambahan = item.jumlah_tambahan || 0;
                        let displayJumlah = '';

                        if (tambahan > 0) {
                            displayJumlah = `<span class="text-green-600 font-bold bg-green-50 px-2 py-1 rounded-md">+${tambahan} Box</span>`;
                        } else if (tambahan < 0) {
                            displayJumlah = `<span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded-md">${tambahan} Box</span>`;
                        } else {
                            displayJumlah = `<span class="text-gray-400 font-medium italic">0 Box</span>`;
                        }

                        const row = `
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">${date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.user ? item.user.nama : '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${item.keterangan || item.tahapan}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">${displayJumlah}</td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                })
                .catch(error => {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-red-500">Gagal memuat data.</td></tr>';
                });
        }

        window.closeHistoryModal = function() {
            document.getElementById('historyModal').classList.add('hidden');
        }

        // PROGRESS MODAL LOGIC
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

            fetch(`/monitoring/${id}/progress`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ jumlah_tambahan: jumlah, tanggal_baru: tanggal })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeProgressModal();
                    Swal.fire({ title: 'Berhasil!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false })
                    .then(() => window.location.reload());
                } else {
                    Swal.fire('Gagal Menyimpan', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Terjadi kesalahan pada sistem.', 'error');
            });
        }
    </script>
</x-layout>
