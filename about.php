<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link  rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
  <?php require('inc/links.php'); ?>
  <title><?php echo $settings_r['site_title'] ?> - ABOUT</title>
  <style>
    .box{
      border-top-color: var(--teal) !important;
    }
  </style>
</head>
<body class="bg-light">

  <?php require('inc/header.php'); ?>

  <div class="section-title" style="margin-top:4rem">
    <span class="eyebrow">Our story</span>
    <h2 class="h-font m-0">About us</h2>
    <div class="h-line"></div>
    <p>A small hotel with a big welcome. We built Nivaara around one idea: every guest should leave more rested than they arrived.</p>
  </div>

  <div class="container">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
        <span class="eyebrow">Since day one</span>
        <h3 class="h-font mb-3" style="font-size:2.2rem">Hospitality, the way it should feel</h3>
        <p class="text-muted">
          We started with a simple promise: clean, calm rooms, fair prices and people who genuinely care.
          Every detail &mdash; from the mattress you sleep on to the coffee you wake up to &mdash; is chosen
          to make you feel at home.
        </p>
        <p class="text-muted mb-0">
          Whether you are here for business, a family trip or a quiet weekend away, our team is on hand
          around the clock to help you make the most of your stay.
        </p>
      </div>
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="images/about/about.jpg" class="w-100 rounded-4 shadow" style="border-radius:18px" alt="Hotel interior">
      </div>
    </div>
  </div>

  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/hotel.svg" width="70px">
          <h4 class="mt-3 h-font" style="font-size:1.6rem">100+ Rooms</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/customers.svg" width="70px">
          <h4 class="mt-3 h-font" style="font-size:1.6rem">200+ Guests</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/rating.svg" width="70px">
          <h4 class="mt-3 h-font" style="font-size:1.6rem">150+ Reviews</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="images/about/staff.svg" width="70px">
          <h4 class="mt-3 h-font" style="font-size:1.6rem">200+ Staff</h4>
        </div>
      </div>
    </div>
  </div>

  <?php
    // "Meet the team" only shows once real team members are added in Admin > Settings
    $about_r = selectAll('team_details');
    if(mysqli_num_rows($about_r) > 0):
  ?>
  <div class="section-title">
    <span class="eyebrow">The people</span>
    <h2 class="h-font m-0">Meet the team</h2>
    <div class="h-line"></div>
  </div>

  <div class="container px-4">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper mb-5">
        <?php 
          $path=ABOUT_IMG_PATH;
          while($row = mysqli_fetch_assoc($about_r)){
            echo<<<data
              <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="$path$row[picture]" class="w-100">
                <h5 class="mt-2">$row[name]</h5>
              </div>
            data;
          }
        
        ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
  <?php endif; ?>


  <?php require('inc/footer.php'); ?>

  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 40,
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
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
  </script>


</body>
</html>