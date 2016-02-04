CREATE TABLE IF NOT EXISTS `#__fandbstartup_fb` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`title` VARCHAR(255)  NOT NULL ,
`filetype` VARCHAR(255)  NOT NULL ,
`description` VARCHAR(255)  NOT NULL ,
`created_date` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

