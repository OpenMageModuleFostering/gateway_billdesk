<?php
/**
 * IndexController
 *
 * @author xxx
 */
class Gateway_Billdesk_NotificationController extends Mage_Core_Controller_Front_Action 
{
   /**
   *
   * Displayed on failure response from Billdesk
   */
  public function failureAction () 
  {
    $session = Mage::getSingleton('checkout/session');
    $this->loadLayout();
    $this->_initLayoutMessages('mbilldesk/session');    
    $this->renderLayout();
  }  
   /**
   *
   * Redirect customer to Billdesk
   */
  public function redirectAction() 
  {
    $session = Mage::getSingleton('checkout/session');
    $session->setBilldeskStandardQuoteId($session->getQuoteId());
    $this->getResponse()->setBody($this->getLayout()->createBlock('mbilldesk/redirect')->toHtml());
    $session->unsQuoteId();
  }
}

