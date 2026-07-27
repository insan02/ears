<!-- Perbaikan Class Transform agar responsif di HP (muncul dari kiri) dan Desktop (mendorong konten) -->
<aside class="bg-white border-r border-gray-200 flex flex-col transition-all duration-300 ease-in-out fixed lg:relative z-40 h-full shadow-2xl lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64 lg:-ml-64 lg:-translate-x-full'">

    <!-- Logo & Close Button -->
    <div class="p-4 border-b border-gray-100 flex justify-between items-center h-16 shrink-0">
        <a href="{{ route('landing') }}" class="flex items-center gap-2 hover:opacity-80 transition" title="Kembali ke Landing Page">
            <img src="{{ asset('images/logo-semen-padang.png') }}" alt="Logo PT Semen Padang" class="h-8 w-auto">
            <span class="font-bold text-gray-800 text-sm hidden sm:block">PT SEMEN PADANG</span>
        </a>

        <!-- Close Sidebar Button (Disembunyikan di Desktop) -->
        <button @click="sidebarOpen = false" class="text-gray-400 hover:text-red-700 bg-gray-50 hover:bg-red-50 p-1.5 rounded-lg transition focus:outline-none lg:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    <!-- Profile Area -->
    <div class="px-6 py-4 text-center shrink-0">
        <a href="{{ route('profile.edit') }}" class="block group cursor-pointer">
            <div class="w-14 h-14 mx-auto bg-red-50 rounded-full flex items-center justify-center text-[#e92027] mb-2 border-2 border-[#e92027] overflow-hidden group-hover:border-[#c41820] shadow-sm transition">
                @if(Auth::user()->photo)
                    <img src="{{ asset(Auth::user()->photo) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                @endif
            </div>
            <h3 class="font-bold text-[#e92027] text-sm group-hover:text-[#b91c1c] transition truncate px-2 leading-tight">
                {{ Auth::user()->nama ?? 'User' }}
            </h3>
            <p class="text-[11px] text-gray-500 mb-0.5 truncate px-2 leading-tight">{{ Auth::user()->email ?? 'email@example.com' }}</p>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto pb-2 custom-scrollbar">

        <!-- BERANDA -->
        <a href="/beranda" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('beranda') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            <span>Beranda</span>
        </a>

        <!-- ARSIP MASUK -->
        <a href="{{ route('arsip-masuk.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('arsip-masuk*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
            <span>Arsip Masuk</span>
        </a>

        <!-- ARSIP -->
        <a href="/arsip" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('arsip') && !Request::is('arsip/musnah') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <span>Arsip</span>
        </a>

        <!-- PEMINJAMAN -->
        <a href="/peminjaman" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('peminjaman*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            <span>Peminjaman</span>
        </a>

        <!-- MONITORING -->
        <a href="/monitoring" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('monitoring*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            <span>Monitor Karyawan</span>
        </a>

        @if(Auth::check() && Auth::user()->role == 'admin')
            <div class="pt-2 pb-1"><p class="px-4 text-[9px] font-bold tracking-wider text-gray-400 uppercase">Administrator</p></div>

            <a href="{{ route('management-akun.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('management-akun*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Manajemen Akun</span>
            </a>

            <a href="{{ route('manajemen-unit.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('manajemen-unit*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span>Manajemen Unit</span>
            </a>

            <a href="{{ route('manajemen-media.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('manajemen-media*') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                <span>Manajemen Media</span>
            </a>

            <a href="{{ route('arsip.musnah') }}" class="flex items-center gap-3 px-4 py-2 text-sm font-medium {{ Request::is('arsip/musnah') ? 'bg-[#e92027] text-white shadow-md' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                <span>Data Musnah</span>
            </a>
        @endif
    </nav>

    <!-- Logout -->
    <div class="px-6 py-3 border-t border-gray-100 shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex justify-center items-center gap-2 px-4 py-2.5 text-sm text-red-700 bg-red-50 hover:bg-red-100 hover:text-red-800 rounded-xl transition font-bold shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
