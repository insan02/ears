<x-layout>
    {{-- Header Page --}}
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[2rem] md:rounded-b-[3rem] shadow-xl mb-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 relative overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto text-center md:text-left">
            <h1 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide">Edit Pengguna</h1>
            <p class="text-red-100 text-sm md:text-base mt-2 opacity-90 font-light">Perbarui informasi dan hak akses pengguna.</p>
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

        <form action="{{ route('management-akun.update', $user->id) }}" method="POST" class="bg-white rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')
            <div class="p-6 md:p-8 space-y-6 md:space-y-8">
                <div>
                    <h2 class="text-base md:text-lg font-bold text-[#e92027] border-b border-gray-100 pb-3 mb-6 flex items-center gap-3">
                        <i class="fas fa-user-edit text-[#e92027]"></i> Data Informasi Akun
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                        {{-- Nama --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Lengkap <span class="text-red-600">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] focus:ring-2 focus:ring-[#e92027]/20 transition duration-200">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Email <span class="text-red-600">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] focus:ring-2 focus:ring-[#e92027]/20 transition duration-200">
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Role Pengguna <span class="text-red-600">*</span></label>
                            <div class="relative">
                                <select name="role" required
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 outline-none appearance-none cursor-pointer focus:bg-white focus:border-[#e92027] focus:ring-2 focus:ring-[#e92027]/20 transition duration-200">
                                    <option value="karyawan" {{ old('role', $user->role) == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <i class="fas fa-chevron-down text-sm"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Ganti Password (Opsional) --}}
                        <div class="md:col-span-2 pt-6 border-t border-gray-100 mt-2">
                            <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-lock text-gray-400"></i> Keamanan (Ganti Password)
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-600 mb-2">Password Baru (Opsional)</label>
                                    <input type="password" name="password"
                                        class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] focus:ring-2 focus:ring-[#e92027]/20 transition duration-200"
                                        placeholder="Biarkan kosong jika tidak diubah">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-600 mb-2">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation"
                                        class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 text-gray-800 focus:bg-white outline-none focus:border-[#e92027] focus:ring-2 focus:ring-[#e92027]/20 transition duration-200"
                                        placeholder="Ketik ulang password baru">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 md:px-8 md:py-6 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3 md:gap-4">
                <a href="{{ route('management-akun.index') }}"
                    class="w-full md:w-auto text-center px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-100 transition">Batalkan</a>
                <button type="submit"
                    class="w-full md:w-auto px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold shadow-md hover:bg-[#c41820] hover:shadow-lg transition transform hover:-translate-y-0.5">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-layout>
