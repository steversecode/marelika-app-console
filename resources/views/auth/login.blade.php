<!DOCTYPE html>
<html lang="id" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Marelika Open Platform Console</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo/1706340633465.jpeg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.cdnfonts.com/css/campton" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { font-family: 'Campton', 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .bg-marel-blue { background-color: #2258a5; }
        .bg-marel-blue:hover { background-color: #1b4787; }
        .bg-marel-orange { background-color: #f58220; }
        .bg-marel-orange:hover { background-color: #dc6e10; }
        .text-marel-blue { color: #2258a5; }
        .text-marel-orange { color: #f58220; }
    </style>
</head>
<body class="h-full antialiased text-[#111318]">

    <div class="flex min-h-screen w-full">
        <!-- Left Column: Marel Brand Theme (Desktop Only) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#123363] via-[#2258a5] to-[#1a447d] text-white p-12 flex-col justify-between overflow-hidden">
            <!-- Orange & Blue Glowing Spheres -->
            <div class="absolute -top-20 -left-20 w-96 h-96 bg-[#f58220]/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-sky-400/20 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Top Brand -->
            <div class="relative z-10 flex items-center gap-3.5">
                <div class="w-13 h-13 rounded-2xl overflow-hidden shadow-lg border border-white/30 bg-white/10 p-0.5 shrink-0 flex items-center justify-center">
                    <img src="{{ asset('images/logo/1706340633465.jpeg') }}" alt="CV Dua Marelika Gemilang" class="w-full h-full object-cover rounded-[14px]">
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight">CV DUA MARELIKA GEMILANG</h2>
                    <p class="text-xs text-sky-200">Marelika Open Platform &amp; Odoo Hub</p>
                </div>
            </div>

            <!-- Middle Highlights -->
            <div class="relative z-10 space-y-6 max-w-md my-auto">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-xs font-semibold text-white">
                    <span class="w-2 h-2 rounded-full bg-[#f58220] animate-pulse"></span>
                    <span>Multi-Store ERP Synchronization</span>
                </div>

                <h1 class="text-3xl xl:text-4xl font-bold leading-tight">
                    Pusat Integrasi API Toko &amp; Otomasi Sinkronisasi Odoo.
                </h1>
                
                <p class="text-sm text-sky-100 leading-relaxed">
                    Kelola API Partner Key untuk seluruh brand store (<strong class="text-[#f58220]">Biemi</strong>, <strong class="text-[#f58220]">Clabycla</strong>, dan <strong class="text-[#f58220]">Manee</strong>) dalam satu dashboard terpusat.
                </p>

                <div class="grid grid-cols-3 gap-3 pt-4 border-t border-white/15">
                    <div class="p-3.5 rounded-xl bg-white/10 backdrop-blur border border-white/15 text-center">
                        <div class="text-base font-bold text-white">Biemi</div>
                        <div class="text-[10px] text-sky-200">Official Web</div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/10 backdrop-blur border border-white/15 text-center">
                        <div class="text-base font-bold text-white">Clabycla</div>
                        <div class="text-[10px] text-sky-200">Fashion Web</div>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/10 backdrop-blur border border-white/15 text-center">
                        <div class="text-base font-bold text-white">Manee</div>
                        <div class="text-[10px] text-sky-200">Lifestyle Web</div>
                    </div>
                </div>
            </div>

            <!-- Bottom Footnote -->
            <div class="relative z-10 text-xs text-sky-200/80 flex items-center justify-between">
                <span>&copy; {{ date('Y') }} CV Dua Marelika Gemilang</span>
                <span class="px-2 py-0.5 rounded bg-white/10 text-white font-mono text-[10px]">Odoo 13 Ready</span>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 md:px-24 xl:px-32 py-12 bg-white">
            <div class="max-w-[420px] w-full mx-auto space-y-8">
                
                <!-- Mobile Logo Header -->
                <div class="lg:hidden flex items-center gap-3 mb-2">
                    <div class="w-11 h-11 rounded-xl overflow-hidden shadow-sm shrink-0 border border-slate-200 p-0.5 bg-white">
                        <img src="{{ asset('images/logo/1706340633465.jpeg') }}" alt="CV Dua Marelika Gemilang" class="w-full h-full object-cover rounded-[10px]">
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-[#2258a5]">MARELIKA CONSOLE</h2>
                        <p class="text-[11px] text-slate-500">CV Dua Marelika Gemilang</p>
                    </div>
                </div>

                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-[#edf4fc] text-[#2258a5] text-xs font-bold mb-2">
                        <img src="{{ asset('images/logo/1706340633465.jpeg') }}" alt="Icon" class="w-4 h-4 rounded object-cover">
                        <span>Portal Administrator</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-[#111318]">
                        Selamat Datang
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Masuk untuk mengelola API Partner Key &amp; kredensial toko.
                    </p>
                </div>

                <!-- Session Alert -->
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Errors -->
                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-3">
                        <span class="material-symbols-outlined text-rose-600 text-[20px] shrink-0 mt-0.5">error</span>
                        <div>
                            <div class="font-bold">Gagal Masuk:</div>
                            <div class="text-rose-700 mt-0.5">{{ $errors->first() }}</div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5 text-xs">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-1.5">
                        <label for="email" class="block font-bold text-slate-700">Email Address</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">mail</span>
                            <input id="email" type="email" name="email" value="{{ old('email', 'admin@marelika.com') }}" required autofocus placeholder="admin@marelika.com"
                                class="w-full h-11 pl-10 pr-4 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:ring-2 focus:ring-[#2258a5]/20 focus:border-[#2258a5] transition-all text-xs font-medium">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block font-bold text-slate-700">Password</label>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock</span>
                            <input id="password" type="password" name="password" required value="password" placeholder="••••••••"
                                class="w-full h-11 pl-10 pr-10 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:ring-2 focus:ring-[#2258a5]/20 focus:border-[#2258a5] transition-all text-xs font-medium">
                            <button type="button" onclick="togglePassword()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700">
                                <span id="pwdEyeIcon" class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 text-slate-600 cursor-pointer select-none">
                            <input type="checkbox" name="remember" checked class="w-4 h-4 rounded border-slate-300 text-[#2258a5] focus:ring-[#2258a5]">
                            <span>Ingat saya</span>
                        </label>
                        <span class="text-slate-400">Default: password</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full h-11 rounded-xl bg-[#2258a5] hover:bg-[#1b4787] text-white font-bold text-xs tracking-wide uppercase transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 cursor-pointer">
                        <span>Masuk ke Console</span>
                        <span class="material-symbols-outlined text-[16px] text-[#f58220]">arrow_forward</span>
                    </button>
                </form>

                <!-- Register Prompt -->
                <div class="text-center text-xs text-slate-500 pt-1">
                    Belum memiliki akun administrator?
                    <a href="{{ route('register') }}" class="font-bold text-[#2258a5] hover:text-[#f58220] hover:underline transition-colors ml-1">
                        Daftar di sini
                    </a>
                </div>

                <!-- Credentials Info Box -->
                <div class="p-3.5 rounded-xl bg-[#edf4fc] border border-[#d3e4f7] text-[11px] text-slate-700 space-y-1">
                    <div class="font-bold text-[#2258a5] flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-[#f58220]">key</span>
                        <span>Akun Default:</span>
                    </div>
                    <div class="font-mono">Email: <strong>admin@marelika.com</strong> atau <strong>admin@biemi.id</strong></div>
                    <div class="font-mono">Password: <strong>password</strong></div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('pwdEyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }
    </script>
</body>
</html>