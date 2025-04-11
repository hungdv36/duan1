
<div class="offcanvas-minicart-wrapper">
    <div class="minicart-inner">
        <div class="offcanvas-overlay"></div>
        <div class="minicart-inner-content">
            <div class="minicart-close">
                <i class="pe-7s-close"></i>
            </div>
            <div class="minicart-content-box">
                <div class="minicart-item-wrapper">
                    <ul>
                        <?php if(isset($chiTietGioHang) && !empty($chiTietGioHang)): ?>
                            <?php foreach($chiTietGioHang as $sanPham): ?>
                                <li class="minicart-item">
                                    <div class="minicart-thumb">
                                        <a href="#">
                                            <img src="<?= $sanPham['hinh_anh'] ?>" alt="<?= $sanPham['ten_san_pham'] ?>">
                                        </a>
                                    </div>
                                    <div class="minicart-content">
                                        <h3 class="product-name">
                                            <a href="#"><?= $sanPham['ten_san_pham'] ?></a>
                                        </h3>
                                        <p>
                                            <span class="cart-quantity"><?= $sanPham['so_luong'] ?> <strong>&times;</strong></span>
                                            <span class="cart-price">
                                                <?php if ($sanPham['gia_khuyen_mai']) { ?>
                                                    <?= formatPrice($sanPham['gia_khuyen_mai']) . ' đ' ?>
                                                <?php } else { ?>
                                                    <?= formatPrice($sanPham['gia_san_pham']) . ' đ' ?>
                                                <?php } ?>
                                            </span>
                                        </p>
                                    </div>
                                    <form action="?act=xoa-san-pham-khoi-gio-hang" method="POST" style="display: inline;">
                                        <input type="hidden" name="san_pham_id" value="<?= $sanPham['san_pham_id'] ?>">
                                        <button type="submit" class="minicart-remove">
                                            <i class="pe-7s-close"></i>
                                        </button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="minicart-item">
                                <div class="minicart-content">
                                    <p>Giỏ hàng trống</p>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <div class="minicart-pricing-box">
                    <ul>
                        <li>
                            <span>Tổng tiền hàng</span>
                            <span>
                                <?php
                                $tongTienHang = 0;
                                if(isset($chiTietGioHang) && !empty($chiTietGioHang)) {
                                    foreach($chiTietGioHang as $sanPham) {
                                        if ($sanPham['gia_khuyen_mai']) {
                                            $tongTienHang += $sanPham['gia_khuyen_mai'] * $sanPham['so_luong'];
                                        } else {
                                            $tongTienHang += $sanPham['gia_san_pham'] * $sanPham['so_luong'];
                                        }
                                    }
                                }
                                echo formatPrice($tongTienHang) . ' đ';
                                ?>
                            </span>
                        </li>
                        <li>
                            <span>Phí vận chuyển</span>
                            <span>30.000 đ</span>
                        </li>
                        <li class="total">
                            <span>Tổng cộng</span>
                            <span>
                                <?php
                                $phiVanChuyen = 30000;
                                $tongCong = $tongTienHang + $phiVanChuyen;
                                echo formatPrice($tongCong) . ' đ';
                                ?>
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="minicart-button">
                    <a href="<?=  BASE_URL . '?act=gio-hang'   ?>"><i class="fa fa-shopping-cart"></i> Xem giỏ hàng</a>
                    <a href="?act=thanh-toan"><i class="fa fa-share"></i> Thanh toán</a>
                </div>
            </div>
        </div>
    </div>
</div>