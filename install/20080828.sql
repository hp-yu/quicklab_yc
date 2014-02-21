CREATE TABLE `chem_structures` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `chem_id` int(9) unsigned NOT NULL,
  `structure` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;