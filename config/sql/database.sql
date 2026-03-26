CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(32) collate utf8_swedish_ci default NULL,
  `apellido_p` varchar(32) collate utf8_swedish_ci default NULL,
  `apellido_m` varchar(32) collate utf8_swedish_ci default NULL,
  `usern` varchar(32) collate utf8_swedish_ci default NULL,
  `pssword` varchar(40) collate utf8_swedish_ci default NULL,
  `activo` int(11) default NULL,
  `email` varchar(100) collate utf8_swedish_ci default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `admin` int(10) default NULL,
  `id_rol` int(10)  default NULL,
  `tutor` int ,
  `status_revision` int,
  `status` int,
  `sexo` int,
  `matricula` varchar(200),
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;



CREATE TABLE IF NOT EXISTS `rols` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(32) collate utf8_swedish_ci default NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  `permisos` text  default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

CREATE TABLE IF NOT EXISTS `escuelas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_maps` varchar(40),
  `cct` varchar(80),
  `id_distribuidor` int,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


CREATE TABLE IF NOT EXISTS `territorios` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_distribuidor` int,
  `color` varchar(10),
  `coords` text,  
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;


insert into usuarios set usern='admin', pssword='d033e22ae348aeb5660fc2140aec35850c4da997',admin=1, activo=1;

