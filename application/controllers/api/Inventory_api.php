<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Endpoint inventory untuk aplikasi mobile.
 *
 * GET  /api/v1/inventory?tipe=kusen_m&ukuran=80&page=1&per_page=50
 * POST /api/v1/inventory/{id_inv}/stok   { delta: int, keterangan: string }
 */
class Inventory_api extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('Api_Auth_Guard');
		$this->api_auth_guard->require_auth();

		$this->load->model('M_ActivityLog');
	}

	// ---------------------------------------------------------------
	//  LIST inventory (paginated, filterable)
	// ---------------------------------------------------------------
	public function index()
	{
		$page     = max(1, (int) $this->input->get('page', TRUE));
		$per_page = min(100, max(1, (int) ($this->input->get('per_page', TRUE) ?: 50)));
		$tipe     = $this->input->get('tipe', TRUE);
		$ukuran   = $this->input->get('ukuran', TRUE);

		$this->db->from('inventory');

		if ( ! empty($tipe))
		{
			$this->db->where('tipe', $tipe);
		}

		if ( ! empty($ukuran))
		{
			$this->db->where('ukuran', (int) $ukuran);
		}

		$total = $this->db->count_all_results('', FALSE);

		$this->db->order_by('tipe', 'ASC');
		$this->db->order_by('ukuran', 'ASC');
		$this->db->limit($per_page, ($page - 1) * $per_page);
		$items = $this->db->get()->result_array();

		api_ok(
			$items,
			'OK',
			api_paginate_meta($total, $page, $per_page)
		);
	}

	// ---------------------------------------------------------------
	//  ADJUST STOCK (owner / admin only)
	// ---------------------------------------------------------------
	public function stok($id_inv = NULL)
	{
		if ($this->method() !== 'POST')
		{
			api_error(405, 'Method not allowed.');
		}

		$role = api_id_from_token('role');
		if ( ! in_array($role, ['owner', 'admin'], TRUE))
		{
			api_error(403, 'Hanya Owner atau Admin yang dapat menyesuaikan stok.');
		}

		if ($id_inv === NULL)
		{
			api_error(400, 'id_inv wajib diisi.');
		}

		$delta      = (int) api_input('delta', 0);
		$keterangan = trim((string) api_input('keterangan', ''));

		if ($delta === 0)
		{
			api_error(422, 'Nilai delta tidak boleh 0.');
		}

		// Fetch current record
		$inv = $this->db->get_where('inventory', ['id_inv' => (int) $id_inv])->row();
		if (empty($inv))
		{
			api_error(404, 'Item inventory tidak ditemukan.');
		}

		// Validate stock won't go negative (R-04)
		$stok_baru = (int) $inv->stok + $delta;
		if ($stok_baru < 0)
		{
			api_error(422, 'Stok tidak mencukupi. Stok saat ini: ' . $inv->stok . ', pengurangan: ' . abs($delta) . '.');
		}

		// Update with atomic expression to prevent race condition
		$this->db->where('id_inv', (int) $id_inv);
		$this->db->set('stok', 'stok + (' . $delta . ')', FALSE);
		$this->db->update('inventory');

		// Log activity
		$aksi = $delta > 0 ? 'tambah_stok' : 'kurangi_stok';
		$desc = '[API] ' . ($delta > 0 ? 'Tambah' : 'Kurangi') . ' stok '
			. $inv->tipe . ' ukuran ' . $inv->ukuran
			. ' sebanyak ' . abs($delta) . ' ' . $inv->satuan;

		if ($keterangan !== '')
		{
			$desc .= '. Keterangan: ' . $keterangan;
		}

		$this->M_ActivityLog->log(
			$aksi,
			$desc,
			NULL,
			['stok_lama' => (int) $inv->stok],
			['stok_baru' => $stok_baru]
		);

		api_ok([
			'id_inv'    => (int) $inv->id_inv,
			'tipe'      => $inv->tipe,
			'ukuran'    => (int) $inv->ukuran,
			'stok_lama' => (int) $inv->stok,
			'stok_baru' => $stok_baru,
			'delta'     => $delta,
		], 'Stok berhasil diperbarui.');
	}
}
