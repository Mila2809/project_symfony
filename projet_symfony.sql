-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 29 déc. 2024 à 23:15
-- Version du serveur : 8.0.30
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int NOT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `date_achat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `utilisateur_id`, `date_achat`) VALUES
(1, 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commandes_contenu_panier`
--

CREATE TABLE `commandes_contenu_panier` (
  `commandes_id` int NOT NULL,
  `contenu_panier_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contenu_panier`
--

CREATE TABLE `contenu_panier` (
  `id` int NOT NULL,
  `produit_id` int DEFAULT NULL,
  `quantite` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20241227164937', '2024-12-27 16:51:48', 219);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `stock` int DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `nom`, `description`, `prix`, `stock`, `photo`) VALUES
(1, 'Raw Meat', 'A fresh chunk of meat. Handled properly, it can be used to make delicious food.\r\n📍Source :\r\nDropped by animals', 240, 6, 'UI_ItemIcon_100061.png'),
(8, 'Mushroom', 'Hardy common fungi. Can grow anywhere with the right amount of shade and moisture. 📍Source : Found in the wild', 25, 15, 'UI_ItemIcon_100011.png'),
(9, 'Sweet Flower', 'Particularly fragrant flowers. They can be found easily, even in the dark. Just follow the scent.📍Source : Found in the wild', 15, 39, 'UI_ItemIcon_100012.png'),
(10, 'Carrot', 'A healthy and nutritious vegetable that is crunchy and sweet to the taste. Easy to grow and harvest.📍Source :\r\nFound in the wild', 34, 45, 'UI_ItemIcon_100013.png'),
(11, 'Radish', 'Rich in fiber and nutrients. Easy to grow and harvest.📍Source\r\nFound in the wild', 23, 75, 'UI_ItemIcon_100014.png'),
(12, 'Snapdragon', 'Can be eaten once cooked. As a spice, it can bring wonderful flavor to dishes.\r\n📍Source :\r\nFound near lakes and river banks', 14, 67, 'UI_ItemIcon_100015.png'),
(13, 'Mint', 'A refreshingly cool ingredient. The cool, fresh flavor can cut through the heat of many dishes.\r\n📍Source :\r\nFound in the wild', 31, 56, 'UI_ItemIcon_100016.png'),
(14, 'Wheat', 'Golden, sun-kissed tassels. Needs to be ground down to flour for further use.\r\n📍Source :\r\nFound in the wild,\r\nSold at general goods shops', 21, 234, 'UI_ItemIcon_100017.png'),
(15, 'Cabbage', 'A layered, leafy vegetable. Said to have originally been an ornamental plant, it certainly looks great in the pot.\r\n📍Source :\r\nFound in the wild,\r\nSold at general goods shops', 24, 45, 'UI_ItemIcon_100018.png');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `created_at`) VALUES
(1, 'mipa@gmail.com', '[]', '$2y$13$4hzXSmiOS5aUxpyo7WZZ6OUfzGgXQbwIoD7cWJlmTgLmi31rrGu7y', 'Pa', 'Mila', '2024-12-29 11:55:14'),
(2, 'mipaa@gmail.com', '[]', 'milaapa', 'Paa', 'Milaa', '2024-12-29 15:28:56'),
(3, 'lo@lo', '[]', '$2y$13$FbZ0AV6DLz8syXXfBP5BBOf4bJ.ihhHghq/F2AEM06WoRqGUkZl8O', 'lo', 'loup', '2024-12-29 19:38:36');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_35D4282CFB88E14F` (`utilisateur_id`);

--
-- Index pour la table `commandes_contenu_panier`
--
ALTER TABLE `commandes_contenu_panier`
  ADD PRIMARY KEY (`commandes_id`,`contenu_panier_id`),
  ADD KEY `IDX_8AF7E83F8BF5C2E6` (`commandes_id`),
  ADD KEY `IDX_8AF7E83F61405BF` (`contenu_panier_id`);

--
-- Index pour la table `contenu_panier`
--
ALTER TABLE `contenu_panier`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_80507DC0F347EFB` (`produit_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `contenu_panier`
--
ALTER TABLE `contenu_panier`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `FK_35D4282CFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `commandes_contenu_panier`
--
ALTER TABLE `commandes_contenu_panier`
  ADD CONSTRAINT `FK_8AF7E83F61405BF` FOREIGN KEY (`contenu_panier_id`) REFERENCES `contenu_panier` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8AF7E83F8BF5C2E6` FOREIGN KEY (`commandes_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contenu_panier`
--
ALTER TABLE `contenu_panier`
  ADD CONSTRAINT `FK_80507DC0F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
