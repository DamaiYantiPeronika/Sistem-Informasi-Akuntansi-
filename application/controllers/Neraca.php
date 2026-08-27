<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neraca extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Neraca_model');
        $this->load->model('Perubahanmodal_model');
    }

    public function index()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $data['title'] = 'Laporan Neraca';
        $data['user'] = is_logged_in();
        $data['periode'] = $bulan && $tahun ? date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun : ($tahun ? 'Tahun ' . $tahun : 'Semua Periode');
        
        // Hitung modal akhir dari laporan perubahan modal
        $modal_awal = $this->Perubahanmodal_model->get_modal_awal();
        $prive = $this->Perubahanmodal_model->get_total_prive($bulan, $tahun);
        $laba_bersih = $this->Perubahanmodal_model->get_total_laba_rugi($bulan, $tahun);
        $modal_akhir = $modal_awal + $laba_bersih - $prive;

        // Ambil data neraca dari model dengan modal akhir
        $neraca = $this->Neraca_model->get_neraca($bulan, $tahun, $modal_akhir);
        $data['neraca'] = $neraca['data'];
        $data['total_aktiva'] = $neraca['total_aktiva'];
        $data['total_pasiva'] = $neraca['total_pasiva'];
        $data['modal_akhir'] = $modal_akhir;

        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('neraca', $data);
        $this->load->view('templates/footer');
    }

    public function print_neraca()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $data['title'] = 'Laporan Neraca';

        // Periode
        if ($bulan && $tahun) {
            $data['periode'] = date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun;
        } elseif ($tahun) {
            $data['periode'] = 'Tahun ' . $tahun;
        } else {
            $data['periode'] = 'Semua Periode';
        }

        // Hitung modal akhir dari laporan perubahan modal
        $modal_awal = $this->Perubahanmodal_model->get_modal_awal();
        $prive = $this->Perubahanmodal_model->get_total_prive($bulan, $tahun);
        $laba_bersih = $this->Perubahanmodal_model->get_total_laba_rugi($bulan, $tahun);
        $modal_akhir = $modal_awal + $laba_bersih - $prive;

        // Ambil data neraca dari model dengan modal akhir
        $neraca = $this->Neraca_model->get_neraca($bulan, $tahun, $modal_akhir);
        $data['neraca'] = $neraca['data'];
        $data['total_aktiva'] = $neraca['total_aktiva'];
        $data['total_pasiva'] = $neraca['total_pasiva'];
        $data['modal_akhir'] = $modal_akhir;

        $this->load->view('neraca_print', $data);
    }

    public function neraca()
    {
        $this->print_neraca();
    }
}