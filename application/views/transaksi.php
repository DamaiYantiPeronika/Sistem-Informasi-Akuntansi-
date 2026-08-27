<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>

            <?= $this->session->flashdata('pesan'); ?>

            <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah Transaksi
            </button> 

            <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= base_url('transaksi/tambah') ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalTambahLabel">Tambah Transaksi</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                    <?= form_error('tanggal', '<div class="text-small text-danger">'); ?>
                                </div>

								<div class="form-group">
									<label>Apakah transaksi ini berkaitan dengan transaksi sebelumnya?</label>
									<select name="is_related" id="is_related" class="form-control" required>
										<option value="0">Tidak</option>
										<option value="1">Ya</option>
									</select>
								</div>
								
								<div id="related-section" style="display:none;">
									<div class="form-group">
                                        <label>Tipe Transaksi Terkait</label>
										<select name="related_type" id="related_type" class="form-control">
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="barangmasuk">Barang Masuk (Hutang ke Supplier)</option>
                                            <option value="barangkeluar">Barang Keluar (Piutang dari Customer)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
    <label>Pilih Customer / Supplier</label>
    <select name="related_party" id="related_party" class="form-control">
        <option value="">-- Pilih --</option>

        <!-- Barang Masuk -->
        <?php foreach ($unpaid_barangmasuk as $bm): ?>
            <option value="<?= $bm->id ?>" data-nama="<?= $bm->nama ?>" data-tanggal="<?= $bm->tanggal ?>" data-tipe="barangmasuk">
                <?= $bm->nama ?> | <?= $bm->tanggal ?> (Barang Masuk)
            </option>
        <?php endforeach; ?>

        <!-- Barang Keluar -->
        <?php foreach ($unpaid_barangkeluar as $bk): ?>
            <option value="<?= $bk->id ?>" data-nama="<?= $bk->nama ?>" data-tanggal="<?= $bk->tanggal ?>" data-tipe="barangkeluar">
                <?= $bk->nama ?> | <?= $bk->tanggal ?> (Barang Keluar)
            </option>
        <?php endforeach; ?>
    </select>
</div>

								</div>

                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" name="keterangan" id="keterangan" class="form-control" required>
                                    <?= form_error('keterangan', '<div class="text-small text-danger">'); ?>
                                </div>

                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="number" name="jumlah_global" id="jumlah_global" class="form-control" required>
                                </div>

                                <div id="baris-form"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

			<script>
