SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `true`
--
-----------------------------------------------------------
--
-- Структура таблицы `uch`, в ней какбы список участников ОРВ
--

CREATE TABLE `ofv_uch` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` text COLLATE utf8_unicode_ci NOT NULL,
`address` text COLLATE utf8_unicode_ci NOT NULL,
`birthday` date NOT NULL,
`pasp_ser` int(4),
`pasp_num` int(6),
`pasp_date` date,
`pasp_who` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Структура таблицы `acc_type`, в ней как бы типы счетов
--

CREATE TABLE `ofv_acc_type` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `acc`, в ней типа "счета"
--

CREATE TABLE `ofv_acc` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`uch_id` int(11) NOT NULL,
`type_id` int(11) NOT NULL,
`creat_date` date NOT NULL,
`clos_date` date,
CONSTRAINT `acc_uch_id` FOREIGN KEY (`uch_id`) REFERENCES `ofv_uch` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT `acc_type_id` FOREIGN KEY (`type_id`) REFERENCES `ofv_acc_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `transactsii`, в которой "транзакции"
--

CREATE TABLE `ofv_transactsii` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`comment` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Структура таблицы `Provodki`, в ней, короче, "бухгалтерские проводки"
--

CREATE TABLE `ofv_provodki` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`cred_acc_id` int(11) NOT NULL,
`deb_acc_id` int(11) NOT NULL,
`summa` bigint NOT NULL,
`exec_date` date NOT NULL,
`purpose` text COLLATE utf8_unicode_ci,
`transact_id` int(11),
CONSTRAINT `provodki_cred_acc_id` FOREIGN KEY (`cred_acc_id`) REFERENCES `ofv_acc` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT `provodki_deb_acc_id` FOREIGN KEY (`deb_acc_id`) REFERENCES `ofv_acc` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT `provodki_transact_id` FOREIGN KEY (`transact_id`) REFERENCES `ofv_transactsii` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
