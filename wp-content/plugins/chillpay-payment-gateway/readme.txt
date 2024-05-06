=== ChillPay WooCommerce ===
Contributors: chillpay01
Tags: chillpay, payment, payment gateway, woocommerce, woocommerce plugin
Requires at least: 4.3.1
Tested up to: 6.4.3
Stable tag: 2.5.1
License: MIT
License URI: https://opensource.org/licenses/MIT

ChillPay WooCommerce payment gateway plugin primarily supports your WooCommerce, enables you to accept payments via Credit and Debit cards, Internet Banking and alternative payment methods with real-time payment reports to help you deliver products & services to customers instantly. Our payment gateway is a White Label providing service under your branding. Moreover, merchant can get paid fast in only one business day. For more information, please see details at https://www.chillpay.co

== Description ==

The ChillPay WooCommerce plugin lets you accept credit cards and more with real-time reports. Get paid fast under your own branding. www.chillpay.co

== Installation ==

After getting the source code, either downloading as a zip from ChillPay 
1. Activate the plugin.
2. Go to menu ChillPay.
3. Select mode and fill out the information to the selected mode.
4. For payment method, select 'Setting' to activate your desired payment channels.
5. Copy URL Background and URL Result and paste to ChillPay backend website following the type of your activated payment method.

== Screenshots ==

1. ChillPay Payment Gateway Dashboard
2. ChillPay Payment Gateway Setting Page
3. ChillPay Payment Gateway Checkout Form

== Changelog ==

= 2.5.1 = 

#### 🛠️ Enhancements

- Update ChillPay logo

= 2.5.0 =

#### 🌟 Highlights

- Support Bill Payment (Counter Bill Payment)
- Support Mobile Banking Payment (Krungthai NEXT)

= 2.4.0 =

#### 🌟 Highlights

- Support Credit Card Payment (Union Pay)
- Support Mobile Banking Payment (Bualuang mBanking)

#### 🛠️ Enhancements

- Change channel code TBANK to TTB.
- Code Refactoring, auto select payment channel.

= 2.3.0 =

#### 🌟 Highlights

- Support Installment Payment (SCB).
- Support Installment Payment (Krungsri Consumer).
- Support Installment Payment (Krungsri First Choice).
- Support Mobile Banking Payment (KMA App).

#### 🛠️ Enhancements

- URL Background and URL Result

= 2.2.0 =

#### 🌟 Highlights

- Support e-Wallet Payment (ShopeePay).
- Support Mobile Banking Payment (SCB Easy App).

#### 🛠️ Enhancements

- Disable payment channel in case the channel is not available.
- Update the error message in case the channel is not available.

#### 🐞 Bug Fixes

- Fix getting data of bill payment.

= 2.1.1 =

#### 🛠️ Enhancements

- Support phone number with country code (Thailand)

= 2.1.0 =

#### 🌟 Highlights

- Support Installment Payment (KTC Flexi).
- Support Pay With Points Payment (KTC Forever).


#### 🛠️ Enhancements

- Change logo TBANK to TTB.

= 2.0.0 = 

#### 🌟 Highlights

- Support Installment Payment (KBANK).

#### 🛠️ Enhancements

- Using CURL Instead of HTTP API
- Code Refactoring, simplifying Callback and Result function.
- Code Refactoring, simplifying ChillPay setting process.
- ChillPay Setting Page, sanitizing input fields before save.
- Added label to show mode on setting page

= 1.8.0 =

#### 🛠️ Enhancements

- Add an enabled button to create new order in case of failed status 

#### 🐞 Bug Fixes

- Adjust Updating Process for Order Status

= 1.7.1 =

#### 🌟 Highlights

- Support custom incrementing order numbers for WooCommerce orders.

#### 🛠️ Enhancements

- Auto check when there is one channel 

#### 🐞 Bug Fixes

- Adjust Updating Process for Order Status

= 1.7.0 =

#### 🌟 Highlights

- Support Alipay Payment.
- Support WeChat Pay Payment.

= 1.6.0 =

#### 🌟 Highlights

- Add menu ChillPay : Manual sync payment status.
- Add process auto sync payment status.

= 1.5.9 =

#### 🌟 Highlights

- Support multi currency
- Support Bill Payment.
- Show payment methods as informed to ChillPay.

#### 🛠️ Enhancements

- Separate Mobile Banking from the Internet Banking.
- Add URL Background (callback function) to use for sending payment results to the system.
- Improve the UI of the payment methods.
- Use Mode selection instead of copying the api url.

= 1.5.8 =

#### 🐞 Bug Fixes

- Error when user enter the mobile number that is not connected to the K PLUS.

= 1.5.7 =

#### 🐞 Bug Fixes

- Fix customer information retrieval.

#### 🛠️ Enhancements

- Add input text api url.