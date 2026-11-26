<?php 

  require_once(__DIR__.'/config_loader.php');

  $hname = app_config('db_host','localhost');
  $uname = app_config('db_user','root');
  $pass = app_config('db_pass','');
  $db = app_config('db_name','hbwebsite');

  mysqli_report(MYSQLI_REPORT_OFF); // keep the helper functions' own error handling (PHP 8.1+ throws by default)
  $con = mysqli_connect($hname,$uname,$pass,$db);

  if(!$con){
    error_log("DB connection failed: ".mysqli_connect_error());
    die("Cannot connect to the database. Check the settings in config.php.");
  }
  mysqli_set_charset($con,'utf8mb4');

  function filteration($data){
    foreach($data as $key => $value){
      if(!is_scalar($value)){ $value = ''; }
      $value = trim((string)$value);
      $value = stripslashes($value);
      $value = strip_tags($value);
      $value = htmlspecialchars($value);
      $data[$key] = $value;
    }
    return $data;
  }

  function selectAll($table)
  {
    $con = $GLOBALS['con'];
    $res = mysqli_query($con,"SELECT * FROM $table");
    return $res;
  }

  function select($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Select");
      }
    }
    else{
      die("Query cannot be prepared - Select");
    }
  }

  function update($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Update");
      }
    }
    else{
      die("Query cannot be prepared - Update");
    }
  }

  function insert($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Insert");
      }
    }
    else{
      die("Query cannot be prepared - Insert");
    }
  }

  function delete($sql,$values,$datatypes)
  {
    $con = $GLOBALS['con'];
    if($stmt = mysqli_prepare($con,$sql))
    {
      mysqli_stmt_bind_param($stmt,$datatypes,...$values);
      if(mysqli_stmt_execute($stmt)){
        $res = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $res;
      }
      else{
        mysqli_stmt_close($stmt);
        die("Query cannot be executed - Delete");
      }
    }
    else{
      die("Query cannot be prepared - Delete");
    }
  }

?>