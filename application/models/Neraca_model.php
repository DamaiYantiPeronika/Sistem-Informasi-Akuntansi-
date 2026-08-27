<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neraca_model extends CI_Model
{
    public function get_neraca($bulan = null, $tahun = null, $modal_akhir = 0)
    {
        $akun_list = $this->db->order_by('no_akun')->get('akun')->result();

        $neraca = []; 
        $total_aktiva = 0;
        $total_pasiva = 0;

        foreach ($akun_list as $akun) {
            // Skip akun yang akan digabung dalam modal akhir
            if (substr($akun->no_akun, 0, 4) == '3011' || // Modal Awal
                substr($akun->no_akun, 0, 4) == '3012' || // Prive
                $akun->nama_akun == 'Ikhtisar L/R' || 
                $akun->nama_akun == 'Ikhtisar Laba/Rugi') {
                continue;
            }

            // saldo awal
            $saldoawal = $this->db->get_where('saldoawal', ['id_akun' => $akun->id_akun])->row();
            $saldo_awal = 0;
            
            if(substr($akun->no_akun,0,1) == '1') {
                // aktiva normal debit
                $saldo_awal = $saldoawal ? 
                    ($saldoawal->jenis_saldo == 'debit' ? $saldoawal->jumlah : -$saldoawal->jumlah) : 0;
            } else if(substr($akun->no_akun,0,1) == '2' || substr($akun->no_akun,0,1) == '3') {
                // kewajiban & modal normal kredit
                $saldo_awal = $saldoawal ? 
                    ($saldoawal->jenis_saldo == 'kredit' ? $saldoawal->jumlah : -$saldoawal->jumlah) : 0;
            }

            // mutasi
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

            // hitung saldo akhir berdasarkan jenis akun
            if (substr($akun->no_akun, 0, 1) == '1') {
                // akun aktiva normal di debit
                $saldo_akhir = $saldo_awal + ($mutasi_debit - $mutasi_kredit);
                $total_aktiva += $saldo_akhir;
            } else if (substr($akun->no_akun, 0, 1) == '2' || substr($akun->no_akun, 0, 1) == '3') {
                // akun kewajiban & modal normal di kredit
                $saldo_akhir = $saldo_awal + ($mutasi_kredit - $mutasi_debit);
                $total_pasiva += $saldo_akhir;
            } else {
                // default kalau ada akun di luar 1,2,3
                $saldo_akhir = $saldo_awal + ($mutasi_debit - $mutasi_kredit);
            }

            // Hanya tampilkan akun yang memiliki saldo
            if ($saldo_akhir != 0) {
                $neraca[] = (object)[
                    'no_akun' => $akun->no_akun,
                    'nama_akun' => $akun->nama_akun,
                    'saldo_akhir' => $saldo_akhir
                ];
            }
        }
        
        // Tambahkan Modal Akhir sebagai satu kesatuan
        if ($modal_akhir != 0) {
            $neraca[] = (object)[
                'no_akun' => '3013', // Nomor akun untuk Modal Akhir
                'nama_akun' => 'Modal Akhir',
                'saldo_akhir' => $modal_akhir
            ];
            $total_pasiva += $modal_akhir;
        }

        return [
            'data' => $neraca,
            'total_aktiva' => $total_aktiva,
            'total_pasiva' => $total_pasiva
        ];
    }

    public function hitung_laba_rugi($bulan = null, $tahun = null)
    {
        $this->db->select('akun.no_akun, akun.nama_akun, akun.id_akun');
        $this->db->from('akun');
        $this->db->where_in('LEFT(no_akun, 1)', ['4', '5', '6']); // 4: Pendapatan, 5: Beban, 6: HPP
        $akun_list = $this->db->get()->result();

        $total_pendapatan = 0;
        $total_beban = 0;

        foreach ($akun_list as $akun) {
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
            $debit = $mutasi->debit ?? 0;
            $kredit = $mutasi->kredit ?? 0;

            if (substr($akun->no_akun, 0, 1) == '4') {
                // Pendapatan normal di kredit
                $total_pendapatan += ($kredit - $debit);
            } else {
                // Beban dan HPP normal di debit
                $total_beban += ($debit - $kredit);
            }
        }

        return $total_pendapatan - $total_beban;
    }
}