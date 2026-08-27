<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">

            
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?><?= $periode ?></h2>

            <div class="card">
                <div class="card-body">
                    <form method="get" action="<?= base_url('penutup') ?>" class="mb-3">
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
                        <a href="<?= base_url('penutup/proses?bulan=' . $this->input->get('bulan') . '&tahun=' . $this->input->get('tahun')) ?>"
                            onclick="return confirm('Yakin ingin memproses jurnal penutup dan menyimpan ke transaksi?')"
                            class="btn btn-success">
                            <i class="fas fa-save"></i> Proses & Simpan Jurnal Penutup
                        </a>
                    </div>

                    <?php
                    $total_debit = 0;
                    $total_kredit = 0;
                    ?>

                    <table class="table table-bordered mt-4">
                        <thead style="background:#d0e3f0;">
                            <tr class="text-center">
                                <th>No Akun</th>
                                <th>Nama Akun</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($penutup as $row): ?>
                                <?php
                                $total_debit += $row->debit;
                                $total_kredit += $row->kredit;
                                ?>
                                <tr>
                                    <td class="text-center"><?= $row->no_akun ?></td>
                                    <td class="text-left"><?= $row->nama_akun ?></td>
                                    <td class="text-right"><?= $row->debit ? number_format($row->debit, 0, ',', '.') : '-' ?></td>
                                    <td class="text-right"><?= $row->kredit ? number_format($row->kredit, 0, ',', '.') : '-' ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr style="background:#f0f8ff;">
                                <th colspan="2" class="text-center">Total</th>
                                <th class="text-right"><?= number_format($total_debit, 0, ',', '.') ?></th>
                                <th class="text-right"><?= number_format($total_kredit, 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>

                </div>
    </section>
</div>
