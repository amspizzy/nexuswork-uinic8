<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MasterHpp extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        $role = $this->session->userdata('role');
        if ($role == 'karyawan') {
            $this->session->set_flashdata('error', 'Akses Ditolak! Anda tidak memiliki izin ke menu ini.');
            redirect('Dashboard');
        }
        $this->load->model(['M_MasterHpp', 'M_ActivityLog']);
    }

    public function index()
    {
        $data['material'] = $this->M_MasterHpp->get_all();
        $data['title'] = 'Daftar Harga HPP';
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('master_hpp/index', $data);
        $this->load->view('layout/footer');
    }

    // FUNGSI TAMBAH (SIMPAN)
    public function simpan()
    {
        // 1. Data untuk tabel Master HPP
        $data_hpp = [
            'kode_produk' => $this->input->post('kode_produk'),
            'jenis_kaca'   => $this->input->post('jenis_kaca'),
            'warna_kusen'  => $this->input->post('warna_kusen'),
            'ukuran'       => $this->input->post('ukuran'),
            'biaya_kaca'   => $this->input->post('biaya_kaca'),
            'biaya_kusen_lengkung' => $this->input->post('biaya_kusen_lengkung'),
            'biaya_kusen_lurus'    => $this->input->post('biaya_kusen_lurus'),
            'biaya_b_6cm'          => $this->input->post('biaya_b_6cm'),
            'biaya_ornamen' => $this->input->post('biaya_ornamen'),
            'biaya_packing' => $this->input->post('biaya_packing'),
            'biaya_tenaga' => $this->input->post('biaya_tenaga'),
            'total_biaya_modal' => $this->input->post('total_biaya_modal') ?: 0
        ];

        // Simpan ke Master HPP
        $this->db->insert('master_hpp', $data_hpp);

        // 2. OTOMATIS: Buat baris di Inventory jika ukuran belum ada untuk tipe tersebut
        $ukuran = $this->input->post('ukuran');
        $kode_produk = $this->input->post('kode_produk');
        
        // Tentukan tipe berdasarkan kode_produk
        $pivot_codes = ['JBP', 'JBPSK', 'JB2', 'JB2SK'];
        $tipe = in_array($kode_produk, $pivot_codes) ? 'kusen_pivot' : 'kusen_m';
        
        $cek_inventory = $this->db->get_where('inventory', ['ukuran' => $ukuran, 'tipe' => $tipe])->num_rows();

        if ($cek_inventory == 0) {
            $data_inv = [
                'ukuran'      => $ukuran,
                'stok'        => 0,
                'satuan'      => 'pcs',
                'tipe'        => $tipe
            ];
            $this->db->insert('inventory', $data_inv);

            $this->session->set_flashdata('pesan', 'Data HPP & Slot Inventory (' . ucfirst(str_replace('_', ' ', $tipe)) . ') Berhasil Dibuat!');
        } else {
            $this->session->set_flashdata('pesan', 'Data HPP Berhasil Ditambahkan! (Slot Inventory sudah ada)');
        }

        // Catat log
        $this->M_ActivityLog->log(
            'tambah_hpp',
            'Menambahkan data HPP baru: ' . $kode_produk . ' ukuran ' . $ukuran,
            null,
            null,
            $data_hpp
        );

        redirect('MasterHpp');
    }

    // FUNGSI UPDATE (UBAH) via AJAX — tetap di halaman yang sama
    public function ubah()
    {
        // Cek apakah request dari AJAX
        $is_ajax = $this->input->is_ajax_request();

        $id   = $this->input->post('id_hpp');
        $data = $this->_get_input();

        $old_data = $this->db->get_where('master_hpp', ['id_hpp' => $id])->row_array();
        $result = $this->M_MasterHpp->update($id, $data);

        // Catat log
        $this->M_ActivityLog->log(
            'edit_hpp',
            'Mengubah data HPP ID: ' . $id,
            null,
            $old_data,
            $data
        );

        if ($is_ajax) {
            // Kembalikan response JSON untuk AJAX
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => 'success',
                    'message' => 'Data HPP berhasil diperbarui!'
                ]));
        } else {
            // Fallback jika bukan AJAX (form biasa)
            $this->session->set_flashdata('pesan', 'Data HPP berhasil diperbarui!');
            redirect('MasterHpp');
        }
    }

    // FUNGSI DELETE (HAPUS)
    public function hapus($id)
    {
        $cek_orderan = $this->db->get_where('orders', ['id_hpp' => $id])->num_rows();

        if ($cek_orderan > 0) {
            $this->session->set_flashdata('error', 'Gagal Hapus! Data HPP ini sudah dipakai di ' . $cek_orderan . ' pesanan.');
            redirect('MasterHpp');
        } else {
            $old_data = $this->db->get_where('master_hpp', ['id_hpp' => $id])->row_array();
            
            $this->db->where('id_hpp', $id);
            $this->db->delete('master_hpp');

            // Catat log
            $this->M_ActivityLog->log(
                'hapus_hpp',
                'Menghapus data HPP: ' . ($old_data ? $old_data['kode_produk'] . ' ukuran ' . $old_data['ukuran'] : $id),
                null,
                $old_data,
                null
            );

            $this->session->set_flashdata('pesan', 'Data HPP berhasil dihapus!');
            redirect('MasterHpp');
        }
    }

    // Helper hitung total otomatis
    private function _get_input()
    {
        $kaca           = $this->input->post('biaya_kaca');
        $kusen_lengkung = $this->input->post('biaya_kusen_lengkung');
        $kusen_lurus    = $this->input->post('biaya_kusen_lurus');
        $b6cm           = $this->input->post('biaya_b_6cm');
        $ornamen        = $this->input->post('biaya_ornamen');
        $packing        = $this->input->post('biaya_packing');
        $tenaga         = $this->input->post('biaya_tenaga');

        return [
            'kode_produk'          => $this->input->post('kode_produk'),
            'ukuran'               => $this->input->post('ukuran'),
            'jenis_kaca'           => $this->input->post('jenis_kaca'),
            'warna_kusen'          => $this->input->post('warna_kusen'),
            'biaya_kaca'           => $kaca,
            'biaya_kusen_lengkung' => $kusen_lengkung,
            'biaya_kusen_lurus'    => $kusen_lurus,
            'biaya_b_6cm'          => $b6cm,
            'biaya_ornamen'        => $ornamen,
            'biaya_packing'        => $packing,
            'biaya_tenaga'         => $tenaga,
            'total_biaya_modal'    => $kaca + $kusen_lengkung + $kusen_lurus + $b6cm + $ornamen + $packing + $tenaga
        ];
    }
}