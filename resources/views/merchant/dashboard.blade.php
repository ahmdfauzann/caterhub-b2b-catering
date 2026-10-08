@extends('layouts.merchant')

@section('title', 'Dashboard Merchant - CaterHub')
@section('page-title', 'Overview Katering')

@section('content')
<div class="space-y-8">
    
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Total Pendapatan</span>
                <span class="text-xl font-extrabold text-brand-600 mt-1 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Total Pesanan</span>
                <span class="text-2xl font-extrabold text-slate-900 mt-1 block">{{ $totalOrders }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Pesanan Pending</span>
                <span class="text-2xl font-extrabold text-amber-600 mt-1 block">{{ $pendingOrders }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 block uppercase tracking-wider">Jumlah Menu</span>
                <span class="text-2xl font-extrabold text-emerald-600 mt-1 block">{{ $totalMenus }}</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-utensils"></i>
            </div>
        </div>

    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-4 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg">Pesanan Terakhir Masuk</h3>
                <p class="text-xs text-slate-500">Tinjau dan konfirmasi pesanan dari klien kantor.</p>
            </div>
            <a href="{{ route('merchant.orders.index') }}" class="text-xs font-bold text-brand-600 hover:underline">Lihat Semua Pesanan</a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="p-3">Kode Order</th>
                            <th class="p-3">Klien Perusahaan</th>
                            <th class="p-3">Tgl Pengiriman</th>
                            <th class="p-3">Porsi & Total</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-medium">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-bold text-slate-900">{{ $order->order_code }}</td>
                                <td class="p-3 font-bold text-slate-800">{{ $order->customer->name }}</td>
                                <td class="p-3 text-slate-600">{{ $order->delivery_date->format('d M Y') }} ({{ $order->delivery_time }})</td>
                                <td class="p-3 font-bold text-brand-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }} ({{ $order->items->sum('quantity') }} porsi)</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $order->status_badge }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <a href="{{ route('merchant.orders.show', $order->id) }}" class="px-3 py-1 bg-slate-900 text-white rounded-lg text-xs font-bold hover:bg-brand-600 transition-colors">
                                        Kelola
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-xs text-slate-400 italic text-center py-6">Belum ada pesanan masuk.</p>
        @endif
    </div>

</div>
@endsection
