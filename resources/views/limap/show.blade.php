@if(isset($printMode) && $printMode)
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Papan Informasi 5P - {{ $data->pic }}</title>
        <style>
            body { font-family: Arial, sans-serif; background: #fff; color: #000; padding: 20px; line-height: 1.5; font-size: 11pt; }
            .header { text-align: center; border-bottom: 4px solid #c41820; padding-bottom: 10px; margin-bottom: 20px; }
            .header h1 { margin: 0; color: #c41820; font-size: 24px; text-transform: uppercase; }
            .header p { margin: 5px 0 0; font-size: 14px; font-weight: bold; }
            .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
            .box { border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
            .box-title { font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; color: #c41820; text-transform: uppercase; font-size: 12px; }
            .content { white-space: pre-line; }
            @page { size: A4 portrait; margin: 15mm; }
        </style>
    </head>
    <body onload="window.print()">
        <div class="header">
            <h1>Papan Informasi 5P (Pemilahan, Penataan, Pembersihan, Penjagaan, Pendisiplinan)</h1>
            <p>Person In Charge (PIC): {{ $data->pic ?: 'Belum Ditentukan' }}</p>
        </div>

        <div class="grid">
            <div class="box"><div class="box-title">Kesepakatan</div><div class="content">{{ $data->kesepakatan ?: '-' }}</div></div>
            <div class="box"><div class="box-title">Pembagian Area</div><div class="content">{{ $data->pembagian_area ?: '-' }}</div></div>
            <div class="box"><div class="box-title">Visi & Misi</div><div class="content">{{ $data->visi_misi ?: '-' }}</div></div>
            <div class="box"><div class="box-title">Struktur</div><div class="content">{{ $data->struktur ?: '-' }}</div></div>
            <div class="box"><div class="box-title">Jadwal Kegiatan</div><div class="content">{{ $data->jadwal_kegiatan ?: '-' }}</div></div>
            <div class="box"><div class="box-title">Kaizen</div><div class="content">{{ $data->kaizen ?: '-' }}</div></div>
        </div>
    </body>
    </html>
@else
<x-layout>
    <div class="bg-gradient-to-br from-[#e92027] via-[#b91c1c] to-[#7f090b] text-white pb-32 pt-16 px-8 -mt-6 -mx-6 mb-8 rounded-b-[3rem] shadow-2xl relative">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center relative z-10 gap-6">
           <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                    <a href="{{ route('limap.index') }}" class="bg-white/20 hover:bg-white/40 p-2 rounded-full transition"><i class="fas fa-arrow-left"></i></a>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Papan 5P Area</h2>
                </div>
                <p class="text-red-50 text-sm md:text-base font-light ml-12">Detail papan informasi 5P.</p>
           </div>
           <div class="flex gap-2">
               <!-- Tombol Cetak PDF dibiarkan agar user biasa bisa mencetak -->
               <a href="{{ route('limap.show', ['id' => $data->id, 'print' => 'true']) }}" target="_blank" class="group bg-white/20 hover:bg-white/30 text-white px-6 py-3 rounded-full font-bold border border-white/30 flex items-center gap-2 transition">
                   <i class="fas fa-print"></i> Cetak PDF
               </a>

               <!-- TOMBOL EDIT HANYA UNTUK ADMIN -->
               @if(Auth::check() && Auth::user()->role == 'admin')
                   <a href="{{ route('limap.edit', $data->id) }}" class="group bg-white text-[#e92027] hover:bg-gray-50 px-6 py-3 rounded-full font-bold shadow-2xl flex items-center gap-2 transition">
                       <i class="fas fa-pen"></i> Edit Papan
                   </a>
               @endif
           </div>
       </div>
   </div>

   <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 mb-12">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-3 shadow-sm">
                <div class="bg-green-100 p-2 rounded-full text-green-600"><i class="fas fa-check"></i></div>
                <p class="text-sm font-bold text-green-800 flex-1">{{ session('success') }}</p>
                <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="space-y-6 lg:col-span-2">
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-6 md:p-8"><h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-3"><i class="fas fa-handshake text-[#e92027]"></i> Kesepakatan</h3><div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line leading-relaxed">{{ $data->kesepakatan ?: '-' }}</div></div>
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-6 md:p-8"><h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-3"><i class="fas fa-bullseye text-[#e92027]"></i> Visi & Misi</h3><div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line leading-relaxed">{{ $data->visi_misi ?: '-' }}</div></div>
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-6 md:p-8"><h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-3"><i class="fas fa-sitemap text-[#e92027]"></i> Struktur</h3><div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line leading-relaxed">{{ $data->struktur ?: '-' }}</div></div>
            </div>

            <div class="space-y-6">
                <div class="bg-[#e92027] rounded-[2rem] shadow-xl border border-red-800 p-6 md:p-8 text-white text-center relative overflow-hidden"><i class="fas fa-user-shield absolute -right-4 -bottom-4 text-7xl text-white opacity-10"></i><h3 class="text-sm font-bold text-red-100 uppercase tracking-widest mb-2 relative z-10">PIC Area Ini</h3><div class="text-2xl font-extrabold relative z-10">{{ $data->pic ?: 'Belum Ditentukan' }}</div></div>
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-6 md:p-8"><h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-3"><i class="fas fa-map-marked-alt text-[#e92027]"></i> Pembagian Area</h3><div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line leading-relaxed">{{ $data->pembagian_area ?: '-' }}</div></div>
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-6 md:p-8"><h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-3"><i class="fas fa-calendar-alt text-[#e92027]"></i> Jadwal Kegiatan</h3><div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line leading-relaxed">{{ $data->jadwal_kegiatan ?: '-' }}</div></div>
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-6 md:p-8"><h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4 flex items-center gap-3"><i class="fas fa-lightbulb text-[#e92027]"></i> Kaizen</h3><div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line leading-relaxed">{{ $data->kaizen ?: '-' }}</div></div>
            </div>
        </div>
   </div>
</x-layout>
@endif
