<x-layout>
    <div x-data="arsipForm()" @classification-selected="updateDefaults($event.detail)">

        {{-- Error Alert --}}
        @if ($errors->any())
        <div class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl shadow-2xl flex items-center gap-4 animate-bounce">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <strong class="font-bold">Terjadi Kesalahan!</strong>
                <ul class="list-disc ml-5 text-sm mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        @endif


        {{-- Main Card Container --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden flex flex-col min-h-[600px]">

                <form action="{{ isset($arsip) ? route('arsip.update', $arsip->id) : route('arsip.store') }}" method="POST" class="flex flex-col flex-1 h-full">
                    @csrf
                    @if(isset($arsip))
                        @method('PUT')
                    @endif

                    {{-- Header --}}
                    <div class="px-8 py-6 border-b border-gray-50 bg-gradient-to-r from-red-600 to-red-800 flex justify-between items-center shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm text-white shadow-inner">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-black text-white tracking-tight">{{ isset($arsip) ? 'Edit Arsip' : 'Input Daftar Arsip' }}</h1>
                                <p class="text-red-100 text-sm font-medium">Kelola dokumen anda dengan mudah dan rapi.</p>
                            </div>
                        </div>

                        {{-- Step Indicator --}}
                        <div class="hidden md:flex items-center gap-3 bg-black/10 px-4 py-2 rounded-full backdrop-blur-md border border-white/10">
                            <div class="flex items-center gap-2" :class="{'opacity-100': formStep === 1, 'opacity-50': formStep !== 1}">
                                <span class="w-6 h-6 rounded-full bg-white text-red-700 flex items-center justify-center text-xs font-bold">1</span>
                                <span class="text-white text-xs font-bold uppercase tracking-wider">Identitas</span>
                            </div>
                            <div class="w-8 h-px bg-white/30"></div>
                            <div class="flex items-center gap-2" :class="{'opacity-100': formStep === 2, 'opacity-50': formStep !== 2}">
                                <span class="w-6 h-6 rounded-full bg-white text-red-700 flex items-center justify-center text-xs font-bold">2</span>
                                <span class="text-white text-xs font-bold uppercase tracking-wider">Rincian</span>
                            </div>
                        </div>
                    </div>

                    {{-- Scrollable Content Area --}}
                    <div class="flex-1 overflow-y-auto overflow-x-hidden p-8 bg-gray-50/50">

                        {{-- STEP 1 --}}
                        <div x-show="formStep === 1" x-transition:enter="transition ease-[cubic-bezier(0.34,1.56,0.64,1)] duration-500" x-transition:enter-start="opacity-0 translate-y-10 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                            <div class="max-w-xl mx-auto py-12 space-y-12">
                                <div class="text-center space-y-4">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-50 rounded-3xl text-red-600 mb-2 shadow-sm transform transition hover:scale-110 hover:rotate-3 duration-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-4xl font-extrabold text-gray-800 tracking-tight">Identitas Berkas</h3>
                                    <p class="text-gray-500 text-lg">Mulai dengan mengisi identitas dasar dokumen.</p>
                                </div>

                                <div class="space-y-8 bg-white p-8 rounded-[2rem] shadow-xl border border-gray-100/50">
                                    {{-- No Berkas (Readonly) --}}
                                    <div class="group relative">
                                        <label class="block font-bold text-gray-700 mb-3 pl-2 text-sm uppercase tracking-wide">No Berkas</label>
                                        <div class="relative transition-all duration-300 transform group-hover:-translate-y-1">
                                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                                <span class="text-red-400 font-black text-xl">#</span>
                                            </div>
                                            <input type="text" value="{{ $nextNumber ?? 'AUTO' }}" disabled
                                                class="w-full pl-12 pr-6 py-5 border-2 border-gray-100 rounded-2xl bg-gray-50/50 text-gray-800 font-bold text-lg shadow-sm cursor-not-allowed">
                                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center">
                                                 <span class="px-3 py-1 bg-gray-200 text-gray-500 text-xs font-bold rounded-full">{{ isset($arsip) ? 'EXISTING' : 'AUTO' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Nama Berkas --}}
                                    <div class="group relative">
                                        <label class="block font-bold text-gray-700 mb-3 pl-2 text-sm uppercase tracking-wide group-focus-within:text-red-600 transition-colors">Nama Berkas</label>
                                        <div class="relative transition-all duration-300 transform group-focus-within:-translate-y-1 group-hover:-translate-y-1">
                                             <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                                <svg class="w-6 h-6 text-gray-300 group-focus-within:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </div>
                                            <input type="text" name="nama_berkas" x-model="namaBerkas" placeholder="Contoh: Laporan Keuangan 2024"
                                                class="w-full pl-14 pr-6 py-5 border-2 border-gray-100 rounded-2xl bg-white text-gray-800 font-bold text-lg shadow-sm focus:border-red-500 focus:ring-[6px] focus:ring-red-100 outline-none transition-all placeholder:font-normal placeholder:text-gray-300">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="button" @click="validateStep1()" class="group relative w-full overflow-hidden rounded-2xl bg-gradient-to-r from-red-800 to-red-600 p-0.5 shadow-xl transition-all duration-300 hover:shadow-red-900/40 hover:-translate-y-1 active:scale-95 active:translate-y-0">
                                        <div class="relative flex items-center justify-center gap-3 bg-transparent px-8 py-5 font-bold text-white text-xl uppercase tracking-widest transition-all group-hover:bg-opacity-0">
                                            Selanjutnya
                                            <svg class="h-6 w-6 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        </div>
                                    </button>
                                </div>
                            </div>

                        </div>

                    {{-- STEP 2 --}}
                    <div x-show="formStep === 2" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-[20px]" x-transition:enter-end="opacity-100 translate-x-0">

                        <div class="space-y-8">

                            {{-- SECTION 1: CLASSIFICATION & UNIT --}}
                            <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    {{-- Unit Pengolah --}}
                                    <div class="group">
                                        <label class="block font-bold text-gray-700 mb-2 transition group-focus-within:text-red-700 text-sm uppercase tracking-wide">Unit Pengolah</label>
                                        <select :name="isEdit ? 'isi_berkas[0][unit_pengolah]' : 'unit_pengolah'" x-model="unitPengolah" class="w-full p-4 border-2 border-transparent bg-white rounded-2xl focus:border-red-500 focus:ring-4 focus:ring-red-50 outline-none transition shadow-sm font-bold text-gray-800 appearance-none cursor-pointer">
                                            <option value="" disabled selected>Pilih Unit Asal</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->nama_unit }}">{{ $unit->nama_unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Hidden Input for Classification (Must be in parent scope) --}}
                                    <template x-if="isEdit">
                                        <input type="hidden" name="isi_berkas[0][klasifikasi_id]" :value="klasifikasiId">
                                    </template>

                                    {{-- Kode Klasifikasi --}}
