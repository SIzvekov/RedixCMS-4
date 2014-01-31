CREATE TABLE IF NOT EXISTS `#__config` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_autofill` varchar(255) NOT NULL,
  `keywords` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  `close` enum('0','1') NOT NULL DEFAULT '0',
  `home_url` varchar(200) NOT NULL DEFAULT '',
  `gostat` enum('0','1') NOT NULL DEFAULT '0',
  `ipstoplist` text NOT NULL,
  `debug` enum('0','1') NOT NULL DEFAULT '0',
  `tpl` varchar(100) NOT NULL DEFAULT '',
  `adm_tpl` varchar(100) NOT NULL DEFAULT '',
  `use_trash` enum('0','1') NOT NULL DEFAULT '0',
  `use_param` enum('0','1') DEFAULT '0',
  `admin_par` varchar(10) NOT NULL DEFAULT '',
  `charset` varchar(15) NOT NULL DEFAULT '',
  `cemail` varchar(100) NOT NULL DEFAULT '',
  `use_pathway` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='configuration of this site' AUTO_INCREMENT=2 ;
INSERT INTO `#__config` (`id`, `title`, `title_autofill`, `keywords`, `description`, `close`, `home_url`, `gostat`, `ipstoplist`, `debug`, `tpl`, `adm_tpl`, `use_trash`, `use_param`, `admin_par`, `charset`, `cemail`) VALUES
(1, '', '', '', '', '0', 'index', '0', '', '0', 'default', 'default', '0', '0', 'ru', 'utf-8', '1');
CREATE TABLE IF NOT EXISTS `#__contacts` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `map_small` varchar(100) DEFAULT NULL,
  `map_big` varchar(100) NOT NULL DEFAULT '',
  `send_email_to` text NOT NULL,
  `send_email_tema` varchar(255) NOT NULL DEFAULT '',
  `meta_title` varchar(255) NOT NULL DEFAULT '',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__menulist` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `public` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__menupunkti` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `pid` smallint(6) DEFAULT '0',
  `mid` smallint(6) DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `sort` smallint(6) DEFAULT '0',
  `public` enum('0','1') DEFAULT '0',
  `page_id` int(6) DEFAULT '0',
  `target` enum('_self','_blank') NOT NULL DEFAULT '_self',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
CREATE TABLE IF NOT EXISTS `#__sitemap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `public` enum('0','1') NOT NULL DEFAULT '0',
  `sort` smallint(6) NOT NULL DEFAULT '0',
  `component` varchar(100) NOT NULL,
  `type` enum('page','content') NOT NULL DEFAULT 'page',
  `template` varchar(100) NOT NULL,
  `tplfile` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL DEFAULT '0',
  `multirecords` int(11) NOT NULL,
  `meta_title` varchar(255) NOT NULL DEFAULT '',
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `include_in_pathway` enum('0','1') DEFAULT '1',
  `pathway` varchar(50) NOT NULL DEFAULT '',
  `getsubpages` enum('0','1') NOT NULL DEFAULT '0',
  `getsubpages_deep` smallint(6) DEFAULT '1',
  `com_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
INSERT INTO `#__sitemap` (`id`, `pid`, `url`, `title`, `public`, `sort`, `component`, `type`, `template`, `tplfile`, `record_id`, `multirecords`, `meta_title`, `meta_keywords`, `meta_description`, `include_in_pathway`, `pathway`, `getsubpages`, `getsubpages_deep`, `com_id`) VALUES
(1, 0, 'index', 'Главная страница', '1', 1, 'default', 'page', 'text', 'text.php', 1, 0, '', '', '', '1', '', '0', 1, 2);
CREATE TABLE IF NOT EXISTS `#__textcontent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0;
INSERT INTO `#__textcontent` (`id`, `text`) VALUES (NULL, 'Welcome! Your website is based on RedixCMS v5.x');
CREATE TABLE IF NOT EXISTS `#__textmodulcontent` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `code` varchar(100) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `public` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__trash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wasid` int(11) NOT NULL DEFAULT '0',
  `aid` smallint(6) NOT NULL DEFAULT '0',
  `date` int(11) NOT NULL DEFAULT '0',
  `parttitle` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `table` varchar(100) NOT NULL DEFAULT '',
  `data` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
