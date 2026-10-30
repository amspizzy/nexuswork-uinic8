<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                Activity Log &bull; Audit Trail
            </h1>
            <p class="text-muted small mb-0">Pencatatan riwayat setiap aksi dan mutasi data oleh pengguna demi kepatuhan & transparansi sistem.</p>
        </div>
        <span class="badge badge-soft-primary px-3 py-2 font-weight-bold">
            <i class="fas fa-shield-alt mr-1"></i> Tamper-evident Audit
        </span>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 bg-light">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Filter Jejak Audit
            </h6>
        </div>
        <div class="card-body">
            <form action="<?= site_url('ActivityLog'); ?>" method="GET" class="row align-items-end">
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold text-dark small">Tipe Aksi Operasional</label>
                    <select name="aksi" class="form-control">
                        <option value="">-- Seluruh Aksi --</option>
                        <?php foreach($list_aksi as $k => $v): ?>
                            <option value="<?= $k ?>" <?= ($filter_aksi == $k) ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold text-dark small">Mulai Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold text-dark small">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                </div>
                <div class="col-md-3 mb-2 d-flex">
                    <button type="submit" class="btn btn-primary mr-2 flex-grow-1">
                        Terapkan Filter
                    </button>
                    <a href="<?= site_url('ActivityLog'); ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTable Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Kronologi Riwayat Aktivitas
            </h6>
            <span class="small text-muted">Total: <strong><?= count($logs); ?></strong> catatan audit</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 160px;">Waktu & Tanggal</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Deskripsi Mutasi</th>
                            <th>No. SPK Terkait</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-search fa-2x mb-2 d-block text-gray-300"></i>
                                    Tidak ada catatan log pada filter yang dipilih.
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($logs as $log) : 
                            $badge_class = 'badge-soft-secondary';
                            if (in_array($log->aksi, ['tambah_order', 'tambah_hpp', 'tambah_stok'])) {
                                $badge_class = 'badge-soft-success';
                            } elseif (in_array($log->aksi, ['edit_order', 'edit_hpp', 'ubah_status'])) {
                                $badge_class = 'badge-soft-primary';
                            } elseif (in_array($log->aksi, ['hapus_order', 'hapus_hpp', 'kurangi_stok'])) {
                                $badge_class = 'badge-soft-danger';
                            } elseif (in_array($log->aksi, ['update_harga'])) {
                                $badge_class = 'badge-soft-warning';
                            }
                            
                            $aksi_label = isset($list_aksi[$log->aksi]) ? $list_aksi[$log->aksi] : $log->aksi;
                            
                            $role_class = 'badge-soft-secondary';
                            if ($log->role == 'owner') $role_class = 'user-role-badge role-owner';
                            elseif ($log->role == 'admin') $role_class = 'user-role-badge role-admin';
                            elseif ($log->role == 'karyawan') $role_class = 'user-role-badge role-karyawan';
                        ?>
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= date('d M Y', strtotime($log->created_at)); ?></div>
                                    <small class="text-muted"><i class="far fa-clock mr-1"></i><?= date('H:i:s', strtotime($log->created_at)); ?> WIB</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark"><?= $log->nama_user; ?></div>
                                    <span class="<?= $role_class; ?>"><?= strtoupper($log->role); ?></span>
                                </td>
                                <td>
                                    <span class="badge <?= $badge_class; ?> px-2 py-1 font-weight-bold">
                                        <?= $aksi_label; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="text-dark small" style="line-height: 1.4;"><?= $log->deskripsi; ?></div>
                                </td>
                                <td>
                                    <?php if ($log->no_spk): ?>
                                        <span class="badge badge-light border text-monospace font-weight-bold text-primary">
                                            <?= $log->no_spk; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

</div>
