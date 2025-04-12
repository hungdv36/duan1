<?php include './views/layout/header.php'; ?>
<?php include './views/layout/navbar.php'; ?>
<?php include './views/layout/sidebar.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="content-wrapper">
  <section class="content py-4">
    <div class="container-fluid">
      <div class="row g-4">

        <!-- Tổng số đơn hàng -->
        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h4 class="card-title mb-1"><?php echo $totalOrders; ?></h4>
                <p class="card-text mb-0">Tổng số đơn hàng</p>
              </div>
              <div class="fs-1">
                <i class="fas fa-shopping-cart"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Tổng doanh thu -->
        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h4 class="card-title mb-1"><?php echo number_format($totalRevenue, 0, ',', '.'); ?> VNĐ</h4>
                <p class="card-text mb-0">Tổng doanh thu</p>
              </div>
              <div class="fs-1">
                <i class="fas fa-coins"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Tổng số sản phẩm -->
        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h4 class="card-title mb-1"><?php echo $totalProducts; ?></h4>
                <p class="card-text mb-0">Tổng số sản phẩm</p>
              </div>
              <div class="fs-1">
                <i class="fas fa-boxes"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Tổng số người dùng -->
        <div class="col-md-3 col-sm-6">
          <div class="card shadow-sm border-0 bg-danger text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <h4 class="card-title mb-1"><?php echo $totalUsers; ?></h4>
                <p class="card-text mb-0">Tổng số người dùng</p>
              </div>
              <div class="fs-1">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>
        </div>

      </div> <!-- row -->
    </div>
    <div class="row g-3 mb-3">
      <div class="col-xxl-12 col-xl-12">
        
        <div class="card-body">
          <canvas id="revenueChart" width="400" height="200"></canvas>
        </div>

        <script>
          const revenueData = <?php echo json_encode($revenueByDate); ?>;

          const labels = revenueData.map(item => item.date);
          const data = revenueData.map(item => item.revenue);

          // Tạo biểu đồ
          const ctx = document.getElementById('revenueChart').getContext('2d');
          const revenueChart = new Chart(ctx, {
            type: 'line', // Loại biểu đồ
            data: {
              labels: labels, // Ngày
              datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: data, // Doanh thu
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                tension: 0.4 // Đường cong mượt
              }]
            },
            options: {
              responsive: true,
              plugins: {
                legend: {
                  display: true,
                  position: 'top'
                }
              },
              scales: {
                x: {
                  title: {
                    display: true,
                    text: 'Ngày'
                  }
                },
                y: {
                  title: {
                    display: true,
                    text: 'Doanh thu (VNĐ)'
                  },
                  beginAtZero: true
                }
              }
            }
          });
        </script>
      </div>


    </div>
  </section>
</div>

<?php include './views/layout/footer.php'; ?>