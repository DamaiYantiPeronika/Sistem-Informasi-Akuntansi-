<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?><?= $periode; ?></h2>

            <div class="card">
                <div class="card-body">
                    <form method="get" action="<?= base_url('perubahanmodal') ?>" class="mb-3">
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
                $print_url = base_url('Perubahanmodal/print_perubahanmodal');
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
                    <i class="fas fa-print"></i> Cetak Laporan Perubahan Modal
                </a>
            </div>

                    <?php
                    // Tentukan apakah laba atau rugi berdasarkan nilai laba_bersih
                    $is_laba = $laba_bersih >= 0;
                    $label_laba_rugi = $is_laba ? 'Laba Bersih' : 'Rugi Bersih';
                    $nilai_absolut = abs($laba_bersih);
                    
                    // Modal akhir sudah dihitung di controller
                    ?>

                    <table class="table table-bordered mt-4">
                        <tr>
                            <th>Modal Awal (3011)</th>
                            <td class="text-right"><?= number_format($modal_awal, 0, ',', '.') ?></td>
                        </tr>
                        <tr class="<?= $is_laba ? 'table-success' : 'table-danger' ?>">
                            <th><?= $label_laba_rugi ?></th>
                            <td class="text-right">
                                <?php if ($is_laba): ?>
                                    <?= number_format($nilai_absolut, 0, ',', '.') ?>
                                <?php else: ?>
                                    (<?= number_format($nilai_absolut, 0, ',', '.') ?>)
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Prive (3012)</th>
                            <td class="text-right">(<?= number_format($prive, 0, ',', '.') ?>)</td>
                        </tr>
                        <tr style="background-color:#f0f8ff;">
                            <th>Modal Akhir</th>
                            <td class="text-right font-weight-bold"><?= number_format($modal_akhir, 0, ',', '.') ?></td>
                        </tr>
                    </table>

                    <div class="mt-3">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Informasi Perhitungan:</h6>
                            <ul class="mb-0">
                                <li><strong>Modal Awal:</strong> <?= number_format($modal_awal, 0, ',', '.') ?></li>
                                <li><strong><?= $label_laba_rugi ?>:</strong> 
                                    <?php if ($is_laba): ?>
                                        <span class="text-success">+<?= number_format($nilai_absolut, 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-danger">-<?= number_format($nilai_absolut, 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </li>
                                <li><strong>Prive:</strong> <span class="text-danger">-<?= number_format($prive, 0, ',', '.') ?></span></li>
                                <li><strong>Modal Akhir:</strong> <span class="font-weight-bold"><?= number_format($modal_akhir, 0, ',', '.') ?></span></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
