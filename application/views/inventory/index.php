<div class="container-fluid">
    
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                Manajemen Stok Bahan Baku
            </h1>
            <p class="text-muted small mb-0">Kontrol stok gudang real-time dengan dukungan smart minus stock untuk sistem konsinyasi UMKM.</p>
        </div>
        <div class="d-flex align-items-center mt-3 mt-sm-0">
            <span class="badge badge-soft-primary px-3 py-2 font-weight-bold">
                Auto-Deduct on Finish
            </span>
        </div>
    </div>

    <!-- Inventory Quick Stats -->
    <?php
        $total_items = count($inventory);
        $minus_items = 0;
        $low_items = 0;
        $safe_items = 0;
        foreach ($inventory as $item) {
            if ($item->stok < 0) $minus_items++;
            elseif ($item->stok < 5) $low_items++;
            else $safe_items++;
        }
    ?>
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-2 mb-xl-0">
            <div class="metric-card accent-primary py-3 px-3">
                <div class="metric-label mb-1">Total Varian Bahan</div>
                <div class="h4 font-weight-bold text-gray-900 mb-0"><?= $total_items ?> <span class="small text-muted font-weight-normal">Varian</span></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-2 mb-xl-0">
            <div class="metric-card accent-success py-3 px-3">
                <div class="metric-label mb-1">Stok Aman (>= 5)</div>
                <div class="h4 font-weight-bold text-success mb-0"><?= $safe_items ?> <span class="small text-muted font-weight-normal">Item</span></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-2 mb-xl-0">
            <div class="metric-card accent-warning py-3 px-3">
                <div class="metric-label mb-1">Stok Menipis (&lt; 5)</div>
                <div class="h4 font-weight-bold text-warning mb-0"><?= $low_items ?> <span class="small text-muted font-weight-normal">Item</span></div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-2 mb-xl-0">
            <div class="metric-card accent-danger py-3 px-3">
                <div class="metric-label mb-1">Minus (Konsinyasi)</div>
                <div class="h4 font-weight-bold text-danger mb-0"><?= $minus_items ?> <span class="small text-muted font-weight-normal">Item</span></div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <?php if ($this->session->flashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 rounded-lg shadow-sm" role="alert">
            <div><?= $this->session->flashdata('pesan'); ?></div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 rounded-lg shadow-sm" role="alert">
            <div><?= $this->session->flashdata('error'); ?></div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Main Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Data Stok Fisik Gudang
            </h6>
            <div class="d-flex align-items-center mt-2 mt-md-0">
                <form method="get" class="d-flex align-items-center">
                    <label class="mr-2 mb-0 font-weight-bold small text-muted">Kategori Tipe:</label>
                    <select name="tipe" class="form-control form-control-sm" style="width: 160px;" onchange="this.form.submit()">
                        <option value="kusen_m" <?= ($current_filter ?? 'kusen_m') == 'kusen_m' ? 'selected' : '' ?>>Kusen M</option>
                        <option value="kusen_pivot" <?= ($current_filter ?? 'kusen_m') == 'kusen_pivot' ? 'selected' : '' ?>>Kusen Pivot</option>
                        <option value="" <?= ($current_filter ?? 'kusen_m') == '' ? 'selected' : '' ?>>Semua Tipe</option>
                    </select>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ukuran Profil</th>
                            <th>Tipe Material</th>
                            <th>Stok Tersisa</th>
                            <th>Status Gudang</th>
                            <th>Satuan</th>
                            <th class="text-center" style="width: 200px; white-space: nowrap;">Mutasi Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inventory as $i): ?>
                            <tr>
                                <td>
                                    <span class="font-weight-bold text-dark h6 mb-0"><?= $i->ukuran ?> CM</span>
                                </td>
                                <td>
                                    <span class="badge <?= $i->tipe == 'kusen_pivot' ? 'badge-soft-warning' : 'badge-soft-info' ?> font-weight-bold">
                                        <?= $i->tipe == 'kusen_pivot' ? 'Kusen Pivot' : 'Kusen M' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="h5 font-weight-bold mb-0 <?= ($i->stok < 0) ? 'text-danger' : (($i->stok < 5) ? 'text-warning' : 'text-dark') ?>">
                                        <?= number_format($i->stok) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($i->stok < 0): ?>
                                        <span class="badge badge-soft-danger">
                                            Minus (Hutang Konsinyasi)
                                        </span>
                                    <?php elseif ($i->stok < 5): ?>
                                        <span class="badge badge-soft-warning">
                                            Menipis (&lt; 5)
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-success">
                                            Tersedia Aman
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-muted small font-weight-bold"><?= strtoupper($i->satuan ?: 'BATANG') ?></span>
                                </td>
                                <td class="text-center" style="white-space: nowrap; width: 200px;">
                                    <div class="stock-action-pill">
                                        <button type="button" class="btn-stock-action action-add" onclick="tambahStok(<?= $i->id_inv ?>, '<?= $i->ukuran ?> CM')" title="Tambah Stok Masuk">
                                            + Tambah
                                        </button>
                                        <span class="stock-action-divider"></span>
                                        <button type="button" class="btn-stock-action action-sub" onclick="kurangiStok(<?= $i->id_inv ?>, '<?= $i->ukuran ?> CM')" title="Kurangi Stok Gudang">
                                            &minus; Kurangi
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Stok -->
<div class="modal fade" id="modalStok" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    Tambah Stok Masuk: <span id="nama_barang"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('Inventory/tambah_stok') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_inv" id="id_inv">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Jumlah Masuk (Unit / Batang)</label>
                        <input type="number" name="jumlah_tambah" id="jumlah_tambah" class="form-control form-control-lg font-weight-bold" placeholder="0" min="1" value="1" required autofocus>
                        <small class="text-muted mt-2 d-block">Stok fisik gudang akan langsung bertambah dan tercatat di activity log.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold">
                        Simpan Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kurangi Stok -->
<div class="modal fade" id="modalKurangi" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    Kurangi Stok Gudang: <span id="nama_barang_kurangi"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('Inventory/kurangi_stok') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_inv" id="id_inv_kurangi">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-dark">Jumlah Pengurangan</label>
                        <input type="number" name="jumlah_kurangi" id="jumlah_kurangi" class="form-control form-control-lg font-weight-bold" placeholder="0" min="1" value="1" required autofocus>
                        <small class="text-muted mt-2 d-block">
                            Catatan: Sistem mendukung minus stock jika bahan digunakan mendahului penerimaan invoice konsinyasi.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">
                        Konfirmasi Pengurangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function tambahStok(id, nama) {
        $('#id_inv').val(id);
        $('#nama_barang').text(nama);
        $('#modalStok').modal('show');
    }

    function kurangiStok(id, nama) {
        $('#id_inv_kurangi').val(id);
        $('#nama_barang_kurangi').text(nama);
        $('#jumlah_kurangi').val(1);
        $('#modalKurangi').modal('show');
    }
</script>
