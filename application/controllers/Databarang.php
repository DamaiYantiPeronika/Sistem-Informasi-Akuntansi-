<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Databarang extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Databarang_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->_rules(); // validasi nama_barang saja

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = ' Data Barang';
            $data['user'] = is_logged_in();
            $data['databarang'] = $this->Databarang_model->get_data('databarang')->result();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('databarang', $data);
            $this->load->view('templates/footer');
        } else {
            $data = array(
                'kode_barang' => $this->_generateKodeBarang(),
                'nama_barang' => $this->input->post('nama_barang'),
            );
            $this->Databarang_model->insert_data($data, 'databarang');

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Data Berhasil Ditambahkan!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('databarang');
        }
    }

    public function edit($id)
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Gagal menyimpan perubahan. Pastikan semua input terisi dengan benar.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('databarang');
        } else {
            $data = array(
                'nama_barang' => $this->input->post('nama_barang'),
            );
            $where = array('id_databarang' => $id);
            $this->Databarang_model->update_data('databarang', $data, $where);

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                Data Berhasil Diubah!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>');
            redirect('databarang');
        }
    }

    public function delete($id)
    {
        $where = array('id_databarang' => $id);
        $this->Databarang_model->delete('databarang', $where);
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Data Berhasil Dihapus!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>');
        redirect('databarang');
    }

    private function _rules()
    {
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required', ['required' => '%s harus diisi!']);
    }

    private function _generateKodeBarang()
    {
        $this->db->select('kode_barang');
        $this->db->order_by('id_databarang', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('databarang');
        $row = $query->row();

        if ($row) {
            $kodeLama = $row->kode_barang;
            $angka = intval(substr($kodeLama, 3)) + 1;
        } else {
            $angka = 1;
        }
        return 'BRG' . str_pad($angka, 4, '0', STR_PAD_LEFT);
    }
}
