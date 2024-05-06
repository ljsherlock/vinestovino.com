<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

if ( class_exists( 'ChillPay_Fee' ) ) {
	return;
}

class ChillPay_Fee {
    public $check = 0;

    /**
	 * @since 1.0
	 */
	public function __construct() {
        $this->chillpay_settings = new ChillPay_Setting;
    }

	public static function get_payment_fee($currency, $amount) {

        global $check;
        $check = 1;

        $settings = new ChillPay_Setting();

        $update_date = $settings->get_update_date();
        $now = date('Y/m/d');
        if(empty($update_date) || strcmp($update_date,$now) !== 0)
        {
			$settings->APIFee_V2();
            $settings->update_fee();
            error_log('update fee [checkout]');
        }

        global $data_internetbanking;      
        $data_internetbanking['fee_scb'] = -1;
        $data_internetbanking['fee_ktb'] = -1;
        $data_internetbanking['fee_bay'] = -1;
        $data_internetbanking['fee_bbl'] = -1;
        $data_internetbanking['fee_ttb'] = -1;

        global $data_mobilebanking;
        $data_mobilebanking['fee_kplus'] = -1;
        $data_mobilebanking['fee_scb_easy'] = -1;
        $data_mobilebanking['fee_kma'] = -1;
        $data_mobilebanking['fee_bbl_mbanking'] = -1;
        $data_mobilebanking['fee_krungthai_next'] = -1;

        global $data_creditcard;
        $data_creditcard['fee_creditcard'] = -1;

        global $data_ewallet;
        $data_ewallet['fee_linepay'] = -1;
        $data_ewallet['fee_truemoney'] = -1;
        $data_ewallet['fee_alipay'] = -1;
        $data_ewallet['fee_wechatpay'] = -1;
        $data_ewallet['fee_shopeepay'] = -1;

        global $data_billpayment;
        $data_billpayment['fee_bigc'] = -1;
        $data_billpayment['fee_cenpay'] = -1;
        $data_billpayment['fee_counter_bill_payment'] = -1;

        global $data_qrcode;
        $data_qrcode['fee_qrcode'] = -1;

        global $data_kiosk_machine;
        $data_kiosk_machine['fee_boonterm'] = -1;

        global $data_installment;
        $data_installment['min_amount_installment_kbank'] = -1;
        $data_installment['max_amount_installment_kbank'] = -1;
        $data_installment['has_merchant_route_installment_kbank'] = 0; 
        $data_installment['has_merchant_fee_installment_kbank'] = 0; 
        $data_installment['has_merchant_service_fee_installment_kbank'] = 0;
        $data_installment['installment_kbank'] = -1; 

        $data_installment['min_amount_installment_ktc_flexi'] = -1;
        $data_installment['max_amount_installment_ktc_flexi'] = -1;
        $data_installment['has_merchant_route_installment_ktc_flexi'] = 0; 
        $data_installment['has_merchant_fee_installment_ktc_flexi'] = 0; 
        $data_installment['has_merchant_service_fee_installment_ktc_flexi'] = 0; 
        $data_installment['installment_ktc_flexi'] = -1;

        $data_installment['min_amount_installment_scb'] = -1;
        $data_installment['max_amount_installment_scb'] = -1;
        $data_installment['has_merchant_route_installment_scb'] = 0; 
        $data_installment['has_merchant_fee_installment_scb'] = 0; 
        $data_installment['has_merchant_service_fee_installment_scb'] = 0; 
        $data_installment['installment_scb'] = -1;

        $data_installment['min_amount_installment_krungsri'] = -1;
        $data_installment['max_amount_installment_krungsri'] = -1;
        $data_installment['has_merchant_route_installment_krungsri'] = 0; 
        $data_installment['has_merchant_fee_installment_krungsri'] = 0; 
        $data_installment['has_merchant_service_fee_installment_krungsri'] = 0; 
        $data_installment['card_type_installment_krungsri'] = ''; 
        $data_installment['installment_krungsri'] = -1;

        $data_installment['min_amount_installment_firstchoice'] = -1;
        $data_installment['max_amount_installment_firstchoice'] = -1;
        $data_installment['has_merchant_route_installment_firstchoice'] = 0; 
        $data_installment['has_merchant_fee_installment_firstchoice'] = 0; 
        $data_installment['has_merchant_service_fee_installment_firstchoice'] = 0; 
        $data_installment['card_type_installment_firstchoice'] = '';
        $data_installment['installment_firstchoice'] = -1;

        $data_installment['installment_tbank'] = -1; 

        global $data_pay_with_points;
        $data_pay_with_points['fee_point_ktc_forever'] = -1;
        
        $internetbanking_fees = $settings->get_internetbanking_fees();
        $mobilebanking_fees = $settings->get_mobilebanking_fees();
        $creditcard_fees = $settings->get_creditcard_fees();
        $ewallet_fees = $settings->get_ewallet_fees();
        $qrcode_fees = $settings->get_qrcode_fees();
        $billpayment_fees = $settings->get_billpayment_fees();
        $kiosk_machine_fees = $settings->get_kiosk_machine_fees();
        $installment_fees = $settings->get_installment_fees();
        $pay_with_points_fees = $settings->get_pay_with_points_fees();

        $installment_settings = get_option('woocommerce_chillpay_installment_settings');
        $absorb_by_installment_kbank = 'Customer';
        $absorb_by_installment_ktc_flexi = 'Customer';
        $absorb_by_installment_scb = 'Customer';
        $absorb_by_installment_krungsri = 'Customer';
        $absorb_by_installment_firstchoice = 'Customer';
      
        if($installment_settings != null)
        {
            if(isset($installment_settings['absorb_by_installment_kbank']))
            {
                if (strcmp($installment_settings['absorb_by_installment_kbank'],'01') === 0) $absorb_by_installment_kbank = 'Merchant';
            }
            if(isset($installment_settings['absorb_by_installment_ktc_flexi']))
            {
                if (strcmp($installment_settings['absorb_by_installment_ktc_flexi'],'01') === 0) $absorb_by_installment_ktc_flexi = 'Merchant';   
            }
            if(isset($installment_settings['absorb_by_installment_scb']))
            {
                if (strcmp($installment_settings['absorb_by_installment_scb'],'01') === 0) $absorb_by_installment_scb = 'Merchant';   
            }
            if(isset($installment_settings['absorb_by_installment_krungsri']))
            {
                if (strcmp($installment_settings['absorb_by_installment_krungsri'],'01') === 0) $absorb_by_installment_krungsri = 'Merchant';
            }
            if(isset($installment_settings['absorb_by_installment_firstchoice']))
            {
                if (strcmp($installment_settings['absorb_by_installment_firstchoice'],'01') === 0) $absorb_by_installment_firstchoice = 'Merchant';     
            }
        }
               
        foreach ($internetbanking_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {
                $fee = self::calculate_fee($amount, $currency, $value);

                if (strcmp($channel,'internetbank_scb') === 0) $data_internetbanking['fee_scb'] = $fee;
                elseif (strcmp($channel,'internetbank_ktb') === 0) $data_internetbanking['fee_ktb'] = $fee;
                elseif (strcmp($channel,'internetbank_bay') === 0) $data_internetbanking['fee_bay'] = $fee;
                elseif (strcmp($channel,'internetbank_bbl') === 0) $data_internetbanking['fee_bbl'] = $fee;
                elseif (strcmp($channel,'internetbank_ttb') === 0) $data_internetbanking['fee_ttb'] = $fee;
            }
        }

        foreach ($mobilebanking_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {
                $fee = self::calculate_fee($amount, $currency, $value);

                if (strcmp($channel,'payplus_kbank') === 0) $data_mobilebanking['fee_kplus'] = $fee;
                elseif (strcmp($channel,'mobilebank_scb') === 0) $data_mobilebanking['fee_scb_easy'] = $fee;
                elseif (strcmp($channel,'mobilebank_bay') === 0) $data_mobilebanking['fee_kma'] = $fee;
                elseif (strcmp($channel,'mobilebank_bbl') === 0) $data_mobilebanking['fee_bbl_mbanking'] = $fee;
                elseif (strcmp($channel,'mobilebank_ktb') === 0) $data_mobilebanking['fee_krungthai_next'] = $fee;
            }
        }

        foreach ($creditcard_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {
                
                $array_card_type = array();
                foreach ($value as $data )
                {
                    if(strcmp($currency,$data['currncy_name']) === 0)
                    {           
                        if ($array_card_type == array()) {
                            $array_card_type = array($data['card_type']);
                        } else {
                            array_push($array_card_type, $data['card_type']);
                        }
                    }                
                }

                $card_type = '';
                if (in_array('unionpay', $array_card_type)) $card_type = 'unionpay';
                
                $fee = self::calculate_fee($amount, $currency, $value);
                if (strpos($channel,'creditcard') === 0) {
                    $data_creditcard['fee_creditcard'] = $fee;
                    $data_creditcard['card_type_creditcard'] = $card_type;
                }
            }
        }

        foreach ($ewallet_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {
                $fee = self::calculate_fee($amount, $currency, $value);

                if (strcmp($channel,'epayment_linepay') === 0) $data_ewallet['fee_linepay'] = $fee;
                elseif (strcmp($channel,'epayment_truemoney') === 0) $data_ewallet['fee_truemoney'] = $fee;
                elseif (strcmp($channel,'epayment_alipay') === 0) $data_ewallet['fee_alipay'] = $fee;
                elseif (strcmp($channel,'epayment_wechatpay') === 0) $data_ewallet['fee_wechatpay'] = $fee;
                elseif (strcmp($channel,'epayment_shopeepay') === 0) $data_ewallet['fee_shopeepay'] = $fee;
            }
        }

        foreach ($billpayment_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {
                $fee = self::calculate_fee($amount, $currency, $value);

                if (strcmp($channel,'billpayment_bigc') === 0) $data_billpayment['fee_bigc'] = $fee;
                elseif (strcmp($channel,'billpayment_cenpay') === 0) $data_billpayment['fee_cenpay'] = $fee;
                elseif (strcmp($channel,'billpayment_counter') === 0) $data_billpayment['fee_counter_bill_payment'] = $fee;
            }
        }

        foreach ($qrcode_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {
                $fee = self::calculate_fee($amount, $currency, $value);

                if (strcmp($channel,'bank_qrcode') === 0) $data_qrcode['fee_qrcode'] = $fee;
            }
        }

        foreach ($kiosk_machine_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {
                $bill_amount = ceil($amount);
                $fee = self::calculate_fee($bill_amount, $currency, $value);

                if (strcmp($channel,'billpayment_boonterm') === 0) $data_kiosk_machine['fee_boonterm'] = ceil($fee);
            }
        }

        foreach ($installment_fees as $channel => $value) {
            $count_value = count($value);
            $installment_kbank = array();
            $installment_ktc_flexi = array();
            $installment_scb = array();
            $installment_krungsri = array();
            $installment_firstchoice = array();
            $installment_tbank = array();
            $tbank = array();
        
            if($count_value > 0)
            {
                foreach ($value as $data )
                {
                    $installments = $data['installments'];         
                    $fee = self::calculate_fee($amount, $currency, $value);                   
                    if (strcmp($channel,'installment_kbank') === 0)
                    {    
                        $data_installment['has_merchant_route_installment_kbank'] = 1;
                        $data_installment['has_merchant_fee_installment_kbank'] = 1;
                        $payment_min_price = 0;
                        $installment_min_amount = 0;
                        $display_min = 0; 
                        $i = 0;                   

                        $payment_min = $data['payment_min_price'];
                                                
                        if($payment_min_price == 0 || $payment_min_price > $payment_min){
                            $payment_min_price = $payment_min;
                        }

                        foreach ($installments as $installment )
                        {
                            $plans = array();
                            if (strcmp($installment['AbsorbBy'],$absorb_by_installment_kbank) === 0)
                            {
                                $data_installment['has_merchant_service_fee_installment_kbank'] = 1;
                                $sum_installment_min_amount = $installment['InstallmentMinAmount'] * $installment['Terms'];

                                if($installment_min_amount == 0 || $installment_min_amount > $sum_installment_min_amount){
                                    $installment_min_amount = $sum_installment_min_amount;
                                }
    
                                if($amount >= $data['payment_min_price'] && $amount <= $data['payment_max_price'] && $amount >= $sum_installment_min_amount){
    
                                    $interest_rate = $installment['InterestRate'];
    
                                    if(strcmp($installment['AbsorbBy'],"Customer") === 0){
                                        $interest_rate = 0.65;
                                    }
    
                                    $interest = $amount * $interest_rate * $installment['Terms'] / 100;
                                    $monthly_amount = round( ( $amount + $interest ) / $installment['Terms'], 2 );
    
                                    $plans[] = array(
                                        'term_length'    => $installment['Terms'],
                                        'monthly_amount' => $monthly_amount,
                                        'interest_rate'  => $interest_rate,
                                    );
    
                                }
    
                                if($plans != null)
                                {

                                    if(
                                        $installment_kbank == array())
                                        { $installment_kbank = array($plans);}
                                    else{ array_push($installment_kbank,$plans);}                             
                            
                                }
                            }                        
                                                                                                    
                        }   

                        $result = array_map("unserialize", array_unique(array_map("serialize", $installment_kbank)));
                        $data_installment['installment_kbank'] = $result;
                        $data_installment['min_amount_installment_kbank'] = $payment_min_price; 
                        $data_installment['max_amount_installment_kbank'] = $data['payment_max_price'];                      
                        
                    }

                    if (strcmp($channel,'installment_ktc_flexi') === 0)
                    {     
                        $data_installment['has_merchant_route_installment_ktc_flexi'] = 1;
                        $data_installment['has_merchant_fee_installment_ktc_flexi'] = 1;
                        $payment_min_price = 0;
                        $installment_min_amount = 0;
                        $display_min = 0; 
                        $i = 0;                   

                        $payment_min = $data['payment_min_price'];
                        
                        if($payment_min_price == 0 || $payment_min_price > $payment_min){
                            $payment_min_price = $payment_min;
                        }

                        foreach ($installments as $installment )
                        {
                            $plans = array();
                            if (strcmp($installment['AbsorbBy'],$absorb_by_installment_ktc_flexi) === 0)
                            {
                                $data_installment['has_merchant_service_fee_installment_ktc_flexi'] = 1;
                                $sum_installment_min_amount = $installment['InstallmentMinAmount'] * $installment['Terms'];

                                if($installment_min_amount == 0 || $installment_min_amount > $sum_installment_min_amount){
                                    $installment_min_amount = $sum_installment_min_amount;
                                }
    
                                if($amount >= $data['payment_min_price'] && $amount <= $data['payment_max_price'] && $amount >= $sum_installment_min_amount){
    
                                    $interest_rate = $installment['InterestRate'];
    
                                    if(strcmp($installment['AbsorbBy'],"Customer") === 0){
                                        $interest_rate = 0.74;
                                    }
    
                                    $interest = $amount * $interest_rate * $installment['Terms'] / 100;
                                    $monthly_amount = round( ( $amount + $interest ) / $installment['Terms'], 2 );
    
                                    $plans[] = array(
                                        'term_length'    => $installment['Terms'],
                                        'monthly_amount' => $monthly_amount,
                                        'interest_rate'  => $interest_rate,
                                    );
    
                                }
    
                                if($plans != null)
                                {

                                    if(
                                        $installment_ktc_flexi == array())
                                        { $installment_ktc_flexi = array($plans);}
                                    else{ array_push($installment_ktc_flexi,$plans);}                             
                            
                                }
                            }                        
                                                                                                    
                        }   

                        $result = array_map("unserialize", array_unique(array_map("serialize", $installment_ktc_flexi)));
                        $data_installment['installment_ktc_flexi'] = $result;
                        $data_installment['min_amount_installment_ktc_flexi'] = $payment_min_price; 
                        $data_installment['max_amount_installment_ktc_flexi'] = $data['payment_max_price'];        
                    }

                    if (strcmp($channel,'installment_scb') === 0)
                    {
                        $data_installment['has_merchant_route_installment_scb'] = 1;
                        $data_installment['has_merchant_fee_installment_scb'] = 1;
                        $payment_min_price = 0;
                        $installment_min_amount = 0;
                        $display_min = 0; 
                        $i = 0;                   

                        $payment_min = $data['payment_min_price'];
                                                
                        if($payment_min_price == 0 || $payment_min_price > $payment_min){
                            $payment_min_price = $payment_min;
                        }

                        foreach ($installments as $installment )
                        {
                            $plans = array();
                            if (strcmp($installment['AbsorbBy'],$absorb_by_installment_scb) === 0)
                            {
                                $data_installment['has_merchant_service_fee_installment_scb'] = 1;
                                $sum_installment_min_amount = $installment['InstallmentMinAmount'] * $installment['Terms'];

                                if($installment_min_amount == 0 || $installment_min_amount > $sum_installment_min_amount){
                                    $installment_min_amount = $sum_installment_min_amount;
                                }
    
                                if($amount >= $data['payment_min_price'] && $amount <= $data['payment_max_price'] && $amount >= $sum_installment_min_amount){
    
                                    $interest_rate = $installment['InterestRate'];
    
                                    if(strcmp($installment['AbsorbBy'],"Customer") === 0){
                                        $interest_rate = 0.74;
                                    }
    
                                    $interest = $amount * $interest_rate * $installment['Terms'] / 100;
                                    $monthly_amount = round( ( $amount + $interest ) / $installment['Terms'], 2 );
    
                                    $plans[] = array(
                                        'term_length'    => $installment['Terms'],
                                        'monthly_amount' => $monthly_amount,
                                        'interest_rate'  => $interest_rate,
                                    );
    
                                }
    
                                if($plans != null)
                                {

                                    if(
                                        $installment_scb == array())
                                        { $installment_scb = array($plans);}
                                    else{ array_push($installment_scb,$plans);}                             
                            
                                }
                            }                        
                                                                                                    
                        }   

                        $result = array_map("unserialize", array_unique(array_map("serialize", $installment_scb)));
                        $data_installment['installment_scb'] = $result;
                        $data_installment['min_amount_installment_scb'] = $payment_min_price; 
                        $data_installment['max_amount_installment_scb'] = $data['payment_max_price']; 
                    }

                    if (strcmp($channel,'installment_krungsri') === 0)
                    {
                        $data_installment['has_merchant_route_installment_krungsri'] = 1;
                        $data_installment['has_merchant_fee_installment_krungsri'] = 1;
                        $payment_min_price = 0;
                        $installment_min_amount = 0;
                        $display_min = 0; 
                        $i = 0;              

                        $payment_min = $data['payment_min_price'];
                                                
                        if($payment_min_price == 0 || $payment_min_price > $payment_min){
                            $payment_min_price = $payment_min;
                        }

                        foreach ($installments as $installment )
                        {
                            $plans = array();
                            if (strcmp($installment['AbsorbBy'],$absorb_by_installment_krungsri) === 0)
                            {
                                $data_installment['has_merchant_service_fee_installment_krungsri'] = 1;
                                $sum_installment_min_amount = $installment['InstallmentMinAmount'] * $installment['Terms'];

                                if($installment_min_amount == 0 || $installment_min_amount > $sum_installment_min_amount){
                                    $installment_min_amount = $sum_installment_min_amount;
                                }                            
    
                                if($amount >= $data['payment_min_price'] && $amount <= $data['payment_max_price'] && $amount >= $sum_installment_min_amount){
    
                                    $interest_rate = $installment['InterestRate'];
    
                                    if(strcmp($installment['AbsorbBy'],"Customer") === 0){
                                        $interest_rate = 0.72;
                                    }
    
                                    $interest = $amount * $interest_rate * $installment['Terms'] / 100;
                                    $monthly_amount = round( ( $amount + $interest ) / $installment['Terms'], 2 );
    
                                    $plans[] = array(
                                        'term_length'    => $installment['Terms'],
                                        'monthly_amount' => $monthly_amount,
                                        'interest_rate'  => $interest_rate,
                                    );
    
                                }
    
                                if($plans != null)
                                {

                                    if(
                                        $installment_krungsri == array())
                                        { $installment_krungsri = array($plans);}
                                    else{ array_push($installment_krungsri,$plans);}                             
                            
                                }
                            }                        
                                                                                                    
                        }   

                        $result = array_map("unserialize", array_unique(array_map("serialize", $installment_krungsri)));
                        $data_installment['installment_krungsri'] = $result;
                        $data_installment['min_amount_installment_krungsri'] = $payment_min_price; 
                        $data_installment['max_amount_installment_krungsri'] = $data['payment_max_price']; 
                        $data_installment['card_type_installment_krungsri'] = $data['card_type'];
                    }

                    if (strcmp($channel,'installment_firstchoice') === 0)
                    {
                        $data_installment['has_merchant_route_installment_firstchoice'] = 1;
                        $data_installment['has_merchant_fee_installment_firstchoice'] = 1;
                        $payment_min_price = 0;
                        $installment_min_amount = 0;
                        $display_min = 0; 
                        $i = 0;                   

                        $payment_min = $data['payment_min_price'];
                                                
                        if($payment_min_price == 0 || $payment_min_price > $payment_min){
                            $payment_min_price = $payment_min;
                        }

                        foreach ($installments as $installment )
                        {
                            $plans = array();
                            if (strcmp($installment['AbsorbBy'],$absorb_by_installment_firstchoice) === 0)
                            {
                                $data_installment['has_merchant_service_fee_installment_firstchoice'] = 1;
                                $sum_installment_min_amount = $installment['InstallmentMinAmount'] * $installment['Terms'];

                                if($installment_min_amount == 0 || $installment_min_amount > $sum_installment_min_amount){
                                    $installment_min_amount = $sum_installment_min_amount;
                                }
    
                                if($amount >= $data['payment_min_price'] && $amount <= $data['payment_max_price'] && $amount >= $sum_installment_min_amount){
    
                                    $interest_rate = $installment['InterestRate'];
    
                                    if(strcmp($installment['AbsorbBy'],"Customer") === 0){
                                        $interest_rate = 1.16;
                                    }
    
                                    $interest = $amount * $interest_rate * $installment['Terms'] / 100;
                                    $monthly_amount = round( ( $amount + $interest ) / $installment['Terms'], 2 );
    
                                    $plans[] = array(
                                        'term_length'    => $installment['Terms'],
                                        'monthly_amount' => $monthly_amount,
                                        'interest_rate'  => $interest_rate,
                                    );
    
                                }
    
                                if($plans != null)
                                {

                                    if(
                                        $installment_firstchoice == array())
                                        { $installment_firstchoice = array($plans);}
                                    else{ array_push($installment_firstchoice,$plans);}                             
                            
                                }
                            }                        
                                                                                                    
                        }   

                        $result = array_map("unserialize", array_unique(array_map("serialize", $installment_firstchoice)));
                        $data_installment['installment_firstchoice'] = $result;
                        $data_installment['min_amount_installment_firstchoice'] = $payment_min_price; 
                        $data_installment['max_amount_installment_firstchoice'] = $data['payment_max_price']; 
                        $data_installment['card_type_installment_firstchoice'] = $data['card_type'];
                    }

                    if (strcmp($channel,'installment_tbank') === 0)
                    {                    
                        foreach ($installments as $installment )
                        {
                            $monthly_amount = self::calculate_monthly_payment_amount($amount, $installment['Terms'], $installment['InterestRate'], $installment['InstallmentMinAmount']);
                       
                            if($installment_tbank == array()){ $installment_tbank = array($monthly_amount);}
                            else{ array_push($installment_tbank,$monthly_amount);}                           
                        }
                        $data_installment['installment_tbank'] = $installment_tbank;
                    }
                }
            }
        }

        foreach ($pay_with_points_fees as $channel => $value) {
            $count_value = count($value);
            if($count_value > 0)
            {

                $bill_amount = self::roundout($amount,1);
                $fee = self::calculate_fee($bill_amount, $currency, $value);

                $pay_with_points_fee = self::roundout($fee,1);

                if (strcmp($channel,'point_ktc_forever') === 0) $data_pay_with_points['fee_point_ktc_forever'] = $pay_with_points_fee;
            }
        }

    }  

