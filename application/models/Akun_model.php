<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Akun_model extends CI_Model
{

    public function get_all()
    {
        return $this->db->get('akun')->result();
    }
 
    public function cek_no_akun_exists($no_akun)
    {
        return $this->db->where('no_akun', $no_akun)->get('akun')->num_rows() > 0;
    }

    public function get_last_no_akun_by_prefix($prefix)
    {
        $this->db->like('no_akun', $prefix, 'after');
        $this->db->order_by('no_akun', 'DESC');
        $this->db->limit(1);
        return $this->db->get('akun')->row();
    }
 

    public function get_data($table)
    {
        return $this->db->get($table);
    }

    public function get_jenis_saldo($no_akun)
    {
        $this->db->select('jumlah, jenis_saldo');
        $this->db->where('id_akun', $no_akun);
        return $this->db->get('saldoawal')->row();
    }

    public function get_by_no_akun($no_akun)
    {
        $this->db->where('no_akun', $no_akun);
        return $this->db->get('akun')->row_array();
    }

    public function get_by_id($id)
    {
        $this->db->where('id_akun', $id);
        return $this->db->get('akun')->row_array();
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
    public function get_all_akun()
    {
        return $this->db->get('akun')->result_array();
    }
}
