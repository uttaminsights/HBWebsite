<?php

  /*
    Paytm posts the transaction result here (cross-site POST, so the user's
    session cookie is not available). We trust nothing in the POST until:
      1. the checksum verifies with our merchant key,
      2. Paytm's server-side status API confirms the same order/status, and
      3. the paid amount matches what we charged for the booking.
  */

  require('admin/inc/db_config.php');
  require('admin/inc/essentials.php');

  require('inc/paytm/config_paytm.php');
  require('inc/paytm/encdec_paytm.php');

  date_default_timezone_set("Asia/Kolkata");

  header("Pragma: no-cache");
  header("Cache-Control: no-cache");
  header("Expires: 0");

  if(app_config('payment_mode','demo') !== 'paytm' || PAYTM_MERCHANT_KEY === '' || PAYTM_MERCHANT_MID === ''){
    redirect('index.php');
  }

  $paramList = $_POST;
  $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? (string)$_POST["CHECKSUMHASH"] : "";
  $order_id = isset($_POST['ORDERID']) ? (string)$_POST['ORDERID'] : '';

  if($order_id === '' || $paytmChecksum === '' || verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum) !== "TRUE"){
    redirect('index.php');
  }

  $slct_res = select("SELECT bo.`booking_id`, bo.`booking_status`, bd.`total_pay` FROM `booking_order` bo
    INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id
    WHERE bo.`order_id`=? LIMIT 1",[$order_id],'s');

  if(mysqli_num_rows($slct_res)==0){
    redirect('index.php');
  }

  $booking = mysqli_fetch_assoc($slct_res);

  // Only a pending booking can be settled (prevents replaying an old callback).
  if($booking['booking_status'] !== 'pending'){
    redirect('pay_status.php?order='.urlencode($order_id));
  }

  // Ask Paytm directly for the authoritative status of this order.
  $statusReq = ["MID" => PAYTM_MERCHANT_MID, "ORDERID" => $order_id];
  $statusReq["CHECKSUMHASH"] = getChecksumFromArray($statusReq, PAYTM_MERCHANT_KEY);
  $status = getTxnStatusNew($statusReq);

  $txn_status = is_array($status) ? (string)($status['STATUS'] ?? '') : '';
  $txn_id = is_array($status) ? (string)($status['TXNID'] ?? '') : '';
  $txn_amt = is_array($status) ? (float)($status['TXNAMOUNT'] ?? 0) : 0;
  $resp_msg = is_array($status) ? substr((string)($status['RESPMSG'] ?? ''),0,200) : 'Could not verify payment';

  $paid_ok = ($txn_status === 'TXN_SUCCESS') && ((int)round($txn_amt) === (int)$booking['total_pay']);

  if($txn_status === 'PENDING'){
    // leave booking pending; Paytm will settle it later
    redirect('bookings.php');
  }

  if($paid_ok)
  {
    update("UPDATE `booking_order` SET `booking_status`='booked', `trans_id`=?, `trans_amt`=?,
      `trans_status`=?, `trans_resp_msg`=? WHERE `booking_id`=? AND `booking_status`='pending'",
      [$txn_id,(int)round($txn_amt),$txn_status,$resp_msg,$booking['booking_id']],'sissi');
  }
  else
  {
    if($txn_status === 'TXN_SUCCESS'){
      $resp_msg = 'Amount mismatch - contact support';
      error_log("Paytm amount mismatch for order $order_id: paid $txn_amt, expected {$booking['total_pay']}");
    }
    update("UPDATE `booking_order` SET `booking_status`='payment failed', `trans_id`=?, `trans_amt`=?,
      `trans_status`=?, `trans_resp_msg`=? WHERE `booking_id`=? AND `booking_status`='pending'",
      [$txn_id,(int)round($txn_amt),$txn_status !== '' ? $txn_status : 'TXN_FAILURE',$resp_msg,$booking['booking_id']],'sissi');
  }

  // Top-level same-site navigation: the user's own session cookie is sent again here.
  redirect('pay_status.php?order='.urlencode($order_id));

?>
