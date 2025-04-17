<?php
require_once 'layout/header.php';
require_once 'layout/menu.php';

// Khởi tạo model
$modelSanPham = new SanPham();
$modelDanhMuc = new DanhMuc();

// Lấy danh sách danh mục
$danh_mucs = $modelDanhMuc->danhSachDanhMuc();

// Khởi tạo bộ lọc
/** @var array $filters */
$filters = [
    'danh_muc_id' => null,
    'gia_min' => null,
    'gia_max' => null,
];

// Xử lý bộ lọc từ GET/POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filters['danh_muc_id'] = isset($_POST['danh_muc_id']) ? (int)$_POST['danh_muc_id'] : null;
    $filters['gia_min'] = isset($_POST['gia_min']) && is_numeric($_POST['gia_min']) ? (float)$_POST['gia_min'] : null;
    $filters['gia_max'] = isset($_POST['gia_max']) && is_numeric($_POST['gia_max']) ? (float)$_POST['gia_max'] : null;
} elseif (isset($_GET['danh_muc_id'])) {
    $filters['danh_muc_id'] = (int)$_GET['danh_muc_id'];
}

// Lấy danh sách sản phẩm
$san_phams = $modelSanPham->getAllSanPham($filters);
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
                                <li class="breadcrumb-item active" aria-current="page">Cửa hàng</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- page main wrapper start -->
    <div class="shop-main-wrapper section-padding">
        <div class="container">
            <div class="row">
                <!-- sidebar area start -->
                <div class="col-lg-3 order-2 order-lg-1">
                    <aside class="sidebar-wrapper">
                        <!-- single sidebar start -->
                        <div class="sidebar-single">
                            <h5 class="sidebar-title">Danh mục</h5>
                            <div class="sidebar-body">
                                <ul class="shop-categories">
                                    <?php foreach ($danh_mucs as $danh_muc): ?>
                                        <li>
                                            <a href="<?= BASE_URL . '?act=shop&danh_muc_id=' . $danh_muc['id'] ?>">
                                                <?= htmlspecialchars($danh_muc['ten_danh_muc']) ?>
                                                <span>(<?php
                                                    $count = count($modelSanPham->listSanPhamDanhMuc($danh_muc['id']));
                                                    echo $count;
                                                ?>)</span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <!-- single sidebar end -->

                        <!-- single sidebar start -->
                        <div class="sidebar-single">
                            <h5 class="sidebar-title">Lọc theo giá</h5>
                            <div class="sidebar-body">
                                <form action="<?= BASE_URL . '?act=shop' ?>" method="post">
                                    <div class="price-range-wrap mb-4">
                                        <div class="price-input">
                                            <label for="gia_min">Từ: </label>
                                            <input type="number" name="gia_min" id="gia_min" value="<?= isset($_POST['gia_min']) ? $_POST['gia_min'] : '' ?>" placeholder="0" min="0">
                                            <label for="gia_max">Đến: </label>
                                            <input type="number" name="gia_max" id="gia_max" value="<?= isset($_POST['gia_max']) ? $_POST['gia_max'] : '' ?>" placeholder="10000000" min="0">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sqr">Lọc</button>
                                </form>
                            </div>
                        </div>
                        <!-- single sidebar end -->

                        <!-- single sidebar start -->
                        <div class="sidebar-banner">
                            <div class="img-container">
                                <a href="#">
                                    <img src="assets/img/banner/sidebar-banner.jpg" alt="Banner">
                                </a>
                            </div>
                        </div>
                        <!-- single sidebar end -->
                    </aside>
                </div>
                <!-- sidebar area end -->

                <!-- shop main wrapper start -->
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="shop-product-wrapper">
                        <!-- shop product top wrap start -->
                        <div class="shop-top-bar">
                            <div class="row align-items-center">
                                <div class="col-lg-7 col-md-6 order-2 order-md-1">
                                    <div class="top-bar-left">
                                        <div class="product-view-mode">
                                            <a class="active" href="#" data-target="grid-view" data-bs-toggle="tooltip" title="Grid View"><i class="fa fa-th"></i></a>
                                            <a href="#" data-target="list-view" data-bs-toggle="tooltip" title="List View"><i class="fa fa-list"></i></a>
                                        </div>
                                        <div class="product-amount">
                                            <p>Hiển thị 1–<?= count($san_phams) ?> của <?= count($san_phams) ?> sản phẩm</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-6 order-1 order-md-2">
                                    <div class="top-bar-right">
                                        <div class="product-short">
                                            <p>Sắp xếp theo: </p>
                                            <select class="nice-select" name="sortby">
                                                <option value="trending">Mức độ liên quan</option>
                                                <option value="sales">Tên (A - Z)</option>
                                                <option value="sales-desc">Tên (Z - A)</option>
                                                <option value="price-asc">Giá (Thấp > Cao)</option>
                                                <option value="price-desc">Giá (Cao > Thấp)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- shop product top wrap end -->

                        <!-- product item list wrapper start -->
                        <div class="shop-product-wrap grid-view row mbn-30">
                            <?php foreach ($san_phams as $san_pham): ?>
                                <!-- product single item start -->
                                <div class="col-md-4 col-sm-6">
                                    <!-- product grid start -->
                                    <div class="product-item">
                                        <figure class="product-thumb">
                                            <a href="<?= BASE_URL . '?act=san-pham-tri-tiet&id_san_pham=' . $san_pham['id'] ?>">
                                                <img class="pri-img" src="<?= htmlspecialchars($san_pham['hinh_anh'] ?? 'assets/img/product/default.jpg') ?>" alt="product">
                                                <?php
                                                $anh_phu = $modelSanPham->getListAnhSanPham($san_pham['id']);
                                                $anh_phu_url = !empty($anh_phu) ? htmlspecialchars($anh_phu[0]['anh_san_pham'] ?? 'assets/img/product/default.jpg') : htmlspecialchars($san_pham['hinh_anh'] ?? 'assets/img/product/default.jpg');
                                                ?>
                                                <img class="sec-img" src="<?= $anh_phu_url ?>" alt="product">
                                            </a>
                                            <div class="product-badge">
                                                <?php
                                                $ngay_nhap = new DateTime($san_pham['ngay_nhap'] ?? 'now');
                                                $ngay_hien_tai = new DateTime();
                                                $tinh_ngay = $ngay_hien_tai->diff($ngay_nhap);
                                                if ($tinh_ngay->days <= 7):
                                                ?>
                                                    <div class="product-label new">
                                                        <span>Mới</span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($san_pham['gia_khuyen_mai'] ?? 0 > 0): ?>
                                                    <div class="product-label discount">
                                                        <span>Giảm giá</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="button-group">
                                                <a href="<?= BASE_URL . '?act=wishlist&add=' . $san_pham['id'] ?>" data-bs-toggle="tooltip" data-bs-placement="left" title="Thêm vào yêu thích"><i class="pe-7s-like"></i></a>
                                                <a href="<?= BASE_URL . '?act=compare&add=' . $san_pham['id'] ?>" data-bs-toggle="tooltip" data-bs-placement="left" title="So sánh"><i class="pe-7s-refresh-2"></i></a>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick_view_<?= $san_pham['id'] ?>"><span data-bs-toggle="tooltip" data-bs-placement="left" title="Xem nhanh"><i class="pe-7s-search"></i></span></a>
                                            </div>
                                            <div class="cart-hover">
                                                <form action="<?= BASE_URL . '?act=them-gio-hang' ?>" method="post">
                                                    <input type="hidden" name="san_pham_id" value="<?= $san_pham['id'] ?>">
                                                    <input type="hidden" name="so_luong" value="1">
                                                    <button type="submit" class="btn btn-cart">Thêm vào giỏ</button>
                                                </form>
                                            </div>
                                        </figure>
                                        <div class="product-caption text-center">
                                            <div class="product-identity">
                                                <p class="manufacturer-name"><a href="#"><?= htmlspecialchars($san_pham['ten_danh_muc']) ?></a></p>
                                            </div>
                                            <h6 class="product-name">
                                                <a href="<?= BASE_URL . '?act=san-pham-tri-tiet&id_san_pham=' . $san_pham['id'] ?>"><?= htmlspecialchars($san_pham['ten_san_pham']) ?></a>
                                            </h6>
                                            <div class="price-box">
                                                <?php if ($san_pham['gia_khuyen_mai'] ?? 0 > 0): ?>
                                                    <span class="price-regular"><?= formatPrice($san_pham['gia_khuyen_mai']) ?>₫</span>
                                                    <span class="price-old"><del><?= formatPrice($san_pham['gia_san_pham']) ?>₫</del></span>
                                                <?php else: ?>
                                                    <span class="price-regular"><?= formatPrice($san_pham['gia_san_pham']) ?>₫</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- product grid end -->

                                    <!-- product list item start -->
                                    <div class="product-list-item">
                                        <figure class="product-thumb">
                                            <a href="<?= BASE_URL . '?act=san-pham-tri-tiet&id_san_pham=' . $san_pham['id'] ?>">
                                                <img class="pri-img" src="<?= htmlspecialchars($san_pham['hinh_anh'] ?? 'assets/img/product/default.jpg') ?>" alt="product">
                                                <img class="sec-img" src="<?= $anh_phu_url ?>" alt="product">
                                            </a>
                                            <div class="product-badge">
                                                <?php if ($tinh_ngay->days <= 7): ?>
                                                    <div class="product-label new">
                                                        <span>Mới</span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($san_pham['gia_khuyen_mai'] ?? 0 > 0): ?>
                                                    <div class="product-label discount">
                                                        <span>Giảm giá</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="button-group">
                                                <a href="<?= BASE_URL . '?act=wishlist&add=' . $san_pham['id'] ?>" data-bs-toggle="tooltip" data-bs-placement="left" title="Thêm vào yêu thích"><i class="pe-7s-like"></i></a>
                                                <a href="<?= BASE_URL . '?act=compare&add=' . $san_pham['id'] ?>" data-bs-toggle="tooltip" data-bs-placement="left" title="So sánh"><i class="pe-7s-refresh-2"></i></a>
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quick_view_<?= $san_pham['id'] ?>"><span data-bs-toggle="tooltip" data-bs-placement="left" title="Xem nhanh"><i class="pe-7s-search"></i></span></a>
                                            </div>
                                            <div class="cart-hover">
                                                <form action="<?= BASE_URL . '?act=them-gio-hang' ?>" method="post">
                                                    <input type="hidden" name="san_pham_id" value="<?= $san_pham['id'] ?>">
                                                    <input type="hidden" name="so_luong" value="1">
                                                    <button type="submit" class="btn btn-cart">Thêm vào giỏ</button>
                                                </form>
                                            </div>
                                        </figure>
                                        <div class="product-content-list">
                                            <div class="manufacturer-name">
                                                <a href="#"><?= htmlspecialchars($san_pham['ten_danh_muc']) ?></a>
                                            </div>
                                            <h5 class="product-name">
                                                <a href="<?= BASE_URL . '?act=san-pham-tri-tiet&id_san_pham=' . $san_pham['id'] ?>"><?= htmlspecialchars($san_pham['ten_san_pham']) ?></a>
                                            </h5>
                                            <div class="price-box">
                                                <?php if ($san_pham['gia_khuyen_mai'] ?? 0 > 0): ?>
                                                    <span class="price-regular"><?= formatPrice($san_pham['gia_khuyen_mai']) ?>₫</span>
                                                    <span class="price-old"><del><?= formatPrice($san_pham['gia_san_pham']) ?>₫</del></span>
                                                <?php else: ?>
                                                    <span class="price-regular"><?= formatPrice($san_pham['gia_san_pham']) ?>₫</span>
                                                <?php endif; ?>
                                            </div>
                                            <p><?= htmlspecialchars($san_pham['mo_ta'] ?? 'Không có mô tả.') ?></p>
                                        </div>
                                    </div>
                                    <!-- product list item end -->
                                </div>
                                <!-- product single item end -->

                                <!-- Quick View Modal -->
                                <div class="modal fade" id="quick_view_<?= $san_pham['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><?= htmlspecialchars($san_pham['ten_san_pham']) ?></h5>
                                                <button type="button" class="close" data-bs-dismiss="modal">×</button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?= htmlspecialchars($san_pham['hinh_anh'] ?? 'assets/img/product/default.jpg') ?>" alt="product" style="max-width: 100%;">
                                                <p>Giá: <?php if ($san_pham['gia_khuyen_mai'] ?? 0 > 0): ?>
                                                        <?= formatPrice($san_pham['gia_khuyen_mai']) ?>₫
                                                        <del><?= formatPrice($san_pham['gia_san_pham']) ?>₫</del>
                                                    <?php else: ?>
                                                        <?= formatPrice($san_pham['gia_san_pham']) ?>₫
                                                    <?php endif; ?></p>
                                                <p>Số lượng: <?= $san_pham['so_luong'] ?? 'Không xác định' ?></p>
                                                <p><?= htmlspecialchars($san_pham['mo_ta'] ?? 'Không có mô tả.') ?></p>
                                                <form action="<?= BASE_URL . '?act=them-gio-hang' ?>" method="post">
                                                    <input type="hidden" name="san_pham_id" value="<?= $san_pham['id'] ?>">
                                                    <input type="number" name="so_luong" value="1" min="1" style="width: 60px;">
                                                    <button type="submit" class="btn btn-cart">Thêm vào giỏ</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- product item list wrapper end -->

                        <!-- start pagination area -->
                        <div class="paginatoin-area text-center">
                            <ul class="pagination-box">
                                <li><a class="previous" href="#"><i class="pe-7s-angle-left"></i></a></li>
                                <li class="active"><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a class="next" href="#"><i class="pe-7s-angle-right"></i></a></li>
                            </ul>
                        </div>
                        <!-- end pagination area -->
                    </div>
                </div>
                <!-- shop main wrapper end -->
            </div>
        </div>
    </div>
    <!-- page main wrapper end -->
</main>

<?php require_once 'layout/footer.php'; ?>