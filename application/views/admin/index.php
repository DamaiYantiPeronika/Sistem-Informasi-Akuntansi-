<!-- Content Wrapper -->
<div class="content-wrapper">

    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <!-- Mulai card profile -->
            <div class="card p-4">
                <div class="d-flex align-items-center">
                    <img src="<?= base_url('assets/img/profile/') . $user['image']; ?>" 
                         class="rounded-circle mr-4" 
                         style="height: 100px; width: 100px; object-fit: cover;">
                    <div>
                        <h4><?= $user['name']; ?></h4>
                        <p><?= $user['email']; ?></p>
                        <p class="text-muted">
                            <?= ($user['role_id'] == 1 ? 'Admin' : ($user['role_id'] == 2 ? 'Karyawan' : 'User')) ?> <?= isset($user['date_created']) ? date('d F Y', $user['date_created']) : 'Tanggal tidak tersedia'; ?>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Akhir card profile -->
        </div>
    </div>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->