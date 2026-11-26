<footer class="site-footer">
  <div class="container">
    <div class="row align-items-end mb-5 g-4">
      <div class="col-lg-8">
        <span class="eyebrow">Reservations open</span>
        <h2 class="footer-cta">Your next trip<br>starts <span class="gold-text">right here.</span></h2>
      </div>
      <div class="col-lg-4 text-lg-end">
        <a href="rooms.php" class="btn custom-bg px-4 py-3">Book your room <i class="bi bi-arrow-up-right ms-2"></i></a>
      </div>
    </div>
    <div class="row g-5 pt-4" style="border-top:1px solid var(--line)">
      <div class="col-lg-5">
        <h3 class="mb-3"><?php echo $settings_r['site_title'] ?></h3>
        <p class="pe-lg-5"><?php echo $settings_r['site_about'] ?></p>
        <div class="social mt-4">
          <?php if($contact_r['fb']!=''): ?>
            <a href="<?php echo safe_url($contact_r['fb']) ?>" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <?php endif; ?>
          <?php if($contact_r['insta']!=''): ?>
            <a href="<?php echo safe_url($contact_r['insta']) ?>" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <?php endif; ?>
          <?php if($contact_r['tw']!=''): ?>
            <a href="<?php echo safe_url($contact_r['tw']) ?>" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6>Explore</h6>
        <ul class="list-unstyled d-grid gap-2">
          <li><a href="index.php">Home</a></li>
          <li><a href="rooms.php">Rooms</a></li>
          <li><a href="facilities.php">Facilities</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6>Your stay</h6>
        <ul class="list-unstyled d-grid gap-2">
          <li><a href="bookings.php">My bookings</a></li>
          <li><a href="profile.php">Profile</a></li>
          <li><a href="rooms.php">Availability</a></li>
        </ul>
      </div>
      <div class="col-lg-3">
        <h6>Get in touch</h6>
        <ul class="list-unstyled d-grid gap-2">
          <li><i class="bi bi-geo-alt me-2" style="color:var(--gold)"></i><?php echo $contact_r['address'] ?></li>
          <li><a href="tel:+<?php echo $contact_r['pn1'] ?>"><i class="bi bi-telephone me-2" style="color:var(--gold)"></i>+<?php echo $contact_r['pn1'] ?></a></li>
          <li><a href="mailto:<?php echo $contact_r['email'] ?>"><i class="bi bi-envelope me-2" style="color:var(--gold)"></i><?php echo $contact_r['email'] ?></a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="wordmark" aria-hidden="true"><?php echo strtoupper(html_entity_decode($settings_r['site_title'])) ?></div>
  <div class="container">
    <div class="bottom d-flex flex-column flex-md-row justify-content-between gap-2">
      <span>&copy; <?php echo date('Y') ?> <?php echo $settings_r['site_title'] ?>. All rights reserved.</span>
      <span>Crafted by <span class="gold-text fw-bold">UttamInsights</span></span>
    </div>
  </div>
