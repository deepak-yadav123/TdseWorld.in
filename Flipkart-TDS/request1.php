<?php
require __DIR__ . '/vendor/autoload.php';
require('razorpay-php/Razorpay.php');
ob_start();
include 'header.php';
include 'partials/_categories_nav.php';

if(!isset($_POST['orderId'])){
  header('location:index.php');
}
?>

<?php
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$keySecret = "zhbWrW3cd7BnYN2wvAabQSQw"; /* enter secret key here */
$keyId = "rzp_test_tEVwQ4gLK5uFIl";
$api = new Api($keyId, $keySecret);

$orderData = [
    'receipt'         => 3456,
    'amount'          => 5000, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$order = $api->order->create($orderData);

if (!$order['id']) {
    // Handle error
}

$orderId = $order['id'];


$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => "DJ Tiesto",
    "description"       => "Tron Legacy",
    "image"             => "https://s29.postimg.org/r6dj1g85z/daft_punk.jpg",
    "prefill"           => [
    "name"              => "Daft Punk",
    "email"             => "customer@merchant.com",
    "contact"           => "9999999999",
    ],
    "notes"             => [
    "address"           => "Hello World",
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
   /* "appId" => $appId, 
    "orderId" => $orderId, 
    "orderAmount" => $orderAmount, 
    "orderCurrency" => $orderCurrency, 
    "orderNote" => $orderNote, 
    "customerName" => $customerName, 
    "customerPhone" => $customerPhone, 
    "customerEmail" => $customerEmail,
    "returnUrl" => $returnUrl, 
    "notifyUrl" => $notifyUrl, */
    "order_id"          => $razorpayOrderId,
  ];

$json = json_encode($data);

$encrypted = encrypt($json, $keySecret);

$razorpayPaymentGatewayHtml = '
    <form id="checkout-form" action="checkout.php" method="POST">
        <script src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="' . $keyId . '"
                data-amount="' . $amount . '"
                data-currency="INR"
                data-name="' . $productName . '"
                data-description="' . $productDescription . '"
                data-image="' . $imageUrl . '"
                data-prefill.name="' . $customerName . '"
                data-prefill.email="' . $customerEmail . '"
                data-prefill.contact="' . $customerPhone . '"
                data-notes.address="' . $customerAddress . '"
                data-order_id="' . $orderId . '"
                data-encrypted="' . $encrypted . '"
                async>
        </script>
    </form>
';

// Display Razorpay payment gateway HTML
echo $razorpayPaymentGatewayHtml;
