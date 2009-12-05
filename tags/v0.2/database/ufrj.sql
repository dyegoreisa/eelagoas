CREATE TABLE `colheta_nivel` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `colheta_id` int(10) unsigned NOT NULL,
  `nivel_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  FOREIGN KEY (`colheta_id`) REFERENCES `colhetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`nivel_id`) REFERENCES `niveis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `colheta_parametros` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `colheta_id` int(10) unsigned NOT NULL,
  `parametro_id` int(10) unsigned NOT NULL,
  `valor` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  FOREIGN KEY (`colheta_id`) REFERENCES `colhetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`parametro_id`) REFERENCES `parametros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `colheta_parametros_perfil` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `colheta_id` int(10) unsigned NOT NULL,
  `parametro_id` int(10) unsigned NOT NULL,
  `nivel_id` int(10) unsigned NOT NULL,
  `valor` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  FOREIGN KEY (`colheta_id`) REFERENCES `colhetas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`parametro_id`) REFERENCES `parametros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `colhetas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `lagoa_id` int(10) unsigned NOT NULL,
  `ponto_id` int(10) unsigned NOT NULL,
  `profundidade_id` int(10) unsigned NOT NULL,
  `data` date default '9999-12-31',
  PRIMARY KEY  (`id`),
  FOREIGN KEY (`lagoa_id`) REFERENCES `lagoas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`ponto_id`) REFERENCES `pontos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`profundidade_id`) REFERENCES `profundidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `lagoas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `niveis` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nivel` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `parametros` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `parametros_perfil` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `pontos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nome` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `profundidades` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nome` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

