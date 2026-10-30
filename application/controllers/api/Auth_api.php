<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Endpoint autentikasi untuk aplikasi mobile.
 *
 * POST /api/auth_api/login
 *   body: { "username": "...", "password": "..." }
 *   resp: { success, message, data: { user, token, expires_in, expires_at } }
 *
 * POST /api/auth_api/me   (butuh Bearer token)
 *   resp: { success, message, data: { user } }
 */
class Auth_api extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		require_once APPPATH.'libraries/Jwt_lib.php';
		$this->jwt = new Jwt_lib();
	}

	/**
	 * Login: support 2 mode
	 * 1. Username + password (md5 legacy)
	 * 2. id_token (Firebase ID Token) -> tukar ke JWT backend
	 */
	public function login()
	{
		if ($this->method() !== 'POST')
		{
			api_error(405, 'Method not allowed.');
		}

		$id_token = trim((string) api_input('id_token'));
		$username = trim((string) api_input('username'));
		$password = trim((string) api_input('password'));

		// Mode 1: Firebase ID Token
		if ($id_token !== '')
		{
			$this->load->library('Firebase_lib');

			try
			{
				$verified = $this->firebase_lib->verify_id_token($id_token);
			}
			catch (RuntimeException $e)
			{
				$code = $e->getCode() ?: 401;
				api_error($code, $e->getMessage());
			}

			$uid = $verified['uid'];

			// Sub-mode 1A: Link account on-the-fly if username & password also provided
			if ($username !== '' && $password !== '')
			{
				$user = $this->db
					->select('id_user, username, nama_lengkap, role, firebase_uid')
					->where('username', $username)
					->where('password', md5($password))
					->get('users')
					->row_array();

				if (empty($user))
				{
					api_error(401, 'Username atau password salah untuk menghubungkan akun Google.');
				}

				// Update firebase_uid
				$this->db->where('id_user', $user['id_user'])->update('users', ['firebase_uid' => $uid]);
				$user['firebase_uid'] = $uid;

				$token_data = $this->jwt->issue($user);

				api_ok(array(
					'user'       => $user,
					'token'      => $token_data['token'],
					'expires_in' => $token_data['expires_in'],
					'expires_at' => $token_data['expires_at'],
				), 'Akun Google berhasil dihubungkan dan login berhasil.');
			}

			// Sub-mode 1B: Cari user berdasarkan firebase_uid yang sudah terhubung
			$user = $this->db
				->select('id_user, username, nama_lengkap, role, firebase_uid')
				->where('firebase_uid', $uid)
				->get('users')
				->row_array();

			if (empty($user))
			{
				api_error(403, 'Akun Google ini belum terhubung dengan akun NexusWork. Silakan hubungkan akun terlebih dahulu.');
			}

			$token_data = $this->jwt->issue($user);

			api_ok(array(
				'user'       => $user,
				'token'      => $token_data['token'],
				'expires_in' => $token_data['expires_in'],
				'expires_at' => $token_data['expires_at'],
			), 'Login Google berhasil.');
		}

		// Mode 2: Standard Username + Password (md5)
		if ($username === '' || $password === '')
		{
			api_error(422, 'Username dan password wajib diisi (atau sediakan id_token).', array(
				'username' => $username === '' ? 'Wajib diisi.' : NULL,
				'password' => $password === '' ? 'Wajib diisi.' : NULL,
			));
		}

		// Cari user — cocokkan password md5 (legacy)
		$user = $this->db
			->select('id_user, username, nama_lengkap, role, firebase_uid')
			->where('username', $username)
			->where('password', md5($password))
			->get('users')
			->row_array();

		if (empty($user))
		{
			api_error(401, 'Username atau password salah.');
		}

		// Terbitkan JWT
		$token_data = $this->jwt->issue($user);

		api_ok(array(
			'user'       => $user,
			'token'      => $token_data['token'],
			'expires_in' => $token_data['expires_in'],
			'expires_at' => $token_data['expires_at'],
		), 'Login berhasil.');
	}

	/**
	 * Profil user yang sedang login (butuh token).
	 */
	public function me()
	{
		if ($this->method() !== 'POST' && $this->method() !== 'GET')
		{
			api_error(405, 'Method not allowed.');
		}

		// Guard
		$this->load->library('Api_Auth_Guard');
		$claims = $this->api_auth_guard->require_auth();

		api_ok(array(
			'user' => $claims['user'],
		), 'OK');
	}

	/**
	 * Refresh token — terbitkan token baru selama token lama masih valid.
	 */
	public function refresh()
	{
		if ($this->method() !== 'POST')
		{
			api_error(405, 'Method not allowed.');
		}

		$this->load->library('Api_Auth_Guard');
		$claims = $this->api_auth_guard->require_auth();

		$token_data = $this->jwt->issue($claims['user']);

		api_ok(array(
			'token'      => $token_data['token'],
			'expires_in' => $token_data['expires_in'],
			'expires_at' => $token_data['expires_at'],
		), 'Token diperbarui.');
	}
}
