# 💳 [MercadoPago](https://www.mercadopago.com.br/developers/) Gateway for [Paymenter](https://paymenter.org/)

Extension to integrate **MercadoPago** as a payment method on the **Paymenter** panel. Supports payments via **Pix, boleto, credit card**, and Mercado Pago account balance.

---

## 📂 Installation

1. Create a `MercadoPago` folder in the Paymenter extensions directory and clone the repository content into it:
   ```
   mkdir -p var/www/paymenter/extensions/Gateways/MercadoPago && git clone https://github.com/Jean2232/Paymenter-MercadoPago.git var/www/paymenter/extensions/Gateways/MercadoPago
   ```
2. In the Paymenter admin panel:
   - Go to **Admin > Gateways**
   - Click **New gateway**
   - Select **MercadoPago**

3. Enter your **Access Token** from your Mercado Pago account (see below on how to obtain it).

> ⚠️ Only PRODUCTION credentials are supported in this version.

---

## 🔐 How to get your Access Token

1. Go to the credentials panel:  
   [https://www.mercadopago.com.br/developers/panel/credentials](https://www.mercadopago.com.br/developers/panel/credentials)

2. Copy your **PRODUCTION Access Token**, which looks like:
   ```
   APP_USR-XXXXXXXXXXXXXXXXXXXXXXXXXXXX
   ```

3. Paste this token into the `Access Token` field in the gateway settings on Paymenter.

---

## 🔁 Setting up the Webhook

To allow Paymenter to automatically update invoices after payment, you need to configure a Webhook on Mercado Pago:

1. Go to:  
   [https://www.mercadopago.com.br/developers/panel/webhooks](https://www.mercadopago.com.br/developers/panel/webhooks)

2. Create a new webhook with the URL:
   ```
   https://YOUR-DOMAIN.com/extensions/mercadopago/webhook
   ```

3. Select the following events:
   - `payment`
   - (optional) `merchant_order`

4. Save.

---

## 🧪 Testing

1. Generate an invoice in Paymenter  
2. Choose "Mercado Pago" as the payment method  
3. Complete the payment using any method (Pix, credit card, etc.)  
4. Wait for processing and verify if the invoice is automatically marked as **paid**

---

## 🛠️ Common Issues

- Ensure the domain has **active and valid HTTPS**
- Check logs at:
  ```
  storage/logs/laravel.log
  ```
- Make sure the webhook is correctly configured and active

---

## 📄 License

Freely distributed under the [MIT License](LICENSE).
