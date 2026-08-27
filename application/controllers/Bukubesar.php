<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bukubesar extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Akun_model');
        $this->load->model('Saldoawal_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Buku Besar';
        $data['user'] = is_logged_in();

        $data['daftar_akun'] = $this->Akun_model->get_all();
        $data['saldo_awal'] = 0;
        $data['jenis_saldo_awal'] = '';

        $no_akun = $this->input->get('no_akun');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        // Inisialisasi variabel
        $totalDebit = 0;
        $totalKredit = 0;
        $saldo_berjalan = 0;

        if ($no_akun) {
            // Ambil saldo awal berdasarkan no_akun
            $saldo_awal_data = $this->Saldoawal_model->get_saldo_awal_by_no_akun($no_akun);
            if ($saldo_awal_data) {
                $data['saldo_awal'] = $saldo_awal_data->jumlah;
                // Ambil jenis saldo dari tabel saldoawal, bukan dari akun
                $data['jenis_saldo_awal'] = $saldo_awal_data->jenis_saldo;
            }
        }

        // Hitung saldo awal dan masukkan ke total serta saldo berjalan
        if ($data['jenis_saldo_awal'] == 'debit') {
            $totalDebit += $data['saldo_awal'];
            $saldo_berjalan = $data['saldo_awal']; // Saldo debit positif
        } elseif ($data['jenis_saldo_awal'] == 'kredit') {
            $totalKredit += $data['saldo_awal'];
            $saldo_berjalan = -$data['saldo_awal']; // Saldo kredit negatif dalam perhitungan
        }

        $data['no_akun'] = $no_akun;
        $data['nama_akun'] = '';
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        $transaksi = $this->Transaksi_model->get_all();

        // Filter transaksi berdasarkan bulan dan tahun
        $filtered = [];
        foreach ($transaksi as $tr) {
            $ok = true;
            if ($bulan && (int)date('m', strtotime($tr->tanggal)) != (int)$bulan) $ok = false;
            if ($tahun && (int)date('Y', strtotime($tr->tanggal)) != (int)$tahun) $ok = false;
            if ($ok) $filtered[] = $tr;
        }

        // Kelompokkan transaksi berdasarkan tanggal, no_trsk, dan keterangan
        $grouped = [];
        foreach ($filtered as $tr) {
            $key = $tr->tanggal . '|' . $tr->no_trsk . '|' . $tr->keterangan;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $tr;
        }

        if ($no_akun) {
            // Cari transaksi yang memiliki akun yang dipilih
            $tanggal_ada_akun = [];
            foreach ($grouped as $key => $blok) {
                foreach ($blok as $tr) {
                    if ($tr->no_akun == $no_akun) {
                        $tanggal = explode('|', $key)[0];
                        $tanggal_ada_akun[$tanggal] = true;
                    }
                }
            }

            // Filter hanya transaksi yang memiliki akun yang dipilih
            $filtered_grouped = [];
            foreach ($grouped as $key => $blok) {
                $tanggal = explode('|', $key)[0];
                if (isset($tanggal_ada_akun[$tanggal])) {
                    usort($blok, function ($a, $b) {
                        return (int)$a->id - (int)$b->id;
                    });
                    $filtered_grouped[$key] = $blok;
                }
            }

            // Hitung total debit dan kredit dari transaksi untuk akun yang dipilih
            foreach ($filtered_grouped as $blok) {
                foreach ($blok as $bb) {
                    if ($bb->no_akun == $no_akun) {
                        if ($bb->jenis_saldo == 'debit') {
                            $totalDebit += $bb->jumlah;
                            $saldo_berjalan += $bb->jumlah;
                        } elseif ($bb->jenis_saldo == 'kredit') {
                            $totalKredit += $bb->jumlah;
                            $saldo_berjalan -= $bb->jumlah;
                        }
                    }
                }
            }

            // Hitung selisih dan tentukan posisi saldo akhir
            $selisih = $totalDebit - $totalKredit;
            $data['selisih'] = $saldo_berjalan; // Saldo berjalan adalah saldo akhir yang sebenarnya

            // Tentukan posisi saldo akhir di debit atau kredit berdasarkan nilai saldo berjalan
            if ($saldo_berjalan >= 0) {
                $data['total_saldo_debit'] = $saldo_berjalan;
                $data['total_saldo_kredit'] = 0;
            } else {
                $data['total_saldo_debit'] = 0;
                $data['total_saldo_kredit'] = abs($saldo_berjalan);
            }

            // Urutkan berdasarkan tanggal
            uksort($filtered_grouped, function ($a, $b) {
                $aParts = explode('|', $a);
                $bParts = explode('|', $b);
                $cmp = strtotime($aParts[0]) - strtotime($bParts[0]);
                return $cmp === 0 ? strcmp($a, $b) : $cmp;
            });

            $data['grouped'] = $filtered_grouped;

            // Ambil nama akun
            foreach ($data['daftar_akun'] as $akun) {
                if ($akun->no_akun == $no_akun) {
                    $data['nama_akun'] = $akun->nama_akun;
                    break;
                }
            }
        } else {
            $data['grouped'] = [];
            $data['total_saldo_debit'] = 0;
            $data['total_saldo_kredit'] = 0;
            $data['selisih'] = 0;
        }

        $data['debit'] = $totalDebit;
        $data['kredit'] = $totalKredit;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('bukubesar', $data);
        $this->load->view('templates/footer');
    }
}
