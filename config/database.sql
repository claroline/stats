-- http://ondras.zarovi.cz/sql/demo/?keyword=claroline-stats

DROP TABLE IF EXISTS `stats`;

CREATE TABLE IF NOT EXISTS `stats` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `ip` VARCHAR(30) NOT NULL,
  `platform_name` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `lang` VARCHAR(6) NOT NULL,
  `country` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `version` VARCHAR(30) NOT NULL,
  `workspaces` INTEGER NOT NULL,
  `personal_workspaces` INTEGER NOT NULL,
  `users` INTEGER NOT NULL,
  `stats_type` INTEGER NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

DROP TABLE IF EXISTS `stats_platform`;

CREATE TABLE IF NOT EXISTS `stats_platform` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `ip` VARCHAR(30) NOT NULL,
  `platform_name` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `lang` VARCHAR(6) NOT NULL,
  `country` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `version` VARCHAR(30) NOT NULL,
  `workspaces` INTEGER NOT NULL,
  `personal_workspaces` INTEGER NOT NULL,
  `users` INTEGER NOT NULL,
  `stats_type` INTEGER NOT NULL,
  `token` VARCHAR(255),
  `active` TINYINT(1) NOT NULL,
  `date` DATETIME NOT NULL,
  `is_prod` TINYINT(1) DEFAULT '1' NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

ALTER TABLE `stats_platform` ADD UNIQUE (`url`);

-- TO UPDATE DATABASE

ALTER TABLE `stats_platform` ADD `is_prod` TINYINT(1) DEFAULT '1' NOT NULL;