@extends('layouts.merchant')

@section('title', 'Daftar Pesanan & Invoice - Merchant Portal')
@section('page-title', 'Daftar Pesanan Masuk')

@section('content')
<div class="space-y-6">
    
    <!-- Status Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between gap-4">
        <form action="{{ route('merchant.orders.index') }}" method="GET" class="flex items-center gap-3">
            <select name="status" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-medium outline-none">
                <option value="">Semua Status Pesanan</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Konfirmasi)</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed (Dikonfirmasi)</option>
                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing (Dimasak)</option>
                <option value="delivering" {{ request('status') == 'delivering' ? 'selected' : '' }}>Delivering (Dalam Pengiriman)</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered (Sampai Lokasi)</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
            </select>
            
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-brand-600 transition-colors">
                Filter Status
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="p-4">Kode Order</th>
                            <th class="p-4">Klien Perusahaan</th>
                            <th class="p-4">Tgl Pengiriman</th>
                            <th class="p-4">Porsi & Grand Total</th>
                            <th class="p-4">Status Pesanan</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50">
                                <td class="p-4 font-bold text-slate-900">
                                    {{ $order->order_code }}
                                    <span class="block text-[10px] text-slate-400 font-normal">{{ $order->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td class="p-4 font-bold text-slate-800">
                                    {{ $order->customer->name }}
                                    <span class="block text-[10px] text-slate-400 font-normal">{{ optional($order->customer->customerProfile)->company_name ?? 'Client Kantor' }}</span>
                                </td>
                                <td class="p-4 text-slate-700">
                                    <span class="font-bold">{{ $order->delivery_date->format('d M Y') }}</span>
                                    <span class="block text-[10px] text-slate-400">{{ $order->delivery_time }}</span>
                                </td>
                                <td class="p-4 font-extrabold text-brand-600">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    <span class="block text-[10px] text-slate-500 font-normal">{{ $order->items->sum('quantity') }} porsi</span>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    <a href="{{ route('merchant.orders.show', $order->id) }}" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-brand-600 transition-colors">
                                        Kelola & Status
                                    </a>
                                    @if($order->invoice)
                                        <a href="{{ route('invoice.show', $order->invoice->invoice_number) }}" target="_blank" class="px-3.5 py-1.5 bg-amber-500 text-slate-900 rounded-xl text-xs font-bold hover:bg-amber-400 transition-colors">
                                            Invoice
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $orders->links() }}
            </div>
        @else
            <div class="p-12 text-center text-xs text-slate-400 space-y-2">
                <i class="fa-solid fa-receipt text-3xl text-slate-300"></i>
                <p>Belum ada pesanan masuk dalam kategori status ini.</p>
            </div>
        @endif
    </div>

</div>
@endsection
