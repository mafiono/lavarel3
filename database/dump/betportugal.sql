-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 15, 2016 at 12:24 PM
-- Server version: 5.5.45-cll-lve
-- PHP Version: 5.4.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `betportugal`
--
CREATE DATABASE IF NOT EXISTS `betportugal` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `betportugal`;

-- --------------------------------------------------------

--
-- Table structure for table `api_request_logs`
--

DROP TABLE IF EXISTS `api_request_logs`;
CREATE TABLE IF NOT EXISTS `api_request_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request` varchar(1000) COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `bonus`
--

DROP TABLE IF EXISTS `bonus`;
CREATE TABLE IF NOT EXISTS `bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bonus_origin_id` varchar(15) COLLATE utf8_general_ci NOT NULL DEFAULT 'sport',
  `bonus_type_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `title` varchar(32) COLLATE utf8_general_ci DEFAULT NULL,
  `description` varchar(250) COLLATE utf8_general_ci DEFAULT NULL,
  `value` decimal(15,2) NOT NULL,
  `value_type` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `apply` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `apply_deposit_methods` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `destiny` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `destiny_operation` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `destiny_value` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `focus` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `bonus_segment_id` int(11) NOT NULL,
  `target` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `min_deposit` decimal(15,2) NOT NULL,
  `max_deposit` decimal(15,2) NOT NULL,
  `bailout_date` date NOT NULL,
  `min_odd` decimal(15,2) NOT NULL,
  `rollover_amount` decimal(15,2) NOT NULL,
  `available_from` date NOT NULL,
  `available_until` date NOT NULL,
  `deadline` int(11) NOT NULL,
  `staff_id` int(10) DEFAULT NULL,
  `staff_session_id` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=13;

--
-- Dumping data for table `bonus`
--

INSERT INTO `bonus` (`id`, `bonus_origin_id`, `bonus_type_id`, `title`, `description`, `value`, `value_type`, `apply`, `apply_deposit_methods`, `destiny`, `destiny_operation`, `destiny_value`, `focus`, `bonus_segment_id`, `target`, `min_deposit`, `max_deposit`, `bailout_date`, `min_odd`, `rollover_amount`, `available_from`, `available_until`, `deadline`, `staff_id`, `staff_session_id`, `updated_at`, `created_at`) VALUES
(1, 'sport', 'bonus_prime', 'Bonus virtual', 'Bonus malandro', '10.00', '', '', '', 'all', '', '0', 'Espanha', 3, '', '5.00', '10.00', '2016-02-29', '2.00', '69.00', '0000-00-00', '0000-00-00', 0, NULL, NULL, NULL, NULL),
(2, 'sport', 'bonus_prime', 'title', 'desc', '69.00', 'absolute', 'all_deposits', 'paypal', 'player', 'including', '69', '', 0, 'Shark', '1.00', '2.00', '0000-00-00', '0.00', '4.00', '0000-00-00', '0000-00-00', 5, NULL, NULL, '2016-03-01 18:07:48', '2016-03-01 18:07:48'),
(3, 'sport', 'bonus_prime', '', '', '0.00', '', '', 'all', 'all', '', '0', '', 0, 'all', '0.00', '0.00', '0000-00-00', '0.00', '0.00', '0000-00-00', '0000-00-00', 0, NULL, NULL, '2016-03-01 18:12:48', '2016-03-01 18:12:48'),
(4, 'sport', 'bonus_prime', '', '', '0.00', '', 'free_apply', 'all', 'all', '', '0', '', 0, 'all', '0.00', '0.00', '0000-00-00', '0.00', '0.00', '0000-00-00', '0000-00-00', 0, NULL, NULL, '2016-03-01 18:37:23', '2016-03-01 18:37:23'),
(5, 'sport', 'free_bet', 'bluebet', 'desk', '112.00', '', 'free_apply', 'all', 'player', '', '', '', 0, 'all', '2.00', '3.00', '0000-00-00', '1.00', '2.00', '0000-00-00', '2016-04-15', 2, 3, 524, '2016-03-31 23:29:02', '2016-03-01 18:39:02'),
(6, 'sport', 'deposits', 'blue', 'desc', '21.00', 'percentage', 'all_deposits', 'all', 'all', '', '0', '', 0, 'all', '1.00', '4.00', '0000-00-00', '1.00', '1.00', '0000-00-00', '2016-03-23', 1, NULL, NULL, '2016-03-02 17:30:41', '2016-03-01 19:21:53'),
(7, 'sport', 'first_deposit', '1', '2', '3.00', '', 'free_apply', 'all', 'all', '', '0', '', 0, 'all', '0.00', '0.00', '0000-00-00', '0.00', '0.00', '0000-00-00', '2016-03-11', 0, NULL, NULL, '2016-03-02 17:09:33', '2016-03-01 22:06:07'),
(8, 'sport', 'free_bet', '122', 'desc', '3.00', 'percentage', 'free_apply', 'all', 'player', 'including', 'miguel', '', 0, 'all', '1.00', '2.00', '0000-00-00', '3.00', '5.00', '0000-00-00', '2016-03-03', 2, NULL, NULL, '2016-03-02 23:18:39', '2016-03-02 17:00:30'),
(9, 'sport', 'first_deposit', 'title', 'desc', '12.00', 'absolute', '', 'all', 'all', '', '0', '', 0, 'all', '12.00', '12.00', '0000-00-00', '12.00', '12.00', '0000-00-00', '2016-03-23', 2, NULL, NULL, '2016-03-02 17:03:06', '2016-03-02 17:03:06'),
(10, 'sport', 'first_deposit', 'Buuunos', 'Nice', '-100.00', 'absolute', 'first_deposit', 'all', 'all', '', '1', '', 0, 'all', '1.00', '1.00', '0000-00-00', '1.00', '1.00', '0000-00-00', '2016-04-22', 1, 3, 532, '2016-04-01 16:38:21', '2016-03-02 17:20:13'),
(11, 'sport', 'free_bet', 'TitanBet', 'd2', '69.00', 'percentage', 'free_apply', 'all', 'all', '', 'm', '', 0, 'all', '2.00', '2.00', '0000-00-00', '3.00', '0.00', '2016-04-02', '2016-04-14', 6, 3, 534, '2016-04-01 20:05:28', '2016-03-02 17:31:26'),
(12, 'sport', 'free_bet', '122', '3', '233.00', 'absolute', 'free_apply', 'all', 'country', '', 'm', '', 0, '2', '1.00', '2.00', '0000-00-00', '3.00', '14.00', '0000-00-00', '2016-03-11', 2, NULL, NULL, '2016-03-02 21:19:55', '2016-03-02 17:32:52');

-- --------------------------------------------------------

--
-- Table structure for table `bonus_segments`
--

DROP TABLE IF EXISTS `bonus_segments`;
CREATE TABLE IF NOT EXISTS `bonus_segments` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bonus_segments`
--

INSERT INTO `bonus_segments` (`id`, `name`) VALUES
('all', 'All'),
('event', 'Event'),
('game', 'Game'),
('last_login', 'Last Login'),
('market', 'Market'),
('player', 'Specific Player'),
('sport', 'Sport');

-- --------------------------------------------------------

--
-- Table structure for table `bonus_types`
--

DROP TABLE IF EXISTS `bonus_types`;
CREATE TABLE IF NOT EXISTS `bonus_types` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `bonus_types`
--

INSERT INTO `bonus_types` (`id`, `name`) VALUES
('all', 'All'),
('bonus_prime', 'Bonus Prime'),
('deposits', 'Deposits'),
('first_deposit', '1º Deposit'),
('free_bet', 'Free Bet'),
('money_back', 'Money Back');

-- --------------------------------------------------------

--
-- Table structure for table `casino_games`
--

DROP TABLE IF EXISTS `casino_games`;
CREATE TABLE IF NOT EXISTS `casino_games` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `available` tinyint(1) NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `game_type_id` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `image_url` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `casino_games`
--

