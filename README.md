Berikut **README.md** yang bisa langsung kamu taruh di root project.

---

# Adhivasindo BookHub 

API sederhana untuk Take-Home Test: **Auth (Sanctum)**, **CRUD Buku** + filter, dan **Peminjaman (Loans)** yang mengurangi stok & kirim email via queue (mailer `log`). Disertai **Postman Collection** dan **Testing**.

---

## 1) Prasyarat

* PHP 8.2+, Composer
* SQLite (disarankan) atau MySQL
* Ekstensi `sqlite3` aktif (jika pakai SQLite)

---

## 2) Instalasi & Konfigurasi

### Opsi A — SQLite (paling cepat)

```bash
composer install
cp .env.example .env
php artisan key:generate

# Buat file database SQLite
# Windows PowerShell:
New-Item -ItemType File database\database.sqlite -Force | Out-Null
# Linux/Mac:
# touch database/database.sqlite
```

Edit `.env`:

```env
APP_NAME="Adhivasindo BookHub"
APP_ENV=local
APP_KEY=base64:... (sudah diisi)
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
# Kosongkan DB_HOST/PORT/USER/PASS/DB_DATABASE

MAIL_MAILER=log
QUEUE_CONNECTION=database   # atau 'sync' untuk dev cepat (tanpa worker)
```

### Opsi B — MySQL (opsional)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookhub
DB_USERNAME=root
DB_PASSWORD=
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

> Buat database `bookhub` terlebih dahulu.

---

## 3) Migrasi & Seeder

```bash
php artisan migrate --seed
```

Seeder menambahkan user demo:

* Email: `iqbaldzulkarnaen12@gmail.com`
* Password: `password`

> Pastikan **User model** memakai `use Laravel\Sanctum\HasApiTokens;` dan tabel `personal_access_tokens` sudah termigrasi.

---

## 4) Menjalankan Aplikasi

```bash
# Jalankan server
php artisan serve

# Jika QUEUE_CONNECTION=database, jalankan worker di terminal lain
php artisan queue:work
```

---

## 5) Endpoint Utama

| Method | Path                   | Auth   | Keterangan                               |
| -----: | ---------------------- | ------ | ---------------------------------------- |
|   POST | `/api/login`           | —      | Login → Bearer token                     |
|    GET | `/api/books`           | Bearer | List buku + filter `?author=&year=&q=`   |
|   POST | `/api/books`           | Bearer | Tambah buku                              |
|    PUT | `/api/books/{id}`      | Bearer | Update buku                              |
| DELETE | `/api/books/{id}`      | Bearer | Hapus buku                               |
|   POST | `/api/loans`           | Bearer | Pinjam buku (`book_id`) → stok–1 + email |
|    GET | `/api/loans/{user_id}` | Bearer | Daftar pinjaman aktif user               |

**Opsional (memudahkan Postman):**
Tambahkan di `routes/api.php` untuk ambil user saat ini:

```php
use Illuminate\Http\Request;
Route::middleware('auth:sanctum')->get('/me', fn(Request $r) => $r->user());
```

> **Laravel 11:** pastikan `bootstrap/app.php` mendaftarkan route API:

```php
->withRouting(
  web: __DIR__.'/../routes/web.php',
  api: __DIR__.'/../routes/api.php',
  commands: __DIR__.'/../routes/console.php',
  health: '/up',
)
```

---

## 6) Pengujian via Postman

1. Import file:
   * `Adhivasindo BookHub API.postman_collection.json`
2. Pilih environment **Adhivasindo BookHub API.postman_collection.json**.
3. Jalankan:

   * **Auth → Login** → token otomatis tersimpan (`{{token}}`).
   * **Auth → Me** (opsional) → `{{user_id}}` otomatis terisi.
   * **Books → Create/List/Search** → `{{book_id}}` terisi saat create.
   * **Loans → Create Loan** → pinjam buku.
   * **Loans → List Active Loans by User** → lihat pinjaman aktif.

**Cek email log** (karena `MAIL_MAILER=log`):

* Windows PowerShell:

  ```powershell
  Get-Content .\storage\logs\mail.log -Wait
  # atau:
  Get-Content .\storage\logs\laravel.log -Wait
  ```

---

## 7) Testing Otomatis

### Pest (disarankan untuk gaya `it()`)

```bash
composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies
php artisan pest:install
```

`tests/Pest.php` minimal:

```php
<?php
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(TestCase::class, RefreshDatabase::class)->in('Feature','Unit');
```

`.env.testing`:

```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
CACHE_DRIVER=array
SESSION_DRIVER=array
MAIL_MAILER=log
QUEUE_CONNECTION=sync
```

Jalankan:

```bash
php artisan optimize:clear
php artisan test -v
```

> Alternatif: PHPUnit (class-based) dengan `class ... extends Tests\TestCase { use RefreshDatabase; }`.

---

## 8) Troubleshooting

* **`route:list` hanya 4 route** → pastikan `bootstrap/app.php` memetakan `routes/api.php`.
* **401 Unauthorized** → belum login / header Bearer token tidak dikirim.
* **422 "Stok habis"** → update stok buku dulu → pinjam ulang.
* **Email tidak muncul** → set `QUEUE_CONNECTION=sync` **atau** jalankan `php artisan queue:work`.

---

## Lisensi

Untuk keperluan take-home test & edukasi.
