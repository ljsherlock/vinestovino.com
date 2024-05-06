<?php
require_once dirname(__FILE__,6) . '/chillpay-config.php';

class ChillPayApiResource extends ChillPayObject
{
    // Request methods
    const REQUEST_GET = 'GET';
    const REQUEST_POST = 'POST';
    const REQUEST_DELETE = 'DELETE';
    const REQUEST_PATCH = 'PATCH';

    /**
	 * @var ChillPay_Setting
	 */                                                    
    protected $chillpay_settings;
    
    /**
	 * @since 1.0
	 */
	public function __construct($apikey = null, $secretkey = null)
    {
        $this->chillpay_settings = new ChillPay_Setting;

        if ($apikey !== null) {
            $this->_apikey = $apikey;
        } else {
            $this->_apikey = $this->chillpay_settings->api_key();
        }

        if ($secretkey !== null) {
            $this->_secretkey = $secretkey;
        } else {
            $this->_secretkey = $this->chillpay_settings->md5_key();
        }

        parent::__construct($this->_apikey, $this->_secretkey);
    }
    
    /**
	 * @return array
	 *
	 * @since  1.0
	 */
	protected function get_settings()
    {
		return $this->chillpay_settings->get_settings();
    }
    
    /**
     * Returns an instance of the class given in $clazz or raise an error.
     *
     * @param  string $clazz
     * @param  string $apikey
     * @param  string $secretkey
     *
     * @throws Exception
     *
     * @return ChillPayResource
     */
    protected static function getInstance($clazz, $apikey, $secretkey)
    {
        if (class_exists($clazz)) {
            return new $clazz($apikey, $secretkey);
        }

        throw new Exception('Undefined class.');
    }

    /**
     * Retrieves the resource.
     *
     * @param  string $clazz
     * @param  string $apikey
     * @param  string $secretkey
     *
     * @throws Exception|ChillPayException
     *
     * @return ChillPayAccount|ChillPayCharge|ChillPayPaymentGateway
     */
    protected static function g_retrieve($clazz, $url, $apikey, $secretkey)
    {
        $resource = call_user_func(array($clazz, 'getInstance'), $clazz, $apikey, $secretkey);
        $result   = $resource->execute($url, self::REQUEST_GET);

        return $resource;
    }

    /**
     * Creates the resource with given parameters.in an associative array.
     *
     * @param  string $clazz
     * @param  string $url
     * @param  array  $params
     * @param  string $apikey
     * @param  string $secretkey
     *
     * @throws Exception|ChillPayException
     *
     * @return ChillPayAccount|ChillPayCharge|ChillPayPaymentGateway
     */
    public function create_payment($url, $params)
    {
        error_log(get_class() . '->create_payment //URL:' . $url . "\nParams:" . json_encode($params));

        $result = $this->execute($url, self::REQUEST_POST, $params);
        return $result;
    }

    /**
     * Makes a request and returns a decoded JSON data as an associative array.
     *
     * @param  string $url
     * @param  string $requestMethod
     * @param  array  $params
     *
     * @throws ChillPayException
     *
     * @return array
     */
    protected function execute($url, $requestMethod, $params)
    {
        // If this class is execute by phpunit > get test mode.
        if (preg_match('/phpunit/', $_SERVER['SCRIPT_NAME'])) {
            $result = $this->_executeTest($url, $requestMethod, $params);
        } else {
            // $result = $this->_executeCurl($url, $requestMethod, $params);

            try {
                $post_data = $this->get_payment_parameters($params);
                $headers = array(
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'User-Agent' => $this->get_chillpay_user_agent(),
                );
                $args = array(
                    'body'        => $post_data,
                    'timeout'     => CHILLPAY_TIMEOUT,
                    'redirection' => CHILLPAY_TIMEOUT,
                    'httpversion' => CURL_HTTP_VERSION_1_1,
                    'headers'     => $headers,
                );

                error_log(get_class() . '->execute: ' . $url . ", Post Data:" . json_encode($args));

                $json_response = wp_remote_post($url, $args);
                $http_body = wp_remote_retrieve_body($json_response);

                if (is_wp_error($json_response)) {
                    error_log('wp_remote_post: ' . json_encode($json_response) . ' //' . get_class($json_response));

                    throw new Exception($json_response->get_error_message());
                }

                if ($json_response['response']['code'] !== 200) {
                    error_log('wp_remote_post: ' . json_encode($json_response) . ' //' . get_class($json_response));

                    throw new Exception($json_response['response']['message']);
                }

                error_log('wp_remote_retrieve_body: ' . json_encode($http_body));

                return $http_body;
            } catch (Exception $e) {
                error_log(get_class() . '->execute: ' . $e->getMessage());

                throw new Exception($this->get_system_error());
            }
        }

        return $result;
    }

