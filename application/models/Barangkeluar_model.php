<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar_model extends CI_Model
{
    public function get_by_id($id) 
{
    $this->db->where('id', $id);
    return $this->db->get('barangkeluar')->row();
}
public function get_data($table)
    {
        return $this->db->get($table);
    } 

    public function get_barangkeluar()
    {
        $this->db->select('barangkeluar.*, customer.nama_customer, stokbarang.harga_rata2, databarang.kode_barang');
        $this->db->from('barangkeluar');
        $this->db->join('databarang', 'databarang.id_databarang = barangkeluar.id_databarang');
        $this->db->join('customer', 'customer.id_customer = barangkeluar.id_customer');
        $this->db->join('stokbarang', 'stokbarang.id_databarang = barangkeluar.id_databarang');
		$this->db->order_by('tanggal_keluar', 'DESC');
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

    public function get_by_nama_customer($nama_customer)
    {
        return $this->db->get_where('barangkeluar', ['nama_customer' => $nama_customer])->row();
    }

    public function get_barangkeluar_filtered($tanggal_awal = null, $tanggal_akhir = null, $id_databarang = null, $id_customer = null, $payment = null)
    {
        $this->db->select('barangkeluar.*, customer.nama_customer, databarang.kode_barang');
        $this->db->from('barangkeluar');
        $this->db->join('customer', 'customer.id_customer = barangkeluar.id_customer');
        $this->db->join('databarang', 'databarang.id_databarang = barangkeluar.id_databarang');
		$this->db->order_by('tanggal_keluar', 'DESC');

        if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
            $this->db->where('barangkeluar.tanggal_keluar >=', $tanggal_awal);
            $this->db->where('barangkeluar.tanggal_keluar <=', $tanggal_akhir);
        }
        if (!empty($id_databarang)) {
            $this->db->where('barangkeluar.id_databarang', $id_databarang);
        }
        if (!empty($id_customer)) {
            $this->db->where('barangkeluar.id_customer', $id_customer);
        }
        if (!empty($payment)) {
            $this->db->where('barangkeluar.payment', $payment);
        }
		if (!empty($status)) {
            $this->db->where('barangkeluar.status', $status);
        }
        return $this->db->get()->result();
    }

	//piutang
	public function get_unpaid_credit_by_customer()
{
    $this->db->select('bk.id, c.nama_customer AS nama, bk.tanggal_keluar AS tanggal, bk.total AS total');
    $this->db->from('barangkeluar bk');
    $this->db->join('customer c', 'c.id_customer = bk.id_customer');
    $this->db->where('bk.payment', 'credit');
    $this->db->set('bk.status', 'Belum Lunas');
	$this->db->order_by('tanggal_keluar', 'DESC');
    return $this->db->get()->result();
}

public function update_status_lunas_barangkeluar($id) {
    $this->db->where('id', $id);
    return $this->db->update('barangkeluar', ['status' => 'Lunas']);
}

	// DASHBOARD
    public function get_total_penjualan()
    {
        $this->db->select('SUM(jumlah * harga_jual) AS total');
        $query = $this->db->get('barangkeluar');
        return $query->row()->total ?? 0;
    }
 
    public function getMonthlyPenjualan()
{
    $this->db->select('MONTH(tanggal_keluar) as bulan, SUM(total) as total_penjualan');
    $this->db->group_by('MONTH(tanggal_keluar)');
    $this->db->order_by('MONTH(tanggal_keluar)');
    return $this->db->get('barangkeluar')->result();
}
public function get_credit_belum_lunas()
{
    return $this->db->where('payment', 'credit')
                    ->where('status', 'Belum Lunas')
                    ->get('barangkeluar')
                    ->result();
}

}
