<?php
/**
 *
 * Billdesk main controller. Receives server responses
 * @author xxx
 *
 */
class Gateway_Billdesk_ResponseController extends Mage_Core_Controller_Front_Action 
{
  /**
   *
   * PositiveUrl, just to show an information to customer about success. Also log the entry
   */
  public function successAction() 
  {
    Mage::helper('mbilldesk/log')->log('== successRespAction >>>');
    
    if ($order_id = $this->getRequest()->getParam('session_id')) {
      Mage::helper('mbilldesk/log')->log($order_id);
    }
    Mage::helper('mbilldesk/log')->log('<<<<s successRespAction');
    $this->_redirect('checkout/onepage/success');
  }
  /**
   *
   * NegativeUrl, just to show an information to customer about failure. Also log the entry
   */
  public function failureAction() 
  {
    Mage::helper('mbilldesk/log')->log('== failureRespAction >>>');
    if ($order_id = $this->getRequest()->getParam('session_id')) {
      Mage::helper('mbilldesk/log')->log($order_id);
    }
    Mage::helper('mbilldesk/log')->log('<<<<f failureRespAction');
    $this->_redirect('billdesk/notification/failure');
  }
  /**
   *
   * And last, but not least - most importaint OnlineUrl providing
   * information about order status change
   */
  public function reportAction() 
  {
      $response = $this->getRequest()->getPost();
      $response_array = array();
      $response_array = explode('|',$response['msg']);
      $order_id = $response_array[1];     
      $check_array = $response_array;           //copy of response arry to do checksum calculation
      $check_array[25] = $this->getConfig()->getChecksumKey();  //add Checksum key at last node for calculation
      $query_without_checksum = implode('|',$check_array);
      $checks = crc32($query_without_checksum);
      $checker = sprintf("%u", $checks);         //CHECKSUM KEY FOR RESPONSE
      
      $http_reffer = $_SERVER['HTTP_ORIGIN'];
      $response_string = implode('|',$response_array);       
      $order  = Mage::getModel('sales/order')->loadByIncrementId($order_id);                        
      $connection = Mage::getSingleton('core/resource')->getConnection('core_write');  
      $sql = "INSERT INTO `mbilldesk` (`order_id`,`transaction_id`,`amount`,`txn_date_time`,`status_id`,`customer_id`,`customer_name`,`customer_email`,`status_message`,`http_referer`,`response_string`) VALUES ('" . $response_array[1] . "', '" . $response_array[2] . "', '" . $response_array[4] . "', '" . $response_array[13] . "', '" . $response_array[14] . "', '" . $response_array[16] . "', '" . $response_array[17] . "', '" . $response_array[18] . "', '" . $response_array[24] . "','". $http_reffer ."','". $response_string ."')"; 
      $connection->query($sql);               
      if(($response_array[14] == 300) && ($checker == $response_array[25])){
          $statusMessage = 'Payment Recieved from Billdesk';
          $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, $statusMessage);        
          $order->save();          
//        Trigger Order Confirmation E-mail
          try
            {
            $order->sendNewOrderEmail();
            } catch (Exception $ex) {  }
            $this->_redirect('checkout/onepage/success');
      }   
      else{
          Mage::getSingleton('core/session')->setError($response_array[24]);   
          $this->_redirect('checkout/onepage/failure');        
      } 
  }
  /**
   *
   * Say 'OK' to your Billdesk friend. Its required by the server.
   * Billdesk will continue to send reports, until it will get the "OK" confirmation.
   */
  protected function confirm($response) {
    $response->setBody($this->getLayout()->createBlock('mbilldesk/report')->toHtml());
  }
    /**
   *
   * @return Gateway_Billdesk_Model_Config
   */
  public function getConfig() 
  {
    return Mage::getSingleton('mbilldesk/config');
  }
}
