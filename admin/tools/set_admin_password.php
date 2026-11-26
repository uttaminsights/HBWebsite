<?php
/*
 * Command-line only: create or update an admin account.
 *   php admin/tools/set_admin_password.php <admin_name> <new_password>
 */

if (PHP_SAPI !== 'cli') {
  http_response_code(404);
  exit;
}

if ($argc !== 3) {
  fwrite(STDERR, "Usage: php admin/tools/set_admin_password.php <admin_name> <new_password>\n");
  exit(1);
}

require(__DIR__ . '/../inc/db_config.php');

[, $name, $pass] = $argv;
if (strlen($pass) < 8) {
  fwrite(STDERR, "Password must be at least 8 characters.\n");
  exit(1);
}

$hash = password_hash($pass, PASSWORD_DEFAULT);
$exists = mysqli_num_rows(select("SELECT `sr_no` FROM `admin_cred` WHERE `admin_name`=?", [$name], 's'));

if ($exists) {
  update("UPDATE `admin_cred` SET `admin_pass`=? WHERE `admin_name`=?", [$hash, $name], 'ss');
  echo "Password updated for admin '$name'.\n";
} else {
  insert("INSERT INTO `admin_cred`(`admin_name`,`admin_pass`) VALUES (?,?)", [$name, $hash], 'ss');
  echo "Admin '$name' created.\n";
}
