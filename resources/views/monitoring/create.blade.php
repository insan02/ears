<x-layout>
    <div class="max-w-4xl mx-auto my-10">
        <div class="bg-[#e92027] rounded-t-2xl shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-red-900 to-[#e92027]"></div>
            <div class="absolute top-0 right-0 p-4 opacity-10">
                 <svg class="w-24 h-24 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="p-8 relative z-10">
                <h2 class="text-3xl font-bold text-white">Formulir Monitoring Kinerja Karyawan</h2>
                <p class="text-white mt-2">Silakan lengkapi data monitoring di bawah ini</p>
            </div>
        </div>
        
        <div class="bg-white p-8 rounded-b-2xl shadow-xl border border-red-100">
            <form action="{{ route('monitoring.store') }}" method="POST">
                 @csrf
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                     <!-- PIC -->
                     <div>
                         <label class="block text-gray-800 font-bold mb-2 text-sm">PIC</label>
                         <select name="user_id" required class="w-full bg-[#fff1f2] border border-red-200 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e92027]">
                             @foreach($users as $user)
                                 <option value="{{ $user->id }}">{{ $user->nama }}</option>
                             @endforeach
                         </select>
                     </div>

                     <!-- Tanggal Kerja -->
                     <div>
                         <label class="block text-gray-800 font-bold mb-2 text-sm">Tanggal Kerja</label>
                         <input type="date" name="tanggal_kerja" required class="w-full bg-[#fff1f2] border border-red-200 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e92027]">
                     </div>

                     <!-- Nomor Berita Acara -->
                     <div>
                         <label class="block text-gray-800 font-bold mb-2 text-sm">Nomor Berita Acara</label>
                         <select name="arsip_masuk_id" required class="w-full bg-[#fff1f2] border border-red-200 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e92027]">
                             <option value="" disabled selected>Pilih Nomor Berita Acara</option>
                             @foreach($arsipMasuk as $arsip)
                                 <option value="{{ $arsip->id }}">{{ $arsip->nomor_berita_acara }} ({{ $arsip->unit_asal }})</option>
                             @endforeach
                         </select>
                     </div>

                     <!-- Jumlah Box Selesai -->
                     <div>
                         <label class="block text-gray-800 font-bold mb-2 text-sm">Jumlah Selesai</label>
                         <input type="number" name="jumlah_box_selesai" class="w-full bg-[#fff1f2] border border-red-200 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e92027]" placeholder="0">
                     </div>

                     <!-- Keterangan -->
                     <div class="md:row-span-2">
                         <label class="block text-gray-800 font-bold mb-2 text-sm">Keterangan</label>
                         <textarea name="keterangan" rows="5" class="w-full bg-[#fff1f2] border border-red-200 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e92027]" placeholder="Opsional"></textarea>
                     </div>

                     <!-- Tahapan Pengarsipan -->
                     <div>
                         <label class="block text-gray-800 font-bold mb-2 text-sm">Tahapan Pengarsipan</label>
                         <select name="tahapan" required class="w-full bg-[#fff1f2] border border-red-200 rounded-lg p-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#e92027]">
                            <option value="" disabled selected>Pilih Tahapan</option>
                            <option value="Pemilahan">Pemilahan</option>
                            <option value="Pendataan">Pendataan</option>
                            <option value="Pelabelan">Pelabelan</option>
                            <option value="Alih Media">Alih Media</option>
                            <option value="Input E-Arsip">Input E-Arsip</option>
                         </select>
                     </div>
                     
    
                 </div>
    
                 <div class="flex justify-end mt-10 gap-4">
                     <a href="{{ route('monitoring.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-10 rounded-lg shadow-lg transform transition hover:scale-105 flex items-center justify-center">
                         Kembali
                     </a>
                     <button type="submit" class="bg-[#e92027] hover:bg-[#c41820] text-white font-bold py-3 px-10 rounded-lg shadow-lg transform transition hover:scale-105">
                         Submit
                     </button>
                 </div>
            </form>
        </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tahapanSelect = document.querySelector('select[name="tahapan"]');
            const nbaSelect = document.querySelector('select[name="arsip_masuk_id"]');
            const nbaContainer = nbaSelect.closest('div');

            function toggleNba() {
                if (tahapanSelect.value === 'Alih Media') {
                    nbaContainer.style.display = 'none';
                    nbaSelect.removeAttribute('required');
                    nbaSelect.value = '';
                } else {
                    nbaContainer.style.display = 'block';
                    nbaSelect.setAttribute('required', 'required');
                }
            }

            tahapanSelect.addEventListener('change', toggleNba);
            toggleNba(); // Run on load
        });
    </script>
    </div>
</x-layout>
