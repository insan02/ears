<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Berita & Informasi - PT Semen Padang</title>
    <link rel="icon" href="{{ asset('images/logosp.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Header Sederhana -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-red-50 text-gray-500 group-hover:text-[#e92027] transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </div>
                <span class="font-bold text-gray-700 group-hover:text-[#e92027] hidden sm:block transition-colors">Kembali ke Landing Page</span>
            </a>
            <img src="{{ asset('images/sp-black.png') }}" alt="Logo Semen Padang" class="h-8 md:h-10">
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-grow container mx-auto px-6 max-w-[1400px] py-12">
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Semua Berita & Informasi</h1>
                <p class="text-gray-500">Kumpulan seluruh aktivitas dan dokumentasi pengelolaan arsip.</p>
            </div>

        </div>

        @if($mediaInfo->isEmpty())
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-lg font-bold text-gray-800">Pencarian Tidak Ditemukan</h3>
                <p class="text-gray-500">Silakan gunakan kata kunci lain.</p>
            </div>
        @else
            {{-- Grid yang persis sama dengan di Landing Page --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 mb-12">
                @foreach($mediaInfo as $item)
                    @php
                        $gambarArray = json_decode($item->gambar, true);
                        if (!is_array($gambarArray)) { $gambarArray = $item->gambar ? [$item->gambar] : []; }
                        $jumlahFoto = count($gambarArray);
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-shadow duration-300">
                        <div x-data="{ activeImg: 0, sliderImgs: {{ \Illuminate\Support\Js::from($gambarArray) }} }"
                             class="relative h-40 md:h-48 w-full overflow-hidden bg-slate-900 flex items-center justify-center">

                            <template x-for="(img, idx) in sliderImgs" :key="idx">
                                <img x-show="activeImg === idx" :src="'{{ asset('storage') }}/' + img" alt="{{ $item->judul }}"
                                     class="max-w-full max-h-full w-auto h-auto object-contain select-none">
                            </template>

                            <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm text-[#e92027] text-[10px] font-bold px-2 py-1 rounded-full shadow-sm z-10">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </div>

                            <template x-if="sliderImgs.length > 1">
                                <div>
                                    <button @click.stop="activeImg = activeImg === 0 ? sliderImgs.length - 1 : activeImg - 1" class="absolute left-1 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-[#e92027] text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition duration-300 z-10"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg></button>
                                    <button @click.stop="activeImg = activeImg === sliderImgs.length - 1 ? 0 : activeImg + 1" class="absolute right-1 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-[#e92027] text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition duration-300 z-10"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg></button>
                                </div>
                            </template>
                        </div>

                        <div class="p-4 flex flex-col grow">
                            <h3 class="font-bold text-gray-800 text-sm md:text-base mb-2 line-clamp-2 group-hover:text-[#e92027] transition-colors" title="{{ $item->judul }}">{{ $item->judul }}</h3>
                            <p class="text-gray-500 text-xs leading-relaxed line-clamp-3 mb-4 grow">{{ $item->deskripsi }}</p>
                            <button onclick="bukaModalBerita_{{ $item->id }}()" class="mt-auto text-[#e92027] font-bold text-xs flex items-center gap-1.5 hover:gap-2 transition-all duration-300 w-fit">Selengkapnya <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></button>
                        </div>
                    </div>

                    {{-- Modal Detail Berita --}}
                    <div id="modal-berita-{{ $item->id }}" class="fixed inset-0 z-[200] flex items-center justify-center bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4">
                        <div class="bg-white rounded-3xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col relative transform scale-95 transition-transform duration-300" id="modal-content-{{ $item->id }}">
                            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-white z-10 sticky top-0">
                                <h3 class="font-bold text-gray-800 line-clamp-1 pr-4">{{ $item->judul }}</h3>
                                <button onclick="tutupModalBerita_{{ $item->id }}()" class="p-2 bg-gray-100 hover:bg-red-50 text-gray-500 hover:text-red-500 rounded-full transition-colors focus:outline-none"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                            <div class="overflow-y-auto p-6 grow custom-scrollbar">
                                <div class="flex items-center gap-3 mb-6"><span class="bg-red-50 text-[#e92027] text-xs font-bold px-3 py-1 rounded-full border border-red-100">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span></div>
                                @if($jumlahFoto > 0)
                                    <div x-data="{ activeImage: 0, images: {{ \Illuminate\Support\Js::from(array_map(fn($img) => asset('storage/' . $img), $gambarArray)) }} }" class="mb-6 relative rounded-2xl overflow-hidden bg-slate-900 border border-gray-200">
                                        <div class="h-64 sm:h-80 md:h-96 w-full flex items-center justify-center p-2">
                                            <template x-for="(img, index) in images" :key="index"><img x-show="activeImage === index" :src="img" class="max-h-full max-w-full w-auto h-auto object-contain transition-opacity duration-300" x-transition.opacity></template>
                                        </div>
                                        @if($jumlahFoto > 1)
                                            <button @click="activeImage = activeImage === 0 ? images.length - 1 : activeImage - 1" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-[#e92027] text-white p-2 rounded-full backdrop-blur-sm transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
                                            <button @click="activeImage = activeImage === images.length - 1 ? 0 : activeImage + 1" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-[#e92027] text-white p-2 rounded-full backdrop-blur-sm transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
                                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 bg-black/50 px-2 py-1 rounded-full backdrop-blur-md"><template x-for="(img, index) in images" :key="index"><div :class="activeImage === index ? 'w-4 bg-white' : 'w-1.5 bg-white/50'" class="h-1.5 rounded-full transition-all duration-300"></div></template></div>
                                        @endif
                                    </div>
                                @endif
                                <div class="prose prose-sm md:prose-base max-w-none text-gray-600 leading-loose">{!! nl2br(e($item->deskripsi)) !!}</div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function bukaModalBerita_{{ $item->id }}() { const modal = document.getElementById('modal-berita-{{ $item->id }}'); const content = document.getElementById('modal-content-{{ $item->id }}'); modal.classList.remove('opacity-0', 'pointer-events-none'); content.classList.remove('scale-95'); document.body.style.overflow = 'hidden'; }
                        function tutupModalBerita_{{ $item->id }}() { const modal = document.getElementById('modal-berita-{{ $item->id }}'); const content = document.getElementById('modal-content-{{ $item->id }}'); modal.classList.add('opacity-0', 'pointer-events-none'); content.classList.add('scale-95'); document.body.style.overflow = 'auto'; }
                    </script>
                @endforeach
            </div>

            {{-- Pagination Links (Bawaan Laravel) --}}
            <div class="mt-8">
                {{ $mediaInfo->links() }}
            </div>
        @endif
    </main>

    <!-- Footer -->
    <div class="py-6 border-t border-gray-200 mt-auto bg-white">
        <div class="container mx-auto px-6 text-center">
             <p class="text-gray-500 text-sm font-medium">Record Center PT Semen Padang &copy; {{ date('Y') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
