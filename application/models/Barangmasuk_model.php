<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangmasuk_model extends CI_Model
{

    public function get_by_id($id) 
{
    $this->db->where('id', $id);
    return $this->db->get('barangmasuk')->row();
}

    public function get_data($table)
    { 
        return $this->db->get($table); // return object, bukan ->result langsung
    }

     // Khusus untuk barangmasuk + supplier join
    public function get_barangmasuk()
    {
        $this->db->select(' 
            barangmasuk.*, 
            supplier.nama_supplier, 
            databarang.kode_barang, 
            databarang.nama_barang
        ');
        $this->db->from('barangmasuk');
        $this->db->join('supplier', 'supplier.id_supplier = barangmasuk.id_supplier');
        $this->db->join('databarang', 'databarang.id_databarang = barangmasuk.id_databarang');
		$this->db->order_by('tanggal_masuk', 'DESC');
        return $this->db->get()->result();
    }

    public function insert_data($data, $table)
    {
        $this->db->insert($table, $data);
    }

    public function update_data($table, $data, $where)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
    }

    public function delete($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
    }

    // Tambahan fungsi untuk kode barang jika diperlukan
    public function get_kode_barang($kode_barang)
    {
        return $this->db->get_where('barangmasuk', ['kode_barang' => $kode_barang])->row();
    }

    // Total pembelian
    public function get_total_pembelian()
    {
        $this->db->select_sum('total');
        $query = $this->db->get('barangmasuk');
        return $query->row()->total ?? 0;
    }

    // Persediaan awal
    public function get_persediaan_awal($tanggal_awal)
    {
        $this->db->select_sum('jumlah');
        $this->db->where('tanggal_masuk <', $tanggal_awal);
        $barang_masuk = $this->db->get('barangmasuk')->row()->jumlah ?? 0;

        $this->db->select_sum('jumlah');
        $this->db->where('tanggal_keluar <', $tanggal_awal);
        $barang_keluar = $this->db->get('barangkeluar')->row()->jumlah ?? 0;

        return $barang_masuk - $barang_keluar;
    }

    // Persediaan akhir
    public function get_persediaan_akhir($tanggal_akhir)
    {
        $this->db->select_sum('jumlah');
        $this->db->where('tanggal_masuk <=', $tanggal_akhir);
        $barang_masuk = $this->db->get('barangmasuk')->row()->jumlah ?? 0;

        $this->db->select_sum('jumlah');
        $this->db->where('tanggal_keluar <=', $tanggal_akhir);
        $barang_keluar = $this->db->get('barangkeluar')->row()->jumlah ?? 0;

        return $barang_masuk - $barang_keluar;
    }

    public function get_barangmasuk_filtered($tanggal_awal = null, $tanggal_akhir = null, $id_databarang = null, $id_supplier = null, $payment = null)
    {
        $this->db->select('
            barangmasuk.*,
            supplier.nama_supplier,
            databarang.kode_barang,
            databarang.nama_barang
        ');
        $this->db->from('barangmasuk');
        $this->db->join('supplier', 'supplier.id_supplier = barangmasuk.id_supplier');
        $this->db->join('databarang', 'databarang.id_databarang = barangmasuk.id_databarang'); // JOIN dengan tabel databarang

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $this->db->where('barangmasuk.tanggal_masuk >=', $tanggal_awal);
            $this->db->where('barangmasuk.tanggal_masuk <=', $tanggal_akhir);
        }
        if (!empty($id_databarang)) {
            $this->db->where('barangmasuk.id_databarang', $id_databarang); // Filter berdasarkan id_databarang
        }
        if (!empty($id_supplier)) {
            $this->db->where('barangmasuk.id_supplier', $id_supplier);
        }
        if (!empty($payment)) {
            $this->db->where('barangmasuk.payment', $payment);
        }
		if (!empty($status)) {
			$this->db->where('barangmasuk.status', $status);
		}
		$this->db->order_by('barangmasuk.tanggal_masuk', 'DESC');
        return $this->db->get()->result();
    }

	//hutang
	public function get_unpaid_credit_by_supplier()
{
    $this->db->select('bm.id, s.nama_supplier AS nama, bm.tanggal_masuk AS tanggal');
    $this->db->from('barangmasuk bm');
    $this->db->join('supplier s', 's.id_supplier = bm.id_supplier');
    $this->db->where('bm.payment', 'credit');
    $this->db->set('bm.status', 'Belum Lunas');
    $this->db->order_by('tanggal_masuk','DESC');
    return $this->db->get()->result();
}

public function update_status_lunas_barangmasuk($id) {
    $this->db->where('id', $id);
    return $this->db->update('barangmasuk', ['status' => 'Lunas']);
}


// DASHBOARD
    public function get_total_pembelian1()
    {
        $this->db->select('SUM(jumlah * harga_beli) AS total');
        $query = $this->db->get('barangmasuk');
        return $query->row()->total ?? 0;
    }

    public function getMonthlyPembelian()
    {
        $this->db->select('MONTH(tanggal_masuk) as bulan, SUM(total) as total_pembelian');
        $this->db->group_by('MONTH(tanggal_masuk)');
        $this->db->order_by('MONTH(tanggal_masuk)');
        return $this->db->get('barangmasuk')->result();
    }

	public function get_credit_belum_lunas()
{
    return $this->db->where('payment', 'credit')
                    ->where('status', 'Belum Lunas')
                    ->get('barangmasuk')
                    ->result();
}
}
