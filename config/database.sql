-- http://ondras.zarovi.cz/sql/demo/?keyword=claroline-stats

DROP TABLE IF EXISTS `stats`;

CREATE TABLE `stats` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `ip` VARCHAR(30) NOT NULL,
  `url` VARCHAR(256) NOT NULL,
  `lang` VARCHAR(6) NOT NULL,
  `country` VARCHAR(256) NOT NULL,
  `email` VARCHAR(256) NOT NULL,
  `version` VARCHAR(30) NOT NULL,
  `workspaces` INTEGER NOT NULL,
  `users` INTEGER NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
);
