# Sistem Manajemen Anggota (PHP + MySQL)

Project ini berisi 3 halaman sesuai gambar yang diberikan:
- **Login & Registrasi** (`login.php`, `register.php`)
- **Dashboard** (`dashboard.php`) — statistik + data anggota (tambah/edit/hapus/cari)
- **Laporan Data Anggota** (`laporan.php`) — filter status/bulan/tahun + Export PDF & Export Excel

## Cara menjalankan (XAMPP / Laragon)

1. **Copy folder** `manajemen_anggota` ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
   Contoh path akhir: `C:\xampp\htdocs\manajemen_anggota`

2. **Nyalakan Apache dan MySQL** dari XAMPP Control Panel.

3. **Buat database**:
   - Buka `http://localhost/phpmyadmin`
   - Klik tab **Import**, pilih file `database.sql`, klik **Go**.
   - Atau lewat terminal: `mysql -u root -p < database.sql`

4. **Buka di browser**:
   `http://localhost/manajemen_anggota/`
   (otomatis diarahkan ke halaman login)

5. **Login dengan akun demo**:
   - Username: `samso`
   - Password: `samso123`

   Atau klik **Registrasi** untuk membuat akun baru.

## Struktur file

```
manajemen_anggota/
├── index.php              # redirect otomatis ke login/dashboard
├── login.php
├── register.php
├── logout.php
├── dashboard.php
├── tambah_anggota.php
├── edit_anggota.php
├── delete_anggota.php
├── laporan.php
├── export_excel.php       # unduh laporan sebagai .xls
├── export_pdf.php         # tampilan cetak, otomatis buka print dialog -> Save as PDF
├── config/
│   └── db.php              # koneksi database (ubah user/password MySQL di sini jika perlu)
├── includes/
│   ├── auth.php            # proteksi halaman (wajib login)
│   └── sidebar.php         # menu sidebar
├── assets/
│   └── css/style.css       # semua styling (warna ungu sidebar, kartu statistik, dll)
└── database.sql            # skema database + data contoh
```

## Catatan penting supaya tidak "not found"

- Semua link sidebar (`Dashboard`, `Tambah Anggota`, `Laporan`, `Logout`) sudah menunjuk ke file
  yang benar-benar ada di folder ini — tidak ada link yang menuju halaman kosong.
- `index.php` disediakan supaya membuka folder root (`/manajemen_anggota/`) tidak menampilkan
  error "404 / Object not found", langsung diarahkan ke login atau dashboard.
- Tombol **Edit** dan **Hapus** pada tabel memakai `id` anggota yang valid dari database,
  jadi tidak akan mengarah ke halaman kosong.
- Jika halaman menampilkan error koneksi database, itu tandanya file `database.sql` belum
  di-import atau MySQL belum menyala — bukan error "file not found".

## Kredensial database default

Di `config/db.php`:
```php
DB_HOST = localhost
DB_NAME = manajemen_anggota
DB_USER = root
DB_PASS = ""   // kosong (default XAMPP)
```
Ubah `DB_USER` / `DB_PASS` jika instalasi MySQL Anda berbeda.
