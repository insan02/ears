<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visi Misi - Sistem Informasi e-Arsip PT Semen Padang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif !important; }
    </style>
</head>
<body class="bg-white">

    <!-- Header Mengambang (Floating Capsule) -->
    <header x-data="{ mobileMenuOpen: false, isScrolled: false }"
            @scroll.window="isScrolled = (window.pageYOffset > 20)"
            class="fixed top-3 md:top-5 left-0 w-full z-50 px-4 md:px-6 flex flex-col items-center transition-all duration-300">

        <!-- Navbar Tabung / Kapsul -->
        <nav :class="isScrolled ? 'shadow-[0_10px_40px_rgba(0,0,0,0.15)] bg-white/95' : 'shadow-lg bg-white/80'"
             class="w-full max-w-5xl backdrop-blur-md rounded-full px-5 md:px-8 py-2 md:py-3 flex justify-between items-center transition-all duration-300 border border-white/60">

            <!-- Logo (Ukurannya disesuaikan agar pas di dalam tabung) -->
            <div class="flex items-center">
                 <img src="{{ asset('images/sp-black.png') }}"
                      alt="Logo Semen Padang"
                      class="h-9 sm:h-10 md:h-12 drop-shadow-sm filter brightness-100 transition-all duration-300">
            </div>

            <!-- Menu Desktop (Tampil di Laptop) -->
            <div class="hidden md:flex items-center gap-8 font-semibold text-sm text-gray-700">
                <a href="{{ route('landing') }}" class="hover:text-[#e92027] transition-colors">Beranda</a>

                <!-- Dropdown Tentang Kami -->
                <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="hover:text-[#e92027] transition-colors flex items-center gap-1 focus:outline-none py-2">
                        Tentang Kami
                        <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <!-- Dropdown Content (Mengambang di bawah menu) -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-3"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         class="absolute left-1/2 transform -translate-x-1/2 mt-0 w-56 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl py-3 z-50 border border-gray-100"
                         style="display: none;">
                        <a href="{{ route('visi-misi') }}" class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-[#e92027] transition-colors">Visi Misi</a>
                        <a href="{{ route('sejarah') }}" class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-[#e92027] transition-colors">Sejarah</a>
                        <a href="{{ route('struktur') }}" class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-[#e92027] transition-colors">Struktur Organisasi</a>
                        <a href="{{ route('penghargaan') }}" class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-[#e92027] transition-colors">Penghargaan</a>
                    </div>
                </div>

                <a href="{{ route('landing') }}#fitur" class="hover:text-[#e92027] transition-colors">Fitur</a>
                <a href="{{ route('landing') }}#kontak" class="hover:text-[#e92027] transition-colors">Kontak</a>
            </div>

            <!-- Tombol Hamburger (Tampil di HP) -->
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="md:hidden p-2 text-gray-800 hover:text-[#e92027] hover:bg-gray-100 rounded-full transition-colors focus:outline-none">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" style="display:none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </nav>

        <!-- Menu Dropdown Mobile (Mengambang terpisah di bawah Kapsul) -->
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
             class="md:hidden w-full max-w-sm mt-3 bg-white/95 backdrop-blur-xl shadow-2xl border border-gray-100 rounded-3xl flex flex-col max-h-[75vh] overflow-hidden overflow-y-auto"
             style="display: none;">

             <a href="{{ route('landing') }}" class="px-6 py-4 text-gray-800 font-bold border-b border-gray-100 hover:bg-red-50 hover:text-[#e92027]">Beranda</a>

             <!-- Accordion Tentang Kami di Mobile -->
             <div x-data="{ mobileTentang: false }" class="border-b border-gray-100">
                 <button @click="mobileTentang = !mobileTentang" class="w-full px-6 py-4 text-left text-gray-800 font-bold flex justify-between items-center hover:bg-red-50" :class="mobileTentang ? 'text-[#e92027]' : ''">
                     Tentang Kami
                     <svg class="w-5 h-5 transition-transform duration-300" :class="mobileTentang ? 'rotate-180 text-[#e92027]' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                 </button>
                 <div x-show="mobileTentang"
                      x-transition
                      class="bg-gray-50/80 px-6 py-2 flex flex-col space-y-1">
                     <a href="{{ route('visi-misi') }}" class="text-sm text-gray-600 hover:text-[#e92027] py-2 pl-4 border-l-2 border-transparent hover:border-[#e92027]">Visi Misi</a>
                     <a href="{{ route('sejarah') }}" class="text-sm text-gray-600 hover:text-[#e92027] py-2 pl-4 border-l-2 border-transparent hover:border-[#e92027]">Sejarah</a>
                     <a href="{{ route('struktur') }}" class="text-sm text-gray-600 hover:text-[#e92027] py-2 pl-4 border-l-2 border-transparent hover:border-[#e92027]">Struktur Organisasi</a>
                     <a href="{{ route('penghargaan') }}" class="text-sm text-gray-600 hover:text-[#e92027] py-2 pl-4 border-l-2 border-transparent hover:border-[#e92027]">Penghargaan</a>
                 </div>
             </div>

             <a href="{{ route('landing') }}#fitur" @click="mobileMenuOpen = false" class="px-6 py-4 text-gray-800 font-bold border-b border-gray-100 hover:bg-red-50 hover:text-[#e92027]">Fitur</a>
             <a href="{{ route('landing') }}#kontak" @click="mobileMenuOpen = false" class="px-6 py-4 text-gray-800 font-bold hover:bg-red-50 hover:text-[#e92027] pb-5">Kontak</a>
        </div>
    </header>

    <!-- Hero Section -->
    <style>
        @keyframes zoomIn {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        .animate-zoom {
            animation: zoomIn 10s infinite alternate;
        }
    </style>
    <div class="relative h-[40vh] w-full overflow-hidden">
        <div class="absolute inset-0">
             <img src="{{ asset('images/LP2.JPG') }}" class="w-full h-full object-cover animate-zoom">
             <!-- Overlay Gradient -->
             <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="absolute inset-0 flex items-center">
            <div class="container mx-auto px-6 pt-20">
                <div class="max-w-2xl text-white">
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-2 drop-shadow-lg">
                        Tentang Kami
                    </h1>
                     <p class="text-xl font-light">Visi Misi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="py-20 relative bg-white bg-cover bg-center" style="background-image: url('{{ asset('images/BG_supergrafis.png') }}');">
        <div class="container mx-auto px-6 relative z-10">

            <!-- Meaning Semen Padang -->
            <div class="text-center mb-16">
                 <h2 class="text-2xl text-gray-800 font-medium mb-2">Kearsipan PT Semen Padang</h2>
                 <h3 class="text-3xl md:text-4xl font-bold text-[#e92027]">“Tepat Kelola, Tepat Saji”</h3>
            </div>

            <div class="max-w-7xl mx-auto px-6 lg:px-20">
                <div class="flex flex-col md:flex-row gap-16 items-start">
                     <!-- Images Grid -->
                     <div class="w-full md:w-1/2 grid grid-cols-2 gap-4">
                          <!-- Main Image (hp 6) -->
                          <div class="col-span-2 rounded-2xl shadow-xl overflow-hidden aspect-video relative group">
                               <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-500 z-10"></div>
                               <img src="{{ asset('images/hp 6.jpeg') }}" alt="Visi Misi Semen Padang" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                          </div>
                          <!-- Secondary Image 1 (hp 4) -->
                          <div class="rounded-2xl shadow-xl overflow-hidden aspect-video relative group">
                               <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-500 z-10"></div>
                               <img src="{{ asset('images/hp 4.jpeg') }}" alt="Kegiatan Arsip" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                          </div>
                          <!-- Secondary Image 2 (hp 5) -->
                          <div class="rounded-2xl shadow-xl overflow-hidden aspect-video relative group">
                               <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition duration-500 z-10"></div>
                               <img src="{{ asset('images/hp 5.jpeg') }}" alt="Fasilitas Arsip" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                          </div>
                     </div>

                     <!-- Text -->
                     <div class="w-full md:w-1/2">
                          <!-- Visi -->
                          <div class="mb-8">
                               <div class="flex items-center gap-3 mb-2">
                                   <div class="w-1.5 h-8 bg-[#e92027]"></div>
                                   <h2 class="text-3xl font-bold text-gray-900 tracking-wide">VISI</h2>
                               </div>
                               <p class="text-gray-700 leading-relaxed text-lg pl-4 border-l-2 border-gray-100 text-justify">
                                    "Menuju manajemen kearsipan yang <span class="font-bold text-gray-900">TEPAT KELOLA DAN TEPAT SAJI DENGAN MEMPERHATIKAN ASPEK K3</span>"
                               </p>
                          </div>

                          <!-- Misi -->
                          <div>
                               <div class="flex items-center gap-3 mb-4">
                                   <div class="w-1.5 h-8 bg-[#e92027]"></div>
                                   <h2 class="text-3xl font-bold text-gray-900 tracking-wide">MISI</h2>
                               </div>
                               <ol class="list-none space-y-3 text-gray-700 leading-relaxed text-lg text-justify">
                                   <li class="flex gap-4">
                                       <span class="font-bold text-[#e92027] text-xl">1.</span>
                                       <span>Membangun manajemen kearsipan yang <span class="font-bold text-gray-900">EFEKTIF DAN EFISIEN</span></span>
                                   </li>
                                   <li class="flex gap-3">
                                       <span class="font-bold text-[#e92027] text-xl">2.</span>
                                       <span>Peningkatan kemampuan dan <span class="font-bold text-gray-900">KOMPETENSI SDM</span> diunit kearsipan serta koordinator anggota tu- ukp unit kerja</span>
                                   </li>
                                   <li class="flex gap-3">
                                       <span class="font-bold text-[#e92027] text-xl">3.</span>
                                       <span>Meningkatkan efesiensi dan efektifitas <span class="font-bold text-gray-900">PELAYANAN</span></span>
                                   </li>
                                   <li class="flex gap-3">
                                       <span class="font-bold text-[#e92027] text-xl">4.</span>
                                       <span>Menjadikan arsip sebagai memori kolektif dan jati diri perusahaan</span>
                                   </li>
                               </ol>
                          </div>
                     </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Kami Navigation -->
    <section class="py-16 relative overflow-hidden">
        <!-- Background with subtle pattern -->
        <div class="absolute inset-0 bg-gray-50"></div>
        <div class="absolute inset-0 opacity-5" style="background-image: url('{{ asset('images/BG_supergrafis.png') }}'); background-size: cover; background-position: center;"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Jelajahi Tentang Kami</h2>
                    <div class="h-1 w-20 bg-[#e92027] rounded-full"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- 1. Visi Misi -->
                <a href="{{ route('visi-misi') }}" class="group bg-white p-8 rounded-xl shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border-l-4 border-transparent hover:border-[#e92027] flex flex-col justify-between h-48">
                    <div>
                         <span class="text-sm font-semibold text-gray-400 group-hover:text-[#e92027] transition mb-2 block">01</span>
                         <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#e92027] transition">Visi Misi</h3>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-gray-500 font-medium group-hover:text-red-500 transition">Lihat Detail</span>
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-[#e92027] group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- 2. Sejarah -->
                <a href="{{ route('sejarah') }}" class="group bg-white p-8 rounded-xl shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border-l-4 border-transparent hover:border-[#e92027] flex flex-col justify-between h-48">
                    <div>
                         <span class="text-sm font-semibold text-gray-400 group-hover:text-[#e92027] transition mb-2 block">02</span>
                         <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#e92027] transition">Sejarah</h3>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-gray-500 font-medium group-hover:text-red-500 transition">Lihat Detail</span>
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-[#e92027] group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- 3. Struktur Organisasi -->
                <a href="{{ route('struktur') }}" class="group bg-white p-8 rounded-xl shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border-l-4 border-transparent hover:border-[#e92027] flex flex-col justify-between h-48">
                    <div>
                         <span class="text-sm font-semibold text-gray-400 group-hover:text-[#e92027] transition mb-2 block">03</span>
                         <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#e92027] transition">Struktur Organisasi</h3>
                    </div>
                     <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-gray-500 font-medium group-hover:text-red-500 transition">Lihat Detail</span>
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-[#e92027] group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </a>

                <!-- 4. Penghargaan -->
                <a href="{{ route('penghargaan') }}" class="group bg-white p-8 rounded-xl shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border-l-4 border-transparent hover:border-[#e92027] flex flex-col justify-between h-48">
                    <div>
                         <span class="text-sm font-semibold text-gray-400 group-hover:text-[#e92027] transition mb-2 block">04</span>
                         <h3 class="text-xl font-bold text-gray-800 group-hover:text-[#e92027] transition">Penghargaan</h3>
                    </div>
                     <div class="flex justify-between items-center mt-4">
                        <span class="text-xs text-gray-500 font-medium group-hover:text-red-500 transition">Lihat Detail</span>
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-[#e92027] group-hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="relative bg-cover bg-center border-t border-gray-200" style="background-image: url('{{ asset('images/SuperGrafis.png') }}');">
        <!-- Main Footer Content with Pattern -->
        <div class="pt-12 pb-8">
            <div class="container mx-auto px-12 md:px-24">
                <!-- Top Section: 3 Columns -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Column 1: Kantor Utama -->
                    <div class="text-left">
                        <h4 class="font-bold text-gray-900 text-lg mb-3">Record Center</h4>
                        <p class="text-gray-600 text-sm leading-relaxed max-w-xs">
                            Jl. Raya Indarung, Kec. Lubuk Kilangan<br>
                            Kota Padang 25237, Sumatera Barat
                        </p>

                        <!-- Google Maps Embed -->
                        <div class="mt-4 rounded-xl overflow-hidden shadow-lg border border-gray-100 w-full max-w-xs h-48">
                            <iframe
                                width="100%"
                                height="100%"
                                frameborder="0"
                                scrolling="no"
                                marginheight="0"
                                marginwidth="0"
                                src="https://maps.google.com/maps?q=3F2F%2B6Q%20Indarung%2C%20Kota%20Padang%2C%20Sumatera%20Barat%2C%20Indonesia&t=&z=15&ie=UTF8&iwloc=&output=embed">
                            </iframe>
                        </div>
                    </div>

                    <!-- Column 3: Media Sosial & SIG Group -->
                    <div class="text-right flex flex-col justify-end h-full">
                        <h4 class="font-bold text-gray-900 text-lg mb-3">Media Sosial</h4>
                        <div class="flex justify-end gap-4 mb-6">
                            <a href="https://twitter.com/semenpadang1910" target="_blank" class="w-8 h-8 link-hover"><svg class="w-5 h-5 text-gray-600 hover:text-black transition" fill="currentColor" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg></a>
                            <a href="https://www.instagram.com/semenpadang/" target="_blank" class="w-8 h-8 link-hover"><svg class="w-5 h-5 text-gray-600 hover:text-[#e92027] transition" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                            <a href="https://www.youtube.com/channel/UCIi9Yy9jRMlB8k9_8djAJcA/feed" target="_blank" class="w-8 h-8 link-hover"><svg class="w-5 h-5 text-gray-600 hover:text-[#e92027] transition" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                            <a href="https://www.tiktok.com/@semenpadang1910?_t=8hadknUhwFF&_r=1" target="_blank" class="w-8 h-8 link-hover"><svg class="w-5 h-5 text-gray-600 hover:text-[#e92027] transition" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.65-1.62-1.1-.04 1.86.04 3.66.17 5.51.18 2.58-.62 5.13-2.4 7.29-1.42 1.75-3.64 2.7-5.99 2.7-3.36.03-6.54-1.74-8.19-4.57-1.74-3.08-1.55-7.06.63-9.92.51-.7 1.12-1.32 1.83-1.83 1.96-1.43 4.54-1.85 6.93-1.25.1.58.21 1.17.32 1.76-1.09-.37-2.29-.44-3.41-.09-1.13.34-2.11 1.05-2.73 2.05-.66 1.06-.82 2.37-.58 3.6.43 2.21 2.4 4.02 4.63 4.1 1.23.07 2.45-.31 3.42-1.1 1.08-.85 1.66-2.26 1.58-3.62-.06-2.58-.02-5.16-.01-7.74-.01-.98-.02-1.95-.03-2.93-.01-.65-.01-1.31-.02-1.96H12.525z"/></svg></a>
                            <a href="https://web.facebook.com/PTsemenpadang1910/" target="_blank" class="w-8 h-8 link-hover"><svg class="w-5 h-5 text-gray-600 hover:text-[#e92027] transition" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                        </div>

                        <h4 class="font-bold text-gray-900 text-lg mb-2">Record Center</h4>
                        <p class="text-gray-600 hover:text-[#e92027] transition font-medium">arsipsp@sig.id</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Red Bar -->
        <div class="bg-[#e92027] h-14 w-full relative">
            <div class="container mx-auto px-6 h-full flex justify-end items-center">
            </div>
        </div>

        <!-- Copyright -->
        <div class="py-4">
            <div class="container mx-auto px-6 text-center">
                  <p class="text-gray-800 text-sm font-medium">Record Center PT Semen Padang &copy; Copyright {{ date('Y') }}.</p>
            </div>
        </div>
    </footer>

</body>
</html>
