# Website Lembaga Pelatihan + Dashboard (Laravel)

Website company profile lembaga pelatihan & sertifikasi, lengkap dengan dashboard admin
untuk mengelola program, bidang, artikel, galeri, klien, testimoni, pendaftar, dan pengaturan situs.

Folder ini berisi **file aplikasi** yang ditimpakan ke proyek Laravel baru (Laravel 11 atau 12, PHP 8.2+).

## Instalasi

```bash
# 1. Buat proyek Laravel baru
composer create-project laravel/laravel lembaga-pelatihan
cd lembaga-pelatihan

# 2. Salin semua isi folder ini ke dalam proyek (timpa file yang sama)
#    app/, bootstrap/, database/, public/, resources/, routes/

# 3. Atur .env
#    APP_NAME="Nama Lembaga"
#    APP_URL=http://localhost:8000
#    APP_LOCALE=id              <- supaya tanggal tampil "16 September 2026"
#    DB_CONNECTION=mysql        <- atau biarkan sqlite untuk percobaan
#    DB_DATABASE=lembaga_pelatihan
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Buat tabel + isi data contoh
php artisan migrate --seed

# 5. Hubungkan folder upload agar gambar bisa tampil
php artisan storage:link

# 6. Jalankan
php artisan serve
```

- Website: http://localhost:8000
- Dashboard: http://localhost:8000/admin
- Login awal: `admin@namalembaga.co.id` / `password123`
  **Segera ganti email & kata sandi ini** (misalnya lewat `php artisan tinker`).

Tidak perlu `npm install` — CSS dan JS sudah berupa file biasa di `public/css` dan `public/js`.

Untuk memasang di VPS, lihat [DEPLOY.md](DEPLOY.md).

## Fitur

**Website publik**
- Beranda: hero, keunggulan, tentang kami + visi-misi, daftar program dengan filter bidang
  dan detail yang bisa dibuka-tutup, alur pendaftaran, galeri, angka pencapaian, klien,
  testimoni, formulir pendaftaran, artikel terbaru
- Halaman daftar artikel (`/artikel`) dan detail artikel (`/artikel/{slug}`)
- Tombol WhatsApp melayang, nomor/sosmed/alamat diambil dari pengaturan
- Responsif (HP) dan mendukung mode gelap

**Dashboard admin** (`/admin`)
- Ringkasan: pendaftar baru, total pendaftar, program aktif, artikel, program terpopuler
- **Pendaftar**: cari & filter, ubah status (baru / dihubungi / terdaftar / batal),
  tombol chat WhatsApp berisi sapaan otomatis, unduh CSV untuk Excel
- **Program**: tambah/ubah/hapus, harga, durasi, level, gambar, sembunyikan/tampilkan,
  detail unit kompetensi/kualifikasi/persyaratan/fasilitas (satu poin per baris)
- **Bidang**: kategori program + warna penanda
- **Artikel**: judul, slug otomatis, ringkasan, isi HTML, sampul, draf & terjadwal
- **Galeri**: unggah banyak foto sekaligus
- **Klien** dan **Testimoni**
- **Pengaturan**: nama lembaga, logo, teks beranda, visi-misi, angka pencapaian,
  WhatsApp, email, alamat, Google Maps, media sosial

## Struktur penting

| Lokasi | Isi |
|---|---|
| `routes/web.php` | Semua URL website & dashboard |
| `app/Http/Controllers/SiteController.php` | Halaman publik & simpan pendaftaran |
| `app/Http/Controllers/Admin/*` | Semua fitur dashboard |
| `app/Models/*` | Model database |
| `database/migrations/…create_site_tables.php` | Struktur tabel |
| `database/seeders/DatabaseSeeder.php` | Akun admin & data contoh |
| `resources/views/site/*` | Tampilan website |
| `resources/views/admin/*` | Tampilan dashboard |
| `public/css/site.css`, `public/css/admin.css` | Gaya tampilan |

## Catatan keamanan
- Isi artikel ditampilkan sebagai HTML. Berikan akses dashboard hanya kepada admin tepercaya.
- Formulir pendaftaran dibatasi 5 kiriman per menit per IP; login dibatasi 10 percobaan per menit.
- Saat online, set `APP_ENV=production` dan `APP_DEBUG=false`.
