<?php
/**
 *
 * Main payment model. Gets the payment types and handles the redirection form.
 * @author xxx
 */
class Gateway_Billdesk_Model_Billdesk extends Mage_Payment_Model_Method_Abstract 
{
  /**
   *	Magento configuration fields
   */
  protected $_code          = 'mbilldesk';
  protected $_formBlockType       = 'mbilldesk/form';

  protected $_isGateway         = false;
  protected $_canAuthorize      = false;
  protected $_canCapture        = true;
  protected $_canCapturePartial     = false;
  protected $_canRefund         = false;
  protected $_canVoid         = false;
  protected $_canUseInternal      = true;
  protected $_canUseCheckout      = true;
  protected $_canUseForMultishipping  = false;

  protected $_canSaveCc         = false;
  /**
   *
   * @return Gateway_Billdesk_Model_Config
   */
  public function getConfig() {
    return Mage::getSingleton('mbilldesk/config');
  }
  /**
   *
   * @return Gateway_Billdesk_Model_Session
   */
  public function getSession() {
    return Mage::getSingleton('mbilldesk/session');
  }
  public function getCheckout() {
    return Mage::getSingleton('checkout/session');
  }
  public function getQuote() {
    return $this->getCheckout()->getQuote();
  }

  /**
   *
   * Encode request as XML
   * @return SimpleXMLElement
   */
  protected function encodeToXml($request) {
    try {
      return new SimpleXMLElement($request);
    } catch(Exception $e) {  //Exception needed for the fresh installation
      return array();
    }
  }


  /**
   *
   * URL for the billdesk redirection form
   */
  public function getOrderPlaceRedirectUrl() {
    return Mage::getUrl('billdesk/notification/redirect');
  }

  /**
   *
   * Prepares the data for redirection form.
   * @see Gateway_Billdesk_Block_Redirect
   * @return array
   */
  public function getRedirectionFormData() 
  {
    $order_id = $this->getCheckout()->getLastRealOrderId();
    $order  = Mage::getModel('sales/order')->loadByIncrementId($order_id);
    $payment  = $order->getPayment()->getData();
    $billing  = $order->getBillingAddress();
    $redirectionFormData = array(
			"MerchantID" => $this->getConfig()->getMerchantId(),
                        "txtCustomerID" => $order_id,
                        "NotSpecified1" => "NA",
			"txtTxnAmount"  => (int)(round($order->getBaseGrandTotal(), 2)),
                        "NotSpecified2" => "NA",
                        "NotSpecified3" => "NA",
                        "NotSpecified4" => "NA",
			"CurrencyType"  => 'INR',
                        "NotSpecified5" => "NA",
                        "TypeField1" => "R",
                        "SecurityID" => $this->getConfig()->getSecurityId(),
                        "NotSpecified6" => "NA",
                        "NotSpecified7" => "NA",
                        "TypeField2" => "F",
                        "AdditionalInfo1" => $order->getCustomerId(),
			"AdditionalInfo2" => $billing->getFirstname().$billing->getLastname(),
                        "AdditionalInfo3" => $order->getCustomerEmail(),
                        "AdditionalInfo4" => substr(implode(" ",$billing->getStreet()), 0 ,100),
			"AdditionalInfo5" => $billing->getCity(),
			"AdditionalInfo6" => $billing->getPostcode(),
                        "AdditionalInfo7" => $billing->getTelephone(),
                         "RU" => Mage::getBaseUrl()."billdesk/response/report",
                         );    
    return (array)@$redirectionFormData;
  }
  public function checkIfPaymentIsEnabled($payment) 
  {
    $payments_enabled = explode(",", $this->getConfig()->getEnabledPaymentTypes());
    if (in_array($payment, $payments_enabled)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }  
  // end class
}
