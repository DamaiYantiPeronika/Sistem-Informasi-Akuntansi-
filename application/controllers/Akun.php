<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akun extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Akun_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $this->_rules(); // Aturan validasi form

        if ($this->form_validation->run() == FALSE) {
            // Sama seperti sebelumnya
            $data['title'] = ' Daftar Akun';
            $data['akun'] = $this->Akun_model->get_data('akun')->result();
            $data['user'] = is_logged_in();
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('akun', $data);
            $this->load->view('templates/footer');
        } else {
            // Ambil jenis akun
            $jenis_akun = $this->input->post('jenis_akun');
            $nama_akun = $this->input->post('nama_akun');

            // Dapatkan no akun terakhir berdasarkan jenis akun
            $last_no_akun = $this->Akun_model->get_last_no_akun_by_prefix($jenis_akun);

            if ($last_no_akun) {
                $new_no_akun = strval(intval($last_no_akun->no_akun) + 1);
            } else {
                $new_no_akun = $jenis_akun . '1';
            }

            // Pastikan no akun unik
            if ($this->Akun_model->cek_no_akun_exists($new_no_akun)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">No akun ' . $new_no_akun . ' sudah terpakai!</div>');
                redirect('akun');
            }

            // Simpan
            $data = [
                'no_akun' => $new_no_akun,
                'nama_akun' => $nama_akun,
                'saldo_normal' => $this->_get_saldo_normal($new_no_akun)
            ];

            $this->Akun_model->insert_data($data, 'akun');

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        Data Berhasil Ditambahkan dengan No Akun: ' . $new_no_akun . '
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>');

            redirect('akun');
        }
    }

    private function _get_saldo_normal($no_akun)
    {
        $berawalan = substr($no_akun, 0, 1);

        if ($berawalan == '1' || $berawalan == '6') {
            return 'Debit'; // Akun 1 dan 6 → saldo bertambah di debit
        } elseif (in_array($berawalan, ['2', '3', '4', '5'])) {
            return 'Kredit'; // Akun 2–5 → saldo bertambah di kredit
        } else {
            return 'Debit'; // Default
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
            redirect('akun'); // kembali ke halaman utama jika validasi gagal
        } else {
            $data = array(
                'nama_akun'     => $this->input->post('nama_akun'),
                'no_akun'   => $this->input->post('no_akun'),
                'saldo_normal' => $this->_get_saldo_normal($this->input->post('no_akun'))
            );

            $where = array('id_akun' => $id);
            $this->Akun_model->update_data('akun', $data, $where);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> Data Berhasil Diubah!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
            <span aria-hidden="true">&times </span>
            </button>
            </div>');
            redirect('akun');
        }
    }


    private function _rules()
    {
        $this->form_validation->set_rules('nama_akun', 'Nama Akun', 'required', ['required' => '%s harus diisi!']);
        $this->form_validation->set_rules('jenis_akun', 'Jenis Akun', 'required', ['required' => '%s harus dipilih!']);
    }


    public function delete($id)
    {
        $where = array('id_akun' => $id);
        $this->Akun_model->delete('akun', $where);
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Data Berhasil Dihapus!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
        <span aria-hidden="true">&times </span>
        </button>
        </div>');
        redirect('akun');
    }
}
