
<?php require_once 'views/layout/header.php'?>
<?php require_once 'views/layout/menu.php'?>


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
                                <li class="breadcrumb-item"><a href="shop.html">shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">cart</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- cart main wrapper start -->
    <div class="cart-main-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Cart Table Area -->
                        <div class="cart-table table-responsive">
                            <form action="?act=cap-nhat-so-luong" method="POST">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="pro-thumbnail">ảnh sản phẩm</th>
                                        <th class="pro-title">Tên sản phẩm</th>
                                        <th class="pro-price">Giá Tiền</th>
                                        <th class="pro-quantity">Số lượng</th>
                                        <th class="pro-subtotal">Tổng tiền</th>
                                        <!-- <th class="pro-remove">Thao tác</th> -->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $tongGioHang = 0;
                                    foreach($chiTietGioHang as $key => $sanPham): 
                                    ?>
                                        <tr>
                                            <td class="pro-thumbnail">
                                                <a href="#"><img class="img-fluid" src="<?= $sanPham['hinh_anh'] ?>" alt="Product" /></a>
                                            </td>
                                            <td class="pro-title">
                                                <a href="#"><?= $sanPham['ten_san_pham'] ?></a>
                                            </td>
                                            <td class="pro-price">
                                                <span>
                                                    <?php if ($sanPham['gia_khuyen_mai']) { ?>
                                                        <?= formatPrice($sanPham['gia_khuyen_mai']) . ' đ' ?>
                                                    <?php } else { ?>
                                                        <?= formatPrice($sanPham['gia_san_pham']) . ' đ' ?>
                                                    <?php } ?>
                                                </span>
                                            </td>
                                            <td class="pro-quantity">
                                            <input type="number" name="so_luong[<?= $sanPham['san_pham_id'] ?>]" value="<?= $sanPham['so_luong'] ?>" min="0" class="form-control" style="width: 80px;">
                                            </td>
                                            <td class="pro-subtotal">
                                                <span>
                                                    <?php
                                                    $tongTien = 0;
                                                    if ($sanPham['gia_khuyen_mai']) {
                                                        $tongTien = $sanPham['gia_khuyen_mai'] * $sanPham['so_luong'];
                                                    } else {
                                                        $tongTien = $sanPham['gia_san_pham'] * $sanPham['so_luong'];
                                                    }
                                                    $tongGioHang +=$tongTien;
                                                    echo formatPrice($tongTien) . ' đ';
                                                    ?>
                                                </span>
                                            </td>
                                            <!-- <td class="pro-remove">
                                                <a href="?act=xoa-san-pham-khoi-gio-hang&san_pham_id=<?= $sanPham['san_pham_id'] ?>" 
                                                class="minicart-remove" 
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                    <i class="pe-7s-close"></i>
                                                </a>
                                            </td> -->
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- Cart Update Option -->
                                <!-- <div class="cart-update-option d-block d-md-flex justify-content-between">
                                    <div class="apply-coupon-wrapper">
                                        <form action="#" method="post" class=" d-block d-md-flex">
                                            <input type="text" placeholder="Enter Your Coupon Code" required />
                                            <button class="btn btn-sqr">Apply Coupon</button>
                                        </form>
                                    </div>
                                    <div class="cart-update">
                                        <button type="submit" class="btn btn-sqr">Update Cart</button>
                                    </div>
                                </div> -->
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 ml-auto">
                        <!-- Cart Calculation Area -->
                        <div class="cart-calculator-wrapper">
                            <div class="cart-calculate-items">
                                <h6>Cart Totals</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td>Tổng tiền hàng</td>
                                            <td>
                                                <?php
                                                $tongTienHang = 0;
                                                foreach($chiTietGioHang as $sanPham) {
                                                    if ($sanPham['gia_khuyen_mai']) {
                                                        $tongTienHang += $sanPham['gia_khuyen_mai'] * $sanPham['so_luong'];
                                                    } else {
                                                        $tongTienHang += $sanPham['gia_san_pham'] * $sanPham['so_luong'];
                                                    }
                                                }
                                                echo formatPrice($tongTienHang) . ' đ';
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phí vận chuyển</td>
                                            <td>30.000 đ</td>
                                        </tr>
                                        <tr class="total">
                                            <td>Tổng cộng</td>
                                            <td class="total-amount">
                                                <?php
                                                $phiVanChuyen = 30000;
                                                $tongCong = $tongTienHang + $phiVanChuyen;
                                                echo formatPrice($tongCong) . ' đ';
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <a href="<?= BASE_URL. '?act=thanh-toan' ?>" class="btn btn-sqr d-block">Tiến hàng đặt hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cart main wrapper end -->
</main>

<?php require_once 'views/layout/miniCart.php'?>

<!-- JS
============================================ -->

<?php require_once 'views/layout/footer.php'?>