<?php

class ChillPay_Helper {
    private $params;
    private $hashValue;
    private $payment_setting_values;
    
	function __construct() {      
        $this->chillpay_settings   = new ChillPay_Setting;
    }

    function chillpay_get_merchant_route_checksum($parameter) {
        if(array_key_exists('MerchantCode',$parameter)) $this->params .= $parameter['MerchantCode'];

        if(array_key_exists('RouteNo',$parameter)) $this->params .= $parameter['RouteNo'];

        if(array_key_exists('Amount',$parameter)) $this->params .= $parameter['Amount'];

        if(array_key_exists('CurrencyCode',$parameter)) $this->params .= $parameter['CurrencyCode'];

        if(array_key_exists('ApiKey',$parameter)) $this->params .= $parameter['ApiKey'];

        if(array_key_exists('SecretKey',$parameter)) $this->params .= $parameter['SecretKey'];
    
        $checksum = md5($this->params);

        return $checksum;
    }
    
    function chillpay_get_payment_checksum($parameter) {

        if(array_key_exists('TransactionId',$parameter)) $this->params .= $parameter['TransactionId'];

        if(array_key_exists('Amount',$parameter)) $this->params .= $parameter['Amount'];

        if(array_key_exists('OrderNo',$parameter)) $this->params .= $parameter['OrderNo'];

        if(array_key_exists('CustomerId',$parameter)) $this->params .= $parameter['CustomerId'];

        if(array_key_exists('BankCode',$parameter)) $this->params .= $parameter['BankCode'];

        if(array_key_exists('PaymentDate',$parameter)) $this->params .= $parameter['PaymentDate'];

        if(array_key_exists('PaymentStatus',$parameter)) $this->params .= $parameter['PaymentStatus'];

        if(array_key_exists('BankRefCode',$parameter)) $this->params .= $parameter['BankRefCode'];

        if(array_key_exists('CurrentDate',$parameter)) $this->params .= $parameter['CurrentDate'];

        if(array_key_exists('CurrentTime',$parameter)) $this->params .= $parameter['CurrentTime'];

        if(array_key_exists('PaymentDescription',$parameter)) $this->params .= $parameter['PaymentDescription'];

        if(array_key_exists('CreditCardToken',$parameter)) $this->params .= $parameter['CreditCardToken'];

        if(array_key_exists('Currency',$parameter)) $this->params .= $parameter['Currency'];

        if(array_key_exists('CustomerName',$parameter)) $this->params .= $parameter['CustomerName'];

        $md5_key = esc_attr($this->chillpay_settings->md5_key());

        $this->params .= trim($md5_key);

        $checksum = md5($this->params);

        //error_log('chillpay_get_payment_checksum parameter:'.json_encode($parameter));
        //error_log('chillpay_get_payment_checksum this params :'.$this->params);

        return $checksum;
    }
}