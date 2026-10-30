<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                Input Pesanan Baru
            </h1>
            <p class="text-muted small mb-0">Terbitkan Surat Perintah Kerja (SPK) baru dan hubungkan otomatis dengan HPP material.</p>
        </div>
        <a href="<?= site_url('Orders/list'); ?>" class="btn btn-outline-secondary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Kembali ke Monitoring
        </a>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 rounded-lg shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle text-danger mr-2 fa-lg"></i>
            <div><?= $this->session->flashdata('error'); ?></div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('Orders/simpan'); ?>" method="post" id="formOrder">
        <div class="row">
            
            <!-- Left Column: Form Details -->
            <div class="col-lg-8">
                
                <!-- Card 1: Data Pemesan & Pengiriman -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-gray-900">
                            <span class="badge badge-soft-primary mr-2">1</span> Identitas Pemesan & Ekspedisi
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">No. SPK <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fas fa-file-invoice"></i></span>
                                    </div>
                                    <input type="text" name="no_spk" class="form-control" placeholder="Contoh: SPK-<?= date('ymd'); ?>-01" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Tanggal Order <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_order" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nama Pelanggan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pelanggan" class="form-control" placeholder="Nama instansi / perorangan" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light"><i class="fab fa-whatsapp"></i></span>
                                    </div>
                                    <input type="text" name="no_telp" class="form-control" placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold">Alamat Lengkap / Kota Tujuan <span class="text-danger">*</span></label>
                                <textarea name="alamat_pelanggan" class="form-control" rows="2" placeholder="Alamat pengiriman proyek atau bengkel..." required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Tenggat Waktu (Deadline) <span class="text-danger">*</span></label>
                                <input type="date" name="deadline" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Metode Ekspedisi / Pengiriman <span class="text-danger">*</span></label>
                                <select name="ekspedisi" class="form-control" required>
                                    <option value="">-- Pilih Ekspedisi --</option>
                                    <option value="Ambil Sendiri">Ambil Sendiri (Pickup Bengkel)</option>
                                    <option value="Kurir Internal">Armada Internal Bengkel</option>
                                    <option value="JNE">JNE Freight / Trucking</option>
                                    <option value="J&T">J&T Cargo</option>
                                    <option value="Sicepat">Sicepat Cargo</option>
                                    <option value="Lalamove/GoSend">Lalamove / PickUp Online</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Spesifikasi Material Produk -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-gray-900">
                            <span class="badge badge-soft-primary mr-2">2</span> Spesifikasi Material Produk (Master HPP)
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Kode Produk</label>
                                <select id="f_kode" class="form-control hpp-filter" required>
                                    <option value="">-- Pilih Kode Produk --</option>
                                    <?php foreach ($list_kode as $kd) echo "<option value='$kd->kode_produk'>$kd->kode_produk</option>"; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Ukuran (CM)</label>
                                <select id="f_ukuran" class="form-control hpp-filter" required disabled>
                                    <option value="">- Pilih Kode dulu -</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Jenis Kaca</label>
                                <select id="f_kaca" class="form-control hpp-filter" required disabled>
                                    <option value="">- Pilih Ukuran dulu -</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Warna Kusen</label>
                                <select id="f_warna" class="form-control hpp-filter" required disabled>
                                    <option value="">- Pilih Kaca dulu -</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="id_hpp" id="id_hpp">
                        <input type="hidden" name="hpp_saat_ini" id="hpp_saat_ini">
                    </div>
                </div>

            </div>

            <!-- Right Column: Live Price & Summary Widget -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4 position-sticky" style="top: 85px;">
                    <div class="card shadow-sm mb-4 position-sticky" style="top: 85px;">
                    <div class="card-header py-3 bg-light">
                        <h6 class="m-0 font-weight-bold text-gray-900">
                            Ringkasan &amp; Kalkulasi
                        </h6>
                    </div>
                    <div class="card-body">
                        
                        <!-- Qty Input -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Jumlah Pesanan (Qty)</label>
                            <div class="input-group">
                                <input type="number" name="qty" id="qty" class="form-control form-control-lg font-weight-bold text-center" value="1" min="1" max="99">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light font-weight-bold">Unit</span>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Jual Satuan -->
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark">Harga Jual Satuan (Deal)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light font-weight-bold">Rp</span>
                                </div>
                                <input type="number" name="harga_jual_satuan" id="harga_jual" class="form-control form-control-lg font-weight-bold" placeholder="0" required>
                            </div>
                            <small class="text-muted">Dapat disesuaikan dari estimasi default.</small>
                        </div>

                        <hr class="my-3">

                        <!-- Breakdown Info -->
                        <div class="p-3 rounded-lg mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Biaya Modal (HPP):</span>
                                <span class="font-weight-bold text-dark" id="display_hpp">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Margin Satuan:</span>
                                <span class="font-weight-bold text-success" id="display_margin">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between small pt-2 border-top">
                                <span class="font-weight-bold text-dark">Total Biaya Produksi:</span>
                                <span class="font-weight-bold text-muted" id="display_total_hpp">Rp 0</span>
                            </div>
                        </div>

                        <!-- Grand Total Highlight -->
                        <div class="p-3 rounded-lg text-center mb-4" style="background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%); border: 1px solid #bae6fd;">
                            <span class="text-primary font-weight-bold small text-uppercase letter-spacing-1 d-block mb-1">Total Tagihan (Grand Total)</span>
                            <div class="h3 font-weight-bold text-primary mb-0">
                                Rp <span id="label_total">0</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="btn-simpan" class="btn btn-primary btn-block btn-lg shadow" disabled>
                            Simpan &amp; Terbitkan SPK
                        </button>
                        <p class="text-muted text-center mt-2 small mb-0">
                            Otomatis memotong stok saat selesai
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script>
    $(document).ready(function() {

        function resetFrom(level) {
            if (level === 'ukuran' || level === 'kaca' || level === 'warna' || level === 'hpp') {
                if (level === 'ukuran') {
                    $('#f_ukuran').html('<option value="">- Pilih Kode dulu -</option>').prop('disabled', true);
                }
                $('#f_kaca').html('<option value="">- Pilih Ukuran dulu -</option>').prop('disabled', true);
                $('#f_warna').html('<option value="">- Pilih Kaca dulu -</option>').prop('disabled', true);
                $('#id_hpp').val('');
                $('#hpp_saat_ini').val('');
                $('#display_hpp').text('Rp 0');
                $('#display_margin').text('Rp 0');
                $('#display_total_hpp').text('Rp 0');
                $('#btn-simpan').prop('disabled', true);
            }
        }

        // TINGKAT 1: Kode Produk
        $('#f_kode').change(function() {
            var kode = $(this).val();
            resetFrom('ukuran');
            if (!kode) return;

            $.ajax({
                url: "<?= site_url('Orders/get_options_by_kode') ?>",
                type: "POST",
                data: { kode: kode },
                dataType: "JSON",
                success: function(data) {
                    $('#f_ukuran').html('<option value="">- Pilih Ukuran -</option>');
                    $.each(data.ukuran, function(i, v) {
                        $('#f_ukuran').append('<option value="' + v + '">' + v + ' CM</option>');
                    });
                    $('#f_ukuran').prop('disabled', false);
                }
            });
        });

        // TINGKAT 2: Ukuran
        $('#f_ukuran').change(function() {
            var kode   = $('#f_kode').val();
            var ukuran = $(this).val();
            resetFrom('kaca');
            if (!kode || !ukuran) return;

            $.ajax({
                url: "<?= site_url('Orders/get_kaca_by_kode_ukuran') ?>",
                type: "POST",
                data: { kode: kode, ukuran: ukuran },
                dataType: "JSON",
                success: function(data) {
                    $('#f_kaca').html('<option value="">- Pilih Kaca -</option>');
                    $.each(data.kaca, function(i, v) {
                        $('#f_kaca').append('<option value="' + v + '">' + v + '</option>');
                    });
                    $('#f_kaca').prop('disabled', false);
                }
            });
        });

        // TINGKAT 3: Jenis Kaca
        $('#f_kaca').change(function() {
            var kode   = $('#f_kode').val();
            var ukuran = $('#f_ukuran').val();
            var kaca   = $(this).val();
            resetFrom('warna');
            if (!kode || !ukuran || !kaca) return;

            $.ajax({
                url: "<?= site_url('Orders/get_warna_by_kode_ukuran_kaca') ?>",
                type: "POST",
                data: { kode: kode, ukuran: ukuran, kaca: kaca },
                dataType: "JSON",
                success: function(data) {
                    $('#f_warna').html('<option value="">- Pilih Warna -</option>');
                    $.each(data.warna, function(i, v) {
                        $('#f_warna').append('<option value="' + v + '">' + v + '</option>');
                    });
                    $('#f_warna').prop('disabled', false);
                }
            });
        });

        // TINGKAT 4: Warna Kusen
        $('#f_warna').change(function() {
            var kode   = $('#f_kode').val();
            var ukuran = $('#f_ukuran').val();
            var kaca   = $('#f_kaca').val();
            var warna  = $(this).val();

            $('#id_hpp').val('');
            $('#hpp_saat_ini').val('');
            $('#display_hpp').text('Rp 0');
            $('#btn-simpan').prop('disabled', true);

            if (!kode || !ukuran || !kaca || !warna) return;

            $.ajax({
                url: "<?= site_url('Orders/get_hpp_by_params') ?>",
                type: "POST",
                data: { kode: kode, ukuran: ukuran, kaca: kaca, warna: warna },
                dataType: "JSON",
                success: function(data) {
                    if (data && data.id_hpp) {
                        var hppNum = parseInt(data.total_biaya_modal);
                        $('#id_hpp').val(data.id_hpp);
                        $('#hpp_saat_ini').val(hppNum);
                        $('#display_hpp').text("Rp " + hppNum.toLocaleString('id-ID'));

                        // Default recommended harga jual: HPP + Rp 50.000
                        $('#harga_jual').val(hppNum + 50000);
                        $('#btn-simpan').prop('disabled', false);
                        hitungTotal();
                    } else {
                        alert("Data HPP tidak ditemukan untuk kombinasi ini!");
                        $('#display_hpp').text("Rp 0");
                        $('#btn-simpan').prop('disabled', true);
                    }
                }
            });
        });

        // QTY Validation
        $('#qty').on('input', function() {
            var val = parseInt($(this).val()) || 0;
            if (val < 1) { $(this).val(1); val = 1; }
            if (val > 99) { $(this).val(99); val = 99; }
            hitungTotal();
        });

        $('#harga_jual').on('input', function() {
            hitungTotal();
        });

        function hitungTotal() {
            var q = parseInt($('#qty').val()) || 0;
            var h = parseInt($('#harga_jual').val()) || 0;
            var hpp = parseInt($('#hpp_saat_ini').val()) || 0;
            
            var total = q * h;
            var totalHpp = q * hpp;
            var margin = (h > 0 && hpp > 0) ? (h - hpp) : 0;

            $('#label_total').text(total.toLocaleString('id-ID'));
            $('#display_total_hpp').text("Rp " + totalHpp.toLocaleString('id-ID'));
            $('#display_margin').text("Rp " + margin.toLocaleString('id-ID'));
        }

        // Qty confirmation
        $('form').on('submit', function(e) {
            var qty = parseInt($('#qty').val()) || 0;
            if (qty > 10) {
                if (!confirm('Jumlah pesanan sebanyak ' + qty + ' unit. Apakah data sudah sesuai?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });

    });
</script>
