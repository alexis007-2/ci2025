<?php

namespace App\Controllers;


class Payment extends BaseController
{
    private $client;

    public function __construct()
    {
        helper('url');
        $this->client = service('curlrequest');
    }

    public function achatPayPal(): string
    {
        return view('achatPayPal');
    }

    public function payPalTraitement()
    {
        $url1 = site_url('success');
        $url2 = site_url('echec');
        $paypalClientId = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $paypalSecret = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $response = $this->client->request('POST', 'https://api-m.sandbox.paypal.com/v1/oauth2/token', [
            'auth' => [$paypalClientId, $paypalSecret],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $accessToken = $data['access_token'];
        $response = $this->client->request('POST', 'https://api-m.sandbox.paypal.com/v2/checkout/orders', [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => '50.00'
                    ]
                ]],
                'application_context' => [
                    'return_url' => $url1,
                    'cancel_url' => $url2
                ]
            ]
        ]);

        $order = json_decode($response->getBody(), true);

        // Rediriger l'utilisateur vers PayPal
        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect()->to($link['href']);
            }
        }
    }


    public function success(): string
    {
        return view('success');
    }


    public function echec(): string
    {
        return view('echec');
    }

    public function achatStripe(): string
    {
        return view('achatStripe');
    }

    public function stripeTraitement()
    {
        $url2 = site_url('echec');
        $stripeSecretKey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $response = $this->client->request('POST', 'https://api.stripe.com/v1/checkout/sessions',  [
            'headers' => [
                'Authorization' => 'Bearer ' . $stripeSecretKey,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'payment_method_types[]' => 'card',
                'mode' => 'payment',
                'success_url' => site_url('success?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => site_url('echec'),
                'line_items[0][price_data][currency]' => 'eur',
                'line_items[0][price_data][product_data][name]' => 'Formation PHP Guzzle',
                'line_items[0][price_data][unit_amount]' => 5000,
                'line_items[0][quantity]' => 1,
            ],
        ]);

        $session = json_decode($response->getBody(), true);

        // Rediriger vers l'URL Stripe Checkout
        return redirect()->to($session['url']);
    }
}
