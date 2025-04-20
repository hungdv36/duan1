<?php
 require_once 'views/layout/header.php';
 require_once 'views/layout/menu.php';
 ?>
 
 <main>
     <!-- breadcrumb area start -->
     <div class="breadcrumb-area">
         <div class="container">
             <div class="row">
                 <div class="col-12">
                     <div class="breadcrumb-wrap">
                         <nav aria-label="breadcrumb">
                             <ul class="breadcrumb">
                                 <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a></li>
                                 <li class="breadcrumb-item active" aria-current="page">Đăng ký</li>
                             </ul>
                         </nav>
                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- breadcrumb area end -->
 
     <!-- login register wrapper start -->
     <div class="login-register-wrapper section-padding">
         <div class="container" style="max-width: 40vw">
             <div class="member-area-from-wrap">
                 <div class="row">
                     <!-- Register Content Start -->
                     <div class="col-lg-12">
                         <div class="login-reg-form-wrap sign-up-form">
                             <h5 class="text-center">ĐĂNG KÝ</h5>
                             <?php if (isset($_SESSION['error']) && $_SESSION['flash'] && is_string($_SESSION['error'])) : ?>
                                 <p class="text-danger login-box-msg"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?></p>
                             <?php else : ?>
                                 <p class="login-box-msg">Tạo tài khoản mới</p>
                             <?php endif; ?>
                             <form action="<?= BASE_URL . '?act=post-register' ?>" method="post">
                                 <div class="single-input-item">
                                     <input type="text" placeholder="Họ và tên" name="ho_ten" required />
                                 </div>
                                 <div class="single-input-item">
                                     <input type="email" placeholder="Email" name="email" required />
                                 </div>
                                 <div class="single-input-item">
                                     <input type="password" placeholder="Mật khẩu" name="password" required />
                                 </div>
                                 <div class="single-input-item">
                                     <input type="password" placeholder="Xác nhận mật khẩu" name="confirm_password" required />
                                 </div>
                                 <div class="single-input-item text-center">
                                     <button class="btn btn-sqr">Đăng ký</button>
                                 </div>
                             </form>
                             <p class="text-center mt-3">
                                 Đã có tài khoản? <a href="<?= BASE_URL . '?act=login' ?>">Đăng nhập</a>
                             </p>
                         </div>
                     </div>
                     <!-- Register Content End -->
                 </div>
             </div>
         </div>
     </div>
     <!-- login register wrapper end -->
 </main>
 
 <?php require_once 'views/layout/footer.php'; ?>