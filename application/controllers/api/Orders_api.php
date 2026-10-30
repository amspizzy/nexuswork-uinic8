<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Endpoint pesanan untuk aplikasi mobile.
 *
 * Semua endpoint membutuhkan Bearer token.
 *
 * GET  /api/orders_api/index?page=1&per_page=20&status=Produksi&search=keyword
 * GET  /api/orders_api/detail/{no_spk}
 * POST /api/orders_api/update_status   { no_spk, status_pengerjaan }
 * GET  /api/orders_api/products        (master_hpp dropdown data)
 */
class Orders_api extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('Api_Auth_Guard');
		$this->api_auth_guard->require_auth();

		$this->load->model(['M_Orders', 'M_MasterHpp', 'M_ActivityLog']);
	}

	// ---------------------------------------------------------------
	//  LIST orders (paginated, filterable)
	// ---------------------------------------------------------------
	public function index()
	{
		$page     = max(1, (int) $this->input->get('page', TRUE));
		$per_page = min(100, max(1, (int) ($this->input->get('per_page', TRUE) ?: 20)));
		$status   = $this->input->get('status', TRUE);
		$search   = $this->input->get('search', TRUE);

		// Base query
		$this->db->select('orders.no_spk, orders.tanggal_order, orders.tgl_input,
			orders.qty, orders.hpp_saat_ini, orders.harga_jual_satuan,
			orders.total_harga_jual, orders.total_harga, orders.deadline, orders.ekspedisi,
			customers.nama_c, customers.alamat, customers.no_telp,
			monitoring.status_pengerjaan,
			master_hpp.ukuran, master_hpp.jenis_kaca, master_hpp.warna_kusen, master_hpp.kode_produk');
		$this->db->from('orders');
		$this->db->join('customers', 'customers.id_cust = orders.id_cust');
		$this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp');
		$this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk');

		if ( ! empty($status))
		{
			$this->db->where('monitoring.status_pengerjaan', $status);
		}

		if ( ! empty($search))
		{
			$this->db->group_start();
			$this->db->like('orders.no_spk', $search);
			$this->db->or_like('customers.nama_c', $search);
			$this->db->group_end();
		}

		// Count first (clone not available in CI3, so run count separately)
		$count_db = clone $this->db;
		// CI3 doesn't support clone cleanly — use count trick
		$total = $this->db->count_all_results('', FALSE);

		// Pagination
		$this->db->order_by('orders.tgl_input', 'DESC');
		$this->db->limit($per_page, ($page - 1) * $per_page);
		$orders = $this->db->get()->result_array();

		api_ok(
			$orders,
			'OK',
			api_paginate_meta($total, $page, $per_page)
		);
	}

	// ---------------------------------------------------------------
	//  DETAIL single order
	// ---------------------------------------------------------------
	public function detail($no_spk = NULL)
	{
		if ($no_spk === NULL)
		{
			api_error(400, 'no_spk wajib diisi.');
		}

		$this->db->select('orders.*, customers.nama_c, customers.alamat, customers.no_telp,
			monitoring.status_pengerjaan, monitoring.tgl_update,
			master_hpp.ukuran, master_hpp.jenis_kaca, master_hpp.warna_kusen,
			master_hpp.kode_produk, master_hpp.total_biaya_modal');
		$this->db->from('orders');
		$this->db->join('customers', 'customers.id_cust = orders.id_cust');
		$this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp');
		$this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk', 'left');
		$this->db->where('orders.no_spk', $no_spk);
		$order = $this->db->get()->row_array();

		if (empty($order))
		{
			api_error(404, 'Order tidak ditemukan.');
		}

		api_ok($order, 'OK');
	}

	// ---------------------------------------------------------------
	//  UPDATE STATUS (respects role permissions like web version)
	// ---------------------------------------------------------------
	public function update_status($no_spk = NULL)
	{
		if ($this->method() !== 'POST')
		{
			api_error(405, 'Method not allowed.');
		}

		// Accept no_spk from URL param or body
		if ($no_spk === NULL)
		{
			$no_spk = trim((string) api_input('no_spk'));
		}
		$status_baru = trim((string) api_input('status_pengerjaan'));
		// Also accept 'status' as alias (for mobile)
		if ($status_baru === '')
		{
			$status_baru = trim((string) api_input('status'));
		}
		$role        = api_id_from_token('role');

		if ($no_spk === '' || $status_baru === '')
		{
			api_error(422, 'no_spk dan status_pengerjaan wajib diisi.');
		}

		$valid_statuses = ['Antri', 'Produksi', 'Packing', 'Dikirim', 'Diterima', 'Cancel'];
		if ( ! in_array($status_baru, $valid_statuses, TRUE))
		{
			api_error(422, 'Status tidak valid. Pilihan: ' . implode(', ', $valid_statuses));
		}

		// Ambil monitoring lama
		$monitoring = $this->db->get_where('monitoring', ['no_spk' => $no_spk])->row();
		if (empty($monitoring))
		{
			api_error(404, 'SPK tidak ditemukan di monitoring.');
		}

		$status_lama = $monitoring->status_pengerjaan;
		$status_order = ['Antri' => 1, 'Produksi' => 2, 'Packing' => 3, 'Dikirim' => 4, 'Diterima' => 5, 'Cancel' => 6];
		$level_lama = $status_order[$status_lama] ?? 0;
		$level_baru = $status_order[$status_baru] ?? 0;

		// Role validation (same as web)
		if ($role === 'admin')
		{
			$owner_only = ['Diterima', 'Cancel'];
			if (in_array($status_baru, $owner_only, TRUE))
			{
				api_error(403, 'Status "' . $status_baru . '" hanya bisa diubah oleh Owner.');
			}
			if ($level_baru < $level_lama)
			{
				api_error(403, 'Admin tidak bisa memundurkan status.');
			}
		}
		elseif ($role === 'karyawan')
		{
			$blocked = ['Dikirim', 'Diterima', 'Cancel'];
			if (in_array($status_baru, $blocked, TRUE))
			{
				api_error(403, 'Status "' . $status_baru . '" hanya bisa diubah oleh Admin/Owner.');
			}
			if ($level_baru < $level_lama)
			{
				api_error(403, 'Kamu tidak bisa memundurkan status.');
			}
		}

		// Inventory logic (same as web)
		$order = $this->db->get_where('orders', ['no_spk' => $no_spk])->row();
		$hpp   = $this->db->get_where('master_hpp', ['id_hpp' => $order->id_hpp])->row();

		$pivot_codes = ['JBP', 'JBPSK', 'JB2', 'JB2SK'];
		$jb_codes    = ['JB', 'JBSK'];

		if (in_array($hpp->kode_produk, $pivot_codes))
		{
			$tipe = 'kusen_pivot'; $is_double = TRUE;
		}
		elseif (in_array($hpp->kode_produk, $jb_codes))
		{
			$tipe = 'kusen_m'; $is_double = TRUE;
		}
		else
		{
			$tipe = 'kusen_m'; $is_double = FALSE;
		}

		// Potong stok: dari non-Dikirim ke Dikirim
		if ($status_lama !== 'Dikirim' && $status_baru === 'Dikirim')
		{
			$inv = $this->db->get_where('inventory', ['ukuran' => $hpp->ukuran, 'tipe' => $tipe])->row();
			if ($inv)
			{
				$qty_d = $is_double ? $order->qty * 2 : $order->qty;
				$this->db->where(['ukuran' => $hpp->ukuran, 'tipe' => $tipe]);
				$this->db->set('stok', 'stok - ' . (int) $qty_d, FALSE);
				$this->db->update('inventory');
			}
		}
		// Balikin stok: dari Dikirim ke non-Dikirim
		elseif ($status_lama === 'Dikirim' && $status_baru !== 'Dikirim')
		{
			$inv = $this->db->get_where('inventory', ['ukuran' => $hpp->ukuran, 'tipe' => $tipe])->row();
			if ($inv)
			{
				$qty_a = $is_double ? $order->qty * 2 : $order->qty;
				$this->db->where(['ukuran' => $hpp->ukuran, 'tipe' => $tipe]);
				$this->db->set('stok', 'stok + ' . (int) $qty_a, FALSE);
				$this->db->update('inventory');
			}
		}

		// Update monitoring
		$this->db->where('no_spk', $no_spk);
		$this->db->update('monitoring', [
			'status_pengerjaan' => $status_baru,
			'tgl_update'        => date('Y-m-d H:i:s'),
		]);

		// Log
		$this->M_ActivityLog->log(
			'ubah_status',
			'[API] Status SPK: ' . $no_spk . ' dari ' . $status_lama . ' ke ' . $status_baru,
			$no_spk,
			['status_lama' => $status_lama],
			['status_baru' => $status_baru]
		);

		api_ok([
			'no_spk'             => $no_spk,
			'status_lama'        => $status_lama,
			'status_baru'        => $status_baru,
		], 'Status berhasil diperbarui.');
	}

	// ---------------------------------------------------------------
	//  PRODUCTS — dropdown data for mobile form
	// ---------------------------------------------------------------
	public function products()
	{
		$kode_produk = $this->db->select('kode_produk')->distinct()->get('master_hpp')->result_array();
		$ukuran      = $this->db->select('ukuran')->distinct()->order_by('CAST(ukuran AS UNSIGNED)', 'ASC')->get('master_hpp')->result_array();
		$jenis_kaca  = $this->db->select('jenis_kaca')->distinct()->get('master_hpp')->result_array();
		$warna_kusen = $this->db->select('warna_kusen')->distinct()->get('master_hpp')->result_array();

		api_ok([
			'kode_produk' => array_column($kode_produk, 'kode_produk'),
			'ukuran'      => array_column($ukuran, 'ukuran'),
			'jenis_kaca'  => array_column($jenis_kaca, 'jenis_kaca'),
			'warna_kusen' => array_column($warna_kusen, 'warna_kusen'),
		], 'OK');
	}

	// ---------------------------------------------------------------
	//  Dashboard summary counts
	// ---------------------------------------------------------------
	public function summary()
	{
		$statuses = ['Antri', 'Produksi', 'Packing', 'Dikirim', 'Diterima', 'Cancel'];
		$counts = [];

		foreach ($statuses as $s)
		{
			$counts[$s] = (int) $this->db
				->where('status_pengerjaan', $s)
				->count_all_results('monitoring');
		}

		$counts['total'] = (int) $this->db->count_all('orders');

		api_ok($counts, 'OK');
	}

	// ---------------------------------------------------------------
	//  CREATE order
	// ---------------------------------------------------------------
	public function create()
	{
		if ($this->method() !== 'POST')
		{
			api_error(405, 'Method not allowed.');
		}

		// Get inputs
		$no_spk             = trim((string) api_input('no_spk'));
		$tanggal_order      = trim((string) api_input('tanggal_order'));
		$nama_pelanggan     = trim((string) api_input('nama_pelanggan'));
		$alamat_pelanggan   = trim((string) api_input('alamat_pelanggan'));
		$no_telp            = trim((string) api_input('no_telp'));
		$kota               = trim((string) api_input('kota'));
		$id_hpp             = (int) api_input('id_hpp');
		$qty                = (int) api_input('qty');
		$harga_jual_satuan  = (float) api_input('harga_jual_satuan');
		$hpp_saat_ini       = (float) api_input('hpp_saat_ini');
		$deadline           = trim((string) api_input('deadline'));
		$ekspedisi          = trim((string) api_input('ekspedisi'));

		// Validation
		if ($no_spk === '' || $tanggal_order === '' || $nama_pelanggan === '' || $id_hpp === 0 || $qty <= 0 || $harga_jual_satuan <= 0 || $hpp_saat_ini <= 0)
		{
			api_error(422, 'Data tidak lengkap. Pastikan no_spk, tanggal_order, nama_pelanggan, id_hpp, qty, harga_jual_satuan, dan hpp_saat_ini terisi.');
		}

		// Check duplicate no_spk
		$cek_duplikat = $this->db->get_where('orders', ['no_spk' => $no_spk])->num_rows();
		if ($cek_duplikat > 0)
		{
			api_error(409, 'Nomor SPK ' . $no_spk . ' sudah terdaftar. Gunakan nomor lain.');
		}

		// Insert customer
		$cust_data = [
			'nama_c'  => $nama_pelanggan,
			'alamat'  => $alamat_pelanggan,
			'no_telp' => $no_telp,
			'kota'    => $kota,
		];
		$this->db->insert('customers', $cust_data);
		$id_cust = $this->db->insert_id();

		// Calculate totals
		$total_harga_jual = $qty * $harga_jual_satuan;
		$total_harga      = $qty * $hpp_saat_ini;

		// Insert order
		$order_data = [
			'no_spk'            => $no_spk,
			'tanggal_order'     => $tanggal_order,
			'tgl_input'         => date('Y-m-d H:i:s'),
			'id_cust'           => $id_cust,
			'id_hpp'            => $id_hpp,
			'qty'               => $qty,
			'hpp_saat_ini'      => $hpp_saat_ini,
			'harga_jual_satuan' => $harga_jual_satuan,
			'total_harga_jual'  => $total_harga_jual,
			'deadline'          => $deadline !== '' ? $deadline : NULL,
			'ekspedisi'         => $ekspedisi,
			'total_harga'       => $total_harga,
		];
		$this->db->insert('orders', $order_data);

		// Insert monitoring
		$this->db->insert('monitoring', [
			'no_spk'            => $no_spk,
			'status_pengerjaan' => 'Antri',
		]);

		// Log activity
		$this->M_ActivityLog->log(
			'tambah_order',
			'[API] Menambahkan pesanan baru dengan SPK: ' . $no_spk,
			$no_spk,
			NULL,
			$order_data
		);

		api_created([
			'no_spk'            => $no_spk,
			'id_cust'           => $id_cust,
			'total_harga_jual'  => $total_harga_jual,
			'total_harga'       => $total_harga,
			'status_pengerjaan' => 'Antri',
		], 'Order berhasil dibuat.');
	}

	// ---------------------------------------------------------------
	//  CASCADING DROPDOWN: get ukuran by kode_produk
	// ---------------------------------------------------------------
	public function options_by_kode($kode = NULL)
	{
		if ($kode === NULL)
		{
			api_error(400, 'Parameter kode_produk wajib diisi.');
		}

		$this->db->select('ukuran')->distinct()->where('kode_produk', $kode)->order_by('CAST(ukuran AS UNSIGNED)', 'ASC');
		$ukuran = $this->db->get('master_hpp')->result_array();

		api_ok([
			'ukuran' => array_column($ukuran, 'ukuran'),
		], 'OK');
	}

	// ---------------------------------------------------------------
	//  CASCADING DROPDOWN: get jenis_kaca by kode_produk + ukuran
	// ---------------------------------------------------------------
	public function options_by_kode_ukuran($kode = NULL, $ukuran = NULL)
	{
		if ($kode === NULL || $ukuran === NULL)
		{
			api_error(400, 'Parameter kode_produk dan ukuran wajib diisi.');
		}

		$this->db->select('jenis_kaca')->distinct()->where('kode_produk', $kode)->where('ukuran', $ukuran);
		$kaca = $this->db->get('master_hpp')->result_array();

		api_ok([
			'jenis_kaca' => array_column($kaca, 'jenis_kaca'),
		], 'OK');
	}

	// ---------------------------------------------------------------
	//  CASCADING DROPDOWN: get warna_kusen by kode+ukuran+kaca
	// ---------------------------------------------------------------
	public function options_by_kode_ukuran_kaca($kode = NULL, $ukuran = NULL, $kaca = NULL)
	{
		if ($kode === NULL || $ukuran === NULL || $kaca === NULL)
		{
			api_error(400, 'Parameter kode_produk, ukuran, dan jenis_kaca wajib diisi.');
		}

		$this->db->select('warna_kusen')->distinct()->where('kode_produk', $kode)->where('ukuran', $ukuran)->where('jenis_kaca', $kaca);
		$warna = $this->db->get('master_hpp')->result_array();

		api_ok([
			'warna_kusen' => array_column($warna, 'warna_kusen'),
		], 'OK');
	}

	// ---------------------------------------------------------------
	//  GET HPP by full params
	// ---------------------------------------------------------------
	public function get_hpp($kode = NULL, $ukuran = NULL, $kaca = NULL, $warna = NULL)
	{
		if ($kode === NULL || $ukuran === NULL || $kaca === NULL || $warna === NULL)
		{
			api_error(400, 'Parameter kode_produk, ukuran, jenis_kaca, dan warna_kusen wajib diisi.');
		}

		$res = $this->db->get_where('master_hpp', [
			'kode_produk' => $kode,
			'ukuran'      => $ukuran,
			'jenis_kaca'  => $kaca,
			'warna_kusen' => $warna,
		])->row_array();

		if (empty($res))
		{
			api_error(404, 'HPP tidak ditemukan dengan kombinasi parameter tersebut.');
		}

		api_ok($res, 'OK');
	}

	// ---------------------------------------------------------------
	//  GET Invoice data (full detail for PDF generation)
	// ---------------------------------------------------------------
	public function invoice($no_spk = NULL)
	{
		if ($no_spk === NULL)
		{
			api_error(400, 'no_spk wajib diisi.');
		}

		// Get order with full details
		$this->db->select('orders.*, 
			customers.nama_c, customers.alamat, customers.no_telp, customers.kota,
			monitoring.status_pengerjaan, monitoring.tgl_update,
			master_hpp.ukuran, master_hpp.jenis_kaca, master_hpp.warna_kusen,
			master_hpp.kode_produk, master_hpp.total_biaya_modal as hpp_harga');
		$this->db->from('orders');
		$this->db->join('customers', 'customers.id_cust = orders.id_cust');
		$this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp');
		$this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk', 'left');
		$this->db->where('orders.no_spk', $no_spk);
		$order = $this->db->get()->row_array();

		if (empty($order))
		{
			api_error(404, 'Order tidak ditemukan.');
		}

		// Company info (hardcoded - bisa dipindah ke config)
		$company = [
			'name' => 'NexusWork',
			'address' => 'Jl. Raya NexusWork No. 123, Jakarta',
			'phone' => '021-1234567',
			'email' => 'info@nexuswork.com',
		];

		api_ok([
			'order' => $order,
			'company' => $company,
		], 'Invoice data retrieved successfully');
	}
}
