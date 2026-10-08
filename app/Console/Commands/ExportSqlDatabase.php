<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportSqlDatabase extends Command
{
    protected $signature = 'db:export-sql';
    protected $description = 'Export database tables and seed data to database.sql file in root directory';

    public function handle()
    {
        $sql = "-- ==========================================================\n";
        $sql .= "-- MarketPlace Katering (CaterHub) - MySQL Database Export\n";
        $sql .= "-- Created for JASAMEDIKA TRANSMEDIC PT Coding Test\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- ==========================================================\n\n";

        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "START TRANSACTION;\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";

        $tables = [
            'users',
            'password_reset_tokens',
            'sessions',
            'merchant_profiles',
            'customer_profiles',
            'categories',
            'menus',
            'orders',
            'order_items',
            'invoices',
            'reviews',
        ];

        // 1. Users Table Schema & Data
        $sql .= "-- --------------------------------------------------------\n";
        $sql .= "-- Table structure for `users`\n";
        $sql .= "-- --------------------------------------------------------\n";
        $sql .= "DROP TABLE IF EXISTS `users`;\n";
        $sql .= "CREATE TABLE `users` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `name` varchar(255) NOT NULL,\n";
        $sql .= "  `email` varchar(255) NOT NULL,\n";
        $sql .= "  `email_verified_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `password` varchar(255) NOT NULL,\n";
        $sql .= "  `role` varchar(255) NOT NULL DEFAULT 'customer',\n";
        $sql .= "  `phone` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `avatar` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `remember_token` varchar(100) DEFAULT NULL,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  UNIQUE KEY `users_email_unique` (`email`)\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Merchant Profiles
        $sql .= "-- Table structure for `merchant_profiles`\n";
        $sql .= "DROP TABLE IF EXISTS `merchant_profiles`;\n";
        $sql .= "CREATE TABLE `merchant_profiles` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `user_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `company_name` varchar(255) NOT NULL,\n";
        $sql .= "  `slug` varchar(255) NOT NULL,\n";
        $sql .= "  `description` text DEFAULT NULL,\n";
        $sql .= "  `phone` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `address` text DEFAULT NULL,\n";
        $sql .= "  `city` varchar(255) NOT NULL DEFAULT 'Jakarta',\n";
        $sql .= "  `logo` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `banner` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `cuisine_type` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `min_order_amount` decimal(12,2) NOT NULL DEFAULT 0.00,\n";
        $sql .= "  `rating_avg` decimal(3,2) NOT NULL DEFAULT 0.00,\n";
        $sql .= "  `status` enum('active','pending','suspended') NOT NULL DEFAULT 'active',\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  UNIQUE KEY `merchant_profiles_slug_unique` (`slug`),\n";
        $sql .= "  KEY `merchant_profiles_user_id_foreign` (`user_id`),\n";
        $sql .= "  CONSTRAINT `merchant_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Customer Profiles
        $sql .= "-- Table structure for `customer_profiles`\n";
        $sql .= "DROP TABLE IF EXISTS `customer_profiles`;\n";
        $sql .= "CREATE TABLE `customer_profiles` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `user_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `company_name` varchar(255) NOT NULL,\n";
        $sql .= "  `pic_name` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `phone` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `office_address` text DEFAULT NULL,\n";
        $sql .= "  `city` varchar(255) NOT NULL DEFAULT 'Jakarta',\n";
        $sql .= "  `employee_count` int(11) DEFAULT NULL,\n";
        $sql .= "  `notes` text DEFAULT NULL,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  KEY `customer_profiles_user_id_foreign` (`user_id`),\n";
        $sql .= "  CONSTRAINT `customer_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Categories
        $sql .= "-- Table structure for `categories`\n";
        $sql .= "DROP TABLE IF EXISTS `categories`;\n";
        $sql .= "CREATE TABLE `categories` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `name` varchar(255) NOT NULL,\n";
        $sql .= "  `slug` varchar(255) NOT NULL,\n";
        $sql .= "  `icon` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `description` text DEFAULT NULL,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  UNIQUE KEY `categories_slug_unique` (`slug`)\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Menus
        $sql .= "-- Table structure for `menus`\n";
        $sql .= "DROP TABLE IF EXISTS `menus`;\n";
        $sql .= "CREATE TABLE `menus` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `merchant_profile_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `category_id` bigint(20) UNSIGNED DEFAULT NULL,\n";
        $sql .= "  `name` varchar(255) NOT NULL,\n";
        $sql .= "  `slug` varchar(255) NOT NULL,\n";
        $sql .= "  `description` text DEFAULT NULL,\n";
        $sql .= "  `price` decimal(12,2) NOT NULL,\n";
        $sql .= "  `photo` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `min_portion` int(11) NOT NULL DEFAULT 10,\n";
        $sql .= "  `dietary_tags` varchar(255) DEFAULT NULL,\n";
        $sql .= "  `is_available` tinyint(1) NOT NULL DEFAULT 1,\n";
        $sql .= "  `sales_count` int(11) NOT NULL DEFAULT 0,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  KEY `menus_merchant_profile_id_foreign` (`merchant_profile_id`),\n";
        $sql .= "  KEY `menus_category_id_foreign` (`category_id`),\n";
        $sql .= "  CONSTRAINT `menus_merchant_profile_id_foreign` FOREIGN KEY (`merchant_profile_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE CASCADE,\n";
        $sql .= "  CONSTRAINT `menus_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Orders
        $sql .= "-- Table structure for `orders`\n";
        $sql .= "DROP TABLE IF EXISTS `orders`;\n";
        $sql .= "CREATE TABLE `orders` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `order_code` varchar(255) NOT NULL,\n";
        $sql .= "  `customer_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `merchant_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `delivery_date` date NOT NULL,\n";
        $sql .= "  `delivery_time` varchar(255) NOT NULL DEFAULT '12:00 - Makan Siang',\n";
        $sql .= "  `delivery_address` text NOT NULL,\n";
        $sql .= "  `total_amount` decimal(12,2) NOT NULL,\n";
        $sql .= "  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,\n";
        $sql .= "  `delivery_fee` decimal(12,2) NOT NULL DEFAULT 0.00,\n";
        $sql .= "  `grand_total` decimal(12,2) NOT NULL,\n";
        $sql .= "  `status` enum('pending','confirmed','preparing','delivering','delivered','completed','cancelled') NOT NULL DEFAULT 'pending',\n";
        $sql .= "  `payment_status` enum('unpaid','paid','verified') NOT NULL DEFAULT 'unpaid',\n";
        $sql .= "  `payment_method` varchar(255) NOT NULL DEFAULT 'Corporate Billing',\n";
        $sql .= "  `notes` text DEFAULT NULL,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  UNIQUE KEY `orders_order_code_unique` (`order_code`),\n";
        $sql .= "  KEY `orders_customer_id_foreign` (`customer_id`),\n";
        $sql .= "  KEY `orders_merchant_id_foreign` (`merchant_id`),\n";
        $sql .= "  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,\n";
        $sql .= "  CONSTRAINT `orders_merchant_id_foreign` FOREIGN KEY (`merchant_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE CASCADE\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Order Items
        $sql .= "-- Table structure for `order_items`\n";
        $sql .= "DROP TABLE IF EXISTS `order_items`;\n";
        $sql .= "CREATE TABLE `order_items` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `order_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `menu_id` bigint(20) UNSIGNED DEFAULT NULL,\n";
        $sql .= "  `menu_name` varchar(255) NOT NULL,\n";
        $sql .= "  `unit_price` decimal(12,2) NOT NULL,\n";
        $sql .= "  `quantity` int(11) NOT NULL,\n";
        $sql .= "  `subtotal` decimal(12,2) NOT NULL,\n";
        $sql .= "  `notes` text DEFAULT NULL,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  KEY `order_items_order_id_foreign` (`order_id`),\n";
        $sql .= "  KEY `order_items_menu_id_foreign` (`menu_id`),\n";
        $sql .= "  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,\n";
        $sql .= "  CONSTRAINT `order_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE SET NULL\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Invoices
        $sql .= "-- Table structure for `invoices`\n";
        $sql .= "DROP TABLE IF EXISTS `invoices`;\n";
        $sql .= "CREATE TABLE `invoices` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `invoice_number` varchar(255) NOT NULL,\n";
        $sql .= "  `order_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `issue_date` date NOT NULL,\n";
        $sql .= "  `due_date` date NOT NULL,\n";
        $sql .= "  `amount` decimal(12,2) NOT NULL,\n";
        $sql .= "  `status` enum('unpaid','paid','cancelled') NOT NULL DEFAULT 'unpaid',\n";
        $sql .= "  `notes` text DEFAULT NULL,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),\n";
        $sql .= "  KEY `invoices_order_id_foreign` (`order_id`),\n";
        $sql .= "  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Reviews
        $sql .= "-- Table structure for `reviews`\n";
        $sql .= "DROP TABLE IF EXISTS `reviews`;\n";
        $sql .= "CREATE TABLE `reviews` (\n";
        $sql .= "  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n";
        $sql .= "  `order_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `customer_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `merchant_id` bigint(20) UNSIGNED NOT NULL,\n";
        $sql .= "  `rating` int(11) NOT NULL DEFAULT 5,\n";
        $sql .= "  `comment` text DEFAULT NULL,\n";
        $sql .= "  `created_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  `updated_at` timestamp NULL DEFAULT NULL,\n";
        $sql .= "  PRIMARY KEY (`id`),\n";
        $sql .= "  KEY `reviews_order_id_foreign` (`order_id`),\n";
        $sql .= "  KEY `reviews_customer_id_foreign` (`customer_id`),\n";
        $sql .= "  KEY `reviews_merchant_id_foreign` (`merchant_id`),\n";
        $sql .= "  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,\n";
        $sql .= "  CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,\n";
        $sql .= "  CONSTRAINT `reviews_merchant_id_foreign` FOREIGN KEY (`merchant_id`) REFERENCES `merchant_profiles` (`id`) ON DELETE CASCADE\n";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

        // Now Dump Data from Database
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) continue;
            $rows = DB::table($table)->get();
            if ($rows->count() > 0) {
                $sql .= "-- Dumping data for table `$table` --\n";
                foreach ($rows as $row) {
                    $array = (array) $row;
                    $cols = array_keys($array);
                    $escapedCols = array_map(fn($c) => "`$c`", $cols);
                    $vals = array_values($array);
                    $escapedVals = array_map(function ($val) {
                        if (is_null($val)) return "NULL";
                        if (is_numeric($val) && !is_string($val)) return $val;
                        $escaped = str_replace(["\\", "'", "\r", "\n"], ["\\\\", "\\'", "\\r", "\\n"], $val);
                        return "'$escaped'";
                    }, $vals);

                    $sql .= "INSERT INTO `$table` (" . implode(', ', $escapedCols) . ") VALUES (" . implode(', ', $escapedVals) . ");\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $sql .= "COMMIT;\n";

        file_put_contents(base_path('database.sql'), $sql);
        $this->info("database.sql has been successfully generated in the project root!");
    }
}
