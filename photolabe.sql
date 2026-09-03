-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2026 at 07:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `photolab_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `before_after_categories`
--

CREATE TABLE `before_after_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `before_after_categories`
--

INSERT INTO `before_after_categories` (`id`, `name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Retouching', 'retouching', 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(2, 'Background', 'background', 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(3, 'Color', 'color', 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `before_after_projects`
--

CREATE TABLE `before_after_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `before_image` varchar(255) NOT NULL,
  `after_image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `before_after_projects`
--

INSERT INTO `before_after_projects` (`id`, `category_id`, `service_id`, `title`, `before_image`, `after_image`, `description`, `sort_order`, `is_featured`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, 'Portrait Retouching', 'demo/before.jpg', 'demo/after.jpg', 'Professional skin retouching and beauty editing', 0, 1, 'published', '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(2, 2, NULL, 'Product Background Removal', 'demo/before.jpg', 'demo/after.jpg', 'Clean background removal for e-commerce', 0, 1, 'published', '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(3, 3, NULL, 'Color Grading', 'demo/before.jpg', 'demo/after.jpg', 'Professional color correction and grading', 0, 1, 'published', '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(4, 1, NULL, 'Beauty Retouching', 'demo/before.jpg', 'demo/after.jpg', 'High-end beauty retouching', 0, 1, 'published', '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_categories`
--

CREATE TABLE `blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_categories`
--

INSERT INTO `blog_categories` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Photography Tips', 'photography-tips', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(2, 'Photo Editing', 'photo-editing', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(3, 'Industry News', 'industry-news', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(4, 'Tutorials', 'tutorials', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `category_id`, `author_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `is_featured`, `status`, `published_at`, `seo_title`, `seo_description`, `views_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, '10 Product Photography Tips for E-commerce', '10-product-photography-tips', 'Master product photography with these essential tips for e-commerce success.', '<p>Product photography is crucial for e-commerce success. Here are 10 tips to help you capture stunning product images that convert.</p><h2>1. Use Proper Lighting</h2><p>Good lighting is the foundation of great product photography. Natural light or studio strobes can make a huge difference.</p>', NULL, 1, 'published', '2026-08-28 09:53:19', NULL, NULL, 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(2, 2, 1, 'Complete Guide to Background Removal', 'complete-guide-background-removal', 'Learn professional techniques for perfect background removal in Photoshop.', '<p>Background removal is one of the most requested photo editing services. Here is our complete guide to achieving perfect results.</p>', NULL, 1, 'published', '2026-08-30 09:53:19', NULL, NULL, 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(3, 4, 1, 'Color Correction Mastery: A Step-by-Step Tutorial', 'color-correction-mastery', 'Master color correction with this comprehensive step-by-step tutorial.', '<p>Color correction is an essential skill for any photographer or retoucher. This tutorial covers everything from white balance to creative color grading.</p>', NULL, 1, 'published', '2026-09-01 09:53:19', NULL, NULL, 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_post_tag`
--

CREATE TABLE `blog_post_tag` (
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_tags`
--

CREATE TABLE `blog_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_tags`
--

INSERT INTO `blog_tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Lightroom', 'lightroom', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(2, 'Photoshop', 'photoshop', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(3, 'Retouching', 'retouching', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(4, 'Color Correction', 'color-correction', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(5, 'Product Photography', 'product-photography', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(6, 'Portrait', 'portrait', '2026-09-02 09:53:19', '2026-09-02 09:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `session_id`, `coupon_code`, `discount`, `created_at`, `updated_at`) VALUES
(1, NULL, 'U597vuljXMUapt90zdQ8lRHOUz6oYdYb52pqJHjz', NULL, 0.00, '2026-09-02 11:05:14', '2026-09-02 11:05:14'),
(2, NULL, '24oYQdRNYuaRhK7BSYAyIXoUTNgZBnhW8YbVj3s7', NULL, 0.00, '2026-09-02 11:06:08', '2026-09-02 11:06:08'),
(3, NULL, 'Ae3TzRnr57lQ1qBLUFDmamomRgAgp0aLvwzAtpOm', NULL, 0.00, '2026-09-02 11:06:40', '2026-09-02 11:06:40'),
(4, NULL, 'yyYSy1WSfLsaGhfUn9Ya1959pBZ3hqEgb17tEmqU', NULL, 0.00, '2026-09-02 11:07:17', '2026-09-02 11:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_amount` decimal(10,2) DEFAULT NULL,
  `max_uses` decimal(10,2) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `due_date` date DEFAULT NULL,
  `paid_at` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `folder_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_folders`
--

CREATE TABLE `media_folders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000010_create_services_tables', 1),
(5, '2024_01_01_000020_create_portfolio_tables', 1),
(6, '2024_01_01_000030_create_before_after_tables', 1),
(7, '2024_01_01_000040_create_product_tables', 1),
(8, '2024_01_01_000050_create_quote_tables', 1),
(9, '2024_01_01_000060_create_order_tables', 1),
(10, '2024_01_01_000070_create_payment_tables', 1),
(11, '2024_01_01_000080_create_cms_tables', 1),
(12, '2024_01_01_000090_create_other_tables', 1),
(13, '2024_01_01_000095_create_cart_tables', 1),
(14, '2024_01_01_000096_create_purchases_and_audit_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quote_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `quantity` int(11) NOT NULL DEFAULT 1,
  `deadline` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `priority` varchar(255) NOT NULL DEFAULT 'normal',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_files`
--

CREATE TABLE `order_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'input',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_messages`
--

CREATE TABLE `order_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_revisions`
--

CREATE TABLE `order_revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `template` varchar(255) NOT NULL DEFAULT 'default',
  `featured_image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `template`, `featured_image`, `status`, `seo_title`, `seo_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'About Us', 'about-us', '<h2>Our Story</h2><p>PhotoLabe is a professional photo editing and creative design studio.</p><h2>Our Mission</h2><p>We combine technical expertise with creative vision.</p>', 'default', NULL, 'published', NULL, NULL, '2026-09-02 10:38:51', '2026-09-02 10:38:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `gateway` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_categories`
--

CREATE TABLE `portfolio_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_categories`
--

INSERT INTO `portfolio_categories` (`id`, `name`, `slug`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Product Photography', 'product-photography', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(2, 'Jewelry', 'jewelry', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(3, 'Fashion', 'fashion', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(4, 'Real Estate', 'real-estate', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(5, 'Wedding', 'wedding', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(6, 'Graphic Design', 'graphic-design', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_images`
--

CREATE TABLE `portfolio_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_projects`
--

CREATE TABLE `portfolio_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `client` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_projects`
--

INSERT INTO `portfolio_projects` (`id`, `category_id`, `title`, `slug`, `client`, `description`, `featured_image`, `url`, `is_featured`, `status`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'E-commerce Product Shoot', 'ecommerce-product-shoot', 'FashionCo', '<p>Complete product photography and retouching for an online fashion retailer.</p>', NULL, NULL, 1, 'published', 0, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(2, 2, 'Luxury Diamond Collection', 'luxury-diamond-collection', 'GemHouse', '<p>High-end jewelry retouching for a luxury brand catalog.</p>', NULL, NULL, 1, 'published', 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(3, 3, 'Summer Fashion Campaign', 'summer-fashion-campaign', 'StyleBrand', '<p>Fashion photography retouching for seasonal marketing campaign.</p>', NULL, NULL, 1, 'published', 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(4, 4, 'Luxury Apartment Listings', 'luxury-apartment-listings', 'PrimeRealty', '<p>Real estate photo enhancement for luxury property listings.</p>', NULL, NULL, 1, 'published', 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(5, 5, 'Destination Wedding Album', 'destination-wedding-album', 'Sarah & Mike', '<p>Complete wedding photo editing for a destination wedding.</p>', NULL, NULL, 1, 'published', 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(6, 6, 'Brand Identity Design', 'brand-identity-design', 'TechStartup', '<p>Complete brand identity and marketing materials design.</p>', NULL, NULL, 1, 'published', 0, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_project_tag`
--

CREATE TABLE `portfolio_project_tag` (
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_project_tag`
--

INSERT INTO `portfolio_project_tag` (`project_id`, `tag_id`) VALUES
(1, 3),
(1, 6),
(2, 2),
(2, 6),
(3, 3),
(3, 4),
(4, 2),
(4, 6),
(5, 2),
(5, 5),
(6, 3),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `portfolio_tags`
--

CREATE TABLE `portfolio_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolio_tags`
--

INSERT INTO `portfolio_tags` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Retouching', 'retouching', '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(2, 'Color Correction', 'color-correction', '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(3, 'Background Removal', 'background-removal', '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(4, 'Composite', 'composite', '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(5, 'Shadow', 'shadow', '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(6, 'Clipping Path', 'clipping-path', '2026-09-02 09:53:18', '2026-09-02 09:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `compatibility` varchar(255) DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `featured_image` varchar(255) DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `title`, `slug`, `price`, `sale_price`, `short_description`, `description`, `compatibility`, `features`, `featured_image`, `download_count`, `is_featured`, `status`, `seo_title`, `seo_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Cinematic Film Presets', 'cinematic-film-presets', 29.99, NULL, '30 cinematic film-style presets for Lightroom', '<p>Transform your photos with these 30 cinematic film-inspired presets. Perfect for portrait and street photography.</p>', 'Lightroom Classic, Lightroom CC, Camera Raw', '[\"30 presets\",\"Before\\/after preview\",\"Works on mobile & desktop\"]', NULL, 0, 1, 'published', NULL, NULL, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(2, 1, 'Portrait Perfection Presets', 'portrait-perfection-presets', 24.99, NULL, '25 presets designed for portrait photography', '<p>Enhance your portraits with these professional presets designed specifically for skin tones and natural lighting.</p>', 'Lightroom Classic, Lightroom CC', '[\"25 presets\",\"Skin tone optimized\",\"One-click apply\"]', NULL, 0, 1, 'published', NULL, NULL, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(3, 2, 'Skin Retouching Actions', 'skin-retouching-actions', 19.99, NULL, 'Professional skin retouching in one click', '<p>Automate your skin retouching workflow with these professional Photoshop actions.</p>', 'Photoshop CC 2020+', '[\"15 actions\",\"Frequency separation\",\"Dodge & burn\"]', NULL, 0, 1, 'published', NULL, NULL, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(4, 5, 'Cinematic LUTs Pack', 'cinematic-luts-pack', 34.99, NULL, '20 cinematic LUTs for video color grading', '<p>Professional cinematic LUTs for video color grading. Compatible with all major video editors.</p>', 'Premiere Pro, DaVinci Resolve, Final Cut Pro', '[\"20 LUTs\",\".cube format\",\"4K compatible\"]', NULL, 0, 1, 'published', NULL, NULL, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `slug`, `description`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Lightroom Presets', 'lightroom-presets', 'Professional Lightroom presets for photographers', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(2, 'Photoshop Actions', 'photoshop-actions', 'Time-saving Photoshop actions', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(3, 'Photoshop Brushes', 'photoshop-brushes', 'Custom Photoshop brushes', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(4, 'Overlays', 'overlays', 'Photo overlays and effects', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(5, 'LUTs', 'luts', 'Color grading LUTs', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(6, 'Textures', 'textures', 'Background textures and patterns', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(7, 'Mockups', 'mockups', 'Product and branding mockups', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(8, 'Templates', 'templates', 'Design templates for social media and print', NULL, 0, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_files`
--

CREATE TABLE `product_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `deadline` date DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `quoted_price` decimal(10,2) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `priority` varchar(255) NOT NULL DEFAULT 'normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote_files`
--

CREATE TABLE `quote_files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quote_id` bigint(20) UNSIGNED NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `stored_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quote_items`
--

CREATE TABLE `quote_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quote_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reviewable_type` varchar(255) NOT NULL,
  `reviewable_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `starting_price` decimal(10,2) DEFAULT NULL,
  `delivery_time` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category_id`, `title`, `slug`, `short_description`, `description`, `featured_image`, `starting_price`, `delivery_time`, `is_featured`, `status`, `seo_title`, `seo_description`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Professional Photo Retouching', 'professional-photo-retouching', 'Expert retouching for portraits, products, and commercial photography.', '<p>Our professional photo retouching service covers everything from basic cleanup to advanced skin retouching, object removal, and compositing.</p><p>We work with photographers, e-commerce businesses, and brands to deliver pixel-perfect results.</p>', NULL, 2.99, '24 hours', 1, 'published', NULL, NULL, 0, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(2, 2, 'Background Removal', 'background-removal', 'Precise background removal with clean edges for product photography.', '<p>Get clean, professional product images with perfectly removed backgrounds. Our clipping path experts deliver pixel-perfect results.</p>', NULL, 1.49, '12 hours', 1, 'published', NULL, NULL, 0, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(3, 3, 'Color Correction', 'color-correction', 'Professional color grading, white balance, and exposure correction.', '<p>Transform your images with professional color correction. We fix white balance, exposure, contrast, and color grading.</p>', NULL, 1.99, '12 hours', 1, 'published', NULL, NULL, 0, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(4, 4, 'Graphic Design', 'graphic-design', 'Creative graphic design for marketing materials and branding.', '<p>From social media graphics to print materials, our design team creates stunning visuals that capture attention.</p>', NULL, 29.99, '48 hours', 1, 'published', NULL, NULL, 0, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(5, 1, 'Jewelry Retouching', 'jewelry-retouching', 'Specialized retouching for jewelry photography.', '<p>Expert jewelry retouching including stone enhancement, metal polishing, shadow creation, and background cleanup.</p>', NULL, 3.99, '24 hours', 1, 'published', NULL, NULL, 0, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(6, 2, 'Clipping Path', 'clipping-path', 'Precise manual clipping paths for complex objects.', '<p>Hand-drawn clipping paths for products with complex shapes like hair, fur, and transparent objects.</p>', NULL, 1.99, '12 hours', 1, 'published', NULL, NULL, 0, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `slug`, `description`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Photo Retouching', 'photo-retouching', 'Professional photo retouching services', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(2, 'Background Removal', 'background-removal', 'Clean background removal and replacement', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(3, 'Color Correction', 'color-correction', 'Professional color grading and correction', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(4, 'Creative Design', 'creative-design', 'Graphic design and creative services', NULL, 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_features`
--

CREATE TABLE `service_features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_features`
--

INSERT INTO `service_features` (`id`, `service_id`, `title`, `description`, `icon`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'High Quality', 'Pixel-perfect results', NULL, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(2, 1, 'Fast Delivery', 'Quick turnaround time', NULL, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(3, 1, 'Free Revisions', 'Until you are satisfied', NULL, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(4, 2, 'High Quality', 'Pixel-perfect results', NULL, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(5, 2, 'Fast Delivery', 'Quick turnaround time', NULL, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(6, 2, 'Free Revisions', 'Until you are satisfied', NULL, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(7, 3, 'High Quality', 'Pixel-perfect results', NULL, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(8, 3, 'Fast Delivery', 'Quick turnaround time', NULL, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(9, 3, 'Free Revisions', 'Until you are satisfied', NULL, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(10, 4, 'High Quality', 'Pixel-perfect results', NULL, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(11, 4, 'Fast Delivery', 'Quick turnaround time', NULL, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(12, 4, 'Free Revisions', 'Until you are satisfied', NULL, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(13, 5, 'High Quality', 'Pixel-perfect results', NULL, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(14, 5, 'Fast Delivery', 'Quick turnaround time', NULL, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(15, 5, 'Free Revisions', 'Until you are satisfied', NULL, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(16, 6, 'High Quality', 'Pixel-perfect results', NULL, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(17, 6, 'Fast Delivery', 'Quick turnaround time', NULL, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(18, 6, 'Free Revisions', 'Until you are satisfied', NULL, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `service_pricing`
--

CREATE TABLE `service_pricing` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_pricing`
--

INSERT INTO `service_pricing` (`id`, `service_id`, `plan_name`, `price`, `description`, `features`, `is_popular`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Basic', 2.99, 'Standard quality', '[\"5 images\",\"24h delivery\",\"1 revision\"]', 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(2, 1, 'Premium', 5.98, 'Premium quality', '[\"20 images\",\"12h delivery\",\"Unlimited revisions\"]', 1, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(3, 1, 'Enterprise', 14.95, 'Bulk pricing', '[\"100+ images\",\"Priority support\",\"Dedicated designer\"]', 0, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(4, 2, 'Basic', 1.49, 'Standard quality', '[\"5 images\",\"24h delivery\",\"1 revision\"]', 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(5, 2, 'Premium', 2.98, 'Premium quality', '[\"20 images\",\"12h delivery\",\"Unlimited revisions\"]', 1, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(6, 2, 'Enterprise', 7.45, 'Bulk pricing', '[\"100+ images\",\"Priority support\",\"Dedicated designer\"]', 0, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(7, 3, 'Basic', 1.99, 'Standard quality', '[\"5 images\",\"24h delivery\",\"1 revision\"]', 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(8, 3, 'Premium', 3.98, 'Premium quality', '[\"20 images\",\"12h delivery\",\"Unlimited revisions\"]', 1, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(9, 3, 'Enterprise', 9.95, 'Bulk pricing', '[\"100+ images\",\"Priority support\",\"Dedicated designer\"]', 0, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(10, 4, 'Basic', 29.99, 'Standard quality', '[\"5 images\",\"24h delivery\",\"1 revision\"]', 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(11, 4, 'Premium', 59.98, 'Premium quality', '[\"20 images\",\"12h delivery\",\"Unlimited revisions\"]', 1, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(12, 4, 'Enterprise', 149.95, 'Bulk pricing', '[\"100+ images\",\"Priority support\",\"Dedicated designer\"]', 0, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(13, 5, 'Basic', 3.99, 'Standard quality', '[\"5 images\",\"24h delivery\",\"1 revision\"]', 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(14, 5, 'Premium', 7.98, 'Premium quality', '[\"20 images\",\"12h delivery\",\"Unlimited revisions\"]', 1, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(15, 5, 'Enterprise', 19.95, 'Bulk pricing', '[\"100+ images\",\"Priority support\",\"Dedicated designer\"]', 0, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(16, 6, 'Basic', 1.99, 'Standard quality', '[\"5 images\",\"24h delivery\",\"1 revision\"]', 0, 1, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(17, 6, 'Premium', 3.98, 'Premium quality', '[\"20 images\",\"12h delivery\",\"Unlimited revisions\"]', 1, 2, '2026-09-02 09:53:18', '2026-09-02 09:53:18'),
(18, 6, 'Enterprise', 9.95, 'Bulk pricing', '[\"100+ images\",\"Priority support\",\"Dedicated designer\"]', 0, 3, '2026-09-02 09:53:18', '2026-09-02 09:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('00m3qYIHKp81Ns2YolehzlKshuegQJMpHDPs9CzG', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0JrQWFvZm83T3dvQmplNXhiVnNJa0NVMW5LTW92VjY2a1RCOVphNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nZXQtYS1xdW90ZSI7czo1OiJyb3V0ZSI7czoxMjoicXVvdGUuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364672),
('05CKdE4LcwSDHZziFxZpJ0KCsumUHy9ehhpZmoJt', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRHBnQUdGR3F2M1lYT0Y3dHNISGhPZTlXRGpWYTlUbFU4RW9ockFtUSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3Byb2ZpbGUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3Byb2ZpbGUiO3M6NToicm91dGUiO3M6NzoicHJvZmlsZSI7fX0=', 1788365576),
('0EXWjXgtcaBDhNhJ8jZkBlCKFB8oFo1F19xCWCSQ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDF5akU3MFoyT2tSeHFWVlg5OTBnOGhQTXFaUXhMV0g4MmtRa3RlTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjc6ImNvbnRhY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364673),
('0L0sjt88VcrI7WUfIeElC9wtf8PY1yoWaygZ5jB6', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieWx3bUJZaUQ5UFNGS01sT2dmdGdwN1ZhT0dncEJNQXJPWmVtVUZxZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364670),
('1PkhDnEBrSZyrNhOxKHHnJCWlLzn5a2z6VOlUi79', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNnJDT0tTdFNVQ3hwcjM5RnJWSnlSa3NFRWNoZW5PeFRuQmRLdFdrVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3Jldmlld3MiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozNToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3Jldmlld3MiO3M6NToicm91dGUiO3M6MTk6ImFkbWluLnJldmlld3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788367090),
('24oYQdRNYuaRhK7BSYAyIXoUTNgZBnhW8YbVj3s7', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2g4OVFwWXBwd2txNVlxekpBVDFMRk42bEVkcUhBT2pDalE3andRQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368769),
('2IkOFdcUWfWI4shCNJE3sWgaJx1aCraxOMl4dxtI', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic212cHZFOUhqTXQ5cm5LZWRwM0RyNGlQblBMRVhqZ0NJa2dITU96QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYWdlL2Fib3V0IjtzOjU6InJvdXRlIjtzOjk6InBhZ2Uuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788367089),
('3HLOe8zXTe5szOUiFRvtCdIJwgtDuxLYgNWCNfuj', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTWd2WnRKQVBiQUZnVDdvQk45OVY2TU1mUUc5YkhNQWRWdGdwRkVMSyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365632),
('3vX0fJ7G8lEHXL2bAw9eaSP00iu8STPhdhK5uPd5', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnBEUUpiUGpwSGQxbmZraWJDZXNOeDNRWnZrTEh2M3BlWXdQUXpUSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365621),
('4sFbSW17XDoRgW1Jr3EQJoB0KfxfJ1TkHEuQz4qi', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlp4dkNkQlF4dFByam5BRXg5d29OY2pvYVkwbHVDeUE0ejRuM3doMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iZWZvcmUtYWZ0ZXIiO3M6NToicm91dGUiO3M6MTI6ImJlZm9yZS1hZnRlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788368836),
('4VWKLiDPjkOW5B6Ihf4YXDsXqlSCnWnN9mObvc1z', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGdJSDd4eXZCNU9tam1KVWJ5a1BTVDJENFJmZHBQOHJjU2NUYkllWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mYXEiO3M6NToicm91dGUiO3M6MzoiZmFxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364602),
('4xAQo6Et2A6PIYmsP1RPVRyyPLiokKdealWJsNw1', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaWt3elZHeDZlZGUyMXdRa3ltaVhtOFdiN2tiVDJ1bkMwcG01Q0VhZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8vZWNvbW1lcmNlLXByb2R1Y3Qtc2hvb3QiO3M6NToicm91dGUiO3M6MTQ6InBvcnRmb2xpby5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788366132),
('5iUo1kTEHuqFpVln9ala6J1JiePAcWhWWzA5ZISM', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.135.0 Chrome/148.0.7778.280 Electron/42.8.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTXdGQnFxM0pqbml5SHRlUXZoVUNFb1E3WnFUUXJQUWhCUno1QU92QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365008),
('63b7EVqrpsJXidHoheESF0aqpoXau2ZjPxZ5zyug', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHFCSExqaDZGcjFTUjNDaVJPRjdUbE9KREhuRElBNjJMVlQxZEc0QSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788368713),
('6KlPzIMLEgH2gek9PFc3r15dysxihIrfDS8qmvpP', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRlhpa0dNWEZBZFlkaDNONkRtVU4zYkQxVUFVNVp1SWxPaXNNN1FoNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mYXEiO3M6NToicm91dGUiO3M6MzoiZmFxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365626),
('6lXtrMNeNyNrqsLWYxSEE3XP1zf8ELu5DLimMRqp', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVhBU3I0aU5sQXhPanNoMjc1ZUpBOGtNWGxhVk5CZGNIZlV6VmJ2WCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364571),
('6QSdW1o4Se828j6JGuc4kQzCEO4DqBhh4ftgrzs1', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1E4YXJOdVZkQU9JdVJ4blo3TmlZTE9wU2dWbzc4YXJvdDZFNHlCViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364600),
('6SjpFBmEpCy1VkOOgoUT6mYeRG7PXX3lGIC7db5n', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibkhrWDltM0pPYXNmbkdWeXM5d2NkNkczNnU5MkJyMXg0WFg4ZnVTRyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2N1c3RvbWVycyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vY3VzdG9tZXJzIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5jdXN0b21lcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365251),
('73mntOlv2uHrA3dDfT7W0UXEOyrgWyj6YUqFM5Lq', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3NqVDNxYklPcWlTTjh2T3NFUHBLM0Y4RVdidVl0N1RQOFBTbE5pTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8iO3M6NToicm91dGUiO3M6MTU6InBvcnRmb2xpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788368832),
('7fOyI4GdJHoHSKaxqFgr3mEkkurHlp9Hr3PtQ7Bu', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXJxSFNvN0VmNmhUSlc3bTlaeDR4RTZiTkF5Z01taDVmcHJyVlFaaSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3Rlc3RpbW9uaWFscyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vdGVzdGltb25pYWxzIjtzOjU6InJvdXRlIjtzOjI0OiJhZG1pbi50ZXN0aW1vbmlhbHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365251),
('7NvVznRCbPNYZTVdoIQB5rZINXagP9Fi3jNV81f7', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVBnTjhlWUs3R2JzeWlSeGhKdHFlc3RUUzN1ZWNsQ0xaamFuTDlNQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8iO3M6NToicm91dGUiO3M6MTU6InBvcnRmb2xpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364572),
('7Xoh4hDKQgutGRyo9FZhZob9uukVRvwcCyDUCRpZ', NULL, '127.0.0.1', 'curl/8.14.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoieTVuWXNxOUtsVTRHRVk3SE4xdnRuRjN1SVFrYkdvSnVjd1hKQ1UwMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788369578),
('83tjsCAbtHGb6XTWFcCdZUzgiW5VaXl6pbVI7PT0', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia09kUDU3aktybXBKTFFDZjZUUUFRQkVsYTV3TEdDQnlsUWxWaVVkdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3NlcnZpY2VzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uc2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365247),
('86yaSl5kGs8g6T02VDZKqgiMhIexhCwAkjIGGExn', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWkhGYjdnVkl0dFVsREtBSFlOWnlvd1ZXcEtZT1pEY2FoM3VEQ05CNSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL21lZGlhIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9tZWRpYSI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ubWVkaWEuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365637),
('8i0uBLDkD2J2SrjV95jY9l8SCTzzGWYDEJ4nGle0', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWxlOW8yQXFEQjlCS2VQeTZNOFduNnJrRHNWUDQycnRHSERzRmZvciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcmljaW5nIjtzOjU6InJvdXRlIjtzOjc6InByaWNpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364603),
('8jZJ4bEyzl3Dop6kuuplBq8WfgJtx4fauimzakfG', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibzU2UWhmUnFVVXhhZFRUTDdJTWJwTWY1dlUwdHU2ZmpPZGlaVjFtRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364672),
('8PgSshlvlpIgAqn3PO6RKxtxtF8OkXp3TT6iYoSO', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGFXSUpySUp3S0YyamdRWDRPUTlxNThjN211UExlT3lMcTJrZEsyMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788366126),
('9cvgtS7d1bVxoyvAnftjj96xFcZPhNItEnXA1mfJ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWFhaRUpkbm1SMGNOeVRiSlQ1TzJpNFdtYm95TDU1YXRXSDZDT0tyaCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3BvcnRmb2xpbyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcG9ydGZvbGlvIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5wb3J0Zm9saW8uaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365633),
('ABC3EskQquLcy866vt05XwlzyXYoBbLWSItnw3AP', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjRoelFLaDJEajdNVHNZMnNsVzdWRW1iejBOdzFUOE1WdVMzRG5TOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364489),
('Ae3TzRnr57lQ1qBLUFDmamomRgAgp0aLvwzAtpOm', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRGVxaUcxaGFYZ01hbVZmazE1T3F5T3d3N2UxUzBVN3ZkdXRESnN0WSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368801),
('aKBraEUYoTLErPrKV5GJes3MRWiyH2JjhHBI5fqe', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUUZlWDdKRHNsSHQ4UE0wVzlkdUpYSUZuNW5ZazR2QkZJTFBFZmtpbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mYXEiO3M6NToicm91dGUiO3M6MzoiZmFxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368835),
('ApfLphTjDSGVTl1kzMM48usf72NwbuW4RU6VGkZ8', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjV0SmxlaWE0M1h5VWtiRnpJeEFWaTdxY0N5b0hmdnVhQ1Myb0tRSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hYm91dCI7czo1OiJyb3V0ZSI7czo1OiJhYm91dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364674),
('aZaZkVFKUZAiTJObm9Gjzlm2krI3mBlmZGYHer01', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0dqR0FUZVh6WjN0VUtCWVlGTE9FUzZoSUFRVXhEU2ZPSXdsMmtnMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364664),
('bGoBysb0I6oQ9PklbqMfV5GDhUctbAC2Dh6v7ZqV', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWE94Wk56SHBoc215WllCeWxYOFVHejJvWHhsSGdMa2RvTGZTYzd0SyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3NldHRpbmdzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uc2V0dGluZ3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365638),
('BLMjlhghyyfufFJXfbZQ9jPJVlE3BgGY8k2Gvvln', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFprSlpjWHpGRW9FT0pScnc0QVk0VWFuSTk5NmZ6d1RjSEh6UGwzYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788367088),
('BOsaNw9frtFHN6zN8OTyA9fPZ4Kx1pihX35eYcia', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicG5neDBkNW9FazB5OEJYcXd2S0lHd2NZVmlBNnoyV0xFSnMyeGVmTiI7czo1OiJlcnJvciI7czoxOToiWW91ciBjYXJ0IGlzIGVtcHR5LiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjE6e2k6MDtzOjU6ImVycm9yIjt9fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY2hlY2tvdXQiO3M6NToicm91dGUiO3M6MTM6ImNoZWNrb3V0LnNob3ciO319', 1788368837),
('c2rkAz10NeabTCmC3RKrfBRtg6rVNYL9rH3uVpl1', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibXo4QkFQeUptdlh5ZTFaTjhNcTdQVVZxWG52S2l0SnVwcnlZd2FtMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcmljaW5nIjtzOjU6InJvdXRlIjtzOjc6InByaWNpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365627),
('CDi3yoP2cQewGsYEM8Caa10Ln6S8wV8qelFsOqxl', NULL, '127.0.0.1', 'curl/8.14.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYzRSNDgydXZublBpcERHcWVuVzBOS0k1amR2YmxZcVlwZlNrOUdvdiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788369576),
('ceAJkNfucGUnVZHGYRPHtISPa1Zc8YppOhMm2CMW', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicUs1NEZZWWtQMTJiRHNqajBtVDV4VUlwWFpaRDQzMkVhVWhOWG5wRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hYm91dCI7czo1OiJyb3V0ZSI7czo1OiJhYm91dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788368835),
('ciOh9O7CzK78lWVttnUcQXgCtFjJpIyu2DBafeG0', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieGpEdGxjSnkyeGE4Z2hVaVE1MVVxaHFxSmZYNGgzRk9TZEtPZldNRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364581),
('CLGA2F9dOiEdNiZFG5ax4bMuV0njSDWkZwNBZwd2', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHM3akdRd014MkVWMmVFTk9KY2dEa2FCYndoSWdDTlRWbUxyaVlLRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364656),
('Cu9ysy3wLOMEmDXVGgWbd78YxpGwNYv4JZmHD5LS', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGRvMTFOWnl2RFAxY3B3Q2RuV1lCWEsxRzhiejNSQzg5VzBYa1pDZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365639),
('D8xd5tRQZr295X7fbRRUTPWhkQyl2FZyNBn2V6fE', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidlNKWG5HekxsSU5tM0FkZU84UjZSNlVFTUFaRkNwN3VKWjhHdzVWdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365574),
('dbnZ7j39t3etS3x2ZIGpzI15hnRrMSM6uXWbUhnW', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2hTQmFqanhMS3hoSzkwcmVZVVdxVUluVDFIRTkzdlFwYXppVFc4MCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjc6ImNvbnRhY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788368834),
('ddJ3e5jsP52cxGVb1tunJKkASoK3LQrIx0VuzTZE', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ21tQVNRWHZwV1ptZjRkMVVKU1E2cVFTUnRialRIWTJYSFlocVZGMCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2Jsb2ciO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2Jsb2ciO3M6NToicm91dGUiO3M6MTY6ImFkbWluLmJsb2cuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365249),
('deJDBZbt2RmKiV065gXk4PPEalJLE4WnaRkUZWUj', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNDVNU2dCQ2xvVFdNTG1LZHUzS0pvd05CN0xvOGtja01ndFdLdmtKciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368833),
('DEq3Kijl5jzEC6Z536QvLlmAXy8v8rEEVHc2ix4w', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEZXMUw1T3ZCODd1VGlkOENSRFJtQ2Q3dHhUWnc0cWJNd3lueFZpSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365628),
('dlV6QLT84NB8mWmpOQ41QgOytTWOzx9eyYE2Fvfb', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSE5tdDQ3Zms5ZmVJMEp5T01iV2dNUEJ6d1kxZVlNSnB2a0VKN1JaQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zaXRlbWFwLnhtbCI7czo1OiJyb3V0ZSI7czo3OiJzaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368724),
('DmLpo164xbT2fqSRr8d97mVqRWmWiCIUc8fev3Zw', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZkRJM3M0dkgydUFnaWtkQ3FRU29wSFJpNlRNOW1jN0QwUVUySXVwZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364605),
('Dr4RyXOHWUiIbZDKutZphVIgD69o3V6FhvsiPNYC', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGVoUHhpNnA1MHNyQ1A1cXhaVzBLa3pRQTZjMUh5TkpYWVY1MjBRTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hYm91dCI7czo1OiJyb3V0ZSI7czo1OiJhYm91dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365626),
('DriHEbheVvZw2p60tWmgPlzch8w4a3f4dYke0Bdx', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic3R5RVloT1ViMzBzajRrdmNraFF5NGZYWGNEWndDZ3dZOG1zMTZldCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364573),
('EIIcNqksJ19HGxMAnGBzFKgITNfAMoIcbNC2ZEKy', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZndBZXlOQ1pBOWtHR0ZEWHNmRUg1bTh0ZldBMTREMWx0emx1M0dVRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NjA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcy9wcm9mZXNzaW9uYWwtcGhvdG8tcmV0b3VjaGluZyI7czo1OiJyb3V0ZSI7czoxMzoic2VydmljZXMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788366130),
('ejCCAbPEJpIWFWxrrsumBuIfbFzzA9uv5TWlFWpC', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1FodVBqSU5rdWozU01ic2pwWWg0ZTN1RE82S2Rab01pcU9TMW8wOCI7czo1OiJlcnJvciI7czoxOToiWW91ciBjYXJ0IGlzIGVtcHR5LiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjE6e2k6MDtzOjU6ImVycm9yIjt9fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMwOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY2hlY2tvdXQiO3M6NToicm91dGUiO3M6MTM6ImNoZWNrb3V0LnNob3ciO319', 1788368723),
('emtl9GQdAuLu2d2HhhPgELEzY8Hg2rCjp81OmOFG', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXBwSG1QMURlRk5Sdmh2UFdBZWg2WUJtUFpLcUFEMzB6cGttVnliUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8iO3M6NToicm91dGUiO3M6MTU6InBvcnRmb2xpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788366127),
('EqliiDO4TXRbxe9OiYvq5nxx5r4aZFdH9E52DUWF', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDk1QzlzUm5YUnRiVldqSXBuekgxT0JPY3FSQ08yVndsSjc4TjNNdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8iO3M6NToicm91dGUiO3M6MTU6InBvcnRmb2xpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364529),
('EUOjgUyrPKPs8NfSIo1QgZAFg5p7U3AJ0oCGvCXd', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFdZRVZmSDUwRWtzcTJIdHJnTTBWa0FGNGVXQjZmZWl0VGJpcE1lRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365620),
('eZLGWFKoJZgC3BL9mrFpnPaw8nLMEqG0IrSiaUQ9', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVTN6MDlDaVdpZGNneUxPZjFFYVR4bGdEVGlLQTJXeXB2djY0MWMzNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364597),
('FdnbbxXj4Xq1m5hB5pxMnWWIfzlfe4XLBhFqgRIs', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSVExSTE1YXFEUWh1b1pja3ZYbm03NTJnaXB6VWJqbklJVDV3REhBMiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2NoYW5nZS1wYXNzd29yZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY2hhbmdlLXBhc3N3b3JkIjtzOjU6InJvdXRlIjtzOjE1OiJwYXNzd29yZC5jaGFuZ2UiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365631),
('fMVdHpXC0th1Qo65dA0KfSjM0tA38fBDogrn8s1m', NULL, '127.0.0.1', 'curl/8.14.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMlNZUXRlelM0MVQzSXNuWVBMV3pQdHE2RE15MU1ma0dWRlFKZjlORCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788369575),
('fsJKswqhYB4wNueXOSF93eL5pDbDQYi6xDGK0RTW', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSnppUkgwTUlaWXVCdFlmeUlhRUdGYzdDcU05TkVxbThHYU9Wa0JtMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364598),
('ge15Al8RXgVQex2D8vS50VtpOkCKBRbVFKADGeRB', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSDlicDJQMmxFeENETTFGa3ZXS2pkV3VwQ0RzV3BhQmE1aFFTdHhYayI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2N1c3RvbWVycyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vY3VzdG9tZXJzIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5jdXN0b21lcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365636),
('glhRAvjxLwmSz8wlowfaE2oGPzBJELbs1TBErm1b', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoialcwN1VJVDR5eXdMaXo2U1RUMWdFR3o1QjRaeGdRckZUTWYwcHpSViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364670),
('Gos9iLlbilcH5brH4jOMPIMvaOgC0PQk5LLtTTlD', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWjM0V2NmRDB0NFp3WVpIWmhNUk9xcnBIY3BkQjBwYW5RRkRzTDBYYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8iO3M6NToicm91dGUiO3M6MTU6InBvcnRmb2xpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365621),
('Gy5Atbe5W2AwBH3L6uPNfjAMu1B3DVIDH4LqXPlz', NULL, '127.0.0.1', 'curl/8.14.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVmRhekRMR1Y5c2p3eGs2SVVSRHkwN1Z0ZGw3ZnY1dWZtQnRvRUJIWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365591),
('hbthLNsw5k4fCKKTLqh16zuHFPV8lLqsawZ3ojEh', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWptTkpsME4yUEtMM2FTNWxmMTdQa0k1NEJFeVp3NHpIeHBEaHpzayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjc6ImNvbnRhY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364601),
('hqdOj9PnBA90OhU6I3HA02vB1LZ7NXKJKtB9OPLD', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiczRrcHVhQUZkdE5oTWZkYjdjRnpuMWJtakZHMFp4ZzNWN0doR3hESyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nZXQtYS1xdW90ZSI7czo1OiJyb3V0ZSI7czoxMjoicXVvdGUuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368833),
('HYSGzDR0l2hE9wXuF1mul2u6mXdmgDNNSwkcinzb', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDFkODdSMEZ2U0pyOEsyN0lkaE9ZUWlFaVptMmw1enFKSXg1eUdMSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365574),
('I3AqaucOG1gbVc46LdIJbqcLREi6toaRAFjnpMu5', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNnZpWGlFNDBLbHdHS2lGWGJta003RWE5R0ZRandpZkxlMFhKVUl1dCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3ZlcmlmeS1lbWFpbCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvdmVyaWZ5LWVtYWlsIjtzOjU6InJvdXRlIjtzOjE5OiJ2ZXJpZmljYXRpb24ubm90aWNlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365631),
('I4pFWm6YtUdwsS1rbhKa8i6HICdAB2CTBVleeFVd', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZklyNWpVenBicFZqMjZ0WjNhMm5ubDdvV0NVbFlNTjBPQVFVRlgxdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365622),
('IBHEABUfJmtXICQZJ9GgYC2kUZWuELvEQiXEBFgD', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUmc3aVYwZmhCWHVTQXZjcFRxdG1HeUpYOUNWbmNvaVhZRGtra29lciI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3Byb2R1Y3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4ucHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365249),
('IyuMTRzTquU3oWlLuCyvAr40B6aZsniHXY3xJolW', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWdNUGdoRm11d0RHZWNIVnBrQ3dJam5aZHhLU1l0TklaWUVVVjJiRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364600),
('j6ahlZ7ZWVDsyBcMrlOQEypiphEUNAPLH2bNUfGU', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnBEb2VNUEJpODFsUWtLYzZUTTRGOHRIUkFDR3dYa3pnT2hXUTUxcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zaXRlbWFwLnhtbCI7czo1OiJyb3V0ZSI7czo3OiJzaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788369578),
('JoJGJoPUeY0PewXmZ31iweFnh20rZ2XmBG6RDgPP', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQldYVzFDNWthcVJGUFZvUkJyeGU4Q3E0amNlWmFwSFRwcFJvNnN1UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcmljaW5nIjtzOjU6InJvdXRlIjtzOjc6InByaWNpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364675),
('jTFcoIXlINahtSovueXPVnPZuUFXTYbId4cff8jL', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEl3bE55OEVYWkNvUjBkY0tZblpPcHpzZk5lT1dHZ2cxQTFtcHFrMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjc6ImNvbnRhY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365625),
('K2ZYGTGXsbJFdxVPG2gs1e8QZOjR8bhCgVMdLAsd', NULL, '127.0.0.1', 'curl/8.14.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoib1NIYTlDdTZuVDlVMTRzMkR6NnNJVnhUOEdGYkViR3c4VFRtOHB5VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788369575),
('L82NnZaITIOiXZNvfxXEGVLPbz6Hpaa0tdPPJrso', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOW5nckdpU1RZV3JWNXBKM1ZSeW1rMTZzbFNSMjJkQk04UEtmQWJjQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zaXRlbWFwLnhtbCI7czo1OiJyb3V0ZSI7czo3OiJzaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368838),
('lJF2o7fr8O3UhP87XmxzSOjg2KNSWv7ea7g391nB', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFZnTWtiTjFlbjhlR296Q2hzZmlSQjNzdTR5SllLY2tEWXlPU0ZaSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iZWZvcmUtYWZ0ZXIiO3M6NToicm91dGUiO3M6MTI6ImJlZm9yZS1hZnRlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365628),
('LuXm67UnCvarOKZe7IiXJn3HXC9yPMX83nyAxasd', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicGFxRGl3TWd6amZDZFNPcVlDaXc0ZXVtRlJ4cmswODZ3Q2Jmd3JpSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364530),
('Mb5H4hVR98JjDzEotjivLwJvTgfiXfOTwhDzk7s4', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUnBtbFZJcWx4ZlFpTFBnb1FWcURtbnAyaTRiS096cjRSa1B0aFVGZyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FjY291bnQiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FjY291bnQiO3M6NToicm91dGUiO3M6MTc6ImFjY291bnQuZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365632),
('mEZLezV8kObqjnuH7o0agUh9cJPnQMC4K0wF0h5f', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUkRlbUxmajNhTGpVUkJuSGNGYWxBeWgyckJSNnl2U2ZQN1kzMVQyZyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2Jsb2ciO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2Jsb2ciO3M6NToicm91dGUiO3M6MTY6ImFkbWluLmJsb2cuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365634),
('mllmymiRc21QoitQKtJSG2M64kIMwojpIcMjeLbJ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmNhMHRtREV0WkVYcDdGdDlWUVhLSjM4VVZLWUpDN3RZdkdnSXJwWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788368839),
('mMjrk1FzG8VMT9APsUJMaFfvViJRCirLuxYylO7p', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUJtUkxFRnc5NHYzZ25ja0tZZldXVzdaenA1RmVuOFgwaG5LZlFJeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nZXQtYS1xdW90ZSI7czo1OiJyb3V0ZSI7czoxMjoicXVvdGUuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364532),
('MprdtqID4zXxmlLkbAGLOja0g3rCgWykrKm535h4', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTVNRZzJsTlJISzFnaVBWdFhXZnJBeUdmY0dQZFVhSmpyWnQwY0h5cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8iO3M6NToicm91dGUiO3M6MTU6InBvcnRmb2xpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364671),
('mQZR1EdNhV5usMZPZpK6bqwwvFt0hNY75lielYDj', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibEtYSGNyUjdNOVlGTG9FbUNyMzYzYVcxNUxjMjQxaE5qbXQ1emFEdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364503),
('muzFPyokGVu6HlI7MHRVKjYRTjM0EJMDAmmfIqfw', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHhIamZqa003bXlHTUlIOFY2YWo1WGFOR2FHbnpmWFdRZnN2aGJVOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365592),
('MYc93zUtqQ2Zdl7XE79ndGp22jv8tN4mhG51NInp', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ3lCeEpnamdNNmpVeExZbTJqNVJXTzg2N0tUSGcwRUx1bzRlOVhnbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788366023),
('NITRmRQvwXaYsHms9NmOvxyObFkQ08wP8A7SMDxD', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXhSbzNTcllWTDNCakY3M2g3dWpPU0VhdTlJWXNndVYzQzBYMTJ2VyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nZXQtYS1xdW90ZSI7czo1OiJyb3V0ZSI7czoxMjoicXVvdGUuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365624),
('nzL5M500I62dXIkc0HgJA2s2qC1Bjxf5DRHoU7TX', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNDNYVEN5TUcyNmI3UDA0dXEzNHp3Uk53eEl5WXlOMVdGZmdlaTFMUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iZWZvcmUtYWZ0ZXIiO3M6NToicm91dGUiO3M6MTI6ImJlZm9yZS1hZnRlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364675),
('O8HtO6CQKKNAxjapvzH5BnH01JvB9AVEuUG817qn', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibTdTZHZzUjV5Q3ltMjhNZnVOdDRKWFEwZmlHNDNCaG5JSkc3UWxMRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wb3J0Zm9saW8iO3M6NToicm91dGUiO3M6MTU6InBvcnRmb2xpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364598),
('oedbsvxPsJVtG5VUikTtxoa16EYs4FOGOrhuhHxO', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUGloOUxhQUN4bXVWZ2lKYVlMRXB4eGg5aVV0REZmbDRnRlIwcjhQcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nZXQtYS1xdW90ZSI7czo1OiJyb3V0ZSI7czoxMjoicXVvdGUuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364573),
('Ohyioj6NYDzNxyhKZQY0zFA0jSheHCASeEnzV1OP', NULL, '127.0.0.1', 'curl/8.14.1', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiQWJ6ZTRWZnNHaUNhT01GMVpRTlRCTHBUN0RxNjZmS0ZsZ253RXM5eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788369576),
('pAATjpxJrE5sYiWUR2MV7iwGM5c9lSeicjzHX32M', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMm5udEprWmF4QXBkdEJzTlA4aVpLcGRSdzRaMUFJTzRleTVmdFFTcyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3Rlc3RpbW9uaWFscyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vdGVzdGltb25pYWxzIjtzOjU6InJvdXRlIjtzOjI0OiJhZG1pbi50ZXN0aW1vbmlhbHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365637),
('PatkmGAU5D4qJvFDLCyUDrSFTvZcO1TNeKcs2E0u', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRmZyOUFUUVJYRmcwZ0VoZXNPVUUxVXBtczdZSDlQR2pVQ2REczBHeiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3BhZ2VzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9wYWdlcyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ucGFnZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788367089),
('PIYQ7E5CcKjweXrhk3vjKaLB75tfqD6yIUGy7exL', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRnZvcHhoazIwQTI4UnZoRk1rRjJuNGUwb1dLMEFrdzhYUUxheVFVaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zaXRlbWFwLnhtbCI7czo1OiJyb3V0ZSI7czo3OiJzaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368724),
('PJM9dh8KxHxWE4UDZLtH0duxW0ZqjjFxpVBW3gCJ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidEhHbkpxM2hUOFNHVjdYYVpLMm5PUGxXUTJKVE53UFpiV3lmMDJJYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364664),
('puW5ywEHhxFtROIxJkFlFoRG6ibPO0tX4h2Ox4QF', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidHZxdXJsbHcyQVdFb3JwdmNxQURhdzVFMEZ6UWdOWjQ2WEdPU3R4TyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3F1b3RlcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcXVvdGVzIjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5xdW90ZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365250),
('Q0kQr0HjMut8x8WTP5clQyV4OrGQEcqDCeFBI4p6', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRjBBakg0SU4waVk1MjVURFR0QkhWeWVqNkdLRGNiNGVUUnNPaW9HbyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3Byb2ZpbGUiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoyOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL3Byb2ZpbGUiO3M6NToicm91dGUiO3M6NzoicHJvZmlsZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365630),
('ql90FsQUhskscky9KvklJQ8bqMOfMyh8J8tlH2FL', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWthMTNaeGZPeVI0THI1NGsyZ0NxaTRyTnNpVGJaZ0lXeG8zRHV6TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zaXRlbWFwLnhtbCI7czo1OiJyb3V0ZSI7czo3OiJzaXRlbWFwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368840),
('REXMOiSXRSULgFzaJqPEKAeHucCT1eTIyWHHvOHG', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlBGVUtibFRKRzl5OHpuakNnbU0zbkN1blNEc2JYeWtHVmNpTVhQNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYWdlL2Fib3V0LXVzIjtzOjU6InJvdXRlIjtzOjk6InBhZ2Uuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788367140),
('RiaqabtEY9f9GNhO5jrwRuieyBTeRzN7l2LiKn02', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXpqcmRjbDZJTWZrN21oZDQ5WDB0M0tldjZUYnFLZjJzVWhuT01qcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364575),
('rrhBXF2ermbTv14A05pijJhwVRj7tHk53TxrXUBQ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmg1RWVlY0p5R0VBdko5TW9MV3NVeXp3VWtNTHNGYmd5ZGxwdkM2YSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365573),
('SFmjTPMxg5stwReOgZ5JfZ3IuuX9vHBONxnHtg2e', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMzhNVVJKMmF5WFZ4VDVPWnVtNVI1VkRxaHJRcWU3d0pBQzN3VjlWRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364676),
('Sjm1yj4GQpwbZp9TvtV2Qx3bER9cmWgss4b71h9p', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXE1QWVkdk16TUtSdE5nZHFZQTZGckt6MW8xcVV3bjVQQ1doZW43NCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjc6ImNvbnRhY3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364574),
('sLnX9iDP8jXf2YYoHkttHIvXG6tByVYmkLycFzKg', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieHVLUzk5SHo1TE4xSXMyWjlmVERLZGp0NVduZm56c1hZVklMQ1o2dyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364676),
('smu4lq94yn54BSucJaqBhKMVmRz4mB7Esqbdo6D3', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVTVFM20xV2F3azlKbWlLUXVqTTVPaVRqNWJKUkpET1RPWnYyRncwcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hYm91dCI7czo1OiJyb3V0ZSI7czo1OiJhYm91dCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364602),
('sX1GZhxNVJb53ajCB57D1T2Pvg3oH8tS34NglENH', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieVRieVFncGRFaDVyS1Z3aTRtQ3ZxSFJkMkNXdUlWUDdDb04xSWttNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3NlcnZpY2VzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uc2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365633),
('t5TugDmmfJBwtyFEtHVgwznllHji7EqP0v1GxqB0', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDhlOVd2azlWbGxyaEdFOUdxS0loblBIdTVmelVmWjBCV3VaaEtUdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mYXEiO3M6NToicm91dGUiO3M6MzoiZmFxIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364674),
('TkyRaqXx5EFd2cfygtUctaKuoRIGt7EEs95YdanG', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVDNXdjJ3cnN5ZTR3V2w3VXdBS2lyRWY1enk2RjlmQzhjeFJybXZWOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364604),
('tqnDE07Zn87tFYjUJW36oJiJRtCL88U9JTkWcnlY', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWU1MVRXYmxCTU9RbzBOd0doWmczajFjS09tVWFUNFBZMmU3YTdzMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364671),
('TWSIZVb0gmGyT9zxhzJJ8oC8GZtxRZvDeayMRvSw', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1g4QUdFT2pJMlZsdDVialhMWkI2a2VCc1NLM0txbDhod1lCMG5hciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788364527),
('u475i4i4TTudxjYxtYaDkK86dOqFeNOL8yRiHWm4', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWlYcW5IOVYzOFB4REJJVmFpR2xST1VLdFNyOWx1UGRRSzN3MWNTeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364677),
('U597vuljXMUapt90zdQ8lRHOUz6oYdYb52pqJHjz', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlBPVmdHcEVpTzgyV1B0ZDBuUmJCWmZYb1pXVE9sWmM4S1FRWkxBdyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368723),
('ucAEo4F3QGWNY6wZeYzVSTbKZoEhhka4vYrixZvG', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMklzRHhwSEdpMlJHdEwwM3FxWVJWc0VsMWVoajlaOGpGU0pKdWVkciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9zZXJ2aWNlcyI7czo1OiJyb3V0ZSI7czoxNDoic2VydmljZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788368831),
('uiioCn30BhZGV2SZAIYD1n6OEu5iCvtwENizHpze', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUnBWMGFQVlNLcVB3RXFtbUNFU0VZSDBONThKRllYMHk0NVFrcElhdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcmljaW5nIjtzOjU6InJvdXRlIjtzOjc6InByaWNpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788368836),
('useOSggQOZVg8F00LobqdCzkwTeJ19F3ob6522WP', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRmQ5cGlncjFJTUMzOWRDaktTYmpjMFo5a0xEemhnTktPemNJcEYzVSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL21lZGlhIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9tZWRpYSI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4ubWVkaWEuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365252),
('UvNaYNbRjwOLbXyuaq7TmyMid93tw0yfXHU8sSFL', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNGIwc25xbXhKMHoxaU55ZXhDb05yRWlieEdybUxqMjR2akNyV3hyaCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL2JlZm9yZS1hZnRlciI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQwOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vYmVmb3JlLWFmdGVyIjtzOjU6InJvdXRlIjtzOjI0OiJhZG1pbi5iZWZvcmUtYWZ0ZXIuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365248),
('uYH2blFCW5hGQUHFWmMDbnQ11KgfvaVPFMbb6AKD', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTW9pVHY3ZEpXdFFsVU5xQ0FMNlk2RExiV1ZmS0pyaWxLUTg5N3pWdSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3F1b3RlcyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcXVvdGVzIjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5xdW90ZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365635),
('uYT65iG4WaooxFBKsbVFBgqBCtTVVuPZnQF5IFvz', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0dyZ2FlWENHbDBPTjhRNktFbm1qZDdjanoyM2hDcHNVS05TbU5iNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364575),
('VJZAN43IvnSWrmE9ThUMtWmVDEZRD1InpXou5gnQ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1dQeGZmTW1pcjc4d0k2bzVEY3hwQXJSdDZKWmc0MXpYYmJ3NUVFRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788365629),
('VVlWfSoucRkjBLnBbDoyJOM3kkCjdSosCqvW4NSW', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTU0xenBhTUJHTVhKNXdmZ08yQzBqZEp5dGFsanRkVnMyQ3FFQW9aaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365623),
('W8Gp7Tw82P6j22PK1bm6ynIhrppwJiGPV8yCH207', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFY5b04wajlZY2xsZ2RXN0VYV09jb1lGNDF5WGtlYXJEZTBWMVV5MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364521),
('wF4OMsE7NrWkVxkQf0DHtRvQboaGDvZKu3R10IEC', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidWNEdG5XTnNSdEtKTkxQUngwRVdhM3FtR2taem50cjJpN3FaMTF4eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364611),
('wo2fEqKI0lLZHEBAspu8jLpG0KpHK7jEkRBKsxEL', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT0JncDNUeHlMRXd1dVlURm85MDJwa3Fhb0FEcjBrY0tNNWNJSk5vMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoicHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788368832);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('wSrwYNBUW7whoMXTA5QQEs2SgkdNHPnVmXI39CGd', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3lMaUZraWs5VXRhaXRRcUdDa29VY29BSEN3bXUyMGlZVlczNGN6VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9mb3Jnb3QtcGFzc3dvcmQiO3M6NToicm91dGUiO3M6MTY6InBhc3N3b3JkLnJlcXVlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365630),
('wyx3VS876CZwNsIMN18kvoNTZZV8cuQB3zsEr8VQ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWhMN2JBd2NiQjJ3MnRWakF2Q3JZYlRETzhoQTJTY3FESnFJeENzQSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3NldHRpbmdzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4uc2V0dGluZ3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365252),
('WZ7hQY1rkTNUMRDOf1VB47CfKqHIyYCx9yLW3qsH', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibmRQc0xrWjFTYWVXNmFtSXplNjY3aGlOWjhIM3hMcXdPWktNa2tVQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3Byb2R1Y3RzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoyMDoiYWRtaW4ucHJvZHVjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365634),
('xDYahiGxK5IPFfj49DlJMqRvcx5dALy5QgN9DUHZ', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic09MaGtIeDBacWV0RjFMR3ZNZEdjUTdMZ0I0QklBak1CVk9YdWdKYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iZWZvcmUtYWZ0ZXIiO3M6NToicm91dGUiO3M6MTI6ImJlZm9yZS1hZnRlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364604),
('XjR5J8daMRgA3Mlsf1rGvufmPLhPnUJARbtgqOYt', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXpFaXVWSm50d1FHQ0ZDOWM3QzdSUzlpRWRqQUxrV3NhUlVTYW45UyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368830),
('xXSUvBRmERfNnvUhmIUI8OdwZpysNvKgwQ87mrHy', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWUtrQ1F2VFRhRFMyUVRQb0dnT0FwVXR0bjhxVFdidTEzNExoQU5ycSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL3BvcnRmb2xpbyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcG9ydGZvbGlvIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5wb3J0Zm9saW8uaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365247),
('ypojr1fTHCeXKF8JgqtuW9XjqTgCyfyIcj3i8tLW', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUzBQMWdJcFFMSmJSTjk0VTFJemNkak53MXlMejZwSDlJaWVlVGlFeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9nZXQtYS1xdW90ZSI7czo1OiJyb3V0ZSI7czoxMjoicXVvdGUuY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364601),
('yyYSy1WSfLsaGhfUn9Ya1959pBZ3hqEgb17tEmqU', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3BNU1ZKckxDN1hhWnlEUEJCWkxIcTZ2bkpxekF0bXRwTnZ6Y2ZWSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jYXJ0IjtzOjU6InJvdXRlIjtzOjEwOiJjYXJ0LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368837),
('ZdiO7wZUM9OsPyQ13kpIGTEvwkMk6VkuyYvxQnWH', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMEdhdG5TRVJPdDFTU3FqcHJ5TDhZeE5XNlI2T3d2NEhRNU1ibkk5OSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyNzoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788365246),
('ZGW0SPitKzL7PpVP0UbdRPsaub3SrO5lz6aE2ktm', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTk5vellmclBmRE5NczlPQzNKbTBaS2RuSE1CeUI0aUdZQzVlemk2bSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788364605),
('ZLLVV6iXkGPanKc9JDiSLmdx5QKE5uiorudWu8rm', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibEVPTU9OQkRqS05JbkZDZ2pMYXU3R1g3Mmc2NHBQU1JnSWJnMGFJQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788368839),
('zLurOb7uzBLlisEpjxk3RzJS7ibQJHUtnO13NOkX', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSXhTTW1TY2JleWdQRUoyNnVFQlFOalowVnJTaXJlNkFuUUFFTGlGMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9ibG9nIjtzOjU6InJvdXRlIjtzOjEwOiJibG9nLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1788364590),
('ZpC1caBrJKqFz08ZDYHUvkE0YVY6hJDcMjDbioCe', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSW1wQ1I3aVRFcHpXZGw0VklLMEpOSVU2M0tnTEJONEZXTGpodERaYyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL29yZGVycyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vb3JkZXJzIjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5vcmRlcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365250),
('ztoovWoPjtrUeoysdyhoME9RaAnga3IwpE32tOjY', NULL, '127.0.0.1', 'curl/8.14.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmpmaHdZWWlBa0lvcmNiZUpJTnVPSDk1TW5IQ0haNW5uR0hnY1pYMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iZWZvcmUtYWZ0ZXIiO3M6NToicm91dGUiO3M6MTI6ImJlZm9yZS1hZnRlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788366127),
('zv3qnxbEXM8w0EE6JcnnIQeqXeApt8F9lpUW7tpH', NULL, '127.0.0.1', 'curl/8.14.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZllJaER0ZWxMSXB0NmgwTGRtdjNtdlB4S3NHUzFhYm5xSUNKMUVsTCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2FkbWluL29yZGVycyI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vb3JkZXJzIjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5vcmRlcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788365636);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `group`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
(1, 'general', 'site_name', 'PhotoLabe', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(2, 'general', 'site_email', 'hello@photolabe.com', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(3, 'general', 'support_email', 'support@photolabe.com', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(4, 'general', 'phone', '+1 (555) 123-4567', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(5, 'general', 'address', '123 Creative Street, Design City, DC 10001', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(6, 'general', 'currency', 'USD', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(7, 'general', 'timezone', 'UTC', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(8, 'seo', 'default_meta_title', 'PhotoLabe - Professional Photo Editing & Creative Design Services', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19'),
(9, 'seo', 'default_meta_description', 'Transform your images into professional, market-ready visuals with expert photo editing and creative design services.', 'text', '2026-09-02 09:53:19', '2026-09-02 09:53:19');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket_messages`
--

CREATE TABLE `support_ticket_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `is_staff` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `title`, `company`, `avatar`, `content`, `rating`, `is_featured`, `is_active`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Sarah Johnson', 'Photographer', 'Sarah J Photography', NULL, 'Outstanding quality and incredibly fast turnaround. They have been handling all my retouching needs for over a year now.', 5, 1, 1, 1, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(2, 'Michael Chen', 'E-commerce Manager', 'FashionCo', NULL, 'The background removal service is flawless. Our product images look professional and consistent across our entire catalog.', 5, 1, 1, 2, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(3, 'Emily Rodriguez', 'Creative Director', 'Brand Studio', NULL, 'Their attention to detail is remarkable. Every project is delivered on time with exceptional quality.', 5, 1, 1, 3, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(4, 'David Kim', 'Wedding Photographer', 'DK Photography', NULL, 'They handle all my wedding photo editing. The consistency and quality across hundreds of images is impressive.', 5, 1, 1, 4, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL),
(5, 'Amanda Foster', 'Real Estate Agent', 'Prime Realty', NULL, 'My listing photos have never looked better. Their real estate photo enhancement service is worth every penny.', 5, 1, 1, 5, '2026-09-02 09:53:19', '2026-09-02 09:53:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `gateway_transaction_id` varchar(255) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `avatar`, `role`, `status`, `address`, `city`, `country`, `timezone`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super Admin', 'admin@photolabe.com', '2026-09-02 09:53:17', '$2y$12$3y9u4ZK5E0yGKmC1yQyTWu4t50HAZjXtQMzJ/W1Pzc8.YiSM1Sl5m', NULL, NULL, 'super_admin', 'active', NULL, NULL, NULL, NULL, NULL, '2026-09-02 09:53:17', '2026-09-02 09:53:17', NULL),
(2, 'Editor User', 'editor@photolabe.com', '2026-09-02 09:53:17', '$2y$12$dbqIKgJDyw5hywkC2qH7z.oqmlbazPvyf6Kl3.hYJ9STRLYXUzst6', NULL, NULL, 'editor', 'active', NULL, NULL, NULL, NULL, NULL, '2026-09-02 09:53:17', '2026-09-02 09:53:17', NULL),
(3, 'Designer User', 'designer@photolabe.com', '2026-09-02 09:53:18', '$2y$12$PfjrX3aks.odfGW5IeUgFeajVGZwTKrEU2IxoSkZLlzNqJ1OMeBSa', NULL, NULL, 'designer', 'active', NULL, NULL, NULL, NULL, NULL, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(4, 'John Customer', 'john@example.com', '2026-09-02 09:53:18', '$2y$12$KvDK19xzvpXnuxWZDd1Xu.Vs.OFlwksedhm06OPJU0LxNJXvcbwyq', '+1-555-0101', NULL, 'customer', 'active', NULL, NULL, NULL, NULL, NULL, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL),
(5, 'Jane Smith', 'jane@example.com', '2026-09-02 09:53:18', '$2y$12$VHTeDOrcdE/SsWRPiY.biudUiEWgiv1cQeklAL4KKHiA9CoRbUhtG', '+1-555-0102', NULL, 'customer', 'active', NULL, NULL, NULL, NULL, NULL, '2026-09-02 09:53:18', '2026-09-02 09:53:18', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Indexes for table `before_after_categories`
--
ALTER TABLE `before_after_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `before_after_categories_slug_unique` (`slug`);

--
-- Indexes for table `before_after_projects`
--
ALTER TABLE `before_after_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `before_after_projects_category_id_foreign` (`category_id`),
  ADD KEY `before_after_projects_service_id_foreign` (`service_id`);

--
-- Indexes for table `blog_categories`
--
ALTER TABLE `blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_categories_slug_unique` (`slug`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  ADD KEY `blog_posts_category_id_foreign` (`category_id`),
  ADD KEY `blog_posts_author_id_foreign` (`author_id`);

--
-- Indexes for table `blog_post_tag`
--
ALTER TABLE `blog_post_tag`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `blog_post_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `blog_tags`
--
ALTER TABLE `blog_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_tags_slug_unique` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_usages_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_usages_user_id_foreign` (`user_id`),
  ADD KEY `coupon_usages_order_id_foreign` (`order_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_user_id_foreign` (`user_id`),
  ADD KEY `invoices_order_id_foreign` (`order_id`),
  ADD KEY `invoices_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_folder_id_foreign` (`folder_id`),
  ADD KEY `media_user_id_foreign` (`user_id`);

--
-- Indexes for table `media_folders`
--
ALTER TABLE `media_folders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_folders_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_quote_id_foreign` (`quote_id`),
  ADD KEY `orders_service_id_foreign` (`service_id`);

--
-- Indexes for table `order_files`
--
ALTER TABLE `order_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_files_order_id_foreign` (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`);

--
-- Indexes for table `order_messages`
--
ALTER TABLE `order_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_messages_order_id_foreign` (`order_id`),
  ADD KEY `order_messages_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_revisions`
--
ALTER TABLE `order_revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_revisions_order_id_foreign` (`order_id`),
  ADD KEY `order_revisions_user_id_foreign` (`user_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `portfolio_categories`
--
ALTER TABLE `portfolio_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolio_categories_slug_unique` (`slug`);

--
-- Indexes for table `portfolio_images`
--
ALTER TABLE `portfolio_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolio_images_project_id_foreign` (`project_id`);

--
-- Indexes for table `portfolio_projects`
--
ALTER TABLE `portfolio_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolio_projects_slug_unique` (`slug`),
  ADD KEY `portfolio_projects_category_id_foreign` (`category_id`);

--
-- Indexes for table `portfolio_project_tag`
--
ALTER TABLE `portfolio_project_tag`
  ADD PRIMARY KEY (`project_id`,`tag_id`),
  ADD KEY `portfolio_project_tag_tag_id_foreign` (`tag_id`);

--
-- Indexes for table `portfolio_tags`
--
ALTER TABLE `portfolio_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolio_tags_slug_unique` (`slug`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_categories_slug_unique` (`slug`);

--
-- Indexes for table `product_files`
--
ALTER TABLE `product_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_files_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchases_purchase_number_unique` (`purchase_number`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `purchases_product_id_foreign` (`product_id`),
  ADD KEY `purchases_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotes_user_id_foreign` (`user_id`),
  ADD KEY `quotes_service_id_foreign` (`service_id`);

--
-- Indexes for table `quote_files`
--
ALTER TABLE `quote_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_files_quote_id_foreign` (`quote_id`);

--
-- Indexes for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_items_quote_id_foreign` (`quote_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`),
  ADD KEY `services_category_id_foreign` (`category_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_categories_slug_unique` (`slug`);

--
-- Indexes for table `service_features`
--
ALTER TABLE `service_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_features_service_id_foreign` (`service_id`);

--
-- Indexes for table `service_pricing`
--
ALTER TABLE `service_pricing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_pricing_service_id_foreign` (`service_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `support_tickets_ticket_number_unique` (`ticket_number`),
  ADD KEY `support_tickets_user_id_foreign` (`user_id`),
  ADD KEY `support_tickets_order_id_foreign` (`order_id`);

--
-- Indexes for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_ticket_messages_ticket_id_foreign` (`ticket_id`),
  ADD KEY `support_ticket_messages_user_id_foreign` (`user_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `before_after_categories`
--
ALTER TABLE `before_after_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `before_after_projects`
--
ALTER TABLE `before_after_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blog_categories`
--
ALTER TABLE `blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blog_tags`
--
ALTER TABLE `blog_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_folders`
--
ALTER TABLE `media_folders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_files`
--
ALTER TABLE `order_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_messages`
--
ALTER TABLE `order_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_revisions`
--
ALTER TABLE `order_revisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolio_categories`
--
ALTER TABLE `portfolio_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `portfolio_images`
--
ALTER TABLE `portfolio_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolio_projects`
--
ALTER TABLE `portfolio_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `portfolio_tags`
--
ALTER TABLE `portfolio_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_files`
--
ALTER TABLE `product_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quote_files`
--
ALTER TABLE `quote_files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quote_items`
--
ALTER TABLE `quote_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_features`
--
ALTER TABLE `service_features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `service_pricing`
--
ALTER TABLE `service_pricing`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `before_after_projects`
--
ALTER TABLE `before_after_projects`
  ADD CONSTRAINT `before_after_projects_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `before_after_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `before_after_projects_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blog_posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `blog_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blog_post_tag`
--
ALTER TABLE `blog_post_tag`
  ADD CONSTRAINT `blog_post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `blog_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_folder_id_foreign` FOREIGN KEY (`folder_id`) REFERENCES `media_folders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `media_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `media_folders`
--
ALTER TABLE `media_folders`
  ADD CONSTRAINT `media_folders_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `media_folders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_files`
--
ALTER TABLE `order_files`
  ADD CONSTRAINT `order_files_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_messages`
--
ALTER TABLE `order_messages`
  ADD CONSTRAINT `order_messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_revisions`
--
ALTER TABLE `order_revisions`
  ADD CONSTRAINT `order_revisions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_revisions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolio_images`
--
ALTER TABLE `portfolio_images`
  ADD CONSTRAINT `portfolio_images_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `portfolio_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolio_projects`
--
ALTER TABLE `portfolio_projects`
  ADD CONSTRAINT `portfolio_projects_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `portfolio_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolio_project_tag`
--
ALTER TABLE `portfolio_project_tag`
  ADD CONSTRAINT `portfolio_project_tag_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `portfolio_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `portfolio_project_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `portfolio_tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_files`
--
ALTER TABLE `product_files`
  ADD CONSTRAINT `product_files_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchases_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotes`
--
ALTER TABLE `quotes`
  ADD CONSTRAINT `quotes_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quotes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quote_files`
--
ALTER TABLE `quote_files`
  ADD CONSTRAINT `quote_files_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quote_items`
--
ALTER TABLE `quote_items`
  ADD CONSTRAINT `quote_items_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_features`
--
ALTER TABLE `service_features`
  ADD CONSTRAINT `service_features_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_pricing`
--
ALTER TABLE `service_pricing`
  ADD CONSTRAINT `service_pricing_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_ticket_messages`
--
ALTER TABLE `support_ticket_messages`
  ADD CONSTRAINT `support_ticket_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_ticket_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
