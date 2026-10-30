<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                Arsip Pesanan Diterima
            </h1>
            <p class="text-muted small mb-0">Daftar Surat Perintah Kerja (SPK) yang telah tuntas diproduksi dan diterima oleh pelanggan.</p>
        </div>
        <div class="mt-3 mt-sm-0">
            <a href="<?= base_url('Laporan'); ?>" class="btn btn-outline-primary btn-sm shadow-sm mr-2">
                Analisis Laporan Profit
            </a>
            <a href="<?= site_url('Orders/list'); ?>" class="btn btn-primary btn-sm shadow-sm">
                Monitoring Produksi
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Data Transaksi Berhasil
            </h6>
            <span class="badge badge-soft-success font-weight-bold">
                <?= count($history) ?> Pesanan Selesai
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No. SPK</th>
                            <th>Tanggal Selesai</th>
                            <th>Pelanggan</th>
                            <th>Item &amp; Ukuran</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Total Transaksi</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-light border text-monospace font-weight-bold text-primary" style="font-size: 0.8rem;">
                                        <?= $h->no_spk; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= date('d M Y', strtotime($h->tgl_selesai)); ?></div>
                                    <small class="text-muted">Tuntas</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= $h->nama_pelanggan; ?></div>
                                </td>
                                <td>
                                    <div><strong class="text-primary"><?= $h->kode_produk; ?></strong> &bull; <?= $h->jenis_kaca; ?></div>
                                    <small class="text-muted">Kusen: <?= $h->warna_kusen; ?> (<?= $h->ukuran; ?> CM)</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-secondary font-weight-bold px-2 py-1"><?= $h->qty; ?> PCS</span>
                                </td>
                                <td class="text-right font-weight-bold text-dark">
                                    Rp <?= number_format($h->harga_jual_satuan * $h->qty, 0, ',', '.'); ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-success px-3 py-2">
                                        Diterima
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('Orders/invoice/'.$h->no_spk) ?>" target="_blank" class="btn btn-outline-secondary btn-sm p-1 px-2" title="Cetak Nota Invoice">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($history)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Belum ada pesanan dengan status diterima.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
