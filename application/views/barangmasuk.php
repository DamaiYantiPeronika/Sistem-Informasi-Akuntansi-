<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

            <?= $this->session->flashdata('pesan'); ?>

            <!-- Button Tambah Barang Masuk -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Barang Masuk
            </button>

            <!-- Modal Tambah Barang Masuk -->
            <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('barangmasuk') ?>" method="POST" enctype="multipart/form-data">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahLabel">Tambah Barang Masuk</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" name="tanggal_masuk" class="form-control" required>
                                    <?= form_error('tanggal_masuk', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Barang</label>
                                    <select name="id_databarang" class="form-control" required>
                                        <option value="">-- Pilih Barang --</option>
                                        <?php foreach ($databarang as $dabar) : ?>
                                            <option value="<?= $dabar->id_databarang ?>"><?= $dabar->kode_barang ?> - <?= $dabar->nama_barang ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('id_databarang', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Nama Supplier</label>
                                    <select name="id_supplier" class="form-control" required>
                                        <option value="">-- Pilih Nama Supplier --</option>
                                        <?php foreach ($supplier as $supp) : ?>
                                            <option value="<?= $supp->id_supplier ?>"><?= $supp->nama_supplier ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?= form_error('id_supplier', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah / Kg</label>
                                    <input type="number" name="jumlah" class="form-control" required>
                                    <?= form_error('jumlah', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label>Harga Beli / Rp</label>
                                    <input type="text" name="harga_beli" class="form-control harga_beli" placeholder="Contoh: 12,500" required>
                                    <?= form_error('harga_beli', '<div class="text-small text-danger">', '</div>'); ?>
                                </div>
                                <div class="form-group">
                                    <label for="payment">Payment</label>
                                    <select name="payment" id="payment" class="form-control" required>
                                        <option value="cash">Cash</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </div>
								<div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="Lunas">Lunas</option>
                                        <option value="Belum Lunas">Belum Lunas</option>
                                    </select>
                                </div>
								<div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan" class="form-control" required>
                                </div>
                                <!-- Upload bukti -->
                                <div class="form-group">
                                    <label>Upload Bukti (Opsional)</label>
                                    <input type="file" name="bukti" class="form-control" accept="image/*" onchange="previewImage(this, 'preview_tambah_masuk')">
                                    <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                                    <div class="mt-2">
                                        <img id="preview_tambah_masuk" src="" alt="Preview" style="display: none; max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">
                                    </div>
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

            <!-- Tabel Barang Masuk -->
            <div class="card">
                <div class="card-body"> 
                    <!-- Form Filter -->
                    <form method="get" action="<?= base_url('barangmasuk') ?>" class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <select name="filter_by" id="filter_by" class="form-control">
                                    <option value="">-- Semua Data --</option>
                                    <option value="barang" <?= $this->input->get('filter_by') == 'barang' ? 'selected' : '' ?>>Barang</option>
                                    <option value="supplier" <?= $this->input->get('filter_by') == 'supplier' ? 'selected' : '' ?>>Supplier</option>
                                    <option value="payment" <?= $this->input->get('filter_by') == 'payment' ? 'selected' : '' ?>>Payment</option>
                                    <option value="tanggal" <?= $this->input->get('filter_by') == 'tanggal' ? 'selected' : '' ?>>Tanggal</option>
                                    <option value="status" <?= $this->input->get('filter_by') == 'status' ? 'selected' : '' ?>>Status</option>
                                </select>
                            </div>
                            <div class="col-5">
                                <!-- Input value berubah sesuai filter -->
                                <div id="input-barang" class="filter-input" style="display:none;">
                                    <select name="id_databarang" class="form-control">
                                        <option value="">Pilih Barang</option>
                                        <?php foreach ($databarang as $dabar): ?>
                                            <option value="<?= $dabar->id_databarang ?>" <?= set_select('id_databarang', $this->input->get('id_databarang')) ?>><?= $dabar->kode_barang ?> - <?=$dabar->nama_barang?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="input-supplier" class="filter-input" style="display:none;">
                                    <select name="id_supplier" class="form-control">
                                        <option value="">Pilih Supplier</option>
                                        <?php foreach ($supplier as $supp): ?>
                                            <option value="<?= $supp->id_supplier ?>" <?= set_select('id_supplier', $this->input->get('id_supplier')) ?>><?= $supp->nama_supplier ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="input-payment" class="filter-input" style="display:none;">
                                    <select name="payment" class="form-control">
                                        <option value="">Pilih Payment</option>
                                        <option value="cash" <?= set_select('payment', 'cash', $this->input->get('payment') == 'cash') ?>>Cash</option>
                                        <option value="credit" <?= set_select('payment', 'credit', $this->input->get('payment') == 'credit') ?>>Credit</option>
                                    </select>
                                </div>
                                <div id="input-tanggal" class="filter-input" style="display:none;">
                                    <div class="row">
                                        <div class="col">
                                            <input type="date" name="tanggal_awal" class="form-control" value="<?= $this->input->get('tanggal_awal') ?>">
                                        </div>
                                        <div class="col">
                                            <input type="date" name="tanggal_akhir" class="form-control" value="<?= $this->input->get('tanggal_akhir') ?>">
                                        </div>
                                    </div>
                                </div>
								<div id="input-status" class="filter-input" style="display:none;">
                                    <select name="status" class="form-control">
                                        <option value="">Pilih Status</option>
                                        <option value="Lunas" <?= set_select('status', 'Lunas', $this->input->get('status') == 'Lunas') ?>>Lunas</option>
                                        <option value="Belum Lunas" <?= set_select('status', 'Belum Lunas', $this->input->get('status') == 'Belum Lunas') ?>>Belum Lunas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="<?= base_url('barangmasuk') ?>" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <script>
                        function showFilterInput() {
                            var val = document.getElementById('filter_by').value;
                            document.querySelectorAll('.filter-input').forEach(function(el) {
                                el.style.display = 'none';
                            });
                            if (val) {
                                document.getElementById('input-' + val).style.display = 'block';
                            }
                        }
                        document.getElementById('filter_by').addEventListener('change', showFilterInput);
                        window.onload = showFilterInput;
                    </script>

<script>
// Fungsi untuk format ribuan dengan koma
function formatRibuanKoma(angka) {
    let numberString = angka.replace(/[^,\d]/g, '').toString(),
        split = numberString.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? ',' : '';
        rupiah += separator + ribuan.join(',');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return rupiah;
}

// Event Listener untuk semua input harga_beli (Tambah & Edit)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.harga_beli').forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = formatRibuanKoma(this.value);
        });
    });

    // Saat submit form, hapus koma agar yang dikirim ke server hanya angka
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            form.querySelectorAll('.harga_beli').forEach(function(input) {
                input.value = input.value.replace(/,/g, '');
            });
        });
    });
});

