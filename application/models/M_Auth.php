<?php
class M_Auth extends CI_Model {
    public function get_user($username) {
        return $this->db->get_where('users', ['username' => $username])->row();
    }
}