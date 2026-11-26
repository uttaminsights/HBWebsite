<?php

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  require('inc/paytm/config_paytm.php');
  require('inc/paytm/encdec_paytm.php');

  date_default_timezone_set("Asia/Kolkata");

  app_session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true)){
    redirect('index.php');
  }

  if(!isset($_POST['pay_now'])){
    redirect('rooms.php');
  }

  // Everything that affects price/dates comes from the session, set by the
  // server-side availability check in ajax/confirm_booking.php.
  $room = $_SESSION['room'] ?? null;

  if(!$room || empty($room['available']) || empty($room['checkin']) || empty($room['checkout'])){
    redirect('rooms.php');
  }

  // Re-check vacancy right before creating the booking (another user may have booked meanwhile).
  if(!room_has_vacancy($room['id'],$room['checkin'],$room['checkout'])){
    unset($_SESSION['room']);
    echo "<script>alert('Sorry, this room is no longer available for the selected dates.');</script>";
    redirect('rooms.php');
  }

  // Recompute the amount from the current DB price.
  $room_fetch = mysqli_fetch_assoc(select("SELECT `name`,`price` FROM `rooms` WHERE `id`=? AND `status`=1 AND `removed`=0",[$room['id']],'i'));
  if(!$room_fetch){
    redirect('rooms.php');
  }
  $days = (new DateTime($room['checkin']))->diff(new DateTime($room['checkout']))->days;
  if($days < 1){
    redirect('rooms.php');
  }
  $TXN_AMOUNT = (int)$room_fetch['price'] * $days;

  $frm_data = filteration($_POST);
  $CUST_ID = (int)$_SESSION['uId'];
  $ORDER_ID = 'ORD_'.$CUST_ID.'_'.bin2hex(random_bytes(6));

  $payment_mode = app_config('payment_mode','demo');

  // Booking starts as 'pending'; it only becomes 'booked' after a verified payment.
  $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`, `check_in`, `check_out`,`order_id`,`booking_status`,`trans_amt`) VALUES (?,?,?,?,?,?,?)";
  insert($query1,[$CUST_ID,$room['id'],$room['checkin'],$room['checkout'],$ORDER_ID,'pending',0],'iissssi');

  $booking_id = mysqli_insert_id($con);

  $query2 = "INSERT INTO `booking_details`(`booking_id`, `room_name`, `price`, `total_pay`,
    `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";

  insert($query2,[$booking_id,$room_fetch['name'],$room_fetch['price'],
    $TXN_AMOUNT,$frm_data['name'],$frm_data['phonenum'],$frm_data['address']],'isiisss');

  // One booking per availability check.
  unset($_SESSION['room']);

  if($payment_mode === 'paytm')
  {
    if(PAYTM_MERCHANT_KEY === '' || PAYTM_MERCHANT_MID === ''){
      error_log('payment_mode is paytm but Paytm keys are not set in config.php');
      update("UPDATE `booking_order` SET `booking_status`='payment failed', `trans_status`='TXN_FAILURE', `trans_resp_msg`='Payment gateway not configured' WHERE `booking_id`=?",[$booking_id],'i');
      redirect('pay_status.php?order='.urlencode($ORDER_ID));
    }

    $paramList = array();
    $paramList["MID"] = PAYTM_MERCHANT_MID;
    $paramList["ORDER_ID"] = $ORDER_ID;
    $paramList["CUST_ID"] = $CUST_ID;
    $paramList["INDUSTRY_TYPE_ID"] = INDUSTRY_TYPE_ID;
    $paramList["CHANNEL_ID"] = CHANNEL_ID;
    $paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
    $paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
    $paramList["CALLBACK_URL"] = CALLBACK_URL;

    $checkSum = getChecksumFromArray($paramList,PAYTM_MERCHANT_KEY);

    header("Pragma: no-cache");
    header("Cache-Control: no-cache");
    header("Expires: 0");
?>
<!DOCTYPE html>
<html>
<head><title>Redirecting to payment...</title></head>
<body>
  <p>Redirecting to the payment page, please do not refresh...</p>
  <form method="post" action="<?php echo htmlspecialchars(PAYTM_TXN_URL,ENT_QUOTES) ?>" name="paytm_form">
    <?php foreach($paramList as $name => $value): ?>
      <input type="hidden" name="<?php echo htmlspecialchars($name,ENT_QUOTES) ?>" value="<?php echo htmlspecialchars((string)$value,ENT_QUOTES) ?>">
    <?php endforeach; ?>
    <input type="hidden" name="CHECKSUMHASH" value="<?php echo htmlspecialchars($checkSum,ENT_QUOTES) ?>">
  </form>
  <script>document.paytm_form.submit();</script>
</body>
</html>
<?php
    exit;
  }
  else
  {
    // DEMO MODE: no real payment is taken. For local development/testing only.
    update("UPDATE `booking_order` SET `booking_status`='booked', `trans_id`=?, `trans_amt`=?,
      `trans_status`='TXN_SUCCESS', `trans_resp_msg`='Demo payment (no money charged)' WHERE `booking_id`=?",
      ['DEMO_'.$ORDER_ID,$TXN_AMOUNT,$booking_id],'sii');

    redirect('pay_status.php?order='.urlencode($ORDER_ID));
  }

?>
