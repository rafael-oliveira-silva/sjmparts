<?php
/**
* Trezo
*
* NOTICE OF LICENSE
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.trezo.com.br for more information.
*
* @category Trezo
* @package Trezo_Itaushopline
*
* @copyright Copyright (c) 2017 Trezo. (http://www.trezo.com.br)
*
* @author Trezo Core Team <contato@trezo.com.br>
*/

$installer = $this;
$installer->startSetup();
$sqlBlock = <<<SQLBLOCK
CREATE TABLE {$this->getTable ('trezo_itaushopline_transactions')} (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    order_id int(11) unsigned NOT NULL,
    amount float unsigned NOT NULL,
    number char(11) NOT NULL,
    expiration date NOT NULL,
    submit_dc text NOT NULL,
    query_dc text NOT NULL,
    PRIMARY KEY (id),
    KEY order_id (order_id),
    KEY amount (amount),
    KEY number (number),
    KEY expiration (expiration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
SQLBLOCK;
$installer->run($sqlBlock);

$installer->endSetup();
