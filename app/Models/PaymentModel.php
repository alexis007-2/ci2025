<?php
namespace App\Models;
use CodeIgniter\Model;

class PaymentModel extends Model
{
    private $client;

    public function __construct()
    {
        $this->client = service('curlrequest');
    }

    public function allProduct():array
    {
    $paypalClientId ='AeeBKCqKhQw20F8YCvQVTiCrfOEifpm78rCB3fBFetVBRRoCRKGQiyJSjYdNXWV3mte8SY1K0sa0XKu9'; 
    $paypalSecret ='EIlRkbXZ6v9YgGqEYFPJ2tPg-dVwGSxdk_m1QI3bU2siv7wNI66PahODm2950G4ipBDMbizvwx0OacnD'; 
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
            'return_url' => 'http://localhost/paypal/ok.php',
            'cancel_url' => 'http://localhost/paypal/echec.php'
        ]
    ]
]);

$order = json_decode($response->getBody(), true);

// Rediriger l'utilisateur vers PayPal
foreach ($order['links'] as $link) {
    if ($link['rel'] === 'approve') {
        header('Location: ' . $link['href']);
        exit;
    }
}



        $products = $this->client->request('GET','https://noframe.dev.accatone.net/api-test/index.php/products',[
            "verify"=>false
        ]);
        $response = $products->getBody();
        $res = json_decode($response);
        return $res;
    }

    public function oneProduct(int $id):object
    {
        $product = $this->client->request('GET','https://noframe.dev.accatone.net/api-test/index.php/products/'.$id,[
            "verify"=>false
        ]);
        $response = $product->getBody();
        $res = json_decode($response);
        return $res;
    }

    public function insertProduct(array $data):int
    {
        $body = json_encode($data);
        $product = $this->client->request('POST','https://noframe.dev.accatone.net/api-test/index.php/products',
        [
            "verify"=>false,
            "body"=>$body
        ]);
        $response = $product->getStatusCode();
        return $response;
    }

    public function updateProduct(array $data,int $id):int
    {
       $body = json_encode($data);
       $product = $this->client->request('PATCH','https://noframe.dev.accatone.net/api-test/index.php/products/'.$id,
       [
            "verify"=>false,
            "body"=>$body
       ]);     
    $response = $product->getStatusCode();
    return $response;
    }

    public function deleteProduct(int $id):int
    {
        $product = $this->client->request('DELETE','https://noframe.dev.accatone.net/api-test/index.php/products/'.$id,
        [
            "verify"=>false
        ]);
        $response = $product->getStatusCode();
        return $response;
    }

    public function meteo(float $lat,float $lon):object
    {
        $request = $this->client->request('GET','https://api.openweathermap.org/data/2.5/weather',[
            "verify"=>false,
            "query"=>[
                "lat"=>$lat,
                "lon"=>$lon,
                "units"=>"metric",
                "lang"=>"fr",
                "appid"=>"xxxxxxxxxxx"
            ]
            ]);
            $response = $request->getBody();
            $res = json_decode($response);
            return $res;
    }

    public function movie(int $id):object
    {
        $request = $this->client->request('GET','https://api.themoviedb.org/3/movie/'.$id,[
            "verify"=>false,
            "headers"=>[
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzN2IwODk2MmU0NWQyNThkNDRhZjJkODUyOGQ2NDY4NSIsIm5iZiI6MTUyNTMwMDc2Ni4wNzIsInN1YiI6IjVhZWEzZTFlOTI1MTQxNzJiYjAwMjFhMCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.J-KxP8o260pP89vw5Q5t3OkN_scN_IB_la5DSerSbCk',
                'accept' => 'application/json'
            ],
            "query"=>
            [
                "language"=>"fr-FR"
            ]
            ]);
            $response = $request->getBody();
            $res = json_decode($response);
            return $res;
    }

}