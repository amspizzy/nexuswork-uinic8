<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Inventory extends CI_Model {

    protected $table = 'inventory';

    public function __construct() {
        parent::__construct();
    }

    // Ambil semua data stok
    public function get_all() {
        $this->db->order_by('ukuran', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Ambil data stok berdasarkan tipe
    public function get_by_tipe($tipe) {
        $this->db->order_by('ukuran', 'ASC');
        return $this->db->get_where($this->table, ['tipe' => $tipe])->result();
    }

    // Ambil satu data barang berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_inv' => $id])->row();
    }

    // Fungsi Update Stok (Bisa buat Tambah atau Kurang)
    // $operator bisa diisi '+' atau '-'
    public function update_stok($id, $qty, $operator = '+') {
        $this->db->set('stok', "stok $operator $qty", FALSE);
        $this->db->where('id_inv', $id);
        return $this->db->update($this->table);
    }
    
    // Cari barang berdasarkan spesifikasi (Untuk otomatisasi potong stok)
    public function find_item($kaca, $ukuran) {
        return $this->db->get_where($this->table, [
            'jenis_kaca' => $kaca,
            'ukuran' => $ukuran
        ])->row();
    }
}