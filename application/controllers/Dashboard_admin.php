<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load model yang ADA
         $this->load->model('Transaksi_model');
         $this->load->model('Databarang_model');
         $this->load->model('Supplier_model');
         $this->load->model('Customer_model');
         $this->load->model('Stok_model');
         $this->load->model('Akun_model');
         $this->load->model('Saldoawal_model');
         $this->load->model('Barangmasuk_model');
         $this->load->model('Barangkeluar_model');

    }

    public function index()
    {
        $data['title'] = ' Dashboard';
		$data['user'] = is_logged_in();


        // Ambil data
        $data['barang_masuk'] = $this->Barangmasuk_model->get_barangmasuk();
        $data['barang_keluar'] = $this->Barangkeluar_model->get_barangkeluar();
        $data['total_pembelian'] = $this->Barangmasuk_model->get_total_pembelian();

        // Contoh stok akhir (persediaan)
        $data['persediaan_akhir'] = $this->Barangmasuk_model->get_persediaan_akhir(date('Y-m-d'));

        // Total transaksi
        $data['total_transaksi'] = $this->Transaksi_model->getTotal();
        // Hitung total pembelian

        $total_pembelian = $this->Barangmasuk_model->get_total_pembelian1();

        // Hitung total pembelian
        $total_penjualan = $this->Barangkeluar_model->get_total_penjualan();


        $data['total_pembelian'] = $total_pembelian;
        $data['total_penjualan'] = $total_penjualan;
        $data['laba_kotor'] = $total_penjualan - $total_pembelian;

		//credit belum lunas
		$data['barang_keluar'] = $this->Barangkeluar_model->get_credit_belum_lunas();
		//credit belum lunas
		$data['barang_masuk'] = $this->Barangmasuk_model->get_credit_belum_lunas();
		
        // Total stok barang
        $data['total_stok'] = count($this->Stok_model->getallstok());

        // Total mitra (supplier + customer)
        $data['total_supplier'] = $this->Supplier_model->get_data('supplier')->num_rows();
        $data['total_customer'] = $this->Customer_model->get_data('customer')->num_rows();

        // Saldo kas bisa dihitung dari saldo awal + transaksi debit - kredit
        $saldo_awal = 0;
        $saldo_awal_row = $this->Saldoawal_model->get_saldo_awal_by_no_akun('1011');
        if ($saldo_awal_row) {
            $saldo_awal = ($saldo_awal_row->jenis_saldo == 'debit' ? $saldo_awal_row->jumlah : -$saldo_awal_row->jumlah);
        }
        $debit = 0;
        $kredit = 0;
        $transaksi = $this->Transaksi_model->get_all();
        foreach ($transaksi as $t) {
            if ($t->no_akun == '1011') {
                if ($t->jenis_saldo == 'debit') {
                    $debit += $t->jumlah;
                } else {
                    $kredit += $t->jumlah;
                }
            }
        }
        $data['saldo_kas'] = $saldo_awal + $debit - $kredit;

        // Transaksi terbaru
        $data['latest_transaksi'] = $this->Transaksi_model->getLatest(5);

        // Grafik transaksi bulanan
        $bulan = [];
        $jumlah = [];
        $result = $this->Transaksi_model->getMonthlyTransactionSummary();
        foreach ($result as $row) {
            $bulan[] = $row['bulan'];
            $jumlah[] = $row['jumlah'];
        }
        $data['bulan'] = $bulan;
        $data['jumlah_transaksi'] = $jumlah;

        // Ambil data grafik Penjualan dan Pembelian
$penjualan_data = $this->Barangkeluar_model->getMonthlyPenjualan();
$pembelian_data = $this->Barangmasuk_model->getMonthlyPembelian();

// Siapkan array bulan dan nilai
$bulan_penjualan_pembelian = [];
$jumlah_penjualan = [];
$jumlah_pembelian = [];

for ($i = 1; $i <= 12; $i++) {
    $bulan_penjualan_pembelian[] = date('M', mktime(0,0,0,$i,1,date('Y'))); // Jan, Feb, dst.

    // Penjualan
    $found_penjualan = false;
    foreach ($penjualan_data as $p) {
        if ($p->bulan == $i) {
            $jumlah_penjualan[] = (float)$p->total_penjualan;
            $found_penjualan = true;
            break;
        }
    }
    if (!$found_penjualan) {
        $jumlah_penjualan[] = 0;
    }

    // Pembelian
    $found_pembelian = false;
    foreach ($pembelian_data as $pb) {
        if ($pb->bulan == $i) {
            $jumlah_pembelian[] = (float)$pb->total_pembelian;
            $found_pembelian = true;
            break;
        }
    }
    if (!$found_pembelian) {
        $jumlah_pembelian[] = 0;
    }
}

// Kirim ke view
$data['bulan_penjualan_pembelian'] = $bulan_penjualan_pembelian;
$data['jumlah_penjualan'] = $jumlah_penjualan;
$data['jumlah_pembelian'] = $jumlah_pembelian;


$data['barang_keluar'] = $this->db
        ->select('barangkeluar.*, databarang.kode_barang, databarang.nama_barang, customer.nama_customer')
        ->from('barangkeluar')
        ->join('databarang', 'databarang.id_databarang = barangkeluar.id_databarang')
        ->join('customer', 'customer.id_customer = barangkeluar.id_customer')
        ->where('barangkeluar.payment', 'credit')
        ->where('barangkeluar.status', 'Belum Lunas')
        ->get()
        ->result();

$data['barang_masuk'] = $this->db
        ->select('barangmasuk.*, databarang.kode_barang, databarang.nama_barang, supplier.nama_supplier')
        ->from('barangmasuk')
        ->join('databarang', 'databarang.id_databarang = barangmasuk.id_databarang')
        ->join('supplier', 'supplier.id_supplier = barangmasuk.id_supplier')
        ->where('barangmasuk.payment', 'credit')
        ->where('barangmasuk.status', 'Belum Lunas')
        ->get()
        ->result();


        // Load view
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('dashboard_admin', $data);
        $this->load->view('templates/footer');
    }

}
