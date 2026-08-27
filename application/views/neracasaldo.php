<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?><?= $periode; ?></h2>

            <form method="get" action="<?= base_url('neracasaldo') ?>" class="mb-3">
                <div class="row align-items-center">
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
                        <button type="submit" class="btn btn-primary mt-4 btn-block">Tampilkan</button>
                    </div>
                </div>
            </form>

            <?php if (isset($periode) && $periode): ?>
                <div class="mb-2">
                    <b>Periode:</b> <?= $periode ?>
                </div>
            <?php endif; ?>

            <!-- Validasi Balance -->
            <?php 
            $balance_info = $this->Neracasaldo_model->validate_balance($this->input->get('bulan'), $this->input->get('tahun'));
            ?>
           
            <div class="mb-3">
                <?php 
                $print_url = base_url('neracasaldo/print_neraca');
                $params = [];
                if ($bulan) $params[] = 'bulan=' . $bulan;
                if ($tahun) $params[] = 'tahun=' . $tahun;
                if (!empty($params)) {
                    $print_url .= '?' . implode('&', $params);
                }
                ?>
                <a href="<?= $print_url; ?>" target="_blank" class="btn btn-primary">
                    <i class="fas fa-print"></i> Cetak Neraca Saldo
                </a>
            </div>
			
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="text-center" style="background-color:#2c3e50; color: white;">
                                <th rowspan="2">No. Akun</th>
                                <th rowspan="2">Nama Akun</th>
                                <th colspan="2">Saldo Awal</th>
                                <th colspan="2">Mutasi</th>
                                <th colspan="2">Saldo Akhir</th>
                            </tr>
                            <tr class="text-center" style="background-color:#34495e; color: white;">
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($neraca_saldo) && !empty($neraca_saldo)): ?>
                                <?php 
                                $total_saldo_awal_debit = 0;
                                $total_saldo_awal_kredit = 0;
                                $total_mutasi_debit = 0;
                                $total_mutasi_kredit = 0;
                                $total_saldo_akhir_debit = 0;
                                $total_saldo_akhir_kredit = 0;
                                ?>
                                <?php foreach ($neraca_saldo as $akun): ?>
                                    <?php
                                    // Akumulasi total
                                    $total_saldo_awal_debit += $akun->saldo_awal_debit;
                                    $total_saldo_awal_kredit += $akun->saldo_awal_kredit;
                                    $total_mutasi_debit += $akun->mutasi_debit;
                                    $total_mutasi_kredit += $akun->mutasi_kredit;
                                    $total_saldo_akhir_debit += $akun->saldo_akhir_debit;
                                    $total_saldo_akhir_kredit += $akun->saldo_akhir_kredit;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $akun->no_akun ?></td>
                                        <td class="text-left"><?= $akun->nama_akun ?></td>
                                        <td class="text-right">
                                            <?= $akun->saldo_awal_debit > 0 ? number_format($akun->saldo_awal_debit, 0, ',', '.') : '-' ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $akun->saldo_awal_kredit > 0 ? number_format($akun->saldo_awal_kredit, 0, ',', '.') : '-' ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $akun->mutasi_debit > 0 ? number_format($akun->mutasi_debit, 0, ',', '.') : '-' ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $akun->mutasi_kredit > 0 ? number_format($akun->mutasi_kredit, 0, ',', '.') : '-' ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $akun->saldo_akhir_debit > 0 ? number_format($akun->saldo_akhir_debit, 0, ',', '.') : '-' ?>
                                        </td>
                                        <td class="text-right">
                                            <?= $akun->saldo_akhir_kredit > 0 ? number_format($akun->saldo_akhir_kredit, 0, ',', '.') : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data yang ditemukan</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color:#ecf0f1; font-weight: bold;">
                                <td colspan="2" class="text-center font-weight-bold">TOTAL</td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_saldo_awal_debit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_saldo_awal_kredit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_mutasi_debit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_mutasi_kredit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_saldo_akhir_debit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_saldo_akhir_kredit, 0, ',', '.') ?>
                                </td>
                            </tr>
                            <!-- Balance Check Row -->
                            <tr style="background-color:#<?= $balance_info['is_balanced'] ? 'd4edda' : 'f8d7da' ?>;">
                                <td colspan="2" class="text-center font-weight-bold">SELISIH</td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_saldo_awal_debit - $total_saldo_awal_kredit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">-</td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_mutasi_debit - $total_mutasi_kredit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">-</td>
                                <td class="text-right font-weight-bold">
                                    <?= number_format($total_saldo_akhir_debit - $total_saldo_akhir_kredit, 0, ',', '.') ?>
                                </td>
                                <td class="text-right font-weight-bold">-</td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Informasi Balance -->
                    <div class="mt-3">
                        <h6>Keterangan:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Saldo Awal:</strong> Total Debit = <?= number_format($total_saldo_awal_debit, 0, ',', '.') ?>, Total Kredit = <?= number_format($total_saldo_awal_kredit, 0, ',', '.') ?></li>
                            <li><strong>Mutasi:</strong> Total Debit = <?= number_format($total_mutasi_debit, 0, ',', '.') ?>, Total Kredit = <?= number_format($total_mutasi_kredit, 0, ',', '.') ?></li>
                            <li><strong>Saldo Akhir:</strong> Total Debit = <?= number_format($total_saldo_akhir_debit, 0, ',', '.') ?>, Total Kredit = <?= number_format($total_saldo_akhir_kredit, 0, ',', '.') ?></li>
                            <li><strong>Status:</strong> <span class="badge badge-<?= $balance_info['is_balanced'] ? 'success' : 'danger' ?>"><?= $balance_info['is_balanced'] ? 'SEIMBANG' : 'TIDAK SEIMBANG' ?></span></li>
                        </ul>
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
    
    .btn, .form-control, .alert, .card {
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
}
</style>
