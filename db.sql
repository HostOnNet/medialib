CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_type` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `log_mid` int(11) NOT NULL,
  `log_time` int(11) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `media_tag_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `time_start` varchar(13) COLLATE utf8_general_ci NOT NULL,
  `likes` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `medias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `time_start_hms` varchar(9) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `time_end_hms` varchar(9) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `view_time` int(11) NOT NULL,
  `view_again` int(11) NOT NULL DEFAULT '0',
  `view_again_days` tinyint(2) NOT NULL,
  `views` int(11) NOT NULL,
  `volume` int(3) NOT NULL DEFAULT '150',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `description_2` (`description`),
  FULLTEXT KEY `description_3` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `playlist_media` (
  `pm_id` int(11) NOT NULL AUTO_INCREMENT,
  `pm_media_id` int(4) NOT NULL,
  `pm_playlist_id` int(4) NOT NULL,
  `pm_time_start` varchar(20) NOT NULL,
  PRIMARY KEY (`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `playlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_id` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tag_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `tag_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
