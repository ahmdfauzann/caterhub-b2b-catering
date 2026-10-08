@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_code)

@section('content')
<div class="py-10 bg-slate-100 min-h-[85vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('customer.orders.index') }}" class="text-xs text-brand-600 font-bold hover:underline mb-1 inline-block">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Riwayat Pesanan
                </a>
                <h1 class="text-3xl font-extrabold text-slate-900">Pesanan {{ $order->order_code }}</h1>
                <p class="text-xs text-slate-500">Dibuat pada {{ $order->created_at->format('d M Y H:i WIB') }}</p>
            </div>

            @if($order->invoice)
                <a href="{{ route('invoice.show', $order->invoice->invoice_number) }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-900 font-extrabold text-xs rounded-xl shadow-md transition-all">
                    <i class="fa-solid fa-file-invoice mr-1.5"></i> Lihat Invoice Tagihan
                </a>
            @endif
        </div>

        <!-- Payment Confirmation Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-credit-card text-brand-600"></i> Status Pembayaran B2B
                </h3>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold {{ $order->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ $order->payment_status === 'paid' ? 'LUNAS / PAID' : 'BELUM DIBAYAR' }}
                </span>
            </div>

            @if($order->payment_status === 'paid')
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs flex items-center justify-between">
                    <div>
                        <span class="font-bold">Pembayaran Telah Dikonfirmasi Lunas.</span>
                        <p class="text-[11px] text-emerald-700 mt-0.5">Metode Pembayaran: {{ $order->payment_method }}</p>
                    </div>
                    <i class="fa-solid fa-circle-check text-2xl text-emerald-600"></i>
                </div>
            @else
                <form action="{{ route('customer.orders.payment', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="p-4 bg-amber-50/80 border border-amber-200 rounded-2xl space-y-2 text-xs">
                        <p class="font-bold text-amber-900">Metode Pembayaran: {{ $order->payment_method }}</p>
                        <p class="text-slate-700">Silakan lakukan transfer sejumlah <strong class="text-brand-600 font-extrabold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong> ke rekening berikut:</p>
                        <p class="font-mono font-bold text-slate-900 bg-white p-2.5 rounded-xl border">BNI Virtual Account: 8891 0023 9912 0012 (A/N CaterHub Digital B2B)</p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <input type="file" name="payment_receipt" accept="image/*,.pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shrink-0">
                            Konfirmasi Pembayaran Lunas
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Order Progress Timeline -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
            <h3 class="font-extrabold text-slate-900 text-base border-b border-slate-100 pb-3">Status Progress Katering</h3>

            @php
                $statuses = [
                    'pending' => ['label' => 'Menunggu Konfirmasi', 'icon' => 'fa-clock'],
                    'confirmed' => ['label' => 'Dikonfirmasi Vendor', 'icon' => 'fa-circle-check'],
                    'preparing' => ['label' => 'Sedang Dimasak', 'icon' => 'fa-fire-burner'],
                    'delivering' => ['label' => 'Dalam Pengiriman', 'icon' => 'fa-truck-fast'],
                    'delivered' => ['label' => 'Sampai di Lokasi', 'icon' => 'fa-box-open'],
                ];
                $orderStatusKey = array_search($order->status, array_keys($statuses));
                if ($orderStatusKey === false) $orderStatusKey = 4; // for completed
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 relative">
                @foreach(array_values($statuses) as $index => $st)
                    @php $isDone = $index <= $orderStatusKey; @endphp
                    <div class="flex flex-col items-center text-center space-y-2">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-base transition-all {{ $isDone ? 'bg-brand-600 text-white shadow-md shadow-brand-600/30' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                            <i class="fa-solid {{ $st['icon'] }}"></i>
                        </div>
                        <span class="text-xs font-bold {{ $isDone ? 'text-slate-900' : 'text-slate-400' }}">{{ $st['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Items & Merchant Detail -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <span class="text-[10px] text-brand-600 font-bold uppercase">MITRA KATERING</span>
                    <h3 class="font-extrabold text-slate-900 text-lg">{{ $order->merchant->company_name }}</h3>
                </div>
                <div class="text-right text-xs text-slate-500">
                    <p><i class="fa-solid fa-phone text-brand-500 mr-1"></i> {{ $order->merchant->phone }}</p>
                    <p><i class="fa-solid fa-location-dot text-brand-500 mr-1"></i> {{ $order->merchant->city }}</p>
                </div>
            </div>

            <!-- Items Table -->
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

            <!-- Total Calculation -->
            <div class="pt-4 border-t border-slate-200 flex flex-col items-end space-y-2 text-xs">
                <div class="w-64 space-y-1.5 text-slate-600">
                    <div class="flex justify-between">
                        <span>Subtotal Porsi</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>PBN (Pajak 10%)</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkos Kirim B2B</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-200 flex justify-between text-sm font-extrabold">
                        <span class="text-slate-900">Grand Total</span>
                        <span class="text-brand-600">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Review Form if Order Delivered -->
        @if(in_array($order->status, ['delivered', 'completed']))
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-star text-amber-500"></i> Ulasan & Rating Pesanan
                </h3>

                @if($order->review)
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl space-y-2 text-xs">
                        <div class="flex items-center justify-between font-bold text-amber-900">
                            <span>Rating Anda: {{ $order->review->rating }} / 5 Bintang</span>
                            <span class="text-[10px] text-amber-700">{{ $order->review->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="text-slate-700 italic">"{{ $order->review->comment }}"</p>
                    </div>
                @else
                    <form action="{{ route('customer.orders.review', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Beri Rating (1 - 5 Bintang) *</label>
                            <select name="rating" required class="w-full sm:w-48 px-4 py-2 rounded-xl border border-slate-300 text-sm font-bold text-amber-600">
                                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Memuaskan)</option>
                                <option value="4">⭐⭐⭐⭐ (4 - Bagus & Lezat)</option>
                                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                <option value="2">⭐⭐ (2 - Kurang)</option>
                                <option value="1">⭐ (1 - Perlu Perbaikan)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ulasan / Komentar untuk Katering</label>
                            <textarea name="comment" rows="3" placeholder="Tuliskan pengalaman Anda mengenai cita rasa, kerapian, dan ketepatan waktu pengiriman..."
                                      class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:border-brand-500 outline-none"></textarea>
                        </div>

                        <button type="submit" class="px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl text-xs shadow-md">
                            Kirim Ulasan & Rating
                        </button>
                    </form>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
