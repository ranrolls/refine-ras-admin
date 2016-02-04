CREATE TABLE IF NOT EXISTS `#__fandb_fand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` DATETIME NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;