-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 oct. 2026 à 10:47
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `rh_app`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Structure de la table `candidats`
--

CREATE TABLE `candidats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cv_path` varchar(255) NOT NULL,
  `lettre_path` varchar(255) DEFAULT NULL,
  `moyenne_finale` decimal(5,2) DEFAULT NULL,
  `statut` enum('en_attente','en_cours','refuse','retenu') NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidats`
--

INSERT INTO `candidats` (`id`, `user_id`, `nom`, `prenom`, `email`, `cv_path`, `lettre_path`, `moyenne_finale`, `statut`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ANALENE', 'Émile', 'emileanalene03@gmail.com', 'cvs/t9F38anIXX701MwmkrOxgOFz8ZP8QEeNHqZRSdYD.pdf', 'lettres/AjWwZTHmu1yTCppnGEsGUdhvog175tERQgV0T1we.pdf', NULL, 'retenu', '2026-09-16 10:28:00', '2026-09-16 10:31:02');

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidat_id` bigint(20) UNSIGNED NOT NULL,
  `offre_id` bigint(20) UNSIGNED NOT NULL,
  `date_postulation` date NOT NULL,
  `statut` enum('en_attente','preselectionne','entretien','valide','refuse') NOT NULL DEFAULT 'en_attente',
  `commentaire_rh` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `candidatures`
--

