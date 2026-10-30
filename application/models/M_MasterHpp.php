<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_MasterHpp extends CI_Model
{

    // Nama tabel di database
    protected $table = 'master_hpp';

    // 1. Mengambil semua data untuk ditampilkan di tabel utama
    public function get_all()
    {
        $this->db->order_by('CAST(ukuran AS UNSIGNED)', 'ASC'); // Urutkan berdasarkan ukuran terkecil
        return $this->db->get($this->table)->result();
    }

    // 2. Mengambil satu data spesifik (digunakan jika perlu validasi)
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_hpp' => $id])->row();
    }

    // 3. Fungsi Simpan Data Baru
    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    // 4. Fungsi Update Data (Berdasarkan id_hpp)
    public function update($id, $data)
    {
        $this->db->where('id_hpp', $id);
        return $this->db->update($this->table, $data);
    }

    // 5. Fungsi Hapus Data
    public function delete($id)
    {
        $this->db->where('id_hpp', $id);
        return $this->db->delete($this->table);
    }
}
