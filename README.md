# ERP Telur — Sistem Informasi Produksi & Pergudangan

Aplikasi ERP berbasis web untuk mengelola alur produksi telur, mulai dari penerimaan bahan baku, proses produksi, quality control, penyimpanan di gudang, sampai pengiriman produk jadi ke pelanggan.

Dibangun dengan **Laravel 12** dan **Bootstrap 5**, dilengkapi kontrol akses berbasis peran (role), pencatatan audit log, serta ekspor laporan ke PDF dan CSV.

---

## Daftar Isi

- [Fitur](#fitur)
- [Peran Pengguna](#peran-pengguna)
- [Alur Bisnis](#alur-bisnis)
- [Teknologi](#teknologi)
- [Instalasi](#instalasi)
- [Akun Admin Pertama](#akun-admin-pertama)
- [Struktur Proyek](#struktur-proyek)
- [Lisensi](#lisensi)

---

## Fitur

### Master Data
- **Satuan** — satuan ukur bahan dan produk (kg, butir, pcs, dll.)
- **Bahan Baku** — data bahan baku beserta stok
- **Supplier** — data pemasok
- **Kemasan** — data kemasan beserta stok
- **Produk** — produk jadi beserta stok
- **Jenis Produk** — kategorisasi produk
- **Gudang** — gudang penyimpanan beserta kapasitas terpakai
- **User** — manajemen pengguna dan hak akses

### Transaksi
- **Penerimaan Bahan Baku** — pencatatan barang masuk dari supplier, stok otomatis bertambah
- **Penerimaan Kemasan** — pencatatan kemasan masuk
- **Produksi** — pemakaian beberapa bahan baku + kemasan sekaligus, menghasilkan produk jadi dan menambah stok gudang
- **Quality Control** — pengujian per batch produksi; batch yang gagal akan mengoreksi stok produk secara otomatis
- **Pengiriman** — pengeluaran produk dari gudang beserta cetak **Surat Jalan (PDF)**

### Laporan
- Laporan Produksi — tampilan web, **PDF**, dan **CSV/Excel**
- Laporan QC — tampilan web dan **PDF**
- Laporan Stok — tampilan web dan **PDF**

### Keamanan & Audit
- Autentikasi login dengan status aktif/nonaktif karyawan
- Otorisasi per peran melalui middleware `role`
- **Audit Log** mencatat setiap aksi penting (siapa, kapan, modul apa) dan dapat diekspor ke PDF

---

## Peran Pengguna

| Peran | Hak Akses |
| --- | --- |
| **Admin** | Akses penuh: seluruh master data, user, transaksi, laporan, dan audit log |
| **Produksi** | Lihat bahan baku & produk, input produksi, lihat laporan produksi |
| **QC** | Lihat jenis produk, input quality control, lihat laporan QC |
| **Gudang** | Lihat gudang, penerimaan bahan baku & kemasan, pengiriman, laporan stok |
| **Pimpinan** | Hanya melihat laporan (produksi, QC, stok) |

Hak akses ditegakkan di dua lapis: pada definisi route (`routes/web.php`) dan pada tampilan menu di layout.

---

## Alur Bisnis

```
Supplier ──► Penerimaan Bahan Baku ──┐
                                     ├──► Produksi ──► QC ──► Stok Produk Jadi
Penerimaan Kemasan ──────────────────┘                            │
                                                                  ▼
                                                          Pengiriman + Surat Jalan
```

1. **Admin** menyiapkan master data (satuan, bahan baku, kemasan, produk, gudang, user).
2. **Gudang** mencatat penerimaan bahan baku dan kemasan sehingga stok bertambah.
3. **Produksi** mencatat pemakaian bahan baku dan kemasan; stok bahan berkurang dan stok produk jadi bertambah di gudang tujuan.
4. **QC** menguji batch produksi. Jika status **Gagal**, stok produk dan kapasitas gudang otomatis dikoreksi.
5. **Gudang** mengirim produk ke pelanggan; stok berkurang dan surat jalan dapat dicetak.
6. Setiap aksi tercatat di **Audit Log** dan tersedia dalam bentuk laporan.

---

## Teknologi

| Komponen | Versi / Keterangan |
| --- | --- |
| PHP | `^8.2` |
| Laravel | `^12.0` |
| Database | SQLite (default) atau MySQL/MariaDB |
| Tampilan | Blade + Bootstrap 5.3 + Font Awesome 6 |
| PDF | `barryvdh/laravel-dompdf` |
| Frontend build | Vite 7 (opsional, aset utama memakai CDN) |

---

## Instalasi

### Prasyarat
- PHP 8.2+ dengan ekstensi `pdo`, `mbstring`, `openssl`, `fileinfo`
- Composer
- Node.js & npm (opsional, untuk build aset)

### Langkah

```bash
# 1. Clone repositori
git clone https://github.com/timothychristopher/erp-telur.git
cd erp-telur

# 2. Install dependency PHP
composer install

# 3. Siapkan file environment
cp .env.example .env
php artisan key:generate
```

Untuk Windows (PowerShell), gunakan `Copy-Item .env.example .env`.

```bash
# 4. Sesuaikan koneksi database di .env
#    Default memakai SQLite. Untuk MySQL:
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=erp_telur
#    DB_USERNAME=root
#    DB_PASSWORD=

# 5. Buat database SQLite (lewati jika memakai MySQL)
touch database/database.sqlite

# 6. Jalankan migrasi + seeder
php artisan migrate --seed

# 7. Build aset frontend (opsional)
npm install && npm run build

# 8. Jalankan server
php artisan serve
```

Aplikasi dapat diakses di <http://127.0.0.1:8000>.

---

## Akun Admin Pertama

Akun admin dibuat oleh `database/seeders/UserSeeder.php` dan mengambil kredensial dari file `.env`:

```env
ADMIN_EMAIL=admin@ovoegg.com
ADMIN_PASSWORD=ganti-password-ini
```

**Disarankan** mengisi `ADMIN_PASSWORD` di `.env` **sebelum** menjalankan `php artisan migrate --seed`. Jika tidak diisi, password default akan dipakai — segera ganti setelah login pertama.

> ⚠️ Jangan pernah meng-commit file `.env` ke repositori.

---

## Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/     # Logika tiap modul (Produksi, QC, Pengiriman, Laporan, dll.)
│   └── Middleware/      # RoleMiddleware — otorisasi berbasis peran
└── Models/              # Model Eloquent (BahanBaku, Produksi, Pengirimans, ...)

database/
├── migrations/          # Skema tabel
└── seeders/             # Data awal (akun admin)

resources/views/         # Tampilan Blade per modul
routes/web.php           # Definisi route + pembagian hak akses
```

---

## Lisensi

Dirilis di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
