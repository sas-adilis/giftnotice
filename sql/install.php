<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2015 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gift_notice` (
	`id_gift_notice` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`internal_name` varchar(255) NOT NULL,
	`id_shop` int(11) unsigned NOT NULL DEFAULT "1",
	`amount_from` decimal(10.6) unsigned NOT NULL DEFAULT "0",
	`amount_to` decimal(10.6) unsigned NOT NULL DEFAULT "0",
	`date_add` datetime NOT NULL,
	`date_upd` datetime NOT NULL, 
	`note` text,
	PRIMARY KEY (`id_gift_notice`),
	KEY `id_shop` (`id_shop`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'gift_notice_lang` (
    `id_gift_notice` int(10) unsigned NOT NULL,
    `id_lang` int(10) unsigned NOT NULL,
    `message` varchar(512) NOT NULL DEFAULT \'\',
    PRIMARY KEY (`id_gift_notice`,`id_lang`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}