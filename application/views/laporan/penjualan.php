<?php
    $summary_modal = 0;
    $summary_profit = 0;
    $summary_terjual = 0;
    foreach ($laporan as $r) {
        $summary_modal += $r->total_modal;
        $summary_profit += $r->total_profit;
        $summary_terjual += $r->total_terjual;
    }
    $summary_omzet = $summary_modal + $summary_profit;
    $margin_pct = ($summary_omzet > 0) ? round(($summary_profit / $summary_omzet) * 100, 1) : 0;
?>

<div class="container-fluid">
    <!-- Page Header (Screen only) -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 no-print">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                Laporan Penjualan &amp; Profitabilitas
            </h1>
            <p class="text-muted small mb-0">Analisis margin keuntungan, omzet penjualan, dan realisasi biaya pokok produksi (HPP).</p>
        </div>
        <button onclick="window.print()" class="btn btn-primary btn-sm shadow-sm mt-3 mt-sm-0">
            Cetak Laporan Resmi
        </button>
    </div>

    <!-- KPI Summary Cards (Screen only) -->
    <div class="row mb-4 no-print">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card accent-primary h-100 py-3">
                <div class="metric-label mb-1">Estimasi Omzet Kotor</div>
                <div class="h5 font-weight-bold text-dark mb-1">Rp <?= number_format($summary_omzet, 0, ',', '.'); ?></div>
                <div class="small text-muted font-weight-bold">Modal + Profit</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card accent-warning h-100 py-3">
                <div class="metric-label mb-1">Total Realisasi HPP</div>
                <div class="h5 font-weight-bold text-dark mb-1">Rp <?= number_format($summary_modal, 0, ',', '.'); ?></div>
                <div class="small text-muted font-weight-bold">Akumulasi Biaya Bahan &amp; Tenaga</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card accent-success h-100 py-3">
                <div class="metric-label mb-1">Keuntungan Bersih (Profit)</div>
                <div class="h5 font-weight-bold text-success mb-1">Rp <?= number_format($summary_profit, 0, ',', '.'); ?></div>
                <div class="metric-trend up">Margin: <?= $margin_pct ?>%</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="metric-card accent-info h-100 py-3">
                <div class="metric-label mb-1">Total Volume Terjual</div>
                <div class="h5 font-weight-bold text-dark mb-1"><?= number_format($summary_terjual); ?> PCS</div>
                <div class="small text-muted font-weight-bold">Unit Terverifikasi Diterima</div>
            </div>
        </div>
    </div>

    <!-- KOP LAPORAN: hanya muncul saat dicetak -->
    <div class="print-only kop-laporan" style="display: none;">
        <img src="<?= base_url('assets/img/app-logo.png'); ?>" alt="Logo IPMS" class="kop-logo" style="height: 52px; width: 52px; object-fit: contain;">
        <div class="kop-text">
            <h2>IPMS &bull; INDUSTRIAL FABRICATION ECOSYSTEM</h2>
            <p>Sistem Manajemen Produksi & Integrasi Operasional Bengkel Fabrikasi</p>
            <p>UINIC 8.0 Web Development Competition &bull; Official Financial Report</p>
        </div>
    </div>
    <div class="print-only" style="display: none;">
        <div class="text-center judul-laporan">
            <h3>LAPORAN PENJUALAN, HPP &amp; PROFITABILITAS</h3>
            <p>
                Periode: <?= date('d/m/Y', strtotime($tgl_awal)); ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)); ?>
                <?php if (!empty($kode_aktif)): ?> | Kode Barang: <?= $kode_aktif; ?><?php else: ?> | Seluruh Kode Produk<?php endif; ?>
                &bull; Dicetak: <?= date('d/m/Y H:i:s'); ?>
            </p>
        </div>
    </div>

    <!-- Filter Card (Screen only) -->
    <div class="card shadow-sm mb-4 no-print">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Parameter Filter Periode &amp; Produk
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= base_url('Laporan'); ?>" class="form-row align-items-end">
                <div class="form-group col-md-3 mb-2">
                    <label class="font-weight-bold text-dark small">Pilihan Periode:</label>
                    <select name="filter" id="filter" class="form-control" onchange="resetTanggalDanSubmit(this)">
                        <option value="bulan" <?= ($filter_aktif == 'bulan') ? 'selected' : ''; ?>>Bulan Ini</option>
                        <option value="hari" <?= ($filter_aktif == 'hari') ? 'selected' : ''; ?>>Hari Ini</option>
                        <option value="minggu" <?= ($filter_aktif == 'minggu') ? 'selected' : ''; ?>>7 Hari Terakhir</option>
                        <option value="custom" <?= ($filter_aktif == 'custom') ? 'selected' : ''; ?>>Rentang Kustom</option>
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="font-weight-bold text-dark small">Kode Produk:</label>
                    <select name="kode" class="form-control">
                        <option value="">Semua Kode</option>
                        <?php foreach ($list_kode as $kd): ?>
                            <option value="<?= $kd->kode_produk; ?>" <?= ($kode_aktif == $kd->kode_produk) ? 'selected' : ''; ?>>
                                <?= $kd->kode_produk; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="font-weight-bold text-dark small">Tanggal Mulai:</label>
                    <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control" value="<?= $tgl_awal; ?>">
                </div>
                <div class="form-group col-md-2 mb-2">
                    <label class="font-weight-bold text-dark small">Tanggal Akhir:</label>
                    <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" value="<?= $tgl_akhir; ?>">
                </div>
                <div class="form-group col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary btn-block mr-1">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center no-print">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Data Penjualan: <?= date('d/m/Y', strtotime($tgl_awal)); ?> &ndash; <?= date('d/m/Y', strtotime($tgl_akhir)); ?>
                <?php if (!empty($kode_aktif)): ?> &bull; Kode: <span class="badge badge-soft-primary"><?= $kode_aktif; ?></span><?php endif; ?>
            </h6>
            <span class="small text-muted">Klik badge terjual untuk rincian SPK</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-center">
                            <th width="8%">Ukuran</th>
                            <th>Kaca</th>
                            <th>Kusen Lkg</th>
                            <th>Kusen Lrs</th>
                            <th>B 6cm</th>
                            <th>Ornamen</th>
                            <th>Packing</th>
                            <th>Tenaga</th>
                            <th class="bg-warning text-dark font-weight-bold">Modal HPP</th>
                            <th class="bg-success text-white font-weight-bold">Profit</th>
                            <th>Terjual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_all_kaca           = 0;
                        $total_all_kusen_lengkung = 0;
                        $total_all_kusen_lurus    = 0;
                        $total_all_b_6cm          = 0;
                        $total_all_ornamen        = 0;
                        $total_all_packing        = 0;
                        $total_all_tenaga         = 0;
                        $grand_modal              = 0;
                        $grand_profit             = 0;
                        $grand_terjual            = 0;

                        foreach ($laporan as $row):
                            $total_all_kaca           += $row->total_kaca;
                            $total_all_kusen_lengkung += $row->total_kusen_lengkung;
                            $total_all_kusen_lurus    += $row->total_kusen_lurus;
                            $total_all_b_6cm          += $row->total_b_6cm;
                            $total_all_ornamen        += $row->total_ornamen;
                            $total_all_packing        += $row->total_packing;
                            $total_all_tenaga         += $row->total_tenaga;
                            $grand_modal              += $row->total_modal;
                            $grand_profit             += $row->total_profit;
                            $grand_terjual            += $row->total_terjual;
                        ?>
                            <tr>
                                <td class="text-center font-weight-bold text-dark"><?= $row->ukuran; ?> CM</td>
                                <td class="text-right small"><?= number_format($row->total_kaca, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($row->total_kusen_lengkung, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($row->total_kusen_lurus, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($row->total_b_6cm, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($row->total_ornamen, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($row->total_packing, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($row->total_tenaga, 0, ',', '.'); ?></td>

                                <td class="text-right font-weight-bold text-dark" style="background-color: #fffbeb;">
                                    <?= number_format($row->total_modal, 0, ',', '.'); ?>
                                </td>
                                <td class="text-right font-weight-bold text-success" style="background-color: #ecfdf5;">
                                    <?= number_format($row->total_profit, 0, ',', '.'); ?>
                                </td>
                                <td class="text-center font-weight-bold">
                                    <span class="badge badge-soft-primary p-2 badge-terjual" style="cursor:pointer;"
                                        data-ukuran="<?= $row->ukuran ?>"
                                        data-tgl-awal="<?= $tgl_awal ?>"
                                        data-tgl-akhir="<?= $tgl_akhir ?>"
                                        data-kode="<?= $kode_aktif ?>"
                                        title="Klik untuk rincian SPK">
                                        <i class="fas fa-search mr-1"></i><?= $row->total_terjual ?> PCS
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-light font-weight-bold text-dark">
                        <tr>
                            <td class="text-center">TOTAL</td>
                            <td class="text-right small"><?= number_format($total_all_kaca, 0, ',', '.'); ?></td>
                            <td class="text-right small"><?= number_format($total_all_kusen_lengkung, 0, ',', '.'); ?></td>
                            <td class="text-right small"><?= number_format($total_all_kusen_lurus, 0, ',', '.'); ?></td>
                            <td class="text-right small"><?= number_format($total_all_b_6cm, 0, ',', '.'); ?></td>
                            <td class="text-right small"><?= number_format($total_all_ornamen, 0, ',', '.'); ?></td>
                            <td class="text-right small"><?= number_format($total_all_packing, 0, ',', '.'); ?></td>
                            <td class="text-right small"><?= number_format($total_all_tenaga, 0, ',', '.'); ?></td>

                            <td class="text-right font-weight-bold text-warning" style="background-color: #fef3c7;">
                                Rp <?= number_format($grand_modal, 0, ',', '.'); ?>
                            </td>
                            <td class="text-right font-weight-bold text-success" style="background-color: #d1fae5;">
                                Rp <?= number_format($grand_profit, 0, ',', '.'); ?>
                            </td>
                            <td class="text-center font-weight-bold text-primary">
                                <?= $grand_terjual; ?> PCS
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- VALIDASI / TANDA TANGAN: hanya muncul saat dicetak -->
    <div class="print-only validasi-laporan" style="display: none;">
        <p class="tanggal-cetak">Yogyakarta, <?= date('d F Y'); ?></p>
        <div class="kolom-validasi">
            <div class="kolom-ttd">
                <p>Dibuat oleh,</p>
                <div class="ruang-ttd"></div>
                <p class="garis-nama font-weight-bold">( <?= $this->session->userdata('nama_user') ?: 'Staff Admin' ?> )</p>
                <p>Bagian Administrasi & Logistik</p>
            </div>
            <div class="kolom-ttd">
                <p>Mengetahui &amp; Menyetujui,</p>
                <div class="ruang-ttd"></div>
                <p class="garis-nama font-weight-bold">( Owner / Pimpinan )</p>
                <p>Kepala Operasional Bengkel</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Transaksi per Ukuran -->
<div class="modal fade" id="modalDetailUkuran" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalDetailLabel">
                    Detail Transaksi SPK
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light small">
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th>No. SPK</th>
                                <th>Tgl Order</th>
                                <th>Pelanggan</th>
                                <th class="text-center">Qty</th>
                                <th>Resi / Ekspedisi</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-detail-body">
                            <tr><td colspan="6" class="text-center text-muted p-4">Memuat data transaksi...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print,
        .sidebar,
        .topbar,
        #sidebarToggle,
        .btn {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        body {
            font-size: 11px;
            background: #ffffff !important;
        }

        .container-fluid {
            width: 100% !important;
            padding: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table thead th {
            background-color: #0f172a !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact;
        }
    }

    @page {
        size: landscape;
        margin: 1.2cm;
    }

    .kop-laporan {
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 2px solid #0f172a;
        padding-bottom: 12px;
        margin-bottom: 8px;
    }

    .kop-logo {
        height: 60px;
        width: 60px;
        object-fit: contain;
    }

    .kop-text h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 0.05em;
        color: #0f172a;
    }

    .kop-text p {
        margin: 2px 0;
        font-size: 11px;
        color: #475569;
    }

    .judul-laporan h3 {
        margin: 12px 0 3px;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.05em;
    }

    .judul-laporan p {
        margin: 0 0 14px;
        font-size: 11px;
        color: #64748b;
    }

    .validasi-laporan {
        margin-top: 30px;
    }

    .tanggal-cetak {
        text-align: right;
        margin-bottom: 8px;
        font-size: 11px;
    }

    .kolom-validasi {
        display: flex;
        justify-content: space-between;
        padding: 0 50px;
    }

    .kolom-ttd {
        text-align: center;
        font-size: 11px;
        width: 220px;
    }

    .ruang-ttd {
        height: 65px;
    }

    .garis-nama {
        border-top: 1px solid #334155;
        padding-top: 4px;
        margin-bottom: 2px;
    }
</style>

<script>
    function resetTanggalDanSubmit(select) {
        if (select.value !== 'custom') {
            document.getElementById('tgl_mulai').value = '';
            document.getElementById('tgl_selesai').value = '';
        }
        select.form.submit();
    }

    window.addEventListener('load', function () {
        $(document).on('click', '.badge-terjual', function () {
            var ukuran   = $(this).data('ukuran');
            var tglAwal  = $(this).data('tgl-awal');
            var tglAkhir = $(this).data('tgl-akhir');
            var kode     = $(this).data('kode');

            $('#modalDetailUkuran .modal-title').html('<i class="fas fa-list-alt text-primary mr-2"></i> Detail Transaksi &bull; Ukuran ' + ukuran + ' CM');
            $('#tabel-detail-body').html('<tr><td colspan="6" class="text-center p-4"><i class="fas fa-spinner fa-spin fa-2x text-primary mb-2 d-block"></i>Memuat transaksi...</td></tr>');
            $('#modalDetailUkuran').modal('show');

            $.ajax({
                url: '<?= base_url('Laporan/get_detail_ukuran') ?>',
                method: 'GET',
                data: { ukuran: ukuran, tgl_awal: tglAwal, tgl_akhir: tglAkhir, kode: kode },
                success: function (res) {
                    var data = typeof res === 'string' ? JSON.parse(res) : res;
                    var html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="6" class="text-center text-muted p-4">Tidak ada data transaksi untuk ukuran ini</td></tr>';
                    } else {
                        $.each(data, function (i, d) {
                            var tgl = new Date(d.tanggal_order).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'});
                            var resi = d.ekspedisi && d.ekspedisi.trim() !== '' ? d.ekspedisi : '<span class="text-muted">-</span>';
                            html += '<tr>';
                            html += '<td class="text-center font-weight-bold text-muted">' + (i + 1) + '</td>';
                            html += '<td><span class="badge badge-light border text-monospace font-weight-bold">' + d.no_spk + '</span></td>';
                            html += '<td>' + tgl + '</td>';
                            html += '<td class="font-weight-bold text-dark">' + d.nama_c + '</td>';
                            html += '<td class="text-center"><span class="badge badge-soft-primary font-weight-bold">' + d.qty + ' PCS</span></td>';
                            html += '<td><i class="fas fa-truck text-muted mr-1"></i>' + resi + '</td>';
                            html += '</tr>';
                        });
                    }
                    $('#tabel-detail-body').html(html);
                },
                error: function (xhr) {
                    $('#tabel-detail-body').html('<tr><td colspan="6" class="text-center text-danger p-4"><i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>Gagal memuat data (Error: ' + xhr.status + ')</td></tr>');
                }
            });
        });
    });
</script>
