<x-layout>
    <div x-data="{
        @php
            $gambarLama = json_decode($media->gambar, true);
            if(!is_array($gambarLama)) $gambarLama = [$media->gambar];
        @endphp

        oldImages: {{ Js::from($gambarLama) }},
        newInputs: [],
        showImageErrorModal: false,
        imageErrorMessage: '',

        get totalImages() { return this.oldImages.length + this.newInputs.length; },
        get canAddMore() { return this.totalImages < 5; },

        removeOldImage(index) {
            this.oldImages.splice(index, 1);
        },
        addNewInput() {
            if (this.canAddMore) {
                this.newInputs.push({ id: Date.now(), preview: null });
            } else {
                alert('Maksimal hanya 5 foto!');
            }
        },
        removeNewInput(index) {
            this.newInputs.splice(index, 1);
        },
        validateImage(e, index) {
            const file = e.target.files[0];
            if (!file) return;

            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type) || file.size > 5242880) {
                this.imageErrorMessage = 'File tidak valid. Pastikan format JPG/PNG dan ukuran maksimal 5 MB.';
                this.showImageErrorModal = true;
                e.target.value = '';
                this.newInputs[index].preview = null;
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => { this.newInputs[index].preview = e.target.result; };
            reader.readAsDataURL(file);
        }
    }">

        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[3rem] mb-8 relative">
            <h1 class="text-3xl font-extrabold text-white text-center">Edit Berita / Media</h1>
        </div>

        <div class="max-w-4xl mx-auto px-4 -mt-20 relative z-20 mb-12">
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm font-bold text-red-700">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <form action="{{ route('manajemen-media.update', $media->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-xl border border-gray-100">
                @csrf @method('PUT')

                <div class="p-6 md:p-8 grid gap-6">
                    <div>
                        <label class="block font-bold mb-2">Judul</label>
                        <input type="text" name="judul" value="{{ old('judul', $media->judul) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027]/20">
                    </div>
                    <div>
                        <label class="block font-bold mb-2">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', $media->tanggal) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>

                    <!-- Area Manajemen Foto -->
                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                        <label class="block font-bold mb-4">Galeri Foto (Max 5 Foto)</label>

                        <!-- List Foto Lama (Dipertahankan) -->
                        <div class="space-y-3 mb-4">
                            <template x-for="(img, index) in oldImages" :key="'old-'+index">
                                <div class="flex items-center gap-4 bg-white p-3 rounded-xl border shadow-sm">
                                    <input type="hidden" name="keep_gambar[]" :value="img">
                                    <img :src="'/' + img" class="w-16 h-16 object-cover rounded-lg border">
                                    <div class="flex-grow"><span class="text-sm text-gray-500 font-medium">Foto Terpasang Saat Ini</span></div>
                                    <button type="button" @click="removeOldImage(index)" class="text-red-500 p-2 hover:bg-red-50 rounded-lg"><i class="fas fa-trash"></i></button>
                                </div>
                            </template>
                        </div>

                        <!-- List Foto Baru (Di Tambah) -->
                        <div class="space-y-3">
                            <template x-for="(input, index) in newInputs" :key="input.id">
                                <div class="flex items-center gap-4 bg-white p-3 rounded-xl border shadow-sm">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                                        <template x-if="input.preview"><img :src="input.preview" class="w-full h-full object-cover"></template>
                                        <template x-if="!input.preview"><i class="fas fa-image text-gray-300"></i></template>
                                    </div>
                                    <input type="file" name="gambar[]" accept="image/jpeg, image/png" required @change="validateImage($event, index)" class="flex-grow text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:bg-red-50 file:text-[#e92027]">
                                    <button type="button" @click="removeNewInput(index)" class="text-red-500 p-2 hover:bg-red-50 rounded-lg"><i class="fas fa-times"></i></button>
                                </div>
                            </template>
                        </div>

                        <!-- Tombol Tambah -->
                        <button x-show="canAddMore" type="button" @click="addNewInput" class="mt-4 w-full py-3 border-2 border-dashed border-[#e92027] text-[#e92027] font-bold rounded-xl hover:bg-red-50 flex items-center justify-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Foto
                        </button>
                    </div>

                    <div>
                        <label class="block font-bold mb-2">Deskripsi</label>
                        <textarea name="deskripsi" rows="5" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">{{ old('deskripsi', $media->deskripsi) }}</textarea>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-5 flex justify-end gap-4 rounded-b-3xl">
                    <a href="{{ route('manajemen-media.index') }}" class="px-6 py-3 bg-white border border-gray-200 font-bold rounded-xl">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-[#e92027] text-white font-bold rounded-xl shadow-md hover:bg-[#c41820]">Simpan Perubahan</button>
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
