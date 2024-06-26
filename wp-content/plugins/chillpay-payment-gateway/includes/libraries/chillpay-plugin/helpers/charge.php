<?php
if (! class_exists('ChillPayPluginHelperCharge')) {
    class ChillPayPluginHelperCharge
    {
        /**
         * @param \chillpay-php\ChillPayCharge $charge
         * @return boolean
         */
        public static function isChargeObject($charge)
        {
            if (! isset($charge['object']) || $charge['object'] !== 'charge')
                return false;

            return true;
        }

        /**
         * @param \chillpay-php\ChillPayCharge $charge
         * @return boolean
         */
        public static function isAuthorized($charge)
        {
            if (self::isChargeObject($charge)) {
                if ($charge['authorized'] === true)
                    return true;
            }

            return false;
        }

        /**
         * @param \chillpay-php\ChillPayCharge $charge
         * @return boolean
         */
        public static function isPaid($charge)
        {
            if (self::isChargeObject($charge)) {
                // support ChillPay API version '2018-10-25' by checking if 'captured' exist.
                $paid = isset($charge['captured']) ? $charge['captured'] : $charge['paid'];
                if ($paid === true)
                    return true;
            }

            return false;
        }

        /**
         * @param \chillpay-php\ChillPayCharge $charge
         * @return boolean
         */
        public static function isFailed($charge)
        {
            if (! self::isChargeObject($charge))
                return true;

            if ((! is_null($charge['failure_code']) && $charge['failure_code'] !== "")
                || (! is_null($charge['failure_message']) && $charge['failure_message'] !== ""))
                return true;

            if (strtoupper($charge['status']) === 'FAILED')
                return true;

            return false;
        }
    }
}