SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `ofv_uch` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` text COLLATE utf8_unicode_ci NOT NULL,
`address` text COLLATE utf8_unicode_ci,
`address_fact` text COLLATE utf8_unicode_ci,
`pol` int(1),
`birthday` date,
`pasp_ser` int(4),
`pasp_num` int(6),
`pasp_date` date,
`pasp_who` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ofv_acc_type` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ofv_acc` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`uch_id` int(11) NOT NULL,
`type_id` int(11) NOT NULL,
`creat_date` date NOT NULL,
`clos_date` date,
`remark` text COLLATE utf8_unicode_ci,
CONSTRAINT `acc_uch_id` FOREIGN KEY (`uch_id`) REFERENCES `ofv_uch` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT `acc_type_id` FOREIGN KEY (`type_id`) REFERENCES `ofv_acc_type` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ofv_transactsii` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`comment` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `ofv_loan_agr` (
`base_debt_acc` int(11) PRIMARY KEY,
`sum` bigint NOT NULL,
`base_rate` double NOT NULL,
`fuflo_rate` double NOT NULL,
`fuflo_debt_acc` int(11),
`int_acc` int(11),
CONSTRAINT `loan_agr_base_debt_acc` FOREIGN KEY (`base_debt_acc`) REFERENCES `ofv_acc` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `loan_agr_fuflo_debt_acc` FOREIGN KEY (`fuflo_debt_acc`) REFERENCES `ofv_acc` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT `loan_agr_int_acc` FOREIGN KEY (`int_acc`) REFERENCES `ofv_acc` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ofv_garant` (
`base_debt_acc` int(11) NOT NULL,
`uch_id` int(11) NOT NULL,
PRIMARY KEY(`base_debt_acc`, `uch_id`),
CONSTRAINT `garant_uch_id` FOREIGN KEY (`uch_id`) REFERENCES `ofv_uch` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
CONSTRAINT `garant_base_debt_acc` FOREIGN KEY (`base_debt_acc`) REFERENCES `ofv_loan_agr` (`base_debt_acc`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ofv_sched` (
`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
`base_debt_acc` int(11) NOT NULL, 
`reason` int(11) NOT NULL, 
`date` date NOT NULL,
CONSTRAINT `sched_base_debt_acc` FOREIGN KEY (`base_debt_acc`) REFERENCES `ofv_loan_agr` (`base_debt_acc`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ofv_sched_line` (
`sched_id` int(11) NOT NULL,
`date` date NOT NULL,
`base_debt` bigint NOT NULL,
`int` bigint NOT NULL,
`remainder` bigint NOT NULL,
CONSTRAINT `sched_line_pk` PRIMARY KEY (`sched_id`, `date`),
CONSTRAINT `sched_line_sched_id` FOREIGN KEY (`sched_id`) REFERENCES `ofv_sched` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DELIMITER //
CREATE PROCEDURE ins_loan_agr (p_uch_id INT(11), p_creat_date DATE, p_remark TEXT  CHARSET utf8
			     ,p_sum BIGINT, p_base_rate DOUBLE, p_fuflo_rate DOUBLE) 
BEGIN
    INSERT INTO ofv_acc (uch_id, type_id, creat_date, remark)
        SELECT p_uch_id , id, p_creat_date, p_remark
	FROM ofv_acc_type
	WHERE name = 'Ссудный';
    INSERT INTO ofv_loan_agr (base_debt_acc, sum, base_rate, fuflo_rate) 
	VALUES (LAST_INSERT_ID(), p_sum, p_base_rate, p_fuflo_rate);
END //

CREATE PROCEDURE upd_loan_agr (p_id INT(11), p_creat_date DATE, p_clos_date DATE, p_remark TEXT CHARSET utf8
                             , p_sum BIGINT, p_base_rate DOUBLE, p_fuflo_rate DOUBLE)
BEGIN
    UPDATE ofv_acc
    SET creat_date = p_creat_date, clos_date = p_clos_date, remark = p_remark 
	WHERE id = p_id;
    UPDATE ofv_loan_agr 
    SET sum = p_sum, base_rate = p_base_rate, fuflo_rate = p_fuflo_rate
    WHERE ofv_loan_agr.base_debt_acc = p_id;
END //

CREATE PROCEDURE del_loan_agr (p_id INT(11))
BEGIN
	DELETE FROM ofv_acc
	WHERE id in (
		SELECT p_id
		UNION ALL
		SELECT fuflo_debt_acc
		FROM ofv_loan_agr
		WHERE base_debt_acc = p_id
		UNION ALL
		SELECT int_acc
		FROM ofv_loan_agr
		WHERE base_debt_acc = p_id
	);
END //
