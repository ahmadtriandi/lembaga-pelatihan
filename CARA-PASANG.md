# Galeri dua baris: baris foto dan baris video

Paket ini **mengganti 3 file** dari paket galeri sebelumnya. File lain
(migration, model, controller, tampilan admin, route) tidak berubah,
jadi kalau paket sebelumnya sudah dipasang, cukup timpa 3 file ini.

| File | Keterangan |
|---|---|
| `resources/views/site/partials/gallery.blade.php` | Timpa |
| `public/css/gallery.css` | Timpa |
| `public/js/gallery.js` | Timpa |

## Kalau paket galeri sebelumnya BELUM dipasang
Pasang dulu paket itu sampai langkah 6 (migration, model, controller,
tampilan admin, route, sambungan CSS/JS di layout, `@include` di beranda),
baru timpa 3 file ini.

## Setelah menimpa
```bash
php artisan optimize
```
Lalu muat ulang halaman dengan Ctrl+F5 agar CSS dan JS lama tidak dipakai browser.

## Hasilnya
- Baris pertama berjudul **Foto**, baris kedua berjudul **Video**.
- Tiap baris punya panah gesernya sendiri, muncul hanya kalau isinya tidak muat di layar.
- Kartu foto berbentuk 4:3, kartu video 16:9 mengikuti bentuk asli YouTube.
- Baris yang kosong tidak ditampilkan. Jadi kalau belum ada video,
  hanya baris Foto yang muncul.
- Urutan dalam tiap baris mengikuti angka urutan yang Anda atur di dashboard.

## Ingin batas jumlah item per baris?
Di `app/Http/Controllers/SiteController.php` method `home()`, naikkan jumlah
item yang diambil supaya kedua baris cukup terisi, misalnya:

```php
'galleries' => Gallery::orderBy('sort_order')->latest()->take(24)->get(),
```
