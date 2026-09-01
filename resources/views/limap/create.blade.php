<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative">
        <div class="max-w-7xl mx-auto relative z-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Tambah Papan 5P</h1>
            <p class="text-red-50 text-base font-light">Unggah gambar dokumentasi 5P untuk area yang ditentukan.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 -mt-20 relative z-20 mb-10">
        <form action="{{ route('limap.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @csrf
            <div class="p-6 md:p-8 space-y-8">
                <div>
                    <label class="block text-sm font-bold text-[#e92027] mb-2"><i class="fas fa-user-shield"></i> Nama PIC Area</label>
                    <input type="text" name="pic" required placeholder="Contoh: Budi Santoso - Area Gudang" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white outline-none focus:border-[#e92027] font-bold shadow-sm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-6">
                    @php
                        $fileInputs = [
                            'kesepakatan' => ['icon' => 'fa-handshake', 'label' => 'Kesepakatan'],
                            'visi_misi' => ['icon' => 'fa-bullseye', 'label' => 'Visi & Misi'],
                            'pembagian_area' => ['icon' => 'fa-map-marked-alt', 'label' => 'Pembagian Area'],
                            'struktur' => ['icon' => 'fa-sitemap', 'label' => 'Struktur Organisasi'],
                            'jadwal_kegiatan' => ['icon' => 'fa-calendar-alt', 'label' => 'Jadwal Kegiatan'],
                        ];
                    @endphp

                    @foreach($fileInputs as $key => $info)
                    {{-- ALPINE JS IMAGE UPLOADER COMPONENT --}}
                    <div class="bg-gray-50 p-5 rounded-2xl border border-gray-200"
                         x-data="{
                            inputs: [],
                            addInput() {
                                let id = Date.now().toString() + Math.floor(Math.random() * 1000).toString();
                                this.inputs.push({ id: id, preview: null });
                                this.$nextTick(() => {
                                    document.getElementById('input_{{ $key }}_' + id).click();
                                });
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
                            }
                         }">

                        <label class="block text-sm font-bold text-gray-800 mb-4"><i class="fas {{ $info['icon'] }} text-[#e92027] mr-2"></i> Gambar {{ $info['label'] }}</label>

                        <!-- Area Tampil Preview -->
                        <div class="flex flex-wrap gap-4 mb-4">
                            <template x-for="input in inputs" :key="input.id">
                                <div x-show="input.preview" class="relative rounded-xl overflow-hidden border border-gray-300 w-28 h-28 group shadow-sm bg-white">
                                    <img :src="input.preview" class="w-full h-full object-cover">
                                    <!-- Tombol Batal Upload -->
                                    <button type="button" @click="removeInput(input.id)" class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer text-white">
                                        <i class="fas fa-times-circle text-2xl mb-1 text-red-500"></i>
                                        <span class="text-[10px] font-bold">Batal Upload</span>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <!-- Kumpulan Input Hidden -->
                        <template x-for="input in inputs" :key="input.id">
                            <input type="file" :id="'input_{{ $key }}_' + input.id" name="{{ $key }}[]" accept=".jpg,.jpeg,.png" class="hidden" @change="handleFile($event, input.id)">
                        </template>

                        <!-- Tombol Tambah Gambar -->
                        <button type="button" @click="addInput()" class="text-xs font-bold text-[#e92027] bg-red-50 hover:bg-red-100 border border-red-200 px-4 py-2.5 rounded-xl transition shadow-sm flex items-center gap-2">
                            <i class="fas fa-plus-circle"></i> Tambah Gambar
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('limap.index') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold shadow-sm hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold hover:bg-[#a0131a] shadow-lg transform hover:-translate-y-0.5 transition">Simpan Data</button>
            </div>
        </form>
    </div>
</x-layout>
