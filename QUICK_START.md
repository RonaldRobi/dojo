# 🚀 Quick Start - Deploy dari GitHub

## 1. Clone dari GitHub
```bash
cd public_html
git clone https://github.com/yourusername/dojo.git .
```

## 2. Install & Setup
```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

## 3. Edit .env
Edit file `.env` dengan database credentials Anda.

## 4. Database & Permissions
```bash
chmod -R 755 storage bootstrap/cache
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 5. Point Domain
Point domain ke folder `public/` atau pastikan file `.htaccess` di root ada.

## ✅ Done!

Untuk update selanjutnya:
```bash
./deploy.sh
```

Atau manual:
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

📖 Lihat `README_GITHUB_DEPLOY.md` untuk panduan lengkap.

