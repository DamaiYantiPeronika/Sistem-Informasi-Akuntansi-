<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penyesuaian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Akun_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = ' Jurnal Penyesuaian';
        $data['user'] = is_logged_in();

        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun') ?? date('Y');

        // Ambil seluruh transaksi dengan filter bulan & tahun
        $transaksi = $this->Transaksi_model->get_all_filtered($bulan, $tahun);

        // Group per transaksi utama (tanggal|no_trsk|keterangan)
        $grouped = [];
        foreach ($transaksi as $tr) {
            $key = $tr->tanggal . '|' . $tr->no_trsk . '|' . $tr->keterangan;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $tr;
        }

        // Filter: hanya blok yang pada tanggalnya ada akun 6013
        $tanggal_ada_6013 = [];
        foreach ($grouped as $key => $blok) {
            foreach ($blok as $tr) {
                if ($tr->no_akun == '6013') {
                    $tanggal = explode('|', $key)[0];
                    $tanggal_ada_6013[$tanggal] = true;
                }
            }
        }

        $filtered_grouped = [];
        foreach ($grouped as $key => $blok) {
            $tanggal = explode('|', $key)[0];
            if (isset($tanggal_ada_6013[$tanggal])) {
                $filtered_grouped[$key] = $blok;
            }
        }

        $data['grouped'] = $filtered_grouped;

        // Hitung total debit dan kredit
        $totalDebit = 0;
        $totalKredit = 0;
        foreach ($filtered_grouped as $blok) {
            foreach ($blok as $tr) {
                if ($tr->jenis_saldo == 'debit') {
                    $totalDebit += $tr->jumlah;
                } elseif ($tr->jenis_saldo == 'kredit') {
                    $totalKredit += $tr->jumlah;
                }
            }
        }
        $data['debit'] = $totalDebit;
        $data['kredit'] = $totalKredit;
        $data['selisih'] = $totalDebit - $totalKredit;
        $data['seimbang'] = ($totalDebit - $totalKredit == 0);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('penyesuaian', $data);
        $this->load->view('templates/footer');
    }
}