<div x-data="classificationDropdown({{ json_encode($initialData[0] ?? null) }})" class="relative group" @reset-selection.window="reset()" @set-classification.window="setFromParent($event.detail)">
                                        <label class="block font-bold text-gray-700 mb-2 text-sm uppercase tracking-wide">Kode Klasifikasi</label>
                                        {{-- Bind validation input to parent state
                                        <input type="hidden" name="klasifikasi_id" x-model="klasifikasiId"> --}}

                                        {{-- Trigger --}}
                                        <div @click="toggle()" class="w-full p-4 border-2 border-transparent bg-white rounded-2xl focus:ring-4 ring-offset-0 focus:ring-red-100 ring-red-500 cursor-pointer flex justify-between items-center shadow-sm hover:bg-gray-50 transition-colors">
                                            <span x-text="displayText" :class="{'text-gray-400': !selectedItem, 'text-gray-800 font-bold': selectedItem}"></span>
                                            <svg class="w-5 h-5 text-gray-400 transition" :class="{'rotate-180 text-red-500': open}" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>

                                        {{-- Dropdown --}}
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute z-50 w-full mt-2 bg-white border border-red-100 rounded-2xl shadow-2xl max-h-80 overflow-y-auto"
                                            style="display: none;">

                                            {{-- Header --}}
                                            <div class="px-5 py-3 bg-red-50/50 border-b border-red-100 flex items-center gap-3 sticky top-0 backdrop-blur-sm z-10">
                                                <template x-if="step > 1">
                                                    <button type="button" @click.stop="goBack()" class="p-1 hover:bg-red-100 rounded-full text-red-600 transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                                    </button>
                                                </template>
                                                <span class="text-xs font-bold text-red-800 tracking-wider uppercase"
                                                    x-text="step === 1 ? 'Pilih Pokok Masalah' : (step === 2 ? 'Pilih Sub Masalah' : 'Pilih Jenis Arsip')">
                                                </span>
                                            </div>

                                            <ul x-show="!loading" class="py-2">
                                                <template x-for="option in options" :key="option.code">
                                                    <li @click="selectOption(option)"
                                                        class="px-5 py-3 hover:bg-red-50 cursor-pointer text-sm text-gray-700 flex justify-between items-center group transition">
                                                        <span x-text="option.label" class="group-hover:text-red-700 font-medium"></span>
                                                        <svg class="w-4 h-4 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                    </li>
                                                </template>
                                                <template x-if="options.length === 0 && !loading">
                                                    <li class="px-5 py-4 text-gray-400 italic text-center">Data tidak ditemukan</li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Metadata Grid --}}
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-400 uppercase">Akses</span>
                                        <div class="text-right">
                                            <span x-text="newHakAkses || '-'" class="block text-sm font-bold text-red-600 truncate"></span>
                                            {{-- <input type="hidden" name="hak_akses" required> --}}
                                        </div>
                                    </div>
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-400 uppercase">Masa Simpan</span>
                                        <span x-text="newMasaSimpan || '-'" class="block text-sm font-bold text-gray-700 truncate"></span>
                                    </div>
                                    <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-400 uppercase">Tindakan</span>
                                        <span x-text="newTindakan || '-'" class="block text-sm font-bold text-gray-700 truncate"></span>
                                        <template x-if="isEdit">
                                            <div>
                                                <input type="hidden" name="isi_berkas[0][hak_akses]" :value="newHakAkses">
                                                <input type="hidden" name="isi_berkas[0][masa_simpan]" :value="newMasaSimpan">
                                                <input type="hidden" name="isi_berkas[0][tindakan_akhir]" :value="newTindakan">
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 2: INPUT FORM --}}
                            <div class="bg-gradient-to-br from-red-50 to-white p-6 rounded-3xl border border-red-100 shadow-sm space-y-4">
                                <h3 class="text-sm font-black text-red-900 uppercase tracking-wide flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs">02</span>
                                    Input Isi Berkas
                                </h3>

                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                    <div class="md:col-span-12 space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Deskripsi Berkas</label>
                                        <input type="text" x-ref="uraian" x-model="newIsi" :name="isEdit ? 'isi_berkas[0][isi]' : ''" @keydown.enter.prevent="addIsi()" placeholder="Misal: Kwitansi Pembelian ATK..."
                                        class="w-full p-3 border-2 border-transparent bg-white rounded-xl focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition shadow-sm font-medium">
                                    </div>

                                    <div class="md:col-span-3 space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Jenis</label>
                                        <select x-model="newMedia" :name="isEdit ? 'isi_berkas[0][jenis_media]' : ''" @keydown.enter.prevent="addIsi()" class="w-full p-3 border-2 border-transparent bg-white rounded-xl focus:border-red-500 outline-none transition shadow-sm text-sm">
                                            <option value="" disabled selected>Pilih...</option>
                                            <option value="Kertas">Kertas</option>
                                            <option value="Foto">Foto</option>
                                            <option value="Kartografi">Kartografi</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2 space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Box</label>
                                        <input type="text" x-model="newNoBox" :name="isEdit ? 'isi_berkas[0][no_box]' : ''" @keydown.enter.prevent="addIsi()" placeholder="Box 1"
                                            class="w-full p-3 border-2 border-transparent bg-white rounded-xl focus:border-red-500 outline-none transition shadow-sm text-sm text-center font-bold">
                                    </div>

                                    <div class="md:col-span-2 space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tahun</label>
                                        <input type="number" x-model="newTahun" :name="isEdit ? 'isi_berkas[0][tahun]' : ''" @keydown.enter.prevent="addIsi()" placeholder="YYYY"
                                            class="w-full p-3 border-2 border-transparent bg-white rounded-xl focus:border-red-500 outline-none transition shadow-sm text-sm text-center">
                                    </div>
                                    <div class="md:col-span-3 space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Tanggal</label>
                                        <input type="date" x-model="newTanggal" :name="isEdit ? 'isi_berkas[0][tanggal]' : ''" @keydown.enter.prevent="addIsi()"
                                            class="w-full p-3 border-2 border-transparent bg-white rounded-xl focus:border-red-500 outline-none transition shadow-sm text-sm text-center text-gray-600">
                                    </div>

                                    <div class="md:col-span-2 space-y-1">
                                        <label class="text-xs font-bold text-gray-500 uppercase ml-1">Jml</label>
                                        <input type="number" x-model="newJumlah" :name="isEdit ? 'isi_berkas[0][jumlah]' : ''" placeholder="1" min="1"
                                            class="w-full p-3 border-2 border-transparent bg-white rounded-xl focus:border-red-500 outline-none transition shadow-sm text-sm text-center font-bold">
                                    </div>
                                </div>

                                <div class="pt-2" x-show="!isEdit">
                                    <button type="button" @click="addIsi()"
                                        class="w-full bg-gradient-to-r from-red-600 to-red-800 text-white p-3 rounded-xl font-bold shadow-lg shadow-red-200 hover:shadow-red-300 hover:scale-[1.01] active:scale-95 transition flex justify-center items-center gap-2 group-btn">
                                        <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        TAMBAH KE DAFTAR
                                    </button>
                                </div>
                            </div>

                            {{-- SECTION 3: TABLE PREVIEW --}}
                            <div class="w-full" x-show="!isEdit">
                                <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden flex flex-col">
                                    <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                                        <div>
                                            <h3 class="text-lg font-black text-gray-800 text-transparent bg-clip-text bg-gradient-to-r from-red-900 to-red-600">Preview Berkas</h3>
                                            <p class="text-xs text-gray-400 font-medium">Pastikan data sudah benar sebelum disimpan.</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-3xl font-black text-gray-800" x-text="isiBerkas.length">0</span>
                                            <span class="text-xs text-gray-400 font-bold uppercase block">Items</span>
                                        </div>
                                    </div>

                                    <!-- Mobile View (Cards) -->
                                    <div class="block md:hidden p-4 bg-gray-50/30 space-y-4 max-h-[500px] overflow-y-auto">
                                        <template x-for="(item, index) in isiBerkas" :key="index">
                                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 relative">
                                                <button type="button" @click="removeIsi(index)" class="absolute top-4 right-4 text-red-500 bg-red-50 p-1.5 rounded-lg"><i class="fas fa-trash-alt"></i></button>
                                                <button type="button" @click="editItem(index)" class="absolute top-4 right-12 text-blue-500 bg-blue-50 p-1.5 rounded-lg"><i class="fas fa-pen"></i></button>

                                                <div class="mb-2"><span class="bg-red-100 text-red-800 text-[10px] font-bold px-2 py-0.5 rounded" x-text="item.kode_klasifikasi"></span></div>
                                                <div class="font-bold text-gray-800 text-sm mb-1 truncate pr-16" x-text="namaBerkas"></div>
                                                <div class="text-xs text-gray-500 mb-3 line-clamp-2" x-text="item.isi"></div>
                                                <div class="grid grid-cols-2 gap-2 text-[10px] bg-gray-50 p-2 rounded-lg mb-2">
                                                    <div><span class="text-gray-400 uppercase font-bold block">Tahun / Tgl</span><span class="font-bold text-gray-700" x-text="item.tahun + ' (' + item.tanggal + ')'"></span></div>
                                                    <div><span class="text-gray-400 uppercase font-bold block">Box & Jml</span><span class="font-bold text-gray-700" x-text="'Box '+item.no_box+' / '+item.jumlah+' Jml'"></span></div>
                                                    <div><span class="text-gray-400 uppercase font-bold block">Akses</span><span class="font-bold text-red-600" x-text="item.hak_akses"></span></div>
                                                    <div><span class="text-gray-400 uppercase font-bold block">Unit</span><span class="font-bold text-gray-700 truncate block" x-text="item.unit_pengolah"></span></div>
                                                </div>
                                            </div>
                                        </template>
                                        <div x-show="isiBerkas.length === 0" class="text-center py-10 text-gray-400 italic text-sm border-2 border-dashed border-gray-200 rounded-xl">Belum ada item ditambahkan.</div>
                                    </div>

                                    <!-- Desktop View (Table) -->
                                    <div class="hidden md:block overflow-x-auto custom-scrollbar max-h-[500px]">
                                        <table class="w-full text-xs text-left relative table-fixed">
                                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-[10px] tracking-wider sticky top-0 z-10 shadow-sm border-b border-gray-200">
                                                <tr>
                                                    <th class="px-4 py-3 w-1/4">Klasifikasi & Nama</th>
                                                    <th class="px-4 py-3 w-1/4">Isi Berkas</th>
                                                    <th class="px-4 py-3 w-32 text-center">Fisik (Thn/Tgl/Box)</th>
                                                    <th class="px-4 py-3 w-32 text-center">Status (Akses/Tndkn)</th>
                                                    <th class="px-4 py-3 w-32 text-center">Unit & Media</th>
                                                    <th class="px-4 py-3 w-20 text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50">
                                                <template x-for="(item, index) in isiBerkas" :key="index">
                                                    <tr x-transition:enter="transition ease-out duration-300" class="group hover:bg-red-50/30 transition-colors">
                                                        <td class="px-4 py-3">
                                                            <div class="mb-1"><span class="bg-red-100 text-red-700 px-1.5 py-0.5 rounded font-bold text-[9px] border border-red-200" x-text="item.kode_klasifikasi"></span></div>
                                                            <div class="font-bold text-gray-800 truncate" x-text="namaBerkas" :title="namaBerkas"></div>
                                                        </td>
                                                        <td class="px-4 py-3 text-gray-600 line-clamp-2" x-text="item.isi" :title="item.isi"></td>
                                                        <td class="px-4 py-3 text-center">
                                                            <div class="font-bold text-gray-800" x-text="item.tahun || '-'"></div>
                                                            <div class="text-[10px] text-gray-500 my-0.5" x-text="item.tanggal || '-'"></div>
                                                            <div class="text-[10px] bg-gray-100 px-2 rounded inline-block font-bold">Box: <span class="text-red-600 font-mono" x-text="item.no_box"></span> | <span x-text="item.jumlah"></span></div>
                                                        </td>
                                                        <td class="px-4 py-3 text-center">
                                                            <div class="text-[9px] font-bold px-2 py-0.5 rounded-full border mb-1 truncate bg-gray-50 text-gray-600 border-gray-200" x-text="'Akses: '+item.hak_akses"></div>
                                                            <div class="text-[9px] font-bold px-2 py-0.5 rounded-full border truncate bg-gray-50 text-gray-600 border-gray-200" x-text="'Tndkn: '+item.tindakan_akhir"></div>
                                                        </td>
                                                        <td class="px-4 py-3 text-center">
                                                            <div class="font-bold text-gray-700 text-[10px] truncate uppercase mb-1" x-text="item.unit_pengolah" :title="item.unit_pengolah"></div>
                                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded border border-blue-200 text-[9px] font-bold uppercase" x-text="item.jenis_media"></span>
                                                        </td>
                                                        <td class="px-4 py-3 text-center">
                                                            <div class="flex justify-center gap-1.5">
                                                                <button type="button" @click="editItem(index)" class="p-1.5 text-amber-500 bg-white border border-gray-200 hover:bg-amber-50 rounded-lg shadow-sm"><i class="fas fa-pen"></i></button>
                                                                <button type="button" @click="removeIsi(index)" class="p-1.5 text-red-500 bg-white border border-gray-200 hover:bg-red-50 rounded-lg shadow-sm"><i class="fas fa-trash-alt"></i></button>
                                                            </div>

                                                            <template x-if="!isEdit">
                                                                <div>
                                                                    <input type="hidden" :name="`isi_berkas[${index}][isi]`" :value="item.isi">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][tahun]`" :value="item.tahun">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][tanggal]`" :value="item.tanggal">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][jumlah]`" :value="item.jumlah">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][no_box]`" :value="item.no_box">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][hak_akses]`" :value="item.hak_akses">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][jenis_media]`" :value="item.jenis_media">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][masa_simpan]`" :value="item.masa_simpan">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][tindakan_akhir]`" :value="item.tindakan_akhir">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][unit_pengolah]`" :value="item.unit_pengolah">
                                                                    <input type="hidden" :name="`isi_berkas[${index}][klasifikasi_id]`" :value="item.klasifikasi_id">
                                                                </div>
                                                            </template>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr x-show="isiBerkas.length === 0">
                                                    <td colspan="6" class="px-6 py-16 text-center">
                                                        <div class="flex flex-col items-center text-gray-300">
                                                            <i class="fas fa-folder-open text-4xl mb-3"></i>
                                                            <p class="text-sm font-medium">Belum ada data arsip.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Footer / Submit --}}
                            <div class="p-6 bg-white border-t border-gray-100 flex justify-between items-center gap-4 rounded-3xl shadow-lg border border-gray-100 mt-6">
                                <button type="button" @click="formStep = 1" class="text-gray-500 font-bold px-4 py-2 text-sm hover:text-red-800 transition">
                                    Kembali
                                </button>
                                <button type="submit"
                                    :disabled="!isEdit && isiBerkas.length === 0"
                                    :class="{'opacity-50 cursor-not-allowed': !isEdit && isiBerkas.length === 0}"
                                    class="flex-1 bg-gradient-to-r from-red-700 to-red-900 text-white py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                                    <span>{{ isset($arsip) ? 'SIMPAN PERUBAHAN' : 'SIMPAN SEMUA' }}</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</script>
