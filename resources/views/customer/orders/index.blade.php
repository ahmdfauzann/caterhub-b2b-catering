@extends('layouts.app')

@section('title', 'Riwayat Pesanan Katering Kantor - CaterHub')

@section('content')
<div class="py-10 bg-slate-100 min-h-[85vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Riwayat Pesanan Katering</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar semua pesanan katering dan invoice tagihan kantor Anda.</p>
            </div>
            <a href="{{ route('search') }}" class="px-4 py-2.5 bg-brand-600 text-white rounded-xl font-bold text-xs hover:bg-brand-700 shadow-md">
                + Pesan Katering Baru
            </a>
        </div>

        @if($orders->count() > 0)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="p-4">Kode Pesanan</th>
                                <th class="p-4">Vendor Katering</th>
                                <th class="p-4">Tgl Pengiriman</th>
                                <th class="p-4">Total Biaya</th>
                                <th class="p-4">Status Pesanan</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-medium">
                            @foreach($orders as $order)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 font-bold text-slate-900">
                                        {{ $order->order_code }}
                                        <span class="block text-[10px] text-slate-400 font-normal">{{ $order->created_at->format('d M Y H:i') }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span class="font-bold text-slate-800">{{ $order->merchant->company_name }}</span>
                                        <span class="block text-[10px] text-slate-400">{{ $order->items->count() }} menu ({{ $order->items->sum('quantity') }} porsi)</span>
                                    </td>
                                    <td class="p-4">
                                        <span class="font-bold text-slate-800">{{ $order->delivery_date->format('d M Y') }}</span>
                                        <span class="block text-[10px] text-slate-500">{{ $order->delivery_time }}</span>
                                    </td>
                                    <td class="p-4 font-extrabold text-brand-600">
                                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $order->status_badge }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right space-x-2">
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="px-3 py-1.5 bg-slate-900 text-white rounded-xl font-bold text-[11px] hover:bg-brand-600 transition-colors">
                                            Detail
                                        </a>
                                        @if($order->invoice)
                                            <a href="{{ route('invoice.show', $order->invoice->invoice_number) }}" class="px-3 py-1.5 bg-amber-500 text-slate-900 rounded-xl font-bold text-[11px] hover:bg-amber-400 transition-colors">
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
            </div>
        @else
            <div class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3">
                <i class="fa-solid fa-receipt text-3xl text-slate-300"></i>
                <p class="text-xs text-slate-500">Belum ada riwayat pesanan katering.</p>
            </div>
        @endif

    </div>
</div>
@endsection
