-- Adminer 4.2.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `adj_pos`;
CREATE TABLE `adj_pos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `match` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `targeting` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `adj_pos` (`id`, `keyword`, `match`, `nombre`, `genre`, `targeting`) VALUES
(1,	'ma',	'ta',	's',	'f',	'me'),
(2,	'ta',	'ma',	's',	'f',	'you'),
(3,	'mon',	'ton',	's',	'm',	'me'),
(4,	'ton',	'mon',	's',	'm',	'you'),
(5,	'mes',	'tes',	'p',	'f',	'me'),
(6,	'tes',	'mes',	'p',	'm',	'you'),
(7,	'notre',	'votre',	's',	'm',	'me'),
(8,	'votre',	'notre',	's',	'm',	'you');

DROP TABLE IF EXISTS `ai_memory_one`;
CREATE TABLE `ai_memory_one` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adj` text CHARACTER SET utf8 COLLATE utf8_bin,
  `adv` text CHARACTER SET utf8 COLLATE utf8_bin,
  `art` text CHARACTER SET utf8 COLLATE utf8_bin,
  `aux` text CHARACTER SET utf8 COLLATE utf8_bin,
  `con` text CHARACTER SET utf8 COLLATE utf8_bin,
  `lia` text CHARACTER SET utf8 COLLATE utf8_bin,
  `nom` text CHARACTER SET utf8 COLLATE utf8_bin,
  `ono` text CHARACTER SET utf8 COLLATE utf8_bin,
  `other` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pre` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro` text CHARACTER SET utf8 COLLATE utf8_bin,
  `ver` text CHARACTER SET utf8 COLLATE utf8_bin,
  `ver_inf` text CHARACTER SET utf8 COLLATE utf8_bin,
  `ver_past` text CHARACTER SET utf8 COLLATE utf8_bin,
  `adj_dem` text CHARACTER SET utf8 COLLATE utf8_bin,
  `adj_ind` text CHARACTER SET utf8 COLLATE utf8_bin,
  `adj_int` text CHARACTER SET utf8 COLLATE utf8_bin,
  `adj_num` text CHARACTER SET utf8 COLLATE utf8_bin,
  `adj_pos` text CHARACTER SET utf8 COLLATE utf8_bin,
  `art_def` text CHARACTER SET utf8 COLLATE utf8_bin,
  `art_ind` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro_dem` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro_ind` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro_int` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro_per` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro_per_con` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro_pos` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pro_rel` text CHARACTER SET utf8 COLLATE utf8_bin,
  `human` text CHARACTER SET utf8 COLLATE utf8_bin,
  `pattern` text CHARACTER SET utf8 COLLATE utf8_bin,
  `question` text CHARACTER SET utf8 COLLATE utf8_bin,
  `keywords` text CHARACTER SET utf8 COLLATE utf8_bin,
  `wikipedia` text CHARACTER SET utf8 COLLATE utf8_bin,
  `ip` text CHARACTER SET utf8 COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 2017-12-17 18:56:01
