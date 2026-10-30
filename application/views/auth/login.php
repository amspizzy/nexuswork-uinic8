<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#0d63d8">
    <title>Login &bull; IPMS</title>
    
    <link rel="icon" href="<?= base_url('assets/img/app-logo.png'); ?>" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

    <style>
        :root {
            --blue-deep: #0245a3;
            --blue-main: #0d63d8;
            --blue-sky: #1e80ff;
            --blue-gradient: linear-gradient(155deg, #0245a3 0%, #0d63d8 50%, #1e80ff 100%);
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #ffffff !important;
        }

        /* 100% Fullscreen Split Layout */
        .auth-fullscreen-wrapper {
            display: flex;
            width: 100vw;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            position: relative;
            overflow-x: hidden;
        }

        /* Left Branding Panel with Cloud Background */
        .auth-brand-side {
            width: 45%;
            min-height: 100vh;
            background: var(--blue-gradient);
            padding: 3.5rem 3.5rem 2.5rem;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        /* Subtle ambient sparkles */
        .auth-brand-side::before {
            content: '';
            position: absolute;
            top: -120px;
            left: -120px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .auth-brand-side::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: -60px;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* The Signature Organic Cloud Wave Divider (Mirrors Reference Image) */
        .cloud-divider-container {
            position: absolute;
            top: 0;
            right: -2px;
            bottom: 0;
            width: 140px;
            height: 100%;
            pointer-events: none;
            z-index: 4;
        }
        .cloud-divider-svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Left Side Content (Mirrors "Welcome to / Rocket / Spacer" Concept) */
        .brand-center-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: auto 0;
            max-width: 400px;
            z-index: 2;
        }

        .brand-welcome-title {
            font-size: 1.55rem;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: -0.01em;
            margin-bottom: 1.5rem;
        }

        .brand-logo-container {
            width: 84px;
            height: 84px;
            margin: 0 auto 1.35rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border-radius: 22px;
            padding: 12px;
            box-shadow: 0 16px 36px rgba(2, 69, 163, 0.28), 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .brand-logo-container:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 42px rgba(2, 69, 163, 0.35);
        }
        .brand-logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .brand-title {
            font-size: 2.35rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.03em;
            margin-bottom: 0.85rem;
            line-height: 1.2;
        }

        .brand-description {
            font-size: 0.94rem;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.65;
            max-width: 360px;
            margin: 0 auto 1.5rem;
        }

        .brand-pill-tags {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .brand-pill-tag {
            font-size: 0.72rem;
            font-weight: 700;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.28);
            padding: 4px 12px;
            border-radius: 9999px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .brand-footer {
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
            z-index: 2;
        }

        /* Right Form Side */
        .auth-form-side {
            width: 55%;
            min-height: 100vh;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3.5rem 4.5rem;
            position: relative;
            z-index: 2;
        }

        .form-inner-box {
            width: 100%;
            max-width: 440px;
        }

        .form-heading {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.4rem;
            letter-spacing: -0.025em;
        }

        .form-subheading {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 2rem;
        }

        /* Modern Accessible Form Fields */
        .form-group-item {
            margin-bottom: 1.4rem;
        }

        .form-group-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
            display: block;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper .icon-prefix {
            position: absolute;
            left: 1.25rem;
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .custom-form-input {
            width: 100%;
            height: 3.2rem;
            padding: 0.5rem 1.25rem 0.5rem 3rem;
            border-radius: 9999px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.92rem;
            font-weight: 500;
            color: #0f172a;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .custom-form-input:focus {
            outline: none;
            border-color: var(--blue-main);
            box-shadow: 0 0 0 4px rgba(13, 99, 216, 0.12);
        }

        .custom-form-input:focus ~ .icon-prefix {
            color: var(--blue-main);
        }

        .btn-eye {
            position: absolute;
            right: 1.25rem;
            border: none;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            font-size: 0.95rem;
        }
        .btn-eye:hover {
            color: var(--blue-main);
        }

        /* Action Button */
        .btn-login-main {
            width: 100%;
            height: 3.2rem;
            border-radius: 9999px;
            background: var(--blue-gradient);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.96rem;
            box-shadow: 0 6px 20px rgba(13, 99, 216, 0.35);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
        }

        .btn-login-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 26px rgba(13, 99, 216, 0.45);
        }
        .btn-login-main:active {
            transform: translateY(0);
        }

        /* Divider & Google Sign-In */
        .auth-separator {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.15rem 0;
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .auth-separator::before,
        .auth-separator::after {
            content: '';
            flex: 1;
            border-bottom: 1.5px solid #e2e8f0;
        }
        .auth-separator:not(:empty)::before {
            margin-right: 0.85em;
        }
        .auth-separator:not(:empty)::after {
            margin-left: 0.85em;
        }

        .btn-google-signin {
            width: 100%;
            height: 3.2rem;
            border-radius: 9999px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.92rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 1.5rem;
        }

        .btn-google-signin:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }

        .btn-google-signin:active {
            transform: translateY(0);
        }

        .btn-google-signin:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Quick Demo Login Section */
        .quick-section {
            border-top: 1px solid #e2e8f0;
            padding-top: 1.4rem;
        }

        .quick-section-title {
            font-size: 0.74rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .demo-badge-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 600;
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-right: 8px;
            margin-bottom: 8px;
        }

        .demo-badge-btn:hover {
            background: #e0f2fe;
            border-color: #bae6fd;
            color: var(--blue-main);
            transform: translateY(-1px);
        }

        .cloud-divider-mobile {
            display: none;
        }

        /* Responsive Breakpoints (Mobile & Tablet) */
        @media (max-width: 960px) {
            .auth-fullscreen-wrapper {
                flex-direction: column;
                min-height: 100vh;
            }
            .auth-brand-side {
                width: 100%;
                min-height: auto;
                padding: 3rem 1.75rem 2.75rem;
            }
            .cloud-divider-container {
                display: none;
            }
            .cloud-divider-mobile {
                display: block;
                position: absolute;
                bottom: -1px;
                left: 0;
                width: 100%;
                height: 38px;
                pointer-events: none;
                z-index: 4;
            }
            .cloud-divider-mobile svg {
                width: 100%;
                height: 100%;
                display: block;
            }
            .brand-description,
            .brand-footer {
                display: none;
            }
            .brand-welcome-title {
                font-size: 1.25rem;
                margin-bottom: 0.85rem;
            }
            .brand-rocket-circle {
                width: 76px;
                height: 76px;
                margin-bottom: 0.85rem;
            }
            .brand-title {
                font-size: 1.75rem;
                margin-bottom: 0.25rem;
            }
            .auth-form-side {
                width: 100%;
                min-height: auto;
                padding: 2.5rem 1.75rem 3rem;
                flex-grow: 1;
            }
        }
    </style>
</head>
<body>

    <div class="auth-fullscreen-wrapper">
        
        <!-- Left Side: 100vh Fullscreen Branding Panel with Signature Cloud Edge -->
        <div class="auth-brand-side">
            <!-- Center Branding Content -->
            <div class="brand-center-content">
                <div class="brand-welcome-title">Welcome to</div>

                <div class="brand-logo-container">
                    <img src="<?= base_url('assets/img/app-logo.png'); ?>" alt="IPMS Logo">
                </div>

                <div class="brand-title">IPMS</div>

                <p class="brand-description">
                    Integrated Production Management System untuk Bengkel Fabrikasi. Menghubungkan administrasi kantor dan operasional produksi secara realtime.
                </p>

                <div class="brand-pill-tags">
                    <span class="brand-pill-tag">Realtime Sync</span>
                    <span class="brand-pill-tag">SDG 8 &amp; SDG 9</span>
                    <span class="brand-pill-tag">Bengkel Fabrikasi</span>
                </div>
            </div>

            <div class="brand-footer">
                UINIC 8.0 WEB DEVELOPMENT &nbsp;&bull;&nbsp; SDG 8 &amp; SDG 9
            </div>

            <!-- Signature 3-Layer Organic Cloud Wave Divider (Bleeds seamlessly into white right panel) -->
            <div class="cloud-divider-container">
                <svg class="cloud-divider-svg" viewBox="0 0 140 1000" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Layer 1: Dark Azure / Sky Blue Bubble Peek -->
                    <path d="M 50 0 
                             C -5 45, -5 125, 42 165 
                             C -14 205, -14 295, 38 335 
                             C -22 375, -22 470, 35 510 
                             C -16 550, -16 645, 40 685 
                             C -20 725, -20 820, 35 860 
                             C -6 900, -6 965, 45 1000 
                             L 140 1000 L 140 0 Z" 
                          fill="rgba(56, 189, 248, 0.35)" />

                    <!-- Layer 2: Soft Cyan / Azure Bubble Middle Layer -->
                    <path d="M 72 0 
                             C 18 45, 18 120, 62 160 
                             C 8 200, 8 290, 58 330 
                             C -2 370, -2 465, 55 505 
                             C 5 545, 5 640, 60 680 
                             C 0 720, 0 815, 55 855 
                             C 15 895, 15 960, 65 1000 
                             L 140 1000 L 140 0 Z" 
                          fill="rgba(186, 230, 253, 0.65)" />

                    <!-- Layer 3: Solid White Cloud Puffs (Seamlessly merges with right white form panel) -->
                    <path d="M 95 0 
                             C 42 45, 42 115, 84 155 
                             C 32 195, 32 285, 80 325 
                             C 22 365, 22 460, 78 500 
                             C 28 540, 28 635, 82 675 
                             C 24 715, 24 810, 78 850 
                             C 38 890, 38 955, 88 1000 
                             L 140 1000 L 140 0 Z" 
                          fill="#ffffff" />
                </svg>
            </div>

            <!-- Mobile Cloud Divider (Horizontal bottom wave on small screens) -->
            <div class="cloud-divider-mobile">
                <svg viewBox="0 0 1200 60" preserveAspectRatio="none">
                    <path d="M0,0 C150,45 350,45 500,10 C650,45 850,45 1000,10 C1100,35 1150,35 1200,15 L1200,60 L0,60 Z" fill="#ffffff"></path>
                </svg>
            </div>
        </div>

        <!-- Right Side: 100vh Fullscreen Form Panel -->
        <div class="auth-form-side">
            <div class="form-inner-box">
                
                <div class="form-heading">Masuk ke Akun Anda</div>
                <div class="form-subheading">Silakan masukkan username dan kata sandi untuk mengakses dashboard.</div>

                <?php if($this->session->flashdata('pesan')): ?>
                    <div class="alert alert-danger py-2 px-3 small rounded-lg mb-3">
                        <i class="fas fa-exclamation-circle mr-1"></i> <?= $this->session->flashdata('pesan'); ?>
                    </div>
                <?php endif; ?>

                <div id="googleAlert" class="py-2 px-3 small rounded-lg mb-3" style="display: none;"></div>

                <form action="<?= site_url('Auth/proses_login'); ?>" method="POST" id="mainLoginForm">
                    <!-- Username -->
                    <div class="form-group-item">
                        <label class="form-group-label" for="username">Username / ID Pengguna</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user icon-prefix"></i>
                            <input type="text" name="username" id="username" class="custom-form-input" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group-item">
                        <label class="form-group-label" for="password">Kata Sandi (Password)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock icon-prefix"></i>
                            <input type="password" name="password" id="password" class="custom-form-input" placeholder="Masukkan password" required>
                            <button type="button" class="btn-eye" onclick="togglePasswordVisibility()" title="Lihat Password">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login-main">
                        Masuk ke Dashboard
                    </button>
                </form>

                <!-- Separator & Google Sign-In Button -->
                <div class="auth-separator">
                    <span>atau masuk dengan</span>
                </div>

                <button type="button" id="btnGoogleSignIn" class="btn-google-signin">
                    <svg width="20" height="20" viewBox="0 0 18 18">
                        <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.616z"/>
                        <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z"/>
                        <path fill="#FBBC05" d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707 0-.59.102-1.167.282-1.707V4.961H.957C.347 6.175 0 7.55 0 9s.347 2.825.957 4.039l3.007-2.332z"/>
                        <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z"/>
                    </svg>
                    <span id="btnGoogleText">Masuk dengan Akun Google</span>
                </button>

                <!-- Quick Demo Login Buttons (Clean Minimalist Professional) -->
                <div class="quick-section">
                    <div class="quick-section-title">
                        Akses Akun Demo:
                    </div>
                    <div class="d-flex flex-wrap">
                        <button type="button" class="demo-badge-btn" onclick="quickFill('admin', 'admin123')">
                            Owner (admin)
                        </button>
                        <button type="button" class="demo-badge-btn" onclick="quickFill('sewelasowner', 'sewelas123')">
                            Admin (sewelasowner)
                        </button>
                        <button type="button" class="demo-badge-btn" onclick="quickFill('produksi', 'produksi')">
                            Karyawan (produksi)
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        function togglePasswordVisibility() {
            var input = document.getElementById('password');
            var icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function quickFill(user, pass) {
            document.getElementById('username').value = user;
            document.getElementById('password').value = pass;
        }
    </script>

    <!-- Firebase SDK & Google Sign-In Integration -->
    <script type="module">
        import { initializeApp, getApps, getApp } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js';
        import { getAuth, signInWithPopup, GoogleAuthProvider } from 'https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js';

        const firebaseConfig = {
            apiKey: "***",
            authDomain: "sewelasapp.firebaseapp.com",
            projectId: "sewelasapp",
            storageBucket: "sewelasapp.firebasestorage.app",
            messagingSenderId: "172933565352"
        };

        const app = getApps().length > 0 ? getApp() : initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const provider = new GoogleAuthProvider();

        const btnGoogle = document.getElementById('btnGoogleSignIn');
        const btnGoogleText = document.getElementById('btnGoogleText');
        const googleAlert = document.getElementById('googleAlert');

        if (btnGoogle) {
            btnGoogle.addEventListener('click', async () => {
                try {
                    btnGoogle.disabled = true;
                    btnGoogleText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menghubungkan Google...';
                    googleAlert.style.display = 'none';

                    const result = await signInWithPopup(auth, provider);
                    const user = result.user;
                    const idToken = await user.getIdToken();

                    btnGoogleText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengautentikasi...';

                    const formData = new FormData();
                    formData.append('id_token', idToken);
                    formData.append('uid', user.uid);
                    formData.append('email', user.email || '');
                    formData.append('name', user.displayName || '');

                    const response = await fetch('<?= site_url("Auth/google_login"); ?>', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        googleAlert.className = 'alert alert-success py-2 px-3 small rounded-lg mb-3';
                        googleAlert.textContent = data.message || 'Login berhasil! Mengalihkan...';
                        googleAlert.style.display = 'block';
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 600);
                    } else {
                        throw new Error(data.message || 'Login Google gagal diverifikasi.');
                    }
                } catch (error) {
                    console.error('Google Sign-In Error:', error);
                    btnGoogle.disabled = false;
                    btnGoogleText.innerText = 'Masuk dengan Akun Google';
                    
                    let msg = error.message;
                    if (error.code === 'auth/popup-closed-by-user') {
                        msg = 'Jendela login Google ditutup sebelum selesai.';
                    } else if (error.code === 'auth/cancelled-popup-request') {
                        msg = 'Proses login Google dibatalkan.';
                    } else if (error.code === 'auth/popup-blocked') {
                        msg = 'Popup login Google diblokir browser. Izinkan popup untuk melanjutkan.';
                    } else if (error.code === 'auth/unauthorized-domain' || (msg && msg.includes('unauthorized-domain'))) {
                        msg = 'Domain (' + window.location.hostname + ') belum diotorisasi Firebase. Silakan buka aplikasi via http://localhost' + (window.location.port ? ':' + window.location.port : '') + window.location.pathname;
                    }

                    googleAlert.className = 'alert alert-danger py-2 px-3 small rounded-lg mb-3';
                    googleAlert.textContent = msg;
                    googleAlert.style.display = 'block';
                }
            });
        }
    </script>

</body>
</html>
