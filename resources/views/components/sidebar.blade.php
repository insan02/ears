<style>
    @media (min-width: 1024px) {
        button[\@click*="sidebarOpen"]:not(.btn-panah-desktop),
        button[x-on\:click*="sidebarOpen"]:not(.btn-panah-desktop) {
            display: none !important;
        }
    }
</style>

<!-- ========================================== -->
<!-- 1. DESKTOP SIDEBAR                         -->
<!-- ========================================== -->
<aside class="hidden lg:flex bg-white border-r border-gray-200 flex-col transition-all duration-300 ease-in-out relative z-40 h-full"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    <button @click="sidebarOpen = !sidebarOpen"
            class="btn-panah-desktop absolute -right-3.5 top-4 bg-white border border-gray-200 text-gray-500 hover:text-[#e92027] hover:bg-red-50 rounded-full w-7 h-7 flex items-center justify-center shadow-md z-50 transition-transform duration-300 focus:outline-none"
            :class="!sidebarOpen ? 'rotate-180' : ''" title="Toggle Sidebar">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
    </button>

    <div class="border-b border-gray-100 flex items-center h-14 shrink-0 transition-all duration-300 overflow-hidden px-4"
         :class="sidebarOpen ? 'justify-start' : 'justify-center'">
        <a href="{{ route('landing') }}" class="flex items-center hover:opacity-80 transition w-full" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
            <img src="{{ asset('images/logo-semen-padang.png') }}" alt="Logo" class="transition-all duration-300 shrink-0" :class="sidebarOpen ? 'h-7 w-auto' : 'h-7 w-7 object-contain'">
            <span class="font-bold text-gray-800 text-sm whitespace-nowrap overflow-hidden transition-all duration-300 ease-in-out"
                  :class="sidebarOpen ? 'max-w-[100px] opacity-100 ml-2' : 'max-w-0 opacity-0 ml-0'">E-Arsip</span>
        </a>
    </div>

    <div class="py-2.5 text-center shrink-0 transition-all duration-300 overflow-hidden" :class="sidebarOpen ? 'px-6' : 'px-2'">
        <a href="{{ route('profile.edit') }}" class="block group flex flex-col items-center">
            <div class="mx-auto bg-red-50 rounded-full flex items-center justify-center text-[#e92027] border-2 border-[#e92027] overflow-hidden group-hover:border-[#c41820] shadow-sm transition-all duration-300 shrink-0"
                 :class="sidebarOpen ? 'w-10 h-10 mb-1' : 'w-8 h-8 mb-0'">
                @if(Auth::user()->photo)
                    <img src="{{ asset(Auth::user()->photo) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                    <span class="font-bold" :class="sidebarOpen ? 'text-sm' : 'text-xs'">{{ substr(Auth::user()->nama, 0, 1) }}</span>
                @endif
            </div>
            <div class="w-full overflow-hidden transition-all duration-300 ease-in-out"
                 :class="sidebarOpen ? 'max-h-12 opacity-100' : 'max-h-0 opacity-0'">
                <h3 class="font-bold text-[#e92027] text-sm truncate px-2">{{ Auth::user()->nama ?? 'User' }}</h3>
                <p class="text-[10px] text-gray-500 truncate px-2">{{ Auth::user()->email ?? 'email@example.com' }}</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 space-y-1 overflow-hidden pb-2 px-3 flex flex-col justify-center">
        @php
            $menus = [
                ['url' => '/beranda', 'label' => 'Beranda', 'active' => Request::is('beranda'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['url' => route('arsip-masuk.index'), 'label' => 'Arsip Masuk', 'active' => Request::is('arsip-masuk*'), 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 11-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2'],
                ['url' => '/arsip', 'label' => 'Arsip', 'active' => Request::is('arsip') && !Request::is('arsip/musnah'), 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                ['url' => '/peminjaman', 'label' => 'Peminjaman', 'active' => Request::is('peminjaman*'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['url' => '/monitoring', 'label' => 'Monitoring', 'active' => Request::is('monitoring*'), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ['url' => route('limap.index'), 'label' => 'Informasi 5P', 'active' => Request::is('5p*'), 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z']
            ];
        @endphp

        @foreach($menus as $menu)
        <a href="{{ $menu['url'] }}" class="flex items-center py-1.5 px-3 text-sm font-medium {{ $menu['active'] ? 'bg-[#e92027] text-white shadow-sm' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-lg transition-all duration-300 overflow-hidden"
           :class="sidebarOpen ? 'justify-start' : 'justify-center'">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}" /></svg>
            <span class="whitespace-nowrap overflow-hidden transition-all duration-300 ease-in-out"
                  :class="sidebarOpen ? 'max-w-[200px] opacity-100 ml-3' : 'max-w-0 opacity-0 ml-0'">{{ $menu['label'] }}</span>
        </a>
        @endforeach

        @if(Auth::check() && Auth::user()->role == 'admin')
            <div class="pt-2 pb-1 flex items-center h-6">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap overflow-hidden transition-all duration-300"
                   :class="sidebarOpen ? 'max-w-[200px] opacity-100 px-2' : 'max-w-0 opacity-0 px-0'">Administrator</p>
                <div class="w-full h-px bg-gray-200 transition-all duration-300 mx-3" :class="sidebarOpen ? 'hidden' : 'block'"></div>
            </div>

            @php
                $adminMenus = [
                    ['url' => route('management-akun.index'), 'label' => 'Akun', 'active' => Request::is('management-akun*'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['url' => route('manajemen-unit.index'), 'label' => 'Unit', 'active' => Request::is('manajemen-unit*'), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['url' => route('manajemen-media.index'), 'label' => 'Media', 'active' => Request::is('manajemen-media*'), 'icon' => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z'],
                    ['url' => route('arsip.musnah'), 'label' => 'Data Musnah', 'active' => Request::is('arsip/musnah'), 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16']
                ];
            @endphp

            @foreach($adminMenus as $menu)
            <a href="{{ $menu['url'] }}" class="flex items-center py-1.5 px-3 text-sm font-medium {{ $menu['active'] ? 'bg-[#e92027] text-white shadow-sm' : 'text-gray-600 hover:bg-red-50 hover:text-[#e92027]' }} rounded-lg transition-all duration-300 overflow-hidden"
               :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}" /></svg>
                <span class="whitespace-nowrap overflow-hidden transition-all duration-300 ease-in-out"
                      :class="sidebarOpen ? 'max-w-[200px] opacity-100 ml-3' : 'max-w-0 opacity-0 ml-0'">{{ $menu['label'] }}</span>
            </a>
            @endforeach
        @endif
    </nav>

    <div class="p-2 border-t border-gray-100 shrink-0 transition-all duration-300 overflow-hidden">
        <button type="button" @click="showLogoutModal = true" class="w-full flex items-center py-2 text-sm text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition font-bold"
                :class="sidebarOpen ? 'justify-start px-3' : 'justify-center px-0'">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            <span class="whitespace-nowrap overflow-hidden transition-all duration-300 ease-in-out"
                  :class="sidebarOpen ? 'max-w-[150px] opacity-100 ml-3' : 'max-w-0 opacity-0 ml-0'">Logout</span>
        </button>
    </div>
</aside>

<!-- ========================================== -->
<!-- 2. MOBILE BOTTOM NAV & MORE MENU           -->
<!-- ========================================== -->
<div x-data="{ showMobileMore: false }" class="lg:hidden">

    <!-- TAMPILAN FIXED BAWAH: Berlaku Untuk SEMUA USER -->
    <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 z-[100] shadow-[0_-4px_10px_rgba(0,0,0,0.05)] pb-safe">
        <div class="flex justify-between items-center px-2 py-1.5">
            <!-- Beranda -->
            <a href="/beranda" class="flex-1 flex flex-col items-center justify-center {{ Request::is('beranda') ? 'text-[#e92027]' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::is('beranda') ? '2.5' : '2' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span class="text-[9px] sm:text-[10px] font-bold tracking-tight truncate w-full text-center">Beranda</span>
            </a>

            <!-- Arsip Masuk -->
            <a href="{{ route('arsip-masuk.index') }}" class="flex-1 flex flex-col items-center justify-center {{ Request::is('arsip-masuk*') ? 'text-[#e92027]' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::is('arsip-masuk*') ? '2.5' : '2' }}" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 11-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                <span class="text-[9px] sm:text-[10px] font-bold tracking-tight truncate w-full text-center">Masuk</span>
            </a>

            <!-- Arsip Biasa -->
            <a href="/arsip" class="flex-1 flex flex-col items-center justify-center {{ Request::is('arsip') && !Request::is('arsip/musnah') ? 'text-[#e92027]' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::is('arsip') && !Request::is('arsip/musnah') ? '2.5' : '2' }}" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <span class="text-[9px] sm:text-[10px] font-bold tracking-tight truncate w-full text-center">Arsip</span>
            </a>

            <!-- Pinjam -->
            <a href="/peminjaman" class="flex-1 flex flex-col items-center justify-center {{ Request::is('peminjaman*') ? 'text-[#e92027]' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::is('peminjaman*') ? '2.5' : '2' }}" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                <span class="text-[9px] sm:text-[10px] font-bold tracking-tight truncate w-full text-center">Pinjam</span>
            </a>

            <!-- Monitor -->
            <a href="/monitoring" class="flex-1 flex flex-col items-center justify-center {{ Request::is('monitoring*') ? 'text-[#e92027]' : 'text-gray-400 hover:text-gray-600' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ Request::is('monitoring*') ? '2.5' : '2' }}" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <span class="text-[9px] sm:text-[10px] font-bold tracking-tight truncate w-full text-center">Monitor</span>
            </a>

            <!-- Lainnya -->
            <button @click="showMobileMore = true" class="flex-1 flex flex-col items-center justify-center text-gray-400 hover:text-[#e92027] focus:outline-none">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                <span class="text-[9px] sm:text-[10px] font-bold tracking-tight truncate w-full text-center">Lainnya</span>
            </button>
        </div>
    </nav>

    <!-- BOTTOM SHEET: Menu Tambahan -->
    <div x-show="showMobileMore" style="display: none;" class="fixed inset-0 z-[110] flex flex-col justify-end">

        <div x-show="showMobileMore" x-transition.opacity @click="showMobileMore = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div x-show="showMobileMore"
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
             class="relative bg-gray-50 rounded-t-[2rem] w-full max-h-[85vh] flex flex-col overflow-hidden pb-safe shadow-2xl">

            <div class="bg-white px-6 py-4 flex justify-between items-center rounded-t-[2rem] shadow-sm z-10">
                <h3 class="font-bold text-gray-800 text-lg">Menu Lainnya</h3>
                <button @click="showMobileMore = false" class="bg-gray-100 p-2 rounded-full text-gray-500 hover:text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-4 overflow-y-auto custom-scrollbar space-y-6">

                <!-- Grup Admin -->
                @if(Auth::check() && Auth::user()->role == 'admin')
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">Administrator</h4>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <a href="{{ route('management-akun.index') }}" class="flex items-center gap-4 px-4 py-3.5 hover:bg-red-50 border-b border-gray-50">
                            <div class="bg-purple-50 text-purple-600 p-2 rounded-lg"><i class="fas fa-users-cog w-5 text-center text-lg"></i></div>
                            <span class="font-semibold text-gray-700 text-sm">Manajemen Akun</span>
                        </a>
                        <a href="{{ route('manajemen-unit.index') }}" class="flex items-center gap-4 px-4 py-3.5 hover:bg-red-50 border-b border-gray-50">
                            <div class="bg-amber-50 text-amber-600 p-2 rounded-lg"><i class="fas fa-building w-5 text-center text-lg"></i></div>
                            <span class="font-semibold text-gray-700 text-sm">Manajemen Unit</span>
                        </a>
                        <a href="{{ route('manajemen-media.index') }}" class="flex items-center gap-4 px-4 py-3.5 hover:bg-red-50 border-b border-gray-50">
                            <div class="bg-emerald-50 text-emerald-600 p-2 rounded-lg"><i class="fas fa-folder-open w-5 text-center text-lg"></i></div>
                            <span class="font-semibold text-gray-700 text-sm">Manajemen Media</span>
                        </a>
                        <a href="{{ route('arsip.musnah') }}" class="flex items-center gap-4 px-4 py-3.5 hover:bg-red-50 border-b border-gray-50">
                            <div class="bg-red-50 text-red-600 p-2 rounded-lg"><i class="fas fa-trash-alt w-5 text-center text-lg"></i></div>
                            <span class="font-semibold text-gray-700 text-sm">Data Arsip Musnah</span>
                        </a>
                        <a href="{{ route('limap.index') }}" class="flex items-center gap-4 px-4 py-3.5 hover:bg-red-50">
                            <div class="bg-blue-50 text-blue-600 p-2 rounded-lg"><i class="fas fa-flask w-5 text-center text-lg"></i></div>
                            <span class="font-semibold text-gray-700 text-sm">Informasi 5P</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Grup Pengaturan -->
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-2">Pengaturan</h4>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 px-4 py-3.5 hover:bg-gray-50 border-b border-gray-50">
                            @if(Auth::user()->photo)
                                <img src="{{ asset(Auth::user()->photo) }}" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            @else
                                <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-gray-500"><i class="fas fa-user"></i></div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">Edit Profil Saya</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </a>

                        <button type="button" @click="showLogoutModal = true; showMobileMore = false" class="w-full flex items-center gap-4 px-4 py-3.5 text-red-600 hover:bg-red-50 transition">
                            <div class="p-2"><i class="fas fa-sign-out-alt text-lg"></i></div>
                            <span class="font-bold text-sm">Keluar (Logout)</span>
                        </button>
                    </div>
                </div>

                <div class="h-6"></div>
            </div>
        </div>
    </div>
</div>
