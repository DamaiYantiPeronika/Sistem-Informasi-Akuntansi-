<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->_rules(); // Aturan validasi form

        if ($this->form_validation->run() == FALSE) {
            // Tampilkan data customer + form
            $data['title'] = ' Customer';
            $data['customer'] = $this->Customer_model->get_data('customer')->result();
            $data['user'] = is_logged_in();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('customer', $data); // view yang menampilkan form + tabel
            $this->load->view('templates/footer');
        } else {
            // Simpan data ke database jika validasi lolos
            $data = array(
                'nama_customer'     => $this->input->post('nama_customer'),
                'alamat_customer'   => $this->input->post('alamat_customer'),
                'nomor_telepon'     => $this->input->post('nomor_telepon'),
            );

            $this->Customer_model->insert_data($data, 'customer');

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Data Berhasil Ditambahkan!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');

            redirect('customer'); // Redirect agar form bersih
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
            redirect('customer'); // kembali ke halaman utama jika validasi gagal
        } else {
            $data = array(
                'nama_customer'     => $this->input->post('nama_customer'),
                'alamat_customer'   => $this->input->post('alamat_customer'),
                'nomor_telepon'     => $this->input->post('nomor_telepon')
            );

            $where = array('id_customer' => $id);
            $this->Customer_model->update_data('customer', $data, $where);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Diubah!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
            <span aria-hidden="true">&times </span>
            </button>
            </div>');
            redirect('customer');
        }
    }


    private function _rules()
    {
        $this->form_validation->set_rules('nama_customer', 'Nama Customer', 'required', ['required' => '%s harus diisi!']);
        $this->form_validation->set_rules('alamat_customer', 'Alamat Customer', 'required', ['required' => '%s harus diisi!']);
        $this->form_validation->set_rules('nomor_telepon', 'No. Telepon', 'required', ['required' => '%s harus diisi!']);
    }

    public function delete($id)
    {
        $where = array('id_customer' => $id);
        $this->Customer_model->delete('customer', $where);
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data Berhasil Dihapus!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
        <span aria-hidden="true">&times </span>
        </button>
        </div>');
        redirect('customer');
    }
}
