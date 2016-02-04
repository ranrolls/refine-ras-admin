ALTER TABLE `#__tagmeta_rules` ADD `placeholders` text NOT NULL AFTER `last_rule`;
ALTER TABLE `#__tagmeta_rules` ADD `rights` varchar(255) DEFAULT NULL AFTER `keywords`;
ALTER TABLE `#__tagmeta_rules` ADD `xreference` varchar(255) DEFAULT NULL AFTER `rights`;
ALTER TABLE `#__tagmeta_rules` ADD `preserve_title` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=No,1=Yes' AFTER `synonweight`;