# Deploy dari GitHub ke Shared Hosting

Panduan cepat untuk deploy aplikasi Droplets Dojo dari GitHub ke shared hosting.

## ⚡ Quick Deploy (5 Menit)

### Prerequisites
- ✅ Shared hosting dengan PHP 8.1+
- ✅ SSH access (recommended) atau File Manager
- ✅ MySQL database
- ✅ Git (jika via SSH)

### Step 1: Clone dari GitHub

**Via SSH:**
```bash
cd public_html  # atau www, atau folder yang ditentukan hosting
git clone https://github.com/yourusername/dojo.git .
```

**Note:** Tanda `.` di akhir berarti clone langsung ke folder current, bukan buat subfolder baru.

**Via File Manager:**
1. Download repository sebagai ZIP dari GitHub
2. Extract ke folder `public_html`
3. Lanjut ke Step 3

### Step 2: Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### Step 3: Setup Environment

```bash
# Copy file .env
cp .env.example .env

# Generate APP_KEY
php artisan key:generate

# Edit .env file dengan informasi hosting Anda
nano .env  # atau gunakan editor di File Manager
```

**Isi file `.env`:**
```env
APP_NAME="Droplets Dojo"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 4: Setup Permissions

```bash
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

### Step 5: Database Setup

```bash
# Run migrations
php artisan migrate --force

# (Optional) Seed database dengan data awal
php artisan db:seed --force
```

### Step 6: Optimize

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Point Domain

**Option A:** Point domain ke folder `public/`
- Di cPanel/hosting panel, point domain ke: `public_html/public/`

**Option B:** Jika tidak bisa point ke public/, gunakan file `.htaccess` di root
- File `.htaccess` sudah termasuk di repository
- File ini akan redirect semua request ke folder `public/`

## 🔄 Update dari GitHub

Setelah initial setup, untuk update aplikasi:

### Manual Update:
```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Auto Update (Recommended):
```bash
# Buat script executable
chmod +x deploy.sh

# Jalankan script
./deploy.sh
```

Script `deploy.sh` akan:
- Pull latest changes dari GitHub
- Install dependencies
- Run migrations
- Clear dan cache config/routes/views

## 📁 Struktur Folder

Pastikan struktur folder seperti ini:

```
public_html/          (atau www/)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/          ← Domain point ke sini (recommended)
│   ├── index.php
│   ├── .htaccess
│   └── assets/
├── resources/
├── routes/
├── storage/
│   └── logs/        ← Pastikan writable
├── vendor/
├── .env             ← Jangan commit ke GitHub!
├── .htaccess        ← Redirect ke public/ (jika domain tidak point ke public/)
├── artisan
├── composer.json
└── deploy.sh        ← Script auto deploy
```

## ⚠️ Penting!

1. **File `.env` JANGAN di-commit ke GitHub**
   - Sudah di-exclude di `.gitignore`
   - Pastikan file `.env` ada di server tapi tidak di GitHub

2. **Point Domain ke Folder `public/`**
   - Ini adalah best practice Laravel
   - Jika tidak bisa, gunakan `.htaccess` di root

3. **PHP Version**
   - Minimum PHP 8.1
   - Check dengan: `php -v`

4. **Permissions**
   - Folder `storage/` dan `bootstrap/cache/` harus writable
   - Run: `chmod -R 755 storage bootstrap/cache`

5. **Security**
   - Set `APP_DEBUG=false` di production
   - Gunakan HTTPS (SSL certificate)
   - Strong password untuk database

## 🐛 Troubleshooting

### Error 500
1. Check file `.env` sudah benar
2. Check permissions: `chmod -R 755 storage bootstrap/cache`
3. Check error log: `storage/logs/laravel.log`
4. Pastikan `APP_KEY` sudah di-generate

### Permission Denied
```bash
chmod -R 755 storage bootstrap/cache
chmod 644 .env
```

### Route tidak bekerja
1. Check `mod_rewrite` enabled di Apache
2. Check file `.htaccess` ada di `public/` folder
3. Check `APP_URL` di `.env` sudah benar

### Composer tidak ditemukan
1. Download `composer.phar` dan upload ke root folder
2. Atau install via hosting panel
3. Atau gunakan: `php composer.phar install`

### Database Connection Error
1. Check credentials di `.env`
2. Check database user memiliki privileges
3. Check `DB_HOST` (biasanya `localhost` atau `127.0.0.1`)

## 📚 Dokumentasi Lengkap

- **DEPLOYMENT.md** - Panduan deployment lengkap
- **README_SHARED_HOSTING.md** - Quick reference
- **SHARED_HOSTING_SETUP.txt** - Panduan singkat

## 🔐 Security Checklist

- [ ] `APP_DEBUG=false` di `.env`
- [ ] `APP_ENV=production` di `.env`
- [ ] Strong password untuk database
- [ ] SSL/HTTPS enabled
- [ ] File `.env` tidak di-commit ke GitHub
- [ ] Regular backup database
- [ ] Keep Laravel updated

## 💡 Tips

1. **Auto Deploy via Cron (Opsional)**
   - Setup cron job untuk auto-pull dari GitHub
   - Atau gunakan webhook dari GitHub

2. **Backup Sebelum Update**
   - Backup database sebelum update
   - Backup folder `storage/` jika ada file upload

3. **Test di Staging**
   - Buat subdomain untuk testing
   - Test update di staging dulu sebelum production

4. **Monitor Error Logs**
   - Check `storage/logs/laravel.log` regularly
   - Setup email notification untuk errors (jika mungkin)

## 🚀 Ready to Deploy?

1. Push code ke GitHub
2. Clone di hosting
3. Setup `.env`
4. Run migrations
5. Optimize
6. Done! ✅

---

**Need Help?** Check `DEPLOYMENT.md` untuk panduan lebih detail.

