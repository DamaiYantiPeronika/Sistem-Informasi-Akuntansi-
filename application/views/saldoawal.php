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
                        <form action="<?= base_url('saldoawal') ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahLabel">Tambah Saldo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Akun</label>
                                    <select name="id_akun" class="form-control" required>
                                        <option value="">-- Pilih Akun --</option>
                                        <?php foreach ($akun as $ak) : ?>
                                            <option value="<?= $ak->id_akun ?>"><?= $ak->no_akun ?> - <?= $ak->nama_akun ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" name="jumlah" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Jenis Saldo</label>
                                    <select name="jenis_saldo" class="form-control" required>
                                        <option value="debit">Debit</option>
                                        <option value="kredit">Kredit</option>
                                    </select>
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
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($saldoawal as $sa) : ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td class="text-left"><?= $sa->nama_akun ?></td>
                                    <td><?= $sa->no_akun ?></td>
                                    <td class="text-right"><?= $sa->jenis_saldo == 'debit' ? 'Rp ' . number_format($sa->jumlah, 2, ',', '.') : '-' ?></td>
                                    <td class="text-right"><?= $sa->jenis_saldo == 'kredit' ? 'Rp ' . number_format($sa->jumlah, 2, ',', '.') : '-' ?></td>
                                    <td>
                                        <button data-toggle="modal" data-target="#edit<?= $sa->id_saldoawal ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                        <a href="<?= base_url('saldoawal/delete/' . $sa->id_saldoawal) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-center font-weight-bold">TOTAL</td>
                                <td class="text-right font-weight-bold">Rp <?= number_format($debit, 2, ',', '.') ?></td>
                                <td class="text-right font-weight-bold">Rp <?= number_format($kredit, 2, ',', '.') ?></td>
                                <td class="text-center">
                                    <?php
                                    $selisih = $debit - $kredit;
                                    $seimbang = ($selisih == 0);
                                    ?>
                                    <span class="badge <?= $seimbang ? 'badge-success' : 'badge-danger' ?>">
                                        <?= $seimbang ? 'SEIMBANG' : 'TIDAK SEIMBANG' ?>
                                    </span>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Modal Edit Akun -->
            <?php foreach ($saldoawal as $sa) : ?>
                <div class="modal fade" id="edit<?= $sa->id_saldoawal ?>" tabindex="-1" aria-labelledby="editLabel<?= $sa->id_saldoawal ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= base_url('saldoawal/edit/' . $sa->id_saldoawal) ?>" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLabel<?= $sa->id_saldoawal ?>">Edit Saldo Awal</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Akun</label>
                                        <select name="id_akun" class="form-control" required>
                                            <option value="">-- Pilih Akun --</option>
                                            <?php foreach ($akun as $ak) : ?>
                                                <option value="<?= $ak->id_akun ?>" <?= $ak->id_akun == $sa->id_akun ? 'selected' : '' ?>>
                                                    <?= $ak->no_akun ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah</label>
                                        <input type="number" name="jumlah" class="form-control" value="<?= $sa->jumlah ?>" required>
                                        <?= form_error('jumlah', '<div class="text-small text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Saldo</label>
                                        <select name="jenis_saldo" class="form-control" required>
                                            <option value="debit" <?= $sa->jenis_saldo == 'debit' ? 'selected' : '' ?>>Debit</option>
                                            <option value="kredit" <?= $sa->jenis_saldo == 'kredit' ? 'selected' : '' ?>>Kredit</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>