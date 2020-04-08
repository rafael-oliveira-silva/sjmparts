<?php
$installer = $this;
$installer->startSetup();
$sql = '
	CREATE TABLE `'.Mage::getConfig()->getTablePrefix().'banner_creator` (
		`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		`title` varchar(500) DEFAULT NULL,
		`row_1_text` text,
		`row_1_font_size` int(10) unsigned DEFAULT \'18\',
		`row_1_color` varchar(12) DEFAULT \'#000\',
		`row_1_image_filepath` varchar(500) DEFAULT NULL,
		`row_1_image_position` enum(\'left\',\'right\',\'center\') DEFAULT \'center\',
		`row_2_text` text,
		`row_2_font_size` int(10) unsigned DEFAULT \'18\',
		`row_2_color` varchar(12) DEFAULT \'#000\',
		`row_2_image_filepath` varchar(500) DEFAULT NULL,
		`row_2_image_position` enum(\'left\',\'right\',\'center\') DEFAULT \'center\',
		`row_3_text` text,
		`row_3_font_size` int(10) unsigned DEFAULT \'18\',
		`row_3_color` varchar(12) DEFAULT \'#000\',
		`row_3_image_filepath` varchar(500) DEFAULT NULL,
		`row_3_image_position` enum(\'left\',\'right\',\'center\') DEFAULT \'center\',
		`image_filepath` varchar(500) DEFAULT NULL,
		`image_position` enum(\'left\', \'right\') DEFAULT \'left\',
		`url` varchar(600) DEFAULT NULL,
		`sort_order` int(11) unsigned DEFAULT \'0\',
		`status` tinyint(1) unsigned DEFAULT \'1\',
		`created_at` timestamp NULL DEFAULT NULL,
		`updated_at` timestamp NULL DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB;
';

$installer->run($sql);

$installer->endSetup();
	 