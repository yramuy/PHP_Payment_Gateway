<?php

if (isset($_POST['merchantId']) && isset($_POST['transactionId']) && isset($_POST['amount'])) {

    // $merchantId = $_POST['merchantId'];
    // $merchantTransactionId = $_POST['transactionId'];
    // $amount = $_POST['amount'];

    // Define PhonePe gateway information
    $gateway = (object) [
        'token' => 'PGTESTPAYUAT',
        'secret_key' => '099eb0cd-02cf-4e2a-8aca-3e6c6aff0399',
    ];

    // Extract transaction ID from POST data
    $orderId = $_POST['transactionId'];

    // Construct X-VERIFY header for status check
    $encodeIn265 = hash('sha256', '/pg/v1/status/' . $gateway->token . '/' . $orderId . $gateway->secret_key) . '###1';

    // Set headers for the status check request
    $headers = [
        'http' => [
            'method' => 'GET',
            'header' => 'Content-Type: application/json' . "\r\n" .
                'X-MERCHANT-ID: ' . $gateway->token . "\r\n" .
                'X-VERIFY: ' . $encodeIn265 . "\r\n" .
                'Accept: application/json'
        ]
    ];

    // Define PhonePe status check URL
    $phonePeStatusUrl = 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/status/' . $gateway->token . '/' . $orderId; // For Development
    // $phonePeStatusUrl = 'https://api.phonepe.com/apis/hermes/pg/v1/status/' . $gateway->token . '/' . $orderId; // For Production

    // Initialize stream context for file_get_contents
    $context = stream_context_create($headers);

    // Make the status check request using file_get_contents
    $response = file_get_contents($phonePeStatusUrl, false, $context);

    // Decode the status check response
    $api_response = json_decode($response);

    // Check if the payment was successful
    if ($api_response->code == "PAYMENT_SUCCESS") {
        // Insert payment details into your database
        print_r($api_response->data);
        // $insert = mysqli_query($conn, "INSERT INTO YOUR_TABLE SET payment_txn_id = '{$api_response->data->merchantTransactionId}', payment_amount = '{$api_response->data->amount}' WHERE id = 'YOUR_REF_ID'");
        // echo "Thank you for your payment. We will contact you shortly!";
    } else {
        // Handle failed transactions
        echo "Transaction Failed";
    }
}