INSERT INTO `casino_games` (`id`, `game_id`, `provider_id`, `name`, `available`, `featured`, `game_type_id`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 'DJ Wild', 1, 1, 'cards', 'assets/portal/img/demo/img8.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 0, 0, 'Eletric SAM', 0, 0, 'cards', 'assets/portal/img/demo/img6.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 0, 0, 'Green Lantern', 1, 1, 'cards', 'assets/portal/img/demo/img2.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 0, 0, 'Superman', 1, 1, 'cards', 'assets/portal/img/demo/img4.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 0, 0, 'The Flash', 1, 0, 'cards', 'assets/portal/img/demo/img3.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 0, 0, 'The Lab', 1, 0, 'cards', 'assets/portal/img/demo/img7.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 0, 0, 'Wild RIDE', 1, 1, 'cards', 'assets/portal/img/demo/img10.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 0, 0, 'Wolf PACK', 1, 1, 'cards', 'assets/portal/img/demo/img9.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 0, 0, 'Wonder Women', 1, 0, 'cards', 'assets/portal/img/demo/img1.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(10, 0, 0, 'Champions Goal', 1, 0, 'cards', 'assets/portal/img/demo/img5.jpg', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `casino_game_types`
--

DROP TABLE IF EXISTS `casino_game_types`;
CREATE TABLE IF NOT EXISTS `casino_game_types` (
  `id` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `css_icon` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `available` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `casino_game_types`
--

INSERT INTO `casino_game_types` (`id`, `name`, `css_icon`, `available`, `position`, `created_at`, `updated_at`) VALUES
('all', 'Todos', 'fa-check-circle', 1, 0, '2016-02-09 07:00:00', '2016-02-09 07:00:00'),
('cards', 'Jogos de cartas', 'fa-clone', 1, 2, '2016-02-09 23:31:32', '2016-02-09 23:31:32'),
('featured', 'Em destaque', 'fa-asterisk', 1, 1, '2016-02-09 18:40:00', '2016-02-09 18:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `name` varchar(255) DEFAULT NULL,
  `name_eng` varchar(255) NOT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `cod_num` int(11) NOT NULL,
  `cod_alf2` varchar(2) NOT NULL,
  `cod_alf3` varchar(3) NOT NULL,
  PRIMARY KEY (`cod_alf2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`name`, `name_eng`, `nationality`, `cod_num`, `cod_alf2`, `cod_alf3`) VALUES
('Andorra', 'andorra, principality of', NULL, 20, 'AD', 'AND'),
('Emiratos arabes unidos', 'united arab emirates (was trucial states)', NULL, 784, 'AE', 'ARE'),
('Afeganistao', 'afghanistan', 'Afegão', 4, 'AF', 'AFG'),
('Antigua e barbuda', 'antigua and barbuda', 'Antiguano', 28, 'AG', 'ATG'),
('Anguila', 'anguilla', NULL, 660, 'AI', 'AIA'),
('Albania', 'albania, people''s socialist republic of', NULL, 8, 'AL', 'ALB'),
('Armenia', 'armenia', 'Armeno', 51, 'AM', 'ARM'),
('Antilhas holandesas', 'netherlands antilles', NULL, 530, 'AN', 'ANT'),
('Angola', 'angola, republic of', 'Angolano', 24, 'AO', 'AGO'),
('Antarctica', 'antarctica (the territory south of 60 deg s)', NULL, 10, 'AQ', 'ATA'),
('Argentina', 'argentina, argentine republic', 'Argentino', 32, 'AR', 'ARG'),
('Samoa americana', 'american samoa', NULL, 16, 'AS', 'ASM'),
('Austria', 'austria, republic of', 'Austríaco', 40, 'AT', 'AUT'),
('Australia', 'australia, commonwealth of', 'Australiano', 36, 'AU', 'AUS'),
('Aruba', 'aruba', NULL, 533, 'AW', 'ABW'),
('Ilhas aland', 'aland islands', NULL, 248, 'AX', 'ALA'),
('Azerbaijao', 'azerbaijan, republic of', NULL, 31, 'AZ', 'AZE'),
('Bosnia e herzegovina', 'bosnia and herzegovina', NULL, 70, 'BA', 'BIH'),
('Barbados', 'barbados', 'Barbadiano, barbadense', 52, 'BB', 'BRB'),
('Bangladesh', 'bangladesh, people''s republic of', NULL, 50, 'BD', 'BGD'),
('Belgica', 'belgium, kingdom of', 'Belga', 56, 'BE', 'BEL'),
('Burkina faso', 'burkina faso (was upper volta)', NULL, 854, 'BF', 'BFA'),
('Bulgaria', 'bulgaria, people''s republic of', NULL, 100, 'BG', 'BGR'),
('Barem', 'bahrain, kingdom of', NULL, 48, 'BH', 'BHR'),
('Burundi', 'burundi, republic of', NULL, 108, 'BI', 'BDI'),
('Benin', 'benin (was dahomey), people''s republic of', NULL, 204, 'BJ', 'BEN'),
('Bermuda', 'bermuda', NULL, 60, 'BM', 'BMU'),
('Brunei darussalam', 'brunei darussalam', NULL, 96, 'BN', 'BRN'),
('Bolivia', 'bolivia, republic of', 'Boliviano', 68, 'BO', 'BOL'),
('Brasil', 'brazil, federative republic of', 'Brasileiro', 76, 'BR', 'BRA'),
('Bahamas', 'bahamas, commonwealth of the', 'Bahamense', 44, 'BS', 'BHS'),
('Butao', 'bhutan, kingdom of', NULL, 64, 'BT', 'BTN'),
('Ilhas bouvet', 'bouvet island (bouvetoya)', NULL, 74, 'BV', 'BVT'),
('Botswana', 'botswana, republic of', NULL, 72, 'BW', 'BWA'),
('Bielorrussia', 'belarus', NULL, 112, 'BY', 'BLR'),
('Belize', 'belize', 'Belizenho', 84, 'BZ', 'BLZ'),
('Canada', 'canada', 'Canadense', 124, 'CA', 'CAN'),
('Ilhas cocos (keeling)', 'cocos (keeling) islands', NULL, 166, 'CC', 'CCK'),
('Congo (republica democratica do)', 'congo, democratic republic of (was zaire)', NULL, 180, 'CD', 'COD'),
('Centro-africana (republica)', 'central african republic', NULL, 140, 'CF', 'CAF'),
('Congo', 'congo, people''s republic of', NULL, 178, 'CG', 'COG'),
('Suica', 'switzerland, swiss confederation', 'Suíço', 756, 'CH', 'CHE'),
('Costa do marfim', 'cote d''ivoire, ivory coast, republic of the', 'Marfinense', 384, 'CI', 'CIV'),
('Ilhas cook', 'cook islands', NULL, 184, 'CK', 'COK'),
('Chile', 'chile, republic of', 'Chileno', 152, 'CL', 'CHL'),
('Camaroes', 'cameroon, united republic of', 'Camaronense', 120, 'CM', 'CMR'),
('China', 'china, people''s republic of', 'Chinês', 156, 'CN', 'CHN'),
('Colombia', 'colombia, republic of', 'Colombiano', 170, 'CO', 'COL'),
('Costa rica', 'costa rica, republic of', 'Costarriquenho', 188, 'CR', 'CRI'),
('Servia e montenegro', 'serbia and montenegro (was yugoslavia) (até 2006-09-25)', NULL, 891, 'CS', 'SCG'),
('Cuba', 'cuba, republic of', 'Cubano', 192, 'CU', 'CUB'),
('Cabo verde', 'cape verde, republic of', NULL, 132, 'CV', 'CPV'),
('Ilhas christmas', 'christmas island', NULL, 162, 'CX', 'CXR'),
('Chipre', 'cyprus, republic of', NULL, 196, 'CY', 'CYP'),
('Republica checa', 'czech republic', NULL, 203, 'CZ', 'CZE'),
('Alemanha', 'germany', 'Alemão', 276, 'DE', 'DEU'),
('Jibuti', 'djibouti, republic of (was french afars and issas)', NULL, 262, 'DJ', 'DJI'),
('Dinamarca', 'denmark, kingdom of', 'Dinamarquês', 208, 'DK', 'DNK'),
('Dominica', 'dominica, commonwealth of', 'Dominicano', 212, 'DM', 'DMA'),
('Republica dominicana', 'dominican republic', 'Dominicana', 214, 'DO', 'DOM'),
('Argelia', 'algeria, people''s democratic republic of', 'Argélia', 12, 'DZ', 'DZA'),
('Equador', 'ecuador, republic of', 'Equatoriano', 218, 'EC', 'ECU'),
('Estonia', 'estonia', NULL, 233, 'EE', 'EST'),
('Egipto', 'egypt, arab republic of', NULL, 818, 'EG', 'EGY'),
('Sara ocidental', 'western sahara (was spanish sahara)', NULL, 732, 'EH', 'ESH'),
('Eritreia', 'eritrea', NULL, 232, 'ER', 'ERI'),
('Espanha', 'spain, spanish state', 'Espanhol', 724, 'ES', 'ESP'),
('Etiopia', 'ethiopia', NULL, 231, 'ET', 'ETH'),
('Finlandia', 'finland, republic of', NULL, 246, 'FI', 'FIN'),
('Ilhas fiji', 'fiji, republic of the fiji islands', NULL, 242, 'FJ', 'FJI'),
('Ilhas falkland (malvinas)', 'falkland islands (malvinas)', NULL, 238, 'FK', 'FLK'),
('Micronesia (estados federados da)', 'micronesia, federated states of', NULL, 583, 'FM', 'FSM'),
('Ilhas faroe', 'faeroe islands', NULL, 234, 'FO', 'FRO'),
('Franca', 'france, french republic', 'Francês', 250, 'FR', 'FRA'),
('Gabao', 'gabon, gabonese republic', NULL, 266, 'GA', 'GAB'),
('Reino unido', 'united kingdom of great britain & n. ireland', 'Britânico', 826, 'GB', 'GBR'),
('Granada', 'grenada', 'Granadino', 308, 'GD', 'GRD'),
('Georgia', 'georgia', NULL, 268, 'GE', 'GEO'),
('Guiana francesa', 'french guiana', 'Guianense', 254, 'GF', 'GUF'),
('Gernsey', 'gernsey', NULL, 831, 'GG', 'GGY'),
('Gana', 'ghana, republic of', 'Ganés', 288, 'GH', 'GHA'),
('Gibraltar', 'gibraltar', NULL, 292, 'GI', 'GIB'),
('Gronelandia', 'greenland', NULL, 304, 'GL', 'GRL'),
('Gambia', 'gambia, republic of the', NULL, 270, 'GM', 'GMB'),
('Guine', 'guinea, revolutionary people''s rep''c of', NULL, 324, 'GN', 'GIN'),
('Guadalupe', 'guadaloupe', NULL, 312, 'GP', 'GLP'),
('Guine equatorial', 'equatorial guinea, republic of', NULL, 226, 'GQ', 'GNQ'),
('Grecia', 'greece, hellenic republic', 'Grego', 300, 'GR', 'GRC'),
('Georgia do sul e ilhas sandwich', 'south georgia and the south sandwich islands', NULL, 239, 'GS', 'SGS'),
('Guatemala', 'guatemala, republic of', 'Guatemalteco', 320, 'GT', 'GTM'),
('Guam', 'guam', NULL, 316, 'GU', 'GUM'),
('Guine-bissau', 'guinea-bissau, republic of (was portuguese guinea)', NULL, 624, 'GW', 'GNB'),
('Guiana', 'guyana, republic of', 'Guianês', 328, 'GY', 'GUY'),
('Hong', 'kong hong kong, special administrative region of china', NULL, 344, 'HK', 'HKG'),
('Ilhas heard e ilhas mcdonald', 'heard and mcdonald islands', NULL, 334, 'HM', 'HMD'),
('Honduras', 'honduras, republic of', 'Hondurenho', 340, 'HN', 'HND'),
('Croacia', 'hrvatska (croatia)', 'Croata', 191, 'HR', 'HRV'),
('Haiti', 'haiti, republic of', 'Haitiano', 332, 'HT', 'HTI'),
('Hungria', 'hungary, hungarian people''s republic', 'Húngaro', 348, 'HU', 'HUN'),
('Indonesia', 'indonesia, republic of', 'Indonésio', 360, 'ID', 'IDN'),
('Irlanda', 'ireland', 'Irlandês', 372, 'IE', 'IRL'),
('Israel', 'israel, state of', 'Israelita', 376, 'IL', 'ISR'),
('Ilha de man', 'isle of man', NULL, 833, 'IM', 'IMN'),
('India', 'india, republic of', 'Indiano', 356, 'IN', 'IND'),
('Territorio britanico do oceano indico', 'british indian ocean territory (chagos archipelago)', NULL, 86, 'IO', 'IOT'),
('Iraque', 'iraq, republic of', 'Iraquiano', 368, 'IQ', 'IRQ'),
('Irao (republica islamica)', 'iran, islamic republic of', NULL, 364, 'IR', 'IRN'),
('Islandia', 'iceland, republic of', NULL, 352, 'IS', 'ISL'),
('Italia', 'italy, italian republic', 'Italiano', 380, 'IT', 'ITA'),
('Jersey', 'jersey (a partir de 2006-03-29)', NULL, 832, 'JE', 'JEY'),
('Jamaica', 'jamaica', 'Jamaicano', 388, 'JM', 'JAM'),
('Jordania', 'jordan, hashemite kingdom of', NULL, 400, 'JO', 'JOR'),
('Japao', 'japan', 'Japonês', 392, 'JP', 'JPN'),
('Kenya', 'kenya, republic of', NULL, 404, 'KE', 'KEN'),
('Quirguizistao', 'kyrgyz republic', NULL, 417, 'KG', 'KGZ'),
('Camboja', 'cambodia, kingdom of (was khmer republic/kampuchea)', NULL, 116, 'KH', 'KHM'),
('Kiribati', 'kiribati, republic of (was gilbert islands)', NULL, 296, 'KI', 'KIR'),
('Comores', 'comoros, federal and islamic republic of', 'Comorense', 174, 'KM', 'COM'),
('Sao cristovao e nevis', 'st. kitts and nevis', 'São-cristovense', 659, 'KN', 'KNA'),
('Coreia (republica popular democratica da)', 'korea, democratic people''s republic of', NULL, 408, 'KP', 'PRK'),
('Coreia (republica da)', 'korea, republic of', NULL, 410, 'KR', 'KOR'),
('Kuwait', 'kuwait, state of', NULL, 414, 'KW', 'KWT'),
('Ilhas caimao', 'cayman islands', NULL, 136, 'KY', 'CYM'),
('Cazaquistao', 'kazakhstan, republic of', NULL, 398, 'KZ', 'KAZ'),
('Laos (republica popular democratica do)', 'lao people''s democratic republic', NULL, 418, 'LA', 'LAO'),
('Libano', 'lebanon, lebanese republic', NULL, 422, 'LB', 'LBN'),
('Santa lucia', 'st. lucia', 'Santa-lucense', 662, 'LC', 'LCA'),
('Liechtenstein', 'liechtenstein, principality of', NULL, 438, 'LI', 'LIE'),
('Sri lanka', 'sri lanka, democratic socialist republic of (was ceylon)', 'Cingalês', 144, 'LK', 'LKA'),
('Liberia', 'liberia, republic of', NULL, 430, 'LR', 'LBR'),
('Lesoto', 'lesotho, kingdom of', NULL, 426, 'LS', 'LSO'),
('Lituania', 'lithuania', NULL, 440, 'LT', 'LTU'),
('Luxemburgo', 'luxembourg, grand duchy of', NULL, 442, 'LU', 'LUX'),
('Letonia', 'latvia', NULL, 428, 'LV', 'LVA'),
('Libia (jamahiriya arabe da)', 'libyan arab jamahiriya', NULL, 434, 'LY', 'LBY'),
('Marrocos', 'morocco, kingdom of', 'Marroquino', 504, 'MA', 'MAR'),
('Monaco', 'monaco, principality of', NULL, 492, 'MC', 'MCO'),
('Moldova (republica de)', 'moldova, republic of', NULL, 498, 'MD', 'MDA'),
('Montenegro (republica de)', 'montenegro, republic of (a partir de 2006-09-26)', NULL, 499, 'ME', 'MNE'),
('Madagascar', 'madagascar, republic of', NULL, 450, 'MG', 'MDG'),
('Ilhas marshall', 'marshall islands', NULL, 584, 'MH', 'MHL'),
('Macedonia (antiga republica jugoslava da)', 'macedonia, the former yugoslav republic of', NULL, 807, 'MK', 'MKD'),
('Mali', 'mali, republic of', NULL, 466, 'ML', 'MLI'),
('Myanmar', 'myanmar (was burma)', NULL, 104, 'MM', 'MMR'),
('Mongolia', 'mongolia, mongolian people''s republic', NULL, 496, 'MN', 'MNG'),
('Macau', 'macao, special administrative region of china', NULL, 446, 'MO', 'MAC'),
('Ilhas marianas do norte', 'northern mariana islands', NULL, 580, 'MP', 'MNP'),
('Martinica', 'martinique', NULL, 474, 'MQ', 'MTQ'),
('Mauritania', 'mauritania, islamic republic of', NULL, 478, 'MR', 'MRT'),
('Monserrate', 'montserrat', NULL, 500, 'MS', 'MSR'),
('Malta', 'malta, republic of', NULL, 470, 'MT', 'MLT'),
('Mauricias', 'mauritius', NULL, 480, 'MU', 'MUS'),
('Maldivas', 'maldives, republic of', NULL, 462, 'MV', 'MDV'),
('Malawi', 'malawi, republic of', NULL, 454, 'MW', 'MWI'),
('Mexico', 'mexico, united mexican states', 'Mexicano', 484, 'MX', 'MEX'),
('Malasia', 'malaysia', 'Malaio', 458, 'MY', 'MYS'),
('Mocambique', 'mozambique, people''s republic of', 'Moçambicano', 508, 'MZ', 'MOZ'),
('Namibia', 'namibia', NULL, 516, 'NA', 'NAM'),
('Nova caledonia', 'new caledonia', NULL, 540, 'NC', 'NCL'),
('Niger', 'niger, republic of the', NULL, 562, 'NE', 'NER'),
('Ilhas norfolk', 'norfolk island', NULL, 574, 'NF', 'NFK'),
('Nigeria', 'nigeria, federal republic of', 'Nigeriano', 566, 'NG', 'NGA'),
('Nicaragua', 'nicaragua, republic of', 'Nicaraguense', 558, 'NI', 'NIC'),
('Paises baixos', 'netherlands, kingdom of the', 'Holandês', 528, 'NL', 'NLD'),
('Noruega', 'norway, kingdom of', 'Noruego', 578, 'NO', 'NOR'),
('Nepal', 'nepal, kingdom of', 'Nepalês', 524, 'NP', 'NPL'),
('Nauru', 'nauru, republic of', NULL, 520, 'NR', 'NRU'),
('Niue', 'niue, republic of', NULL, 570, 'NU', 'NIU'),
('Nova zelandia', 'new zealand', 'Neozelandês', 554, 'NZ', 'NZL'),
('Oma', 'oman, sultanate of (was muscat and oman)', 'Omanense', 512, 'OM', 'OMN'),
('Panama', 'panama, republic of', 'Panamenho', 591, 'PA', 'PAN'),
('Peru', 'peru, republic of', 'Peruano', 604, 'PE', 'PER'),
('Polinesia francesa', 'french polynesia', NULL, 258, 'PF', 'PYF'),
('Papuasia-nova guine', 'papua new guinea', NULL, 598, 'PG', 'PNG'),
('Filipinas', 'philippines, republic of the', NULL, 608, 'PH', 'PHL'),
('Paquistao', 'pakistan, islamic republic of', 'Paquistanês', 586, 'PK', 'PAK'),
('Polonia', 'poland, polish people''s republic', 'Polonês', 616, 'PL', 'POL'),
('Sao pedro e miquelon', 'st. pierre and miquelon', NULL, 666, 'PM', 'SPM'),
('Pitcairn', 'pitcairn island', NULL, 612, 'PN', 'PCN'),
('Porto rico', 'puerto rico', 'Portorriquenho', 630, 'PR', 'PRI'),
('Territorio palestiniano ocupado', 'palestinian territory, occupied', NULL, 275, 'PS', 'PSE'),
('Portugal', 'portugal, portuguese republic', 'Português', 620, 'PT', 'PRT'),
('Palau', 'palau', NULL, 585, 'PW', 'PLW'),
('Paraguai', 'paraguay, republic of', 'Paraguaio', 600, 'PY', 'PRY'),
('Catar', 'qatar, state of', NULL, 634, 'QA', 'QAT'),
('Reuniao', 'reunion', NULL, 638, 'RE', 'REU'),
('Romenia', 'romania, socialist republic of', 'Romeno', 642, 'RO', 'ROU'),
('Servia (republica da)', 'serbia, republic of (a partir de 2006-09-26)', NULL, 688, 'RS', 'SRB'),
('Russia (federacao da)', 'russian federation', NULL, 643, 'RU', 'RUS'),
('Ruanda', 'rwanda, rwandese republic', 'Ruandês', 646, 'RW', 'RWA'),
('Arabia saudita', 'saudi arabia, kingdom of', 'Saudita', 682, 'SA', 'SAU'),
('Ilhas salomao', 'solomon islands (was british solomon islands)', NULL, 90, 'SB', 'SLB'),
('Seychelles', 'seychelles, republic of', NULL, 690, 'SC', 'SYC'),
('Sudao', 'sudan, democratic republic of the', NULL, 736, 'SD', 'SDN'),
('Suecia', 'sweden, kingdom of', 'Sueco', 752, 'SE', 'SWE'),
('Singapura', 'singapore, republic of', NULL, 702, 'SG', 'SGP'),
('Santa helena', 'st. helena', NULL, 654, 'SH', 'SHN'),
('Eslovenia', 'slovenia', 'Esloveno', 705, 'SI', 'SVN'),
('Svalbard e a ilha de jan mayen', 'svalbard & jan mayen islands', NULL, 744, 'SJ', 'SJM'),
('Eslovaca (republica)', 'slovakia (slovak republic)', NULL, 703, 'SK', 'SVK'),
('Serra leoa', 'sierra leone, republic of', NULL, 694, 'SL', 'SLE'),
('Sao marino', 'san marino, republic of', NULL, 674, 'SM', 'SMR'),
('Senegal', 'senegal, republic of', NULL, 686, 'SN', 'SEN'),
('Somalia', 'somalia, somali republic', 'Somali', 706, 'SO', 'SOM'),
('Suriname', 'suriname, republic of', 'Surinamês', 740, 'SR', 'SUR'),
('Sao tome e principe', 'sao tome and principe, democratic republic of', NULL, 678, 'ST', 'STP'),
('El salvador', 'el salvador, republic of', 'Salvadorenho', 222, 'SV', 'SLV'),
('Siria (republica arabe da)', 'syrian arab republic', NULL, 760, 'SY', 'SYR'),
('Suazilandia', 'swaziland, kingdom of', NULL, 748, 'SZ', 'SWZ'),
('Turcos e caicos (ilhas)', 'turks and caicos islands', NULL, 796, 'TC', 'TCA'),
('Chade', 'chad, republic of', NULL, 148, 'TD', 'TCD'),
('Territorios franceses do sul', 'french southern territories', NULL, 260, 'TF', 'ATF'),
('Togo', 'togo, togolese republic', NULL, 768, 'TG', 'TGO'),
('Tailandia', 'thailand, kingdom of', 'Tailandês', 764, 'TH', 'THA'),
('Tajiquistao', 'tajikistan', NULL, 762, 'TJ', 'TJK'),
('Tokelau', 'tokelau (tokelau islands)', NULL, 772, 'TK', 'TKL'),
('Timor leste', 'east timor, democratic republic of', NULL, 626, 'TL', 'TLS'),
('Turquemenistao', 'turkmenistan', NULL, 795, 'TM', 'TKM'),
('Tunisia', 'tunisia, republic of', NULL, 788, 'TN', 'TUN'),
('Tonga', 'tonga, kingdom of', NULL, 776, 'TO', 'TON'),
('Turquia', 'turkey, republic of', 'Turco', 792, 'TR', 'TUR'),
('Trindade e tobago', 'trinidad and tobago, republic of', NULL, 780, 'TT', 'TTO'),
('Tuvalu', 'tuvalu (was part of gilbert & ellice islands)', NULL, 798, 'TV', 'TUV'),
('Taiwan (provincia da china)', 'taiwan, province of china', NULL, 158, 'TW', 'TWN'),
('Tanzania, republica unida da', 'tanzania, united republic of', NULL, 834, 'TZ', 'TZA'),
('Ucrania', 'ukraine', 'Ucraniano', 804, 'UA', 'UKR'),
('Uganda', 'uganda, republic of', 'Ugandense', 800, 'UG', 'UGA'),
('Ilhas menores distantes dos estados unidos united', 'states minor outlying islands', NULL, 581, 'UM', 'UMI'),
('Estados unidos', 'united states of america', 'Americano', 840, 'US', 'USA'),
('Uruguai', 'uruguay, eastern republic of', 'Uruguaio', 858, 'UY', 'URY'),
('Usbequistao', 'uzbekistan', NULL, 860, 'UZ', 'UZB'),
('Santa se (cidade estado do vaticano)', 'holy see (vatican city state)', NULL, 336, 'VA', 'VAT'),
('Sao vicente e granadinas', 'st. vincent and the grenadines', 'São-vicentino', 670, 'VC', 'VCT'),
('Venezuela', 'venezuela, bolivarian republic of', 'Venezuelano', 862, 'VE', 'VEN'),
('Ilhas virgens (britanicas)', 'british virgin islands', NULL, 92, 'VG', 'VGB'),
('Ilhas virgens (estados unidos)', 'us virgin islands', NULL, 850, 'VI', 'VIR'),
('Vietname', 'viet nam, socialist republic of (was democratic republic of)', NULL, 704, 'VN', 'VNM'),
('Vanuatu', 'vanuatu (was new hebrides)', NULL, 548, 'VU', 'VUT'),
('Wallis e futuna (ilhas)', 'wallis and futuna islands', NULL, 876, 'WF', 'WLF'),
('Samoa', 'samoa, independent state of (was western samoa)', NULL, 882, 'WS', 'WSM'),
('Iemen', 'yemen', 'Iemenita', 887, 'YE', 'YEM'),
('Mayotte', 'mayotte', NULL, 175, 'YT', 'MYT'),
('Africa do sul', 'south africa, republic of', 'Sul-africano', 710, 'ZA', 'ZAF'),
('Zambia', 'zambia, republic of', NULL, 894, 'ZM', 'ZMB'),
('Zimbabwe', 'zimbabwe (was southern rhodesia)', NULL, 716, 'ZW', 'ZWE'),
('Ilhas do canal', 'channel islands', NULL, 830, '__', '___');

-- --------------------------------------------------------

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
CREATE TABLE IF NOT EXISTS `document_types` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `code` varchar(1) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `document_types`
--

INSERT INTO `document_types` (`id`, `name`, `code`) VALUES
('bilhete_identidade', 'Bilhete de Identidade', 'B'),
('cartao_cidadao', 'Cartão de Cidadão', 'C'),
('comprovativo_iban', 'Comprovativo de Iban', ''),
('comprovativo_identidade', 'Comprovativo de Identidade', ''),
('comprovativo_morada', 'Comprovativo de Morada', ''),
('passaporte', 'Passaporte', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `global_settings`
--

DROP TABLE IF EXISTS `global_settings`;
CREATE TABLE IF NOT EXISTS `global_settings` (
  `id` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `value` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `global_settings`
--

INSERT INTO `global_settings` (`id`, `value`, `description`, `created_at`, `updated_at`) VALUES
('bet_tax_rate', '0.08', 'A taxa cobrada de momento deve ser de 8% sobre os lucros.', '2016-02-08 23:00:00', '2016-02-08 23:00:00'),
('lower_bet_limit', '2', 'Limite inferior da aposta.', '2016-02-16 23:16:03', '2016-02-16 23:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `limit_types`
--

DROP TABLE IF EXISTS `limit_types`;
CREATE TABLE IF NOT EXISTS `limit_types` (
  `id` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `limit_type` varchar(10) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `limit_types`
--

INSERT INTO `limit_types` (`id`, `name`, `limit_type`, `priority`) VALUES
('limit_betting_daily', 'Limite Diário', 'betting', 4),
('limit_betting_monthly', 'Limite Mensal', 'betting', 6),
('limit_betting_weekly', 'Limite Semanal', 'betting', 5),
('limit_deposit_daily', 'Limite Diário', 'deposit', 1),
('limit_deposit_monthly', 'Limite Mensal', 'deposit', 3),
('limit_deposit_weekly', 'Limite Semanal', 'deposit', 2);

-- --------------------------------------------------------

--
-- Table structure for table `list_identity_checks`
--

DROP TABLE IF EXISTS `list_identity_checks`;
CREATE TABLE IF NOT EXISTS `list_identity_checks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `tax_number` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `birth_date` datetime NOT NULL,
  `deceased` tinyint(4) NOT NULL,
  `under_age` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `list_self_exclusions`
--

DROP TABLE IF EXISTS `list_self_exclusions`;
CREATE TABLE IF NOT EXISTS `list_self_exclusions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_number` varchar(15) COLLATE utf8_general_ci NOT NULL,
  `document_type_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `list_self_exclusions_document_type_id_foreign` (`document_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` text NOT NULL,
  `log` text NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2015_10_02_092958_create_initial_schema', 1),
('2015_11_10_120239_create_api_request_logs', 1),
('2015_11_17_114512_create_user_bet_statuses', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `desc` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `grupo` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `desc`, `grupo`, `created_at`, `updated_at`) VALUES
('bonus.create', 'Add bonus', 'bonus', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('bonus.edit', 'Edit bonus', 'bonus', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('bonus.list', 'List bonuses', 'bonus', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('customers.columnvis', 'Customers Table Column Visibility', 'Customers', '2015-12-01 05:16:30', '2015-12-01 05:16:30'),
('customers.confirm-docs', 'Confirm Customer Documents', 'Customers', '2016-03-31 21:39:51', '2016-03-31 21:39:51'),
('customers.create', 'Create Customer', 'Customers', '2015-12-01 03:56:54', '2015-12-01 03:56:54'),
('customers.delete', 'Remove Customer', 'Customers', '2015-12-01 03:56:54', '2015-12-01 03:56:54'),
('customers.edit', 'Edit Customer', 'Customers', '2015-12-01 03:53:02', '2015-12-01 03:53:02'),
('customers.list', 'List Customers', 'Customers', '2015-11-30 07:00:00', '2015-11-30 07:00:00'),
('customers.tableaction', 'Customers Table Action', 'Customers', '2015-12-01 05:07:33', '2015-12-01 05:07:33'),
('customers.view', 'View Customer', 'Customers', '2016-01-16 21:39:51', '2016-01-16 21:39:51'),
('financialtransactions.admin-financial-moves.add', 'Add Admin Financial Moves', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('financialtransactions.admin-financial-moves.list', 'List Admin Financial Moves', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('financialtransactions.deposits.list', 'List Deposits', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('financialtransactions.withdrawals.list.approved', 'List Withdrawals Approved', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('financialtransactions.withdrawals.list.declined', 'List Withdrawals Declined', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('financialtransactions.withdrawals.list.delayed', 'List Withdrawals Delayed', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('financialtransactions.withdrawals.list.pending', 'List Withdrawals Pending', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('financialtransactions.withdrawals.list.processed', 'List Withdrawals Processed', 'Financial Transactions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('staff.create', 'Create Staff', 'Staff', '2015-12-01 03:56:54', '2015-12-01 03:56:54'),
('staff.delete', 'Remove Staff', 'Staff', '2015-12-01 03:56:54', '2015-12-01 03:56:54'),
('staff.edit', 'Edit Staff', 'Staff', '2015-12-01 03:53:02', '2015-12-01 03:53:02'),
('staff.list', 'List Staff', 'Staff', '2015-11-30 07:00:00', '2015-11-30 07:00:00'),
('staff.tableaction', 'Staff Table Action', 'Staff', '2015-12-01 05:07:33', '2015-12-01 05:07:33'),
('stafftypes.create', 'Create Staff Type', 'Staff Types', '2015-12-01 03:56:54', '2015-12-01 03:56:54'),
('stafftypes.delete', 'Remove Staff Type', 'Staff Types', '2015-12-01 03:56:54', '2015-12-01 03:56:54'),
('stafftypes.edit', 'Edit Staff Type', 'Staff Types', '2015-12-01 03:53:02', '2015-12-01 03:53:02'),
('stafftypes.list', 'List Staff Types', 'Staff Types', '2015-12-15 06:14:15', '2015-12-15 06:14:15'),
('stafftypes.tableaction', 'Staff Type Table Action', 'Staff Types', '2015-12-01 05:07:33', '2015-12-01 05:07:33'),
('definitions.edit', 'Edit Definitions', 'Definitions', '2016-05-22 23:00:00', '2016-05-22 23:00:00'),
('definitions.legal_docs', 'Legal Docs', 'Definitions', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('definitions.view', 'View Definitions', 'Definitions', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `permission_staff`
--

DROP TABLE IF EXISTS `permission_staff`;
CREATE TABLE IF NOT EXISTS `permission_staff` (
  `permission_id` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `staff_id` int(11) NOT NULL,
  PRIMARY KEY (`permission_id`,`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `permission_staff`
--

INSERT INTO `permission_staff` (`permission_id`, `staff_id`) VALUES
('bonus.create', 1),
('bonus.create', 2),
('bonus.create', 3),
('bonus.edit', 1),
('bonus.edit', 2),
('bonus.edit', 3),
('bonus.list', 1),
('bonus.list', 2),
('bonus.list', 3),
('customers.columnvis', 1),
('customers.columnvis', 2),
('customers.columnvis', 3),
('customers.confirm-docs', 1),
('customers.create', 1),
('customers.create', 2),
('customers.create', 3),
('customers.delete', 1),
('customers.delete', 2),
('customers.delete', 3),
('customers.edit', 1),
('customers.edit', 2),
('customers.edit', 3),
('customers.list', 1),
('customers.list', 2),
('customers.list', 3),
('customers.tableaction', 1),
('customers.tableaction', 2),
('customers.tableaction', 3),
('customers.view', 1),
('customers.view', 2),
('customers.view', 3),
('financialtransactions.admin-financial-moves.add', 1),
('financialtransactions.admin-financial-moves.add', 2),
('financialtransactions.admin-financial-moves.list', 1),
('financialtransactions.admin-financial-moves.list', 2),
('financialtransactions.deposits.list', 1),
('financialtransactions.deposits.list', 2),
('financialtransactions.deposits.list', 3),
('financialtransactions.withdrawals.list.approved', 1),
('financialtransactions.withdrawals.list.approved', 2),
('financialtransactions.withdrawals.list.approved', 3),
('financialtransactions.withdrawals.list.declined', 1),
('financialtransactions.withdrawals.list.declined', 2),
('financialtransactions.withdrawals.list.declined', 3),
('financialtransactions.withdrawals.list.delayed', 1),
('financialtransactions.withdrawals.list.delayed', 2),
('financialtransactions.withdrawals.list.delayed', 3),
('financialtransactions.withdrawals.list.pending', 1),
('financialtransactions.withdrawals.list.pending', 2),
('financialtransactions.withdrawals.list.pending', 3),
('financialtransactions.withdrawals.list.processed', 1),
('financialtransactions.withdrawals.list.processed', 2),
('financialtransactions.withdrawals.list.processed', 3),
('staff.create', 1),
('staff.create', 2),
('staff.create', 3),
('staff.delete', 1),
('staff.delete', 2),
('staff.delete', 3),
('staff.edit', 1),
('staff.edit', 2),
('staff.edit', 3),
('staff.list', 1),
('staff.list', 2),
('staff.list', 3),
('staff.tableaction', 1),
('staff.tableaction', 2),
('staff.tableaction', 3),
('stafftypes.create', 1),
('stafftypes.create', 2),
('stafftypes.create', 3),
('stafftypes.delete', 1),
('stafftypes.delete', 2),
('stafftypes.delete', 3),
('stafftypes.edit', 1),
('stafftypes.edit', 2),
('stafftypes.edit', 3),
('stafftypes.list', 1),
('stafftypes.list', 2),
('stafftypes.list', 3),
('stafftypes.tableaction', 1),
('stafftypes.tableaction', 2),
('stafftypes.tableaction', 3);

-- --------------------------------------------------------

--
-- Table structure for table `permission_stafftype`
--

DROP TABLE IF EXISTS `permission_stafftype`;
CREATE TABLE IF NOT EXISTS `permission_stafftype` (
  `stafftype_id` int(11) NOT NULL,
  `permission_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`stafftype_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `permission_stafftype`
--

INSERT INTO `permission_stafftype` (`stafftype_id`, `permission_id`) VALUES
(1, 'bonus.create'),
(1, 'bonus.edit'),
(1, 'bonus.list'),
(1, 'customers.columnvis'),
(1, 'customers.confirm-docs'),
(1, 'customers.create'),
(1, 'customers.delete'),
(1, 'customers.edit'),
(1, 'customers.list'),
(1, 'customers.tableaction'),
(1, 'customers.view'),
(1, 'financialtransactions.deposits.list'),
(1, 'financialtransactions.withdrawals.list.declined'),
(1, 'financialtransactions.withdrawals.list.delayed'),
(1, 'financialtransactions.withdrawals.list.pending'),
(1, 'financialtransactions.withdrawals.list.processed'),
(1, 'staff.create'),
(1, 'staff.delete'),
(1, 'staff.edit'),
(1, 'staff.list'),
(1, 'staff.tableaction'),
(1, 'stafftypes.create'),
(1, 'stafftypes.delete'),
(1, 'stafftypes.edit'),
(1, 'stafftypes.list'),
(1, 'stafftypes.tableaction');

-- --------------------------------------------------------

--
-- Table structure for table `professional_situation`
--

DROP TABLE IF EXISTS `professional_situation`;
CREATE TABLE IF NOT EXISTS `professional_situation` (
  `id` varchar(2) NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `professional_situation`
--

INSERT INTO `professional_situation` (`id`, `name`) VALUES
('11', 'Trabalhador por conta própria'),
('22', 'Trabalhador por conta de outrem'),
('33', 'Profissional liberal'),
('44', 'Estudante'),
('55', 'Reformado'),
('66', 'Estagiário'),
('77', 'Sem atividade profissional'),
('88', 'Desempregado'),
('99', 'Outra');

-- --------------------------------------------------------

--
-- Table structure for table `self_exclusion_types`
--

DROP TABLE IF EXISTS `self_exclusion_types`;
CREATE TABLE IF NOT EXISTS `self_exclusion_types` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `self_exclusion_types`
--

INSERT INTO `self_exclusion_types` (`id`, `name`, `priority`) VALUES
('1year_period', 'Auto-Exclusão por 1 ano', 4),
('3months_period', 'Auto-Exclusão por 3 meses', 3),
('minimum_period', 'Auto-Exclusão durante:', 1),
('reflection_period', 'Prazo de Reflexão durante:', 2),
('undetermined_period', 'Auto-Exclusão por periodo indeterminado', 5);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `form_type` varchar(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `form_type`) VALUES
('chat', 'Chat', 'checkbox'),
('correio', 'Correio', 'checkbox'),
('email', 'Email', 'checkbox'),
('newsletter', 'Newsletter', 'checkbox'),
('sms', 'SMS', 'checkbox'),
('telefone', 'Telefone', 'checkbox');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `email` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_general_ci DEFAULT NULL,
  `code` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `stafftype_id` int(11) NOT NULL,
  `dt_nasc` date NOT NULL,
  `telemovel` varchar(15) COLLATE utf8_general_ci NOT NULL,
  `skype` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `morada` text COLLATE utf8_general_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `photo` varchar(45) COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8_general_ci DEFAULT 'active',
  `staff_session_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_username_unique` (`username`),
  UNIQUE KEY `staff_email_unique` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=2;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `email`, `password`, `name`, `code`, `stafftype_id`, `dt_nasc`, `telemovel`, `skype`, `morada`, `remember_token`, `photo`, `status`, `staff_session_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'admin@ibetup.co.uk', '$10$WEDiUWjArJ0naoneKsan1.7x/LgVodFmuYoOzzVmgBBv0jqLz1jpa', 'Administrator', '', 1, '2016-03-04', '0', '-', '-', 'J0dKrX1TAmEjIhfwxaYvgaxFhJ7LA8uGK4RPqxA0d5ZB1ta5Gp9dTypKhAlN', NULL, 'active', 438, '0000-00-00 00:00:00', '2016-03-02 23:51:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stafftypes`
--

DROP TABLE IF EXISTS `stafftypes`;
CREATE TABLE IF NOT EXISTS `stafftypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `staff_session_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7;

--
-- Dumping data for table `stafftypes`
--

INSERT INTO `stafftypes` (`id`, `name`, `staff_session_id`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Finance', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Marketing', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'IT', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'HR', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Operations', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `staff_sessions`
--

DROP TABLE IF EXISTS `staff_sessions`;
CREATE TABLE IF NOT EXISTS `staff_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(10) unsigned NOT NULL,
  `session_number` int(11) NOT NULL,
  `session_id` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `staff_sessions_staff_id_foreign` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

DROP TABLE IF EXISTS `statuses`;
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `bo_name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`, `bo_name`) VALUES
('active', 'Ativa', 'Ativa'),
('pre-approved', 'Pre aprovado', 'Pre approved'),
('approved', 'Aprovado', 'Approved'),
('canceled', 'Cancelada', 'Canceled'),
('confirmed', 'Confirmada', 'Confirmada'),
('confirming_identity', 'Aguardar confirmação.', 'Aguardar confirmação.'),
('declined', 'Recusado', 'Declined'),
('delayed', 'Adiado', 'Delayed'),
('in_use', 'Em uso', 'Em uso'),
('inactive', 'Desativada', 'Desativada'),
('on_hold', 'A aguardar', 'On-Hold'),
('pending', 'pendente', 'Pending'),
('processed', 'Processado', 'Processed'),
('step_3', 'Registo - Step 3', 'Registo - Step 3'),
('suspended', 'Suspensa', 'Suspensa'),
('suspended_1_year', 'Suspensa 1 ano', 'Suspensa 1 ano'),
('suspended_3_months', 'Suspensa 3 meses', 'Suspensa 3 meses'),
('suspended_6_months', 'Suspensa 6 meses', 'Suspensa 6 meses'),
('waiting_confirmation', 'Aguardar confirmação', 'Aguardar confirmação'),
('waiting_document', 'Aguardar comprovativo', 'Aguardar comprovativo'),
('waiting_identity', 'A aguardar comprovativo', 'A aguardar comprovativo');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `name`) VALUES
('bank_transfer', 'Transferência Bancária'),
('payment_service', 'Pagamento de Serviços'),
('paypal', 'Paypal');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_general_ci NOT NULL,
  `security_pin` varchar(10) COLLATE utf8_general_ci NOT NULL,
  `identity_checked` tinyint(1) NOT NULL DEFAULT '0',
  `identity_method` varchar(50) COLLATE utf8_general_ci DEFAULT NULL,
  `identity_date` datetime NOT NULL,
  `user_code` varchar(40) COLLATE utf8_general_ci DEFAULT NULL,
  `promo_code` varchar(40) COLLATE utf8_general_ci DEFAULT NULL,
  `currency` varchar(10) COLLATE utf8_general_ci DEFAULT NULL,
  `user_role_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `api_token` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `api_password` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `rating_risk` varchar(20) COLLATE utf8_general_ci DEFAULT NULL,
  `rating_group` varchar(20) COLLATE utf8_general_ci DEFAULT NULL,
  `rating_type` varchar(20) COLLATE utf8_general_ci DEFAULT NULL,
  `rating_category` varchar(20) COLLATE utf8_general_ci DEFAULT NULL,
  `rating_class` varchar(20) COLLATE utf8_general_ci DEFAULT NULL,
  `rating_status` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `ggr_sb` decimal(15,2) DEFAULT '0.00',
  `ggr_casino` decimal(15,2) DEFAULT '0.00',
  `margin_sb` decimal(15,3) DEFAULT '0.000',
  `margin_casino` decimal(15,3) DEFAULT '0.000',
  `staff_id` int(10) DEFAULT NULL,
  `staff_session_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_code_unique` (`user_code`),
  KEY `users_user_role_id_foreign` (`user_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_balances`
--

DROP TABLE IF EXISTS `user_balances`;
CREATE TABLE IF NOT EXISTS `user_balances` (
  `user_id` int(10) unsigned NOT NULL,
  `balance_available` decimal(15,2) NOT NULL DEFAULT '0.00',
  `b_av_check` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_captive` decimal(15,2) NOT NULL DEFAULT '0.00',
  `b_ca_check` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_bonus` decimal(15,2) NOT NULL DEFAULT '0.00',
  `b_bo_check` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_accounting` decimal(15,2) NOT NULL DEFAULT '0.00',
  `b_ac_check` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `b_to_check` decimal(15,2) NOT NULL DEFAULT '0.00',
  `user_session_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`),
  KEY `user_balances_user_id_foreign` (`user_id`),
  KEY `user_balances_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_accounts`
--

DROP TABLE IF EXISTS `user_bank_accounts`;
CREATE TABLE IF NOT EXISTS `user_bank_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `bank_account` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `iban` varchar(25) COLLATE utf8_general_ci NOT NULL,
  `status_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `user_session_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_bank_accounts_user_id_foreign` (`user_id`),
  KEY `user_bank_accounts_user_session_id_foreign` (`user_session_id`),
  KEY `status_id` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_bets`
--

DROP TABLE IF EXISTS `user_bets`;
CREATE TABLE IF NOT EXISTS `user_bets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `api_bet_id` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `api_bet_type` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `api_transaction_id` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `api_withdrawal_id` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8_general_ci DEFAULT NULL,
  `initial_win_balance` decimal(15,2) NOT NULL,
  `initial_balance` decimal(15,2) NOT NULL,
  `final_balance` decimal(15,2) NOT NULL,
  `initial_bonus` decimal(15,2) NOT NULL,
  `final_bonus` decimal(15,2) NOT NULL,
  `result` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `result_amount` decimal(15,2) DEFAULT NULL,
  `result_tax` decimal(15,2) DEFAULT NULL,
  `sys_bet` varchar(10) COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(30) COLLATE utf8_general_ci DEFAULT NULL,
  `user_session_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_bets_user_id_foreign` (`user_id`),
  KEY `user_bets_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_bets_bc`
--

DROP TABLE IF EXISTS `user_bets_bc`;
CREATE TABLE IF NOT EXISTS `user_bets_bc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_bet_id` int(11) unsigned NOT NULL,
  `date_time` datetime NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `sysbet` int(11) NOT NULL,
  `bet_coeficient` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_bets_nyx`
--

DROP TABLE IF EXISTS `user_bets_nyx`;
CREATE TABLE IF NOT EXISTS `user_bets_nyx` (
  `id` int(11) unsigned NOT NULL,
  `user_bet_id` int(11) NOT NULL,
  `accountid` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `betamount` float NOT NULL,
  `device` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gamemodel` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gamesessionid` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `gametype` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gpgameid` varchar(128) COLLATE utf8_general_ci NOT NULL,
  `gpid` int(11) NOT NULL,
  `nogsgameid` int(11) NOT NULL,
  `product` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `roundid` bigint(20) NOT NULL,
  `transactionid` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_bet_bc_events`
--

DROP TABLE IF EXISTS `user_bet_bc_events`;
CREATE TABLE IF NOT EXISTS `user_bet_bc_events` (
  `id` int(11) unsigned NOT NULL,
  `user_bet_id` int(11) NOT NULL,
  `event_name` varchar(256) COLLATE utf8_general_ci NOT NULL,
  `market_name` varchar(256) COLLATE utf8_general_ci NOT NULL,
  `game_name` varchar(256) COLLATE utf8_general_ci NOT NULL,
  `competition_name` varchar(256) COLLATE utf8_general_ci NOT NULL,
  `sport_index` varchar(256) COLLATE utf8_general_ci NOT NULL,
  `coeficient` float NOT NULL,
  `basis` float NOT NULL,
  `game_start_date` datetime NOT NULL,
  `name-first-number` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `name-last-number` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `game_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_bet_statuses`
--

DROP TABLE IF EXISTS `user_bet_statuses`;
CREATE TABLE IF NOT EXISTS `user_bet_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_bet_id` int(10) unsigned NOT NULL,
  `status_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `current` tinyint(4) NOT NULL DEFAULT '1',
  `user_session_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_bet_statuses_user_bet_id_foreign` (`user_bet_id`),
  KEY `user_bet_statuses_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_bet_transactions`
--

DROP TABLE IF EXISTS `user_bet_transactions`;
CREATE TABLE IF NOT EXISTS `user_bet_transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_bet_id` int(11) unsigned NOT NULL,
  `api_transaction_id` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `operation` varchar(20) COLLATE utf8_general_ci NOT NULL,
  `amount` float NOT NULL,
  `type` varchar(10) COLLATE utf8_general_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_bet_id` (`user_bet_id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_bet_transaction_types`
--

DROP TABLE IF EXISTS `user_bet_transaction_types`;
CREATE TABLE IF NOT EXISTS `user_bet_transaction_types` (
  `id` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_bonus`
--

DROP TABLE IF EXISTS `user_bonus`;
CREATE TABLE IF NOT EXISTS `user_bonus` (
  `user_id` int(10) unsigned NOT NULL,
  `bonus_id` int(10) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`bonus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_documentation`
--

DROP TABLE IF EXISTS `user_documentation`;
CREATE TABLE IF NOT EXISTS `user_documentation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `file` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `description` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `user_session_id` int(10) unsigned NOT NULL,
  `staff_session_id` int(10) unsigned DEFAULT NULL,
  `status_id` varchar(45) COLLATE utf8_general_ci DEFAULT 'pending',
  `expire` datetime DEFAULT NULL,
  `motive` varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_documentation_user_id_foreign` (`user_id`),
  KEY `user_documentation_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_invites`
--

DROP TABLE IF EXISTS `user_invites`;
CREATE TABLE IF NOT EXISTS `user_invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_friend_id` int(10) unsigned NOT NULL,
  `user_promo_code` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `regist_date` datetime NOT NULL,
  `email` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `status_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `bet_sum` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_invites_user_id_foreign` (`user_id`),
  KEY `user_invites_user_friend_id_foreign` (`user_friend_id`),
  UNIQUE KEY `user_invites_user_friend_unique` (`user_id`, `user_friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_limits`
--

DROP TABLE IF EXISTS `user_limits`;
CREATE TABLE IF NOT EXISTS `user_limits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_session_id` int(10) unsigned NOT NULL,
  `limit_id` varchar(45) NOT NULL,
  `limit_value` decimal(15,2) DEFAULT NULL,
  `implement_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_limits_user_id_foreign` (`user_id`),
  KEY `user_limits_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_session_id` int(10) unsigned NOT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `staff_session_id` int(10) unsigned DEFAULT NULL,
  `gender` varchar(1) COLLATE utf8_general_ci DEFAULT NULL,
  `name` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `email` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `email_checked` tinyint(1) NOT NULL DEFAULT '0',
  `email_token` varchar(20) COLLATE utf8_general_ci DEFAULT NULL,
  `birth_date` datetime NOT NULL,
  `nationality` varchar(2) COLLATE utf8_general_ci DEFAULT NULL,
  `professional_situation` varchar(2) COLLATE utf8_general_ci DEFAULT NULL,
  `profession` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `address` text COLLATE utf8_general_ci NOT NULL,
  `zip_code` varchar(15) COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8_general_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `country` varchar(2) COLLATE utf8_general_ci NOT NULL,
  `document_number` varchar(15) COLLATE utf8_general_ci NOT NULL,
  `document_type_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `tax_number` varchar(15) COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_profiles_email_unique` (`email`),
  KEY `user_profiles_user_id_foreign` (`user_id`),
  KEY `user_profiles_staff_id_foreign` (`staff_id`),
  KEY `user_profiles_document_type_id_foreign` (`document_type_id`),
  KEY `user_profiles_user_session_id_foreign` (`user_session_id`),
  KEY `user_profiles_staff_session_id_foreign` (`staff_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles_log`
--

DROP TABLE IF EXISTS `user_profiles_log`;
CREATE TABLE IF NOT EXISTS `user_profiles_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(45) NOT NULL,
  `alias` varchar(45) NOT NULL,
  `account` varchar(15) NOT NULL,
  `payment_type` int(11) NOT NULL,
  `document_number` varchar(15) NOT NULL,
  `document_type_id` varchar(45) NOT NULL,
  `name` varchar(250) NOT NULL,
  `birth_date` datetime NOT NULL,
  `tax_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `zip_code` varchar(15) NOT NULL,
  `nationality` varchar(2) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(45) NOT NULL,
  `tax_authority_reply_id` int(11) NOT NULL,
  `tax_authority_reply` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_profiles_log_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_results_nyx`
--

DROP TABLE IF EXISTS `user_results_nyx`;
CREATE TABLE IF NOT EXISTS `user_results_nyx` (
  `id` int(11) unsigned NOT NULL,
  `user_bet_id` int(11) NOT NULL,
  `accountid` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `device` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gamemodel` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gamesessionid` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `gamestatus` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gametype` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gpgameid` varchar(128) COLLATE utf8_general_ci NOT NULL,
  `gpid` int(11) NOT NULL,
  `nogsgameid` int(11) NOT NULL,
  `product` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `result` float NOT NULL,
  `roundid` bigint(20) NOT NULL,
  `transactionid` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_revocations`
--

DROP TABLE IF EXISTS `user_revocations`;
CREATE TABLE IF NOT EXISTS `user_revocations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `user_session_id` int(10) unsigned DEFAULT NULL,
  `self_exclusion_id` int(10) unsigned DEFAULT NULL,
  `request_date` datetime DEFAULT NULL,
  `status_id` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `name` varchar(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `name`) VALUES
('admin', 'Administrator'),
('player', 'Player');

-- --------------------------------------------------------

--
-- Table structure for table `user_rollbacks_nyx`
--

DROP TABLE IF EXISTS `user_rollbacks_nyx`;
CREATE TABLE IF NOT EXISTS `user_rollbacks_nyx` (
  `id` int(11) unsigned NOT NULL,
  `user_bet_id` int(11) NOT NULL,
  `accountid` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `device` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gamemodel` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gamesessionid` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `gametype` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `gpgameid` varchar(128) COLLATE utf8_general_ci NOT NULL,
  `gpid` int(11) NOT NULL,
  `nogsgameid` int(11) NOT NULL,
  `product` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `rollbackamount` float NOT NULL,
  `roundid` bigint(20) NOT NULL,
  `transactionid` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_self_exclusions`
--

DROP TABLE IF EXISTS `user_self_exclusions`;
CREATE TABLE IF NOT EXISTS `user_self_exclusions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `request_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `self_exclusion_type_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `motive` varchar(1000) COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `user_session_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_self_exclusions_user_id_foreign` (`user_id`),
  KEY `user_self_exclusions_self_exclusion_type_id_foreign` (`self_exclusion_type_id`),
  KEY `user_self_exclusions_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `session_types`
--

DROP TABLE IF EXISTS `session_types`;
CREATE TABLE IF NOT EXISTS `session_types` (
   `id` VARCHAR(100) COLLATE utf8_general_ci NOT NULL,
   `name` VARCHAR(255) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `session_types`
--

INSERT INTO `session_types` (`id`, `name`) VALUES 
('login',''),
('logout',''),
('sign_up',''),
('new_session',''),
('bet.received',''),
('new_game_session',''),
('uploaded_doc',''),
('uploaded_doc.comprovativo_identidade',''),
('uploaded_doc.comprovativo_iban',''),
('uploaded_doc.comprovativo_morada',''),
('self_exclusion',''),
('self_exclusion.1year_period',''),
('self_exclusion.3months_period',''),
('self_exclusion.minimum_period',''),
('self_exclusion.reflection_period',''),
('self_exclusion.undetermined_period',''),
('self_exclusion.revocation',''),
('self_exclusion.cancel_revocation',''),
('self_exclusion.from_srij',''),
('change_pin',''),
('change_profile',''),
('change_password',''),
('change_trans',''),
('change_trans.bank_transfer',''),
('change_trans.payment_service',''),
('change_trans.paypal',''),
('change_limits',''),
('change_limits.bets',''),
('change_limits.deposits',''),
('confirmed.email',''),
('create.iban',''),
('deposit',''),
('deposit.bank_transfer',''),
('deposit.payment_service',''),
('deposit.paypal',''),
('withdrawal',''),
('withdrawal.bank_transfer',''),
('withdrawal.payment_service',''),
('withdrawal.paypal',''),
('reset_password',''),
('check.identity',''),
('sent.confirm_mail',''),
('user_agent',''),
('test','');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE IF NOT EXISTS `user_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `session_number` int(11) NOT NULL,
  `session_id` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `session_type` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `description` varchar(500) COLLATE utf8_general_ci DEFAULT NULL,
  `ip` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_sessions_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE IF NOT EXISTS `user_settings` (
  `user_id` int(10) unsigned NOT NULL,
  `chat` tinyint(1) NOT NULL DEFAULT '0',
  `email` tinyint(1) NOT NULL DEFAULT '0',
  `mail` tinyint(1) NOT NULL DEFAULT '0',
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `sms` tinyint(1) NOT NULL DEFAULT '0',
  `phone` tinyint(1) NOT NULL DEFAULT '0',
  `user_session_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_id`),
  KEY `user_settings_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `user_statuses`
--

DROP TABLE IF EXISTS `user_statuses`;
CREATE TABLE IF NOT EXISTS `user_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_session_id` int(10) unsigned DEFAULT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `staff_session_id` int(10) unsigned DEFAULT NULL,
  `status_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `identity_status_id` varchar(45) COLLATE utf8_general_ci NOT NULL DEFAULT 'waiting_document',
  `email_status_id` varchar(45) CHARACTER SET utf8 NOT NULL DEFAULT 'waiting_confirmation',
  `iban_status_id` varchar(45) CHARACTER SET utf8 NOT NULL DEFAULT 'waiting_document',
  `address_status_id` varchar(45) COLLATE utf8_general_ci NOT NULL DEFAULT 'waiting_document',
  `selfexclusion_status_id` varchar(45) COLLATE utf8_general_ci DEFAULT NULL,
  `motive` varchar(1000) COLLATE utf8_general_ci DEFAULT NULL,
  `balance` decimal(15,2) DEFAULT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `address_status_id` (`address_status_id`),
  KEY `identity_status_id` (`identity_status_id`),
  KEY `user_statuses_status_id_foreign` (`status_id`),
  KEY `user_statuses_user_id_foreign` (`user_id`),
  KEY `user_statuses_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user_transactions`
--

DROP TABLE IF EXISTS `user_transactions`;
CREATE TABLE IF NOT EXISTS `user_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `staff_id` int(10) unsigned DEFAULT NULL,
  `origin` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `transaction_id` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `api_transaction_id` varchar(45) COLLATE utf8_general_ci DEFAULT NULL,
  `user_bank_account_id` int(10) unsigned DEFAULT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `initial_balance` decimal(15,2) NOT NULL,
  `final_balance` decimal(15,2) NOT NULL,
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `date` datetime NOT NULL,
  `status_id` varchar(45) COLLATE utf8_general_ci DEFAULT NULL,
  `description` text COLLATE utf8_general_ci NOT NULL,
  `user_session_id` int(10) unsigned DEFAULT NULL,
  `staff_session_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_transactions_user_id_foreign` (`user_id`),
  KEY `user_transactions_transaction_id_foreign` (`transaction_id`),
  KEY `user_transactions_user_bank_account_id_foreign` (`user_bank_account_id`),
  KEY `user_transactions_user_session_id_foreign` (`user_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `legal_docs`
--

DROP TABLE IF EXISTS `legal_docs`;
CREATE TABLE IF NOT EXISTS `legal_docs` (
  `id` varchar(50) NOT NULL,
  `parent_id` varchar(50) DEFAULT NULL,
  `approved_version` int(10) unsigned NOT NULL,
  `last_version` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `legal_docs_parent_id_foreign` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `legal_docs_versions`
--

DROP TABLE IF EXISTS `legal_docs_versions`;
CREATE TABLE IF NOT EXISTS `legal_docs_versions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `legal_doc_id` varchar(50) DEFAULT NULL,
  `version` int(10) unsigned NOT NULL,
  `name` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `description` text COLLATE utf8_general_ci NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `staff_id` int(10) unsigned NOT NULL,
  `staff_session_id` int(10) unsigned NOT NULL,
  `approved_staff_id` int(10) unsigned DEFAULT NULL,
  `approved_staff_session_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`legal_doc_id`,`version`),
  KEY `legal_docs_versions_id_auto` (`id`),
  KEY `legal_docs_versions_parent_id_foreign` (`legal_doc_id`),
  KEY `legal_docs_versions_staff_id_foreign` (`staff_id`),
  KEY `legal_docs_versions_staff_session_id_foreign` (`staff_session_id`),
  KEY `legal_docs_versions_approved_staff_id_foreign` (`approved_staff_id`),
  KEY `legal_docs_versions_approved_staff_session_id_foreign` (`approved_staff_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1;

--
-- Constraints for dumped tables
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
