<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perubahanmodal_model extends CI_Model
{
    public function get_modal_awal($bulan = null, $tahun = null)
    {
        // Ambil saldo awal akun modal (3011)
        $this->db->select('saldoawal.*, akun.no_akun');
        $this->db->from('saldoawal');
        $this->db->join('akun', 'akun.id_akun = saldoawal.id_akun');
        $this->db->like('akun.no_akun', '3011', 'after');
        $saldo_awal_db = $this->db->get()->row();

        $modal_awal = 0;
        if ($saldo_awal_db) {
            $modal_awal = $saldo_awal_db->jenis_saldo == 'kredit' ? $saldo_awal_db->jumlah : -$saldo_awal_db->jumlah;
        }

        // Ambil penambahan modal dari transaksi (akun 3011, jenis saldo kredit)
        $this->db->select('SUM(transaksi.jumlah) as total_penambahan_modal');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
        $this->db->like('akun.no_akun', '3011', 'after');
        $this->db->where('transaksi.jenis_saldo', 'kredit'); // Penambahan modal biasanya di sisi kredit

        // Filter periode untuk transaksi penambahan modal
        if ($bulan && $tahun) {
            $this->db->where('MONTH(transaksi.tanggal)', $bulan);
            $this->db->where('YEAR(transaksi.tanggal)', $tahun);
        } elseif ($tahun) {
            $this->db->where('YEAR(transaksi.tanggal)', $tahun);
        }
        
        $res_penambahan_modal = $this->db->get()->row();
        $penambahan_modal = $res_penambahan_modal ? (float)$res_penambahan_modal->total_penambahan_modal : 0;

        return $modal_awal + $penambahan_modal;
    }

    public function get_total_prive($bulan = null, $tahun = null)
    {
        // 1. Ambil total prive dari transaksi untuk periode yang difilter
        $this->db->select('SUM(transaksi.jumlah) as total');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
        $this->db->like('akun.no_akun', '3012', 'after');
        
        // Filter periode
        if ($bulan && $tahun) {
            $this->db->where('MONTH(transaksi.tanggal)', $bulan);
            $this->db->where('YEAR(transaksi.tanggal)', $tahun);
        } elseif ($tahun) {
            $this->db->where('YEAR(transaksi.tanggal)', $tahun);
        }
        
        $this->db->where('transaksi.jenis_saldo', 'debit'); // Prive normalnya saldo debit
        $res_transaksi = $this->db->get()->row();
        $total_prive_dari_transaksi = $res_transaksi ? (float)$res_transaksi->total : 0;

        // 2. Jika tidak ada transaksi prive dalam periode yang dipilih,
        // ambil saldo awal dari akun prive (3012)
        if ($total_prive_dari_transaksi == 0) {
            $this->db->select('saldoawal.*, akun.no_akun');
            $this->db->from('saldoawal');
            $this->db->join('akun', 'akun.id_akun = saldoawal.id_akun');
            $this->db->like('akun.no_akun', '3012', 'after');
            $saldo_awal_prive_db = $this->db->get()->row();
            
            if ($saldo_awal_prive_db) {
                // Pastikan saldo awal prive dihitung sebagai nilai positif karena prive adalah pengurang modal
                $total_prive_dari_transaksi = $saldo_awal_prive_db->jenis_saldo == 'debit' ? $saldo_awal_prive_db->jumlah : -$saldo_awal_prive_db->jumlah;
            }
        }

        return $total_prive_dari_transaksi;
    }

    public function get_total_laba_rugi($bulan = null, $tahun = null)
    {
        // Hitung laba bersih dari akun 4-5-6
        $this->db->select('akun.*');
        $this->db->from('akun');
        $this->db->where('(LEFT(no_akun, 1) = "4" OR LEFT(no_akun, 1) = "5" OR LEFT(no_akun, 1) = "6")');
        $this->db->order_by('no_akun');
        $akun_list = $this->db->get()->result();

        $total_pendapatan = 0;
        $total_beban = 0;

        foreach ($akun_list as $akun) {
            // Ambil saldo awal
            $this->db->select('*');
            $this->db->from('saldoawal');
            $this->db->where('id_akun', $akun->id_akun);
            $saldoawal = $this->db->get()->row();
            
            $saldo_awal = 0;
            if ($saldoawal) {
                // The sign here depends on the normal balance of the account.
                // For revenue (4xx), normal is credit, so initial credit is positive.
                // For expenses (5xx, 6xx), normal is debit, so initial debit is positive.
                if (substr($akun->no_akun, 0, 1) == '4') { // Revenue
                    $saldo_awal = $saldoawal->jenis_saldo == 'kredit' ? $saldoawal->jumlah : -$saldoawal->jumlah;
                } elseif (substr($akun->no_akun, 0, 1) == '5' || substr($akun->no_akun, 0, 1) == '6') { // Expense/COGS
                    $saldo_awal = $saldoawal->jenis_saldo == 'debit' ? $saldoawal->jumlah : -$saldoawal->jumlah;
                }
            }

            // Ambil mutasi transaksi
            $this->db->select('
                SUM(CASE WHEN jenis_saldo = "debit" THEN jumlah ELSE 0 END) as debit,
                SUM(CASE WHEN jenis_saldo = "kredit" THEN jumlah ELSE 0 END) as kredit
            ');
            $this->db->from('transaksi');
            $this->db->where('id_akun', $akun->id_akun);
            
            // Filter periode
            if ($bulan && $tahun) {
                $this->db->where('MONTH(tanggal)', $bulan);
                $this->db->where('YEAR(tanggal)', $tahun);
            } elseif ($tahun) {
                $this->db->where('YEAR(tanggal)', $tahun);
            }
            
            $mutasi = $this->db->get()->row();
            $mutasi_debit = $mutasi ? (float)$mutasi->debit : 0;
            $mutasi_kredit = $mutasi ? (float)$mutasi->kredit : 0;

            // Hitung saldo berdasarkan jenis akun
            if (substr($akun->no_akun, 0, 1) == '4') {
                // Pendapatan (normal kredit)
                $saldo = $saldo_awal + $mutasi_kredit - $mutasi_debit;
                $total_pendapatan += $saldo;
            } elseif (substr($akun->no_akun, 0, 1) == '5' || substr($akun->no_akun, 0, 1) == '6') {
                // Beban/HPP (normal debit)
                $saldo = $saldo_awal + $mutasi_debit - $mutasi_kredit;
                $total_beban += $saldo;
            }
        }

        return $total_pendapatan - $total_beban;
    }

    // Method debugging untuk melihat data prive
    public function debug_prive($bulan = null, $tahun = null)
    {
        $debug_info = [];
        
        // 1. Cek akun prive ada atau tidak
        $this->db->select('*');
        $this->db->from('akun');
        $this->db->like('no_akun', '3012', 'after');
        $akun_prive = $this->db->get()->result();
        $debug_info['akun_prive'] = $akun_prive;
        
        if (!empty($akun_prive)) {
            foreach ($akun_prive as $akun) {
                // 2. Cek saldo awal
                $this->db->select('*');
                $this->db->from('saldoawal');
                $this->db->where('id_akun', $akun->id_akun);
                $saldo_awal = $this->db->get()->row();
                $debug_info['saldo_awal_' . $akun->no_akun] = $saldo_awal;
                
                // 3. Cek transaksi
                $this->db->select('*');
                $this->db->from('transaksi');
                $this->db->where('id_akun', $akun->id_akun);
                
                if ($bulan && $tahun) {
                    $this->db->where('MONTH(tanggal)', $bulan);
                    $this->db->where('YEAR(tanggal)', $tahun);
                } elseif ($tahun) {
                    $this->db->where('YEAR(tanggal)', $tahun);
                }
                
                $transaksi = $this->db->get()->result();
                $debug_info['transaksi_' . $akun->no_akun] = $transaksi;
            }
        }
        
        // 4. Hasil perhitungan
        $debug_info['total_prive'] = $this->get_total_prive($bulan, $tahun);
        
        return $debug_info;
    }

    // Method alternatif untuk prive jika struktur berbeda
    public function get_total_prive_alternative($bulan = null, $tahun = null)
    {
        // Alternatif 1: Cari semua akun yang mengandung "prive" di nama
        $this->db->select('akun.*');
        $this->db->from('akun');
        $this->db->where('(LOWER(nama_akun) LIKE "%prive%" OR no_akun LIKE "%3012%")');
        $akun_prive = $this->db->get()->result();
        
        $total_prive = 0;
        
        foreach ($akun_prive as $akun) {
            // Ambil saldo awal
            $this->db->select('*');
            $this->db->from('saldoawal');
            $this->db->where('id_akun', $akun->id_akun);
            $saldoawal = $this->db->get()->row();
            
            $saldo_awal = 0;
            if ($saldoawal) {
                $saldo_awal = $saldoawal->jenis_saldo == 'debit' ? $saldoawal->jumlah : -$saldoawal->jumlah;
            }
            
            // Ambil mutasi
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
            
            // Prive normal debit
            $saldo_prive = $saldo_awal + $mutasi_debit - $mutasi_kredit;
            $total_prive += $saldo_prive;
        }
        
        return abs($total_prive); 
    }
}