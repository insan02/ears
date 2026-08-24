<x-layout>
    {{-- Inisialisasi state isSubmitting --}}
    <div x-data="{ isSubmitting: false }" class="relative">
        
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h2 class="text-2xl font-bold text-[#8B1A1A] mb-8 border-b pb-4">Edit Profile</h2>

            @if (session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 text-[#c41820] p-4 rounded-lg mb-6 border border-red-200">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tambahkan @submit="isSubmitting = true" pada form --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" @submit="isSubmitting = true">
                @csrf
                @method('PUT')

                {{-- 1. FOTO PROFIL --}}
                <div class="flex items-center gap-8">
                    <div class="shrink-0 relative group">
                        @if($user->photo)
                            <img id="preview-photo" src="{{ asset($user->photo) }}" alt="Foto Profil"
                                class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-md">
                        @else
                            <div class="w-32 h-32 bg-red-100 rounded-full flex items-center justify-center text-[#e92027] border-4 border-gray-100 shadow-md">
                                <span class="text-4xl font-bold">{{ substr($user->nama, 0, 1) }}</span>
                            </div>
                            <img id="preview-photo" src="" class="hidden w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-md absolute top-0 left-0">
                        @endif

                        <label for="photo" class="absolute bottom-0 right-0 bg-white border border-gray-200 p-2 rounded-full shadow-lg cursor-pointer hover:bg-gray-50 transition">
                            <i class="fas fa-camera text-gray-600"></i>
                            <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </label>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Foto Profil</h3>
                        <p class="text-gray-500 text-sm">Format JPG, JPEG, PNG. Maksimal 2MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- 2. INFORMASI DASAR --}}
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e92027] focus:border-[#e92027] outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e92027] focus:border-[#e92027] outline-none transition">
                        </div>
                    </div>

                    {{-- 3. GANTI PASSWORD --}}
                    <div class="space-y-4 bg-gray-50 p-6 rounded-xl border border-gray-100">
                        <h4 class="font-bold text-gray-600 mb-2">Ganti Password (Opsional)</h4>
                        <div>
                            <input type="password" name="password" placeholder="Password Baru"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e92027] focus:border-[#e92027] outline-none transition">
                        </div>
                        <div>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e92027] focus:border-[#e92027] outline-none transition">
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SIMPAN --}}
                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" :disabled="isSubmitting"
                        class="bg-[#8B1A1A] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#6B1414] transition shadow-lg transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!isSubmitting">Simpan Perubahan</span>
                        <span x-show="isSubmitting">Memproses...</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ANIMASI LOADING OVERLAY --}}
        <div x-show="isSubmitting" style="display: none;" 
            class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
            <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center gap-4 animate-bounce">
                <!-- Spinner Tailwind -->
                <svg class="animate-spin h-10 w-10 text-[#e92027]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-800 font-bold text-sm">Menyimpan data...</p>
            </div>
        </div>

    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var preview = document.getElementById('preview-photo');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layout>