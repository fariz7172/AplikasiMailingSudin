<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - E-SPP Admin</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2F2FE4',
                        accent: '#2FA4D7',
                        surface: '#FFFFFF',
                        background: '#F8FAFC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #2FA4D7;
            border-radius: 10px;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Premium Input Styling */
        .form-input-premium {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #f8fafc; /* Slate 50 */
            border: 1px solid #e2e8f0; /* Slate 200 */
            border-radius: 1rem;
            outline: none;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
            color: #1e293b; /* Slate 800 */
        }

        .form-input-premium:hover {
            background-color: #f1f5f9; /* Slate 100 */
            border-color: #cbd5e1; /* Slate 300 */
        }

        .form-input-premium:focus {
            background-color: #ffffff;
            border-color: #2F2FE4; /* Your Royal Blue */
            box-shadow: 0 0 0 4px rgba(47, 47, 228, 0.1);
        }

        /* Label Styling */
        .form-label-premium {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b; /* Slate 500 */
            text-transform: uppercase;
            letter-spacing: 0.025em;
            margin-bottom: 0.5rem;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-background font-sans text-slate-800">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="fixed top-0 left-0 h-full w-64 bg-primary text-white z-50 transform -translate-x-full lg:translate-x-0 sidebar-transition flex flex-col shadow-2xl">
        <div class="p-6 flex items-center gap-3">
            <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center shadow-lg shadow-accent/30">
                <i data-lucide="graduation-cap" class="text-white w-6 h-6"></i>
            </div>
            <span class="text-xl font-bold tracking-tight">E-SPP Admin</span>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto">
            <x-nav-link href="{{ route('dashboard') }}" icon="layout-dashboard"
                active="{{ request()->routeIs('dashboard') }}">Dashboard</x-nav-link>
            <x-nav-link href="{{ route('import.index') }}" icon="upload-cloud"
                active="{{ request()->routeIs('import.*') }}">Import Data</x-nav-link>
            <x-nav-link href="{{ route('payments.index') }}" icon="credit-card"
                active="{{ request()->routeIs('payments.*') }}">Pembayaran</x-nav-link>
            <x-nav-link href="{{ route('pptk.index') }}" icon="user-check"
                active="{{ request()->routeIs('pptk.*') }}">Data Pejabat</x-nav-link>

            {{-- Master Data (collapsible) --}}
            <div x-data="{ open: {{ request()->routeIs('programs.*') || request()->routeIs('kegiatans.*') || request()->routeIs('sub-kegiatans.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-sm font-semibold transition-all
                    {{ request()->routeIs('programs.*') || request()->routeIs('kegiatans.*') || request()->routeIs('sub-kegiatans.*') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <i data-lucide="database" class="w-5 h-5 shrink-0"></i>
                        Master Data
                    </span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open" x-collapse class="mt-1 ml-4 space-y-1 border-l border-white/10 pl-3">
                    <a href="{{ route('programs.index') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all
                        {{ request()->routeIs('programs.*') ? 'bg-white/20 text-white' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="folder" class="w-4 h-4 shrink-0"></i>
                        Program
                    </a>
                    <a href="{{ route('kegiatans.index') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all
                        {{ request()->routeIs('kegiatans.*') ? 'bg-white/20 text-white' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="layers" class="w-4 h-4 shrink-0"></i>
                        Kegiatan
                    </a>
                    <a href="{{ route('sub-kegiatans.index') }}"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all
                        {{ request()->routeIs('sub-kegiatans.*') ? 'bg-white/20 text-white' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="git-branch" class="w-4 h-4 shrink-0"></i>
                        Sub Kegiatan
                    </a>
                </div>
            </div>

            <div class="pt-4 pb-2 px-4">
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Pengaturan</p>
            </div>
            <x-nav-link href="#" icon="settings">Konfigurasi</x-nav-link>
            <x-nav-link href="#" icon="shield">User Access</x-nav-link>
        </nav>


        <div class="p-4 mt-auto">
            <div class="bg-white/5 rounded-2xl p-4 border border-white/5 backdrop-blur-sm">
                <div class="flex items-center gap-3 mb-3">
                    <img src="https://ui-avatars.com/api/?name=Fariz+Admin&background=2FA4D7&color=fff"
                        class="w-8 h-8 rounded-full border border-white/20" alt="">
                    <div class="overflow-hidden">
                        <p class="text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Fariz Project' }}</p>
                        <p class="text-[10px] text-white/50 truncate">Senior Administrator</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2 bg-white/10 hover:bg-red-500/20 text-white/80 hover:text-red-400 rounded-lg text-sm transition-colors group">
                        <i data-lucide="log-out" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen sidebar-transition">

        <!-- Navbar -->
        <header class="sticky top-0 z-30 px-6 py-4 flex items-center justify-between glass shadow-sm">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="hidden sm:block">
                    <h2 class="text-lg font-bold">@yield('page_title', 'Ringkasan Sistem')</h2>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                <div class="relative hidden md:block group">
                    <i data-lucide="search"
                        class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-accent transition-colors"></i>
                    <input type="text" placeholder="Cari data..."
                        class="pl-10 pr-4 py-2 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-accent w-64 text-sm outline-none transition-all">
                </div>

                <button class="p-2 hover:bg-slate-100 rounded-lg relative transition-all active:scale-90">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>

                <div class="h-8 w-px bg-slate-200 mx-2"></div>

                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold group-hover:text-primary transition-colors">
                            {{ auth()->user()->name ?? 'Fariz Admin' }}</p>
                        <p class="text-[10px] text-slate-400">Online</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Fariz+Admin&background=2F2FE4&color=fff" alt="Avatar"
                        class="w-9 h-9 rounded-xl shadow-sm border-2 border-transparent group-hover:border-primary transition-all">
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-6">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="px-6 py-8 text-center text-slate-400 text-xs">
            <div class="flex items-center justify-center gap-4 mb-2">
                <a href="#" class="hover:text-primary transition-colors">Bantuan</a>
                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                <a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
            </div>
            <p>&copy; 2026 <strong>E-SPP Application</strong>. Dikembangkan dengan Standar IT Senior.</p>
        </footer>
    </main>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggle = document.getElementById('sidebar-toggle');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        toggle.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', toggleSidebar);

        // Sidebar link active state transition
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) toggleSidebar();
            });
        });
    </script>
    @stack('scripts')
</body>

</html>