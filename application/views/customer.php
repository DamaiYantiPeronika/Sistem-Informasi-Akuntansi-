<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

            <?= $this->session->flashdata('pesan'); ?>

            <!-- Button Tambah Customer -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Customer
            </button>

            <!-- Modal Tambah Customer -->
            <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('customer') ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahLabel">Tambah Customer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Customer</label>
                                    <input type="text" name="nama_customer" class="form-control" required>
                                    <?= form_error('nama_customer', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Customer</label>
                                    <input type="text" name="alamat_customer" class="form-control" required>
                                    <?= form_error('alamat_customer', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" name="nomor_telepon" class="form-control" required>
                                    <?= form_error('nomor_telepon', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tabel Customer -->
            <div class="card">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center" style="background-color:#9db4d8;">
                                <th>No</th>
                                <th>Nama Customer</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($customer as $cust) : ?>
                            <tr class="text-center">
                                <td><?= $no++ ?></td>
                                <td class="text-left"><?= $cust->nama_customer ?></td>
                                <td><?= $cust->alamat_customer ?></td>
                                <td><?= $cust->nomor_telepon ?></td>
                                <td>
                                    <button data-toggle="modal" data-target="#edit<?= $cust->id_customer ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                    <a href="<?= base_url('customer/delete/' . $cust->id_customer) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach ;?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Edit Customer -->
            <?php foreach($customer as $cust) : ?>
            <div class="modal fade" id="edit<?= $cust->id_customer ?>" tabindex="-1" aria-labelledby="editLabel<?= $cust->id_customer ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('customer/edit/' . $cust->id_customer) ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editLabel<?= $cust->id_customer ?>">Edit Customer</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Customer</label>
                                    <input type="text" name="nama_customer" class="form-control" value="<?= $cust->nama_customer ?>" required>
                                    <?= form_error('nama_customer', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Customer</label>
                                    <input type="text" name="alamat_customer" class="form-control" value="<?= $cust->alamat_customer ?>" required>
                                    <?= form_error('alamat_customer', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" name="nomor_telepon" class="form-control" value="<?= $cust->nomor_telepon ?>" required>
                                    <?= form_error('nomor_telepon', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach ;?>

        </div>
    </section>
</div>