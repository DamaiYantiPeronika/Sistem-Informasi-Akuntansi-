<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemasukankas extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = ' Pemasukan Kas';
        $data['user'] = is_logged_in();

        // Ambil bulan dan tahun dari parameter GET
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun') ?? date('Y'); // Default ke tahun sekarang jika tidak ada

        //untuk mendapatkan transaksi kas debit yang difilter
        $kas_debit = $this->Transaksi_model->get_filtered_kas_debit($bulan, $tahun);


        // Ambil semua tanggal unik dari kas debit
        $blok_keys = [];
        foreach ($kas_debit as $kas) {
            $blok_keys[] = $kas->tanggal . '|' . $kas->no_trsk . '|' . $kas->keterangan;
        }
        $blok_keys = array_unique($blok_keys);

        // Ambil semua transaksi (semua akun) pada tanggal-tanggal tersebut
        $all_transaksi = $this->Transaksi_model->get_transaksi_by_tanggal_kas(); // ambil semua transaksi

        $grouped_transaksi = [];
        foreach ($all_transaksi as $tr) {
            $key = $tr->tanggal . '|' . $tr->no_trsk . '|' . $tr->keterangan;
            if (in_array($key, $blok_keys)) {
                $grouped_transaksi[$key][] = $tr;
            }
        }
        $data['grouped_transaksi'] = $grouped_transaksi;

        // Hitung total debit dan kredit dari kas debit saja
        $totalDebit = 0;
        $totalKredit = 0;
        foreach ($kas_debit as $ju) {
            if ($ju->jenis_saldo == 'debit') {
                $totalDebit += $ju->jumlah;
            } elseif ($ju->jenis_saldo == 'kredit') {
                $totalKredit += $ju->jumlah;
            }
        }
        $selisih = $totalDebit - $totalKredit;
        $seimbang = ($selisih == 0);

        $data['debit'] = $totalDebit;
        $data['kredit'] = $totalKredit;
        $data['selisih'] = $selisih;
        $data['seimbang'] = $seimbang;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('pemasukankas', $data);
        $this->load->view('templates/footer');
    }
}
