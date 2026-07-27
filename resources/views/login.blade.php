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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif !important;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .form-floating input:focus ~ label,
        .form-floating input:not(:placeholder-shown) ~ label {
            transform: scale(0.85) translateY(-2.5rem);
            color: #e92027;
            background-color: white;
            padding: 0 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased min-h-screen flex flex-col">

    <div class="flex-1 w-full flex flex-col md:flex-row bg-white">

        <!-- Left Column: Branding & Pattern (Sekarang Tampil di Semua Layar) -->
        <!-- Menghapus 'hidden' dan mengatur padding/height agar pas di HP -->
        <div class="flex w-full md:w-1/3 relative overflow-hidden bg-gradient-to-br from-[#c41820] to-[#8a1216] text-white flex-col justify-center md:justify-between p-8 pb-14 md:p-12 z-20 shadow-lg md:shadow-2xl">
            <!-- Geometric Pattern Overlay -->
            <div class="absolute inset-0 opacity-10">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <!-- Modern Curvy Shapes -->
            <div class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-80 h-80 bg-black opacity-20 rounded-full blur-3xl"></div>

             <!-- Content -->
             <!-- Di HP posisinya di tengah (text-center), di laptop di kiri (md:text-left) -->
            <div class="relative z-10 mt-auto mb-auto flex flex-col items-center md:items-start text-center md:text-left">
                 <div class="w-16 lg:w-20 h-1 bg-white mb-4 md:mb-6 rounded-full opacity-50"></div>
                 <h2 class="text-3xl lg:text-4xl font-bold mb-2 leading-tight">Selamat Datang!</h2>
                 <p class="text-white/90 text-base lg:text-lg font-light leading-relaxed">
                     Record Center <br class="hidden md:block">
                     <span class="font-semibold">PT Semen Padang</span>
                 </p>
            </div>

            <div class="hidden md:block relative z-10 text-xs opacity-50 font-light mt-auto">
                &copy; {{ date('Y') }} Record Center. All rights reserved.
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <!-- Menambahkan -mt-8 dan rounded-t-3xl agar di HP form sedikit menimpa panel merah -->
        <div class="flex-1 w-full md:w-2/3 relative flex items-center justify-center p-4 sm:p-8 bg-gray-50 -mt-6 md:mt-0 rounded-t-[2rem] md:rounded-none z-30 shadow-[0_-10px_20px_rgba(0,0,0,0.05)] md:shadow-none">

            <!-- Background Image -->
            <div class="absolute inset-0 z-0 opacity-40 rounded-t-[2rem] md:rounded-none overflow-hidden"
                style="background-image: url('{{ asset('images/SuperGrafis.png') }}'); background-size: cover; background-position: center;">
            </div>

             <!-- Login Card -->
            <div class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-md p-6 sm:p-8 md:p-10 rounded-2xl md:rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-white/50 my-8 md:my-0">

                <div class="text-center mb-8 md:mb-10">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Login</h1>
                    <p class="text-sm text-gray-500 md:hidden mt-2">Silakan masuk ke akun Anda</p>
                </div>

                <form method="POST" action="{{ route('login.authenticate') }}" class="space-y-5 md:space-y-6">
                    @csrf

                    <!-- Alert Error (Tetap sama seperti sebelumnya) -->
                    @if($errors->any())
                        <div x-data="{ show: true }" x-show="show" class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-4 border border-red-100 flex items-start gap-3">
                            <i class="fas fa-exclamation-circle mt-0.5"></i>
                            <div>
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button @click="show = false" type="button" class="ml-auto text-red-400 hover:text-red-700">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Email Input -->
                    <div class="relative group form-floating">
                        <!-- Tambahkan maxlength="255" -->
                        <input type="email" name="email" id="email" placeholder=" " required
                            autocomplete="email" autofocus maxlength="255"
                            class="peer w-full px-4 md:px-5 py-3 md:py-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all duration-300 text-gray-700 text-sm placeholder-transparent">
                        <label for="email"
                               class="absolute left-1 top-3.5 md:top-4 text-gray-400 text-sm transition-all duration-300 pointer-events-none">
                            Email Address
                        </label>
                        <i class="far fa-envelope absolute right-4 md:right-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#e92027] transition-colors"></i>
                    </div>

                    <!-- Password Input -->
                    <div class="relative group form-floating" x-data="{ showPass: false }">
                        <!-- Tambahkan maxlength="64" -->
                        <input :type="showPass ? 'text' : 'password'" name="password" id="password" placeholder=" " required
                            autocomplete="current-password" maxlength="64"
                            class="peer w-full px-4 md:px-5 py-3 md:py-4 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-[#e92027]/20 focus:border-[#e92027] transition-all duration-300 text-gray-700 text-sm placeholder-transparent">
                        <label for="password"
                               class="absolute left-1 top-3.5 md:top-4 text-gray-400 text-sm transition-all duration-300 pointer-events-none">
                            Password
                        </label>
                        <button type="button" @click="showPass = !showPass" class="absolute right-4 md:right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#e92027] transition-colors focus:outline-none">
                            <i class="far" :class="showPass ? 'fa-eye' : 'fa-eye-slash'"></i>
                        </button>
                    </div>

                    <!-- Elemen lainnya (Remember me, Forgot Password, Button) tetap sama -->

                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember" type="checkbox" class="h-4 w-4 text-[#e92027] focus:ring-[#e92027] border-gray-300 rounded">
                            <label for="remember-me" class="ml-2 block text-xs md:text-sm text-gray-900">
                                Remember Me
                            </label>
                        </div>

                        <div class="text-xs md:text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-[#e92027] hover:text-[#b91c1c]">
                                Forgot Password?
                            </a>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#e92027] text-white font-bold py-3 md:py-4 mt-2 rounded-xl hover:bg-[#c41820] hover:shadow-lg hover:shadow-red-500/30 transition-all duration-300 transform active:scale-95 text-sm tracking-wide">
                        LOGIN
                    </button>

                </form>

                <!-- Footer untuk HP dipindah ke bawah card -->
                <div class="mt-8 text-center md:hidden">
                    <p class="text-gray-400 text-xs">
                        &copy; {{ date('Y') }} Record Center PT Semen Padang
                    </p>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
