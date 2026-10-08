@extends('layouts.app')

@section('title', 'CaterHub - Marketplace Katering B2B Kantor & Perusahaan')

@section('content')
<!-- Hero Section -->
<section class="relative bg-slate-900 text-white overflow-hidden pt-12 pb-24 border-b border-slate-800">
    <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-600/30 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 -left-40 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <!-- Left Text Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-800/90 border border-slate-700/80 text-brand-400 text-xs font-bold tracking-wide uppercase">
                    <i class="fa-solid fa-building text-amber-400"></i> Solusi Katering Makan Siang Perusahaan
                </div>
                
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    Solusi <span class="bg-gradient-to-r from-brand-400 via-amber-400 to-orange-500 bg-clip-text text-transparent">Makan Siang Kantor</span> Terpercaya & Otomatis.
                </h1>

                <p class="text-base sm:text-lg text-slate-300 max-w-2xl font-normal leading-relaxed">
                    Hubungkan kantor Anda dengan puluhan vendor katering profesional tersertifikasi. Transaksi transparan, pengiriman tepat waktu, dan invoice tagihan otomatis.
                </p>

                <!-- Search Bar Container -->
                <form action="{{ route('search') }}" method="GET" class="bg-white p-3 rounded-2xl shadow-2xl border border-slate-200/50 flex flex-col sm:flex-row gap-3">
                    <div class="flex-1 flex items-center px-3 gap-3">
                        <i class="fa-solid fa-magnifying-glass text-slate-400 text-lg"></i>
                        <input type="text" name="q" placeholder="Cari nama katering atau menu (e.g. Nasi Liwet, Bento, Salad)..." 
                               class="w-full bg-transparent text-slate-800 placeholder-slate-400 text-sm focus:outline-none py-2 font-medium">
                    </div>

                    <div class="sm:w-48 flex items-center px-3 gap-2 border-t sm:border-t-0 sm:border-l border-slate-200">
                        <i class="fa-solid fa-location-dot text-brand-500 text-base"></i>
                        <select name="city" class="w-full bg-transparent text-slate-800 text-sm focus:outline-none py-2 font-medium">
                            <option value="">Semua Kota</option>
                            <option value="Jakarta Selatan">Jakarta Selatan</option>
                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                            <option value="Tangerang Selatan">Tangerang Selatan</option>
                            <option value="Bandung">Bandung</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-brand-600 to-amber-500 hover:from-brand-700 hover:to-amber-600 text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-brand-500/30 text-sm transition-all flex items-center justify-center gap-2">
                        <span>Cari Menu</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

                <!-- Stats Bar -->
                <div class="pt-6 grid grid-cols-3 gap-4 border-t border-slate-800/80">
                    <div>
                        <span class="block text-2xl font-extrabold text-white">{{ \App\Models\MerchantProfile::where('status', 'active')->count() }}+</span>
                        <span class="text-xs text-slate-400">Mitra Katering Verified</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-extrabold text-white">{{ number_format(\App\Models\Menu::sum('sales_count')) }}+</span>
                        <span class="text-xs text-slate-400">Porsi Terkirim Tepat Waktu</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-extrabold text-white">100%</span>
                        <span class="text-xs text-slate-400">Invoice B2B Otomatis</span>
                    </div>
                </div>

            </div>

            <!-- Right Hero Image / Mockup Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-700/80 bg-slate-800">
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&auto=format&fit=crop&q=80" 
                             alt="CaterHub Catering Box" class="w-full h-80 object-cover opacity-90 hover:scale-105 transition-transform duration-700">
                        
                        <!-- Floating Badge 1 -->
                        <div class="absolute top-6 left-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-shield-check text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">100% Halal & Higienis</p>
                                <p class="text-[10px] text-slate-500">Sertifikasi Standar Dapur</p>
                            </div>
                        </div>

                        <!-- Floating Badge 2 -->
                        <div class="absolute bottom-6 right-6 bg-slate-900/90 text-white backdrop-blur-md p-4 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-500 text-white flex items-center justify-center font-bold">
                                <i class="fa-solid fa-file-invoice text-lg"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold">Auto B2B Invoice</p>
                                <p class="text-[10px] text-slate-400">Siap Cetak & Rekapitulasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Category Pills Section -->
