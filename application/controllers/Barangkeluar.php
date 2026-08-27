<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangkeluar extends CI_Controller 
{

    public function __construct()
    { 
        parent::__construct();
        $this->load->model('Barangkeluar_model');
        $this->load->model('Barangmasuk_model');
        $this->load->model('Stok_model');
        $this->load->library('form_validation');
        $this->load->library('upload');
    }

    public function index()
    {
        $this->_rules();

        // Ambil filter dari GET
        $filter_by = $this->input->get('filter_by');
        $filters = array(
            'tanggal_awal'    => $this->input->get('tanggal_awal'),
            'tanggal_akhir'   => $this->input->get('tanggal_akhir'),
            'id_databarang'   => $this->input->get('id_databarang'),
            'id_customer'     => $this->input->get('id_customer'),
            'payment'         => $this->input->get('payment'),
            'status'          => $this->input->get('status')
        );

        $data['barangkeluar'] = $this->Barangkeluar_model->get_barangkeluar_filtered($filters, $filter_by);

        // Hitung total dari data yang sudah difilter
        $total_semua = 0;
        if (!empty($data['barangkeluar'])) {
            foreach ($data['barangkeluar'] as $bk) {
                $total_semua += $bk->total;
            }
        }
        $data['total_semua'] = $total_semua;

        if ($this->form_validation->run() == FALSE) {
            $data['title']      = ' Data Barang Keluar';
            $data['user']       = is_logged_in();
            $data['stok']       = $this->Stok_model->getallstok('stokbarang');
            $data['stokbarang'] = $this->Stok_model->getallstok();
            $data['customer']   = $this->Barangkeluar_model->get_data('customer')->result();
            $data['databarang'] = $this->Barangmasuk_model->get_data('databarang')->result();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('barangkeluar', $data);
            $this->load->view('templates/footer'); 
        } else {
            // Ambil data barang dari stok
            $id_databarang = $this->input->post('id_databarang');
            $barang = $this->Stok_model->get_id_databarang($id_databarang);
            $jumlah = $this->input->post('jumlah');

            if ($barang) { 
                $harga_rata2 = $barang->harga_rata2;  // HPP
                $harga_jual  = $barang->harga_jual;   // Harga jual yang sudah dihitung di stokbarang
                $hpp         = round($harga_rata2 * $jumlah);
            } else {
                $harga_rata2 = 0;
                $hpp         = 0;
                $harga_jual  = 0;
            }

            $total = $harga_jual * $jumlah;

            $data = array(
                'id_databarang'  => $id_databarang,
                'id_customer'    => $this->input->post('id_customer'),
                'tanggal_keluar' => $this->input->post('tanggal_keluar'),
                'jumlah'         => $jumlah,
                'hpp'            => $hpp,
                'harga_jual'     => $harga_jual,
                'total'          => $total,
                'payment'        => $this->input->post('payment'),
                'status'         => $this->input->post('status'),
                'keterangan'     => $this->input->post('keterangan'),
            );

            // Handle upload bukti
            $bukti_name = $this->upload_bukti('barang_keluar');
            if ($bukti_name) {
                $data['bukti'] = $bukti_name;
            }

            $this->Barangkeluar_model->insert_data($data, 'barangkeluar');
            $this->Stok_model->updateStokKeluar($id_databarang, $jumlah);

            if ($bukti_name !== FALSE || empty($_FILES['bukti']['name'])) {
                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Data Barang Keluar Berhasil Ditambahkan!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>'
                );
            } else {
                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Data berhasil ditambah, tetapi bukti gagal diupload. ' . $this->upload->display_errors('', '')
                        . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>'
                );
            }

            redirect('barangkeluar');
        }
    }

    public function edit($id)
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Gagal mengubah data. Pastikan semua input sudah benar!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
            );
            redirect('barangkeluar');
        } else {
            // Ambil data lama untuk keperluan bukti
            $data_lama = $this->Barangkeluar_model->get_by_id($id);
            
            $id_databarang = $this->input->post('id_databarang');
            $barang = $this->Stok_model->get_id_databarang($id_databarang);
            $jumlah = $this->input->post('jumlah');

            if ($barang) {
                $hpp        = $barang->harga_rata2;  // HPP dari stokbarang
                $harga_jual = $barang->harga_jual;   // Harga jual yang sudah dihitung
            } else {
                $hpp        = 0;
                $harga_jual = 0;
            }

            $total = round($harga_jual * $jumlah);

            $data = array(
                'id_databarang'  => $id_databarang,
                'id_customer'    => $this->input->post('id_customer'),
                'tanggal_keluar' => $this->input->post('tanggal_keluar'),
                'jumlah'         => $jumlah,
                'hpp'            => $hpp,
                'harga_jual'     => $harga_jual,
                'total'          => $total,
                'payment'        => $this->input->post('payment'),
                'status'         => $this->input->post('status'),
                'keterangan'     => $this->input->post('keterangan'),
            );

            // Handle upload bukti baru jika ada
            if (!empty($_FILES['bukti']['name'])) {
                $bukti_name = $this->upload_bukti('barangkeluar');
                if ($bukti_name) {
                    // Hapus bukti lama jika ada
                    if (!empty($data_lama->bukti)) {
                        $old_path = FCPATH . 'uploads/barangkeluar/' . $data_lama->bukti;
                        if (file_exists($old_path)) {
                            unlink($old_path);
                        }
                    }
                    $data['bukti'] = $bukti_name;
                }
            }

            $where = array('id' => $id);
            $this->Barangkeluar_model->update_data('barangkeluar', $data, $where);

            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data Barang Keluar Berhasil Diubah!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
            );

            redirect('barangkeluar');
        }
    }

    public function delete($id)
    {
        // Ambil data untuk menghapus bukti jika ada
        $data = $this->Barangkeluar_model->get_by_id($id);
        
        // Hapus bukti jika ada
        if (!empty($data->bukti)) {
            $file_path = FCPATH . 'uploads/barangkeluar/' . $data->bukti;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $where = array('id' => $id);
        $this->Barangkeluar_model->delete('barangkeluar', $where);

        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Data Barang Keluar Berhasil Dihapus!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>'
        );

        redirect('barangkeluar');
    }

    // Private method untuk upload bukti
    private function upload_bukti($folder) 
    {
        // Buat folder jika belum ada
        $upload_path = FCPATH . 'uploads/' . $folder . '/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, TRUE);
        }

        $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048, // 2MB
            'encrypt_name'  => TRUE,
            'remove_spaces' => TRUE
        );

        $this->upload->initialize($config);

        if ($this->upload->do_upload('bukti')) {
            $upload_data = $this->upload->data();
            return $upload_data['file_name'];
        } else {
            // Log error jika diperlukan
            log_message('error', 'Upload Error: ' . $this->upload->display_errors());
            return FALSE;
        }
    }

    private function _rules()
    {
        $this->form_validation->set_rules('id_databarang', 'Barang', 'required', [
            'required' => '%s harus diisi!'
        ]);
        $this->form_validation->set_rules('id_customer', 'Nama Customer', 'required', [
            'required' => '%s harus diisi!'
        ]);
        $this->form_validation->set_rules('tanggal_keluar', 'Tanggal Keluar', 'required', [
            'required' => '%s harus diisi!'
        ]);
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric', [
            'required' => '%s harus diisi!'
        ]);
    }
}