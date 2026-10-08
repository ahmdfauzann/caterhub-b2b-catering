@extends('layouts.app')

@section('title', 'Invoice Tagihan #' . $invoice->invoice_number)

@section('content')
<div class="py-10 bg-slate-100 min-h-[85vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Printable Action Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('customer.orders.index') }}" class="text-xs font-bold text-slate-600 hover:text-brand-600 flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
            </a>

            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Cetak / Unduh PDF Invoice
                </button>
            </div>
        </div>

        <!-- Invoice Document Paper -->
        <div id="invoice-paper" class="bg-white p-8 sm:p-12 rounded-3xl border border-slate-200 shadow-xl space-y-8">
            
            <!-- Invoice Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-200 pb-8 gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center font-bold text-xl">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-900">CaterHub</span>
                    </div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Platform Marketplace Katering B2B</p>
                </div>

                <div class="text-left sm:text-right space-y-1">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $invoice->status === 'paid' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        {{ $invoice->status === 'paid' ? 'LUNAS / PAID' : 'MENUNGGU PEMBAYARAN' }}
                    </span>
                    <h2 class="text-xl font-extrabold text-slate-900 mt-1">{{ $invoice->invoice_number }}</h2>
                    <p class="text-xs text-slate-500">Tgl Diterbitkan: {{ $invoice->issue_date->format('d M Y') }}</p>
                    <p class="text-xs text-slate-500">Tgl Jatuh Tempo: {{ $invoice->due_date->format('d M Y') }}</p>
                </div>
            </div>

            <!-- B2B Entity Info: Vendor (Merchant) & Customer (Kantor) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs">
                
                <!-- Vendor Info -->
                <div class="space-y-2 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">DITERBITKAN OLEH (VENDOR KATERING)</span>
                    <h3 class="font-extrabold text-slate-900 text-sm">{{ $invoice->order->merchant->company_name }}</h3>
                    <p class="text-slate-600">{{ $invoice->order->merchant->address }}, {{ $invoice->order->merchant->city }}</p>
                    <p class="text-slate-600">Telepon: {{ $invoice->order->merchant->phone }}</p>
                </div>

                <!-- Customer Info -->
                <div class="space-y-2 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <span class="text-[10px] font-bold text-brand-600 uppercase tracking-wider block">DITAGIHKAN KEPADA (KLIEN KANTOR)</span>
                    <h3 class="font-extrabold text-slate-900 text-sm">{{ optional($invoice->order->customer->customerProfile)->company_name ?? $invoice->order->customer->name }}</h3>
                    <p class="text-slate-600">Attn: {{ $invoice->order->customer->name }}</p>
                    <p class="text-slate-600">{{ $invoice->order->delivery_address }}</p>
                    <p class="text-slate-600">Telepon: {{ $invoice->order->customer->phone ?? '081234567890' }}</p>
                </div>

            </div>

            <!-- Delivery Details -->
            <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                <div>
                    <span class="font-bold text-amber-900">Jadwal Pengiriman Makan Siang:</span>
                    <span class="text-amber-800 ml-1">{{ $invoice->order->delivery_date->format('d M Y') }} ({{ $invoice->order->delivery_time }})</span>
                </div>
                <div>
                    <span class="font-bold text-amber-900">Kode Referensi Order:</span>
                    <span class="text-amber-800 font-mono ml-1">{{ $invoice->order->order_code }}</span>
                </div>
            </div>

            <!-- Itemized Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 font-extrabold uppercase border-b border-slate-200">
                            <th class="p-3">No</th>
                            <th class="p-3">Deskripsi Menu Makanan</th>
                            <th class="p-3 text-center">Harga Satuan</th>
                            <th class="p-3 text-center">Jumlah Porsi</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @foreach($invoice->order->items as $index => $item)
                            <tr>
                                <td class="p-3 text-slate-400">{{ $index + 1 }}</td>
                                <td class="p-3 font-bold text-slate-900">{{ $item->menu_name }}</td>
                                <td class="p-3 text-center text-slate-600">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="p-3 text-center font-bold text-slate-800">{{ $item->quantity }} porsi</td>
                                <td class="p-3 text-right font-extrabold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financial Calculation Summary -->
            <div class="flex flex-col sm:flex-row justify-between items-start pt-4 border-t border-slate-200 gap-6 text-xs">
                
                <!-- Payment Instructions -->
                <div class="space-y-2 max-w-sm">
                    <span class="font-bold text-slate-800 uppercase tracking-wider block">Instruksi Pembayaran:</span>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-[11px] space-y-1 text-slate-600">
                        <p class="font-bold text-slate-800">Metode: {{ $invoice->order->payment_method }}</p>
                        <p>Bank BNI Virtual Account: <span class="font-mono font-bold text-slate-900">8891 0023 9912 0012</span></p>
                        <p>Atas Nama: <span class="font-bold text-slate-900">PT CaterHub Digital B2B</span></p>
                    </div>

                    @if($invoice->status !== 'paid' && auth()->check() && auth()->user()->isCustomer())
                        <form action="{{ route('customer.orders.payment', $invoice->order->id) }}" method="POST" class="pt-2">
                            @csrf
                            <button type="submit" class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                                <i class="fa-solid fa-check-circle mr-1"></i> Konfirmasi Pembayaran Lunas
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Totals -->
                <div class="w-full sm:w-64 space-y-2 text-slate-600">
                    <div class="flex justify-between">
                        <span>Subtotal Porsi</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($invoice->order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>PBN (Pajak 10%)</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($invoice->order->tax_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Pengiriman</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($invoice->order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-slate-300 flex justify-between text-base font-extrabold text-slate-900">
                        <span>TOTAL TAGIHAN</span>
                        <span class="text-brand-600">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

            <!-- Footer Seal & Signature -->
            <div class="pt-10 border-t border-slate-200 flex justify-between items-end text-[11px] text-slate-400">
                <div>
                    <p class="font-bold text-slate-700">Catatan:</p>
                    <p>Invoice ini dibuat dan diverifikasi secara sah oleh sistem digital CaterHub B2B Marketplace.</p>
                </div>
                <div class="text-center">
                    <div class="w-24 h-12 border-b border-slate-300 mb-1 mx-auto flex items-center justify-center italic text-slate-300 font-serif">
                        Verified Seal
                    </div>
                    <span class="font-bold text-slate-700 block">Departemen Keuangan</span>
                    <span>CaterHub B2B System</span>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
@media print {
    header, footer, nav, button, a[href*="customer"] { display: none !important; }
    body { background: white !important; }
    #invoice-paper { border: none !important; shadow: none !important; padding: 0 !important; }
}
</style>
@endsection
