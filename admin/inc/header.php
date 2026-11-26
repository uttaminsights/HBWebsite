<?php
  // brand name comes from Settings (site title)
  require_once(__DIR__.'/db_config.php');
  $admin_brand = mysqli_fetch_assoc(mysqli_query($con,"SELECT `site_title` FROM `settings` WHERE `sr_no`=1"));
  $admin_brand = $admin_brand ? $admin_brand['site_title'] : 'Admin';
  $admin_initial = htmlspecialchars(mb_substr(html_entity_decode($admin_brand),0,1));
?>
<div class="container-fluid admin-topbar text-light p-3 d-flex align-items-center justify-content-between sticky-top">
  <h3 class="mb-0 h-font"><span class="brand-mark"><?php echo $admin_initial ?></span> <?php echo $admin_brand ?> <span class="tag ms-2">Admin</span></h3>
  <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i> Log out</a>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid flex-lg-column align-items-stretch">
      <h4 class="mt-3 mb-2 text-light">NAVIGATION</h4>
      <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item">
            <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
          </li>
          <li class="nav-item">
            <button class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#bookingLinks">
              <span>Bookings</span>
              <span><i class="bi bi-caret-down-fill"></i></span>
            </button>
            <div class="collapse show px-3 small mb-1" id="bookingLinks">
              <ul class="nav nav-pills flex-column rounded border border-secondary">
                <li class="nav-item">
                  <a class="nav-link text-white" href="new_bookings.php">New Bookings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="refund_bookings.php">Refund Bookings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="booking_records.php">Booking Records</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="users.php">Users</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="user_queries.php">User Queries</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="rate_review.php">Ratings & Reviews</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="rooms.php">Rooms</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="features_facilities.php">Features & Facilities</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="carousel.php">Carousel</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="settings.php">Settings</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</div>