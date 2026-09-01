<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-40">
            <svg class="absolute w-full h-full" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                <defs><linearGradient id="polyGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#580000;stop-opacity:0.3" /><stop offset="100%" style="stop-color:#000000;stop-opacity:0.4" /></linearGradient></defs>
                <path fill="url(#polyGrad)" d="M0 0 L1000 0 L1000 500 L0 300 Z" />
                <path fill="#000000" opacity="0.1" d="M-100 0 L500 0 L200 600 L-100 400 Z" />
                <path fill="#580000" opacity="0.2" d="M800 0 L1400 0 L1400 400 L600 600 Z" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto relative z-10 text-center md:text-left">
            <h1 class="text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Form Peminjaman Baru</h1>
            <p class="text-red-50 text-base font-light opacity-95 max-w-lg leading-relaxed drop-shadow-sm">Isi formulir di bawah ini untuk mengajukan peminjaman arsip.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 md:px-6 -mt-20 relative z-20 mb-10" x-data="peminjamanForm()">
        <form action="/peminjaman" method="POST" enctype="multipart/form-data" @submit.prevent="submitForm($el)" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" novalidate>
            @csrf

            <div class="p-6 md:p-8 space-y-8">
                <!-- Data Peminjam -->
                <div>
                    <h2 class="text-lg font-bold text-[#e92027] border-b border-gray-100 pb-3 mb-6 flex items-center gap-3"><i class="fas fa-user-circle text-[#e92027]"></i> Data Peminjaman</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div><label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Peminjaman <span class="text-[#e92027]">*</span></label><input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] transition"></div>
                        <div><label class="block text-sm font-bold text-gray-800 mb-2">Nama Peminjam <span class="text-[#e92027]">*</span></label><input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] transition"></div>
                        <div><label class="block text-sm font-bold text-gray-800 mb-2">NIP <span class="text-[#e92027]">*</span></label><input type="text" name="nip" value="{{ old('nip') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] transition"></div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Jabatan <span class="text-[#e92027]">*</span></label>
                            <div class="relative">
                                <select name="jabatan_peminjam" x-model="jabatan" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 outline-none appearance-none cursor-pointer focus:bg-white focus:border-[#e92027] transition">
                                    <option value="" disabled selected>-- Pilih Jabatan --</option>
                                    <option value="Direksi">Direksi</option><option value="Band I">Band I</option><option value="Band II">Band II</option><option value="Band III">Band III</option><option value="Band IV">Band IV</option><option value="Karyawan/Pelaksana">Karyawan/Pelaksana</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500"><i class="fas fa-chevron-down text-sm"></i></div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Unit Kerja <span class="text-[#e92027]">*</span></label>
                            <div class="relative">
                                <select name="unit" x-model="unit" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 outline-none appearance-none cursor-pointer focus:bg-white focus:border-[#e92027] transition">
                                    <option value="" disabled selected>-- Pilih Unit --</option>
                                    @foreach($units as $u) <option value="{{ $u->nama_unit }}">{{ $u->nama_unit }}</option> @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500"><i class="fas fa-chevron-down text-sm"></i></div>
                            </div>
                        </div>
                        <div class="md:col-span-2"><label class="block text-sm font-bold text-gray-800 mb-2">Keperluan <span class="text-[#e92027]">*</span></label><textarea name="keperluan" rows="3" required class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] transition">{{ old('keperluan') }}</textarea></div>
                    </div>
                </div>

                <!-- Daftar Arsip -->
                <div class="bg-red-50/50 p-4 md:p-6 rounded-2xl border border-red-100">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-lg font-bold text-[#e92027] flex items-center gap-3"><i class="fas fa-box-open"></i> Daftar Arsip</h3>
                        <button type="button" @click="openModal()" class="w-full md:w-auto px-5 py-2.5 bg-[#e92027] text-white text-sm font-bold rounded-xl hover:bg-[#801010] shadow-md transition flex items-center justify-center gap-2"><i class="fas fa-plus-circle"></i> Tambah Arsip</button>
                    </div>

                    <!-- Mobile List View -->
                    <div class="grid grid-cols-1 gap-4 md:hidden">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm relative">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-sm text-gray-800 truncate pr-4" x-text="item.display_name"></div>
                                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 bg-red-50 rounded-lg p-1.5 absolute top-3 right-3"><i class="fas fa-trash-alt"></i></button>
                                </div>
                                <div x-show="item.source === 'manual'" class="mb-2"><span class="text-[9px] font-bold text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded border border-yellow-300">Manual Input</span></div>
                                <div class="grid grid-cols-2 gap-2 text-xs mb-1">
                                    <div class="text-gray-500">Box: <span class="font-mono text-gray-800 font-bold" x-text="item.no_box || '-'"></span></div>
                                    <div class="text-gray-500">Media: <span class="font-bold text-gray-800" x-text="item.media"></span></div>
                                </div>
                                <div class="text-[10px] font-bold px-2 py-0.5 rounded bg-red-50 text-[#a0131a] border border-red-200 inline-block mt-1" x-text="item.akses"></div>

                                <input type="hidden" name="items_source[]" :value="item.source">
                                <input type="hidden" name="items_arsip_id[]" :value="item.id">
                                <input type="hidden" name="items_nama_manual[]" :value="item.nama_manual">
                                <input type="hidden" name="items_box_manual[]" :value="item.no_box">
                                <input type="hidden" name="items_akses_manual[]" :value="item.akses">
                                <input type="hidden" name="items_media[]" :value="item.media">
                                <input type="hidden" name="items_fisik[]" :value="item.fisik">
                            </div>
                        </template>
                        <div x-show="items.length === 0" class="p-6 text-center text-gray-400 bg-white rounded-xl border border-gray-200 italic text-sm">Belum ada arsip yang ditambahkan.</div>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-left table-fixed">
                            <thead class="bg-[#e92027] text-white">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold uppercase w-12 text-center">No</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase w-1/2">Nama Arsip</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase w-24 text-center">Box</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase w-36 text-center">Media & Akses</th>
                                    <th class="px-4 py-3 text-xs font-bold uppercase w-16 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-red-100">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr class="hover:bg-red-50">
                                        <td class="px-4 py-3 text-sm text-center font-bold text-gray-500" x-text="index + 1"></td>
                                        <td class="px-4 py-3 truncate">
                                            <div class="font-bold text-sm text-gray-800 truncate" x-text="item.display_name" :title="item.display_name"></div>
                                            <div x-show="item.source === 'manual'" class="mt-1"><span class="text-[9px] font-bold text-yellow-800 bg-yellow-100 px-2 py-0.5 rounded border border-yellow-300">Manual Input</span></div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-center font-mono text-gray-600" x-text="item.no_box || '-'"></td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-700 border border-gray-200 truncate w-full" x-text="item.media" :title="item.media"></span>
                                                <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-red-50 text-[#a0131a] border border-red-200 truncate w-full" x-text="item.akses" :title="item.akses"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" @click="removeItem(index)" class="text-[#e92027] hover:text-white bg-white border border-red-200 hover:bg-[#e92027] w-8 h-8 rounded-lg flex items-center justify-center mx-auto shadow-sm transition"><i class="fas fa-trash-alt text-xs"></i></button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="items.length === 0"><td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada arsip yang ditambahkan.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bukti -->
                <div>
                    <h2 class="text-lg font-bold text-[#e92027] border-b border-gray-100 pb-3 mb-6 flex items-center gap-3"><i class="fas fa-file-pdf text-[#e92027]"></i> Bukti Peminjaman (PDF) <span class="text-[#e92027]">*</span></h2>
                    <div class="space-y-4">
                        <template x-for="(file, index) in files" :key="file.id">
                            <div class="flex items-center gap-3">
                                <label class="flex-1 flex items-center justify-between px-4 py-3 bg-white border border-gray-300 rounded-xl cursor-pointer hover:border-[#e92027] hover:bg-red-50/30 transition group">
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="bg-red-100 text-[#a0131a] w-10 h-10 rounded-lg flex items-center justify-center border border-red-200 flex-shrink-0"><i class="fas fa-file-pdf text-lg"></i></div>
                                        <div class="flex flex-col overflow-hidden">
                                            <span class="text-sm font-bold text-gray-800 truncate" x-text="file.name ? file.name : 'Pilih File PDF'"></span>
                                            <span class="text-[10px] text-gray-500" x-text="file.name ? 'Siap diupload' : 'Format PDF Maksimal 2 MB'"></span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-[#e92027] bg-white border border-red-200 px-3 py-1.5 rounded-lg group-hover:bg-[#e92027] group-hover:text-white transition">Browse</span>
                                    <input type="file" name="bukti_pinjam[]" class="hidden" accept=".pdf" @change="handleFileChange($event, index)">
                                </label>
                                <button type="button" @click="removeFile(index)" class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-xl border border-red-200 text-[#e92027] bg-white hover:bg-red-100 shadow-sm transition" x-show="files.length > 1 || file.name"><i class="fas fa-trash-alt text-lg"></i></button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addFile()" class="mt-4 w-full py-3 border-2 border-dashed border-red-300 rounded-xl text-[#e92027] font-bold hover:bg-red-50 hover:border-[#e92027] transition flex items-center justify-center gap-2"><i class="fas fa-plus-circle"></i> Tambah File Lain</button>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-end gap-3 rounded-b-3xl">
                <a href="/peminjaman" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold shadow-lg hover:bg-[#a0131a] transition transform hover:-translate-y-0.5">Simpan Peminjaman</button>
            </div>
        </form>

        {{-- Modal Input Arsip --}}
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl overflow-visible border-t-8 border-[#e92027] relative" @click.away="closeModal()">
                <div class="p-6 md:p-8 space-y-6">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-extrabold text-gray-800">Pilih Arsip</h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-[#e92027]"><i class="fas fa-times text-xl"></i></button>
                    </div>

                    <div class="flex bg-gray-100 p-1 rounded-xl">
                        <button type="button" @click="tempItem.source = 'db'" class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all" :class="tempItem.source === 'db' ? 'bg-white text-[#e92027] shadow' : 'text-gray-500 hover:bg-gray-200'">Dari Database</button>
                        <button type="button" @click="tempItem.source = 'manual'" class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all" :class="tempItem.source === 'manual' ? 'bg-white text-[#e92027] shadow' : 'text-gray-500 hover:bg-gray-200'">Input Manual</button>
                    </div>

                    <div x-show="tempItem.source === 'db'" class="space-y-4">
                        <div class="relative">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Cari Arsip (Nama / Isi / Box)</label>
                            <input type="text" x-model="searchQuery" @focus="openDropdown = true" @click="openDropdown = true" @click.away="openDropdown = false" placeholder="Ketik kata kunci..." class="w-full border border-gray-300 rounded-xl pl-4 pr-4 py-3 text-sm focus:ring-2 focus:ring-red-100 focus:border-[#e92027] outline-none transition bg-gray-50 focus:bg-white" autocomplete="off">

                            <!-- DROPDOWN PENCARIAN -->
                            <div x-show="openDropdown" class="absolute z-[999] w-full bg-white border border-gray-200 mt-2 rounded-xl shadow-xl max-h-72 overflow-y-auto" style="display: none;">
                                <ul x-show="filteredArsip.length > 0" class="divide-y divide-gray-100">
                                    <template x-for="opt in filteredArsip" :key="opt.id">
                                        <li @click="selectArsip(opt); openDropdown = false" class="px-4 py-3 hover:bg-red-50 cursor-pointer flex justify-between items-center transition group">
                                            <div class="flex-1 pr-4 overflow-hidden">
                                                <div class="font-bold text-sm text-gray-800 group-hover:text-[#e92027] truncate" x-text="opt.nama_berkas"></div>
                                                <div class="text-[10px] text-gray-500 mt-1 max-w-full truncate" x-text="opt.isi || '-'"></div>
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold">Unit: <span x-text="opt.unit_pengolah_name || '-'"></span></span>
                                                    <span class="bg-white border border-gray-200 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold">Box: <span x-text="opt.no_box || '-'"></span></span>
                                                </div>
                                            </div>
                                            <div class="text-[10px] font-bold px-2 py-1 rounded bg-red-50 text-[#a0131a] border border-red-100 whitespace-nowrap" x-text="opt.hak_akses"></div>
                                        </li>
                                    </template>
                                </ul>
                                <div x-show="filteredArsip.length === 0" class="p-4 text-center text-sm text-gray-500">Tidak ada arsip yang sesuai.</div>
                            </div>
                        </div>
                        <div x-show="tempItem.id" class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800 flex items-start gap-3">
                            <i class="fas fa-check-circle mt-1 text-green-600"></i>
                            <div class="overflow-hidden"><span class="font-bold block mb-1">Arsip Terpilih:</span><span class="truncate block" x-text="tempItem.display_name"></span></div>
                        </div>
                    </div>

                    <div x-show="tempItem.source === 'manual'" class="space-y-4">
                        <div><label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nama Arsip</label><input type="text" x-model="tempItem.nama_manual" class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-[#e92027] outline-none bg-gray-50 focus:bg-white transition"></div>
                        <div><label class="block text-xs font-bold text-gray-700 uppercase mb-2">No. Box</label><input type="text" x-model="tempItem.no_box" class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:border-[#e92027] outline-none bg-gray-50 focus:bg-white transition"></div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Hak Akses</label>
                            <select x-model="tempItem.akses" class="w-full border border-gray-300 rounded-xl p-3 text-sm bg-gray-50 focus:bg-white focus:border-[#e92027] outline-none">
                                <option value="Biasa">Biasa</option>
                                <template x-if="['Direksi', 'Band I', 'Band II'].includes(jabatan) || ( ((unit || '').toLowerCase().includes('hukum') || (unit || '').toLowerCase().includes('internal audit')) && jabatan !== 'Karyawan/Pelaksana' )">
                                    <option value="Terbatas">Terbatas</option>
                                </template>
                                <template x-if="['Direksi', 'Band I'].includes(jabatan) || ( (unit || '').toLowerCase().includes('hukum') && jabatan !== 'Karyawan/Pelaksana' )">
                                    <option value="Rahasia">Rahasia</option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Jenis Media</label>
                            <select x-model="tempItem.media" class="w-full border border-gray-300 rounded-xl p-3 text-sm bg-gray-50 focus:bg-white focus:border-[#e92027] outline-none">
                                <option value="Softfile">Softfile</option><option value="Hardfile">Hardfile</option>
                            </select>
                        </div>
                        <div x-show="tempItem.media === 'Hardfile'">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Detail Fisik</label>
                            <select x-model="tempItem.fisik" class="w-full border border-gray-300 rounded-xl p-3 text-sm bg-gray-50 focus:bg-white focus:border-[#e92027] outline-none">
                                <option value="Berkas Asli">Berkas Asli</option><option value="Berkas Copy">Berkas Copy</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="closeModal()" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition">Batal</button>
                        <button type="button" @click="addItem()" class="px-6 py-3 bg-[#e92027] text-white rounded-xl font-bold hover:bg-[#a0131a] shadow-md transition">Simpan Item</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validation Modal -->
        <div x-show="showValidationModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="showValidationModal = false" class="bg-white rounded-[2rem] w-full max-w-sm p-8 text-center relative overflow-hidden shadow-2xl border-t-8 border-[#e92027]">
                <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-[#e92027] shadow-sm animate-bounce"><i class="fas fa-exclamation-triangle text-3xl"></i></div>
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Perhatian!</h3>
                <template x-if="serverErrors.length > 0">
                    <div class="text-gray-600 mb-6 text-sm text-left bg-red-50 p-4 rounded-xl border border-red-100">
                        <ul class="list-disc list-inside space-y-1">
                            <template x-for="error in serverErrors"><li x-text="error" class="text-[#c41820] font-medium leading-snug"></li></template>
                        </ul>
                    </div>
                </template>
                <template x-if="serverErrors.length === 0">
                    <p class="text-gray-500 mb-8 leading-relaxed">Mohon lengkapi form dengan benar sebelum menyimpan.</p>
                </template>
                <button @click="showValidationModal = false" class="w-full py-3.5 bg-[#e92027] text-white rounded-xl text-sm font-bold hover:bg-[#801010] shadow-lg transition">OK, Saya Mengerti</button>
            </div>
        </div>
    </div>

    <!-- Script SweetAlert & Alpine -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Gunakan window agar tidak error deklarasi ganda saat diload HTMX
        window.daftarArsipData = @json($daftarArsip ?? []);

        window.peminjamanForm = function() {
            return {
                jabatan: '', unit: '', items: [], files: [{ id: Date.now(), name: null }],
                showModal: false, showValidationModal: false, serverErrors: @json($errors->all()),
                searchQuery: '', openDropdown: false,
                tempItem: { source: 'db', id: null, display_name: '', nama_manual: '', no_box: '', akses: 'Biasa', media: 'Softfile', fisik: 'Berkas Asli' },

                init() { if (this.serverErrors.length > 0) this.showValidationModal = true; },
                openModal() { this.tempItem = { source: 'db', id: null, display_name: '', nama_manual: '', no_box: '', akses: 'Biasa', media: 'Softfile', fisik: 'Berkas Asli' }; this.searchQuery = ''; this.showModal = true; },
                closeModal() { this.showModal = false; },

                get filteredArsip() {
                    const query = this.searchQuery.toLowerCase();
                    return window.daftarArsipData.filter(item => {
                        if (query === '') return true;
                        const matchNama = (item.nama_berkas || '').toLowerCase().includes(query);
                        const matchIsi = (item.isi || '').toLowerCase().includes(query);
                        const matchBox = (item.no_box || '').toLowerCase().includes(query);
                        return matchNama || matchIsi || matchBox;
                    }).slice(0, 15);
                },

                selectArsip(arsip) { this.tempItem.id = arsip.id; this.tempItem.display_name = arsip.nama_berkas; this.tempItem.no_box = arsip.no_box; this.tempItem.akses = arsip.hak_akses; this.searchQuery = arsip.nama_berkas; },

                addItem() {
                    if (this.tempItem.source === 'db' && !this.tempItem.id) { this.serverErrors = ['Pilih arsip!']; this.showValidationModal = true; return; }
                    if (this.tempItem.source === 'manual' && !this.tempItem.nama_manual) { this.serverErrors = ['Nama arsip wajib diisi!']; this.showValidationModal = true; return; }
                    if (!this.jabatan) { this.serverErrors = ['Mohon pilih Jabatan di form utama terlebih dahulu.']; this.showValidationModal = true; return; }

                    if (this.tempItem.source === 'manual') this.tempItem.display_name = this.tempItem.nama_manual;

                    const unitLower = (this.unit || '').toLowerCase();
                    const isHukum = unitLower.includes('hukum');
                    const isAudit = unitLower.includes('internal audit');
                    const isPelaksana = (this.jabatan === 'Karyawan/Pelaksana');

                    if (this.tempItem.akses === 'Rahasia') {
                        const allowedByJabatan = ['Direksi', 'Band I'].includes(this.jabatan);
                        const allowedByUnit = isHukum && !isPelaksana;

                        if (!allowedByJabatan && !allowedByUnit) {
                            this.serverErrors = [`Gagal! Jabatan ${this.jabatan} (Unit ${this.unit || '-'}) tidak diizinkan akses arsip Rahasia.`];
                            this.showValidationModal = true; return;
                        }
                    } else if (this.tempItem.akses === 'Terbatas') {
                        const allowedByJabatan = ['Direksi', 'Band I', 'Band II'].includes(this.jabatan);
                        const allowedByUnit = (isHukum || isAudit) && !isPelaksana;

                        if (!allowedByJabatan && !allowedByUnit) {
                            this.serverErrors = [`Gagal! Jabatan ${this.jabatan} (Unit ${this.unit || '-'}) tidak diizinkan akses arsip Terbatas.`];
                            this.showValidationModal = true; return;
                        }
                    }

                    if (this.editingIndex !== undefined && this.editingIndex !== null) {
                        this.items[this.editingIndex] = {...this.tempItem};
                    } else {
                        this.items.push({...this.tempItem});
                    }

                    this.closeModal();
                },

                removeItem(index) { this.items.splice(index, 1); },
                addFile() { this.files.push({ id: Date.now(), name: null }); },
                removeFile(index) { if (this.files.length > 1) this.files.splice(index, 1); else this.files[0].name = null; },

                handleFileChange(e, i) {
                    const file = e.target.files[0];
                    if(file) {
                        if(file.type !== 'application/pdf') {
                            this.serverErrors = ['File bukti wajib berformat PDF!'];
                            this.showValidationModal = true; e.target.value = ''; this.files[i].name = null; return;
                        }
                        if(file.size > 2097152) {
                            this.serverErrors = ['Ukuran file PDF tidak boleh lebih dari 2 MB!'];
                            this.showValidationModal = true; e.target.value = ''; this.files[i].name = null; return;
                        }
                        this.files[i].name = file.name;
                    } else {
                        this.files[i].name = null;
                    }
                },

                submitForm(form) {
                    this.serverErrors = [];
                    let formValid = true;

                    const fieldLabels = {
                        'tanggal': 'Tanggal Peminjaman', 'nama_peminjam': 'Nama Peminjam',
                        'nip': 'NIP', 'unit': 'Unit Kerja', 'keperluan': 'Keperluan'
                    };

                    ['tanggal', 'nama_peminjam', 'nip', 'unit', 'keperluan'].forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (!input || !input.value.trim()) { this.serverErrors.push(`Kotak ${fieldLabels[field]} harus diisi.`); formValid = false; }
                    });
                    if (!this.jabatan) { this.serverErrors.push('Kotak Jabatan harus dipilih.'); formValid = false; }
                    if (this.items.length === 0) { this.serverErrors.push('Tambahkan minimal 1 item arsip yang dipinjam.'); formValid = false; }

                    const hasFile = this.files.some(f => f.name !== null);
                    if (!hasFile) { this.serverErrors.push('Wajib mengupload minimal 1 File Bukti Peminjaman (Format PDF).'); formValid = false; }

                    if (!formValid) { this.showValidationModal = true; return; }

                    Swal.fire({
                        title: 'Simpan Peminjaman?', text: "Pastikan data dan daftar arsip sudah benar.",
                        icon: 'question', showCancelButton: true, confirmButtonColor: '#e92027', cancelButtonColor: '#E5E7EB',
                        confirmButtonText: 'Ya, Simpan', cancelButtonText: 'Batal', customClass: { cancelButton: 'text-gray-700 font-bold' }
                    }).then((result) => {
                        if (result.isConfirmed) { form.submit(); }
                    });
                }
            };
        };
    </script>
</x-layout>
