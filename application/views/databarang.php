<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

            <?= $this->session->flashdata('pesan'); ?>

            <!-- Button Tambah Barang  -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Barang
            </button>

            <!-- Modal Tambah Barang  -->
            <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('databarang') ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahLabel">Tambah Barang</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- kode_barang otomatis tidak diinput user -->
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" required>
                                    <?= form_error('nama_barang', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Tambah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center" style="background-color:#9db4d8;">
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($databarang as $dabar) : ?>
                            <tr class="text-center">
                                <td><?= $no++ ?></td>
                                <td><?= $dabar->kode_barang ?></td>
                                <td class="text-left"><?= $dabar->nama_barang ?></td>
                                <td>
                                    <button data-toggle="modal" data-target="#edit<?= $dabar->id_databarang ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                    <a href="<?= base_url('databarang/delete/' . $dabar->id_databarang) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Edit -->
            <?php foreach($databarang as $dabar) : ?>
            <div class="modal fade" id="edit<?= $dabar->id_databarang ?>" tabindex="-1" aria-labelledby="editLabel<?= $dabar->id_databarang ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('databarang/edit/' . $dabar->id_databarang) ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editLabel<?= $dabar->id_databarang ?>">Edit Data Barang</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" value="<?= $dabar->nama_barang ?>" required>
                                    <?= form_error('nama_barang', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </section>
</div>
