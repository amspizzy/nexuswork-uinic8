<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }
        $this->load->model(['M_Orders', 'M_MasterHpp', 'M_ActivityLog']);
    }

    public function index()
    {
        $data['title'] = 'Input Pesanan';

        $data['list_ukuran'] = $this->db->select('ukuran')->distinct()->order_by('CAST(ukuran AS UNSIGNED)', 'ASC')->get('master_hpp')->result();
        $data['list_kaca']   = $this->db->select('jenis_kaca')->distinct()->get('master_hpp')->result();
        $data['list_warna']  = $this->db->select('warna_kusen')->distinct()->get('master_hpp')->result();
        $data['list_kode']   = $this->db->select('kode_produk')->distinct()->get('master_hpp')->result();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('orders/input', $data);
        $this->load->view('layout/footer');
    }

    // Endpoint: ambil opsi ukuran berdasarkan kode produk yang dipilih
    public function get_options_by_kode()
    {
        $kode = $this->input->post('kode');

        $this->db->select('ukuran')->distinct()->where('kode_produk', $kode)->order_by('CAST(ukuran AS UNSIGNED)', 'ASC');
        $ukuran = $this->db->get('master_hpp')->result();

        echo json_encode([
            'ukuran' => array_column($ukuran, 'ukuran'),
        ]);
    }

    // Endpoint: ambil opsi jenis kaca berdasarkan kode produk + ukuran
    public function get_kaca_by_kode_ukuran()
    {
        $kode   = $this->input->post('kode');
        $ukuran = $this->input->post('ukuran');

        $this->db->select('jenis_kaca')->distinct()->where('kode_produk', $kode)->where('ukuran', $ukuran);
        $kaca = $this->db->get('master_hpp')->result();

        echo json_encode([
            'kaca' => array_column($kaca, 'jenis_kaca'),
        ]);
    }

    // Endpoint: ambil opsi warna kusen berdasarkan kode produk + ukuran + jenis kaca
    public function get_warna_by_kode_ukuran_kaca()
    {
        $kode   = $this->input->post('kode');
        $ukuran = $this->input->post('ukuran');
        $kaca   = $this->input->post('kaca');

        $this->db->select('warna_kusen')->distinct()->where('kode_produk', $kode)->where('ukuran', $ukuran)->where('jenis_kaca', $kaca);
        $warna = $this->db->get('master_hpp')->result();

        echo json_encode([
            'warna' => array_column($warna, 'warna_kusen'),
        ]);
    }

    public function get_hpp_by_params()
    {
        $kd = $this->input->post('kode');
        $u = $this->input->post('ukuran');
        $k = $this->input->post('kaca');
        $w = $this->input->post('warna');

        $res = $this->db->get_where('master_hpp', [
            'kode_produk' => $kd,
            'ukuran' => $u,
            'jenis_kaca' => $k,
            'warna_kusen' => $w
        ])->row();

        echo json_encode($res);
    }

    public function simpan()
    {
        $qty = $this->input->post('qty');
        $harga_jual_satuan = $this->input->post('harga_jual_satuan');
        $hpp_satuan = $this->input->post('hpp_saat_ini');

        $total_harga = $qty * $harga_jual_satuan;
        $total_modal = $qty * $hpp_satuan;

        $no_spk = $this->input->post('no_spk');

             // 1. CEK KE DATABASE: Apakah No. SPK ini sudah ada?
        $cek_duplikat = $this->db->get_where('orders', ['no_spk' => $no_spk])->num_rows();

        if ($cek_duplikat > 0) {
            // 2. JIKA ADA: Kirim notifikasi error dan stop proses simpan
            $this->session->set_flashdata('error', 'Gagal! Nomor SPK <b>' . $no_spk . '</b> sudah terdaftar. Gunakan nomor lain.');

            // Kembalikan ke halaman form tambah (pastikan URL-nya benar)
            redirect('Orders');
        } else {

            $cust_data = [
                'nama_c' => $this->input->post('nama_pelanggan'),
                'alamat' => $this->input->post('alamat_pelanggan'),
                'no_telp' => $this->input->post('no_telp')

            ];
            $this->db->insert('customers', $cust_data);
            $id_cust = $this->db->insert_id();


            $order_data = [
                'no_spk'            => $no_spk,
                'tanggal_order'     => $this->input->post('tanggal_order'),
                'tgl_input'         => date('Y-m-d H:i:s'),              // Otomatis timestamp sekarang
                'id_cust'           => $id_cust,
                'id_hpp'            => $this->input->post('id_hpp'),
                'qty'               => $this->input->post('qty'),
                'hpp_saat_ini'      => $this->input->post('hpp_saat_ini'),
                'harga_jual_satuan' => $this->input->post('harga_jual_satuan'),
                'total_harga_jual'  => $total_harga,
                'deadline'          => $this->input->post('deadline'),
                'ekspedisi'         => $this->input->post('ekspedisi'),
                'total_harga'       => $total_modal


            ];

            $this->db->insert('orders', $order_data);

            // Input ke monitoring
            $this->db->insert('monitoring', [
                'no_spk' => $no_spk,
                'status_pengerjaan' => 'Antri'
            ]);

            // Catat log
            $this->M_ActivityLog->log(
                'tambah_order',
                'Menambahkan pesanan baru dengan SPK: ' . $no_spk,
                $no_spk,
                null,
                $order_data
            );

            $this->session->set_flashdata('pesan', 'Data order berhasil disimpan. SPK: ' . $no_spk);
            redirect('Orders/list');
        }
    }

    public function list()
    {
        $data['title'] = 'Production Monitoring';
        
        // Ambil data dropdown untuk edit modal
        $data['list_ukuran'] = $this->db->select('ukuran')->distinct()->order_by('CAST(ukuran AS UNSIGNED)', 'ASC')->get('master_hpp')->result();
        $data['list_kaca']   = $this->db->select('jenis_kaca')->distinct()->get('master_hpp')->result();
        $data['list_warna']  = $this->db->select('warna_kusen')->distinct()->get('master_hpp')->result();
        $data['list_kode']   = $this->db->select('kode_produk')->distinct()->get('master_hpp')->result();
        
        $this->db->select('orders.*, customers.nama_c, customers.alamat, customers.no_telp, 
                       monitoring.status_pengerjaan, master_hpp.ukuran, master_hpp.jenis_kaca, master_hpp.warna_kusen, master_hpp.kode_produk, orders.ekspedisi');
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id_cust = orders.id_cust');
        $this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp');
        $this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk');
        $this->db->order_by('orders.tgl_input', 'DESC');
        $data['orders'] = $this->db->get()->result();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('orders/list', $data);
        $this->load->view('layout/footer');
    }

    public function update_status()
    {
        // Support both form POST and Ajax
        $status_baru = $this->input->post('status_baru') ?: $this->input->post('status_pengerjaan');
        $role        = $this->session->userdata('role');
        $no_spk      = $this->input->post('no_spk');
        $is_ajax     = $this->input->is_ajax_request();

        // Urutan status yang valid (index menentukan "level")
        $status_order = ['Antri' => 1, 'Produksi' => 2, 'Packing' => 3, 'Dikirim' => 4, 'Diterima' => 5, 'Cancel' => 6];

        // Ambil status lama terlebih dahulu untuk validasi
        $monitoring_lama   = $this->db->get_where('monitoring', ['no_spk' => $no_spk])->row();
        $status_lama       = $monitoring_lama->status_pengerjaan;
        $level_lama        = $status_order[$status_lama]  ?? 0;
        $level_baru        = $status_order[$status_baru]  ?? 0;

        // Admin: bisa ubah status maju atau cancel, tapi TIDAK boleh mundur
        // Owner: bebas ubah ke status apa pun
        if ($role === 'admin') {
            // Blokir status yang hanya boleh untuk owner
            $owner_only = ['Diterima', 'Cancel'];
            if (in_array($status_baru, $owner_only)) {
                if ($is_ajax) {
                    echo json_encode(['status' => 'error', 'message' => 'Status "' . $status_baru . '" hanya bisa diubah oleh Owner.']);
                    return;
                }
                $this->session->set_flashdata('error', 'Status "' . $status_baru . '" hanya bisa diubah oleh Owner.');
                redirect('Orders/list');
                return;
            }
            // Blokir mundur status
            if ($level_baru < $level_lama) {
                if ($is_ajax) {
                    echo json_encode(['status' => 'error', 'message' => 'Admin tidak bisa memundurkan status dari "' . $status_lama . '" ke "' . $status_baru . '". Hanya Owner yang bisa.']);
                    return;
                }
                $this->session->set_flashdata('error', 'Admin tidak bisa memundurkan status dari "' . $status_lama . '" ke "' . $status_baru . '". Hanya Owner yang bisa.');
                redirect('Orders/list');
                return;
            }
        }

        // Karyawan (tim produksi): boleh ubah status ke Antri/Produksi/Packing sesuai tombol yang
        // muncul di UI mereka, tapi tidak boleh menyentuh status yang ranahnya Admin/Owner
        // (Dikirim/Diterima/Cancel), dan tidak boleh memundurkan status — sama seperti Admin.
        if ($role === 'karyawan') {
            $karyawan_blocked = ['Dikirim', 'Diterima', 'Cancel'];
            if (in_array($status_baru, $karyawan_blocked)) {
                if ($is_ajax) {
                    echo json_encode(['status' => 'error', 'message' => 'Status "' . $status_baru . '" hanya bisa diubah oleh Admin/Owner.']);
                    return;
                }
                $this->session->set_flashdata('error', 'Status "' . $status_baru . '" hanya bisa diubah oleh Admin/Owner.');
                redirect('Orders/list');
                return;
            }
            if ($level_baru < $level_lama) {
                if ($is_ajax) {
                    echo json_encode(['status' => 'error', 'message' => 'Kamu tidak bisa memundurkan status dari "' . $status_lama . '" ke "' . $status_baru . '".']);
                    return;
                }
                $this->session->set_flashdata('error', 'Kamu tidak bisa memundurkan status dari "' . $status_lama . '" ke "' . $status_baru . '".');
                redirect('Orders/list');
                return;
            }
        }

        // $monitoring_lama dan $status_lama sudah diambil di atas untuk validasi role

        // 2. Ambil detail order & spek HPP (untuk tahu apa yang harus dipotong/ditambah)
        $order = $this->db->get_where('orders', ['no_spk' => $no_spk])->row();
        $hpp   = $this->db->get_where('master_hpp', ['id_hpp' => $order->id_hpp])->row();

        // --- LOGIKA STOK ---

        // KONDISI A: Status berubah DARI (apa pun) KE 'Dikirim' -> POTONG STOK
        if ($status_lama != 'Dikirim' && $status_baru == 'Dikirim') {
            // Tentukan tipe berdasarkan kode produk
            $pivot_codes = ['JBP', 'JBPSK', 'JB2', 'JB2SK'];
            $jb_codes = ['JB', 'JBSK'];
            
            if (in_array($hpp->kode_produk, $pivot_codes)) {
                $tipe = 'kusen_pivot';
                $is_double = true;
            } elseif (in_array($hpp->kode_produk, $jb_codes)) {
                $tipe = 'kusen_m';
                $is_double = true;
            } else {
                $tipe = 'kusen_m'; // Default kusen_m untuk kode lainnya
                $is_double = false;
            }
            
            // Cari inventory berdasarkan ukuran dan tipe
            $inventory = $this->db->get_where('inventory', ['ukuran' => $hpp->ukuran, 'tipe' => $tipe])->row();
            
            if ($inventory) {
                $qty_to_deduct = $is_double ? $order->qty * 2 : $order->qty;
                $this->db->where(['ukuran' => $hpp->ukuran, 'tipe' => $tipe]);
                $this->db->set('stok', 'stok - ' . $qty_to_deduct, FALSE);
                $this->db->update('inventory');
            }
        }

        // KONDISI B: Status berubah DARI 'Dikirim' KE (apa pun selain dikirim) -> BALIKIN STOK
        elseif ($status_lama == 'Dikirim' && $status_baru != 'Dikirim') {
            // Tentukan tipe berdasarkan kode produk (sama dengan pengurangan)
            $pivot_codes = ['JBP', 'JBPSK', 'JB2', 'JB2SK'];
            $jb_codes = ['JB', 'JBSK'];
            
            if (in_array($hpp->kode_produk, $pivot_codes)) {
                $tipe = 'kusen_pivot';
                $is_double = true;
            } elseif (in_array($hpp->kode_produk, $jb_codes)) {
                $tipe = 'kusen_m';
                $is_double = true;
            } else {
                $tipe = 'kusen_m'; // Default kusen_m untuk kode lainnya
                $is_double = false;
            }
            
            // Cari inventory berdasarkan ukuran dan tipe
            $inventory = $this->db->get_where('inventory', ['ukuran' => $hpp->ukuran, 'tipe' => $tipe])->row();
            
            if ($inventory) {
                $qty_to_add = $is_double ? $order->qty * 2 : $order->qty;
                $this->db->where(['ukuran' => $hpp->ukuran, 'tipe' => $tipe]);
                $this->db->set('stok', 'stok + ' . $qty_to_add, FALSE);
                $this->db->update('inventory');
            }
        }

        // 3. Update status akhir di tabel monitoring
        $this->db->where('no_spk', $no_spk);
        $this->db->update('monitoring', [
            'status_pengerjaan' => $status_baru,
            'tgl_update'        => date('Y-m-d H:i:s')
        ]);

        // 4. Sync ke Firebase Realtime Database untuk real-time tracking
        $this->load->library('Firebase_lib');
        $firebase_data = [
            'no_spk'            => $no_spk,
            'status_pengerjaan' => $status_baru,
            'tgl_update'        => date('Y-m-d H:i:s'),
            'updated_by'        => $this->session->userdata('nama'),
            'role'              => $role
        ];
        $this->firebase_lib->sync_order($no_spk, $firebase_data);

        // Catat log
        $this->M_ActivityLog->log(
            'ubah_status',
            'Mengubah status pesanan SPK: ' . $no_spk . ' dari ' . $status_lama . ' menjadi ' . $status_baru,
            $no_spk,
            ['status_lama' => $status_lama],
            ['status_baru' => $status_baru]
        );

        // Pesan khusus untuk status Cancel
        $message = '';
        if ($status_baru == 'Cancel') {
            $message = 'Pesanan dengan SPK ' . $no_spk . ' telah dicancel. Stok telah dikembalikan jika diperlukan.';
        } else {
            $message = 'Status berhasil diperbarui dan stok telah disesuaikan.';
        }
        
        // Response based on request type
        if ($is_ajax) {
            echo json_encode([
                'status' => 'success',
                'message' => $message,
                'data' => [
                    'no_spk' => $no_spk,
                    'status_baru' => $status_baru,
                    'status_lama' => $status_lama
                ]
            ]);
        } else {
            $this->session->set_flashdata('pesan', $message);
            redirect('Orders/list');
        }
    }

    public function update_harga_final()
    {
        $no_spk = $this->input->post('no_spk');
        $total_harga_final = $this->input->post('total_harga_final');

        // Ambil qty dari database
        $order = $this->db->get_where('orders', ['no_spk' => $no_spk])->row();
        $qty = $order->qty;

        // Hitung harga satuan dari total
        $harga_satuan_baru = $total_harga_final / $qty;

        // Update harga jual satuan dan total harga di orders
        $this->db->where('no_spk', $no_spk);
        $this->db->update('orders', [
            'harga_jual_satuan' => $harga_satuan_baru,
            'total_harga' => $total_harga_final
        ]);

        // Update status monitoring ke Diterima
        $this->db->where('no_spk', $no_spk);
        $this->db->update('monitoring', [
            'status_pengerjaan' => 'Diterima',
            'tgl_update' => date('Y-m-d H:i:s')
        ]);

        // Catat log
        $this->M_ActivityLog->log(
            'update_harga',
            'Mengupdate harga final untuk SPK: ' . $no_spk . ' menjadi Rp ' . number_format($total_harga_final, 0, ',', '.'),
            $no_spk,
            null,
            ['harga_final_baru' => $total_harga_final]
        );

        $this->session->set_flashdata('pesan', 'Total harga final berhasil diupdate dan status berubah ke Diterima.');
        redirect('Orders/list');
    }
    public function get_order_data()
    {
        $no_spk = $this->input->post('no_spk');
        
        $order = $this->db->get_where('orders', ['no_spk' => $no_spk])->row();
        if (!$order) {
            echo json_encode(['status' => 'error', 'message' => 'Order tidak ditemukan']);
            return;
        }

        $customer = $this->db->get_where('customers', ['id_cust' => $order->id_cust])->row();
        $hpp = $this->db->get_where('master_hpp', ['id_hpp' => $order->id_hpp])->row();

        echo json_encode([
            'status' => 'success',
            'order' => $order,
            'customer' => $customer,
            'hpp' => $hpp
        ]);
    }

    public function get_hpp_details()
    {
        $kode = $this->input->post('kode');
        $ukuran = $this->input->post('ukuran');
        $kaca = $this->input->post('kaca');
        $warna = $this->input->post('warna');

        $hpp = $this->db->get_where('master_hpp', [
            'kode_produk' => $kode,
            'ukuran' => $ukuran,
            'jenis_kaca' => $kaca,
            'warna_kusen' => $warna
        ])->row();

        if ($hpp) {
            echo json_encode(['status' => 'success', 'data' => $hpp]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Kombinasi HPP tidak ditemukan']);
        }
    }

    public function edit_order()
    {
        $no_spk = $this->input->post('no_spk');
        $old_order = $this->db->get_where('orders', ['no_spk' => $no_spk])->row();

        if (!$old_order) {
            $this->session->set_flashdata('error', 'Order tidak ditemukan.');
            redirect('Orders/list');
        }

        // Ambil data dari form
        $id_hpp = $this->input->post('id_hpp');
        $qty_baru = $this->input->post('qty');
        $hpp_satuan = $this->input->post('hpp_saat_ini');
        $harga_jual_satuan = $this->input->post('harga_jual_satuan');
        $qty_lama = $old_order->qty;

        $total_harga = $qty_baru * $harga_jual_satuan;
        $total_modal = $qty_baru * $hpp_satuan;

        // Update customer data
        $this->db->where('id_cust', $old_order->id_cust);
        $this->db->update('customers', [
            'nama_c' => $this->input->post('nama_pelanggan'),
            'alamat' => $this->input->post('alamat_pelanggan'),
            'no_telp' => $this->input->post('no_telp')
        ]);

        // Update order data
        $this->db->where('no_spk', $no_spk);
        $this->db->update('orders', [
            'id_hpp' => $id_hpp,
            'qty' => $qty_baru,
            'hpp_saat_ini' => $hpp_satuan,
            'harga_jual_satuan' => $harga_jual_satuan,
            'total_harga_jual' => $total_harga,
            'total_harga' => $total_modal,
            'tanggal_order' => $this->input->post('tanggal_order'),
            'deadline' => $this->input->post('deadline'),
            'ekspedisi' => $this->input->post('ekspedisi')
        ]);

        // --- ADJUSTMENT STOK JIKA STATUS DIKIRIM DAN QTY BERUBAH ---
        $monitoring = $this->db->get_where('monitoring', ['no_spk' => $no_spk])->row();
        
        if ($monitoring->status_pengerjaan == 'Dikirim' && $qty_lama != $qty_baru) {
            $hpp = $this->db->get_where('master_hpp', ['id_hpp' => $id_hpp])->row();
            
            $pivot_codes = ['JBP', 'JBPSK', 'JB2', 'JB2SK'];
            $jb_codes = ['JB', 'JBSK'];
            
            if (in_array($hpp->kode_produk, $pivot_codes)) {
                $tipe = 'kusen_pivot';
                $is_double = true;
            } elseif (in_array($hpp->kode_produk, $jb_codes)) {
                $tipe = 'kusen_m';
                $is_double = true;
            } else {
                $tipe = 'kusen_m';
                $is_double = false;
            }
            
            $inventory = $this->db->get_where('inventory', ['ukuran' => $hpp->ukuran, 'tipe' => $tipe])->row();
            
            if ($inventory) {
                $qty_old_deduct = $is_double ? $qty_lama * 2 : $qty_lama;
                $qty_new_deduct = $is_double ? $qty_baru * 2 : $qty_baru;
                $qty_diff = $qty_new_deduct - $qty_old_deduct;
                
                $this->db->where(['ukuran' => $hpp->ukuran, 'tipe' => $tipe]);
                $this->db->set('stok', 'stok - ' . $qty_diff, FALSE);
                $this->db->update('inventory');
            }
        }

        // Catat log
        $this->M_ActivityLog->log(
            'edit_order',
            'Mengedit pesanan dengan SPK: ' . $no_spk,
            $no_spk,
            $old_order,
            ['id_hpp' => $id_hpp, 'qty' => $qty_baru, 'harga_jual_satuan' => $harga_jual_satuan, 'total_harga_jual' => $total_harga]
        );

        $this->session->set_flashdata('pesan', 'Order SPK ' . $no_spk . ' berhasil diupdate.');
        redirect('Orders/list');
    }

    public function delete_order()
    {
        $no_spk = $this->input->post('no_spk');
        $order = $this->db->get_where('orders', ['no_spk' => $no_spk])->row();

        if (!$order) {
            $this->session->set_flashdata('error', 'Order tidak ditemukan.');
            redirect('Orders/list');
        }

        // Cek status monitoring
        $monitoring = $this->db->get_where('monitoring', ['no_spk' => $no_spk])->row();
        
        // Jika status Dikirim, kembalikan stok
        if ($monitoring && $monitoring->status_pengerjaan == 'Dikirim') {
            $hpp = $this->db->get_where('master_hpp', ['id_hpp' => $order->id_hpp])->row();
            
            $pivot_codes = ['JBP', 'JBPSK', 'JB2', 'JB2SK'];
            $jb_codes = ['JB', 'JBSK'];
            
            if (in_array($hpp->kode_produk, $pivot_codes)) {
                $tipe = 'kusen_pivot';
                $is_double = true;
            } elseif (in_array($hpp->kode_produk, $jb_codes)) {
                $tipe = 'kusen_m';
                $is_double = true;
            } else {
                $tipe = 'kusen_m';
                $is_double = false;
            }
            
            $inventory = $this->db->get_where('inventory', ['ukuran' => $hpp->ukuran, 'tipe' => $tipe])->row();
            
            if ($inventory) {
                $qty_to_add = $is_double ? $order->qty * 2 : $order->qty;
                $this->db->where(['ukuran' => $hpp->ukuran, 'tipe' => $tipe]);
                $this->db->set('stok', 'stok + ' . $qty_to_add, FALSE);
                $this->db->update('inventory');
            }
        }

        // Hapus dari monitoring dulu (foreign key constraint)
        $this->db->where('no_spk', $no_spk);
        $this->db->delete('monitoring');

        // Hapus order
        $this->db->where('no_spk', $no_spk);
        $this->db->delete('orders');

        // Catat log
        $this->M_ActivityLog->log(
            'hapus_order',
            'Menghapus pesanan dengan SPK: ' . $no_spk,
            $no_spk,
            $order,
            null
        );

        $this->session->set_flashdata('pesan', 'Order SPK ' . $no_spk . ' berhasil dihapus.');
        redirect('Orders/list');
    }

    public function history()
    {
        $data['title'] = 'Riwayat Pesanan Diterima';
        $this->db->select('
        o.*, 
        m.status_pengerjaan, 
        m.tgl_update as tgl_selesai, 
        c.nama_c as nama_pelanggan, 
        h.jenis_kaca, 
        h.warna_kusen,
        h.ukuran,
        h.kode_produk
    '); // Tambahkan h.ukuran dan h.kode_produk di sini
        $this->db->from('orders o');
        $this->db->join('monitoring m', 'o.no_spk = m.no_spk');
        $this->db->join('customers c', 'o.id_cust = c.id_cust');
        $this->db->join('master_hpp h', 'o.id_hpp = h.id_hpp');
        $this->db->where('m.status_pengerjaan', 'Diterima');
        $this->db->order_by('m.tgl_update', 'DESC');

        $data['history'] = $this->db->get()->result();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('orders/history', $data);
        $this->load->view('layout/footer');
    }

    public function history_cancel()
    {
        $data['title'] = 'Riwayat Pesanan Cancel';
        $this->db->select('
        o.*, 
        m.status_pengerjaan, 
        m.tgl_update as tgl_cancel, 
        c.nama_c as nama_pelanggan, 
        h.jenis_kaca, 
        h.warna_kusen,
        h.ukuran,
        h.kode_produk
    ');
        $this->db->from('orders o');
        $this->db->join('monitoring m', 'o.no_spk = m.no_spk');
        $this->db->join('customers c', 'o.id_cust = c.id_cust');
        $this->db->join('master_hpp h', 'o.id_hpp = h.id_hpp');
        $this->db->where('m.status_pengerjaan', 'Cancel');
        $this->db->order_by('m.tgl_update', 'DESC');

        $data['history'] = $this->db->get()->result();

        $this->load->view('layout/header', $data);
        $this->load->view('layout/sidebar');
        $this->load->view('layout/topbar');
        $this->load->view('orders/history_cancel', $data);
        $this->load->view('layout/footer');
    }

    public function invoice($no_spk)
    {
        $data['title'] = 'Invoice ' . $no_spk;
        
        $this->db->select('orders.*, customers.nama_c, customers.alamat, customers.no_telp, 
                       monitoring.status_pengerjaan, master_hpp.ukuran, master_hpp.jenis_kaca, master_hpp.warna_kusen, master_hpp.kode_produk');
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id_cust = orders.id_cust');
        $this->db->join('master_hpp', 'master_hpp.id_hpp = orders.id_hpp');
        $this->db->join('monitoring', 'monitoring.no_spk = orders.no_spk', 'left'); // Using left join in case monitoring is somehow missing
        $this->db->where('orders.no_spk', $no_spk);
        $order = $this->db->get()->row();

        if (!$order) {
            $this->session->set_flashdata('error', 'Order tidak ditemukan.');
            redirect('Orders/list');
        }

        $data['order'] = $order;

        $this->load->view('orders/invoice', $data);
    }
}