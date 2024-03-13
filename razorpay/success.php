<?php 
require 'config.php';
session_start();

if (!empty($_POST)) {
    $order_id = $_SESSION['order_id'];

    // response from razorpay
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];
    $razorpay_payment_id = $_POST['razorpay_payment_id'];

    $genarated_signature = hash_hmac('sha256',$order_id."|".$razorpay_payment_id, API_SECRET);

    if ($genarated_signature == $razorpay_signature) {
        echo "Payment is successful";
    } else {
        echo "Payment is failed";
    }
}


?>