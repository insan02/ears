<div x-data="{ showDeleteModal: false, deleteUrl: '' }" class="relative">

    <!-- ========================================== -->
    <!-- TAMPILAN MOBILE (CARDS KHUSUS HP)          -->
    <!-- ========================================== -->
    <div class="block xl:hidden space-y-4">
        @forelse ($arsips as $arsip)
            @php
                $checkFields = [
                    $arsip->no_berkas, $arsip->nama_berkas, $arsip->isi,
                    $arsip->tahun, $arsip->tanggal_masuk, $arsip->jumlah,
                    $arsip->hak_akses, $arsip->masa_simpan, $arsip->tindakan_akhir,
                    $arsip->no_box, $arsip->unit_pengolah, $arsip->jenis_media
                ];
                $nonEmpty = 0; $mergedText = '';
                foreach($checkFields as $f) {
                    if ($f !== null && trim($f) !== '' && trim($f) !== '-') {
                        $nonEmpty++; $mergedText = $f;
                    }
                }
                $isMerged = ($nonEmpty === 1);
                $tindakanLower = strtolower($arsip->tindakan_akhir ?? '-');
            @endphp

            @if($isMerged)
                <div class="bg-gray-100 p-4 rounded-xl text-center font-bold text-gray-800 uppercase tracking-widest border border-gray-200 shadow-sm relative">
                    {{ $mergedText }}
                    <button type="button" @click="deleteUrl = '{{ route('arsip.destroy', $arsip->id) }}'; showDeleteModal = true" class="absolute top-2 right-2 p-1.5 bg-white text-[#e92027] rounded shadow-sm border border-gray-200 hover:bg-red-50"><i class="fas fa-trash-alt text-[10px]"></i></button>
                </div>
            @else
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 relative group">
                    <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                        <div class="pr-8">
                            <div class="flex items-center gap-2 mb-1">
                                @if($arsip->no_berkas)
                                    <span class="inline-block bg-gray-100 text-gray-700 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200">No: {{ $arsip->no_berkas }}</span>
                                @endif
                                @if($arsip->nama_berkas)
                                    <span class="inline-block bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded border border-red-200">{{ $arsip->klasifikasi->kode_klasifikasi ?? '-' }}</span>
                                @endif
                            </div>
                            @if($arsip->nama_berkas)
                                <div class="font-bold text-gray-900 text-sm leading-relaxed">{{ $arsip->nama_berkas }}</div>
                            @endif
                        </div>
                        <div class="absolute top-4 right-4">
                            <input type="checkbox" name="selected_arsip[]" value="{{ $arsip->id }}" class="rounded border-gray-300 text-[#e92027] focus:ring-[#e92027] cursor-pointer w-4 h-4">
                        </div>
                    </div>

                    <div class="text-xs text-gray-600 mb-3 leading-relaxed whitespace-normal">{{ $arsip->isi }}</div>

                    <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-xl border border-gray-100 mb-3 text-[11px]">
                        <div><span class="text-gray-400 font-bold uppercase block">Tahun</span><span class="font-bold text-gray-800">{{ $arsip->tahun ?? '-' }}</span></div>
                        <div><span class="text-gray-400 font-bold uppercase block">Box</span><span class="font-mono font-bold text-gray-700">{{ $arsip->no_box ?? '-' }}</span></div>
                        <div><span class="text-gray-400 font-bold uppercase block">Jumlah</span><span class="font-bold text-gray-800">{{ $arsip->jumlah ?? '-' }}</span></div>
                        <div><span class="text-gray-400 font-bold uppercase block">Akses</span><span class="font-bold text-red-600">{{ $arsip->hak_akses ?? '-' }}</span></div>
                    </div>

                    <div class="flex flex-wrap gap-1 mb-3">
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[9px] font-bold uppercase rounded border border-gray-200 whitespace-normal text-center">{{ $arsip->unit_pengolah ?? '-' }}</span>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[9px] font-bold uppercase rounded border border-blue-200 whitespace-nowrap">{{ $arsip->jenis_media ?? '-' }}</span>
                        <span class="px-2 py-0.5 bg-purple-50 text-purple-700 text-[9px] font-bold uppercase rounded border border-purple-200 whitespace-normal text-center">{{ $arsip->tindakan_akhir ?? '-' }}</span>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <a href="{{ route('arsip.edit', $arsip->id) }}" class="p-2 bg-white text-amber-500 rounded-lg shadow-sm border border-gray-200 hover:bg-amber-50"><i class="fas fa-pen text-xs"></i></a>
                        @if(str_contains($tindakanLower, 'musnah'))
                            <button type="button" @click="deleteUrl = '{{ route('arsip.destroy', $arsip->id) }}'; showDeleteModal = true" class="p-2 bg-white text-[#e92027] rounded-lg shadow-sm border border-gray-200 hover:bg-red-50"><i class="fas fa-fire text-xs"></i></button>
                        @endif
                    </div>
                </div>
            @endif
        @empty
            <div class="py-12 text-center bg-white rounded-2xl border border-gray-200"><i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i><p class="text-sm text-gray-400">Belum ada arsip ditemukan.</p></div>
        @endforelse
    </div>

    <!-- ========================================== -->
    <!-- TAMPILAN DESKTOP (TABEL FIT SCREEN LAPTOP) -->
    <!-- ========================================== -->
    <div class="hidden xl:block rounded-xl border border-gray-200 shadow-sm bg-white overflow-hidden w-full">
        <!-- PERBAIKAN LEBAR KOLOM = TOTAL PAS 100% -->
        <table class="w-full text-left border-collapse table-fixed bg-white">
            <thead>
                <tr class="bg-[#e92027] text-white uppercase tracking-wider text-[9px] shadow-sm">
                    <th class="py-3 px-1 text-center w-[2%] border-r border-red-900/20"><input type="checkbox" onclick="toggleAll(this)" class="rounded border-none focus:ring-0 text-red-600 bg-white cursor-pointer w-3 h-3"></th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[3%]">No</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[5%]">Kode</th>
                    <th class="py-3 px-2 font-bold border-r border-red-900/20 w-[15%]">Nama Berkas</th>
                    <th class="py-3 px-2 font-bold border-r border-red-900/20 w-[21%]">Isi Arsip</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[4%]">Thn</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[5%]">Tgl</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[3%]">Jml</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[5%]">Akses</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[6%]">Retensi</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[8%]">Status</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[4%]">Box</th>
                    <th class="py-3 px-2 font-bold border-r border-red-900/20 w-[10%]">Unit</th>
                    <th class="py-3 px-1 text-center font-bold border-r border-red-900/20 w-[5%]">Media</th>
                    <th class="py-3 px-1 text-center font-bold w-[4%]">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($arsips as $arsip)
                    @php
                        $checkFields = [
                            $arsip->no_berkas, $arsip->nama_berkas, $arsip->isi,
                            $arsip->tahun, $arsip->tanggal_masuk, $arsip->jumlah,
                            $arsip->hak_akses, $arsip->masa_simpan, $arsip->tindakan_akhir,
                            $arsip->no_box, $arsip->unit_pengolah, $arsip->jenis_media
                        ];
                        $nonEmpty = 0; $mergedText = '';
                        foreach($checkFields as $f) {
                            if ($f !== null && trim($f) !== '' && trim($f) !== '-') {
                                $nonEmpty++; $mergedText = $f;
                            }
                        }
                        $isMerged = ($nonEmpty === 1);
                        $tindakan = $arsip->tindakan_akhir ?? '-';
                        $tindakanLower = strtolower($tindakan);
                    @endphp

                    @if($isMerged)
                        <tr class="group hover:bg-gray-100 transition-all duration-200 text-[10px] bg-gray-50/80">
                            <td class="py-2 px-1 text-center border-r border-gray-100 align-middle">
                                <input type="checkbox" name="selected_arsip[]" value="{{ $arsip->id }}" class="rounded border-gray-300 text-[#e92027] focus:ring-[#e92027] cursor-pointer w-3 h-3">
                            </td>
                            <td colspan="13" class="py-3 px-4 text-center font-black text-gray-800 uppercase tracking-widest border-r border-gray-100 align-middle">
                                {{ $mergedText }}
                            </td>
                            <td class="py-2 px-1 text-center align-middle">
                                <button type="button" @click="deleteUrl = '{{ route('arsip.destroy', $arsip->id) }}'; showDeleteModal = true" class="w-6 h-6 flex items-center justify-center bg-white text-[#e92027] rounded border border-gray-200 hover:bg-red-50 mx-auto" title="Hapus">
                                    <i class="fas fa-trash-alt text-[9px]"></i>
                                </button>
                            </td>
                        </tr>
                    @else
                        <!-- PERBAIKAN CLASS: Menggunakan whitespace-nowrap untuk kolom pendek, dan whitespace-normal untuk teks panjang -->
                        <tr class="group hover:bg-red-50/40 transition-all duration-200 text-[10px]">
                            <td class="py-2 px-1 text-center border-r border-gray-100 align-top">
                                <input type="checkbox" name="selected_arsip[]" value="{{ $arsip->id }}" class="rounded border-gray-300 text-[#e92027] focus:ring-[#e92027] cursor-pointer w-3 h-3 mt-1">
                            </td>

                            <td class="py-2 px-1 text-center font-bold text-gray-500 align-top border-r border-gray-100 whitespace-nowrap">
                                {{ $arsip->no_berkas ?? '-' }}
                            </td>

                            <td class="py-2 px-1 font-bold text-gray-700 align-top border-r border-gray-100 text-center">
                                @if($arsip->nama_berkas) {{ $arsip->klasifikasi->kode_klasifikasi ?? '-' }} @else - @endif
                            </td>

                            <!-- Hanya Nama dan Isi yang diperbolehkan Break-Words (Jika kata terlalu panjang) -->
                            <td class="py-2 px-2 font-bold text-gray-900 align-top border-r border-gray-100 whitespace-normal break-words leading-relaxed">
                                {{ $arsip->nama_berkas ?? '-' }}
                            </td>

                            <td class="py-2 px-2 text-gray-600 align-top border-r border-gray-100 whitespace-normal break-words leading-relaxed">
                                {{ $arsip->isi ?? '-' }}
                            </td>

                            <td class="py-2 px-1 text-center font-medium text-gray-700 border-r border-gray-100 align-top whitespace-nowrap">
                                {{ $arsip->tahun ?? '-' }}
                            </td>

                            <td class="py-2 px-1 text-center text-gray-500 border-r border-gray-100 align-top whitespace-nowrap">
                                {{ $arsip->tanggal_masuk ? \Carbon\Carbon::parse($arsip->tanggal_masuk)->format('d/m/y') : '-' }}
                            </td>

                            <td class="py-2 px-1 text-center font-bold text-gray-800 border-r border-gray-100 align-top whitespace-nowrap">
                                {{ $arsip->jumlah ?? '-' }}
                            </td>

                            <td class="py-2 px-1 text-center border-r border-gray-100 align-top">
                                @php
                                    $akses = $arsip->hak_akses; $colorClass = 'text-gray-700';
                                    if(in_array($akses, ['Biasa', 'Terbuka'])) $colorClass = 'text-green-600';
                                    elseif($akses == 'Terbatas') $colorClass = 'text-yellow-600';
                                    elseif(in_array($akses, ['Rahasia', 'Tertutup', 'Sangat Rahasia'])) $colorClass = 'text-red-600';
                                @endphp
                                <span class="font-bold uppercase {{ $colorClass }}">{{ $akses ?? '-' }}</span>
                            </td>

                            <td class="py-2 px-1 text-center text-gray-600 border-r border-gray-100 align-top">
                                {{ $arsip->masa_simpan ?? '-' }}
                            </td>

                            <td class="py-2 px-1 text-center border-r border-gray-100 align-top">
                                @if (str_contains($tindakanLower, 'musnah'))
                                    <span class="font-bold uppercase text-red-600 block">{{ $tindakan }}</span>
                                @elseif (str_contains($tindakanLower, 'permanen'))
                                    <span class="font-bold uppercase text-blue-600 block">{{ $tindakan }}</span>
                                @elseif (str_contains($tindakanLower, 'dinilai'))
                                    <span class="font-bold uppercase text-yellow-600 block">{{ $tindakan }}</span>
                                @else
                                    <span class="text-gray-500 font-bold uppercase block">{{ $tindakan }}</span>
                                @endif
                            </td>

                            <td class="py-2 px-1 text-center font-mono font-bold text-[#e92027] border-r border-gray-100 align-top whitespace-nowrap">
                                {{ $arsip->no_box ?? '-' }}
                            </td>

                            <td class="py-2 px-2 text-gray-600 font-bold uppercase tracking-wide border-r border-gray-100 align-top whitespace-normal break-words">
                                {{ $arsip->unit_pengolah ?? '-' }}
                            </td>

                            <td class="py-2 px-1 text-center border-r border-gray-100 align-top">
                                <span class="font-bold text-purple-700 uppercase block">{{ $arsip->jenis_media ?? '-' }}</span>
                            </td>

                            <td class="py-2 px-1 text-center align-top">
                                <div class="flex justify-center items-center gap-1 mt-1">
                                    <a href="{{ route('arsip.edit', $arsip->id) }}" class="w-6 h-6 flex items-center justify-center bg-white text-amber-500 rounded border border-gray-200 hover:bg-amber-50" title="Edit">
                                        <i class="fas fa-pen text-[9px]"></i>
                                    </a>
                                    @if(str_contains($tindakanLower, 'musnah'))
                                        <button type="button" @click="deleteUrl = '{{ route('arsip.destroy', $arsip->id) }}'; showDeleteModal = true" class="w-6 h-6 flex items-center justify-center bg-white text-[#e92027] rounded border border-gray-200 hover:bg-red-50" title="Musnahkan">
                                            <i class="fas fa-fire text-[9px]"></i>
                                        </button>
                                    @else
                                        <button disabled class="w-6 h-6 flex items-center justify-center bg-gray-50 text-gray-300 rounded border border-gray-100 cursor-not-allowed">
                                            <i class="fas fa-fire text-[9px]"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="15" class="py-12 text-center bg-gray-50/50">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fas fa-folder-open text-3xl mb-2 text-gray-300"></i>
                                <span class="font-bold text-sm">Tidak ada data arsip.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Delete Modal --}}
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="showDeleteModal = false"></div>
        <div class="bg-white rounded-3xl w-full max-w-sm p-8 text-center relative z-10 shadow-2xl">
            <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-[#e92027] animate-bounce"><i class="fas fa-exclamation-triangle text-3xl"></i></div>
            <h3 class="text-xl font-extrabold text-gray-800 mb-2">Musnahkan Arsip?</h3>
            <p class="text-gray-500 text-sm mb-6 leading-relaxed">Arsip ini akan dipindahkan ke daftar <b>Data Musnah</b>.</p>
            <div class="flex flex-col gap-3">
                <form :action="deleteUrl" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3.5 bg-[#e92027] text-white rounded-xl text-sm font-bold hover:bg-[#c41820] shadow-md transition">Ya, Musnahkan</button>
                </form>
                <button type="button" @click="showDeleteModal = false" class="w-full py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition">Batal</button>
            </div>
        </div>
    </div>
</div>
