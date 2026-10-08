@extends('layouts.app')

@section('title', 'Checkout Pesanan & Generate Invoice - CaterHub')

@section('content')
<div class="py-10 bg-slate-100 min-h-[85vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Checkout & Penerbitan Invoice</h1>
            <p class="text-xs text-slate-500 mt-1">Lengkapi informasi pengiriman dan metode pembayaran B2B perusahaan Anda.</p>
        </div>

        <form action="{{ route('customer.checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf

            <!-- Form Left (Col 7) -->
            <div class="lg:col-span-7 space-y-6">
                
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-truck text-brand-600"></i> Informasi Pengiriman Makan Siang
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Pengiriman *</label>
                            <input type="date" name="delivery_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" min="{{ date('Y-m-d') }}" required
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sesi Waktu Pengiriman *</label>
                            <select name="delivery_time" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none font-medium">
                                <option value="08:00 - 08:30 WIB">08:00 - 08:30 WIB</option>
                                <option value="09:30 - 10:00 WIB">09:30 - 10:00 WIB</option>
                                <option value="11:00 - 11:30 WIB">11:00 - 11:30 WIB</option>
                                <option value="11:30 - 12:00 WIB" selected>11:30 - 12:00 WIB</option>
                                <option value="12:00 - 12:30 WIB">12:00 - 12:30 WIB</option>
                                <option value="12:30 - 13:00 WIB">12:30 - 13:00 WIB</option>
                                <option value="15:30 - 16:00 WIB">15:30 - 16:00 WIB</option>
                                <option value="17:30 - 18:00 WIB">17:30 - 18:00 WIB</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap Pengiriman & Lantai Kantor *</label>
                        <textarea name="delivery_address" rows="3" required placeholder="Gedung, Lantai, Ruangan, No. Telepon Penerima..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none font-medium">{{ old('delivery_address', $customer->office_address ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Khusus Katering (Opsional)</label>
                        <input type="text" name="notes" placeholder="e.g. Sambal dipisah, pisahkan sendok kayu ramah lingkungan..."
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none">
                    </div>
                </div>

                <!-- Payment Method Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-file-invoice-dollar text-brand-600"></i> Metode Pembayaran B2B
                    </h3>

                    <div class="space-y-3">
                        <label class="flex items-center justify-between p-4 rounded-2xl border-2 border-brand-500 bg-brand-50/30 cursor-pointer shadow-sm">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="payment_method" value="Corporate Billing" checked class="text-brand-600 focus:ring-brand-500">
                                <div>
                                    <span class="block font-bold text-xs text-slate-900">Corporate Billing</span>
                                    <span class="text-[10px] text-slate-500">Tagihan Resmi B2B Invoice Pasca-Pengiriman</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-brand-600 bg-brand-100 px-2.5 py-1 rounded-full">B2B Invoice</span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Summary Breakdown Right (Col 5) -->
            <div class="lg:col-span-5">
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-lg space-y-6 sticky top-28">
                    
                    <div class="border-b border-slate-100 pb-4">
                        <span class="text-[10px] text-brand-600 font-bold uppercase">Ringkasan Pemesanan</span>
                        <h3 class="font-extrabold text-slate-900 text-lg">{{ $merchant->company_name }}</h3>
                    </div>

                    <div class="space-y-3 max-h-56 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                            <div class="flex items-center justify-between text-xs">
                                <div class="truncate max-w-[180px]">
                                    <span class="font-bold text-slate-900 block truncate">{{ $item['name'] }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $item['quantity'] }} porsi @ Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                                <span class="font-bold text-slate-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-2 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal Menu</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>PBN (Pajak 10%)</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Ongkos Kirim B2B</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-3 border-t border-slate-200 flex justify-between items-center text-base font-extrabold">
                            <span class="text-slate-900">Grand Total</span>
                            <span class="text-brand-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-brand-600 to-amber-500 hover:from-brand-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-brand-500/20 transition-all">
                        Konfirmasi & Terbitkan Invoice
                    </button>
                    
                    <p class="text-[10px] text-slate-400 text-center">
                        <i class="fa-solid fa-lock mr-1"></i> Data tagihan akan langsung dikirim ke sistem invoice katering dan kantor.
                    </p>

                </div>
            </div>

        </form>

    </div>
</div>
@endsection
