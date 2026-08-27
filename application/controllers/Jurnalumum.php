<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jurnalumum extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Jurnal Umum';
        $data['user'] = is_logged_in();

        // Ambil bulan dan tahun dari parameter GET
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun') ?? date('Y'); // Default ke tahun sekarang jika tidak ada

        // Panggil model untuk mendapatkan data jurnal umum yang sudah difilter
        // Menggunakan get_filtered_jurnal_umum yang akan kita perbaiki di model
        $jurnal_umum_data = $this->Transaksi_model->get_filtered_jurnal_umum($bulan, $tahun);

        // Inisialisasi variabel untuk menyimpan data yang dikelompokkan
        $grouped = [];
        foreach ($jurnal_umum_data as $tr) {
            // Group by tanggal + No Transaksi + keterangan
            $key = $tr->tanggal . '|' . $tr->no_trsk . '|' . $tr->keterangan;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $tr;
        }
        $data['grouped'] = $grouped;

        // Cari tanggal yang mengandung akun 6013 (logika filter yang sudah ada)
        $tanggal_terlarang = [];
        foreach ($grouped as $key => $blok) {
            foreach ($blok as $tr) {
                if ($tr->no_akun == '6013') {
                    // Ambil tanggal dari key
                    $tanggal = explode('|', $key)[0];
                    $tanggal_terlarang[$tanggal] = true;
                }
            }
        }

        // Filter blok yang tanggalnya tidak ada akun 6013
        $filtered_grouped = [];
        foreach ($grouped as $key => $blok) {
            $tanggal = explode('|', $key)[0];
            if (!isset($tanggal_terlarang[$tanggal])) {
                $filtered_grouped[$key] = $blok;
            }
        }

        $data['grouped'] = $filtered_grouped;

        // Hitung total debit dan kredit HANYA dari data yang tampil (filtered_grouped)
        $totalDebit = 0;
        $totalKredit = 0;
        foreach ($filtered_grouped as $blok) {
            foreach ($blok as $ju) {
                if ($ju->jenis_saldo == 'debit') {
                    $totalDebit += $ju->jumlah;
                } elseif ($ju->jenis_saldo == 'kredit') {
                    $totalKredit += $ju->jumlah;
                }
            }
        }
        $selisih = $totalDebit - $totalKredit;
        $seimbang = ($selisih == 0);

        $data['debit'] = $totalDebit;
        $data['kredit'] = $totalKredit;
        $data['selisih'] = $selisih;
        $data['seimbang'] = $seimbang;

        // Variabel untuk mempertahankan nilai filter di form
        $data['selected_bulan'] = $bulan;
        $data['selected_tahun'] = $tahun;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('jurnalumum', $data); // Pastikan view ini memiliki form filter
        $this->load->view('templates/footer');
    }
}
