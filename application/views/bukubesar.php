<div class="content-wrapper">
    <section class="content"> 
        <div class="container-fluid">
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2>
            <?= $this->session->flashdata('pesan'); ?>

            <form method="get" action="<?= base_url('bukubesar') ?>" class="mb-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label for="no_akun">Nama Akun</label>
                        <select name="no_akun" id="no_akun" class="form-control" required>
                            <option value="">-- Pilih Akun --</option>
                            <?php foreach ($daftar_akun as $akun): ?>
                                <option value="<?= $akun->no_akun ?>" <?= $this->input->get('no_akun') == $akun->no_akun ? 'selected' : '' ?>>
                                    <?= $akun->nama_akun ?> (<?= $akun->no_akun ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bulan">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control">
                            <option value="">-- Semua --</option>
                            <?php for ($m=1; $m<=12; $m++): ?>
                                <option value="<?= sprintf('%02d', $m) ?>" <?= $this->input->get('bulan') == sprintf('%02d', $m) ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0,0,0,$m,1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">-- Semua --</option>
                            <?php
                            $tahun_sekarang = date('Y');
                            for ($y = $tahun_sekarang; $y >= $tahun_sekarang-10; $y--): ?>
                                <option value="<?= $y ?>" <?= $this->input->get('tahun') == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary mt-4 btn-block">Cari</button>
                    </div>
                </div>
            </form>

            <?php if (isset($nama_akun) && $nama_akun): ?>
            <div class="mb-2">
                <b>Nama Akun:</b> <?= $nama_akun ?> <br>
                <b>Kode Akun:</b> <?= $no_akun ?>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">

<?php
// Inisialisasi saldo berjalan untuk perhitungan di view
$saldo_berjalan = 0;
if (isset($jenis_saldo_awal) && $jenis_saldo_awal == 'debit') {
    $saldo_berjalan = $saldo_awal;
} elseif (isset($jenis_saldo_awal) && $jenis_saldo_awal == 'kredit') {
    $saldo_berjalan = -$saldo_awal;
}
?>

<table class="table table-bordered table-striped">
    <thead>
        <tr class="text-center" style="background-color:#9db4d8;">
            <th rowspan="2">Tanggal</th>
            <th rowspan="2">No Transaksi</th>
            <th rowspan="2">Keterangan</th>
            <th rowspan="2">Nama Akun</th>
            <th rowspan="2">No Akun</th>
            <th rowspan="2">Debit / Rp</th>
            <th rowspan="2">Kredit / Rp</th>
            <th colspan="2">Saldo Akhir/ Rp</th>
        </tr>
        <tr class="text-center" style="background-color:#b8cce8;">
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
        <?php if (isset($no_akun) && $no_akun && $saldo_awal > 0): ?>
        <tr>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            <td><b>Saldo Awal</b></td>
            <td class="text-left"><?= $nama_akun ?></td>
            <td class="text-center"><?= $no_akun ?></td>
            <td class="text-right">
                <?= (isset($jenis_saldo_awal) && $jenis_saldo_awal == 'debit') ? number_format($saldo_awal, 0, ',', '.') : '-' ?>
            </td>
            <td class="text-right">
                <?= (isset($jenis_saldo_awal) && $jenis_saldo_awal == 'kredit') ? number_format($saldo_awal, 0, ',', '.') : '-' ?>
            </td>
            <td class="text-right">
                <?= ($saldo_berjalan >= 0) ? number_format(abs($saldo_berjalan), 0, ',', '.') : '-' ?>
            </td>
            <td class="text-right">
                <?= ($saldo_berjalan < 0) ? number_format(abs($saldo_berjalan), 0, ',', '.') : '-' ?>
            </td>
        </tr>
        <?php endif; ?>
        
        <?php foreach ($grouped as $groupKey => $transaksis): ?>
            <?php
                list($tanggal, $no_trsk, $keterangan) = explode('|', $groupKey);
                $firstRow = true;
            ?>
            <?php foreach ($transaksis as $tr): ?>
                <?php
                    $d = 0;
                    $k = 0;
                    if ($tr->no_akun == $no_akun) {
                        if ($tr->jenis_saldo == 'debit') {
                            $d = $tr->jumlah;
                            $saldo_berjalan += $tr->jumlah;
                        } elseif ($tr->jenis_saldo == 'kredit') {
                            $k = $tr->jumlah;
                            $saldo_berjalan -= $tr->jumlah;
                        }
                    }
                ?>
                <?php if ($tr->no_akun == $no_akun): ?>
                <tr>
                    <td class="text-center"><?= $firstRow ? date('d-m-Y', strtotime($tanggal)) : '' ?></td>
                    <td class="text-center"><?= $firstRow ? $no_trsk : '' ?></td>
                    <td><?= $firstRow ? $keterangan : '' ?></td>
                    <td><?= $tr->nama_akun ?></td>
                    <td class="text-center"><?= $tr->no_akun ?></td>
                    <td class="text-right"><?= $d ? number_format($d, 0, ',', '.') : '-' ?></td>
                    <td class="text-right"><?= $k ? number_format($k, 0, ',', '.') : '-' ?></td>
                    <td class="text-right">
                        <?= ($saldo_berjalan >= 0) ? number_format(abs($saldo_berjalan), 0, ',', '.') : '-' ?>
                    </td>
                    <td class="text-right">
                        <?= ($saldo_berjalan < 0) ? number_format(abs($saldo_berjalan), 0, ',', '.') : '-' ?>
                    </td>
                </tr>
                <?php $firstRow = false; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-center font-weight-bold">TOTAL</td>
            <td class="text-right font-weight-bold">Rp <?= number_format($debit, 0, ',', '.') ?></td>
            <td class="text-right font-weight-bold">Rp <?= number_format($kredit, 0, ',', '.') ?></td>
            <td class="text-right font-weight-bold">
                <?= isset($total_saldo_debit) && $total_saldo_debit > 0 ? 'Rp ' . number_format($total_saldo_debit, 0, ',', '.') : '-' ?>
            </td>
            <td class="text-right font-weight-bold">
                <?= isset($total_saldo_kredit) && $total_saldo_kredit > 0 ? 'Rp ' . number_format($total_saldo_kredit, 0, ',', '.') : '-' ?>
            </td>
        </tr>
    </tfoot>
</table>

                </div>
            </div>
        </div>
    </section>
</div>
