<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Supplier_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->_rules(); // Aturan validasi form

        if ($this->form_validation->run() == FALSE) {
            // Tampilkan data supplier + form
            $data['title'] = ' Supplier';
            $data['supplier'] = $this->Supplier_model->get_data('supplier')->result();
            $data['user'] = is_logged_in();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('supplier', $data); // view yang menampilkan form + tabel
            $this->load->view('templates/footer');
        } else {
            // Simpan data ke database jika validasi lolos
            $data = array(
                'nama_supplier'     => $this->input->post('nama_supplier'),
                'alamat_supplier'   => $this->input->post('alamat_supplier'),
                'nomor_telepon'     => $this->input->post('nomor_telepon'),
            );

            $this->Supplier_model->insert_data($data, 'supplier');

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Data Berhasil Ditambahkan!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');

            redirect('supplier'); // Redirect agar form bersih
        }
    }

    public function edit($id)
    {
        $this->_rules(); // validasi form
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Gagal menyimpan perubahan. Pastikan semua input terisi dengan benar.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('supplier'); // kembali ke halaman utama jika validasi gagal
        } else {
            $data = array(
                'nama_supplier'     => $this->input->post('nama_supplier'),
                'alamat_supplier'   => $this->input->post('alamat_supplier'),
                'nomor_telepon'     => $this->input->post('nomor_telepon')
            );

            $where = array('id_supplier' => $id);
            $this->Supplier_model->update_data('supplier', $data, $where);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Diubah!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
            <span aria-hidden="true">&times </span>
            </button>
            </div>');
            redirect('supplier');
        }
    }


    private function _rules()
    {
        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required', ['required' => '%s harus diisi!']);
        $this->form_validation->set_rules('alamat_supplier', 'Alamat Supplier', 'required', ['required' => '%s harus diisi!']);
        $this->form_validation->set_rules('nomor_telepon', 'No. Telepon', 'required', ['required' => '%s harus diisi!']);
    }

    public function delete($id)
    {
        $where = array('id_supplier' => $id);
        $this->Supplier_model->delete('supplier', $where);
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data Berhasil Dihapus!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
        <span aria-hidden="true">&times </span>
        </button>
        </div>');
        redirect('supplier');
    }
}
