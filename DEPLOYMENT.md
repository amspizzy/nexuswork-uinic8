# Deployment Guide - Hostinger

## Prerequisites
- Hostinger account with PHP 8.0+ support
- MySQL database access
- FTP/File Manager access

## Steps

### 1. Create Database on Hostinger

1. Login to Hostinger hPanel
2. Go to **Databases** → **MySQL Databases**
3. Create new database:
   - Database name: `u123456_nexuswork` (or similar)
   - Username: auto-generated
   - Password: **save this**
4. Note: **hostname, username, password, database name**

### 2. Import Database

**Option A: phpMyAdmin**
1. Open phpMyAdmin from hPanel
2. Select your database
3. Go to **Import** tab
4. Upload `database/demo-data.sql`
5. Click **Go**

**Option B: Terminal (if SSH access)**
```bash
mysql -u username -p database_name < database/demo-data.sql
```

### 3. Upload Files

**Upload these folders/files to `public_html/` (or subdirectory):**

```
public_html/nexuswork/
├── app/
│   ├── application/
│   ├── assets/
│   ├── system/
│   ├── vendor/
│   ├── .htaccess
│   ├── index.php
│   ├── composer.json
│   └── .env (configure this!)
├── database/
├── SETUP.md
└── README.md
```

**Exclude:**
- `.git/`
- `app/.env` (configure separately)
- Local config files

### 4. Configure Environment

Create `app/.env` on server:

```env
# Production Environment

# --- Base URL (auto-detected, optional override) ---
# BASE_URL=https://yourdomain.com/nexuswork/app/

# --- Database ---
DB_HOSTNAME=localhost
DB_USERNAME=u123456_user
DB_PASSWORD=your_password_here
DB_DATABASE=u123456_nexuswork

# --- JWT ---
JWT_SECRET=production_secret_key_change_this_random_string
JWT_TTL=3600

# --- Firebase (optional) ---
FIREBASE_CREDENTIALS=
FIREBASE_PROJECT_ID=
```

**Important:**
- Change `JWT_SECRET` to random string (32+ chars)
- Use actual database credentials from step 1
- Verify `DB_HOSTNAME` (usually `localhost`, sometimes specific host)

### 5. Set Permissions

Via File Manager or FTP:
- `app/application/cache/` → 755
- `app/application/logs/` → 755
- `app/vendor/` → 755

### 6. Configure .htaccess

Verify `app/.htaccess` exists with:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

### 7. Test Access

1. **Homepage:** `https://yourdomain.com/nexuswork/app/`
2. **Login page should appear**
3. **Test login:**
   - Username: `admin`
   - Password: `admin`

### 8. Verify Functionality

After login, test:
- ✅ Dashboard loads
- ✅ Orders page shows data
- ✅ Inventory accessible
- ✅ No PHP errors
- ✅ Base URL redirects work

## Troubleshooting

### Issue: 500 Internal Server Error
- Check PHP version (must be 8.0+)
- Verify `.htaccess` uploaded
- Check file permissions
- View error logs in hPanel

### Issue: Database connection failed
- Verify credentials in `.env`
- Check `DB_HOSTNAME` (try `localhost` or specific host)
- Ensure database imported successfully

### Issue: Blank page / PHP errors
- Enable error display temporarily in `app/index.php`:
  ```php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  ```
- Check `composer install` ran (vendor folder exists)

### Issue: CSS/JS not loading
- Verify `app/assets/vendor/` uploaded
- Check base URL in browser console
- Clear browser cache

### Issue: Redirect loop
- Check `.htaccess` configuration
- Verify `base_url` in `app/application/config/config.php`

## Security Checklist

Before making public:
- ✅ Change default admin password
- ✅ Unique `JWT_SECRET` in `.env`
- ✅ `.env` file NOT publicly accessible (outside web root or protected)
- ✅ Disable error display in production
- ✅ HTTPS enabled (Hostinger provides free SSL)

## Demo Credentials

For competition jury:
- **URL:** `https://yourdomain.com/nexuswork/app/`
- **Username:** `admin`
- **Password:** `admin` (or change & document)
- **Role:** Owner (full access)

## Support

Issues? Check:
- Hostinger knowledge base
- PHP error logs in hPanel
- Browser console for JS errors
