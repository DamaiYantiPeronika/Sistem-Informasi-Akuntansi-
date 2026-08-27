<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Neracasaldo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Neracasaldo_model');
        $this->load->model('Saldoawal_model');
        $this->load->model('Akun_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Neraca Saldo';
        $data['user'] = is_logged_in();

        // Ambil parameter filter
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        // Set periode untuk ditampilkan
        $periode = '';
        if ($bulan && $tahun) {
            $nama_bulan = date('F', mktime(0, 0, 0, $bulan, 1));
            $periode = $nama_bulan . ' ' . $tahun;
        } elseif ($bulan) {
            $nama_bulan = date('F', mktime(0, 0, 0, $bulan, 1));
            $periode = $nama_bulan;
        } elseif ($tahun) {
            $periode = 'Tahun ' . $tahun;
        } else {
            $periode = 'Semua Periode';
        }

        $data['periode'] = $periode;
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        // Ambil data neraca saldo
        $data['neraca_saldo'] = $this->Neracasaldo_model->get_neraca_saldo($bulan, $tahun);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('neracasaldo', $data);
        $this->load->view('templates/footer');
    }

    public function print_neraca()
    {
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        $data['title'] = 'Laporan Neraca Saldo';

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

        // Ambil data neraca saldo dengan struktur yang sama
        $data['neraca_saldo'] = $this->Neracasaldo_model->get_neraca_saldo($bulan, $tahun);

        // Ambil informasi balance
        $data['balance_info'] = $this->Neracasaldo_model->validate_balance($bulan, $tahun);

        $this->load->view('neracasaldo_print', $data);
    }

    // Method untuk backward compatibility
    public function neracasaldo()
    {
        $this->print_neraca();
    }
}
