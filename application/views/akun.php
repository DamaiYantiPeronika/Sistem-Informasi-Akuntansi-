<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

            <?= $this->session->flashdata('pesan'); ?>
 
            <!-- Button Tambah Akun -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Akun
            </button>

            <!-- Modal Tambah Akun -->
            <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('akun') ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahLabel">Tambah Akun</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Jenis Akun</label>
                                    <select name="jenis_akun" class="form-control" required>
                                        <option value="">-- Pilih Jenis Akun --</option>
                                        <option value="101">Aktiva Lancar</option>
                                        <option value="102">Aktiva Tetap</option>
                                        <option value="201">Kewajiban</option>
                                        <option value="301">Modal</option>
                                        <option value="401">Pendapatan</option>
                                        <option value="501">HPP</option>
                                        <option value="601">Beban</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nama Akun</label>
                                    <input type="text" name="nama_akun" class="form-control" required>
                                    <?= form_error('nama_akun', '<div class="text-small text-danger">', '</div>'); ?>
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
                                <th>Nama Akun</th>
                                <th>No Akun</th>
                                <th>Jenis Akun</th>
                                <th>Saldo Normal</th>
                                <th>Bertambah di</th> 
                                <th>Berkurang di</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($akun as $ak) : ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="text-left"><?= $ak->nama_akun ?></td>
                                    <td><?= $ak->no_akun ?></td>
                                    <td><?= $ak->jenis_akun ?></td>
                                    <td><?= $ak->saldo_normal ?></td>
                                    <!-- Kolom Bertambah di -->
                                    <td>
                                        <?php if ($ak->saldo_normal == 'Debit') : ?>
                                            Debit
                                        <?php else : ?>
                                            Kredit
                                        <?php endif; ?>
                                    </td>
                                    <!-- Kolom Berkurang di -->
                                    <td>
                                        <?php if ($ak->saldo_normal == 'Debit') : ?>
                                            Kredit
                                        <?php else : ?>
                                            Debit
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button data-toggle="modal" data-target="#edit<?= $ak->id_akun ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                        <a href="<?= base_url('akun/delete/' . $ak->id_akun) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div> 

            <!-- Modal Edit Akun -->
            <?php foreach ($akun as $ak) : ?>
                <div class="modal fade" id="edit<?= $ak->id_akun ?>" tabindex="-1" aria-labelledby="editLabel<?= $ak->id_akun ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= base_url('akun/edit/' . $ak->id_akun) ?>" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLabel<?= $ak->id_akun ?>">Edit Akun</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                    <label>Jenis Akun</label>
                                    <select name="jenis_akun" class="form-control" required>
                                        <option value="">-- Pilih Jenis Akun --</option>
                                        <option value="101">Aktiva Lancar</option>
                                        <option value="102">Aktiva Tetap</option>
                                        <option value="201">Kewajiban</option>
                                        <option value="301">Modal</option>
                                        <option value="401">Pendapatan</option>
                                        <option value="501">HPP</option>
                                        <option value="601">Beban</option>
                                    </select>
                                </div>
                                    <div class="form-group">
                                        <label>Nama Akun</label>
                                        <input type="text" name="nama_akun" class="form-control" value="<?= $ak->nama_akun ?>" required>
                                        <?= form_error('nama_akun', '<div class="text-small text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>No Akun</label>
                                        <input type="text" name="no_akun" class="form-control" value="<?= $ak->no_akun ?>" required>
                                        <?= form_error('no_akun', '<div class="text-small text-danger">', '</div>'); ?>
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