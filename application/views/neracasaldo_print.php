<!DOCTYPE html>
<html>
<head>
    <title>Cetak Neraca Saldo</title>
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
			text-align: right;
            margin-top: 15px;
        }

        .table th {
			border: 1px solid #333;
			padding: 4px 6px;
			text-align: left;
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
		.table td:first-child {
			text-align: center;
		}
		.table th:nth-child(2) {
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
		.table th:nth-child(4) {
			text-align: center;
		}
		.table td:nth-child(4) {
			text-align: right;
		}

        .total-row {
            background-color: #6dbefdff;
            font-weight: bold;
        }

        .balance-row {
            background-color: #6dbefdff;
            font-weight: bold;
        }

        .balance-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
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
        <div class="report-title">Laporan Neraca Saldo</div>
        <div class="period">Per <?= $periode ?></div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th rowspan="2">No. Akun</th>
                <th rowspan="2">Nama Akun</th>
                <th colspan="2">Saldo Awal</th>
                <th colspan="2">Mutasi</th>
                <th colspan="2">Saldo Akhir</th>
            </tr>
            <tr>
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
                        <td><?= $akun->no_akun ?></td>
                        <td><?= $akun->nama_akun ?></td>
                        <td><?= $akun->saldo_awal_debit > 0 ? number_format($akun->saldo_awal_debit, 0, ',', '.') : '-' ?></td>
                        <td><?= $akun->saldo_awal_kredit > 0 ? number_format($akun->saldo_awal_kredit, 0, ',', '.') : '-' ?></td>
                        <td><?= $akun->mutasi_debit > 0 ? number_format($akun->mutasi_debit, 0, ',', '.') : '-' ?></td>
                        <td><?= $akun->mutasi_kredit > 0 ? number_format($akun->mutasi_kredit, 0, ',', '.') : '-' ?></td>
                        <td><?= $akun->saldo_akhir_debit > 0 ? number_format($akun->saldo_akhir_debit, 0, ',', '.') : '-' ?></td>
                        <td><?= $akun->saldo_akhir_kredit > 0 ? number_format($akun->saldo_akhir_kredit, 0, ',', '.') : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data yang ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" style="text-align: center;"><strong>TOTAL</strong></td>
                <td><strong><?= number_format($total_saldo_awal_debit, 0, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_saldo_awal_kredit, 0, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_mutasi_debit, 0, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_mutasi_kredit, 0, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_saldo_akhir_debit, 0, ',', '.') ?></strong></td>
                <td><strong><?= number_format($total_saldo_akhir_kredit, 0, ',', '.') ?></strong></td>
            </tr>
            <tr class="balance-row">
                <td colspan="2" style="text-align: center;"><strong>SELISIH</strong></td>
                <td><strong><?= number_format($total_saldo_awal_debit - $total_saldo_awal_kredit, 0, ',', '.') ?></strong></td>
                <td><strong>-</strong></td>
                <td><strong><?= number_format($total_mutasi_debit - $total_mutasi_kredit, 0, ',', '.') ?></strong></td>
                <td><strong>-</strong></td>
                <td><strong><?= number_format($total_saldo_akhir_debit - $total_saldo_akhir_kredit, 0, ',', '.') ?></strong></td>
                <td><strong>-</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Informasi Balance -->
    <div class="balance-info">
        <h4>Informasi Balance:</h4>
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 30%;"><strong>Saldo Awal:</strong></td>
                <td style="border: none;">Total Debit = <?= number_format($total_saldo_awal_debit, 0, ',', '.') ?>, Total Kredit = <?= number_format($total_saldo_awal_kredit, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Mutasi:</strong></td>
                <td style="border: none;">Total Debit = <?= number_format($total_mutasi_debit, 0, ',', '.') ?>, Total Kredit = <?= number_format($total_mutasi_kredit, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Saldo Akhir:</strong></td>
                <td style="border: none;">Total Debit = <?= number_format($total_saldo_akhir_debit, 0, ',', '.') ?>, Total Kredit = <?= number_format($total_saldo_akhir_kredit, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Status:</strong></td>
                <td style="border: none;">
                    <span class="<?= $balance_info['is_balanced'] ? 'status-balanced' : 'status-unbalanced' ?>">
                        <?= $balance_info['is_balanced'] ? 'SEIMBANG' : 'TIDAK SEIMBANG' ?>
                    </span>
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
