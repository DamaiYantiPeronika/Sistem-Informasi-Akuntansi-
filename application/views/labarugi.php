<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>
 
            <div class="card">
                <div class="card-body">
                    <form method="get" action="<?= base_url('labarugi') ?>" class="mb-3">
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
                        $print_url = base_url('labarugi/print_labarugi');
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
                            <i class="fas fa-print"></i> Cetak Laporan Laba Rugi
                        </a>
                    </div>

                    <?php if (isset($periode) && $periode): ?>
                        <div class="mb-2">
                            <b>Periode:</b> <?= $periode ?>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">

                            <?php
                            $total_pendapatan = 0;
                            $total_beban_hpp = 0;
                            ?>

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr class="text-center" style="background-color:#9db4d8;">
                                        <th>No Akun</th>
                                        <th>Nama Akun</th>
                                        <th>Jumlah</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- PENDAPATAN -->
                                    <tr style="background-color:#e8f4f8;">
                                        <td colspan="3" class="text-left font-weight-bold">PENDAPATAN</td>
                                    </tr>
                                    <?php if (isset($laba_rugi) && !empty($laba_rugi)): ?>
                                        <?php foreach ($laba_rugi as $item): ?>
                                            <?php if (substr($item->no_akun, 0, 1) == '4'): ?>
                                                <?php
                                                // saldo akhir pendapatan (normal kredit)
                                                $saldo_akhir = $item->saldo_awal_kredit - $item->saldo_awal_debit + $item->mutasi_kredit - $item->mutasi_debit;
                                                $total_pendapatan += $saldo_akhir;
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?= $item->no_akun ?></td>
                                                    <td class="text-left"><?= $item->nama_akun ?></td>
                                                    <td class="text-right"><?= number_format($saldo_akhir, 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data pendapatan</td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr style="background-color:#f0f8ff;">
                                        <td colspan="2" class="text-right font-weight-bold">Total Pendapatan</td>
                                        <td class="text-right font-weight-bold"><?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                                    </tr>

                                    <!-- HPP & BEBAN -->
                                    <tr style="background-color:#e8f4f8;">
                                        <td colspan="3" class="text-left font-weight-bold">HPP & BEBAN</td>
                                    </tr>
                                    <?php if (isset($laba_rugi) && !empty($laba_rugi)): ?>
                                        <?php foreach ($laba_rugi as $item): ?>
                                            <?php if (substr($item->no_akun, 0, 1) == '5' || substr($item->no_akun, 0, 1) == '6'): ?>
                                                <?php
                                                // saldo akhir beban/hpp (normal debit)
                                                $saldo_akhir = $item->saldo_awal_debit - $item->saldo_awal_kredit + $item->mutasi_debit - $item->mutasi_kredit;
                                                $total_beban_hpp += $saldo_akhir;
                                                ?>
                                                <tr>
                                                    <td class="text-center"><?= $item->no_akun ?></td>
                                                    <td class="text-left"><?= $item->nama_akun ?></td>
                                                    <td class="text-right"><?= number_format($saldo_akhir, 0, ',', '.') ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data HPP & Beban</td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr style="background-color:#f0f8ff;">
                                        <td colspan="2" class="text-right font-weight-bold">Total HPP & Beban</td>
                                        <td class="text-right font-weight-bold"><?= number_format($total_beban_hpp, 0, ',', '.') ?></td>
                                    </tr>

                                    <!-- LABA / RUGI -->
                                    <?php $laba_rugi_bersih = $total_pendapatan - $total_beban_hpp; ?>
                                    <tr style="background-color:#<?= $laba_rugi_bersih >= 0 ? 'd4edda' : 'f8d7da' ?>;">
                                        <td colspan="2" class="text-right font-weight-bold">
                                            <?= $laba_rugi_bersih >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' ?>
                                        </td>
                                        <td class="text-right font-weight-bold">
                                            <?= number_format(abs($laba_rugi_bersih), 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Informasi Summary -->
                            <div class="mt-3">
                                <h6>Ringkasan:</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Total Pendapatan:</strong> <?= number_format($total_pendapatan, 0, ',', '.') ?></li>
                                    <li><strong>Total HPP & Beban:</strong> <?= number_format($total_beban_hpp, 0, ',', '.') ?></li>
                                    <li><strong>Hasil:</strong> 
                                        <span class="badge badge-<?= $laba_rugi_bersih >= 0 ? 'success' : 'danger' ?>">
                                            <?= $laba_rugi_bersih >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' ?> 
                                            <?= number_format(abs($laba_rugi_bersih), 0, ',', '.') ?>
                                        </span>
                                    </li>
                                    <li><strong>Margin (%):</strong> 
                                        <?php if ($total_pendapatan > 0): ?>
                                            <?= number_format(($laba_rugi_bersih / $total_pendapatan) * 100, 2, ',', '.') ?>%
                                        <?php else: ?>
                                            0.00%
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<style>
@media print {
    .content-wrapper {
        margin: 0 !important;
        padding: 15px !important;
    }
    
    .btn, .form-control, .card {
        display: none !important;
    }
    
    .table {
        margin-top: 20px !important;
        font-size: 12px !important;
    }
    
    .table th, .table td {
        padding: 5px !important;
    }
    
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    body {
        font-size: 12px !important;
    }

    @page {
        margin: 0.5in;
        @bottom-left { content: ""; }
        @bottom-right { content: ""; }
        @bottom-center { content: ""; }
    }
    
    footer, .print-footer, .url-footer {
        display: none !important;
    }
}
</style> 
