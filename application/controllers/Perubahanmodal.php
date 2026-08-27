<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Perubahanmodal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Perubahanmodal_model');
    }

    public function index()
    {
        $data['title'] = 'Laporan Perubahan Modal';
        $data['user'] = is_logged_in();
        
        // Ambil filter bulan & tahun (sama seperti di Labarugi)
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        
        // Format periode (sama seperti di Labarugi)
        $periode = $this->format_periode($bulan, $tahun);
        $modal_awal = $this->Perubahanmodal_model->get_modal_awal($bulan, $tahun); 
        $prive = $this->Perubahanmodal_model->get_total_prive($bulan, $tahun);
        $laba_bersih = $this->Perubahanmodal_model->get_total_laba_rugi($bulan, $tahun);
        
        // Hitung modal akhir
        $modal_akhir = $modal_awal + $laba_bersih - $prive;
        
        $data['periode'] = $periode;
        $data['modal_awal'] = $modal_awal;
        $data['laba_bersih'] = $laba_bersih; 
        $data['prive'] = $prive;
        $data['modal_akhir'] = $modal_akhir;
        $data['bulan_selected'] = $bulan;
        $data['tahun_selected'] = $tahun;
        
        $this->load->view('templates/header');
        $this->load->view('templates/sidebar', $data);
        $this->load->view('perubahanmodal', $data);
        $this->load->view('templates/footer');
    }
    
    private function format_periode($bulan, $tahun)
    {
        // Format periode sama seperti di controller Labarugi
        if ($bulan && $tahun) {
            $periode = date('F', mktime(0, 0, 0, $bulan, 10)) . ' ' . $tahun;
        } elseif ($tahun) {
            $periode = 'Tahun ' . $tahun;
        } else {
            $periode = 'Semua Periode';
        }
        return $periode;
    }
    
    public function print_perubahanmodal()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        
        $data['title'] = 'Laporan Perubahan Modal';
        
        // Set periode untuk ditampilkan
        $data['periode'] = $this->format_periode($bulan, $tahun);
        $modal_awal = $this->Perubahanmodal_model->get_modal_awal($bulan, $tahun);
        $prive = $this->Perubahanmodal_model->get_total_prive($bulan, $tahun);
        $laba_bersih = $this->Perubahanmodal_model->get_total_laba_rugi($bulan, $tahun);
        $modal_akhir = $modal_awal + $laba_bersih - $prive;
        
        $data['modal_awal'] = $modal_awal;
        $data['laba_bersih'] = $laba_bersih;
        $data['prive'] = $prive;
        $data['modal_akhir'] = $modal_akhir;
        
        // Load view untuk print
        $this->load->view('perubahanmodal_print', $data);
    }
    
    // Method untuk debugging - melihat detail perhitungan
    public function debug_perhitungan()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');
        
        $data = [
            'modal_awal' => $this->Perubahanmodal_model->get_modal_awal($bulan, $tahun),
            'total_prive' => $this->Perubahanmodal_model->get_total_prive($bulan, $tahun),
            'total_prive_alternative' => $this->Perubahanmodal_model->get_total_prive_alternative($bulan, $tahun),
            'laba_rugi_bersih' => $this->Perubahanmodal_model->get_total_laba_rugi($bulan, $tahun),
            'debug_prive' => $this->Perubahanmodal_model->debug_prive($bulan, $tahun),
            'filter' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'periode' => $this->format_periode($bulan, $tahun)
            ]
        ];
        
        $data['modal_akhir'] = $data['modal_awal'] + $data['laba_rugi_bersih'] - $data['total_prive'];
        $data['is_laba'] = $data['laba_rugi_bersih'] >= 0;
        $data['label_laba_rugi'] = $data['is_laba'] ? 'Laba Bersih' : 'Rugi Bersih';
    
    }

}