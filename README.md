# 💳 Mercado Pago Gateway for Paymenter

Extensão para integrar o **Mercado Pago** como método de pagamento no painel **Paymenter**. Permite pagamentos via **Pix, boleto, cartão de crédito** e saldo da conta Mercado Pago.

---

## 📂 Instalação

1. Faça upload da pasta `mercadopago` para o seguinte caminho no seu projeto:
   ```
   modules/extensions/mercadopago/
   ```

2. No painel admin do Paymenter:
   - Vá em **Admin > Gateways**
   - Clique em **Novo gateway**
   - Selecione **Mercado Pago**

3. Informe o **Access Token** da sua conta Mercado Pago (veja abaixo como obter).

> ⚠️ Apenas credenciais de PRODUÇÃO são suportadas nesta versão.

---

## 🔐 Como obter seu Access Token

1. Acesse o painel de credenciais:
   [https://www.mercadopago.com.br/developers/panel/credentials](https://www.mercadopago.com.br/developers/panel/credentials)

2. Copie o **Access Token de PRODUÇÃO**, algo como:
   ```
   APP_USR-XXXXXXXXXXXXXXXXXXXXXXXXXXXX
   ```

3. Cole esse token no campo `Access Token` na configuração do gateway no Paymenter.

---

## 🔁 Configurando o Webhook

Para que o Paymenter atualize as faturas automaticamente após o pagamento, é necessário configurar um Webhook no Mercado Pago:

1. Acesse:
   [https://www.mercadopago.com.br/developers/panel/webhooks](https://www.mercadopago.com.br/developers/panel/webhooks)

2. Crie um novo webhook com a URL:
   ```
   https://SEU-DOMINIO.com/extensions/mercadopago/webhook
   ```

3. Selecione os seguintes eventos:
   - `payment`
   - (opcional) `merchant_order`

4. Salve.

---

## 🧪 Testes

1. Gere uma fatura no Paymenter
2. Escolha "Mercado Pago" como forma de pagamento
3. Finalize o pagamento usando qualquer método (Pix, cartão, etc.)
4. Aguarde o processamento e verifique se o pagamento foi marcado como **pago** automaticamente

---

## 🛠️ Problemas comuns

- Verifique se o domínio tem **HTTPS ativo** e válido
- Confira os logs em:
  ```
  storage/logs/laravel.log
  ```
- Certifique-se de que o webhook está corretamente configurado e ativo

---

## 📄 Licença

Distribuído livremente sob a [MIT License](LICENSE).

---
