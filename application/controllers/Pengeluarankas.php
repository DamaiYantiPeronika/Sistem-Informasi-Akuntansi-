<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengeluarankas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['title'] = ' Pengeluaran Kas';
        $data['user'] = is_logged_in();

        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun') ?? date('Y');

        // Ambil semua transaksi kas kredit (akun kas di kredit)
        $kas_kredit = $this->Transaksi_model->get_filtered_kas_kredit($bulan, $tahun);

        // Ambil kombinasi unik tanggal, keterangan, ref dari kas kredit 
        $blok_keys = [];
        foreach ($kas_kredit as $kas) {
            $blok_keys[] = $kas->tanggal . '|' . $kas->no_trsk . '|' . $kas->keterangan;
        }
        $blok_keys = array_unique($blok_keys);

        // Ambil semua transaksi (semua akun) yang termasuk dalam blok-blok tersebut
        $all_transaksi = $this->Transaksi_model->get_transaksi_by_tanggal_kas();

        $grouped_transaksi = [];
        foreach ($all_transaksi as $tr) {
            $key = $tr->tanggal . '|' . $tr->no_trsk . '|' . $tr->keterangan;
            if (in_array($key, $blok_keys)) {
                $grouped_transaksi[$key][] = $tr;
            }
        }
        $data['grouped_transaksi'] = $grouped_transaksi;

        // Hitung total debit dan kredit
        $totalDebit = 0;
        $totalKredit = 0;
        foreach ($kas_kredit as $ju) {
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
        $this->load->view('pengeluarankas', $data);
        $this->load->view('templates/footer');
    }
}
