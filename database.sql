-- ==========================================================
-- MarketPlace Katering (CaterHub) - MySQL Database Export
-- Created for JASAMEDIKA TRANSMEDIC PT Coding Test
-- Date: 2026-10-08 05:08:22
-- ==========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `merchant_profiles`
DROP TABLE IF EXISTS `merchant_profiles`;
CREATE TABLE `merchant_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Jakarta',
  `logo` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `cuisine_type` varchar(255) DEFAULT NULL,
  `min_order_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,
  `status` enum('active','pending','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `merchant_profiles_slug_unique` (`slug`),
  KEY `merchant_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `merchant_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `customer_profiles`
DROP TABLE IF EXISTS `customer_profiles`;
CREATE TABLE `customer_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `pic_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `office_address` text DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Jakarta',
  `employee_count` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `customer_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `categories`
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `menus`
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant_profile_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `min_portion` int(11) NOT NULL DEFAULT 10,
  `dietary_tags` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `sales_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menus_merchant_profile_id_foreign` (`merchant_profile_id`),
  KEY `menus_category_id_foreign` (`category_id`),
  CONSTRAINT `menus_merchant_profile_id_foreign` FOREIGN KEY (`merchant_profile_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `orders`
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `merchant_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_time` varchar(255) NOT NULL DEFAULT '12:00 - Makan Siang',
  `delivery_address` text NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `delivery_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','preparing','delivering','delivered','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','verified') NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) NOT NULL DEFAULT 'Corporate Billing',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_code_unique` (`order_code`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `orders_merchant_id_foreign` (`merchant_id`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_merchant_id_foreign` FOREIGN KEY (`merchant_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `order_items`
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `menu_name` varchar(255) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `invoices`
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('unpaid','paid','cancelled') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table structure for `reviews`
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `merchant_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_order_id_foreign` (`order_id`),
  KEY `reviews_customer_id_foreign` (`customer_id`),
  KEY `reviews_merchant_id_foreign` (`merchant_id`),
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_merchant_id_foreign` FOREIGN KEY (`merchant_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `users` --
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES (1, 'Hj. Siti Rahmawati (Owner)', 'berkah@catering.com', NULL, '$2y$12$zi/IGopZxPFs1tKs7LeJJ.zV62gkXxhMhPNnM3SR8caHjoBapkHAy', 'merchant', '081311223344', NULL, NULL, '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES (2, 'Ahmad Fauzan (Procurement Manager)', 'customer@jasamedika.com', NULL, '$2y$12$ZR6YqeQt9BF8c48I7HP4nezeD3t6kAvnLsD3K1EEpZjm3X73mK0oW', 'customer', '081234567890', NULL, NULL, '2026-10-08 05:08:21', '2026-10-08 05:08:21');

-- Dumping data for table `merchant_profiles` --
INSERT INTO `merchant_profiles` (`id`, `user_id`, `company_name`, `slug`, `description`, `phone`, `address`, `city`, `logo`, `banner`, `cuisine_type`, `min_order_amount`, `rating_avg`, `status`, `created_at`, `updated_at`) VALUES (1, 1, 'Berkah Catering Nusantara', 'berkah-catering-nusantara', 'Pelopor katering kantor terpercaya di Jakarta sejak 2012. Menyediakan ragam hidangan khas Nusantara dengan cita rasa otentik, higienis, dan tersertifikasi Halal MUI.', '081311223344', 'Jl. Tebet Raya No. 45, Jakarta Selatan', 'Jakarta Selatan', NULL, NULL, 'Indonesian, Traditional, Nusantara', 300000, 5, 'active', '2026-10-08 05:08:21', '2026-10-08 05:08:21');

-- Dumping data for table `customer_profiles` --
INSERT INTO `customer_profiles` (`id`, `user_id`, `company_name`, `pic_name`, `phone`, `office_address`, `city`, `employee_count`, `notes`, `created_at`, `updated_at`) VALUES (1, 2, 'PT Jasamedika Transmedic', 'Ahmad Fauzan', '081234567890', 'Gedung Jasamedika Tower Lt. 5, Jl. Gatot Subroto Kav. 32, Jakarta Selatan', 'Jakarta Selatan', 120, 'Membutuhkan pengiriman konsisten jam 11:30 - 12:00 WIB tepat waktu untuk makan siang karyawan.', '2026-10-08 05:08:21', '2026-10-08 05:08:21');

-- Dumping data for table `categories` --
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`, `updated_at`) VALUES (1, 'Nasi Kotak Premium', 'nasi-kotak-premium', 'fa-box', 'Paket nasi box praktis dan lezat untuk makan siang kantor harian maupun meeting.', '2026-10-08 05:08:20', '2026-10-08 05:08:20');
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`, `updated_at`) VALUES (2, 'Prasmanan & Buffet', 'prasmanan-buffet', 'fa-utensils', 'Layanan catering buffet lengkap dengan alat & server untuk event perusahaan.', '2026-10-08 05:08:20', '2026-10-08 05:08:20');
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`, `updated_at`) VALUES (3, 'Snack Box Corporate', 'snack-box-corporate', 'fa-cookie-bite', 'Pilihan kudapan manis & gurih pendamping coffee break & seminar kantor.', '2026-10-08 05:08:20', '2026-10-08 05:08:20');
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`, `updated_at`) VALUES (4, 'Healthy & Fit Box', 'healthy-fit-box', 'fa-heartpulse', 'Menu sehat rendah kalori, kaya nutrisi, non-MSG untuk gaya hidup sehat.', '2026-10-08 05:08:20', '2026-10-08 05:08:20');
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`, `updated_at`) VALUES (5, 'Bento Box Bento', 'bento-box', 'fa-bowl-rice', 'Menu khas Jepang & Asian fusion disajikan rapi dalam tempat bento bersekat.', '2026-10-08 05:08:20', '2026-10-08 05:08:20');
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `created_at`, `updated_at`) VALUES (6, 'Minuman & Dessert', 'minuman-dessert', 'fa-glass-water', 'Aneka es segar, buah potong, puding, dan jus buah asli penyegar acara.', '2026-10-08 05:08:20', '2026-10-08 05:08:20');

-- Dumping data for table `menus` --
INSERT INTO `menus` (`id`, `merchant_profile_id`, `category_id`, `name`, `slug`, `description`, `price`, `photo`, `min_portion`, `dietary_tags`, `is_available`, `sales_count`, `created_at`, `updated_at`) VALUES (1, 1, 1, 'Paket Nasi Liwet Solo Komplit', 'paket-nasi-liwet-solo-komplit', 'Nasi liwet gurih aromatik disajikan dengan Ayam Suwir Opor, Sambal Goreng Manisa, Telur Pindang, Ampela Ati, dan Kerupuk Udang.', 38000, NULL, 10, 'Halal, Traditional Best Seller', 1, 1250, '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `menus` (`id`, `merchant_profile_id`, `category_id`, `name`, `slug`, `description`, `price`, `photo`, `min_portion`, `dietary_tags`, `is_available`, `sales_count`, `created_at`, `updated_at`) VALUES (2, 1, 1, 'Nasi Rendang Sapi Padang Authentic', 'nasi-rendang-sapi-padang-authentic', 'Nasi putih pulen, Rendang Sapi rempah meresap, Daun Singkong Rebus, Sambal Hijau, dan Perkedel Kentang.', 45000, NULL, 10, 'Halal, Premium Beef', 1, 980, '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `menus` (`id`, `merchant_profile_id`, `category_id`, `name`, `slug`, `description`, `price`, `photo`, `min_portion`, `dietary_tags`, `is_available`, `sales_count`, `created_at`, `updated_at`) VALUES (3, 1, 2, 'Paket Prasmanan Nusantara Gold (Min 50 Pax)', 'paket-prasmanan-nusantara-gold', 'Menu buffet komplit: Nasi Goreng Jawa, Ayam Bakar Madu, Sop Kimlo, Daging Sapi Lada Hitam, Capcay Seafood, Es Buah Kombinasi.', 75000, NULL, 50, 'Halal, Full Service Buffet', 1, 420, '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `menus` (`id`, `merchant_profile_id`, `category_id`, `name`, `slug`, `description`, `price`, `photo`, `min_portion`, `dietary_tags`, `is_available`, `sales_count`, `created_at`, `updated_at`) VALUES (4, 1, 3, 'Snack Box Nusantara Deluxe', 'snack-box-nusantara-deluxe', 'Kue Lemper Ayam Premium, Risoles Ragout Daging, Pastel Telur, Puding Santan Pandan, dan Air Mineral Bottle.', 22000, NULL, 15, 'Halal, Coffee Break Favorite', 1, 2100, '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `menus` (`id`, `merchant_profile_id`, `category_id`, `name`, `slug`, `description`, `price`, `photo`, `min_portion`, `dietary_tags`, `is_available`, `sales_count`, `created_at`, `updated_at`) VALUES (5, 1, 4, 'Clean Eating Chicken breast Bowl', 'clean-eating-chicken-breast-bowl', 'Dada ayam panggang sous-vide, Nasi Merah Organik, Edamame, Avokad, Jagung Manis, dan Sesame Ginger Sauce.', 48000, NULL, 10, 'Halal, Low Calorie, Non-MSG', 1, 1100, '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `menus` (`id`, `merchant_profile_id`, `category_id`, `name`, `slug`, `description`, `price`, `photo`, `min_portion`, `dietary_tags`, `is_available`, `sales_count`, `created_at`, `updated_at`) VALUES (6, 1, 6, 'Es Cendol Dawet Ayu Solo (Pitcher/Porsi)', 'es-cendol-dawet-ayu-solo', 'Es cendol nangka gula jawa murni dan santan kelapa gurih alami, disajikan dingin penyegar acara.', 20000, NULL, 10, 'Halal, Fresh Drink', 1, 850, '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `menus` (`id`, `merchant_profile_id`, `category_id`, `name`, `slug`, `description`, `price`, `photo`, `min_portion`, `dietary_tags`, `is_available`, `sales_count`, `created_at`, `updated_at`) VALUES (7, 1, 6, 'Jus Alpukat Kocok Brown Sugar', 'jus-alpukat-kocok-brown-sugar', 'Jus alpukat mentega segar dengan gula palma organik dan susu cokelat nikmat.', 22000, NULL, 10, 'Halal, 100% Real Fruit', 1, 640, '2026-10-08 05:08:21', '2026-10-08 05:08:21');

-- Dumping data for table `orders` --
INSERT INTO `orders` (`id`, `order_code`, `customer_id`, `merchant_id`, `delivery_date`, `delivery_time`, `delivery_address`, `total_amount`, `tax_amount`, `delivery_fee`, `grand_total`, `status`, `payment_status`, `payment_method`, `payment_receipt`, `notes`, `created_at`, `updated_at`) VALUES (1, 'ORD-20261007-001', 2, 1, '2026-10-09 00:00:00', '11:30 - 12:00 WIB', 'Gedung Jasamedika Tower Lt. 5, Jl. Gatot Subroto Kav. 32, Jakarta Selatan', 1900000, 190000, 50000, 2140000, 'delivered', 'paid', 'Corporate Billing', NULL, 'Tolong pisahkan kerupuk agar tetap renyah.', '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `orders` (`id`, `order_code`, `customer_id`, `merchant_id`, `delivery_date`, `delivery_time`, `delivery_address`, `total_amount`, `tax_amount`, `delivery_fee`, `grand_total`, `status`, `payment_status`, `payment_method`, `payment_receipt`, `notes`, `created_at`, `updated_at`) VALUES (2, 'ORD-20261007-002', 2, 1, '2026-10-10 00:00:00', '12:00 - 12:30 WIB', 'Gedung Jasamedika Tower Lt. 5, Jl. Gatot Subroto Kav. 32, Jakarta Selatan', 1350000, 135000, 35000, 1520000, 'pending', 'unpaid', 'Corporate Billing', NULL, 'Tolong dikirimkan sebelum jam 12 siang.', '2026-10-08 05:08:21', '2026-10-08 05:08:21');

-- Dumping data for table `order_items` --
INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `menu_name`, `unit_price`, `quantity`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES (1, 1, 1, 'Paket Nasi Liwet Solo Komplit', 38000, 50, 1900000, 'Level pedas sedang', '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `order_items` (`id`, `order_id`, `menu_id`, `menu_name`, `unit_price`, `quantity`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES (2, 2, 2, 'Nasi Rendang Sapi Padang Authentic', 45000, 30, 1350000, NULL, '2026-10-08 05:08:21', '2026-10-08 05:08:21');

-- Dumping data for table `invoices` --
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `issue_date`, `due_date`, `amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES (1, 'INV-20261007-CAT-0001', 1, '2026-10-08 05:08:21', '2026-10-15 05:08:21', 2140000, 'paid', 'Pembayaran via Corporate Billing B2B telah diverifikasi.', '2026-10-08 05:08:21', '2026-10-08 05:08:21');
INSERT INTO `invoices` (`id`, `invoice_number`, `order_id`, `issue_date`, `due_date`, `amount`, `status`, `notes`, `created_at`, `updated_at`) VALUES (2, 'INV-20261007-CAT-0002', 2, '2026-10-08 05:08:21', '2026-10-13 05:08:21', 1520000, 'unpaid', 'Menunggu pembayaran dari divisi keuangan kantor.', '2026-10-08 05:08:21', '2026-10-08 05:08:21');

-- Dumping data for table `reviews` --
INSERT INTO `reviews` (`id`, `order_id`, `customer_id`, `merchant_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES (1, 1, 2, 1, 5, 'Makanan sangat lezat, porsi pas untuk tim kami, dan datang tepat waktu sebelum jam istirahat!', '2026-10-08 05:08:21', '2026-10-08 05:08:21');

SET FOREIGN_KEY_CHECKS=1;
COMMIT;
