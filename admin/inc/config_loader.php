<?php
/*
 * Loads config.php from the project root once and exposes it via app_config().
 * Also derives the project's base URL so the site works from any folder name.
 */

if (!function_exists('app_config')) {

  define('PROJECT_ROOT', str_replace('\\', '/', realpath(__DIR__ . '/../..')));

  function app_config($key = null, $default = null)
  {
    static $config = null;

    if ($config === null) {
      $file = PROJECT_ROOT . '/config.php';
      if (!is_file($file)) {
        http_response_code(500);
        die('Missing config.php - copy config.example.php to config.php and fill in your settings.');
      }
      $config = require $file;
    }

    if ($key === null) {
      return $config;
    }
    return array_key_exists($key, $config) ? $config[$key] : $default;
  }

  function app_base_url()
  {
    $configured = trim((string) app_config('site_url', ''));
    if ($configured !== '') {
      return rtrim($configured, '/') . '/';
    }

    // Auto-detect: path of the project root relative to the web server's document root.
    $doc_root = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '');
    $base_path = '/';
    if ($doc_root !== '' && stripos(PROJECT_ROOT, $doc_root) === 0) {
      $base_path = '/' . trim(substr(PROJECT_ROOT, strlen($doc_root)), '/') . '/';
      $base_path = preg_replace('#/+#', '/', $base_path);
    }

    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $scheme = $https ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    return $scheme . '://' . $host . $base_path;
  }

  // Idempotent session start with hardened cookie settings.
  function app_session_start()
  {
    if (session_status() === PHP_SESSION_ACTIVE) {
      return;
    }
    if (headers_sent()) {
      // Output was already sent: PHP cannot set the cookie now. Fail loudly in logs.
      error_log('app_session_start(): headers already sent; enable output_buffering.');
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
      'lifetime' => 0,
      'path' => '/',
      'secure' => $https,
      'httponly' => true,
      'samesite' => 'Lax',
    ]);
    session_start();
  }

  // Clears the session completely (data, cookie, server-side file).
  function app_session_destroy()
  {
    app_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
      $p = session_get_cookie_params();
      setcookie(session_name(), '', time() - 3600, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
  }

  // CSRF token for the current session.
  function csrf_token()
  {
    app_session_start();
    if (empty($_SESSION['csrf'])) {
      $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
  }

  if (app_config('debug', false)) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
  } else {
    ini_set('display_errors', '0');
  }
}
