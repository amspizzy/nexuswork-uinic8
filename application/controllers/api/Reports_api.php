<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Endpoint laporan profit untuk aplikasi mobile.
 *
 * GET  /api/v1/reports/profit?dari=2026-01-01&sampai=2026-12-31
 *
 * Hanya role owner yang dapat mengakses. Perhitungan:
 *   Omset     = SUM(total_harga_jual) dari pesanan berstatus 'Diterima'
 *   Modal     = SUM(hpp_saat_ini * qty)
 *   Profit    = Omset - Modal
 *   Margin(%) = (Profit / Omset) * 100
 */
class Reports_api extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('Api_Auth_Guard');
		$this->api_auth_guard->require_auth();
	}

	// ---------------------------------------------------------------
	//  PROFIT REPORT (owner only)
	// ---------------------------------------------------------------
	public function profit()
	{
		$role = api_id_from_token('role');
		if ($role !== 'owner')
		{
			api_error(403, 'Laporan profit hanya dapat diakses oleh Owner.');
		}

		$dari   = $this->input->get('dari', TRUE);
		$sampai = $this->input->get('sampai', TRUE);

		// --- Summary ---
		$this->db->select('
			COUNT(orders.no_spk) AS total_order,
			COALESCE(SUM(orders.total_harga_jual), 0) AS omset,
			COALESCE(SUM(orders.hpp_saat_ini * orders.qty), 0) AS modal
		');
		$this->db->from('orders');
		$this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk');
		$this->db->where('monitoring.status_pengerjaan', 'Diterima');

		if ( ! empty($dari))
		{
			$this->db->where('orders.tanggal_order >=', $dari);
		}
		if ( ! empty($sampai))
		{
			$this->db->where('orders.tanggal_order <=', $sampai);
		}

		$summary_row = $this->db->get()->row_array();

		$omset  = (float) $summary_row['omset'];
		$modal  = (float) $summary_row['modal'];
		$profit = $omset - $modal;
		$margin = $omset > 0 ? round(($profit / $omset) * 100, 2) : 0;

		$summary = [
			'total_order' => (int) $summary_row['total_order'],
			'omset'       => $omset,
			'modal'       => $modal,
			'profit'      => $profit,
			'margin_pct'  => $margin,
		];

		// --- Per-ukuran breakdown ---
		$this->db->select('
			master_hpp.ukuran,
			COUNT(orders.no_spk) AS total_order,
			COALESCE(SUM(orders.total_harga_jual), 0) AS omset,
			COALESCE(SUM(orders.hpp_saat_ini * orders.qty), 0) AS modal
		');
		$this->db->from('orders');
		$this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk');
		$this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp');
		$this->db->where('monitoring.status_pengerjaan', 'Diterima');

		if ( ! empty($dari))
		{
			$this->db->where('orders.tanggal_order >=', $dari);
		}
		if ( ! empty($sampai))
		{
			$this->db->where('orders.tanggal_order <=', $sampai);
		}

		$this->db->group_by('master_hpp.ukuran');
		$this->db->order_by('master_hpp.ukuran', 'ASC');
		$breakdown_rows = $this->db->get()->result_array();

		$breakdown = [];
		foreach ($breakdown_rows as $row)
		{
			$o = (float) $row['omset'];
			$m = (float) $row['modal'];
			$p = $o - $m;

			$breakdown[] = [
				'ukuran'      => $row['ukuran'],
				'total_order' => (int) $row['total_order'],
				'omset'       => $o,
				'modal'       => $m,
				'profit'      => $p,
				'margin_pct'  => $o > 0 ? round(($p / $o) * 100, 2) : 0,
			];
		}

		// --- Per-month trend (last 12 months if no filter) ---
		$this->db->select("
			DATE_FORMAT(orders.tanggal_order, '%Y-%m') AS bulan,
			COALESCE(SUM(orders.total_harga_jual), 0) AS omset,
			COALESCE(SUM(orders.hpp_saat_ini * orders.qty), 0) AS modal
		");
		$this->db->from('orders');
		$this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk');
		$this->db->where('monitoring.status_pengerjaan', 'Diterima');

		if ( ! empty($dari))
		{
			$this->db->where('orders.tanggal_order >=', $dari);
		}
		else
		{
			// Default: 12 bulan terakhir
			$this->db->where('orders.tanggal_order >=', date('Y-m-d', strtotime('-12 months')));
		}
		if ( ! empty($sampai))
		{
			$this->db->where('orders.tanggal_order <=', $sampai);
		}

		$this->db->group_by("DATE_FORMAT(orders.tanggal_order, '%Y-%m')");
		$this->db->order_by("bulan", 'ASC');
		$trend_rows = $this->db->get()->result_array();

		$trend = [];
		foreach ($trend_rows as $row)
		{
			$o = (float) $row['omset'];
			$m = (float) $row['modal'];
			$trend[] = [
				'bulan'  => $row['bulan'],
				'omset'  => $o,
				'modal'  => $m,
				'profit' => $o - $m,
			];
		}

		api_ok([
			'summary'   => $summary,
			'breakdown' => $breakdown,
			'trend'     => $trend,
			'filter'    => [
				'dari'   => $dari ?: null,
				'sampai' => $sampai ?: null,
			],
		], 'OK');
	}
}
