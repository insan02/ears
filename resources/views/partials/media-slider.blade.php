<section id="informasi" class="py-20 bg-white">
    <div class="container mx-auto px-6">

        {{-- Section Header --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-[#e92027] uppercase tracking-wider mb-4">Informasi & Media</h2>
            <div class="w-24 h-1 bg-[#e92027] mx-auto rounded"></div>
            <p class="mt-6 text-gray-500 max-w-2xl mx-auto">
                Berita terkini dan informasi terbaru seputar aktivitas pengelolaan arsip di lingkungan PT Semen Padang.
            </p>
        </div>

        @if($mediaInfo->isEmpty())
            <div class="text-center py-12 bg-gray-50 rounded-3xl border border-gray-100">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                <p class="text-gray-500 font-medium">Belum ada berita atau informasi terbaru.</p>
            </div>
        @else
            {{-- Horizontal Scroll Container (Lebih mulus di HP) --}}
            <div class="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory hide-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    .hide-scrollbar::-webkit-scrollbar { display: none; }
                </style>

                @foreach($mediaInfo as $item)
                    {{-- Proses JSON Array Gambar --}}
                    @php
                        $gambarArray = json_decode($item->gambar, true);
                        // Ambil gambar pertama untuk sampul. Jika data lama yang bukan array, ambil langsung datanya
                        $sampul = is_array($gambarArray) && count($gambarArray) > 0 ? $gambarArray[0] : $item->gambar;
                        $jumlahFoto = is_array($gambarArray) ? count($gambarArray) : 1;
                    @endphp

                    {{-- Card Berita --}}
                    <div class="min-w-[300px] md:min-w-[350px] max-w-[350px] bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-50 overflow-hidden snap-center flex flex-col group hover:-translate-y-2 transition-transform duration-300">

                        {{-- Thumbnail Alpine Slider Langsung di Card --}}
                        <div x-data="{ activeImg: 0, sliderImgs: {{ Js::from($gambarArray) }} }"
                             class="relative h-48 md:h-56 overflow-hidden bg-gray-100 group/slider">

                            <!-- Looping Gambar -->
                            <template x-for="(img, idx) in sliderImgs" :key="idx">
                                <img x-show="activeImg === idx" :src="'/' + img" alt="{{ $item->judul }}"
                                     x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-105" x-transition:enter-end="opacity-100 transform scale-100"
                                     class="absolute inset-0 w-full h-full object-cover">
                            </template>

                            {{-- Badge Tanggal --}}
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-[#e92027] text-xs font-bold px-3 py-1.5 rounded-full shadow-sm z-10">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </div>

                            <!-- Navigasi Kiri/Kanan (Muncul saat diarahkan mouse & Ada > 1 gambar) -->
                            <template x-if="sliderImgs.length > 1">
                                <div>
                                    <button @click.stop="activeImg = activeImg === 0 ? sliderImgs.length - 1 : activeImg - 1"
                                            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-[#e92027] text-white p-2 rounded-full opacity-0 group-hover/slider:opacity-100 transition duration-300 z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                                    </button>
                                    <button @click.stop="activeImg = activeImg === sliderImgs.length - 1 ? 0 : activeImg + 1"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-[#e92027] text-white p-2 rounded-full opacity-0 group-hover/slider:opacity-100 transition duration-300 z-10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                    </button>

                                    <!-- Dots Indikator -->
                                    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1 bg-black/30 px-2 py-1 rounded-full z-10">
                                        <template x-for="(img, idx) in sliderImgs" :key="idx">
                                            <div :class="activeImg === idx ? 'bg-white w-3' : 'bg-white/50 w-1.5'" class="h-1.5 rounded-full transition-all duration-300"></div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Text Content --}}
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-bold text-gray-800 text-lg mb-3 line-clamp-2 group-hover:text-[#e92027] transition-colors">
                                {{ $item->judul }}
                            </h3>
                            <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-6 flex-grow">
                                {{ $item->deskripsi }}
                            </p>

                            {{-- Button Baca Selengkapnya --}}
                            <button onclick="bukaModalBerita_{{ $item->id }}()" class="mt-auto text-[#e92027] font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all duration-300">
                                Baca Selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Detail Berita (Tersembunyi) --}}
                    <div id="modal-berita-{{ $item->id }}" class="fixed inset-0 z-[200] flex items-center justify-center bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4">
                        <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col relative transform scale-95 transition-transform duration-300" id="modal-content-{{ $item->id }}">

                            {{-- Header Modal --}}
                            <div class="flex justify-between items-center p-5 border-b border-gray-100 bg-white z-10 sticky top-0">
                                <h3 class="font-bold text-gray-800 line-clamp-1 pr-4">{{ $item->judul }}</h3>
                                <button onclick="tutupModalBerita_{{ $item->id }}()" class="p-2 bg-gray-100 hover:bg-red-50 text-gray-500 hover:text-red-500 rounded-full transition-colors focus:outline-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            {{-- Body Modal --}}
                            <div class="overflow-y-auto p-6 flex-grow custom-scrollbar">
                                <div class="flex items-center gap-3 mb-6">
                                    <span class="bg-red-50 text-[#e92027] text-xs font-bold px-3 py-1 rounded-full border border-red-100">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </span>
                                </div>

                                {{-- Slider Gambar di dalam Modal --}}
                                @if($jumlahFoto > 0)
                                    <div x-data="{
                                            activeImage: 0,
                                            images: [
                                                @if(is_array($gambarArray))
                                                    @foreach($gambarArray as $img) '{{ asset($img) }}', @endforeach
                                                @else
                                                    '{{ asset($item->gambar) }}'
                                                @endif
                                            ]
                                         }"
                                         class="mb-6 relative rounded-2xl overflow-hidden bg-gray-100 border border-gray-100">

                                        <!-- Gambar Utama -->
                                        <div class="aspect-video relative">
                                            <template x-for="(img, index) in images" :key="index">
                                                <img x-show="activeImage === index" :src="img"
                                                     class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
                                                     x-transition.opacity>
                                            </template>
                                        </div>

                                        <!-- Tombol Prev/Next (Hanya muncul jika foto > 1) -->
                                        @if($jumlahFoto > 1)
                                            <button @click="activeImage = activeImage === 0 ? images.length - 1 : activeImage - 1" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-[#e92027] text-white p-2 rounded-full backdrop-blur-sm transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                            </button>
                                            <button @click="activeImage = activeImage === images.length - 1 ? 0 : activeImage + 1" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/50 hover:bg-[#e92027] text-white p-2 rounded-full backdrop-blur-sm transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </button>

                                            <!-- Titik Indikator -->
                                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 bg-black/40 px-2 py-1 rounded-full backdrop-blur-md">
                                                <template x-for="(img, index) in images" :key="index">
                                                    <div :class="activeImage === index ? 'w-4 bg-white' : 'w-1.5 bg-white/50'" class="h-1.5 rounded-full transition-all duration-300"></div>
                                                </template>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="prose prose-sm md:prose-base max-w-none text-gray-600 leading-loose">
                                    {!! nl2br(e($item->deskripsi)) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Vanilla JS untuk Handle Modal --}}
                    <script>
                        function bukaModalBerita_{{ $item->id }}() {
                            const modal = document.getElementById('modal-berita-{{ $item->id }}');
                            const content = document.getElementById('modal-content-{{ $item->id }}');
                            modal.classList.remove('opacity-0', 'pointer-events-none');
                            content.classList.remove('scale-95');
                            document.body.style.overflow = 'hidden'; // Kunci scroll belakang
                        }
                        function tutupModalBerita_{{ $item->id }}() {
                            const modal = document.getElementById('modal-berita-{{ $item->id }}');
                            const content = document.getElementById('modal-content-{{ $item->id }}');
                            modal.classList.add('opacity-0', 'pointer-events-none');
                            content.classList.add('scale-95');
                            document.body.style.overflow = 'auto'; // Buka scroll belakang
                        }
                    </script>

                @endforeach
            </div>
        @endif

    </div>
</section>
