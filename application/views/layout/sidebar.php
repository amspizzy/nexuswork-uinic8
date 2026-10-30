<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">

    <!-- Brand Header: Official Logo & Minimalist Typography -->
    <a class="sidebar-brand d-flex align-items-center justify-content-start px-3" href="<?= site_url('dashboard'); ?>">
        <img src="<?= base_url('assets/img/app-logo.png'); ?>" alt="IPMS Logo" class="sidebar-brand-logo mr-2">
        <div class="sidebar-brand-text">
            <span>IPMS</span>
        </div>
    </a>

    <!-- Blue Curved Sidebar Panel: Signature Google Drive Mockup Curved Silhouette -->
    <div class="sidebar-blue-panel">
        <div>
            <!-- White Pill CTA Button: Mirrors "Upload New File" from Reference Mockup -->
            <?php if ($this->session->userdata('role') == 'owner' || $this->session->userdata('role') == 'admin') : ?>
                <a href="<?= site_url('Orders'); ?>" class="btn-sidebar-cta">
                    <i class="fas fa-plus"></i>
                    <span>Buat SPK Baru</span>
                </a>
            <?php endif; ?>

            <!-- Navigation Menu Items -->
            <?php if ($this->session->userdata('role') == 'owner' || $this->session->userdata('role') == 'admin') : ?>
                <li class="nav-item <?= ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= site_url('dashboard'); ?>">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard Overview</span>
                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item <?= ($this->uri->segment(1) == 'Orders' && $this->uri->segment(2) == 'list') ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= site_url('Orders/list'); ?>">
                    <i class="fas fa-stream"></i>
                    <span>Monitoring Produksi</span>
                </a>
            </li>

            <?php if ($this->session->userdata('role') == 'owner' || $this->session->userdata('role') == 'admin') : ?>
                <li class="nav-item <?= ($this->uri->segment(1) == 'Orders' && $this->uri->segment(2) == 'history') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= site_url('Orders/history'); ?>">
                        <i class="fas fa-check-circle"></i>
                        <span>Riwayat Diterima</span>
                    </a>
                </li>

                <li class="nav-item <?= ($this->uri->segment(1) == 'Orders' && $this->uri->segment(2) == 'history_cancel') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= site_url('Orders/history_cancel'); ?>">
                        <i class="fas fa-ban"></i>
                        <span>Riwayat Batal</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($this->session->userdata('role') == 'owner' || $this->session->userdata('role') == 'admin') : ?>
                <div class="sidebar-section-title">Data Induk</div>

                <li class="nav-item <?= ($this->uri->segment(1) == 'MasterHpp') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= site_url('MasterHpp'); ?>">
                        <i class="fas fa-tags"></i>
                        <span>Daftar Harga HPP</span>
                    </a>
                </li>

                <li class="nav-item <?= ($this->uri->segment(1) == 'Inventory') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= site_url('Inventory'); ?>">
                        <i class="fas fa-boxes"></i>
                        <span>Stok Gudang</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($this->session->userdata('role') == 'owner') : ?>
                <div class="sidebar-section-title">Laporan &amp; Audit</div>

                <li class="nav-item <?= ($this->uri->segment(1) == 'Laporan') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url('Laporan'); ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>

                <li class="nav-item <?= ($this->uri->segment(1) == 'ActivityLog') ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?= base_url('ActivityLog'); ?>">
                        <i class="fas fa-history"></i>
                        <span>Activity Audit Trail</span>
                    </a>
                </li>
            <?php endif; ?>
        </div>

        <!-- Storage & System Status Widget (Mirrors "STORAGE DETAILS" from Reference Mockup) -->
        <div class="sidebar-storage-box">
            <!-- Active User Role Indicator -->
            <div class="sidebar-user-chip mb-2">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-white font-weight-bold" style="font-size: 0.74rem; text-transform: uppercase; letter-spacing: 0.04em;">
                        <?= $this->session->userdata('role') ?: 'Operator'; ?>
                    </span>
                    <span class="badge badge-light px-2 py-1" style="font-size: 0.65rem; color: #1562d6; font-weight: 800;">
                        <?= $this->session->userdata('username') ?: 'Online'; ?>
                    </span>
                </div>
            </div>

            <div class="storage-box-label">SISTEM &amp; DATABASE</div>
            <div class="mb-2">
                <div class="d-flex align-items-center justify-content-between mb-1 text-white" style="font-size: 0.76rem;">
                    <span class="d-flex align-items-center">
                        <i class="fas fa-database mr-2 text-white-50" style="font-size: 0.72rem;"></i>
                        <span>MySQL DB</span>
                    </span>
                    <span style="font-size: 0.65rem; color: rgba(255,255,255,0.85); font-weight: 600;">Active &bull; db_sewelas</span>
                </div>
                <div class="progress" style="height: 3px; background: rgba(255,255,255,0.22); border-radius: 999px;">
                    <div class="progress-bar bg-white" style="width: 85%;"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="d-flex align-items-center justify-content-between mb-1 text-white" style="font-size: 0.76rem;">
                    <span class="d-flex align-items-center">
                        <i class="fas fa-satellite-dish mr-2 text-white-50" style="font-size: 0.72rem;"></i>
                        <span>Firebase Sync</span>
                    </span>
                    <span style="font-size: 0.65rem; color: #86efac; font-weight: 700;">Realtime Live</span>
                </div>
                <div class="progress" style="height: 3px; background: rgba(255,255,255,0.22); border-radius: 999px;">
                    <div class="progress-bar bg-white" style="width: 100%;"></div>
                </div>
            </div>
            <a href="<?= site_url('ActivityLog'); ?>" class="storage-link">
                Status Sistem &bull; IPMS
            </a>
        </div>

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center mt-2">
            <button class="rounded-circle border-0" id="sidebarToggle" title="Toggle Sidebar"></button>
        </div>

    </div>

</ul>
