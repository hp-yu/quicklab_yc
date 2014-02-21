-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2008 年 08 月 28 日 14:04
-- 服务器版本: 5.0.27
-- PHP 版本: 5.2.1
-- 
-- 数据库: `quicklab`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `accounts`
-- 
DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` mediumint(3) unsigned NOT NULL auto_increment,
  `name` varchar(30) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci,
  `date_start` date NOT NULL,
  `date_finish` date NOT NULL,
  `money_total` float(9,2) unsigned NOT NULL,
  `money_available` float(9,2) unsigned NOT NULL,
  `note` text collate utf8_unicode_ci,
  `state` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `accounts`
-- 

INSERT INTO `accounts` (`id`, `name`, `description`, `date_start`, `date_finish`, `money_total`, `money_available`, `note`, `state`) VALUES 
(1, '666666', '', '0000-00-00', '0000-00-00', 100000.00, 200000.00, '', 1),
(2, '888888', '', '0000-00-00', '0000-00-00', 0.00, 250000.00, '', 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `ani_strains`
-- 

DROP TABLE IF EXISTS `ani_strains`;
CREATE TABLE IF NOT EXISTS `ani_strains` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `species` mediumint(6) unsigned NOT NULL,
  `genes_alleles` varchar(100) NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `ani_strains`
-- 

INSERT INTO `ani_strains` (`id`, `name`, `description`, `species`, `genes_alleles`, `date_create`, `date_update`, `created_by`, `updated_by`) VALUES 
(1, 'C57BL/6J', 'C57 Black;      B6;      B6J;      Black 6;', 2, 'Cdh23;   Cdh23ahl;   Nnt;   NntC57BL/6J;', '2008-02-27', '2008-02-29', 1, 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `animals`
-- 

DROP TABLE IF EXISTS `animals`;
CREATE TABLE IF NOT EXISTS `animals` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `strain` mediumint(6) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `health` tinyint(1) unsigned NOT NULL,
  `gender` tinyint(1) unsigned NOT NULL,
  `qty` smallint(3) NOT NULL,
  `weight` varchar(20) NOT NULL,
  `age` varchar(20) NOT NULL,
  `date_birth` date NOT NULL,
  `date_arrival` date NOT NULL,
  `date_experiment` date NOT NULL,
  `date_death` date NOT NULL,
  `state` tinyint(1) NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text NOT NULL,
  `mask` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- 导出表中的数据 `animals`
-- 

INSERT INTO `animals` (`id`, `strain`, `name`, `description`, `health`, `gender`, `qty`, `weight`, `age`, `date_birth`, `date_arrival`, `date_experiment`, `date_death`, `state`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(1, 1, 'C57BL/6J (Mouse)', 'diabetes', 2, 1, 5, '30g', '3 weeks', '0000-00-00', '2008-03-08', '2008-03-02', '2008-03-02', 1, '2008-02-29', '2008-03-02', 1, 1, 1, 'note', 0),
(2, 1, 'C57BL/6J (Mouse)2', '', 0, 0, 0, '', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 0, '2008-03-02', '0000-00-00', 1, 0, 1, '', 0),
(3, 1, 'C57BL/6J (Mouse)3', '', 0, 0, 0, '', '', '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00', 0, '2008-03-02', '0000-00-00', 1, 0, 1, '', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `antibodies`
-- 

DROP TABLE IF EXISTS `antibodies`;
CREATE TABLE IF NOT EXISTS `antibodies` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `antibody_type` tinyint(1) unsigned NOT NULL,
  `specification` tinyint(1) unsigned NOT NULL,
  `isotype` tinyint(1) unsigned NOT NULL,
  `host` mediumint(6) unsigned NOT NULL,
  `species_reactivity` varchar(50) NOT NULL,
  `specificity` varchar(100) NOT NULL,
  `marker` varchar(50) NOT NULL,
  `application` varchar(100) NOT NULL,
  `purity` varchar(50) NOT NULL,
  `concentration` varchar(50) NOT NULL,
  `mw` varchar(50) NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text NOT NULL,
  `mask` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- 
-- 导出表中的数据 `antibodies`
-- 

INSERT INTO `antibodies` (`id`, `name`, `description`, `antibody_type`, `specification`, `isotype`, `host`, `species_reactivity`, `specificity`, `marker`, `application`, `purity`, `concentration`, `mw`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(1, '1', '2', 1, 1, 1, 0, '7', '8', '9', '10', '11', '12', '13', '2008-01-20', '0000-00-00', 1, 0, 1, '15', 0),
(2, '1', '2', 2, 2, 2, 1, '7', '8', '9', '10', '11', '12', '13', '2008-01-20', '0000-00-00', 1, 0, 1, '15', 0),
(3, '1', '2', 0, 0, 3, 2, '7', '8', '9', '10', '11', '12', '13', '2008-01-20', '0000-00-00', 1, 0, 1, '15', 0),
(4, '1', '2', 0, 0, 4, 3, '7', '8', '9', '10', '11', '12', '13', '2008-01-20', '0000-00-00', 1, 0, 1, '15', 0),
(5, '1', '2', 0, 0, 5, 4, '7', '8', '9', '10', '11', '12', '13', '2008-01-20', '0000-00-00', 1, 0, 1, '15', 0),
(6, '1', '2', 0, 0, 0, 5, '7', '8', '9', '10', '11', '12', '13', '2008-01-20', '0000-00-00', 1, 0, 1, '15', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `antibody_options`
-- 

DROP TABLE IF EXISTS `antibody_options`;
CREATE TABLE IF NOT EXISTS `antibody_options` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  `option_type` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

-- 
-- 导出表中的数据 `antibody_options`
-- 

INSERT INTO `antibody_options` (`id`, `name`, `option_type`) VALUES 
(1, 'IgA', 'isotype'),
(2, 'IgD', 'isotype'),
(3, 'IgE', 'isotype'),
(4, 'IgG', 'isotype'),
(5, 'IgM', 'isotype'),
(6, 'APC', 'marker'),
(7, 'Biotin', 'marker'),
(8, 'Cy3', 'marker'),
(9, 'Cy5', 'marker'),
(10, 'FITC', 'marker'),
(11, 'Peroxidase(HRP)', 'marker'),
(12, 'Rhodamine', 'marker'),
(13, 'Texas Red', 'marker'),
(14, 'Streptavidin', 'marker'),
(15, 'Immunoprecipitation(IP)', 'app'),
(16, 'Immunohistochemistry(IHC)', 'app'),
(17, 'Immunocytochemistry(IC)', 'app'),
(18, 'Immunofluorescence(IF)', 'app'),
(19, 'Western Blot', 'app'),
(20, 'Flow Cytometry', 'app'),
(21, 'ELISA', 'app'),
(22, 'DELFIA®', 'app');

-- --------------------------------------------------------

-- 
-- 表的结构 `authority`
-- 

DROP TABLE IF EXISTS `authority`;
CREATE TABLE IF NOT EXISTS `authority` (
  `id` tinyint(3) NOT NULL auto_increment,
  `name` varchar(20) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- 
-- 导出表中的数据 `authority`
-- 

INSERT INTO `authority` (`id`, `name`) VALUES 
(1, 'Super administrator'),
(2, 'Administrator'),
(3, 'Staff'),
(4, 'User');

-- --------------------------------------------------------

-- 
-- 表的结构 `cells`
-- 

DROP TABLE IF EXISTS `cells`;
CREATE TABLE IF NOT EXISTS `cells` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `atcc_nbr` varchar(50) NOT NULL,
  `organism` mediumint(6) unsigned NOT NULL,
  `source` varchar(100) NOT NULL,
  `medium` text NOT NULL,
  `temperature` varchar(50) NOT NULL,
  `atmosphere` varchar(50) NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text NOT NULL,
  `mask` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- 导出表中的数据 `cells`
-- 

INSERT INTO `cells` (`id`, `name`, `description`, `atcc_nbr`, `organism`, `source`, `medium`, `temperature`, `atmosphere`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(1, 'Sf9', 'used to replicate baculovirus expression vectors', 'CRL-1711', 14, '1', '2', '3', '', '2008-01-19', '2008-01-20', 1, 1, 1, '4', 0),
(5, 'HCT 116', 'transfection host ', 'CCL-247', 1, 'colorectal carcinoma', 'medium', 'growth_condition', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'NOTE', 0),
(4, 'A549', 'transfection host ', 'CCL-185', 1, 'carcinoma', 'medium medium', 'growth_condition', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'note', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `chem_structures`
-- 

DROP TABLE IF EXISTS `chem_structures`;
CREATE TABLE IF NOT EXISTS `chem_structures` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `chem_id` int(9) unsigned NOT NULL,
  `structure` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- 导出表中的数据 `chem_structures`
-- 

INSERT INTO `chem_structures` (`id`, `chem_id`, `structure`) VALUES 
(1, 1, 'chem_id:1\r\n  Quicklab0827082220\r\n\r\n 35 42  0  0  0  0  0  0  0  0999 V2000\r\n    6.5529    0.0000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    1.7108    2.7957    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    2.7816    3.8317    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    7.8544   10.7587    0.0000 O   0  0  0  0  0  0  0  0  0  0  0  0\r\n   10.5142    7.7034    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    0.1694    7.8774    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n   10.6369    6.3110    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    0.0000    6.4899    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    9.2467    8.2934    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    1.4559    8.4245    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    9.4923    5.5085    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    1.1170    5.6493    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.2543   10.3486    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.5529    2.7957    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    5.3425    0.6989    0.0000 N   0  0  0  0  0  0  0  0  0  0  0  0\r\n    5.3989   11.1892    0.0000 N   0  0  0  0  0  0  0  0  0  0  0  0\r\n    2.9213    2.0967    0.0000 O   0  0  0  0  0  0  0  0  0  0  0  0\r\n    5.3425    4.8924    0.0000 O   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.5158   10.3486    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.6639    9.0500    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.0616    9.0266    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    8.1023    7.4909    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    2.5727    7.5839    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    8.2251    6.0985    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    2.4033    6.1965    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    3.9448    7.8515    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.7401    7.8046    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.0209    6.6057    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.6232    6.6293    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    5.3425    2.0967    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.5529    4.1935    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.1318    2.7957    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.9385    5.5514    0.0000 N   0  0  0  0  0  0  0  0  0  0  0  0\r\n    3.6705    5.6065    0.0000 N   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.1318    4.1935    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n  1 15  1  0  0  0  0\r\n  2 17  1  0  0  0  0\r\n 35  3  1  6  0  0  0\r\n  4 19  2  0  0  0  0\r\n  5  7  1  0  0  0  0\r\n  5  9  2  0  0  0  0\r\n  6  8  1  0  0  0  0\r\n  6 10  2  0  0  0  0\r\n  7 11  2  0  0  0  0\r\n  8 12  2  0  0  0  0\r\n  9 22  1  0  0  0  0\r\n 10 23  1  0  0  0  0\r\n 11 24  1  0  0  0  0\r\n 12 25  1  0  0  0  0\r\n 13 16  1  0  0  0  0\r\n 13 20  1  0  0  0  0\r\n 14 30  1  0  0  0  0\r\n 14 31  1  0  0  0  0\r\n 30 15  1  1  0  0  0\r\n 16 19  1  0  0  0  0\r\n 32 17  1  1  0  0  0\r\n 18 31  1  0  0  0  0\r\n 18 35  1  0  0  0  0\r\n 19 21  1  0  0  0  0\r\n 20 21  2  0  0  0  0\r\n 20 26  1  0  0  0  0\r\n 21 27  1  0  0  0  0\r\n 22 24  2  0  0  0  0\r\n 22 27  1  0  0  0  0\r\n 23 25  2  0  0  0  0\r\n 23 26  1  0  0  0  0\r\n 24 33  1  0  0  0  0\r\n 25 34  1  0  0  0  0\r\n 26 29  2  0  0  0  0\r\n 27 28  2  0  0  0  0\r\n 28 29  1  0  0  0  0\r\n 28 33  1  0  0  0  0\r\n 29 34  1  0  0  0  0\r\n 30 32  1  0  0  0  0\r\n 31 33  1  1  0  0  0\r\n 32 35  1  0  0  0  0\r\n 35 34  1  1  0  0  0\r\nM  END'),
(3, 2, 'chem_id:2\r\n  Quicklab0827082224\r\n\r\n 20 23  0  0  0  0  0  0  0  0999 V2000\r\n    7.8263    5.9641    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.0622    5.6000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    2.4249    3.5000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    0.0000    0.7000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    0.0000    2.1000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    1.2124    0.0000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    8.2166    3.5000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    3.6373    0.0000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.8497    0.7000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    7.3937    2.3674    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    3.6373    4.2000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    1.2124    2.8000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.8497    4.9000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    7.3937    4.6326    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    2.4249    0.7000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    4.8497    2.1000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.0622    2.8000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    3.6373    2.8000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    6.0622    4.2000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n    2.4249    2.1000    0.0000 C   0  0  0  0  0  0  0  0  0  0  0  0\r\n  1 14  1  0  0  0  0\r\n  2 19  1  0  0  0  0\r\n  3 20  1  0  0  0  0\r\n  4  5  1  0  0  0  0\r\n  4  6  1  0  0  0  0\r\n  5 12  1  0  0  0  0\r\n  6 15  1  0  0  0  0\r\n  7 10  1  0  0  0  0\r\n  7 14  1  0  0  0  0\r\n  8  9  1  0  0  0  0\r\n  8 15  1  0  0  0  0\r\n  9 16  1  0  0  0  0\r\n 10 17  1  0  0  0  0\r\n 11 13  1  0  0  0  0\r\n 11 18  1  0  0  0  0\r\n 12 20  1  0  0  0  0\r\n 13 19  1  0  0  0  0\r\n 14 19  1  0  0  0  0\r\n 15 20  1  0  0  0  0\r\n 16 17  1  0  0  0  0\r\n 16 18  1  0  0  0  0\r\n 17 19  1  0  0  0  0\r\n 18 20  1  0  0  0  0\r\nM  END');

-- --------------------------------------------------------

-- 
-- 表的结构 `chemicals`
-- 

DROP TABLE IF EXISTS `chemicals`;
CREATE TABLE IF NOT EXISTS `chemicals` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `synonym` varchar(200) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `cas` varchar(20) collate utf8_unicode_ci NOT NULL,
  `structure` varchar(50) collate utf8_unicode_ci NOT NULL,
  `formula` varchar(200) collate utf8_unicode_ci NOT NULL,
  `mw` varchar(20) collate utf8_unicode_ci NOT NULL,
  `purity` varchar(20) collate utf8_unicode_ci NOT NULL,
  `form` varchar(200) collate utf8_unicode_ci NOT NULL,
  `storage` varchar(200) collate utf8_unicode_ci NOT NULL,
  `solubility` varchar(200) collate utf8_unicode_ci NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text collate utf8_unicode_ci,
  `mask` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `chemicals`
-- 

INSERT INTO `chemicals` (`id`, `name`, `synonym`, `description`, `cas`, `structure`, `formula`, `mw`, `purity`, `form`, `storage`, `solubility`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(1, 'Staurosporine,星孢菌素', 'Stsp', 'kinase inhibitor', '62996-74-1', 'data/chemicals/structure_1.sdf', 'C28H26N4O3', '466.5', '', '', '-20°C', '', '2008-01-07', '2008-02-29', 1, 1, 1, '', 0),
(2, '甾', '2', '3', '4', '', '5', '6', '7', '8', '9', '10', '2008-01-07', '2008-08-27', 1, 1, 2, '12', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `custom_fields`
-- 

DROP TABLE IF EXISTS `custom_fields`;
CREATE TABLE IF NOT EXISTS `custom_fields` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `module_name` varchar(100) NOT NULL,
  `field_id` varchar(100) NOT NULL,
  `field_name` varchar(100) NOT NULL,
  `field_type` varchar(10) NOT NULL,
  `field_length_values` varchar(10) NOT NULL,
  `is_null` tinyint(1) unsigned NOT NULL default '0',
  `field_default` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `search` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- 导出表中的数据 `custom_fields`
-- 

INSERT INTO `custom_fields` (`id`, `module_name`, `field_id`, `field_name`, `field_type`, `field_length_values`, `is_null`, `field_default`, `note`, `search`) VALUES 
(1, 'samples', 'custom_field_1', 'weight', 'VARCHAR', '20', 0, '', '重量', 0),
(2, 'samples', 'custom_field_2', 'organism', 'VARCHAR', '20', 1, '', '组织', 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `items_relation`
-- 

DROP TABLE IF EXISTS `items_relation`;
CREATE TABLE IF NOT EXISTS `items_relation` (
  `item_from` varchar(13) collate utf8_unicode_ci NOT NULL,
  `item_to` varchar(13) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- 导出表中的数据 `items_relation`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `label_sheets`
-- 

DROP TABLE IF EXISTS `label_sheets`;
CREATE TABLE IF NOT EXISTS `label_sheets` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `labels_total` tinyint(3) unsigned NOT NULL,
  `labels_row` tinyint(3) unsigned NOT NULL,
  `labels_column` tinyint(3) unsigned NOT NULL,
  `label_width` double unsigned NOT NULL,
  `label_height` double unsigned NOT NULL,
  `margin_left` double unsigned NOT NULL,
  `margin_top` double unsigned NOT NULL,
  `spacing_row` double unsigned NOT NULL,
  `spacing_column` double unsigned NOT NULL,
  `padding_left` double unsigned NOT NULL,
  `padding_top` double unsigned NOT NULL,
  `lines` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `label_sheets`
-- 

INSERT INTO `label_sheets` (`id`, `labels_total`, `labels_row`, `labels_column`, `label_width`, `label_height`, `margin_left`, `margin_top`, `spacing_row`, `spacing_column`, `padding_left`, `padding_top`, `lines`) VALUES 
(1, 84, 4, 21, 36.6, 12.7, 7.5, 15, 0, 14, 3, 1, 4);

-- --------------------------------------------------------

-- 
-- 表的结构 `location`
-- 

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `user` tinyint(3) unsigned NOT NULL,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `pid` mediumint(6) unsigned NOT NULL,
  `isbox` tinyint(1) unsigned NOT NULL default '0',
  `box_size` varchar(10) collate utf8_unicode_ci NOT NULL,
  `note` text collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=825 ;

-- 
-- 导出表中的数据 `location`
-- 

INSERT INTO `location` (`id`, `user`, `name`, `pid`, `isbox`, `box_size`, `note`) VALUES 
(1, 0, 'LabMap', 0, 0, '', 'lj'),
(2, 0, 'Room-227', 1, 0, '', ''),
(3, 0, 'Room-130', 1, 0, '', ''),
(4, 0, 'Room-131', 1, 0, '', ''),
(5, 0, 'Room-136', 1, 0, '', ''),
(6, 0, 'Freezer-01', 2, 0, '', ''),
(7, 0, 'Rack-02', 6, 0, '', ''),
(8, 0, 'Position-01', 7, 0, '', ''),
(9, 0, 'Position-02', 7, 0, '', ''),
(10, 0, 'Position-03', 7, 0, '', ''),
(11, 0, 'Position-04', 7, 0, '', ''),
(12, 0, 'Position-05', 7, 0, '', ''),
(13, 0, 'Position-06', 7, 0, '', ''),
(14, 0, 'Position-07', 7, 0, '', ''),
(15, 0, 'Position-08', 7, 0, '', ''),
(16, 0, 'Position-09', 7, 0, '', ''),
(17, 0, 'Position-10', 7, 0, '', ''),
(18, 0, 'Position-11', 7, 0, '', ''),
(19, 0, 'Position-12', 7, 0, '', ''),
(20, 0, 'Position-13', 7, 0, '', ''),
(21, 0, 'Position-14', 7, 0, '', ''),
(22, 0, 'Position-15', 7, 0, '', ''),
(23, 0, 'Position-16', 7, 0, '', ''),
(24, 0, 'Position-17', 7, 0, '', ''),
(25, 0, 'Position-18', 7, 0, '', ''),
(26, 0, 'Position-19', 7, 0, '', ''),
(27, 0, 'Position-20', 7, 0, '', ''),
(28, 0, 'Rack-01', 6, 0, '', ''),
(29, 0, 'Position-01', 28, 0, '', ''),
(30, 0, 'Position-02', 28, 0, '', ''),
(31, 0, 'Position-03', 28, 0, '', ''),
(32, 0, 'Position-04', 28, 0, '', ''),
(33, 0, 'Position-05', 28, 0, '', ''),
(34, 0, 'Position-06', 28, 0, '', ''),
(35, 0, 'Position-07', 28, 0, '', ''),
(36, 0, 'Position-08', 28, 0, '', ''),
(37, 0, 'Position-09', 28, 0, '', ''),
(38, 0, 'Position-10', 28, 0, '', ''),
(39, 0, 'Position-11', 28, 0, '', ''),
(40, 0, 'Position-12', 28, 0, '', ''),
(41, 0, 'Position-13', 28, 0, '', ''),
(42, 0, 'Position-14', 28, 0, '', ''),
(43, 0, 'Position-15', 28, 0, '', ''),
(44, 0, 'Position-16', 28, 0, '', ''),
(45, 0, 'Position-17', 28, 0, '', ''),
(46, 0, 'Position-18', 28, 0, '', ''),
(47, 0, 'Position-19', 28, 0, '', ''),
(48, 0, 'Position-20', 28, 0, '', ''),
(49, 0, 'Rack-03', 6, 0, '', ''),
(50, 0, 'Position-01', 49, 0, '', ''),
(51, 0, 'Position-02', 49, 0, '', ''),
(52, 0, 'Position-03', 49, 0, '', ''),
(53, 0, 'Position-04', 49, 0, '', ''),
(54, 0, 'Position-05', 49, 0, '', ''),
(55, 0, 'Position-06', 49, 0, '', ''),
(56, 0, 'Position-07', 49, 0, '', ''),
(57, 0, 'Position-08', 49, 0, '', ''),
(58, 0, 'Position-09', 49, 0, '', ''),
(59, 0, 'Position-10', 49, 0, '', ''),
(60, 0, 'Position-11', 49, 0, '', ''),
(61, 0, 'Position-12', 49, 0, '', ''),
(62, 0, 'Position-13', 49, 0, '', ''),
(63, 0, 'Position-14', 49, 0, '', ''),
(64, 0, 'Position-15', 49, 0, '', ''),
(65, 0, 'Position-16', 49, 0, '', ''),
(66, 0, 'Position-17', 49, 0, '', ''),
(67, 0, 'Position-18', 49, 0, '', ''),
(68, 0, 'Position-19', 49, 0, '', ''),
(69, 0, 'Position-20', 49, 0, '', ''),
(70, 0, 'Rack-04', 6, 0, '', ''),
(71, 0, 'Position-01', 70, 0, '', ''),
(72, 0, 'Position-02', 70, 0, '', ''),
(73, 0, 'Position-03', 70, 0, '', ''),
(74, 0, 'Position-04', 70, 0, '', ''),
(75, 0, 'Position-05', 70, 0, '', ''),
(76, 0, 'Position-06', 70, 0, '', ''),
(77, 0, 'Position-07', 70, 0, '', ''),
(78, 0, 'Position-08', 70, 0, '', ''),
(79, 0, 'Position-09', 70, 0, '', ''),
(80, 0, 'Position-10', 70, 0, '', ''),
(81, 0, 'Position-11', 70, 0, '', ''),
(82, 0, 'Position-12', 70, 0, '', ''),
(83, 0, 'Position-13', 70, 0, '', ''),
(84, 0, 'Position-14', 70, 0, '', ''),
(85, 0, 'Position-15', 70, 0, '', ''),
(86, 0, 'Position-16', 70, 0, '', ''),
(87, 0, 'Position-17', 70, 0, '', ''),
(88, 0, 'Position-18', 70, 0, '', ''),
(89, 0, 'Position-19', 70, 0, '', ''),
(90, 0, 'Position-20', 70, 0, '', ''),
(91, 0, 'Rack-05', 6, 0, '', ''),
(92, 0, 'Position-01', 91, 0, '', ''),
(93, 0, 'Position-02', 91, 0, '', ''),
(94, 0, 'Position-03', 91, 0, '', ''),
(95, 0, 'Position-04', 91, 0, '', ''),
(96, 0, 'Position-05', 91, 0, '', ''),
(97, 0, 'Position-06', 91, 0, '', ''),
(98, 0, 'Position-07', 91, 0, '', ''),
(99, 0, 'Position-08', 91, 0, '', ''),
(100, 0, 'Position-09', 91, 0, '', ''),
(101, 0, 'Position-10', 91, 0, '', ''),
(102, 0, 'Position-11', 91, 0, '', ''),
(103, 0, 'Position-12', 91, 0, '', ''),
(104, 0, 'Position-13', 91, 0, '', ''),
(105, 0, 'Position-14', 91, 0, '', ''),
(106, 0, 'Position-15', 91, 0, '', ''),
(107, 0, 'Position-16', 91, 0, '', ''),
(108, 0, 'Position-17', 91, 0, '', ''),
(109, 0, 'Position-18', 91, 0, '', ''),
(110, 0, 'Position-19', 91, 0, '', ''),
(111, 0, 'Position-20', 91, 0, '', ''),
(112, 0, 'Rack-06', 6, 0, '', ''),
(113, 0, 'Position-01', 112, 0, '', ''),
(114, 0, 'Position-02', 112, 0, '', ''),
(115, 0, 'Position-03', 112, 0, '', ''),
(116, 0, 'Position-04', 112, 0, '', ''),
(117, 0, 'Position-05', 112, 0, '', ''),
(118, 0, 'Position-06', 112, 0, '', ''),
(119, 0, 'Position-07', 112, 0, '', ''),
(120, 0, 'Position-08', 112, 0, '', ''),
(121, 0, 'Position-09', 112, 0, '', ''),
(122, 0, 'Position-10', 112, 0, '', ''),
(123, 0, 'Position-11', 112, 0, '', ''),
(124, 0, 'Position-12', 112, 0, '', ''),
(125, 0, 'Position-13', 112, 0, '', ''),
(126, 0, 'Position-14', 112, 0, '', ''),
(127, 0, 'Position-15', 112, 0, '', ''),
(128, 0, 'Position-16', 112, 0, '', ''),
(129, 0, 'Position-17', 112, 0, '', ''),
(130, 0, 'Position-18', 112, 0, '', ''),
(131, 0, 'Position-19', 112, 0, '', ''),
(132, 0, 'Position-20', 112, 0, '', ''),
(133, 0, 'Rack-07', 6, 0, '', ''),
(134, 0, 'Position-01', 133, 0, '', ''),
(135, 0, 'Position-02', 133, 0, '', ''),
(136, 0, 'Position-03', 133, 0, '', ''),
(137, 0, 'Position-04', 133, 0, '', ''),
(138, 0, 'Position-05', 133, 0, '', ''),
(139, 0, 'Position-06', 133, 0, '', ''),
(140, 0, 'Position-07', 133, 0, '', ''),
(141, 0, 'Position-08', 133, 0, '', ''),
(142, 0, 'Position-09', 133, 0, '', ''),
(143, 0, 'Position-10', 133, 0, '', ''),
(144, 0, 'Position-11', 133, 0, '', ''),
(145, 0, 'Position-12', 133, 0, '', ''),
(146, 0, 'Position-13', 133, 0, '', ''),
(147, 0, 'Position-14', 133, 0, '', ''),
(148, 0, 'Position-15', 133, 0, '', ''),
(149, 0, 'Position-16', 133, 0, '', ''),
(150, 0, 'Position-17', 133, 0, '', ''),
(151, 0, 'Position-18', 133, 0, '', ''),
(152, 0, 'Position-19', 133, 0, '', ''),
(153, 0, 'Position-20', 133, 0, '', ''),
(154, 0, 'Rack-08', 6, 0, '', ''),
(155, 0, 'Position-01', 154, 0, '', ''),
(156, 0, 'Position-02', 154, 0, '', ''),
(157, 0, 'Position-03', 154, 0, '', ''),
(158, 0, 'Position-04', 154, 0, '', ''),
(159, 0, 'Position-05', 154, 0, '', ''),
(160, 0, 'Position-06', 154, 0, '', ''),
(161, 0, 'Position-07', 154, 0, '', ''),
(162, 0, 'Position-08', 154, 0, '', ''),
(163, 0, 'Position-09', 154, 0, '', ''),
(164, 0, 'Position-10', 154, 0, '', ''),
(165, 0, 'Position-11', 154, 0, '', ''),
(166, 0, 'Position-12', 154, 0, '', ''),
(167, 0, 'Position-13', 154, 0, '', ''),
(168, 0, 'Position-14', 154, 0, '', ''),
(169, 0, 'Position-15', 154, 0, '', ''),
(170, 0, 'Position-16', 154, 0, '', ''),
(171, 0, 'Position-17', 154, 0, '', ''),
(172, 0, 'Position-18', 154, 0, '', ''),
(173, 0, 'Position-19', 154, 0, '', ''),
(174, 0, 'Position-20', 154, 0, '', ''),
(175, 0, 'Rack-09', 6, 0, '', ''),
(176, 0, 'Position-01', 175, 0, '', ''),
(177, 0, 'Position-02', 175, 0, '', ''),
(178, 0, 'Position-03', 175, 0, '', ''),
(179, 0, 'Position-04', 175, 0, '', ''),
(180, 0, 'Position-05', 175, 0, '', ''),
(181, 0, 'Position-06', 175, 0, '', ''),
(182, 0, 'Position-07', 175, 0, '', ''),
(183, 0, 'Position-08', 175, 0, '', ''),
(184, 0, 'Position-09', 175, 0, '', ''),
(185, 0, 'Position-10', 175, 0, '', ''),
(186, 0, 'Position-11', 175, 0, '', ''),
(187, 0, 'Position-12', 175, 0, '', ''),
(188, 0, 'Position-13', 175, 0, '', ''),
(189, 0, 'Position-14', 175, 0, '', ''),
(190, 0, 'Position-15', 175, 0, '', ''),
(191, 0, 'Position-16', 175, 0, '', ''),
(192, 0, 'Position-17', 175, 0, '', ''),
(193, 0, 'Position-18', 175, 0, '', ''),
(194, 0, 'Position-19', 175, 0, '', ''),
(195, 0, 'Position-20', 175, 0, '', ''),
(196, 0, 'Rack-10', 6, 0, '', ''),
(197, 0, 'Position-01', 196, 0, '', ''),
(198, 0, 'Position-02', 196, 0, '', ''),
(199, 0, 'Position-03', 196, 0, '', ''),
(200, 0, 'Position-04', 196, 0, '', ''),
(201, 0, 'Position-05', 196, 0, '', ''),
(202, 0, 'Position-06', 196, 0, '', ''),
(203, 0, 'Position-07', 196, 0, '', ''),
(204, 0, 'Position-08', 196, 0, '', ''),
(205, 0, 'Position-09', 196, 0, '', ''),
(206, 0, 'Position-10', 196, 0, '', ''),
(207, 0, 'Position-11', 196, 0, '', ''),
(208, 0, 'Position-12', 196, 0, '', ''),
(209, 0, 'Position-13', 196, 0, '', ''),
(210, 0, 'Position-14', 196, 0, '', ''),
(211, 0, 'Position-15', 196, 0, '', ''),
(212, 0, 'Position-16', 196, 0, '', ''),
(213, 0, 'Position-17', 196, 0, '', ''),
(214, 0, 'Position-18', 196, 0, '', ''),
(215, 0, 'Position-19', 196, 0, '', ''),
(216, 0, 'Position-20', 196, 0, '', ''),
(217, 0, 'Rack-11', 6, 0, '', ''),
(218, 0, 'Position-01', 217, 0, '', ''),
(219, 0, 'Position-02', 217, 0, '', ''),
(220, 0, 'Position-03', 217, 0, '', ''),
(221, 0, 'Position-04', 217, 0, '', ''),
(222, 0, 'Position-05', 217, 0, '', ''),
(223, 0, 'Position-06', 217, 0, '', ''),
(224, 0, 'Position-07', 217, 0, '', ''),
(225, 0, 'Position-08', 217, 0, '', ''),
(226, 0, 'Position-09', 217, 0, '', ''),
(227, 0, 'Position-10', 217, 0, '', ''),
(228, 0, 'Position-11', 217, 0, '', ''),
(229, 0, 'Position-12', 217, 0, '', ''),
(230, 0, 'Position-13', 217, 0, '', ''),
(231, 0, 'Position-14', 217, 0, '', ''),
(232, 0, 'Position-15', 217, 0, '', ''),
(233, 0, 'Position-16', 217, 0, '', ''),
(234, 0, 'Position-17', 217, 0, '', ''),
(235, 0, 'Position-18', 217, 0, '', ''),
(236, 0, 'Position-19', 217, 0, '', ''),
(237, 0, 'Position-20', 217, 0, '', ''),
(238, 0, 'Rack-12', 6, 0, '', ''),
(239, 0, 'Position-01', 238, 0, '', ''),
(240, 0, 'Position-02', 238, 0, '', ''),
(241, 0, 'Position-03', 238, 0, '', ''),
(242, 0, 'Position-04', 238, 0, '', ''),
(243, 0, 'Position-05', 238, 0, '', ''),
(244, 0, 'Position-06', 238, 0, '', ''),
(245, 0, 'Position-07', 238, 0, '', ''),
(246, 0, 'Position-08', 238, 0, '', ''),
(247, 0, 'Position-09', 238, 0, '', ''),
(248, 0, 'Position-10', 238, 0, '', ''),
(249, 0, 'Position-11', 238, 0, '', ''),
(250, 0, 'Position-12', 238, 0, '', ''),
(251, 0, 'Position-13', 238, 0, '', ''),
(252, 0, 'Position-14', 238, 0, '', ''),
(253, 0, 'Position-15', 238, 0, '', ''),
(254, 0, 'Position-16', 238, 0, '', ''),
(255, 0, 'Position-17', 238, 0, '', ''),
(256, 0, 'Position-18', 238, 0, '', ''),
(257, 0, 'Position-19', 238, 0, '', ''),
(258, 0, 'Position-20', 238, 0, '', ''),
(259, 0, 'Rack-13', 6, 0, '', ''),
(260, 0, 'Position-01', 259, 0, '', ''),
(261, 0, 'Position-02', 259, 0, '', ''),
(262, 0, 'Position-03', 259, 0, '', ''),
(263, 0, 'Position-04', 259, 0, '', ''),
(264, 0, 'Position-05', 259, 0, '', ''),
(265, 0, 'Position-06', 259, 0, '', ''),
(266, 0, 'Position-07', 259, 0, '', ''),
(267, 0, 'Position-08', 259, 0, '', ''),
(268, 0, 'Position-09', 259, 0, '', ''),
(269, 0, 'Position-10', 259, 0, '', ''),
(270, 0, 'Position-11', 259, 0, '', ''),
(271, 0, 'Position-12', 259, 0, '', ''),
(272, 0, 'Position-13', 259, 0, '', ''),
(273, 0, 'Position-14', 259, 0, '', ''),
(274, 0, 'Position-15', 259, 0, '', ''),
(275, 0, 'Position-16', 259, 0, '', ''),
(276, 0, 'Position-17', 259, 0, '', ''),
(277, 0, 'Position-18', 259, 0, '', ''),
(278, 0, 'Position-19', 259, 0, '', ''),
(279, 0, 'Position-20', 259, 0, '', ''),
(280, 0, 'Rack-14', 6, 0, '', ''),
(281, 0, 'Position-01', 280, 0, '', ''),
(282, 0, 'Position-02', 280, 0, '', ''),
(283, 0, 'Position-03', 280, 0, '', ''),
(284, 0, 'Position-04', 280, 0, '', ''),
(285, 0, 'Position-05', 280, 0, '', ''),
(286, 0, 'Position-06', 280, 0, '', ''),
(287, 0, 'Position-07', 280, 0, '', ''),
(288, 0, 'Position-08', 280, 0, '', ''),
(289, 0, 'Position-09', 280, 0, '', ''),
(290, 0, 'Position-10', 280, 0, '', ''),
(291, 0, 'Position-11', 280, 0, '', ''),
(292, 0, 'Position-12', 280, 0, '', ''),
(293, 0, 'Position-13', 280, 0, '', ''),
(294, 0, 'Position-14', 280, 0, '', ''),
(295, 0, 'Position-15', 280, 0, '', ''),
(296, 0, 'Position-16', 280, 0, '', ''),
(297, 0, 'Position-17', 280, 0, '', ''),
(298, 0, 'Position-18', 280, 0, '', ''),
(299, 0, 'Position-19', 280, 0, '', ''),
(300, 0, 'Position-20', 280, 0, '', ''),
(433, 0, 'Rack-03', 427, 0, '', ''),
(432, 0, 'Rack-02', 427, 0, '', ''),
(431, 0, 'Rack-01', 427, 0, '', ''),
(430, 0, 'Rack-03', 426, 0, '', ''),
(429, 0, 'Rack-02', 426, 0, '', ''),
(428, 0, 'Rack-01', 426, 0, '', ''),
(427, 0, 'Bottom', 425, 0, '', ''),
(426, 0, 'Top', 425, 0, '', ''),
(424, 0, 'Rack-01', 414, 0, '', ''),
(423, 0, 'Rack-02', 414, 0, '', ''),
(422, 0, 'Rack-03', 414, 0, '', ''),
(421, 0, 'Rack-04', 414, 0, '', ''),
(420, 0, 'Rack-05', 414, 0, '', ''),
(419, 0, 'Rack-06', 414, 0, '', ''),
(418, 0, 'Rack-07', 414, 0, '', ''),
(417, 0, 'Rack-08', 414, 0, '', ''),
(416, 0, 'Drawer-01', 414, 0, '', ''),
(415, 0, 'Drawer-02', 414, 0, '', ''),
(414, 0, 'Top', 408, 0, '', ''),
(413, 0, 'Drawer-01', 409, 0, '', ''),
(412, 0, 'Drawer-02', 409, 0, '', ''),
(411, 0, 'Drawer-03', 409, 0, '', ''),
(407, 0, 'Drawer-04', 393, 0, '', ''),
(406, 1, 'Drawer-03', 393, 0, '', ''),
(405, 1, 'Drawer-02', 393, 0, '', ''),
(404, 5, 'Drawer-01', 393, 0, '', '-20'),
(403, 0, 'Drawer-02', 392, 0, '', ''),
(402, 0, 'Drawer-01', 392, 0, '', ''),
(401, 0, 'Rack-08', 392, 0, '', ''),
(400, 0, 'Rack-07', 392, 0, '', ''),
(399, 0, 'Rack-06', 392, 0, '', ''),
(398, 0, 'Rack-05', 392, 0, '', ''),
(397, 0, 'Rack-04', 392, 0, '', ''),
(396, 0, 'Rack-03', 392, 0, '', ''),
(395, 0, 'Rack-02', 392, 0, '', ''),
(394, 0, 'Rack-01', 392, 0, '', ''),
(393, 0, 'Bottom', 385, 0, '', ''),
(392, 0, 'Top', 385, 0, '', ''),
(563, 0, 'Refrigerator-17', 4, 0, '', ''),
(343, 0, 'Rack-15', 6, 0, '', ''),
(344, 0, 'Position-01', 343, 0, '', ''),
(345, 0, 'Position-02', 343, 0, '', ''),
(346, 0, 'Position-03', 343, 0, '', ''),
(347, 0, 'Position-04', 343, 0, '', ''),
(348, 0, 'Position-05', 343, 0, '', ''),
(349, 0, 'Position-06', 343, 0, '', ''),
(350, 0, 'Position-07', 343, 0, '', ''),
(351, 0, 'Position-08', 343, 0, '', ''),
(352, 0, 'Position-09', 343, 0, '', ''),
(353, 0, 'Position-10', 343, 0, '', ''),
(354, 0, 'Position-11', 343, 0, '', ''),
(355, 0, 'Position-12', 343, 0, '', ''),
(356, 0, 'Position-13', 343, 0, '', ''),
(357, 0, 'Position-14', 343, 0, '', ''),
(358, 0, 'Position-15', 343, 0, '', ''),
(359, 0, 'Position-16', 343, 0, '', ''),
(360, 0, 'Position-17', 343, 0, '', ''),
(361, 0, 'Position-18', 343, 0, '', ''),
(362, 0, 'Position-19', 343, 0, '', ''),
(363, 0, 'Position-20', 343, 0, '', ''),
(364, 0, 'Rack-16', 6, 0, '', ''),
(365, 0, 'Position-01', 364, 0, '', ''),
(366, 0, 'Position-02', 364, 0, '', ''),
(367, 0, 'Position-03', 364, 0, '', ''),
(368, 0, 'Position-04', 364, 0, '', ''),
(369, 0, 'Position-05', 364, 0, '', ''),
(370, 0, 'Position-06', 364, 0, '', ''),
(371, 0, 'Position-07', 364, 0, '', ''),
(372, 0, 'Position-08', 364, 0, '', ''),
(373, 0, 'Position-09', 364, 0, '', ''),
(374, 0, 'Position-10', 364, 0, '', ''),
(375, 0, 'Position-11', 364, 0, '', ''),
(376, 0, 'Position-12', 364, 0, '', ''),
(377, 0, 'Position-13', 364, 0, '', ''),
(378, 0, 'Position-14', 364, 0, '', ''),
(379, 0, 'Position-15', 364, 0, '', ''),
(380, 0, 'Position-16', 364, 0, '', ''),
(381, 0, 'Position-17', 364, 0, '', ''),
(382, 0, 'Position-18', 364, 0, '', ''),
(383, 0, 'Position-19', 364, 0, '', ''),
(384, 0, 'Position-20', 364, 0, '', ''),
(385, 0, 'Refrigerator-02', 2, 0, '', ''),
(410, 0, 'Drawer-04', 409, 0, '', ''),
(409, 0, 'Bottom', 408, 0, '', ''),
(425, 0, 'Refrigerator-08', 3, 0, '', ''),
(408, 0, 'Refrigerator-03', 2, 0, '', ''),
(434, 0, 'Refrigerator-09', 3, 0, '', ''),
(435, 0, 'Rack-01', 434, 0, '', ''),
(436, 0, 'Rack-02', 434, 0, '', ''),
(437, 0, 'Rack-03', 434, 0, '', ''),
(438, 0, 'Rack-04', 434, 0, '', ''),
(439, 0, 'Rack-05', 434, 0, '', ''),
(440, 0, 'Rack-06', 434, 0, '', ''),
(441, 0, 'Refrigerator-10', 3, 0, '', ''),
(442, 0, 'Top', 441, 0, '', ''),
(443, 0, 'Bottom', 441, 0, '', ''),
(444, 0, 'Rack-01', 442, 0, '', ''),
(445, 0, 'Rack-02', 442, 0, '', ''),
(446, 0, 'Rack-03', 442, 0, '', ''),
(447, 0, 'Rack-04', 442, 0, '', ''),
(448, 0, 'Rack-05', 442, 0, '', ''),
(449, 0, 'Rack-06', 442, 0, '', ''),
(450, 0, 'Rack-07', 442, 0, '', ''),
(451, 0, 'Drawer-01', 443, 0, '', ''),
(452, 0, 'Drawer-02', 443, 0, '', ''),
(453, 0, 'Drawer-03', 443, 0, '', ''),
(454, 0, 'Drawer-04', 443, 0, '', ''),
(455, 0, 'Refrigerator-11', 3, 0, '', ''),
(456, 0, 'Top', 455, 0, '', ''),
(457, 0, 'Rack-01', 456, 0, '', ''),
(458, 0, 'Rack-02', 456, 0, '', ''),
(459, 0, 'Rack-03', 456, 0, '', ''),
(460, 0, 'Rack-04', 456, 0, '', ''),
(461, 0, 'Rack-05', 456, 0, '', ''),
(462, 0, 'Rack-06', 456, 0, '', ''),
(463, 0, 'Rack-07', 456, 0, '', ''),
(464, 0, 'Bottom', 455, 0, '', ''),
(465, 0, 'Drawer-01', 464, 0, '', ''),
(466, 0, 'Drawer-02', 464, 0, '', ''),
(467, 0, 'Drawer-03', 464, 0, '', ''),
(468, 0, 'Drawer-04', 464, 0, '', ''),
(469, 0, 'Refrigerator-12', 3, 0, '', ''),
(470, 0, 'Top', 469, 0, '', ''),
(471, 0, 'Bottom', 469, 0, '', ''),
(472, 0, 'Rack-01', 470, 0, '', ''),
(473, 0, 'Rack-02', 470, 0, '', ''),
(474, 0, 'Rack-03', 470, 0, '', ''),
(475, 0, 'Rack-04', 470, 0, '', ''),
(476, 0, 'Rack-05', 470, 0, '', ''),
(477, 0, 'Rack-06', 470, 0, '', ''),
(478, 0, 'Rack-01', 471, 0, '', ''),
(479, 0, 'Rack-02', 471, 0, '', ''),
(480, 0, 'Rack-03', 471, 0, '', ''),
(481, 0, 'Rack-04', 471, 0, '', ''),
(482, 0, 'Rack-05', 471, 0, '', ''),
(483, 0, 'Rack-06', 471, 0, '', ''),
(484, 0, 'Rack-07', 471, 0, '', ''),
(485, 0, 'Rack-08', 471, 0, '', ''),
(486, 0, 'Refrigerator-13', 3, 0, '', ''),
(487, 0, 'Top', 486, 0, '', ''),
(488, 0, 'Rack-01', 487, 0, '', ''),
(489, 0, 'Rack-02', 487, 0, '', ''),
(490, 0, 'Rack-03', 487, 0, '', ''),
(491, 0, 'Rack-04', 487, 0, '', ''),
(492, 0, 'Rack-05', 487, 0, '', ''),
(493, 0, 'Rack-06', 487, 0, '', ''),
(494, 0, 'Rack-07', 487, 0, '', ''),
(495, 0, 'Bottom', 486, 0, '', ''),
(496, 0, 'Drawer-01', 495, 0, '', ''),
(497, 0, 'Drawer-02', 495, 0, '', ''),
(498, 0, 'Drawer-03', 495, 0, '', ''),
(499, 0, 'Drawer-04', 495, 0, '', ''),
(500, 0, 'Refrigerator-14', 3, 0, '', ''),
(501, 0, 'Top', 500, 0, '', ''),
(502, 0, 'Rack-01', 501, 0, '', ''),
(503, 0, 'Rack-02', 501, 0, '', ''),
(504, 0, 'Rack-03', 501, 0, '', ''),
(505, 0, 'Rack-04', 501, 0, '', ''),
(506, 0, 'Rack-05', 501, 0, '', ''),
(507, 0, 'Rack-06', 501, 0, '', ''),
(508, 0, 'Rack-07', 501, 0, '', ''),
(509, 0, 'Bottom', 500, 0, '', ''),
(510, 0, 'Drawer-01', 509, 0, '', ''),
(511, 0, 'Drawer-02', 509, 0, '', ''),
(512, 0, 'Drawer-03', 509, 0, '', ''),
(513, 0, 'Drawer-04', 509, 0, '', ''),
(514, 0, 'Refrigerator-15', 3, 0, '', ''),
(515, 0, 'Rack-01', 514, 0, '', ''),
(516, 0, 'Rack-02', 514, 0, '', ''),
(517, 0, 'Rack-03', 514, 0, '', ''),
(518, 0, 'Rack-04', 514, 0, '', ''),
(519, 0, 'Rack-05', 514, 0, '', ''),
(530, 0, 'Refrigerator-04', 2, 0, '', ''),
(521, 0, 'Refrigerator-16', 3, 0, '', ''),
(522, 0, 'Bottom', 521, 0, '', ''),
(523, 0, 'Rack-03', 522, 0, '', ''),
(524, 0, 'Rack-02', 522, 0, '', ''),
(525, 0, 'Rack-01', 522, 0, '', ''),
(526, 0, 'Top', 521, 0, '', ''),
(527, 0, 'Rack-03', 526, 0, '', ''),
(528, 0, 'Rack-02', 526, 0, '', ''),
(529, 0, 'Rack-01', 526, 0, '', ''),
(531, 0, 'Rack-01', 530, 0, '', ''),
(532, 0, 'Rack-02', 530, 0, '', ''),
(533, 0, 'Rack-03', 530, 0, '', ''),
(534, 0, 'Rack-04', 530, 0, '', ''),
(535, 0, 'Rack-05', 530, 0, '', ''),
(537, 0, 'Refrigerator-05', 2, 0, '', ''),
(538, 0, 'Rack-01', 537, 0, '', ''),
(539, 0, 'Rack-02', 537, 0, '', ''),
(540, 0, 'Rack-03', 537, 0, '', ''),
(541, 0, 'Rack-04', 537, 0, '', ''),
(542, 0, 'Rack-05', 537, 0, '', ''),
(543, 0, 'Refrigerator-06', 2, 0, '', ''),
(544, 0, 'Bottom', 543, 0, '', ''),
(545, 0, 'Rack-03', 544, 0, '', ''),
(546, 0, 'Rack-02', 544, 0, '', ''),
(547, 0, 'Rack-01', 544, 0, '', ''),
(548, 0, 'Top', 543, 0, '', ''),
(549, 0, 'Rack-03', 548, 0, '', ''),
(550, 0, 'Rack-02', 548, 0, '', ''),
(551, 0, 'Rack-01', 548, 0, '', ''),
(552, 0, 'Rack-04', 544, 0, '', ''),
(553, 0, 'Refrigerator-07', 2, 0, '', ''),
(554, 0, 'Bottom', 553, 0, '', ''),
(556, 0, 'Rack-02', 554, 0, '', ''),
(557, 0, 'Rack-01', 554, 0, '', ''),
(564, 0, 'Bottom', 563, 0, '', ''),
(559, 0, 'Top', 553, 0, '', ''),
(560, 0, 'Rack-03', 559, 0, '', ''),
(561, 0, 'Rack-02', 559, 0, '', ''),
(562, 0, 'Rack-01', 559, 0, '', ''),
(565, 0, 'Rack-03', 564, 0, '', ''),
(566, 0, 'Rack-02', 564, 0, '', ''),
(567, 0, 'Rack-01', 564, 0, '', ''),
(568, 0, 'Top', 563, 0, '', ''),
(569, 0, 'Rack-03', 568, 0, '', ''),
(570, 0, 'Rack-02', 568, 0, '', ''),
(571, 0, 'Rack-01', 568, 0, '', ''),
(572, 0, 'Refrigerator-18', 4, 0, '', ''),
(573, 0, 'Rack-01', 572, 0, '', ''),
(574, 0, 'Rack-02', 572, 0, '', ''),
(575, 0, 'Rack-03', 572, 0, '', ''),
(576, 0, 'Rack-04', 572, 0, '', ''),
(577, 0, 'Rack-05', 572, 0, '', ''),
(578, 0, 'Rack-06', 572, 0, '', ''),
(579, 0, 'Refrigerator-19', 4, 0, '', ''),
(580, 0, 'Top', 579, 0, '', ''),
(581, 0, 'Rack-01', 580, 0, '', ''),
(582, 0, 'Rack-02', 580, 0, '', ''),
(583, 0, 'Rack-03', 580, 0, '', ''),
(584, 0, 'Rack-04', 580, 0, '', ''),
(585, 0, 'Rack-05', 580, 0, '', ''),
(586, 0, 'Rack-06', 580, 0, '', ''),
(587, 0, 'Rack-07', 580, 0, '', ''),
(588, 0, 'Bottom', 579, 0, '', ''),
(589, 0, 'Drawer-01', 588, 0, '', ''),
(590, 0, 'Drawer-02', 588, 0, '', ''),
(591, 0, 'Drawer-03', 588, 0, '', ''),
(592, 0, 'Drawer-04', 588, 0, '', ''),
(593, 0, 'Refrigerator-20', 4, 0, '', ''),
(594, 0, 'Bottom', 593, 0, '', ''),
(595, 0, 'Rack-03', 594, 0, '', ''),
(596, 0, 'Rack-02', 594, 0, '', ''),
(597, 0, 'Rack-01', 594, 0, '', ''),
(598, 0, 'Top', 593, 0, '', ''),
(599, 0, 'Rack-03', 598, 0, '', ''),
(600, 0, 'Rack-02', 598, 0, '', ''),
(601, 0, 'Rack-01', 598, 0, '', ''),
(602, 0, 'Rack-04', 594, 0, '', ''),
(603, 0, 'Refrigerator-21', 4, 0, '', ''),
(604, 0, 'Top', 603, 0, '', ''),
(605, 0, 'Rack-01', 604, 0, '', ''),
(606, 0, 'Rack-02', 604, 0, '', ''),
(607, 0, 'Rack-03', 604, 0, '', ''),
(608, 0, 'Rack-04', 604, 0, '', ''),
(609, 0, 'Rack-05', 604, 0, '', ''),
(610, 0, 'Rack-06', 604, 0, '', ''),
(611, 0, 'Rack-07', 604, 0, '', ''),
(612, 0, 'Bottom', 603, 0, '', ''),
(613, 0, 'Drawer-01', 612, 0, '', ''),
(614, 0, 'Drawer-02', 612, 0, '', ''),
(615, 0, 'Drawer-03', 612, 0, '', ''),
(616, 0, 'Drawer-04', 612, 0, '', ''),
(617, 0, 'Refrigerator-22', 4, 0, '', ''),
(618, 0, 'Top', 617, 0, '', ''),
(619, 0, 'Rack-01', 618, 0, '', ''),
(620, 0, 'Rack-02', 618, 0, '', ''),
(621, 0, 'Rack-03', 618, 0, '', ''),
(622, 0, 'Rack-04', 618, 0, '', ''),
(623, 0, 'Rack-05', 618, 0, '', ''),
(624, 0, 'Rack-06', 618, 0, '', ''),
(625, 0, 'Rack-07', 618, 0, '', ''),
(626, 0, 'Bottom', 617, 0, '', ''),
(627, 0, 'Drawer-01', 626, 0, '', ''),
(628, 0, 'Drawer-02', 626, 0, '', ''),
(629, 0, 'Drawer-03', 626, 0, '', ''),
(630, 0, 'Drawer-04', 626, 0, '', ''),
(631, 0, 'Refrigerator-23', 4, 0, '', ''),
(632, 0, 'Top', 631, 0, '', ''),
(633, 0, 'Rack-01', 632, 0, '', ''),
(634, 0, 'Rack-02', 632, 0, '', ''),
(635, 0, 'Rack-03', 632, 0, '', ''),
(636, 0, 'Rack-04', 632, 0, '', ''),
(637, 0, 'Rack-05', 632, 0, '', ''),
(638, 0, 'Rack-06', 632, 0, '', ''),
(639, 0, 'Rack-07', 632, 0, '', ''),
(640, 0, 'Bottom', 631, 0, '', ''),
(641, 0, 'Drawer-01', 640, 0, '', ''),
(642, 0, 'Drawer-02', 640, 0, '', ''),
(643, 0, 'Drawer-03', 640, 0, '', ''),
(644, 0, 'Drawer-04', 640, 0, '', ''),
(645, 0, 'Refrigerator-24', 4, 0, '', ''),
(646, 0, 'Top', 645, 0, '', ''),
(647, 0, 'Rack-01', 646, 0, '', ''),
(648, 0, 'Rack-02', 646, 0, '', ''),
(649, 0, 'Rack-03', 646, 0, '', ''),
(650, 0, 'Rack-04', 646, 0, '', ''),
(651, 0, 'Rack-05', 646, 0, '', ''),
(652, 0, 'Rack-06', 646, 0, '', ''),
(653, 0, 'Rack-07', 646, 0, '', ''),
(654, 0, 'Bottom', 645, 0, '', ''),
(655, 0, 'Drawer-01', 654, 0, '', ''),
(656, 0, 'Drawer-02', 654, 0, '', ''),
(657, 0, 'Drawer-03', 654, 0, '', ''),
(658, 0, 'Drawer-04', 654, 0, '', ''),
(659, 0, 'Rack-08', 604, 0, '', ''),
(660, 0, 'Rack-09', 604, 0, '', ''),
(661, 0, 'Rack-08', 646, 0, '', ''),
(662, 0, 'Room-107', 1, 0, '', 'he'),
(663, 0, 'Refrigerator-27', 5, 0, '', ''),
(664, 0, 'Bottom', 663, 0, '', ''),
(665, 0, 'Drawer-04', 664, 0, '', ''),
(666, 0, 'Drawer-03', 664, 0, '', ''),
(667, 0, 'Drawer-02', 664, 0, '', ''),
(668, 0, 'Drawer-01', 664, 0, '', ''),
(669, 0, 'Top', 663, 0, '', ''),
(670, 0, 'Drawer-02', 669, 0, '', ''),
(671, 0, 'Drawer-01', 669, 0, '', ''),
(672, 0, 'Rack-08', 669, 0, '', ''),
(673, 0, 'Rack-07', 669, 0, '', ''),
(674, 0, 'Rack-06', 669, 0, '', ''),
(675, 0, 'Rack-05', 669, 0, '', ''),
(676, 0, 'Rack-04', 669, 0, '', ''),
(677, 0, 'Rack-03', 669, 0, '', ''),
(678, 0, 'Rack-02', 669, 0, '', ''),
(679, 0, 'Rack-01', 669, 0, '', ''),
(680, 0, 'Refrigerator-30', 5, 0, '', ''),
(681, 0, 'Bottom', 680, 0, '', ''),
(682, 0, 'Drawer-04', 681, 0, '', ''),
(683, 0, 'Drawer-03', 681, 0, '', ''),
(684, 0, 'Drawer-02', 681, 0, '', ''),
(685, 0, 'Drawer-01', 681, 0, '', ''),
(686, 0, 'Top', 680, 0, '', ''),
(687, 0, 'Drawer-02', 686, 0, '', ''),
(688, 0, 'Drawer-01', 686, 0, '', ''),
(689, 0, 'Rack-08', 686, 0, '', ''),
(690, 0, 'Rack-07', 686, 0, '', ''),
(691, 0, 'Rack-06', 686, 0, '', ''),
(692, 0, 'Rack-05', 686, 0, '', ''),
(693, 0, 'Rack-04', 686, 0, '', ''),
(694, 0, 'Rack-03', 686, 0, '', ''),
(695, 0, 'Rack-02', 686, 0, '', ''),
(696, 0, 'Rack-01', 686, 0, '', ''),
(697, 0, 'Refrigerator-28', 5, 0, '', ''),
(698, 0, 'Bottom', 697, 0, '', ''),
(699, 0, 'Rack-03', 698, 0, '', ''),
(700, 0, 'Rack-02', 698, 0, '', ''),
(701, 0, 'Rack-01', 698, 0, '', ''),
(702, 0, 'Top', 697, 0, '', ''),
(703, 0, 'Rack-03', 702, 0, '', ''),
(704, 0, 'Rack-02', 702, 0, '', ''),
(705, 0, 'Rack-01', 702, 0, '', ''),
(706, 0, 'Refrigerator-29', 5, 0, '', ''),
(707, 0, 'Bottom', 706, 0, '', ''),
(708, 0, 'Rack-03', 707, 0, '', ''),
(709, 0, 'Rack-02', 707, 0, '', ''),
(710, 0, 'Rack-01', 707, 0, '', ''),
(711, 0, 'Top', 706, 0, '', ''),
(712, 0, 'Rack-03', 711, 0, '', ''),
(713, 0, 'Rack-02', 711, 0, '', ''),
(714, 0, 'Rack-01', 711, 0, '', ''),
(715, 0, 'Refrigerator-31', 5, 0, '', ''),
(716, 0, 'Bottom', 715, 0, '', ''),
(717, 0, 'Rack-03', 716, 0, '', ''),
(718, 0, 'Rack-02', 716, 0, '', ''),
(719, 0, 'Rack-01', 716, 0, '', ''),
(720, 0, 'Top', 715, 0, '', ''),
(721, 0, 'Rack-03', 720, 0, '', ''),
(722, 0, 'Rack-02', 720, 0, '', ''),
(723, 0, 'Rack-01', 720, 0, '', ''),
(724, 0, 'Refrigerator-32', 5, 0, '', ''),
(725, 0, 'Bottom', 724, 0, '', ''),
(726, 0, 'Rack-03', 725, 0, '', ''),
(727, 0, 'Rack-02', 725, 0, '', ''),
(728, 0, 'Rack-01', 725, 0, '', ''),
(729, 0, 'Top', 724, 0, '', ''),
(730, 0, 'Rack-03', 729, 0, '', ''),
(731, 0, 'Rack-02', 729, 0, '', ''),
(732, 0, 'Rack-01', 729, 0, '', ''),
(733, 0, 'Freezer-25', 662, 0, '', ''),
(734, 0, 'Rack-01', 733, 0, '', ''),
(735, 5, 'Drawer-01', 734, 0, '', '-86'),
(736, 0, 'Drawer-02', 734, 0, '', ''),
(737, 0, 'Drawer-03', 734, 0, '', ''),
(738, 0, 'Drawer-04', 734, 0, '', ''),
(739, 0, 'Rack-02', 733, 0, '', ''),
(740, 0, 'Drawer-01', 739, 0, '', ''),
(741, 0, 'Drawer-02', 739, 0, '', ''),
(742, 0, 'Drawer-03', 739, 0, '', ''),
(743, 0, 'Drawer-04', 739, 0, '', ''),
(744, 0, 'Rack-03', 733, 0, '', ''),
(745, 0, 'Drawer-01', 744, 0, '', ''),
(746, 0, 'Drawer-02', 744, 0, '', ''),
(747, 0, 'Drawer-03', 744, 0, '', ''),
(748, 0, 'Drawer-04', 744, 0, '', ''),
(749, 0, 'Rack-04', 733, 0, '', ''),
(750, 0, 'Drawer-01', 749, 0, '', ''),
(751, 0, 'Drawer-02', 749, 0, '', ''),
(752, 0, 'Drawer-03', 749, 0, '', ''),
(753, 0, 'Drawer-04', 749, 0, '', ''),
(754, 0, 'Rack-05', 733, 0, '', ''),
(755, 0, 'Drawer-01', 754, 0, '', ''),
(756, 0, 'Drawer-02', 754, 0, '', ''),
(757, 0, 'Drawer-03', 754, 0, '', ''),
(758, 0, 'Drawer-04', 754, 0, '', ''),
(759, 0, 'Rack-06', 733, 0, '', ''),
(760, 0, 'Drawer-01', 759, 0, '', ''),
(761, 0, 'Drawer-02', 759, 0, '', ''),
(762, 0, 'Drawer-03', 759, 0, '', ''),
(763, 0, 'Drawer-04', 759, 0, '', ''),
(764, 0, 'Rack-07', 733, 0, '', ''),
(765, 0, 'Drawer-01', 764, 0, '', ''),
(766, 0, 'Drawer-02', 764, 0, '', ''),
(767, 0, 'Drawer-03', 764, 0, '', ''),
(768, 0, 'Drawer-04', 764, 0, '', ''),
(769, 0, 'Rack-08', 733, 0, '', ''),
(770, 0, 'Drawer-01', 769, 0, '', ''),
(771, 0, 'Drawer-02', 769, 0, '', ''),
(772, 0, 'Drawer-03', 769, 0, '', ''),
(773, 0, 'Drawer-04', 769, 0, '', ''),
(774, 0, 'Freezer-26', 662, 0, '', ''),
(775, 0, 'Rack-01', 774, 0, '', ''),
(776, 0, 'Drawer-01', 775, 0, '', ''),
(777, 0, 'Drawer-02', 775, 0, '', ''),
(778, 0, 'Drawer-03', 775, 0, '', ''),
(779, 0, 'Rack-02', 774, 0, '', ''),
(780, 0, 'Drawer-01', 779, 0, '', ''),
(781, 0, 'Drawer-02', 779, 0, '', ''),
(782, 0, 'Drawer-03', 779, 0, '', ''),
(783, 0, 'Rack-03', 774, 0, '', ''),
(784, 0, 'Drawer-01', 783, 0, '', ''),
(785, 0, 'Drawer-02', 783, 0, '', ''),
(786, 0, 'Drawer-03', 783, 0, '', ''),
(787, 0, 'Rack-04', 774, 0, '', ''),
(788, 0, 'Drawer-01', 787, 0, '', ''),
(789, 0, 'Drawer-02', 787, 0, '', ''),
(790, 0, 'Drawer-03', 787, 0, '', ''),
(791, 0, 'Rack-05', 774, 0, '', ''),
(792, 0, 'Drawer-01', 791, 0, '', ''),
(793, 0, 'Drawer-02', 791, 0, '', ''),
(794, 0, 'Drawer-03', 791, 0, '', ''),
(795, 0, 'Rack-06', 774, 0, '', ''),
(796, 0, 'Drawer-01', 795, 0, '', ''),
(797, 0, 'Drawer-02', 795, 0, '', ''),
(798, 0, 'Drawer-03', 795, 0, '', ''),
(799, 0, 'Rack-07', 774, 0, '', ''),
(800, 0, 'Drawer-01', 799, 0, '', ''),
(801, 0, 'Drawer-02', 799, 0, '', ''),
(802, 0, 'Drawer-03', 799, 0, '', ''),
(803, 0, 'Rack-08', 774, 0, '', ''),
(804, 0, 'Drawer-01', 803, 0, '', ''),
(805, 0, 'Drawer-02', 803, 0, '', ''),
(806, 0, 'Drawer-03', 803, 0, '', ''),
(807, 0, 'Rack-09', 774, 0, '', ''),
(808, 0, 'Drawer-01', 807, 0, '', ''),
(809, 0, 'Drawer-02', 807, 0, '', ''),
(810, 0, 'Drawer-03', 807, 0, '', ''),
(811, 0, 'Rack-10', 774, 0, '', ''),
(812, 0, 'Drawer-01', 811, 0, '', ''),
(813, 0, 'Drawer-02', 811, 0, '', ''),
(814, 0, 'Drawer-03', 811, 0, '', ''),
(815, 0, 'Refrigerator-33', 4, 0, '', ''),
(816, 0, 'Left', 815, 0, '', ''),
(817, 0, 'Rack-01', 816, 0, '', ''),
(818, 0, 'Rack-02', 816, 0, '', ''),
(819, 0, 'Rack-03', 816, 0, '', ''),
(820, 0, 'Right', 815, 0, '', ''),
(821, 0, 'Rack-01', 820, 0, '', ''),
(822, 0, 'Rack-02', 820, 0, '', ''),
(823, 0, 'Rack-03', 820, 0, '', ''),
(824, 0, 'new', 3, 1, '', '');

-- --------------------------------------------------------

-- 
-- 表的结构 `mail`
-- 

DROP TABLE IF EXISTS `mail`;
CREATE TABLE IF NOT EXISTS `mail` (
  `smtpserver` varchar(100) NOT NULL,
  `smtpserverport` varchar(10) NOT NULL,
  `smtpusermail` varchar(100) NOT NULL,
  `smtpuser` varchar(100) NOT NULL,
  `smtppass` varchar(100) NOT NULL,
  `mailtype` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- 导出表中的数据 `mail`
-- 

INSERT INTO `mail` (`smtpserver`, `smtpserverport`, `smtpusermail`, `smtpuser`, `smtppass`, `mailtype`) VALUES 
('mail.shcnc.ac.cn', '25', 'ljgroup@mail.shcnc.ac.cn', 'ljgroup@mail.shcnc.ac.cn', 'hpyu0000', 'HTML');

-- --------------------------------------------------------

-- 
-- 表的结构 `modules`
-- 

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- 
-- 导出表中的数据 `modules`
-- 

INSERT INTO `modules` (`id`, `name`) VALUES 
(1, 'primers'),
(2, 'proteins'),
(3, 'chemicals'),
(4, 'plasmids'),
(5, 'antibodies'),
(6, 'cells'),
(7, 'reagents'),
(8, 'supplies'),
(9, 'sequences'),
(10, 'animals'),
(11, 'samples');

-- --------------------------------------------------------

-- 
-- 表的结构 `orders`
-- 

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `module_id` tinyint(3) unsigned NOT NULL,
  `item_id` int(9) unsigned NOT NULL,
  `trade_name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `manufacturer` mediumint(6) unsigned NOT NULL,
  `dealer` mediumint(6) unsigned NOT NULL,
  `cat_nbr` varchar(30) collate utf8_unicode_ci NOT NULL,
  `lot_nbr` varchar(30) collate utf8_unicode_ci NOT NULL,
  `pack` varchar(50) collate utf8_unicode_ci NOT NULL,
  `qty` mediumint(6) unsigned default NULL,
  `unit_price` float(8,2) unsigned default NULL,
  `price` float(9,2) unsigned NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `approved_by` mediumint(6) unsigned NOT NULL,
  `ordered_by` mediumint(6) unsigned NOT NULL,
  `received_by` mediumint(6) unsigned NOT NULL,
  `cancelled_by` mediumint(6) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `approve_date` datetime NOT NULL,
  `order_date` datetime NOT NULL,
  `receive_date` datetime NOT NULL,
  `cancel_date` datetime NOT NULL,
  `invoice` varchar(50) collate utf8_unicode_ci NOT NULL,
  `account_id` tinyint(3) unsigned NOT NULL,
  `note` text collate utf8_unicode_ci,
  `state` tinyint(1) unsigned NOT NULL,
  `cancel` tinyint(1) unsigned NOT NULL,
  `mask` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- 
