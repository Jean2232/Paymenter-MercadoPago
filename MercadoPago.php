<?php

namespace Paymenter\Extensions\Gateways\MercadoPago;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use App\Models\Invoice;
use App\Helpers\ExtensionHelper;
use App\Classes\Extension\Gateway;

class MercadoPago extends Gateway
{
    public function boot()
    {
        require __DIR__ . '/routes/web.php';
        View::addNamespace('mercadopago', __DIR__ . '/resources/views');
    }

    public function getConfig($values = [])
    {
        return [
            [
                'name' => 'access_token',
                'label' => 'Access Token',
                'type' => 'text',
                'description' => 'Access Token da sua conta Mercado Pago.',
                'required' => true,
            ],
        ];
    }

    public function pay($invoice, $total)
    {
        $invoice->load('user');
        $email = $invoice->user->email;
        $orderId = $invoice->id;

        $response = Http::withToken($this->config('access_token'))->post('https://api.mercadopago.com/checkout/preferences', [
            'items' => [[
                'title' => 'Fatura #' . $orderId,
                'quantity' => 1,
                'unit_price' => (float) $total,
                'currency_id' => 'BRL'
            ]],
            'payer' => [
                'email' => $email
            ],
            'external_reference' => $orderId,
            'notification_url' => url('/mercadopago/webhook'),
            'back_urls' => [
                'success' => url("/invoices/{$orderId}"),
                'failure' => url("/invoices/{$orderId}"),
                'pending' => url("/invoices/{$orderId}")
            ],
            'auto_return' => 'approved'
        ]);

        $data = $response->json();

        if (!isset($data['init_point'])) {
            Log::error('Erro ao criar preferência no MercadoPago', $data);
            abort(500, 'Erro ao criar link de pagamento.');
        }

        return redirect($data['init_point']);
    }

    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::debug('[MercadoPago] Webhook recebido:', $payload);

        $paymentId = data_get($payload, 'data.id');

        if (!$paymentId) {
            return response()->json(['error' => 'ID de pagamento não encontrado'], 400);
        }

        $response = Http::withToken($this->config('access_token'))
            ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

        if (!$response->ok()) {
            Log::error('[MercadoPago] Erro ao consultar pagamento', [
                'payment_id' => $paymentId,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json(['error' => 'Erro ao consultar o pagamento'], 500);
        }

        $payment = $response->json();

        if (($payment['status'] ?? null) === 'approved') {
            $invoiceId = $payment['external_reference'] ?? null;
            if ($invoiceId && $invoice = Invoice::find($invoiceId)) {
                ExtensionHelper::addPayment(
                    $invoiceId,
                    'MercadoPago',
                    $payment['transaction_amount'],
                    $payment['fee_details'][0]['amount'] ?? 0,
                    $payment['id']
                );
                Log::info("[MercadoPago] Fatura #{$invoiceId} marcada como paga via webhook.");
            } else {
                Log::warning("[MercadoPago] Fatura não encontrada para external_reference: {$invoiceId}");
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
