<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stokbarang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Stok_model');
        $this->load->library('form_validation');
    }

    public function index() 
    {
        // Update harga rata-rata otomatis setiap kali halaman stok dibuka
        $data_avg = $this->Stok_model->get_average_harga_rata();
        // Stokbarang.php
        foreach ($data_avg as $row) {
            $harga_rata2 = $row->harga_rata2;
            $harga_jual = round($harga_rata2 + ($harga_rata2 * 0.10)); // Harga jual = HPP + 10%

    $this->db->where('id_databarang', $row->id_databarang);
    $this->db->update('stokbarang', [
        'harga_rata2' => $harga_rata2,
        'harga_jual'  => $harga_jual
    ]);
        }

        // Ambil data stok setelah update
        $data['title'] = ' Stok Barang';
        $data['user'] = is_logged_in();
        $data['stok'] = $this->Stok_model->getAllStok();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('stokbarang', $data);
        $this->load->view('templates/footer');
    }

    public function update_harga_rata()
    {
        $data_avg = $this->Stok_model->get_average_harga_rata();
        foreach ($data_avg as $row) {
            $this->db->where('kode_barang', $row->kode_barang);
            $this->db->update('stokbarang', ['harga_rata2' => $row->harga_rata2]);
        }
        $this->session->set_flashdata('pesan', 'Harga rata-rata berhasil diperbarui.');
        redirect('stokbarang');
    }
    
}
