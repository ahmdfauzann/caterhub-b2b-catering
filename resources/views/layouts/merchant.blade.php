<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Merchant - CaterHub')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased flex min-h-screen">

    <!-- Merchant Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 z-40 border-r border-slate-800 shadow-xl">
        <!-- Sidebar Brand -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800 gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-amber-500 flex items-center justify-center text-white font-bold text-xl shadow-md">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <span class="font-extrabold text-lg text-white">Merchant Portal</span>
                <span class="block text-[10px] uppercase font-semibold text-brand-400">CaterHub B2B</span>
            </div>
        </div>

        <!-- Merchant Profile Quick Card -->
        <div class="p-4 mx-3 my-4 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center font-bold text-amber-400 uppercase text-sm shrink-0">
                {{ substr(auth()->user()->merchantProfile->company_name ?? 'M', 0, 2) }}
            </div>
            <div class="overflow-hidden">
                <p class="font-bold text-xs text-white truncate">{{ auth()->user()->merchantProfile->company_name ?? auth()->user()->name }}</p>
                <p class="text-[10px] text-slate-400 capitalize"><i class="fa-solid fa-circle text-[8px] text-emerald-400 mr-1"></i> Aktif</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto">
            <a href="{{ route('merchant.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('merchant.dashboard') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-gauge w-5"></i> Dashboard
            </a>

            <a href="{{ route('merchant.menus.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('merchant.menus.*') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-utensils w-5"></i> Kelola Menu Makanan
            </a>

            <a href="{{ route('merchant.orders.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('merchant.orders.*') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-file-invoice-dollar w-5"></i> Pesanan & Invoice
            </a>

            <a href="{{ route('merchant.profile') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('merchant.profile') ? 'bg-brand-600 text-white font-bold shadow-md shadow-brand-600/30' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                <i class="fa-solid fa-sliders w-5"></i> Profil Katering
            </a>

            <div class="pt-4 border-t border-slate-800 my-2"></div>

            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-xs font-medium text-slate-400 hover:bg-slate-800 hover:text-white">
                <i class="fa-solid fa-arrow-up-right-from-square w-5"></i> Lihat Website Utama
            </a>
        </nav>

        <!-- Logout Bottom -->
        <div class="p-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 text-rose-400 hover:bg-rose-600 hover:text-white text-xs font-bold transition-all">
                    <i class="fa-solid fa-right-from-bracket"></i> Keluar Portal
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Right Content Wrapper -->
    <div class="pl-64 flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900">@yield('page-title', 'Dashboard Merchant')</h1>
                <p class="text-xs text-slate-500">Kelola operasional dan pesanan katering kantor Anda.</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('merchant.menus.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-bold hover:bg-brand-700 shadow-md shadow-brand-600/20 transition-all">
                    <i class="fa-solid fa-plus"></i> Tambah Menu Baru
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="p-8 flex-1">
            @if(session('success'))
                <div class="p-4 mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-exclamation text-xl text-rose-600"></i>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