document.addEventListener('DOMContentLoaded', function() {
    const relatedType = document.getElementById('related_type');
    const relatedParty = document.getElementById('related_party');
    const keteranganInput = document.getElementById('keterangan');

    relatedType.addEventListener('change', function() {
        const type = this.value;
        Array.from(relatedParty.options).forEach(opt => {
            if (type === '') {
                opt.style.display = '';
            } else if (opt.getAttribute('data-tipe') !== type) {
                opt.style.display = 'none';
            } else {
                opt.style.display = '';
            }
        });
    }); 

    relatedParty.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const nama = selected.getAttribute('data-nama');
        const tanggal = selected.getAttribute('data-tanggal');
        const tipe = selected.getAttribute('data-tipe');

        if (nama && tanggal && tipe) {
            if (tipe === 'barangmasuk') {
                keteranganInput.value = `Pelunasan Hutang - ${nama} (${tanggal})`;
            } else if (tipe === 'barangkeluar') {
                keteranganInput.value = `Pelunasan Piutang - ${nama} (${tanggal})`;
            }
        }
    });

    document.getElementById('is_related').addEventListener('change', function() {
        const show = this.value == '1';
        document.getElementById('related-section').style.display = show ? 'block' : 'none';
        if (!show) {
            relatedType.value = "";
            relatedParty.selectedIndex = 0;
            keteranganInput.value = "";
        }
    });
});
</script>

            <!--filter-->
            <div class="card">
                <div class="card-body">
                    <form method="get" action="<?= base_url('transaksi') ?>" class="mb-3">
                        <div class="form-row align-items-end">
                            <div class="col-md-4">
                                <label>Bulan</label>
                                <select name="bulan" class="form-control">
                                    <option value="">All</option>
                                    <option value="01" <?= $this->input->get('bulan') == '01' ? 'selected' : '' ?>>Januari</option>
                                    <option value="02" <?= $this->input->get('bulan') == '02' ? 'selected' : '' ?>>Februari</option>
                                    <option value="03" <?= $this->input->get('bulan') == '03' ? 'selected' : '' ?>>Maret</option>
                                    <option value="04" <?= $this->input->get('bulan') == '04' ? 'selected' : '' ?>>April</option>
                                    <option value="05" <?= $this->input->get('bulan') == '05' ? 'selected' : '' ?>>Mei</option>
                                    <option value="06" <?= $this->input->get('bulan') == '06' ? 'selected' : '' ?>>Juni</option>
                                    <option value="07" <?= $this->input->get('bulan') == '07' ? 'selected' : '' ?>>Juli</option>
                                    <option value="08" <?= $this->input->get('bulan') == '08' ? 'selected' : '' ?>>Agustus</option>
                                    <option value="09" <?= $this->input->get('bulan') == '09' ? 'selected' : '' ?>>September</option>
                                    <option value="10" <?= $this->input->get('bulan') == '10' ? 'selected' : '' ?>>Oktober</option>
                                    <option value="11" <?= $this->input->get('bulan') == '11' ? 'selected' : '' ?>>November</option>
                                    <option value="12" <?= $this->input->get('bulan') == '12' ? 'selected' : '' ?>>Desember</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Tahun</label>
                                <input type="number" name="tahun" class="form-control" value="<?= $this->input->get('tahun') ?? date('Y') ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary mt-4 btn-block">Cari</button>
                            </div>
                        </div> 
                    </form>

                    <!--tabel transaksi-->
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center" style="background-color:#9db4d8;">
                                <th>Tanggal</th>
                                <th>No Transaksi</th>
                                <th>Nama Akun</th>
                                <th>No Akun</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($grouped as $key => $transaksi): ?>
                                <?php
                                [$tanggalGroup, $no_trskGroup, $keteranganGroup] = explode('|', $key);
                                // Urutkan transaksi dalam blok berdasarkan id (atau kolom lain yang menandakan urutan input)
                                usort($transaksi, function ($a, $b) {
                                    return $a->id > $b->id ? 1 : -1; // Ganti $a->id jika urutan pakai kolom lain
                                });
                                ?>
                                <tr style="background:#ddd;font-weight:bold;">
                                    <td colspan="7"><?= strtoupper($keteranganGroup) ?></td>
                                </tr>
                                <?php $firstRow = true; ?>
                                <?php foreach ($transaksi as $tr): ?>
                                    <tr>
                                        <td class="text-center">
                                            <?= $firstRow ? date('d-m-Y', strtotime($tanggalGroup)) : '' ?>
                                        </td>
                                        <td class="text-center">
                                            <?= $firstRow ? $no_trskGroup : '' ?>
                                        </td>
                                        <td class="text-left"><?= $tr->nama_akun ?></td>
                                        <td class="text-center"><?= $tr->no_akun ?></td>
                                        <td class="text-right">
                                            <?= $tr->jenis_saldo == 'debit' ? 'Rp ' . number_format($tr->jumlah, 0, ',', '.') : '-' ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $tr->jenis_saldo == 'kredit' ? 'Rp ' . number_format($tr->jumlah, 0, ',', '.') : '-' ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($firstRow): ?>
                                                <button data-toggle="modal" data-target="#edit<?= $no_trskGroup ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="<?= base_url('transaksi/delete/' . $no_trskGroup) ?>" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Apakah anda yakin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $firstRow = false; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-center font-weight-bold">TOTAL</td>
                                <td class="text-right font-weight-bold">Rp <?= number_format($debit, 0, ',', '.') ?></td>
                                <td class="text-right font-weight-bold">Rp <?= number_format($kredit, 0, ',', '.') ?></td>
                            </tr>
                            <tr> 
                                <td colspan="4" class="text-center font-weight-bold">SELISIH</td>
                                <td class="text-center font-weight-bold" colspan="2">
                                    <?php if ($debit >= $kredit): ?>
                                        Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                                    <?php else: ?>
                                        -Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                                    <?php endif; ?>
                                </td>
                                <td colspan="3" class="text-center">
                                    <?php if ($seimbang): ?>
                                        <span class="badge badge-success">SEIMBANG</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">TIDAK SEIMBANG</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <?php
            $no_trsk_unique = [];
            foreach ($edit as $tr):
                if (in_array($tr->no_trsk, $no_trsk_unique)) continue;
                $no_trsk_unique[] = $tr->no_trsk;

                // Ambil semua transaksi dengan no_trsk sama
                $transaksis = array_filter($edit, function ($item) use ($tr) {
                    return $item->no_trsk == $tr->no_trsk;
                });
                $transaksis = array_values($transaksis);

                // Definisikan jumlah_global_edit di sini berdasarkan transaksi pertama
                $jumlah_global_edit = 0;
                if (!empty($transaksis)) {
                    // Cek jenis transaksi dari keterangan untuk menentukan jumlah global
                    $keterangan_modal = strtolower($transaksis[0]->keterangan);
                    if ($keterangan_modal === 'penjualan tunai') {
                        // Untuk penjualan tunai, ambil jumlah dari akun Kas (1011) yang biasanya di debit
                        foreach ($transaksis as $item) {
                            if ($item->no_akun == '1011' && $item->jenis_saldo == 'debit') {
                                $jumlah_global_edit = $item->jumlah;
                                break;
                            }
                        }
                    } elseif ($keterangan_modal === 'penjualan kredit') {
                        // Untuk penjualan kredit, ambil jumlah dari akun Piutang Usaha (1012) yang biasanya di debit
                        foreach ($transaksis as $item) {
                            if ($item->no_akun == '1012' && $item->jenis_saldo == 'debit') {
                                $jumlah_global_edit = $item->jumlah;
                                break;
                            }
                        }
                    } else {
                        // Untuk transaksi lainnya, ambil jumlah dari salah satu akun yang di debit
                        foreach ($transaksis as $item) {
                            if ($item->jenis_saldo == 'debit') {
                                $jumlah_global_edit = $item->jumlah;
                                break;
                            }
                        }
                        // Fallback jika tidak ada debit atau jika logikanya perlu lebih spesifik
                        if ($jumlah_global_edit == 0 && !empty($transaksis)) {
                            // Jika tidak ditemukan debit, ambil saja jumlah dari transaksi pertama
                            $jumlah_global_edit = $transaksis[0]->jumlah;
                        }
                    }
                }
            ?>
                <div class="modal fade" id="edit<?= $tr->no_trsk ?>" tabindex="-1" aria-labelledby="editLabel<?= $tr->no_trsk ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="<?= base_url('transaksi/edit/' .  $tr->no_trsk) ?>" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLabel<?= $tr->no_trsk ?>">Edit Transaksi <?= $tr->no_trsk ?></h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control" value="<?= $transaksis[0]->tanggal ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <input type="text" name="keterangan" class="form-control keterangan-edit" value="<?= $transaksis[0]->keterangan ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Jumlah</label>
                                        <input type="number" name="jumlah_global" class="form-control jumlah-global-edit" value="<?= $jumlah_global_edit ?>" required>
                                    </div>

                                    <div class="baris-form-edit"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- tambah script -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const keteranganInput = document.getElementById('keterangan');
                    const jumlahGlobalInput = document.getElementById('jumlah_global');
                    const barisForm = document.getElementById('baris-form');
                    const akunMapping = <?= json_encode(array_reduce($akun, function ($carry, $ak) {
                                            $carry[$ak->no_akun] = $ak->id_akun;
                                            return $carry;
                                        }, [])); ?>;
                    const akunNama = <?= json_encode(array_reduce($akun, function ($carry, $ak) {
                                            $carry[$ak->no_akun] = $ak->nama_akun;
                                            return $carry;
                                        }, [])); ?>;


                    function renderBaris() {
                        const ket = keteranganInput.value.toLowerCase();
                        const jumlah = parseFloat(jumlahGlobalInput.value) || 0;
                        let hpp = jumlah;
                        // let keuntungan = 0; // Variabel ini tidak digunakan, bisa dihapus jika tidak ada rencana penggunaan
                        barisForm.innerHTML = '';

                        if (ket.includes('penjualan tunai') || ket.includes('penjualan kredit')) {
                            hpp = jumlah / 1.1; // HPP adalah harga pokok penjualan
                            // keuntungan = jumlah - hpp; // Variabel ini tidak digunakan, bisa dihapus jika tidak ada rencana penggunaan
                        }

                        function buatBaris(noAkun, posisi, jumlahAkun) { // Mengubah parameter jumlah menjadi jumlahAkun agar tidak ambigu
                            const namaAkun = akunNama[noAkun] || '';
                            return `
                                <div class="form-row">
                                    <input type="hidden" name="id_akun[]" value="${akunMapping[noAkun]}">
                                    <div class="form-group col-md-4">
                                        <label>${noAkun} - ${namaAkun}</label>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <input type="number" name="jumlah[]" class="form-control" value="${jumlahAkun.toFixed(2)}" required readonly>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select name="jenis_saldo[]" class="form-control" required>
                                            <option value="debit" ${posisi=="debit"?"selected":""}>Debit</option>
                                            <option value="kredit" ${posisi=="kredit"?"selected":""}>Kredit</option>
                                        </select>
                                    </div>
                                </div>`;
                        }
                        
                        
                        if (ket.includes('setoran dana')) {
                            barisForm.innerHTML += buatBaris("1011", "debit", jumlah); // Kas (D)
                            barisForm.innerHTML += buatBaris("3011", "kredit", jumlah); // Modal (K)
                        } else if (ket.includes('penjualan tunai')) {
                            barisForm.innerHTML += buatBaris("1011", "debit", jumlah); // Kas (D)
                            barisForm.innerHTML += buatBaris("4011", "kredit", jumlah); // Pendapatan Penjualan (K)
                            barisForm.innerHTML += buatBaris("5011", "debit", hpp); // Beban Pokok Penjualan (D)
                            barisForm.innerHTML += buatBaris("1013", "kredit", hpp); // Persediaan Barang Dagang (K)
                        } else if (ket.includes('penjualan kredit')) {
                            barisForm.innerHTML += buatBaris("1012", "debit", jumlah); // Piutang Usaha (D)
                            barisForm.innerHTML += buatBaris("4011", "kredit", jumlah); // Pendapatan Penjualan (K)
                            barisForm.innerHTML += buatBaris("5011", "debit", hpp); // Beban Pokok Penjualan (D)
                            barisForm.innerHTML += buatBaris("1013", "kredit", hpp); // Persediaan Barang Dagang (K)
                        } else if (ket.includes('pembelian tunai persediaan')) {
                            barisForm.innerHTML += buatBaris("1013", "debit", jumlah); // Persediaan Barang Dagang (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pembelian kredit persediaan')) {
                            barisForm.innerHTML += buatBaris("1013", "debit", jumlah); // Persediaan Barang Dagang
                            barisForm.innerHTML += buatBaris("2011", "kredit", jumlah); // Utang Usaha
                        } else if (ket.includes('pembelian karung')) {
                            barisForm.innerHTML += buatBaris("1014", "debit", jumlah); // Peralatan (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pembayaran gaji')) {
                            barisForm.innerHTML += buatBaris("6011", "debit", jumlah); // Beban Gaji (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pelunasan hutang')) {
                            barisForm.innerHTML += buatBaris("2011", "debit", jumlah); // Utang Usaha
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas
                        } else if (ket.includes('pelunasan piutang')) {
                            barisForm.innerHTML += buatBaris("1011", "debit", jumlah); // Kas
                            barisForm.innerHTML += buatBaris("1012", "kredit", jumlah); // Piutang Usaha
                        } else if (ket.includes('pembayaran utang gaji')) {
                            barisForm.innerHTML += buatBaris("2012", "debit", jumlah); // Utang Gaji (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pemeliharaan kendaraan')) {
                            barisForm.innerHTML += buatBaris("6014", "debit", jumlah); // Beban Pemeliharaan Kendaraan (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('beban listrik')) {
                            barisForm.innerHTML += buatBaris("6015", "debit", jumlah); // Beban Listrik (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('penyusutan kendaraan')) {
                            barisForm.innerHTML += buatBaris("6013", "debit", jumlah); // Beban Penyusutan Kendaraan (D)
                            barisForm.innerHTML += buatBaris("1022", "kredit", jumlah); // Akumulasi Penyusutan Kendaraan (K)
                        }
                    }

                    keteranganInput.addEventListener('input', renderBaris);
                    jumlahGlobalInput.addEventListener('input', renderBaris);
                });
            </script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const akunMapping = <?= json_encode(array_reduce($akun, function ($carry, $ak) {
                                            $carry[$ak->no_akun] = $ak->id_akun;
                                            return $carry;
                                        }, [])); ?>;
                    const akunNama = <?= json_encode(array_reduce($akun, function ($carry, $ak) {
                                            $carry[$ak->no_akun] = $ak->nama_akun;
                                            return $carry;
                                        }, [])); ?>;

                    function buatBaris(noAkun, posisi, jumlahAkun) { // Mengubah parameter jumlah menjadi jumlahAkun
                        const namaAkun = akunNama[noAkun] || '';
                        return `
                            <div class="form-row">
                                <input type="hidden" name="id_akun[]" value="${akunMapping[noAkun]}">
                                <div class="form-group col-md-4">
                                    <label>${noAkun} - ${namaAkun}</label>
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="number" name="jumlah[]" class="form-control" value="${jumlahAkun.toFixed(2)}" required readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <select name="jenis_saldo[]" class="form-control" required>
                                        <option value="debit" ${posisi=="debit"?"selected":""}>Debit</option>
                                        <option value="kredit" ${posisi=="kredit"?"selected":""}>Kredit</option>
                                    </select>
                                </div>
                            </div>`;
                    }

                    document.querySelectorAll('.modal').forEach(function(modal) {
                        const ketInput = modal.querySelector('.keterangan-edit');
                        const jumlahInput = modal.querySelector('.jumlah-global-edit');
                        const barisForm = modal.querySelector('.baris-form-edit');
                        if (!ketInput || !jumlahInput || !barisForm) return;

                        function renderBarisEdit() {
                            const ket = ketInput.value.toLowerCase();
                            const jumlah = parseFloat(jumlahInput.value) || 0;
                            let hpp = jumlah;
                            if (ket.includes('penjualan tunai') || ket.includes('penjualan kredit')) {
                                hpp = jumlah / 1.1;
                            }

                            barisForm.innerHTML = '';
                            if (ket.includes('setoran dana')) {
                            barisForm.innerHTML += buatBaris("1011", "debit", jumlah); // Kas (D)
                            barisForm.innerHTML += buatBaris("3011", "kredit", jumlah); // Modal (K)
                        } else if (ket.includes('penjualan tunai')) {
                            barisForm.innerHTML += buatBaris("1011", "debit", jumlah); // Kas (D)
                            barisForm.innerHTML += buatBaris("4011", "kredit", jumlah); // Pendapatan Penjualan (K)
                            barisForm.innerHTML += buatBaris("5011", "debit", hpp); // Beban Pokok Penjualan (D)
                            barisForm.innerHTML += buatBaris("1013", "kredit", hpp); // Persediaan Barang Dagang (K)
                        } else if (ket.includes('penjualan kredit')) {
                            barisForm.innerHTML += buatBaris("1012", "debit", jumlah); // Piutang Usaha (D)
                            barisForm.innerHTML += buatBaris("4011", "kredit", jumlah); // Pendapatan Penjualan (K)
                            barisForm.innerHTML += buatBaris("5011", "debit", hpp); // Beban Pokok Penjualan (D)
                            barisForm.innerHTML += buatBaris("1013", "kredit", hpp); // Persediaan Barang Dagang (K)
                        } else if (ket.includes('pembelian tunai persediaan')) {
                            barisForm.innerHTML += buatBaris("1013", "debit", jumlah); // Persediaan Barang Dagang (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pembelian kredit persediaan')) {
                            barisForm.innerHTML += buatBaris("1013", "debit", jumlah); // Persediaan Barang Dagang
                            barisForm.innerHTML += buatBaris("2011", "kredit", jumlah); // Utang Usaha
                        } else if (ket.includes('pembelian karung')) {
                            barisForm.innerHTML += buatBaris("1014", "debit", jumlah); // Peralatan (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pembayaran gaji')) {
                            barisForm.innerHTML += buatBaris("6011", "debit", jumlah); // Beban Gaji (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pelunasan hutang')) {
                            barisForm.innerHTML += buatBaris("2011", "debit", jumlah); // Utang Usaha
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas
                        } else if (ket.includes('pelunasan piutang')) {
                            barisForm.innerHTML += buatBaris("1011", "debit", jumlah); // Kas
                            barisForm.innerHTML += buatBaris("1012", "kredit", jumlah); // Piutang Usaha
                        } else if (ket.includes('pembayaran utang gaji')) {
                            barisForm.innerHTML += buatBaris("2012", "debit", jumlah); // Utang Gaji (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('pemeliharaan kendaraan')) {
                            barisForm.innerHTML += buatBaris("6014", "debit", jumlah); // Beban Pemeliharaan Kendaraan (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('beban listrik')) {
                            barisForm.innerHTML += buatBaris("6015", "debit", jumlah); // Beban Listrik (D)
                            barisForm.innerHTML += buatBaris("1011", "kredit", jumlah); // Kas (K)
                        } else if (ket.includes('penyusutan kendaraan')) {
                            barisForm.innerHTML += buatBaris("6013", "debit", jumlah); // Beban Penyusutan Kendaraan (D)
                            barisForm.innerHTML += buatBaris("1022", "kredit", jumlah); // Akumulasi Penyusutan Kendaraan (K)
                        }

                    }

                        // Panggil render saat pertama modal tampil
                        $(modal).on('shown.bs.modal', function() {
                            renderBarisEdit();
                        });

                        ketInput.addEventListener('input', renderBarisEdit);
                        jumlahInput.addEventListener('input', renderBarisEdit);
                    });
                });
            </script>
        </div>
    </section>
</div>
