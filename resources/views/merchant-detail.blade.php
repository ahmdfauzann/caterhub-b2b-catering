@extends('layouts.app')

@section('title', $merchant->company_name . ' - Detail Katering & Menu')

@section('content')
<!-- Merchant Hero Banner -->
<div class="relative bg-slate-900 text-white h-72 lg:h-96">
    <img src="{{ $merchant->banner_url }}" alt="{{ $merchant->company_name }}" class="w-full h-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>

    <div class="absolute bottom-0 inset-x-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
            
            <div class="flex items-end gap-5">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white p-2 shadow-2xl overflow-hidden border-4 border-white shrink-0">
                    <img src="{{ $merchant->logo_url }}" alt="Logo" class="w-full h-full object-cover rounded-2xl">
                </div>
                <div class="space-y-1">
                    <span class="px-3 py-1 rounded-full bg-brand-600/90 text-white text-[11px] font-bold tracking-wide uppercase">
                        {{ $merchant->cuisine_type }}
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-white">{{ $merchant->company_name }}</h1>
                    <p class="text-xs sm:text-sm text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-brand-500"></i> {{ $merchant->address }}, {{ $merchant->city }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4 bg-slate-800/80 backdrop-blur-md p-4 rounded-2xl border border-slate-700/80">
                <div class="text-center px-3 border-r border-slate-700">
                    <span class="block text-xl font-extrabold text-amber-400"><i class="fa-solid fa-star"></i> {{ number_format($merchant->rating_avg, 1) }}</span>
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Rating Ulasan</span>
                </div>
                <div class="text-center px-3">
                    <span class="block text-xl font-extrabold text-emerald-400">{{ $totalSales }}</span>
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Pesanan Selesai</span>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Main Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Menu List (Col 8) -->
        <div class="lg:col-span-8 space-y-12">
            
            <!-- Description Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-3">
                <h3 class="font-extrabold text-slate-900 text-base">Tentang {{ $merchant->company_name }}</h3>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $merchant->description }}</p>
                <div class="pt-3 border-t border-slate-100 flex items-center gap-6 text-xs text-slate-500">
                    <span><i class="fa-solid fa-phone text-brand-600 mr-1.5"></i> {{ $merchant->phone }}</span>
                    <span><i class="fa-solid fa-box-open text-brand-600 mr-1.5"></i> Min. Order: Rp {{ number_format($merchant->min_order_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Menus Grouped by Category -->
            @foreach($menusGrouped as $categoryName => $menus)
                <div class="space-y-4">
                    <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <i class="fa-solid fa-utensils text-brand-600"></i> {{ $categoryName }}
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($menus as $menu)
                            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                                <div>
                                    <div class="relative h-44 overflow-hidden bg-slate-100">
                                        <img src="{{ $menu->photo_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @if($menu->dietary_tags)
                                            <div class="absolute top-3 left-3 bg-slate-900/80 text-white px-2.5 py-1 rounded-full text-[10px] font-bold">
                                                {{ $menu->dietary_tags }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-5">
                                        <h3 class="font-extrabold text-base text-slate-900 group-hover:text-brand-600 transition-colors">
                                            {{ $menu->name }}
                                        </h3>
                                        <p class="text-xs text-slate-500 line-clamp-2 mt-2 leading-relaxed">
                                            {{ $menu->description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="p-5 pt-0 space-y-3">
                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                        <div>
                                            <span class="text-[10px] text-slate-400 block font-semibold">Min. {{ $menu->min_portion }} porsi</span>
                                            <span class="text-lg font-extrabold text-brand-600">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Order Form with Quantity Selector -->
                                    <form action="{{ route('customer.cart.add') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-slate-50">
                                            <span class="px-2 text-[10px] font-bold text-slate-400 uppercase">Porsi:</span>
                                            <input type="number" name="quantity" value="{{ $menu->min_portion }}" min="{{ $menu->min_portion }}" 
                                                   class="w-16 px-2 py-1.5 text-xs font-bold text-center bg-transparent outline-none">
                                        </div>
                                        <button type="submit" class="flex-1 py-2 bg-gradient-to-r from-brand-600 to-amber-500 hover:from-brand-700 text-white rounded-xl font-bold text-xs shadow-md shadow-brand-600/20 transition-all flex items-center justify-center gap-1.5">
                                            <i class="fa-solid fa-cart-plus"></i> Tambah
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Customer Reviews Section -->
            <div class="pt-8 border-t border-slate-200 space-y-6">
                <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-comments text-amber-500"></i> Ulasan Klien Perusahaan ({{ $merchant->reviews->count() }})
                </h2>

                @if($merchant->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($merchant->reviews as $review)
                            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-slate-900 text-white font-bold text-xs flex items-center justify-center">
                                            {{ substr($review->customer->name ?? 'C', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-xs text-slate-900">{{ $review->customer->name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-amber-400 text-xs font-bold">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-xs text-slate-600 leading-relaxed italic bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        "{{ $review->comment }}"
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Belum ada ulasan untuk vendor ini.</p>
                @endif
            </div>

        </div>

        <!-- Right: Floating Order Sticky Summary (Col 4) -->
        <div class="lg:col-span-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-lg sticky top-28 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="font-extrabold text-slate-900 text-lg flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-brand-600"></i> Ringkasan Pesanan
                    </h3>
                </div>

                @php
                    $cart = session()->get('cart', []);
                    $cartMerchantId = !empty($cart) ? (reset($cart)['merchant_id'] ?? null) : null;
                    $isSameMerchant = ($cartMerchantId == $merchant->id);
                    $totalCartItems = $isSameMerchant ? count($cart) : 0;
                    $cartTotal = 0;
                    if($isSameMerchant) {
                        foreach($cart as $item) {
                            $cartTotal += $item['price'] * $item['quantity'];
                        }
                    }
                @endphp

                @if(!empty($cart) && $isSameMerchant)
                    <div class="space-y-3">
                        @foreach($cart as $item)
                            <div class="flex items-center justify-between text-xs">
                                <div class="truncate max-w-[160px]">
                                    <span class="font-bold text-slate-800 block truncate">{{ $item['name'] }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $item['quantity'] }} porsi x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                                <span class="font-bold text-slate-900">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">Subtotal Pesanan</span>
                            <span class="text-base font-extrabold text-brand-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('customer.cart') }}" class="block w-full py-3 bg-gradient-to-r from-brand-600 to-amber-500 text-white font-bold text-center text-xs rounded-xl shadow-md hover:from-brand-700 transition-all">
                            Lanjut ke Keranjang & Checkout
                        </a>
                    </div>
                @else
                    <div class="text-center py-6 text-slate-400 space-y-2">
                        <i class="fa-solid fa-basket-shopping text-3xl text-slate-300"></i>
                        <p class="text-xs">Keranjang Anda masih kosong untuk katering ini.</p>
                        <p class="text-[11px] text-slate-400">Pilih menu di sebelah kiri dan atur porsi untuk menambah ke keranjang.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
