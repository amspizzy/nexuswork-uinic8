<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

/**
 * Penerbit dan pemeriksa JSON Web Token untuk sesi API mobile.
 *
 * Algoritme: HS256 dengan kunci simetris dari JWT_SECRET pada .env.
 * Masa berlaku: JWT_TTL detik (bawaan 3600 = 60 menit).
 *
 * Token ini BUKAN pengganti Firebase ID token. Firebase memverifikasi
 * identitas pengguna; JWT di sini adalah tiket sesi jangka pendek yang
 * diterbitkan backend setelah identitas tersebut terverifikasi.
 */
class Jwt_lib
{
	const ALGORITHM = 'HS256';

	/** @var string */
	protected $secret;

	/** @var int */
	protected $ttl;

	/** @var string */
	protected $issuer;

	public function __construct()
	{
		$this->secret = (string) $this->_env('JWT_SECRET', '');
		$this->ttl    = (int) $this->_env('JWT_TTL', 3600);
		$this->issuer = (string) $this->_env('JWT_ISSUER', 'nexuswork-api');

		if ($this->ttl < 60)
		{
			$this->ttl = 60;
		}
	}

	/**
	 * Apakah kunci sudah dikonfigurasi.
	 *
	 * @return bool
	 */
	public function is_configured()
	{
		return $this->secret !== '' && strlen($this->secret) >= 32;
	}

	/**
	 * Terbitkan token untuk satu pengguna.
	 *
	 * @param array $user  Baris tabel users (id_user, username, role, nama_lengkap).
	 * @param array $extra Klaim tambahan, misalnya 'firebase_uid'.
	 * @return array ['token' => string, 'expires_in' => int, 'expires_at' => int]
	 * @throws RuntimeException bila kunci belum dikonfigurasi.
	 */
	public function issue(array $user, array $extra = array())
	{
		if ( ! $this->is_configured())
		{
			throw new RuntimeException('JWT_SECRET belum diatur pada berkas .env (minimal 32 karakter).');
		}

		$now = time();

		$payload = array(
			'iss'  => $this->issuer,
			'sub'  => (int) $user['id_user'],
			'role' => (string) $user['role'],
			'name' => isset($user['nama_lengkap']) ? (string) $user['nama_lengkap'] : '',
			'usr'  => isset($user['username']) ? (string) $user['username'] : '',
			'iat'  => $now,
			'nbf'  => $now,
			'exp'  => $now + $this->ttl,
			'jti'  => bin2hex(random_bytes(16)),
		);

		foreach ($extra as $key => $value)
		{
			if ( ! array_key_exists($key, $payload) && $value !== NULL)
			{
				$payload[$key] = $value;
			}
		}

		$token = JWT::encode($payload, $this->secret, self::ALGORITHM);

		return array(
			'token'      => $token,
			'expires_in' => $this->ttl,
			'expires_at' => $now + $this->ttl,
		);
	}

	/**
	 * Periksa token dan kembalikan klaimnya.
	 *
	 * @param string $token
	 * @return array Klaim yang sudah terverifikasi.
	 * @throws RuntimeException bila token tidak sah, kedaluwarsa, atau belum berlaku.
	 */
	public function verify($token)
	{
		if ( ! $this->is_configured())
		{
			throw new RuntimeException('JWT_SECRET belum diatur pada berkas .env.');
		}

		if ( ! is_string($token) || $token === '')
		{
			throw new RuntimeException('Token kosong.');
		}

		try
		{
			$claims = (array) JWT::decode($token, new Key($this->secret, self::ALGORITHM));
		}
		catch (ExpiredException $e)
		{
			throw new RuntimeException('Sesi sudah berakhir. Silakan masuk kembali.', 401, $e);
		}
		catch (BeforeValidException $e)
		{
			throw new RuntimeException('Token belum berlaku.', 401, $e);
		}
		catch (SignatureInvalidException $e)
		{
			throw new RuntimeException('Token tidak sah.', 401, $e);
		}
		catch (Exception $e)
		{
			throw new RuntimeException('Token tidak dapat dibaca.', 401, $e);
		}

		if ( ! isset($claims['iss']) || $claims['iss'] !== $this->issuer)
		{
			throw new RuntimeException('Token berasal dari penerbit lain.', 401);
		}

		if ( ! isset($claims['sub'], $claims['role']))
		{
			throw new RuntimeException('Token tidak memuat data pengguna.', 401);
		}

		return $claims;
	}

	/**
	 * Ambil kunci dari .env tanpa bergantung pada CI_Controller,
	 * agar pustaka ini dapat dipakai di luar siklus permintaan
	 * (misalnya dari skrip CLI).
	 */
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
