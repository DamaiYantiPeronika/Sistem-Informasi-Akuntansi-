<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model extends CI_Model
{ 
    public function get_all()
    {
        $this->db->select('transaksi.*, akun.nama_akun, akun.no_akun');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
        $this->db->order_by('transaksi.tanggal', 'DESC');
        return $this->db->get()->result();
    } 

    public function insert($data)
    {
        return $this->db->insert('transaksi',$data);
    }

    public function get_by_id($id)
    {
        $this->db->select('transaksi.*, akun.nama_akun, akun.no_akun');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
        $this->db->where('transaksi.id', $id);
		$this->db->order_by('transaksi.tanggal', 'ASC');
        return $this->db->get()->row();
    }

    public function get_by_no_trsk($no_trsk)
    {
        $this->db->select('transaksi.*, akun.nama_akun, akun.no_akun');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
        $this->db->where('transaksi.no_trsk', $no_trsk);
		$this->db->order_by('transaksi.tanggal', 'ASC');
        return $this->db->get()->result(); // karena satu no_trsk = banyak baris akun
    }

    public function get_by_bulan_tahun($bulan, $tahun)
    {
        $this->db->select('transaksi.*, akun.nama_akun, akun.no_akun');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');

        if ($bulan != '') {
            $this->db->where("DATE_FORMAT(transaksi.tanggal, '%m') =", $bulan);
        }
        if ($tahun != '') {
            $this->db->where("DATE_FORMAT(transaksi.tanggal, '%Y') =", $tahun);
        }

        $this->db->order_by('transaksi.tanggal', 'ASC');
        return $this->db->get()->result();
    }

    // Metode baru untuk mendapatkan semua transaksi (jurnal umum) berdasarkan array tanggal, bulan, dan tahun
    public function get_filtered_jurnal_umum($bulan = null, $tahun = null)
    {
        $this->db->select('t.id, t.tanggal, t.keterangan, t.no_trsk, a.nama_akun, a.no_akun, t.jumlah, t.jenis_saldo');
        $this->db->from('transaksi t');
        $this->db->join('akun a', 'a.id_akun = t.id_akun');

        // Tambahkan filter bulan dan tahun untuk konsistensi
        if ($bulan) {
            $this->db->where("DATE_FORMAT(t.tanggal, '%m') =", $bulan);
        }
        if ($tahun) {
            $this->db->where("DATE_FORMAT(t.tanggal, '%Y') =", $tahun);
        }

        $this->db->order_by('t.tanggal', 'ASC');
        $this->db->order_by('t.no_trsk', 'ASC');
        $this->db->order_by('t.id', 'ASC'); // Penting untuk pengurutan dalam grup
        $query = $this->db->get();
        return $query->result();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('transaksi', $data);
    }


    public function delete($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    //fungsi untuk mendapatkan data jurnal umum (tanpa filter bulan/tahun)
    public function get_jurnalumum()
    {
        $this->db->select('t.id, t.tanggal, t.keterangan, t.no_trsk, a.nama_akun, a.no_akun, t.jumlah, t.jenis_saldo');
        $this->db->from('transaksi t');
        $this->db->join('akun a', 'a.id_akun = t.id_akun');
        $this->db->order_by('t.tanggal', 'ASC');
        return $this->db->get()->result();
    }

    //fungsi untuk pemasukan kas
    public function get_filtered_kas_debit($bulan = null, $tahun = null)
    {
        $this->db->select('t.*, a.nama_akun, a.no_akun'); // Pilih kolom dari transaksi dan akun
        $this->db->from('transaksi t');
        $this->db->join('akun a', 'a.id_akun = t.id_akun'); // Join dengan tabel akun
        $this->db->where('a.no_akun', '1011'); // Filter berdasarkan no_akun dari tabel akun
        $this->db->where('t.jenis_saldo', 'debit'); // Pemasukan Kas

        if ($bulan) {
            $this->db->where("DATE_FORMAT(t.tanggal, '%m') =", $bulan);
        }
        if ($tahun) {
            $this->db->where("DATE_FORMAT(t.tanggal, '%Y') =", $tahun);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_transaksi_by_tanggal_kas()
    {
        // Ambil semua tanggal yang ada transaksi kas (1011)
        $this->db->select('tanggal');
        $this->db->from('transaksi t');
        $this->db->join('akun a', 'a.id_akun = t.id_akun');
        $this->db->where('a.no_akun', '1011');
        $this->db->get_compiled_select();

        // Ambil semua transaksi pada tanggal-tanggal tersebut
        $this->db->select('transaksi.*, akun.nama_akun, akun.no_akun');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
		$this->db->order_by('transaksi.tanggal', 'ASC');
        return $this->db->get()->result();
    }

    //fungsi untuk pengeluaran kas
    public function get_filtered_kas_kredit($bulan = null, $tahun = null)
    {
        $this->db->select('t.*, a.nama_akun, a.no_akun'); // Pilih kolom dari transaksi dan akun
        $this->db->from('transaksi t');
        $this->db->join('akun a', 'a.id_akun = t.id_akun'); // Join dengan tabel akun
        $this->db->where('a.no_akun', '1011'); // Filter berdasarkan no_akun dari tabel akun
        $this->db->where('t.jenis_saldo', 'kredit'); // Pengeluaran Kas

        if ($bulan) {
            $this->db->where("DATE_FORMAT(t.tanggal, '%m') =", $bulan);
        }
        if ($tahun) {
            $this->db->where("DATE_FORMAT(t.tanggal, '%Y') =", $tahun);
        }

        $query = $this->db->get();
        return $query->result();
    }

    //penyesuaian
    public function get_all_filtered($bulan = null, $tahun = null)
    {
        $this->db->select('transaksi.*, akun.nama_akun, akun.no_akun');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
		$this->db->order_by('transaksi.tanggal', 'ASC');

        if ($bulan) {
            $this->db->where('MONTH(transaksi.tanggal)', $bulan);
        }
        if ($tahun) {
            $this->db->where('YEAR(transaksi.tanggal)', $tahun);
        }

        return $this->db->get()->result();
    }

	// UNTUK DASHBOARD
    public function getTotal()
    {
        $this->db->select('tanggal');
    $this->db->from('transaksi');
    $this->db->group_by('tanggal');

    $query = $this->db->get();
    return $query->num_rows();
    }

    public function getLatest($limit = 5)
    {
        $this->db->select('transaksi.*, akun.nama_akun, akun.no_akun');
        $this->db->from('transaksi');
        $this->db->join('akun', 'akun.id_akun = transaksi.id_akun');
        $this->db->order_by('transaksi.tanggal', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function getMonthlyTransactionSummary()
    {
        $this->db->select("DATE_FORMAT(tanggal, '%b') as bulan, COUNT(*) as jumlah");
        $this->db->group_by("MONTH(tanggal)");
        $this->db->order_by("MONTH(tanggal)", "ASC");
        return $this->db->get('transaksi')->result_array();
    }

}
