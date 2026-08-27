<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

            <?= $this->session->flashdata('pesan'); ?>

            <!-- Button Tambah Supplier -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Supplier
            </button>

            <!-- Modal Tambah Supplier -->
            <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('supplier') ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahLabel">Tambah Supplier</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Supplier</label>
                                    <input type="text" name="nama_supplier" class="form-control" required>
                                    <?= form_error('nama_supplier', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Supplier</label>
                                    <input type="text" name="alamat_supplier" class="form-control" required>
                                    <?= form_error('alamat_supplier', '<div class="text-small text-danger">', '</div>'); ?>
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

            <!-- Tabel Supplier -->
            <div class="card">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center" style="background-color:#9db4d8;">
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($supplier as $supp) : ?>
                            <tr class="text-center">
                                <td><?= $no++ ?></td>
                                <td class="text-left"><?= $supp->nama_supplier ?></td>
                                <td><?= $supp->alamat_supplier ?></td>
                                <td><?= $supp->nomor_telepon ?></td>
                                <td>
                                    <button data-toggle="modal" data-target="#edit<?= $supp->id_supplier ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                    <a href="<?= base_url('supplier/delete/' . $supp->id_supplier) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach ;?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Edit Supplier -->
            <?php foreach($supplier as $supp) : ?>
            <div class="modal fade" id="edit<?= $supp->id_supplier ?>" tabindex="-1" aria-labelledby="editLabel<?= $supp->id_supplier ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('supplier/edit/' . $supp->id_supplier) ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editLabel<?= $supp->id_supplier ?>">Edit Supplier</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Supplier</label>
                                    <input type="text" name="nama_supplier" class="form-control" value="<?= $supp->nama_supplier ?>" required>
                                    <?= form_error('nama_supplier', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Alamat Supplier</label>
                                    <input type="text" name="alamat_supplier" class="form-control" value="<?= $supp->alamat_supplier ?>" required>
                                    <?= form_error('alamat_supplier', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" name="nomor_telepon" class="form-control" value="<?= $supp->nomor_telepon ?>" required>
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

