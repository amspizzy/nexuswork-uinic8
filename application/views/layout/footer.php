    </div>
    <!-- End of Main Content (#content) -->

    <!-- Footer -->
    <footer class="sticky-footer">
        <div class="container my-auto">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between small text-muted">
                <div class="mb-2 mb-md-0">
                    <strong>IPMS &bull; Production System</strong> &copy; <?= date('Y'); ?>. All Rights Reserved.
                    <span class="mx-2 text-muted">&bull;</span>
                    <span class="text-primary font-weight-bold">UINIC 8.0 Web Development Competition</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="footer-sdg-badge footer-sdg-8 mr-1" title="SDG 8: Decent Work and Economic Growth">
                        <i class="fas fa-chart-line"></i> SDG 8
                    </span>
                    <span class="footer-sdg-badge footer-sdg-9 mr-2" title="SDG 9: Industry, Innovation and Infrastructure">
                        <i class="fas fa-industry"></i> SDG 9
                    </span>
                    <span class="badge badge-soft-secondary font-weight-bold">IPMS Enterprise</span>
                </div>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper (#content-wrapper) -->

</div>
<!-- End of Page Wrapper (#wrapper) -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded shadow" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="logoutModalLabel">
                    Konfirmasi Logout
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-gray-700">
                Apakah Anda yakin ingin mengakhiri sesi kerja saat ini di <strong>IPMS</strong>? Seluruh progres tersimpan secara realtime di sistem.
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    Batal
                </button>
                <a class="btn btn-danger" href="<?= site_url('Auth/logout'); ?>">
                    Ya, Logout
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pengaturan Tautkan Akun Google -->
<div class="modal fade" id="modalGoogleLink" tabindex="-1" role="dialog" aria-labelledby="modalGoogleLinkLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalGoogleLinkLabel">
                    Pengaturan Tautan Google
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark">
                <!-- Status Box -->
                <div class="p-3 rounded-lg mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small font-weight-bold text-muted text-uppercase letter-spacing-1">Status Tautan</span>
                        <span id="googleLinkBadge" class="badge badge-soft-secondary font-weight-bold px-2 py-1">Memeriksa...</span>
                    </div>
                    <div id="googleLinkEmailDisplay" class="font-weight-bold text-dark" style="font-size: 0.95rem;">-</div>
                </div>

                <!-- Domain 127.0.0.1 Notice -->
                <div id="googleLinkDomainNotice" class="alert alert-warning py-2 px-3 small rounded-lg mb-3" style="display: none;">
                    <strong>Perhatian Host:</strong> Anda membuka aplikasi via IP <code id="googleLinkHostText">127.0.0.1</code>. Firebase Google Sign-In mewajibkan domain <code>localhost</code>.
                    <div class="mt-2">
                        <a href="#" id="btnSwitchToLocalhost" class="btn btn-warning btn-sm font-weight-bold py-1 px-3">
                            Beralih ke Localhost
                        </a>
                    </div>
                </div>

                <div id="googleLinkAlert" class="alert py-2 px-3 small rounded-lg mb-3" style="display: none;"></div>

                <p class="small text-muted mb-3" id="googleLinkDesc">
                    Kaitkan akun Google Anda untuk memungkinkan login cepat 1-klik di halaman masuk tanpa perlu memasukkan password.
                </p>

                <!-- Action Button Area (OAuth 1-klik) -->
                <div id="googleLinkActionArea">
                    <button type="button" id="btnLinkGoogleAccount" class="btn btn-primary btn-block py-2 font-weight-bold d-flex align-items-center justify-content-center shadow-sm">
                        <svg width="18" height="18" viewBox="0 0 18 18" class="mr-2">
                            <path fill="#ffffff" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.616z"/>
                            <path fill="#ffffff" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z"/>
                            <path fill="#ffffff" d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707 0-.59.102-1.167.282-1.707V4.961H.957C.347 6.175 0 7.55 0 9s.347 2.825.957 4.039l3.007-2.332z"/>
                            <path fill="#ffffff" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z"/>
                        </svg>
                        <span id="btnLinkGoogleText">Tautkan via Google Sign-In</span>
                    </button>
                </div>

                <!-- Secondary Option: Manual Email Binding Form -->
                <div id="googleEmailManualSection" class="mt-3 pt-3 border-top">
                    <div class="small font-weight-bold text-muted mb-2">Atau Tautkan via Alamat Email Google:</div>
                    <div class="input-group">
                        <input type="email" id="inputGoogleEmail" class="form-control form-control-sm" placeholder="contoh@gmail.com">
                        <div class="input-group-append">
                            <button type="button" id="btnSaveGoogleEmail" class="btn btn-outline-primary btn-sm font-weight-bold">
                                Simpan
                            </button>
                        </div>
                    </div>
                    <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">
                        Setelah email disimpan, Anda dapat login menggunakan tombol Google Sign-In dengan email tersebut.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Minimalist Toast Notification Container -->
<div id="nw-toast"><i class="fas fa-check-circle toast-icon mr-2"></i><span id="nw-toast-msg"></span></div>

<!-- Core Scripts -->
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>

<script>
    // Global Plugin Initializer
    function initPagePlugins() {
        if (window.jQuery && $.fn.DataTable) {
            if ($('#dataTable').length && !$.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable({
                    "order": [],
                    "deferRender": true,
                    "pageLength": 10,
                    "language": {
                        "search": "Cari data:",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "info": "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                        "infoEmpty": "Tidak ada data yang tersedia",
                        "infoFiltered": "(difilter dari total _MAX_ data)",
                        "zeroRecords": "Data tidak ditemukan",
                        "paginate": {
                            "first": "Awal",
                            "last": "Akhir",
                            "next": "Selanjutnya",
                            "previous": "Sebelumnya"
                        }
                    }
                });
            }
        }
    }

    $(document).ready(function() {
        initPagePlugins();
    });

    // =========================================================================
    // Instant SPA Navigation Engine with Hover Preload & 0ms Latency
    // =========================================================================
    (function() {
        var pageCache = new Map();
        var isNavigating = false;
        var progressBar = document.getElementById('nw-top-progress');

        function progressStart() {
            if (!progressBar) return;
            progressBar.style.opacity = '1';
            progressBar.style.width = '35%';
            setTimeout(function() {
                if (isNavigating && progressBar) progressBar.style.width = '70%';
            }, 60);
        }

        function progressFinish() {
            if (!progressBar) return;
            progressBar.style.width = '100%';
            setTimeout(function() {
                progressBar.style.opacity = '0';
                setTimeout(function() {
                    progressBar.style.width = '0%';
                    progressBar.style.opacity = '1';
                }, 200);
            }, 120);
        }

        function progressReset() {
            if (!progressBar) return;
            progressBar.style.opacity = '0';
            progressBar.style.width = '0%';
        }

        // Complete initial page load progress
        if (progressBar) {
            progressFinish();
        }

        // Global Cache Invalidation (Call after status update, submit, etc.)
        window.clearNwPageCache = function() {
            pageCache.clear();
        };

        function isEligibleLink(a) {
            if (!a) return false;
            var href = a.getAttribute('href');
            if (!href) return false;
            if (a.getAttribute('target') === '_blank') return false;
            if (a.getAttribute('data-toggle') === 'modal') return false;
            if (a.hasAttribute('download')) return false;
            if (href.startsWith('javascript:') || href.startsWith('#')) return false;
            if (href.indexOf('Auth/logout') !== -1) return false;
            if (href.indexOf('invoice') !== -1) return false;
            if (href.indexOf('print') !== -1) return false;

            try {
                var url = new URL(href, window.location.origin);
                return url.origin === window.location.origin;
            } catch (e) {
                return false;
            }
        }

        // Instant Preload on Hover / Touch
        function prefetch(url) {
            if (!url || pageCache.has(url)) return;
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(res) {
                    if (res.ok) return res.text();
                    throw new Error('Fetch failed');
                })
                .then(function(html) {
                    pageCache.set(url, { html: html, time: Date.now() });
                })
                .catch(function() {});
        }

        document.addEventListener('mouseover', function(e) {
            var a = e.target.closest('a');
            if (a && isEligibleLink(a)) {
                var targetUrl = new URL(a.getAttribute('href'), window.location.origin).href;
                prefetch(targetUrl);
            }
        }, { passive: true });

        document.addEventListener('touchstart', function(e) {
            var a = e.target.closest('a');
            if (a && isEligibleLink(a)) {
                var targetUrl = new URL(a.getAttribute('href'), window.location.origin).href;
                prefetch(targetUrl);
            }
        }, { passive: true });

        // Update active class on sidebar items instantly
        function updateSidebarActive(targetUrl) {
            var targetPath = new URL(targetUrl, window.location.origin).pathname.replace(/\/+$/, '');
            var items = document.querySelectorAll('#accordionSidebar .nav-item');
            items.forEach(function(item) {
                var link = item.querySelector('.nav-link');
                if (link) {
                    var linkPath = new URL(link.getAttribute('href'), window.location.origin).pathname.replace(/\/+$/, '');
                    if (linkPath === targetPath) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                }
            });
        }

        // Navigate without full page reload
        window.nwNavigate = function(targetUrl, pushState) {
            if (targetUrl === window.location.href) return;
            if (isNavigating) return;
            isNavigating = true;

            progressStart();
            updateSidebarActive(targetUrl);

            var cached = pageCache.get(targetUrl);
            var fetchPromise;

            if (cached && (Date.now() - cached.time < 60000)) {
                fetchPromise = Promise.resolve(cached.html);
            } else {
                fetchPromise = fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function(res) {
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        return res.text();
                    })
                    .then(function(html) {
                        pageCache.set(targetUrl, { html: html, time: Date.now() });
                        return html;
                    });
            }

            fetchPromise.then(function(html) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');
                var newContent = doc.getElementById('content');
                var currentContent = document.getElementById('content');

                if (!newContent || !currentContent) {
                    window.location.href = targetUrl;
                    return;
                }

                if (doc.title) {
                    document.title = doc.title;
                }

                // Smooth instant micro-fade
                currentContent.classList.add('nw-page-transitioning');

                setTimeout(function() {
                    currentContent.innerHTML = newContent.innerHTML;
                    currentContent.classList.remove('nw-page-transitioning');
                    currentContent.classList.add('nw-page-enter');

                    setTimeout(function() {
                        currentContent.classList.remove('nw-page-enter');
                    }, 180);

                    // Execute page-level scripts
                    var scripts = currentContent.querySelectorAll('script');
                    scripts.forEach(function(oldScript) {
                        var newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(function(attr) {
                            newScript.setAttribute(attr.name, attr.value);
                        });
                        if (oldScript.innerHTML) {
                            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        }
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });

                    // Re-init plugins
                    initPagePlugins();

                    if (pushState !== false) {
                        window.history.pushState({ url: targetUrl }, doc.title, targetUrl);
                    }

                    window.scrollTo({ top: 0, behavior: 'instant' });
                    progressFinish();
                    isNavigating = false;
                }, 70);
            }).catch(function(err) {
                console.warn('Fallback to standard navigation:', err);
                progressReset();
                isNavigating = false;
                window.location.href = targetUrl;
            });
        };

        // Intercept clicks on internal links
        document.addEventListener('click', function(e) {
            var a = e.target.closest('a');
            if (a && isEligibleLink(a)) {
                var targetUrl = new URL(a.getAttribute('href'), window.location.origin).href;
                if (targetUrl === window.location.href) {
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                window.nwNavigate(targetUrl, true);
            }
        });

        // Handle Browser Back / Forward buttons
        window.addEventListener('popstate', function() {
            window.nwNavigate(window.location.href, false);
        });
    })();

    // Minimalist Toast Notification Function
    window.showNwToast = function(msg) {
        var toast = document.getElementById('nw-toast');
        var toastMsg = document.getElementById('nw-toast-msg');
        if (!toast || !toastMsg) return;
        toastMsg.textContent = msg;
        toast.classList.add('show');
        clearTimeout(window._nwToastTimer);
        window._nwToastTimer = setTimeout(function() {
            toast.classList.remove('show');
        }, 2800);
    };
</script>

<!-- Firebase SDK & Google Linking Integration -->
<script type="module">
    import { initializeApp, getApps, getApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
    import { getAuth, signInWithPopup, GoogleAuthProvider } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js';

    const firebaseConfig = {
        apiKey: "AIzaSyAGpqUjwwkPHVPoGAWbwQt4n4N3Tx5u1og",
        authDomain: "sewelasapp.firebaseapp.com",
        projectId: "sewelasapp",
        storageBucket: "sewelasapp.firebasestorage.app",
        messagingSenderId: "172933565352"
    };

    const app = getApps().length > 0 ? getApp() : initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const provider = new GoogleAuthProvider();

    // Check host domain (127.0.0.1 warning)
    function checkHostDomain() {
        const domainNotice = document.getElementById('googleLinkDomainNotice');
        const hostText = document.getElementById('googleLinkHostText');
        const btnSwitch = document.getElementById('btnSwitchToLocalhost');
        if (domainNotice && hostText) {
            if (window.location.hostname === '127.0.0.1') {
                hostText.textContent = window.location.hostname;
                domainNotice.style.display = 'block';
                if (btnSwitch) {
                    const port = window.location.port ? ':' + window.location.port : '';
                    btnSwitch.href = window.location.protocol + '//localhost' + port + window.location.pathname + window.location.search;
                }
            } else {
                domainNotice.style.display = 'none';
            }
        }
    }

    const badge = document.getElementById('googleLinkBadge');
    const emailDisplay = document.getElementById('googleLinkEmailDisplay');
    const desc = document.getElementById('googleLinkDesc');
    const alertBox = document.getElementById('googleLinkAlert');
    const actionArea = document.getElementById('googleLinkActionArea');

    function renderLinkedUI(email) {
        if (badge) {
            badge.className = 'badge badge-soft-success font-weight-bold px-2 py-1';
            badge.textContent = 'Terhubung';
        }
        if (emailDisplay) emailDisplay.textContent = email || 'Akun Google Terhubung';
        if (desc) desc.textContent = 'Akun Google Anda telah terhubung. Anda sekarang dapat masuk ke IPMS langsung menggunakan tombol "Masuk dengan Akun Google" di halaman login.';
        if (actionArea) {
            actionArea.innerHTML = `
                <button type="button" id="btnUnlinkGoogleAccount" class="btn btn-outline-danger btn-block py-2 font-weight-bold">
                    Putuskan Tautan Akun Google
                </button>
            `;
        }
    }

    function renderUnlinkedUI() {
        if (badge) {
            badge.className = 'badge badge-soft-secondary font-weight-bold px-2 py-1';
            badge.textContent = 'Belum Terhubung';
        }
        if (emailDisplay) emailDisplay.textContent = 'Belum ada akun Google yang dikaitkan';
        if (desc) desc.textContent = 'Kaitkan akun Google Anda untuk memungkinkan login cepat 1-klik di halaman masuk tanpa perlu memasukkan password.';
        if (actionArea) {
            actionArea.innerHTML = `
                <button type="button" id="btnLinkGoogleAccount" class="btn btn-primary btn-block py-2 font-weight-bold d-flex align-items-center justify-content-center shadow-sm">
                    <svg width="18" height="18" viewBox="0 0 18 18" class="mr-2">
                        <path fill="#ffffff" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.616z"/>
                        <path fill="#ffffff" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z"/>
                        <path fill="#ffffff" d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707 0-.59.102-1.167.282-1.707V4.961H.957C.347 6.175 0 7.55 0 9s.347 2.825.957 4.039l3.007-2.332z"/>
                        <path fill="#ffffff" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z"/>
                    </svg>
                    <span id="btnLinkGoogleText">Tautkan via Google Sign-In</span>
                </button>
            `;
        }
    }

    async function refreshGoogleStatus() {
        checkHostDomain();
        try {
            const res = await fetch('<?= site_url("Auth/get_google_status"); ?>');
            const data = await res.json();
            if (data.status === 'success') {
                if (data.linked) {
                    renderLinkedUI(data.email);
                } else {
                    renderUnlinkedUI();
                }
            }
        } catch (e) {
            console.warn('Gagal memuat status Google:', e);
            renderUnlinkedUI();
        }
    }

    // Expose handlers globally to ensure delegation works seamlessly
    window._nwLastGoogleAuth = null;

    window.nwHandleGoogleLink = async function(forceTransfer = false) {
        const btn = document.getElementById('btnLinkGoogleAccount');
        const txt = document.getElementById('btnLinkGoogleText');
        const alertBox = document.getElementById('googleLinkAlert');

        try {
            if (btn) btn.disabled = true;
            if (txt) txt.textContent = forceTransfer ? 'Memindahkan tautan...' : 'Membuka Google Sign-In...';
            if (alertBox) alertBox.style.display = 'none';

            let user, idToken;
            if (forceTransfer && window._nwLastGoogleAuth) {
                user = window._nwLastGoogleAuth.user;
                idToken = window._nwLastGoogleAuth.idToken;
            } else {
                const result = await signInWithPopup(auth, provider);
                user = result.user;
                idToken = await user.getIdToken();
                window._nwLastGoogleAuth = { user: user, idToken: idToken };
            }

            if (txt) txt.textContent = 'Menyimpan tautan akun...';

            const formData = new FormData();
            formData.append('uid', user.uid);
            formData.append('email', user.email || '');
            formData.append('name', user.displayName || '');
            formData.append('id_token', idToken);
            if (forceTransfer) {
                formData.append('force_transfer', '1');
            }

            const res = await fetch('<?= site_url("Auth/link_google"); ?>', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (res.status === 409 || data.status === 'conflict') {
                if (alertBox) {
                    alertBox.className = 'alert alert-warning py-2 px-3 small rounded-lg mb-3';
                    alertBox.innerHTML = `
                        <div class="font-weight-bold mb-1">Pindahkan Tautan Akun?</div>
                        <div class="mb-2">${data.message}</div>
                        <button type="button" class="btn btn-warning btn-sm font-weight-bold" onclick="window.nwHandleGoogleLink(true)">
                            Ya, Pindahkan ke Akun Ini
                        </button>
                    `;
                    alertBox.style.display = 'block';
                }
                if (btn) btn.disabled = false;
                if (txt) txt.textContent = 'Tautkan via Google Sign-In';
                return;
            }

            if (data.status === 'success') {
                if (alertBox) {
                    alertBox.className = 'alert alert-success py-2 px-3 small rounded-lg mb-3';
                    alertBox.textContent = data.message;
                    alertBox.style.display = 'block';
                }
                renderLinkedUI(data.email || user.email);
                if (typeof showNwToast === 'function') {
                    showNwToast('Akun Google berhasil ditautkan!');
                }
            } else {
                throw new Error(data.message || 'Gagal menautkan akun Google.');
            }
        } catch (err) {
            console.error('Link Google Error:', err);
            if (btn) btn.disabled = false;
            if (txt) txt.textContent = 'Tautkan via Google Sign-In';

            let msg = err.message || 'Terjadi kesalahan saat otentikasi Google.';
            if (err.code === 'auth/popup-closed-by-user') {
                msg = 'Jendela popup Google ditutup sebelum selesai.';
            } else if (err.code === 'auth/popup-blocked') {
                msg = 'Popup Google diblokir oleh browser. Izinkan popup untuk domain ini atau gunakan opsi Simpan Email Google di bawah.';
            } else if (err.code === 'auth/unauthorized-domain' || (msg && msg.includes('unauthorized-domain'))) {
                msg = 'Domain (' + window.location.hostname + ') belum terotorisasi di Firebase. Silakan akses aplikasi via http://localhost' + (window.location.port ? ':' + window.location.port : '') + window.location.pathname + ' atau gunakan opsi Simpan Email Google di bawah.';
            }

            if (alertBox) {
                alertBox.className = 'alert alert-danger py-2 px-3 small rounded-lg mb-3';
                alertBox.textContent = msg;
                alertBox.style.display = 'block';
            }
        }
    };

    window.nwHandleGoogleUnlink = async function() {
        if (!confirm('Apakah Anda yakin ingin memutuskan tautan akun Google dari akun Anda? Setelah diputuskan, Anda tidak dapat login menggunakan Google Sign-In sebelum menautkannya kembali.')) {
            return;
        }

        const btn = document.getElementById('btnUnlinkGoogleAccount');
        const alertBox = document.getElementById('googleLinkAlert');
        try {
            if (btn) {
                btn.disabled = true;
                btn.textContent = 'Memutuskan tautan...';
            }
            if (alertBox) alertBox.style.display = 'none';

            const res = await fetch('<?= site_url("Auth/unlink_google"); ?>', { method: 'POST' });
            const data = await res.json();

            if (data.status === 'success') {
                if (alertBox) {
                    alertBox.className = 'alert alert-info py-2 px-3 small rounded-lg mb-3';
                    alertBox.textContent = data.message;
                    alertBox.style.display = 'block';
                }
                renderUnlinkedUI();
                if (typeof showNwToast === 'function') {
                    showNwToast('Tautan akun Google diputuskan.');
                }
            } else {
                throw new Error(data.message || 'Gagal memutuskan tautan.');
            }
        } catch (err) {
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Putuskan Tautan Akun Google';
            }
            if (alertBox) {
                alertBox.className = 'alert alert-danger py-2 px-3 small rounded-lg mb-3';
                alertBox.textContent = err.message;
                alertBox.style.display = 'block';
            }
        }
    };

    window.nwHandleSaveGoogleEmail = async function(forceTransfer = false) {
        const input = document.getElementById('inputGoogleEmail');
        const btn = document.getElementById('btnSaveGoogleEmail');
        const alertBox = document.getElementById('googleLinkAlert');

        const email = input ? input.value.trim() : '';
        if (!email) {
            if (alertBox) {
                alertBox.className = 'alert alert-warning py-2 px-3 small rounded-lg mb-3';
                alertBox.textContent = 'Harap masukkan alamat email Google Anda.';
                alertBox.style.display = 'block';
            }
            return;
        }

        try {
            if (btn) btn.disabled = true;
            if (alertBox) alertBox.style.display = 'none';

            const formData = new FormData();
            formData.append('email', email);
            if (forceTransfer) formData.append('force_transfer', '1');

            const res = await fetch('<?= site_url("Auth/link_google_email"); ?>', {
                method: 'POST',
                body: formData
            });

            const data = await res.json();

            if (res.status === 409 || data.status === 'conflict') {
                if (alertBox) {
                    alertBox.className = 'alert alert-warning py-2 px-3 small rounded-lg mb-3';
                    alertBox.innerHTML = `
                        <div class="font-weight-bold mb-1">Pindahkan Tautan Email?</div>
                        <div class="mb-2">${data.message}</div>
                        <button type="button" class="btn btn-warning btn-sm font-weight-bold" onclick="window.nwHandleSaveGoogleEmail(true)">
                            Ya, Pindahkan ke Akun Ini
                        </button>
                    `;
                    alertBox.style.display = 'block';
                }
                if (btn) btn.disabled = false;
                return;
            }

            if (data.status === 'success') {
                if (alertBox) {
                    alertBox.className = 'alert alert-success py-2 px-3 small rounded-lg mb-3';
                    alertBox.textContent = data.message;
                    alertBox.style.display = 'block';
                }
                if (input) input.value = '';
                renderLinkedUI(data.email);
                if (typeof showNwToast === 'function') {
                    showNwToast('Email Google berhasil ditautkan!');
                }
            } else {
                throw new Error(data.message || 'Gagal menyimpan email Google.');
            }
        } catch (err) {
            if (alertBox) {
                alertBox.className = 'alert alert-danger py-2 px-3 small rounded-lg mb-3';
                alertBox.textContent = err.message;
                alertBox.style.display = 'block';
            }
        } finally {
            if (btn) btn.disabled = false;
        }
    };

    // Event Delegation using jQuery / Document
    $(document).on('click', '#btnLinkGoogleAccount', function() {
        window.nwHandleGoogleLink(false);
    });

    $(document).on('click', '#btnUnlinkGoogleAccount', function() {
        window.nwHandleGoogleUnlink();
    });

    $(document).on('click', '#btnSaveGoogleEmail', function() {
        window.nwHandleSaveGoogleEmail(false);
    });

    // Modal show event
    $('#modalGoogleLink').on('show.bs.modal', function() {
        if (alertBox) alertBox.style.display = 'none';
        refreshGoogleStatus();
    });

    // Initial check
    refreshGoogleStatus();
</script>
</script>

</body>
</html>
