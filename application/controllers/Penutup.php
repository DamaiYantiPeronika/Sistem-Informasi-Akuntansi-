<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penutup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Penutup_model');
        $this->load->model('Penutuphistori_model');
        $this->load->model('Akun_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = ' Jurnal Penutup';
        $data['user'] = is_logged_in();

        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        // Periode untuk ditampilkan di view
        $data['periode'] = ($bulan && $tahun) ? date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun : ($tahun ? 'Tahun ' . $tahun : 'Semua Periode');
        $data['penutup'] = $this->Penutup_model->get_jurnal_penutup($bulan, $tahun);
        $data['akun'] = $this->Akun_model->get_all_akun();


        // Ambil total debit dan kredit untuk periode yang dipilih
        $data['total_histori'] = $this->Penutuphistori_model->get_total_histori($bulan, $tahun);

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('penutup', $data);
        $this->load->view('templates/footer');
    }

    public function proses()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $penutup = $this->Penutup_model->get_jurnal_penutup($bulan, $tahun);

        // Buat no bukti jurnal penutup
        $no_trsk = $this->generate_no_trsk('JP', $bulan, $tahun);

        // Simpan ke tabel transaksi
        foreach ($penutup as $row) {
            $id_akun = $this->get_id_akun_by_no($row->no_akun);
            $this->db->insert('transaksi', [
                'no_trsk' => $no_trsk,
				'tanggal' => date('Y-m-d'), // hanya tanggal
                'keterangan' => 'Jurnal Penutup periode ' . ($bulan && $tahun ? date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun : ($tahun ? "Tahun $tahun" : "Semua Periode")),
                'id_akun' => $id_akun,
                'jumlah' => $row->debit > 0 ? $row->debit : $row->kredit,
                'jenis_saldo' => $row->debit > 0 ? 'debit' : 'kredit'
            ]);
        }

        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Jurnal penutup berhasil disimpan.</div>');
        redirect('penutup?bulan=' . $bulan . '&tahun=' . $tahun);
    }
    // generate nomor bukti
    private function generate_no_trsk($prefix, $bulan, $tahun)
    {
        $this->db->select_max('id');
        $last = $this->db->get('transaksi')->row();
        $urutan = str_pad(($last ? $last->id + 1 : 1), 3, '0', STR_PAD_LEFT);
        return "{$prefix}-{$tahun}{$bulan}{$urutan}";
    }

    // ambil id akun dari no akun
    private function get_id_akun_by_no($no_akun)
    {
        $akun = $this->db->get_where('akun', ['no_akun' => $no_akun])->row();
        return $akun ? $akun->id_akun : null;
    }
}
