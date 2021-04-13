SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `fbpp`;
CREATE TABLE IF NOT EXISTS `fbpp` (
  `hash` varchar(16) NOT NULL,
  `wp` varchar(16) NOT NULL,
  `ip` text NOT NULL COMMENT 'client ip',
  `sessionid` text NOT NULL COMMENT 'session id',
  `ep_id` int(6) NOT NULL,
  `url` text NOT NULL,
  `data` varchar(8) NOT NULL,
  `access` int(1) NOT NULL DEFAULT '0' COMMENT 'access quota',
  `expires` timestamp NULL DEFAULT NULL COMMENT 'Date Time+30min',
  PRIMARY KEY (`hash`),
  KEY `wp` (`wp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
