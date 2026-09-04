<x-layout>
    {{-- Header Page --}}
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[2rem] md:rounded-b-[3rem] shadow-xl mb-8 -mt-4 md:-mt-6 -mx-4 md:-mx-6 relative overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto text-center md:text-left">
            <h1 class="text-2xl md:text-4xl font-extrabold text-white tracking-wide">Tambah Data Baru</h1>
            </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-16 md:-mt-24 relative z-20 mb-12">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-bold text-red-700">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('monitoring.store') }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden" hx-disable>
            @csrf
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">PIC (Staf) <span class="text-red-600">*</span></label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                        <option value="" disabled selected>Pilih PIC</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Pilih Berita Acara (BA) <span class="text-red-600">*</span></label>
                    <select name="arsip_masuk_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                        <option value="" disabled selected>Pilih BA Target Pekerjaan</option>
                        @foreach($arsipMasuk as $arsip)
                            <option value="{{ $arsip->id }}" {{ old('arsip_masuk_id') == $arsip->id ? 'selected' : '' }}>
                                BA: {{ $arsip->nomor_berita_acara }} ({{ $arsip->jumlah_box_masuk }} Box)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Tahapan <span class="text-red-600">*</span></label>
                    <select name="tahapan" id="tahapanSelect" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                        <option value="" disabled selected>Pilih Tahapan Mulai</option>
                        <option value="Pemilahan" {{ old('tahapan') == 'Pemilahan' ? 'selected' : '' }}>Pemilahan</option>
                        <option value="Pendataan" {{ old('tahapan') == 'Pendataan' ? 'selected' : '' }}>Pendataan</option>
                        <option value="Pelabelan" {{ old('tahapan') == 'Pelabelan' ? 'selected' : '' }}>Pelabelan</option>
                        <option value="Alih Media" {{ old('tahapan') == 'Alih Media' ? 'selected' : '' }}>Alih Media (Jumlah Lembar)</option>
                        <option value="Input E-Arsip" {{ old('tahapan') == 'Input E-Arsip' ? 'selected' : '' }}>Input E-Arsip</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Pekerjaan Dimulai <span class="text-red-600">*</span></label>
                    <input type="date" name="tanggal_kerja" value="{{ old('tanggal_kerja', date('Y-m-d')) }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-2">Progress Awal <span id="lblUnit">Box</span> Selesai</label>
                    <input type="number" name="jumlah_box_selesai" value="{{ old('jumlah_box_selesai', 0) }}" min="0" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                    <p class="text-[11px] text-gray-500 mt-1.5"><i class="fas fa-info-circle"></i> Biarkan 0 jika baru akan dimulai.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-800 mb-2">Keterangan / Catatan</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('monitoring.index') }}" class="px-6 py-3 bg-white border border-gray-200 rounded-xl font-bold hover:bg-gray-100">Batal</a>
                <button type="submit" class="px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold hover:bg-[#c41820]">Simpan Tugas</button>
            </div>
        </form>
    </div>
    
    <script>
        document.getElementById('tahapanSelect').addEventListener('change', function() {
            document.getElementById('lblUnit').innerText = this.value === 'Alih Media' ? 'Lembar' : 'Box';
        });
    </script>
</x-layout>