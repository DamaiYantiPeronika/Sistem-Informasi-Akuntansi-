<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penutup_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Perubahanmodal_model');
    }

    public function get_jurnal_penutup($bulan = null, $tahun = null)
    {
        $result = [];
        $total_pendapatan_penutup = 0;
        $total_beban_penutup = 0;

        // ambil akun pendapatan
        $pendapatan = $this->get_saldo_akhir('4', $bulan, $tahun);
        foreach ($pendapatan as $row) {
            if ($row->saldo_akhir != 0) {
                // Debit Akun Pendapatan
                $result[] = (object)[
                    'no_akun' => $row->no_akun, // Akun Pendapatan
                    'nama_akun' => $row->nama_akun,
                    'debit' => abs($row->saldo_akhir),
                    'kredit' => 0
                ];
                // Kredit Ikhtisar Laba/Rugi
                $result[] = (object)[
                    'no_akun' => '3013', // Ikhtisar L/R
                    'nama_akun' => 'Ikhtisar L/R',
                    'debit' => 0,
                    'kredit' => abs($row->saldo_akhir)
                ];
                $total_pendapatan_penutup += abs($row->saldo_akhir);
            }
        }

        // ambil akun HPP & beban
        $beban = $this->get_saldo_akhir(['5', '6'], $bulan, $tahun);
        foreach ($beban as $row) {
            if ($row->saldo_akhir != 0) {
                // Debit Ikhtisar Laba/Rugi
                $result[] = (object)[
                    'no_akun' => '3013', // Ikhtisar L/R
                    'nama_akun' => 'Ikhtisar L/R',
                    'debit' => abs($row->saldo_akhir),
                    'kredit' => 0
                ];
                // Kredit Akun Beban/HPP
                $result[] = (object)[
                    'no_akun' => $row->no_akun, // Akun Beban/HPP
                    'nama_akun' => $row->nama_akun,
                    'debit' => 0,
                    'kredit' => abs($row->saldo_akhir)
                ];
                $total_beban_penutup += abs($row->saldo_akhir);
            }
        }

        // --- Perbaikan: Hapus penutupan laba/rugi bersih dari Ikhtisar L/R ke Modal Dana (3011) ---
        // Jika Anda ingin ini tidak muncul di daftar jurnal penutup
        // Konsekuensi: Laba/Rugi bersih tidak akan ditampilkan ditutup ke Modal di daftar ini,
        // meskipun secara aktual harusnya memengaruhi modal.

        // --- Penutupan akun Prive ---
        // Prive tetap harus ditutup ke modal karena ia adalah akun kontra-modal.
        // Jika Anda juga tidak ingin modal tampil di sini, itu perlu pendekatan lain
        // (misalnya membuat jurnal prive hanya mempengaruhi laba/rugi di model ini,
        // yang secara akuntansi tidak standar untuk jurnal penutup final).

        // Load Perubahanmodal_model jika belum di-load (opsional jika sudah di autoload/controller)
        if (!isset($this->Perubahanmodal_model)) {
            $this->load->model('Perubahanmodal_model');
        }

        $total_prive = $this->Perubahanmodal_model->get_total_prive($bulan, $tahun);

        if ($total_prive != 0) {
            // Prive memiliki saldo normal debit, jadi untuk menutupnya, kita kredit Prive
            // dan debit akun Modal.
            $result[] = (object)[
                'no_akun' => '3011', // Akun Modal
                'nama_akun' => 'Modal Dana',
                'debit' => abs($total_prive),
                'kredit' => 0
            ];
            $result[] = (object)[
                'no_akun' => '3012', // Akun Prive
                'nama_akun' => 'Prive',
                'debit' => 0,
                'kredit' => abs($total_prive)
            ];
        }

        return $result;
    }

    private function get_saldo_akhir($prefix, $bulan, $tahun)
    {
        $this->db->select('a.no_akun, a.nama_akun,
            (IFNULL(sa.debit,0) - IFNULL(sa.kredit,0) +
            IFNULL(m.mutasi_debit,0) - IFNULL(m.mutasi_kredit,0)) AS saldo_akhir');
        $this->db->from('akun a');
        $this->db->join('(SELECT id_akun,
                        SUM(CASE WHEN jenis_saldo="debit" THEN jumlah ELSE 0 END) as debit,
                        SUM(CASE WHEN jenis_saldo="kredit" THEN jumlah ELSE 0 END) as kredit
                        FROM saldoawal GROUP BY id_akun) sa', 'sa.id_akun=a.id_akun', 'left');
        $this->db->join('(SELECT id_akun,
                        SUM(CASE WHEN jenis_saldo="debit" THEN jumlah ELSE 0 END) as mutasi_debit,
                        SUM(CASE WHEN jenis_saldo="kredit" THEN jumlah ELSE 0 END) as mutasi_kredit
                        FROM transaksi
                        ' . ($bulan && $tahun ? 'WHERE MONTH(tanggal)=' . $bulan . ' AND YEAR(tanggal)=' . $tahun : ($tahun ? 'WHERE YEAR(tanggal)=' . $tahun : '')) . '
                        GROUP BY id_akun) m', 'm.id_akun=a.id_akun', 'left');
        $this->db->order_by('a.no_akun', 'ASC');
        if (is_array($prefix)) {
            $this->db->group_start();
            foreach ($prefix as $i => $p) {
                $this->db->or_like('a.no_akun', $p, 'after');
            }
            $this->db->group_end();
        } else {
            $this->db->like('a.no_akun', $prefix, 'after');
        }

        return $this->db->get()->result();
    }

    private function hitung_laba_bersih($bulan, $tahun)
    {
        $pendapatan = $this->get_saldo_akhir('4', $bulan, $tahun);
        $beban = $this->get_saldo_akhir(['5', '6'], $bulan, $tahun);
        
        $total_pendapatan = array_sum(array_map(function($x) {
            return $x->saldo_akhir;
        }, $pendapatan));
        
        $total_beban = array_sum(array_map(function($x) {
            return $x->saldo_akhir;
        }, $beban));
        
        return $total_pendapatan - $total_beban;
    }
}