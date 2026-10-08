<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MerchantProfile;
use App\Models\CustomerProfile;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = [
            [
                'name' => 'Nasi Kotak Premium',
                'slug' => 'nasi-kotak-premium',
                'icon' => 'fa-box',
                'description' => 'Paket nasi box praktis dan lezat untuk makan siang kantor harian maupun meeting.',
            ],
            [
                'name' => 'Prasmanan & Buffet',
                'slug' => 'prasmanan-buffet',
                'icon' => 'fa-utensils',
                'description' => 'Layanan catering buffet lengkap dengan alat & server untuk event perusahaan.',
            ],
            [
                'name' => 'Snack Box Corporate',
                'slug' => 'snack-box-corporate',
                'icon' => 'fa-cookie-bite',
                'description' => 'Pilihan kudapan manis & gurih pendamping coffee break & seminar kantor.',
            ],
            [
                'name' => 'Healthy & Fit Box',
                'slug' => 'healthy-fit-box',
                'icon' => 'fa-heartpulse',
                'description' => 'Menu sehat rendah kalori, kaya nutrisi, non-MSG untuk gaya hidup sehat.',
            ],
            [
                'name' => 'Bento Box Bento',
                'slug' => 'bento-box',
                'icon' => 'fa-bowl-rice',
                'description' => 'Menu khas Jepang & Asian fusion disajikan rapi dalam tempat bento bersekat.',
            ],
            [
                'name' => 'Minuman & Dessert',
                'slug' => 'minuman-dessert',
                'icon' => 'fa-glass-water',
                'description' => 'Aneka es segar, buah potong, puding, dan jus buah asli penyegar acara.',
            ],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = Category::create($cat);
        }

        // 2. Main Merchant User & Profile
        $userM1 = User::create([
            'name' => 'Hj. Siti Rahmawati (Owner)',
            'email' => 'berkah@catering.com',
            'password' => Hash::make('password'),
            'role' => 'merchant',
            'phone' => '081311223344',
        ]);

        $m1 = MerchantProfile::create([
            'user_id' => $userM1->id,
            'company_name' => 'Berkah Catering Nusantara',
            'slug' => 'berkah-catering-nusantara',
            'description' => 'Pelopor katering kantor terpercaya di Jakarta sejak 2012. Menyediakan ragam hidangan khas Nusantara dengan cita rasa otentik, higienis, dan tersertifikasi Halal MUI.',
            'phone' => '081311223344',
            'address' => 'Jl. Tebet Raya No. 45, Jakarta Selatan',
            'city' => 'Jakarta Selatan',
            'cuisine_type' => 'Indonesian, Traditional, Nusantara',
            'min_order_amount' => 300000,
            'rating_avg' => 5.0,
            'status' => 'active',
        ]);

        // 3. Menus for Main Merchant
        $menus = [
            [
                'merchant_profile_id' => $m1->id,
                'category_id' => $catModels['nasi-kotak-premium']->id,
                'name' => 'Paket Nasi Liwet Solo Komplit',
                'slug' => 'paket-nasi-liwet-solo-komplit',
                'description' => 'Nasi liwet gurih aromatik disajikan dengan Ayam Suwir Opor, Sambal Goreng Manisa, Telur Pindang, Ampela Ati, dan Kerupuk Udang.',
                'price' => 38000,
                'photo' => null,
                'min_portion' => 10,
                'dietary_tags' => 'Halal, Traditional Best Seller',
                'is_available' => true,
                'sales_count' => 1250,
            ],
            [
                'merchant_profile_id' => $m1->id,
                'category_id' => $catModels['nasi-kotak-premium']->id,
                'name' => 'Nasi Rendang Sapi Padang Authentic',
                'slug' => 'nasi-rendang-sapi-padang-authentic',
                'description' => 'Nasi putih pulen, Rendang Sapi rempah meresap, Daun Singkong Rebus, Sambal Hijau, dan Perkedel Kentang.',
                'price' => 45000,
                'photo' => null,
                'min_portion' => 10,
                'dietary_tags' => 'Halal, Premium Beef',
                'is_available' => true,
                'sales_count' => 980,
            ],
            [
                'merchant_profile_id' => $m1->id,
                'category_id' => $catModels['prasmanan-buffet']->id,
                'name' => 'Paket Prasmanan Nusantara Gold (Min 50 Pax)',
                'slug' => 'paket-prasmanan-nusantara-gold',
                'description' => 'Menu buffet komplit: Nasi Goreng Jawa, Ayam Bakar Madu, Sop Kimlo, Daging Sapi Lada Hitam, Capcay Seafood, Es Buah Kombinasi.',
                'price' => 75000,
                'photo' => null,
                'min_portion' => 50,
                'dietary_tags' => 'Halal, Full Service Buffet',
                'is_available' => true,
                'sales_count' => 420,
            ],
            [
                'merchant_profile_id' => $m1->id,
                'category_id' => $catModels['snack-box-corporate']->id,
                'name' => 'Snack Box Nusantara Deluxe',
                'slug' => 'snack-box-nusantara-deluxe',
                'description' => 'Kue Lemper Ayam Premium, Risoles Ragout Daging, Pastel Telur, Puding Santan Pandan, dan Air Mineral Bottle.',
                'price' => 22000,
                'photo' => null,
                'min_portion' => 15,
                'dietary_tags' => 'Halal, Coffee Break Favorite',
                'is_available' => true,
                'sales_count' => 2100,
            ],
            [
                'merchant_profile_id' => $m1->id,
                'category_id' => $catModels['healthy-fit-box']->id,
                'name' => 'Clean Eating Chicken breast Bowl',
                'slug' => 'clean-eating-chicken-breast-bowl',
                'description' => 'Dada ayam panggang sous-vide, Nasi Merah Organik, Edamame, Avokad, Jagung Manis, dan Sesame Ginger Sauce.',
                'price' => 48000,
                'photo' => null,
                'min_portion' => 10,
                'dietary_tags' => 'Halal, Low Calorie, Non-MSG',
                'is_available' => true,
                'sales_count' => 1100,
            ],
            [
                'merchant_profile_id' => $m1->id,
                'category_id' => $catModels['minuman-dessert']->id,
                'name' => 'Es Cendol Dawet Ayu Solo (Pitcher/Porsi)',
                'slug' => 'es-cendol-dawet-ayu-solo',
                'description' => 'Es cendol nangka gula jawa murni dan santan kelapa gurih alami, disajikan dingin penyegar acara.',
                'price' => 20000,
                'photo' => null,
                'min_portion' => 10,
                'dietary_tags' => 'Halal, Fresh Drink',
                'is_available' => true,
                'sales_count' => 850,
            ],
            [
                'merchant_profile_id' => $m1->id,
                'category_id' => $catModels['minuman-dessert']->id,
                'name' => 'Jus Alpukat Kocok Brown Sugar',
                'slug' => 'jus-alpukat-kocok-brown-sugar',
                'description' => 'Jus alpukat mentega segar dengan gula palma organik dan susu cokelat nikmat.',
                'price' => 22000,
                'photo' => null,
                'min_portion' => 10,
                'dietary_tags' => 'Halal, 100% Real Fruit',
                'is_available' => true,
                'sales_count' => 640,
            ],
        ];

        $createdMenus = [];
        foreach ($menus as $m) {
            $createdMenus[] = Menu::create($m);
        }

        // 4. Main Customer User & Profile (PT Jasamedika Transmedic)
        $userC1 = User::create([
            'name' => 'Ahmad Fauzan (Procurement Manager)',
            'email' => 'customer@jasamedika.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '081234567890',
        ]);

        $c1Profile = CustomerProfile::create([
            'user_id' => $userC1->id,
            'company_name' => 'PT Jasamedika Transmedic',
            'pic_name' => 'Ahmad Fauzan',
            'phone' => '081234567890',
            'office_address' => 'Gedung Jasamedika Tower Lt. 5, Jl. Gatot Subroto Kav. 32, Jakarta Selatan',
            'city' => 'Jakarta Selatan',
            'employee_count' => 120,
            'notes' => 'Membutuhkan pengiriman konsisten jam 11:30 - 12:00 WIB tepat waktu untuk makan siang karyawan.',
        ]);

        // 5. Sample Orders & Invoices
        // Order 1: Delivered & Paid
        $order1 = Order::create([
            'order_code' => 'ORD-20261007-001',
            'customer_id' => $userC1->id,
            'merchant_id' => $m1->id,
            'delivery_date' => now()->addDays(1)->format('Y-m-d'),
            'delivery_time' => '11:30 - 12:00 WIB',
            'delivery_address' => $c1Profile->office_address,
            'total_amount' => 1900000, // 50 porsi @ 38000
            'tax_amount' => 190000,   // 10%
            'delivery_fee' => 50000,
            'grand_total' => 2140000,
            'status' => 'delivered',
            'payment_status' => 'paid',
            'payment_method' => 'Corporate Billing',
            'notes' => 'Tolong pisahkan kerupuk agar tetap renyah.',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'menu_id' => $createdMenus[0]->id,
            'menu_name' => 'Paket Nasi Liwet Solo Komplit',
            'unit_price' => 38000,
            'quantity' => 50,
            'subtotal' => 1900000,
            'notes' => 'Level pedas sedang',
        ]);

        Invoice::create([
            'invoice_number' => 'INV-20261007-CAT-0001',
            'order_id' => $order1->id,
            'issue_date' => now(),
            'due_date' => now()->addDays(7),
            'amount' => 2140000,
            'status' => 'paid',
            'notes' => 'Pembayaran via Corporate Billing B2B telah diverifikasi.',
        ]);

        Review::create([
            'order_id' => $order1->id,
            'customer_id' => $userC1->id,
            'merchant_id' => $m1->id,
            'rating' => 5,
            'comment' => 'Makanan sangat lezat, porsi pas untuk tim kami, dan datang tepat waktu sebelum jam istirahat!',
        ]);

        // Order 2: Pending Order
        $order2 = Order::create([
            'order_code' => 'ORD-20261007-002',
            'customer_id' => $userC1->id,
            'merchant_id' => $m1->id,
            'delivery_date' => now()->addDays(2)->format('Y-m-d'),
            'delivery_time' => '12:00 - 12:30 WIB',
            'delivery_address' => $c1Profile->office_address,
            'total_amount' => 1350000, // 30 porsi @ 45000
            'tax_amount' => 135000,
            'delivery_fee' => 35000,
            'grand_total' => 1520000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'Corporate Billing',
            'notes' => 'Tolong dikirimkan sebelum jam 12 siang.',
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'menu_id' => $createdMenus[1]->id,
            'menu_name' => 'Nasi Rendang Sapi Padang Authentic',
            'unit_price' => 45000,
            'quantity' => 30,
            'subtotal' => 1350000,
        ]);

        Invoice::create([
            'invoice_number' => 'INV-20261007-CAT-0002',
            'order_id' => $order2->id,
            'issue_date' => now(),
            'due_date' => now()->addDays(5),
            'amount' => 1520000,
            'status' => 'unpaid',
            'notes' => 'Menunggu pembayaran dari divisi keuangan kantor.',
        ]);
    }
}
