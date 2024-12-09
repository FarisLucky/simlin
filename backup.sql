/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `daftar` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `kd_unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `kode` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml` int NOT NULL,
  `jenis` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pengajuan` datetime DEFAULT NULL,
  `terima` datetime DEFAULT NULL,
  `kembalikan` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `ket` varchar(240) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_by` smallint unsigned NOT NULL,
  `created_by_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved_by` smallint unsigned DEFAULT NULL,
  `approved_by_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` smallint unsigned DEFAULT NULL,
  `updated_by_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `daftar` (`id`, `kd_unit`, `kode`, `jml`, `jenis`, `pengajuan`, `terima`, `kembalikan`, `selesai`, `status`, `ket`, `created_by`, `created_by_name`, `approved_by`, `approved_by_name`, `updated_by`, `updated_by_name`, `created_at`, `updated_at`) VALUES
	(24, 'ANK', '202410-1', 0, 'LINEN', '2024-10-23 21:35:13', '2024-10-28 01:56:41', '2024-10-28 03:18:37', NULL, 4, '', 1, 'salman', NULL, NULL, 124, 'sufyan', '2024-10-20 22:19:21', '2024-10-27 21:29:09'),
	(29, 'ANK', '202410-2', 0, 'LINEN', NULL, NULL, NULL, NULL, 0, '', 1, 'salman', NULL, NULL, 1, NULL, '2024-10-22 18:42:47', '2024-10-22 18:42:47'),
	(41, 'CSSD', '202410-3', 0, 'LINEN', NULL, NULL, NULL, NULL, 0, '', 1, 'salman', NULL, NULL, 1, NULL, '2024-10-23 13:04:14', '2024-10-23 13:04:14'),
	(42, 'ANK', '202410-4', 0, 'LINEN', '2024-10-29 04:55:01', '2024-10-29 04:55:28', '2024-10-29 05:02:04', NULL, 3, '', 124, 'sufyan', NULL, NULL, 1, 'salman', '2024-10-28 21:40:32', '2024-10-28 22:02:04');

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `linen` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nota` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_daftar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml` int NOT NULL,
  `berat` double(8,2) NOT NULL,
  `selesai` datetime DEFAULT NULL,
  `kembali` datetime DEFAULT NULL,
  `status` tinyint NOT NULL,
  `kd_unit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_by` smallint unsigned NOT NULL,
  `updated_by` smallint unsigned DEFAULT NULL,
  `updated_by_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `linen` (`id`, `nota`, `kode_daftar`, `nama`, `jml`, `berat`, `selesai`, `kembali`, `status`, `kd_unit`, `created_by`, `updated_by`, `updated_by_name`, `created_at`, `updated_at`) VALUES
	('2348832c-890a-4a4a-a999-aae041b93240', 'LN202410-1', '202410-1', 'LINEN', 0, 0.00, NULL, NULL, 0, 'ANK', 1, 1, 'id', '2024-10-20 22:19:21', '2024-10-20 22:19:21'),
	('612583ba-e695-450a-ad9a-2d589dc0e70a', 'LN202410-4', '202410-4', 'LINEN', 0, 0.00, NULL, NULL, 0, 'ANK', 124, 124, 'sufyan', '2024-10-28 21:40:32', '2024-10-28 21:40:32'),
	('d6657c66-ce4b-49fe-a69e-e18ab8bcf4d0', 'LN202410-2', '202410-2', 'LINEN', 0, 0.00, NULL, NULL, 0, 'ANK', 1, 1, 'id', '2024-10-22 18:42:47', '2024-10-22 18:42:47'),
	('daeec49e-5720-47e5-b302-717687964396', 'LN202410-3', '202410-3', 'LINEN', 0, 0.00, NULL, NULL, 0, 'CSSD', 1, 1, 'id', '2024-10-23 13:04:14', '2024-10-23 13:04:14');

