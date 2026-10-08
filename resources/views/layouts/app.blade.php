<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CaterHub - Platform Marketplace Katering B2B Kantor')</title>

    <!-- Google Fonts & Tailwind CSS CDN & FontAwesome 6 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for interactive UI -->
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
                            100: '#ffedd5',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        },
                        navy: {
                            800: '#0f172a',
                            900: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(249, 115, 22, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- Header Navigation -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/80 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-brand-600 to-amber-500 flex items-center justify-center text-white font-bold text-2xl shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-utensils"></i>
                    </div>
                    <div>
                        <span class="text-2xl font-extrabold bg-gradient-to-r from-slate-900 via-brand-600 to-amber-600 bg-clip-text text-transparent">CaterHub</span>
                        <span class="block text-xs font-semibold text-slate-400 tracking-wider uppercase">B2B Catering Marketplace</span>
                    </div>
                </a>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                    <a href="{{ route('home') }}" class="hover:text-brand-600 transition-colors {{ request()->routeIs('home') ? 'text-brand-600 font-bold' : '' }}">Beranda</a>
                    <a href="{{ route('search') }}" class="hover:text-brand-600 transition-colors {{ request()->routeIs('search') ? 'text-brand-600 font-bold' : '' }}">Cari Katering</a>
                    <a href="{{ route('home') }}#cara-kerja" class="hover:text-brand-600 transition-colors">Cara Kerja</a>
                </nav>

                <!-- User Auth Section -->
                <div class="flex items-center gap-4">
                    @auth
                        <!-- Cart Icon for Customer -->
                        @if(auth()->user()->isCustomer())
                            @php
                                $cart = session()->get('cart', []);
                                $cartCount = count($cart);
                            @endphp
                            <a href="{{ route('customer.cart') }}" class="relative p-2.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-brand-50 hover:text-brand-600 transition-all">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-brand-600 text-white text-xs font-bold flex items-center justify-center animate-pulse">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3 p-1.5 pr-3 rounded-full border border-slate-200 hover:border-brand-500 hover:shadow-md transition-all bg-white">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-brand-600 to-amber-500 text-white flex items-center justify-center font-bold text-sm uppercase">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <span class="block text-xs font-bold text-slate-800 leading-none">{{ auth()->user()->name }}</span>
                                    <span class="block text-[10px] font-medium text-slate-400 capitalize mt-0.5">
                                        {{ auth()->user()->role === 'merchant' ? 'Mitra Katering' : 'Klien Kantor' }}
                                    </span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-cloak
                                 class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 py-2 text-sm z-50 transform transition-all">
                                
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-xs text-slate-400">Signed in as</p>
                                    <p class="font-bold text-slate-800 truncate">{{ auth()->user()->email }}</p>
                                </div>

                                @if(auth()->user()->isMerchant())
                                    <a href="{{ route('merchant.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <i class="fa-solid fa-gauge w-5 text-slate-400"></i> Portal Merchant
                                    </a>
                                    <a href="{{ route('merchant.menus.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <i class="fa-solid fa-bowl-food w-5 text-slate-400"></i> Kelola Menu
                                    </a>
                                    <a href="{{ route('merchant.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <i class="fa-solid fa-receipt w-5 text-slate-400"></i> Pesanan & Invoice
                                    </a>
                                    <a href="{{ route('merchant.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <i class="fa-solid fa-store w-5 text-slate-400"></i> Profil Katering
                                    </a>
                                @else
                                    <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <i class="fa-solid fa-chart-line w-5 text-slate-400"></i> Portal Kantor
                                    </a>
                                    <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <i class="fa-solid fa-clock-history w-5 text-slate-400"></i> Riwayat Pesanan
                                    </a>
                                    <a href="{{ route('customer.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-slate-700 hover:bg-slate-50 hover:text-brand-600 font-medium">
                                        <i class="fa-solid fa-building w-5 text-slate-400"></i> Profil Perusahaan
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 my-1"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-rose-600 hover:bg-rose-50 font-medium">
                                        <i class="fa-solid fa-right-from-bracket w-5"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-brand-600 px-3 py-2">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-gradient-to-r from-brand-600 to-amber-500 hover:from-brand-700 hover:to-amber-600 px-5 py-2.5 rounded-xl shadow-md shadow-brand-500/20 hover:shadow-lg transition-all">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Flash Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="p-4 mb-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-check text-xl text-emerald-600"></i>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-xl text-rose-600"></i>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-navy-900 text-slate-300 mt-20 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white font-bold text-xl">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <span class="text-2xl font-extrabold text-white">CaterHub</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Platform marketplace katering B2B terpercaya untuk kebutuhan makan siang harian, meeting direksi, dan event perusahaan di seluruh Indonesia.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-brand-600 flex items-center justify-center transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-brand-600 flex items-center justify-center transition-all"><i class="fa-brands fa-linkedin"></i></a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-brand-600 flex items-center justify-center transition-all"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                </div>

                <!-- Col 2: Layanan B2B -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Layanan B2B</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('search') }}?category=nasi-kotak-premium" class="hover:text-brand-500 transition-colors">Nasi Kotak Harian Kantor</a></li>
                        <li><a href="{{ route('search') }}?category=prasmanan-buffet" class="hover:text-brand-500 transition-colors">Prasmanan Meeting & Event</a></li>
                        <li><a href="{{ route('search') }}?category=snack-box-corporate" class="hover:text-brand-500 transition-colors">Snack Box Coffee Break</a></li>
                        <li><a href="{{ route('search') }}?category=healthy-fit-box" class="hover:text-brand-500 transition-colors">Healthy Clean Eating Box</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal Pendaftaran -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Portal Pendaftaran</h4>
                    <ul class="space-y-2.5 text-xs text-slate-400">
                        <li><a href="{{ route('register.customer') }}" class="hover:text-brand-500 transition-colors">Daftarkan Perusahaan / Kantor</a></li>
                        <li><a href="{{ route('register.merchant') }}" class="hover:text-brand-500 transition-colors">Gabung Sebagai Mitra Katering</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-brand-500 transition-colors">Login Merchant Portal</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-brand-500 transition-colors">Login Corporate Client</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Test Submission Info -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Informasi Pengajuan</h4>
                    <div class="bg-slate-800/80 rounded-2xl p-4 border border-slate-700/60 space-y-2 text-xs">
                        <p class="text-amber-400 font-bold"><i class="fa-solid fa-award mr-1"></i> JASAMEDIKA TRANSMEDIC PT</p>
                        <p class="text-slate-300">Coding Test Submission Project</p>
                        <p class="text-slate-400 text-[11px]">Developer: Ahmad Fauzan</p>
                        <div class="pt-2 border-t border-slate-700 text-[11px] text-slate-400">
                          
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} CaterHub - Marketplace Katering B2B.</p>
                <div class="flex items-center gap-6">
                   
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
