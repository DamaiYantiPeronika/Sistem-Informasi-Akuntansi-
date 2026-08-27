<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?><?= $periode; ?></h2>

            <div class="card">
                <div class="card-body">
                    <form method="get" action="<?= base_url('neraca') ?>" class="mb-3">
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

                    <div class="mb-3">
                        <?php 
                        $print_url = base_url('Neraca/print_neraca');
                        $params = [];
                        $bulan = $this->input->get('bulan');
                        $tahun = $this->input->get('tahun');
                        if ($bulan) $params[] = 'bulan=' . $bulan;
                        if ($tahun) $params[] = 'tahun=' . $tahun;
                        if (!empty($params)) {
                            $print_url .= '?' . implode('&', $params);
                        }
                        ?>
                        <a href="<?= $print_url; ?>" target="_blank" class="btn btn-primary">
                            <i class="fas fa-print"></i> Cetak Laporan Neraca
                        </a>
                    </div>

                    <?php
                    $total_aktiva_view = 0;
                    $total_pasiva_view = 0;
                    ?>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h4>AKTIVA</h4>
                            <table class="table table-bordered">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($neraca as $row): ?>
                                        <?php if (substr($row->no_akun, 0, 1) == '1' && $row->saldo_akhir != 0): ?>
                                            <?php $total_aktiva_view += $row->saldo_akhir; ?>
                                            <tr>
                                                <td class="text-left">
                                                    <?= $row->nama_akun ?>
                                                    <small class="text-muted">(<?= $row->no_akun ?>)</small>
                                                </td>
                                                <td class="text-right"><?= number_format($row->saldo_akhir, 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot style="background:#f0f8ff;">
                                    <tr>
                                        <th>Total Aktiva</th>
                                        <th class="text-right"><?= number_format($total_aktiva_view, 0, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4>KEWAJIBAN & MODAL</h4>
                            <table class="table table-bordered">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Nama Akun</th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Tampilkan Kewajiban (akun 2xxx) -->
                                    <?php foreach ($neraca as $row): ?>
                                        <?php if (substr($row->no_akun, 0, 1) == '2' && $row->saldo_akhir != 0): ?>
                                            <?php $total_pasiva_view += $row->saldo_akhir; ?>
                                            <tr>
                                                <td class="text-left">
                                                    <?= $row->nama_akun ?>
                                                    <small class="text-muted">(<?= $row->no_akun ?>)</small>
                                                </td>
                                                <td class="text-right"><?= number_format($row->saldo_akhir, 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    
                                    <!-- Tampilkan Modal lainnya (akun 3xxx kecuali yang sudah diexclude) -->
                                    <?php foreach ($neraca as $row): ?>
                                        <?php if (substr($row->no_akun, 0, 1) == '3' && 
                                                  $row->nama_akun != 'Modal Akhir' && 
                                                  $row->saldo_akhir != 0): ?>
                                            <?php $total_pasiva_view += $row->saldo_akhir; ?>
                                            <tr>
                                                <td class="text-left">
                                                    <?= $row->nama_akun ?>
                                                    <small class="text-muted">(<?= $row->no_akun ?>)</small>
                                                </td>
                                                <td class="text-right"><?= number_format($row->saldo_akhir, 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    
                                    <!-- Tampilkan Modal Akhir sebagai satu kesatuan -->
                                    <?php foreach ($neraca as $row): ?>
                                        <?php if ($row->nama_akun == 'Modal Akhir'): ?>
                                            <?php $total_pasiva_view += $row->saldo_akhir; ?>
                                            <tr class="table-info">
                                                <td class="text-left">
                                                    <strong><?= $row->nama_akun ?></strong>
                                                    <small class="text-muted">(Hasil Lap. Perubahan Modal)</small>
                                                </td>
                                                <td class="text-right"><strong><?= number_format($row->saldo_akhir, 0, ',', '.') ?></strong></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot style="background:#f0f8ff;">
                                    <tr>
                                        <th>Total Kewajiban & Modal</th>
                                        <th class="text-right"><?= number_format($total_pasiva_view, 0, ',', '.') ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table">
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <?php 
                                        $selisih = abs($total_aktiva_view - $total_pasiva_view);
                                        ?>
                                        <?php if ($total_aktiva_view != $total_pasiva_view): ?>
                                            <div class="alert alert-danger">
                                                <strong>PERINGATAN: Neraca Tidak Seimbang!</strong><br>
                                                Total Aktiva: <?= number_format($total_aktiva_view, 0, ',', '.') ?><br>
                                                Total Pasiva: <?= number_format($total_pasiva_view, 0, ',', '.') ?><br>
                                                Selisih: <?= number_format($selisih, 0, ',', '.') ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-success">
                                                <strong>✓ Neraca Seimbang.</strong><br>
                                                Total: <?= number_format($total_aktiva_view, 0, ',', '.') ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Tampilkan detail perhitungan modal -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Detail Perhitungan Modal Akhir:</h6>
                                <p class="mb-1">Modal Akhir ini merupakan hasil dari Laporan Perubahan Modal yang mencakup:</p>
                                <ul class="mb-0">
                                    <li>Modal Awal (3011)</li>
                                    <li>+ Laba Bersih (atau - Rugi Bersih)</li>
                                    <li>- Prive (3012)</li>
                                    <li>= Modal Akhir: <strong><?= number_format($modal_akhir, 0, ',', '.') ?></strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
