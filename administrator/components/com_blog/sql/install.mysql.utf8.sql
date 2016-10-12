CREATE TABLE IF NOT EXISTS `#__blog` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`categories` INT(11)  NOT NULL ,
`mode` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`description` TEXT NOT NULL ,
`content` TEXT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`language` VARCHAR(5)  NOT NULL ,
`created_date` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

