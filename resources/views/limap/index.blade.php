<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-6">
           <div class="text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Daftar Papan 5P</h2>
                <p class="text-red-50 text-sm md:text-base font-light opacity-95">Informasi pemilahan, penataan, pembersihan, penjagaan, dan pendisiplinan.</p>
           </div>
           <div class="flex flex-wrap gap-2 justify-center">
               <a href="{{ route('limap.export') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-3 rounded-xl font-bold border border-white/30 transition flex items-center gap-2"><i class="fas fa-file-export"></i> <span class="hidden md:inline">Export</span></a>

               @if(Auth::check() && Auth::user()->role == 'admin')
                   <button x-data @click="$dispatch('open-import-modal')" class="bg-white/20 hover:bg-white/30 text-white px-4 py-3 rounded-xl font-bold border border-white/30 transition flex items-center gap-2"><i class="fas fa-file-import"></i> <span class="hidden md:inline">Import</span></button>
                   <a href="{{ route('limap.create') }}" class="bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-xl font-extrabold shadow-xl flex items-center gap-2 transition"><i class="fas fa-plus-circle"></i> Tambah 5P</a>
               @endif
           </div>
       </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 mb-12">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <div class="bg-green-100 p-2 rounded-full text-green-600"><i class="fas fa-check"></i></div>
                <p class="text-sm font-bold text-green-800 flex-1">{{ session('success') }}</p>
                <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- TAMPILAN MOBILE (CARDS KHUSUS HP)          -->
        <!-- ========================================== -->
        <div class="block lg:hidden space-y-4">
            @forelse($data as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 relative overflow-hidden group">
                    <div class="mb-3 relative z-10">
                        <span class="text-[10px] font-bold text-[#e92027] uppercase tracking-wider bg-red-50 px-2 py-1 rounded border border-red-100">PIC Area</span>
                        <h3 class="font-extrabold text-lg text-gray-800 mt-1">{{ $item->pic ?: 'Belum Ditentukan' }}</h3>
                    </div>
                    <div class="text-xs text-gray-500 line-clamp-2 mb-4 relative z-10 leading-relaxed">
                        <b>Area:</b> {{ $item->pembagian_area ?: 'Belum ada data area...' }}
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-100 relative z-10">
                        <a href="{{ route('limap.show', $item->id) }}" class="text-xs font-bold text-[#e92027] hover:text-[#c41820] flex items-center gap-1">Buka Papan <i class="fas fa-arrow-right"></i></a>
                        @if(Auth::check() && Auth::user()->role == 'admin')
                            <div class="flex gap-2">
                                <a href="{{ route('limap.edit', $item->id) }}" class="w-7 h-7 flex items-center justify-center bg-gray-50 text-amber-500 rounded-md border border-gray-200"><i class="fas fa-pen text-[10px]"></i></a>
                                <form action="{{ route('limap.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data 5P ini secara permanen?');">
                                    @csrf @method('DELETE')
                                    <button class="w-7 h-7 flex items-center justify-center bg-gray-50 text-[#e92027] rounded-md border border-gray-200"><i class="fas fa-trash-alt text-[10px]"></i></button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-12 text-center bg-white rounded-2xl border border-gray-100"><i class="fas fa-flask text-3xl mb-3 text-gray-300"></i><p class="text-sm text-gray-400 font-medium">Belum ada data 5P.</p></div>
            @endforelse
        </div>

        <!-- ========================================== -->
        <!-- TAMPILAN DESKTOP (TABEL FIT SCREEN LAPTOP) -->
        <!-- ========================================== -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse table-fixed">
                <thead>
                    <tr class="bg-[#e92027] text-white uppercase tracking-wider text-xs">
                        <th class="py-4 px-4 font-bold text-center w-[5%] border-r border-red-900/20">No</th>
                        <th class="py-4 px-6 font-bold w-[25%] border-r border-red-900/20">PIC Area</th>
                        <th class="py-4 px-6 font-bold w-[45%] border-r border-red-900/20">Pembagian Area</th>
                        <th class="py-4 px-4 font-bold text-center w-[25%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($data as $index => $item)
                        <tr class="hover:bg-red-50/40 transition duration-200 group">
                            <td class="py-4 px-4 text-center font-bold text-gray-500 border-r border-gray-100 align-top">{{ $index + 1 }}</td>
                            <td class="py-4 px-6 border-r border-gray-100 align-top">
                                <div class="font-extrabold text-gray-900">{{ $item->pic ?: 'Belum Ditentukan' }}</div>
                            </td>
                            <td class="py-4 px-6 border-r border-gray-100 align-top">
                                <div class="text-gray-600 line-clamp-2 leading-relaxed whitespace-pre-line" title="{{ $item->pembagian_area }}">{{ $item->pembagian_area ?: 'Belum ada data area...' }}</div>
                            </td>
                            <td class="py-4 px-4 text-center align-middle">
                                <div class="flex justify-center items-center gap-2">
                                    <!-- Tombol Buka Papan -->
                                    <a href="{{ route('limap.show', $item->id) }}" class="px-4 py-2 bg-red-50 text-[#e92027] font-bold rounded-lg border border-red-200 hover:bg-[#e92027] hover:text-white transition flex items-center gap-2 text-xs shadow-sm">
                                        <i class="fas fa-external-link-alt"></i> Buka Papan
                                    </a>

                                    <!-- Tombol Edit & Hapus (Khusus Admin) -->
                                    @if(Auth::check() && Auth::user()->role == 'admin')
                                        <a href="{{ route('limap.edit', $item->id) }}" class="w-8 h-8 flex items-center justify-center bg-white text-amber-500 rounded-lg hover:bg-amber-50 border border-gray-200 transition shadow-sm" title="Edit">
                                            <i class="fas fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('limap.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data 5P ini secara permanen?');" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button class="w-8 h-8 flex items-center justify-center bg-white text-[#e92027] rounded-lg hover:bg-red-50 border border-gray-200 transition shadow-sm" title="Hapus">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-16 text-center bg-gray-50/50">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-flask text-4xl mb-3 text-gray-300"></i>
                                    <span class="text-gray-400 font-medium">Belum ada data 5P yang ditambahkan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL IMPORT EXCEL (HANYA DIRENDER JIKA ADMIN) -->
    @if(Auth::check() && Auth::user()->role == 'admin')
    <div x-data="{ showImport: false }" @open-import-modal.window="showImport = true">
        <div x-show="showImport" style="display: none;" class="fixed inset-0 z-[999] flex items-center justify-center p-4">
            <div @click="showImport = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>
            <div class="relative bg-white rounded-[2rem] w-full max-w-md shadow-2xl border-t-8 border-green-600 overflow-hidden z-10">
                <form action="{{ route('limap.import') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading()">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1">Import Data 5P</h3>
                        <p class="text-xs text-gray-500 mb-6">Unggah file Excel (.xlsx) untuk menambahkan data secara massal.</p>
                        <input type="file" name="file" required accept=".xlsx, .xls, .csv" class="w-full border-2 border-dashed border-gray-200 rounded-2xl p-4 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer mb-4">
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                        <button type="button" @click="showImport = false" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold shadow-md hover:bg-green-700">Import Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showLoading() {
            Swal.fire({ title: 'Mengimpor Data...', text: 'Mohon tunggu sebentar.', allowOutsideClick: false, showConfirmButton: false, didOpen: () => { Swal.showLoading(); } });
        }
    </script>
</x-layout>
