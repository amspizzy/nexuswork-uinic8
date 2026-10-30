# Setup Guide - UINIC8 Competition Demo

## Quick Start (Local Development)

### Prerequisites
- PHP 8.3+
- MariaDB/MySQL
- Composer

### Installation

1. **Clone repository**
   ```bash
   cd ~/Projects/UINIC8-WebDev-2026
   ```

2. **Install dependencies**
   ```bash
   cd app
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```

   Edit `app/.env`:
   ```env
   BASE_URL=http://127.0.0.1:8899/
   
   DB_HOSTNAME=127.0.0.1
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_pass
   DB_DATABASE=db_sewelas
   
   JWT_SECRET=your_secret_key_here
   JWT_TTL=3600
   ```

4. **Import database**
   ```bash
   mysql -u username -p db_sewelas < database/schema.sql
   ```

5. **Start development server**
   ```bash
   cd app
   php -S 127.0.0.1:8899
   ```

6. **Access application**
   - Web: http://127.0.0.1:8899/
   - Default login: admin / admin

## Project Structure

```
UINIC8-WebDev-2026/
├── app/                    # CI3 application root
│   ├── application/        # Controllers, models, views
│   ├── assets/            # CSS, JS, images
│   ├── system/            # CI3 framework
│   └── .env               # Environment config (not in git)
├── database/              # SQL schemas & migrations
├── docs/                  # Proposal & documentation
└── README.md             # Project overview
```

## Competition Details

- **Event**: UINIC 8.0 Web Development Competition
- **Theme**: Engineering Interconnected Web Solutions
- **SDG Focus**: Economic Growth (SDG 8) & Innovation (SDG 9)
- **Deadline**: October 30, 2026

## Features

- Multi-module ERP (Inventory, Orders, Reports)
- REST API with JWT authentication
- Mobile-ready architecture
- Real-time dashboard
- Activity logging & audit trail
