<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semen Padang Arsip</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    <!-- Deteksi layar: Di Desktop otomatis terbuka, di HP otomatis tertutup -->
    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
         @resize.window="sidebarOpen = window.innerWidth >= 1024"
         class="flex h-screen overflow-hidden relative w-full">

        @auth
        <!-- Overlay gelap untuk HP saat sidebar terbuka -->
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-30 lg:hidden" style="display: none;"></div>

        <!-- Sidebar Component -->
        <x-sidebar />
        @endauth

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300 relative w-full">

            @auth
            <!-- MOBILE HEADER (Hanya tampil di HP) -->
            <div class="lg:hidden bg-white shadow-sm px-4 py-3 flex items-center justify-between border-b border-gray-200 z-20">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-semen-padang.png') }}" alt="Logo" class="h-8 w-auto">
                    <span class="font-bold text-gray-800 text-sm">Record Center</span>
                </div>
                <button @click="sidebarOpen = true" class="p-2 text-gray-600 hover:text-[#e92027] focus:outline-none rounded-lg hover:bg-red-50 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- DESKTOP OPEN BUTTON (Tampil di laptop jika sidebar disembunyikan) -->
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

            <!-- Padding disesuaikan p-4 untuk HP, p-6 untuk Laptop -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 md:p-6 w-full">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>