INSERT INTO `candidatures` (`id`, `candidat_id`, `offre_id`, `date_postulation`, `statut`, `commentaire_rh`, `created_at`, `updated_at`, `archived_at`) VALUES
(1, 1, 1, '2026-09-16', 'valide', NULL, '2026-09-16 10:28:00', '2026-09-16 10:31:02', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `carriere_historiques`
--

CREATE TABLE `carriere_historiques` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employe_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `ancienne_valeur` varchar(255) NOT NULL,
  `nouvelle_valeur` varchar(255) NOT NULL,
  `date_changement` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `conges`
--

CREATE TABLE `conges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employe_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nombre_jours` decimal(5,1) NOT NULL,
  `justificatif_path` varchar(255) DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `statut` enum('en_attente','valide_chef','valide_rh','refuse') NOT NULL DEFAULT 'en_attente',
  `commentaire_refus` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `conges`
--

INSERT INTO `conges` (`id`, `employe_id`, `type`, `date_debut`, `date_fin`, `nombre_jours`, `justificatif_path`, `motif`, `statut`, `commentaire_refus`, `created_at`, `updated_at`) VALUES
(1, 5, 'permission', '2026-09-17', '2026-09-18', 2.0, 'justificatifs/IE4eidjyC1P61r7MIlBiOc5sOFov8PdXjIRbkE18.pdf', NULL, 'valide_rh', NULL, '2026-09-16 10:33:08', '2026-09-16 10:34:16');

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

CREATE TABLE `contrats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employe_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('CDI','CDD','Stage') NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `salaire_base` decimal(10,2) NOT NULL,
  `poste` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `matricule` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `poste_actuel` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `date_embauche` date NOT NULL,
  `solde_conge` decimal(5,1) NOT NULL DEFAULT 30.0,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`id`, `user_id`, `matricule`, `nom`, `prenom`, `poste_actuel`, `service`, `date_embauche`, `solde_conge`, `photo`, `created_at`, `updated_at`) VALUES
(1, 1, 'EMP-ADMIN-001', 'Admin', 'Système', 'Administrateur', 'Direction', '2024-01-01', 30.0, NULL, '2026-09-15 23:40:36', '2026-09-15 23:40:36'),
(2, 2, 'EMP-RH-001', 'Laurent', 'Marie', 'Responsable RH', 'Ressources Humaines', '2024-01-01', 30.0, NULL, '2026-09-15 23:40:37', '2026-09-15 23:40:37'),
(3, 3, 'EMP-CHEF-001', 'Dubois', 'Thomas', 'Chef de service IT', 'Informatique', '2024-01-01', 30.0, NULL, '2026-09-15 23:40:37', '2026-09-15 23:40:37'),
(4, 4, 'EMP-001', 'Martin', 'Sophie', 'Développeuse', 'Informatique', '2024-01-01', 30.0, NULL, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(5, 5, 'EMP-2026-002', 'ANALENE', 'Émile', 'Développeur Full Stack Laravel', 'À définir', '2026-09-16', 28.0, NULL, '2026-09-16 10:31:24', '2026-09-16 10:34:16');

-- --------------------------------------------------------

--
-- Structure de la table `etapes_recrutement`
--

CREATE TABLE `etapes_recrutement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `offre_id` bigint(20) UNSIGNED NOT NULL,
  `nom_etape` varchar(255) NOT NULL,
  `ordre` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etapes_recrutement`
--

INSERT INTO `etapes_recrutement` (`id`, `offre_id`, `nom_etape`, `ordre`, `created_at`, `updated_at`) VALUES
(1, 1, 'Présélection', 1, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(2, 1, 'Test technique', 2, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(3, 1, 'Entretien RH', 3, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(4, 1, 'Entretien technique', 4, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(5, 1, 'Validation finale', 5, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(6, 2, 'Présélection', 1, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(7, 2, 'Test technique', 2, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(8, 2, 'Entretien RH', 3, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(9, 2, 'Entretien technique', 4, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(10, 2, 'Validation finale', 5, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(11, 3, 'Présélection', 1, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(12, 3, 'Test technique', 2, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(13, 3, 'Entretien RH', 3, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(14, 3, 'Entretien technique', 4, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(15, 3, 'Validation finale', 5, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(16, 4, 'Présélection', 1, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(17, 4, 'Test technique', 2, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(18, 4, 'Entretien RH', 3, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(19, 4, 'Entretien technique', 4, '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(20, 4, 'Validation finale', 5, '2026-09-15 23:40:38', '2026-09-15 23:40:38');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidature_id` bigint(20) UNSIGNED NOT NULL,
  `etape_recrutement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `evaluateur_id` bigint(20) UNSIGNED NOT NULL,
  `note` decimal(5,2) NOT NULL,
  `etape` varchar(255) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `candidature_id`, `etape_recrutement_id`, `evaluateur_id`, `note`, `etape`, `commentaire`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 14.00, NULL, NULL, '2026-09-16 10:29:27', '2026-09-16 10:29:27'),
(2, 1, 4, 2, 13.00, NULL, NULL, '2026-09-16 10:30:41', '2026-09-16 10:30:41');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_23_084124_create_roles_table', 1),
(5, '2026_04_23_084305_add_role_id_to_users_table', 1),
(6, '2026_04_23_084349_create_employes_table', 1),
(7, '2026_04_23_084846_create_candidats_table', 1),
(8, '2026_04_23_085553_create_offres_table', 1),
(9, '2026_04_23_085645_create_candidatures_table', 1),
(10, '2026_04_23_085713_create_etapes_recrutement_table', 1),
(11, '2026_04_23_085735_create_evaluations_table', 1),
(12, '2026_04_23_085758_create_contrats_table', 1),
(13, '2026_04_23_085822_create_conges_table', 1),
(14, '2026_04_23_085842_create_notifications_table', 1),
(15, '2026_04_27_113426_add_archived_at_to_candidatures_table', 1),
(16, '2026_04_27_131037_create_carriere_historiques_table', 1),
(17, '2026_08_22_102009_add_etape_recrutement_id_to_evaluations_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `url`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 'candidature', '📌 Nouvelle candidature', 'Émile ANALENE a postulé à Développeur Full Stack Laravel', 'http://127.0.0.1:8000/admin/candidatures/1', 0, '2026-09-16 10:28:00', '2026-09-16 10:28:00'),
(2, 2, 'candidature', '📌 Nouvelle candidature', 'Émile ANALENE a postulé à Développeur Full Stack Laravel', 'http://127.0.0.1:8000/admin/candidatures/1', 0, '2026-09-16 10:28:00', '2026-09-16 10:28:00'),
(3, 5, 'candidature', '📢 Statut de votre candidature', 'Votre candidature pour Développeur Full Stack Laravel est maintenant : Preselectionne', 'http://127.0.0.1:8000/mes-candidatures', 0, '2026-09-16 10:29:39', '2026-09-16 10:29:39'),
(4, 5, 'candidature', '📢 Statut de votre candidature', 'Votre candidature pour Développeur Full Stack Laravel est maintenant : Valide', 'http://127.0.0.1:8000/mes-candidatures', 0, '2026-09-16 10:31:08', '2026-09-16 10:31:08'),
(5, 3, 'conge', '⏰ Demande de permission', 'Émile ANALENE a demandé une permission', 'http://127.0.0.1:8000/conges', 0, '2026-09-16 10:33:08', '2026-09-16 10:33:08'),
(6, 2, 'conge', '⏰ Demande de permission (en attente Chef)', 'Émile ANALENE a demandé une permission', 'http://127.0.0.1:8000/conges', 0, '2026-09-16 10:33:08', '2026-09-16 10:33:08'),
(7, 1, 'conge', '⏰ Demande de permission (en attente Chef)', 'Émile ANALENE a demandé une permission', 'http://127.0.0.1:8000/conges', 0, '2026-09-16 10:33:08', '2026-09-16 10:33:08'),
(8, 5, 'conge', '✅ Demande validée (Niveau 1)', 'Votre demande a été validée par le Chef de service. En attente de validation RH.', 'http://127.0.0.1:8000/conges', 0, '2026-09-16 10:33:53', '2026-09-16 10:33:53'),
(9, 5, 'conge', '🎉 Demande validée définitivement', 'Votre demande a été validée par le RH. Votre solde a été mis à jour.', 'http://127.0.0.1:8000/conges', 0, '2026-09-16 10:34:16', '2026-09-16 10:34:16');

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

CREATE TABLE `offres` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date_publication` date NOT NULL,
  `lieu` varchar(255) DEFAULT NULL,
  `type_contrat` varchar(255) NOT NULL,
  `statut` enum('publiee','fermee','en_attente') NOT NULL DEFAULT 'publiee',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id`, `titre`, `description`, `date_publication`, `lieu`, `type_contrat`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'Développeur Full Stack Laravel', 'Nous recherchons un développeur Full Stack maîtrisant Laravel, PHP, MySQL, JavaScript, HTML/CSS. Bon relationnel et travail en équipe requis.', '2026-09-15', 'Lomé, Togo', 'CDI', 'publiee', '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(2, 'Responsable Ressources Humaines', 'Nous recherchons un responsable RH pour gérer le recrutement, la formation, les relations sociales et la paie. Expérience exigée.', '2026-09-15', 'Lomé, Togo', 'CDI', 'publiee', '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(3, 'Infographiste 3D', 'Création de visuels 3D, maîtrise de Blender, Maya ou 3ds Max. Portfolio exigé.', '2026-09-15', 'Lomé, Togo', 'CDD', 'publiee', '2026-09-15 23:40:38', '2026-09-15 23:40:38'),
(4, 'Chef de Projet Digital', 'Pilotage de projets digitaux, gestion d\'équipe, relation client. Expérience en agence web appréciée.', '2026-09-15', 'Lomé, Togo', 'CDI', 'publiee', '2026-09-15 23:40:38', '2026-09-15 23:40:38');

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
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `nom`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2026-09-15 23:40:36', '2026-09-15 23:40:36'),
(2, 'RH', '2026-09-15 23:40:36', '2026-09-15 23:40:36'),
(3, 'Chef', '2026-09-15 23:40:36', '2026-09-15 23:40:36'),
(4, 'Employe', '2026-09-15 23:40:36', '2026-09-15 23:40:36'),
(5, 'Candidat', '2026-09-15 23:40:36', '2026-09-15 23:40:36');

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'Administrateur', 'admin@newbrain.com', NULL, '$2y$12$VA3u6ANv7JLaqIgp.CoEsO8HIATtONzopw7fiuCNP1DUN47M2QDbO', NULL, '2026-09-15 23:40:36', '2026-09-15 23:40:36', 1),
(2, 'Responsable RH', 'rh@newbrain.com', NULL, '$2y$12$pNMg57U9lbK69Lp/aSO1quuO4081nDfFz0LVBZteB4lEWQG/hrVpW', NULL, '2026-09-15 23:40:37', '2026-09-15 23:40:37', 2),
(3, 'Chef de service', 'chef@newbrain.com', NULL, '$2y$12$Clc0WlZ9MxdHlfFO58xqhe0FegeKvM2gchUx2qiPWpaEmJ1yIIQie', NULL, '2026-09-15 23:40:37', '2026-09-15 23:40:37', 3),
(4, 'Employé', 'employe@newbrain.com', NULL, '$2y$12$H8SOMP9cN/q1qL3UBBqcmuead2k9.ThUVOMSFqsXEHdG3ARv0/6zG', NULL, '2026-09-15 23:40:38', '2026-09-15 23:40:38', 4),
(5, 'Émile ANALENE', 'emileanalene03@gmail.com', NULL, '$2y$12$YmJy5HOj9ipqiz9L4JP2KuRmQn6XaDvYT1tnIh0XhWuv1WjoWUoSC', NULL, '2026-09-16 10:27:23', '2026-09-16 10:31:24', 4);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `candidats`
--
ALTER TABLE `candidats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidats_user_id_foreign` (`user_id`);

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidatures_candidat_id_foreign` (`candidat_id`),
  ADD KEY `candidatures_offre_id_foreign` (`offre_id`);

--
-- Index pour la table `carriere_historiques`
--
ALTER TABLE `carriere_historiques`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carriere_historiques_employe_id_foreign` (`employe_id`);

--
-- Index pour la table `conges`
--
ALTER TABLE `conges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conges_employe_id_foreign` (`employe_id`);

--
-- Index pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contrats_employe_id_foreign` (`employe_id`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employes_matricule_unique` (`matricule`),
  ADD KEY `employes_user_id_foreign` (`user_id`);

--
-- Index pour la table `etapes_recrutement`
--
ALTER TABLE `etapes_recrutement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etapes_recrutement_offre_id_foreign` (`offre_id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evaluations_candidature_id_foreign` (`candidature_id`),
  ADD KEY `evaluations_evaluateur_id_foreign` (`evaluateur_id`),
  ADD KEY `evaluations_etape_recrutement_id_foreign` (`etape_recrutement_id`);

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
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Index pour la table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nom_unique` (`nom`);

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
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `candidats`
--
ALTER TABLE `candidats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `carriere_historiques`
--
ALTER TABLE `carriere_historiques`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `conges`
--
ALTER TABLE `conges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `contrats`
--
ALTER TABLE `contrats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `etapes_recrutement`
--
ALTER TABLE `etapes_recrutement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidats`
--
ALTER TABLE `candidats`
  ADD CONSTRAINT `candidats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_candidat_id_foreign` FOREIGN KEY (`candidat_id`) REFERENCES `candidats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatures_offre_id_foreign` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `carriere_historiques`
--
ALTER TABLE `carriere_historiques`
  ADD CONSTRAINT `carriere_historiques_employe_id_foreign` FOREIGN KEY (`employe_id`) REFERENCES `employes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `conges`
--
ALTER TABLE `conges`
  ADD CONSTRAINT `conges_employe_id_foreign` FOREIGN KEY (`employe_id`) REFERENCES `employes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `contrats_employe_id_foreign` FOREIGN KEY (`employe_id`) REFERENCES `employes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `employes`
--
ALTER TABLE `employes`
  ADD CONSTRAINT `employes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etapes_recrutement`
--
ALTER TABLE `etapes_recrutement`
  ADD CONSTRAINT `etapes_recrutement_offre_id_foreign` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_candidature_id_foreign` FOREIGN KEY (`candidature_id`) REFERENCES `candidatures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_etape_recrutement_id_foreign` FOREIGN KEY (`etape_recrutement_id`) REFERENCES `etapes_recrutement` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `evaluations_evaluateur_id_foreign` FOREIGN KEY (`evaluateur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
