CREATE TABLE IF NOT EXISTS `#__slideshow` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`image` VARCHAR(255)  NOT NULL ,
`m_image` VARCHAR(255)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`alt` VARCHAR(255)  NOT NULL ,
`description` TEXT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

