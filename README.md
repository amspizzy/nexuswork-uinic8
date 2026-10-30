# NexusWork
### Sistem Manajemen Produksi Terintegrasi untuk Bengkel Fabrikasi

[![UINIC 8.0](https://img.shields.io/badge/UINIC-8.0-blue)](https://uinicyogyakarta.com)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-3.1.13-EE432E?logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://php.net)
[![Database](https://img.shields.io/badge/Database-MySQL%20%2F%20MariaDB-4479A1?logo=mysql&logoColor=white)](https://mariadb.org)

> **Competition Entry**: UINIC 8.0 Web Development Competition  
> **Theme**: Engineering Interconnected Web Solutions to Empower Digital Ecosystems  
> **SDG Focus**: Economic Growth (SDG 8), Industry & Innovation (SDG 9), Responsible Production (SDG 12)

---

## 🎯 Problem Statement

Bengkel fabrikasi dan manufaktur skala kecil di Indonesia masih mengandalkan pencatatan manual atau spreadsheet sederhana untuk mengelola operasional produksi. Tantangan utama meliputi:

- **Keterbatasan visibilitas** status pengerjaan order secara real-time
- **Kesulitan tracking** bahan baku dan harga pokok produksi (HPP)
- **Tidak adanya integrasi** antara administrasi kantor dan lapangan produksi
- **Ketiadaan sistem audit** untuk pelacakan perubahan data
- **Barrier entry tinggi** untuk sistem ERP enterprise yang kompleks dan mahal

Kondisi ini menyebabkan inefisiensi operasional, waste material, dan hambatan pertumbuhan bisnis UMKM.

---

## 💡 Solution: NexusWork

**NexusWork** adalah sistem manajemen produksi berbasis web dengan arsitektur API-first yang dirancang khusus untuk bengkel fabrikasi. Sistem ini menjadi **nexus** (titik penghubung) antara:

- 📊 Administrasi kantor (web dashboard)
- 📱 Operasional lapangan (mobile app ready via REST API)
- 🔗 Ekosistem digital masa depan (modular & extensible)

### Core Features

#### 1. **Production Order Management (SPK)**
- Pembuatan, monitoring, dan pelacakan Surat Perintah Kerja
- Status tracking real-time: Antri → Produksi → Packing → Dikirim → Diterima
- History lengkap order selesai dan dibatalkan

#### 2. **Integrated Inventory System**
- Master HPP (Harga Pokok Produksi) bahan baku
- Stok gudang dengan dukungan **minus stock** (adaptasi praktik hutang/konsinyasi UMKM)
- Automatic stock deduction saat order completion

#### 3. **Multi-User Role-Based Access Control**
- **Owner**: Full access + financial reports
- **Admin**: Operational management
- **Production Staff**: Limited to production monitoring

#### 4. **Real-time Dashboard**
- Metrik operasional: total order, revenue, profit
- Status overview produksi aktif
- Quick access ke semua modul

#### 5. **Profitability Reporting**
- Analisis omzet, HPP, dan margin profit per periode
- Grafik tren penjualan
- Export-ready untuk decision making

#### 6. **Activity Logging & Audit Trail**
- Pencatatan seluruh mutasi data order
- Traceability untuk compliance dan debugging

#### 7. **REST API Layer (Mobile-Ready)**
- JWT-protected endpoints
- Complete CRUD operations untuk mobile integration
- Firebase Authentication support

---

## 🏗️ Architecture

### System Architecture

```
┌─────────────────────┐         ┌──────────────────┐         ┌────────────────┐
│   Web Dashboard     │◄────────┤  CodeIgniter 3   ├────────►│ MySQL/MariaDB  │
│  (Browser-based)    │         │   Application    │         │   Database     │
└─────────────────────┘         └────────┬─────────┘         └────────────────┘
                                         │
                                ┌────────▼─────────┐
                                │   REST API       │
                                │ (JWT Protected)  │
                                └────────┬─────────┘
                                         │
                                ┌────────▼─────────┐
                                │  Mobile App      │
                                │ (Flutter Ready)  │
                                └──────────────────┘
```

### Tech Stack

| Component | Technology | Version |
|:---|:---|:---|
| **Backend Framework** | CodeIgniter | 3.1.13 |
| **Language** | PHP | 8.3.x |
| **Database** | MySQL / MariaDB | 10.x / 8.x |
| **Authentication** | JWT + Firebase Auth | 7.2 / 8.6 |
| **API Architecture** | RESTful JSON | - |
| **Dependency Manager** | Composer | 2.x |

---

## 🌱 SDG Alignment

### SDG 8: Decent Work and Economic Growth
- **Digitalisasi UMKM**: Menurunkan barrier entry untuk adopsi teknologi manajemen
- **Produktivitas**: Efisiensi operasional meningkatkan kapasitas produksi 
- **Job Creation**: Sistem yang mudah digunakan memungkinkan ekspansi bisnis

### SDG 9: Industry, Innovation and Infrastructure  
- **Inovasi Inklusif**: Teknologi enterprise-grade untuk skala kecil
- **Digital Infrastructure**: API-first architecture untuk interoperabilitas
- **Technology Adoption**: Bridging traditional business to digital ecosystem

### SDG 12: Responsible Consumption and Production
- **Waste Reduction**: Tracking HPP dan inventory mencegah over-procurement
- **Resource Efficiency**: Visibility stok real-time optimasi penggunaan material
- **Sustainable Operations**: Data-driven decision untuk keberlanjutan bisnis

---

## 🚀 Quick Start

See **[SETUP.md](SETUP.md)** for detailed installation guide.

```bash
# Clone repository
git clone https://github.com/amspizzy/nexuswork-uinic8.git
cd nexuswork-uinic8/app

# Install dependencies
composer install

# Configure environment
cp .env.example .env
# Edit .env with your database credentials

# Import database
mysql -u username -p database_name < ../database/schema.sql

# Start development server
php -S 127.0.0.1:8899

# Access: http://127.0.0.1:8899/
# Default login: admin / admin
```

---

## 🔌 API Endpoints

Base URL: `http://your-domain.com/api/v1`

### Authentication
- `POST /auth/login` - Login & get JWT token
- `GET /auth/me` - Get current user profile

### Orders
- `GET /orders` - List all orders (with filters)
- `GET /orders/{no_spk}` - Order details
- `POST /orders` - Create new order
- `PATCH /orders/{no_spk}/status` - Update order status

### Inventory
- `GET /inventory` - List inventory items
- `GET /inventory/{id}` - Item details

### Reports
- `GET /reports/profit` - Profitability analytics
- `GET /activity_log` - Activity history

---

## 🎨 Innovation Highlights

### 1. **Adaptive Stock Management**
Unlike rigid enterprise systems, NexusWork allows **negative stock** to reflect real UMKM practices:
- Hutang bahan baku dari supplier
- Konsinyasi material
- Emergency order saat stok habis

### 2. **Interconnected Architecture**
API-first design enables:
- Web dashboard + mobile app synchronization
- Future integration with payment gateway, supplier networks, analytics platforms
- Modular expansion without breaking existing features

### 3. **Low-Complexity, High-Value**
- Fokus pada 80% kebutuhan inti (vs 20% edge cases ERP besar)
- Learning curve rendah untuk adopsi cepat
- Maintenance sederhana tanpa vendor lock-in

---

## 📊 Database Schema

8 relational tables:

| Table | Description | Primary Key |
|:---|:---|:---|
| `users` | User accounts & roles | `id_user` |
| `orders` | Production orders (SPK) | `no_spk` |
| `monitoring` | Order status tracking | `id_mon` |
| `master_hpp` | Material master & pricing | `id_hpp` |
| `inventory` | Stock movements | `id_inv` |
| `customers` | Customer database | `id_cust` |
| `activity_log` | Audit trail | `id_log` |
| `ci_sessions` | Session storage | `id` |

**Order Flow:** `Antri` → `Produksi` → `Packing` → `Dikirim` → `Diterima` (or `Cancel`)

---

## 📱 Mobile Integration

NexusWork dirancang dengan **mobile-first API** untuk mendukung aplikasi lapangan:

**Use Cases:**
- Production staff update status pengerjaan via smartphone
- Field workers check material availability real-time
- Mobile order entry untuk sales team
- Push notification untuk order updates

**Technology:** REST API + JWT authentication enables framework-agnostic integration (Flutter, React Native, native iOS/Android)

---

## 🏆 Competition Deliverables

-  Working web application (demo site)
-  Public GitHub repository
-  Complete API documentation
-  Architecture diagrams
-  Technical proposal (30 pages)
-  User testing results
-  Video presentation

---

## 📄 License

This project is developed for UINIC 8.0 Web Development Competition.

---

## 🙏 Acknowledgments

- HMPS Informatika UIN Sunan Kalijaga Yogyakarta
- UINIC 8.0 Committee
- Indonesian UMKM community for real-world insights

---

**Built with ❤️ for Indonesian SME Digital Transformation**
