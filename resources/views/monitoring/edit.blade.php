<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] px-4 md:px-8 pt-8 md:pt-12 pb-24 md:pb-32 rounded-b-[3rem] mb-8 relative">
        <h1 class="text-3xl font-extrabold text-white text-center">Edit Monitoring Kinerja</h1>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 -mt-24 relative z-20 mb-12">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm text-red-700 font-bold">
                <ul class="list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <form action="{{ route('monitoring.update', $monitoring->id) }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @csrf @method('PUT')
            
            <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                <div>
                    <label class="block font-bold mb-2">PIC (Staf) <span class="text-red-600">*</span></label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $monitoring->user_id) == $user->id ? 'selected' : '' }}>{{ $user->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-2">Berita Acara (BA) <span class="text-red-600">*</span></label>
                    <select name="arsip_masuk_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                        @foreach($arsipMasuk as $arsip)
                            <option value="{{ $arsip->id }}" {{ old('arsip_masuk_id', $monitoring->arsip_masuk_id) == $arsip->id ? 'selected' : '' }}>BA: {{ $arsip->nomor_berita_acara }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-2">Tahapan <span class="text-red-600">*</span></label>
                    <select name="tahapan" id="tahapanSelect" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-[#e92027] outline-none">
                        <option value="Pemilahan" {{ old('tahapan', $monitoring->tahapan) == 'Pemilahan' ? 'selected' : '' }}>Pemilahan</option>
                        <option value="Pendataan" {{ old('tahapan', $monitoring->tahapan) == 'Pendataan' ? 'selected' : '' }}>Pendataan</option>
                        <option value="Pelabelan" {{ old('tahapan', $monitoring->tahapan) == 'Pelabelan' ? 'selected' : '' }}>Pelabelan</option>
                        <option value="Alih Media" {{ old('tahapan', $monitoring->tahapan) == 'Alih Media' ? 'selected' : '' }}>Alih Media</option>
                        <option value="Input E-Arsip" {{ old('tahapan', $monitoring->tahapan) == 'Input E-Arsip' ? 'selected' : '' }}>Input E-Arsip</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold mb-2">Tanggal Pengerjaan</label>
                    <input type="date" name="tanggal_kerja" value="{{ old('tanggal_kerja', $monitoring->tanggal_kerja) }}" required class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-[#e92027] outline-none">
                </div>

                <div>
                    <label class="block font-bold mb-2">Progress (<span id="lblUnit">Box</span>) Selesai</label>
                    <input type="number" name="jumlah_box_selesai" value="{{ old('jumlah_box_selesai', $monitoring->jumlah_box_selesai) }}" min="0" class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-[#e92027] outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-bold mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 bg-gray-50 border rounded-xl focus:ring-[#e92027] outline-none">{{ old('keterangan', $monitoring->keterangan) }}</textarea>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-5 border-t flex justify-end gap-3 rounded-b-3xl">
                <a href="{{ route('monitoring.index') }}" class="px-6 py-3 bg-white border font-bold rounded-xl hover:bg-gray-100">Batal</a>
                <button type="submit" class="px-8 py-3 bg-[#e92027] text-white font-bold rounded-xl shadow-md hover:bg-[#c41820]">Update Data</button>
            </div>
        </form>
    </div>

    <script>
        const tahapanSelect = document.getElementById('tahapanSelect');
        const updateUnit = () => document.getElementById('lblUnit').innerText = tahapanSelect.value === 'Alih Media' ? 'Lembar' : 'Box';
        tahapanSelect.addEventListener('change', updateUnit);
        updateUnit(); // Init
    </script>
</x-layout>