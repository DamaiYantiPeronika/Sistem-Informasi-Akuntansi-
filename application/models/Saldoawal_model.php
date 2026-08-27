<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saldoawal_model extends CI_Model
{

    public function get_all()
    {
        $this->db->select('saldoawal.*, akun.nama_akun, akun.no_akun');
        $this->db->from('saldoawal');
        $this->db->join('akun', 'akun.id_akun = saldoawal.id_akun');
        return $this->db->get()->result();
    }

    public function get_akun_by_id($id_akun)
    {
        $this->db->select('posisi_saldo_normal');
        $this->db->from('akun');
        $this->db->where('id_akun', $id_akun);
        return $this->db->get()->row();
    }

    public function get_data($table)
    {
        return $this->db->get($table);
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
        return $this->db->get('saldoawal')->result_array();
    }

    // Fungsi utama untuk mengambil saldo awal berdasarkan no_akun
    // Jenis saldo diambil dari tabel saldoawal, bukan dari akun
    public function get_saldo_awal_by_no_akun($no_akun)
    {
        $this->db->select('saldoawal.jumlah, saldoawal.jenis_saldo');
        $this->db->from('saldoawal');
        $this->db->join('akun', 'akun.id_akun = saldoawal.id_akun');
        $this->db->where('akun.no_akun', $no_akun);
        return $this->db->get()->row();
    }

    // Fungsi alternatif (untuk backward compatibility)
    public function get_saldo_awal_by_noakun($no_akun)
    {
        return $this->get_saldo_awal_by_no_akun($no_akun);
    }

    // Fungsi untuk mendapatkan jenis saldo berdasarkan no_akun
    // Menggunakan join dengan tabel akun
    public function get_jenis_saldo($no_akun)
    {
        $this->db->select('saldoawal.jumlah, saldoawal.jenis_saldo');
        $this->db->from('saldoawal');
        $this->db->join('akun', 'akun.id_akun = saldoawal.id_akun');
        $this->db->where('akun.no_akun', $no_akun);
        return $this->db->get()->row();
    }
}
