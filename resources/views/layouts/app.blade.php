<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="flex bg-gray-100 min-h-screen font-sans">

    {{-- Mobile Menu Button --}}
    <button id="mobileMenuBtn" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-blue-600 text-white shadow-lg">
        <i data-feather="menu" class="h-6 w-6"></i>
    </button>

    {{-- Overlay --}}
    <div id="overlay" class="lg:hidden fixed inset-0 bg-black/50 z-30 hidden"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="w-64 h-screen fixed shadow-lg bg-gradient-to-b from-blue-600 to-indigo-700 text-white flex flex-col z-40 transition-transform duration-300 -translate-x-full lg:translate-x-0"> 
        {{-- Close Button (Mobile Only) --}}
        <button id="closeSidebarBtn" class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-white/20">
            <i data-feather="x" class="h-6 w-6"></i>
        </button>

        <div class="p-6 flex flex-col items-center border-b border-white/20">
            <h1 class="text-2xl font-bold mb-2">SLPSC</h1>
            <p class="text-sm text-white/70 text-center">Sistem Loyalitas Pelanggan Sebelas Coffee</p>
        </div>
        
        {{-- Navigasi Utama --}}
        <nav class="mt-6 flex flex-col gap-1 px-4 overflow-y-auto flex-1"> 
            
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                        {{ request()->routeIs('dashboard') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="grid" class="mr-3 h-4 w-4"></i>
                Dashboard
            </a>

            {{-- Transaksi --}}
            <a href="{{ route('transaksi.index') }}"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                        {{ request()->routeIs('transaksi.*') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="shopping-cart" class="mr-3 h-4 w-4"></i>
                Transaksi
            </a>

            {{-- Produk --}}
            <a href="{{ route('produk.index') }}"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                        {{ request()->routeIs('produk.*') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="box" class="mr-3 h-4 w-4"></i>
                Produk
            </a>

            {{-- Kategori --}}
            <a href="{{ route('kategori.index') }}"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                        {{ request()->routeIs('kategori.*') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="tag" class="mr-3 h-4 w-4"></i>
                Kategori
            </a>
            
            {{-- Pelanggan --}}
            <a href="{{ route('pelanggan.index') }}"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                        {{ request()->routeIs('pelanggan.*') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="users" class="mr-3 h-4 w-4"></i>
                Pelanggan
            </a>
            
            {{-- Membership --}}
            <a href="{{ route('membership.index') }}"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                        {{ request()->routeIs('membership.*') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="star" class="mr-3 h-4 w-4"></i>
                Membership
            </a>

            {{-- User - Only visible for admin --}}
            @if(Auth::user()->role === 'admin')
            <a href="/users"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                         {{ request()->is('users') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="user" class="mr-3 h-4 w-4"></i>
                User
            </a>
            @endif

            {{-- Laporan --}}
            <a href="{{ route('laporan.index') }}"
               class="flex items-center px-4 py-2.5 rounded-lg transition-colors duration-200
                     {{ request()->routeIs('laporan.*') ? 'bg-white text-blue-700 font-bold shadow-lg' : 'hover:bg-white/20' }}">
                <i data-feather="bar-chart-2" class="mr-3 h-4 w-4"></i>
                Laporan
            </a>
        </nav>
        
        {{-- User Info & Logout --}}
        <div class="mt-auto mb-4 px-4 py-3 border-t border-white/20">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-3">
                        <i data-feather="user" class="h-4 w-4"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ Auth::user()->nama }}</p>
                        <p class="text-xs text-white/70 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="p-2 rounded-lg transition-colors duration-200 hover:bg-red-500/20 text-red-200 hover:text-white"
                            title="Logout">
                        <i data-feather="log-out" class="h-4 w-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="lg:ml-64 flex-1 p-6 pt-20 lg:pt-6">
        @yield('content')
    </main>

    <script>
        feather.replace();

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        // Open sidebar
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        // Close sidebar
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        closeSidebarBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking on navigation links (mobile only)
        const navLinks = sidebar.querySelectorAll('nav a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        // Re-initialize feather icons after sidebar opens
        const observer = new MutationObserver(() => {
            feather.replace();
        });
        observer.observe(sidebar, { childList: true, subtree: true });
    </script>
</body>
</html>