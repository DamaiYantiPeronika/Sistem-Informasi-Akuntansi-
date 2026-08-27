<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>
 
            <?= $this->session->flashdata('pesan'); ?>

            <!-- Button Tambah Barang Keluar -->
            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Barang Keluar
            </button>

            <!-- Modal Tambah Barang Keluar -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('barangkeluar') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Barang Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" class="form-control" required>
                    </div>
                    <div class="form-group">                                     
                        <label>Barang</label>                                     
                        <select name="id_databarang" id="id_databarang" class="form-control" required onchange="setHargaJual(this)">                                         
                            <option value="">-- Pilih Barang --</option>                                         
                            <?php foreach ($stok as $s) : ?>                                             
                                <option value="<?= $s->id_databarang ?>" 
                                        data-harga="<?= $s->harga_jual ?>" 
                                        data-hpp="<?= $s->harga_rata2 ?>">
                                    <?= $s->kode_barang ?> - <?= $s->nama_barang ?>
                                </option>                                         
                            <?php endforeach; ?>                                     
                        </select>                                     
                        <?= form_error('id_databarang', '<div class="text-small text-danger">', '</div>'); ?>                                 
                    </div>
                    <div class="form-group">                                     
                        <label>Nama Customer</label>                                     
                        <select name="id_customer" class="form-control" required>                                         
                            <option value="">-- Pilih Nama Customer --</option>                                         
                            <?php foreach ($customer as $cust) : ?>                                             
                                <option value="<?= $cust->id_customer ?>"><?= $cust->nama_customer ?></option>                                         
                                <?php endforeach; ?>                                     
                            </select>                                     
                            <?= form_error('id_customer', '<div class="text-small text-danger">', '</div>'); ?>                                 
                        </div>
                    <div class="form-group">
                        <label>Jumlah / Kg</label>
                        <input type="number" name="jumlah" class="form-control" required oninput="hitungTotal()">
                    </div>
                    <div class="form-group">
                        <label>Harga Jual / kg</label>
                        <input type="number" step="0.01" name="harga_jual" id="hargaJual" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total Harga</label>
                        <input type="text" id="totalHarga" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="payment">Payment</label>
                        <select name="payment" class="form-control" required>
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
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
                        <input type="file" name="bukti" class="form-control" accept="image/*" onchange="previewImage(this, 'preview_tambah')">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                        <div class="mt-2">
                            <img id="preview_tambah" src="" alt="Preview" style="display: none; max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">
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
            <!-- Tabel Barang Keluar -->
            <div class="card">
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="get" action="<?= base_url('barangkeluar') ?>" class="mb-3">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <select name="filter_by" id="filter_by" class="form-control">
                                    <option value="">-- Semua Data --</option>
                                    <option value="barang" <?= $this->input->get('filter_by') == 'barang' ? 'selected' : '' ?>>Barang</option>
                                    <option value="customer" <?= $this->input->get('filter_by') == 'customer' ? 'selected' : '' ?>>Customer</option>
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
                                            <option value="<?= $dabar->id_databarang ?>" <?= set_select('id_databarang', $this->input->get('id_databarang')) ?>><?= $dabar->kode_barang ?> - <?= $dabar->nama_barang ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="input-customer" class="filter-input" style="display:none;">
                                    <select name="id_customer" class="form-control">
                                        <option value="">Pilih Customer</option>
                                        <?php foreach ($customer as $cust): ?>
                                            <option value="<?= $cust->id_customer ?>" <?= set_select('id_customer', $this->input->get('id_customer')) ?>><?= $cust->nama_customer ?></option>
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
                                <a href="<?= base_url('barangkeluar') ?>" class="btn btn-secondary">Reset</a>
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

                    <!-- tabel -->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center" style="background-color:#9db4d8;">
                                <th>No</th>
                                <th>Tanggal Keluar</th>
                                <th>Kode Barang</th>
                                <th>Nama Customer</th>
                                <th>Jumlah</th>
                                <th>Hpp / Rp</th>
                                <th>Harga Jual /kg</th>
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
                            foreach ($barangkeluar as $bk) : ?>
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td><?= date('d-m-Y', strtotime($bk->tanggal_keluar)) ?></td>
                                    <td><?= $bk->kode_barang ?></td>
                                    <td class="text-left"><?= $bk->nama_customer ?></td>
                                    <td><?= $bk->jumlah ?></td>
                                    <td><?= number_format($bk->hpp, 0, ',', '.') ?></td>
                                    <td><?= number_format($bk->harga_jual, 0, ',', '.') ?></td>
                                    <td><?= number_format($bk->total, 0, ',', '.') ?></td>
                                    <td><?= $bk->payment ?></td>
									<td><?= $bk->status ?></td>
									<td class="text-left"><?= $bk->keterangan ?></td>
                                    <td>
                                        <?php if (!empty($bk->bukti)) : ?>
                                            <img src="<?= base_url('uploads/barangkeluar/' . $bk->bukti) ?>" 
                                                 alt="bukti" 
                                                 style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;" 
                                                 onclick="showImageModal('<?= base_url('uploads/barangkeluar/' . $bk->bukti) ?>')">
                                        <?php else : ?>
                                            <span class="text-muted">Tidak ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?= $bk->id ?>"><i class="fas fa-edit"></i></button>
                                        <a href="<?= base_url('barangkeluar/delete/' . $bk->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i></a>
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
            <?php foreach ($barangkeluar as $bk) : ?>
