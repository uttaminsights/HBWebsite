<?php 

  require('admin/inc/essentials.php');

  app_session_start();

  // Clear only the customer login (an admin logged in on the same browser stays logged in).
  foreach(['login','uId','uName','uPic','uPhone','room'] as $k){
    unset($_SESSION[$k]);
  }
  session_regenerate_id(true);

  if(empty($_SESSION['adminLogin'])){
    app_session_destroy();
  }

  redirect('index.php');

?>
