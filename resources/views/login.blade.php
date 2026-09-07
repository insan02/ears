<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Arsip PT Semen Padang</title>
    <link rel="icon" href="{{ asset('images/logosp.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google Fonts: Montserrat --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }
        /* Animasi Kustom untuk Floating Label yang Solid */
        .form-floating input:focus ~ label,
        .form-floating input:not(:placeholder-shown) ~ label {
            transform: scale(0.85) translateY(-2.5rem);
            color: #e92027;
            background-color: white;
            padding: 0 0.5rem;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased text-gray-800">

    <div class="min-h-screen flex w-full relative">

        <!-- ========================================== -->
        <!-- KOLOM KIRI: BRANDING (Hanya Tampil di PC)  -->
        <!-- ========================================== -->
        <div class="hidden lg:flex w-1/2 bg-linear-to-br from-[#c41820] to-[#7f090b] relative flex-col justify-between p-16 overflow-hidden shadow-2xl z-10">

            <!-- Pattern Geometris -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <!-- Modern Blur Effects -->
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-white opacity-5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-black opacity-20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Konten Atas Kiri -->
            <div class="relative z-10">
                <img src="{{ asset('images/sp-white.png') }}" alt="Logo Semen Padang" class="h-12 drop-shadow-md mb-12">
            </div>

            <!-- Konten Tengah Kiri -->
            <div class="relative z-10 w-full pr-4">
                <h1 class="text-3xl lg:text-3xl xl:text-4xl font-extrabold text-white mb-5 leading-tight drop-shadow-md tracking-tight">
                    <span class="block">Sistem Informasi Record Center</span>
                    <span class="block">PT Semen Padang</span>
                </h1>

                <!-- Garis putih dipindah ke bawah judul -->
                <div class="w-20 h-1.5 bg-white mb-6 rounded-full opacity-60"></div>

            </div>

            <!-- Konten Bawah Kiri -->
            <div class="relative z-10 text-xs text-red-200/60 font-medium tracking-wide">
                &copy; {{ date('Y') }} Record Center - PT Semen Padang. All rights reserved.
            </div>
        </div>

        <!-- ========================================== -->
        <!-- KOLOM KANAN: FORM LOGIN (Semua Layar)      -->
        <!-- ========================================== -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-5 sm:p-12 relative min-h-screen bg-gray-50 lg:bg-transparent">

            <!-- DEKORASI BACKGROUND KHUSUS HP (Merah Melengkung di Atas) -->
            <div class="absolute top-0 left-0 w-full h-[40vh] min-h-75 bg-linear-to-br from-[#c41820] to-[#8a1216] lg:hidden z-0 rounded-b-[3rem] shadow-lg flex flex-col items-center pt-10 overflow-hidden">
                <!-- Pattern Geometris untuk HP -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs><pattern id="grid-mobile" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs>
                        <rect width="100%" height="100%" fill="url(#grid-mobile)" />
                    </svg>
                </div>
                <!-- Logo & Teks untuk HP -->
                <img src="{{ asset('images/sp-white.png') }}" alt="Logo Semen Padang" class="h-10 relative z-10 drop-shadow-md">
                <p class="text-white/80 text-[10px] font-bold tracking-widest uppercase mt-3 relative z-10">Sistem Informasi Record Center PT Semen Padang</p>
            </div>

            <!-- CONTAINER KARTU LOGIN -->
            <div class="w-full max-w-md bg-white/95 backdrop-blur-xl p-8 sm:p-10 rounded-4xl shadow-2xl lg:shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white lg:border-gray-100 relative z-10 mt-10 lg:mt-0">

                <!-- Tombol Kembali ke Landing -->
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-[#e92027] transition-all text-xs font-bold mb-8 group bg-gray-50 hover:bg-red-50 pr-4 py-1.5 rounded-full w-fit border border-gray-100">
                    <div class="w-7 h-7 rounded-full bg-white shadow-sm flex items-center justify-center group-hover:-translate-x-0.5 transition-transform ml-0.5 border border-gray-100">
                        <i class="fas fa-arrow-left text-[#e92027]"></i>
                    </div>
                    Kembali ke Beranda
                </a>

                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-1">Sign In</h2>
                </div>

                <!-- Flash Message Sukses -->
                @if (session('success'))
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-start gap-3 shadow-sm">
                        <i class="fas fa-check-circle mt-0.5 text-lg"></i>
                        <span class="text-sm font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.authenticate') }}" class="space-y-6"
                      x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                    @csrf

                    <!-- Flash Message Error -->
                    @if($errors->any())
                        <div x-data="{ show: true }" x-show="show" class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-100 flex items-start gap-3 shadow-sm transition-all">
                            <i class="fas fa-exclamation-circle mt-0.5 text-lg"></i>
                            <div class="flex-1">
                                <ul class="list-disc list-inside font-medium space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button @click="show = false" type="button" class="text-red-400 hover:text-red-700 focus:outline-none">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Email Input -->
                    <div class="relative group form-floating pt-2">
                        <input type="email" name="email" id="email" placeholder=" " required maxlength="100" value="{{ old('email') }}"
                            class="peer w-full px-5 py-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-[#e92027] transition-all duration-300 text-gray-800 font-medium placeholder-transparent shadow-sm hover:border-gray-300">
                        <label for="email" class="absolute left-3 top-4 text-gray-400 text-sm font-medium transition-all duration-300 pointer-events-none z-10 px-1">
                            Alamat Email
                        </label>
                        <i class="far fa-envelope absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#e92027] transition-colors text-lg"></i>
                    </div>

                    <!-- Password Input -->
                    <div class="relative group form-floating pt-2" x-data="{ showPass: false }">
                        <input :type="showPass ? 'text' : 'password'" name="password" id="password" placeholder=" " required maxlength="16"
                            class="peer w-full px-5 py-4 bg-white border-2 border-gray-200 rounded-xl outline-none focus:border-[#e92027] transition-all duration-300 text-gray-800 font-medium placeholder-transparent shadow-sm hover:border-gray-300">
                        <label for="password" class="absolute left-3 top-4 text-gray-400 text-sm font-medium transition-all duration-300 pointer-events-none z-10 px-1">
                            Password
                        </label>
                        <button type="button" @click="showPass = !showPass" tabindex="-1" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#e92027] transition-colors focus:outline-none z-20">
                            <i class="far text-lg" :class="showPass ? 'fa-eye' : 'fa-eye-slash'"></i>
                        </button>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center cursor-pointer group">
                            <input id="remember-me" name="remember" type="checkbox" class="w-4 h-4 text-[#e92027] border-gray-300 rounded focus:ring-[#e92027] transition-colors cursor-pointer">
                            <span class="ml-2 text-sm text-gray-600 font-medium group-hover:text-gray-900 transition-colors">Ingat Saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm font-bold text-[#e92027] hover:text-[#a0131a] transition-colors">
                            Lupa Password?
                        </a>
                    </div>

                    <button type="submit" x-bind:disabled="isSubmitting"
                        class="w-full bg-[#e92027] text-white font-extrabold py-4 mt-4 rounded-xl hover:bg-[#c41820] transition-all duration-300 transform active:scale-[0.98] tracking-widest uppercase shadow-[0_8px_20px_rgba(233,32,39,0.3)] hover:shadow-[0_8px_25px_rgba(233,32,39,0.4)] disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-3">

                        <span x-show="!isSubmitting">Masuk Sekarang</span>
                        <span x-show="isSubmitting" style="display: none;">
                            <i class="fas fa-circle-notch fa-spin text-lg"></i> Memproses...
                        </span>

                    </button>
                </form>

                <!-- Footer Khusus Mobile -->
                <div class="mt-10 text-center lg:hidden pb-2 border-t border-gray-100 pt-6">
                    <p class="text-gray-400 text-[10px] font-semibold tracking-wide uppercase">
                        &copy; {{ date('Y') }} Record Center - PT Semen Padang
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
