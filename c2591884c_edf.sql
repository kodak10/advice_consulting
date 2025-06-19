-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 19 juin 2025 à 12:38
-- Version du serveur : 10.11.13-MariaDB-cll-lve
-- Version de PHP : 8.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `c2591884c_edf`
--

-- --------------------------------------------------------

--
-- Structure de la table `banques`
--

CREATE TABLE `banques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `num_compte` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banques`
--

INSERT INTO `banques` (`id`, `name`, `num_compte`, `created_at`, `updated_at`) VALUES
(1, 'UBA COTE D\'IVOIRE', 'CI150  01001  1010  90003846  40', '2025-06-19 08:07:01', '2025-06-19 08:07:01'),
(2, 'VERSUS BANK', 'CI112  01001  012206440008  24', '2025-06-19 08:08:26', '2025-06-19 08:08:26'),
(3, 'GUARANTY TRUST CI BANK', 'CI163  01202  000000022365  89', '2025-06-19 08:11:17', '2025-06-19 08:11:17');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('902ba3cda1883801594b6e1b452790cc53948fda', 'i:1;', 1750328819),
('902ba3cda1883801594b6e1b452790cc53948fda:timer', 'i:1750328819;', 1750328819),
('assistant.comptable@adviceconsulting.net|160.155.18.77', 'i:1;', 1750327655),
('assistant.comptable@adviceconsulting.net|160.155.18.77:timer', 'i:1750327655;', 1750327655),
('da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1750327559),
('da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1750327559;', 1750327559);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numero_cc` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `attn` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `nom`, `numero_cc`, `telephone`, `adresse`, `ville`, `attn`, `created_by`, `email`, `created_at`, `updated_at`) VALUES
(1, 'UBA COTE D\'IVOIRE', '0708843 Z', '2720312222', 'Imm. Kharrat,Angle Bd Botreau Roussel-Av. Nogues Abidjan', 'Abidjan', NULL, 2, NULL, '2025-06-19 08:22:08', '2025-06-19 08:22:08');

-- --------------------------------------------------------

--
-- Structure de la table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `designations`
--

INSERT INTO `designations` (`id`, `reference`, `description`, `prix_unitaire`, `created_at`, `updated_at`) VALUES
(1, '1750053503', 'Cassette wincor', 179744.00, '2025-06-19 08:28:20', '2025-06-19 08:28:20'),
(2, '1750056651', 'Cassette rejet wincor', 119800.00, '2025-06-19 08:33:41', '2025-06-19 08:33:41');

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE `devis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date_emission` date NOT NULL,
  `date_echeance` date NOT NULL,
  `commande` varchar(255) DEFAULT NULL,
  `livraison` varchar(255) DEFAULT NULL,
  `validite` varchar(255) DEFAULT NULL,
  `delai` varchar(255) DEFAULT NULL,
  `banque_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total_ht` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tva` decimal(10,2) NOT NULL DEFAULT 0.18,
  `total_ttc` decimal(10,2) NOT NULL DEFAULT 0.00,
  `acompte` decimal(10,2) NOT NULL DEFAULT 0.00,
  `solde` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pdf_path` varchar(255) DEFAULT NULL,
  `num_proforma` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `pays_id` bigint(20) UNSIGNED DEFAULT NULL,
  `devise` varchar(255) NOT NULL DEFAULT 'XOF',
  `message` text DEFAULT NULL,
  `texte` text DEFAULT NULL,
  `taux` decimal(10,4) NOT NULL DEFAULT 1.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `devis`
--

INSERT INTO `devis` (`id`, `client_id`, `user_id`, `date_emission`, `date_echeance`, `commande`, `livraison`, `validite`, `delai`, `banque_id`, `total_ht`, `tva`, `total_ttc`, `acompte`, `solde`, `pdf_path`, `num_proforma`, `status`, `pays_id`, `devise`, `message`, `texte`, `taux`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-06-19', '2025-06-27', '100', '0', '7', '3', 1, 480000.00, 18.00, 566400.00, 566400.00, 0.00, 'pdf/devis/devis-1.pdf', 'ADC 202506001', 'En Attente de validation', 1, 'XOF', NULL, 'Merci de nous consulter, veuillez trouver notre meilleure offre', 1.0000, '2025-06-19 08:36:43', '2025-06-19 08:36:43');

-- --------------------------------------------------------

--
-- Structure de la table `devises`
--

CREATE TABLE `devises` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `taux_conversion` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `devises`
--

INSERT INTO `devises` (`id`, `code`, `taux_conversion`, `created_at`, `updated_at`) VALUES
(1, 'CFA', 1.00, '2025-06-19 07:55:14', '2025-06-19 07:55:14'),
(2, 'EUR', 655.96, '2025-06-19 07:55:14', '2025-06-19 07:55:14'),
(3, 'USD', 600.00, '2025-06-19 07:55:14', '2025-06-19 07:55:14'),
(4, 'GBP', 850.00, '2025-06-19 07:55:14', '2025-06-19 07:55:14'),
(5, 'XOF', 1.00, '2025-06-19 07:55:14', '2025-06-19 07:55:14'),
(6, 'XPF', 5.43, '2025-06-19 07:55:14', '2025-06-19 07:55:14');

-- --------------------------------------------------------

--
-- Structure de la table `devis_designation`
--

CREATE TABLE `devis_designation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `devis_id` bigint(20) UNSIGNED NOT NULL,
  `designation_id` bigint(20) UNSIGNED NOT NULL,
  `taux` decimal(10,4) NOT NULL DEFAULT 1.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `devis_details`
--

CREATE TABLE `devis_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `devis_id` bigint(20) UNSIGNED NOT NULL,
  `designation_id` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `remise` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `devis_details`
--

INSERT INTO `devis_details` (`id`, `devis_id`, `designation_id`, `quantite`, `prix_unitaire`, `remise`, `total`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 180000.00, 0.00, 360000.00, '2025-06-19 08:36:43', '2025-06-19 08:36:43'),
(2, 1, 2, 1, 120000.00, 0.00, 120000.00, '2025-06-19 08:36:43', '2025-06-19 08:36:43');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type_facture` enum('Partielle','Totale') NOT NULL DEFAULT 'Totale',
  `montant` decimal(15,2) NOT NULL DEFAULT 0.00,
  `devis_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `num_bc` varchar(255) DEFAULT NULL,
  `num_rap` varchar(255) DEFAULT NULL,
  `num_bl` varchar(255) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `remise_speciale` decimal(10,2) NOT NULL,
  `montant_solde` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pays_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Non renseigné',
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `selected_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`selected_items`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
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
-- Structure de la table `jobs`
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
-- Structure de la table `job_batches`
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
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0000_02_20_084433_create_countries_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2025_02_12_103056_create_clients_table', 1),
(6, '2025_02_12_171836_create_banques_table', 1),
(7, '2025_02_12_175552_create_designations_table', 1),
(8, '2025_02_12_184956_create_devis_table', 1),
(9, '2025_02_15_111042_create_permission_tables', 1),
(10, '2025_02_16_144932_create_devis_designation_table', 1),
(11, '2025_02_17_114200_create_factures_table', 1),
(12, '2025_02_17_124529_create_devis_details_table', 1),
(13, '2025_02_21_095553_create_notifications_table', 1),
(14, '2025_02_25_115534_create_devises_table', 1),
(15, '2025_03_11_154759_add_title_to_notifications_table', 1),
(16, '2025_05_02_102530_add_montant_solde_to_factures_table', 1),
(17, '2025_05_19_134005_add_type_facture_and_montant_to_factures_table', 1),
(18, '2025_05_26_154152_add_selected_items_to_factures_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(4, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 11),
(5, 'App\\Models\\User', 12);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pays`
--

CREATE TABLE `pays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `indicatif` varchar(3) NOT NULL,
  `devise` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pays`
--

INSERT INTO `pays` (`id`, `name`, `indicatif`, `devise`, `created_at`, `updated_at`) VALUES
(1, 'Côte d\'Ivoire', '225', 'F CFA', '2025-06-19 07:55:13', '2025-06-19 07:55:13'),
(2, 'Guinée', '224', 'GNF', '2025-06-19 07:55:13', '2025-06-19 07:55:13'),
(3, 'Tchad', '235', 'F CFA', '2025-06-19 07:55:13', '2025-06-19 07:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur', 'web', '2025-06-19 07:55:13', '2025-06-19 07:55:13'),
(2, 'DG', 'web', '2025-06-19 07:55:13', '2025-06-19 07:55:13'),
(3, 'Daf', 'web', '2025-06-19 07:55:13', '2025-06-19 07:55:13'),
(4, 'Commercial', 'web', '2025-06-19 07:55:13', '2025-06-19 07:55:13'),
(5, 'Comptable', 'web', '2025-06-19 07:55:13', '2025-06-19 07:55:13');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
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
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('CKBh7hDzuCAmmcVBGBQGaFGrULS53RWDoWWdAUEb', 2, '160.155.18.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQUFvWFdMVGlUNTZHM0Nad2YzTGppeU1HYXo5cXZpd285MDNMS1ZPZCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwczovL2VkZi5ncm91cGFkdmljZS5uZXQvZGFzaGJvYXJkL2RldmlzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NTAzMjc0OTk7fX0=', 1750329405),
('HhgP5UogLs8lnAz7SS0b4Ds0IuptsIY7K0CmCTvq', 1, '160.155.18.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZ0NlTGtHU2RMTmFPUlBSUXcxTTNqQUNNVmJ5bEVTb2thT296RkprSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vZWRmLmdyb3VwYWR2aWNlLm5ldC9kYXNoYm9hcmQvdXNlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc1MDMyNjk4Nzt9fQ==', 1750327537),
('qmN1Kk94HRY1DGdrHzibgO47czYs33f7Vokbp4wN', 7, '160.155.18.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSnlwcjJwUXJBWndoWFpCY25FOVJuWTV4NDZLZFdvYWszbThOS0xWdiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ0OiJodHRwczovL2VkZi5ncm91cGFkdmljZS5uZXQvZGFzaGJvYXJkL3Byb2ZpbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzUwMzI4MzU2O319', 1750328718),
('ZgHdY25XGB7YdNW5q3OEy7XJzuNUO6rrqf5mufOf', 7, '160.155.18.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoic0lNQTI3czNGM3RjZDFRV3o5NVJKcUJyQUsyZ08yam55RFEzemo2RSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwczovL2VkZi5ncm91cGFkdmljZS5uZXQvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NztzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NTAzMjg3NTk7fX0=', 1750328760),
('ZTjAKhqr2SdgrOJqZvin4UFrpyKOnNjYmiyTsYA5', NULL, '160.155.18.77', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYXVTRzByMGhPRkwydDZuU3FjbWYxWktYck9lUTIzTzJKZk9qQklzZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNzg6Imh0dHBzOi8vZWRmLmdyb3VwYWR2aWNlLm5ldC9lbWFpbC92ZXJpZnkvMTIvYWJhMGJlODQxMmI3YWYxOGQ2NzNhYTFmMWNiMTUxYWMwMTA5OTQ3MD9leHBpcmVzPTE3NTAzMzExMDYmc2lnbmF0dXJlPWY0MDhiOWY2ZjgzNGRjYzZiNjliMGY4ZWRmNGRlNmI2MmI2ZjdkZWM5MGJiZTk1MGQ5MzBlMzlkMGMwOWZiOTQiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMzoiaHR0cHM6Ly9lZGYuZ3JvdXBhZHZpY2UubmV0L2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1750327595);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Actif',
  `image` varchar(255) NOT NULL DEFAULT 'storage/images/user.jpg',
  `pays_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `adresse`, `status`, `image`, `pays_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@groupadvice.net', '2025-06-19 07:55:13', '$2y$12$FkxiMXuKhzBLtXInbR8EUeAacWJwwNK2BLUzJ74a6bGdWvzDETbB.', '+22501010101', 'Abidjan, Côte d\'Ivoire', 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 07:55:14', '2025-06-19 07:55:14'),
(2, 'AHIBA N’TAPKE VINCENT DE PAUL', 'Paul.ahiba@adviceconsulting.net', '2025-06-19 08:04:59', '$2y$12$wRzR83pajug8T7c9a6wzC.Y76Z5VXVu3Rb95dfD7xDoFJHQ/qdcAO', '0101215081', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 07:57:52', '2025-06-19 08:04:59'),
(3, 'DJADOU BORIS JUNIOR', 'Boris.djadou@adviceconsulting.net', NULL, '$2y$12$/cf8Ea39//5hU9pi3b8HKOEWEnrQnNdJGF7nXiskP5/9dXdrClNcm', '0708288762', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 07:58:45', '2025-06-19 07:58:45'),
(4, 'ADJAFI LANDRY VENANCE KOUADIO', 'Landry.adjafi@adviceconsulting.net', NULL, '$2y$12$WqAtepeiUrGTxQE8onbmse4p.sn4RLz9WqtbYKzOp9p5ZvqBfR2Aq', '0749004719', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 07:59:31', '2025-06-19 07:59:31'),
(5, 'KOUAKOU AFFOUE JULIETTE', 'Juliette.kouakou@adviceconsulting.net', NULL, '$2y$12$T2SvFe1KPG/Z6K4wHCvv0Of8rH07XgOaTM5YWz2om6Aptd1v18OAK', '0747902640', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:00:25', '2025-06-19 08:00:25'),
(6, 'N’GBESSO AFFOUKA CELESTIN', 'Celestin.affouka@adviceconsulting.net', NULL, '$2y$12$iHvvpS0lt4xwsRv1607GHumGjYqmMlKnR.A4MmVekEAPh5Xkh9zRq', '0554715414', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:00:57', '2025-06-19 08:00:57'),
(7, 'BLE MOMINE GERARD', 'gerard.ble@adviceconsulting.net', '2025-06-19 08:19:17', '$2y$12$/Rn.w4TBreyvnutsBVHkd.Z5T48pzW7QUaBH/kh/RA00m.1859NFi', '0749090797', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:01:21', '2025-06-19 08:25:16'),
(8, 'N’GUESSAN ELISANNE SANDRA', 'Elisanne.nguessan@adviceconsulting.net', NULL, '$2y$12$QrNK56h9diijdH.hmG6NbOAok/oP/QVne/VaW0jcWhlYldC9bVx/e', '0708571626', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:01:45', '2025-06-19 08:01:45'),
(9, 'VANGAH LANDRY', 'Landry.vangah@adviceconsulting.net', NULL, '$2y$12$i6oKy2OKtbCY/Wb4nP4gfO0gafVEidICubWQ6w3ZnwAwAjohZ72G2', '0000000', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:02:11', '2025-06-19 08:02:11'),
(10, 'ESSO ANGE', 'Ange.esso@adviceconsulting.net', NULL, '$2y$12$iewLFSUDjaUT3YAxkuA0wONYiQj6YlB5cEiZ5/GhWYyPfttoxvQ/y', '0757489333', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:02:53', '2025-06-19 08:02:53'),
(11, 'ESSO DARLENE', 'Darlene.esso@adviceconsulting.net', NULL, '$2y$12$hzJUVBn0bsY7Xp6Bv/V6Be2uEA0A0LvaAJ7KpyK4Pfrlv.CX/lY1W', '0141976084', NULL, 'Actif', 'storage/images/user.jpg', 3, NULL, '2025-06-19 08:03:51', '2025-06-19 08:03:51'),
(12, 'N’GUESSAN JEAN NOEL', 'Assistant.comptable@adviceconsulting.net', NULL, '$2y$12$UJqfwIu.Bo9HntJ90PdHqeApc4XO2z2JyTJ4Cy6eQbpeB107HmOrm', '0556800789', NULL, 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:05:05', '2025-06-19 08:05:05'),
(13, 'KOUASSI ATCHIN PARFAIT', 'k.parfait@groupadvice.net', NULL, '$2y$12$Aiah9I10GYTdsS6ZlALdNegdhK7e.eL1Ojvc6TuGDinywZVUqiKC.', '0103810998', 'Abidjan, cocody Angré', 'Actif', 'storage/images/user.jpg', 1, NULL, '2025-06-19 08:05:34', '2025-06-19 08:05:34');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `banques`
--
ALTER TABLE `banques`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_numero_cc_unique` (`numero_cc`),
  ADD UNIQUE KEY `clients_email_unique` (`email`),
  ADD KEY `clients_created_by_foreign` (`created_by`);

--
-- Index pour la table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `designations_reference_unique` (`reference`);

--
-- Index pour la table `devis`
--
ALTER TABLE `devis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devis_client_id_foreign` (`client_id`),
  ADD KEY `devis_user_id_foreign` (`user_id`),
  ADD KEY `devis_banque_id_foreign` (`banque_id`),
  ADD KEY `devis_pays_id_foreign` (`pays_id`);

--
-- Index pour la table `devises`
--
ALTER TABLE `devises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devises_code_unique` (`code`);

--
-- Index pour la table `devis_designation`
--
ALTER TABLE `devis_designation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devis_designation_devis_id_foreign` (`devis_id`),
  ADD KEY `devis_designation_designation_id_foreign` (`designation_id`);

--
-- Index pour la table `devis_details`
--
ALTER TABLE `devis_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `devis_details_devis_id_foreign` (`devis_id`),
  ADD KEY `devis_details_designation_id_foreign` (`designation_id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factures_devis_id_foreign` (`devis_id`),
  ADD KEY `factures_user_id_foreign` (`user_id`),
  ADD KEY `factures_pays_id_foreign` (`pays_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `pays`
--
ALTER TABLE `pays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pays_name_unique` (`name`),
  ADD UNIQUE KEY `pays_indicatif_unique` (`indicatif`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_pays_id_foreign` (`pays_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `banques`
--
ALTER TABLE `banques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `devises`
--
ALTER TABLE `devises`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `devis_designation`
--
ALTER TABLE `devis_designation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `devis_details`
--
ALTER TABLE `devis_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `pays`
--
ALTER TABLE `pays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `devis_banque_id_foreign` FOREIGN KEY (`banque_id`) REFERENCES `banques` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `devis_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `devis_pays_id_foreign` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `devis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `devis_designation`
--
ALTER TABLE `devis_designation`
  ADD CONSTRAINT `devis_designation_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `devis_designation_devis_id_foreign` FOREIGN KEY (`devis_id`) REFERENCES `devis` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `devis_details`
--
ALTER TABLE `devis_details`
  ADD CONSTRAINT `devis_details_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `devis_details_devis_id_foreign` FOREIGN KEY (`devis_id`) REFERENCES `devis` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_devis_id_foreign` FOREIGN KEY (`devis_id`) REFERENCES `devis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `factures_pays_id_foreign` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `factures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_pays_id_foreign` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