// Fungsi untuk preview bukti
function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    
    if (file) {
        // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Validasi tipe file
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file tidak didukung! Gunakan JPG, JPEG, atau PNG.');
            input.value = '';
            preview.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

// Fungsi untuk menampilkan bukti dalam modal
function showImageModal(imageSrc) {
    document.getElementById('fullImageMasuk').src = imageSrc;
    $('#imageModalMasuk').modal('show');
}
</script>

                    <!-- Tabel Barang Masuk -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center" style="background-color:#9db4d8;">
                                <th>No</th>
                                <th>Tanggal Masuk</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Nama Supplier</th>
                                <th>Jumlah / Kg</th>
                                <th>Harga Beli / Rp</th>
                                <th>Total / Rp</th>
                                <th>Payment</th>
								<th>Status</th>
								<th>Keterangan</th>
                                <th>Bukti Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($barangmasuk as $bm) : ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d-m-Y', strtotime($bm->tanggal_masuk)) ?></td>
                                    <td><?= $bm->kode_barang ?></td>
                                    <td class="text-left"><?= $bm->nama_barang ?></td>
                                    <td class="text-left"><?= $bm->nama_supplier ?></td>
                                    <td><?= $bm->jumlah ?></td>
                                    <td><?= number_format($bm->harga_beli, 0, ',', ',') ?></td>
                                    <td><?= number_format($bm->total, 0, ',', ',') ?></td>
                                    <td><?= $bm->payment ?></td>
									<td><?= $bm->status ?></td>
									<td class="text-left"><?= $bm->keterangan ?></td>
                                    <td>
                                        <?php if (!empty($bm->bukti)) : ?>
                                            <img src="<?= base_url('uploads/barangmasuk/' . $bm->bukti) ?>" 
                                                 alt="bukti" 
                                                 style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;" 
                                                 onclick="showImageModal('<?= base_url('uploads/barangmasuk/' . $bm->bukti) ?>')">
                                        <?php else : ?>
                                            <span class="text-muted">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button data-toggle="modal" data-target="#edit<?= $bm->id ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                        <a href="<?= base_url('barangmasuk/delete/' . $bm->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" class="text-center font-weight-bold">TOTAL</td>
                                <td class="text-left font-weight-bold" colspan="3">
                                    Rp <?= number_format($total_semua, 0, ',', '.') ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Modal Edit -->
            <?php foreach ($barangmasuk as $bm) : ?>
                <div class="modal fade" id="edit<?= $bm->id ?>" tabindex="-1" aria-labelledby="editLabel<?= $bm->id ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= base_url('barangmasuk/edit/' . $bm->id) ?>" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLabel<?= $bm->id ?>">Edit Data Barang Masuk</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tanggal Masuk</label>
                                        <input type="date" name="tanggal_masuk" class="form-control" value="<?= $bm->tanggal_masuk ?>" required>
                                        <?= form_error('tanggal_masuk', '<div class="text-small text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Barang</label>
                                        <select name="id_databarang" class="form-control" required>
                                            <option value="">-- Pilih Barang --</option>
                                            <?php foreach ($databarang as $dabar) : ?>
                                                <option value="<?= $dabar->id_databarang ?>" <?= $dabar->id_databarang == $bm->id_databarang ? 'selected' : '' ?>><?= $dabar->kode_barang ?> - <?= $dabar->nama_barang ?>
                                    </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= form_error('id_databarang', '<div class="text-small text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Supplier</label>
                                        <select name="id_supplier" class="form-control" required>
                                            <option value="">-- Pilih Nama Supplier --</option>
                                            <?php foreach ($supplier as $supp) : ?>
                                                <option value="<?= $supp->id_supplier ?>" <?= $supp->id_supplier == $bm->id_supplier ? 'selected' : '' ?>>
                                                    <?= $supp->nama_supplier ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?= form_error('id_supplier', '<div class="text-small text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Jumlah / Kg</label>
                                        <input type="number" name="jumlah" class="form-control" value="<?= $bm->jumlah ?>" required>
                                        <?= form_error('jumlah', '<div class="text-small text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Beli / Rp</label>
                                        <input type="text" name="harga_beli" class="form-control harga_beli" value="<?= number_format($bm->harga_beli, 0, ',', ',') ?>" required>
                                        <?= form_error('harga_beli', '<div class="text-small text-danger">', '</div>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment">Payment</label>
                                        <select name="payment" class="form-control" required>
                                            <option value="cash" <?= $bm->payment == 'cash' ? 'selected' : '' ?>>Cash</option>
                                            <option value="credit" <?= $bm->payment == 'credit' ? 'selected' : '' ?>>Credit</option>
                                        </select>
                                    </div>
									<div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="Lunas" <?= $bm->status == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                                            <option value="Belum Lunas" <?= $bm->status == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                                        </select>
                                    </div>
									<div class="form-group">
										<label>Keterangan</label>
										<input type="text" name="keterangan" class="form-control" value="<?= $bm->keterangan ?>" required>
									</div>
                                    <!-- Upload bukti Edit -->
                                    <div class="form-group">
                                        <label>Upload Bukti Baru (Opsional)</label>
                                        <input type="file" name="bukti" class="form-control" accept="image/*" onchange="previewImage(this, 'preview_edit_masuk_<?= $bm->id ?>')">
                                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah bukti.</small>
                                        
                                        <?php if (!empty($bm->bukti)) : ?>
                                            <div class="mt-2">
                                                <label class="text-muted">Bukti Saat Ini:</label><br>
                                                <img src="<?= base_url('uploads/barangmasuk/' . $bm->bukti) ?>" 
                                                     alt="Bukti Saat Ini" 
                                                     style="max-width: 150px; max-height: 100px; border: 1px solid #ddd; border-radius: 5px;">
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="mt-2">
                                            <img id="preview_edit_masuk_<?= $bm->id ?>" src="" alt="Preview" style="display: none; max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">
                                        </div>
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
            <?php endforeach; ?>

            <!-- Modal untuk menampilkan bukti penuh -->
            <div class="modal fade" id="imageModalMasuk" tabindex="-1" aria-labelledby="imageModalMasukLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalMasukLabel">Bukti Barang Masuk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="fullImageMasuk" src="" alt="Bukti Penuh" style="max-width: 100%; height: auto;">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>