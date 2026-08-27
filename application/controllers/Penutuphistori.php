<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penutuphistori extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Penutuphistori_model');
    }

    public function index()
    {
        $data['title'] = 'Histori Jurnal Penutup';
        $data['user'] = is_logged_in();

        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $periode = ($bulan && $tahun) ? date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun : ($tahun ? "Tahun $tahun" : "Semua Periode");
        $data['total_histori'] = $this->Penutuphistori_model->get_total_histori($bulan, $tahun);
       
        $data['histori'] = $this->Penutuphistori_model->get_histori_penutup($bulan, $tahun);
        $data['periode'] = $periode;
        $data['bulan_selected'] = $bulan;
        $data['tahun_selected'] = $tahun;

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('penutuphistori', $data);
        $this->load->view('templates/footer');
    }
}
