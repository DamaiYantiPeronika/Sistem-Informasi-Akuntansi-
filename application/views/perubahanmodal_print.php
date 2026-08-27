<!DOCTYPE html>
<html>

<head>
    <title>Cetak Perubahan Modal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: right;
        }

        th {
            background-color: #50b1fbff;
            text-align: center;
        }

        .highlight {
            font-weight: bold;
            background-color: #f8f9fa;
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
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">UD. Bawang Tobing</div>
        <div class="report-title">Laporan Perubahan Modal</div>
        <div class="period">Per <?= $periode ?></div>
    </div>

    <table>
        <tr>
            <th>Modal Awal</th>
            <td><?= number_format($modal_awal, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <th>Laba Bersih (Ikhtisar Laba/Rugi)</th>
            <td><?= number_format($laba_bersih, 0, ',', '.'); ?></td>
        </tr>
        <tr>
            <th>Prive</th>
            <td><?= number_format($prive, 0, ',', '.'); ?></td>
        </tr>
        <tr class="highlight">
            <th>Modal Akhir</th>
            <td><?= number_format($modal_akhir, 0, ',', '.'); ?></td>
        </tr>
    </table>

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
