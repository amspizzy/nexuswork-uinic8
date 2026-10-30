<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar: Minimalist Clean Header (Mirrors Reference Mockup) -->
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

            <!-- Sidebar Toggle (Mobile) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-secondary">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Pill Search Bar (Mirrors Reference Mockup) -->
            <form action="<?= site_url('Orders/list'); ?>" method="GET" class="d-none d-sm-inline-block form-inline mr-auto my-2 my-md-0">
                <div class="topbar-pill-search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="search" placeholder="Cari SPK, produk, atau pelanggan... (Ctrl+K)"
                        aria-label="Search">
                </div>
            </form>

            <!-- Topbar Center / Right Navigation Icons -->
            <ul class="navbar-nav ml-auto align-items-center">

                <!-- Mobile Search Toggle -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                        aria-labelledby="searchDropdown">
                        <form action="<?= site_url('Orders/list'); ?>" method="GET" class="form-inline mr-auto w-100">
                            <input type="text" name="search" class="form-control form-control-sm w-100" placeholder="Cari SPK atau Nama...">
                        </form>
                    </div>
                </li>

                <!-- Low Stock Notifications Dropdown -->
                <?php
                    $ci =& get_instance();
                    $ci->load->database();
                    $low_stock_items = $ci->db->where('stok <', 10)->get('inventory')->result();
                    $low_stock_count = count($low_stock_items);
                ?>
                <li class="nav-item dropdown no-arrow mx-1">
                    <button type="button" class="topbar-circle-btn dropdown-toggle" id="alertsDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Peringatan Stok">
                        <i class="far fa-bell"></i>
                        <?php if ($low_stock_count > 0): ?>
                            <span class="btn-badge-dot"></span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="alertsDropdown" style="width: 320px;">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <span>Notifikasi Bahan Baku</span>
                            <span class="badge badge-soft-warning"><?= $low_stock_count ?> Alert</span>
                        </div>
                        <?php if ($low_stock_count > 0): ?>
                            <div style="max-height: 250px; overflow-y: auto;">
                                <?php 
                                $show_items = array_slice($low_stock_items, 0, 5);
                                foreach ($show_items as $item): ?>
                                    <a class="dropdown-item d-flex align-items-center py-2" href="<?= site_url('Inventory') ?>">
                                        <div class="mr-3">
                                            <span class="badge badge-soft-warning font-weight-bold px-2 py-1" style="font-size: 0.72rem;">Alert</span>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Sisa Stok: <strong class="text-danger"><?= $item->stok ?> <?= isset($item->satuan) ? $item->satuan : 'unit' ?></strong></div>
                                            <span class="font-weight-bold text-dark small">
                                                <?= isset($item->ukuran) ? $item->ukuran . ' CM' : 'Item' ?> 
                                                (<?= isset($item->tipe) ? ($item->tipe == 'kusen_pivot' ? 'Pivot' : 'Kusen M') : 'Umum' ?>)
                                            </span>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="p-3 text-center text-muted small">
                                <i class="fas fa-check-circle text-success mb-2 d-block fa-2x"></i>
                                Semua stok bahan baku dalam batas aman.
                            </div>
                        <?php endif; ?>
                        <div class="p-2 border-top bg-light text-center">
                            <a class="btn btn-sm btn-outline-primary btn-block text-center" style="font-size: 0.78rem;" href="<?= site_url('Inventory') ?>">
                                Buka Manajemen Stok
                            </a>
                        </div>
                    </div>
                </li>

                <!-- Help / Documentation Link (Mirrors ? icon in reference mockup) -->
                <li class="nav-item mx-1 d-none d-md-block">
                    <a href="<?= site_url('Orders/list'); ?>" class="topbar-circle-btn" title="Panduan & Monitoring Alur">
                        <i class="far fa-question-circle"></i>
                    </a>
                </li>

                <!-- Settings / Activity Log (Mirrors cog icon in reference mockup) -->
                <?php if ($this->session->userdata('role') == 'owner'): ?>
                <li class="nav-item mx-1 d-none d-md-block">
                    <a href="<?= site_url('ActivityLog'); ?>" class="topbar-circle-btn" title="Activity Audit Trail">
                        <i class="fas fa-cog"></i>
                    </a>
                </li>
                <?php endif; ?>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- User Profile (Mirrors "Jessica [J]" Circular Avatar Initial from reference mockup) -->
                <?php
                    $nama_user = $this->session->userdata('nama_user') ?: 'User';
                    $user_initial = strtoupper(substr($nama_user, 0, 1));
                    $user_role = strtolower($this->session->userdata('role') ?: 'guest');
                ?>
                <li class="nav-item dropdown no-arrow ml-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center py-0" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="d-flex flex-column text-right mr-3 d-none d-lg-flex">
                            <span class="font-weight-bold text-dark" style="font-size: 0.88rem; line-height: 1.2;">
                                <?= $nama_user; ?>
                            </span>
                            <span class="user-role-badge role-<?= $user_role; ?> mt-1 align-self-end">
                                <?= strtoupper($user_role); ?>
                            </span>
                        </div>
                        <div class="user-avatar-initial" title="<?= $nama_user; ?>">
                            <?= $user_initial; ?>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                            Login sebagai <strong><?= $nama_user; ?></strong>
                        </div>
                        <?php if($this->session->userdata('role') == 'owner'): ?>
                        <a class="dropdown-item" href="<?= site_url('ActivityLog'); ?>">
                            Activity Log
                        </a>
                        <a class="dropdown-item" href="<?= site_url('Laporan'); ?>">
                            Laporan Keuangan
                        </a>
                        <?php endif; ?>
                        <a class="dropdown-item" href="<?= site_url('Orders/list'); ?>">
                            Monitoring Order
                        </a>
                        <a class="dropdown-item font-weight-bold text-primary" href="#" data-toggle="modal" data-target="#modalGoogleLink">
                            Tautkan Akun Google
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                            Keluar (Logout)
                        </a>
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->
