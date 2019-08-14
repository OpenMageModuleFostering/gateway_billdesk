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
 * @package    Billdesk
 * @author     xxx
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 *
 * Configuration handling class. Extracts the proper values from the global config
 * @author xxx
 *
 */
class Gateway_Billdesk_Model_Config extends Varien_Object 
{
  /**
   *
   * Merchant Id in Billdesk database
   */
  public function getMerchantId() {
    return $this->getConfigData("merchant_id");
  }
  /**
   * 
   * Billdesk Security ID
   */
  public function getSecurityId() {
    return $this->getConfigData("security_id");
  }
  /**
   * 
   * Hash key used for signing our own requests
   */
  public function getChecksumKey() {
    return $this->getConfigData("checksum_key");
  }
  /**
   * 
   * Paygate URL 
   */
  public function getCgiUrl() {
    return $this->getConfigData("cgi_url");
  }

  /**
   * 
   * URL for creation of new payment (UTF)
   */
  public function getPaymentURI(){
    return $this->getCgiUrl();
  }
  /**
   * 
   * Payment status URL (for asking the Billdesk server) 
   */
  public function getReportingURI(){
        return null;
  }

  /**
   * 
   * Translates error codes into error messages 
   * @param int $code
   */
  public function getErrorMessagesWithCode($code) {
    $error['205'] = Mage::helper('mbilldesk')->__("Transaction amount is lower than minimum amount.");
    $error['206'] = Mage::helper('mbilldesk')->__("Transaction amount is higher than maximum amount.");
    $error['207'] = Mage::helper('mbilldesk')->__("You have reached transactions limit at this time.");
    $error['501'] = Mage::helper('mbilldesk')->__("Authorization failed for this transaction.");
    $error['502'] = Mage::helper('mbilldesk')->__("Transaction was started before.");
    $error['503'] = Mage::helper('mbilldesk')->__("Transaction is already authorized.");
    $error['504'] = Mage::helper('mbilldesk')->__("Transaction was cancelled before.");
    $error['505'] = Mage::helper('mbilldesk')->__("Transaction authorization request was sent before.");

    if (array_key_exists($code, $error)) {
      return $error[$code];
    } else {
      return Mage::helper('mbilldesk')->__("Transaction Error Occurred, please contact Customer Service.");
    }
  }

  /**
   * 
   * Provides status info based on status code
   * @param int $code
   */
  public function getStatusesWithCode($code) {
    $status['1']   = Mage::helper('mbilldesk')->__("New");
    $status['2']   = Mage::helper('mbilldesk')->__("Cancelled");
    $status['3']   = Mage::helper('mbilldesk')->__("Rejected");
    $status['4']   = Mage::helper('mbilldesk')->__("Started");
    $status['5']   = Mage::helper('mbilldesk')->__("Waiting For Payment");
    $status['7']   = Mage::helper('mbilldesk')->__("Rejected");
    $status['99']  = Mage::helper('mbilldesk')->__("Payment Received");
    $status['888'] = Mage::helper('mbilldesk')->__("Wrong Status");

    if (array_key_exists($code, $status)) {
      return $status[$code];
    } else {
      return Mage::helper('mbilldesk')->__("Wrong Status");
    }
  }
  
  /**
   * 
   * Gets the log file name for Billdesk. If not specified - "mbilldesk.log"
   */
  public function getLogFileName() {
    return $this->getConfigData("log_file_name", "mbilldesk.log");
  }

  /**
   * 
   * Get the global config value for a specified key  
   * @param $key
   * @param $default value if key not exists
   */
  public function getConfigData($key, $default = false) {
    if ( ! $this->hasData($key)) {
      $value = Mage::getStoreConfig('payment/mbilldesk/' . $key);
      if (is_null($value) || false === $value) {
        $value = $default;
      }
      $this->setData($key, $value);
    }
    return $this->getData($key);
  }  
  // end class
}
