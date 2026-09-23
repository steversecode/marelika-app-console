<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f4f7fc]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marelika Open Platform Console')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo/1706340633465.jpeg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        marel: {
                            blue: '#2258a5',
                            'blue-hover': '#1b4787',
                            'blue-dark': '#123363',
                            'blue-light': '#edf4fc',
                            orange: '#f58220',
                            'orange-hover': '#dc6e10',
                            'orange-light': '#fff5eb',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.cdnfonts.com/css/campton" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { font-family: 'Campton', 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        [x-cloak] { display: none !important; }
        .bg-marel-gradient {
            background: linear-gradient(135deg, #123363 0%, #2258a5 50%, #1e4a8a 100%);
        }
        /* Smooth custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }
    </style>
    <script>
        (function () {
            const savedMode = localStorage.getItem('marelika_dark_mode');
            if (savedMode === 'dark' || (!savedMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup.marel-swal {
            border-radius: 1.25rem !important;
            padding: 1.5rem !important;
            font-family: inherit !important;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.25) !important;
        }
        .swal2-title.marel-swal-title {
            font-size: 1.05rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em !important;
        }
        .swal2-html-container.marel-swal-html {
            font-size: 0.8125rem !important;
            color: #64748b !important;
            line-height: 1.5 !important;
            margin-top: 0.5rem !important;
        }
        .dark .swal2-html-container.marel-swal-html {
            color: #94a3b8 !important;
        }
        .marel-swal-btn-confirm {
            border-radius: 0.75rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            padding: 0.6rem 1.25rem !important;
            box-shadow: 0 4px 10px rgba(34, 88, 165, 0.25) !important;
            transition: all 0.2s !important;
        }
        .marel-swal-btn-cancel {
            border-radius: 0.75rem !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            padding: 0.6rem 1.25rem !important;
            background-color: #64748b !important;
            color: #ffffff !important;
            transition: all 0.2s !important;
        }
        .swal2-toast.marel-toast {
            border-radius: 1rem !important;
            padding: 0.75rem 1rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
            font-family: inherit !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
        }
    </style>
</head>
<body class="h-full flex flex-col antialiased bg-[#f4f7fc] dark:bg-[#0a1524] text-slate-800 dark:text-slate-100 transition-colors duration-200 overflow-hidden">

    <!-- Top Navigation Bar (Full Width) -->
    <header class="w-full bg-white dark:bg-[#102038] border-b border-slate-200/80 dark:border-slate-800 shrink-0 z-30 shadow-2xs">
        <div class="w-full px-3 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2">
            
            <!-- Left Logo & Mobile Toggle -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Hamburger Menu for Mobile -->
                <button type="button" onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-600 dark:text-slate-300 hover:text-[#2258a5] dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors" aria-label="Toggle navigation menu">
                    <span class="material-symbols-outlined text-[22px] block">menu</span>
                </button>

                <a href="{{ route('console.apps.index') }}" class="flex items-center gap-2.5 sm:gap-3 group">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl overflow-hidden shadow-xs transition-transform group-hover:scale-105 flex items-center justify-center relative p-0.5 bg-white border border-slate-200/80 dark:border-slate-700 shrink-0">
                        <img src="{{ asset('images/logo/1706340633465.jpeg') }}" alt="Marelika Logo" class="w-full h-full object-cover rounded-[8px]">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-xs sm:text-sm text-[#2258a5] dark:text-white tracking-tight flex items-center gap-1">
                            MARELIKA <span class="font-normal text-slate-300 dark:text-slate-600">|</span> <span class="text-[#f58220] font-semibold">Console</span>
                        </span>
                        <span class="hidden sm:block text-[10px] text-slate-400 dark:text-slate-400 font-medium tracking-wider uppercase truncate max-w-[150px] md:max-w-none">CV Dua Marelika Gemilang</span>
                    </div>
                </a>
            </div>

            <!-- Right Controls -->
            <div class="flex items-center gap-1.5 sm:gap-4">
                
                <!-- Quick Links (Desktop & Tablet) -->
                <a href="{{ route('console.tools.test') }}" class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-[#edf4fc] dark:hover:bg-slate-800 hover:text-[#2258a5] transition-colors">
                    <span class="material-symbols-outlined text-[16px] text-[#f58220]">build</span>
                    <span>API Test Tool</span>
                </a>

                <!-- Dark Mode Toggle Button -->
                <button type="button" onclick="toggleDarkMode()" class="p-2 rounded-xl text-slate-500 hover:text-[#2258a5] dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Toggle Dark/Light Mode">
                    <span id="darkIcon" class="material-symbols-outlined text-[18px]">dark_mode</span>
                </button>

                <div class="h-4 w-px bg-slate-200 dark:bg-slate-800"></div>

                <!-- User Dropdown & Logout -->
                <div class="flex items-center gap-1.5 sm:gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-[#2258a5] text-white flex items-center justify-center font-bold text-xs shadow-2xs border border-white/20 shrink-0">
                            {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                        </div>
                        <div class="hidden lg:block text-left">
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="text-[10px] text-slate-400 font-mono leading-tight">{{ auth()->user()->email ?? 'admin@marelika.com' }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-950/40 transition-colors" title="Logout">
                            <span class="material-symbols-outlined text-[18px] block">logout</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    <!-- Full-Width Body Container (Sidebar + Content) -->
    <div class="relative flex flex-1 h-full w-full overflow-hidden">
        
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div id="sidebarBackdrop" onclick="closeSidebar()" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden hidden transition-opacity duration-300 opacity-0" aria-hidden="true"></div>

        <!-- Left Sidebar (Collapsible Drawer on Mobile, Static on Desktop) -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-72 lg:w-64 shrink-0 h-full p-4 lg:p-6 overflow-y-auto transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out bg-[#f4f7fc] dark:bg-[#0a1524] lg:bg-transparent shadow-2xl lg:shadow-none">
            <div class="bg-white dark:bg-[#102038] rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 space-y-4">
                
                <!-- Mobile Drawer Header -->
                <div class="flex items-center justify-between px-2 pt-1 pb-2 border-b border-slate-100 dark:border-slate-800 lg:hidden">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-xs text-[#2258a5] dark:text-white">Navigation Menu</span>
                    </div>
                    <button type="button" onclick="closeSidebar()" class="p-1.5 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-[18px] block">close</span>
                    </button>
                </div>

                <!-- Category 1: App Management -->
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 py-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">apps</span>
                        <span>App Management</span>
                    </div>
                    <nav class="mt-1 space-y-1">
                        <a href="{{ route('console.apps.index') }}" onclick="closeSidebarOnMobile()" class="flex items-center justify-between px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('console.apps.index') || request()->routeIs('console.apps.show') || request()->routeIs('console.apps.edit') ? 'bg-[#2258a5] text-white shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:bg-[#edf4fc] dark:hover:bg-slate-800/70 hover:text-[#2258a5] dark:hover:text-white' }}">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('console.apps.index') || request()->routeIs('console.apps.show') || request()->routeIs('console.apps.edit') ? 'text-[#f58220]' : '' }}">storefront</span>
                                <span>App List</span>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ request()->routeIs('console.apps.index') || request()->routeIs('console.apps.show') || request()->routeIs('console.apps.edit') ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                                {{ \App\Models\ApiApp::count() }}
                            </span>
                        </a>
                        <a href="{{ route('console.apps.create') }}" onclick="closeSidebarOnMobile()" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('console.apps.create') ? 'bg-[#2258a5] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-[#edf4fc] dark:hover:bg-slate-800/70 hover:text-[#2258a5] dark:hover:text-white' }}">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            <span>Create App</span>
                        </a>
                    </nav>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800"></div>

                <!-- Category 2: Tools & Diagnostics -->
                <div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 py-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">construction</span>
                        <span>Tools &amp; Audit</span>
                    </div>
                    <nav class="mt-1 space-y-1">
                        <a href="{{ route('console.tools.test') }}" onclick="closeSidebarOnMobile()" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('console.tools.test') ? 'bg-[#2258a5] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-[#edf4fc] dark:hover:bg-slate-800/70 hover:text-[#2258a5] dark:hover:text-white' }}">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('console.tools.test') ? 'text-[#f58220]' : '' }}">play_circle</span>
                            <span>API Test Tool</span>
                        </a>
                        <a href="{{ route('console.logs.index') }}" onclick="closeSidebarOnMobile()" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('console.logs.index') ? 'bg-[#2258a5] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-[#edf4fc] dark:hover:bg-slate-800/70 hover:text-[#2258a5] dark:hover:text-white' }}">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('console.logs.index') ? 'text-[#f58220]' : '' }}">manage_search</span>
                            <span>API Access Logs</span>
                        </a>
                        <a href="{{ route('console.logs.statistics') }}" onclick="closeSidebarOnMobile()" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('console.logs.statistics') ? 'bg-[#2258a5] text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-[#edf4fc] dark:hover:bg-slate-800/70 hover:text-[#2258a5] dark:hover:text-white' }}">
                            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('console.logs.statistics') ? 'text-[#f58220]' : '' }}">bar_chart</span>
                            <span>Call Statistics</span>
                        </a>
                    </nav>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800"></div>

                <!-- Info Card -->
                <div class="p-3.5 rounded-xl bg-gradient-to-br from-[#edf4fc] to-[#fff5eb] dark:from-[#13253d] dark:to-[#1a2d47] border border-[#d3e4f7] dark:border-slate-700 text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed">
                    <div class="font-bold text-[#2258a5] dark:text-[#f58220] mb-1.5 flex items-center gap-2">
                        <img src="{{ asset('images/logo/1706340633465.jpeg') }}" alt="Logo" class="w-4 h-4 rounded object-cover shadow-2xs">
                        <span>CV Dua Marelika Gemilang</span>
                    </div>
                    Konfigurasi key dari console ini ke modul Odoo <strong>Marelika Store</strong> untuk sinkronisasi pesanan multi-store.
                </div>

            </div>
        </aside>

        <!-- Right Main Content Area (Full Width & Scrollable) -->
        <main class="flex-1 w-full min-w-0 flex flex-col h-full overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center justify-between text-xs shadow-2xs">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-emerald-600 text-[18px]">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <span class="material-symbols-outlined text-[16px]">close</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 rounded-2xl text-xs shadow-2xs">
                    <div class="font-bold flex items-center gap-2 mb-1">
                        <span class="material-symbols-outlined text-rose-600 text-[18px]">error</span>
                        <span>Periksa kembali data berikut:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-700 dark:text-rose-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Dark Mode, Sidebar Drawer, SweetAlert2 & Clipboard JS -->
    <script>
        // Sidebar Toggle for Mobile Drawer
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;

            const isOpen = !sidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                closeSidebar();
            } else {
                openSidebar();
            }
        }

        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;

            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            }, 10);
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;

            sidebar.classList.add('-translate-x-full');
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
            document.body.classList.remove('overflow-hidden');
        }

        function closeSidebarOnMobile() {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        }

        // Close on Escape key press
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        // Responsive resize handler
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const backdrop = document.getElementById('sidebarBackdrop');
                if (backdrop) {
                    backdrop.classList.add('hidden');
                    backdrop.classList.remove('opacity-100');
                    backdrop.classList.add('opacity-0');
                }
            }
        });

        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.toggle('dark');
            localStorage.setItem('marelika_dark_mode', isDark ? 'dark' : 'light');
            updateDarkIcon();
        }

        function updateDarkIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = document.getElementById('darkIcon');
            if (icon) {
                icon.innerText = isDark ? 'light_mode' : 'dark_mode';
            }
        }
        document.addEventListener('DOMContentLoaded', updateDarkIcon);

        // Minimalist SweetAlert2 Toast
        const MarelToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            customClass: {
                popup: 'marel-toast'
            }
        });

        // Minimalist SweetAlert2 Confirm Dialog
        function marelConfirm(title, text, confirmBtnText = 'Ya, Lanjutkan', icon = 'warning', confirmBtnColor = '#2258a5') {
            const isDark = document.documentElement.classList.contains('dark');
            return Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmBtnColor,
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: isDark ? '#0f1d30' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#1e293b',
                customClass: {
                    popup: 'marel-swal',
                    title: 'marel-swal-title',
                    htmlContainer: 'marel-swal-html',
                    confirmButton: 'marel-swal-btn-confirm',
                    cancelButton: 'marel-swal-btn-cancel'
                }
            });
        }

        // Copy to clipboard with sleek Toast
        function copyToClipboard(text, element, label = 'Item') {
            navigator.clipboard.writeText(text).then(function() {
                if (element) {
                    const originalHtml = element.innerHTML;
                    element.innerHTML = '<span class="material-symbols-outlined text-[14px] text-emerald-500">done</span> Disalin!';
                    setTimeout(() => {
                        element.innerHTML = originalHtml;
                    }, 2000);
                }
                MarelToast.fire({
                    icon: 'success',
                    title: (label || 'Kredensial') + ' berhasil disalin!'
                });
            }).catch(function() {
                MarelToast.fire({
                    icon: 'error',
                    title: 'Gagal menyalin teks'
                });
            });
        }

        // Trigger flash messages via SweetAlert2 if any
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                MarelToast.fire({
                    icon: 'success',
                    title: {!! json_encode(session('success')) !!}
                });
            });
        @endif

        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                MarelToast.fire({
                    icon: 'error',
                    title: {!! json_encode(session('error')) !!}
                });
            });
        @endif
    </script>
</body>
</html>