<section class="py-12 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900">Kategori Menu Katering popular</h2>
            <p class="text-xs text-slate-500 mt-1">Pilih jenis santapan yang sesuai dengan kebutuhan acara kantor Anda.</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('search') }}?category={{ $cat->slug }}" 
                   class="group p-5 rounded-2xl bg-slate-50 hover:bg-gradient-to-br hover:from-brand-500 hover:to-amber-500 hover:text-white border border-slate-200/80 transition-all duration-300 text-center shadow-sm hover:shadow-xl hover:-translate-y-1">
                    <div class="w-12 h-12 rounded-xl bg-white text-brand-600 group-hover:bg-white/20 group-hover:text-white flex items-center justify-center mx-auto text-xl mb-3 transition-colors shadow-sm">
                        <i class="fa-solid {{ $cat->icon ?? 'fa-utensils' }}"></i>
                    </div>
                    <h3 class="font-bold text-xs group-hover:text-white text-slate-800">{{ $cat->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured B2B Catering Vendors Section -->
<section class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Vendor Pilihan</span>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Mitra Katering Terfavorit</h2>
                <p class="text-sm text-slate-500 mt-1">Penyedia jasa boga berpengalaman dengan rating & ulasan terbaik dari berbagai perusahaan.</p>
            </div>
            <a href="{{ route('search') }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand-600 hover:text-brand-700">
                Lihat Semua Vendor <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredMerchants as $merchant)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-md hover:shadow-2xl transition-all duration-300 flex flex-col group">
                    
                    <!-- Banner -->
                    <div class="relative h-48 bg-slate-200 overflow-hidden">
                        <img src="{{ $merchant->banner_url }}" alt="{{ $merchant->company_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        
                        <!-- Logo & Rating Badge -->
                        <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                            <div class="w-14 h-14 rounded-2xl bg-white p-1 shadow-lg overflow-hidden border-2 border-white">
                                <img src="{{ $merchant->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                            </div>
                            <div class="px-3 py-1.5 rounded-full bg-amber-400 text-slate-900 font-extrabold text-xs shadow-md flex items-center gap-1.5">
                                <i class="fa-solid fa-star text-slate-900"></i> {{ number_format($merchant->rating_avg, 1) }}
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span><i class="fa-solid fa-location-dot text-brand-500 mr-1"></i> {{ $merchant->city }}</span>
                                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full font-medium">{{ $merchant->cuisine_type }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                                <a href="{{ route('merchant.detail', $merchant->slug) }}">{{ $merchant->company_name }}</a>
                            </h3>
                            <p class="text-xs text-slate-600 line-clamp-2 mt-2 leading-relaxed">
                                {{ $merchant->description }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="block text-[10px] text-slate-400 font-semibold uppercase">Min. Pemesanan</span>
                                <span class="text-xs font-bold text-slate-800">Rp {{ number_format($merchant->min_order_amount, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('merchant.detail', $merchant->slug) }}" 
                               class="px-4 py-2 rounded-xl bg-slate-900 text-white hover:bg-brand-600 font-bold text-xs transition-colors shadow-sm">
                                Lihat Menu & Pesan
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Popular Corporate Menus Grid -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">Pilihan Menu Favorit</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Menu Makan Siang Terlaris</h2>
            <p class="text-sm text-slate-500 mt-1">Hidangan lezat siap saji yang sering dipesan oleh perusahaan rekanan kami.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($popularMenus as $menu)
                <div class="bg-slate-50 rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="relative h-44 overflow-hidden bg-slate-200">
                            <img src="{{ $menu->photo_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @if($menu->dietary_tags)
                                <div class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white px-2.5 py-1 rounded-full text-[10px] font-bold">
                                    {{ $menu->dietary_tags }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-5">
                            <span class="text-[11px] font-bold text-brand-600 uppercase tracking-wide">{{ $menu->merchant->company_name }}</span>
                            <h3 class="font-bold text-base text-slate-900 group-hover:text-brand-600 transition-colors mt-1 line-clamp-1">
                                {{ $menu->name }}
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 mt-2 leading-relaxed">
                                {{ $menu->description }}
                            </p>
                        </div>
                    </div>

                    <div class="p-5 pt-0">
                        <div class="pt-3 border-t border-slate-200/60 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-semibold">Harga per Porsi</span>
                                <span class="text-base font-extrabold text-brand-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('merchant.detail', $menu->merchant->slug) }}" 
                               class="w-10 h-10 rounded-xl bg-brand-50 hover:bg-brand-600 text-brand-600 hover:text-white flex items-center justify-center font-bold transition-all shadow-sm">
                                <i class="fa-solid fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How it Works B2B Section -->
<section id="cara-kerja" class="py-16 bg-slate-900 text-white border-y border-slate-800 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">Kemudahan Pemesanan</span>
            <h2 class="text-3xl font-extrabold text-white mt-1">Cara Kerja B2B CaterHub</h2>
            <p class="text-sm text-slate-400 mt-2">Langkah simpel dan transparan dalam memesan katering untuk kantor Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="bg-slate-800/80 p-8 rounded-3xl border border-slate-700/80 relative space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white font-extrabold text-xl flex items-center justify-center shadow-lg">
                    1
                </div>
                <h3 class="text-lg font-bold text-white">Registrasi Akun Kantor</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Daftarkan profil kantor Anda lengkap dengan alamat pengiriman dan PIC penanggung jawab.</p>
            </div>

            <div class="bg-slate-800/80 p-8 rounded-3xl border border-slate-700/80 relative space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white font-extrabold text-xl flex items-center justify-center shadow-lg">
                    2
                </div>
                <h3 class="text-lg font-bold text-white">Pilih Vendor & Menu</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Eksplorasi ragam menu Nusantara, Healthy Box, hingga Bento. Tentukan porsi & jadwal pengiriman.</p>
            </div>

            <div class="bg-slate-800/80 p-8 rounded-3xl border border-slate-700/80 relative space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white font-extrabold text-xl flex items-center justify-center shadow-lg">
                    3
                </div>
                <h3 class="text-lg font-bold text-white">Invoice B2B Otomatis</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Sistem langsung men-generate Invoice tagihan lengkap dengan rincian PBN & tanggal pembayaran.</p>
            </div>

            <div class="bg-slate-800/80 p-8 rounded-3xl border border-slate-700/80 relative space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white font-extrabold text-xl flex items-center justify-center shadow-lg">
                    4
                </div>
                <h3 class="text-lg font-bold text-white">Pengiriman Tepat Waktu</h3>
                <p class="text-xs text-slate-400 leading-relaxed">Katering tiba di lokasi kantor sebelum jam makan siang dengan pelacakan status pesanan real-time.</p>
            </div>

        </div>
    </div>
</section>
@endsection