    public static function roundout ($value, $places=0) {
        if ($places < 0) { $places = 0; }
        $x= pow(10, $places);
        return ($value >= 0 ? ceil($value * $x):floor($value * $x)) / $x;
    }

    public static function calculate_fee( $purchase_amount, $currency, $fee_data) {
        $fee_amount = -1;
        foreach ($fee_data as $data) {

            $currncy_name = $data['currncy_name'];
            $fee_min_amount = $data['fee_min_amount'];
            $payment_min_price = $data['payment_min_price'];
            $payment_max_price = $data['payment_max_price'];
            $fee_type = $data['fee_type'];
            
            if($purchase_amount >= $payment_min_price && $purchase_amount <= $payment_max_price && strcmp($currency,$currncy_name) === 0)
            {
                $fee_amount = $data['fee_amount'];

                if(strcmp($fee_type,'Percent') === 0)
                {
                    $fee_amount = ($purchase_amount * $fee_amount) / 100;
                }

                if($fee_min_amount > 0 && $fee_min_amount > $fee_amount)
                {
                    $fee_amount = $fee_min_amount;
                }
            }

        }
        return $fee_amount;
    }
    
    public static function calculate_monthly_payment_amount( $purchase_amount, $term, $interest_rate, $installment_min_amount, $payment_min_price, $payment_max_price) {
        $plans = array();
        $per_month = $purchase_amount / $term;
        $interest = $purchase_amount * $interest_rate * $term / 100;
        $monthly_amount = round( ( $purchase_amount + $interest ) / $term, 2 );
        $sum_installment_min_amount = $installment_min_amount * $term;

        if($purchase_amount >= $payment_min_price && $purchase_amount <= $payment_max_price && $purchase_amount >= $sum_installment_min_amount)
        {
            $plans[] = array(
                'term_length'    => $term,
                'monthly_amount' => $monthly_amount,
                'interest_rate'  => $interest_rate,
            );          
        }

        return $plans;
    }

