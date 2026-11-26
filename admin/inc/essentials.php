<?php 

  require_once(__DIR__.'/config_loader.php');

  //frontend purpose data

  define('SITE_URL',app_base_url());
  define('ABOUT_IMG_PATH',SITE_URL.'images/about/');
  define('CAROUSEL_IMG_PATH',SITE_URL.'images/carousel/');
  define('FACILITIES_IMG_PATH',SITE_URL.'images/facilities/');
  define('ROOMS_IMG_PATH',SITE_URL.'images/rooms/');
  define('USERS_IMG_PATH',SITE_URL.'images/users/');


  //backend upload process needs this data

  define('UPLOAD_IMAGE_PATH',PROJECT_ROOT.'/images/');
  define('ABOUT_FOLDER','about/');
  define('CAROUSEL_FOLDER','carousel/');
  define('FACILITIES_FOLDER','facilities/');
  define('ROOMS_FOLDER','rooms/');
  define('USERS_FOLDER','users/');

  // sendgrid api key

  define('SENDGRID_API_KEY',app_config('sendgrid_api_key',''));
  define('SENDGRID_EMAIL',app_config('sendgrid_email',''));
  define('SENDGRID_NAME',app_config('sendgrid_name','Hotel Booking'));

  // Possible "booking status" values in db = pending, booked, payment failed, cancelled
  
  // to configure paytm gateway check file 'project folder / inc / paytm / config_paytm.php' 

  function adminLogin()
  {
    app_session_start();
    if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true)){
      echo"<script>
        window.location.href='index.php';
      </script>";
      exit;
    }
  }

  function redirect($url){
    echo"<script>
      window.location.href='$url';
    </script>";
    exit;
  }

  function alert($type,$msg){    
    $bs_class = ($type == "success") ? "alert-success" : "alert-danger";

    echo <<<alert
      <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
        <strong class="me-3">$msg</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    alert;
  }

  // Detects the real MIME type from file contents (never trust $_FILES['type']).
  function detectMime($tmp_path)
  {
    if(!is_uploaded_file($tmp_path) && !is_file($tmp_path)){
      return '';
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    return (string) $finfo->file($tmp_path);
  }

  function randomImageName($ext)
  {
    return 'IMG_'.bin2hex(random_bytes(8)).'.'.$ext;
  }

  function uploadImage($image,$folder)
  {
    $ext_map = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];

    if(!isset($image['tmp_name']) || ($image['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK){
      return 'upd_failed';
    }

    $img_mime = detectMime($image['tmp_name']);

    if(!isset($ext_map[$img_mime]) || @getimagesize($image['tmp_name'])===false){
      return 'inv_img'; //invalid image mime or format
    }
    else if(($image['size']/(1024*1024))>2){
      return 'inv_size'; //invalid size greater than 2mb
    }
    else{
      $rname = randomImageName($ext_map[$img_mime]);

      $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;
      if(move_uploaded_file($image['tmp_name'],$img_path)){
        return $rname;
      }
      else{
        return 'upd_failed';
      }
    }
  }

  function deleteImage($image, $folder)
  {
    $image = basename((string)$image);
    if($image === '' || $image === '.' || $image === '..'){
      return false;
    }
    $path = UPLOAD_IMAGE_PATH.$folder.$image;
    return is_file($path) && unlink($path);
  }

  function uploadSVGImage($image,$folder)
  {
    if(!isset($image['tmp_name']) || ($image['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK){
      return 'upd_failed';
    }

    if(($image['size']/(1024*1024))>1){
      return 'inv_size'; //invalid size greater than 1mb
    }

    $svg = file_get_contents($image['tmp_name']);
    $img_mime = detectMime($image['tmp_name']);

    // must actually be SVG markup
    if(!in_array($img_mime,['image/svg+xml','text/xml','application/xml','text/plain','text/html'])
      || stripos($svg,'<svg')===false){
      return 'inv_img';
    }

    // reject active content (scripts, event handlers, external entities, embedded HTML)
    if(preg_match('/<\s*script|<\s*foreignObject|<\s*iframe|<\s*embed|<\s*object|<!ENTITY|<!DOCTYPE|\son[a-z]+\s*=|javascript:|data:text\/html/i',$svg)){
      return 'inv_img';
    }

    $rname = randomImageName('svg');

    $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;
    if(move_uploaded_file($image['tmp_name'],$img_path)){
      return $rname;
    }
    else{
      return 'upd_failed';
    }
  }

  function uploadUserImage($image)
  {
    if(!isset($image['tmp_name']) || ($image['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK){
      return 'inv_img';
    }

    if(($image['size']/(1024*1024))>2){
      return 'inv_img';
    }

    $img_mime = detectMime($image['tmp_name']);

    // re-encode to JPEG with GD: strips anything that isn't pixel data
    if($img_mime == 'image/png') {
      $img = @imagecreatefrompng($image['tmp_name']);
    }
    else if($img_mime == 'image/webp') {
      $img = @imagecreatefromwebp($image['tmp_name']);
    }
    else if($img_mime == 'image/jpeg') {
      $img = @imagecreatefromjpeg($image['tmp_name']);
    }
    else{
      return 'inv_img';
    }

    if(!$img){
      return 'inv_img';
    }

    $rname = randomImageName('jpeg');
    $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

    if(imagejpeg($img,$img_path,75)){
      return $rname;
    }
    else{
      return 'upd_failed';
    }
  }

  // A full-width table row used when a list has nothing to show.
  function empty_row($cols, $title, $hint = '', $icon = 'bi-inbox')
  {
    $cols = (int) $cols;
    $hint_html = $hint !== '' ? "<div class='empty-hint'>$hint</div>" : '';
    return "<tr class='empty-row'><td colspan='$cols'>
      <div class='empty-state'>
        <i class='bi $icon'></i>
        <div class='empty-title'>$title</div>
        $hint_html
      </div>
    </td></tr>";
  }

  // Search-aware variant: different wording when the admin typed a search term.
  function empty_row_search($cols, $search, $title, $hint, $icon = 'bi-inbox')
  {
    if (trim((string) $search) !== '') {
      return empty_row($cols, 'No results for &ldquo;' . $search . '&rdquo;', 'Try a different order ID, phone number or name.', 'bi-search');
    }
    return empty_row($cols, $title, $hint, $icon);
  }

  // Returns the URL only if it is http(s); otherwise '#'. Escaped for HTML attributes.
  function safe_url($url)
  {
    $url = html_entity_decode((string)$url,ENT_QUOTES);
    $scheme = strtolower((string)parse_url($url,PHP_URL_SCHEME));
    if(!in_array($scheme,['http','https'],true)){
      return '#';
    }
    return htmlspecialchars($url,ENT_QUOTES);
  }

  // True if at least one unit of the room is free for [checkin, checkout).
  // Requires db_config.php to be loaded.
  function room_has_vacancy($room_id,$checkin,$checkout)
  {
    $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
      WHERE booking_status=? AND room_id=?
      AND check_out > ? AND check_in < ?";
    $tb_fetch = mysqli_fetch_assoc(select($tb_query,['booked',$room_id,$checkin,$checkout],'siss'));

    $rq_fetch = mysqli_fetch_assoc(select("SELECT `quantity` FROM `rooms` WHERE `id`=?",[$room_id],'i'));
    if(!$rq_fetch){
      return false;
    }
    return ($rq_fetch['quantity'] - $tb_fetch['total_bookings']) > 0;
  }

?>
