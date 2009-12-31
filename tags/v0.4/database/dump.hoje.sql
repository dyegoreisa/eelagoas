CREATE TABLE `projeto` (
  `id_projeto` int(11) NOT NULL auto_increment,
  `nome` varchar(200) NOT NULL,
  PRIMARY KEY  (`id_projeto`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL auto_increment,
  `login` varchar(8) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) default NULL,
  `lastlogin` datetime default NULL,
  `lastupdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `parametro_extra` (
  `id_parametro_extra` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `tem_valor` tinyint(1) default '0',
  `tem_relacao` tinyint(1) default '0',
  `tabela` varchar(200) default NULL,
  PRIMARY KEY  (`id_parametro_extra`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `categoria_extra` (
  `id_categoria_extra` int(11) NOT NULL auto_increment,
  `nome` varchar(200) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `tem_valor` tinyint(1) default '0',
  `tem_relacao` tinyint(1) default '0',
  `tabela` varchar(200) default NULL,
  PRIMARY KEY  (`id_categoria_extra`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `parametro` (
  `id_parametro` int(11) NOT NULL auto_increment,
  `id_parametro_extra` int(11) NOT NULL default '1',
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_parametro`),
  FOREIGN KEY (`id_parametro_extra`) REFERENCES `parametro_extra` (`id_parametro_extra`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL auto_increment,
  `id_categoria_extra` int(11) NOT NULL, 
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_categoria`),
  FOREIGN KEY (`id_categoria_extra`) REFERENCES `categoria_extra` (`id_categoria_extra`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `especie` (
  `id_especie` int(11) NOT NULL auto_increment,
  `id_parametro` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  PRIMARY KEY  (`id_especie`),
  FOREIGN KEY (`id_parametro`) REFERENCES `parametro` (`id_parametro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `lagoa` (
  `id_lagoa` int(11) NOT NULL auto_increment,
  `id_projeto` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_lagoa`),
  FOREIGN KEY (`id_projeto`) REFERENCES `projeto` (`id_projeto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `ponto_amostral` (
  `id_ponto_amostral` int(11) NOT NULL auto_increment,
  `id_lagoa` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY  (`id_ponto_amostral`),
  FOREIGN KEY (`id_lagoa`) REFERENCES `lagoa` (`id_lagoa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `coleta` (
  `id_coleta` int(11) NOT NULL auto_increment,
  `id_lagoa` int(11) NOT NULL,
  `id_ponto_amostral` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `data` datetime NOT NULL,
  `tipo_periodo` enum('mensal','diario') NOT NULL default 'mensal',
  PRIMARY KEY (`id_coleta`),
  FOREIGN KEY (`id_lagoa`) REFERENCES `lagoa` (`id_lagoa`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`id_ponto_amostral`) REFERENCES `ponto_amostral` (`id_ponto_amostral`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `coleta_parametro` (
  `id_coleta_parametro` int(11) NOT NULL auto_increment,
  `id_coleta` int(11) NOT NULL,
  `id_parametro` int(11) NOT NULL,
  `valor` float default NULL,
  `extra` varchar(200) default NULL,
  PRIMARY KEY  (`id_coleta_parametro`),
  FOREIGN KEY (`id_coleta`) REFERENCES `coleta` (`id_coleta`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`id_parametro`) REFERENCES `parametro` (`id_parametro`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE `coleta_parametro_especie` (
  `id_coleta_parametro_especie` int(11) NOT NULL auto_increment,
  `id_coleta_parametro` int(11) NOT NULL,
  `id_especie` int(11) NOT NULL,
  PRIMARY KEY  (`id_coleta_parametro_especie`),
  FOREIGN KEY (`id_coleta_parametro`) REFERENCES `coleta_parametro` (`id_coleta_parametro`) ON UPDATE CASCADE,
  FOREIGN KEY (`id_especie`) REFERENCES `especie` (`id_especie`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

