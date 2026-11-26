<?php 

  require('../admin/inc/db_config.php');
  require('../admin/inc/essentials.php');

  date_default_timezone_set("Asia/Kolkata");

  app_session_start();

  if(!(isset($_SESSION['login']) && $_SESSION['login']==true) || empty($_SESSION['uId'])){
    http_response_code(401);
    echo 0;
    exit;
  }

  $uid = (int)$_SESSION['uId'];

  if(isset($_POST['info_form']))
  {
    $frm_data = filteration($_POST);

    $u_exist = select("SELECT * FROM `user_cred` WHERE `phonenum`=? AND `id`!=? LIMIT 1",
      [$frm_data['phonenum'],$uid],"si");

    if(mysqli_num_rows($u_exist)!=0){
      echo 'phone_already';
      exit;
    }

    $query = "UPDATE `user_cred` SET `name`=?, `address`=?, `phonenum`=?,
      `pincode`=?, `dob`=? WHERE `id`=? LIMIT 1";
    
    $values = [$frm_data['name'],$frm_data['address'],$frm_data['phonenum'],
      $frm_data['pincode'],$frm_data['dob'],$uid];

    if(update($query,$values,'sssssi')){
      $_SESSION['uName'] = $frm_data['name'];
      echo 1;
    }
    else{
      echo 0;
    }

  }


  if(isset($_POST['profile_form']))
  {
    if(!isset($_FILES['profile'])){
      echo 'inv_img';
      exit;
    }

    $img = uploadUserImage($_FILES['profile']);
    
    if($img == 'inv_img'){
      echo 'inv_img';
      exit;
    }
    else if($img == 'upd_failed'){
      echo 'upd_failed';
      exit;
    }


    //fetching old image and deleting it

    $u_exist = select("SELECT `profile` FROM `user_cred` WHERE `id`=? LIMIT 1",[$uid],"i");
    $u_fetch = mysqli_fetch_assoc($u_exist);

    if(!empty($u_fetch['profile'])){
      deleteImage($u_fetch['profile'],USERS_FOLDER);
    }


    $query = "UPDATE `user_cred` SET `profile`=? WHERE `id`=? LIMIT 1";
    
    $values = [$img,$uid];

    if(update($query,$values,'si')){
      $_SESSION['uPic'] = $img;
      echo 1;
    }
    else{
      echo 0;
    }

  }

  if(isset($_POST['pass_form']))
  {
    $frm_data = filteration($_POST);

    if($frm_data['new_pass']!=$frm_data['confirm_pass']){
      echo 'mismatch';
      exit;
    }

    // require the current password before changing it
    $u_fetch = mysqli_fetch_assoc(select("SELECT `password` FROM `user_cred` WHERE `id`=? LIMIT 1",[$uid],'i'));
    if(!$u_fetch || !password_verify($frm_data['current_pass'] ?? '',$u_fetch['password'])){
      echo 'wrong_current';
      exit;
    }

    $enc_pass = password_hash($frm_data['new_pass'],PASSWORD_BCRYPT);

    $query = "UPDATE `user_cred` SET `password`=? WHERE `id`=? LIMIT 1";
    $values = [$enc_pass,$uid];

    if(update($query,$values,'si')){
      session_regenerate_id(true);
      echo 1;
    }
    else{
      echo 0;
    }

  }


?>
