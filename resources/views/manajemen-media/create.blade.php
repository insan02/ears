<x-layout>
    <div x-data="{
        inputs: [{ id: Date.now(), preview: null }],
        showImageErrorModal: false,
        imageErrorMessage: '',

        get canAddMore() { return this.inputs.length < 5; },

        addNewInput() {
            if (this.canAddMore) {
                this.inputs.push({ id: Date.now(), preview: null });
            }
        },
        removeInput(index) {
            this.inputs.splice(index, 1);
        },
        validateImage(e, index) {
            const file = e.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type) || file.size > 5242880) {
                this.imageErrorMessage = 'File tidak valid. Pastikan format JPG/PNG dan ukuran maksimal 5 MB.';
                this.showImageErrorModal = true;
                e.target.value = '';
                this.inputs[index].preview = null;
                return;
            }

            // Buat Preview
            const reader = new FileReader();
            reader.onload = (e) => { this.inputs[index].preview = e.target.result; };
            reader.readAsDataURL(file);
        }
    }">

        {{-- Header Page --}}
        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[2rem] md:rounded-b-[3rem] shadow-xl mb-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 relative overflow-hidden">
            <div class="relative z-10 max-w-4xl mx-auto text-center md:text-left">
                <h1 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide">Tambah Berita / Media</h1>
                <p class="text-red-100 mt-2">Publikasikan konten informasi terbaru (Maksimal 5 Foto).</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-16 md:-mt-24 relative z-20 mb-12">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
                    <ul class="list-disc list-inside text-sm text-red-700 font-bold">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('manajemen-media.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                @csrf

                <div class="p-6 md:p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Judul -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Judul Berita <span class="text-red-600">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}" maxlength="50" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e92027]/20 outline-none">
                        </div>

                        <!-- Tanggal -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Publikasi <span class="text-red-600">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e92027]/20 outline-none">
                        </div>

                        <!-- Area Upload Gambar Dinamis -->
                        <div class="md:col-span-2 bg-gray-50 p-6 rounded-2xl border border-gray-200">
                            <label class="block text-sm font-bold text-gray-800 mb-4">Galeri Foto (Max 5 Foto) <span class="text-red-600">*</span></label>

                            <div class="space-y-4">
                                <template x-for="(input, index) in inputs" :key="input.id">
                                    <div class="flex flex-col sm:flex-row items-center gap-4 bg-white p-3 rounded-xl shadow-sm border border-gray-100">
                                        <!-- Kotak Preview -->
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 border border-gray-200 overflow-hidden flex items-center justify-center">
                                            <template x-if="input.preview">
                                                <img :src="input.preview" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!input.preview">
                                                <i class="fas fa-image text-gray-300 text-2xl"></i>
                                            </template>
                                        </div>

                                        <!-- Input File -->
                                        <div class="flex-grow w-full">
                                            <input type="file" name="gambar[]" accept="image/jpeg, image/png" required @change="validateImage($event, index)"
                                                class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-red-50 file:text-[#e92027] hover:file:bg-red-100 cursor-pointer">
                                        </div>

                                        <!-- Tombol Hapus Baris -->
                                        <template x-if="inputs.length > 1">
                                            <button type="button" @click="removeInput(index)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Foto">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- Tombol Tambah Baris -->
                            <button x-show="canAddMore" type="button" @click="addNewInput" class="mt-4 w-full py-3 border-2 border-dashed border-[#e92027] text-[#e92027] font-bold rounded-xl hover:bg-red-50 transition flex justify-center items-center gap-2">
                                <i class="fas fa-plus"></i> Tambah Foto Lain
                            </button>
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Deskripsi Lengkap <span class="text-red-600">*</span></label>
                            <textarea name="deskripsi" rows="6" maxlength="100" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e92027]/20 outline-none">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-end gap-4">
                    <a href="{{ route('manajemen-media.index') }}" class="px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-100">Batalkan</a>
                    <button type="submit" class="px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold shadow-md hover:bg-[#c41820]">Simpan Berita</button>
                </div>
            </form>
        </div>

        {{-- Modal Peringatan Error --}}
        <div x-show="showImageErrorModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div @click.away="showImageErrorModal = false" class="bg-white rounded-3xl p-6 text-center max-w-sm">
                <div class="text-[#e92027] mb-4 text-5xl"><i class="fas fa-exclamation-circle"></i></div>
                <h3 class="font-bold text-xl mb-2">Gagal</h3>
                <p class="text-gray-500 mb-6" x-text="imageErrorMessage"></p>
                <button @click="showImageErrorModal = false" type="button" class="w-full py-3 bg-[#e92027] text-white rounded-xl font-bold">Mengerti</button>
            </div>
        </div>
    </div>
</x-layout>
