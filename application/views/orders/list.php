<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 mt-2">
        <div>
            <h1 class="dash-header-title mb-0">Monitoring Produksi</h1>
            <div class="small text-muted mt-1">Realtime Lifecycle Tracking &amp; Kontrol Alur Fabrikasi Bengkel</div>
        </div>
        <div class="d-flex align-items-center mt-2 mt-sm-0">
            <span class="badge badge-pill badge-primary px-3 py-2 font-weight-bold mr-2" style="font-size: 0.78rem; background: #0d63d8;">
                Live Firebase Synced
            </span>
            <?php if (in_array($this->session->userdata('role'), ['owner', 'admin'])): ?>
                <a href="<?= site_url('Orders'); ?>" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
                    + Buat SPK Baru
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan')): ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 rounded-lg shadow-sm" role="alert">
            <div><?= $this->session->flashdata('pesan') ?></div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 rounded-lg shadow-sm" role="alert">
            <div><?= $this->session->flashdata('error') ?></div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- QUICK ACCESS: Minimalist Folder Cards Grid (Mirrors Reference Mockup) -->
    <?php
        $cnt_antri = 0;
        $cnt_produksi = 0;
        $cnt_packing = 0;
        $cnt_dikirim = 0;
        if (!empty($orders)) {
            foreach ($orders as $item) {
                $st = strtolower($item->status_pengerjaan ?: '');
                if ($st == 'antri') $cnt_antri++;
                elseif ($st == 'produksi') $cnt_produksi++;
                elseif ($st == 'packing') $cnt_packing++;
                elseif ($st == 'dikirim') $cnt_dikirim++;
            }
        }
    ?>
    <div class="quick-access-heading">
        <span>QUICK ACCESS &bull; STATUS ALUR PRODUKSI</span>
        <span class="small font-weight-bold text-muted"><?= count($orders); ?> Total Antrian Aktif</span>
    </div>

    <div class="row mb-4">
        <!-- Card 1: Active Cobalt Blue Folder (Matches First Folder in Mockup) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card folder-active-blue">
                <div>
                    <div class="folder-card-tag">LIFECYCLE TAHAP 2</div>
                    <div class="mb-3">
                        <span class="folder-meta-pill text-white" style="background: rgba(255,255,255,0.18); font-size: 0.72rem; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; font-weight: 600;">
                            Lantai Fabrikasi
                        </span>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">Sedang Produksi</div>
                    <div class="folder-card-val"><?= number_format($cnt_produksi); ?> <span style="font-size: 0.82rem; font-weight: 600; opacity: 0.85;">SPK</span></div>
                </div>
            </div>
        </div>

        <!-- Card 2: Clean Off-White Folder (Antrian Masuk) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card">
                <div>
                    <div class="folder-card-tag">LIFECYCLE TAHAP 1</div>
                    <div class="mb-3">
                        <span class="folder-meta-pill" style="background: #f1f5f9; color: #475569; font-size: 0.72rem; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; font-weight: 600;">
                            Menunggu Diproses
                        </span>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">Antrian Masuk</div>
                    <div class="folder-card-val"><?= number_format($cnt_antri); ?> <span style="font-size: 0.82rem; font-weight: 600; color: #64748b;">SPK</span></div>
                </div>
            </div>
        </div>

        <!-- Card 3: Clean Off-White Folder (QC & Packing) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card">
                <div>
                    <div class="folder-card-tag">LIFECYCLE TAHAP 3</div>
                    <div class="mb-3">
                        <span class="folder-meta-pill" style="background: #e0f2fe; color: #0284c7; font-size: 0.72rem; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; font-weight: 600;">
                            Pemeriksaan QC
                        </span>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">QC &amp; Packing</div>
                    <div class="folder-card-val"><?= number_format($cnt_packing); ?> <span style="font-size: 0.82rem; font-weight: 600; color: #64748b;">SPK</span></div>
                </div>
            </div>
        </div>

        <!-- Card 4: Clean Off-White Folder (Pengiriman Ekspedisi) -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="folder-card">
                <div>
                    <div class="folder-card-tag">LIFECYCLE TAHAP 4</div>
                    <div class="mb-3">
                        <span class="folder-meta-pill" style="background: #ede9fe; color: #6d28d9; font-size: 0.72rem; padding: 4px 10px; border-radius: 9999px; display: inline-flex; align-items: center; font-weight: 600;">
                            Ekspedisi Kurir
                        </span>
                    </div>
                </div>
                <div>
                    <div class="folder-card-title">Dalam Pengiriman</div>
                    <div class="folder-card-val"><?= number_format($cnt_dikirim); ?> <span style="font-size: 0.82rem; font-weight: 600; color: #64748b;">SPK</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ALL ACTIVE SPK: Minimalist Table Container (Mirrors Reference Mockup) -->
    <div class="minimal-table-card">
        <div class="minimal-table-heading">
            <div class="d-flex align-items-center">
                <span class="font-weight-800">ALL ACTIVE SPK (<?= count($orders); ?> ANTRIAN AKTIF)</span>
            </div>
            <div class="small text-muted font-weight-bold">
                Realtime Auto-Update
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle minimal-table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tgl Masuk</th>
                        <th>Produk &amp; SPK</th>
                        <th>Spesifikasi</th>
                        <th>Ukuran</th>
                        <th>Qty</th>
                        <th>Pelanggan</th>
                        <th>Status Pengerjaan</th>
                        <th>Deadline</th>
                        <th>Ekspedisi</th>
                        <?php if (in_array($this->session->userdata('role'), ['owner', 'admin'])): ?>
                            <th style="width: 110px;" class="text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $file_icons = ['doc-blue', 'sheet-green', 'slide-orange', 'pdf-red'];
                    $idx = 0;
                    foreach ($orders as $o): 
                        $f_icon = $file_icons[$idx % count($file_icons)];
                        $idx++;
                    ?>
                        <tr>
                            <td>
                                <div class="font-weight-bold text-dark">
                                    <?= !empty($o->tanggal_order) ? date('d/m/y', strtotime($o->tanggal_order)) : '-' ?>
                                </div>
                                <small class="text-muted"><?= !empty($o->tgl_input) ? date('H:i', strtotime($o->tgl_input)) : '00:00' ?> WIB</small>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark mb-0"><?= $o->kode_produk ?></div>
                                <span class="badge badge-light border text-monospace font-weight-bold" style="background:#f8fafc; font-size:0.72rem; letter-spacing:0.03em;">
                                    <?= $o->no_spk ?>
                                </span>
                            </td>
                            <td>
                                <div class="small text-dark font-weight-600">Kaca: <?= $o->jenis_kaca ?></div>
                                <div class="small text-muted">Kusen: <?= $o->warna_kusen ?></div>
                            </td>
                            <td>
                                <span class="badge badge-soft-secondary font-weight-bold"><?= $o->ukuran ?> CM</span>
                            </td>
                            <td>
                                <span class="badge badge-dark py-1 px-2 font-weight-bold"><?= $o->qty ?></span>
                            </td>
                            <td>
                                <div>
                                    <div class="font-weight-bold text-dark small"><?= strtoupper($o->nama_c) ?></div>
                                    <div class="small text-muted" style="max-width: 160px; white-space: normal; line-height: 1.25; font-size: 0.74rem;"><?= $o->alamat ?></div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $role = $this->session->userdata('role');
                                $status = $o->status_pengerjaan;
                                $status_order = ['Antri' => 1, 'Produksi' => 2, 'Packing' => 3, 'Dikirim' => 4, 'Diterima' => 5, 'Cancel' => 6];
                                $level_saat_ini = $status_order[$status] ?? 0;
                                $disabled_antri = ($role === 'admin' && $level_saat_ini > $status_order['Antri']) ? 'disabled title="Admin tidak bisa mundurkan status"' : '';
                                ?>
                                <div class="status-pill-group" role="group">
                                    <button type="button" onclick="updateStatus('<?= $o->no_spk ?>', 'Antri', this)" class="status-pill-btn <?= $status == 'Antri' ? 'active status-antri' : '' ?>" <?= $disabled_antri ?>>Antri</button>
                                    <button type="button" onclick="updateStatus('<?= $o->no_spk ?>', 'Produksi', this)" class="status-pill-btn <?= $status == 'Produksi' ? 'active status-produksi' : '' ?>" <?= ($role === 'admin' && $level_saat_ini > $status_order['Produksi']) ? 'disabled title="Admin tidak bisa mundurkan status"' : '' ?>>Produksi</button>
                                    <button type="button" onclick="updateStatus('<?= $o->no_spk ?>', 'Packing', this)" class="status-pill-btn <?= $status == 'Packing' ? 'active status-packing' : '' ?>" <?= ($role === 'admin' && $level_saat_ini > $status_order['Packing']) ? 'disabled title="Admin tidak bisa mundurkan status"' : '' ?>>Packing</button>
                                    <?php if ($role === 'owner' || $role === 'admin'): ?>
                                        <button type="button" onclick="updateStatus('<?= $o->no_spk ?>', 'Dikirim', this)" class="status-pill-btn <?= $status == 'Dikirim' ? 'active status-dikirim' : '' ?>" <?= ($role === 'admin' && $level_saat_ini > $status_order['Dikirim']) ? 'disabled title="Admin tidak bisa mundurkan status"' : '' ?>>Dikirim</button>
                                    <?php endif; ?>
                                    <?php if ($role === 'owner'): ?>
                                        <button type="button" onclick="showHargaModal('<?= $o->no_spk ?>', '<?= $o->total_harga_jual ?>')" class="status-pill-btn <?= $status == 'Diterima' ? 'active status-diterima' : '' ?>">Diterima</button>
                                        <button type="button" onclick="updateStatus('<?= $o->no_spk ?>', 'Cancel', this)" class="status-pill-btn <?= $status == 'Cancel' ? 'active status-cancel' : '' ?>">Batal</button>
                                    <?php elseif ($role === 'admin'): ?>
                                        <button type="button" class="status-pill-btn" disabled title="Hanya Owner yang bisa set Diterima">Diterima</button>
                                        <button type="button" class="status-pill-btn" disabled title="Hanya Owner yang bisa Cancel">Batal</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">
                                    <?= !empty($o->deadline) ? date('d M Y', strtotime($o->deadline)) : '-' ?>
                                </div>
                                <?php
                                if (!empty($o->deadline)) {
                                    if ($o->status_pengerjaan == 'Diterima') {
                                        echo '<span class="status-pill pill-diterima mt-1" style="font-size:0.68rem; padding: 2px 8px;"><span class="status-pill-dot"></span>Selesai</span>';
                                    } else {
                                        $d1 = new DateTime(date('Y-m-d'));
                                        $d2 = new DateTime($o->deadline);
                                        $diff = $d1->diff($d2)->format("%r%a");
                                        if ($diff <= 3 && $diff >= 0) echo '<span class="badge badge-soft-danger font-weight-bold mt-1" style="font-size:0.68rem; padding: 2px 8px;">H-' . $diff . ' Urgent</span>';
                                        elseif ($diff < 0) echo '<span class="badge badge-soft-danger font-weight-bold mt-1" style="font-size:0.68rem; padding: 2px 8px;">Overdue</span>';
                                        else echo '<span class="badge badge-soft-secondary font-weight-bold mt-1" style="font-size:0.68rem; padding: 2px 8px;">H-' . $diff . '</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <span class="small font-weight-bold text-muted">
                                    <?= $o->ekspedisi ?: '-' ?>
                                </span>
                            </td>
                            <?php if (in_array($this->session->userdata('role'), ['owner', 'admin'])): ?>
                                <td class="text-center" style="white-space: nowrap;">
                                    <a href="<?= site_url('Orders/invoice/'.$o->no_spk) ?>" target="_blank" class="btn-row-action" title="Cetak Invoice">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <button type="button" onclick="showEditOrderModal('<?= $o->no_spk ?>')" class="btn-row-action text-primary" title="Edit Order">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($this->session->userdata('role') == 'owner'): ?>
                                    <button type="button" onclick="deleteOrder('<?= $o->no_spk ?>')" class="btn-row-action text-danger" title="Hapus Order">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="formUpdateStatus" action="<?= site_url('Orders/update_status') ?>" method="POST" style="display:none;">
    <input type="hidden" name="no_spk" id="input_spk">
    <input type="hidden" name="status_pengerjaan" id="input_status">
</form>

<!-- Form untuk Delete Order -->
<form id="formDeleteOrder" action="<?= site_url('Orders/delete_order') ?>" method="POST" style="display:none;">
    <input type="hidden" name="no_spk" id="delete_spk">
</form>

<!-- Modal untuk Update Harga Final -->
<div class="modal fade" id="modalHargaFinal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    Update Harga Final &bull; <span id="modal_spk"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('Orders/update_harga_final') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="no_spk" id="harga_spk">
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">Total Pembayaran Final (Aktual)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text font-weight-bold">Rp</span>
                            </div>
                            <input type="number" name="total_harga_final" id="total_harga_final" class="form-control form-control-lg font-weight-bold" required>
                        </div>
                        <small class="text-muted mt-2 d-block">Masukkan nominal akhir yang dibayarkan pelanggan saat barang diterima.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success font-weight-bold">
                        Update &amp; Set Diterima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Edit Order (Lengkap) -->
<div class="modal fade" id="modalEditOrder" tabindex="-1" role="dialog" style="overflow-y: auto;">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    Edit Order &bull; SPK <span id="edit_order_spk"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="<?= site_url('Orders/edit_order') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="no_spk" id="edit_order_no_spk">
                    <input type="hidden" name="id_hpp" id="edit_order_id_hpp">

                    <!-- Data Pelanggan -->
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-soft-primary mr-2">1</span>
                        <h6 class="mb-0 font-weight-bold text-gray-900">Data Pelanggan</h6>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" id="edit_nama_pelanggan" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label>Alamat / Kota</label>
                            <input type="text" name="alamat_pelanggan" id="edit_alamat_pelanggan" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>No. Telp / WhatsApp</label>
                            <input type="text" name="no_telp" id="edit_no_telp" class="form-control" required>
                        </div>
                    </div>

                    <!-- Detail Produk -->
                    <hr class="my-3">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-soft-primary mr-2">2</span>
                        <h6 class="mb-0 font-weight-bold text-gray-900">Spesifikasi Material Produk</h6>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Kode Produk</label>
                            <select name="kode_produk" id="edit_kode_produk" class="form-control" onchange="editLoadUkuran()" required>
                                <option value="">-- Pilih Kode --</option>
                                <?php foreach ($list_kode as $k): ?>
                                    <option value="<?= $k->kode_produk ?>"><?= $k->kode_produk ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Ukuran (CM)</label>
                            <select name="ukuran" id="edit_ukuran" class="form-control" onchange="editLoadKaca()" required disabled>
                                <option value="">-- Pilih Kode dulu --</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Jenis Kaca</label>
                            <select name="kaca" id="edit_kaca" class="form-control" onchange="editLoadWarna()" required disabled>
                                <option value="">-- Pilih Ukuran dulu --</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Warna Kusen</label>
                            <select name="warna" id="edit_warna" class="form-control" onchange="editLookupHpp()" required disabled>
                                <option value="">-- Pilih Kaca dulu --</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label>QTY (Jumlah)</label>
                            <input type="number" name="qty" id="edit_qty" class="form-control" value="1" onchange="updateHpp()" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>HPP Satuan (Rp)</label>
                            <input type="number" name="hpp_saat_ini" id="edit_hpp_satuan" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <!-- Harga -->
                    <hr class="my-3">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-soft-primary mr-2">3</span>
                        <h6 class="mb-0 font-weight-bold text-gray-900">Harga & Pengiriman</h6>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Harga Jual Satuan (Rp)</label>
                            <input type="number" name="harga_jual_satuan" id="edit_harga_satuan" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tanggal Order</label>
                            <input type="date" name="tanggal_order" id="edit_tanggal_order" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Deadline Produksi</label>
                            <input type="date" name="deadline" id="edit_deadline" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Ekspedisi</label>
                            <input type="text" name="ekspedisi" id="edit_ekspedisi" class="form-control" placeholder="Contoh: JNE, Pickup Sendiri, dll">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateStatus(spk, status, btn) {
        if (status === 'Cancel') {
            if (!confirm('Apakah Anda yakin ingin membatalkan pesanan SPK ' + spk + '?')) return;
        }

        // Smooth instant visual update on clicked button
        var group = btn ? btn.closest('.status-pill-group') : null;
        var prevActive = group ? group.querySelector('.status-pill-btn.active') : null;
        if (group && btn) {
            var allBtns = group.querySelectorAll('.status-pill-btn');
            allBtns.forEach(function(b) {
                b.className = 'status-pill-btn';
            });
            btn.classList.add('active', 'status-' + status.toLowerCase());
        }

        // Smooth progress trigger
        var progress = document.getElementById('nw-top-progress');
        if (progress) {
            progress.style.opacity = '1';
            progress.style.width = '70%';
        }

        $.ajax({
            url: '<?= site_url('Orders/update_status') ?>',
            type: 'POST',
            data: { 
                no_spk: spk, 
                status_baru: status 
            },
            dataType: 'json',
            success: function(response) {
                if (progress) {
                    progress.style.width = '100%';
                    setTimeout(function() {
                        progress.style.opacity = '0';
                        setTimeout(function() { progress.style.width = '0%'; }, 200);
                    }, 200);
                }

                if (response.status === 'success') {
                    if (typeof window.clearNwPageCache === 'function') {
                        window.clearNwPageCache();
                    }
                    if (typeof showNwToast === 'function') {
                        showNwToast('Status SPK ' + spk + ' diubah ke ' + status);
                    }
                } else {
                    if (prevActive && btn) {
                        btn.className = 'status-pill-btn';
                        prevActive.classList.add('active');
                    }
                    alert('Gagal update: ' + (response.message || 'Terjadi kesalahan'));
                }
            },
            error: function(xhr, statusErr, error) {
                if (progress) progress.style.opacity = '0';
                if (prevActive && btn) {
                    btn.className = 'status-pill-btn';
                    prevActive.classList.add('active');
                }
                alert('Koneksi terputus: ' + error);
            }
        });
    }

    function showHargaModal(spk, currentTotal) {
        document.getElementById('modal_spk').textContent = spk;
        document.getElementById('harga_spk').value = spk;
        document.getElementById('total_harga_final').value = currentTotal;
        $('#modalHargaFinal').modal('show');
    }

    // ===== SHOW EDIT MODAL =====
    function showEditOrderModal(spk) {
        $.ajax({
            url: '<?= site_url('Orders/get_order_data') ?>',
            type: 'POST',
            data: { no_spk: spk },
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    const order = data.order;
                    const customer = data.customer;
                    const hpp = data.hpp;

                    document.getElementById('edit_order_spk').textContent = spk;
                    document.getElementById('edit_order_no_spk').value = spk;
                    document.getElementById('edit_nama_pelanggan').value = customer.nama_c;
                    document.getElementById('edit_alamat_pelanggan').value = customer.alamat;
                    document.getElementById('edit_no_telp').value = customer.no_telp;

                    document.getElementById('edit_harga_satuan').value = order.harga_jual_satuan;
                    document.getElementById('edit_tanggal_order').value = order.tanggal_order;
                    document.getElementById('edit_deadline').value = order.deadline;
                    document.getElementById('edit_ekspedisi').value = order.ekspedisi || '';
                    document.getElementById('edit_qty').value = order.qty;

                    $('#edit_kode_produk').val(hpp.kode_produk);

                    $.ajax({
                        url: '<?= site_url('Orders/get_ukuran_by_kode') ?>',
                        type: 'POST', data: { kode: hpp.kode_produk }, dataType: 'json',
                        success: function(resUkuran) {
                            var $u = $('#edit_ukuran').html('<option value="">-- Pilih Ukuran --</option>');
                            $.each(resUkuran.ukuran, function(i, v) {
                                var sel = (v == hpp.ukuran) ? 'selected' : '';
                                $u.append('<option value="'+v+'" '+sel+'>'+v+'</option>');
                            });
                            $u.prop('disabled', false);

                            $.ajax({
                                url: '<?= site_url('Orders/get_kaca_by_kode_ukuran') ?>',
                                type: 'POST', data: { kode: hpp.kode_produk, ukuran: hpp.ukuran }, dataType: 'json',
                                success: function(resKaca) {
                                    var $k = $('#edit_kaca').html('<option value="">-- Pilih Kaca --</option>');
                                    $.each(resKaca.kaca, function(i, v) {
                                        var sel = (v == hpp.jenis_kaca) ? 'selected' : '';
                                        $k.append('<option value="'+v+'" '+sel+'>'+v+'</option>');
                                    });
                                    $k.prop('disabled', false);

                                    $.ajax({
                                        url: '<?= site_url('Orders/get_warna_by_kode_ukuran_kaca') ?>',
                                        type: 'POST', data: { kode: hpp.kode_produk, ukuran: hpp.ukuran, kaca: hpp.jenis_kaca }, dataType: 'json',
                                        success: function(resWarna) {
                                            var $w = $('#edit_warna').html('<option value="">-- Pilih Warna --</option>');
                                            $.each(resWarna.warna, function(i, v) {
                                                var sel = (v == hpp.warna_kusen) ? 'selected' : '';
                                                $w.append('<option value="'+v+'" '+sel+'>'+v+'</option>');
                                            });
                                            $w.prop('disabled', false);

                                            document.getElementById('edit_hpp_satuan').value = hpp.total_biaya_modal;
                                            document.getElementById('edit_order_id_hpp').value = hpp.id_hpp;

                                            $('#modalEditOrder').modal('show');
                                        }
                                    });
                                }
                            });
                        }
                    });
                } else {
                    alert('Error: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Gagal mengambil data order: ' + error);
            }
        });
    }

    function editLoadUkuran() {
        var kode = document.getElementById('edit_kode_produk').value;
        $('#edit_ukuran').html('<option value="">-- Pilih Kode dulu --</option>').prop('disabled', true);
        $('#edit_kaca').html('<option value="">-- Pilih Ukuran dulu --</option>').prop('disabled', true);
        $('#edit_warna').html('<option value="">-- Pilih Kaca dulu --</option>').prop('disabled', true);
        $('#edit_hpp_satuan').val('');
        $('#edit_order_id_hpp').val('');

        if (!kode) return;
        $.ajax({
            url: '<?= site_url('Orders/get_ukuran_by_kode') ?>',
            type: 'POST', data: { kode: kode }, dataType: 'json',
            success: function(res) {
                var $s = $('#edit_ukuran').html('<option value="">-- Pilih Ukuran --</option>');
                $.each(res.ukuran, function(i, v) { $s.append('<option value="'+v+'">'+v+'</option>'); });
                $s.prop('disabled', false);
            }
        });
    }

    function editLoadKaca() {
        var kode   = document.getElementById('edit_kode_produk').value;
        var ukuran = document.getElementById('edit_ukuran').value;
        $('#edit_kaca').html('<option value="">-- Pilih Ukuran dulu --</option>').prop('disabled', true);
        $('#edit_warna').html('<option value="">-- Pilih Kaca dulu --</option>').prop('disabled', true);
        $('#edit_hpp_satuan').val('');
        $('#edit_order_id_hpp').val('');

        if (!kode || !ukuran) return;
        $.ajax({
            url: '<?= site_url('Orders/get_kaca_by_kode_ukuran') ?>',
            type: 'POST', data: { kode: kode, ukuran: ukuran }, dataType: 'json',
            success: function(res) {
                var $s = $('#edit_kaca').html('<option value="">-- Pilih Kaca --</option>');
                $.each(res.kaca, function(i, v) { $s.append('<option value="'+v+'">'+v+'</option>'); });
                $s.prop('disabled', false);
            }
        });
    }

    function editLoadWarna() {
        var kode   = document.getElementById('edit_kode_produk').value;
        var ukuran = document.getElementById('edit_ukuran').value;
        var kaca   = document.getElementById('edit_kaca').value;
        $('#edit_warna').html('<option value="">-- Pilih Kaca dulu --</option>').prop('disabled', true);
        $('#edit_hpp_satuan').val('');
        $('#edit_order_id_hpp').val('');

        if (!kode || !ukuran || !kaca) return;
        $.ajax({
            url: '<?= site_url('Orders/get_warna_by_kode_ukuran_kaca') ?>',
            type: 'POST', data: { kode: kode, ukuran: ukuran, kaca: kaca }, dataType: 'json',
            success: function(res) {
                var $s = $('#edit_warna').html('<option value="">-- Pilih Warna --</option>');
                $.each(res.warna, function(i, v) { $s.append('<option value="'+v+'">'+v+'</option>'); });
                $s.prop('disabled', false);
            }
        });
    }

    function editLookupHpp() {
        var kode   = document.getElementById('edit_kode_produk').value;
        var ukuran = document.getElementById('edit_ukuran').value;
        var kaca   = document.getElementById('edit_kaca').value;
        var warna  = document.getElementById('edit_warna').value;
        $('#edit_hpp_satuan').val('');
        $('#edit_order_id_hpp').val('');

        if (!kode || !ukuran || !kaca || !warna) return;
        $.ajax({
            url: '<?= site_url('Orders/get_hpp_details') ?>',
            type: 'POST',
            data: { kode: kode, ukuran: ukuran, kaca: kaca, warna: warna },
            dataType: 'json',
            success: function(data) {
                if (data.status === 'success') {
                    document.getElementById('edit_hpp_satuan').value = data.data.total_biaya_modal;
                    document.getElementById('edit_order_id_hpp').value = data.data.id_hpp;
                } else {
                    alert('Kombinasi HPP tidak ditemukan untuk pilihan ini.');
                }
            }
        });
    }

    function deleteOrder(spk) {
        if (confirm('Yakin ingin menghapus order SPK ' + spk + '?\n\nAksi ini akan menghapus order dan monitoring. Stok akan dikembalikan jika sudah dikirim.\n\nAksi ini TIDAK bisa dibatalkan!')) {
            document.getElementById('delete_spk').value = spk;
            document.getElementById('formDeleteOrder').submit();
        }
    }
</script>
<!-- Firebase Realtime Tracking -->
<script type="module" src="<?= base_url('assets/js/firebase-realtime.js') ?>"></script>
