<?php

/**
 * Billdesk
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
 * @package  Billdesk
 * @author  xxx
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * 
 * Prepares the payment types option list for dropdowns (frontend & backend)
 * @author xxx
 */
class Gateway_Billdesk_Model_Source_PaymentTypes 
{ 
  public function toOptionArray() {  
    $payments = Mage::getSingleton('mbilldesk/billdesk')->getEnabledPaymentTypes();
		$options = array();     
		foreach($payments as $payment) {
			$options[] = array(
				'value' => (string) $payment->type,
				'label' => (string) $payment->name
			);
		}		
		return $options;
  } 
  // end class
}
