<?php if($this->session->flashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 rounded-lg shadow-sm" role="alert">
        <i class="fas fa-check-circle text-success mr-2 fa-lg"></i>
        <div><?= $this->session->flashdata('pesan'); ?></div>
        <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 rounded-lg shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle text-danger mr-2 fa-lg"></i>
        <div><strong>Perhatian:</strong> <?= $this->session->flashdata('error'); ?></div>
        <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Floating AJAX Notification -->
<div id="notif-ajax" class="alert alert-dismissible fade show d-none" role="alert" style="position:fixed;top:85px;right:25px;z-index:9999;min-width:320px;box-shadow:0 10px 25px rgba(0,0,0,0.18);border-radius:12px;">
    <i class="fas fa-check-circle mr-2"></i> <span id="notif-ajax-msg" class="font-weight-bold"></span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                Master Harga Pokok Produksi (HPP)
            </h1>
            <p class="text-muted small mb-0">Standardisasi rincian biaya komponen material dan tenaga kerja per unit produk.</p>
        </div>
        <button class="btn btn-primary btn-sm shadow-sm mt-3 mt-sm-0" data-toggle="modal" data-target="#modalTambah">
            <i class="fas fa-plus-circle fa-sm mr-1"></i> Tambah Standar HPP
        </button>
    </div>

    <!-- Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex flex-wrap align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-gray-900">
                Rincian Biaya Modal Material &amp; Tenaga
            </h6>
            <div class="small text-muted">
                Total item terdaftar: <strong><?= count($material); ?></strong> formulasi HPP
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>Kode Produk</th>
                            <th>Ukuran</th>
                            <th>Jenis Kaca</th>
                            <th>Warna Kusen</th>
                            <th>Kaca (Rp)</th>
                            <th>Kusen Lkg</th>
                            <th>Kusen Lrs</th>
                            <th>B 6CM</th>
                            <th>Ornamen</th>
                            <th>Packing</th>
                            <th>Tenaga</th>
                            <th>Total Modal</th>
                            <th style="width: 80px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($material as $m): ?>
                            <tr id="row-<?= $m->id_hpp; ?>">
                                <td class="text-center font-weight-bold">
                                    <?php if (strpos($m->kode_produk, 'SK') !== false): ?>
                                        <span class="badge badge-soft-success"><?= $m->kode_produk; ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-soft-primary"><?= $m->kode_produk; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center font-weight-bold"><?= $m->ukuran; ?> CM</td>
                                <td class="text-center small"><?= $m->jenis_kaca; ?></td>
                                <td class="text-center small"><?= $m->warna_kusen; ?></td>
                                <td class="text-right small"><?= number_format($m->biaya_kaca ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($m->biaya_kusen_lengkung ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($m->biaya_kusen_lurus ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($m->biaya_b_6cm ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($m->biaya_ornamen ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($m->biaya_packing ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-right small"><?= number_format($m->biaya_tenaga ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-right font-weight-bold text-dark" style="white-space: nowrap;">
                                    Rp <?= number_format($m->total_biaya_modal ?? 0, 0, ',', '.'); ?>
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <button class="btn btn-outline-primary btn-sm btn-edit p-1 px-2"
                                        data-id="<?= $m->id_hpp; ?>"
                                        data-kode="<?= $m->kode_produk; ?>"
                                        data-ukuran="<?= $m->ukuran; ?>"
                                        data-jkaca="<?= $m->jenis_kaca; ?>"
                                        data-wkusen="<?= $m->warna_kusen; ?>"
                                        data-bkaca="<?= (int)$m->biaya_kaca; ?>"
                                        data-bkusen_lengkung="<?= (int)$m->biaya_kusen_lengkung; ?>"
                                        data-bkusen_lurus="<?= (int)$m->biaya_kusen_lurus; ?>"
                                        data-b6cm="<?= (int)$m->biaya_b_6cm; ?>"
                                        data-bornamen="<?= (int)$m->biaya_ornamen; ?>"
                                        data-bpacking="<?= (int)$m->biaya_packing; ?>"
                                        data-btenaga="<?= (int)$m->biaya_tenaga; ?>"
                                        data-toggle="modal" data-target="#modalEdit"
                                        title="Edit Biaya Modal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="<?= site_url('MasterHpp/hapus/' . $m->id_hpp); ?>" class="btn btn-outline-danger btn-sm p-1 px-2" onclick="return confirm('Yakin hapus formulasi HPP ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= site_url('MasterHpp/simpan'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        Tambah Spesifikasi &amp; Biaya HPP Baru
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-soft-primary mr-2">1</span>
                        <h6 class="mb-0 font-weight-bold text-gray-900">Spesifikasi Profil</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label>Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" placeholder="Contoh: SK01" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Ukuran (CM)</label>
                            <input type="text" name="ukuran" class="form-control" placeholder="60" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Jenis Kaca</label>
                            <input type="text" name="jenis_kaca" class="form-control" placeholder="Clear / Riben" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label>Warna Kusen</label>
                            <input type="text" name="warna_kusen" class="form-control" placeholder="Hitam / Putih" required>
                        </div>
                    </div>

                    <hr class="my-3">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge badge-soft-primary mr-2">2</span>
                        <h6 class="mb-0 font-weight-bold text-gray-900">Rincian Komponen Biaya (Rp)</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-2"><label>Biaya Kaca</label><input type="number" name="biaya_kaca" class="form-control" value="50000"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Kusen Lengkung</label><input type="number" name="biaya_kusen_lengkung" class="form-control" value="50000"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Kusen Lurus</label><input type="number" name="biaya_kusen_lurus" class="form-control" value="0"></div>
                        <div class="col-md-4 mb-2"><label>Biaya B 6CM</label><input type="number" name="biaya_b_6cm" class="form-control" value="0"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Ornamen</label><input type="number" name="biaya_ornamen" class="form-control" value="50000"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Packing</label><input type="number" name="biaya_packing" class="form-control" value="50000"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Tenaga Kerja</label><input type="number" name="biaya_tenaga" class="form-control" value="50000"></div>
                        <div class="col-md-8 mb-2">
                            <label class="font-weight-bold text-primary">Total Biaya Modal (Otomatis)</label>
                            <input type="number" name="total_biaya_modal" id="t_total" class="form-control font-weight-bold bg-light" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        Simpan HPP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit (AJAX) -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="formEdit">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        Edit Data Formulasi HPP
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_hpp" id="e_id">
                    <div class="row">
                        <div class="col-md-3 mb-2"><label>Kode Produk</label><input type="text" name="kode_produk" id="e_kode" class="form-control"></div>
                        <div class="col-md-3 mb-2"><label>Ukuran</label><input type="text" name="ukuran" id="e_ukuran" class="form-control"></div>
                        <div class="col-md-3 mb-2"><label>Jenis Kaca</label><input type="text" name="jenis_kaca" id="e_jkaca" class="form-control"></div>
                        <div class="col-md-3 mb-2"><label>Warna Kusen</label><input type="text" name="warna_kusen" id="e_wkusen" class="form-control"></div>
                    </div>
                    <hr class="my-3">
                    <div class="row">
                        <div class="col-md-4 mb-2"><label>Biaya Kaca</label><input type="number" name="biaya_kaca" id="e_bkaca" class="form-control"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Kusen Lengkung</label><input type="number" name="biaya_kusen_lengkung" id="e_bkusen_lengkung" class="form-control"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Kusen Lurus</label><input type="number" name="biaya_kusen_lurus" id="e_bkusen_lurus" class="form-control"></div>
                        <div class="col-md-4 mb-2"><label>Biaya B 6CM</label><input type="number" name="biaya_b_6cm" id="e_b6cm" class="form-control"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Ornamen</label><input type="number" name="biaya_ornamen" id="e_bornamen" class="form-control"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Packing</label><input type="number" name="biaya_packing" id="e_bpacking" class="form-control"></div>
                        <div class="col-md-4 mb-2"><label>Biaya Tenaga</label><input type="number" name="biaya_tenaga" id="e_btenaga" class="form-control"></div>
                        <div class="col-md-8 mb-2">
                            <label class="font-weight-bold text-info">Total Modal</label>
                            <input type="number" name="total_biaya_modal" id="e_total" class="form-control font-weight-bold bg-light" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdate">
                        <span id="btnUpdateText">Update Data</span>
                        <span id="btnUpdateSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script>
    $(document).on('click', '.btn-edit', function() {
        $('#e_id').val($(this).data('id'));
        $('#e_kode').val($(this).data('kode'));
        $('#e_ukuran').val($(this).data('ukuran'));
        $('#e_jkaca').val($(this).data('jkaca'));
        $('#e_wkusen').val($(this).data('wkusen'));
        $('#e_bkaca').val($(this).data('bkaca'));
        $('#e_bkusen_lengkung').val($(this).data('bkusen_lengkung'));
        $('#e_bkusen_lurus').val($(this).data('bkusen_lurus'));
        $('#e_b6cm').val($(this).data('b6cm'));
        $('#e_bornamen').val($(this).data('bornamen'));
        $('#e_bpacking').val($(this).data('bpacking'));
        $('#e_btenaga').val($(this).data('btenaga'));
        calculateTotalEdit();
    });

    $('#formEdit').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $('#btnUpdateText').text('Menyimpan...');
        $('#btnUpdateSpinner').removeClass('d-none');
        $('#btnUpdate').prop('disabled', true);

        $.ajax({
            url: '<?= site_url("MasterHpp/ubah"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#modalEdit').modal('hide');

                    var id = $('#e_id').val();
                    var bkaca   = parseInt($('#e_bkaca').val()) || 0;
                    var bkl     = parseInt($('#e_bkusen_lengkung').val()) || 0;
                    var bkr     = parseInt($('#e_bkusen_lurus').val()) || 0;
                    var b6      = parseInt($('#e_b6cm').val()) || 0;
                    var bo      = parseInt($('#e_bornamen').val()) || 0;
                    var bp      = parseInt($('#e_bpacking').val()) || 0;
                    var bt      = parseInt($('#e_btenaga').val()) || 0;
                    var total   = bkaca + bkl + bkr + b6 + bo + bp + bt;

                    var row = $('#row-' + id);
                    var cells = row.find('td');
                    cells.eq(0).text($('#e_kode').val());
                    cells.eq(1).text($('#e_ukuran').val() + ' CM');
                    cells.eq(2).text($('#e_jkaca').val());
                    cells.eq(3).text($('#e_wkusen').val());
                    cells.eq(4).text(formatRupiah(bkaca));
                    cells.eq(5).text(formatRupiah(bkl));
                    cells.eq(6).text(formatRupiah(bkr));
                    cells.eq(7).text(formatRupiah(b6));
                    cells.eq(8).text(formatRupiah(bo));
                    cells.eq(9).text(formatRupiah(bp));
                    cells.eq(10).text(formatRupiah(bt));
                    cells.eq(11).text('Rp ' + formatRupiah(total));

                    row.find('.btn-edit')
                        .data('kode', $('#e_kode').val())
                        .data('ukuran', $('#e_ukuran').val())
                        .data('jkaca', $('#e_jkaca').val())
                        .data('wkusen', $('#e_wkusen').val())
                        .data('bkaca', bkaca)
                        .data('bkusen_lengkung', bkl)
                        .data('bkusen_lurus', bkr)
                        .data('b6cm', b6)
                        .data('bornamen', bo)
                        .data('bpacking', bp)
                        .data('btenaga', bt);

                    showNotif('success', response.message);
                } else {
                    showNotif('danger', 'Terjadi kesalahan, coba lagi.');
                }
            },
            error: function() {
                showNotif('danger', 'Koneksi bermasalah, coba lagi.');
            },
            complete: function() {
                $('#btnUpdateText').html('<i class="fas fa-check mr-1"></i> Update Data');
                $('#btnUpdateSpinner').addClass('d-none');
                $('#btnUpdate').prop('disabled', false);
            }
        });
    });

    function showNotif(type, message) {
        var notif = $('#notif-ajax');
        notif.removeClass('d-none alert-success alert-danger alert-warning');
        notif.addClass('alert-' + type);
        $('#notif-ajax-msg').text(message);
        notif.fadeIn();

        setTimeout(function() {
            notif.fadeOut(400, function() {
                notif.addClass('d-none');
            });
        }, 4000);
    }

    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    $(document).on('keyup change', '#modalTambah input[type="number"]', function() {
        calculateTotalTambah();
    });

    $('#modalTambah').on('shown.bs.modal', function() {
        calculateTotalTambah();
    });

    function calculateTotalTambah() {
        let bk  = parseInt($('#modalTambah input[name="biaya_kaca"]').val()) || 0;
        let bkl = parseInt($('#modalTambah input[name="biaya_kusen_lengkung"]').val()) || 0;
        let bkr = parseInt($('#modalTambah input[name="biaya_kusen_lurus"]').val()) || 0;
        let b6  = parseInt($('#modalTambah input[name="biaya_b_6cm"]').val()) || 0;
        let bo  = parseInt($('#modalTambah input[name="biaya_ornamen"]').val()) || 0;
        let bp  = parseInt($('#modalTambah input[name="biaya_packing"]').val()) || 0;
        let bt  = parseInt($('#modalTambah input[name="biaya_tenaga"]').val()) || 0;
        $('#t_total').val(bk + bkl + bkr + b6 + bo + bp + bt);
    }

    $(document).on('keyup change', '#modalEdit input[type="number"]', function() {
        calculateTotalEdit();
    });

    function calculateTotalEdit() {
        let bk  = parseInt($('#e_bkaca').val()) || 0;
        let bkl = parseInt($('#e_bkusen_lengkung').val()) || 0;
        let bkr = parseInt($('#e_bkusen_lurus').val()) || 0;
        let b6  = parseInt($('#e_b6cm').val()) || 0;
        let bo  = parseInt($('#e_bornamen').val()) || 0;
        let bp  = parseInt($('#e_bpacking').val()) || 0;
        let bt  = parseInt($('#e_btenaga').val()) || 0;
        $('#e_total').val(bk + bkl + bkr + b6 + bo + bp + bt);
    }
</script>
