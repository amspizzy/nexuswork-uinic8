<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kelas dasar seluruh controller API.
 *
 * Memuat pustaka yang selalu dibutuhkan, memasang header CORS, dan
 * menyediakan pembantu akses kredensial dari berkas .env.
 *
 * Controller web legacy (Auth, Orders, Dashboard, dan seterusnya) TIDAK
 * mewarisi kelas ini, sehingga perilakunya tidak berubah.
 */
class MY_Controller extends CI_Controller
{
	/**
	 * Klaim JWT hasil verifikasi Api_Auth_Guard.
	 * NULL selama permintaan belum melewati guard.
	 *
	 * @var array|null
	 */
	public $api_claims = NULL;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('api_response');

		// Isi permintaan dinormalkan menjadi array di api_body().
		$this->load->library('form_validation');

		$this->cors();
	}

	/**
	 * Header CORS.
	 *
	 * Aplikasi Flutter berjalan di perangkat asli, bukan di dalam peramban,
	 * sehingga CORS tidak diperlukan untuknya. Header ini disediakan agar
	 * pengujian melalui peramban dan alat seperti Postman tetap berjalan.
	 *
	 * Daftar asal yang diizinkan diatur melalui ALLOWED_ORIGINS pada .env
	 * (dipisahkan koma). Bila dibiarkan kosong, seluruh asal ditolak kecuali
	 * permintaan tanpa Origin.
	 */
	protected function cors()
	{
		$raw = $this->env('ALLOWED_ORIGINS', '');
		$allowed = array_filter(array_map('trim', explode(',', $raw)));
		$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

		if ($origin !== '' && (in_array('*', $allowed, TRUE) || in_array($origin, $allowed, TRUE)))
		{
			header('Access-Control-Allow-Origin: '.$origin);
			header('Vary: Origin');
			header('Access-Control-Allow-Credentials: true');
		}

		header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With');

		if (isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'OPTIONS')
		{
			http_response_code(204);
			exit;
		}
	}

	/**
	 * Baca variabel lingkungan dari .env, dengan nilai cadangan.
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return mixed
	 */
	protected function env($key, $default = NULL)
	{
		$value = getenv($key);

		if ($value === FALSE && isset($_ENV[$key]))
		{
			$value = $_ENV[$key];
		}

		return ($value === FALSE || $value === '') ? $default : $value;
	}

	/**
	 * Metode HTTP dari permintaan saat ini, dalam huruf besar.
	 */
	protected function method()
	{
		return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
	}
}