<script>
</script>
<script>
    function arsipForm() {
        return {
            formStep: 1,
            isiBerkas: @json(old('isi_berkas', $initialData ?? [])),
            newIsi: '',
            newTahun: '',
            newTanggal: '',
            newJumlah: 1,
            newNoBox: '',
            newHakAkses: '',
            newMedia: '',
            newMasaSimpan: '',
            newTindakan: '',
            namaBerkas: @json(old('nama_berkas', $arsip->nama_berkas ?? '')),
            unitPengolah: '',
            kodeKlasifikasi: '',
            // Edit Mode Initialization
            isEdit: {{ isset($arsip) ? 'true' : 'false' }},
            initData: @json($initialData[0] ?? null),

            init() {
                if (this.isEdit && this.initData) {
                    this.formStep = 2; // Go directly to details
                    this.newIsi = this.initData.isi || '';
                    this.newTahun = this.initData.tahun || '';
                    this.newTanggal = this.initData.tanggal || '';
                    this.newJumlah = this.initData.jumlah || 1;
                    this.newNoBox = this.initData.no_box || '';
                    this.newHakAkses = this.initData.hak_akses || '';
                    this.newMedia = this.initData.jenis_media || '';
                    this.newMasaSimpan = this.initData.masa_simpan || '';
                    this.newTindakan = this.initData.tindakan_akhir || '';
                    this.unitPengolah = this.initData.unit_pengolah || '';
                    this.kodeKlasifikasi = this.initData.kode_klasifikasi || '';
                    this.klasifikasiId = this.initData.klasifikasi_id || '';

                    // Note: Classification dropdown now initializes itself via x-data param
                    // but we keep this just in case other listeners need it
                     this.$nextTick(() => {
                        this.$dispatch('set-classification', {
                            code: this.initData.kode_klasifikasi,
                            id: this.initData.klasifikasi_id,
                            label: this.initData.kode_klasifikasi,
                            hak_akses: this.initData.hak_akses,
                            masa_simpan: this.initData.masa_simpan,
                            tindakan: this.initData.tindakan_akhir
                        });
                    });
                }
            },

            addIsi() {
                if (this.newIsi.trim() !== '') {
                    this.isiBerkas.push({
                        isi: this.newIsi.trim(),
                        tahun: this.newTahun,
                        tanggal: this.newTanggal,
                        jumlah: this.newJumlah,
                        no_box: this.newNoBox,
                        hak_akses: this.newHakAkses,
                        jenis_media: this.newMedia || 'Kertas',
                        masa_simpan: this.newMasaSimpan,
                        tindakan_akhir: this.newTindakan,
                        unit_pengolah: this.unitPengolah,
                        kode_klasifikasi: this.kodeKlasifikasi,
                        klasifikasi_id: this.klasifikasiId
                    });

                    /* Reset Item Fields */
                    this.newIsi = '';
                    this.newTahun = '';
                    this.newTanggal = '';
                    this.newJumlah = 1;
                    this.newNoBox = '';
                    this.newMedia = '';

                    /* Reset Header Fields (Unit & Klasifikasi) */
                    this.unitPengolah = '';
                    this.kodeKlasifikasi = '';
                    this.klasifikasiId = '';
                    this.newHakAkses = '';
                    this.newMasaSimpan = '';
                    this.newTindakan = 'Musnah';
                    this.$dispatch('reset-selection');

                    /* Focus back */
                    this.$nextTick(() => {
                        const uInput = document.querySelector('[name=unit_pengolah]');
                        if(uInput) uInput.focus();
                    });
                }
            },
            editItem(index) {
                const item = this.isiBerkas[index];
                this.newIsi = item.isi;
                this.newTahun = item.tahun;
                this.newTanggal = item.tanggal;
                this.newJumlah = item.jumlah;
                this.newNoBox = item.no_box;
                this.newHakAkses = item.hak_akses;
                this.newMedia = item.jenis_media;
                this.newMasaSimpan = item.masa_simpan;
                this.newTindakan = item.tindakan_akhir;
                this.unitPengolah = item.unit_pengolah;
                this.kodeKlasifikasi = item.kode_klasifikasi;
                this.klasifikasiId = item.klasifikasi_id;

                this.$dispatch('set-classification', {
                    code: item.kode_klasifikasi,
                    id: item.klasifikasi_id,
                    label: item.kode_klasifikasi,
                    hak_akses: item.hak_akses,
                    masa_simpan: item.masa_simpan,
                    tindakan: item.tindakan_akhir
                });
                this.isiBerkas.splice(index, 1);
            },
            removeIsi(index) {
                this.isiBerkas.splice(index, 1);
            },
            validateStep1() {
                const nama = document.querySelector('[name=nama_berkas]').value;
                if (!nama) {
                    alert('Mohon lengkapi Nama Berkas terlebih dahulu.');
                    return;
                }
                this.formStep = 2;
            },
            updateDefaults(data) {
                this.newHakAkses = data.hak_akses || '';
                this.newMasaSimpan = data.masa_simpan || '';
                this.newTindakan = data.tindakan || '';
                this.kodeKlasifikasi = data.code || '';
                this.klasifikasiId = data.id || '';
            }
        }
    }

    function classificationDropdown(initData) {
        return {
            open: false,
            step: 1,
            breadcrumbs: [],
            options: [],
            loading: false,
            selectedItem: null,
            displayText: 'Pilih Kode Klasifikasi',

            init() {
                this.fetchOptions(1);

                if (initData && initData.kode_klasifikasi) {
                    this.selectedItem = {
                        code: initData.kode_klasifikasi,
                        label: initData.kode_klasifikasi, // We usually don't have full label in initData, so use code
                        id: initData.klasifikasi_id
                    };
                    this.displayText = initData.kode_klasifikasi;
                }
            },

            toggle() {
                this.open = !this.open;
                if(this.open) {
                   // Ensure we start from scratch or current step?
                   // If they have selected something, maybe easier to just show root options to allow change
                   if (this.options.length === 0) this.fetchOptions(1);

                   // If we want to allow re-selection, reset step to 1 on open
                   this.step = 1;
                   this.breadcrumbs = [];
                   this.fetchOptions(1);
                }
            },

            fetchOptions(level, parent = null) {
                this.loading = true;
                let url = `/api/klasifikasi-options?level=${level}&parent=${parent}`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        this.options = data;
                        this.loading = false;
                    });
            },

            selectOption(opt) {
                if (this.step === 1) {
                    this.breadcrumbs.push({ level: 1, label: opt.label, value: opt.code });
                    this.step = 2;
                    this.fetchOptions(2, opt.code);
                } else if (this.step === 2) {
                    this.breadcrumbs.push({ level: 2, label: opt.label, value: opt.code });
                    this.step = 3;
                    this.fetchOptions(3, opt.code);
                } else if (this.step === 3) {
                    this.selectedItem = opt;
                    this.displayText = opt.label;

                    this.$dispatch('classification-selected', {
                        hak_akses: opt.hak_akses,
                        masa_simpan: opt.masa_simpan,
                        tindakan: opt.tindakan_akhir,
                        code: opt.code,
                        id: opt.id
                    });

                    this.open = false;
                }
            },

            goBack() {
                if (this.step > 1) {
                    this.step--;
                    this.breadcrumbs.pop();
                    const parent = this.breadcrumbs.length > 0 ? this.breadcrumbs[this.breadcrumbs.length - 1].value : null;
                    this.fetchOptions(this.step, parent);
                }
            },

            reset() {
                this.step = 1;
                this.breadcrumbs = [];
                this.selectedItem = null;
                this.displayText = 'Pilih Kode Klasifikasi';
                this.fetchOptions(1);
                this.open = false;
            },

            setFromParent(detail) {
                this.selectedItem = { code: detail.code, label: detail.label, id: detail.id };
                this.displayText = detail.code;
                this.open = false;
                this.step = 1;
                this.breadcrumbs = [];
            }
        }
    }
</script>
</x-layout>