</footer>
<div id="scroll-progress"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>

  function alert(type,msg,position='body')
  {
    let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
    let element = document.createElement('div');
    element.innerHTML = `
      <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
        <strong class="me-3">${msg}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    `;

    if(position=='body'){
      document.body.append(element);
      element.classList.add('custom-alert');
    }
    else{
      document.getElementById(position).appendChild(element);
    }
    setTimeout(remAlert, 3000);
  }

  function remAlert(){
    document.getElementsByClassName('alert')[0].remove();
  }

  function setActive()
  {
    let navbar = document.getElementById('nav-bar');
    let a_tags = navbar.getElementsByTagName('a');

    for(i=0; i<a_tags.length; i++)
    {
      let file = a_tags[i].href.split('/').pop();
      let file_name = file.split('.')[0];

      if(document.location.href.indexOf(file_name) >= 0){
        a_tags[i].classList.add('active');
      }

    }
  }

  let register_form = document.getElementById('register-form');

  register_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();

    data.append('name',register_form.elements['name'].value);
    data.append('email',register_form.elements['email'].value);
    data.append('phonenum',register_form.elements['phonenum'].value);
    data.append('address',register_form.elements['address'].value);
    data.append('pincode',register_form.elements['pincode'].value);
    data.append('dob',register_form.elements['dob'].value);
    data.append('pass',register_form.elements['pass'].value);
    data.append('cpass',register_form.elements['cpass'].value);
    data.append('profile',register_form.elements['profile'].files[0]);
    data.append('register','');

    var myModal = document.getElementById('registerModal');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/login_register.php",true);

    xhr.onload = function(){
      if(this.responseText == 'pass_mismatch'){
        alert('error',"Password Mismatch!");
      }
      else if(this.responseText == 'email_already'){
        alert('error',"Email is already registered!");
      }
      else if(this.responseText == 'phone_already'){
        alert('error',"Phone number is already registered!");
      }
      else if(this.responseText == 'inv_img'){
        alert('error',"Only JPG, WEBP & PNG images are allowed!");
      }
      else if(this.responseText == 'upd_failed'){
        alert('error',"Image upload failed!");
      }
      else if(this.responseText == 'mail_failed'){
        alert('error',"Cannot send confirmation email! Server down!");
      }
      else if(this.responseText == 'ins_failed'){
        alert('error',"Registration failed! Server down!");
      }
      else if(this.responseText == 'verified'){
        alert('success',"Account created! You can sign in now.");
        register_form.reset();
      }
      else{
        alert('success',"Registration successful. Confirmation link sent to email!");
        register_form.reset();
      }
    }

    xhr.send(data);
  });

  let login_form = document.getElementById('login-form');

  login_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();

    data.append('email_mob',login_form.elements['email_mob'].value);
    data.append('pass',login_form.elements['pass'].value);
    data.append('login','');

    var myModal = document.getElementById('loginModal');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/login_register.php",true);

    xhr.onload = function(){
      if(this.responseText == 'inv_email_mob'){
        alert('error',"Invalid Email or Mobile Number!");
      }
      else if(this.responseText == 'not_verified'){
        alert('error',"Email is not verified!");
      }
      else if(this.responseText == 'inactive'){
        alert('error',"Account Suspended! Please contact Admin.");
      }
      else if(this.responseText == 'invalid_pass'){
        alert('error',"Incorrect Password!");
      }
      else{
        let fileurl = window.location.href.split('/').pop().split('?').shift();
        if(fileurl == 'room_details.php'){
          window.location = window.location.href;
        }
        else{
          window.location = window.location.pathname;
        }
      }
    }

    xhr.send(data);
  });

  let forgot_form = document.getElementById('forgot-form');

  forgot_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();

    data.append('email',forgot_form.elements['email'].value);
    data.append('forgot_pass','');

    var myModal = document.getElementById('forgotModal');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/login_register.php",true);

    xhr.onload = function(){
      if(this.responseText == 'inv_email'){
        alert('error',"Invalid Email !");
      }
      else if(this.responseText == 'not_verified'){
        alert('error',"Email is not verified! Please contact Admin");
      }
      else if(this.responseText == 'inactive'){
        alert('error',"Account Suspended! Please contact Admin.");
      }
      else if(this.responseText == 'mail_failed'){
        alert('error',"Cannot send email. Server Down!");
      }
      else if(this.responseText == 'upd_failed'){
        alert('error',"Account recovery failed. Server Down!");
      }
      else{
        alert('success',"Reset link sent to email!");
        forgot_form.reset();
      }
    }

    xhr.send(data);
  });

  function checkLoginToBook(status,room_id){
    if(status){
      window.location.href='confirm_booking.php?id='+room_id;
    }
    else{
      alert('error','Please login to book room!');
    }
  }

  setActive();

  // navbar shrink + scroll progress
  (function(){
    const nav = document.getElementById('nav-bar');
    const bar = document.getElementById('scroll-progress');
    function onScroll(){
      const y = window.scrollY;
      if(nav) nav.classList.toggle('scrolled', y > 40);
      const h = document.documentElement.scrollHeight - window.innerHeight;
      if(bar) bar.style.width = (h > 0 ? (y / h) * 100 : 0) + '%';
    }
    window.addEventListener('scroll', onScroll, {passive:true});
    onScroll();
  })();

  // reveal-on-scroll for elements marked .reveal
  (function(){
    const els = document.querySelectorAll('.reveal');
    if(!('IntersectionObserver' in window)){ els.forEach(e=>e.classList.add('in')); return; }
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(en=>{ if(en.isIntersecting){ en.target.classList.add('in'); io.unobserve(en.target); } });
    },{threshold:.12});
    els.forEach(e=>io.observe(e));
  })();

</script>