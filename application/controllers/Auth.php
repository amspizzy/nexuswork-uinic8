<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function index()
    {
        if ($this->session->userdata('logged_in')) redirect('Dashboard');
        $this->load->view('auth/login');
    }

    public function proses_login()
    {
        $user = $this->input->post('username');
        $pass = $this->input->post('password');

        $cek = $this->db->get_where('users', [
            'username' => $user,
            'password' => md5($pass) 
        ])->row();

        if ($cek) {
            $session = [
                'id_user'   => $cek->id_user,
                'nama_user' => $cek->nama_lengkap,
                'role'      => $cek->role,
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session);
            
            // Catat log login
            $this->load->model('M_ActivityLog');
            $this->M_ActivityLog->log(
                'login',
                'User berhasil login ke sistem',
                null,
                null,
                null
            );

            redirect('Dashboard');
        } else {
            $this->session->set_flashdata('pesan', 'Username atau Password Salah!');
            redirect('Auth');
        }
    }

    public function google_login()
    {
        $this->output->set_content_type('application/json');

        $id_token = trim((string) $this->input->post('id_token'));
        $uid      = trim((string) $this->input->post('uid'));
        $email    = trim((string) $this->input->post('email'));
        $name     = trim((string) $this->input->post('name'));

        if (empty($uid) && empty($id_token)) {
            $this->output->set_status_header(400);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Data otentikasi Google tidak lengkap.'
            ]);
            return;
        }

        // Coba verifikasi dengan Firebase_lib bila kredensial tersedia
        $this->load->library('Firebase_lib');
        if (!empty($id_token) && $this->firebase_lib->is_configured()) {
            try {
                $verified = $this->firebase_lib->verify_id_token($id_token);
                $uid   = $verified['uid'];
                $email = isset($verified['email']) ? $verified['email'] : $email;
                $name  = isset($verified['name']) ? $verified['name'] : $name;
            } catch (Exception $e) {
                log_message('error', 'Firebase verify token error: ' . $e->getMessage());
            }
        }

        if (empty($uid)) {
            $this->output->set_status_header(401);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Identitas Google (UID) tidak ditemukan.'
            ]);
            return;
        }

        // 1. Cari user berdasarkan firebase_uid yang telah dikaitkan sebelumnya
        $user = $this->db->get_where('users', ['firebase_uid' => $uid])->row();

        // 2. Jika tidak ditemukan via firebase_uid, cari via google_email yang telah ditautkan
        if (!$user && !empty($email)) {
            $user = $this->db->get_where('users', ['google_email' => $email])->row();
            if ($user) {
                // Sinkronkan firebase_uid secara otomatis ke user ini
                $this->db->where('id_user', $user->id_user)->update('users', ['firebase_uid' => $uid]);
            }
        }

        // 3. Jika akun Google belum pernah dikaitkan ke pengguna mana pun di sistem:
        //    Sesuai kebijakan keamanan, user HARUS login dulu dengan username & password, lalu menautkannya di dalam web.
        if (!$user) {
            $this->output->set_status_header(403);
            echo json_encode([
                'status'  => 'error',
                'message' => 'Akun Google ini (' . (!empty($email) ? $email : 'Google Account') . ') belum ditautkan ke akun NexusWork Anda. Silakan login terlebih dahulu dengan Username & Password, lalu hubungkan akun Google di menu profil di dalam web.'
            ]);
            return;
        }

        // Perbarui email Google jika kolom tersedia
        if ($this->db->field_exists('google_email', 'users') && !empty($email) && empty($user->google_email)) {
            $this->db->where('id_user', $user->id_user)->update('users', ['google_email' => $email]);
        }

        // Set session login CodeIgniter
        $session = [
            'id_user'   => $user->id_user,
            'nama_user' => $user->nama_lengkap ?: $user->username,
            'role'      => $user->role,
            'logged_in' => TRUE
        ];
        $this->session->set_userdata($session);

        // Catat log activity
        $this->load->model('M_ActivityLog');
        $this->M_ActivityLog->log(
            'login',
            'User berhasil login via Google Sign-In (' . $user->username . ')',
            null,
            null,
            null
        );

        echo json_encode([
            'status'   => 'success',
            'message'  => 'Selamat datang, ' . ($user->nama_lengkap ?: $user->username) . '!',
            'redirect' => site_url('Dashboard')
        ]);
    }

    /**
     * Mengaitkan Akun Google ke Pengguna yang Sedang Login
     */
    public function link_google()
    {
        $this->output->set_content_type('application/json');

        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(['status' => 'error', 'message' => 'Sesi login telah berakhir. Silakan login ulang.']);
            return;
        }

        $cur_id   = $this->session->userdata('id_user');
        $uid      = trim((string) $this->input->post('uid'));
        $email    = trim((string) $this->input->post('email'));
        $id_token = trim((string) $this->input->post('id_token'));

        if (empty($uid)) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Data otentikasi Google (UID) tidak ditemukan.']);
            return;
        }

        // Cek apakah UID Google ini sudah ditautkan ke user LAIN
        $existing = $this->db->get_where('users', ['firebase_uid' => $uid])->row();
        if ($existing && $existing->id_user != $cur_id) {
            $force_transfer = $this->input->post('force_transfer');
            if ($force_transfer === '1' || $force_transfer === 'true' || $force_transfer === true) {
                // Lepaskan tautan dari user lama
                $this->db->where('id_user', $existing->id_user)->update('users', [
                    'firebase_uid' => NULL,
                    'google_email' => NULL
                ]);
            } else {
                $this->output->set_status_header(409);
                echo json_encode([
                    'status'      => 'conflict',
                    'linked_user' => $existing->username,
                    'message'     => 'Akun Google ini saat ini sudah terhubung ke akun pengguna "' . $existing->username . '". Apakah Anda ingin memindahkan tautan akun Google ini ke akun ' . $this->session->userdata('nama_user') . '?'
                ]);
                return;
            }
        }

        // Pastikan kolom google_email ada di tabel users
        if (!$this->db->field_exists('google_email', 'users')) {
            $this->load->dbforge();
            $this->dbforge->add_column('users', [
                'google_email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 120,
                    'null'       => TRUE,
                    'default'    => NULL
                ]
            ]);
        }

        // Simpan tautan Google ke akun user saat ini
        $update_data = ['firebase_uid' => $uid];
        if (!empty($email)) {
            $update_data['google_email'] = $email;
        }

        $this->db->where('id_user', $cur_id)->update('users', $update_data);

        // Catat jejak audit
        $this->load->model('M_ActivityLog');
        $this->M_ActivityLog->log(
            'update_profil',
            'Pengguna berhasil mengaitkan Akun Google (' . ($email ?: $uid) . ') ke akun ' . $this->session->userdata('nama_user'),
            null,
            null,
            null
        );

        echo json_encode([
            'status'  => 'success',
            'message' => 'Akun Google (' . ($email ?: 'Google') . ') berhasil ditautkan! Anda kini dapat login langsung menggunakan tombol Google Sign-In.',
            'email'   => $email
        ]);
    }

    /**
     * Alternatif: Tautkan Akun Menggunakan Alamat Email Google
     */
    public function link_google_email()
    {
        $this->output->set_content_type('application/json');

        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(['status' => 'error', 'message' => 'Sesi login telah berakhir.']);
            return;
        }

        $email = trim(strtolower((string) $this->input->post('email')));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->output->set_status_header(400);
            echo json_encode(['status' => 'error', 'message' => 'Format email Google tidak valid.']);
            return;
        }

        $cur_id = $this->session->userdata('id_user');

        // Pastikan kolom google_email ada di tabel users
        if (!$this->db->field_exists('google_email', 'users')) {
            $this->load->dbforge();
            $this->dbforge->add_column('users', [
                'google_email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 120,
                    'null'       => TRUE,
                    'default'    => NULL
                ]
            ]);
        }

        // Cek jika email sudah terpakai di akun lain
        $existing = $this->db->get_where('users', ['google_email' => $email])->row();
        if ($existing && $existing->id_user != $cur_id) {
            $force = $this->input->post('force_transfer');
            if ($force === '1' || $force === 'true') {
                $this->db->where('id_user', $existing->id_user)->update('users', [
                    'google_email' => NULL,
                    'firebase_uid' => NULL
                ]);
            } else {
                $this->output->set_status_header(409);
                echo json_encode([
                    'status'      => 'conflict',
                    'linked_user' => $existing->username,
                    'message'     => 'Email Google (' . $email . ') saat ini terhubung ke akun ' . $existing->username . '. Pindahkan tautan ke akun Anda?'
                ]);
                return;
            }
        }

        $this->db->where('id_user', $cur_id)->update('users', ['google_email' => $email]);

        $this->load->model('M_ActivityLog');
        $this->M_ActivityLog->log(
            'update_profil',
            'Pengguna mengaitkan email Google (' . $email . ') ke akun ' . $this->session->userdata('nama_user'),
            null,
            null,
            null
        );

        echo json_encode([
            'status'  => 'success',
            'message' => 'Email Google (' . $email . ') berhasil dikaitkan ke akun Anda! Anda sekarang dapat masuk menggunakan tombol Google Sign-In.',
            'email'   => $email
        ]);
    }

    /**
     * Memutuskan Tautan Akun Google dari Pengguna yang Sedang Login
     */
    public function unlink_google()
    {
        $this->output->set_content_type('application/json');

        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(['status' => 'error', 'message' => 'Sesi login telah berakhir.']);
            return;
        }

        $cur_id = $this->session->userdata('id_user');
        $update_data = ['firebase_uid' => NULL];
        if ($this->db->field_exists('google_email', 'users')) {
            $update_data['google_email'] = NULL;
        }

        $this->db->where('id_user', $cur_id)->update('users', $update_data);

        $this->load->model('M_ActivityLog');
        $this->M_ActivityLog->log(
            'update_profil',
            'Pengguna memutuskan tautan Akun Google dari akun ' . $this->session->userdata('nama_user'),
            null,
            null,
            null
        );

        echo json_encode([
            'status'  => 'success',
            'message' => 'Tautan Akun Google berhasil diputuskan dari akun Anda.'
        ]);
    }

    /**
     * Mengambil Status Tautan Google untuk Modal Pengaturan
     */
    public function get_google_status()
    {
        $this->output->set_content_type('application/json');

        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            return;
        }

        $cur_id = $this->session->userdata('id_user');
        $user = $this->db->get_where('users', ['id_user' => $cur_id])->row();

        $linked = !empty($user->firebase_uid);
        $email  = ($user && isset($user->google_email) && !empty($user->google_email)) ? $user->google_email : null;

        echo json_encode([
            'status' => 'success',
            'linked' => $linked,
            'email'  => $email
        ]);
    }

    public function logout()
    {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('M_ActivityLog');
            $this->M_ActivityLog->log(
                'logout',
                'User logout dari sistem',
                null,
                null,
                null
            );
        }

        $this->session->sess_destroy();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        redirect('Auth');
    }
}
