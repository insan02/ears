<x-layout>
    {{-- Header Page --}}
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[2rem] md:rounded-b-[3rem] shadow-xl mb-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 relative overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto text-center md:text-left">
            <h1 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide">Edit Monitoring Kinerja</h1>
            <p class="text-red-100 text-sm md:text-base mt-2 opacity-90 font-light">Perbarui data atau koreksi kesalahan input staf.</p>
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
                        <h3 class="text-sm font-bold text-red-800">Gagal Memperbarui Data!</h3>
                        <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('monitoring.update', $monitoring->id) }}" method="POST" class="bg-white rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-6 md:space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">

                    <!-- PIC -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">PIC (Staf) <span class="text-red-600">*</span></label>
                        <select name="user_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                            <option value="" disabled>Pilih PIC</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $monitoring->user_id) == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Kerja -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Pengerjaan <span class="text-red-600">*</span></label>
                        <input type="date" name="tanggal_kerja" value="{{ old('tanggal_kerja', $monitoring->tanggal_kerja) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                    </div>

                    <!-- Tahapan Pengarsipan -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Tahapan Pengarsipan <span class="text-red-600">*</span></label>
                        <select name="tahapan" id="tahapanSelect" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                            <option value="" disabled>Pilih Tahapan</option>
                            <option value="Pemilahan" {{ old('tahapan', $monitoring->tahapan) == 'Pemilahan' ? 'selected' : '' }}>Pemilahan</option>
                            <option value="Pendataan" {{ old('tahapan', $monitoring->tahapan) == 'Pendataan' ? 'selected' : '' }}>Pendataan</option>
                            <option value="Pelabelan" {{ old('tahapan', $monitoring->tahapan) == 'Pelabelan' ? 'selected' : '' }}>Pelabelan</option>
                            <option value="Alih Media" {{ old('tahapan', $monitoring->tahapan) == 'Alih Media' ? 'selected' : '' }}>Alih Media</option>
                            <option value="Input E-Arsip" {{ old('tahapan', $monitoring->tahapan) == 'Input E-Arsip' ? 'selected' : '' }}>Input E-Arsip</option>
                        </select>
                    </div>

                    <!-- Nomor Berita Acara -->
                    <div id="nbaContainer">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Nomor Berita Acara <span class="text-red-600">*</span></label>
                        <select name="arsip_masuk_id" id="nbaSelect" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm">
                            <option value="" disabled>Pilih Berita Acara</option>
                            @foreach($arsipMasuk as $arsip)
                                <option value="{{ $arsip->id }}" {{ old('arsip_masuk_id', $monitoring->arsip_masuk_id) == $arsip->id ? 'selected' : '' }}>
                                    {{ $arsip->nomor_berita_acara }} ({{ $arsip->unit_asal }} - {{ $arsip->jumlah_box_masuk }} Box)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Jumlah Box Selesai -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Progress Selesai (Box)</label>
                        <input type="number" name="jumlah_box_selesai" value="{{ old('jumlah_box_selesai', $monitoring->jumlah_box_selesai) }}" min="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm" placeholder="Contoh: 5">
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="4" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition text-sm resize-y" placeholder="Catatan opsional...">{{ old('keterangan', $monitoring->keterangan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="bg-gray-50 px-6 py-5 md:px-8 md:py-6 border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3 md:gap-4">
                <a href="{{ route('monitoring.index') }}" class="w-full md:w-auto text-center px-6 py-3 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-100 transition">Batal</a>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold shadow-md hover:bg-[#c41820] hover:shadow-lg transition transform hover:-translate-y-0.5">Perbarui Data</button>
            </div>
        </form>
    </div>

    <!-- Script Logika Tampilan Form -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tahapanSelect = document.getElementById('tahapanSelect');
            const nbaSelect = document.getElementById('nbaSelect');
            const nbaContainer = document.getElementById('nbaContainer');

            // Simpan data lama agar bisa dikembalikan saat batal memilih "Alih Media"
            const originalNbaValue = nbaSelect.value;

            function toggleNba() {
                if (tahapanSelect.value === 'Alih Media') {
                    nbaContainer.style.display = 'none';
                    nbaSelect.removeAttribute('required');
                    nbaSelect.value = ''; // Kosongkan agar validasi controller tembus
                } else {
                    nbaContainer.style.display = 'block';
                    nbaSelect.setAttribute('required', 'required');

                    // Kembalikan ke pilihan asal jika tiba-tiba diubah dari Alih Media ke opsi lain
                    if (nbaSelect.value === '' && originalNbaValue !== '') {
                        nbaSelect.value = originalNbaValue;
                    }
                }
            }

            if(tahapanSelect) {
                tahapanSelect.addEventListener('change', toggleNba);
                toggleNba(); // Jalankan saat pertama kali dimuat
            }
        });
    </script>
</x-layout>
