<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_model extends CI_Model
{

    public function getallstok()
    {
        $query = $this->db->query("
            SELECT
                sb.id_databarang,
                db.kode_barang,
                db.nama_barang,
                sb.harga_rata2,
                sb.harga_jual,
                sb.sisa,
                COALESCE(m.total_masuk, 0) - COALESCE(k.total_keluar, 0) AS stok
            FROM stokbarang sb
            LEFT JOIN databarang db ON sb.id_databarang = db.id_databarang
            LEFT JOIN (
                SELECT id_databarang, SUM(jumlah) AS total_masuk
                FROM barangmasuk
                GROUP BY id_databarang
            ) m ON sb.id_databarang = m.id_databarang
            LEFT JOIN (
                SELECT id_databarang, SUM(jumlah) AS total_keluar
                FROM barangkeluar
                GROUP BY id_databarang
            ) k ON sb.id_databarang = k.id_databarang
            WHERE db.id_databarang IS NOT NULL
            ORDER BY db.kode_barang ASC
        ");
        return $query->result();
    }

    public function updateStokMasuk($id_databarang, $jumlah_baru, $harga_beli_baru)
    {
        // Hitung harga rata-rata
        $this->db->select('jumlah, harga_beli');
        $this->db->where('id_databarang', $id_databarang);
        $barangmasuk = $this->db->get('barangmasuk')->result();

        $total_jumlah = 0;
        $total_nilai = 0;

        foreach ($barangmasuk as $bm) {
            $total_jumlah += $bm->jumlah;
            $total_nilai += $bm->jumlah * $bm->harga_beli;
        }

        $harga_rata2 = ($total_jumlah > 0) ? ($total_nilai / $total_jumlah) : 0;

        $harga_jual = round($harga_rata2 + ($harga_rata2 * 0.10));

        // Cek stok
        $stok = $this->db->get_where('stokbarang', ['id_databarang' => $id_databarang])->row();
        if ($stok) {
            $this->db->where('id_databarang', $id_databarang);
            $this->db->update('stokbarang', ['harga_rata2' => $harga_rata2]);
        } else {
            $this->db->insert('stokbarang', [
                'id_databarang' => $id_databarang,
                'stok' => 0,
                'harga_rata2' => $harga_rata2,
                'sisa' => 350
            ]);
        }
    }

    public function updateStokKeluar($id_databarang, $jumlah)
    {
        $stok = $this->db->get_where('stokbarang', ['id_databarang' => $id_databarang])->row();
        if ($stok) {
            $sisa = min(350, $stok->stok - $jumlah);
            $this->db->where('id_databarang', $id_databarang);
            $this->db->update('stokbarang', ['stok' => $sisa]);
        }
    }

    public function get_id_databarang($id_databarang)
    {
        $this->db->select('sb.*, db.kode_barang, db.nama_barang');
        $this->db->from('stokbarang sb');
        $this->db->join('databarang db', 'sb.id_databarang = db.id_databarang', 'left');
        $this->db->where('sb.id_databarang', $id_databarang);
        return $this->db->get()->row();
    }


    public function getNamaBarang($kode_barang)
    {
        $barang = $this->db->get_where('databarang', ['kode_barang' => $kode_barang])->row();
        return $barang ? $barang->nama_barang : '';
    }

    public function getBarangStokMinim()
    {
        return $this->db
            ->where('stok <= sisa')
            ->get('stok_barang')
            ->result();
    }

    public function countBarangStokMinim()
    {
        return $this->db
            ->where('stok <= sisa')
            ->from('stok_barang')
            ->count_all_results();
    }

    public function updatesisa($kode_barang)
    {
        // Ambil lead time dari stokbarang
        $stok = $this->db->get_where('stokbarang', ['kode_barang' => $kode_barang])->row();
        if (!$stok) return;
        $lead_time = $stok->lead_time;

        // Hitung pemakaian harian (ambil rata-rata dari barang keluar 30 hari terakhir)
        $this->db->select_sum('jumlah');
        $this->db->where('kode_barang', $kode_barang);
        $this->db->where('tanggal_keluar >=', date('Y-m-d', strtotime('-30 days')));
        $barangkeluar = $this->db->get('barangkeluar')->row();

        $total_keluar = $barangkeluar->jumlah ?? 0;
        $pemakaian_harian = $total_keluar / 3;

        // Hitung sisa dan batasi minimal 350
        $sisa = ceil($lead_time * $pemakaian_harian);
        if ($sisa < 350) {
            $sisa = 350;
        }

        // Update ke tabel stokbarang
        $this->db->where('kode_barang', $kode_barang);
        $this->db->update('stokbarang', ['sisa' => $sisa]);
    }

    public function get_average_harga_rata()
    {
        $this->db->select('id_databarang, 
        CASE 
            WHEN SUM(jumlah) = 0 THEN 0 
            ELSE SUM(jumlah * harga_beli) / SUM(jumlah) 
        END AS harga_rata2');
        $this->db->from('barangmasuk');
        $this->db->group_by('id_databarang');
        return $this->db->get()->result();
    }
}
