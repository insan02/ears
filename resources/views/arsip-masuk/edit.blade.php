<x-layout>
    {{-- Header Page --}}
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[2rem] md:rounded-b-[3rem] shadow-xl mb-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 relative overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto text-center md:text-left">
            <h1 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide">Edit Arsip Masuk</h1>
            <p class="text-red-100 text-sm md:text-base mt-2 opacity-90 font-light">Perbarui data awal arsip masuk.</p>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
    </div>

    {{-- Main Form Container --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-16 md:-mt-24 relative z-20 mb-12">
        <form action="{{ route('arsip-masuk.update', $arsipMasuk->id) }}" method="POST" class="bg-white rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-6 md:space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                    <!-- Unit Asal -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Unit Asal <span class="text-red-600">*</span></label>
                        <select name="unit_asal" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                            <option value="" disabled>Pilih Unit Asal</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->nama_unit }}" {{ $arsipMasuk->unit_asal == $unit->nama_unit ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nomor Berita Acara -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Nomor Berita Acara <span class="text-red-600">*</span></label>
                        <input type="text" name="nomor_berita_acara" value="{{ old('nomor_berita_acara', $arsipMasuk->nomor_berita_acara) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                    </div>

                    <!-- User Penerima -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Penerima <span class="text-red-600">*</span></label>
                        <select name="user_penerima" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $arsipMasuk->user_penerima == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Terima -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Terima <span class="text-red-600">*</span></label>
                        <input type="date" name="tanggal_terima" value="{{ old('tanggal_terima', $arsipMasuk->tanggal_terima) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                    </div>

                    <!-- Jumlah Box Masuk -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Jumlah Box Masuk <span class="text-red-600">*</span></label>
                        <input type="number" name="jumlah_box_masuk" value="{{ old('jumlah_box_masuk', $arsipMasuk->jumlah_box_masuk) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 md:px-8 md:py-6 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3 md:gap-4">
                <a href="{{ route('arsip-masuk.index') }}" class="w-full md:w-auto text-center px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold shadow-md hover:bg-[#c41820] hover:shadow-lg transition transform hover:-translate-y-0.5">Perbarui Data</button>
            </div>
        </form>
    </div>
</x-layout>
