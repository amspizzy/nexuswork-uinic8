<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                Arsip Pesanan Dibatalkan
            </h1>
            <p class="text-muted small mb-0">Daftar riwayat SPK yang dibatalkan oleh pihak manajemen atau pemesan.</p>
        </div>
        <div class="mt-3 mt-sm-0">
            <a href="<?= site_url('Orders/list'); ?>" class="btn btn-primary btn-sm shadow-sm">
                Monitoring Produksi
            </a>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Data Pesanan Batal
            </h6>
            <span class="badge badge-soft-danger font-weight-bold">
                <?= count($history) ?> Pesanan Cancel
            </span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No. SPK</th>
                            <th>Tanggal Cancel</th>
                            <th>Pelanggan</th>
                            <th>Item &amp; Ukuran</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Nominal Nilai</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $h): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-light border text-monospace font-weight-bold text-danger" style="font-size: 0.8rem;">
                                        <?= $h->no_spk; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= date('d M Y', strtotime($h->tgl_cancel)); ?></div>
                                    <small class="text-muted">Dibatalkan</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= $h->nama_pelanggan; ?></div>
                                </td>
                                <td>
                                    <div><strong class="text-dark"><?= $h->kode_produk; ?></strong> &bull; <?= $h->jenis_kaca; ?></div>
                                    <small class="text-muted">Kusen: <?= $h->warna_kusen; ?> (<?= $h->ukuran; ?> CM)</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-secondary font-weight-bold px-2 py-1"><?= $h->qty; ?> PCS</span>
                                </td>
                                <td class="text-right font-weight-bold text-muted">
                                    Rp <?= number_format($h->harga_jual_satuan * $h->qty, 0, ',', '.'); ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-soft-danger px-3 py-2">
                                        Cancel
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($history)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Tidak ada catatan pesanan yang dibatalkan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
