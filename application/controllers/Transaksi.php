<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi extends CI_Controller
{ 
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Transaksi_model');
        $this->load->model('Akun_model');
		$this->load->model('Barangmasuk_model');
		$this->load->model('Barangkeluar_model');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['title'] = ' Data Transaksi';
        $data['user'] = is_logged_in();
        $data['transaksi'] = $this->Transaksi_model->get_all();
        $data['edit'] = $this->Transaksi_model->get_all();
        $data['akun'] = $this->Akun_model->get_all();
		$data['unpaid_barangmasuk'] = $this->Barangmasuk_model->get_unpaid_credit_by_supplier();
		$data['unpaid_barangkeluar'] = $this->Barangkeluar_model->get_unpaid_credit_by_customer();


        // Ambil data transaksi untuk dropdown filter akun
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        if ($bulan || $tahun) {
            $data['transaksi'] = $this->Transaksi_model->get_by_bulan_tahun($bulan, $tahun);
        } else {
            $data['transaksi'] = $this->Transaksi_model->get_all();
        }
        $grouped = [];
        foreach ($data['transaksi'] as $tr) {
            $key = $tr->tanggal . '|' . $tr->no_trsk . '|' . $tr->keterangan;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $tr;
        }
        $data['grouped'] = $grouped;

        $totalDebit = 0;
        $totalKredit = 0;
        foreach ($data['transaksi'] as $tr) {
            if ($tr->jenis_saldo == 'debit') {
                $totalDebit += $tr->jumlah;
            } elseif ($tr->jenis_saldo == 'kredit') {
                $totalKredit += $tr->jumlah;
            }
        }
        $selisih = round($totalDebit - $totalKredit);
        $seimbang = round($selisih == 0);

        $data['debit'] = $totalDebit;
        $data['kredit'] = $totalKredit;
        $data['selisih'] = $selisih;
        $data['seimbang'] = $seimbang;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('transaksi', $data);
        $this->load->view('templates/footer', $data);
    }

public function tambah()
{
    $tanggal = $this->input->post('tanggal');
    $keterangan = strtolower($this->input->post('keterangan'));
    $id_akun = $this->input->post('id_akun');
    $jumlah = $this->input->post('jumlah');
    $jenis_saldo = $this->input->post('jenis_saldo');
    $is_related = $this->input->post('is_related');
    $related_type = $this->input->post('related_type');
    $related_id = $this->input->post('related_party');

    // Validasi awal: pastikan semua array memiliki jumlah yang sama dan tidak kosong
    if (empty($id_akun) || empty($jumlah) || empty($jenis_saldo)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data akun belum lengkap</div>');
        redirect('transaksi');
        return;
    }

    if (count($id_akun) !== count($jumlah) || count($id_akun) !== count($jenis_saldo)) {
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Jumlah baris akun tidak valid</div>');
        redirect('transaksi');
        return;
    }

    // Generate kode transaksi berdasarkan keterangan
    if (strpos($keterangan, 'penjualan tunai') !== false) {
        $prefix = 'JT-';
    } elseif (strpos($keterangan, 'penjualan kredit') !== false) {
        $prefix = 'JK-';
    } elseif (strpos($keterangan, 'pembelian tunai') !== false) {
        $prefix = 'BT-';
    } elseif (strpos($keterangan, 'pembelian kredit') !== false) {
        $prefix = 'BK-';
    } else {
        $prefix = 'T-';
    }

    // Format tanggal dan generate nomor transaksi
    $tanggal = date('Y-m-d', strtotime($tanggal));
    $today = date('ymd', strtotime($tanggal));

    $this->db->like('no_trsk', $prefix . $today);
    $this->db->from('transaksi');
    $count_today = $this->db->count_all_results();

    $urut = $count_today + 1;
    $no_trsk = $prefix . $today . sprintf("%03d", $urut);

    // Simpan tiap baris akun
    foreach ($id_akun as $i => $id) {
        // Validasi tambahan: skip jika salah satu elemen kosong
        if (empty($id) || !isset($jumlah[$i]) || !isset($jenis_saldo[$i])) {
            continue;
        }

        $data = [
            'tanggal' => $tanggal,
            'no_trsk' => $no_trsk,
            'keterangan' => $this->input->post('keterangan'),
            'id_akun' => $id,
            'jumlah' => floatval($jumlah[$i]),
            'jenis_saldo' => $jenis_saldo[$i],
        ];
        $this->Transaksi_model->insert($data);
    }

    // Update status hutang/piutang jika terkait
    if ($is_related == '1' && $related_type && $related_id) {
        if ($related_type == 'barangmasuk') {
            $this->load->model('Barangmasuk_model');
            $this->Barangmasuk_model->update_status_lunas_barangmasuk($related_id);
        } elseif ($related_type == 'barangkeluar') {
            $this->load->model('Barangkeluar_model');
            $this->Barangkeluar_model->update_status_lunas_barangkeluar($related_id);
        }
    }

    // Selesai
    $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil ditambahkan dengan No: ' . $no_trsk . '</div>');
    redirect('transaksi');
}


    public function edit($no_trsk)
    {
        $data['title'] = 'Edit Transaksi';
        $data['akun'] = $this->Akun_model->get_all();
        $data['transaksi'] = $this->Transaksi_model->get_by_no_trsk($no_trsk);

        if ($this->input->post()) {
            // Hapus transaksi lama
            $this->db->where('no_trsk', $no_trsk)->delete('transaksi');

            $tanggal = $this->input->post('tanggal');
            $keterangan = strtolower($this->input->post('keterangan'));
            $jumlah_global_edit = $this->input->post('jumlah_global'); 
            $id_akun = $this->input->post('id_akun');
            $jumlah = $this->input->post('jumlah');
            $jenis_saldo = $this->input->post('jenis_saldo');

            // Validasi array minimal harus terisi
            if (empty($id_akun) || empty($jumlah) || empty($jenis_saldo)) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data akun belum lengkap</div>');
                redirect('transaksi');
            }

            // Simpan ulang data transaksi
            $dataBatch = [];
            foreach ($id_akun as $i => $id) {
                $dataBatch[] = [
                    'tanggal' => $tanggal,
                    'no_trsk' => $no_trsk,
                    'keterangan' => $keterangan,
                    'id_akun' => $id,
                    'jumlah' => $jumlah[$i],
                    'jenis_saldo' => $jenis_saldo[$i],
                ];
            }

            $this->db->insert_batch('transaksi', $dataBatch);

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Transaksi berhasil diperbarui!</div>');
            redirect('transaksi');
        } else {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('transaksi/edit', $data);
            $this->load->view('templates/footer');
        }
    }
 
    public function delete($no_trsk)
    {
        $this->db->where('no_trsk', $no_trsk);
        $this->db->delete('transaksi');
        $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Transaksi dengan No: ' . $no_trsk . ' berhasil dihapus!</div>');
        redirect('transaksi');
    }
}
 