    /**
	 * Return ChillPay Fee (Internetbanking).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_internetebanking() {
        global $data_internetbanking;
		return $data_internetbanking;
    }

    /**
	 * Return ChillPay Fee (Mobilebanking).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_mobilebanking() {
        global $data_mobilebanking;
		return $data_mobilebanking;
    }

    /**
	 * Return ChillPay Fee (CreditCard).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_creditcard() {
        global $data_creditcard;
		return $data_creditcard;
    }

    /**
	 * Return ChillPay Fee (eWallet).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_ewallet() {
        global $data_ewallet;
		return $data_ewallet;
    }
    
    /**
	 * Return ChillPay Fee (BillPayment).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_billpayment() {
        global $data_billpayment;
		return $data_billpayment;
    }

    /**
	 * Return ChillPay Fee (QR Code).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_qrcode() {
        global $data_qrcode;
		return $data_qrcode;
    }

    /**
	 * Return ChillPay Fee (Kiosk Machine).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_kiosk_machine() {
        global $data_kiosk_machine;
		return $data_kiosk_machine;
    }

    /**
	 * Return ChillPay Fee (Kiosk Machine).
	 *
	 * @return string
	 *
	 * @since  2.0
	 */
	public static function fee_installment() {
        global $data_installment;
		return $data_installment;
    }

    /**
	 * Return ChillPay Fee (Pay With Points).
	 *
	 * @return string
	 *
	 * @since  2.1
	 */
	public static function fee_pay_with_points() {
        global $data_pay_with_points;
		return $data_pay_with_points;
    }
    
    public static function check_fee() {
        global $check;
		return $check;
    }
}