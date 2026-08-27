<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

            <?php if ($this->session->flashdata('pesan')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('pesan'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead class="text-center" style="background-color:#9db4d8;">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok / Kg</th>
                                <th>Harga Rata-Rata / Kg</th>
                                <th>Harga Jual / Kg</th>
                                <th>Sisa / Kg</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($stok as $s): ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td><?= $s->kode_barang ?></td>
                                    <td class="text-left"><?= $s->nama_barang ?></td>
                                    <td><?= $s->stok ?></td>
                                    <td><?= number_format($s->harga_rata2, 0, ',', '.') ?></td>
                                    <td><?= number_format($s->harga_jual, 0, ',', '.') ?></td>
                                    <td><?= $s->sisa ?></td>
                                    <td>
                                        <?php if ($s->stok <= $s->sisa): ?>
                                            <span class="badge badge-danger">Perlu Restok</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">Aman</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>
</div>
