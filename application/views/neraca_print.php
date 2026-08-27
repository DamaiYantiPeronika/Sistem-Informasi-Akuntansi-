<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Neraca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .period {
            font-size: 14px;
            font-style: italic;
        }
        
        .content {
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }
        
        .section {
            flex: 1;
        }
        
        .section h3 {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        td, th {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        
        .account-name {
            text-align: left;
        }
        
        .amount {
            text-align: right;
            width: 120px;
        }
        
        .total-row {
            background-color: #50b1fbff;
            font-weight: bold;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }
        
        .balance-check {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            border: 2px solid #000;
            font-weight: bold;
        }
        
        .balance-success {
            color: #28a745;
        }
        
        .balance-error {
            color: #dc3545;
        }
        
        .balance-info {
            margin-top: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #50b1fbff;
        }
        
        .balance-info h4 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 14px;
            color: #333;
        }
        
        .balance-info table {
            margin-bottom: 0;
        }
        
        .balance-info td {
            border: none !important;
            padding: 5px 0;
        }
        
        .status-balanced {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-unbalanced {
            color: #dc3545;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        
        .signature {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            height: 60px;
            margin-bottom: 5px;
			
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php
    // Ambil parameter dari URL
    $bulan = $this->input->get('bulan');
    $tahun = $this->input->get('tahun') ?? date('Y');
    
    // Format periode
    $nama_bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    
    if ($bulan && isset($nama_bulan[$bulan])) {
        $periode = $nama_bulan[$bulan] . ' ' . $tahun;
    } else {
        $periode = 'Tahun ' . $tahun;
    }
    ?>

    <div class="header">
        <div class="company-name">UD. Bawang Tobing</div>
        <div class="report-title">NERACA</div>
        <div class="period">Per <?= $periode ?></div>
    </div>

    <?php
    // Pastikan data neraca tersedia
    if (!isset($neraca) || empty($neraca)) {
        $neraca = [];
    }
    
    // Hitung total balance untuk informasi balance
    $total_saldo_awal_debit = 0;
    $total_saldo_awal_kredit = 0;
    $total_mutasi_debit = 0;
    $total_mutasi_kredit = 0;
    $total_saldo_akhir_debit = 0;
    $total_saldo_akhir_kredit = 0;
    
    foreach ($neraca as $row) {
        // Pastikan $row adalah objek dan memiliki properti yang diperlukan
        if (!is_object($row)) continue;
        
        // Saldo Awal
        if (isset($row->saldo_awal) && is_numeric($row->saldo_awal)) {
            if ($row->saldo_awal >= 0) {
                $total_saldo_awal_debit += $row->saldo_awal;
            } else {
                $total_saldo_awal_kredit += abs($row->saldo_awal);
            }
        }
        
        // Mutasi (jika ada field mutasi_debit dan mutasi_kredit)
        if (isset($row->mutasi_debit) && is_numeric($row->mutasi_debit)) {
            $total_mutasi_debit += $row->mutasi_debit;
        }
        if (isset($row->mutasi_kredit) && is_numeric($row->mutasi_kredit)) {
            $total_mutasi_kredit += $row->mutasi_kredit;
        }
        
        // Saldo Akhir - pastikan properti saldo_akhir ada
        if (isset($row->saldo_akhir) && is_numeric($row->saldo_akhir)) {
            if ($row->saldo_akhir >= 0) {
                $total_saldo_akhir_debit += $row->saldo_akhir;
            } else {
                $total_saldo_akhir_kredit += abs($row->saldo_akhir);
            }
        }
    }
    
    // Informasi balance
    $balance_info = [
        'is_balanced' => ($total_saldo_akhir_debit == $total_saldo_akhir_kredit)
    ];
    ?>
        
		<?php
		$total_pendapatan = 0;
        $total_beban_hpp = 0;
        ?>

    <div class="content">
        <div class="section">
            <h3>AKTIVA</h3>
            <table>
                <?php 
                $total_aktiva = 0;
                if (!empty($neraca)): 
                    foreach ($neraca as $row): 
                        if (is_object($row) && isset($row->nama_akun)):
                            if (substr($row->no_akun, 0, 1) == '1'):
                                $total_aktiva += $row->saldo_akhir; ?>
								<tr>
                                    <td><?= $row->nama_akun ?></td>
                                    <td><?= number_format($row->saldo_akhir, 0, ',', '.') ?></td>
                                </tr>
                <?php 
                            endif;
                        endif;
                    endforeach; 
                endif;
                ?>
                <tr class="total-row">
                    <td class="account-name"><strong>TOTAL AKTIVA</strong></td>
                    <td class="amount"><strong>Rp <?= number_format($total_aktiva, 0, ',', '.') ?></strong></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>KEWAJIBAN & MODAL</h3>
            <table>
                <?php 
                $total_pasiva = 0;
                if (!empty($neraca)): 
                    foreach ($neraca as $row): 
                        if (!is_object($row) || !isset($row->no_akun, $row->nama_akun, $row->saldo_akhir)) continue;
                        
                        // Handle Ikhtisar Laba/Rugi
                        if ($row->nama_akun == 'Ikhtisar L/R'): 
                            // Cari nilai Ikhtisar Laba/Rugi dari array neraca
                            $laba_rugi = 0;
                            foreach ($neraca as $r) {
                                if (is_object($r) && isset($r->nama_akun) && $r->nama_akun == 'Ikhtisar Laba/Rugi' && isset($r->saldo_akhir)) {
                                    $laba_rugi = $r->saldo_akhir;
                                    break;
                                }
                            }
                            $total_pasiva += $laba_rugi;
                ?>
                    <tr>
                        <td class="account-name"><?= htmlspecialchars($row->nama_akun) ?></td>
                        <td class="amount">Rp <?= number_format($laba_rugi, 0, ',', '.') ?></td>
                    </tr>
                <?php 
                        elseif (
                            (substr($row->no_akun, 0, 1) == '2' || substr($row->no_akun, 0, 1) == '3')
                            && $row->nama_akun != 'Ikhtisar L/R'
                            && $row->nama_akun != 'Ikhtisar Laba/Rugi'
                        ):
                            $total_pasiva += $row->saldo_akhir;
                ?>
                    <tr>
                        <td class="account-name"><?= htmlspecialchars($row->nama_akun) ?></td>
                        <td class="amount">Rp <?= number_format($row->saldo_akhir, 0, ',', '.') ?></td>
                    </tr>
                <?php 
                        endif;
                    endforeach; 
                endif;
                ?>
                <tr class="total-row">
                    <td class="account-name"><strong>TOTAL KEWAJIBAN & MODAL</strong></td>
                    <td class="amount"><strong>Rp <?= number_format($total_pasiva, 0, ',', '.') ?></strong></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Informasi Balance -->
    <div class="balance-check">
        <?php if (number_format($total_aktiva, 0, '.', '') != number_format($total_pasiva, 0, '.', '')): ?>
            <span class="balance-error">⚠️ PERINGATAN: NERACA TIDAK SEIMBANG!</span><br>
            <small>Selisih: Rp <?= number_format(abs($total_aktiva - $total_pasiva), 0, ',', '.') ?></small>
        <?php else: ?>
            <span class="balance-success">NERACA SEIMBANG</span>
        <?php endif; ?>
    </div>

    <div class="footer">
        <div class="signature">
            <div><?= date('d F Y') ?></div>
            <div class="signature-line"></div>
            <div>UD. Bawang Tobing</div>
        </div>
    </div>

    <script>
        // Hide unwanted elements before printing
        window.onbeforeprint = function() {
            // Hide all elements that might contain unwanted text
            document.querySelectorAll('.no-print, .print-header, .print-footer, .print-title, .screen-only').forEach(function(el) {
                el.style.display = 'none';
                el.style.visibility = 'hidden';
            });
            
            // Hide any elements containing localhost
            document.querySelectorAll('*').forEach(function(el) {
                if (el.innerText && el.innerText.includes('localhost')) {
                    el.style.display = 'none';
                }
            });

            // Set document title to empty to prevent it from showing
            document.title = '';
        };

        // Clean up after printing
        window.onafterprint = function() {
            // Restore title if needed
            document.title = 'Cetak Laporan Neraca';
        };

        // Additional cleanup on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Remove any localhost references from the DOM
            document.querySelectorAll('*').forEach(function(el) {
                if (el.innerText && el.innerText.includes('localhost')) {
                    el.remove();
                }
            });
        });
    </script>
</body>
</html>
