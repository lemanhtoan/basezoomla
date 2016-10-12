CREATE TABLE IF NOT EXISTS `#__product` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`name` VARCHAR(255)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`image_hover` VARCHAR(255)  NOT NULL ,
`category_id` INT(11)  NOT NULL ,
`frame_width` VARCHAR(255)  NOT NULL ,
`frame_height` VARCHAR(255)  NOT NULL ,
`lens_width` VARCHAR(255)  NOT NULL ,
`temple_arms` VARCHAR(255)  NOT NULL ,
`bridge` VARCHAR(255)  NOT NULL ,
`colours` TEXT NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`language` VARCHAR(5)  NOT NULL ,
`updated_date` DATETIME NOT NULL ,
`created_date` DATETIME NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__product_colours` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`name` VARCHAR(255)  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`language` VARCHAR(5)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;