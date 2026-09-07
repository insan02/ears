<x-layout>
    <div class="min-h-[80vh] flex flex-col justify-center items-center py-10">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-red-900">Buku Tamu Digital</h1>
            <p class="text-gray-500 mt-2">Silakan isi data diri Anda sebelum memasuki Ruang Arsip.</p>
        </div>

        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-gray-100 overflow-hidden relative">
            <div class="h-2 bg-linear-to-r from-red-900 via-red-700 to-red-500"></div>

            <div class="p-8 md:p-12">
                <form action="{{ route('pengunjung.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" required
                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200 transition outline-none"
                                placeholder="Tulis nama lengkap Anda...">
                            @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Asal Instansi / Umum</label>
                                <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}" required
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200 transition outline-none"
                                    placeholder="Contoh: UNAND / Umum">
                                @error('asal_instansi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Handphone</label>
                                <input type="number" name="no_hp" value="{{ old('no_hp') }}" required
                                    class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200 transition outline-none"
                                    placeholder="08xxxxxxxxxx">
                                @error('no_hp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pesan & Kesan</label>
                            <textarea name="pesan_kesan" rows="4" required
                                class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-200 transition outline-none resize-none"
                                placeholder="Tuliskan pengalaman atau pesan Anda berkunjung ke sini..."></textarea>
                            @error('pesan_kesan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="mt-10 flex items-center justify-between gap-4">
                        <a href="{{ route('pengunjung.index') }}" class="text-gray-500 font-semibold hover:text-gray-800 transition">
                            &larr; Kembali
                        </a>
                        <button type="submit" class="bg-red-900 hover:bg-red-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                            Simpan Buku Tamu
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-layout>
