<?php 

  require('inc/essentials.php');

  app_session_start();

  // Clear only the admin login (a customer logged in on the same browser stays logged in).
  unset($_SESSION['adminLogin'], $_SESSION['adminId']);
  session_regenerate_id(true);

  if(empty($_SESSION['login'])){
    app_session_destroy();
  }

  redirect('index.php');

?>
