<x-layout>
    <!-- Wrapper Alpine.js untuk keseluruhan halaman -->
    <div x-data="{
        showImageErrorModal: false,
        imageErrorMessage: '',
        validateImage(e) {
            const file = e.target.files[0];
            if (file) {
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    this.imageErrorMessage = 'Format file tidak didukung. Harap upload gambar dengan format JPG, JPEG, atau PNG.';
                    this.showImageErrorModal = true;
                    e.target.value = '';
                    return;
                }
                if (file.size > 5242880) {
                    this.imageErrorMessage = 'Ukuran gambar terlalu besar. Maksimal ukuran file yang diizinkan adalah 5 MB.';
                    this.showImageErrorModal = true;
                    e.target.value = '';
                    return;
                }
            }
        }
    }">

        {{-- Header Page --}}
        <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[2rem] md:rounded-b-[3rem] shadow-xl mb-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 relative overflow-hidden">
            <div class="relative z-10 max-w-4xl mx-auto text-center md:text-left">
                <h1 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide">Tambah Berita / Media</h1>
                <p class="text-red-100 text-sm md:text-base mt-2 opacity-90 font-light">Publikasikan konten informasi terbaru di Landing Page.</p>
            </div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        </div>

        {{-- Main Form Container --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-16 md:-mt-24 relative z-20 mb-12">

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0"><i class="fas fa-exclamation-circle text-red-700 mt-0.5"></i></div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Gagal Menyimpan Data!</h3>
                            <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('manajemen-media.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                @csrf

                <div class="p-6 md:p-8 space-y-6 md:space-y-8">
                    <div>
                        <h2 class="text-base md:text-lg font-bold text-[#e92027] border-b border-gray-100 pb-3 mb-6 flex items-center gap-3">
                            <i class="fas fa-newspaper text-[#e92027]"></i> Konten Berita
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                            <!-- Judul -->
                            <div class="md:col-span-2">
                                <label for="judul" class="block text-sm font-bold text-gray-800 mb-2">Judul Berita <span class="text-red-600">*</span></label>
                                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm"
                                    placeholder="Masukkan judul berita...">
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="block text-sm font-bold text-gray-800 mb-2">Tanggal Publikasi <span class="text-red-600">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm text-gray-700">
                            </div>

                            <!-- Gambar -->
                            <div>
                                <label for="gambar" class="block text-sm font-bold text-gray-800 mb-2">Upload Gambar <span class="text-red-600">*</span></label>
                                <!-- Menggunakan event bawaan Alpine: @change -->
                                <input type="file" name="gambar" id="gambar" accept="image/jpeg, image/png" required @change="validateImage($event)"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-red-50 file:text-[#c41820] hover:file:bg-red-100 cursor-pointer">
                                <p class="text-[11px] text-gray-500 mt-1.5"><i class="fas fa-info-circle"></i> Format: JPG, JPEG, PNG. Maksimal 5 MB.</p>
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-bold text-gray-800 mb-2">Deskripsi Lengkap <span class="text-red-600">*</span></label>
                                <textarea name="deskripsi" id="deskripsi" rows="6" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm resize-y"
                                    placeholder="Tulis deskripsi berita di sini...">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="bg-gray-50 px-6 py-5 md:px-8 md:py-6 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3 md:gap-4">
                    <a href="{{ route('manajemen-media.index') }}"
                        class="w-full md:w-auto text-center px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-100 transition">Batalkan</a>
                    <button type="submit"
                        class="w-full md:w-auto px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold shadow-md hover:bg-[#c41820] hover:shadow-lg transition transform hover:-translate-y-0.5">
                        Simpan Berita
                    </button>
                </div>
            </form>
        </div>

        {{-- Modal Peringatan Error Upload Alpine.js --}}
        <div x-show="showImageErrorModal" style="display: none;"
             class="fixed inset-0 z-[150] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">

            <div @click.away="showImageErrorModal = false"
                 class="bg-white rounded-3xl w-full max-w-sm p-6 md:p-8 text-center relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-2 bg-[#e92027]"></div>

                <div class="bg-red-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-[#e92027] shadow-sm animate-bounce">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-gray-800 mb-2">Upload Ditolak</h3>
                <p class="text-gray-500 mb-8 text-sm md:text-base leading-relaxed" x-text="imageErrorMessage"></p>

                <button @click="showImageErrorModal = false" type="button"
                    class="w-full py-3.5 bg-[#e92027] text-white rounded-xl text-sm font-bold hover:bg-[#c41820] shadow-lg transform hover:-translate-y-0.5 transition">
                    Mengerti
                </button>
            </div>
        </div>

    </div>
</x-layout>
