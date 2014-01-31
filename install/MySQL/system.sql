CREATE TABLE IF NOT EXISTS `#s_cmsextrahosts` (
  `id` smallint(6) NOT NULL auto_increment,
  `hostid` smallint(6) NOT NULL default '0',
  `host` varchar(255) NOT NULL default '',
  `licens` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#s_cmshosts` (
  `id` smallint(6) NOT NULL auto_increment,
  `host` varchar(255) NOT NULL default '',
  `db_prefix` varchar(50) NOT NULL default '',
  `licens` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='table with hosts';
CREATE TABLE IF NOT EXISTS `#s_params` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `hostid` smallint(6) NOT NULL DEFAULT '0',
  `par` varchar(30) NOT NULL DEFAULT '',
  `par_name` varchar(150) NOT NULL DEFAULT '',
  `db_prefix` varchar(50) NOT NULL DEFAULT '',
  `default` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 COMMENT='parametrs for url';