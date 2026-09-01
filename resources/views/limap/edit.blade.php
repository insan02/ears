<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative">
        <div class="max-w-7xl mx-auto relative z-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Edit Data 5P</h1>
            <p class="text-red-50 text-base font-light">Perbarui informasi gambar 5P.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 -mt-20 relative z-20 mb-10">
        <!-- Hapus onsubmit di sini, kita panggil lewat onclick di tombol bawah -->
        <form action="{{ route('limap.update', $data->id) }}" method="POST" enctype="multipart/form-data" id="formEdit5P" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @csrf @method('PUT')
            <div class="p-6 md:p-8 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-[#e92027] mb-2"><i class="fas fa-user-shield"></i> Nama PIC Area</label>
                    <input type="text" name="pic" value="{{ $data->pic }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white outline-none focus:border-[#e92027] font-bold shadow-sm">
                </div>

                <div class="grid grid-cols-1 gap-8 border-t border-gray-100 pt-6">
                    @php
                        $fileInputs = [
                            'kesepakatan' => ['icon' => 'fa-handshake', 'label' => 'Kesepakatan'],
                            'visi_misi' => ['icon' => 'fa-bullseye', 'label' => 'Visi & Misi'],
                            'pembagian_area' => ['icon' => 'fa-map-marked-alt', 'label' => 'Pembagian Area'],
                            'struktur' => ['icon' => 'fa-sitemap', 'label' => 'Struktur'],
                            'jadwal_kegiatan' => ['icon' => 'fa-calendar-alt', 'label' => 'Jadwal Kegiatan'],
                        ];
                    @endphp

                    @foreach($fileInputs as $key => $info)
                    {{-- ALPINE JS IMAGE UPLOADER COMPONENT --}}
                    <div class="bg-gray-50 p-5 md:p-6 rounded-2xl border border-gray-200"
                         x-data="{
                            inputs: [],
                            deletedExisting: [], // Array menampung gambar lama yang dihapus

                            addInput() {
                                let id = Date.now().toString() + Math.floor(Math.random() * 1000).toString();
                                this.inputs.push({ id: id, preview: null });
                                this.$nextTick(() => { document.getElementById('input_{{ $key }}_' + id).click(); });
                            },
                            handleFile(event, id) {
                                let file = event.target.files[0];
                                if (file) {
                                    let reader = new FileReader();
                                    reader.onload = (e) => {
                                        let item = this.inputs.find(i => i.id === id);
                                        if (item) item.preview = e.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                } else {
                                    this.inputs = this.inputs.filter(i => i.id !== id);
                                }
                            },
                            removeInput(id) {
                                this.inputs = this.inputs.filter(i => i.id !== id);
                            },
                            // Hapus instan tanpa modal konfirmasi (hanya disimpan ke array hapus)
                            deleteExisting(imgPath) {
                                if (!this.deletedExisting.includes(imgPath)) {
                                    this.deletedExisting.push(imgPath);
                                }
                            }
                         }">

                        <label class="block text-sm font-bold text-gray-800 mb-4"><i class="fas {{ $info['icon'] }} text-[#e92027] mr-2"></i> Gambar {{ $info['label'] }}</label>

                        <div class="flex flex-wrap gap-4 mb-4">
                            <!-- GAMBAR LAMA (DARI DATABASE) -->
                            @if(!empty($data->$key))
                                @foreach($data->$key as $imgPath)
                                    <!-- Sembunyikan otomatis jika tombol tong sampah diklik -->
                                    <div x-show="!deletedExisting.includes('{{ $imgPath }}')" class="relative rounded-xl overflow-hidden border-2 border-green-500 w-28 h-28 group shadow-sm bg-white">
                                        <img src="{{ asset('storage/'.$imgPath) }}" class="w-full h-full object-cover">

                                        <!-- Tombol Tong Sampah (Langsung Hapus Tanpa Modal) -->
                                        <button type="button" @click="deleteExisting('{{ $imgPath }}')" class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer text-white">
                                            <i class="fas fa-trash-alt text-2xl mb-1 text-red-500"></i>
                                            <span class="text-white text-[10px] font-bold text-center px-1 mt-1">Hapus<br>Gambar</span>
                                        </button>

                                        <div class="absolute top-0 left-0 bg-green-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-br-lg">Tersimpan</div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Input Hidden untuk gambar yang ditandai hapus (dikirim ke Controller) -->
                            <template x-for="delImg in deletedExisting">
                                <input type="hidden" name="hapus_{{ $key }}[]" :value="delImg">
                            </template>

                            <!-- GAMBAR BARU (PREVIEW ALPINE) -->
                            <template x-for="input in inputs" :key="input.id">
                                <div x-show="input.preview" class="relative rounded-xl overflow-hidden border-2 border-amber-400 w-28 h-28 group shadow-sm bg-white">
                                    <img :src="input.preview" class="w-full h-full object-cover">
                                    <!-- Tombol Batal Upload Baru -->
                                    <button type="button" @click="removeInput(input.id)" class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer text-white">
                                        <i class="fas fa-times-circle text-2xl mb-1 text-red-500"></i>
                                        <span class="text-[10px] font-bold">Batal Upload</span>
                                    </button>
                                    <div class="absolute top-0 left-0 bg-amber-400 text-amber-900 text-[8px] font-bold px-2 py-0.5 rounded-br-lg">Baru</div>
                                </div>
                            </template>
                        </div>

                        @if(empty($data->$key))
                            <p x-show="inputs.length === 0" class="text-xs text-gray-400 italic mb-4">Belum ada gambar.</p>
                        @endif

                        <!-- Kumpulan Input File Hidden -->
                        <template x-for="input in inputs" :key="input.id">
                            <input type="file" :id="'input_{{ $key }}_' + input.id" name="{{ $key }}[]" accept=".jpg,.jpeg,.png" class="hidden" @change="handleFile($event, input.id)">
                        </template>

                        <!-- Tombol Tambah Gambar -->
                        <div class="pt-4 border-t border-gray-200 border-dashed">
                            <button type="button" @click="addInput()" class="text-xs font-bold text-[#e92027] bg-white border border-gray-300 hover:bg-gray-100 hover:border-gray-400 px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                                <i class="fas fa-plus"></i> Tambah Gambar Baru
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 3 TOMBOL AKSI UTAMA --}}
            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex flex-col md:flex-row justify-end items-center gap-3">

                <!-- 1. Tombol Batal (Kembali ke Daftar) -->
                <a href="{{ route('limap.index') }}" class="w-full md:w-auto text-center px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold shadow-sm hover:bg-gray-100 transition text-sm">
                    Batal
                </a>

                <!-- 2. Tombol Lihat Data Kaizen (Ke Halaman Detail) -->
                <a href="{{ route('limap.show', $data->id) }}" class="w-full md:w-auto text-center px-6 py-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-xl font-bold shadow-sm hover:bg-blue-100 transition text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-file-pdf"></i> Lihat Data Kaizen
                </a>

                <!-- 3. Tombol Simpan (Menggunakan tipe 'button' untuk mencegah submit otomatis) -->
                <button type="button" onclick="confirmSaveChanges()" class="w-full md:w-auto px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold hover:bg-[#a0131a] shadow-lg transform hover:-translate-y-0.5 transition text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>

            </div>
        </form>
    </div>

    <!-- Script SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmSaveChanges() {
            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Verifikasi kembali gambar yang ditambah/dihapus sebelum menyimpannya ke sistem.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e92027',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Cek Kembali',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit formulir secara manual hanya jika tombol 'Ya, Simpan' ditekan
                    document.getElementById('formEdit5P').submit();
                }
            });
        }
    </script>
</x-layout>
