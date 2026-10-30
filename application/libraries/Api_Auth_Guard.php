<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Penjaga autentikasi dan otorisasi lapisan API.
 *
 * Controller API memanggil require_auth() di dalam konstruktornya.
 * Bila token tidak sah, eksekusi berhenti dengan respons JSON 401.
 *
 * Controller ini juga menyimpan klaim hasil verifikasi pada
 * $CI->api_claims agar dapat dibaca melalui helper api_id_from_token().
 */
class Api_Auth_Guard
{
	/** @var CI_Controller */
	protected $CI;

	/** @var Jwt_lib */
	protected $jwt;

	public function __construct()
	{
		$this->CI =& get_instance();

		require_once APPPATH.'libraries/Jwt_lib.php';
		$this->jwt = new Jwt_lib();
	}

	/**
	 * Wajib terautentikasi.
	 *
	 * @param array $roles Daftar role yang diizinkan; kosong berarti semua role.
	 * @return array Klaim JWT.
	 */
	public function require_auth(array $roles = array())
	{
		$claims = $this->authenticate();
		$this->require_role($claims, $roles);
		return $claims;
	}

	/**
	 * Ambil dan periksa token, tanpa memeriksa role.
	 *
	 * @return array Klaim JWT.
	 */
	public function authenticate()
	{
		$token = $this->bearer_token();

		if ($token === NULL)
		{
			$this->deny(401, 'Token tidak ditemukan. Sertakan header Authorization: Bearer <token>.');
		}

		try
		{
			$claims = $this->jwt->verify($token);
		}
		catch (RuntimeException $e)
		{
			$status = $e->getCode() >= 400 && $e->getCode() < 600 ? (int) $e->getCode() : 401;
			$this->deny($status, $e->getMessage());
		}

		// Pastikan pengguna masih ada dan rolenya belum berubah sejak token diterbitkan.
		$user = $this->CI->db
			->select('id_user, username, nama_lengkap, role')
			->where('id_user', (int) $claims['sub'])
			->get('users')
			->row_array();

		if (empty($user))
		{
			$this->deny(401, 'Akun tidak ditemukan. Silakan masuk kembali.');
		}

		if ((string) $user['role'] !== (string) $claims['role'])
		{
			$this->deny(401, 'Hak akses berubah. Silakan masuk kembali.');
		}

		$claims['role'] = (string) $user['role'];
		$claims['user'] = $user;

		$this->CI->api_claims = $claims;

		if (isset($this->CI->session))
		{
			$this->CI->session->set_userdata([
				'id_user'   => $user['id_user'],
				'nama_user' => !empty($user['nama_lengkap']) ? $user['nama_lengkap'] : $user['username'],
				'role'      => $user['role'],
			]);
		}

		return $claims;
	}

	/**
	 * Pastikan klaim memiliki salah satu role yang diizinkan.
	 *
	 * @param array $claims
	 * @param array $roles
	 * @return void
	 */
	public function require_role(array $claims, array $roles = array())
	{
		if (empty($roles))
		{
			return;
		}

		$role = isset($claims['role']) ? (string) $claims['role'] : '';

		if ( ! in_array($role, $roles, TRUE))
		{
			$this->deny(403, 'Anda tidak memiliki hak akses untuk tindakan ini.');
		}
	}

	/**
	 * Ambil token dari header Authorization.
	 *
	 * @return string|null
	 */
	protected function bearer_token()
	{
		$header = NULL;

		if (isset($_SERVER['HTTP_AUTHORIZATION']))
		{
			$header = $_SERVER['HTTP_AUTHORIZATION'];
		}
		elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']))
		{
			$header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
		}
		elseif (function_exists('getallheaders'))
		{
			foreach (getallheaders() as $name => $value)
			{
				if (strcasecmp($name, 'Authorization') === 0)
				{
					$header = $value;
					break;
				}
			}
		}

		if ($header === NULL || ! preg_match('/^Bearer\s+(.+)$/i', trim($header), $m))
		{
			return NULL;
		}

		return trim($m[1]);
	}

	/**
	 * Hentikan permintaan dengan respons galat JSON.
	 *
	 * @param int    $status
	 * @param string $message
	 * @return void
	 */
	protected function deny($status, $message)
	{
		api_response(NULL, $status, $message);
	}
}
