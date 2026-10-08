<?php

namespace App\Http\Controllers;

use App\Models\MerchantProfile;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MerchantPortalController extends Controller
{
    private function getMerchantProfile()
    {
        return auth()->user()->merchantProfile;
    }

    public function dashboard()
    {
        $merchant = $this->getMerchantProfile();

        if (!$merchant) {
            abort(404, 'Profil Merchant tidak ditemukan.');
        }

        $totalOrders = Order::where('merchant_id', $merchant->id)->count();
        $pendingOrders = Order::where('merchant_id', $merchant->id)->where('status', 'pending')->count();
        $completedOrders = Order::where('merchant_id', $merchant->id)->where('status', 'delivered')->count();
        $totalRevenue = Order::where('merchant_id', $merchant->id)
            ->whereIn('status', ['confirmed', 'preparing', 'delivering', 'delivered', 'completed'])
            ->sum('grand_total');

        $recentOrders = Order::with(['customer', 'items'])
            ->where('merchant_id', $merchant->id)
            ->latest()
            ->take(5)
            ->get();

        $totalMenus = Menu::where('merchant_profile_id', $merchant->id)->count();

        return view('merchant.dashboard', compact(
            'merchant', 'totalOrders', 'pendingOrders', 'completedOrders',
            'totalRevenue', 'recentOrders', 'totalMenus'
        ));
    }

    public function profile()
    {
        $merchant = $this->getMerchantProfile();
        return view('merchant.profile', compact('merchant'));
    }

    public function updateProfile(Request $request)
    {
        $merchant = $this->getMerchantProfile();

        $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'cuisine_type' => 'required|string|max:255',
            'min_order_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'city' => $request->city,
            'address' => $request->address,
            'cuisine_type' => $request->cuisine_type,
            'min_order_amount' => $request->min_order_amount,
            'description' => $request->description,
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('merchants/logos', 'public');
            $data['logo'] = $logoPath;
        }

        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('merchants/banners', 'public');
            $data['banner'] = $bannerPath;
        }

        $merchant->update($data);

        return redirect()->back()->with('success', 'Profil katering berhasil diperbarui.');
    }

    public function menus(Request $request)
    {
        $merchant = $this->getMerchantProfile();
        $categories = Category::all();

        $query = Menu::with('category')->where('merchant_profile_id', $merchant->id);

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $menus = $query->latest()->paginate(10)->withQueryString();

        return view('merchant.menus.index', compact('menus', 'categories'));
    }

    public function createMenu()
    {
        $categories = Category::all();
        return view('merchant.menus.create', compact('categories'));
    }

    public function storeMenu(Request $request)
    {
        $merchant = $this->getMerchantProfile();

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1000',
            'min_portion' => 'required|integer|min:1',
            'description' => 'required|string',
            'dietary_tags' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_available' => 'nullable|boolean',
        ], [
            'name.required' => 'Nama menu wajib diisi.',
            'category_id.required' => 'Kategori menu wajib dipilih.',
            'price.required' => 'Harga menu wajib diisi.',
            'price.min' => 'Harga minimal Rp 1.000.',
            'min_portion.required' => 'Minimal porsi pesanan wajib diisi.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('menus', 'public');
        }

        Menu::create([
            'merchant_profile_id' => $merchant->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'description' => $request->description,
            'price' => $request->price,
            'photo' => $photoPath,
            'min_portion' => $request->min_portion,
            'dietary_tags' => $request->dietary_tags,
            'is_available' => $request->has('is_available') ? true : false,
        ]);

        return redirect()->route('merchant.menus.index')->with('success', 'Menu makanan berhasil ditambahkan!');
    }

    public function editMenu($id)
    {
        $merchant = $this->getMerchantProfile();
        $menu = Menu::where('merchant_profile_id', $merchant->id)->findOrFail($id);
        $categories = Category::all();

        return view('merchant.menus.edit', compact('menu', 'categories'));
    }

    public function updateMenu(Request $request, $id)
    {
        $merchant = $this->getMerchantProfile();
        $menu = Menu::where('merchant_profile_id', $merchant->id)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:1000',
            'min_portion' => 'required|integer|min:1',
            'description' => 'required|string',
            'dietary_tags' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_available' => 'nullable|boolean',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'min_portion' => $request->min_portion,
            'dietary_tags' => $request->dietary_tags,
            'is_available' => $request->has('is_available') ? true : false,
        ];

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('menus', 'public');
            $data['photo'] = $photoPath;
        }

        $menu->update($data);

        return redirect()->route('merchant.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function deleteMenu($id)
    {
        $merchant = $this->getMerchantProfile();
        $menu = Menu::where('merchant_profile_id', $merchant->id)->findOrFail($id);
        $menu->delete();

        return redirect()->route('merchant.menus.index')->with('success', 'Menu makanan telah dihapus.');
    }

    public function orders(Request $request)
    {
        $merchant = $this->getMerchantProfile();
        $query = Order::with(['customer.customerProfile', 'items', 'invoice'])
            ->where('merchant_id', $merchant->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('order_code', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('merchant.orders.index', compact('orders'));
    }

    public function orderDetail($id)
    {
        $merchant = $this->getMerchantProfile();
        $order = Order::with(['customer.customerProfile', 'items.menu', 'invoice', 'review'])
            ->where('merchant_id', $merchant->id)
            ->findOrFail($id);

        return view('merchant.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $merchant = $this->getMerchantProfile();
        $order = Order::where('merchant_id', $merchant->id)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,delivering,delivered,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // If status marked as delivered/completed, sync invoice payment if unpaid
        if (in_array($request->status, ['delivered', 'completed']) && $order->payment_status === 'unpaid') {
            $order->update(['payment_status' => 'paid']);
            if ($order->invoice) {
                $order->invoice->update(['status' => 'paid']);
            }
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . $order->status_label);
    }
}
