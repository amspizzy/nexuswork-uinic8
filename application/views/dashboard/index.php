<div class="container-fluid">

    <!-- Top Header Title -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 mt-2">
        <div>
            <h1 class="dash-header-title mb-0">Dashboard Ringkasan</h1>
            <div class="small text-muted mt-1">Sistem Manajemen Produksi Terintegrasi Bengkel Fabrikasi</div>
        </div>
        <div class="d-flex align-items-center mt-2 mt-sm-0">
            <span class="badge badge-light px-3 py-2 border rounded-pill font-weight-bold text-secondary mr-2" style="font-size: 0.78rem;">
                <?= date('l, d F Y'); ?>
            </span>
            <span class="badge badge-pill badge-primary px-3 py-2 font-weight-bold" style="font-size: 0.78rem; background: #0d63d8;">
                Realtime Sync
            </span>
        </div>
    </div>

    <!-- QUICK ACCESS: Minimalist Folder Cards Grid (Mirrors Reference Mockup) -->
    <div class="quick-access-heading">
        <span>QUICK ACCESS</span>
        <a href="<?= site_url('Orders/list'); ?>" class="text-muted text-decoration-none small font-weight-bold">
            Semua SPK &rarr;
        </a>
    </div>

    <?php
        $total_all = $total_proses + $total_selesai;
        $rate = ($total_all > 0) ? round(($total_selesai / $total_all) * 100, 1) : 0;
    ?>
    <div class="row mb-4">
        <!-- Card 1: Active Cobalt Blue Folder (Matches First Folder in Mockup) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card folder-active-blue">
                <div>
                    <div class="folder-card-tag">DALAM PENGERJAAN</div>
                    <div class="mb-3">
                        <span class="folder-meta-pill text-white" style="background: rgba(255,255,255,0.18); font-size: 0.72rem; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; font-weight: 600;">
                            Lantai Fabrikasi
                        </span>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">SPK Proses Aktif</div>
                    <div class="folder-card-val"><?= number_format($total_proses); ?> <span style="font-size: 0.82rem; font-weight: 600; opacity: 0.85;">SPK</span></div>
                </div>
            </div>
        </div>

        <!-- Card 2: Clean Off-White Folder (Pesanan Selesai) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card">
                <div>
                    <div class="folder-card-tag">STATUS SELESAI</div>
                    <div class="mb-3">
                        <span class="folder-meta-pill" style="background: #ecfdf5; color: #059669; font-size: 0.72rem; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; font-weight: 600;">
                            Telah Selesai
                        </span>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">Telah Diterima</div>
                    <div class="folder-card-val"><?= number_format($total_selesai); ?> <span style="font-size: 0.82rem; font-weight: 600; color: #64748b;">SPK</span></div>
                </div>
            </div>
        </div>

        <!-- Card 3: Clean Off-White Folder (Total Omzet) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card">
                <div>
                    <div class="folder-card-tag">OMZET BERSIH</div>
                    <div class="mb-3">
                        <span class="folder-meta-pill" style="background: #e0f2fe; color: #0284c7; font-size: 0.72rem; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; font-weight: 600;">
                            Terverifikasi
                        </span>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">Pendapatan Tuntas</div>
                    <div class="folder-card-val" style="font-size: 1.25rem;">Rp <?= number_format($total_omzet, 0, ',', '.'); ?></div>
                </div>
            </div>
        </div>

        <!-- Card 4: Clean Off-White Folder (Efisiensi Produksi) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card">
                <div>
                    <div class="folder-card-tag">EFISIENSI BENGKEL</div>
                    <div class="progress mt-1 mb-3" style="height: 6px; border-radius: 999px; background: #f1f5f9;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= min(100, $rate); ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">Rasio Penyelesaian</div>
                    <div class="folder-card-val"><?= $rate; ?>% <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">(<?= $total_selesai ?> / <?= $total_all ?>)</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALL FILES / SPK TERBARU TABLE (Mirrors Reference Mockup Table) -->
    <div class="minimal-table-card">
        <div class="minimal-table-heading">
            <span>DAFTAR SPK TERBARU</span>
            <a href="<?= site_url('Orders/list'); ?>" class="text-muted text-decoration-none small font-weight-bold">
                Lihat Monitoring Realtime &rarr;
            </a>
        </div>
        <div class="table-responsive">
            <table class="minimal-table">
                <thead>
                    <tr>
                        <th>Nama SPK &amp; Produk</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Order</th>
                        <th>Status Pengerjaan</th>
                        <th>Nilai Pesanan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_orders)): ?>
                        <?php 
                        $icons = [
                            ['class' => 'doc-blue', 'icon' => 'far fa-file-alt'],
                            ['class' => 'sheet-green', 'icon' => 'far fa-file-excel'],
                            ['class' => 'slide-orange', 'icon' => 'far fa-file-powerpoint'],
                            ['class' => 'pdf-red', 'icon' => 'far fa-file-pdf'],
                        ];
                        $i = 0;
                        foreach ($recent_orders as $o): 
                            $ic = $icons[$i % count($icons)];
                            $i++;
                            $st = strtolower($o->status_pengerjaan ?: 'antri');
                            $pill_class = 'pill-antri';
                            if ($st == 'produksi') $pill_class = 'pill-produksi';
                            elseif ($st == 'packing') $pill_class = 'pill-packing';
                            elseif ($st == 'dikirim') $pill_class = 'pill-dikirim';
                            elseif ($st == 'diterima') $pill_class = 'pill-diterima';
                        ?>
                        <tr>
                            <td>
                                <div class="font-weight-bold text-dark"><?= $o->no_spk; ?></div>
                                <div class="small text-muted"><?= $o->kode_produk ?: 'Item Fabrikasi'; ?> &bull; <?= $o->qty; ?> Unit</div>
                            </td>
                            <td>
                                <span class="font-weight-600 text-dark"><?= $o->nama_c ?: 'Pelanggan Umum'; ?></span>
                            </td>
                            <td class="text-muted small">
                                <?= !empty($o->tanggal_order) ? date('d M Y', strtotime($o->tanggal_order)) : '-'; ?>
                            </td>
                            <td>
                                <span class="status-pill <?= $pill_class; ?>">
                                    <span class="status-pill-dot"></span>
                                    <?= $o->status_pengerjaan ?: 'Antri'; ?>
                                </span>
                            </td>
                            <td class="font-weight-bold text-dark">
                                Rp <?= number_format($o->total_harga_jual ?: ($o->harga_jual_satuan * $o->qty), 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('Orders/list'); ?>" class="btn-row-action" title="Lihat Detail SPK">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data pesanan SPK.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Production Workflow Pipeline Tracker -->
    <div class="pipeline-stepper mb-4">
        <div class="pipeline-step <?= ($total_proses > 0) ? 'active' : ''; ?>">
            <div class="pipeline-step-badge" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">1</div>
            <div class="pipeline-step-info">
                <span class="pipeline-step-title">Antri</span>
                <span class="pipeline-step-count">SPK Masuk</span>
            </div>
        </div>
        <div class="pipeline-arrow"><i class="fas fa-chevron-right"></i></div>

        <div class="pipeline-step <?= ($total_proses > 0) ? 'active' : ''; ?>">
            <div class="pipeline-step-badge" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6;">2</div>
            <div class="pipeline-step-info">
                <span class="pipeline-step-title">Produksi</span>
                <span class="pipeline-step-count">Fabrikasi</span>
            </div>
        </div>
        <div class="pipeline-arrow"><i class="fas fa-chevron-right"></i></div>

        <div class="pipeline-step">
            <div class="pipeline-step-badge" style="background: rgba(139, 92, 246, 0.15); color: #8b5cf6;">3</div>
            <div class="pipeline-step-info">
                <span class="pipeline-step-title">Packing</span>
                <span class="pipeline-step-count">QC &amp; Box</span>
            </div>
        </div>
        <div class="pipeline-arrow"><i class="fas fa-chevron-right"></i></div>

        <div class="pipeline-step">
            <div class="pipeline-step-badge" style="background: rgba(6, 182, 212, 0.15); color: #06b6d4;">4</div>
            <div class="pipeline-step-info">
                <span class="pipeline-step-title">Dikirim</span>
                <span class="pipeline-step-count">Ekspedisi</span>
            </div>
        </div>
        <div class="pipeline-arrow"><i class="fas fa-chevron-right"></i></div>

        <div class="pipeline-step active">
            <div class="pipeline-step-badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">5</div>
            <div class="pipeline-step-info">
                <span class="pipeline-step-title">Diterima</span>
                <span class="pipeline-step-count"><?= $total_selesai ?> Selesai</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <!-- Area Chart: Tren Omzet Bulanan -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card h-100" style="border-radius: 20px; border: 1px solid #eef2f6;">
                <div class="card-header d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="m-0 font-weight-bold text-gray-900">
                            Tren Omzet Bulanan (<?= date('Y'); ?>)
                        </h6>
                        <small class="text-muted">Grafik perolehan transaksi order berstatus diterima per bulan</small>
                    </div>
                    <span class="badge badge-soft-primary">
                        Realtime Sync
                    </span>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px; position: relative;">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doughnut Chart: Status Beban Kerja Produksi -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card h-100" style="border-radius: 20px; border: 1px solid #eef2f6;">
                <div class="card-header d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <h6 class="m-0 font-weight-bold text-gray-900">
                            Status Produksi Aktif
                        </h6>
                        <small class="text-muted">Proporsi beban kerja bengkel saat ini</small>
                    </div>
                    <span class="badge badge-soft-success">Monitoring</span>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="chart-pie pt-2 pb-2" style="height: 230px; position: relative;">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-3 pt-3 border-top text-center">
                        <div class="row no-gutters text-center small">
                            <div class="col">
                                <span class="d-block font-weight-bold" style="color: #f59e0b;">&bull; Antri</span>
                            </div>
                            <div class="col">
                                <span class="d-block font-weight-bold" style="color: #3b82f6;">&bull; Produksi</span>
                            </div>
                            <div class="col">
                                <span class="d-block font-weight-bold" style="color: #8b5cf6;">&bull; Packing</span>
                            </div>
                            <div class="col">
                                <span class="d-block font-weight-bold" style="color: #06b6d4;">&bull; Dikirim</span>
                            </div>
                            <div class="col">
                                <span class="d-block font-weight-bold" style="color: #10b981;">&bull; Diterima</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js Plugin -->
<script src="<?= base_url('assets/vendor/chart.js/Chart.min.js'); ?>"></script>
<script>
    // Modern Area Chart Logic
    var ctxArea = document.getElementById("myAreaChart");
    if (ctxArea) {
        var gradient = ctxArea.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

        var myAreaChart = new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"],
                datasets: [{
                    label: "Omzet",
                    lineTension: 0.35,
                    backgroundColor: gradient,
                    borderColor: "#2563eb",
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#2563eb",
                    pointBorderWidth: 2.5,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: "#2563eb",
                    pointHoverBorderColor: "#ffffff",
                    pointHoverBorderWidth: 2,
                    data: <?= $chart_area; ?>,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 15, top: 15, bottom: 0 }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                            fontSize: 12,
                            fontColor: "#64748b"
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            color: "#f1f5f9",
                            zeroLineColor: "#e2e8f0",
                            drawBorder: false,
                            borderDash: [3, 3]
                        },
                        ticks: {
                            maxTicksLimit: 6,
                            padding: 10,
                            fontFamily: "'Plus Jakarta Sans', sans-serif",
                            fontSize: 11,
                            fontColor: "#64748b",
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                                }
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "#0f172a",
                    titleFontFamily: "'Plus Jakarta Sans', sans-serif",
                    titleFontSize: 13,
                    titleFontColor: '#ffffff',
                    bodyFontFamily: "'Plus Jakarta Sans', sans-serif",
                    bodyFontSize: 12,
                    bodyFontColor: '#cbd5e1',
                    xPadding: 14,
                    yPadding: 12,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var val = tooltipItem.yLabel || 0;
                            return 'Omzet: Rp ' + Number(val).toLocaleString('id-ID');
                        }
                    }
                }
            }
        });
    }

    // Modern Doughnut Pie Chart
    var ctxPie = document.getElementById("myPieChart");
    if (ctxPie) {
        var statusColorMap = {
            'Antri': '#f59e0b',
            'Produksi': '#3b82f6',
            'Packing': '#8b5cf6',
            'Dikirim': '#06b6d4',
            'Diterima': '#10b981',
            'Cancel': '#ef4444'
        };
        var pieLabels = <?= $pie_labels; ?>;
        var pieColors = pieLabels.map(function(label) {
            return statusColorMap[label] || '#2563eb';
        });

        var myPieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: <?= $pie_counts; ?>,
                    backgroundColor: pieColors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverBorderColor: '#ffffff'
                }],
            },
            options: {
                maintainAspectRatio: false,
                cutoutPercentage: 75,
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "#0f172a",
                    titleFontFamily: "'Plus Jakarta Sans', sans-serif",
                    bodyFontFamily: "'Plus Jakarta Sans', sans-serif",
                    xPadding: 12,
                    yPadding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var index = tooltipItem.index;
                            var label = data.labels[index] || '';
                            var value = dataset.data[index] || 0;
                            return ' ' + label + ': ' + value + ' Order';
                        }
                    }
                }
            }
        });
    }
</script>
