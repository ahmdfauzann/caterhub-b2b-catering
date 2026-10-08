@extends('layouts.app')

@section('title', 'Dasbor Kantor - CaterHub')

@section('content')
<div class="py-10 bg-slate-100 min-h-[85vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-brand-900 p-8 rounded-3xl text-white shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6 relative overflow-hidden">
            <div class="space-y-2 relative z-10">
                <span class="px-3 py-1 rounded-full bg-brand-500/20 text-brand-400 text-xs font-bold border border-brand-500/30">
                    <i class="fa-solid fa-building mr-1"></i> {{ $customer->company_name ?? auth()->user()->name }}
                </span>
                <h1 class="text-3xl font-extrabold">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="text-xs text-slate-300">Kelola pemesanan katering makan siang dan invoice tagihan kantor Anda.</p>
            </div>

            <div class="flex items-center gap-3 relative z-10">
                <a href="{{ route('search') }}" class="px-5 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-lg shadow-brand-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Pesan Katering Baru
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <div>
                    <span class="block text-2xl font-extrabold text-slate-900">{{ $activeOrders->count() }}</span>
                    <span class="text-xs text-slate-500">Pesanan Aktif Berjalan</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <span class="block text-2xl font-extrabold text-slate-900">{{ $completedOrdersCount }}</span>
                    <span class="text-xs text-slate-500">Pesanan Selesai</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <span class="block text-xl font-extrabold text-slate-900">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
                    <span class="text-xs text-slate-500">Total Pengeluaran B2B</span>
                </div>
            </div>
        </div>

        <!-- Active Orders Section -->
        <div class="space-y-4">
            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-brand-600"></i> Status Pesanan Aktif Saat Ini
            </h2>

            @if($activeOrders->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($activeOrders as $order)
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <div>
                                    <span class="text-xs font-bold text-slate-400 block">{{ $order->order_code }}</span>
                                    <h3 class="font-extrabold text-slate-900 text-base">{{ $order->merchant->company_name }}</h3>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $order->status_badge }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>

                            <div class="space-y-2 text-xs text-slate-600">
                                <p><i class="fa-solid fa-calendar text-brand-500 mr-2"></i> {{ $order->delivery_date->format('d M Y') }} ({{ $order->delivery_time }})</p>
                                <p><i class="fa-solid fa-location-dot text-brand-500 mr-2"></i> {{ Str::limit($order->delivery_address, 45) }}</p>
                                <p><i class="fa-solid fa-utensils text-brand-500 mr-2"></i> {{ $order->items->count() }} menu (Total {{ $order->items->sum('quantity') }} porsi)</p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-sm font-extrabold text-brand-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-brand-600 transition-colors">
                                        Detail Pesanan
                                    </a>
                                    @if($order->invoice)
                                        <a href="{{ route('invoice.show', $order->invoice->invoice_number) }}" class="px-3.5 py-2 bg-amber-500 text-slate-900 rounded-xl font-bold text-xs hover:bg-amber-400 transition-colors">
                                            Invoice
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white p-8 rounded-3xl border border-slate-200 text-center text-xs text-slate-500 space-y-2">
                    <p>Tidak ada pesanan yang sedang berlangsung saat ini.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
