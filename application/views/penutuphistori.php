<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>
            <p class="text-center">Periode: <?= $periode; ?></p>

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

                    <div class="card mt-3">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="text-center" style="background-color:#9db4d8;">
                                    <tr>
                                        <th>No Bukti</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Total Debit</th>
                                        <th>Total Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($histori): ?>
                                        <?php foreach ($histori as $row): ?>
                                            <tr>
                                                <td class="text-center"><?= $row->no_trsk ?></td>
												<td class="text-center">
													<?= $firstRow ? date('d-m-Y', strtotime($tanggalGroup)) : '' ?>
												</td>
                                                <td class="text-left"><?= $row->keterangan ?></td>
                                                <td class="text-right"><?= number_format($row->total_debit, 0, ',', '.') ?></td>
                                                <td class="text-right"><?= number_format($row->total_kredit, 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr style="background:#f0f8ff;">
                                        <th colspan="3" class="text-right">Total Debit</th>
                                        <th class="text-right">
                                            <?= number_format($total_histori->total_debit, 0, ',', '.') ?>
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr style="background:#f0f8ff;">
                                        <th colspan="3" class="text-right">Total Kredit</th>
                                        <th class="text-right">
                                            <?= number_format($total_histori->total_kredit, 0, ',', '.') ?>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
    </section>
</div>
