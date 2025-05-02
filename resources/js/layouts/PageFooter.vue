<template>
    <!-- Footer -->
<footer class="border bg-dark text-center text-lg-start bg-body-tertiary text-muted">
 

  <!-- Section: Links  -->
  <section class="flex-row-reverse ">
    <div class="container text-light text-center text-md-start mt-5">
      <!-- Grid row -->
      <div class="row mt-3 flex-row-reverse">
        <!-- Grid column -->
        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4 text-end">
          <!-- Content -->
          <h6 class="text-uppercase fw-bold mb-4">
            جاکتابی
            <i class="fas fa-book-open me-3"></i>
          </h6>
          <p>
            جاکتابی یه خونه برای کتابخوناست
          </p>
        </div>
        <!-- Grid column -->

        <!-- Grid column -->
        <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4 text-end">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">
            دسترسی سریع
          </h6>
          <p>
            <router-link class="nav-link text-end" :to="{name: 'home'}">خانه</router-link>
          </p>
          <p>
            <router-link class="nav-link text-end" :to="{name: 'register'}">ثبت نام</router-link>          
         </p>

        </div>
        <!-- Grid column -->


        <!-- Grid column -->
        <div class="col-md-4 col-lg-3 col-xl-4 mx-auto mb-md-0 mb-4 text-end ">
          <!-- Links -->
          <h6 class="text-uppercase fw-bold mb-4">تماس با ما</h6>
          <p> hadithemohamadzade@gmail.com  <i class="fas fa-envelope me-3"></i></p>
          <p> alikhalpoor@gmail.com  <i class="fas fa-envelope me-3"></i></p>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->
  <!-- Copyright -->
  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2025 Copyright:
    <a class="text-reset fw-bold" href="https://mdbootstrap.com/">example.com</a>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->
</template>