    /**
     * @param  string $url
     * @param  string $requestMethod
     * @param  array  $params
     *
     * @throws ChillPayException
     *
     * @return string
     */
    private function _executeCurl($url, $requestMethod, $params = null)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, $this->genOptions($requestMethod, '', $params));

        // Make a request or thrown an exception.
        if (($result = curl_exec($ch)) === false) {
            $error = curl_error($ch);
            curl_close($ch);

            error_log(get_class() . '->_executeCurl //' . $error);

            throw new Exception($error);
        }

        // Close.
        curl_close($ch);
        return $result;
    }

    /**
     * @param  string $url
     * @param  string $requestMethod
     * @param  array  $params
     *
     * @throws ChillPayException
     *
     * @return string
     */
    private function _executeTest($url, $requestMethod, $params = null)
    {
        // Extract only hostname and URL path without trailing slash.
        $parsed = parse_url($url);
        $request_url = $parsed['host'] . rtrim($parsed['path'], '/');

        // Convert query string into filename friendly format.
        if (!empty($parsed['query'])) {
            $query = base64_encode($parsed['query']);
            $query = str_replace(array('+', '/', '='), array('-', '_', ''), $query);
            $request_url = $request_url.'-'.$query;
        }

        // Finally.
        $request_url = dirname(__FILE__).'/../../../tests/fixtures/'.$request_url.'-'.strtolower($requestMethod).'.json';

        // Make a request from Curl if json file was not exists.
        if (! file_exists($request_url)) {
            // Get a directory that's file should contain.
            $request_dir = explode('/', $request_url);
            unset($request_dir[count($request_dir) - 1]);
            $request_dir = implode('/', $request_dir);

            // Create directory if it not exists.
            if (! file_exists($request_dir)) {
                mkdir($request_dir, 0777, true);
            }

            $result = $this->_executeCurl($url, $requestMethod, $params);

            $f = fopen($request_url, 'w');
            if ($f) {
                fwrite($f, $result);

                fclose($f);
            }
        } else { // Or get response from json file.
            $result = file_get_contents($request_url);
        }

        return $result;
    }

    public function get_chillpay_merchant ($merchant_code = null) {
        try{
            $merchant_code = is_null($merchant_code) ? $this->get_merchant_code() : $merchant_code;
            $str = $merchant_code . $this->_apikey . trim(esc_attr($this->_secretkey));
            $checksum = md5($str);

            $body = array(
                'MerchantCode' => $merchant_code,
                'ApiKey' => $this->_apikey,
                'CheckSum' => $checksum,
            );
            $headers = array(
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => $this->get_chillpay_user_agent(),
            );
            $args = array(
                'body'        => $body,
                'timeout'     => CHILLPAY_TIMEOUT,
                'redirection' => CHILLPAY_TIMEOUT,
                'httpversion' => CURL_HTTP_VERSION_1_1,
                'headers'     => $headers,
            );

            error_log('get_chillpay_merchant: ' . $this->get_merchant_api_url() . ", Post Data:" . json_encode($args));

            $json_response = wp_remote_post($this->get_merchant_api_url(), $args);
            $http_body = wp_remote_retrieve_body($json_response);
            // $http_header = wp_remote_retrieve_headers($response);

            if (is_wp_error($json_response)) {
                error_log('wp_remote_post: ' . json_encode($json_response) . ' //' . get_class($json_response));

                throw new Exception($json_response->get_error_message());
            }

            if ($json_response['response']['code'] !== 200) {
                error_log('wp_remote_post: ' . json_encode($json_response));

                // throw new Exception($json_response['response']['message']);
                return null;
            }

            $response_data = json_decode($http_body);
            return $response_data;
        }
        catch(Exception $e)
        {
            error_log('get_chillpay_merchant : ' . $e->getMessage());

            throw new Exception($this->get_system_error('3001', $e->getMessage()));
        }

        return null;
    }

    public function inquiry_payment_status(string $tnx_id)
    {
        error_log(get_class() . '->inquiry_payment_status //' . $tnx_id);

        try {
            $str = $this->get_merchant_code() . $tnx_id . $this->_apikey . trim(esc_attr($this->_secretkey));
            $checksum = md5($str);

            $body = array(
                'MerchantCode' => $this->get_merchant_code(),
                'TransactionId' => $tnx_id,
                'ApiKey' => $this->_apikey,
                'CheckSum' => $checksum,
            );
            $headers = array(
                'Cache-Control' => 'no-cache',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'User-Agent' => $this->get_chillpay_user_agent(),
            );
            $args = array(
                'body'        => $body,
                'timeout'     => CHILLPAY_TIMEOUT,
                'redirection' => CHILLPAY_TIMEOUT,
                'httpversion' => CURL_HTTP_VERSION_1_1,
                'headers'     => $headers,
            );

            error_log('inquiry_payment_status: ' . $this->get_payment_status_api_url() . ", Post Data:" . json_encode($args));

            $json_response = wp_remote_post($this->get_payment_status_api_url(), $args);
            $http_body = wp_remote_retrieve_body($json_response);

            if (is_wp_error($json_response)) {
                error_log('wp_remote_post: ' . json_encode($json_response));
                throw new Exception($json_response->get_error_message());
            }

            if ($json_response['response']['code'] !== 200) {
                error_log('wp_remote_post: ' . json_encode($json_response));
                throw new Exception($json_response['response']['message']);
            }

            error_log('wp_remote_retrieve_body: ' . json_encode($http_body));

            $response_data = json_decode($http_body);
            error_log('$response_data : ' . json_encode($response_data));
            return $response_data;
        } catch (Exception $e) {
            error_log('inquiry_payment_status: ' . $e->getMessage());

            throw new Exception($this->get_system_error('3001', $e->getMessage()));
        }

        return null;
    }

    public function get_system_error(string $error_code = '3001', $error_message = 'System Error')
    {
        return '<br>Code : ' . $error_code . '<br>' .
            'Detail : ' . $error_message . '<br><br>' .
            'ChillPay Customer Support <br>' .
            'Tel : ' . CHILLPAY_SUPPORT_PHONE . ' <br>' .
            'Line : ' . CHILLPAY_SUPPORT_LINE_ID . ' <br>' .
            'Email : ' . CHILLPAY_SUPPORT_EMAIL;
    }

    private function get_payment_parameters($params)
    {
        error_log(get_class() . '->get_payment_parameters');

        // Also merge POST parameters with the option.
        $raw_data = array();

        if (count($params) > 0) {
            $metadata = $params["metadata"];
            $order_number = '0';
            $installment_terms = 0;
            $absorb_by = '';
            $card_type = '';

            if (is_array($metadata)) {
                $order_number = $metadata['order_number'];
                $installment_terms = $metadata['installment_terms'];
                $absorb_by = $metadata['absorb_by'];
                if (isset($metadata['card_type']))
                {
                    $card_type = $metadata['card_type'];
                }
            }

            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if (getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if (getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if (getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if (getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if (getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';

            $sub_ip = substr($ipaddress, 0, 20);
            $customer_id = mb_substr($params["customer_id"], 0, 100);

            $currency_items = $this->chillpay_settings->get_currency_items();
            $currency = $currency_items[$params["currency"]];

            $phone_number = !empty($params["kplus_mobile"]) ? $params["kplus_mobile"] : $params["phone"];

            if (substr($phone_number, 0, 4) == "+660")
                $phone_number = str_replace('+660', '0', $phone_number); 
            elseif (substr($phone_number, 0, 3) == "+66")
                $phone_number = str_replace('+66', '0', $phone_number);
            elseif (substr($phone_number, 0, 3) == "660")
                $phone_number = str_replace('660', '0', $phone_number);
            elseif (substr($phone_number, 0, 2) == "66")
                $phone_number = str_replace('66', '0', $phone_number);
            
            $phone_number = str_replace('+','',$phone_number);              
            $phone_number = substr($phone_number,0,10);

            $description = $order_number;
            if (strpos($params["offsite"], 'creditcard') !== 0 && strcmp($params["offsite"], 'installment_kbank') !== 0) {
                $merchant_data = $this->get_chillpay_merchant($params["merchant_code"]);

                if (!is_null($merchant_data) && !is_null($merchant_data->ShortNameEN)) {
                    $short_name_en = strtoupper($merchant_data->ShortNameEN);
                    $description = $short_name_en . "-" . $order_number;
                } else {
                    $description = "CHILLPAY-" . $order_number;
                }

                $description = (strlen($description) > 30) ? substr($description, 0, 30) : $description;
            }

            $get_locale = get_locale();
            $lang_code = 'EN';
            if (strpos($get_locale, 'th') !== false)
            {
                $lang_code = 'TH';
            }

            $cust_email = $params["customer_email"];

            $str_data = $params["merchant_code"] . $order_number . $customer_id . $params["amount"] . $phone_number;
            $str_data .= $description . $params["offsite"] . $currency . $lang_code . $params["route_no"];
            $str_data .= $sub_ip . $this->_apikey . $installment_terms . $absorb_by . $cust_email . $card_type . trim($this->_secretkey);
            $checksum = md5($str_data);

            $raw_data  = array(
                "MerchantCode"  => $params["merchant_code"],
                "OrderNo"       => $order_number,
                "CustomerId"    => $customer_id,
                "Amount"        => $params["amount"],
                "PhoneNumber"   => $phone_number,
                "Description"   => $description,
                "ChannelCode"   => $params["offsite"],
                "Currency"      => $currency,
                "LangCode"      => $lang_code,
                "RouteNo"       => $params["route_no"],
                "IPAddress"     => $sub_ip,
                "APIKey"        => $this->_apikey,
                "CreditMonth"   => $installment_terms,
                "ShopID"        => $absorb_by,
                "CustEmail"     => $cust_email,
                "CardType"      => $card_type,
                "CheckSum"      => $checksum,
            );
        }

        error_log(get_class() . '->get_payment_parameters // post data: ' . json_encode($raw_data));

        return $raw_data;
    }

    /**
     * Creates an option for php-curl from the given request method and parameters in an associative array.
     *
     * @param  string $requestMethod
     * @param  array  $params
     *
     * @return array
     */
    private function genOptions($requestMethod, $userpwd, $params)
    {
        $user_agent = $this->get_chillpay_user_agent();

        $options = array(
           // Make php-curl returns the data as string.
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           // Time before the request is aborted.
           CURLOPT_TIMEOUT        => CHILLPAY_TIMEOUT,
           // Set the HTTP version to 1.1.
           CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
           // Set the request method.
           CURLOPT_CUSTOMREQUEST  => $requestMethod,
           CURLOPT_SSL_VERIFYPEER => false,
        );


        $options += array(CURLOPT_HTTPHEADER => array("Cache-Control: no-cache", "Content-Type: application/x-www-form-urlencoded"));

        // Config UserAgent   
        if (defined('CHILLPAY_USER_AGENT_SUFFIX')) {
            $options += array(CURLOPT_USERAGENT => $user_agent." ".CHILLPAY_USER_AGENT_SUFFIX);
        } else {
            $options += array(CURLOPT_USERAGENT => $user_agent);
        }

        // Also merge POST parameters with the option.
        if (count($params) > 0) {
            $metadata = $params["metadata"];
            $order_number = '0';
            $installment_terms = 0;
            $absorb_by = '';
            
            if ( is_array($metadata) ) {
                $order_number = $metadata['order_number'];
                if ($metadata['installment_terms'] !== null) $installment_terms = $metadata['installment_terms'];
                if ($metadata['absorb_by'] !== null) $absorb_by = $metadata['absorb_by'];
            }

            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';

            $sub_ip = substr($ipaddress, 0, 20);
            $customer_id = mb_substr($params["customer_id"], 0, 100);
            
            $currency_items = $this->chillpay_settings->get_currency_items();
            $currency = $currency_items[$params["currency"]];
            
            $customer_email = $params["customer_email"];
            $phone_number = ( !empty($params["kplus_mobile"]) ) ? $params["kplus_mobile"] : $params["phone"];

            $description = $order_number;
            if (strpos($params["offsite"],'creditcard') !== 0 && strcmp($params["offsite"],'installment_kbank') !== 0) {
                $merchant_data = $this->get_chillpay_merchant($params["merchant_code"]);
                if (!is_null($merchant_data) && !is_null($merchant_data->ShortNameEN)) {
                    $short_name_en = strtoupper($merchant_data->ShortNameEN);
                    $description = $short_name_en . "-" . $order_number;
                } else {
                    $description = "CHILLPAY-" . $order_number;
                }
                $description = (strlen($description) > 30) ? substr($description, 0, 30) : $description;
            }

            $get_locale = get_locale();
            $lang_code = 'EN';
            if (strpos($get_locale, 'th') !== false)
            {
                $lang_code = 'TH';
            }

            $str_data = $params["merchant_code"] . $order_number . $customer_id . $params["amount"] . $phone_number;
            $str_data .= $description . $params["offsite"] . $currency . $lang_code . $params["route_no"];
            $str_data .= $sub_ip . $this->_apikey . $installment_terms . $absorb_by . $customer_email . trim($this->_secretkey);
            $checksum = md5($str_data);

            $p_value  = "MerchantCode=" .$params["merchant_code"];
            $p_value .= "&OrderNo="     .$order_number;
            $p_value .= "&CustomerId="  .$customer_id;
            $p_value .= "&Amount="      .$params["amount"];
            $p_value .= "&PhoneNumber=" .$phone_number;
            $p_value .= "&Description=" .$description;
            $p_value .= "&ChannelCode=" .$params["offsite"];
            $p_value .= "&Currency="    .$currency;
            $p_value .= "&LangCode="    .$lang_code;
            $p_value .= "&RouteNo="     .$params["route_no"];
            $p_value .= "&IPAddress="   .$sub_ip;
            $p_value .= "&APIKey="      .$this->_apikey;
            $p_value .= "&CreditMonth=" .$installment_terms;
            $p_value .= "&ShopID="      .$absorb_by;
            $p_value .= "&CustEmail="   .$customer_email;
            $p_value .= "&CheckSum="    .$checksum;

            $options += array(CURLOPT_POSTFIELDS => $p_value);
        }

        return $options;
    }

     /**
     * Returns the ChillPay Setting
     * 
     * @return ChillPay_Setting
     */
    protected function get_chillpay_setting()
    {
        return $this->chillpay_settings;
    }

    protected function get_chillpay_user_agent()
    {
        return "ChillPayWooCommerce/" . CHILLPAY_PLUGIN_VERSION . " PHP/" . phpversion();
    }

    protected function get_merchant_code()
    {
        return $this->chillpay_settings->merchant_code();
    }

    protected function get_merchant_api_url()
    {
        return $this->chillpay_settings->get_merchant_url();
    }

    protected function get_payment_status_api_url()
    {
        return $this->chillpay_settings->get_payment_status_url();
    }
}