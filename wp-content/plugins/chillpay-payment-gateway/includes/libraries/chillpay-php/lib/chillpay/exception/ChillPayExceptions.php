<?php

class ChillPayException extends Exception
{
    private $_chillpayError = null;

    public function __construct($message =null, $chillpayError = null)
    {
        parent::__construct($message);
        $this->setChillPayError($chillpayError);
    }

     /**
     * Returns an instance of an exception class from the given error response.
     *
     * @param  array $array
     *
     * @return ChillPayAuthenticationFailureException|ChillPayNotFoundException|ChillPayUsedTokenException|ChillPayInvalidCardException|ChillPayInvalidCardTokenException|ChillPayMissingCardException|ChillPayInvalidChargeException|ChillPayFailedCaptureException|ChillPayFailedFraudCheckException|ChillPayUndefinedException
     */
    public static function getInstance($array)
    {
        switch ($array['code']) {
            case 'authentication_failure':
                return new ChillPayAuthenticationFailureException($array['message'],$array);

            case 'bad_request':
            return new ChillPayBadRequestException($array['message'], $array);

            case 'not_found':
                return new ChillPayNotFoundException($array['message'], $array);

            case 'used_token':
                return new ChillPayUsedTokenException($array['message'], $array);

            case 'invalid_card':
                return new ChillPayInvalidCardException($array['message'], $array);

            case 'invalid_card_token':
                return new ChillPayInvalidCardTokenException($array['message'], $array);

            case 'missing_card':
                return new ChillPayMissingCardException($array['message'], $array);

            case 'invalid_charge':
                return new ChillPayInvalidChargeException($array['message'], $array);

            case 'failed_capture':
                return new ChillPayFailedCaptureException($array['message'], $array);

            case 'failed_fraud_check':
                return new ChillPayFailedFraudCheckException($array['message'], $array);

            case 'failed_refund':
                return new ChillPayFailedRefundException($array['message'], $array);

            case 'invalid_link':
                return new ChillPayInvalidLinkException($array['message'], $array);

            case 'invalid_recipient':
                return new ChillPayInvalidRecipientException($array['message'], $array);

            case 'invalid_bank_account':
                return new ChillPayInvalidBankAccountException($array['message'], $array);

            default:
                return new ChillPayUndefinedException($array['message'], $array);
        }
    }

    /**
     * Sets the error.
     *
     * @param ChillPayError $chillpayError
     */
    public function setChillPayError($chillpayError)
    {
        $this->_chillpayError = $chillpayError;
    }

    /**
     * Gets the ChillPayError object. This method will return null if an error happens outside of the API. (For example, due to HTTP connectivity problem.)
     * Please see https://docs.chillpay.co/api/errors/ for a list of possible errors.
     *
     * @return ChillPayError
     */
    public function getChillPayError()
    {
        return $this->_chillpayError;
    }
}

class ChillPayAuthenticationFailureException extends ChillPayException { }
class ChillPayBadRequestException extends ChillPayException { }
class ChillPayNotFoundException extends ChillPayException { }
class ChillPayUsedTokenException extends ChillPayException { }
class ChillPayInvalidCardException extends ChillPayException { }
class ChillPayInvalidCardTokenException extends ChillPayException { }
class ChillPayMissingCardException extends ChillPayException { }
class ChillPayInvalidChargeException extends ChillPayException { }
class ChillPayFailedCaptureException extends ChillPayException { }
class ChillPayFailedFraudCheckException extends ChillPayException { }
class ChillPayFailedRefundException extends ChillPayException { }
class ChillPayInvalidLinkException extends ChillPayException { }
class ChillPayInvalidRecipientException extends ChillPayException { }
class ChillPayInvalidBankAccountException extends ChillPayException { }
class ChillPayUndefinedException extends ChillPayException { }