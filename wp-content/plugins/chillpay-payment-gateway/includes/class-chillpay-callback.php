<?php

defined('ABSPATH') || exit;

/**
 * @since 2.0
 */
class ChillPay_Callback
{
    /**
     * @var \WC_Abstract_Order
     */
    protected $order;

    /**
     * @var \ChillPayCharge
     */
    protected $charge;

    /**
     * @param \WC_Abstract_Order $order
     */
    public function __construct($order)
    {
        $this->order = $order;
        if (!$this->order || !$this->order instanceof WC_Abstract_Order) $this->invalid_result();
    }

    public static function execute($post_data)
    {
        error_log(get_class() . '->execute');
        $order_number = isset($post_data['OrderNo']) ? sanitize_text_field($post_data['OrderNo']) : null;
        $order_number = ltrim($order_number, '#');

        // search for the order by custom order number
        $query_args = array(
            'numberposts' => 1,
            'meta_key'    => '_order_number_formatted',
            'meta_value'  => $order_number,
            'post_type'   => 'shop_order',
            'post_status' => 'any',
            'fields'      => 'ids',
        );

        $posts            = get_posts($query_args);
        list($order_id) = !empty($posts) ? $posts : null;

        // order was found
        if (null !== $order_id) {
            $callback = new self(wc_get_order($order_id));
        } else {
            $callback = new self(wc_get_order($order_number));
        }

        $callback->validate($post_data);
    }

    public function validate($post_data)
    {
        error_log(get_class() . '->validate');
        $this->order->add_order_note(__('ChillPay: Validating the payment result...', 'chillpay'));

        $helper = new ChillPay_Helper();
        $valid_checksum = $helper->chillpay_get_payment_checksum($post_data);
        $checksum = isset($post_data['CheckSum']) ? sanitize_text_field($post_data['CheckSum']) : "";

        if ($valid_checksum !== $checksum) {
            $this->order->add_order_note(__('ChillPay: We cannot validate your payment result. [E1]', 'chillpay'));

            header('Location: ' . wc_get_cart_url());
            die();
        }

        $settings = new ChillPay_Setting();
        $is_setting_order = $settings->setting_order();

        $update = 1;
        $order_status = $this->order->get_status();

        error_log('(callback) orderNo['.$post_data['OrderNo'].'] order status : '.$order_status.' | chillpay status : '.$post_data['PaymentStatus']);

        if ((strpos($order_status, 'processing') !== false) || (strpos($order_status, 'shipped') !== false) || (strpos($order_status, 'completed') !== false)) {
            $update = 0;
            $message = __('ChillPay: Order has already paid.', 'chillpay');
            $this->order->add_order_note(sprintf(wp_kses($message, array('br' => array()))));
        }

        if ($update === 1) {
            error_log('(callback) update');
            $payment_status = isset($post_data['PaymentStatus']) ? sanitize_text_field($post_data['PaymentStatus']) : "";
            try {
                if ($payment_status === '0') { //Payment : Success
                    $message = __('ChillPay: Payment successful.<br/>An amount of %1$s %2$s has been paid', 'chillpay');

                    $this->order->update_status( 'processing' );
                    $this->order->add_order_note(
                        sprintf(
                            wp_kses($message, array('br' => array())),
                            $this->order->get_total(),
                            $this->order->get_currency()
                        )
                    );

                    WC()->cart->empty_cart();
                    wp_redirect($this->order->get_checkout_order_received_url());
                    die();
                } else if ($payment_status === '1'  && (!$is_setting_order)) { //Payment : Fail
                    $message = __('ChillPay: Payment failed.', 'chillpay');

                    $this->order->update_status('failed');
                    $this->order->add_order_note(sprintf(wp_kses($message, array('br' => array()))));

                    WC()->cart->empty_cart();
                    wp_redirect(wc_get_checkout_url());
                    die();
                } else if ($payment_status === '1'  && ($is_setting_order)) { //Payment : Fail
                    $message = __('ChillPay: Payment cancelled.', 'chillpay');

                    $this->order->update_status('cancelled');
                    $this->order->add_order_note(sprintf(wp_kses($message, array('br' => array()))));

                    WC()->cart->empty_cart();
                    wp_redirect(wc_get_checkout_url());
                    die();
                } else if ($payment_status === '2') { //Payment : Cancel
                    $message = __('ChillPay: Payment cancelled.', 'chillpay');

                    $this->order->update_status('cancelled');
                    $this->order->add_order_note(sprintf(wp_kses($message, array('br' => array()))));

                    WC()->cart->empty_cart();
                    wp_redirect(wc_get_checkout_url());
                    die();
                } else if ($payment_status === '3'  && (!$is_setting_order)) { //Payment : Error
                    $message = __('ChillPay: Payment error.', 'chillpay');
                    $this->order->add_order_note(sprintf(wp_kses($message, array('br' => array()))));

                    $this->order->update_status('failed');

                    WC()->cart->empty_cart();
                    wp_redirect(wc_get_checkout_url());
                    die();
                } else if ($payment_status === '3'  && ($is_setting_order)) { //Payment : Error
                    $message = __('ChillPay: Payment error.', 'chillpay');
                    $this->order->add_order_note(sprintf(wp_kses($message, array('br' => array()))));

                    $this->order->update_status('cancelled');

                    WC()->cart->empty_cart();
                    wp_redirect(wc_get_checkout_url());
                    die();
                } else {
                    //Payment : Error
                    $message = __('ChillPay: Payment error.', 'chillpay');
                    $this->order->add_order_note(sprintf(wp_kses($message, array('br' => array()))));

                    WC()->cart->empty_cart();
                    wp_redirect(wc_get_checkout_url());
                    die();
                }
            } catch (Exception $e) {

                wc_add_notice(
                    sprintf(
                        wp_kses(
                            __('Seems we cannot process your payment properly:<br/>%s', 'chillpay'),
                            array('br' => array())
                        ),
                        $e->getMessage()
                    ),
                    'error'
                );

                $this->order->add_order_note(
                    sprintf(
                        wp_kses(
                            __('ChillPay: Payment failed/error.<br/>%s', 'chillpay'),
                            array('br' => array())
                        ),
                        $e->getMessage()
                    )
                );

                wp_redirect(wc_get_checkout_url());
                die();
            }
        }
        else
        {
            die();
        }
    }

    /**
     * Resolving a case of undefined charge status
     */
    protected function invalid_result()
    {
        error_log(get_class() . '->invalid_result');
        $message = __(
            '<strong>We cannot validate your payment result:</strong><br/>
			 Note that your payment may have already been processed.<br/>
			 Please contact our support team if you have any questions.',
            'chillpay'
        );

        wc_add_notice(wp_kses($message, array('br' => array(), 'strong' => array())), 'error');
        wp_redirect(wc_get_checkout_url());
        exit;
    }
}
