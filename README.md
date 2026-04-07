# Pendaftaran Siswa - Laravel

Migrasi dari PHP native ke Laravel 10/11.

## Stack
- **Framework**: Laravel 10 / 11
- **Database**: PostgreSQL
- **Frontend**: Blade Template + CSS + Vanilla JS + SweetAlert2

---

## Struktur File

```
laravel-pendaftaran/
├── app/
│   ├── Http/Controllers/
│   │   └── PesertaController.php   ← semua logic CRUD + AJAX
│   └── Models/
│       ├── Peserta.php
│       ├── Provinsi.php
│       └── Kabkot.php
├── database/migrations/
│   ├── ..._create_provinsi_table.php
│   ├── ..._create_kabkot_table.php
│   └── ..._create_peserta_table.php
├── public/assets/
│   ├── css/
│   │   ├── style.css               ← halaman utama
│   │   └── editstyle.css           ← halaman edit
│   └── js/
│       ├── script.js               ← halaman utama
│       └── edit.js                 ← halaman edit
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php           ← layout utama
│   └── peserta/
│       ├── index.blade.php         ← form + tabel
│       └── edit.blade.php          ← form edit
└── routes/
    └── web.php                     ← semua route
```

---

## Cara Instalasi

### 1. Buat project Laravel baru
```bash
composer create-project laravel/laravel pendaftaran-siswa
cd pendaftaran-siswa
```

### 2. Copy file-file berikut ke project Laravel kamu:

| File dari paket ini | Tujuan di project Laravel |
|---|---|
| `app/Http/Controllers/PesertaController.php` | `app/Http/Controllers/` |
| `app/Models/Peserta.php` | `app/Models/` |
| `app/Models/Provinsi.php` | `app/Models/` |
| `app/Models/Kabkot.php` | `app/Models/` |
| `database/migrations/*.php` | `database/migrations/` |
| `public/assets/` (folder) | `public/assets/` |
| `resources/views/` (folder) | `resources/views/` |
| `routes/web.php` | `routes/web.php` (replace isi) |

### 3. Setting .env
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=kuliah
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 4. Generate key & jalankan migrasi
```bash
php artisan key:generate
php artisan migrate
```

> **Catatan**: Jika tabel `provinsi`, `kabkot`, dan `peserta` sudah ada di database PostgreSQL kamu,
> **skip** migrasi. Cukup pastikan nama kolom sesuai.

### 5. Buat symlink storage (untuk foto upload)
```bash
php artisan storage:link
```

### 6. Jalankan server
```bash
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## Perbedaan dengan Versi PHP Native

| PHP Native | Laravel |
|---|---|
| `koneksi.php` (PDO manual) | `.env` + Eloquent ORM |
| `index.php` (logic + HTML campur) | `PesertaController@index` + `peserta/index.blade.php` |
| `edit.php` | `PesertaController@edit` + `update` + `peserta/edit.blade.php` |
| `hapus.php` | `PesertaController@destroy` (method DELETE) |
| `get_kabkot.php` | `PesertaController@getKabkot` (route `/get-kabkot`) |
| `move_uploaded_file()` manual | `$request->file('foto')->store('uploads', 'public')` |
| Tidak ada CSRF protection | `@csrf` di setiap form |
| Validasi manual | `$request->validate([...])` |

---

## Routes

| Method | URL | Action |
|---|---|---|
| GET | `/` | Tampilkan form + tabel |
| POST | `/peserta` | Simpan data baru |
| GET | `/peserta/{id}/edit` | Form edit |
| PUT | `/peserta/{id}` | Update data |
| DELETE | `/peserta/{id}` | Hapus data |
| GET | `/get-kabkot?id_prov=X` | AJAX kabupaten/kota |
