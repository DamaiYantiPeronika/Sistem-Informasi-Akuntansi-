<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saldoawal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Saldoawal_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->_rules(); // Aturan validasi form

        if ($this->form_validation->run() == FALSE) {
            // Tampilkan data akun + form
            $data['title'] = ' Saldo Awal';
            $data['user'] = is_logged_in();
            $data['saldoawal'] = $this->Saldoawal_model->get_all(); // Ambil data saldo awal dengan join akun
            $data['akun'] = $this->Saldoawal_model->get_data('akun')->result(); // Ambil data akun untuk dropdown


            // Hitung total debit dan kredit
            $totalDebit = 0;
            $totalKredit = 0;
            foreach ($data['saldoawal'] as $sa) {
                if ($sa->jenis_saldo == 'debit') {
                    $totalDebit += $sa->jumlah;
                } elseif ($sa->jenis_saldo == 'kredit') {
                    $totalKredit += $sa->jumlah;
                }
            }

            // Hitung selisih dan status seimbang
            $selisih = $totalDebit - $totalKredit;
            $seimbang = ($selisih === 0);

            // Simpan ke array data untuk view
            $data['debit'] = $totalDebit;
            $data['kredit'] = $totalKredit;
            $data['selisih'] = $selisih;
            $data['seimbang'] = $seimbang;

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('saldoawal', $data); // view yang menampilkan form + tabel
            $this->load->view('templates/footer');
        } else {
            // Simpan data ke database jika validasi lolos
            $data = array(
                'id_akun'      => $this->input->post('id_akun'),
                'jumlah'       => $this->input->post('jumlah'),
                'jenis_saldo'  => $this->input->post('jenis_saldo'),
            );

            $this->Saldoawal_model->insert_data($data, 'saldoawal');

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            Data Berhasil Ditambahkan!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>');

            redirect('saldoawal'); // Redirect agar form bersih
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
            redirect('saldoawal'); // kembali ke halaman utama jika validasi gagal
        } else {
            $data = array(
                'id_akun'      => $this->input->post('id_akun'),
                'jumlah'       => $this->input->post('jumlah'),
                'jenis_saldo'  => $this->input->post('jenis_saldo'),
            );

            $where = array('id_saldoawal' => $id);
            $this->Saldoawal_model->update_data('saldoawal', $data, $where);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Diubah!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
            <span aria-hidden="true">&times </span>
            </button>
            </div>');
            redirect('saldoawal');
        }
    }


    private function _rules()
    {
        $this->form_validation->set_rules('id_akun', 'Akun', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric');
        $this->form_validation->set_rules('jenis_saldo', 'Jenis Saldo', 'required|in_list[debit,kredit]');
    }

    public function delete($id)
    {
        $where = array('id_saldoawal' => $id);
        $this->Saldoawal_model->delete('saldoawal', $where);
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data Berhasil Dihapus!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
        <span aria-hidden="true">&times </span>
        </button>
        </div>');
        redirect('saldoawal');
    }
}
