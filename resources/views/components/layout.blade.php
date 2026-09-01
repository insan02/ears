<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Arsip</title>
    <link rel="icon" href="{{ asset('images/logosp.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
    /* CSS Animasi Loading HTMX */
    .htmx-indicator {
        opacity: 0;
        visibility: hidden;
        transition: opacity 200ms ease-in-out, visibility 200ms ease-in-out;
        pointer-events: none;
    }
    /* Saat loading aktif */
    .htmx-request .htmx-indicator {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
    .htmx-request.htmx-indicator {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }
</style>
</head>

<!-- Tambahkan hx-indicator untuk menunjuk ke ID layar loading -->
<body class="bg-gray-50 font-sans text-gray-800" hx-boost="true" hx-indicator="#layar-loading">

    <!-- ========================================== -->
    <!-- LAYAR LOADING FULL SCREEN (Animasi Spinner)-->
    <!-- ========================================== -->
    <div id="layar-loading" class="htmx-indicator fixed inset-0 z-[9999] flex items-center justify-center bg-white/60 backdrop-blur-sm">
        <div class="bg-white px-8 py-6 rounded-3xl shadow-2xl flex flex-col items-center gap-4 border border-red-100 transform scale-105">
            <!-- Ikon Berputar -->
            <i class="fas fa-circle-notch fa-spin text-5xl text-[#e92027]"></i>
            <!-- Teks Animasi -->
            <div class="text-sm font-extrabold text-gray-700 tracking-widest uppercase animate-pulse">Memuat...</div>
        </div>
    </div>
    <!-- ========================================== -->

    <div x-data="{ sidebarOpen: window.innerWidth >= 1024, showLogoutModal: false }"
         @resize.window="sidebarOpen = window.innerWidth >= 1024"
         class="flex h-screen overflow-hidden relative w-full">

        @auth
        <!-- Overlay Sidebar Desktop -->
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-30 lg:hidden" style="display: none;"></div>

        <x-sidebar />
        @endauth

        <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300 relative w-full" id="main-content">

            @auth
            <!-- MOBILE HEADER -->
            <div class="lg:hidden bg-white shadow-sm px-4 py-3 flex items-center justify-center border-b border-gray-200 z-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-semen-padang.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="font-bold text-gray-800 text-sm">E-Arsip PT Semen Padang</span>
                </div>
            </div>

            <!-- DESKTOP OPEN BUTTON -->
            <button x-show="!sidebarOpen"
                    @click="sidebarOpen = true"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-x-4"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="hidden lg:block absolute top-6 left-6 z-20 p-2.5 bg-white shadow-md rounded-xl text-gray-600 hover:text-[#e92027] hover:shadow-lg transition focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            @endauth

            <main class="flex-1 overflow-y-auto pb-20 lg:pb-0 relative">
                {{ $slot }}
            </main>
        </div>

        <!-- MODAL KONFIRMASI LOGOUT -->
        <div x-show="showLogoutModal" style="display: none;" class="fixed inset-0 z-[200] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div x-show="showLogoutModal"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="showLogoutModal = false"
                 class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 relative z-10">
                <div x-show="showLogoutModal"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-sm">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start flex-col items-center sm:flex-row text-center sm:text-left">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-sign-out-alt text-red-600"></i>
                            </div>
                            <div class="mt-3 sm:ml-4 sm:mt-0">
                                <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Konfirmasi Keluar</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin keluar dari aplikasi? Sesi Anda akan diakhiri.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 flex flex-col sm:flex-row-reverse sm:px-6 gap-2">
                        <form action="{{ route('logout') }}" method="POST" class="m-0 w-full sm:w-auto" hx-disable>
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                                Ya, Keluar
                            </button>
                        </form>
                        <button type="button" @click="showLogoutModal = false" class="w-full inline-flex justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:w-auto transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
