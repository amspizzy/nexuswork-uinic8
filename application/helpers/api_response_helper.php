<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper pembungkus respons JSON untuk lapisan API.
 *
 * Seluruh endpoint di bawah application/controllers/api/ memakai bentuk
 * respons yang sama agar aplikasi mobile hanya perlu satu penangan:
 *
 *   sukses  -> { "success": true,  "message": "...", "data": ..., "meta": {...} }
 *   gagal   -> { "success": false, "message": "...", "errors": {...} }
 *
 * Kode status HTTP tetap dikirim sesuai makna sebenarnya, bukan selalu 200.
 */

if ( ! function_exists('api_response'))
{
	/**
	 * Kirim respons lalu hentikan eksekusi.
	 *
	 * @param mixed  $data    Muatan utama; null bila tidak ada.
	 * @param int    $status  Kode status HTTP.
	 * @param string $message Keterangan singkat yang bisa ditampilkan ke pengguna.
	 * @param array  $meta    Keterangan tambahan (pagination, jumlah, dsb).
	 * @param array  $errors  Rincian kesalahan per bidang, bila ada.
	 * @return void
	 */
	function api_response($data = NULL, $status = 200, $message = 'OK', array $meta = array(), array $errors = array())
	{
		$body = array(
			'success' => ($status >= 200 && $status < 300),
			'message' => $message,
			'data'    => $data,
		);

		if ( ! empty($meta))
		{
			$body['meta'] = $meta;
		}

		if ( ! empty($errors))
		{
			$body['errors'] = $errors;
		}

		$CI =& get_instance();
		$CI->output
			->set_status_header($status)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();

		exit;
	}
}

if ( ! function_exists('api_ok'))
{
	function api_ok($data = NULL, $message = 'OK', array $meta = array())
	{
		api_response($data, 200, $message, $meta);
	}
}

if ( ! function_exists('api_created'))
{
	function api_created($data = NULL, $message = 'Data berhasil dibuat.')
	{
		api_response($data, 201, $message);
	}
}

if ( ! function_exists('api_error'))
{
	/**
	 * @param int         $status
	 * @param string      $message
	 * @param array       $errors
	 */
	function api_error($status, $message, array $errors = array())
	{
		api_response(NULL, $status, $message, array(), $errors);
	}
}

if ( ! function_exists('api_validation_error'))
{
	/**
	 * 422 dengan rincian per bidang, memakai hasil form_validation CI3.
	 */
	function api_validation_error()
	{
		$CI =& get_instance();
		$errors = array();

		foreach ($CI->form_validation->error_array() as $field => $message)
		{
			$errors[$field] = $message;
		}

		api_response(NULL, 422, 'Data yang dikirim belum lengkap atau tidak valid.', array(), $errors);
	}
}

if ( ! function_exists('api_paginate_meta'))
{
	/**
	 * Susun keterangan pagination secara seragam.
	 */
	function api_paginate_meta($total, $page, $per_page)
	{
		$page = max(1, (int) $page);
		$per_page = max(1, (int) $per_page);
		$total = (int) $total;

		return array(
			'page'        => $page,
			'per_page'    => $per_page,
			'total'       => $total,
			'total_pages' => $per_page > 0 ? (int) ceil($total / $per_page) : 0,
		);
	}
}

if ( ! function_exists('api_body'))
{
	/**
	 * Ambil isi permintaan sebagai array, baik dari JSON maupun form biasa.
	 *
	 * Aplikasi mobile mengirim application/json; halaman web mengirim
	 * application/x-www-form-urlencoded. Keduanya dinormalkan di sini.
	 */
	function api_body()
	{
		static $cached = NULL;

		if ($cached !== NULL)
		{
			return $cached;
		}

		$raw = file_get_contents('php://input');
		$decoded = NULL;

		if ($raw !== FALSE && $raw !== '')
		{
			$decoded = json_decode($raw, TRUE);
		}

		if ( ! is_array($decoded))
		{
			$decoded = array();
		}

		// Untuk request form biasa, isi POST tetap diutamakan bila JSON kosong.
		$CI =& get_instance();
		if (empty($decoded) && ! empty($CI->input->post()))
		{
			$decoded = $CI->input->post(NULL, TRUE);
		}

		$cached = $decoded;
		return $cached;
	}
}

if ( ! function_exists('api_input'))
{
	/**
	 * Ambil satu nilai dari api_body() dengan nilai cadangan.
	 */
	function api_input($key, $default = NULL)
	{
		$body = api_body();
		return array_key_exists($key, $body) ? $body[$key] : $default;
	}
}

if ( ! function_exists('api_id_from_token'))
{
	/**
	 * Ambil klaim pengguna yang sudah diverifikasi Api_Auth_Guard.
	 */
	function api_id_from_token($key = NULL, $default = NULL)
	{
		$CI =& get_instance();
		$claims = isset($CI->api_claims) ? $CI->api_claims : NULL;

		if ( ! is_array($claims))
		{
			return $default;
		}

		if ($key === NULL)
		{
			return $claims;
		}

		return array_key_exists($key, $claims) ? $claims[$key] : $default;
	}
}
