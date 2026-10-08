<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\Review;
use App\Models\MerchantProfile;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerPortalController extends Controller
{
    private function getCustomerProfile()
    {
        return auth()->user()->customerProfile;
    }

    public function dashboard()
    {
        $user = auth()->user();
        $customer = $user->customerProfile;

        $activeOrders = Order::with(['merchant', 'items', 'invoice'])
            ->where('customer_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'delivering'])
            ->latest()
            ->get();

        $completedOrdersCount = Order::where('customer_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->count();

        $totalSpent = Order::where('customer_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('grand_total');

        $recentOrders = Order::with(['merchant', 'items', 'invoice'])
            ->where('customer_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'customer', 'activeOrders', 'completedOrdersCount', 'totalSpent', 'recentOrders'
        ));
    }

    public function profile()
    {
        $customer = $this->getCustomerProfile();
        return view('customer.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = $this->getCustomerProfile();

        $request->validate([
            'company_name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'office_address' => 'required|string',
            'city' => 'required|string|max:100',
            'employee_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($customer) {
            $customer->update($request->only([
                'company_name', 'pic_name', 'phone', 'office_address', 'city', 'employee_count', 'notes'
            ]));
        } else {
            CustomerProfile::create([
                'user_id' => auth()->id(),
                'company_name' => $request->company_name,
                'pic_name' => $request->pic_name,
                'phone' => $request->phone,
                'office_address' => $request->office_address,
                'city' => $request->city,
                'employee_count' => $request->employee_count,
                'notes' => $request->notes,
            ]);
        }

        return redirect()->back()->with('success', 'Profil kantor berhasil diperbarui.');
    }

    // --- CART OPERATIONS ---
    public function cart()
    {
        $cart = session()->get('cart', []);
        $merchant = null;

        if (!empty($cart)) {
            $merchantId = reset($cart)['merchant_id'] ?? null;
            if ($merchantId) {
                $merchant = MerchantProfile::find($merchantId);
            }
        }

        return view('customer.cart', compact('cart', 'merchant'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::with('merchant')->findOrFail($request->menu_id);
        $cart = session()->get('cart', []);

        // Check if cart already has items from another merchant
        if (!empty($cart)) {
            $existingMerchantId = reset($cart)['merchant_id'] ?? null;
            if ($existingMerchantId && $existingMerchantId != $menu->merchant_profile_id) {
                return redirect()->back()->with('error', 'Keranjang saat ini berisi menu dari merchant katering lain. Selesaikan atau kosongkan keranjang terlebih dahulu sebelum memesan dari merchant berbeda.');
            }
        }

        $minPortion = $menu->min_portion;
        $quantity = max($request->quantity, $minPortion);

        if (isset($cart[$menu->id])) {
            $cart[$menu->id]['quantity'] += $quantity;
        } else {
            $cart[$menu->id] = [
                'menu_id' => $menu->id,
                'name' => $menu->name,
                'price' => (float) $menu->price,
                'quantity' => $quantity,
                'photo_url' => $menu->photo_url,
                'min_portion' => $menu->min_portion,
                'merchant_id' => $menu->merchant_profile_id,
                'merchant_name' => $menu->merchant->company_name,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('customer.cart')->with('success', 'Menu "' . $menu->name . '" berhasil ditambahkan ke keranjang pesanan.');
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->menu_id])) {
            $menu = Menu::find($request->menu_id);
            $minPortion = $menu ? $menu->min_portion : 1;

            if ($request->quantity < $minPortion) {
                return redirect()->back()->with('error', "Minimal pemesanan untuk menu {$cart[$request->menu_id]['name']} adalah {$minPortion} porsi.");
            }

            $cart[$request->menu_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Jumlah porsi berhasil diperbarui.');
    }

    public function removeFromCart($menuId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Menu telah dihapus dari keranjang.');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang pesanan telah dikosongkan.');
    }

    // --- CHECKOUT & INVOICE CREATION ---
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('search')->with('error', 'Keranjang pesanan Anda masih kosong. Silakan pilih menu katering terlebih dahulu.');
        }

        $customer = $this->getCustomerProfile();
        $merchantId = reset($cart)['merchant_id'] ?? null;
        $merchant = MerchantProfile::findOrFail($merchantId);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.10; // 10% Tax
        $deliveryFee = $subtotal >= 1000000 ? 0 : 35000; // Free delivery over 1M
        $grandTotal = $subtotal + $tax + $deliveryFee;

        return view('customer.checkout', compact('cart', 'customer', 'merchant', 'subtotal', 'tax', 'deliveryFee', 'grandTotal'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('search')->with('error', 'Keranjang Anda kosong.');
        }

        $request->validate([
            'delivery_date' => 'required|date|after_or_equal:today',
            'delivery_time' => 'required|string',
            'delivery_address' => 'required|string',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'delivery_date.required' => 'Tanggal pengiriman wajib ditentukan.',
            'delivery_date.after_or_equal' => 'Tanggal pengiriman minimal hari ini atau hari kerja mendatang.',
            'delivery_address.required' => 'Alamat pengiriman kantor wajib diisi.',
        ]);

        $user = auth()->user();
        $merchantId = reset($cart)['merchant_id'];

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = $subtotal * 0.10;
        $deliveryFee = $subtotal >= 1000000 ? 0 : 35000;
        $grandTotal = $subtotal + $tax + $deliveryFee;

        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // Create Order
        $order = Order::create([
            'order_code' => $orderCode,
            'customer_id' => $user->id,
            'merchant_id' => $merchantId,
            'delivery_date' => $request->delivery_date,
            'delivery_time' => $request->delivery_time,
            'delivery_address' => $request->delivery_address,
            'total_amount' => $subtotal,
            'tax_amount' => $tax,
            'delivery_fee' => $deliveryFee,
            'grand_total' => $grandTotal,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        // Create Order Items & Update Sales Count
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu_id'],
                'menu_name' => $item['name'],
                'unit_price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            $menu = Menu::find($item['menu_id']);
            if ($menu) {
                $menu->increment('sales_count', $item['quantity']);
            }
        }

        // Generate Invoice
        $invoiceNumber = 'INV-' . date('Ymd') . '-CAT-' . sprintf('%04d', $order->id);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'order_id' => $order->id,
            'issue_date' => now(),
            'due_date' => now()->addDays(5),
            'amount' => $grandTotal,
            'status' => 'unpaid',
            'notes' => 'Tagihan pemesanan katering katering B2B. Metode Pembayaran: ' . $request->payment_method,
        ]);

        // Clear Cart Session
        session()->forget('cart');

        return redirect()->route('invoice.show', $invoice->invoice_number)
            ->with('success', 'Pesanan Anda berhasil dibuat! Silakan tinjau dan unduh invoice tagihan di bawah ini.');
    }

    public function orders(Request $request)
    {
        $user = auth()->user();
        $query = Order::with(['merchant', 'items', 'invoice'])
            ->where('customer_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    public function orderDetail($id)
    {
        $user = auth()->user();
        $order = Order::with(['merchant', 'items.menu', 'invoice', 'review'])
            ->where('customer_id', $user->id)
            ->findOrFail($id);

        return view('customer.orders.show', compact('order'));
    }

    public function storeReview(Request $request, $orderId)
    {
        $user = auth()->user();
        $order = Order::where('customer_id', $user->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->findOrFail($orderId);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            ['order_id' => $order->id],
            [
                'customer_id' => $user->id,
                'merchant_id' => $order->merchant_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        // Recalculate Merchant Average Rating
        $merchant = MerchantProfile::find($order->merchant_id);
        if ($merchant) {
            $avg = Review::where('merchant_id', $merchant->id)->avg('rating');
            $merchant->update(['rating_avg' => round($avg, 1)]);
        }

        return redirect()->back()->with('success', 'Terima kasih! Ulasan dan rating Anda telah berhasil dipublikasikan.');
    }

    public function uploadPaymentReceipt(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::where('customer_id', $user->id)->findOrFail($id);

        $request->validate([
            'payment_receipt' => 'nullable|file|mimes:jpeg,png,jpg,pdf,webp|max:4096',
        ]);

        $receiptPath = null;
        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_receipt' => $receiptPath ?? $order->payment_receipt,
        ]);

        if ($order->invoice) {
            $order->invoice->update(['status' => 'paid']);
        }

        return redirect()->back()->with('success', 'Konfirmasi pembayaran berhasil dikirim! Status invoice Anda kini LUNAS / PAID.');
    }
}
