<?php 

  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');

  date_default_timezone_set("Asia/Kolkata");

  if(isset($_POST['check_availability']))
  {
    $frm_data = filteration($_POST);
    $status = "";
    $result = "";

    app_session_start();

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true) || empty($_SESSION['room']['id'])){
      echo json_encode(["status"=>'unavailable']);
      exit;
    }

    // invalidate any previous availability result until this check passes
    $_SESSION['room']['available'] = false;
    $_SESSION['room']['payment'] = null;

    // check in and out validations

    try{
      $today_date = new DateTime(date("Y-m-d"));
      $checkin_date = new DateTime($frm_data['check_in']);
      $checkout_date = new DateTime($frm_data['check_out']);
    }
    catch(Exception $e){
      echo json_encode(["status"=>'check_in_out_equal']);
      exit;
    }

    if($checkin_date == $checkout_date){
      $status = 'check_in_out_equal';
      $result = json_encode(["status"=>$status]);
    }
    else if($checkout_date < $checkin_date){
      $status = 'check_out_earlier';
      $result = json_encode(["status"=>$status]);
    }
    else if($checkin_date < $today_date){
      $status = 'check_in_earlier';
      $result = json_encode(["status"=>$status]);
    }

    // check booking availability if status is blank else return the error

    if($status!=''){
      echo $result;
    }
    else{
      $checkin_str = $checkin_date->format('Y-m-d');
      $checkout_str = $checkout_date->format('Y-m-d');

      if(!room_has_vacancy($_SESSION['room']['id'],$checkin_str,$checkout_str)){
        echo json_encode(['status'=>'unavailable']);
        exit;
      }

      $count_days = date_diff($checkin_date,$checkout_date)->days;
      $payment = $_SESSION['room']['price'] * $count_days;

      // remember exactly what was priced; pay_now.php uses only these values
      $_SESSION['room']['payment'] = $payment;
      $_SESSION['room']['checkin'] = $checkin_str;
      $_SESSION['room']['checkout'] = $checkout_str;
      $_SESSION['room']['available'] = true;
      
      $result = json_encode(["status"=>'available', "days"=>$count_days, "payment"=> $payment]);
      echo $result;
    }

  }

?>
