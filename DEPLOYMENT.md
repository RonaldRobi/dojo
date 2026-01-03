# Deployment Guide untuk Shared Hosting

Panduan ini menjelaskan cara deploy aplikasi Droplets Dojo ke shared hosting dengan GitHub.

## Persyaratan

- Shared hosting dengan PHP 8.1 atau lebih tinggi
- Composer (biasanya sudah tersedia di shared hosting)
- MySQL database
- SSH access (opsional, tapi direkomendasikan)

## Metode 1: Deploy via GitHub (Recommended)

### Step 1: Setup GitHub Repository

1. Push code ke GitHub repository
2. Pastikan semua file sudah di-commit dan di-push

### Step 2: Clone di Shared Hosting

1. Login ke hosting via SSH atau File Manager
2. Navigate ke folder public_html atau www
3. Clone repository:

```bash
cd public_html
git clone https://github.com/yourusername/your-repo-name.git .
```

**PENTING**: Gunakan `.` di akhir untuk clone langsung ke folder current

### Step 3: Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### Step 4: Setup Environment

1. Copy file `.env.example` ke `.env`:
```bash
cp .env.example .env
```

2. Edit file `.env` dengan informasi hosting Anda:
```env
APP_NAME="Droplets Dojo"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

3. Generate APP_KEY:
```bash
php artisan key:generate
```

### Step 5: Setup Folder Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

*Note: Ganti `www-data` dengan user hosting Anda jika berbeda*

### Step 6: Run Migrations

```bash
php artisan migrate --force
```

*Untuk production, hindari `--seed` kecuali untuk data awal*

### Step 7: Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 8: Point Domain ke Public Folder

Jika hosting Anda memungkinkan, point domain ke folder `public/` atau pastikan file `.htaccess` di root folder sudah mengarah ke `public/`.

## Metode 2: Upload Manual

Jika SSH tidak tersedia:

1. Download repository sebagai ZIP dari GitHub
2. Extract ke folder public_html
3. Upload file `composer.phar` jika perlu
4. Jalankan composer install via hosting panel atau terminal
5. Setup `.env` file
6. Run artisan commands via hosting panel

## Struktur Folder di Shared Hosting

```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          # Folder ini bisa diakses langsung
│   ├── index.php
│   ├── .htaccess
│   └── assets/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── .htaccess        # Redirect ke public/
├── artisan
└── composer.json
```

## Konfigurasi Database

1. Buat database melalui cPanel atau hosting panel
2. Buat user database
3. Assign privileges ke user
4. Update file `.env` dengan kredensial database

## Troubleshooting

### Error 500
- Check file `.env` sudah benar
- Check permissions folder `storage/` dan `bootstrap/cache/`
- Check error log di hosting panel

### Permission Denied
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env
```

### Composer tidak ditemukan
- Download composer.phar dan upload ke root folder
- Atau install via hosting panel

### Route tidak bekerja
- Pastikan mod_rewrite sudah enabled di Apache
- Check file `.htaccess` sudah ada
- Check `APP_URL` di `.env` sudah benar

## Post-Deployment Checklist

- [ ] File `.env` sudah dikonfigurasi
- [ ] APP_KEY sudah di-generate
- [ ] Database sudah dikoneksi
- [ ] Migrations sudah di-run
- [ ] Permissions folder sudah benar
- [ ] Cache sudah di-clear dan di-optimize
- [ ] SSL certificate sudah di-install (jika ada)
- [ ] Error logging aktif untuk monitoring

## Security Recommendations

1. Set `APP_DEBUG=false` di production
2. Jangan commit file `.env` ke GitHub
3. Gunakan strong password untuk database
4. Enable SSL/HTTPS
5. Regular backup database
6. Keep Laravel dan dependencies updated

## Auto-Deploy Script (Opsional)

Jika hosting support cron job atau webhook, Anda bisa setup auto-deploy:

```bash
#!/bin/bash
cd /path/to/public_html
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Simpan sebagai `deploy.sh` dan jalankan via cron atau webhook.

