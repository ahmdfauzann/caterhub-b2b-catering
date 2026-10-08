<?php

namespace App\Http\Controllers;

use App\Models\MerchantProfile;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        
        $featuredMerchants = MerchantProfile::with(['menus', 'reviews'])
            ->where('status', 'active')
            ->orderBy('rating_avg', 'desc')
            ->take(6)
            ->get();

        $popularMenus = Menu::with('merchant')
            ->where('is_available', true)
            ->orderBy('sales_count', 'desc')
            ->take(8)
            ->get();

        $latestReviews = Review::with(['customer', 'merchant'])
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('categories', 'featuredMerchants', 'popularMenus', 'latestReviews'));
    }

    public function search(Request $request)
    {
        $categories = Category::all();
        $query = MerchantProfile::with(['menus', 'reviews'])->where('status', 'active');

        // Filter by Keyword (Company Name or Menu Name)
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('company_name', 'like', "%{$keyword}%")
                  ->orWhere('cuisine_type', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhereHas('menus', function($mq) use ($keyword) {
                      $mq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter by City
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by Category (via Menu relationship)
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('menus.category', function($cq) use ($categorySlug) {
                $cq->where('slug', $categorySlug);
            });
        }

        // Filter by Min Price Range
        if ($request->filled('max_price')) {
            $maxPrice = (float) $request->max_price;
            $query->whereHas('menus', function($mq) use ($maxPrice) {
                $mq->where('price', '<=', $maxPrice);
            });
        }

        $merchants = $query->paginate(9)->withQueryString();

        return view('search', compact('merchants', 'categories'));
    }

    public function merchantDetail($slug)
    {
        $merchant = MerchantProfile::with(['menus.category', 'reviews.customer', 'user'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $menusGrouped = $merchant->menus->groupBy(function($menu) {
            return $menu->category ? $menu->category->name : 'Lainnya';
        });

        $totalSales = $merchant->orders()->where('status', 'completed')->count();

        return view('merchant-detail', compact('merchant', 'menusGrouped', 'totalSales'));
    }

    public function menuDetail($id)
    {
        $menu = Menu::with(['merchant', 'category'])->findOrFail($id);
        return response()->json([
            'id' => $menu->id,
            'name' => $menu->name,
            'description' => $menu->description,
            'price' => (float) $menu->price,
            'formatted_price' => 'Rp ' . number_format($menu->price, 0, ',', '.'),
            'photo_url' => $menu->photo_url,
            'min_portion' => $menu->min_portion,
            'dietary_tags' => $menu->dietary_tags,
            'category' => $menu->category ? $menu->category->name : null,
            'merchant_id' => $menu->merchant_profile_id,
            'merchant_name' => $menu->merchant->company_name,
        ]);
    }
}
