<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link  rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - HOME</title>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <!-- Hero -->

  <section class="hero">
    <div class="swiper swiper-container">
      <div class="swiper-wrapper">
        <?php 
          $res = selectAll('carousel');
          while($row = mysqli_fetch_assoc($res))
          {
            $path = CAROUSEL_IMG_PATH;
            echo <<<data
              <div class="swiper-slide">
                <img src="$path$row[image]" alt="">
              </div>
            data;
          }
        ?>
      </div>
    </div>
    <div class="container hero-content">
      <span class="hero-pill"><b><?php echo date('Y') ?></b> Boutique Hotel &middot; Prayagraj</span>
      <h1><?php echo $settings_r['site_title'] ?></h1>
      <h2 class="hero-sub">Stay Somewhere That Feels Like You</h2>
      <div class="hero-actions">
        <a href="rooms.php" class="btn btn-light-solid">Book a Stay</a>
      </div>
    </div>
    
    <div class="hero-glow" aria-hidden="true"></div>
  </section>

  <!-- check availability form -->

  <div class="container availability-form">
    <div class="row">
      <div class="col-lg-12 bg-white shadow p-4 rounded">
        <h5 class="mb-4">Check availability</h5>
        <form action="rooms.php">
          <div class="row align-items-end">
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;">Check-in</label>
              <input type="date" class="form-control shadow-none" name="checkin" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;">Check-out</label>
              <input type="date" class="form-control shadow-none" name="checkout" required>
            </div>
            <div class="col-lg-3 mb-3">
              <label class="form-label" style="font-weight: 500;">Adult</label>
              <select class="form-select shadow-none" name="adult">
                <?php 
                  $guests_q = mysqli_query($con,"SELECT MAX(adult) AS `max_adult`, MAX(children) AS `max_children` 
                    FROM `rooms` WHERE `status`='1' AND `removed`='0'");  
                  $guests_res = mysqli_fetch_assoc($guests_q);
                  
                  for($i=1; $i<=$guests_res['max_adult']; $i++){
                    echo"<option value='$i'>$i</option>";
                  }
                ?>
              </select>
            </div>
            <div class="col-lg-2 mb-3">
              <label class="form-label" style="font-weight: 500;">Children</label>
              <select class="form-select shadow-none" name="children">
                <?php 
                  for($i=1; $i<=$guests_res['max_children']; $i++){
                    echo"<option value='$i'>$i</option>";
                  }
                ?>
              </select>
            </div>
            <input type="hidden" name="check_availability">
            <div class="col-lg-1 mb-lg-3 mt-2">
              <button type="submit" class="btn text-white shadow-none custom-bg w-100" aria-label="Search"><i class="bi bi-search"></i></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Our Rooms -->

  <div class="marquee" aria-hidden="true">
    <div class="marquee-track">
      <span>Comfy rooms</span><span>Rooftop breakfasts</span><span>24/7 crew</span><span>Spa days</span><span>Book direct &amp; save</span>
      <span>Comfy rooms</span><span>Rooftop breakfasts</span><span>24/7 crew</span><span>Spa days</span><span>Book direct &amp; save</span>
    </div>
  </div>

  <div class="section-title reveal" id="rooms">
    <span class="eyebrow">01 &middot; Rooms</span>
    <h2 class="h-font m-0">Pick your <span class="gold-text">perfect</span> room</h2>
    <div class="h-line"></div>
    <p>Calm, considered spaces for a night or a fortnight &mdash; everything you need and nothing you don&rsquo;t.</p>
  </div>

  <div class="container">
    <div class="row">

      <?php 
            
        $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=? ORDER BY `id` DESC LIMIT 3",[1,0],'ii');

        $room_no = 0;
        while($room_data = mysqli_fetch_assoc($room_res))
        {
          $room_no++;
          $room_no_txt = str_pad($room_no, 2, '0', STR_PAD_LEFT);
          // get features of room

          $fea_q = mysqli_query($con,"SELECT f.name FROM `features` f 
            INNER JOIN `room_features` rfea ON f.id = rfea.features_id 
            WHERE rfea.room_id = '$room_data[id]'");

          $features_data = "";
          while($fea_row = mysqli_fetch_assoc($fea_q)){
            $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
              $fea_row[name]
            </span>";
          }

          // get facilities of room

          $fac_q = mysqli_query($con,"SELECT f.name FROM `facilities` f 
            INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
            WHERE rfac.room_id = '$room_data[id]'");

          $facilities_data = "";
          while($fac_row = mysqli_fetch_assoc($fac_q)){
            $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
              $fac_row[name]
            </span>";
          }

          // get thumbnail of image

          $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
          $thumb_q = mysqli_query($con,"SELECT * FROM `room_images` 
            WHERE `room_id`='$room_data[id]' 
            AND `thumb`='1'");

          if(mysqli_num_rows($thumb_q)>0){
            $thumb_res = mysqli_fetch_assoc($thumb_q);
            $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
          }

          $book_btn = "";

          if(!$settings_r['shutdown']){
            $login=0;
            if(isset($_SESSION['login']) && $_SESSION['login']==true){
              $login=1;
            }

            $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm text-white custom-bg shadow-none'>Book Now</button>";
          }

          $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
            WHERE `room_id`='$room_data[id]' ORDER BY `sr_no` DESC LIMIT 20";

          $rating_res = mysqli_query($con,$rating_q);
          $rating_fetch = mysqli_fetch_assoc($rating_res);

          $rating_data = "";

          if($rating_fetch['avg_rating']!=NULL)
          {
            $rating_data = "<div class='rating mb-4'>
              <h6 class='mb-1'>Rating</h6>
              <span class='badge rounded-pill bg-light'>
            ";

            for($i=0; $i<$rating_fetch['avg_rating']; $i++){
              $rating_data .="<i class='bi bi-star-fill text-warning'></i> ";
            }

            $rating_data .= "</span>
              </div>
            ";
          }

          // print room card

          echo <<<data
            <div class="col-lg-4 col-md-6 my-3 reveal">
              <div class="card room-card">
                <div class="media">
                  <span class="room-no">No. $room_no_txt</span>
                  <img src="$room_thumb" alt="$room_data[name]" loading="lazy">
                  <span class="price-chip">₹$room_data[price] <small>/ night</small></span>
                </div>
                <div class="card-body p-4">
                  <h5>$room_data[name]</h5>
                  <div class="features mb-4">
                    <h6 class="mb-1">Features</h6>
                    $features_data
                  </div>
                  <div class="facilities mb-4">
                    <h6 class="mb-1">Facilities</h6>
                    $facilities_data
                  </div>
                  <div class="guests mb-4">
                    <h6 class="mb-1">Guests</h6>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                      $room_data[adult] Adults
                    </span>
                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                      $room_data[children] Children
                    </span>
                  </div>
                  $rating_data
                  <div class="d-flex gap-2 mb-1">
                    $book_btn
                    <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">Details</a>
                  </div>
                </div>
              </div>
            </div>
          data;

        }

      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="rooms.php" class="btn btn-outline-dark shadow-none">View all rooms <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>
  </div>

  <!-- Our Facilities -->

  <div class="section-title reveal">
    <span class="eyebrow">02 &middot; Perks</span>
    <h2 class="h-font m-0">All the <span class="gold-text">good stuff</span></h2>
    <div class="h-line"></div>
    <p>The little things that make a stay effortless.</p>
  </div>

  <div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
      <?php 
        $res = mysqli_query($con,"SELECT * FROM `facilities` ORDER BY `id` DESC LIMIT 5");
        $path = FACILITIES_IMG_PATH;

        while($row = mysqli_fetch_assoc($res)){
          echo<<<data
            <div class="col-lg-2 col-md-3 col-6 my-3 reveal">
              <div class="facility-tile text-center py-4 px-2 h-100">
                <img src="$path$row[icon]" width="52" alt="">
                <h5 class="mt-3 mb-0">$row[name]</h5>
              </div>
            </div>
          data;
        }
      ?>

      <div class="col-lg-12 text-center mt-5">
        <a href="facilities.php" class="btn btn-outline-dark shadow-none">All facilities <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="container reveal">
    <div class="stats-strip row text-center g-4 mx-0">
      <?php
        $room_total = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) c FROM `rooms` WHERE `status`=1 AND `removed`=0"))['c'];
        $avg_rating = mysqli_fetch_assoc(mysqli_query($con,"SELECT ROUND(AVG(rating),1) r FROM `rating_review`"))['r'];
      ?>
      <div class="col-6 col-md-3"><div class="num"><?php echo $room_total ?></div><div class="lbl">Room types</div></div>
      <div class="col-6 col-md-3"><div class="num">&#8377;0</div><div class="lbl">Hidden fees</div></div>
      <?php if($avg_rating): ?>
      <div class="col-6 col-md-3"><div class="num"><?php echo $avg_rating ?></div><div class="lbl">Average rating</div></div>
      <?php else: ?>
      <div class="col-6 col-md-3"><div class="num">New</div><div class="lbl">Now taking bookings</div></div>
      <?php endif; ?>
      <div class="col-6 col-md-3"><div class="num">24/7</div><div class="lbl">Concierge</div></div>
    </div>
  </div>

  <!-- Testimonials -->

  <div class="section-title reveal">
    <span class="eyebrow">03 &middot; Reviews</span>
    <h2 class="h-font m-0">Guests <span class="gold-text">love</span> it here</h2>
    <div class="h-line"></div>
    <p>Kind words from people who stayed with us.</p>
  </div>

  <div class="container mt-5">
    <div class="swiper swiper-testimonials">
      <div class="swiper-wrapper mb-5">
        <?php

          $review_q = "SELECT rr.*,uc.name AS uname, uc.profile, r.name AS rname FROM `rating_review` rr
            INNER JOIN `user_cred` uc ON rr.user_id = uc.id
            INNER JOIN `rooms` r ON rr.room_id = r.id
            ORDER BY `sr_no` DESC LIMIT 6";

          $review_res = mysqli_query($con,$review_q);
          $img_path = USERS_IMG_PATH;

          if(mysqli_num_rows($review_res)==0){
            echo "<div class='swiper-slide bg-white p-4 text-center'>
                <p class='mb-1' style='color:#fff;font-size:1.05rem'>No reviews yet &mdash; our first guests are on their way.</p>
                <p class='mb-0'>Stayed with us? Share your experience from <a href='bookings.php'>My bookings</a> after check-out.</p>
              </div>";
          }
          else
          {
            while($row = mysqli_fetch_assoc($review_res))
            {
              $stars = "<i class='bi bi-star-fill text-warning'></i> ";
              for($i=1; $i<$row['rating']; $i++){
                $stars .= " <i class='bi bi-star-fill text-warning'></i>";
              }
              // reviewers are shown with an initial badge instead of their profile photo
              $initial = htmlspecialchars(mb_strtoupper(mb_substr(html_entity_decode($row['uname']),0,1)));

              echo<<<slides
                <div class="swiper-slide bg-white p-4">
                  <div class="profile d-flex align-items-center mb-3">
                    <span class="avatar-initial" aria-hidden="true">$initial</span>
                    <h6 class="m-0 ms-2">$row[uname]</h6>
                  </div>
                  <p>
                    $row[review]
                  </p>
                  <div class="rating">
                    $stars
                  </div>
                </div>
              slides;
            }
          }
        
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
    <div class="col-lg-12 text-center mt-5">
      <a href="about.php" class="btn btn-outline-dark shadow-none">Our story <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
  </div>

  <!-- Reach us -->

  <div class="section-title reveal" id="reach-us">
    <span class="eyebrow">04 &middot; Find us</span>
    <h2 class="h-font m-0">Come say <span class="gold-text">hi</span></h2>
    <div class="h-line"></div>
    <p>We&rsquo;re easy to find and always happy to help.</p>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 p-3 mb-lg-0 mb-3 bg-white rounded shadow-sm">
        <iframe class="w-100 rounded" height="320px" src="<?php echo safe_url($contact_r['iframe']) ?>" loading="lazy"></iframe>
      </div>
      <div class="col-lg-4 col-md-4">
        <div class="bg-white p-4 rounded shadow-sm mb-4">
          <h5>Call us</h5>
          <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
            <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
          </a>
          <br>
          <?php 
            if($contact_r['pn2']!=''){
              echo<<<data
                <a href="tel: +$contact_r[pn2]" class="d-inline-block text-decoration-none text-dark">
                  <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                </a>
              data;
            }
          
          ?>
        </div>
        <div class="bg-white p-4 rounded shadow-sm mb-4">
          <h5>Follow us</h5>
          <?php 
            if($contact_r['tw']!=''){
              echo<<<data
                <a href="$contact_r[tw]" class="d-inline-block mb-3">
                  <span class="badge bg-light text-dark fs-6 p-2"> 
                  <i class="bi bi-twitter me-1"></i> Twitter
                  </span>
                </a>
                <br>
              data;
            }
          ?>

          <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
            <span class="badge bg-light text-dark fs-6 p-2"> 
            <i class="bi bi-facebook me-1"></i> Facebook
            </span>
          </a>
          <br>
          <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block">
            <span class="badge bg-light text-dark fs-6 p-2"> 
            <i class="bi bi-instagram me-1"></i> Instagram
            </span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Password reset modal and code -->

  <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="recovery-form">
          <div class="modal-header">
            <h5 class="modal-title d-flex align-items-center">
              <i class="bi bi-shield-lock fs-3 me-2"></i> Set up New Password
            </h5>
          </div>
          <div class="modal-body">
            <div class="mb-4">
              <label class="form-label">New Password</label>
              <input type="password" name="pass" required class="form-control shadow-none">
              <input type="hidden" name="email">
              <input type="hidden" name="token">
            </div>
            <div class="mb-2 text-end">
              <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">CANCEL</button>
              <button type="submit" class="btn btn-dark shadow-none">SUBMIT</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


  <?php require('inc/footer.php'); ?>

  <?php
  
    if(isset($_GET['account_recovery']))
    {
      $data = filteration($_GET);

      $t_date = date("Y-m-d");

      $query = select("SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
        [$data['email'],$data['token'],$t_date],'sss');

      if(mysqli_num_rows($query)==1)
      {
        echo<<<showModal
          <script>
            var myModal = document.getElementById('recoveryModal');

            myModal.querySelector("input[name='email']").value = '$data[email]';
            myModal.querySelector("input[name='token']").value = '$data[token]';

            var modal = bootstrap.Modal.getOrCreateInstance(myModal);
            modal.show();
          </script>
        showModal;
      }
      else{
        alert("error","Invalid or Expired Link !");
      }

    }

  ?>
  
  <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".swiper-container", {
      spaceBetween: 0,
      effect: "fade",
      speed: 1200,
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
      }
    });

    // only loop when there are enough reviews; otherwise Swiper clones the few cards
    var testimonialCount = document.querySelectorAll('.swiper-testimonials .swiper-slide').length;
    var swiper = new Swiper(".swiper-testimonials", {
      effect: "coverflow",
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: "auto",
      slidesPerView: "3",
      loop: testimonialCount > 3,
      coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });

    // recover account
    
    let recovery_form = document.getElementById('recovery-form');

    recovery_form.addEventListener('submit', (e)=>{
      e.preventDefault();

      let data = new FormData();

      data.append('email',recovery_form.elements['email'].value);
      data.append('token',recovery_form.elements['token'].value);
      data.append('pass',recovery_form.elements['pass'].value);
      data.append('recover_user','');

      var myModal = document.getElementById('recoveryModal');
      var modal = bootstrap.Modal.getInstance(myModal);
      modal.hide();

      let xhr = new XMLHttpRequest();
      xhr.open("POST","ajax/login_register.php",true);

      xhr.onload = function(){
        if(this.responseText == 'failed'){
          alert('error',"Account reset failed!");
        }
        else{
          alert('success',"Account Reset Successful !");
          recovery_form.reset();
        }
      }

      xhr.send(data);
    });

  </script>

</body>
</html>