<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_ActivityLog extends CI_Model
{
    public function log($aksi, $deskripsi, $no_spk = NULL, $data_lama = NULL, $data_baru = NULL)
    {
        date_default_timezone_set('Asia/Jakarta');
        $data = [
            'id_user'   => $this->session->userdata('id_user') ?: 0,
            'nama_user' => $this->session->userdata('nama_user') ?: 'System',
            'role'      => $this->session->userdata('role') ?: 'karyawan',
            'aksi'      => $aksi,
            'deskripsi' => $deskripsi,
            'no_spk'    => $no_spk,
            'data_lama' => $data_lama ? json_encode($data_lama) : NULL,
            'data_baru' => $data_baru ? json_encode($data_baru) : NULL,
            'created_at'=> date('Y-m-d H:i:s')
        ];
        return $this->db->insert('activity_log', $data);
    }

    public function get_all($limit, $offset, $filter_aksi = '', $tgl_mulai = '', $tgl_akhir = '')
    {
        $this->db->select('*');
        $this->db->from('activity_log');
        
        if (!empty($filter_aksi)) {
            $this->db->where('aksi', $filter_aksi);
        }
        
        if (!empty($tgl_mulai) && !empty($tgl_akhir)) {
            $this->db->where('DATE(created_at) >=', $tgl_mulai);
            $this->db->where('DATE(created_at) <=', $tgl_akhir);
        }

        $this->db->order_by('created_at', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        return $this->db->get()->result();
    }

    public function count_all($filter_aksi = '', $tgl_mulai = '', $tgl_akhir = '')
    {
        if (!empty($filter_aksi)) {
            $this->db->where('aksi', $filter_aksi);
        }
        
        if (!empty($tgl_mulai) && !empty($tgl_akhir)) {
            $this->db->where('DATE(created_at) >=', $tgl_mulai);
            $this->db->where('DATE(created_at) <=', $tgl_akhir);
        }

        return $this->db->count_all_results('activity_log');
    }
}
