<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }
        // Pastikan Model M_Orders sudah di-load
        $this->load->model('M_Orders');

        // Opsional: Tambahkan proteksi login jika ada session
        // if (!$this->session->userdata('role')) { redirect('Auth'); }
    }

    public function index()
    {
        // Ambil semua input dari URL
        $filter = $this->input->get('filter');
        $tgl_mulai_input = $this->input->get('tgl_mulai');
        $tgl_selesai_input = $this->input->get('tgl_selesai');
        $kode_filter = $this->input->get('kode'); // Tambah filter kode_produk

        $tgl_akhir = date('Y-m-d');
        $tgl_awal  = "";

        // LOGIKA PRIORITAS:
        // 1. Cek dulu apakah user isi tanggal manual (Dari - Sampai)
        if (!empty($tgl_mulai_input) && !empty($tgl_selesai_input)) {
            $tgl_awal = $tgl_mulai_input;
            $tgl_akhir = $tgl_selesai_input;
            $filter_aktif = 'custom'; // Paksa status ke custom biar dropdown nyesuain
        }
        // 2. Kalau tanggal kosong, baru cek Shortcut Dropdown
        else {
            $filter_aktif = $filter ?: 'bulan'; // Default bulan jika baru buka

            if ($filter_aktif == 'hari') {
                $tgl_awal = date('Y-m-d');
            } elseif ($filter_aktif == 'minggu') {
                $tgl_awal = date('Y-m-d', strtotime('-7 days'));
            } else {
                // Default Awal Bulan
                $tgl_awal = date('Y-m-01');
            }
        }

        // Ambil data dari Model berdasarkan range yang sudah fix
        $data['laporan'] = $this->M_Orders->get_laporan_profit($tgl_awal, $tgl_akhir, $kode_filter);

        // Ambil list kode_produk untuk dropdown filter
        $data['list_kode'] = $this->db->select('kode_produk')->distinct()->get('master_hpp')->result();

        // Kirim data ke View
        $data['filter_aktif'] = $filter_aktif;
        $data['tgl_awal'] = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $data['kode_aktif'] = $kode_filter; // Kirim kode filter aktif
        $data['title'] = 'Laporan Profit';

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('laporan/penjualan', $data);
        $this->load->view('layout/footer');
    }

    public function get_detail_ukuran()
    {
        $ukuran     = $this->input->get('ukuran');
        $tgl_awal   = $this->input->get('tgl_awal');
        $tgl_akhir  = $this->input->get('tgl_akhir');
        $kode       = $this->input->get('kode');

        $this->db->select('o.no_spk, o.tanggal_order, o.qty, o.ekspedisi, c.nama_c, o.harga_jual_satuan, o.total_harga_jual');
        $this->db->from('orders o');
        $this->db->join('master_hpp h', 'h.id_hpp = o.id_hpp');
        $this->db->join('customers c', 'c.id_cust = o.id_cust');
        $this->db->join('monitoring mon', 'mon.no_spk = o.no_spk');
        $this->db->where_in('mon.status_pengerjaan', ['Diterima', 'Dikirim']);
        $this->db->where('h.ukuran', $ukuran);
        $this->db->where('o.tanggal_order >=', $tgl_awal);
        $this->db->where('o.tanggal_order <=', $tgl_akhir);

        if (!empty($kode)) {
            $this->db->where('h.kode_produk', $kode);
        }

        $this->db->order_by('o.tanggal_order', 'ASC');
        $result = $this->db->get()->result();

        echo json_encode($result);
    }
}
