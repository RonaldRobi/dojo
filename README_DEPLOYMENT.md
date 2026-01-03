# Deployment Guide - Shared Hosting

Proyek ini sudah dikonfigurasi untuk shared hosting tanpa memerlukan Node.js atau npm.

## Persyaratan
- PHP >= 8.2
- MySQL >= 5.7 atau MariaDB >= 10.3
- Composer (untuk instalasi lokal, tidak diperlukan di server)
- Shared hosting dengan dukungan Laravel

## Setup untuk Shared Hosting

### 1. Upload Files
Upload semua file ke server kecuali:
- `node_modules/` (jika ada)
- `.git/`
- `vendor/` (install ulang di server)
- `.env` (buat baru di server)

### 2. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Environment Setup
Copy `.env.example` ke `.env` dan konfigurasi:
```env
APP_NAME="Droplets Dojo"
APP_ENV=production
APP_KEY=base64:... (generate dengan: php artisan key:generate)
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations
```bash
php artisan migrate --force
```

### 5. Seed Database (Optional)
```bash
php artisan db:seed --force
```

### 6. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Catatan Penting

### Tailwind CSS
- Aplikasi menggunakan Tailwind CSS via CDN (tidak perlu build)
- Semua styling dilakukan langsung di Blade templates
- Custom classes didefinisikan dalam `<style>` tag di layout

### Assets
- Tidak ada proses build yang diperlukan
- Semua assets CSS/JS menggunakan CDN
- File upload akan disimpan di `storage/app/public`

### Storage Link
```bash
php artisan storage:link
```

### Performance
- Cache config, routes, dan views untuk production
- Gunakan `.htaccess` untuk rewrite rules (sudah termasuk)
- Enable OPcache jika memungkinkan

## Struktur File Penting

```
public/          → Web root (point domain ke sini)
app/             → Application logic
resources/views/ → Blade templates
routes/          → Route definitions
database/        → Migrations & seeders
config/          → Configuration files
```

## Troubleshooting

1. **500 Error**: Cek `storage/logs/laravel.log`
2. **Asset tidak load**: Pastikan `APP_URL` benar di `.env`
3. **Permission denied**: Set permission untuk `storage/` dan `bootstrap/cache/`

