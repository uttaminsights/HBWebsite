<?php
  require('inc/essentials.php');
  require('inc/db_config.php');

  app_session_start();
  if((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
    redirect('dashboard.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login Panel</title>
  <?php require('inc/links.php'); ?>
  <style>
    div.login-form{
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%,-50%);
      width: 400px;
    }
  </style>
</head>
<body class="admin-login-bg">
  
  <div class="login-form text-center rounded bg-white shadow overflow-hidden">
    <form method="POST">
      <?php $lb = mysqli_fetch_assoc(mysqli_query($con,"SELECT `site_title` FROM `settings` WHERE `sr_no`=1")); ?>
      <div class="pt-5"><span class="login-mark"><?php echo htmlspecialchars(mb_substr(html_entity_decode($lb['site_title'] ?? 'A'),0,1)) ?></span></div>
      <h4 class="h-font pt-3 pb-1 mb-0" style="font-size:2.2rem;padding-top:1.2rem !important"><?php echo $lb['site_title'] ?? 'Admin' ?></h4>
      <p class="small mb-0" style="color:#8e8e9a">Sign in to the admin dashboard</p>
      <div class="p-4">
        <div class="mb-3">
          <input name="admin_name" required type="text" class="form-control shadow-none text-center py-3" placeholder="Admin username">
        </div>
        <div class="mb-4">
          <input name="admin_pass" required type="password" class="form-control shadow-none text-center py-3" placeholder="Password">
        </div>
        <button name="login" type="submit" class="btn custom-bg shadow-none w-100 py-3">Enter dashboard <i class="bi bi-arrow-right ms-1"></i></button>
      </div>
    </form>
  </div>


  <?php 
    
    if(isset($_POST['login']))
    {
      $frm_data = filteration($_POST);

      $query = "SELECT * FROM  `admin_cred` WHERE `admin_name`=? LIMIT 1";
      $res = select($query,[$frm_data['admin_name']],"s");
      $row = ($res->num_rows==1) ? mysqli_fetch_assoc($res) : null;

      $valid = false;
      if($row){
        $stored = $row['admin_pass'];
        if(password_get_info($stored)['algo']){
          $valid = password_verify($frm_data['admin_pass'],$stored);
        }
        else if(hash_equals($stored,$frm_data['admin_pass'])){
          // legacy plain-text password: accept once and upgrade to a hash
          $valid = true;
          update("UPDATE `admin_cred` SET `admin_pass`=? WHERE `sr_no`=?",
            [password_hash($frm_data['admin_pass'],PASSWORD_DEFAULT),$row['sr_no']],'si');
        }
      }

      if($valid){
        session_regenerate_id(true);
        $_SESSION['adminLogin'] = true;
        $_SESSION['adminId'] = $row['sr_no'];
        redirect('dashboard.php');
      }
      else{
        alert('error','Login failed - Invalid Credentials!');
      }
    }
  
  ?>


  <?php require('inc/scripts.php') ?>
</body>
</html>