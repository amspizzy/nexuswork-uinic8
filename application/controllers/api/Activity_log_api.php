<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Endpoint log aktivitas untuk aplikasi mobile.
 *
 * Semua endpoint membutuhkan Bearer token.
 *
 * GET  /api/activity-log?page=1&per_page=20&no_spk=XXX&username=YYY&aksi=tambah_order
 */
class Activity_log_api extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('Api_Auth_Guard');
		$this->api_auth_guard->require_auth();
	}

	// ---------------------------------------------------------------
	//  LIST activity logs (paginated, filterable)
	// ---------------------------------------------------------------
	public function index()
	{
		$page     = max(1, (int) $this->input->get('page', TRUE));
		$per_page = min(100, max(1, (int) ($this->input->get('per_page', TRUE) ?: 20)));
		$no_spk   = $this->input->get('no_spk', TRUE);
		$username = $this->input->get('username', TRUE);
		$aksi     = $this->input->get('aksi', TRUE);

		// Base query
		$this->db->select('id_log, nama_user, role, aksi, deskripsi, no_spk, created_at');
		$this->db->from('activity_log');

		// Filters
		if ( ! empty($no_spk))
		{
			$this->db->where('no_spk', $no_spk);
		}

		if ( ! empty($username))
		{
			$this->db->like('nama_user', $username);
		}

		if ( ! empty($aksi))
		{
			$this->db->where('aksi', $aksi);
		}

		// Count total
		$total = $this->db->count_all_results('', FALSE);

		// Pagination
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($per_page, ($page - 1) * $per_page);
		$logs = $this->db->get()->result_array();

		api_ok(
			$logs,
			'OK',
			api_paginate_meta($total, $page, $per_page)
		);
	}
}
