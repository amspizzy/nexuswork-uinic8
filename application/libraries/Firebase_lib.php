<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Auth;

/**
 * Pembungkus Firebase Admin SDK.
 *
 * Tugasnya hanya dua:
 *   1. Memeriksa Firebase ID token yang dikirim aplikasi mobile.
 *   2. Menyediakan akses ke Firebase Auth bila diperlukan.
 *
 * Berkas kredensial service account TIDAK boleh berada di dalam folder
 * publik. Lokasinya diatur melalui FIREBASE_CREDENTIALS pada .env dan
 * sebaiknya menunjuk ke direktori di luar document root.
 */
class Firebase_lib
{
	/** @var Factory|null */
	protected $factory = NULL;

	/** @var string */
	protected $credentials_path;

	/** @var string */
	protected $project_id;

	/** @var string|null */
	protected $last_error = NULL;

	public function __construct()
	{
		$this->credentials_path = (string) $this->_env('FIREBASE_CREDENTIALS', '');
		$this->project_id       = (string) $this->_env('FIREBASE_PROJECT_ID', '');

		// Path relatif ditafsirkan terhadap folder app/ agar .env cukup
		// memuat nilai pendek seperti "../secrets/firebase.json".
		if ($this->credentials_path !== '' && $this->credentials_path[0] !== '/')
		{
			$this->credentials_path = realpath(dirname(__FILE__, 3).'/'.$this->credentials_path) ?: '';
		}
	}

	/**
	 * Apakah kredensial Firebase sudah tersedia dan terbaca.
	 *
	 * @return bool
	 */
	public function is_configured()
	{
		return $this->credentials_path !== '' && is_file($this->credentials_path) && is_readable($this->credentials_path);
	}

	/**
	 * Pesan kesalahan terakhir, bila ada.
	 *
	 * @return string|null
	 */
	public function last_error()
	{
		return $this->last_error;
	}

	/**
	 * Objek Auth Firebase, atau NULL bila belum dikonfigurasi.
	 *
	 * @return Auth|null
	 */
	public function auth()
	{
		if ( ! $this->is_configured())
		{
			$this->last_error = 'Kredensial Firebase belum tersedia pada server.';
			return NULL;
		}

		if ($this->factory === NULL)
		{
			try
			{
				$builder = (new Factory())->withServiceAccount($this->credentials_path);

				if ($this->project_id !== '')
				{
					$builder = $builder->withProjectId($this->project_id);
				}

				$this->factory = $builder;
			}
			catch (Exception $e)
			{
				$this->last_error = 'Kredensial Firebase tidak dapat dibaca: '.$e->getMessage();
				return NULL;
			}
		}

		return $this->factory->createAuth();
	}

	/**
	 * Objek Realtime Database Firebase.
	 *
	 * @return \Kreait\Firebase\Contract\Database|null
	 */
	public function database()
	{
		if ( ! $this->is_configured())
		{
			$this->last_error = 'Kredensial Firebase belum tersedia pada server.';
			return NULL;
		}

		if ($this->factory === NULL)
		{
			try
			{
				$builder = (new Factory())->withServiceAccount($this->credentials_path);

				if ($this->project_id !== '')
				{
					$builder = $builder->withProjectId($this->project_id);
				}

				$this->factory = $builder;
			}
			catch (Exception $e)
			{
				$this->last_error = 'Kredensial Firebase tidak dapat dibaca: '.$e->getMessage();
				return NULL;
			}
		}

		return $this->factory->createDatabase();
	}

	/**
	 * Sync order status ke Firebase Realtime Database.
	 *
	 * @param string $no_spk
	 * @param array $data
	 * @return bool
	 */
	public function sync_order($no_spk, $data)
	{
		$db = $this->database();

		if ($db === NULL)
		{
			return FALSE;
		}

		try
		{
			$ref = $db->getReference('orders/' . $no_spk);
			$ref->set($data);
			return TRUE;
		}
		catch (Exception $e)
		{
			$this->last_error = 'Gagal sync Firebase: ' . $e->getMessage();
			return FALSE;
		}
	}

	/**
	 * Hapus order dari Firebase.
	 *
	 * @param string $no_spk
	 * @return bool
	 */
	public function delete_order($no_spk)
	{
		$db = $this->database();

		if ($db === NULL)
		{
			return FALSE;
		}

		try
		{
			$ref = $db->getReference('orders/' . $no_spk);
			$ref->remove();
			return TRUE;
		}
		catch (Exception $e)
		{
			$this->last_error = 'Gagal hapus dari Firebase: ' . $e->getMessage();
			return FALSE;
		}
	}

	/**
	 * Periksa Firebase ID token.
	 *
	 * @param string $id_token
	 * @return array Klaim token yang sudah terverifikasi.
	 * @throws RuntimeException bila token tidak sah atau Firebase belum dikonfigurasi.
	 */
	public function verify_id_token($id_token)
	{
		$auth = $this->auth();

		if ($auth === NULL)
		{
			throw new RuntimeException($this->last_error !== NULL
				? $this->last_error
				: 'Kredensial Firebase belum dikonfigurasi.', 503);
		}

		if ( ! is_string($id_token) || $id_token === '')
		{
			throw new RuntimeException('ID token kosong.', 401);
		}

		try
		{
			$verified = $auth->verifyIdToken($id_token);
		}
		catch (\Kreait\Firebase\Exception\Auth\ExpiredIdToken $e)
		{
			throw new RuntimeException('ID token sudah kedaluwarsa. Silakan masuk kembali.', 401, $e);
		}
		catch (\Kreait\Firebase\Exception\Auth\RevokedIdToken $e)
		{
			throw new RuntimeException('Sesi Firebase sudah dicabut.', 401, $e);
		}
		catch (\Kreait\Firebase\Exception\Auth\InvalidIdToken $e)
		{
			throw new RuntimeException('ID token tidak sah.', 401, $e);
		}
		catch (Exception $e)
		{
			throw new RuntimeException('ID token tidak dapat diverifikasi.', 401, $e);
		}

		$claims = $verified->claims();

		return array(
			'uid'            => (string) $claims->get('sub'),
			'email'          => $claims->has('email') ? (string) $claims->get('email') : NULL,
			'email_verified' => $claims->has('email_verified') ? (bool) $claims->get('email_verified') : FALSE,
			'name'           => $claims->has('name') ? (string) $claims->get('name') : NULL,
			'provider'       => $claims->has('firebase') && isset($claims->get('firebase')['sign_in_provider'])
				? (string) $claims->get('firebase')['sign_in_provider']
				: NULL,
			'claims'         => $claims,
		);
	}

	protected function _env($key, $default = NULL)
	{
		$value = getenv($key);

		if ($value === FALSE && isset($_ENV[$key]))
		{
			$value = $_ENV[$key];
		}

		return ($value === FALSE || $value === '') ? $default : $value;
	}
}
