<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Labarugi_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_labarugi($bulan = null, $tahun = null)
    {
        // Ambil hanya akun dengan no_akun diawali 4,5,6
        $this->db->like('no_akun', '4', 'after');
        $this->db->or_like('no_akun', '5', 'after');
        $this->db->or_like('no_akun', '6', 'after');
        $akun_list = $this->db->order_by('no_akun')->get('akun')->result();

        $labarugi = [];
 
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

            $labarugi[] = (object)[
                'id_akun' => $akun->id_akun,
                'nama_akun' => $akun->nama_akun,
                'no_akun' => $akun->no_akun,
                'saldo_awal_debit' => $saldo_awal_debit,
                'saldo_awal_kredit' => $saldo_awal_kredit,
                'mutasi_debit' => $mutasi_debit,
                'mutasi_kredit' => $mutasi_kredit
            ];
        }

        return $labarugi;
    }
}
