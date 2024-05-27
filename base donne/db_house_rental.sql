-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 12 fév. 2024 à 00:19
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_house_rental`
--

-- --------------------------------------------------------

--
-- Structure de la table `appartement`
--

CREATE TABLE `appartement` (
  `id` int(11) NOT NULL,
  `nom_app` varchar(250) NOT NULL,
  `type` varchar(250) NOT NULL,
  `chambre_coucher` int(11) NOT NULL,
  `location` varchar(250) NOT NULL,
  `img` varchar(250) NOT NULL,
  `prix` int(11) NOT NULL,
  `id_proprietaire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `appartement`
--

INSERT INTO `appartement` (`id`, `nom_app`, `type`, `chambre_coucher`, `location`, `img`, `prix`, `id_proprietaire`) VALUES
(46, 'شقة 1', 'شقة', 2, 'القاهرة', 'image1.jpg', 2000, 9),
(47, 'فيلا 2', 'فيلا', 3, 'الإسكندرية', 'image2.jpg', 5000, 10),
(48, 'شقة 3', 'شقة', 2, 'الغردقة', 'image3.jpg', 1500, 11),
(49, 'شاليه 4', 'شاليه', 1, 'شرم الشيخ', 'image4.jpg', 3000, 12),
(50, 'شقة 5', 'شقة', 2, 'الإسماعيلية', 'image5.jpg', 1800, 13),
(51, 'فيلا 6', 'فيلا', 4, 'أسوان', 'image6.jpg', 4000, 14);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `tele` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id`, `nom`, `prenom`, `tele`, `date_naissance`, `email`, `password`) VALUES
(8, 'أحمد', 'خالد', '0123456789', '1990-05-15', 'mohamed@gmail.com', '12345'),
(9, 'فاطمة', 'محمد', '01123456789', '1988-08-25', 'fatma@example.com', 'pass123'),
(10, 'محمد', 'علي', '01012345678', '1995-02-10', 'mohamed@example.com', 'testpass'),
(11, 'نور', 'حسن', '01543210987', '1992-11-30', 'nour@example.com', 'securepass'),
(12, 'سارة', 'أحمد', '0123456789', '1991-07-20', 'sara@example.com', 'pass123'),
(13, 'يوسف', 'سمير', '01112345678', '1989-04-05', 'youssef@example.com', 'password456'),
(14, 'أحمد', 'خالد', '0123456789', '1990-05-15', 'ahmed@example.com', 'password123'),
(15, 'فاطمة', 'محمد', '01123456789', '1988-08-25', 'fatma@example.com', 'pass123'),
(16, 'محمد', 'علي', '01012345678', '1995-02-10', 'mohamed@example.com', 'testpass'),
(17, 'نور', 'حسن', '01543210987', '1992-11-30', 'nour@example.com', 'securepass'),
(18, 'سارة', 'أحمد', '0123456789', '1991-07-20', 'sara@example.com', 'pass123'),
(19, 'يوسف', 'سمير', '01112345678', '1989-04-05', 'youssef@example.com', 'password456');

-- --------------------------------------------------------

--
-- Structure de la table `proprietaire`
--

CREATE TABLE `proprietaire` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `tele` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `proprietaire`
--

INSERT INTO `proprietaire` (`id`, `nom`, `prenom`, `tele`, `date_naissance`, `email`, `password`) VALUES
(9, 'مالك 6', 'اللقب 6', '01012345678', '1993-06-10', 'owner6@example.com', 'ownerpass6'),
(10, 'مالك 7', 'اللقب 7', '01123456789', '1991-09-15', 'owner7@example.com', 'ownerpass7'),
(11, 'مالك 8', 'اللقب 8', '01234567890', '1988-12-20', 'owner8@example.com', 'ownerpass8'),
(12, 'مالك 9', 'اللقب 9', '01098765432', '1995-04-25', 'owner9@example.com', 'ownerpass9'),
(13, 'مالك 10', 'اللقب 10', '01111112222', '1990-08-30', 'owner10@example.com', 'ownerpass10'),
(14, 'مالك 11', 'اللقب 11', '01222223333', '1987-03-05', 'owner11@example.com', 'ownerpass11');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_appartement` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `id_client`, `id_appartement`, `date_debut`, `date_fin`) VALUES
(1, 8, 46, '2024-02-01', '2024-02-10'),
(2, 8, 46, '2024-02-01', '2024-02-10'),
(3, 8, 47, '2024-02-01', '2024-02-10');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `appartement`
--
ALTER TABLE `appartement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_proprietaire` (`id_proprietaire`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservation_client` (`id_client`),
  ADD KEY `fk_reservation_appartement` (`id_appartement`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `appartement`
--
ALTER TABLE `appartement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appartement`
--
ALTER TABLE `appartement`
  ADD CONSTRAINT `appartement_ibfk_1` FOREIGN KEY (`id_proprietaire`) REFERENCES `proprietaire` (`id`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_reservation_appartement` FOREIGN KEY (`id_appartement`) REFERENCES `appartement` (`id`),
  ADD CONSTRAINT `fk_reservation_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
