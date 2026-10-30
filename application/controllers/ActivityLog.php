<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ActivityLog extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        $role = $this->session->userdata('role');
        if ($role != 'owner') {
            $this->session->set_flashdata('error', 'Akses Ditolak! Fitur Activity Log khusus Owner.');
            redirect('Dashboard');
        }
        $this->load->model('M_ActivityLog');
    }

    public function index()
    {
        $data['title'] = 'Activity Log';
        
        // Setup pagination and filter
        $filter_aksi = $this->input->get('aksi');
        $tgl_mulai = $this->input->get('tgl_mulai');
        $tgl_selesai = $this->input->get('tgl_selesai');

        $data['logs'] = $this->M_ActivityLog->get_all(0, 0, $filter_aksi, $tgl_mulai, $tgl_selesai);
        $data['filter_aksi'] = $filter_aksi;
        $data['tgl_mulai'] = $tgl_mulai;
        $data['tgl_selesai'] = $tgl_selesai;

        // Tipe aksi yang tersedia untuk filter
        $data['list_aksi'] = [
            'tambah_order' => 'Tambah Order',
            'edit_order' => 'Edit Order',
            'hapus_order' => 'Hapus Order',
            'ubah_status' => 'Ubah Status',
            'update_harga' => 'Update Harga Final',
            'tambah_hpp' => 'Tambah HPP',
            'edit_hpp' => 'Edit HPP',
            'hapus_hpp' => 'Hapus HPP',
            'tambah_stok' => 'Tambah Stok',
            'login' => 'Login',
            'logout' => 'Logout'
        ];

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('activity_log/index', $data);
        $this->load->view('layout/footer');
    }
}
