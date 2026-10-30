<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, Arial, sans-serif; 
            margin: 0; 
            padding: 24px; 
            color: #1e293b; 
            background: #f1f5f9; 
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .invoice-box { 
            max-width: 820px; 
            margin: auto; 
            padding: 44px; 
            border: 1px solid #e2e8f0; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08); 
            font-size: 14px; 
            line-height: 22px; 
            position: relative; 
            overflow: hidden; 
            background: #fff; 
            border-radius: 16px; 
        }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 10px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 24px; }
        .invoice-box table tr.top table td.title { font-size: 28px; line-height: 34px; color: #0f172a; font-weight: 800; }
        .invoice-box table tr.information table td { padding-bottom: 30px; }
        .invoice-box table tr.heading td { 
            background: #f8fafc; 
            border-bottom: 2px solid #e2e8f0; 
            border-top: 1px solid #e2e8f0;
            font-weight: 700; 
            color: #475569;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }
        .invoice-box table tr.item td { border-bottom: 1px solid #f1f5f9; padding: 14px 10px; }
        .invoice-box table tr.item.last td { border-bottom: 2px solid #e2e8f0; }
        .invoice-box table tr.total td:nth-child(2) { 
            border-top: 2px solid #0f172a; 
            font-weight: 800; 
            font-size: 18px; 
            color: #0d63d8;
            padding-top: 14px;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        /* Watermark Styles */
        .watermark {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 72px;
            color: rgba(239, 68, 68, 0.06);
            font-weight: 900;
            white-space: nowrap;
            z-index: 0;
            pointer-events: none;
            letter-spacing: 6px;
        }
        .content { position: relative; z-index: 1; }
        
        .badge-pill-invoice {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .badge-paid { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .badge-draft { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }

        /* Signature Area */
        .signature-area { margin-top: 50px; width: 100%; display: flex; justify-content: space-between; }
        .signature-box { text-align: center; width: 220px; font-size: 12px; }
        .signature-box p { margin: 0; }
        .signature-space { height: 70px; }
        
        @media print {
            body { padding: 0; background: #fff; }
            .invoice-box { box-shadow: none; border: none; padding: 0; max-width: 100%; border-radius: 0; }
            .watermark { color: rgba(239, 68, 68, 0.06) !important; }
            .invoice-box table tr.heading td { background-color: #f8fafc !important; }
            @page { margin: 15mm; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="invoice-box">
    <?php if ($order->status_pengerjaan !== 'Diterima' && $order->status_pengerjaan !== 'Selesai'): ?>
        <div class="watermark">DRAFT / SEMENTARA</div>
    <?php endif; ?>

    <div class="content">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                    <img src="<?= base_url('assets/img/app-logo.png') ?>" alt="Logo IPMS" style="height: 44px; width: 44px; object-fit: contain;">
                                    <div>
                                        <div style="font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.1; letter-spacing: -0.02em;">IPMS</div>
                                        <div style="font-size: 9px; font-weight: 700; color: #64748b; letter-spacing: 1px;">INTEGRATED PRODUCTION MANAGEMENT SYSTEM &bull; UINIC 8.0</div>
                                    </div>
                                </div>
                                <div style="font-size: 12px; color: #64748b; font-weight: 500; letter-spacing: normal;">
                                    Sistem Manajemen Produksi Terintegrasi Bengkel Fabrikasi
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">INVOICE PESANAN</div>
                                <span style="font-family: monospace; font-weight: 700; color: #0d63d8; font-size: 14px;">#<?= $order->no_spk ?></span><br>
                                <span style="color: #64748b; font-size: 12px;">Tanggal: <?= date('d M Y', strtotime($order->tanggal_order)) ?></span><br>
                                <span style="color: #64748b; font-size: 12px;">Deadline: <?= date('d M Y', strtotime($order->deadline)) ?></span><br>
                                <div style="margin-top: 6px;">
                                    <?php if ($order->status_pengerjaan === 'Diterima'): ?>
                                        <span class="badge-pill-invoice badge-paid">&#10003; SELESAI / DITERIMA</span>
                                    <?php else: ?>
                                        <span class="badge-pill-invoice badge-draft">&#9679; STATUS: <?= strtoupper($order->status_pengerjaan) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="width: 50%;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.05em; margin-bottom: 4px;">Penerima / Pemesan:</div>
                                <strong style="font-size: 15px; color: #0f172a;"><?= strtoupper($order->nama_c) ?></strong><br>
                                <span style="color: #475569;"><i class="fab fa-whatsapp"></i> <?= $order->no_telp ?></span><br>
                                <span style="color: #64748b;"><?= nl2br($order->alamat) ?></span>
                            </td>
                            <td style="width: 50%; text-align: right;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.05em; margin-bottom: 4px;">Ekspedisi Pengiriman:</div>
                                <strong style="color: #0f172a;"><?= $order->ekspedisi ?: 'Ambil Sendiri di Bengkel' ?></strong><br>
                                <span style="font-size: 12px; color: #64748b;">Standar Penanganan Aman (Packing Kayu/Karton)</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table cellpadding="0" cellspacing="0">
            <tr class="heading">
                <td>Item Spesifikasi Produk</td>
                <td class="text-center" style="width: 90px;">Qty</td>
                <td class="text-right" style="width: 160px;">Harga Satuan</td>
                <td class="text-right" style="width: 170px;">Subtotal</td>
            </tr>

            <tr class="item last">
                <td>
                    <strong style="color: #0f172a; font-size: 14px;"><?= $order->kode_produk ?></strong><br>
                    <span style="color: #64748b; font-size: 13px;">
                        Ukuran: <strong><?= $order->ukuran ?> CM</strong> &bull; Kaca: <?= $order->jenis_kaca ?> &bull; Warna: <?= $order->warna_kusen ?>
                    </span>
                </td>
                <td class="text-center font-weight-bold" style="font-weight: 700;"><?= $order->qty ?> PCS</td>
                <td class="text-right">Rp <?= number_format($order->harga_jual_satuan, 0, ',', '.') ?></td>
                <td class="text-right" style="font-weight: 700;">Rp <?= number_format($order->qty * $order->harga_jual_satuan, 0, ',', '.') ?></td>
            </tr>

            <tr class="total">
                <td colspan="3" class="text-right" style="padding-right: 15px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
                    <b>TOTAL PEMBAYARAN</b>
                </td>
                <td class="text-right">
                   <b>Rp <?= number_format($order->qty * $order->harga_jual_satuan, 0, ',', '.') ?></b>
                </td>
            </tr>
        </table>
        
        <div class="signature-area">
            <div class="signature-box">
                <p>Penerima Pesanan,</p>
                <div class="signature-space"></div>
                <p style="border-top: 1px solid #cbd5e1; padding-top: 4px;"><b>( <?= strtoupper($order->nama_c) ?> )</b></p>
            </div>
            <div class="signature-box">
                <p>Hormat Kami,</p>
                <div class="signature-space"></div>
                <p style="border-top: 1px solid #cbd5e1; padding-top: 4px;"><b>IPMS Production Team</b></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
