<x-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- JSON DATA TERSEMBUNYI (KEBAL HTMX) -->
    <script type="application/json" id="arsip-data">{!! json_encode($arsip ?? []) !!}</script>
    <script type="application/json" id="init-data">{!! json_encode($initialData[0] ?? []) !!}</script>

    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-40">
            <svg class="absolute w-full h-full" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                <defs><linearGradient id="polyGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#580000;stop-opacity:0.3" /><stop offset="100%" style="stop-color:#000000;stop-opacity:0.4" /></linearGradient></defs>
                <path fill="url(#polyGrad)" d="M0 0 L1000 0 L1000 500 L0 300 Z" /><path fill="#000000" opacity="0.1" d="M-100 0 L500 0 L200 600 L-100 400 Z" /><path fill="#580000" opacity="0.2" d="M800 0 L1400 0 L1400 400 L600 600 Z" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto relative z-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 drop-shadow-md">Edit Arsip Dokumen</h1>
            <p class="text-amber-100 text-sm font-medium">Nomor Berkas: #{{ $nextNumber }}</p>
        </div>
    </div>

    <!-- INLINE ALPINE JS (TIDAK BUTUH REFRESH) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 py-8"
         x-data="{
            arsip: {}, initData: {},
            unitPengolah: '', hakAkses: '-', masaSimpan: '-', tindakanAkhir: '-',

            init() {
                this.arsip = JSON.parse(document.getElementById('arsip-data').textContent || '{}');
                this.initData = JSON.parse(document.getElementById('init-data').textContent || '{}');

                this.unitPengolah = this.initData.unit_pengolah || '';
                this.hakAkses = this.initData.hak_akses || '-';
                this.masaSimpan = this.initData.masa_simpan || '-';
                this.tindakanAkhir = this.initData.tindakan_akhir || '-';
            },

            validateEditSubmit(e) {
                const form = e.target;
                const namaBerkas = form.querySelector('[name=\'nama_berkas\']').value;
                const isiBerkas = form.querySelector('[name=\'isi_berkas[0][isi]\']').value;

                let errorMessages = [];
                if (!namaBerkas || namaBerkas.trim() === '') errorMessages.push('<b>Nama Berkas</b> masih kosong.');
                if (!isiBerkas || isiBerkas.trim() === '') errorMessages.push('<b>Uraian Berkas</b> masih kosong.');

                if (errorMessages.length > 0) {
                    Swal.fire({
                        icon: 'error', title: 'Data Belum Lengkap!',
                        html: '<div class=\'text-left text-sm mt-2\'><ul class=\'list-disc pl-5\'><li>' + errorMessages.join('</li><li>') + '</li></ul></div>',
                        confirmButtonColor: '#e92027'
                    });
                } else {
                    form.submit();
                }
            },
            updateDefaults(data) {
                this.hakAkses = data.hak_akses || '';
                this.masaSimpan = data.masa_simpan || '';
                this.tindakanAkhir = data.tindakan || '';
            }
         }"
         @classification-selected.window="updateDefaults($event.detail)">

        @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-lg flex items-center gap-4">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="list-disc ml-5 text-sm mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden flex flex-col">
            <form id="formEditArsip" action="{{ route('arsip.update', $arsip->id) }}" method="POST" @submit.prevent="validateEditSubmit($event)" class="flex flex-col flex-1" hx-disable>
                @csrf @method('PUT')

                {{-- Content --}}
                <div class="p-8 bg-gray-50/50 space-y-8">

                    {{-- Nama Berkas --}}
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-2">
                        <label class="block font-bold text-gray-700 text-sm uppercase tracking-wide">Nama Berkas <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_berkas" value="{{ old('nama_berkas', $arsip->nama_berkas) }}"
                            class="w-full px-4 py-3 border-2 border-gray-100 rounded-xl font-bold text-gray-800 focus:border-red-500 outline-none transition-all shadow-sm">
                    </div>

                    {{-- Unit & Klasifikasi --}}
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold text-gray-700 text-sm uppercase tracking-wide mb-2">Unit Pengolah</label>
                            <select name="isi_berkas[0][unit_pengolah]" x-model="unitPengolah" class="w-full px-4 py-4 border-2 border-gray-100 rounded-xl font-bold text-gray-800 focus:border-red-500 outline-none cursor-pointer shadow-sm">
                                <option value="" disabled>Pilih Unit Asal</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->nama_unit }}">{{ $unit->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Klasifikasi Dropdown (INLINE ALPINE JS) --}}
                        <div class="relative group"
                             x-data="{
                                open: false, step: 1, breadcrumbs: [], options: [], loading: false,
                                selectedItem: null, displayText: 'Pilih Kode Klasifikasi',
                                init() {
                                    const initData = JSON.parse(document.getElementById('init-data').textContent || '{}');
                                    this.fetchOptions(1);
                                    if (initData && initData.kode_klasifikasi) {
                                        this.selectedItem = { code: initData.kode_klasifikasi, label: initData.kode_klasifikasi, id: initData.klasifikasi_id };
                                        this.displayText = initData.kode_klasifikasi;
                                    }
                                },
                                toggle() {
                                    this.open = !this.open;
                                    if(this.open) {
                                       if (this.options.length === 0) this.fetchOptions(1);
                                       this.step = 1; this.breadcrumbs = []; this.fetchOptions(1);
                                    }
                                },
                                fetchOptions(level, parent = null) {
                                    this.loading = true;
                                    fetch(`/api/klasifikasi-options?level=${level}&parent=${parent}`)
                                        .then(res => res.json()).then(data => { this.options = data; this.loading = false; });
                                },
                                selectOption(opt) {
                                    if (this.step === 1) {
                                        this.breadcrumbs.push({ level: 1, label: opt.label, value: opt.code });
                                        this.step = 2; this.fetchOptions(2, opt.code);
                                    } else if (this.step === 2) {
                                        this.breadcrumbs.push({ level: 2, label: opt.label, value: opt.code });
                                        this.step = 3; this.fetchOptions(3, opt.code);
                                    } else if (this.step === 3) {
                                        this.selectedItem = opt; this.displayText = opt.label;
                                        this.$dispatch('classification-selected', { hak_akses: opt.hak_akses, masa_simpan: opt.masa_simpan, tindakan: opt.tindakan_akhir, code: opt.code, id: opt.id });
                                        this.open = false;
                                    }
                                },
                                goBack() {
                                    if (this.step > 1) {
                                        this.step--; this.breadcrumbs.pop();
                                        const parent = this.breadcrumbs.length > 0 ? this.breadcrumbs[this.breadcrumbs.length - 1].value : null;
                                        this.fetchOptions(this.step, parent);
                                    }
                                }
                             }">

                            <input type="hidden" name="isi_berkas[0][klasifikasi_id]" :value="selectedItem ? selectedItem.id : ''">

                            <label class="block font-bold text-gray-700 mb-2 text-sm uppercase tracking-wide">Kode Klasifikasi</label>
                            <div @click="toggle()" class="w-full p-4 bg-white rounded-xl border-2 border-gray-100 cursor-pointer flex justify-between items-center shadow-sm hover:bg-gray-50 transition-colors">
                                <span x-text="displayText" :class="{'text-gray-400': !selectedItem, 'text-gray-800 font-bold': selectedItem}"></span>
                                <svg class="w-5 h-5 text-gray-400 transition" :class="{'rotate-180 text-red-500': open}" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>

                            <div x-show="open" @click.away="open = false" class="absolute z-50 w-full mt-2 bg-white border border-red-100 rounded-2xl shadow-2xl max-h-80 overflow-y-auto" style="display: none;">
                                <div class="px-5 py-3 bg-red-50/50 border-b border-red-100 flex items-center gap-3 sticky top-0 backdrop-blur-sm z-10">
                                    <template x-if="step > 1">
                                        <button type="button" @click.stop="goBack()" class="p-1 hover:bg-red-100 rounded-full text-red-600 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                                    </template>
                                    <span class="text-xs font-bold text-red-800 tracking-wider uppercase" x-text="step === 1 ? 'Pilih Pokok Masalah' : (step === 2 ? 'Pilih Sub Masalah' : 'Pilih Jenis Arsip')"></span>
                                </div>
                                <ul x-show="!loading" class="py-2">
                                    <template x-for="option in options" :key="option.code">
                                        <li @click="selectOption(option)" class="px-5 py-3 hover:bg-red-50 cursor-pointer text-sm text-gray-700 flex justify-between items-center group transition">
                                            <span x-text="option.label" class="group-hover:text-red-700 font-medium"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Metadata Info Otomatis --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 uppercase">Akses</span>
                            <input type="hidden" name="isi_berkas[0][hak_akses]" :value="hakAkses">
                            <span x-text="hakAkses || '-'" class="font-bold text-red-600 text-sm"></span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 uppercase">Masa Simpan</span>
                            <input type="hidden" name="isi_berkas[0][masa_simpan]" :value="masaSimpan">
                            <span x-text="masaSimpan || '-'" class="font-bold text-gray-700 text-sm"></span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 uppercase">Tindakan Akhir</span>
                            <input type="hidden" name="isi_berkas[0][tindakan_akhir]" :value="tindakanAkhir">
                            <span x-text="tindakanAkhir || '-'" class="font-bold text-gray-700 text-sm"></span>
                        </div>
                    </div>

                    {{-- Rincian Arsip --}}
                    <div class="bg-gradient-to-br from-amber-50 to-white p-6 rounded-3xl border border-amber-100 shadow-sm space-y-4">
                        <h3 class="text-sm font-black text-amber-900 uppercase tracking-wide flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs">02</span> Rincian Dokumen
                        </h3>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-500 uppercase ml-1">Deskripsi / Uraian Berkas <span class="text-red-500">*</span></label>
                            <textarea name="isi_berkas[0][isi]" rows="2" class="w-full p-4 border-2 border-gray-100 bg-white rounded-xl outline-none focus:border-amber-500 font-medium text-sm shadow-sm">{{ old('isi', $arsip->isi) }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1 block mb-1">Jenis Media</label>
                                <select name="isi_berkas[0][jenis_media]" class="w-full p-3 border border-gray-200 rounded-xl text-sm font-medium outline-none focus:border-amber-500">
                                    <option value="Kertas" {{ $arsip->jenis_media == 'Kertas' ? 'selected' : '' }}>Kertas</option>
                                    <option value="Foto" {{ $arsip->jenis_media == 'Foto' ? 'selected' : '' }}>Foto</option>
                                    <option value="Kartografi" {{ $arsip->jenis_media == 'Kartografi' ? 'selected' : '' }}>Kartografi</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1 block mb-1">No Box</label>
                                <input type="text" name="isi_berkas[0][no_box]" value="{{ old('no_box', $arsip->no_box) }}" class="w-full p-3 border border-gray-200 rounded-xl text-sm text-center font-bold outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1 block mb-1">Tahun</label>
                                <input type="number" name="isi_berkas[0][tahun]" value="{{ old('tahun', $arsip->tahun) }}" class="w-full p-3 border border-gray-200 rounded-xl text-sm text-center font-medium outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1 block mb-1">Tanggal</label>
                                <input type="date" name="isi_berkas[0][tanggal]" value="{{ old('tanggal', $arsip->tanggal_masuk) }}" class="w-full p-3 border border-gray-200 rounded-xl text-sm text-center font-medium outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase ml-1 block mb-1">Jumlah</label>
                                <input type="number" name="isi_berkas[0][jumlah]" value="{{ old('jumlah', $arsip->jumlah) }}" min="1" class="w-full p-3 border border-gray-200 rounded-xl text-sm text-center font-bold outline-none focus:border-amber-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer / Submit --}}
                <div class="px-8 py-5 bg-white border-t border-gray-100 flex justify-between items-center rounded-b-3xl">
                    <a href="{{ route('arsip.index') }}" class="text-gray-500 font-bold px-4 py-2 text-sm hover:text-gray-800 transition">Batal</a>
                    <button type="submit" class="px-8 py-4 bg-gradient-to-r from-amber-600 to-red-800 text-white rounded-xl font-bold text-base shadow-lg hover:scale-[1.02] transition-all flex items-center gap-2">
                        SIMPAN PERUBAHAN
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
