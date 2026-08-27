<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

      <!-- Stats Cards -->
      <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
          <div class="stat-card">
            <div class="text-center">
              <div class="stat-icon text-primary">
                <i class="fas fa-exchange-alt"></i>
              </div>
              <div class="stat-number"><?= $total_transaksi; ?></div>
              <div class="stat-label">Total Transaksi</div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
          <div class="stat-card success">
            <div class="text-center">
              <div class="stat-icon text-success">
                <i class="fas fa-boxes"></i>
              </div>
              <div class="stat-number"><?= $total_stok; ?></div>
              <div class="stat-label">Total Barang</div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
          <div class="stat-card warning">
            <div class="text-center">
              <div class="stat-icon text-warning">
                <i class="fas fa-truck"></i>
              </div>
              <div class="stat-number"><?= $total_supplier; ?></div>
              <div class="stat-label">Total Supplier</div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
          <div class="stat-card info">
            <div class="text-center">
              <div class="stat-icon text-info">
                <i class="fas fa-wallet"></i>
              </div>
              <div class="stat-number">Rp <?= number_format($saldo_kas, 0, ',', '.'); ?></div>
              <div class="stat-label">Saldo Kas</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Credit Tables -->
      <div class="row g-4 mb-5">
        <div class="col-lg-6">
          <div class="data-card">
            <div class="card-header-custom">
              <i class="fas fa-arrow-down me-2"></i>
              Barang Masuk - Credit Belum Lunas
            </div>
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Tanggal Masuk</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Supplier</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($barang_masuk as $bm) : ?>
                  <tr>
                    <td><?= $bm->tanggal_masuk; ?></td>
                    <td><?= $bm->kode_barang; ?></td>
                    <td><?= $bm->nama_barang; ?></td>
                    <td><?= $bm->jumlah; ?></td>
                    <td><?= $bm->nama_supplier; ?></td>
                    <td><?= $bm->payment; ?></td>
                    <td>
                      <span class="badge <?= $bm->status == 'Lunas' ? 'bg-success' : 'bg-warning'; ?>">
                        <?= $bm->status; ?>
                      </span>
                    </td>
                    <td><?= $bm->keterangan; ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        
        <div class="col-lg-6">
          <div class="data-card">
            <div class="card-header-custom">
              <i class="fas fa-arrow-up me-2"></i>
              Barang Keluar - Credit Belum Lunas
            </div>
            <div class="table-responsive">
              <table class="table table-modern">
                <thead>
                  <tr>
                    <th>Tanggal Keluar</th>
                    <th>Kode Barang</th>
                    <th>Nama Customer</th>
                    <th>Jumlah</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($barang_keluar as $bk) : ?>
                  <tr>
                    <td><?= date('d-m-Y', strtotime($bk->tanggal_keluar)); ?></td>
                    <td><?= $bk->kode_barang; ?></td>
                    <td><?= $bk->nama_customer; ?></td>
                    <td><?= $bk->jumlah; ?></td>
                    <td><?= $bk->payment; ?></td>
                    <td>
                      <span class="badge <?= $bk->status == 'Lunas' ? 'bg-success' : 'bg-danger'; ?>">
                        <?= $bk->status; ?>
                      </span>
                    </td>
                    <td><?= $bk->keterangan; ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="row g-4 mb-5">
        <div class="col-12">
          <div class="data-card">
            <div class="card-header-custom">
              <i class="fas fa-chart-line me-2"></i>
              Grafik Transaksi Bulanan
            </div>
            <div class="chart-container">
              <canvas id="transaksiChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-5">
        <div class="col-12">
          <div class="data-card">
            <div class="card-header-custom">
              <i class="fas fa-chart-bar me-2"></i>
              Grafik Penjualan vs Pembelian Bulanan
            </div>
            <div class="chart-container">
              <canvas id="penjualanPembelianChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Financial Summary -->
      <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
          <div class="stat-card danger">
            <div class="text-center">
              <div class="stat-icon text-danger">
                <i class="fas fa-chart-pie"></i>
              </div>
              <div class="stat-number">Rp <?= number_format($laba_kotor, 0, ',', '.'); ?></div>
              <div class="stat-label">Laba Kotor</div>
            </div>
          </div>
        </div>
        
        <div class="col-xl-6">
          <div class="data-card">
            <div class="card-header-custom">
              <i class="fas fa-money-bill-wave me-2"></i>
              Ringkasan Keuangan
            </div>
            <div class="summary-item">
              <i class="fas fa-arrow-up text-success me-2"></i>
              <strong class="gradient-text">Total Penjualan:</strong> Rp <?= number_format($total_penjualan, 0, ',', '.'); ?>
            </div>
            <div class="summary-item">
              <i class="fas fa-arrow-down text-danger me-2"></i>
              <strong class="gradient-text">Total Pembelian:</strong> Rp <?= number_format($total_pembelian, 0, ',', '.'); ?>
            </div>
            <div class="summary-item">
              <i class="fas fa-chart-line text-primary me-2"></i>
              <strong class="gradient-text">Laba Kotor:</strong> Rp <?= number_format($laba_kotor, 0, ',', '.'); ?>
            </div>
          </div>
        </div>
        
        <div class="col-xl-3">
          <div class="data-card">
            <div class="card-header-custom">
              <i class="fas fa-warehouse me-2"></i>
              Ringkasan Persediaan
            </div>
            <div class="summary-item">
              <i class="fas fa-shopping-cart text-warning me-2"></i>
              <strong class="gradient-text">Total Pembelian:</strong> Rp <?= number_format($total_pembelian, 0, ',', '.'); ?>
            </div>
            <div class="summary-item">
              <i class="fas fa-boxes text-info me-2"></i>
              <strong class="gradient-text">Persediaan Akhir:</strong> <?= $persediaan_akhir; ?> item
            </div>
          </div>
        </div>
      </div>

      <!-- Latest Transactions -->
      <div class="row g-4">
        <div class="col-12">
          <div class="data-card">
            <div class="card-header-custom">
              <i class="fas fa-history me-2"></i>
              Transaksi Terbaru
            </div>
            <?php foreach ($latest_transaksi as $t) : ?>
            <div class="summary-item">
              <i class="fas fa-calendar me-2 text-primary"></i>
              <strong><?= $t['tanggal']; ?></strong> - <?= $t['keterangan']; ?> (<?= $t['jumlah']; ?>)
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- CSS Styles -->
<style>
body {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.content-wrapper {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  margin: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  border: 1px solid rgba(255, 255, 255, 0.18);
}

.page-title {
  color: white;
  font-weight: 700;
  font-size: 2.5rem;
  text-align: center;
  margin-bottom: 40px;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.stat-card {
  background: rgba(255, 255, 255, 0.9);
  border-radius: 20px;
  padding: 25px;
  margin-bottom: 20px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: none;
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card.success::before {
  background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
}

.stat-card.warning::before {
  background: linear-gradient(90deg, #fa709a 0%, #fee140 100%);
}

.stat-card.info::before {
  background: linear-gradient(90deg, #a8edea 0%, #fed6e3 100%);
}

.stat-card.danger::before {
  background: linear-gradient(90deg, #ff9a9e 0%, #fecfef 100%);
}

.stat-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.stat-icon {
  font-size: 3rem;
  margin-bottom: 15px;
  opacity: 0.8;
}

.stat-number {
  font-size: 2.2rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 5px;
}

.stat-label {
  color: #7f8c8d;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.9rem;
  letter-spacing: 1px;
}

.data-card {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 20px;
  padding: 25px;
  margin-bottom: 30px;
  box-shadow: 0 15px 35px rgba(0,0,0,0.1);
  border: none;
}

.card-header-custom {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 15px 15px 0 0;
  padding: 20px 25px;
  margin: -25px -25px 25px -25px;
  font-weight: 600;
  font-size: 1.1rem;
}

.table-modern {
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.table-modern thead th {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: 600;
  border: none;
  padding: 15px;
  font-size: 0.9rem;
}

.table-modern tbody td {
  padding: 12px 15px;
  border-color: #f8f9fa;
  vertical-align: middle;
  font-size: 0.9rem;
}

.table-modern tbody tr:hover {
  background-color: #f8f9fa;
  transform: scale(1.01);
  transition: all 0.2s ease;
}

.chart-container {
  position: relative;
  height: 400px;
  background: white;
  border-radius: 15px;
  padding: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.summary-item {
  background: rgba(255, 255, 255, 0.9);
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 10px;
  border-left: 4px solid #667eea;
  transition: all 0.3s ease;
}

.summary-item:hover {
  transform: translateX(5px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.gradient-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 700;
}

.badge {
  font-size: 0.8rem;
  padding: 0.5em 0.8em;
}

@media (max-width: 768px) {
  .content-wrapper {
    margin: 10px;
    padding: 20px;
  }
  
  .page-title {
    font-size: 2rem;
  }
  
  .stat-card {
    padding: 20px;
  }
  
  .stat-number {
    font-size: 1.8rem;
  }
  
  .table-modern thead th,
  .table-modern tbody td {
    padding: 8px;
    font-size: 0.8rem;
  }
}
</style>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Line Chart untuk Transaksi Bulanan (sesuai permintaan)
const ctx = document.getElementById('transaksiChart').getContext('2d');
const transaksiChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: <?= json_encode($bulan); ?>,
    datasets: [{
      label: 'Jumlah Transaksi',
      data: <?= json_encode($jumlah_transaksi); ?>,
      borderColor: 'rgba(102, 126, 234, 1)',
      backgroundColor: 'rgba(102, 126, 234, 0.1)',
      borderWidth: 3,
      fill: true,
      tension: 0.4,
      pointBackgroundColor: 'rgba(102, 126, 234, 1)',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointRadius: 6,
      pointHoverRadius: 8
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: 'top',
        labels: {
          usePointStyle: true,
          font: {
            size: 14,
            weight: 'bold'
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(0,0,0,0.1)'
        },
        ticks: {
          font: {
            size: 12
          }
        }
      },
      x: {
        grid: {
          color: 'rgba(0,0,0,0.1)'
        },
        ticks: {
          font: {
            size: 12
          }
        }
      }
    },
    interaction: {
      intersect: false,
      mode: 'index'
    }
  }
});

// Bar Chart untuk Penjualan vs Pembelian
const ctx2 = document.getElementById('penjualanPembelianChart').getContext('2d');
const penjualanPembelianChart = new Chart(ctx2, {
  type: 'bar',
  data: {
    labels: <?= json_encode($bulan_penjualan_pembelian); ?>,
    datasets: [
      {
        label: 'Penjualan',
        data: <?= json_encode($jumlah_penjualan); ?>,
        backgroundColor: 'rgba(67, 233, 123, 0.8)',
        borderColor: 'rgba(67, 233, 123, 1)',
        borderWidth: 2,
        borderRadius: 8
      },
      {
        label: 'Pembelian',
        data: <?= json_encode($jumlah_pembelian); ?>,
        backgroundColor: 'rgba(255, 154, 158, 0.8)',
        borderColor: 'rgba(255, 154, 158, 1)',
        borderWidth: 2,
        borderRadius: 8
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: true,
        position: 'top',
        labels: {
          usePointStyle: true,
          font: {
            size: 14,
            weight: 'bold'
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          color: 'rgba(0,0,0,0.1)'
        },
        ticks: {
          font: {
            size: 12
          }
        }
      },
      x: {
        grid: {
          color: 'rgba(0,0,0,0.1)'
        },
        ticks: {
          font: {
            size: 12
          }
        }
      }
    }
  }
});
</script>