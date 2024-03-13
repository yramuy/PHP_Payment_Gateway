<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';

use Razorpay\Api\Api;

if (!empty($_POST['amount'])) {

	$name = $_POST['name'];
	$email = $_POST['email'];
	$amount = $_POST['amount'];
	$api = new Api(API_KEY, API_SECRET);

	$res = $api->order->create(
		array(
			'receipt' => '123',
			'amount' => $amount.'00',
			'currency' => 'INR',
			'notes' => array('key1' => 'value1', 'key2' => 'value2')
		)
	);

	if (!empty($res['id'])) {
		$_SESSION['order_id'] = $res['id'];
	}
}
?>

<form action="<?php echo BASE_URL?>success.php" method="POST">

<script 
	src="https://checkout.razorpay.com/v1/checkout.js" 
	data-key="<?php echo API_KEY; ?>" 
	data-amount="<?php echo $amount; ?>"
	data-currency="INR" 
	data-order_id="<?php echo $res['id'] ?>" 
	data-buttontext="Pay <?php echo $amount; ?> with Razorpay" 
	data-name="<?php echo COMPANY_NAME; ?>" 
	data-description="Company Description" 
	data-image="<?php echo COMPONY_LOGO_URL; ?>"
	data-prefill.name="<?php echo $name; ?>" 
	data-prefill.email="<?php echo $email; ?>" 
	data-theme.color="#F37254" ;>
</script>
<!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
<input type="hidden" name="hidden" custom="Hidden Element">
</form>