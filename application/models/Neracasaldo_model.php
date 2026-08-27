
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neracasaldo_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_neraca_saldo($bulan = null, $tahun = null)
    {
        // Ambil semua akun yang diurutkan berdasarkan no_akun
        $akun_list = $this->db->order_by('no_akun')->get('akun')->result();

        $neraca_saldo = [];

        foreach ($akun_list as $akun) {
            // Ambil saldo awal dari tabel saldoawal
            $saldoawal = $this->db->get_where('saldoawal', ['id_akun' => $akun->id_akun])->row();
            $saldo_awal_debit = $saldoawal ? ($saldoawal->jenis_saldo == 'debit' ? $saldoawal->jumlah : 0) : 0;
            $saldo_awal_kredit = $saldoawal ? ($saldoawal->jenis_saldo == 'kredit' ? $saldoawal->jumlah : 0) : 0;

            // Hitung mutasi dari transaksi sesuai periode
            $this->db->select('
                SUM(CASE WHEN jenis_saldo = "debit" THEN jumlah ELSE 0 END) as debit,
                SUM(CASE WHEN jenis_saldo = "kredit" THEN jumlah ELSE 0 END) as kredit
            ');
            $this->db->from('transaksi');
            $this->db->where('id_akun', $akun->id_akun);

            if ($bulan && $tahun) {
                $this->db->where('MONTH(tanggal)', $bulan);
                $this->db->where('YEAR(tanggal)', $tahun);
            } elseif ($tahun) {
                $this->db->where('YEAR(tanggal)', $tahun);
            }

            $mutasi = $this->db->get()->row();
            $mutasi_debit = $mutasi ? (float)$mutasi->debit : 0;
            $mutasi_kredit = $mutasi ? (float)$mutasi->kredit : 0;

            // Hitung saldo akhir berdasarkan jenis akun
            $saldo_akhir_debit = 0;
            $saldo_akhir_kredit = 0;

            // Tentukan jenis akun berdasarkan kode akun
            $jenis_akun = $this->get_jenis_akun($akun->no_akun);

            if ($jenis_akun == 'debit') {
                // Akun normal debit (Aktiva, Beban)
                $saldo_akhir = ($saldo_awal_debit - $saldo_awal_kredit) + ($mutasi_debit - $mutasi_kredit);
                if ($saldo_akhir >= 0) {
                    $saldo_akhir_debit = $saldo_akhir;
                } else {
                    $saldo_akhir_kredit = abs($saldo_akhir);
                }
            } else {
                // Akun normal kredit (Kewajiban, Modal, Pendapatan)
                $saldo_akhir = ($saldo_awal_kredit - $saldo_awal_debit) + ($mutasi_kredit - $mutasi_debit);
                if ($saldo_akhir >= 0) {
                    $saldo_akhir_kredit = $saldo_akhir;
                } else {
                    $saldo_akhir_debit = abs($saldo_akhir);
                }
            }

            $neraca_saldo[] = (object)[
                'id_akun' => $akun->id_akun,
                'nama_akun' => $akun->nama_akun,
                'no_akun' => $akun->no_akun,
                'jenis_akun' => $jenis_akun,
                'saldo_awal_debit' => $saldo_awal_debit,
                'saldo_awal_kredit' => $saldo_awal_kredit,
                'mutasi_debit' => $mutasi_debit,
                'mutasi_kredit' => $mutasi_kredit,
                'saldo_akhir_debit' => $saldo_akhir_debit,
                'saldo_akhir_kredit' => $saldo_akhir_kredit
            ];
        }

        return $neraca_saldo;
    }

    private function get_jenis_akun($no_akun)
    {
        $kode = substr($no_akun, 0, 1);
        
        switch ($kode) {
            case '1': // Aktiva
                return 'debit';
            case '2': // Kewajiban
                return 'kredit';
            case '3': // Modal
                return 'kredit';
            case '4': // Pendapatan
                return 'kredit';
            case '5': // Beban
                return 'debit';
            case '6': // Beban (alternatif)
                return 'debit';
            default:
                return 'debit';
        }
    }

    public function get_total_neraca_saldo($bulan = null, $tahun = null)
    {
        $neraca_saldo = $this->get_neraca_saldo($bulan, $tahun);
        
        $total = [
            'saldo_awal_debit' => 0,
            'saldo_awal_kredit' => 0,
            'mutasi_debit' => 0,
            'mutasi_kredit' => 0,
            'saldo_akhir_debit' => 0,
            'saldo_akhir_kredit' => 0
        ];

        foreach ($neraca_saldo as $item) {
            $total['saldo_awal_debit'] += $item->saldo_awal_debit;
            $total['saldo_awal_kredit'] += $item->saldo_awal_kredit;
            $total['mutasi_debit'] += $item->mutasi_debit;
            $total['mutasi_kredit'] += $item->mutasi_kredit;
            $total['saldo_akhir_debit'] += $item->saldo_akhir_debit;
            $total['saldo_akhir_kredit'] += $item->saldo_akhir_kredit;
        }

        return $total;
    }

    public function validate_balance($bulan = null, $tahun = null)
    {
        $total = $this->get_total_neraca_saldo($bulan, $tahun);
        
        $saldo_awal_balance = $total['saldo_awal_debit'] - $total['saldo_awal_kredit'];
        $mutasi_balance = $total['mutasi_debit'] - $total['mutasi_kredit'];
        $saldo_akhir_balance = $total['saldo_akhir_debit'] - $total['saldo_akhir_kredit'];

        return [
            'saldo_awal_balance' => $saldo_awal_balance,
            'mutasi_balance' => $mutasi_balance,
            'saldo_akhir_balance' => $saldo_akhir_balance,
            'is_balanced' => (abs($saldo_awal_balance) < 0.01 && abs($mutasi_balance) < 0.01 && abs($saldo_akhir_balance) < 0.01)
        ];
    }
}
