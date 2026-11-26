<?php
/*
 * Copy this file to config.php and fill in your values.
 * config.php is git-ignored so your secrets never get committed.
 */

return [

  // ---- Database (MySQL / MariaDB) ----
  'db_host' => 'localhost',
  'db_user' => 'root',
  'db_pass' => '',
  'db_name' => 'hbwebsite',

  // ---- Site URL ----
  // Leave empty to auto-detect from the request (works for any folder name).
  // Set explicitly in production, e.g. 'https://example.com/' (must end with '/').
  'site_url' => '',

  // Show PHP errors in the browser. Keep false in production.
  'debug' => false,

  // ---- SendGrid (email verification / password reset) ----
  'sendgrid_api_key' => '',
  'sendgrid_email'   => '',
  'sendgrid_name'    => 'Nivaara',

  // ---- Payments ----
  // 'demo'  : bookings are confirmed instantly WITHOUT taking money. Local testing only!
  // 'paytm' : real Paytm checkout (fill in the keys below). Use this in production.
  'payment_mode' => 'demo',

  // ---- Paytm payment gateway ----
  'paytm_environment' => 'TEST',          // TEST or PROD
  'paytm_merchant_key' => '',
  'paytm_merchant_mid' => '',
  'paytm_merchant_website' => 'WEBSTAGING',
  'paytm_industry_type_id' => 'Retail',
  'paytm_channel_id' => 'WEB',

];
