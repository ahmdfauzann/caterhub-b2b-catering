@extends('layouts.app')

@section('title', 'Keranjang Pesanan Katering - CaterHub')

@section('content')
<div class="py-10 bg-slate-100 min-h-[85vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Keranjang Pesanan Kantor</h1>
                <p class="text-xs text-slate-500 mt-1">Tinjau daftar menu makanan dan jumlah porsi sebelum melanjutkan ke checkout.</p>
            </div>
            
            @if(!empty($cart))
                <form action="{{ route('customer.cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs text-rose-600 font-bold hover:underline">
                        <i class="fa-solid fa-trash-can mr-1"></i> Kosongkan Keranjang
                    </button>
                </form>
            @endif
        </div>

        @auth
            @if(auth()->user()->isMerchant())
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-xl"></i>
                        <div>
                            <span class="font-bold">Informasi Peran (Role Merchant):</span> Anda saat ini masuk sebagai <strong>Mitra Katering ({{ optional(auth()->user()->merchantProfile)->company_name ?? auth()->user()->name }})</strong>.
                            <span class="block text-[11px] text-amber-700">Fitur checkout & pendaftaran invoice B2B diperuntukkan bagi **Akun Kantor (Customer)**.</span>
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold text-xs rounded-xl shadow-sm text-center shrink-0">
                        Switch ke Akun Kantor
                    </a>
                </div>
            @endif
        @endauth

        @if(!empty($cart))
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Cart Items List (Col 8) -->
                <div class="lg:col-span-8 space-y-4">
                    
                    @if($merchant)
                        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 p-1 border">
                                <img src="{{ $merchant->logo_url }}" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">VENDOR KATERING TERPILIH</span>
                                <h3 class="font-extrabold text-slate-900 text-sm">{{ $merchant->company_name }}</h3>
                            </div>
                        </div>
                    @endif

                    @php $subtotal = 0; @endphp
                    @foreach($cart as $menuId => $item)
                        @php $itemTotal = $item['price'] * $item['quantity']; $subtotal += $itemTotal; @endphp
                        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                            
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <img src="{{ $item['photo_url'] }}" alt="{{ $item['name'] }}" class="w-20 h-20 rounded-2xl object-cover border border-slate-100">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-base">{{ $item['name'] }}</h4>
                                    <p class="text-xs text-brand-600 font-bold mt-0.5">Rp {{ number_format($item['price'], 0, ',', '.') }} / porsi</p>
                                    <span class="text-[10px] text-slate-400">Min. order: {{ $item['min_portion'] }} porsi</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                
                                <!-- Quantity Form -->
                                <form action="{{ route('customer.cart.update') }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menuId }}">
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="{{ $item['min_portion'] }}" 
                                           class="w-20 px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-bold text-center outline-none focus:border-brand-500">
                                    <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-brand-50 hover:text-brand-600 text-slate-600 text-xs font-bold transition-colors">
                                        Update
                                    </button>
                                </form>

                                <div class="text-right">
                                    <span class="block text-xs font-extrabold text-slate-900">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                                    
                                    <form action="{{ route('customer.cart.remove', $menuId) }}" method="POST" class="mt-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] text-rose-500 hover:underline">Hapus</button>
                                    </form>
                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

                <!-- Order Calculation Summary (Col 4) -->
                <div class="lg:col-span-4">
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-lg space-y-5 sticky top-28">
                        <h3 class="font-extrabold text-slate-900 text-lg border-b border-slate-100 pb-3">Ringkasan Biaya B2B</h3>

                        @php
                            $tax = $subtotal * 0.10;
                            $deliveryFee = $subtotal >= 1000000 ? 0 : 35000;
                            $grandTotal = $subtotal + $tax + $deliveryFee;
                        @endphp

                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between text-slate-600">
                                <span>Subtotal Porsi</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between text-slate-600">
                                <span>PBN (Pajak 10%)</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between text-slate-600">
                                <span>Biaya Pengiriman</span>
                                @if($deliveryFee === 0)
                                    <span class="font-bold text-emerald-600 uppercase text-[10px]">Gratis Ongkir B2B</span>
                                @else
                                    <span class="font-bold text-slate-900">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-slate-200 flex justify-between items-center text-sm">
                                <span class="font-extrabold text-slate-900">Total Biaya</span>
                                <span class="font-extrabold text-xl text-brand-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <a href="{{ route('customer.checkout') }}" class="block w-full py-3.5 bg-gradient-to-r from-brand-600 to-amber-500 hover:from-brand-700 text-white font-bold text-center text-xs rounded-xl shadow-lg shadow-brand-500/20 transition-all">
                            Lanjut ke Pengisian Alamat & Invoice <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

            </div>
        @else
            <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-basket-shopping"></i>
                </div>
                <h2 class="text-xl font-extrabold text-slate-800">Keranjang Pesanan Anda Kosong</h2>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">Silakan cari katering favorit kantor Anda dan tambahkan menu ke keranjang.</p>
                <a href="{{ route('search') }}" class="inline-block px-6 py-3 bg-brand-600 text-white font-bold text-xs rounded-xl shadow-md">
                    Cari Menu Katering
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
