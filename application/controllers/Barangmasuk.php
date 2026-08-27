<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barangmasuk extends CI_Controller 
{

    public function __construct()
    { 
        parent::__construct();
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
            'id_supplier'     => $this->input->get('id_supplier'),
            'payment'         => $this->input->get('payment'),
            'status'          => $this->input->get('status')
        );

        $data['barangmasuk'] = $this->Barangmasuk_model->get_barangmasuk_filtered($filters, $filter_by);

        // Hitung total dari data yang sudah difilter
        $total_semua = 0;
        if (!empty($data['barangmasuk'])) {
            foreach ($data['barangmasuk'] as $bm) {
                $total_semua += $bm->total;
            }
        }
        $data['total_semua'] = $total_semua;

        if ($this->form_validation->run() == FALSE) {
            $data['title']      = ' Data Barang Masuk';
            $data['user']       = is_logged_in();
            $data['databarang'] = $this->Barangmasuk_model->get_data('databarang')->result();
            $data['supplier']   = $this->Barangmasuk_model->get_data('supplier')->result();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('barangmasuk', $data);
            $this->load->view('templates/footer'); 
        } else {
            // Proses data input
            $id_databarang = $this->input->post('id_databarang');
            $jumlah = $this->input->post('jumlah');
            $harga_beli = str_replace(',', '', $this->input->post('harga_beli')); // Hapus koma dari format
            $total = $jumlah * $harga_beli;

            $data = array(
                'id_databarang' => $id_databarang,
                'id_supplier'   => $this->input->post('id_supplier'),
                'tanggal_masuk' => $this->input->post('tanggal_masuk'),
                'jumlah'        => $jumlah,
                'harga_beli'    => $harga_beli,
                'total'         => $total,
                'payment'       => $this->input->post('payment'),
                'status'        => $this->input->post('status'),
                'keterangan'    => $this->input->post('keterangan'),
            );

            // Handle upload bukti
            $bukti_name = $this->upload_bukti('barang_masuk');
            if ($bukti_name) {
                $data['bukti'] = $bukti_name;
            }

            $this->Barangmasuk_model->insert_data($data, 'barangmasuk');
            $this->Stok_model->updateStokMasuk($id_databarang, $jumlah, $harga_beli);

            if ($bukti_name !== FALSE || empty($_FILES['bukti']['name'])) {
                $this->session->set_flashdata(
                    'pesan',
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Data Barang Masuk Berhasil Ditambahkan!
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

            redirect('barangmasuk');
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
            redirect('barangmasuk');
        } else {
            // Ambil data lama untuk keperluan bukti
            $data_lama = $this->Barangmasuk_model->get_by_id($id);
            
            $id_databarang = $this->input->post('id_databarang');
            $jumlah = $this->input->post('jumlah');
            $harga_beli = str_replace(',', '', $this->input->post('harga_beli')); // Hapus koma dari format
            $total = $jumlah * $harga_beli;

            $data = array(
                'id_databarang' => $id_databarang,
                'id_supplier'   => $this->input->post('id_supplier'),
                'tanggal_masuk' => $this->input->post('tanggal_masuk'),
                'jumlah'        => $jumlah,
                'harga_beli'    => $harga_beli,
                'total'         => $total,
                'payment'       => $this->input->post('payment'),
                'status'        => $this->input->post('status'),
                'keterangan'    => $this->input->post('keterangan'),
            );

            // Handle upload bukti baru jika ada
            if (!empty($_FILES['bukti']['name'])) {
                $bukti_name = $this->upload_bukti('barangmasuk');
                if ($bukti_name) {
                    // Hapus bukti lama jika ada
                    if (!empty($data_lama->bukti)) {
                        $old_path = FCPATH . 'uploads/barangmasuk/' . $data_lama->bukti;
                        if (file_exists($old_path)) {
                            unlink($old_path);
                        }
                    }
                    $data['bukti'] = $bukti_name;
                }
            }

            $where = array('id' => $id);
            $this->Barangmasuk_model->update_data('barangmasuk', $data, $where);

            $this->session->set_flashdata(
                'pesan',
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data Barang Masuk Berhasil Diubah!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>'
            );

            redirect('barangmasuk');
        }
    }

    public function delete($id)
    {
        // Ambil data untuk menghapus bukti jika ada
        $data = $this->Barangmasuk_model->get_by_id($id);
        
        // Hapus bukti jika ada
        if (!empty($data->bukti)) {
            $file_path = FCPATH . 'uploads/barangmasuk/' . $data->bukti;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $where = array('id' => $id);
        $this->Barangmasuk_model->delete('barangmasuk', $where);

        $this->session->set_flashdata(
            'pesan',
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                Data Barang Masuk Berhasil Dihapus!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>'
        );

        redirect('barangmasuk');
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
        $this->form_validation->set_rules('id_supplier', 'Supplier', 'required', [
            'required' => '%s harus diisi!'
        ]);
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required', [
            'required' => '%s harus diisi!'
        ]);
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric', [
            'required' => '%s harus diisi!',
            'numeric'  => '%s harus berupa angka!'
        ]);
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required', [
            'required' => '%s harus diisi!'
        ]);
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required', [
            'required' => '%s harus diisi!'
        ]);
    }
}