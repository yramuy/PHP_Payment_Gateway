<?php

$data = [
    "merchantId" => "PGTESTPAYUAT",
    "merchantTransactionId" => "MT7850590068188104",
    "merchantUserId" => "MUID123",
    "amount" => 100,
    "redirectUrl" => "http://localhost/PHP/phonepe_payment_gateway/redirect-url.php",
    "redirectMode" => "POST",
    "callbackUrl" => "http://localhost/PHP/phonepe_payment_gateway/callback-url.php",
    "mobileNumber" => "9999999999",
    "paymentInstrument" => [
        "type" => "PAY_PAGE"
    ]
];

$key = '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399'; // KEY
$key_index = 1; // KEY_INDEX

$encode = json_encode($data);
$encoded = base64_encode($encode);

$string = $encoded . "/pg/v1/pay" . $key;
$sha256 = hash("sha256", $string);
$final_x_header = $sha256 . '###' . $key_index;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay');
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
        "Content-Type: application/json",
        "accept: application/json",
        "X-VERIFY: " . $final_x_header,
    )
);
curl_setopt($ch, CURLOPT_POST, 1);
$data1 = json_encode(['request' => $encoded]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$resp = curl_exec($ch);
$response = json_decode($resp, true);



// $url = "https://api.phonepe.com/apis/hermes/pg/v1/pay"; <PRODUCTION URL>

// $url = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay"; // <TESTING URL>

// $headers = array(
//     "Content-Type: application/json",
//     "accept: application/json",
//     "X-VERIFY: " . $final_x_header,
// );

// $data = json_encode(['request' => $encoded]);

// $curl = curl_init($url);

// curl_setopt($curl, CURLOPT_URL, $url);
// curl_setopt($curl, CURLOPT_POST, true);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
// curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

// $resp = curl_exec($curl);

// curl_close($curl);

// $response = json_decode($resp, true);

echo '<pre>';
print_r($response);
echo '</pre>';

// header('Location:' . $response['data']['instrumentResponse']['redirectInfo']['url']);
// exit;