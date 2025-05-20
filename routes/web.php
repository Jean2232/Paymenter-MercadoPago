<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Paymenter\Extensions\Gateways\MercadoPago\MercadoPago;

Route::post('/mercadopago/webhook', [MercadoPago::class, 'webhook'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('extensions.gateways.mercadopago.webhook');

Route::get('/gateway/mercadopago/success/{invoice}', function (Invoice $invoice) {
    return redirect()->route('invoices.show', ['invoice' => $invoice->id])
        ->with('success', 'Pagamento confirmado com sucesso!');
})->name('mercadopago.success');

Route::get('/gateway/mercadopago/failure/{invoice}', function (Invoice $invoice) {
    return redirect()->route('invoices.show', ['invoice' => $invoice->id])
        ->with('error', 'O pagamento falhou. Tente novamente.');
})->name('mercadopago.failure');

Route::get('/gateway/mercadopago/pending/{invoice}', function (Invoice $invoice) {
    return redirect()->route('invoices.show', ['invoice' => $invoice->id])
        ->with('warning', 'O pagamento está pendente. Aguardando confirmação.');
})->name('mercadopago.pending');

