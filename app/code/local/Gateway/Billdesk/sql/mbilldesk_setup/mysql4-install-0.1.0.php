<?php

/**
 * BillDesk
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Payments
 * @package    Billdesk
 * @author     xxx
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;

$installer->startSetup();
//DROP TABLE IF EXISTS {$this->getTable('mbilldesk/billdesk')};
$installer->run("
CREATE TABLE {$this->getTable('mbilldesk/billdesk')} (
  `billdesk_id` int(11) unsigned NOT NULL auto_increment,
  `order_id` int(15) unsigned NOT NULL,
  `transaction_id` varchar(15) NOT NULL,
  `amount` int(15) unsigned NOT NULL,
  `txn_date_time` varchar(30) NOT NULL,
  `status_id` int(5) NOT NULL,
  `customer_id` int(15) NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  `status_message` varchar(50) NOT NULL,
  `http_referer` varchar(100) NOT NULL,
  `response_string` varchar(5000) NOT NULL,
  PRIMARY KEY (`billdesk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();
