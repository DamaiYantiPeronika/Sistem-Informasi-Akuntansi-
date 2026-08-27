<?php
defined('BASEPATH') or exit('No direct script access allowed');
 
class Labarugi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Labarugi_model');
        $this->load->model('Transaksi_model');
        $this->load->model('Saldoawal_model');
        $this->load->model('Akun_model');
        $this->load->library('form_validation'); 
    } 

    public function index()
    {
        $data['title'] = 'Laporan Laba Rugi';
        $data['user'] = is_logged_in();

        // Ambil filter bulan & tahun
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        // Buat label periode
        if ($bulan && $tahun) {
            $periode = date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun;
        } elseif ($tahun) {
            $periode = 'Tahun ' . $tahun;
        } else {
            $periode = 'Semua Periode';
        }

        // Ambil data laba rugi dari neraca_saldo
        $data['laba_rugi'] = $this->Labarugi_model->get_labarugi($bulan, $tahun);
        $data['periode'] = $periode;
        $data['bulan_selected'] = $bulan;
        $data['tahun_selected'] = $tahun;

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('labarugi', $data);
        $this->load->view('templates/footer');
    }

	public function print_labarugi()
{
    $bulan = $this->input->get('bulan');
    $tahun = $this->input->get('tahun');

    $data['title'] = 'Laporan Laba Rugi';

    // Set periode untuk ditampilkan
    if ($bulan && $tahun) { 
        $data['periode'] = date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun;
    } elseif ($bulan) {
        $nama_bulan = date('F', mktime(0, 0, 0, $bulan, 1));
        $data['periode'] = $nama_bulan;
    } elseif ($tahun) {
        $data['periode'] = 'Tahun ' . $tahun;
    } else {
        $data['periode'] = 'Semua Periode';
    }

    // Ambil data laba rugi dengan struktur yang sama
    $data['laba_rugi'] = $this->Labarugi_model->get_labarugi($bulan, $tahun);

    // Load view untuk print
    $this->load->view('labarugi_print', $data);
}

// Method untuk backward compatibility
public function labarugi()
{
    $this->print_labarugi();
}
}
