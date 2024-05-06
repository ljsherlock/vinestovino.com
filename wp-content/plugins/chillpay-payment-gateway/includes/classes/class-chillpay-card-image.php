<?php
defined( 'ABSPATH' ) or die( "No direct script access allowed." );

if ( ! class_exists( 'ChillPay_Card_Image' )) {
    class ChillPay_Card_Image {
        /**
		 * Compose the given parameters into the string of HTML &lt;img&gt; element
		 *
		 * @param string $file Image file name with extension such as image.jpg
		 * @param string $alternate_text Alternate text for the image
		 * @return string HTML &lt;img&gt; element
		 */
		private static function get_image( $file, $alternate_text, $size, $height ) {
			is_null($height) ? $height = '100%' : $height = $height;
			$url = plugins_url( '../../assets/images/', __FILE__ );
			return "<img src='$url/$file' class='ChillPay-CardBrandImage' style='width: $size;height: 100%;' alt='$alternate_text' />";
        }
        
        /**
		 * Return the CSS used to format the image to be displayed vertical center align with checkbox
		 * at the back-end setting page
		 *
		 * @return string
		 */
		public static function get_css() {
			return 'vertical-align: 5px;';
        }
        
        #region set image credit card

		/**
		 * Return the default setting of display the JCB logo
		 *
		 * @return string
		 */
		public static function get_jcb_default_display() {
			return 'no';
        }
        
        /**
		 * Return the HTML &lt;img&gt; element of JCB logo
		 *
		 * @return string
		 */
		public static function get_jcb_image() {
			return self::get_image( 'icon-credit-card/IconCreditCard_JCB.png', 'JCB' , '38px', null);
        }
        
        /**
		 * Return the default setting of display the MasterCard logo
		 *
		 * @return string
		 */
		public static function get_mastercard_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of MasterCard logo
		 *
		 * @return string
		 */
		public static function get_mastercard_image() {
			return self::get_image( 'icon-credit-card/IconCreditCard_MasterCard.png', 'MasterCard', '38px', null);
        }
        
        /**
		 * Return the default setting of display the Visa logo
		 *
		 * @return string
		 */
		public static function get_visa_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Visa logo
		 *
		 * @return string
		 */
		 public static function get_visa_image() {
			return self::get_image( 'icon-credit-card/IconCreditCard_VISA.png', 'Visa' , '38px', null);
        }
        
        /**
		 * Return the default setting of display the Union Pay logo
		 *
		 * @return string
		 */
		public static function get_unionpay_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Union Pay logo
		 *
		 * @return string
		 */
		 public static function get_unionpay_image() {
			return self::get_image( 'icon-credit-card/IconCreditCard_UnionPay.png', 'UnionPay' , '38px', null);
        }

		/**
		 * Return the default setting of display the VISA, MASTER, JCB logo
		 *
		 * @return string
		 */
		public static function get_creditcard_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of VISA, MASTER, JCB logo
		 *
		 * @return string
		 */
		 public static function get_creditcard_image() {
			return self::get_image( 'icon-credit-card/IconCreditCard_UnionPay.png', 'UnionPay' , '38px', null);
        }
        
        #endregion
        
        #region set image bank

        /**
		 * Return the default setting of display the SCB logo
		 *
		 * @return string
		 */
		public static function get_scb_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of SCB logo
		 *
		 * @return string
		 */
		public static function get_scb_image() {
			return self::get_image( 'icon-bank/IconBank_SCB2.png', 'SCB' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of SCB logo
		 *
		 * @return string
		 */
		public static function get_qr_scb_image() {
			return self::get_image( 'icon-bank/IconBank_SCB.png', 'SCB' , '25px', null);
        }
        
        /**
		 * Return the default setting of display the KTB logo
		 *
		 * @return string
		 */
		public static function get_ktb_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of KTB logo
		 *
		 * @return string
		 */
		public static function get_ktb_image() {
			return self::get_image( 'icon-bank/IconBank_KTB2.png', 'KTB' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of KTB logo
		 *
		 * @return string
		 */
		public static function get_qr_ktb_image() {
			return self::get_image( 'icon-bank/IconBank_KTB.png', 'KTB' , '25px', null);
        }
        
        /**
		 * Return the default setting of display the BAY logo
		 *
		 * @return string
		 */
		public static function get_bay_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of BAY logo
		 *
		 * @return string
		 */
		public static function get_bay_image() {
			return self::get_image( 'icon-bank/IconBank_BAY2.png', 'BAY' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of BAY logo
		 *
		 * @return string
		 */
		public static function get_qr_bay_image() {
			return self::get_image( 'icon-bank/IconBank_BAY.png', 'BAY' , '25px', null);
        }
        
        /**
		 * Return the default setting of display the BBL logo
		 *
		 * @return string
		 */
		public static function get_bbl_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of BBL logo
		 *
		 * @return string
		 */
		public static function get_bbl_image() {
			return self::get_image( 'icon-bank/IconBank_BBL2.png', 'BBL' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of BBL logo
		 *
		 * @return string
		 */
		public static function get_qr_bbl_image() {
			return self::get_image( 'icon-bank/IconBank_BBL.png', 'BBL' , '25px', null);
        }
        
        /**
		 * Return the default setting of display the TBank logo
		 *
		 * @return string
		 */
		public static function get_tbank_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of TBank logo
		 *
		 * @return string
		 */
		public static function get_tbank_image() {
			return self::get_image( 'icon-bank/IconBank_TBANK2.png', 'TBank' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of TBank logo
		 *
		 * @return string
		 */
		public static function get_qr_tbank_image() {
			return self::get_image( 'icon-bank/IconBank_TBANK.png', 'TBank' , '25px', null);
		}

		/**
		 * Return the default setting of display the TTB logo
		 *
		 * @return string
		 */
		public static function get_ttb_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of TTB logo
		 *
		 * @return string
		 */
		public static function get_ttb_image() {
			return self::get_image( 'icon-bank/IconBank_TTB2.png', 'TTB' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of TTB logo
		 *
		 * @return string
		 */
		public static function get_qr_ttb_image() {
			return self::get_image( 'icon-bank/IconBank_TTB.png', 'TTB' , '25px', null);
		}
		
		/**
		 * Return the default setting of display the KBank logo
		 *
		 * @return string
		 */
		public static function get_kbank_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of KBank logo
		 *
		 * @return string
		 */
		public static function get_kbank_image() {
			return self::get_image( 'icon-bank/IconBank_KBANK2.png', 'KBank' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of KBank logo
		 *
		 * @return string
		 */
		public static function get_qr_kbank_image() {
			return self::get_image( 'icon-bank/IconBank_KBANK.png', 'KBank' , '25px', null);
		}

		/**
		 * Return the default setting of display the KTC logo
		 *
		 * @return string
		 */
		public static function get_installment_ktc_flexi_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of KTC logo
		 *
		 * @return string
		 */
		public static function get_ktc_image() {
			return self::get_image( 'icon-bank/IconBank_KTC2.png', 'KTC' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of KTC-Flexi logo
		 *
		 * @return string
		 */
		public static function get_installment_ktc_flexi_image() {
			return self::get_image( 'icon-bank/IconBank_KTC-Flexi2.png', 'KTC-Flexi', '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of KTC logo
		 *
		 * @return string
		 */
		public static function get_qr_ktc_image() {
			return self::get_image( 'icon-bank/IconBank_KTC.png', 'KTC' , '25px', null);
		}
        
        /**
		 * Return the default setting of display the TMB logo
		 *
		 * @return string
		 */
		public static function get_tmb_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of TMB logo
		 *
		 * @return string
		 */
		public static function get_tmb_image() {
			return self::get_image( 'icon-bank/IconBank_TMB2.png', 'TMB' , '38px', null);
		}

		/**
		 * Return the HTML &lt;img&gt; element of TMB logo
		 *
		 * @return string
		 */
		public static function get_qr_tmb_image() {
			return self::get_image( 'icon-bank/IconBank_TMB.png', 'TMB' , '25px', null);
        }
        
        /**
		 * Return the default setting of display the GSB logo
		 *
		 * @return string
		 */
		public static function get_gsb_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of GSB logo
		 *
		 * @return string
		 */
		public static function get_qr_gsb_image() {
			return self::get_image( 'icon-bank/IconBank_GSB.png', 'GSB' , '25px', null);
        }
        
        /**
		 * Return the default setting of display the UOB logo
		 *
		 * @return string
		 */
		public static function get_uob_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of UOB logo
		 *
		 * @return string
		 */
		public static function get_qr_uob_image() {
			return self::get_image( 'icon-bank/IconBank_UOB.png', 'UOB' , '25px', null);
        }
        
        /**
		 * Return the default setting of display the K Plus logo
		 *
		 * @return string
		 */
		public static function get_kplus_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of K Plus logo
		 *
		 * @return string
		 */
		public static function get_kplus_image() {
			return self::get_image( 'icon-bank/IconBank_KPLUS2.png', 'K Plus' , '38px', null);
        }
        
        /**
		 * Return the HTML &lt;img&gt; element of K Plus logo
		 *
		 * @return string
		 */
		public static function get_qr_kplus_image() {
			return self::get_image( 'icon-bank/IconBank_KPLUS.png', 'K Plus' , '25px', null);
		}

        #endregion

		#region set image Mobile Banking

		/**
		 * Return the default setting of display the SCB Easy logo
		 *
		 * @return string
		 */
		public static function get_scb_easy_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of SCB Easy logo
		 *
		 * @return string
		 */
		public static function get_scb_easy_image() {
			return self::get_image( 'icon-mobile-banking/IconMobile_SCB_EASY2.png', 'SCB Easy' , '38px', null);
        }

		/**
		 * Return the default setting of display the KMA logo
		 *
		 * @return string
		 */
		public static function get_kma_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of KMA logo
		 *
		 * @return string
		 */
		public static function get_kma_image() {
			return self::get_image( 'icon-bank/IconBank_BAY2.png', 'KMA' , '38px', null);
        }

		/**
		 * Return the default setting of display the Bualuang mBanking logo
		 *
		 * @return string
		 */
		public static function get_bbl_mbanking_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Bualuang mBanking logo
		 *
		 * @return string
		 */
		public static function get_bbl_mbanking_image() {
			return self::get_image( 'icon-bank/IconBank_BBL2.png', 'Bualuang mBanking' , '38px', null);
        }

		/**
		 * Return the default setting of display the Krungthai NEXT logo
		 *
		 * @return string
		 */
		public static function get_krungthai_next_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Krungthai NEXT logo
		 *
		 * @return string
		 */
		public static function get_krungthai_next_image() {
			return self::get_image( 'icon-bank/IconBank_KTB2.png', 'Krungthai Next' , '38px', null);
        }

		#endregion

        #region set image Alipay / Wechat Pay

        /**
		 * Return the default setting of display the Alipay logo
		 *
		 * @return string
		 */
		public static function get_alipay_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Alipay logo
		 *
		 * @return string
		 */
		public static function get_alipay_image() {
			return self::get_image( 'icon-alipay-wechat/Icon_Alipay.png', 'Alipay' , '38px', null);
        }
        
        /**
		 * Return the default setting of display the WeChatPay logo
		 *
		 * @return string
		 */
		public static function get_wechatpay_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of WeChatPay logo
		 *
		 * @return string
		 */
		public static function get_wechatpay_image() {
			return self::get_image( 'icon-alipay-wechat/Icon_WeChatPay.png', 'WeChatPay' , '38px', null);
		}

        #endregion

        #region set image eWallet

        /**
		 * Return the default setting of display the BluePay logo
		 *
		 * @return string
		 */
		public static function get_bluepay_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of BluePay logo
		 *
		 * @return string
		 */
		public static function get_bluepay_image() {
			return self::get_image( 'icon-epayment/IconEPayment_BluePay.png', 'BluePay' , '38px', null);
        }
        
        /**
		 * Return the default setting of display the Rabbit LINE Pay logo
		 *
		 * @return string
		 */
		public static function get_rabbit_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Rabbit LINE Pay logo
		 *
		 * @return string
		 */
		public static function get_rabbit_image() {
			return self::get_image( 'icon-line-pay/IconLinePay_RabbitLinePay.png', 'Rabbit LINE Pay' , '38px', null);
        }
        
        /**
		 * Return the default setting of display the TrueMoney Wallet logo
		 *
		 * @return string
		 */
		public static function get_true_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of TrueMoney Wallet logo
		 *
		 * @return string
		 */
		public static function get_true_image() {
			return self::get_image( 'icon-epayment/IconEPayment_TrueMoney.png', 'Rabbit LINE Pay' , '38px', null);
		}

		/**
		 * Return the default setting of display the ShopeePay logo
		 *
		 * @return string
		 */
		public static function get_shopeepay_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of ShopeePay logo
		 *
		 * @return string
		 */
		public static function get_shopeepay_image() {
			return self::get_image( 'icon-epayment/Icon_ShopeePay.png', 'ShopeePay' , '38px', null);
		}

        #endregion

        #region set image Billpayment

        /**
		 * Return the default setting of display the CenPay logo
		 *
		 * @return string
		 */
		public static function get_cenpay_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of CenPay logo
		 *
		 * @return string
		 */
		public static function get_cenpay_image() {
			return self::get_image( 'icon-bill-payment/IconCounterBillPayment_CenPay.png', 'CenPay' , '38px', null);
		}

		/**
		 * Return the default setting of display the Big C logo
		 *
		 * @return string
		 */
		public static function get_bigc_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Big C logo
		 *
		 * @return string
		 */
		public static function get_bigc_image() {
			return self::get_image( 'icon-bill-payment/IconCounterBillPayment_BigC.png', 'Big C' , '38px', null);
		}

		/**
		 * Return the default setting of display the Counter Bill Payment logo
		 *
		 * @return string
		 */
		public static function get_counter_bill_payment_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Counter Bill Payment logo
		 *
		 * @return string
		 */
		public static function get_counter_bill_payment_image() {
			return self::get_image( 'icon-bill-payment/IconCounterBillPayment.png', 'Counter Bill Payment' , '38px', null);
		}

        #endregion

        #region set image kiosk machine

        /**
		 * Return the default setting of display the Boonterm logo
		 *
		 * @return string
		 */
		public static function get_boonterm_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Boonterm logo
		 *
		 * @return string
		 */
		public static function get_boonterm_image() {
			return self::get_image( 'icon-kiosk-machine/IconKiosk_Boonterm.png', 'Boonterm' , '38px', null);
		}

        #endregion

		#region set image pay with point

        /**
		 * Return the default setting of display the KTC Forever logo
		 *
		 * @return string
		 */
		public static function get_point_ktc_forever_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of KTC Forever logo
		 *
		 * @return string
		 */
		public static function get_point_ktc_forever_image() {
			return self::get_image( 'icon-pay-with-points/IconPayWithPoints_KTC-Forever.png', 'KTC Forever' , '38px', null);
		}

		#endregion

		#region set image installment

		/**
		 * Return the default setting of display the Krungsri Consumer logo
		 *
		 * @return string
		 */
		public static function get_krungsri_consumer_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Krungsri Consumer logo
		 *
		 * @return string
		 */
		public static function get_krungsri_consumer_image() {
			return self::get_image( 'icon-installment/IconInstallment_Krusri_Consumer.png', 'Krungsri Consumer' , '38px', null);
		}

		/**
		 * Return the default setting of display the Krungsri First Choice logo
		 *
		 * @return string
		 */
		public static function get_krungsri_first_choice_default_display() {
			return 'yes';
		}

		/**
		 * Return the HTML &lt;img&gt; element of Krungsri First Choice logo
		 *
		 * @return string
		 */
		public static function get_krungsri_first_choice_image() {
			return self::get_image( 'icon-installment/IconInstallment_FirstChoice.png', 'Krungsri First Choice' , '38px', null);
		}

        #endregion	

        #region check enabled credit card

        /**
		 * Check whether the setting for MasterCard logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_mastercard_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the MasterCard logo is display by default.
			if ( ! isset( $setting['accept_mastercard'] ) ) {
				return self::get_mastercard_default_display();
			}

			if ( $setting['accept_mastercard'] == 'yes' ) {
				return true;
			}

			return false;
        }
        
        /**
		 * Check whether the setting for Visa logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_visa_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Visa logo is display by default.
			if ( ! isset( $setting['accept_visa'] ) ) {
				return self::get_visa_default_display();
			}

			if ( $setting['accept_visa'] == 'yes' ) {
				return true;
			}

			return false;
        }
        
        /**
		 * Check whether the setting for JCB logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_jcb_enabled( $setting ) {
			if ( isset( $setting['accept_jcb'] ) && $setting['accept_jcb'] == 'yes' ) {
				return true;
			}

			return false;
        }

		/**
		 * Check whether the setting for Union Pay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_creditcard_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the VISA, MASTER, JCB logo is display by default.
			if ( ! isset( $setting['accept_creditcard'] ) ) {
				return self::get_visa_default_display().''.self::get_mastercard_default_display().''.self::get_jcb_default_display();
			}

			if ( $setting['accept_creditcard'] == 'yes' ) {
				return true;
			}

			return false;
		}
        
        /**
		 * Check whether the setting for Union Pay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_unionpay_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Union Pay logo is display by default.
			if ( ! isset( $setting['accept_unionpay'] ) ) {
				return self::get_unionpay_default_display();
			}

			if ( $setting['accept_unionpay'] == 'yes' ) {
				return true;
			}

			return false;
		}

        #endregion

        #region check enabled bank

        /**
		 * Check whether the setting for SCB logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_scb_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the SCB logo is display by default.
			if ( ! isset( $setting['accept_scb'] ) ) {
				return self::get_scb_default_display();
			}

			if ( $setting['accept_scb'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for KTB logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_ktb_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the KTB logo is display by default.
			if ( ! isset( $setting['accept_ktb'] ) ) {
				return self::get_ktb_default_display();
			}

			if ( $setting['accept_ktb'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for BAY logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_bay_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the BAY logo is display by default.
			if ( ! isset( $setting['accept_bay'] ) ) {
				return self::get_bay_default_display();
			}

			if ( $setting['accept_bay'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for BBL logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_bbl_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the BBL logo is display by default.
			if ( ! isset( $setting['accept_bbl'] ) ) {
				return self::get_bbl_default_display();
			}

			if ( $setting['accept_bbl'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for TBank logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_tbank_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the TBank logo is display by default.
			if ( ! isset( $setting['accept_ttb'] ) ) {
				return self::get_tbank_default_display();
			}

			if ( $setting['accept_ttb'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for TMB logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_tmb_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the TMB logo is display by default.
			if ( ! isset( $setting['accept_tmb'] ) ) {
				return self::get_tmb_default_display();
			}

			if ( $setting['accept_tmb'] == 'yes' ) {
				return true;
			}

			return false;
		}		

        #endregion

		#region check enabled mobile banking

		/**
		 * Check whether the setting for K Plus logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_kplus_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the K Plus logo is display by default.
			if ( ! isset( $setting['accept_kplus'] ) ) {
				return self::get_kplus_default_display();
			}

			if ( $setting['accept_kplus'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for SCB Easy logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_scb_easy_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the SCB Easy logo is display by default.
			if ( ! isset( $setting['accept_scb_easy'] ) ) {
				return self::get_scb_easy_default_display();
			}

			if ( $setting['accept_scb_easy'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for KMA logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_kma_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the KMA logo is display by default.
			if ( ! isset( $setting['accept_kma'] ) ) {
				return self::get_kma_default_display();
			}

			if ( $setting['accept_kma'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for Bualuang mBanking logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_bbl_mbanking_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Bualuang mBanking logo is display by default.
			if ( ! isset( $setting['accept_bbl_mbanking'] ) ) {
				return self::get_bbl_mbanking_default_display();
			}

			if ( $setting['accept_bbl_mbanking'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for Krungthai NEXT logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_krungthai_next_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Krungthai NEXT logo is display by default.
			if ( ! isset( $setting['accept_krungthai_next'] ) ) {
				return self::get_krungthai_next_default_display();
			}

			if ( $setting['accept_krungthai_next'] == 'yes' ) {
				return true;
			}

			return false;
		}

		#endregion

        #region check enabled Alipay / WeChatPay

        /**
		 * Check whether the setting for Alipay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_alipay_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Alipay logo is display by default.
			if ( ! isset( $setting['accept_alipay'] ) ) {
				return self::get_alipay_default_display();
			}

			if ( $setting['accept_alipay'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for WeChatPay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_wechatpay_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the WeChatPay logo is display by default.
			if ( ! isset( $setting['accept_wechatpay'] ) ) {
				return self::get_wechatpay_default_display();
			}

			if ( $setting['accept_wechatpay'] == 'yes' ) {
				return true;
			}

			return false;
		}

        #endregion

        #region check enabled eWallet

        /**
		 * Check whether the setting for BluePay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_bluepay_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the BluePay logo is display by default.
			if ( ! isset( $setting['accept_bluepay'] ) ) {
				return self::get_bluepay_default_display();
			}

			if ( $setting['accept_bluepay'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for Rabbit LINE Pay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_rabbit_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Rabbit LINE Pay logo is display by default.
			if ( ! isset( $setting['accept_rabbit'] ) ) {
				return self::get_rabbit_default_display();
			}

			if ( $setting['accept_rabbit'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for TrueMoney Wallet logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_true_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the TrueMoney Wallet logo is display by default.
			if ( ! isset( $setting['accept_true'] ) ) {
				return self::get_true_default_display();
			}

			if ( $setting['accept_true'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for ShopeePay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_shopeepay_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the ShopeePay logo is display by default.
			if ( ! isset( $setting['accept_shopeepay'] ) ) {
				return self::get_shopeepay_default_display();
			}

			if ( $setting['accept_shopeepay'] == 'yes' ) {
				return true;
			}

			return false;
		}

        #endregion

        #region check enabled Billpayment

        /**
		 * Check whether the setting for Big C logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_bigc_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Big C logo is display by default.
			if ( ! isset( $setting['accept_bigc'] ) ) {
				return self::get_bigc_default_display();
			}

			if ( $setting['accept_bigc'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for CenPay logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_cenpay_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the CenPay logo is display by default.
			if ( ! isset( $setting['accept_cenpay'] ) ) {
				return self::get_cenpay_default_display();
			}

			if ( $setting['accept_cenpay'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for Counter Bill Payment logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_counter_bill_payment_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Counter Bill Payment logo is display by default.
			if ( ! isset( $setting['accept_counter_bill_payment'] ) ) {
				return self::get_counter_bill_payment_default_display();
			}

			if ( $setting['accept_counter_bill_payment'] == 'yes' ) {
				return true;
			}

			return false;
		}

        #endregion

        #region check enabled kiosk machine

		/**
		 * Check whether the setting for Boonterm logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_boonterm_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Boonterm logo is display by default.
			if ( ! isset( $setting['accept_boonterm'] ) ) {
				return self::get_boonterm_default_display();
			}

			if ( $setting['accept_boonterm'] == 'yes' ) {
				return true;
			}

			return false;
		}

		#endregion
		
		/**
		 * Check whether the setting for Installment KBANK logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_installment_kbank_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Installment KBANK logo is display by default.
			if ( ! isset( $setting['accept_installment_kbank'] ) ) {
				return self::get_kbank_default_display();
			}

			if ( $setting['accept_installment_kbank'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for Installment SCB logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_installment_scb_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Installment SCB logo is display by default.
			if ( ! isset( $setting['accept_installment_scb'] ) ) {
				return self::get_scb_default_display();
			}

			if ( $setting['accept_installment_scb'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for Installment Krungsri Consumer logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_installment_krungsri_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Installment Krungsri Consumer logo is display by default.
			if ( ! isset( $setting['accept_installment_krungsri'] ) ) {
				return self::get_krungsri_consumer_default_display();
			}

			if ( $setting['accept_installment_krungsri'] == 'yes' ) {
				return true;
			}

			return false;
		}

		/**
		 * Check whether the setting for Installment Krungsri First Choice logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_installment_firstchoice_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Installment Krungsri First Choice logo is display by default.
			if ( ! isset( $setting['accept_installment_firstchoice'] ) ) {
				return self::get_krungsri_first_choice_default_display();
			}

			if ( $setting['accept_installment_firstchoice'] == 'yes' ) {
				return true;
			}

			return false;
		}
		
		/**
		 * Check whether the setting for Installment TBANK logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_installment_tbank_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Installment TBANK logo is display by default.
			if ( ! isset( $setting['accept_installment_tbank'] ) ) {
				return self::get_tbank_default_display();
			}

			if ( $setting['accept_installment_tbank'] == 'yes' ) {
				return true;
			}

			return false;
		}
		
		/**
		 * Check whether the setting for Installment KTC logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_installment_ktc_flexi_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Installment KTC logo is display by default.
			if ( ! isset( $setting['accept_installment_ktc_flexi'] ) ) {
				return self::get_installment_ktc_flexi_default_display();
			}

			if ( $setting['accept_installment_ktc_flexi'] == 'yes' ) {
				return true;
			}

			return false;
        }
        

		#region check enabled pay with point

		/**
		 * Check whether the setting for Pay With Points KTC logo is configured and it was set to display or not display
		 *
		 * @param mixed $setting The array that contains key for checking the flag
		 * @return boolean
		 */
		public static function is_point_ktc_forever_enabled( $setting ) {
			// Make it backward compatible. If the setting is not configured, the Pay With Points KTC logo is display by default.
			if ( ! isset( $setting['accept_point_ktc_forever'] ) ) {
				return self::get_point_ktc_forever_default_display();
			}

			if ( $setting['accept_point_ktc_forever'] == 'yes' ) {
				return true;
			}

			return false;
        }

		#endregion

    }
}