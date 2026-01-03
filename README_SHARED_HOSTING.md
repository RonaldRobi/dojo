# Quick Start - Shared Hosting Deployment

## Setup Cepat (5 Menit)

### 1. Clone Repository

```bash
cd public_html
git clone https://github.com/yourusername/dojo.git .
```

### 2. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Setup Permissions

```bash
chmod -R 755 storage bootstrap/cache
```

### 5. Run Migrations

```bash
php artisan migrate --force
```

### 6. Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Update dari GitHub

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Atau gunakan script:
```bash
chmod +x deploy.sh
./deploy.sh
```

## Struktur Folder

Pastikan struktur folder seperti ini:
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← Domain point ke sini
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── .htaccess
└── composer.json
```

## Catatan Penting

1. ✅ Pastikan PHP versi 8.1 atau lebih tinggi
2. ✅ Pastikan mod_rewrite enabled di Apache
3. ✅ Point domain ke folder `public/` (bukan root)
4. ✅ File `.env` JANGAN di-commit ke GitHub
5. ✅ Set `APP_DEBUG=false` di production

## Bantuan

Lihat file `DEPLOYMENT.md` untuk panduan lengkap.

