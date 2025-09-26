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

    {{-- Sidebar --}}
    <aside class="w-64 h-screen fixed shadow-lg bg-gradient-to-b from-blue-600 to-indigo-700 text-white">
        <div class="p-6 flex flex-col items-center border-b border-white/20">
            <h1 class="text-2xl font-bold mb-2">Balcos Compound</h1>
            <p class="text-sm text-white/70">Admin Panel</p>
        </div>
        
        <nav class="mt-6 flex flex-col gap-1 px-4 flex-1">
            
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

    <main class="ml-64 flex-1 p-6">
        @yield('content')
    </main>

    <script>
        feather.replace();
    </script>
</body>
</html>