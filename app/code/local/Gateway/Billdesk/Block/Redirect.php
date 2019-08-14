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
 * @package  BillDesk
 * @author  xxx
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * 
 * Redirection form block. Its content is send to Billdesk at the end of customer checkout 
 * @author xxx
 */
class Gateway_Billdesk_Block_Redirect extends Mage_Core_Block_Abstract 
{  
  protected function _toHtml() {
    $billdesk = Mage::getSingleton("mbilldesk/billdesk");
    $action = $billdesk->getConfig()->getPaymentURI();   
    $request = array();  
    foreach ($billdesk->getRedirectionFormData() as $field => $value) {            
      $request[] = $value;  
    }
//    Checksum Calculation
    $query_without_checksum = implode('|',$request);
    $common_string = $billdesk->getConfig()->getChecksumKey();
    $string_new = $query_without_checksum."|".$common_string;
    $checks = crc32($string_new);
    $checksum = sprintf("%u", $checks);     
    $msg = $query_without_checksum."|".$checksum; 
    $form .= '<form name="frmPay" action="' . $action . '" method="POST"> ';
    $form .= '<input type= "hidden" name= "msg" value= "'. $msg .'"/>';
    $form .= '<script language="JavaScript">document.frmPay.submit();</script></form>';
    $html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><style type="text/css">body{background-color:#efefef;font:18px Arial,sans-serif;color:#333;} .info{width:400px;height:200px;position:absolute;top:50%;left:50%;margin-top:-100px;margin-left:-200px;} </style></head><body>';
    $html .= '<div class="info"><p>';
    $html .= $this->__('You will be redirected to BillDesk in a few seconds.');
    $html .= '</p>';
    $html .= $form;
    $html .= '<img src=" ' . $this->getSkinUrl('images/billdesk/loader.gif') . '" alt="" /></div><script type="text/javascript">document.getElementById("mbilldesk_billdesk_checkout").submit();</script>';
    $html .= '</body></html>';    
    return $html;
  }
}
