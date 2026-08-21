<!-- CSS untuk otomatis menyembunyikan tombol menu garis tiga di luar sidebar pada versi Desktop -->
<style>
    @media (min-width: 1024px) {
        /* Mencari tombol di luar sidebar yang memiliki fungsi klik sidebarOpen dan menyembunyikannya */
        button[\@click*="sidebarOpen"]:not(.btn-panah-desktop),
        button[x-on\:click*="sidebarOpen"]:not(.btn-panah-desktop) {
            display: none !important;
        }
    }
</style>

<aside class="bg-white border-r border-gray-200 flex flex-col transition-all duration-300 ease-in-out fixed lg:relative z-40 h-full shadow-2xl lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64 lg:translate-x-0 lg:w-20'">

    <!-- Tombol Panah (Khusus Desktop Saja, di Mobile Hilang) -->
    <button @click="sidebarOpen = !sidebarOpen"
            class="btn-panah-desktop hidden lg:flex absolute -right-3.5 top-5 bg-white border border-gray-200 text-gray-500 hover:text-[#e92027] hover:bg-red-50 rounded-full w-7 h-7 items-center justify-center shadow-md z-50 transition-transform duration-300 focus:outline-none"
            :class="!sidebarOpen ? 'rotate-180' : ''"
            title="Toggle Sidebar">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    </button>

    <!-- Logo & Close Button (Mobile) -->
    <div class="border-b border-gray-100 flex items-center h-16 shrink-0 transition-all duration-300 overflow-hidden"
         :class="sidebarOpen ? 'justify-between px-4' : 'justify-center px-2'">
        <!-- Logo -->
        <a href="{{ route('landing') }}" class="flex items-center gap-2 hover:opacity-80 transition" title="Kembali ke Landing Page">
            <img src="{{ asset('images/logo-semen-padang.png') }}" alt="Logo PT Semen Padang" class="transition-all duration-300" :class="sidebarOpen ? 'h-8 w-auto' : 'h-8 w-8 object-contain'">
            <span x-show="sidebarOpen" class="font-bold text-gray-800 text-sm hidden sm:block whitespace-nowrap">E-Arsip PT Semen Padang</span>
        </a>

        <!-- Tombol Silang (Khusus Mobile Saja, di Desktop Hilang) -->
        <button @click="sidebarOpen = false" class="text-gray-400 hover:text-red-700 bg-gray-50 hover:bg-red-50 p-1.5 rounded-lg transition focus:outline-none lg:hidden" x-show="sidebarOpen">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Profile Area -->
    <div class="py-4 text-center shrink-0 transition-all duration-300 overflow-hidden" :class="sidebarOpen ? 'px-6' : 'px-2'">
        <a href="{{ route('profile.edit') }}" class="block group cursor-pointer flex flex-col items-center">
            <div class="mx-auto bg-red-50 rounded-full flex items-center justify-center text-[#e92027] mb-2 border-2 border-[#e92027] overflow-hidden group-hover:border-[#c41820] shadow-sm transition-all duration-300"
                 :class="sidebarOpen ? 'w-14 h-14' : 'w-10 h-10'">
                @if(Auth::user()->photo)
                    <img src="{{ asset(Auth::user()->photo) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-full h-full p-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                @endif
            </div>
            <div x-show="sidebarOpen" class="w-full">
                <h3 class="font-bold text-[#e92027] text-sm group-hover:text-[#b91c1c] transition truncate px-2 leading-tight">
                    {{ Auth::user()->nama ?? 'User' }}
                </h3>
                <p class="text-[11px] text-gray-500 mb-0.5 truncate px-2 leading-tight">{{ Auth::user()->email ?? 'email@example.com' }}</p>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 space-y-2 overflow-y-auto overflow-x-hidden pb-2 custom-scrollbar transition-all duration-300" :class="sidebarOpen ? 'px-4' : 'px-2'">
        <!-- BERANDA -->
        <a href="/beranda" title="Beranda" class="flex items-center py-2 text-sm font-medium {{ Request::is('beranda') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
           :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            <span x-show="sidebarOpen" class="whitespace-nowrap">Beranda</span>
        </a>

        <!-- ARSIP MASUK -->
        <a href="{{ route('arsip-masuk.index') }}" title="Arsip Masuk" class="flex items-center py-2 text-sm font-medium {{ Request::is('arsip-masuk*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
           :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 11-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
            <span x-show="sidebarOpen" class="whitespace-nowrap">Arsip Masuk</span>
        </a>

        <!-- ARSIP -->
        <a href="/arsip" title="Arsip" class="flex items-center py-2 text-sm font-medium {{ Request::is('arsip') && !Request::is('arsip/musnah') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
           :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <span x-show="sidebarOpen" class="whitespace-nowrap">Arsip</span>
        </a>

        <!-- PEMINJAMAN -->
        <a href="/peminjaman" title="Peminjaman" class="flex items-center py-2 text-sm font-medium {{ Request::is('peminjaman*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
           :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            <span x-show="sidebarOpen" class="whitespace-nowrap">Peminjaman</span>
        </a>

        <!-- MONITORING -->
        <a href="/monitoring" title="Monitor Karyawan" class="flex items-center py-2 text-sm font-medium {{ Request::is('monitoring*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
           :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            <span x-show="sidebarOpen" class="whitespace-nowrap">Monitor Karyawan</span>
        </a>

        @if(Auth::check() && Auth::user()->role == 'admin')
            <div x-show="sidebarOpen" class="pt-4 pb-1"><p class="px-4 text-[9px] font-bold tracking-wider text-gray-400 uppercase">Administrator</p></div>
            <div x-show="!sidebarOpen" class="pt-4 pb-1 flex justify-center"><div class="w-6 h-px bg-gray-300"></div></div>

            <!-- MANAJEMEN AKUN -->
            <a href="{{ route('management-akun.index') }}" title="Manajemen Akun" class="flex items-center py-2 text-sm font-medium {{ Request::is('management-akun*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
               :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Akun</span>
            </a>

            <!-- MANAJEMEN UNIT -->
            <a href="{{ route('manajemen-unit.index') }}" title="Manajemen Unit" class="flex items-center py-2 text-sm font-medium {{ Request::is('manajemen-unit*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
               :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Unit</span>
            </a>

            <!-- MANAJEMEN MEDIA -->
            <a href="{{ route('manajemen-media.index') }}" title="Manajemen Media" class="flex items-center py-2 text-sm font-medium {{ Request::is('manajemen-media*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
               :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Manajemen Media</span>
            </a>

            <!-- DATA MUSNAH -->
            <a href="{{ route('arsip.musnah') }}" title="Data Musnah" class="flex items-center py-2 text-sm font-medium {{ Request::is('arsip/musnah') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition-all duration-300"
               :class="sidebarOpen ? 'gap-3 px-4' : 'justify-center px-0'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Data Musnah</span>
            </a>
        @endif
    </nav>

    <!-- Logout -->
    <div class="py-3 border-t border-gray-100 shrink-0 transition-all duration-300 overflow-hidden" :class="sidebarOpen ? 'px-6' : 'px-2'">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" title="Logout" class="w-full flex items-center py-2.5 text-sm text-red-700 bg-red-50 hover:bg-red-100 hover:text-red-800 rounded-xl transition font-bold shadow-sm"
                    :class="sidebarOpen ? 'justify-center gap-2 px-4' : 'justify-center px-0'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Logout</span>
            </button>
        </form>
    </div>
</aside>
