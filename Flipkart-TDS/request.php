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
$mode = "TEST"; //<------------ Change to TEST for test server, PROD for production

// extract($_POST);
  $secretKey = "zhbWrW3cd7BnYN2wvAabQSQw"; /* enter secret key here */
  $appId = "rzp_test_tEVwQ4gLK5uFIl";
  use Razorpay\Api\Api;
  $api = new Api($appId, $secretKey); 
 // $api = new Api($keyId, $keySecret);

 $orderData = [
  'receipt'         => 3456,
  'amount'          => 5000, // 2000 rupees in paise
  'currency'        => 'INR',
  'payment_capture' => 1 // auto capture
];


$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];
echo $razorpayOrder->id;
if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

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
if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);

// Cash Free INTEGRATION 
// ksort($orderData);
// $signatureData = "";
// foreach ($postData as $key => $value){
//     $signatureData .= $key.$value;
// }
// $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
// $signature = base64_encode($signature);

// if ($mode == "PROD") {
//   $url = "https://www.cashfree.com/checkout/post/submit";
// } else {
//   $url = "https://test.cashfree.com/billpay/checkout/post/submit";
// }

?>
  <form action="<?php echo $url; ?>" id="payForm" name="frm1" method="POST">
      <p>Please wait.......</p>
      <input type="hidden" name="signature" value='<?php echo $signature; ?>'/>
      <input type="hidden" name="orderNote" value='<?php echo $orderNote; ?>'/>
      <input type="hidden" name="orderCurrency" value='<?php echo $orderCurrency; ?>'/>
      <input type="hidden" name="customerName" value='<?php echo $customerName; ?>'/>
      <input type="hidden" name="customerEmail" value='<?php echo $customerEmail; ?>'/>
      <input type="hidden" name="customerPhone" value='<?php echo $customerPhone; ?>'/>
      <input type="hidden" name="orderAmount" value='<?php echo $orderAmount; ?>'/>
      <input type ="hidden" name="notifyUrl" value='<?php echo $notifyUrl; ?>'/>
      <input type ="hidden" name="returnUrl" value='<?php echo $returnUrl; ?>'/>
      <input type="hidden" name="appId" value='<?php echo $appId; ?>'/>
      <input type="hidden" name="orderId" value='<?php echo $orderId; ?>'/>
  </form>

<script type="text/javascript">
$(document).ready(function(){
  $('#payForm').submit();
});
</script>

<?php
include 'partials/_footer.php';
?>
