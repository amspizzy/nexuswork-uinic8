<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Orders extends CI_Model
{

    protected $table = 'orders';

    public function __construct()
    {
        parent::__construct();
    }

    // Ambil semua order + Join ke Customer & HPP biar muncul namanya
    public function get_all()
    {
        $this->db->select('orders.*, customers.nama_c, master_hpp.ukuran, master_hpp.jenis_kaca');
        $this->db->from($this->table);
        $this->db->join('customers', 'customers.id_cust = orders.id_cust');
        $this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp');
        $this->db->order_by('orders.tgl_input', 'DESC');
        return $this->db->get()->result();
    }

    // Simpan Order Baru
    public function simpan($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // Ambil list customer untuk dropdown di form
    public function get_customers()
    {
        return $this->db->get('customers')->result();
    }

    public function get_laporan_profit($tgl_awal, $tgl_akhir, $kode_filter = null)
    {
        $this->db->select('
        m.kode_produk,
        m.ukuran, 
        SUM(IFNULL(o.qty, 0) * m.biaya_kaca) as total_kaca,
        SUM(IFNULL(o.qty, 0) * m.biaya_kusen_lengkung) as total_kusen_lengkung,
        SUM(IFNULL(o.qty, 0) * m.biaya_kusen_lurus) as total_kusen_lurus,
        SUM(IFNULL(o.qty, 0) * m.biaya_b_6cm) as total_b_6cm,
        SUM(IFNULL(o.qty, 0) * m.biaya_ornamen) as total_ornamen,
        SUM(IFNULL(o.qty, 0) * m.biaya_packing) as total_packing,
        SUM(IFNULL(o.qty, 0) * m.biaya_tenaga) as total_tenaga,
        SUM(IFNULL(o.hpp_saat_ini * o.qty, 0)) as total_modal, -- TAMBAH INI
        SUM(IFNULL((o.harga_jual_satuan - o.hpp_saat_ini) * o.qty, 0)) as total_profit,
        SUM(IFNULL(o.qty, 0)) as total_terjual
    ');
        $this->db->from('master_hpp m');

        $this->db->join('orders o', "o.id_hpp = m.id_hpp 
        AND o.tanggal_order >= '$tgl_awal' 
        AND o.tanggal_order <= '$tgl_akhir'", 'left');

        $this->db->join('monitoring mon', 'mon.no_spk = o.no_spk AND mon.status_pengerjaan IN ("Diterima", "Dikirim")', 'left');

        // Pastikan hanya hitung order yang statusnya Diterima atau Dikirim
        $this->db->where_in('mon.status_pengerjaan', ['Diterima', 'Dikirim']);

        // Tambah filter kode_produk jika ada
        if (!empty($kode_filter)) {
            $this->db->where('m.kode_produk', $kode_filter);
        }

        $this->db->group_by('m.ukuran');
        $this->db->order_by('CAST(m.ukuran AS UNSIGNED)', 'ASC');

        return $this->db->get()->result();
    }
}