CREATE TABLE IF NOT EXISTS `linen_detail` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '''',
  `nota_linen` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_daftar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_linen` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_linen_unit` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml` int NOT NULL COMMENT 'jumlah diajukan unit',
  `jml_terima` int DEFAULT NULL COMMENT 'jumlah diterima cssd',
  `jml_kembali` int DEFAULT NULL COMMENT 'jumlah dikembalikan ke unit',
  `jml_akhir` int DEFAULT NULL COMMENT 'jumlah dikonfirmasi unit',
  `berat` double(8,2) NOT NULL,
  `kembali` datetime DEFAULT NULL,
  `selesai` datetime DEFAULT NULL,
  `status` tinyint NOT NULL,
  `created_by` smallint unsigned NOT NULL,
  `updated_by` smallint unsigned NOT NULL,
  `updated_by_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `linen_detail` (`id`, `nota_linen`, `kode_daftar`, `kode_linen`, `kode_linen_unit`, `nama`, `jml`, `jml_terima`, `jml_kembali`, `jml_akhir`, `berat`, `kembali`, `selesai`, `status`, `created_by`, `updated_by`, `updated_by_name`, `created_at`, `updated_at`) VALUES
	('520816c4-80cd-4e1d-9b91-b78b452a2c16', 'LN202410-4', '202410-4', 'L-3', 'ANK-6', 'SELIMUT', 1, NULL, NULL, NULL, 0.00, NULL, NULL, 0, 124, 124, 'sufyan', '2024-10-28 21:53:02', '2024-10-28 21:53:02'),
	('61657fe4-87b1-48e2-b996-0204900c5ade', 'LN202410-1', '202410-1', 'L-5', 'ANK-4', 'HANDUK MANDI', 2, 2, 2, 2, 32.00, NULL, NULL, 0, 124, 124, 'id', '2024-10-23 13:37:49', '2024-10-27 21:24:49'),
	('693ff415-d5c6-46ea-b1a4-d2c471b5b09e', 'LN202410-1', '202410-1', 'L-4', 'ANK-7', 'PERLAK', 3, 3, 3, 3, 0.00, NULL, NULL, 0, 1, 1, 'id', '2024-10-21 00:16:51', '2024-10-27 21:24:52'),
	('6daaf573-66ac-4802-8954-2ccf65c16580', 'LN202410-1', '202410-1', 'L-3', 'ANK-6', 'SELIMUT', 2, 2, 2, 2, 0.00, NULL, NULL, 0, 1, 1, 'id', '2024-10-21 00:15:25', '2024-10-27 21:24:54'),
	('823dea9f-4653-4ad9-9517-3ea36ebbc197', 'LN202410-4', '202410-4', 'L-1', 'ANK-5', 'SPREI', 4, NULL, NULL, NULL, 0.00, NULL, NULL, 0, 124, 124, 'sufyan', '2024-10-28 21:53:30', '2024-10-28 21:53:30'),
	('b8c03179-7696-4cf0-ad70-a634c6bebdf2', 'LN202410-2', '202410-2', 'L-5', 'ANK-4', 'HANDUK MANDI', 3, 0, NULL, 0, 32.00, NULL, NULL, 0, 124, 124, 'id', '2024-10-23 13:43:21', '2024-10-23 13:43:21'),
	('fce63c9d-e0ef-427d-b2e7-4c51f32e41dd', 'LN202410-2', '202410-2', 'L-1', 'ANK-5', 'SPREI', 4, 0, NULL, 0, 0.00, NULL, NULL, 0, 124, 124, 'sufyan', '2024-10-23 13:45:01', '2024-10-23 13:45:01');

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_10_07_074905_create_m_unit_table', 1),
	(6, '2024_10_07_075100_create_m_alats_table', 1),
	(7, '2024_10_07_075151_create_m_alat_details_table', 1),
	(8, '2024_10_07_075341_create_m_linens_table', 1),
	(9, '2024_10_07_075449_create_m_linen_units_table', 1),
	(10, '2024_10_07_080105_create_pinjam_alats_table', 1),
	(11, '2024_10_08_033718_create_pinjam_alat_details_table', 1),
	(12, '2024_10_08_034023_create_linens_table', 1),
	(13, '2024_10_08_035018_create_linen_details_table', 1),
	(14, '2024_10_08_035314_create_daftars_table', 1);

CREATE TABLE IF NOT EXISTS `m_alat` (
  `kode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `m_alat` (`kode`, `nama`, `updated_by`, `created_at`, `updated_at`) VALUES
	('AL-2', 'GROUP-23', 1, '2024-10-14 20:54:39', '2024-10-14 20:54:39'),
	('AL-3', 'GROUP-33', 1, '2024-10-14 20:55:02', '2024-10-14 20:57:50'),
	('AL-4', 'HATING SET', 1, '2024-10-15 19:30:27', '2024-10-15 19:30:27');

CREATE TABLE IF NOT EXISTS `m_alat_detail` (
  `kode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_alat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml` smallint NOT NULL,
  `steril` smallint NOT NULL,
  `status` tinyint NOT NULL,
  `updated_by` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kode`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `m_alat_detail` (`kode`, `kode_alat`, `nama`, `jml`, `steril`, `status`, `updated_by`, `created_at`, `updated_at`) VALUES
	('AL-2-1', 'AL-3', 'GROUP-234', 23, 17, 1, 1, '2024-10-14 21:47:14', '2024-10-14 21:51:20'),
	('AL-4-3', 'AL-4', 'GUNTING AJ', 1, 1, 1, 1, '2024-10-15 19:30:50', '2024-10-28 21:46:27');

CREATE TABLE IF NOT EXISTS `m_linen` (
  `kode` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `m_linen` (`kode`, `nama`, `updated_by`, `created_at`, `updated_at`) VALUES
	('L-1', 'SPREI', 1, '2024-10-11 20:52:29', '2024-10-11 20:52:29'),
	('L-2', 'SARUNG BANTALS', 1, '2024-10-11 20:53:48', '2024-10-11 21:13:25'),
	('L-3', 'SELIMUT', 1, '2024-10-11 21:25:57', '2024-10-11 21:25:57'),
	('L-4', 'PERLAK', 1, '2024-10-11 21:26:01', '2024-10-11 21:26:01'),
	('L-5', 'HANDUK MANDI', 1, '2024-10-11 21:26:17', '2024-10-11 21:26:17'),
	('L-6', 'GORDEN', 1, '2024-10-28 21:42:41', '2024-10-28 21:42:41');

CREATE TABLE IF NOT EXISTS `m_linen_unit` (
  `id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_linen` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_unit` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bersih` smallint NOT NULL,
  `terpakai` smallint NOT NULL,
  `kotor` smallint NOT NULL,
  `status` tinyint NOT NULL,
  `updated_by` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `m_linen_unit` (`id`, `kode`, `kode_linen`, `nama`, `kode_unit`, `bersih`, `terpakai`, `kotor`, `status`, `updated_by`, `created_at`, `updated_at`) VALUES
	(4, 'ANK-4', 'L-5', 'HANDUK MANDI', 'ANK', 5, 0, 4, 1, 124, '2024-10-17 23:25:25', '2024-10-23 13:37:44'),
	(5, 'ANK-5', 'L-1', 'SPREI', 'ANK', 10, 0, 8, 1, 1, '2024-10-17 23:35:13', '2024-10-17 23:35:13'),
	(6, 'ANK-6', 'L-3', 'SELIMUT', 'ANK', 1, 0, 1, 1, 1, '2024-10-17 23:35:31', '2024-10-17 23:35:31'),
	(7, 'ANK-7', 'L-4', 'PERLAK', 'ANK', 3, 0, 3, 1, 1, '2024-10-17 23:46:11', '2024-10-21 00:16:37'),
	(8, 'CSSD-8', 'L-5', 'HANDUK MANDI', 'CSSD', 10, 0, 20, 1, 1, '2024-10-23 13:21:38', '2024-10-23 13:21:38'),
	(9, 'BRS-9', 'L-6', 'GORDEN', 'BRS', 5, 0, 3, 1, 1, '2024-10-28 21:45:36', '2024-10-29 19:31:57');

CREATE TABLE IF NOT EXISTS `m_unit` (
  `kode` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` smallint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `m_unit` (`kode`, `nama`, `updated_by`, `created_at`, `updated_at`) VALUES
	('ANK', 'ANAK', 1, '2024-10-08 02:53:09', '2024-10-08 02:53:09'),
	('BRS', 'BERSALIN', 1, '2024-10-08 02:57:30', '2024-10-08 02:57:30'),
	('CSSD', 'CSSD', 1, '2024-10-23 13:18:18', '2024-10-23 13:18:18'),
	('MAT', 'MATERNAL', 1, '2024-10-08 02:17:04', '2024-10-08 02:17:04'),
	('NEO', 'NEONATOLOGI', 1, '2024-10-08 02:53:19', '2024-10-08 02:53:19'),
	('PAV', 'PAVILIUN', 1, '2024-10-08 02:50:56', '2024-10-08 02:50:56'),
	('PLA', 'POLI ANAK', 1, '2024-10-28 21:41:42', '2024-10-28 21:41:52');

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `created_at`, `updated_at`) VALUES
	(1, 'App\\Models\\User', 1, 'WEB', '925eb529decd07e5c19dc38c475da5a41104ab05742f4a263b8e3b84abe9f13b', '["*"]', '2024-10-18 19:30:10', '2024-10-08 00:49:24', '2024-10-18 19:30:10'),
	(2, 'App\\Models\\User', 1, 'WEB', '52b8df4920072dbc8485cb82ae54134f9d525106f0fe4eba8c95fae7cba3b58e', '["*"]', '2024-10-11 02:07:18', '2024-10-10 21:18:11', '2024-10-11 02:07:18'),
	(3, 'App\\Models\\User', 1, 'WEB', '3b6d368406e5c5588c5f56bfb9b959460f022a729c097781c7bf51a505fa71af', '["*"]', '2024-10-17 01:08:28', '2024-10-16 18:13:03', '2024-10-17 01:08:28'),
	(6, 'App\\Models\\User', 1, 'WEB', '52b6af9d84686de9242d5915e9903efe00a5b1ca3d15ae91f795535431bec415', '["*"]', '2024-11-03 14:30:19', '2024-10-22 23:53:19', '2024-11-03 14:30:19'),
	(7, 'App\\Models\\User', 124, 'WEB', 'e68b66f3b6ba2a5dac35d20d1914e2db02377892cfea442fae4efc498d56d3a5', '["*"]', '2024-10-23 15:34:30', '2024-10-23 13:31:31', '2024-10-23 15:34:30'),
	(8, 'App\\Models\\User', 124, 'WEB', '724167cc98127286a6813a99fde70e8be0d929cfe40baa36c4d955c80ab56f02', '["*"]', '2024-10-28 01:22:34', '2024-10-27 20:31:32', '2024-10-28 01:22:34'),
	(9, 'App\\Models\\User', 1, 'WEB', '6bfab7c0b56b54f25ce0bdfb46dcb170200a1731ccb571c23443158d549bcf81', '["*"]', NULL, '2024-10-28 21:23:51', '2024-10-28 21:23:51'),
	(10, 'App\\Models\\User', 1, 'WEB', 'cf1c264b3e57ec0cc1888c64050a43ed49a432ed050440c4f55ac9545e0c97a0', '["*"]', NULL, '2024-10-28 21:24:03', '2024-10-28 21:24:03'),
	(11, 'App\\Models\\User', 1, 'WEB', '4f2d751e4af536327ded7ff61463b9aaf673d23e553f224d78bb24eacb07e7d7', '["*"]', NULL, '2024-10-28 21:24:12', '2024-10-28 21:24:12'),
	(12, 'App\\Models\\User', 1, 'WEB', '4813c5f09228507c094a563f0a02a820bd0acd5c818fee7cef44613789937bf3', '["*"]', NULL, '2024-10-28 21:24:58', '2024-10-28 21:24:58'),
	(13, 'App\\Models\\User', 1, 'WEB', '652a3b2bf540330196590920a9f6b1bad56efd979cf843442a1fb4367703a221', '["*"]', NULL, '2024-10-28 21:25:04', '2024-10-28 21:25:04'),
	(14, 'App\\Models\\User', 1, 'WEB', 'acab7c69265f2874df16d62a5bf5229950dfb45d79f19c367084717c05f8cb6c', '["*"]', NULL, '2024-10-28 21:25:37', '2024-10-28 21:25:37'),
	(15, 'App\\Models\\User', 1, 'WEB', '2491c8a62829ef063e262ff0c8b9b760b855ace92967a2f786e503bd8868408c', '["*"]', NULL, '2024-10-28 21:26:09', '2024-10-28 21:26:09'),
	(16, 'App\\Models\\User', 1, 'WEB', '128bdebb057e78eae9afff5b0bc91643b17b30eb696befa36520ff8e98c87a56', '["*"]', NULL, '2024-10-28 21:26:52', '2024-10-28 21:26:52'),
	(17, 'App\\Models\\User', 1, 'WEB', '8bef7bb03e45de89029f0dbe022624be6fcfb9b482734f6910cbd6b13008dd4a', '["*"]', NULL, '2024-10-28 21:26:54', '2024-10-28 21:26:54'),
	(18, 'App\\Models\\User', 1, 'WEB', '3f13a95898184da067f62e74a546b214649a0cba272e448972a5c075f1b89277', '["*"]', NULL, '2024-10-28 21:27:46', '2024-10-28 21:27:46'),
	(19, 'App\\Models\\User', 1, 'WEB', 'bcf4be515ebbbb58d68f60c05530491db857d6628f0342f06580822b69ba1914', '["*"]', NULL, '2024-10-28 21:28:24', '2024-10-28 21:28:24'),
	(20, 'App\\Models\\User', 1, 'WEB', '06a4b76b3e55f9c963d625da91faf62cdbb4b82b921e723597ce20670bb64d1e', '["*"]', NULL, '2024-10-28 21:30:50', '2024-10-28 21:30:50'),
	(21, 'App\\Models\\User', 1, 'WEB', '700bc5bf8b8074c00db4b4556c2bd920c0527b467a7bc1b75fcd5becf81d32f3', '["*"]', NULL, '2024-10-28 21:32:05', '2024-10-28 21:32:05'),
	(22, 'App\\Models\\User', 1, 'WEB', 'a6de697c996050b51d0ec6fa9efb37addc7988af7296ef92dd85b619608061b3', '["*"]', '2024-10-29 00:41:15', '2024-10-28 21:33:36', '2024-10-29 00:41:15'),
	(24, 'App\\Models\\User', 124, 'WEB', '52696a63c9f67a69c5dc9280d47d3c8249508f271b114d5c439a56a0ce23218a', '["*"]', '2024-10-28 22:02:17', '2024-10-28 21:40:09', '2024-10-28 22:02:17'),
	(25, 'App\\Models\\User', 1, 'WEB', '0b178a6edc4ee01c745c31d4ffa70543c64a094acaedafc2e9ad692918a88e91', '["*"]', '2024-10-31 01:20:26', '2024-10-31 00:19:54', '2024-10-31 01:20:26');

CREATE TABLE IF NOT EXISTS `pinjam_alat` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nota` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_daftar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml` int NOT NULL,
  `pinjam` datetime NOT NULL,
  `kembali` datetime NOT NULL,
  `status` tinyint NOT NULL,
  `kd_unit` char(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_by` smallint unsigned NOT NULL,
  `created_by_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_by` smallint unsigned NOT NULL,
  `updated_by_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `pinjam_alat_detail` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nota_pinjam` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `kode_daftar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_alat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jml` int NOT NULL,
  `jml_kembali` int NOT NULL,
  `pinjam` datetime NOT NULL,
  `kembali` datetime NOT NULL,
  `status` tinyint NOT NULL,
  `updated_by` smallint unsigned NOT NULL,
  `updated_by_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kd_unit` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `kd_unit`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'salman', 'salman', '$2y$10$qyCCPWzPqDg65bOUQbsDyuXXyeI/IVhdvt40H.nzBTSvEmi2KESfG', 'SUPER_ADMIN', 'CSSD', '123', '2024-10-08 07:33:54', '2024-10-08 07:33:55'),
	(124, 'sufyan', 'sufyan', '$2y$10$qyCCPWzPqDg65bOUQbsDyuXXyeI/IVhdvt40H.nzBTSvEmi2KESfG', 'UNIT', 'ANK', '123', '2024-10-08 07:33:54', '2024-10-08 07:33:55');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
