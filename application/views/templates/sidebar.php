<head>
  <!-- ...existing code... -->
  <style>
    .main-sidebar {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 120%) !important;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-sidebar .brand-link,
    .main-sidebar .sidebar,
    .main-sidebar .nav-link,
    .main-sidebar .brand-text,
    .main-sidebar .nav-icon,
    .main-sidebar .nav-treeview .nav-link {
      color: #fff !important;
    }

    .main-sidebar .nav-link.active,
    .main-sidebar .nav-link.active p,
    .main-sidebar .nav-link.active .nav-icon {
      background: #b6d7f7 !important;
      color: #000 !important;
    }

    .main-sidebar .nav-link:hover {
      background: #b6d7f7 !important;
      color: #000 !important;
    }

    .content-wrapper {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  margin: 20px;
  padding: 30px;
  box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
  border: 1px solid rgba(255, 255, 255, 0.18);
}

.page-title {
  color: white !important;
  font-weight: 700;
  font-size: 2.5rem;
  text-align: center;
  margin-bottom: 40px;
  text-shadow: 2px 2px 4px rgba(255, 254, 254, 0.98);
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
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Notifications -->


        <!-- User Info -->
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user['name']; ?></span>
            <img class="img-profile rounded-circle"
              src="<?= base_url('assets/img/profile/') . $user['image']; ?>"
              style="height: 30px; width: 30px; object-fit: cover;">
          </a>
          <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            <a class="dropdown-item" href="<?= base_url('user'); ?>">
              <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
              Profile
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= base_url('user/changepassword'); ?>">
              <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
              Ganti Password
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= base_url('user/edit'); ?>">
              <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-gray-400"></i>
              Edit Profile
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>">
              <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->



    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url('dashboard_admin'); ?>" class="text-center brand-link">
        <i class="fas fa-store brand-icon mr-2"></i>
        <span class="brand-text">UD. Bawang Tobing</span>
      </a>

      <!-- Sidebar Menu -->
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column"
            data-widget="treeview"
            role="menu"
            data-accordion="false">

						<?php if ($user['role_id'] == 1): ?>
							<li class="nav-item">
								<a href="<?= base_url('dashboard_admin') ?>"
									class="nav-link <?= ($this->uri->segment(1) == 'dashboard_admin') ? 'active' : ''; ?>">
									<i class="nav-icon fas fa-tachometer-alt"></i>
									<p>Dashboard</p>
								</a>
							</li>
						<?php elseif ($user['role_id'] == 2): ?>
							<li class="nav-item">
								<a href="<?= base_url('dashboard_karyawan') ?>"
									class="nav-link <?= ($this->uri->segment(1) == 'dashboard_karyawan') ? 'active' : ''; ?>">
									<i class="nav-icon fas fa-tachometer-alt"></i>
									<p>Dashboard</p>
								</a>
							</li>
						<?php endif; ?>

            <!-- Mitra Perusahaan -->
            <li class="nav-item has-treeview
            <?php if (
              in_array($this->uri->segment(1), ['supplier', 'customer', 'mitraperusahaan'])
            ) echo 'menu-open'; ?>">
              <a href="#"
                class="nav-link
             <?php if (
                in_array($this->uri->segment(1), ['supplier', 'customer', 'mitraperusahaan'])
              ) echo 'active'; ?>">
                <i class="nav-icon fas fa-handshake"></i>
                <p>
                  Mitra Perusahaan
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('supplier') ?>"
                    class="nav-link <?= ($this->uri->segment(1) == 'supplier') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-truck"></i>
                    <p>Supplier</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('customer') ?>"
                    class="nav-link <?= ($this->uri->segment(1) == 'customer') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Customer</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Persediaan -->
            <li class="nav-item has-treeview
              <?php if (in_array($this->uri->segment(1), ['persediaan', 'databarang', 'barangmasuk', 'barangkeluar', 'stokbarang'])) echo 'menu-open'; ?>">
              <a href="<?= base_url('persediaan') ?>"
                class="nav-link
                  <?php if (in_array($this->uri->segment(1), ['persediaan', 'databarang', 'barangmasuk', 'barangkeluar', 'stokbarang'])) echo 'active'; ?>">
                <i class="nav-icon fas fa-warehouse"></i>
                <p>
                  Persediaan
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('databarang') ?>"
                    class="nav-link <?= ($this->uri->segment(1) == 'databarang') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Data Barang</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('barangmasuk') ?>"
                    class="nav-link <?= ($this->uri->segment(1) == 'barangmasuk') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-arrow-down"></i>
                    <p>Barang Masuk</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('barangkeluar') ?>"
                    class="nav-link <?= ($this->uri->segment(1) == 'barangkeluar') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-arrow-up"></i>
                    <p>Barang Keluar</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('stokbarang') ?>"
                    class="nav-link <?= ($this->uri->segment(1) == 'stokbarang') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-cubes"></i>
                    <p>Stok Barang</p>
                  </a>
                </li>
              <ul class="nav nav-treeview">
                <?php
                $persediaanMenus = [
                  'databarang'    => 'Data Barang',
                  'barangmasuk'   => 'Barang Masuk',
                  'barangkeluar'  => 'Barang Keluar',
                  'stokbarang'    => 'Stok Barang'
                ];
                foreach ($persediaanMenus as $url => $label): ?>
                  <li class="nav-item">
                    <a href="<?= base_url($url) ?>"
                      class="nav-link <?= ($this->uri->segment(1) == $url) ? 'active' : ''; ?>">
                      <i class="nav-icon fas"></i>
                      <p><?= $label; ?></p>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
              </ul>
            </li>


            <!-- Daftar Akun -->
            <li class="nav-item">
              <a href="<?= base_url('akun') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'akun') ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>Daftar Akun</p>
              </a>
            </li>

            <!-- Transaksi -->
            <li class="nav-item">
              <a href="<?= base_url('transaksi') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'transaksi') ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-exchange-alt"></i>
                <p>Transaksi</p>
              </a>
            </li>

            <?php if ($user['role_id'] == 1): ?>
              <!-- Saldo Awal -->
              <li class="nav-item">
                <a href="<?= base_url('saldoawal') ?>"
                  class="nav-link <?= ($this->uri->segment(1) == 'saldoawal') ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-calculator"></i>
                  <p>Saldo Awal</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if ($user['role_id'] == 1): ?>
              <!-- Jurnal -->
              <li class="nav-item has-treeview
              <?php if (in_array($this->uri->segment(1), [
                'jurnalumum',
                'pemasukankas',
                'pengeluarankas',
                'penyesuaian'
              ])) echo 'menu-open'; ?>">
                <a href="<?= base_url('jurnal') ?>"
                  class="nav-link
                  <?php if (in_array($this->uri->segment(1), [
                    'jurnalumum',
                    'pemasukankas',
                    'pengeluarankas',
                    'penyesuaian'
                  ])) echo 'active'; ?>">
                  <i class="nav-icon fas fa-file-invoice-dollar"></i>
                  <p>
                    Jurnal
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
              <a href="<?= base_url('jurnalumum') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'jurnalumum') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-file-alt"></i>
                    <p>Umum</p>
                  </a>
                </li>
                <li class="nav-item">
              <a href="<?= base_url('pemasukankas') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'pemasukankas') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-arrow-circle-down text-success"></i>
                    <p>Pemasukan Kas</p>
                  </a>
                </li>
                <li class="nav-item">
              <a href="<?= base_url('pengeluarankas') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'pengeluarankas') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-arrow-circle-up text-danger"></i>
                    <p>Pengeluaran Kas</p>
                  </a>
                </li>
                <li class="nav-item">
              <a href="<?= base_url('penyesuaian') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'penyesuaian') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-edit"></i>
                    <p>Penyesuaian</p>
                  </a>
                </li>
                <ul class="nav nav-treeview">
                  <?php
                  $jurnalMenus = [
                    'jurnalumum' => 'Umum',
                    'pemasukankas' => 'Pemasukan Kas',
                    'pengeluarankas' => 'Pengeluaran Kas',
                    'penyesuaian' => 'Penyesuaian',
                  ];
                  foreach ($jurnalMenus as $url => $label): ?>
                    <li class="nav-item">
                      <a href="<?= base_url($url) ?>"
                        class="nav-link <?= ($this->uri->segment(1) == $url) ? 'active' : ''; ?>">
                        <i class="nav-icon fas"></i>
                        <p><?= $label; ?></p>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </ul>
              </li>
            <?php endif; ?>
            
            <?php if ($user['role_id'] == 1): ?>
              <!-- Buku Besar -->
              <li class="nav-item">
                <a href="<?= base_url('bukubesar') ?>"
                  class="nav-link <?= ($this->uri->segment(1) == 'bukubesar') ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-book"></i>
                  <p>Buku Besar</p>
                </a>
              </li>

              <!-- Neraca Saldo -->
              <li class="nav-item">
                <a href="<?= base_url('neracasaldo') ?>"
                  class="nav-link <?= ($this->uri->segment(1) == 'neracasaldo') ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-balance-scale"></i>
                  <p>Neraca Saldo</p>
                </a>
              </li>
            <?php endif; ?>

            <?php if ($user['role_id'] == 1): ?>
              <!-- Laporan Keuangan -->
              <li class="nav-item has-treeview
                 <?php if (in_array($this->uri->segment(1), [
                    'labarugi',
                    'perubahanmodal',
                    'neraca'
                  ])) echo 'menu-open'; ?>">
                <a href="<?= base_url('laporankeuangan') ?>"
                  class="nav-link
                    <?php if (in_array($this->uri->segment(1), [
                      'labarugi',
                      'perubahanmodal',
                      'neraca'
                    ])) echo 'active'; ?>">
                  <i class="nav-icon fas fa-chart-line"></i>
                  <p>
                    Laporan Keuangan
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
              <a href="<?= base_url('labarugi') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'labarugi') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    <p>Laporan Laba Rugi</p>
                  </a>
                </li>
                <li class="nav-item">
              <a href="<?= base_url('perubahanmodal') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'perubahanmodal') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-coins"></i>
                    <p>Laporan Perubahan Modal</p>
                  </a>
                </li>
                <li class="nav-item">
              <a href="<?= base_url('neraca') ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'neraca') ? 'active' : ''; ?>">
                    <i class="nav-icon fas fa-file-invoice"></i>
                    <p>Laporan Neraca</p>
                  </a>
                </li>
                <ul class="nav nav-treeview">
                  <?php
                  $laporanMenus = [
                    'labarugi'        => 'Laporan Laba Rugi',
                    'perubahanmodal'  => 'Laporan Perubahan Modal',
                    'neraca'          => 'Laporan Neraca'
                  ];
                  foreach ($laporanMenus as $url => $label): ?>
                    <li class="nav-item">
                      <a href="<?= base_url($url) ?>"
                        class="nav-link <?= ($this->uri->segment(1) == $url) ? 'active' : ''; ?>">
                        <i class="nav-icon fas"></i>
                        <p><?= $label; ?></p>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
                </ul>
              </li>
            <?php endif; ?>

						<?php if ($user['role_id'] == 1): ?>
              <!-- Penutup -->
              <li class="nav-item">
                <a href="<?= base_url('penutup') ?>"
                  class="nav-link <?= ($this->uri->segment(1) == 'penutup') ? 'active' : ''; ?>">
                  <i class="nav-icon fas fa-calendar-check"></i>
                  <p>Penutup</p>
                </a>
              </li>
            <?php endif; ?>

						<li class="nav-item">
              <a href="<?= base_url('auth/logout'); ?>"
                class="nav-link <?= ($this->uri->segment(1) == 'auth/logout') ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
              </a>
            </li>

          </ul>
        </nav>
      </div>
      <!-- /.sidebar -->
    </aside>
    >

  </div>
  <!-- /.wrapper -->
</body>

</html>
