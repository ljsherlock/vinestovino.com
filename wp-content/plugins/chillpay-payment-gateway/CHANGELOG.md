# CHANGELOG

### 'V2.5.1 (Feb 29, 2024)'

#### 🛠️ Enhancements

- Update ChillPay logo

### 'V2.5.0 (Sep 6, 2023)'

#### 🌟 Highlights

- Support Bill Payment (Counter Bill Payment)
- Support Mobile Banking Payment (Krungthai NEXT)

#### 🛠️ Enhancements

- ChillPay has now been tested up to WordPress 6.3.x.
- Remove ill Payment (Big C)

---

### 'V2.4.0 (Aug 2, 2022)'

#### 🌟 Highlights

- Support Credit Card Payment (Union Pay)
- Support Mobile Banking Payment (Bualuang mBanking)

#### 🛠️ Enhancements

- Change channel code TBANK to TTB.
- Code Refactoring, auto select payment channel.

---

### 'V2.3.0 (Mar 15, 2022)'

#### 🌟 Highlights

- Support Installment Payment (SCB).
- Support Installment Payment (Krungsri Consumer).
- Support Installment Payment (Krungsri First Choice).
- Support Mobile Banking (KMA App).

#### 🛠️ Enhancements

- URL Background and URL Result
- Support phone number with country code

---

### 'v2.2.0 (Sep 30, 2021)'

#### 🌟 Highlights

- Support e-Wallet Payment (ShopeePay).
- Support Mobile Banking Payment (SCB Easy App).

#### 🛠️ Enhancements

- Disable payment channel in case the channel is not available.
- Update the error message in case the channel is not available.

#### 🐞 Bug Fixes

- Fix getting data of bill payment.

---

### 'v2.1.1 (Aug 25, 2021)'

#### 🛠️ Enhancements

- Support phone number with country code (Thailand)

---

### 'v2.1.0 (Jun 21, 2021)'

#### 🌟 Highlights

- Support Installment Payment (KTC Flexi).
- Support Pay With Points Payment (KTC Forever).

#### 🛠️ Enhancements

- Change logo TBANK to TTB.

---

### 'v2.0.0 (Nov 2, 2020)'

#### 🌟 Highlights

- Support Installment Payment (KBANK).

#### 🛠️ Enhancements

- Using CURL Instead of HTTP API.
- Code Refactoring, simplifying Callback and Result function.
- Code Refactoring, simplifying ChillPay setting process.
- ChillPay Setting Page, sanitizing input fields before save.
- Added label to show mode on setting page.

---

### 'v1.8.0 (Aug 14, 2020)'

#### 🛠️ Enhancements

- Add an enabled button to create new order in case of failed status.

#### 🐞 Bug Fixes

- Adjust Updating Process for Order Status.

---

### 'v1.7.1 (May 7, 2020)'

#### 🛠️ Enhancements

- Auto check when there is one channel.
- Support custom incrementing order numbers for WooCommerce orders.

#### 🐞 Bug Fixes

- Adjust Updating Process for Order Status.

---

### 'v1.7.0 (2020)'

#### 🌟 Highlights

- Support Alipay Payment.
- Support WeChat Pay Payment.
- Split Input Text on SandBox/PROD Mode

---

### 'v1.6.0 (Jan 7, 2020)'

#### 🌟 Highlights

- Add menu ChillPay : Manual sync payment status
- Add process auto sync payment status

---

### 'v1.5.9 (Dec 26, 2019)'

#### 🌟 Highlights

- Support multi currency.
- Support Bill Payment.
- Show payment methods as informed to ChillPay.

#### 🛠️ Enhancements

- Separate Mobile Banking from the Internet Banking.
- Add URL Background (callback function) to use for sending payment results to the system.
- Improve the UI of the payment methods.
- Use Mode selection instead of copying the api url.

---

### 'v1.5.8 (Aug 20, 2019)'

#### 🐞 Bug Fixes

- Error when user enter the mobile number that is not connected to the K PLUS.

---

### 'v1.5.7 (May 28, 2019)'

#### 🐞 Bug Fixes

- Fix customer information retrieval.

#### 🛠️ Enhancements

- Add input text api url.
