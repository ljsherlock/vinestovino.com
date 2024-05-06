<?php

defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

define('CHILLPAY_PHP_LIB_VERSION', '7.4.19');

//Mode
define('CHILLPAY_MODE_SANDBOX', 'sandbox');
define('CHILLPAY_MODE_PROD', 'prod');

// API URL
define('CHILLPAY_SANDBOX_API_URL', 'https://sandbox-api-ecommerce.chillpay.co/api/v2');
define('CHILLPAY_PROD_API_URL', 'https://api-ecommerce.chillpay.co/api/v2');

//Payment Url
define('CHILLPAY_SANDBOX_PAYMENT_URL', CHILLPAY_SANDBOX_API_URL . '/Payment/');
define('CHILLPAY_PROD_PAYMENT_URL', CHILLPAY_PROD_API_URL . '/Payment/');

//Check Payment Status
define('CHILLPAY_SANDBOX_PAYMENT_STATUS_URL', CHILLPAY_SANDBOX_API_URL . '/PaymentStatus/');
define('CHILLPAY_PROD_PAYMENT_STATUS_URL', CHILLPAY_PROD_API_URL . '/PaymentStatus/');

//Get Merchant Route
define('CHILLPAY_SANDBOX_MERCHANT_ROUTE_URL', CHILLPAY_SANDBOX_API_URL . '/MerchantRoute/');
define('CHILLPAY_PROD_MERCHANT_ROUTE_URL', CHILLPAY_PROD_API_URL . '/MerchantRoute/');

//Get Merchant Info
define('CHILLPAY_SANDBOX_MERCHANT_URL', CHILLPAY_SANDBOX_API_URL . '/Merchant/');
define('CHILLPAY_PROD_MERCHANT_URL', CHILLPAY_PROD_API_URL . '/Merchant/');

//Get Merchant Fee
define('CHILLPAY_SANDBOX_MERCHANT_FEE_URL', CHILLPAY_SANDBOX_API_URL . '/MerchantFee/');
define('CHILLPAY_PROD_MERCHANT_FEE_URL', CHILLPAY_PROD_API_URL . '/MerchantFee/');

define('CHILLPAY_PLUGIN_VERSION', '2.5.1');
define('CHILLPAY_CONNECTTIMEOUT', 180);
define('CHILLPAY_TIMEOUT', 180);

define('CHILLPAY_SUPPORT_EMAIL', 'help@chillpay.co');
define('CHILLPAY_SUPPORT_LINE_ID', '@chillpay');
define('CHILLPAY_SUPPORT_PHONE', '+(66)2-107-7788 ext. 101');