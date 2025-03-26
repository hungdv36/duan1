<?php include './views/layout/header.php'; ?>
<!-- Navbar -->
<?php include './views/layout/navbar.php'; ?>

<!-- /.navbar -->

<!-- Main Sidebar Container -->
<?php include './views/layout/sidebar.php'; ?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Quản lý danh sách sản phẩm</h1>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="card card-solid">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-sm-6">
            <div class="col-12">
              <img style="width: 400px; height: 400px" src="<?= BASE_URL . $sanPham['hinh_anh'] ?>" class="product-image" alt="Product Image">
            </div>
            <div class="col-12 product-image-thumbs">
              <?php foreach ($listAnhSanPham as $key => $anhSP): ?>
                <div class="product-image-thumb <?= $anhSP[$key] == 0 ? 'active' : '' ?>"><img src="<?= BASE_URL . $anhSP['link_hinh_anh']; ?>" alt="Product Image"></div>
              <?php endforeach ?>
            </div>
          </div>
          <div class="col-12 col-sm-6">
            <h3 class="my-3">Tên sản phẩm: <?= $sanPham['ten_san_pham'] ?></h3>
            <hr>
            <h4 class="mt-3">Giá tiền: <small><?= $sanPham['gia_san_pham'] ?></small></h4>
            <h4 class="mt-3">Giá khuyến mại: <small><?= $sanPham['gia_khuyen_mai'] ?></small></h4>
            <h4 class="mt-3">Số lượng: <small><?= $sanPham['so_luong'] ?></small></h4>
            <h4 class="mt-3">Lượt xem: <small><?= $sanPham['luot_xem'] ?></small></h4>
            <h4 class="mt-3">Ngày nhập: <small><?= $sanPham['ngay_nhap'] ?></small></h4>
            <h4 class="mt-3">Danh mục: <small><?= $sanPham['ten_danh_muc'] ?></small></h4>
            <h4 class="mt-3">Trạng thái: <small><?= $sanPham['trang_thai'] == 1 ? 'Còn hàng' : 'Hết hàng' ?></small></h4>
            <h4 class="mt-3">Mô tả: <small><?= $sanPham['mo_ta'] ?></small></h4>


          </div>
        </div>
        <div class="row mt-4">
          <nav class="w-100">
            <div class="nav nav-tabs" id="product-tab" role="tablist">
              <a class="nav-item nav-link active" id="product-desc-tab" data-bs-toggle="tab" href="#binh-luan" role="tab"
                aria-controls="product-desc" aria-selected="true">Bình luận sản phẩm</a>
            </div>
          </nav>
          <div class="tab-content p-3 w-100" id="nav-tabContent">
            <div class="tab-pane fade show active" id="binh-luan" role="tabpanel" aria-labelledby="product-desc-tab">
              <div class="container-fluid">
                <div class="table-responsive">
                  <table class="table table-striped table-hover text-center">
                    <thead class="table-dark">
                      <tr>
                        <th>#</th>
                        <th>Tên người bình luận</th>
                        <th>Nội dung</th>
                        <th>Ngày đăng</th>
                        <th>Thao tác</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1</td>
                        <td>Đào Văn Hùng</td>
                        <td>Đồng hồ đẹp</td>
                        <td>25/03/2025</td>
                        <td>
                          <div class="btn-group">
                            <a href="#" class="btn btn-warning btn-sm">Ẩn</a>
                            <a href="#" class="btn btn-danger btn-sm">Xóa</a>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>2</td>
                        <td>Nguyễn Thu Trang</td>
                        <td>Đồng hồ đẹp</td>
                        <td>25/03/2025</td>
                        <td>
                          <div class="btn-group">
                            <a href="#" class="btn btn-warning btn-sm">Ẩn</a>
                            <a href="#" class="btn btn-danger btn-sm">Xóa</a>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include './views/layout/footer.php'; ?>

<!-- Page specific script -->
<script>
  $(function() {
    $("#example1").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>
<!-- Code injected by live-server -->
<script>
  $(document).ready(function() {
    $('.product-image-thumb').on('click', function() {
      var $image_element = $(this).find('img')
      $('.product-image').prop('src', $image_element.attr('src'))
      $('.product-image-thumb.active').removeClass('active')
      $(this).addClass('active')
    })
  })
</script>
</body>

</html>