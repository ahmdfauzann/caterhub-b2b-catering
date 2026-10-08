@extends('layouts.app')

@section('title', 'Pencarian Katering B2B - CaterHub')

@section('content')
<div class="bg-slate-900 text-white py-12 border-b border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-extrabold">Cari Vendor & Menu Katering</h1>
        <p class="text-sm text-slate-400 mt-1">Temukan mitra katering terbaik sesuai lokasi kantor, preferensi menu, dan anggaran Anda.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Filter Sidebar (Col 4) -->
        <div class="lg:col-span-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm sticky top-28 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-extrabold text-slate-900 text-lg flex items-center gap-2">
                        <i class="fa-solid fa-filter text-brand-600"></i> Filter Pencarian
                    </h3>
                    <a href="{{ route('search') }}" class="text-xs text-brand-600 hover:underline font-semibold">Reset</a>
                </div>

                <form action="{{ route('search') }}" method="GET" class="space-y-5">
                    <!-- Keyword Search -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Kunci</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nama katering atau menu..." 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lokasi / Kota</label>
                        <select name="city" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                            <option value="">Semua Lokasi</option>
                            <option value="Jakarta Selatan" {{ request('city') == 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan</option>
                            <option value="Jakarta Pusat" {{ request('city') == 'Jakarta Pusat' ? 'selected' : '' }}>Jakarta Pusat</option>
                            <option value="Tangerang Selatan" {{ request('city') == 'Tangerang Selatan' ? 'selected' : '' }}>Tangerang Selatan</option>
                            <option value="Bandung" {{ request('city') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Menu</label>
                        <select name="category" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Max Price Filter -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Maksimal Harga per Porsi</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="e.g. 50000" 
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>

                    <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-brand-600/20">
                        Terapkan Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Results Grid (Col 8) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between text-sm text-slate-500">
                <p>Menampilkan <span class="font-bold text-slate-900">{{ $merchants->total() }}</span> vendor katering mitra.</p>
            </div>

            @if($merchants->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($merchants as $merchant)
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                            
                            <div class="relative h-44 bg-slate-200 overflow-hidden">
                                <img src="{{ $merchant->banner_url }}" alt="{{ $merchant->company_name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-3 left-3 right-3 flex justify-between items-end">
                                    <div class="w-12 h-12 rounded-xl bg-white p-1 shadow-md">
                                        <img src="{{ $merchant->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full bg-amber-400 text-slate-900 font-extrabold text-xs">
                                        <i class="fa-solid fa-star"></i> {{ number_format($merchant->rating_avg, 1) }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div>
                                    <span class="text-xs text-brand-600 font-bold block mb-1"><i class="fa-solid fa-location-dot mr-1"></i> {{ $merchant->city }}</span>
                                    <h3 class="text-lg font-bold text-slate-900 group-hover:text-brand-600 transition-colors">
                                        <a href="{{ route('merchant.detail', $merchant->slug) }}">{{ $merchant->company_name }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 mt-2 leading-relaxed">{{ $merchant->description }}</p>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] text-slate-400 font-semibold block">JENIS MASAKAN</span>
                                        <span class="text-xs font-bold text-slate-700">{{ $merchant->cuisine_type }}</span>
                                    </div>
                                    <a href="{{ route('merchant.detail', $merchant->slug) }}" 
                                       class="px-4 py-2 rounded-xl bg-brand-600 text-white font-bold text-xs hover:bg-brand-700 transition-colors">
                                        Pilih Menu
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <div class="pt-6">
                    {{ $merchants->links() }}
                </div>
            @else
                <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-2xl mx-auto">
                        <i class="fa-solid fa-store-slash"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Katering Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Coba ubah filter pencarian atau gunakan kata kunci lokasi yang lebih umum.</p>
                    <a href="{{ route('search') }}" class="inline-block px-5 py-2.5 bg-slate-900 text-white text-xs font-bold rounded-xl">Lihat Semua Katering</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