-- 导出表中的数据 `orders`
-- 

INSERT INTO `orders` (`id`, `module_id`, `item_id`, `trade_name`, `manufacturer`, `dealer`, `cat_nbr`, `lot_nbr`, `pack`, `qty`, `unit_price`, `price`, `created_by`, `approved_by`, `ordered_by`, `received_by`, `cancelled_by`, `create_date`, `approve_date`, `order_date`, `receive_date`, `cancel_date`, `invoice`, `account_id`, `note`, `state`, `cancel`, `mask`) VALUES 
(1, 1, 9, 'primers:caspase-3-f', 1, 54, '', '', 'OD', 2, 0.00, 100.00, 1, 1, 1, 1, 0, '2008-02-20 00:00:00', '2008-02-20 00:00:00', '2008-02-20 00:00:00', '2008-02-20 00:00:00', '0000-00-00 00:00:00', '11111111', 1, '', 4, 0, 0),
(3, 1, 10, 'primers:caspase-3-r', 0, 61, '', '', 'OD', 2, 0.00, 200.00, 1, 1, 1, 1, 0, '2008-02-21 00:00:00', '2008-02-21 00:00:00', '2008-02-21 00:00:00', '2008-02-21 00:00:00', '0000-00-00 00:00:00', '22222222', 2, '', 4, 0, 1),
(5, 1, 9, 'primers:caspase-3-f', 0, 61, '', '', 'od', 2, 0.00, 100.00, 1, 1, 1, 1, 0, '2008-02-21 00:00:00', '2008-02-21 00:00:00', '2008-02-21 00:00:00', '2008-02-21 00:00:00', '0000-00-00 00:00:00', '22222222', 1, '', 4, 0, 0),
(7, 0, 0, 'quick', 0, 1, '', '', '', 0, 0.00, 100.00, 1, 0, 0, 1, 0, '2008-02-24 00:00:00', '2008-03-02 00:00:00', '0000-00-00 00:00:00', '2008-03-02 00:00:00', '0000-00-00 00:00:00', '', 0, '', 4, 0, 0),
(8, 10, 1, 'animals:C57BL/6J (Mouse)', 0, 1, '', '', '', 5, 0.00, 1000.00, 1, 1, 0, 1, 1, '2008-03-01 00:00:00', '2008-03-02 00:00:00', '0000-00-00 00:00:00', '2008-03-08 00:00:00', '2008-03-09 00:00:00', '', 0, '', 2, 1, 0),
(10, 6, 5, 'cells:HCT 116', 0, 7, '', '', '', 1, 0.00, 10000.00, 1, 1, 0, 0, 0, '2008-08-28 00:00:00', '2008-08-28 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', 0, '', 2, 0, 0),
(11, 6, 5, 'cells:HCT 116', 0, 7, '', '', '', 1, 0.00, 10000.00, 1, 1, 0, 1, 0, '2008-08-28 00:00:00', '2008-08-28 00:00:00', '0000-00-00 00:00:00', '2008-08-28 00:00:00', '0000-00-00 00:00:00', '', 0, '', 4, 0, 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `orders_admin`
-- 

DROP TABLE IF EXISTS `orders_admin`;
CREATE TABLE IF NOT EXISTS `orders_admin` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `people_id` mediumint(6) unsigned NOT NULL,
  `lower_limit` varchar(9) NOT NULL,
  `upper_limit` varchar(9) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- 导出表中的数据 `orders_admin`
-- 

INSERT INTO `orders_admin` (`id`, `people_id`, `lower_limit`, `upper_limit`) VALUES 
(1, 11, '0', ''),
(2, 12, '1000', ''),
(3, 8, '1000', '');

-- --------------------------------------------------------

-- 
-- 表的结构 `orders_approve`
-- 

DROP TABLE IF EXISTS `orders_approve`;
CREATE TABLE IF NOT EXISTS `orders_approve` (
  `key` varchar(20) NOT NULL,
  `value` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- 导出表中的数据 `orders_approve`
-- 

INSERT INTO `orders_approve` (`key`, `value`) VALUES 
('is_approve', '1'),
('lower_limit', '0');

-- --------------------------------------------------------

-- 
-- 表的结构 `orders_mails`
-- 

DROP TABLE IF EXISTS `orders_mails`;
CREATE TABLE IF NOT EXISTS `orders_mails` (
  `key` varchar(20) NOT NULL,
  `value` tinyint(1) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- 导出表中的数据 `orders_mails`
-- 

INSERT INTO `orders_mails` (`key`, `value`) VALUES 
('is_request_mail', 1),
('is_approve_mail', 1),
('is_receive_mail', 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `people`
-- 

DROP TABLE IF EXISTS `people`;
CREATE TABLE IF NOT EXISTS `people` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `name` varchar(100) character set utf8 collate utf8_bin NOT NULL,
  `photo` varchar(50) collate utf8_unicode_ci NOT NULL,
  `signature` varchar(50) collate utf8_unicode_ci NOT NULL,
  `gender` tinyint(1) unsigned NOT NULL,
  `identity_card` varchar(18) collate utf8_unicode_ci NOT NULL,
  `email` varchar(100) collate utf8_unicode_ci NOT NULL,
  `im` varchar(100) collate utf8_unicode_ci NOT NULL,
  `mobile` varchar(20) collate utf8_unicode_ci NOT NULL,
  `tel` varchar(20) collate utf8_unicode_ci NOT NULL,
  `graduate_school` varchar(100) collate utf8_unicode_ci NOT NULL,
  `hometown` varchar(100) collate utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `date_enter` date NOT NULL,
  `date_leave` date NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  `status` varchar(20) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

-- 
-- 导出表中的数据 `people`
-- 

INSERT INTO `people` (`id`, `name`, `photo`, `signature`, `gender`, `identity_card`, `email`, `im`, `mobile`, `tel`, `graduate_school`, `hometown`, `birthday`, `date_enter`, `date_leave`, `state`, `status`) VALUES 
(1, 0xe8999ee6b5b7e5b9b3, 'data/people/photo_1.jpg', 'data/people/signature_1.jpg', 1, '330103198110141013', 'hpyu@mail.shcnc.ac.cn', '', '', '', '中国农业大学', '杭州', '1981-08-20', '2004-03-01', '0000-00-00', 0, '实习研究员'),
(2, 0xe982b1e89393e9a296, '', '', 0, '3.10102E+17', 'byqiu@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '博士研究生在读'),
(3, 0xe887a7e5a595, '', '', 0, '3.10108E+17', 'yzang@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '2037-07-03', '0000-00-00', 0, '博士研究生在读'),
(4, 0xe69d9ce4bf8ae58dbf, '', '', 1, '4.12728E+17', 'jqdu@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '博士研究生在读'),
(5, 0xe69bb9e893ace88da3, '', '', 1, '4.30103E+17', 'cucumber1306@hotmail.com', '', '', '', '', '', '2030-05-06', '0000-00-00', '0000-00-00', 0, 'student'),
(6, 0xe8b0a2e4bca0e6988e, '', '', 1, '34032119810830929x', 'cmxie@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, 'research assistant'),
(7, 0xe591a8e8b68ae6b48b, '', '', 1, '3.10112E+17', 'yyzhou@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '助理研究员'),
(8, 0xe69d8ee99d99e99b85, '', 'data/people/signature_8.jpg', 0, '222222222222222', 'jyli@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '副研究员'),
(9, 0xe5bca0e89687, '', '', 0, '4.12823E+17', 'zhangw@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '实习研究员'),
(10, 0xe591a8e5ae87e6b3a2, '', '', 1, '4.20111E+17', 'ybzhou@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '助理研究员'),
(11, 0xe9ab98e5ae89e685a7, '', '', 0, '3.70728E+17', 'ahgao@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '助理研究员'),
(12, 0xe69d8ee4bdb3, '', 'data/people/signature_12.jpg', 1, '111111111111111', 'jli@mail.shcnc.ac.cn', '', '', '50801313*132', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '研究员'),
(13, 0xe99988e78eb2e78eb2, '', '', 0, '0', 'llchen@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, '硕士研究生'),
(14, 0xe5bca0e4ba9ae8be89, '', '', 0, '0', 'yhzhang@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, '硕士研究生'),
(15, 0xe6af9be4bc9fe5b3b0, '', '', 1, '0', 'wfmao@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, '硕士研究生'),
(16, 0xe9a9ace58899e5bcba, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(17, 0xe5ba9ee6b69b, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(18, 0xe58da2e99d99e8908d, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(19, 0xe69da8e69993e5ae81, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(20, 0xe5bca0e5928fe9948b, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(21, 0xe5bca0e6b69b, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(22, 0xe99988e697ad, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(23, 0xe7a88be593b2, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(24, 0xe8b5b5e8bf9ce8839c, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(25, 0xe99988e6a281, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(26, 0xe78e8be4b8bde5a89c, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, 'NULL'),
(27, 0xe79fb3e7a38a, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(28, 0xe5be90e69db0, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, 'NULL'),
(29, 0xe9a9ace5ada6e790b4, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(30, 0xe69d8ee5aa9be5aa9b, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, 'NULL'),
(31, 0xe88b8fe6988ee6b3a2, '', '', 1, '3.42427E+17', 'mbsu@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, 'NULL'),
(32, 0xe982b5e4bc9f, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(33, 0xe69d8ee68890e6b5b7, '', '', 0, '', '', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, 'NULL'),
(34, 0xe9a9ace4b880e6988e, '', '', 1, '3.20903E+14', 'ymma@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '博士研究生在读'),
(35, 0xe5bca0e4b8bde5a89c, '', '', 0, '4.20105E+17', 'zhanglina96@126.com', 'zhanglina0906@hotmail.com', '', '021-50801313-246', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '硕士研究生在读'),
(36, 0xe99fa9e4bc9f, '', '', 0, '4.10203E+17', 'whan@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '硕士研究生在读'),
(37, 0xe590b4e88ab3, '', '', 0, '42011119780801702x', 'fwu@mail.shcnc.ac.cn', 'caoerwu@hotmail.com', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '助理研究员'),
(38, 0xe586afe697ad, '', '', 1, '4.30121E+17', 'xfeng@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 1, '硕士研究生在读'),
(39, 0xe983ade4bb81e69db0, '', '', 1, '3.10108E+17', 'purpleglory1985@hotmail.com', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '实习研究员'),
(40, 0xe5bdade5bbb6e6958f, '', '', 0, '3.70923E+17', 'ympeng@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '硕士研究生在读'),
(41, 0xe5ada3e587a4e6a285, '', '', 0, '3.10103E+17', 'fmji@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '2038-09-07', '0000-00-00', 0, '实习研究员'),
(42, 0xe69cb1e790bce88ab1, '', '', 0, '3.30183E+17', 'kelianxiaowanshi@126.com', 'kelianxiaowanshi@hotmail.com', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '硕士研究生在读'),
(43, 0xe6b288e5bcba, '', '', 0, '3.10115E+17', 'qshen@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '实验师'),
(44, 0xe79b9be4b8bd, '', '', 0, '3.1023E+17', 'shinhwavsli@sina.com', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '实习生'),
(45, 0xe99988e88c9c, '', '', 0, '4.20923E+17', 'aki1986and570@163.com', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '实习生'),
(47, 0xe5bca0e7bf80, '', '', 1, '', 'czhang@mail.shcnc.ac.cn', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, ''),
(48, 0xe88c83e4b8bde99c9e, '', '', 0, '', 'lxfan@hotmail.com', '', '', '', '', '', '0000-00-00', '0000-00-00', '0000-00-00', 0, '');

-- --------------------------------------------------------

-- 
-- 表的结构 `plasmid_sequences`
-- 

DROP TABLE IF EXISTS `plasmid_sequences`;
CREATE TABLE IF NOT EXISTS `plasmid_sequences` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `plasmid_id` int(9) unsigned NOT NULL,
  `sequence` mediumtext collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- 导出表中的数据 `plasmid_sequences`
-- 


-- --------------------------------------------------------

-- 
-- 表的结构 `plasmids`
-- 

DROP TABLE IF EXISTS `plasmids`;
CREATE TABLE IF NOT EXISTS `plasmids` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `vector_name` varchar(50) collate utf8_unicode_ci NOT NULL,
  `vector_type` varchar(100) collate utf8_unicode_ci NOT NULL,
  `map` varchar(50) collate utf8_unicode_ci NOT NULL,
  `isinsert` tinyint(1) unsigned NOT NULL default '0',
  `insert_name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `species` varchar(50) collate utf8_unicode_ci NOT NULL,
  `sequence_identifier` varchar(50) collate utf8_unicode_ci NOT NULL,
  `insert_size` varchar(20) collate utf8_unicode_ci NOT NULL,
  `modification` varchar(200) collate utf8_unicode_ci NOT NULL,
  `cloning_sites` varchar(100) collate utf8_unicode_ci NOT NULL,
  `tags` varchar(200) collate utf8_unicode_ci NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text collate utf8_unicode_ci,
  `mask` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=542 ;

-- 
-- 导出表中的数据 `plasmids`
-- 

INSERT INTO `plasmids` (`id`, `name`, `description`, `vector_name`, `vector_type`, `map`, `isinsert`, `insert_name`, `species`, `sequence_identifier`, `insert_size`, `modification`, `cloning_sites`, `tags`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(1, 'EcMetAP1', 'PA0001', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2000年9月构建，无突变', 0),
(2, 'HsMetAP1', 'PA0002', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2001年6月构建，无突变', 0),
(3, 'HsMetAP2', 'PA0003', 'pGEMEX-1', '', '', 1, '', 'Human', 'GI:903981', '', '23..1459 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '无突变', 0),
(4, 'EcY62F', 'PA0004', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Y62F', 0),
(5, 'EcY65F', 'PA0005', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Y65F', 0),
(6, 'EcD97N', 'PA0006', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'D97N', 0),
(7, 'EcD97E', 'PA0007', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'D97E', 0),
(8, 'EcY62A', 'PA0008', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Y62A', 0),
(9, 'EcY65A', 'PA0009', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Y65A', 0),
(10, 'EcS68A', 'PA0010', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'S68A', 0),
(11, 'EcH79A', 'PA0011', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'H79A', 0),
(12, 'EcF177A', 'PA0012', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'F177A', 0),
(13, 'EcH185A', 'PA0013', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'H185A', 0),
(14, 'EcM206A', 'PA0014', 'pGEMEX-1', '', '', 1, '', 'E.coli', '', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'M206A', 0),
(15, 'EcW221A', 'PA0015', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'W221A', 0),
(16, 'EcV223A', 'PA0016', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'V223A\r\n', 0),
(17, 'EcQ233A', 'PA0017', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Q233A\r\n', 0),
(18, 'EcY168A', 'PA0018', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219..1013 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Y168A\r\n', 0),
(19, 'HsY194A', 'PA0019', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Y194A\r\n', 0),
(20, 'HsP191A', 'PA0020', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'P191A\r\n', 0),
(21, 'HsC202A', 'PA0021', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'C202A\r\n', 0),
(22, 'HsS200A', 'PA0022', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'S200A\r\n', 0),
(23, 'HsF197A', 'PA0023', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'F197A\r\n', 0),
(24, 'HsH211A', 'PA0024', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'H211A\r\n', 0),
(25, 'HsF308A', 'PA0025', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'F308A\r\n', 0),
(26, 'HsH316A', 'PA0026', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'H316A\r\n', 0),
(27, 'HsW352A', 'PA0027', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'W352A\r\n', 0),
(28, 'HsQ364A', 'PA0028', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Q364A\r\n', 0),
(29, 'HsF199Y', 'PA0029', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'F199Y\r\n', 0),
(30, 'Hsr1-66', 'PA0030', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '224..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '1-66 amino acids deleted\r\n', 0),
(31, 'Hsr1-135', 'PA0031', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '431..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '1-135 amino acids deleted\r\n', 0),
(32, 'fhMetAP2KG', 'PA0032', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:903981', '', '23..1459 bp', 'Smal I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '无突变\r\n', 0),
(33, 'hMetAP2 r1-108', 'PA0033', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:903981', '', '347..1459 bp', 'Smal I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '1-108 amino acids deleted\r\n', 0),
(34, 'hMetAP2 r1-108', 'PA0034', 'pGEMEX-1', '', '', 1, '', 'Human', 'GI:903981', '', '347..1459 bp', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '1-108 amino acids deleted\r\n', 0),
(35, 'HsMetAP2 pFAST', 'PA0035', 'pFASTBAC', '', '', 1, '', 'Human', 'GI:903981', '', '23..1459 bp', 'Xbal I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Unknown\r\n', 0),
(36, 'HsMetAPpVT', 'PA0036', 'pVT', '', '', 1, '', 'Human', 'GI:903981', '', '23..1459 bp', 'Xbal I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '无突变\r\n', 0),
(37, 'HsMetAP1pFAST', 'PA0037', 'pFASTBAC HTa', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Not I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '无突变\r\n', 0),
(38, 'HsY298A', 'PA0038', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Y298A\r\n', 0),
(39, 'HsM337A', 'PA0039', 'pGEX-KG', '', '', 1, '', 'Human', 'GI:577314', '', '26..1186 bp', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'M337A\r\n', 0),
(40, 'SARS 3C proteinase', 'PA0040', 'pMAL-c2x', '', '', 1, '', 'Homo', 'GI:30275666', '', '9969-10883bp', 'XbaI/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003年5月构建 10521bp沉默突变\r\n', 0),
(41, 'SARS 3C proteinase', 'PA0041', 'pGEM-KG', '', '', 1, '', 'Homo', 'GI:30275666', '', '9969-10883bp', 'XbaI/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003年5月构建10521bp沉默突变\r\n', 0),
(42, 'SARS 3C proteinase', 'PA0042', 'pGEMEX-1', '', '', 1, '', 'Homo', 'GI:30275666', '', '9969-10883bp', 'Nhe I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003年8月构建10521bp沉默突变\r\n', 0),
(43, 'SARS polymerase', 'PA0043', 'pFASTBACHTa', '', '', 1, '', 'Homo', 'GI:30275666', '', '13580-16147bp', 'XbaI/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003年7月构建14287bp沉默突变\r\n', 0),
(44, 'Aggrecanase-1', 'PA0044', 'pENTR', '', '', 1, '', 'Homo', 'GI:11497610|ref|NM_005099 ', '', '407-2920bp', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002年购买\r\n', 0),
(45, 'Aggrecanase-1', 'PA0045', 'pPIC9K', '', '', 1, '', 'Homo', 'GI:11497610|ref|NM_005099 ', '', '1043-1699bp', 'SnaB I/Not I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002年8月构建  pPIC9K-agg(cd)\r\n', 0),
(46, 'Aggrecanase-1', 'PA0046', 'pFASTBAC1', '', '', 1, '', 'Homo', 'GI:11497610|ref|NM_005099 ', '', '407-2129bp', 'SnaB I/Not I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"2003年2月构建 pFASTBAC-agg(1-574), 229 a->g 其他序列正常, 414 tcg->tca  沉默突变, 804 cgg->cga 沉默突变, 936 aca->acg 沉默突变, 1345 tgc->cgc Cys->Arg, 1381 cca->tca Pro->Ser,1485 tgc->tgt 沉默突变"\r\n', 0),
(47, 'Aggrecanase-1', 'PA0047', 'pPIC3.5K', '', '', 1, '', 'Homo', 'GI:11497610|ref|NM_005099 ', '', '407-1699bp', 'SnaB I/Not I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"2003年2月构建 pPIC3.5K-agg (signal peptide+pro+cd), 83 att->aat  Ile->Asn, 229 acc->gcc Thr->Ala 其他序列正常,414 tcg->tca  其他序列正常"\r\n', 0),
(48, 'Aggrecanase-1', 'PA0048', 'pPIC9K', '', '', 1, '', 'Homo', 'GI:11497610|ref|NM_005099 ', '', '560-1699bp', 'SnaB I/Not I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003年3月构建 pPIC9K-agg(pro+cd)1.Acc-gcc其他序列中正常 4.cgg-cga沉默 2.Tcg-tca沉默突变                 5.acc-acg沉默 3.Gct-gtt Ala-Val pro区域\r\n', 0),
(49, 'pPIC9K', 'PA0049', 'pPIC9K', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(50, 'pPIC3.5K', 'PA0050', 'pPIC3.5K', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(51, 'pAO815', 'PA0051', 'pAO815', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(52, 'ScMetAP1', 'PA0052', 'pGEX-KG', '', '', 1, '', 'S.cerevisiae', 'GI:975722', '', '1…1164 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Apr 2002\r\n', 0),
(53, 'ScMetAP2', 'PA0053', 'pGEX-KG', '', '', 1, '', 'S.cerevisiae', 'GI:1045301', '', '290…1555 bp', 'Xba I/ Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in May 2002\r\n', 0),
(54, 'ScMetAP2', 'PA0054', 'pVT102Ua', '', '', 1, '', 'S.cerevisiae', 'GI:1045301', '', '290…1555 bp', 'Xba I/ Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Aug 2002\r\n', 0),
(55, 'ScMetAP1-D219/N', 'PA0055', 'pGEX-KG', '', '', 1, '', 'S.cerevisiae', 'GI:975722', '', '1…1164 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Sep 2002\r\n', 0),
(56, 'ScMetAP1-D219/E', 'PA0056', 'pGEX-KG', '', '', 1, '', 'S.cerevisiae', 'GI:975722', '', '1…1164 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Oct 2002\r\n', 0),
(57, 'EcMetAP1-C45A', 'PA0057', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Nov 2002\r\n', 0),
(58, 'EcMetAP1-C59A', 'PA0058', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Nov 2002\r\n', 0),
(59, 'EcMetAP1-C70A', 'PA0059', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Nov 2002\r\n', 0),
(60, 'EcMetAP1-C78A', 'PA0060', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Nov 2002\r\n', 0),
(61, 'EcMetAP1-C126A', 'PA0061', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Nov 2002\r\n', 0),
(62, 'EcMetAP1-C169A', 'PA0062', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Nov 2002\r\n', 0),
(63, 'EcMetAP1-C245A', 'PA0063', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Nov 2002\r\n', 0),
(64, 'EcMetAP1-C78A/C169A', 'PA0064', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(65, 'EcMetAP1-C70A/C169A', 'PA0065', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(66, 'EcMetAP1-C70A/C78A', 'PA0066', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(67, 'EcMetAP1-C59A/C70A', 'PA0067', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(68, 'EcMetAP1-C59A/C78A', 'PA0068', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(69, 'EcMetAP1-C59A/C169A', 'PA0069', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(70, 'EcMetAP1-C59A/C70A/C78A', 'PA0070', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(71, 'EcMetAP1-C59A/C70A/C169A', 'PA0071', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(72, 'EcMetAP1-C59A/C78A/C169A', 'PA0072', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(73, 'EcMetAP1-C70A/C78A/C169A', 'PA0073', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(74, 'EcMetAP1-C59A/C70A/C78A/C169A', 'PA0074', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(75, 'EcMetAP1-C59S', 'PA0075', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(76, 'EcMetAP1-C70S', 'PA0076', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI:146726', '', '219…1013 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2002\r\n', 0),
(77, 'GST-BlaRI-CD (ZMP)', 'PA0077', 'pGEX-KG', '', '', 1, '', 'S.aureus', 'GI:152966', '', '555…1032 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Aug 2001\r\n', 0),
(78, 'GST-BlaI-His6', 'PA0078', 'pGEX-KG', '', '', 1, '', 'S.aureus', 'GI:152966', '', '1093…2283 bp', 'EocR I / Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Sep 2001\r\n', 0),
(79, 'GST-BlaRI-CD (ZMP)-His6', 'PA0079', 'pGEX-KG', '', '', 1, '', 'S.aureus', 'GI:152966', '', '555…1032 bp', 'Hind III/ BamH I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Dec 2001\r\n', 0),
(80, 'BlaRI-CD (ZMP)-His6', 'PA0080', 'pQE30', '', '', 1, '', 'S.aureus', 'GI:152966', '', '555…1032 bp', 'Hind III/ BamH I', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Jen 2002\r\n', 0),
(81, 'pUC18 DNA', 'PA0081', 'pUC18 DNA', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(82, 'VEGFR-3-CD (Hlt4)', 'PA0082', 'pGEX-KG', '', '', 1, '', 'H.sapiens', 'GI:1472933', '', '2443…3495 bp', 'Xba I/ Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'constructed in Mar 2002\r\n', 0),
(83, 'pQE30', 'PA0083', 'pQE30', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(84, 'PTP1B-CD/kG', 'PA0084', 'pGEX-KG', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(85, 'IR(insulin receptor)', 'PA0085', 'pPIC9K', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '严峻\r\n', 0),
(86, 'IR(insulin receptor)his6', 'PA0086', 'pPIC3.5K', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '严峻\r\n', 0),
(87, 'Glucogan Receptor', 'PA0087', 'pCDNA3', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(88, 'ERK', 'PA0088', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '胡梁言\r\n', 0),
(89, 'MEK1', 'PA0089', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '胡梁言\r\n', 0),
(90, 'MEK2', 'PA0090', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '胡梁言\r\n', 0),
(91, 'MEK3', 'PA0091', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '胡梁言\r\n', 0),
(92, 'MEK4', 'PA0092', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '胡梁言\r\n', 0),
(93, 'Gel B(+)', 'PA0093', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-9 CD with insertion\r\n', 0),
(94, 'Gel B(-)', 'PA0094', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-9 CD without insertion\r\n', 0),
(95, 'Gel A(+)', 'PA0095', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-2 CD with insertion\r\n', 0),
(96, 'Gel A(-)', 'PA0096', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-2 CD without insertion\r\n', 0),
(97, 'HME ', 'PA0098', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP12 CD\r\n', 0),
(98, 'Stromerlysin-1', 'PA0099', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-3 CD\r\n', 0),
(99, 'collagenase-1', 'PA0100', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-1 CD\r\n', 0),
(100, 'MMP-23', 'PA0101', 'pGEX-KG', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(101, 'Matrilysin', 'PA0102', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-7 CD\r\n', 0),
(102, 'collagenase-3', 'PA0103', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-13 CD\r\n', 0),
(103, 'MT1 MMP', 'PA0104', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-14 CD\r\n', 0),
(104, 'MT2 MMP', 'PA0105', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-15 CD\r\n', 0),
(105, 'MT3 MMP', 'PA0106', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-16 CD\r\n', 0),
(106, 'MMP-18', 'PA0107', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'prodomain + CD\r\n', 0),
(107, 'MMP-14(2M)', 'PA0108', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-14(2M)\r\n', 0),
(108, 'MMP-26(CD long)', 'PA0109', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MMP-26 CD + C-terminal 8 aminoacid \r\n', 0),
(109, 'MMP-26(C1)', 'PA0110', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(110, 'MMP-26(1M2M)', 'PA0111', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(111, 'pGEMEX', 'PA0112', 'pGEMEX', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(112, 'flt-1', 'PA0113', 'pGEX-KG', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(113, 'MMP23', 'PA0114', 'pPIC9K', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(114, 'Granzyme B CD', 'PA0115', 'pet26(+)b', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Granzyme B CD\r\n', 0),
(115, 'pet23b', 'PA0116', 'pet23b', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(116, 'Gel B full length', 'PA0117', '', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, ' MMP-9 full length 馈赠\r\n', 0),
(117, 'Gel A full length', 'PA0118', '', '', '', 1, '', 'H.sapiens', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, ' MMP-2 full length 馈赠\r\n', 0),
(118, 'caspase-3', 'PA0119', 'EST clone', '', '', 1, '', 'homo', 'AV649560', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"bought, 含有caspase-3全长基因国家南方基因研究中心"\r\n', 0),
(119, 'caspase-3(CD)', 'PA0120', 'pGEMEX-1', '', '', 1, '', 'homo', 'gi|16159550|ref|XM_054686.2|', '', 'bp150-902', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '37281\r\n', 0),
(120, 'caspase-3 P12', 'PA0121', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', 'gi|16159550|ref|XM_054686.2|', '', 'bp609-902', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '37281\r\n', 0),
(121, 'caspase-3 P17', 'PA0122', 'pGEMEX-1', '', '', 1, '', 'H.sapiens', 'gi|16159550|ref|XM_054686.2|', '', 'bp150-608', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-3-26，\r\n', 0),
(122, 'pVT', 'PA0123', 'pVT', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(123, 'pRS415-Cys2-GST', 'PA0124', 'pRS415', '', '', 1, '', 'Schistosoma japonicum ', 'gi:595709', '', '258..753(add TGA to teminate)', 'SpeI/Pst I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"tcc-->tgc,ser-->cys"\r\n', 0),
(124, 'pRS415-Glu2-GST', 'PA0125', 'pRS415', '', '', 1, '', 'Schistosoma japonicum ', 'gi:595709', '', '258..752(add TGA to teminate)', 'SpeI/Pst I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"tcc-->gaa,ser-->glu"\r\n', 0),
(125, '3.5k-sMetAP2', 'PA0126', 'pPIC3.5K', '', '', 1, '', 'Saccharomyces cerevisiae', 'gi:595709', '', '258..956', 'Not I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '" C Terminal, His-tag"\r\n', 0),
(126, '9K-sMetAP2', 'PA0127', 'pPIC-9K', '', '', 1, '', 'Saccharomyces cerevisiae', 'gi:595709', '', '258..956', 'Not I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '" C Terminal, His-tag"\r\n', 0),
(127, 'KG-GSK3beta', 'PA0128', 'pGEX-KG', '', '', 1, '', 'Homo sapiens', 'gi:21361339', '', '233..1534 (1142..1180deletion)', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(128, 'pGEMEX1-GSK3beta', 'PA0129', 'pGEMEX-1', '', '', 1, '', 'Homo sapiens', 'gi:21361339', '', '233..1534 (1142..1181deletion)', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '" N Terminal, His-tag"\r\n', 0),
(129, 'pet32c-GSK3beta', 'PA0130', 'pet32c', '', '', 1, '', 'Homo sapiens', 'gi:21361339', '', '233..1534 (1142..1182deletion)', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(130, 'pFASTBACHTb-GSK3beta', 'PA0131', 'pFASTBACHTb', '', '', 1, '', 'Homo sapiens', 'gi:21361339', '', '233..1534 (1142..1183deletion)', 'EcoR I/Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(131, 'pCDNA-S2A-S9A-GSK3beta', 'PA0132', 'pCDNA', '', '', 1, '', 'Homo sapiens', 'gi:21361339', '', '"1..1639 (1142..1183deletion,total mRNA)"', 'EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"Ser2Ala, Ser9Alatcc-->gcc,ser-->ala"\r\n', 0),
(132, 'pFASTBACHTa-GSK3-alpha', 'PA0133', 'pFASTBACHTa', '', '', 1, '', 'Homo sapiens', 'gi:20380194', '', '139..1590', 'EcoR I/Xba I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(133, 'pMT2-GSK3alpha', 'PA0134', 'pMT2', '', '', 1, '', 'Homo sapiens', 'gi:20380194', '', '1..2208(total mRNA)', 'EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(134, 'pET 23b', 'PA0135', 'pET 23b', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-1\r\n', 0),
(135, 'pET 28a', 'PA0136', 'pET 28a', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-1\r\n', 0),
(136, 'pET 28b', 'PA0137', 'pET 28b', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-1\r\n', 0),
(137, 'pET 28c', 'PA0138', 'pET 28c', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-1\r\n', 0),
(138, 'pET 32a', 'PA0139', 'pET 32a', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-1\r\n', 0),
(139, 'pET 32b', 'PA0140', 'pET 32b', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-1\r\n', 0),
(140, 'pGEX-KG', 'PA0141', 'pGEX-KG', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-1\r\n', 0),
(141, 'collangenase-1', 'PA0142', 'EST clone', '', '', 1, '', 'homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2001-9-26\r\n', 0),
(142, 'collangenase-1', 'PA0143', 'pGEMEX-1', '', '', 1, '', 'homo', '', '', '', 'Nhe 1/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-3-3\r\n', 0),
(143, 'caspase-7', 'PA0144', 'pET32b', '', '', 1, '', 'homo', '', '', '', 'Nde I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-9-20\r\n', 0),
(144, 'caspase-2', 'PA0145', 'pOTB7', '', '', 1, '', 'homo', 'gi:BC002427', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-15 including full length gene武汉三鹰 抗Clr-\r\n', 0),
(145, 'caspase-6', 'PA0146', 'pOTB7', '', '', 1, '', 'homo', 'gi:BC000305', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-15 including full length gene武汉三鹰 抗Clr-\r\n', 0),
(146, 'caspase-9', 'PA0147', 'pOTB7', '', '', 1, '', 'homo', 'gi:BC006463', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-15 including full length gene武汉三鹰 抗Clr-\r\n', 0),
(147, 'pten', 'PA0148', 'pOTB7', '', '', 1, '', 'homo', 'gi:BC005821', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-10-15 including full length gene武汉三鹰 抗Clr-\r\n', 0),
(148, 'MT1-MMP', 'PA0149', 'pGEMEX-1', '', '', 1, '', 'human', '', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2001-11-21无突变\r\n', 0),
(149, ' BACE1 CD', 'PA0150', 'pGEX-KG', '', '', 1, '', 'human', 'gi:660144', '', 'bp:590…1834', 'EcoR I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-12-30无突变\r\n', 0),
(150, ' BACE1 CD', 'PA0151', 'pGEMEX-1', '', '', 1, '', 'human', 'gi:660144', '', 'bp:590…1834', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-1-3无突变\r\n', 0),
(151, 'BACE1 PPC', 'PA0152', 'pMAL ', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1834', 'EcoR I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-12-30无突变\r\n', 0),
(152, 'BACE1 PPC', 'PA0153', 'pGEMEX-1', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1834', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-5-17无突变\r\n', 0),
(153, 'BACE1 FL', 'PA0154', 'pGEMEX-1', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1960', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-5-17无突变\r\n', 0),
(154, 'BACE1 FL', 'PA0155', 'pGEX-KG', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1960', 'EcoR I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-1-23无突变\r\n', 0),
(155, 'BACE1 FL ', 'PA0156', 'pPIC3.5K', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1960', 'EcoR I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-1-23无突变\r\n', 0),
(156, 'BACE1 FL', 'PA0157', 'pPIC9K', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1960', 'EcoR I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-1-23无突变\r\n', 0),
(157, 'BACE1 PPC(N)', 'PA0158', 'pGEMEX-1', '', '', 1, '', 'human', 'gi:660144', '', 'bp:494…1738', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-10-18无突变\r\n', 0),
(158, 'BACE1 FL', 'PA0159', 'pFastBAC1', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1960', 'BamH I/Xba I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-11-12无突变\r\n', 0),
(159, 'BACE1 FL', 'PA0160', 'pcDNA3.1', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1961', 'Hind III/Xba I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-3-8馈赠质粒\r\n', 0),
(160, 'BACE1 FL', 'PA0161', 'pBS-f', '', '', 1, '', 'human', 'gi:660144', '', 'bp:455…1962', 'Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2002-3-8馈赠质粒\r\n', 0),
(161, 'BACE2 FL', 'PA0162', 'pcDNA3.1', '', '', 1, '', 'human', 'gi:671531', '', 'bp:91…1647', 'Hind III/Xba I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-9-3馈赠质粒\r\n', 0),
(162, 'BACE2 FL', 'PA0163', 'pcDNA3/Hygro', '', '', 1, '', 'human', 'gi:6715311 unknown', '', '', 'Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-9-11馈赠质粒\r\n', 0),
(163, 'pFASTBAC1', 'PA0164', 'pFASTBAC1', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(164, 'AMPK-a1/312', 'PA0165', 'pET42b', '', '', 1, '', 'Homo', 'gi|5453963|ref|NM  006251.1|', '', '24bp-959bp', 'EcoR I /Xhol I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(165, 'AMPK-a1/312', 'PA0166', 'pcDNA3.1-C(+)', '', '', 1, '', 'Homo', 'gi|5453963|ref|NM  006251.1|', '', '24bp-959bp', 'EcoR I /Xhol I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(166, 'AMPK-a1/FL', 'PA0167', 'pET42b', '', '', 1, '', 'Homo', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', 'EcoR I /Xhol I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(167, 'AMPK-a1/FL', 'PA0168', 'pcDNA3.1-C(+)', '', '', 1, '', 'Homo', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', 'EcoR I /Xhol I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(168, 'AMPK-a1/FL', 'PA0169', 'pGEX-KG', '', '', 1, '', 'Homo', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', 'EcoR I /Xhol I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(169, 'AMPK-a2/312', 'PA0170', 'pGEX-KG', '', '', 1, '', 'Homo', 'gi|5453965|ref|NM_006252.1|', '', '67bp-1002bp ', 'EcoR I /Xhol I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"in 602bp,876bp有意突变"\r\n', 0),
(170, 'AMPK-a2/FL', 'PA0171', 'pGEX-KG', '', '', 1, '', 'Homo', 'gi|5453965|ref|NM_006252.1|', '', '67bp-1725bp ', 'EcoR I /Xhol I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"in 602bp,876bp,1272bp,1275bp,1276bp有意突变"\r\n', 0),
(171, 'pcDNA3.1-c(+)', 'PA0172', 'pcDNA3.1-c(+)', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(172, 'CaMKKbeta', 'PA0173', 'pMAL-c2X', '', '', 1, '', 'Rat', '', '', '', 'EcoR I /Xba  I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '惠赠\r\n', 0),
(173, 'cdc25A', 'PA0174', 'pGEX-KG', '', '', 1, '', 'Homo', '', '', 'catalytic domain(C-terminal)', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003-11-06邱蓓颖正在扩增\r\n', 0),
(174, 'cdc25B', 'PA0175', 'pGEX-KG', '', '', 1, '', 'Homo', '', '', 'catalytic domain(C-terminal)', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2004-2-12 原始保存的cdc25B 6号菌株获得\r\n', 0),
(175, 'PTP1B-CD/kG', 'PA0176', 'pGEX-KG', '', '', 1, '', 'Homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2003年11月有表达，但需挑克隆\r\n', 0),
(176, 'PRMT-1', 'PA0177', 'pGEX', '', '', 1, '', '', '', '', 'GST fusion', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Richard惠赠 McGill\r\n', 0),
(177, 'PRMT-6', 'PA0178', 'pGEX', '', '', 1, '', '', '', '', 'GST fusion', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Richard惠赠 McGill\r\n', 0),
(178, 'MRE-11', 'PA0179', 'pGEX', '', '', 1, '', '', '', '', 'GST fusion', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Richard惠赠 McGill\r\n', 0),
(179, 'TAT', 'PA0180', 'pGEX', '', '', 1, '', '', '', '', 'GST fusion', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Richard惠赠 McGill\r\n', 0),
(180, 'CD45', 'PA0181', 'pOTB7', '', '', 1, '', '', 'BC014239', '', '', 'EcoRI/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Chl, 购买2003-12-30"\r\n', 0),
(181, 'PRL-3', 'PA0182', 'pCMV-SPORT6', '', '', 1, '', '', 'BI759160', '', '', 'NOt I/EcoR V', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2003-12-30"\r\n', 0),
(182, 'PTP-PEST', 'PA0183', 'pBluescript KS+', '', '', 1, '', '', 'BC050008', '', '', 'BamH I+Sal I/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2003-12-30"\r\n', 0),
(183, 'PTP-e', 'PA0184', 'pBluescriptR', '', '', 1, '', '', 'BC050062', '', '', 'BamH I+Sal I/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2003-12-30"\r\n', 0),
(184, 'SapI', 'PA0185', 'pBluescript SK-', '', '', 1, '', '', 'AA573849', '', '', 'XhoI/EcoRI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2003-12-30"\r\n', 0),
(185, 'SET-7', 'PA0186', 'pGEX', '', '', 1, '', '', '', '', 'GST fusion', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Gifted from Zhangyi\r\n', 0),
(186, 'pcDNA3.1', 'PA0187', 'pcDNA3.1', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Gifted from Dr.Yongtai Hou\r\n', 0),
(187, 'pETDuetTM-1', 'PA0188', 'pETDuetTM-1', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '购买2004-01 from Novagen\r\n', 0),
(188, 'HBGP', 'PA0189', 'pCMV-SPORT6', '', '', 1, '', 'homo', 'BC030795', '', '', 'NotI+SalI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"武汉三鹰公司购买,  抗Amp"\r\n', 0),
(189, 'HLGP', 'PA0190', 'pKK233-2', '', '', 1, '', 'homo', 'AF066858', '', '278-2858bp', 'NcoI+HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"a mutation in1710bp(t:g),  抗Amp"\r\n', 0),
(190, 'HLGP', 'PA0191', 'pGEMEX-1', '', '', 1, '', 'homo', 'AF066858', '', '15-2559bp', 'NheI+HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"with a His-tag in the C-terminal,and the mutation,  抗Amp"\r\n', 0),
(191, 'TCPTP', 'PA0192', 'pCMV-SPORT6', '', '', 1, '', 'homo', 'BC008244', '', '', 'Not I+Mlu I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2004-3-19"\r\n', 0),
(192, 'SHP2', 'PA0193', 'pCMV-SPORT6', '', '', 1, '', 'homo', 'BC008692', '', '', 'Not I+Mlu I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2004-3-19"\r\n', 0),
(193, 'PTP-α', 'PA0194', 'pCMV-SPORT6', '', '', 1, '', 'homo', 'BC027308', '', '', 'Not I+Mlu I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2004-3-19"\r\n', 0),
(194, 'SHP1', 'PA0195', 'pOTB7', '', '', 1, '', 'homo', 'BC002523', '', '', 'EcoRI/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Chl, 购买2004-3-19"\r\n', 0),
(195, 'HD-PTP', 'PA0196', 'pOTB7', '', '', 1, '', 'homo', 'BC004881', '', '', 'EcoRI/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Chl, 购买2004-3-19"\r\n', 0),
(196, 'PRL-3', 'PA0197', 'pOTB7', '', '', 1, '', 'homo', 'BC003105', '', '', 'EcoRI/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Chl, 购买2004-3-19"\r\n', 0),
(197, 'PTP-σ', 'PA0198', 'pBluescriptR', '', '', 1, '', 'homo', 'BC029496', '', '', 'BamH I+Sal I/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 购买2004-3-19"\r\n', 0),
(198, 'pcDNA4-hisA', 'PA0199', 'pcDNA4-hisA', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Gifted from 生化所\r\n', 0),
(199, 'pcDNA4-hisB', 'PA0200', 'pcDNA4-hisB', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Gifted from 生化所\r\n', 0),
(200, 'pcDNA4-hisC', 'PA0201', 'pcDNA4-hisC', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Gifted from 生化所\r\n', 0),
(201, 'pcDNA4-lacZ', 'PA0202', 'pcDNA4-lacZ', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Gifted from 生化所\r\n', 0),
(202, '"HSD11B1(dydroxysteriod(11-beta) dehydrogenase 1,transcript variant 1))"', 'PA0203', 'POTB7', '', '', 1, '', 'homo', '', '', '', 'EcoRI/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '购买2004-3-22\r\n', 0),
(203, 'HSD11B2(hydroxysteriod(11-beta) dehydrogenase 2)', 'PA0204', 'pCMV-SPORT6', '', '', 1, '', 'homo', '', '', '', 'Not I/Mlu I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '购买2004-3-22\r\n', 0),
(204, '"glucokinase(hexokinase 4, maturity onset diabetes of the young 2), transcript variant 1, mRNA"', 'PA0205', 'POTB7', '', '', 1, '', 'homo', '', '', '', 'EcoRI/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '购买2004-3-22\r\n', 0),
(205, 'heparanase', 'PA0206', 'pcDNA3.1', '', '', 1, '', 'homo', 'gi:19923365 ', '', '137..1768', 'EcoR I/Not I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2000-5-29\r\n', 0),
(206, 'PKCa-EGFP', 'PA0207', 'pEGFP-N1', '', '', 1, '', 'homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗卡那霉素, Prof.Christer Larsson/Lund University /Sweden 惠赠"\r\n', 0),
(207, 'PKCd-EGFP', 'PA0208', 'pEGFP-N1', '', '', 1, '', 'homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗卡那霉素, Prof.Christer Larsson/Lund University /Sweden 惠赠"\r\n', 0),
(208, 'LAR', 'PA0209', '', '', '', 1, '', '', '1167B07/1167807', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(209, 'VEGFR1', 'PA0210', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(210, 'LAR-D1', 'PA0211', 'PGEX-KG', '', '', 1, '', 'homo', 'NM-002840', '', '', 'BamH Ⅰ/Hind Ⅲ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '4192-5209\r\n', 0),
(211, 'LAR-D2', 'PA0212', 'PGEX-KG', '', '', 1, '', 'homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(212, 'LAR-D1D2', 'PA0213', 'PGEX-KG', '', '', 1, '', 'homo', 'NM-002840', '', '', 'BamH Ⅰ/Hind Ⅲ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '4192-6061\r\n', 0),
(213, 'LAR-DQ', 'PA0214', 'PGEX-KG', '', '', 1, '', 'homo', 'AK127007', '', '', 'BamH Ⅰ/Hind Ⅲ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '1035-2084\r\n', 0),
(214, 'CD45-D1', 'PA0215', 'PGEX-KG', '', '', 1, '', 'homo', 'NM-002838', '', '', 'Xba Ⅰ/Hind Ⅲ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"1884-2828, 活性低"\r\n', 0),
(215, 'PRL-3', 'PA0216', 'PGEX-KG', '', '', 1, '', 'homo', 'BI759160', '', '', 'EcoRⅠ/SalⅠ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"238-759, 活性低"\r\n', 0),
(216, 'PTP-ε-D1', 'PA0217', 'PGEX-KG', '', '', 1, '', 'homo', 'BC050061', '', '', 'BamH Ⅰ/Hind Ⅲ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '554-1630\r\n', 0),
(217, 'AMPKbeta2', 'PA0218', 'pCMV-SPORT6', '', '', 1, '', 'homo', 'BC053610 ', '', '', 'Not 1/ Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"2004-4-19 购买, 武汉三鹰 抗Amp"\r\n', 0),
(218, 'PRKAB1', 'PA0219', 'pCMV-SPORT6', '', '', 1, '', 'homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"武汉三鹰 抗Amp, 2004-4-19 购买"\r\n', 0),
(219, 'PRKAG1', 'PA0220', 'pOTB7', '', '', 1, '', 'homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"武汉三鹰 抗Chl, 2004-4-19 购买"\r\n', 0),
(220, 'PRKAG2', 'PA0221', 'pCMV-SPORT6', '', '', 1, '', 'homo', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"武汉三鹰 抗Amp, 2004-4-19 购买"\r\n', 0),
(221, 'STAT5a', 'PA0222', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 天津医科大学杨洁教授馈赠 2004-10"\r\n', 0),
(222, 'STAT5b', 'PA0223', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 天津医科大学杨洁教授馈赠 2004-10"\r\n', 0),
(223, 'GST-STAT6-TAD2（缺）', 'PA0224', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 天津医科大学杨洁教授馈赠 2004-10, 质粒抽提第一次未成功"\r\n', 0),
(224, 'pEBB-hSTAT1a-HA', 'PA0225', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 天津医科大学杨洁教授馈赠 2004-10"\r\n', 0),
(225, 'PPRK5-STAT3', 'PA0226', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, 天津医科大学杨洁教授馈赠 2004-10"\r\n', 0),
(226, 'pEGFP-Syn6', 'PA0227', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Kan, JBC 275:1261-8"\r\n', 0),
(227, 'pRcCMV-hIRS-1 MycMyc', 'PA0228', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(228, 'pRcCMV-IRS-2(mouse)', 'PA0229', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(229, 'pMBY129', 'PA0230', ' ', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(230, 'pCYB4', 'PA0231', 'pCYB4', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp/NEB, "\r\n', 0),
(231, 'pGBT9 ras', 'PA0232', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(232, 'pCDNA3', 'PA0233', 'pCDNA3', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(233, 'pCYB1', 'PA0234', 'pCYB1', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp/NEB, "\r\n', 0),
(234, 'pGBT9', 'PA0235', 'pGBT9', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(235, 'pBABE puro', 'PA0236', 'pBABE puro', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(236, 'pSOSColl', 'PA0237', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(237, 'pLNCX1MycAkt2', 'PA0238', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(238, 'pGEX4T-1', 'PA0239', 'pGEX4T-1', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(239, 'pBABE puro/shRNA EFGP', 'PA0240', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(240, 'pJZenNeo', 'PA0241', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(241, 'pEGFP-C3', 'PA0242', 'pEGFP-C3', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Kan, "\r\n', 0),
(242, 'pEGFP-N1', 'PA0243', 'pEGFP-N1', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Kan, "\r\n', 0),
(243, 'pBABE puro/shRNA p53', 'PA0244', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(244, 'pCDNA3.1/Zeo-GFP-p85', 'PA0245', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"抗Amp, "\r\n', 0),
(245, 'pGADGH cdc25', 'PA0246', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"7.8 kb, Amp/Leu2 抗Amp, "\r\n', 0),
(246, 'pET21b', 'PA0247', 'pET21b', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(247, 'pET21d', 'PA0248', 'pET21d', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(248, 'pBABE LDL-R', 'PA0249', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '抗Amp\r\n', 0),
(249, 'pLNCX1MycAkt', 'PA0250', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'c-termi HA tagged constitutively active AKT missing PH domain 抗Amp\r\n', 0),
(250, 'pCB6', 'PA0251', 'pCB6', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp/hygromycin\r\n', 0),
(251, 'pMyr', 'PA0252', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '抗Chlo\r\n', 0),
(252, 'PTPα 全长', 'PA0253', '不详', '', '', 1, '', 'Homo', 'GI：20073056   ', '', '', '不详', '', '2008-01-20', '0000-00-00', 1, 0, 1, '武汉三鹰公司购买\r\n', 0),
(253, 'PTPα-D1 ', 'PA0254', 'pGEX-KG', '', '', 1, '', 'Homo', 'GI：20073056', '', '697~1506 bp', 'Sal I / Hind Ⅲ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2005年11月构建，无突变\r\n', 0),
(254, 'PTPα-D1D2 ', 'PA0255', 'pGEX-KG', '', '', 1, '', 'Homo', 'GI：20073056', '', '697~2577 bp', 'Sal I / Hind Ⅲ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2005年12月构建，无突变\r\n', 0),
(255, 'PTPα-D2', 'PA0256', 'pGEX-KG', '', '', 1, '', 'Homo', 'GI：20073056', '', '1507~2577 bp', 'Sal I / Hind Ⅲ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2005年13月构建，无突变\r\n', 0),
(256, 'pTet-Splice', 'PA0257', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"由美国Cornell大学David Shalloway教授馈赠，参考文献“EMBO J., 2000, Vol.19, No.5, 964-978”, Life Technologies公司产品"\r\n', 0),
(257, 'pTet-tTAK', 'PA0258', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"由美国Cornell大学David Shalloway教授馈赠，参考文献“EMBO J., 2000, Vol.19, No.5, 964-978”, Life Technologies公司产品"\r\n', 0),
(258, 'PTPα', 'PA0259', 'pTet-PTPα', '', '', 1, '', 'Homo', '', '', '', 'HindⅢ / HindⅢ ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"由美国Cornell大学David Shalloway教授馈赠，参考文献“EMBO J., 2000, Vol.19, No.5, 964-978”, Life Technologies公司产品"\r\n', 0),
(259, 'PTPα', 'PA0260', 'pcDNA-PTPα', '', '', 1, '', 'Homo', '', '', '', '不详', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"由美国Cornell大学David Shalloway教授馈赠，参考文献“EMBO J., 2000, Vol.19, No.5, 964-978”, Life Technologies公司产品"\r\n', 0),
(260, 'PTEN全长', 'PA0261', 'pOTB7 (Chl+)', '', '', 1, '', 'Homo', 'BC005821 GI：39644742', '', '', 'EcoRI / XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '武汉三鹰公司购买\r\n', 0),
(261, 'PTEN', 'PA0262', 'pGEX-KG', '', '', 1, '', 'Homo', 'GI：39644743', '', '1~1212 bp', 'XhoI / HindⅢ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(262, 'PTEN', 'PA0263', 'pET-32a', '', '', 1, '', 'Homo', 'GI：39644743', '', '1~1212 bp', 'NdeI / HindⅢ', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'His-tag位于C-terminal\r\n', 0),
(263, 'PTEN', 'PA0264', 'pET-32a', '', '', 1, '', 'Homo', 'GI：39644743', '', '1~1212 bp', 'NdeI / HindⅢ', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'His-tag位于N-terminal\r\n', 0),
(264, 'HBGP', 'PA0265', 'pGEMEX-1', '', '', 1, '', 'Homo', 'GI：34192265', '', '86~2617 bp', 'NheI / HindⅢ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2004年2月构建，无突变\r\n', 0),
(265, 'pCR2.1-DPPIV', 'PA0266', 'pCR2.1', '', '', 1, '', 'Homo', 'NM_001935.3  GI:47078262', '', '2214bp', 'KpnI / HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '由美国Merck公司Babarar Leiting 博士馈赠，无突变\r\n', 0),
(266, 'MBac1-gp67-6his-DPPIV-6his 1', 'PA0267', 'pFastBac1', '', '', 1, '', 'Homo', 'NM_001935.3  GI:47078262', '', '2214bp', 'KpnI / HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '改造后pFastBac1，DPPIV（29-766氨基酸）前面有gp67信号肽序列和6个Histidine的标签；后面有6个Histidine的标签；无突变\r\n', 0),
(267, 'MBac1-HBM-DPPIV-6his 1', 'PA0268', 'pFastBac1', '', '', 1, '', 'Homo', 'NM_001935.3  GI:47078262', '', '2214bp', 'KpnI / HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '改造后pFastBac1，DPPIV（29-766氨基酸）前面有蜂毒肽信号肽序列；后面有6个Histidine的标签；无突变\r\n', 0),
(268, 'PVL/PKCq', 'PA0269', 'PVL', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'pKCq全长，由G.Baier馈赠\r\n', 0),
(269, 'pFastBac1/PKCq', 'PA0270', 'pFastBac1', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'pKCq全长\r\n', 0),
(270, 'pFastBac1/IKKFL', 'PA0271', 'pFastBac1', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(271, 'KG-IkBa(1-54)wt', 'PA0272', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'IkBa(1-54)wt\r\n', 0),
(272, 'KG-IkBa(1-54)mut', 'PA0273', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'IkBa(1-54)mut\r\n', 0),
(273, 'pCMVIKKbFL', 'PA0274', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '哺乳动物表达质粒，由Ishaq MohamIned馈赠 \r\n', 0),
(274, 'pFastBac1/IKK900', 'PA0275', 'pFastBac1', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(275, 'PKCd', 'PA0276', '不明', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(276, 'pGL3-basic', 'PA0277', 'pGL3-basic', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'luciferase reporter vector\r\n', 0),
(277, 'pGL3-control', 'PA0278', 'pGL3-control', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'luciferase reporter vector\r\n', 0),
(278, 'pGL3-promoter', 'PA0279', 'pGL3-promoter', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'luciferase reporter vector\r\n', 0),
(279, 'GLP-1R/pcDNA3.1', 'PA0280', 'pcDNA3.1(?)', '', '', 1, 'GLP-1 Receptor', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2006-01-10 杭州 大抽 1 mg/ml\r\n', 0),
(280, 'pVP16', 'PA0281', 'pVP16', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '3.3-kb AD cloning vector used to express a fusion of a test protein with VP16 AD in Mammalian MATCHMAKERTwo-Hybrid Assay\r\n', 0),
(281, 'pM-53', 'PA0282', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '4.6-kb positive control plasmid that expresses a fusion of the GAL4 DNA-BD to the mouse p53 protein\r\n', 0),
(282, 'pVP16-T', 'PA0283', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '5.3-kb positive control plasmid that expresses a fusion of the VP16 AD to the SV40 large T-antigen\r\n', 0),
(283, 'pM', 'PA0284', 'pM', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '3.5-kb DNA-BD cloning vector used to express a fusion of a test protein with the GAL4 DNA-BD in Mammalian MATCHMAKERTwo-Hybrid Assay\r\n', 0),
(284, 'pM3-VP16', 'PA0285', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '4.4-kb positive control plasmid that expresses a fusion of the GAL4 DNA-BD to the VP16 AD\r\n', 0),
(285, 'pGADT7', 'PA0286', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'pGADT7 is the AD Vector included with MATCHMAKER Two-Hybrid System \r\n', 0);
INSERT INTO `plasmids` (`id`, `name`, `description`, `vector_name`, `vector_type`, `map`, `isinsert`, `insert_name`, `species`, `sequence_identifier`, `insert_size`, `modification`, `cloning_sites`, `tags`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(286, 'pGBKT7-HsMetAP2', 'PA0287', '', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(287, 'pGEX-KGdusp14', 'PA0288', 'p GEX-KG', '', '', 1, '', '', 'BC004448', '', '', 'Xba1/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '原核质粒 IPTG  induce  \r\n', 0),
(288, 'pGEX-KGdusp14（125Y--F）', 'PA0289', 'p GEX-KG', '', '', 1, '', '', 'BC004448', '', '', 'Xba1/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '原核质粒 IPTG  induce  \r\n', 0),
(289, 'pEGFPC1-DUSP14', 'PA0290', 'pEGFPC1', '', '', 1, '', '', 'BC004448', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，duap14在C端Y与EGFP融合\r\n', 0),
(290, 'p EGFPC1-dusp14（125Y--F）', 'PA0291', 'pEGFPC1', '', '', 1, '', '', 'BC004448', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，duap14在C端Y与EGFP融合\r\n', 0),
(291, 'pEGFPN1-DUSP14', 'PA0292', 'pEGFPN1', '', '', 1, '', '', 'BC004448', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，duap14在N端Y与EGFP融合\r\n', 0),
(292, 'pcDNA3.1mychisDUSP14', 'PA0293', 'pcDNA3.1mychis', '', '', 1, '', '', 'BC004448', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，duap14在N端Y与EGFP融合\r\n', 0),
(293, 'pcDNA3.1mychisDUSP14（125Y--F）', 'PA0294', 'pcDNA3.1mychis', '', '', 1, '', '', 'BC004448', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，标签在C端\r\n', 0),
(294, 'p GEX-KGdusp18', 'PA0295', 'p GEX-KG', '', '', 1, '', '', 'BC030987', '', '', 'Xba1/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '原核质粒 IPTG  induce  \r\n', 0),
(295, 'pGEX-KGdusp18（118Y--F）', 'PA0296', 'p GEX-KG', '', '', 1, '', '', 'BC030987', '', '', 'Xba1/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '原核质粒 IPTG  induce  \r\n', 0),
(296, 'pcDNA3.1mychisDUSP18', 'PA0297', 'pcDNA3.1mychis', '', '', 1, '', '', 'BC030987', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，标签在C端\r\n', 0),
(297, 'P EGFPC1-DUSP18', 'PA0298', 'pEGFPC1', '', '', 1, '', '', 'BC030987', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，duap18在C端Y与EGFP融合\r\n', 0),
(298, 'P EGFPN1-DUSP18', 'PA0299', 'pEGFPN1', '', '', 1, '', '', 'BC030987', '', '', 'HindIII/BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '真核质粒，duap18在N端Y与EGFP融合\r\n', 0),
(299, 'PGEMEX-EcMetAP(His)', 'PA0300', 'pGEMEX-1', '', '', 1, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Exjression of  EcMetAP with His Tag\r\n', 0),
(300, 'Casp3 C47A', 'PA0301', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(301, 'Casp3 C116A', 'PA0302', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(302, 'Casp3 C148A', 'PA0303', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(303, 'Casp3 C163A', 'PA0304', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(304, 'Casp3 C70A', 'PA0305', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(305, 'Casp3 C184A', 'PA0306', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(306, 'Casp3 C220A', 'PA0307', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(307, 'Casp3 C264A', 'PA0308', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(308, '"Casp3 C47,116A          "', 'PA0309', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(309, '"Casp3 C170,184A  "', 'PA0310', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(310, '"Casp3 C148,264A"', 'PA0311', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(311, '"Casp3 C47,116,148A                      "', 'PA0312', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(312, '"Casp3 C170,184,220A                     "', 'PA0313', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(313, '"Casp3 C148,170,184A"', 'PA0314', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(314, '"Casp3 C170,184,220,264A      "', 'PA0315', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(315, '"Casp3 C148,170,184,220A"', 'PA0316', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(316, '"Casp3 C148,170,184,220,264A"', 'PA0317', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(317, '"Casp3 C148,163,170,184,220,264A"', 'PA0318', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(318, '"Casp3 C47,116,170,184,220A"', 'PA0319', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(319, '"Casp3 C47,116,170,184,220,264A"', 'PA0320', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI\r\n', '', '2008-01-20', '0000-00-00', 1, 0, 1, '', 0),
(320, '"Casp3 C47,116,148，170,184,220,264A"', 'PA0321', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(321, '"Casp3 C47,116A;C148G          "', 'PA0322', 'PET32b(+)', '', '', 1, '', '', 'NM_032991.2', '', '', 'NdeI/XholI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(322, 'pET21b-CASP1CD D381E', 'PA0323', 'pET21b', '', '', 1, '', '', 'NM 033292.1', '', '', 'NdeI -XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'N-His6 ; 381位氨基酸点突变D变成E\r\n', 0),
(323, 'pET21b-CASP1 CD', 'PA0324', 'pET21b', '', '', 1, '', '', 'NM 033292.1', '', '', 'NdeI -XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'N-His6 ; （RT-PCR得到基因）无突变\r\n', 0),
(324, 'pET21b-CASP1P20', 'PA0325', 'pET21b', '', '', 1, '', '', 'NM 033292.1', '', '', 'NdeI -XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'N-His6 ;  Caspase-1大亚基\r\n', 0),
(325, 'pET21b-CASP1P10', 'PA0326', 'pET21b', '', '', 1, '', '', 'NM 033292.1', '', '', 'NdeI -XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'N-His6 ;  Caspase-1小亚基 无突变\r\n', 0),
(326, 'pET21b', 'PA0327', 'pET21b', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '空载质粒\r\n', 0),
(327, 'pGEMEX-AMPK-a2/333', 'PA0328', 'pGEMEX-1', '', '', 1, '', '', '', '', '', '"NheI,EcoRI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'truncation of AMPK\r\n', 0),
(328, 'pGEMEX-AMPK-a2/398 Delete 310-335', 'PA0329', 'pGEMEX-1', '', '', 1, '', '', '', '', '', '"NheI,EcoRI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'mutation of AMPK\r\n', 0),
(329, 'pGEMEX-AMPK-a2/398 R331A', 'PA0330', 'pGEMEX-1', '', '', 1, '', '', '', '', '', '"NheI,EcoRI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'mutation of AMPK\r\n', 0),
(330, 'pETDuet-b1-a2/FL', 'PA0331', 'pETDuet', '', '', 1, '', '', '', '', '', '"NheI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(331, 'pETDuet-b1-a2/FL T172D', 'PA0332', 'pETDuet', '', '', 1, '', '', '', '', '', '"NheI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(332, 'pGEMEX-AMPK-a2/398 T172D', 'PA0333', 'pGEMEX-1', '', '', 1, '', '', '', '', '', '"NheI,EcoRI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(333, 'pLXSN-neo', 'PA0334', 'pLXSN-neo', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(334, 'pBABE-hygro', 'PA0335', 'pBABE-hygro', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(335, 'pBABE-puro-PT', 'PA0336', 'pBABE-puro-PT', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'MCS: BamH I-SnaB I-Bgl II-EcoR I-Mlu I-Xho I-Nde I-Sal I\r\n', 0),
(336, 'pBABE-GSK3a-Y279F', 'PA0337', '', '', '', 1, '', '', '', '', '', 'BamH1/EcoR1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(337, 'pFastBac1-DPP8-C6*His', 'PA0338', 'pFastBac1', '', '', 1, '', 'homo', 'NM_130434.2|', '', '', '"XbaI,HindIII"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Vector for Bac-to-Bac Baculovirus Expression System\r\n', 0),
(338, 'pFastBac1-DPP9-C6*His', 'PA0339', 'pFastBac1', '', '', 1, '', 'homo', 'AF452102', '', '', '"EcoR1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Vector for Bac-to-Bac Baculovirus Expression System\r\n', 0),
(339, 'pcDNA3.1/myc-HisC-JSP1', 'PA0340', 'pcDNA3.1/myc-HisC', '', '', 1, '', 'homo', 'BC022847', '', '', 'BamH I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(340, 'pET21b-JSP-1', 'PA0341', 'pET21b', '', '', 1, '', 'homo', 'BC022847', '', '', 'NheI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(341, 'pGEX-KG-DUSP3', 'PA0342', 'pGEX-KG', '', '', 1, '', 'homo', 'BC002682', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(342, 'pGEX-KG-SSH3', 'PA0343', 'pGEX-KG', '', '', 1, '', 'homo', 'BC004210', '', '', 'XbaI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(343, 'pGEX-KG-DUSP11', 'PA0344', 'pGEX-KG', '', '', 1, '', 'homo', 'BC000346', '', '', 'XbaI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(344, 'pGEX-KG-DUSP6', 'PA0345', 'pGEX-KG', '', '', 1, '', 'homo', 'BC003143', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(345, 'pGEX-KG-STYX', 'PA0346', 'pGEX-KG', '', '', 1, '', 'homo', 'BC020265', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(346, 'Duet-b1-a1', 'PA0347', 'pETDuet-1', '', '', 1, '', 'homo', 'b1: NM_006253; a1: NM_006251.4', '', '', 'b1:Nco I/Hind III; a1:from pET28b with Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(347, 'Duet-b1', 'PA0348', 'pETDuet-1', '', '', 1, '', 'homo', 'NM_006253', '', '', 'Nco I/Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(348, 'Duet-b1-g1', 'PA0349', 'pETDuet-1', '', '', 1, '', 'homo', 'r1: NM_002733', '', '', 'b1:Nco I/Hind III; g1:Nde I/Kpn I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(349, 'pET28b-a1/FL', 'PA0350', 'pET28b', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(350, 'pET28b-b1', 'PA0351', 'pET28b', '', '', 1, '', 'homo', 'NM_006253', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(351, 'pET28b-g1', 'PA0352', 'pET28b', '', '', 1, '', 'homo', 'NM_002733', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(352, 'pfastBAC1-a1/FL(N-his)', 'PA0353', 'pfastBAC1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(353, 'pfastBAC1-a1/FL(C-his)', 'PA0354', 'pfastBAC1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(354, 'pcDNA3.1-a1/312-T174D', 'PA0355', 'pcDNA3.1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(355, 'pcDNA3.1-a2/FL-K45R', 'PA0356', 'pcDNA3.1', '', '', 1, '', 'homo', 'NM_006252.2', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(356, 'pcDNA3.1-a1/FL-D159A', 'PA0357', 'pcDNA3.1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(357, 'pET28b-a1/FL(D313-335)', 'PA0358', 'pET28b', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(358, 'pGEMEX-a1/394(D313-335)', 'PA0359', 'pGEMEX-1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(359, 'pET28b-a1/394(D313-335)', 'PA0360', 'pET28b', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(360, 'pcDNA3.1-a1/335', 'PA0361', 'pcDNA3.1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(361, 'pcDNA3.1-a1/394', 'PA0362', 'pcDNA3.1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(362, 'pcDNA3.1-a1/394(D313-335)', 'PA0363', 'pcDNA3.1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(363, 'pcDNA3.1-a1/FL(D313-335)', 'PA0364', 'pcDNA3.1', '', '', 1, '', 'homo', 'NM_006251.4', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(364, 'pFastBacHT A', 'PA0365', 'pFastBacHT A', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Vector for Bac-to-Bac Baculovirus Expression System\r\n', 0),
(365, 'pFastBacHT B', 'PA0366', 'pFastBacHT B', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Vector for Bac-to-Bac Baculovirus Expression System\r\n', 0),
(366, 'pFastBacHT C', 'PA0367', 'pFastBacHT C', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Vector for Bac-to-Bac Baculovirus Expression System\r\n', 0),
(367, 'PKM2SH-3', 'PA0368', 'pBABE', '', '', 1, '', 'Rat', '', '', 'GTAAGCAGCAGCTTTGATAGTTttcaagagaAACTATCAAAGCTGCTGCTTTTTTTGGAAG，61bp', 'SnaBI/SalI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2006年12月构建，测序正确\r\n', 0),
(368, 'P53-luc', 'PA0369', 'P53-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(369, 'HSE-luc', 'PA0370', 'HSE-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(370, 'MYC-luc', 'PA0371', 'MYC-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(371, 'pTOPflash-luc', 'PA0372', 'pTOPflash-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(372, 'pFOPflash-luc', 'PA0373', 'pFOPflash-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(373, 'NFAT-luc', 'PA0374', 'NFAT-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(374, 'GRE-luc', 'PA0375', 'GRE-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(375, 'AP1(PMA-luc)', 'PA0376', 'AP1(PMA-luc)', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(376, 'NFKB-luc', 'PA0377', 'NFKB-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(377, 'Rb-luc', 'PA0378', 'Rb-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(378, 'E2F-luc', 'PA0379', 'E2F-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(379, 'CRE-luc', 'PA0380', 'CRE-luc', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(380, 'pATF6-luc(pUPRE-luc)', 'PA0381', 'pATF6-luc(pUPRE-luc)', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(381, 'Pepck-luc(-490--+73)', 'PA0382', 'Pepck-luc(-490--+73)', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(382, 'pADEASY-1', 'PA0383', 'pADEASY-1', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Amp+\r\n', 0),
(383, 'pADTRACK-CMV', 'PA0384', 'pADTRACK-CMV', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Kan+\r\n', 0),
(384, 'pADEASY-1+GFP', 'PA0385', 'pADEASY-1+GFP', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Kan+\r\n', 0),
(385, 'pGBKT7-MAPII', 'PA0386', 'pGBKT7-MAPII', '', '', 0, '', '', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Kan+\r\n', 0),
(386, 'pGEX6P-1 HA.MARK1', 'PA0387', 'pGEX6P-1 ', '', '', 1, '', 'E.coli', 'NM_018650', '', '', 'EcoR1/Sal1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(387, 'pGEX6P-1 HA.QSK', 'PA0388', 'pGEX6P-1', '', '', 1, '', 'E.coli', 'NM_025164', '', '', 'BamH1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(388, 'pGEX6P-1 HA.MELK full', 'PA0389', 'pGEX6P-1 ', '', '', 1, '', 'E.coli', '', '', '', '', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(389, 'pGEX6P-1 HA.SIK', 'PA0390', 'pGEX6P-1 ', '', '', 1, '', 'E.coli', 'NM_173354', '', '', 'BamH1/Not1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(390, 'Gex6-NUAK 2 wt', 'PA0391', 'PGex-6P-1', '', '', 1, '', 'E.coli', 'NP_112214', '', '', 'EcoR1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(391, 'pGEX6P-1 HA.BRSK1', 'PA0392', 'pGEX6P-1 ', '', '', 1, '', 'E.coli', 'NM_032430', '', '', 'BanH1/EcoR1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(392, 'pGEX6P-1 HA.BRSK2 wt', 'PA0393', 'pGEX6P-1 ', '', '', 1, '', 'E.coli', 'AF533878/AY166857', '', '', 'BamH1/Not1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(393, 'pGEX6P-3 HA.QIK wt', 'PA0394', 'pGEX6P-3 ', '', '', 1, '', 'E.coli', 'XM_041314', '', '', 'Sal1/Not1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(394, 'PGex-6p-NUAK 1 wt', 'PA0395', 'PGex-6P', '', '', 1, '', 'E.coli', 'NM_014840', '', '', 'EcoR1/EcoR1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(395, 'PGex-6p-Mark 4 wt', 'PA0396', 'PGex-6P', '', '', 1, '', 'E.coli', 'AK_075272', '', '', 'Bgl2/Not1 ligated into BamH1/Not1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(396, 'pGEX6P-1 HA.MELK kinase domain', 'PA0397', 'pGEX6P-1 ', '', '', 1, '', 'E.coli', 'NM_014791', '', '', 'BanH1/EcoR1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(397, 'Gex6-Mark 3', 'PA0398', 'PGex-6p', '', '', 1, '', 'E.coli', 'NM_002376', '', '', 'Not1/Not1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(398, 'pGEX6P-3 HA.MARK2', 'PA0399', 'pGEX6P-3 ', '', '', 1, '', 'E.coli', 'NM_004954', '', '', 'Sal1/Not1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(399, 'pET28b-Snf1-KD', 'PA0400', 'pET28b', '', '', 1, '', 'E.coli', 'gi|37654883|gb|U33050.4|SCD8035', '', '', 'Nde1/Xho1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(400, 'pET28b-Snf1-KD-AID', 'PA0401', 'pET28b', '', '', 1, '', 'E.coli', 'gi|37654883|gb|U33050.4|SCD8035', '', '', 'Nde1/Xho1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(401, 'smb-2', 'PA0402', 'pFastBacHTb', '', '', 1, '', 'E.coli', 'NM_006257.2', '', '', 'BamH I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(402, 'smb-1', 'PA0403', 'pFastBacHTb', '', '', 1, '', 'E.coli', 'NM_001556.1?', '', '', 'XbaI/HindⅢ', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(403, 'pEgfpn1-glut4-HA', 'PA0404', 'pEgfpn1', '', '', 1, '', 'E.coli', 'gi:186552/m20747', '', '', 'BamH1/SalI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'SEQUENCED OK\r\n', 0),
(404, 'CDC25A', 'PA0405', 'pGEX-KG', '', '', 1, '', 'E.coli', 'gi:33873622', '', '1373-1939BP', 'EcoRI/ Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(405, 'CDC25B', 'PA0406', 'pGEX-KG', '', '', 1, '', 'E.coli', 'GI:11641416  ', '', '1354-1923 BP', ' Xbal I /Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(406, 'PTP-PEST', 'PA0407', 'pGEX-KG', '', '', 1, '', 'E.coli', 'BC050008   ', '', '280-1191BP', 'XbarI Sac I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(407, 'SIGMA-D1', 'PA0408', 'pGEX-2TK', '', '', 1, '', 'E.coli', 'gi:25092608  ', '', '3978-5066BP', 'BAMHI/ECORI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(408, 'SIGMA-D2', 'PA0409', 'pGEX-2TK', '', '', 1, '', 'E.coli', 'gi:25092608  ', '', '5067-5840BP', 'BAMHI/ECORI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(409, 'HDPTP', 'PA0410', 'pGEX-KG', '', '', 1, '', 'E.coli', 'GI:110681717   ', '', '3601-4476BP', 'XbarI Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(410, 'TCPTP', 'PA0411', 'pGEX-KG', '', '', 1, '', 'E.coli', 'BC008244   ', '', '41-1075BP', 'XbarI Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(411, 'PRL-3', 'PA0412', 'pGEX-KG', '', '', 1, '', 'E.coli', 'NM_032611  ', '', '335-856BP', 'EcoRI/ Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(412, 'GLEPP1', 'PA0413', 'pGEX-KG', '', '', 1, '', 'E.coli', 'GI:529411   ', '', '2841-3743BP', 'XbarI Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(413, 'DUSP13', 'PA0414', 'pGEX-KG', '', '', 1, '', 'E.coli', 'gi:14602534   ', '', '190-786BP', 'XbarI Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(414, 'CDKN3', 'PA0415', 'pGEX-KG', '', '', 1, '', 'E.coli', 'gi:40675533   ', '', '75-593BP', 'XbarI Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(415, 'DUSP16', 'PA0416', 'pGEX-KG', '', '', 1, '', 'E.coli', 'GI:80478279  ', '', '594-1703 BP', 'Xbar1 Xhol1', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(416, 'cd45-D1D2', 'PA0417', 'pGEX-KG', '', '', 1, '', 'E.coli', 'gi:18641363   ', '', '1698-3791 BP', 'XbarI Hind III', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(417, 'SAP1', 'PA0418', 'pGEX-KG', '', '', 1, '', 'E.coli', '', '', '', 'EcoRI/ Sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(418, 'LKB1', 'PA0419', 'pEBG', '', '', 1, '', 'mamalian', 'NP000446', '', '', 'Spe1/NotI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'No mutation\r\n', 0),
(419, 'LKB1-KD', 'PA0420', 'pEBG', '', '', 1, '', 'mamalian', 'NP000446', '', '', 'Spe1/NotI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Asp194Ala Kinase Dead Mutation\r\n', 0),
(420, 'MO25-alpha', 'PA0421', 'pCMV5', '', '', 1, '', 'mamalian', 'NP-057373', '', '', 'BamHI/BamHI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'subunit for LKB1\r\n', 0),
(421, 'STRAD-alpha', 'PA0422', 'pCMV5', '', '', 1, '', 'mamalian', 'NP-001003787', '', '', 'r1/XbaI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'subunit for LKB1\r\n', 0),
(422, 'CaMKK-beta', 'PA0423', 'pET-28b', '', '', 1, '', 'rat', 'AF-453383', '', '', 'NheI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, ' Mutations Gly96Arg  Pro108Leu 不影响活性\r\n', 0),
(423, 'AMPK-a-394/T174D', 'PA0424', 'pET-28b', '', '', 1, '', 'human', 'NP_996790.2', '', '', 'NcoI/XhoI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'Thr174Asp\r\n', 0),
(424, 'pFasbac-1-AMPK-a2/FL', 'PA0425', 'pFastbac-1', '', '', 1, '', 'human', 'NP_006243.2  ', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'for insect cell expression\r\n', 0),
(425, 'pFasbac-1-AMPK-b1/FL', 'PA0426', 'pFastbac-1', '', '', 1, '', 'human', 'NP_006244.2 ', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'for insect cell expression\r\n', 0),
(426, 'pFastbac-1-AMPK-g1/FL', 'PA0427', 'pFastbac-1', '', '', 1, '', 'human', 'NP_002724.1  ', '', '', 'BamHI/XbaI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'for insect cell expression\r\n', 0),
(427, 'pFasbac-Htb-AMPK-a2/FL', 'PA0428', 'pFasbac-Htb', '', '', 1, '', 'human', 'NP_006243.2  ', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'for insect cell expression\r\n', 0),
(428, 'pFasbac-Htb-AMPK-b1/FL', 'PA0429', 'pFasbac-Htb', '', '', 1, '', 'human', 'NP_006244.2 ', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'for insect cell expression\r\n', 0),
(429, 'pFasbac-Htb-AMPK-g1/FL', 'PA0430', 'pFasbac-Htb', '', '', 1, '', 'human', 'NP_002724.1  ', '', '', 'BamHI/XbaI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'for insect cell expression\r\n', 0),
(430, 'pFasbac-Htb-AMPK-a2/FL/T172D', 'PA0431', 'pFasbac-Htb', '', '', 1, '', 'human', 'NP_006243.2  ', '', '', 'BamHI/HindIII', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'for insect cell expression\r\n', 0),
(431, 'pBaBe-egfp', 'PA0432', 'pBABE', '', '', 1, '', 'E.coli', 'GI186552', '', '', 'EcoR/sal I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(432, 'pBaBe-glut4-egfp', 'PA0433', 'pBABE', '', '', 1, '', 'E.coli', 'GI186553', '', '', '"BamHI/SnaBI,EcoR/sal I"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(433, 'pBaBe-egfp-glut4', 'PA0434', 'pBABE', '', '', 1, '', 'E.coli', 'GI186554', '', '', '"BamHI/SnaBI,EcoR/sal I"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(434, 'L322Q-a1(394)', 'PA0435', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(435, 'A323R-a1(394)', 'PA0436', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(436, 'V324F-a1(394)', 'PA0437', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(437, 'A325E-a1(394)', 'PA0438', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(438, 'Y326F-a1(394)', 'PA0439', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(439, 'H327E-a1(394)', 'PA0440', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(440, 'L328Q-a1(394)', 'PA0441', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(441, 'I329N-a1(394)', 'PA0442', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(442, 'I330N-a1(394)', 'PA0443', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(443, 'I335N-a1(394)', 'PA0444', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(444, 'D320A-a1(394)', 'PA0445', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(445, 'D331A-a1(394)', 'PA0446', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(446, 'N332A-a1(394)', 'PA0447', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(447, 'R333A-a1(394)', 'PA0448', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(448, 'R334A-a1(394)', 'PA0449', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(449, 'L24Q-a1(394)', 'PA0450', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(450, 'L57Q-a1(394)', 'PA0451', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(451, 'L162Q-a1(394)', 'PA0452', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(452, 'L172Q-a1(394)', 'PA0453', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(453, 'L383A-a1(394)', 'PA0454', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(454, 'T379Y-a1(394)', 'PA0455', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(455, 'Y82G-a1(394)', 'PA0456', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(456, 'V298G-a1(394)', 'PA0457', 'pGEMEX-1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nhe I/EcoR I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(457, 'L39G-a1(394)', 'PA0458', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(458, 'Y82G-a1(394)', 'PA0459', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(459, 'V298G-a1(394)', 'PA0460', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(460, 'R74A-a1(394)', 'PA0461', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(461, 'P76A-a1(394)', 'PA0462', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(462, 'E96A-a1(394)?', 'PA0463', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(463, 'N154A-a1(394)', 'PA0464', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(464, 'K156A-a1(394)', 'PA0465', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(465, 'E281A-a1(394)', 'PA0466', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(466, 'S284A-a1(394)', 'PA0467', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(467, 'Y285A-a1(394)', 'PA0468', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(468, 'D291A-a1(394)', 'PA0469', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(469, 'Y314A-a1(394)', 'PA0470', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(470, 'K80L-a1(394)', 'PA0471', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(471, 'Y326F-a1(394)', 'PA0472', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(472, 'R74A-a1(312)', 'PA0473', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(473, 'P76A-a1(312)', 'PA0474', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(474, 'E96A-a1(312)', 'PA0475', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(475, 'N154A-a1(312)', 'PA0476', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(476, 'K156A-a1(312)', 'PA0477', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(477, 'E281A-a1(312)', 'PA0478', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(478, 'S284A-a1(312)', 'PA0479', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(479, 'Y285A-a1(312)', 'PA0480', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(480, 'D291A-a1(312)', 'PA0481', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(481, 'K80L-a1(312)', 'PA0482', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(482, 'P76A-a1(FL)', 'PA0483', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(483, 'E96A-a1(FL)', 'PA0484', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(484, 'K156A-a1(FL)', 'PA0485', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(485, 'Y285A-a1(FL)', 'PA0486', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(486, 'D291A-a1(FL)', 'PA0487', 'pET28b', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Nco I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(487, 'K80L-a1(FL)', 'PA0488', 'Duet-b1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Bln I/Kpn I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(488, 'P76A-a1(FL)', 'PA0489', 'Duet-b1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Bln I/Kpn I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(489, 'E96A-a1(FL)', 'PA0490', 'Duet-b1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Bln I/Kpn I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(490, 'K156A-a1(FL)', 'PA0491', 'Duet-b1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Bln I/Kpn I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(491, 'D291A-a1(FL)', 'PA0492', 'Duet-b1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Bln I/Kpn I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(492, 'K80L-a1(FL)', 'PA0493', 'Duet-b1', '', '', 1, '', 'E.coli', 'GI: NM_006251.5', '', '', 'Bln I/Kpn I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(493, 'a1(394)-V298G', 'PA0494', 'pcDNA3.1', '', '', 1, '', 'mammalian', 'GI: NM_006251.5', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(494, 'a1(394)-L328Q', 'PA0495', 'pcDNA3.1', '', '', 1, '', 'mammalian', 'GI: NM_006251.5', '', '', 'EcoR I/Xho I', '', '2008-01-20', '0000-00-00', 1, 0, 1, '测序正确\r\n', 0),
(495, 'socs2', 'PA0496', 'pDNR-LIB', '', '', 1, '', 'E.coli', 'BC010399', '', '', '"Sfi1,Sfi1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"三鹰,Chl"\r\n', 0),
(496, 'socs3', 'PA0497', 'pBluescriptR', '', '', 1, '', 'E.coli', 'BC060858', '', '', '"Not1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"三鹰,amp"\r\n', 0),
(497, 'socs4', 'PA0498', 'pBluescriptR', '', '', 1, '', 'E.coli', 'BC060790', '', '', '"Not1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"三鹰,amp"\r\n', 0),
(498, 'socs5', 'PA0499', 'pBluescriptR', '', '', 1, '', 'E.coli', 'BC032862', '', '', '"Not1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"三鹰,amp"\r\n', 0),
(499, 'socs6', 'PA0500', 'pCMV-SPORT6', '', '', 1, '', 'E.coli', 'BC020082', '', '', '"Not1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"三鹰,amp"\r\n', 0),
(500, 'a1R74A', 'PA0501', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(501, 'a1E281A', 'PA0502', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(502, 'a1S284A', 'PA0503', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(503, 'a1Y314A', 'PA0504', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(504, 'a1Y326F', 'PA0505', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(505, 'a1H327E', 'PA0506', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(506, 'a1D96E', 'PA0507', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(507, 'a1K156R', 'PA0508', 'pET28b', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Nco1,Xho1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(508, 'a1R74Ab1', 'PA0509', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(509, 'a1E281Ab1', 'PA0510', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(510, 'a1S284Ab1', 'PA0511', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(511, 'a1Y314Ab1', 'PA0512', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(512, 'a1Y326Fb1', 'PA0513', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(513, 'a1H327Eb1', 'PA0514', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(514, 'a1D96Eb1', 'PA0515', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(515, 'a1K156Rb1', 'PA0516', 'pETduet-1', '', '', 1, '', 'E.coli', 'gi|5453963|ref|NM  006251.1|', '', '24bp-1676bp', '"Bln1,Kpn1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '\r\n', 0),
(516, 'MARK2', 'PA0517', 'pET28b', '', '', 1, '', 'E.coli', 'NM_004954', '', '', '"Nhe1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'human\r\n', 0),
(517, 'MARK2KD+UBA', 'PA0518', 'pET28b', '', '', 1, '', 'E.coli', 'NM_004954', '', '', '"Nhe1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '39asn--371lys\r\n', 0),
(518, 'MARK2T175A', 'PA0519', 'pET28b', '', '', 1, '', 'E.coli', 'NM_004954', '', '', '"Nhe1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'human\r\n', 0),
(519, 'MARK2S179A', 'PA0520', 'pET28b', '', '', 1, '', 'E.coli', 'NM_004954', '', '', '"Nhe1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'human\r\n', 0),
(520, 'MARK2KD+UBAL298Q', 'PA0521', 'pET28b', '', '', 1, '', 'E.coli', 'NM_004954', '', '', '"Nhe1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"39asn--371lys,L298Q"\r\n', 0),
(521, 'MARK2KD+UBAL327Q', 'PA0522', 'pET28b', '', '', 1, '', 'E.coli', 'NM_004954', '', '', '"Nhe1,Sal1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"39asn--371lys,L327Q"\r\n', 0),
(522, 'MARK2', 'PA0523', 'pcDNA3.1C+', '', '', 1, '', '真核', 'NM_004954', '', '', '"Kpn1,Not1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'human\r\n', 0),
(523, 'MARK2T175A', 'PA0524', 'pcDNA3.1C+', '', '', 1, '', '真核', 'NM_004954', '', '', '"Kpn1,Not1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'human\r\n', 0),
(524, 'MARK2S179A', 'PA0525', 'pcDNA3.1C+', '', '', 1, '', '真核', 'NM_004954', '', '', '"Kpn1,Not1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'human\r\n', 0),
(525, 'CaMKKbeta', 'PA0526', 'pEGFPC1', '', '', 1, '', '真核', 'NM_031338', '', '', '"Hind3,BamH1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'rat\r\n', 0),
(526, 'CaMKKbeta469', 'PA0527', 'pEGFPC1', '', '', 1, '', '真核', 'NM_031338', '', '', '"Hind3,BamH1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'rat\r\n', 0),
(527, 'CaMKKbetaD329A', 'PA0528', 'pEGFPC1', '', '', 1, '', '真核', 'NM_031338', '', '', '"Hind3,BamH1"', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'rat\r\n', 0),
(528, 'SAP1(N-His)', 'PA0529', 'pET21b', '', '', 1, '', 'human', 'NM_002842.2', '', '', '"NdeI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"798-1095AA, pI=5.89,  Mw=35084"\r\n', 0),
(529, 'GLEPP1(N-His)', 'PA0530', 'pET28b', '', '', 1, '', 'human', 'NM_030667.1', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"917-1216AA, pI=5.62,  Mw=36195"\r\n', 0),
(530, 'PTP-PEST(N-His)', 'PA0531', 'pET28b', '', '', 1, '', 'human', 'NM_002835.2', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"29-332AA, pI=7.25,  Mw=37018"\r\n', 0),
(531, 'PTP-epsilon-D1D2(N-His)', 'PA0532', 'pET28b', '', '', 1, '', 'human', 'NM_006504.3', '', '', '"NcoI,NotI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"78-710AA, pI=6.95,  Mw=74638"\r\n', 0),
(532, 'DUSP16(N-His)', 'PA0533', 'pET28b', '', '', 1, '', 'human', 'NM_030640.1', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"1-367AA, pI=7.92,  Mw=41330"\r\n', 0),
(533, 'DUSP16-D(N-His)', 'PA0534', 'pET28b', '', '', 1, '', 'human', 'NM_030640.1', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"151-367AA, pI=8.64,  Mw=24723"\r\n', 0),
(534, 'CD45-D1(N-His)', 'PA0535', 'pET28b', '', '', 1, '', 'human', 'NM_080922.1', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"598-918AA, pI=6.71,  Mw=38379"\r\n', 0),
(535, '"CD45-D1(N,C-His)"', 'PA0536', 'pET28b', '', '', 1, '', 'human', 'NM_080922.1', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"598-918AA, pI=6.84,  Mw=39162"\r\n', 0),
(536, 'CD45-D2(N-His)', 'PA0537', 'pET28b', '', '', 1, '', 'human', 'NM_080922.1', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"919-1234AA, pI=6.06,  Mw=37712"\r\n', 0),
(537, 'CD45-D1D2(N-His)', 'PA0538', 'pET28b', '', '', 1, '', 'human', 'NM_080922.1', '', '', '"NcoI,XhoI"', '', '2008-01-20', '0000-00-00', 1, 0, 1, '"598-1234AA, pI=6.21,  Mw=75062"\r\n', 0),
(538, 'shp1wt', 'PA0539', 'pcDNA3.1', '', '', 1, '', 'human', 'GI:33876814', '', '136..1929', 'HindIII/EcoRI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2006年12月构建，无突变\r\n', 0),
(539, 'shp1DN', 'PA0540', 'pcDNA3.1', '', '', 1, '', 'human', 'GI:33876814', '', '136..1929', 'HindIII/EcoRI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'C455S\r\n', 0),
(540, 'shp2wt', 'PA0541', 'pcDNA3.1', '', '', 1, '', 'human', 'GI:33356177', '', '381..2162', 'HindIII/BamHI', '', '2008-01-20', '0000-00-00', 1, 0, 1, '2006年12月构建，无突变\r\n', 0),
(541, 'shp2DN', 'PA0542', 'pcDNA3.1', '', '', 1, '', 'human', 'GI:33356177', '', '381..2162', 'HindIII/BamHI', '', '2008-01-20', '0000-00-00', 1, 0, 1, 'C459S\r\n', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `posts`
-- 

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `created_by` smallint(6) unsigned NOT NULL,
  `updated_by` smallint(6) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- 
-- 导出表中的数据 `posts`
-- 

INSERT INTO `posts` (`id`, `name`, `content`, `date_create`, `date_update`, `created_by`, `updated_by`) VALUES 
(1, 'hello', 'hello', '2008-08-12 21:35:01', '0000-00-00 00:00:00', 1, 0),
(2, 'yuhaiping', 'yuhaiping', '2008-08-12 22:07:41', '0000-00-00 00:00:00', 1, 0),
(3, 'beijing2008', 'beijing2008', '2008-08-12 22:33:49', '0000-00-00 00:00:00', 1, 0),
(7, '金牌榜', '<ul>\r\n    <li><strong>中国代表团第13块金牌</strong></li>\r\n</ul>', '2008-08-13 12:27:45', '0000-00-00 00:00:00', 1, 0),
(9, '北京奥运会', '<em><strong>北京奥运会<br />\r\n雅典奥运会<br />\r\n悉尼奥运会</strong></em>', '2008-08-13 13:17:55', '2008-08-13 13:18:48', 1, 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `primers`
-- 

DROP TABLE IF EXISTS `primers`;
CREATE TABLE IF NOT EXISTS `primers` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `orientation` tinyint(1) unsigned NOT NULL,
  `concentration` varchar(25) collate utf8_unicode_ci NOT NULL,
  `purity` varchar(20) collate utf8_unicode_ci NOT NULL,
  `sequence` text collate utf8_unicode_ci NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text collate utf8_unicode_ci,
  `mask` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- 
-- 导出表中的数据 `primers`
-- 

INSERT INTO `primers` (`id`, `name`, `description`, `orientation`, `concentration`, `purity`, `sequence`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(9, 'caspase-3-f', 'ccc', 0, '1 mg/ml', 'page', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', '2008-01-09', '0000-00-00', 1, 0, 1, 'aaa', 0),
(10, 'caspase-3-r', 'bbb', 1, '2 μg/ml', 'purified', 'tttttttttttttttttttttt', '2008-01-09', '0000-00-00', 1, 0, 2, 'bbb', 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `projects`
-- 

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `name` varchar(30) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci,
  `date_start` date NOT NULL,
  `date_finish` date NOT NULL,
  `note` text collate utf8_unicode_ci,
  `state` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `projects`
-- 

INSERT INTO `projects` (`id`, `name`, `description`, `date_start`, `date_finish`, `note`, `state`) VALUES 
(1, 'others', '', '0000-00-00', '0000-00-00', '', 1),
(2, 'AMPK related kinases', '', '2007-01-01', '0000-00-00', '', 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `protein_sequences`
-- 

DROP TABLE IF EXISTS `protein_sequences`;
CREATE TABLE IF NOT EXISTS `protein_sequences` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `protein_id` int(9) unsigned NOT NULL,
  `sequence` text collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- 
-- 导出表中的数据 `protein_sequences`
-- 

INSERT INTO `protein_sequences` (`id`, `protein_id`, `sequence`) VALUES 
(1, 1, 'aaa');

-- --------------------------------------------------------

-- 
-- 表的结构 `proteins`
-- 

DROP TABLE IF EXISTS `proteins`;
CREATE TABLE IF NOT EXISTS `proteins` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `sequence_identifier` varchar(50) collate utf8_unicode_ci NOT NULL,
  `modification` varchar(200) collate utf8_unicode_ci NOT NULL,
  `amount` varchar(20) collate utf8_unicode_ci NOT NULL,
  `purity` varchar(20) collate utf8_unicode_ci NOT NULL,
  `mw` varchar(20) collate utf8_unicode_ci NOT NULL,
  `concentration` varchar(20) collate utf8_unicode_ci NOT NULL,
  `storage_buffer` text collate utf8_unicode_ci,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text collate utf8_unicode_ci,
  `mask` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- 
-- 导出表中的数据 `proteins`
-- 

INSERT INTO `proteins` (`id`, `name`, `description`, `sequence_identifier`, `modification`, `amount`, `purity`, `mw`, `concentration`, `storage_buffer`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(1, 'a', 'xxx', '2', 'm', '3', '4', '5', '6', '7', '2008-01-06', '2008-07-26', 1, 1, 1, '8', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `protocol_cat`
-- 

DROP TABLE IF EXISTS `protocol_cat`;
CREATE TABLE IF NOT EXISTS `protocol_cat` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `pid` tinyint(3) unsigned NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- 
-- 导出表中的数据 `protocol_cat`
-- 

INSERT INTO `protocol_cat` (`id`, `name`, `pid`, `note`) VALUES 
(1, 'PROTOCOLS', 0, ''),
(2, 'Cell biology', 1, ''),
(3, 'Molecular biology', 1, ''),
(5, '昆虫细胞培养', 2, 'Insect cell culture'),
(6, '逆转录病毒', 2, '逆转录病毒培养方法');

-- --------------------------------------------------------

-- 
-- 表的结构 `protocol_update_log`
-- 

DROP TABLE IF EXISTS `protocol_update_log`;
CREATE TABLE IF NOT EXISTS `protocol_update_log` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `protocol_id` smallint(6) unsigned NOT NULL,
  `date_update` datetime NOT NULL,
  `updated_by` smallint(6) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

-- 
-- 导出表中的数据 `protocol_update_log`
-- 

INSERT INTO `protocol_update_log` (`id`, `protocol_id`, `date_update`, `updated_by`) VALUES 
(1, 3, '2008-07-20 11:04:27', 1),
(2, 3, '2008-07-20 11:08:55', 1),
(3, 3, '2008-07-20 11:15:28', 1),
(4, 5, '2008-07-20 11:15:41', 1),
(5, 5, '2008-07-20 11:16:03', 1),
(6, 5, '2008-07-20 13:02:16', 1),
(7, 5, '2008-07-20 13:02:56', 1),
(8, 5, '2008-07-20 13:05:00', 1),
(9, 3, '2008-07-20 13:47:56', 1),
(10, 5, '2008-07-20 13:54:34', 1),
(11, 5, '2008-07-20 13:59:21', 1),
(12, 5, '2008-07-20 14:00:48', 1),
(13, 5, '2008-07-20 14:01:18', 1),
(14, 5, '2008-07-20 14:01:45', 1),
(15, 5, '2008-07-20 14:02:38', 1),
(16, 5, '2008-07-20 14:03:46', 1),
(17, 5, '2008-07-20 14:04:28', 1),
(18, 5, '2008-07-20 14:05:42', 1),
(19, 5, '2008-07-20 14:48:52', 1),
(20, 5, '2008-07-20 14:51:44', 1),
(21, 5, '2008-07-20 14:53:01', 1),
(22, 5, '2008-07-20 15:34:07', 1),
(23, 5, '2008-07-20 15:35:47', 1),
(24, 5, '2008-07-20 16:03:03', 1),
(25, 5, '2008-07-20 16:18:11', 1),
(26, 5, '2008-07-20 16:31:49', 1),
(27, 5, '2008-07-20 16:36:26', 1),
(28, 5, '2008-07-20 16:37:17', 1),
(29, 5, '2008-07-20 16:37:31', 1),
(30, 5, '2008-07-20 16:38:07', 1),
(31, 5, '2008-07-20 16:38:28', 1),
(32, 5, '2008-07-20 16:38:45', 1),
(33, 5, '2008-07-20 16:38:48', 1),
(34, 5, '2008-07-20 17:56:08', 1),
(35, 5, '2008-07-20 18:35:59', 1),
(36, 5, '2008-07-20 18:46:33', 1),
(37, 5, '2008-07-20 18:50:33', 1),
(38, 5, '2006-01-01 00:02:44', 1),
(39, 5, '2008-07-30 13:30:53', 1),
(40, 5, '2008-07-30 13:31:59', 1),
(41, 5, '2008-07-30 13:35:43', 1),
(42, 5, '2008-07-30 13:36:26', 1),
(43, 5, '2008-07-30 13:41:23', 1),
(44, 5, '2008-07-30 13:43:48', 1),
(45, 5, '2008-07-30 13:47:22', 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `protocols`
-- 

DROP TABLE IF EXISTS `protocols`;
CREATE TABLE IF NOT EXISTS `protocols` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `cat_id` tinyint(3) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `created_by` smallint(6) unsigned NOT NULL,
  `updated_by` smallint(6) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- 导出表中的数据 `protocols`
-- 

INSERT INTO `protocols` (`id`, `cat_id`, `name`, `content`, `date_create`, `date_update`, `created_by`, `updated_by`) VALUES 
(5, 5, '悬浮昆虫细胞的培养', '<h4><span style="font-size: larger"><span><span><strong>根据 Growth and Maintenance of Insect Cell Lines（Invitrogen）翻译<br />\r\n</strong></span></span></span><img height="94" alt="" width="100" src="/quicklab/data/protocols/image/beijing2008.jpg" /><br />\r\n&nbsp;</h4>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">在悬浮培养之前，必须有贴壁培养的细胞。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">1．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">在悬浮培养的状态下，细胞可以维持一个月的对数生长状态。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">2．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">关于细胞密度，对于</span><span style="font-size: 9pt">Sf9, Sf21 </span><span style="font-size: 9pt">和</span><span style="font-size: 9pt"> High Five</span><span style="font-size: 9pt">细胞来说，如果达到</span><span style="font-size: 9pt">2-2.5</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup></span><span style="font-size: 9pt">（</span><span style="font-size: 9pt">40-50</span><span style="font-size: 9pt">）</span><span style="font-size: 9pt">cells/ml</span><span style="font-size: 9pt">，需要稀释至不超过</span><span style="font-size: 9pt">7</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>5</sup> cells/ml.</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">3．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">使用合适的培养瓶，</span><span style="font-size: 9pt">Vertical impeller</span><span style="font-size: 9pt">的要优于</span><span style="font-size: 9pt">hanging stir-bar.</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">4．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">培养物的体积不能超过标注体积的</span><span style="font-size: 9pt">1/2</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">5．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">使用表面活性剂</span><span style="font-size: 9pt"> (surfactant) </span><span style="font-size: 9pt">减少剪切力</span><span style="font-size: 9pt"> (shearing). </span><span style="font-size: 9pt">推荐使用</span><span style="font-size: 9pt">0.1% Pluronic&reg; F-68</span><span style="font-size: 9pt">，</span><span style="font-size: 9pt">Sf-900 II SFM </span><span style="font-size: 9pt">和</span><span style="font-size: 9pt">Express Five &reg; SFM</span><span style="font-size: 9pt">已经含有表面活性剂。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">6．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">旧的培养基不需要移除。新加入的培养基足够补充细胞营养。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">7．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">螺旋桨旋转要平滑，这对于充分的气体交换和较高的细胞存活率来说是必须的。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">保持旋转培养瓶的无菌状态：</span></b></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">1．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">培养细胞时盖紧瓶盖。闲置时也要盖紧瓶盖，湿气可能引起污染，只有在灭菌时需要松开瓶盖。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">2．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">在添除培养基时，避免将移液管、枪头以及培养基接触到旋转培养瓶臂的周围。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">3．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">残留的肥皂和去垢剂会影响细胞生长。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">4．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">培养过杆状病毒的瓶子至少高压灭菌两次，一次</span><span style="font-size: 9pt">wet cycle</span><span style="font-size: 9pt">，一次</span><span style="font-size: 9pt">dry cycle</span><span style="font-size: 9pt">，一次灭菌无法杀死杆状病毒。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">在开始悬浮培养时，细胞的存活率要大于</span><span style="font-size: 9pt">95</span><span style="font-size: 9pt">％，细胞密度要大于</span><span style="font-size: 9pt">1</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，培养基在培养瓶中要淹没螺旋桨超过</span><span style="font-size: 9pt">1cm</span><span style="font-size: 9pt">。以下是不同体积培养瓶对应的最小培养物体积：</span></div>\r\n<p>\r\n<table style="border-right: medium none; border-top: medium none; border-left: medium none; border-bottom: medium none; border-collapse: collapse" cellspacing="0" cellpadding="0" border="1">\r\n    <tbody>\r\n        <tr style="height: 14.85pt">\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: windowtext 0.5pt solid; padding-left: 5.4pt; padding-bottom: 0pt; border-left: windowtext 0.5pt solid; width: 95.4pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; height: 14.85pt; background-color: transparent" valign="top" width="127">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">Size of spinner (ml)</span></div>\r\n            </td>\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: windowtext 0.5pt solid; padding-left: 5.4pt; padding-bottom: 0pt; border-left: #e2e2e2; width: 135pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; height: 14.85pt; background-color: transparent" valign="top" width="180">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">Minimum volumn required (ml)</span></div>\r\n            </td>\r\n        </tr>\r\n        <tr style="height: 13.75pt">\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: #e2e2e2; padding-left: 5.4pt; padding-bottom: 0pt; border-left: windowtext 0.5pt solid; width: 95.4pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; height: 13.75pt; background-color: transparent" valign="top" width="127">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">100</span></div>\r\n            </td>\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: #e2e2e2; padding-left: 5.4pt; padding-bottom: 0pt; border-left: #e2e2e2; width: 135pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; height: 13.75pt; background-color: transparent" valign="top" width="180">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">30</span></div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: #e2e2e2; padding-left: 5.4pt; padding-bottom: 0pt; border-left: windowtext 0.5pt solid; width: 95.4pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; background-color: transparent" valign="top" width="127">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">250</span></div>\r\n            </td>\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: #e2e2e2; padding-left: 5.4pt; padding-bottom: 0pt; border-left: #e2e2e2; width: 135pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; background-color: transparent" valign="top" width="180">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">80</span></div>\r\n            </td>\r\n        </tr>\r\n        <tr>\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: #e2e2e2; padding-left: 5.4pt; padding-bottom: 0pt; border-left: windowtext 0.5pt solid; width: 95.4pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; background-color: transparent" valign="top" width="127">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">500</span></div>\r\n            </td>\r\n            <td style="border-right: windowtext 0.5pt solid; padding-right: 5.4pt; border-top: #e2e2e2; padding-left: 5.4pt; padding-bottom: 0pt; border-left: #e2e2e2; width: 135pt; padding-top: 0pt; border-bottom: windowtext 0.5pt solid; background-color: transparent" valign="top" width="180">\r\n            <div style="margin: 0pt; line-height: 150%" align="center"><span style="font-size: 9pt">200</span></div>\r\n            </td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n</p>\r\n<div style="margin: 0pt; line-height: 150%">&nbsp;</div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 15pt">High Five</span><span style="font-size: 15pt">细胞的悬浮培养</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">在培养</span><span style="font-size: 9pt">High Five</span><span style="font-size: 9pt">细胞时，无血清培养基中通常要加入肝素（</span><span style="font-size: 9pt">heparin</span><span style="font-size: 9pt">），减少大的细胞结团</span><span style="font-size: 9pt">(&gt; 10 cells per</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">aggregate)</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">细胞密度不要超过</span><span style="font-size: 9pt">2.5</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，否则容易形成大的结团。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">在无血清培养基中加入</span><span style="font-size: 9pt">10 units/ml</span><span style="font-size: 9pt">的肝素。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">小的细胞结团</span><span style="font-size: 9pt">(5-10 cells per aggregate)</span><span style="font-size: 9pt">对于</span><span style="font-size: 9pt">High Five</span><span style="font-size: 9pt">细胞来说时正常的，不影响表达。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">需要的仪器材料：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">High Five<sup>TM</sup> insect cells (Invitrogen)</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Express Five &reg; SFM (Invitrogen)</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Heparin, tissue culture grade (Sigma, Catalog no. H3149)</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Spinner flasks (Bellco #1965 Series)</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Magnetic stir plate</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Hemacytometer</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">27&deg;C constant temperature incubator, CO<sub>2</sub> not required</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">悬浮培养起始阶段：</span></b></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">1．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">将贴壁培养的细胞转移到旋转培养瓶中，细胞密度控制在</span><span style="font-size: 9pt">0.7-1.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">2．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">27</span><span style="font-size: 9pt">℃，</span><span style="font-size: 9pt">80-90rpm</span><span style="font-size: 9pt">，培养</span><span style="font-size: 9pt">24h</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">3．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">24h</span><span style="font-size: 9pt">后，细胞存活率应该在</span><span style="font-size: 9pt">90</span><span style="font-size: 9pt">％左右，细胞密度如果小于</span><span style="font-size: 9pt">2.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，继续培养。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">4．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">一旦细胞密度大于</span><span style="font-size: 9pt">2.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，将一半细胞培养物转移至另一个旋转培养瓶，细胞密度达到</span><span style="font-size: 9pt">1.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，如果希望细胞在传代前培养时间超过</span><span style="font-size: 9pt">24h</span><span style="font-size: 9pt">，例如周末，控制细胞密度</span><span style="font-size: 9pt">0.8</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">5．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">继续培养细胞，至细胞密度</span><span style="font-size: 9pt">2.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，细胞存活率</span><span style="font-size: 9pt">95</span><span style="font-size: 9pt">％，扩大培养。（例如从两个</span><span style="font-size: 9pt">100ml</span><span style="font-size: 9pt">培养瓶扩大到一个</span><span style="font-size: 9pt">500ml</span><span style="font-size: 9pt">培养瓶）</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">悬浮培养<span style="color: red">适应</span>阶段：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">完全适应悬浮培养的定义是在</span><span style="font-size: 9pt">500ml</span><span style="font-size: 9pt">的悬浮培养基中细胞存活率大于</span><span style="font-size: 9pt">98</span><span style="font-size: 9pt">％，</span><span style="font-size: 9pt">18-24h</span><span style="font-size: 9pt">扩增一倍。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">1．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">将两个</span><span style="font-size: 9pt">100ml</span><span style="font-size: 9pt">培养瓶中的培养物合到一个</span><span style="font-size: 9pt">500ml</span><span style="font-size: 9pt">培养瓶中，加入</span><span style="font-size: 9pt">150ml</span><span style="font-size: 9pt">添加肝素的无血清培养基，细胞密度不能低于</span><span style="font-size: 9pt">0.8</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">2．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">继续传代，依照上面第</span><span style="font-size: 9pt">4</span><span style="font-size: 9pt">步，直至细胞存活率大于</span><span style="font-size: 9pt">98</span><span style="font-size: 9pt">％，</span><span style="font-size: 9pt">18-24h</span><span style="font-size: 9pt">扩增一倍。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">3．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">一旦细胞存活率大于</span><span style="font-size: 9pt">98</span><span style="font-size: 9pt">％，</span><span style="font-size: 9pt">18-24h</span><span style="font-size: 9pt">扩增一倍，就可以慢慢去掉肝素。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">一旦细胞完全适应悬浮培养，它们不会在缺少肝素的情况下结团。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">悬浮培养的维持：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">长期保存悬浮培养的</span></b><b><span style="font-size: 9pt">High Five</span></b><b><span style="font-size: 9pt">细胞：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">完全适应悬浮培养的</span><span style="font-size: 9pt">High Five</span><span style="font-size: 9pt">细胞可以用冻存培养基冻存</span><span style="font-size: 9pt"> (42.5% conditioned medium, 42.5% fresh medium, 10% DMSO, and 5% FBS)</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">1．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">细胞密度</span><span style="font-size: 9pt">3</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">2．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">-80</span><span style="font-size: 9pt">℃，</span><span style="font-size: 9pt">24h</span><span style="font-size: 9pt">，然后转移到液氮中。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">复苏已经完全适应悬浮培养的</span></b><b><span style="font-size: 9pt">High Five</span></b><b><span style="font-size: 9pt">细胞：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">复苏时仍然需要贴壁培养，依照上述方法，适应时间较没有适应悬浮培养的</span><span style="font-size: 9pt">High Five</span><span style="font-size: 9pt">细胞短。</span></div>\r\n<div style="margin: 0pt; line-height: 150%">&nbsp;</div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 15pt">Sf9</span><span style="font-size: 15pt">、</span><span style="font-size: 15pt">Sf21</span><span style="font-size: 15pt">细胞的悬浮培养：</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">需要的仪器材料：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Adherent Sf9 or Sf21 insect cells</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Spinner flasks (Bellco #1965 Series)</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">Magnetic stir plate</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">27&deg;C constant temperature incubator, CO<sub>2</sub> not required</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">Sf9</span></b><b><span style="font-size: 9pt">、</span></b><b><span style="font-size: 9pt">Sf21</span></b><b><span style="font-size: 9pt">细胞的悬浮培养：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">起始悬浮培养前，必须有对数生长期的，生存率大于</span><span style="font-size: 9pt">95</span><span style="font-size: 9pt">％的细胞。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">1．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">得到足够的处于对数生长期的贴壁培养的细胞，保证起始悬浮培养细胞密度不低于</span><span style="font-size: 9pt">1</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">建议开始使用</span><span style="font-size: 9pt">100ml</span><span style="font-size: 9pt">或者</span><span style="font-size: 9pt">250ml</span><span style="font-size: 9pt">的旋转培养瓶，这样需要的细胞量较少。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">2．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">将贴壁培养的细胞转移至旋转培养瓶中，细胞密度</span><span style="font-size: 9pt">1</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">3．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">27</span><span style="font-size: 9pt">℃，</span><span style="font-size: 9pt">80-90rpm</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt 0pt 0pt 18pt; text-indent: -18pt; line-height: 150%"><span style="font-size: 9pt">4．<span style="font: 7pt ''Times New Roman''">&nbsp;&nbsp; </span></span><span style="font-size: 9pt">细胞密度达到</span><span style="font-size: 9pt">2.0-2.5</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，加入培养基使细胞密度稀释至</span><span style="font-size: 9pt">1</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，这需要</span><span style="font-size: 9pt">24-72h</span><span style="font-size: 9pt">，需要每天检查细胞密度、存活率。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><b><span style="font-size: 9pt">Sf9</span></b><b><span style="font-size: 9pt">、</span></b><b><span style="font-size: 9pt">Sf21</span></b><b><span style="font-size: 9pt">细胞悬浮培养的维持：</span></b></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">一旦培养物达到旋转培养瓶允许的最大体积，你可以转移至一个更大的培养瓶或者保持现有的体积。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">保持现有体积：当细胞密度达到</span><span style="font-size: 9pt">2.0-2.5</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">，进行次培养（</span><span style="font-size: 9pt">subculture</span><span style="font-size: 9pt">，除去一部分培养物，添加新的培养基），保证细胞密度大于</span><span style="font-size: 9pt">1.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。</span></div>\r\n<div style="margin: 0pt; line-height: 150%"><span style="font-size: 9pt">扩大培养：加入足够的，细胞密度</span><span style="font-size: 9pt">2.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">的培养物，保证在大培养瓶中添加新鲜培养基后细胞密度达到</span><span style="font-size: 9pt">1.0</span><span style="font-size: 9pt">&times;</span><span style="font-size: 9pt">10<sup>6</sup> cells/ml</span><span style="font-size: 9pt">。接下去可以保持体积培养或者继续扩大培养。</span></div>', '2008-07-19 21:55:30', '0000-00-00 00:00:00', 1, 0),
(3, 5, 'HI5  细胞培养', '<div>fuck you!</div>', '2008-07-19 21:38:37', '0000-00-00 00:00:00', 1, 0),
(4, 5, '杆粒制备', '', '2008-07-19 21:46:13', '0000-00-00 00:00:00', 1, 0),
(7, 5, 'Sf9 细胞培养', '', '2008-07-20 12:28:24', '0000-00-00 00:00:00', 1, 0),
(6, 5, '培养基配方', '', '2008-07-20 08:31:12', '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `reagent_cat`
-- 

DROP TABLE IF EXISTS `reagent_cat`;
CREATE TABLE IF NOT EXISTS `reagent_cat` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- 导出表中的数据 `reagent_cat`
-- 

INSERT INTO `reagent_cat` (`id`, `name`) VALUES 
(1, 'others'),
(2, 'amino acids'),
(3, 'taq 酶');

-- --------------------------------------------------------

-- 
-- 表的结构 `reagents`
-- 

DROP TABLE IF EXISTS `reagents`;
CREATE TABLE IF NOT EXISTS `reagents` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `reagent_cat_id` tinyint(3) unsigned NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text NOT NULL,
  `mask` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- 导出表中的数据 `reagents`
-- 

INSERT INTO `reagents` (`id`, `name`, `description`, `reagent_cat_id`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(3, '冻干人凝血酶', 'a', 1, '2008-01-20', '0000-00-00', 1, 0, 1, 'a', 0),
(2, 'KOD-Plus', 'aaa', 3, '2008-01-20', '2008-01-20', 1, 1, 1, 'ccc', 0),
(4, '硫酸卡那霉素', 'a', 2, '2008-01-20', '0000-00-00', 1, 0, 2, 'a', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `samples`
-- 

DROP TABLE IF EXISTS `samples`;
CREATE TABLE IF NOT EXISTS `samples` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` smallint(6) unsigned NOT NULL,
  `updated_by` smallint(6) unsigned NOT NULL,
  `project` smallint(6) unsigned NOT NULL,
  `note` text NOT NULL,
  `mask` tinyint(1) unsigned NOT NULL,
  `custom_field_1` varchar(20) NOT NULL,
  `custom_field_2` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- 导出表中的数据 `samples`
-- 

INSERT INTO `samples` (`id`, `name`, `description`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`, `custom_field_1`, `custom_field_2`) VALUES 
(2, 'TEST', 'test', '2008-08-03', '2008-08-05', 1, 1, 1, 'test', 0, 'weight', 'organism'),
(4, '1', '2', '2008-08-03', '2008-08-04', 1, 1, 1, '3', 0, '5', '6');

-- --------------------------------------------------------

-- 
-- 表的结构 `sellers`
-- 

DROP TABLE IF EXISTS `sellers`;
CREATE TABLE IF NOT EXISTS `sellers` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `address` varchar(150) collate utf8_unicode_ci NOT NULL,
  `tel` varchar(30) collate utf8_unicode_ci NOT NULL,
  `fax` varchar(30) collate utf8_unicode_ci NOT NULL,
  `email` varchar(100) collate utf8_unicode_ci NOT NULL,
  `url` blob NOT NULL,
  `note` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=70 ;

-- 
-- 导出表中的数据 `sellers`
-- 

INSERT INTO `sellers` (`id`, `name`, `address`, `tel`, `fax`, `email`, `url`, `note`) VALUES 
(1, 'ABgene', '', '', '', '', '', ''),
(2, 'BD', '', '', '', '', '', ''),
(3, 'Corning', '', '', '', '', '', ''),
(4, 'GE Healthcare', '', '', '', '', '', ''),
(5, 'Greiner bio-one', '', '', '', '', '', ''),
(6, 'Hyclone', '', '', '', '', '', ''),
(7, 'Invitrogen', '', '', '', '', '', ''),
(8, 'Merck', '', '', '', '', '', ''),
(9, 'Millipore', '', '', '', '', '', ''),
(10, 'Pierce', '', '', '', '', '', ''),
(11, 'QIAGEN', '', '', '', '', '', ''),
(12, 'R&D', '', '', '', '', '', ''),
(13, 'Sigma-Aldrich', '', '', '', '', '', ''),
(14, '保定兰格恒流泵有限公司', '', '', '', '', '', ''),
(15, '博奥生物有限公司', '', '', '', '', '', ''),
(16, '大龙医疗设备（上海）有限公司', '', '', '', '', '', ''),
(17, '国家上海新药安全评价研究中心', '', '', '', '', '', ''),
(18, '国药集团化学试剂有限公司', '', '', '', '', '', ''),
(19, '海门市碧云天生物技术研究所', '', '', '', '', '', ''),
(20, '海门市浩天玻璃仪器经销部', '', '', '', '', '', ''),
(21, '海门市三和华宁玻塑仪器经营部', '', '', '', '', '', ''),
(22, '海门市三和金冠玻璃仪器厂', '', '', '', '', '', ''),
(23, '海门市三和镇佳琪玻璃塑仪器经营部', '', '', '', '', '', ''),
(24, '海门市三和镇金博实验仪器经营部', '', '', '', '', '', ''),
(25, '海门市三和志安实验器材销售部', '', '', '', '', '', ''),
(26, '海门市善德玻璃仪器经营部', '', '', '', '', '', ''),
(27, '海门市天补镇江海玻塑仪器经营部', '', '', '', '', '', ''),
(28, '海门市中海实验器材销售部', '', '', '', '', '', ''),
(29, '华粤行仪器有限公司', '', '', '', '', '', ''),
(30, '吉尔生化（上海）有限公司', '', '', '', '', '', ''),
(31, '联邦快递（中国）有限公司上海分公司', '', '', '', '', '', ''),
(32, '麦德龙', '', '', '', '', '', ''),
(33, '梅特勒-托利多仪器（上海）有限公司', '', '', '', '', '', ''),
(34, '珀金埃尔默仪器（上海）有限公司', '', '', '', '', '', ''),
(35, '上海艾本德生物技术国际贸易有限公司', '', '', '', '', '', ''),
(36, '上海安谱科学仪器有限公司', '', '', '', '', '', ''),
(37, '上海安星科学器材进出口有限公司', '', '', '', '', '', ''),
(38, '上海百赛生物技术有限公司', '', '', '', '', '', ''),
(39, '上海保利科技有限公司', '', '', '', '', '', ''),
(40, '上海创未生物技术有限公司', '', '', '', '', '', ''),
(41, '上海鼎安生物科技有限公司', '', '', '', '', '', ''),
(42, '上海鼎国生物技术有限公司', '', '', '', '', '', ''),
(43, '上海复旦张江生物医药股份有限公司', '', '', '', '', '', ''),
(44, '上海复日科技有限公司', '', '', '', '', '', ''),
(45, '上海海尔工贸有限公司', '', '', '', '', '', ''),
(46, '上海皓嘉科技发展有限公司', '', '', '', '', '', ''),
(47, '上海基迈生物科技有限公司', '', '', '', '', '', ''),
(48, '上海吉泰新绎生物科技有限公司', '', '', '', '', '', ''),
(49, '上海捷倍思基因技术有限公司', '', '', '', '', '', ''),
(50, '上海晶美生物技术有限公司', '', '', '', '', '', ''),
(51, '上海美季生物技术有限公司', '', '', '', '', '', ''),
(52, '上海睿星基因技术有限公司', '', '', '', '', '', ''),
(53, '上海赛百盛基因技术有限公司', '', '', '', '', '', ''),
(54, '上海生工生物工程技术服务有限公司', '', '', '', '', '', ''),
(55, '上海双向西巴斯科技发展有限公司', '', '', '', '', '', ''),
(56, '上海硕盟生物科技有限公司', '', '', '', '', '', ''),
(57, '上海天美生物科技有限公司', '', '', '', '', '', ''),
(58, '上海天能科技有限公司', '', '', '', '', '', ''),
(59, '上海同田生化技术有限公司', '', '', '', '', '', ''),
(60, '上海业力生物科技有限公司', '', '', '', '', '', ''),
(61, '上海英骏生物技术有限公司', '', '', '', '', '', ''),
(62, '上海优宁维生物科技有限公司', '', '', '', '', '', ''),
(63, '上海展舒化学试剂有限公司', '', '', '', '', '', ''),
(64, '上海志壮生物科技有限公司', '', '', '', '', '', ''),
(65, '上海智城分析仪器制造有限公司', '', '', '', '', '', ''),
(66, '天根生化科技（北京）有限公司', '', '', '', '', '', ''),
(67, '武汉三鹰生物技术有限公司', '', '', '', '', '', ''),
(68, '中国科学院上海生命科学研究院采购部', '', '', '', '', '', ''),
(69, '中国药品生物制品检定所', '', '', '', '', '', '');

-- --------------------------------------------------------

-- 
-- 表的结构 `sequences`
-- 

DROP TABLE IF EXISTS `sequences`;
CREATE TABLE IF NOT EXISTS `sequences` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `seq_type` tinyint(1) unsigned NOT NULL,
  `sequence_identifier` varchar(50) NOT NULL,
  `sequence` mediumtext NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text NOT NULL,
  `mask` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- 导出表中的数据 `sequences`
-- 

INSERT INTO `sequences` (`id`, `name`, `description`, `seq_type`, `sequence_identifier`, `sequence`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(1, 'TVFV2E', 'TVFV2E envelope protein', 3, 'gi|532319', 'elrlrycapagfallkcndadydgfktncsnvsvvhctnlmnttvttglllngsysenrtqiwqkhrtsndsalillnkhynltvtckrpgnktvlpvtimaglvfhsqkynlrlrqawchfpsnwkgawkevkeeivnlpkeryrgtndpkriffqrqwgdpetanlwfnchgeffyckmdwflnylnnltvdadhneckntsgtksgnkrapgpcvqrtyvachirsviiwletiskktyappreghlectstvtgmtvelnyipknrtnvtlspqiesiwaaeldryklveitpigfaptevrrytggherqkrvpfvxxxxxxxxxxxxxxxxxxxxxxvqsqhllagilqqqknllaaveaqqqmlkltiwgvk', '2008-01-20', '2008-01-20', 1, 1, 1, 'ccc', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `solution_components`
-- 

DROP TABLE IF EXISTS `solution_components`;
CREATE TABLE IF NOT EXISTS `solution_components` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `solution_id` int(9) unsigned NOT NULL,
  `component` varchar(100) NOT NULL,
  `mw` double unsigned NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  `stock_value` double unsigned NOT NULL,
  `stock_unit` smallint(3) unsigned NOT NULL,
  `final_value` double unsigned NOT NULL,
  `final_unit` smallint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- 
-- 导出表中的数据 `solution_components`
-- 

INSERT INTO `solution_components` (`id`, `solution_id`, `component`, `mw`, `state`, `stock_value`, `stock_unit`, `final_value`, `final_unit`) VALUES 
(1, 1, 'G250', 0, 0, 0, 0, 100, 8),
(2, 1, '95%乙醇', 0, 0, 0, 0, 50, 12),
(12, 1, '85%磷酸', 0, 0, 0, 0, 100, 12);

-- --------------------------------------------------------

-- 
-- 表的结构 `solution_units`
-- 

DROP TABLE IF EXISTS `solution_units`;
CREATE TABLE IF NOT EXISTS `solution_units` (
  `id` smallint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `conversion` double unsigned NOT NULL,
  `stocktype` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- 
-- 导出表中的数据 `solution_units`
-- 

INSERT INTO `solution_units` (`id`, `name`, `conversion`, `stocktype`) VALUES 
(1, 'M', 1, 0),
(2, 'mM', 0.001, 0),
(3, 'μM', 1e-006, 0),
(4, 'nM', 1e-009, 0),
(5, '% (w/v)', 10, 0),
(6, 'g/mL', 1000, 0),
(7, 'mg/mL (g/L)', 1, 0),
(8, 'μg/mL (mg/L)', 0.001, 0),
(9, 'ng/mL (μg/L)', 1e-006, 0),
(10, 'ppm', 0.001, 0),
(11, '% (v/v)', 0.01, 1),
(12, 'mL/L', 0.001, 1),
(13, '×', 1, 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `solutions`
-- 

DROP TABLE IF EXISTS `solutions`;
CREATE TABLE IF NOT EXISTS `solutions` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `synonym` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) NOT NULL,
  `updated_by` mediumint(6) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `solutions`
-- 

INSERT INTO `solutions` (`id`, `name`, `synonym`, `description`, `date_create`, `date_update`, `created_by`, `updated_by`, `note`) VALUES 
(1, 'G250', '', '', '2008-04-16', '2008-06-27', 1, 1, '');

-- --------------------------------------------------------

-- 
-- 表的结构 `species`
-- 

DROP TABLE IF EXISTS `species`;
CREATE TABLE IF NOT EXISTS `species` (
  `id` mediumint(6) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `latin` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- 
-- 导出表中的数据 `species`
-- 

INSERT INTO `species` (`id`, `name`, `latin`) VALUES 
(1, 'Human', 'Homo sapiens'),
(2, 'Mouse', 'Mus musculus'),
(3, 'E. coli', 'Escherichia coli'),
(4, 'Rat', 'Rattus sp.'),
(5, 'Hamster', 'Cricetulus griseus'),
(6, 'Rabbit', 'Oryctolagus cuniculus '),
(7, 'Chicken', 'Gallus gallus'),
(8, 'Bovine', 'Bos taurus'),
(9, 'Goat', 'Capra hircus'),
(10, 'Sheep', 'Ovis aries'),
(11, 'Pig', 'Sus scrofa'),
(12, 'Horse', 'Equus caballus'),
(14, 'fall armyworm', 'Spodoptera frugiperda');

-- --------------------------------------------------------

-- 
-- 表的结构 `storages`
-- 

DROP TABLE IF EXISTS `storages`;
CREATE TABLE IF NOT EXISTS `storages` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `module_id` tinyint(3) NOT NULL,
  `item_id` int(9) NOT NULL,
  `keeper` mediumint(6) unsigned NOT NULL,
  `location_id` mediumint(6) NOT NULL,
  `location_details` varchar(50) collate utf8_unicode_ci NOT NULL,
  `date_storage` date NOT NULL,
  `date_expiry` date NOT NULL,
  `note` text collate utf8_unicode_ci,
  `order_id` int(9) unsigned NOT NULL,
  `state` tinyint(1) unsigned NOT NULL default '1',
  `mask` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- 
-- 导出表中的数据 `storages`
-- 

INSERT INTO `storages` (`id`, `name`, `module_id`, `item_id`, `keeper`, `location_id`, `location_details`, `date_storage`, `date_expiry`, `note`, `order_id`, `state`, `mask`) VALUES 
(1, 'cells:HCT 116', 6, 5, 1, 662, '', '2008-02-17', '0000-00-00', '', 0, 1, 0),
(2, 'cells:A549', 6, 4, 1, 662, '', '2008-02-17', '0000-00-00', '', 0, 1, 0),
(3, 'cells:Sf9', 6, 1, 1, 662, '', '2008-02-17', '0000-00-00', '', 0, 1, 0),
(4, 'aaa', 0, 0, 1, 3, '', '2008-02-17', '0000-00-00', '', 0, 1, 0),
(7, 'primers:caspase-3-f', 1, 9, 1, 3, '', '2008-02-20', '0000-00-00', '', 1, 1, 0),
(9, 'primers:caspase-3-f', 1, 9, 1, 405, '', '2008-02-21', '0000-00-00', '', 5, 1, 0),
(10, 'primers:caspase-3-r', 1, 10, 1, 2, '', '2008-02-23', '0000-00-00', '', 0, 0, 1),
(11, 'quick', 0, 0, 1, 2, '', '2008-02-24', '0000-00-00', '', 7, 0, 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `substances`
-- 

DROP TABLE IF EXISTS `substances`;
CREATE TABLE IF NOT EXISTS `substances` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `synonym` varchar(100) NOT NULL,
  `mw` double unsigned NOT NULL,
  `formula` varchar(100) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- 导出表中的数据 `substances`
-- 

INSERT INTO `substances` (`id`, `name`, `synonym`, `mw`, `formula`, `state`) VALUES 
(1, 'NaCl', '氯化钠', 58.44, 'NaCl', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `supplies`
-- 

DROP TABLE IF EXISTS `supplies`;
CREATE TABLE IF NOT EXISTS `supplies` (
  `id` int(9) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `supply_cat_id` tinyint(3) unsigned NOT NULL,
  `date_create` date NOT NULL,
  `date_update` date NOT NULL,
  `created_by` mediumint(6) unsigned NOT NULL,
  `updated_by` mediumint(6) unsigned NOT NULL,
  `project` mediumint(6) unsigned NOT NULL,
  `note` text NOT NULL,
  `mask` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- 
-- 导出表中的数据 `supplies`
-- 

INSERT INTO `supplies` (`id`, `name`, `description`, `supply_cat_id`, `date_create`, `date_update`, `created_by`, `updated_by`, `project`, `note`, `mask`) VALUES 
(2, 'corning 3673', 'aaa', 2, '2008-01-20', '2008-08-27', 1, 1, 1, 'aaa', 0),
(3, 'greiner 788101', 'aaa', 2, '2008-01-20', '0000-00-00', 1, 0, 2, 'aaa', 0);

-- --------------------------------------------------------

-- 
-- 表的结构 `supply_cat`
-- 

DROP TABLE IF EXISTS `supply_cat`;
CREATE TABLE IF NOT EXISTS `supply_cat` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- 
-- 导出表中的数据 `supply_cat`
-- 

INSERT INTO `supply_cat` (`id`, `name`) VALUES 
(1, 'others'),
(2, 'microplates');

-- --------------------------------------------------------

-- 
-- 表的结构 `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` tinyint(3) NOT NULL auto_increment,
  `username` varchar(20) collate utf8_unicode_ci NOT NULL,
  `password` varchar(40) collate utf8_unicode_ci NOT NULL,
  `authority` tinyint(3) NOT NULL default '4',
  `people_id` mediumint(6) NOT NULL,
  `valid` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

-- 
-- 导出表中的数据 `users`
-- 

INSERT INTO `users` (`id`, `username`, `password`, `authority`, `people_id`, `valid`) VALUES 
(1, 'admin', 'e63a1de7b7faf933c7c9f8a5b8ec3d555acfa22b', 1, 1, 1),
(13, 'byqiu', '01eb9129b0093b9d011a14869d592745c69f81b9', 3, 2, 1),
(11, 'user', '12dea96fec20593566ab75692c9949596833adc9', 4, 1, 1),
(12, 'staff', '6ccb4b7c39a6e77f76ecfa935a855c6c46ad5611', 3, 1, 1);

-- --------------------------------------------------------

-- 
-- 表的结构 `vector_type_options`
-- 

DROP TABLE IF EXISTS `vector_type_options`;
CREATE TABLE IF NOT EXISTS `vector_type_options` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- 
-- 导出表中的数据 `vector_type_options`
-- 

INSERT INTO `vector_type_options` (`id`, `name`) VALUES 
(1, 'Adenoviral'),
(2, 'Retroviral'),
(3, 'Lentiviral'),
(4, 'RNAi'),
(5, 'Luciferase'),
(6, 'β-gal'),
(7, 'Yeast Expression'),
(8, 'Insect Expression'),
(9, 'Worm Expression'),
(10, 'Mammalian Expression'),
(11, 'Bacterial Expression'),
(12, 'CRE/LOX');
