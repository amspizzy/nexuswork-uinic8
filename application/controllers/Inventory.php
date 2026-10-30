<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        $role = $this->session->userdata('role');
        if ($role == 'karyawan') {
            $this->session->set_flashdata('error', 'Akses Gudang khusus untuk Admin & Owner!');
            redirect('Dashboard');
        }

        $this->load->model(['M_Inventory', 'M_ActivityLog']);
    }
    public function index()
    {
        $tipe_filter = $this->input->get('tipe') ?: 'kusen_m'; // Default ke kusen_m
        if ($tipe_filter && in_array($tipe_filter, ['kusen_m', 'kusen_pivot'])) {
            $data['inventory'] = $this->M_Inventory->get_by_tipe($tipe_filter);
        } else {
            $data['inventory'] = $this->M_Inventory->get_all();
        }
        $data['current_filter'] = $tipe_filter;

        $data['title'] = 'Stok Gudang';
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('inventory/index', $data);
        $this->load->view('layout/footer');
    }

    public function tambah_stok()
    {
        $id     = $this->input->post('id_inv');
        $jumlah = (int) $this->input->post('jumlah_tambah');

        // Validasi: jumlah harus minimal 1
        if ($jumlah < 1) {
            $this->session->set_flashdata('error', 'Jumlah stok tidak valid. Masukkan angka minimal 1.');
            redirect('Inventory');
            return;
        }

        if ($this->M_Inventory->update_stok($id, $jumlah, '+')) {
            // Ambil data inventory untuk notifikasi
            $inv = $this->db->get_where('inventory', ['id_inv' => $id])->row();
            
            // Catat log
            $this->M_ActivityLog->log(
                'tambah_stok',
                'Menambahkan stok ' . ($inv ? $inv->ukuran . ' CM' : 'ID: ' . $id) . ' sebanyak ' . $jumlah,
                null,
                null,
                ['id_inv' => $id, 'jumlah_tambah' => $jumlah]
            );

            if ($inv) {
                $this->session->set_flashdata('pesan', 'Stok ' . $inv->ukuran . ' CM berhasil ditambahkan sebanyak ' . $jumlah . ' ' . $inv->satuan);
            } else {
                $this->session->set_flashdata('pesan', 'Stok berhasil ditambahkan sebanyak ' . $jumlah . ' unit');
            }
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan stok');
        }
        redirect('Inventory');
    }

    public function kurangi_stok()
    {
        $id     = $this->input->post('id_inv');
        $jumlah = (int) $this->input->post('jumlah_kurangi');

        // Validasi: jumlah harus minimal 1
        if ($jumlah < 1) {
            $this->session->set_flashdata('error', 'Jumlah tidak valid. Masukkan angka minimal 1.');
            redirect('Inventory');
            return;
        }

        if ($this->M_Inventory->update_stok($id, $jumlah, '-')) {
            // Ambil data inventory untuk notifikasi
            $inv = $this->db->get_where('inventory', ['id_inv' => $id])->row();

            // Catat log
            $this->M_ActivityLog->log(
                'kurangi_stok',
                'Mengurangi stok ' . ($inv ? $inv->ukuran . ' CM' : 'ID: ' . $id) . ' sebanyak ' . $jumlah,
                null,
                null,
                ['id_inv' => $id, 'jumlah_kurangi' => $jumlah]
            );

            if ($inv) {
                $this->session->set_flashdata('pesan', 'Stok ' . $inv->ukuran . ' CM berhasil dikurangi sebanyak ' . $jumlah . ' ' . $inv->satuan . '. Sisa stok: ' . $inv->stok);
            } else {
                $this->session->set_flashdata('pesan', 'Stok berhasil dikurangi sebanyak ' . $jumlah . ' unit');
            }
        } else {
            $this->session->set_flashdata('error', 'Gagal mengurangi stok');
        }
        redirect('Inventory');
    }
}
