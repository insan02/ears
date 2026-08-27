<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative">
        <div class="max-w-7xl mx-auto relative z-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Tambah Papan 5P Baru</h1>
            <p class="text-red-50 text-base font-light">Buat data informasi 5P untuk area atau divisi baru.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 -mt-20 relative z-20 mb-10">
        <form action="{{ route('limap.store') }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            @csrf
            <div class="p-6 md:p-8 space-y-6">
                <div>
                    <label class="block text-sm font-bold text-[#e92027] mb-2"><i class="fas fa-user-shield"></i> Nama PIC (Person In Charge)</label>
                    <input type="text" name="pic" required placeholder="Contoh: Budi Santoso - Area Gudang" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 focus:bg-white outline-none focus:border-[#e92027] font-bold">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block text-sm font-bold text-gray-800 mb-2"><i class="fas fa-handshake text-[#e92027]"></i> Kesepakatan</label><textarea name="kesepakatan" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:border-[#e92027]"></textarea></div>
                    <div><label class="block text-sm font-bold text-gray-800 mb-2"><i class="fas fa-bullseye text-[#e92027]"></i> Visi & Misi</label><textarea name="visi_misi" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:border-[#e92027]"></textarea></div>
                    <div><label class="block text-sm font-bold text-gray-800 mb-2"><i class="fas fa-map-marked-alt text-[#e92027]"></i> Pembagian Area</label><textarea name="pembagian_area" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:border-[#e92027]"></textarea></div>
                    <div><label class="block text-sm font-bold text-gray-800 mb-2"><i class="fas fa-sitemap text-[#e92027]"></i> Struktur</label><textarea name="struktur" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:border-[#e92027]"></textarea></div>
                    <div><label class="block text-sm font-bold text-gray-800 mb-2"><i class="fas fa-calendar-alt text-[#e92027]"></i> Jadwal Kegiatan</label><textarea name="jadwal_kegiatan" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:border-[#e92027]"></textarea></div>
                    <div><label class="block text-sm font-bold text-gray-800 mb-2"><i class="fas fa-lightbulb text-[#e92027]"></i> Kaizen</label><textarea name="kaizen" rows="4" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:border-[#e92027]"></textarea></div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('limap.index') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-xl font-bold">Batal</a>
                <button type="submit" class="px-8 py-3 bg-[#e92027] text-white rounded-xl font-bold hover:bg-[#a0131a] shadow-lg">Tambah Papan 5P</button>
            </div>
        </form>
    </div>
</x-layout>
