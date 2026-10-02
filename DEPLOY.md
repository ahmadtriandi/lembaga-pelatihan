# Panduan deploy ke VPS

Untuk VPS yang **sudah punya Nginx (atau Apache) + PHP**. Kode diambil dari GitHub,
lalu database SQLite dan foto upload dari laptop diunggah terpisah (keduanya tidak ikut di Git).

Ganti contoh berikut di semua perintah:

| Contoh | Ganti dengan |
|---|---|
| `user@IP_VPS` | user SSH dan IP VPS Anda, mis. `root@103.10.20.30` |
| `domainanda.com` | domain website |
| `/var/www/lembaga-pelatihan` | folder tujuan (boleh dibiarkan) |
| `8.3` | versi PHP di VPS (cek dengan `php -v`) |

---

## 1. Cek kesiapan server

Jalankan di VPS:

```bash
php -v                      # minimal PHP 8.2
php -m | grep -Ei 'pdo_sqlite|sqlite3|mbstring|xml|curl|fileinfo|gd|zip|bcmath'
composer -V
git --version
```

Jika ada ekstensi yang belum muncul (contoh untuk Ubuntu/Debian, sesuaikan versi PHP):

```bash
sudo apt install php8.3-sqlite3 php8.3-mbstring php8.3-xml php8.3-curl php8.3-gd php8.3-zip php8.3-bcmath
```

Jika Composer belum ada:

```bash
curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer
```

## 2. Ambil kode dari GitHub

```bash
cd /var/www
sudo git clone https://github.com/ahmadtriandi/lembaga-pelatihan.git
sudo chown -R $USER:www-data lembaga-pelatihan
cd lembaga-pelatihan
composer install --no-dev --optimize-autoloader
```

> Jika repo GitHub **private**, `git clone` akan meminta login. Gunakan
> [Personal Access Token](https://github.com/settings/tokens) sebagai password,
> atau pasang deploy key SSH lalu clone dengan `git@github.com:ahmadtriandi/lembaga-pelatihan.git`.

## 3. Atur `.env`

```bash
cp .env.example .env
php artisan key:generate
nano .env
```

Ubah baris-baris ini:

```dotenv
APP_NAME="Nama Lembaga"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com
APP_LOCALE=id

DB_CONNECTION=sqlite
LOG_LEVEL=error
```

`APP_DEBUG=false` **wajib** di server. Kalau `true`, pesan error akan menampilkan isi konfigurasi ke pengunjung.

## 4. Unggah database & foto dari laptop

Jalankan di **laptop** (PowerShell, dari folder proyek). `scp` sudah tersedia di Windows 10/11.

```powershell
scp database/database.sqlite user@IP_VPS:/var/www/lembaga-pelatihan/database/
scp -r storage/app/public user@IP_VPS:/tmp/foto-upload
```

Lalu di **VPS**, pindahkan foto ke tempatnya:

```bash
cd /var/www/lembaga-pelatihan
cp -r /tmp/foto-upload/. storage/app/public/
rm -rf /tmp/foto-upload
```

## 5. Siapkan aplikasi

```bash
cd /var/www/lembaga-pelatihan

php artisan migrate --force        # menambah tabel baru jika ada (aman untuk data lama)
php artisan storage:link           # agar foto upload bisa tampil
php artisan cache:clear            # buang cache bawaan dari laptop

# Izin tulis untuk web server (SQLite butuh izin tulis di file DAN foldernya)
sudo chown -R $USER:www-data storage bootstrap/cache database
sudo chmod -R 775 storage bootstrap/cache database

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Ganti password admin (wajib)

Database dari laptop masih memakai akun bawaan `admin@namalembaga.co.id` / `password123`.
Ganti **sebelum** website dibuka ke publik:

```bash
php artisan tinker --execute="\$u = App\Models\User::first(); \$u->email = 'email-anda@domain.com'; \$u->password = Hash::make('PasswordBaruYangKuat'); \$u->save(); echo 'OK';"
```

## 6. Konfigurasi web server

Arahkan domain ke folder **`public`**, bukan ke folder proyek.

### Nginx

Buat `/etc/nginx/sites-available/lembaga-pelatihan`:

```nginx
server {
    listen 80;
    server_name domainanda.com www.domainanda.com;
    root /var/www/lembaga-pelatihan/public;
    index index.php;

    client_max_body_size 64M;   # gambar jadwal bisa 8 MB, galeri bisa banyak foto sekaligus

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known) { deny all; }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/lembaga-pelatihan /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

Path socket PHP-FPM bisa berbeda. Cek dengan `ls /run/php/`.

### Apache (jika memakai Apache)

```apache
<VirtualHost *:80>
    ServerName domainanda.com
    ServerAlias www.domainanda.com
    DocumentRoot /var/www/lembaga-pelatihan/public
    <Directory /var/www/lembaga-pelatihan/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

```bash
sudo a2enmod rewrite && sudo a2ensite lembaga-pelatihan && sudo systemctl reload apache2
```

### Batas upload PHP

Bawaan PHP hanya 2 MB, sehingga gambar jadwal (sampai 8 MB) dan unggah banyak foto akan gagal.
Ubah `php.ini` milik FPM (mis. `/etc/php/8.3/fpm/php.ini`):

```ini
upload_max_filesize = 10M
post_max_size = 64M
max_file_uploads = 20
```

```bash
sudo systemctl restart php8.3-fpm
```

## 7. HTTPS (SSL gratis)

Pastikan DNS domain (A record) sudah mengarah ke IP VPS, lalu:

```bash
sudo apt install certbot python3-certbot-nginx   # Apache: python3-certbot-apache
sudo certbot --nginx -d domainanda.com -d www.domainanda.com
```

## 8. Cek hasil

- Buka `https://domainanda.com`. Foto, jadwal, fasilitas, dan tombol tema harus tampil.
- Login `https://domainanda.com/admin` dengan email dan password **baru**.
- Coba unggah satu foto di dashboard untuk memastikan izin folder dan batas upload sudah benar.
- **Pengaturan → Kontak → WhatsApp utama** harus berformat `628…`, bukan `08…`.
  Kalau masih `08…`, tombol WhatsApp tidak bisa membuka chat.

Jika muncul **500 Server Error**, lihat log:

```bash
tail -n 50 /var/www/lembaga-pelatihan/storage/logs/laravel.log
```

---

## Update berikutnya

Setelah ada perubahan kode yang sudah di-push ke GitHub, jalankan di VPS:

```bash
cd /var/www/lembaga-pelatihan
php artisan down
git pull
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan up
```

Database dan foto di server **tidak tersentuh** oleh `git pull`. Konten yang diisi lewat dashboard di server tetap aman.
Jangan unggah ulang `database.sqlite` dari laptop setelah website berjalan, karena data pendaftar di server akan tertimpa.

### Backup

Cukup salin dua hal ini secara berkala:

```bash
cd /var/www/lembaga-pelatihan
tar czf ~/backup-$(date +%F).tar.gz database/database.sqlite storage/app/public
```
