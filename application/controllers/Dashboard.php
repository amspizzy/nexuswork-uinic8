<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        if ($this->session->userdata('role') == 'karyawan') {
        redirect('Orders/list'); // Sesuaikan dengan route monitoring kamu
    }

        $this->load->model('M_Dashboard');
    }

    public function index()
    {

        // Cek jumlah data di monitoring secara aman
        $data['total_proses']  = $this->db->where('status_pengerjaan !=', 'Diterima')->from('monitoring')->count_all_results() ?: 0;
        $data['total_selesai'] = $this->db->where('status_pengerjaan', 'Diterima')->from('monitoring')->count_all_results() ?: 0;

        // Hitung Omzet dengan proteksi nilai NULL
        $this->db->select('SUM(harga_jual_satuan * qty) as total');
        $this->db->from('orders o');
        $this->db->join('monitoring m', 'o.no_spk = m.no_spk');
        $this->db->where('m.status_pengerjaan', 'Diterima');
        $res_omzet = $this->db->get()->row();
        $data['total_omzet'] = ($res_omzet && $res_omzet->total) ? $res_omzet->total : 0;

        // Data Area Chart - Tren Omzet
        $omzet_bulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $this->db->select('SUM(o.harga_jual_satuan * o.qty) as total');
            $this->db->from('orders o');
            $this->db->join('monitoring m', 'o.no_spk = m.no_spk', 'left');
            $this->db->where('m.status_pengerjaan', 'Diterima');
            $this->db->where('MONTH(o.tanggal_order)', $i);
            $this->db->where('YEAR(o.tanggal_order)', date('Y'));
            $val = $this->db->get()->row();
            $omzet_bulanan[] = ($val && $val->total) ? (int)$val->total : 0;
        }
        $data['chart_area'] = json_encode($omzet_bulanan);

        // Data Pie Chart - Status Produksi
        $this->db->select('status_pengerjaan, COUNT(*) as jumlah');
        $this->db->group_by('status_pengerjaan');
        $status_data = $this->db->get('monitoring')->result();

        $labels = [];
        $counts = [];

        if (!empty($status_data)) {
            foreach ($status_data as $sd) {
                $labels[] = $sd->status_pengerjaan;
                $counts[] = (int)$sd->jumlah;
            }
        } else {

            $labels = ["Belum Ada Data"];
            $counts = [1];
        }

        $data['pie_labels'] = json_encode($labels);
        $data['pie_counts'] = json_encode($counts);

        // 6 Pesanan terbaru untuk tabel ringkasan dashboard (gaya Minimalist File List)
        $this->db->select('orders.*, customers.nama_c, monitoring.status_pengerjaan, master_hpp.kode_produk');
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id_cust = orders.id_cust', 'left');
        $this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp', 'left');
        $this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk', 'left');
        $this->db->order_by('orders.tanggal_order', 'DESC');
        $this->db->limit(6);
        $data['recent_orders'] = $this->db->get()->result();

        $data['title'] = 'Dashboard';
        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('dashboard/index', $data);
        $this->load->view('layout/footer');
    }
}
