<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            
      <h2 class="pt-3 pb-2 text-white"><?= $title; ?></h2> 
            <?= $this->session->flashdata('pesan'); ?>

            <!-- Form Filter -->
            <div class="card">
                <div class="card-body">
  <form method="get" action="<?= base_url('penyesuaian') ?>" class="mb-3">
    <div class="form-row align-items-end">
        <div class="col-md-4">
            <label>Bulan</label>
            <select name="bulan" class="form-control">
                <option value="">All</option>
                <option value="01" <?= $this->input->get('bulan')=='01'?'selected':'' ?>>Januari</option>
                <option value="02" <?= $this->input->get('bulan')=='02'?'selected':'' ?>>Februari</option>
                <option value="03" <?= $this->input->get('bulan')=='03'?'selected':'' ?>>Maret</option>
                <option value="04" <?= $this->input->get('bulan')=='04'?'selected':'' ?>>April</option>
                <option value="05" <?= $this->input->get('bulan')=='05'?'selected':'' ?>>Mei</option>
                <option value="06" <?= $this->input->get('bulan')=='06'?'selected':'' ?>>Juni</option>
                <option value="07" <?= $this->input->get('bulan')=='07'?'selected':'' ?>>Juli</option>
                <option value="08" <?= $this->input->get('bulan')=='08'?'selected':'' ?>>Agustus</option>
                <option value="09" <?= $this->input->get('bulan')=='09'?'selected':'' ?>>September</option>
                <option value="10" <?= $this->input->get('bulan')=='10'?'selected':'' ?>>Oktober</option>
                <option value="11" <?= $this->input->get('bulan')=='11'?'selected':'' ?>>November</option>
                <option value="12" <?= $this->input->get('bulan')=='12'?'selected':'' ?>>Desember</option>
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

<!-- Tabel Penyesuaian -->
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
    <thead>
        <tr class="text-center" style="background-color:#9db4d8;">
            <th>Tanggal</th>
            <th>No Transaksi</th>
            <th>Nama Akun</th>
            <th>No Akun</th>
            <th>Debit</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $total_debit = 0;
    $total_kredit = 0;
    foreach ($grouped as $key => $blok):
        [$tanggalGroup, $no_trskGroup, $keteranganGroup] = explode('|', $key);
        usort($blok, function($a, $b) { return $a->id > $b->id ? 1 : -1; });
    ?>
    <tr style="background:#ddd;font-weight:bold;">
        <td colspan="6"><?= strtoupper($keteranganGroup) ?></td>
    </tr>
    <?php $firstRow = true; ?>
    <?php foreach ($blok as $tr): ?>
        <tr>
            <td class="text-center"><?= $firstRow ? date('d-m-Y', strtotime($tanggalGroup)) : '' ?></td>
            <td class="text-center"><?= $firstRow ? $no_trskGroup : '' ?></td>
            <td class="text-left"><?= $tr->nama_akun ?></td>
            <td class="text-center"><?= $tr->no_akun ?></td>
            <td class="text-right">
                <?= ($tr->jenis_saldo == 'debit') ? 'Rp '.number_format($tr->jumlah,0,',','.') : '-' ?>
            </td>
            <td class="text-right">
                <?= ($tr->jenis_saldo == 'kredit') ? 'Rp '.number_format($tr->jumlah,0,',','.') : '-' ?>
            </td>
        </tr>
        <?php 
            if ($tr->jenis_saldo == 'debit') $total_debit += $tr->jumlah;
            if ($tr->jenis_saldo == 'kredit') $total_kredit += $tr->jumlah;
            $firstRow = false;
        ?>
    <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="text-center font-weight-bold">TOTAL PENYESUAIAN</td>
            <td class="text-right font-weight-bold">Rp <?= number_format($total_debit,0,',','.') ?></td>
            <td class="text-right font-weight-bold">Rp <?= number_format($total_kredit,0,',','.') ?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center font-weight-bold">SELISIH</td>
            <td class="text-center font-weight-bold" colspan="2">
                <?php
                $selisih = $total_debit - $total_kredit;
                echo ($selisih >= 0 ? 'Rp '.number_format(abs($selisih),0,',','.') : '-Rp '.number_format(abs($selisih),2,',','.'));
                ?>
            </td>
        </tr> 
    </tfoot>
</table>
                </div>
            </div>

        </div>
    </section>
</div>
