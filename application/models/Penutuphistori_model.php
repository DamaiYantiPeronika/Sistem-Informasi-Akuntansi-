<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penutuphistori_model extends CI_Model
{
    public function get_histori_penutup($bulan = null, $tahun = null)
    { 
        $this->db->select('no_trsk, tanggal, keterangan, 
            SUM(debit) as total_debit, 
            SUM(kredit) as total_kredit');
        $this->db->from('transaksi');
        $this->db->like('no_trsk', 'JP-'); // hanya ambil jurnal penutup
        if ($bulan && $tahun) {
            $this->db->where('MONTH(tanggal)', $bulan);
            $this->db->where('YEAR(tanggal)', $tahun);
        } elseif ($tahun) { 
            $this->db->where('YEAR(tanggal)', $tahun);
        }
        $this->db->group_by('no_trsk');
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get()->result();
    }

    public function get_total_histori($bulan = null, $tahun = null)
    {
        $this->db->select('SUM(total_debit) as total_debit, SUM(total_kredit) as total_kredit');
        $this->db->from('penutuphistori');
        if ($bulan) $this->db->where('bulan', $bulan);
        if ($tahun) $this->db->where('tahun', $tahun);
        return $this->db->get()->row();
    }
}