<div class="modal fade" id="edit<?= $bk->id ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $bk->id ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('barangkeluar/edit/' . $bk->id) ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel<?= $bk->id ?>">Edit Data Barang Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" class="form-control" value="<?= $bk->tanggal_keluar ?>" required>
                    </div>
                    <div class="form-group">                                         
                        <label>Barang</label>                                         
                        <select name="id_databarang" class="form-control" required onchange="setHargaJualEdit(this, <?= $bk->id ?>)">                                             
                            <option value="">-- Pilih Barang --</option>                                             
                            <?php foreach ($stokbarang as $s) : ?>                                                 
                                <option value="<?= $s->id_databarang ?>" 
                                        data-harga="<?= $s->harga_jual ?>" 
                                        data-hpp="<?= $s->harga_rata2 ?>"
                                        <?= $s->id_databarang == $bk->id_databarang ? 'selected' : '' ?>>                                                     
                                    <?= $s->kode_barang ?> - <?= $s->nama_barang ?>                                                 
                                </option>                                             
                            <?php endforeach; ?>                                         
                        </select>                                         
                        <?= form_error('id_databarang', '<div class="text-small text-danger">', '</div>'); ?>                                     
                    </div>
                    <div class="form-group">                                         
                        <label>Nama Customer</label>                                         
                        <select name="id_customer" class="form-control" required>                                             
                            <option value="">-- Pilih Nama Customer --</option>                                             
                            <?php foreach ($customer as $cust) : ?>                                                 
                                <option value="<?= $cust->id_customer ?>" <?= $cust->id_customer == $bk->id_customer ? 'selected' : '' ?>>                                                     
                                    <?= $cust->nama_customer ?>                                                 
                                </option>                                             
                                <?php endforeach; ?>                                         
                            </select>                                         
                            <?= form_error('id_customer', '<div class="text-small text-danger">', '</div>'); ?>                                     
                        </div>
                    <div class="form-group">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" data-id="<?= $bk->id ?>" class="form-control" value="<?= $bk->jumlah ?>" required oninput="hitungTotalEdit(<?= $bk->id ?>)">
                    </div>
                    <div class="form-group">
                        <label>Harga Jual / kg</label>
                        <input type="number" step="0.01" name="harga_jual" id="hargaJualEdit<?= $bk->id ?>" class="form-control" value="<?= $bk->harga_jual ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Total Harga</label>
                        <input type="text" id="totalHargaEdit<?= $bk->id ?>" class="form-control" value="<?= number_format($bk->total, 0, ',', '.') ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="payment">Payment</label>
                        <select name="payment" class="form-control" required>
                            <option value="cash" <?= $bk->payment == 'cash' ? 'selected' : '' ?>>Cash</option>
                            <option value="credit" <?= $bk->payment == 'credit' ? 'selected' : '' ?>>Credit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" required>
                            <option value="Lunas" <?= $bk->status == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                            <option value="Belum Lunas" <?= $bk->status == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="<?= $bk->keterangan ?>" required>
                    </div>
                    <!-- Upload bukti Edit -->
                    <div class="form-group">
                        <label>Upload Bukti Baru (Opsional)</label>
                        <input type="file" name="bukti" class="form-control" accept="image/*" onchange="previewImage(this, 'preview_edit_<?= $bk->id ?>')">
                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah bukti.</small>
                        
                        <?php if (!empty($bk->bukti)) : ?>
                            <div class="mt-2">
                                <label class="text-muted">Bukti Saat Ini:</label><br>
                                <img src="<?= base_url('uploads/barangkeluar/' . $bk->bukti) ?>" 
                                     alt="Bukti Saat Ini" 
                                     style="max-width: 150px; max-height: 100px; border: 1px solid #ddd; border-radius: 5px;">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-2">
                            <img id="preview_edit_<?= $bk->id ?>" src="" alt="Preview" style="display: none; max-width: 200px; max-height: 150px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>
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

<!-- Modal untuk menampilkan bukti penuh -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Bukti Barang Keluar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="fullImage" src="" alt="bukti Penuh" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<script>
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
        document.getElementById('fullImage').src = imageSrc;
        $('#imageModal').modal('show');
    }

    function setHargaJual(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var hargaJual = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        document.getElementById('hargaJual').value = hargaJual;
        hitungTotal();
    }

    function hitungTotal() {
        var jumlah = parseFloat(document.querySelector('input[name="jumlah"]').value) || 0;
        var hargaJual = parseFloat(document.getElementById('hargaJual').value) || 0;
        var total = jumlah * hargaJual;
        document.getElementById('totalHarga').value = formatRupiah(total);
    }

    function setHargaJualEdit(selectElement, id) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var hargaJual = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
        document.getElementById('hargaJualEdit' + id).value = hargaJual;
        hitungTotalEdit(id);
    }

    function hitungTotalEdit(id) {
        var jumlah = parseFloat(document.querySelector('input[name="jumlah"][data-id="'+id+'"]').value) || 0;
        var hargaJual = parseFloat(document.getElementById('hargaJualEdit' + id).value) || 0;
        var total = jumlah * hargaJual;
        document.getElementById('totalHargaEdit' + id).value = formatRupiah(total);
    }

    function formatRupiah(angka) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }

    $(document).ready(function() {
        // Untuk modal tambah
        $('#modalTambah').on('shown.bs.modal', function () {
            // Reset form ketika modal dibuka
            $('#id_databarang').val('').trigger('change');
            $('#hargaJual').val('');
            $('#totalHarga').val('');
            // Reset preview bukti
            $('#preview_tambah').hide();
            $('input[name="bukti"]').val('');
        });
        
        // Untuk modal edit, set harga jual ketika modal dibuka
        $('[id^="edit"]').on('shown.bs.modal', function () {
            var modalId = $(this).attr('id');
            var recordId = modalId.replace('edit', '');
            var selectElement = $(this).find('select[name="id_databarang"]')[0];
            if (selectElement && selectElement.selectedIndex > 0) {
                setHargaJualEdit(selectElement, recordId);
            }
        });
    });
</script>

        </div>
    </section>
</div>