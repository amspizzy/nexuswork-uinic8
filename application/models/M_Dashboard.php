<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Dashboard extends CI_Model
{

    public function get_count_proses()
    {
        // Mengambil data dari tabel monitoring yang belum selesai
        $this->db->where_in('status_pengerjaan', ['Antri', 'Produksi', 'Packing']);
        return $this->db->count_all_results('monitoring');
    }

    public function get_count_selesai()
    {
        // Mengambil data dari tabel monitoring yang sudah selesai
        $this->db->where('status_pengerjaan', 'Selesai');
        return $this->db->count_all_results('monitoring');
    }

    public function get_total_omzet()
    {
        // Menjumlahkan total_harga dari tabel orders
        $this->db->select_sum('total_harga');
        $query = $this->db->get('orders');
        return $query->row()->total_harga ?? 0;
    }

    public function get_deadline_terdekat()
    {
        // Menggabungkan tabel orders dan monitoring untuk melihat deadline
        $this->db->select('orders.no_spk, orders.deadline, monitoring.status_pengerjaan');
        $this->db->from('orders');
        $this->db->join('monitoring', 'orders.no_spk = monitoring.no_spk');
        $this->db->where('monitoring.status_pengerjaan !=', 'Selesai');
        $this->db->order_by('orders.deadline', 'ASC');
        $this->db->limit(5);
        return $this->db->get()->result();
    }

    public function get_omzet_bulanan()
    {
        // Mengambil total harga per bulan di tahun berjalan
        $this->db->select('MONTH(tanggal_order) as bulan, SUM(total_harga) as total');
        $this->db->where('YEAR(tanggal_order)', date('Y'));
        $this->db->group_by('MONTH(tanggal_order)');
        return $this->db->get('orders')->result();
    }

    public function get_status_produksi()
    {
        // Mengambil jumlah pesanan berdasarkan status
        $this->db->select('status_pengerjaan, COUNT(*) as jumlah');
        $this->db->group_by('status_pengerjaan');
        return $this->db->get('monitoring')->result();
    }
}
