<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
            color: #333;
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

        .table {
            width: 100%;
            border-collapse: collapse;
			text-align: center;
            margin-top: 15px;
        }

        .table th{
			border: 1px solid #333;
			padding: 4px 6px;
			text-align: center;
			vertical-align: middle;
		}
		
        .table td {
			border: 1px solid #333;
            padding: 4px 6px;
            text-align: right;
            vertical-align: middle;
        }

        .table th {
            background-color: #50b1fbff;
            font-weight: bold;
            text-align: center;
        }

        .table th:first-child {
			text-align: center;
		}
		.table td:first-child{
			text-align: center;
		}
        .table th:nth-child(2){
			text-align: center;
		}
		.table td:nth-child(2) {
			text-align: left;
		}
		.table th:nth-child(3) {
			text-align: center;
		}
		.table td:nth-child(3) {
            text-align: right;
        }
		
        .section-header {
            background-color: #6dbefdff;
            font-weight: bold;
        }

        .subtotal-row {
            background-color: #a2d1f6ff;
            font-weight: bold;
        }

        .profit-row {
            font-weight: bold;
        }

        .profit-positive {
            background-color: #8ffea9ff;
        }

        .profit-negative {
            background-color: #f7a4a4ff;
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
                font-size: 10px;
            }
            
            .no-print {
                display: none;
            }
		}
          
    </style>
</head>
<body>

<div class="header">
        <div class="company-name">UD. Bawang Tobing</div>
        <div class="report-title">Laporan Laba Rugi</div>
        <div class="period">Per <?= $periode ?></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No Akun</th>
                <th>Nama Akun</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_pendapatan = 0;
            $total_beban_hpp = 0;
            ?>

            <!-- PENDAPATAN -->
            <tr class="section-header">
                <td colspan="3" style="text-align: left; font-weight: bold;">PENDAPATAN</td>
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
                            <td><?= $item->no_akun ?></td>
                            <td><?= $item->nama_akun ?></td>
                            <td><?= number_format($saldo_akhir, 0, ',', '.') ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr class="subtotal-row">
                <td colspan="2" style="text-align: right; font-weight: bold;">Total Pendapatan</td>
                <td style="font-weight: bold;"><?= number_format($total_pendapatan, 0, ',', '.') ?></td>
            </tr>

            <!-- HPP & BEBAN -->
            <tr class="section-header">
                <td colspan="3" style="text-align: left; font-weight: bold;">HPP & BEBAN</td>
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
                            <td><?= $item->no_akun ?></td>
                            <td><?= $item->nama_akun ?></td>
                            <td><?= number_format($saldo_akhir, 0, ',', '.') ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr class="subtotal-row">
                <td colspan="2" style="text-align: right; font-weight: bold;">Total HPP & Beban</td>
                <td style="font-weight: bold;"><?= number_format($total_beban_hpp, 0, ',', '.') ?></td>
            </tr>

            <!-- LABA / RUGI -->
            <?php $laba_rugi_bersih = $total_pendapatan - $total_beban_hpp; ?>
            <tr class="profit-row <?= $laba_rugi_bersih >= 0 ? 'profit-positive' : 'profit-negative' ?>">
                <td colspan="2" style="text-align: right; font-weight: bold;">
                    <?= $laba_rugi_bersih >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' ?>
                </td>
                <td style="font-weight: bold;">
                    <?= number_format(abs($laba_rugi_bersih), 0, ',', '.') ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Summary Information -->
    <div style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
        <h4>Ringkasan:</h4>
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 30%;"><strong>Total Pendapatan:</strong></td>
                <td style="border: none;"><?= number_format($total_pendapatan, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Total HPP & Beban:</strong></td>
                <td style="border: none;"><?= number_format($total_beban_hpp, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Hasil:</strong></td>
                <td style="border: none;">
                    <span style="color: <?= $laba_rugi_bersih >= 0 ? '#28a745' : '#dc3545' ?>; font-weight: bold;">
                        <?= $laba_rugi_bersih >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' ?> 
                        <?= number_format(abs($laba_rugi_bersih), 0, ',', '.') ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Margin (%):</strong></td>
                <td style="border: none;">
                    <?php if ($total_pendapatan > 0): ?>
                        <?= number_format(($laba_rugi_bersih / $total_pendapatan) * 100, 2, ',', '.') ?>%
                    <?php else: ?>
                        0.00%
                    <?php endif; ?>
                </td>
            </tr>
        </table>
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
            document.title = 'Cetak Laporan Laba Rugi';
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
