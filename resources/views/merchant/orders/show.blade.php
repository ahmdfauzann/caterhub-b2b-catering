@extends('layouts.merchant')

@section('title', 'Detail Pesanan #' . $order->order_code)
@section('page-title', 'Detail & Update Status Pesanan')

@section('content')
<div class="max-w-4xl space-y-6">
    
    <a href="{{ route('merchant.orders.index') }}" class="text-xs text-brand-600 font-bold hover:underline inline-block mb-2">
        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Pesanan
    </a>

    <!-- Status Updater Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Status Pesanan Saat Ini</span>
            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold border {{ $order->status_badge }}">
                {{ $order->status_label }}
            </span>
        </div>

        <!-- Update Status Form -->
        <form action="{{ route('merchant.orders.update-status', $order->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            @method('PATCH')
            <select name="status" class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs font-bold outline-none focus:border-brand-500">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu Konfirmasi)</option>
                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Konfirmasi & Terima Pesanan</option>
                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Sedang Dimasak / Disiapkan</option>
                <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Dalam Pengiriman Kurir</option>
                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Sampai di Lokasi Kantor</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Pesanan Selesai</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batalkan Pesanan</option>
            </select>

            <button type="submit" class="px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                Update Status
            </button>
        </form>
    </div>

    <!-- Client Info & Order Summary -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
            <div>
                <span class="text-[10px] text-brand-600 font-bold uppercase tracking-wider block">INFORMASI KLIEN KANTOR</span>
                <h3 class="font-extrabold text-slate-900 text-base mt-1">{{ optional($order->customer->customerProfile)->company_name ?? $order->customer->name }}</h3>
                <p class="text-xs text-slate-600 mt-1"><i class="fa-solid fa-user text-slate-400 mr-1.5"></i> PIC: {{ $order->customer->name }}</p>
                <p class="text-xs text-slate-600 mt-1"><i class="fa-solid fa-phone text-slate-400 mr-1.5"></i> {{ $order->customer->phone ?? '081234567890' }}</p>
            </div>

            <div>
                <span class="text-[10px] text-brand-600 font-bold uppercase tracking-wider block">JADWAL PENGIRIMAN</span>
                <p class="text-sm font-extrabold text-slate-900 mt-1"><i class="fa-solid fa-calendar text-brand-500 mr-1.5"></i> {{ $order->delivery_date->format('d M Y') }}</p>
                <p class="text-xs text-slate-600 mt-1"><i class="fa-solid fa-clock text-brand-500 mr-1.5"></i> {{ $order->delivery_time }}</p>
                <p class="text-xs text-slate-600 mt-1"><i class="fa-solid fa-location-dot text-brand-500 mr-1.5"></i> {{ $order->delivery_address }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div>
            <h4 class="font-bold text-slate-900 text-sm mb-3">Daftar Menu & Porsi Makanan</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200">
                            <th class="p-3">Menu Makanan</th>
                            <th class="p-3 text-center">Harga / Porsi</th>
                            <th class="p-3 text-center">Jumlah Porsi</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="p-3 font-bold text-slate-900">{{ $item->menu_name }}</td>
                                <td class="p-3 text-center text-slate-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="p-3 text-center font-bold text-slate-800">{{ $item->quantity }} porsi</td>
                                <td class="p-3 text-right font-extrabold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-between items-center text-xs">
            @if($order->invoice)
                <a href="{{ route('invoice.show', $order->invoice->invoice_number) }}" target="_blank" class="px-4 py-2 bg-amber-500 text-slate-900 font-bold rounded-xl hover:bg-amber-400 transition-colors">
                    <i class="fa-solid fa-print mr-1"></i> Cetak Invoice Tagihan
                </a>
            @else
                <div></div>
            @endif

            <div class="text-right">
                <span class="block text-[10px] text-slate-400 font-semibold uppercase">GRAND TOTAL</span>
                <span class="text-lg font-extrabold text-brand-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>

</div>
@endsection
