# 📚 Laravel Library API

Take-home test project untuk posisi **Backend Developer**.  
Dibuat menggunakan **Laravel 12** + **Sanctum**.

---

## 🚀 Fitur
- **Auth** dengan Laravel Sanctum (Register, Login, Logout).
- **CRUD Buku**:
  - Validasi ISBN unik, published_year 4 digit, stock >= 0.
  - List dengan **pagination** + **search & filter** (by title, author, year).
- **Pinjam Buku (Loan API)**:
  - `POST /api/loans` → user meminjam buku.
  - `GET /api/loans/{user_id}` → daftar buku yang sedang dipinjam.
  - Stok otomatis berkurang, tidak bisa pinjam kalau stok habis.
- **Job Queue & Email Notifikasi**:
  - Saat pinjam, notifikasi email dikirim lewat **queue**.
- **Seeder**:
  - 10 user & 30 buku sample.
- **PSR-12** compliant, service layer terpisah untuk clean code.
- **Helper ApiResponse** untuk konsistensi JSON response.

---

## 🛠️ Persiapan

### 1. Clone & Install
```bash
git clone <repo-url>
cd <project-folder>
composer install
cp .env.example .env


2. Konfigurasi .env

Atur database (MySQL/SQLite) & mail driver:

DB_CONNECTION=mysql
DB_DATABASE=laravel_library
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
MAIL_MAILER=log

3. Migrasi & Seeder
php artisan migrate --seed


Seeder membuat:

10 user random.

30 buku random.

1 test user:

Email: test@example.com

Password: password

4. Jalankan server
php artisan serve

5. Jalankan queue worker
php artisan queue:work

📌 Endpoint API
Auth

POST /api/register → register user

POST /api/login → login user (dapatkan token)

POST /api/logout → logout user (butuh token)

Books (auth required)

GET /api/books → list buku (pagination, search, filter)

POST /api/books → tambah buku

GET /api/books/{id} → detail buku

PUT /api/books/{id} → update buku

DELETE /api/books/{id} → hapus buku

Loans (auth required)

POST /api/loans → pinjam buku (stok berkurang, email terkirim via queue)

GET /api/loans/{user_id} → daftar pinjaman user

🧪 Testing (opsional)

Jika ingin menjalankan test:

php artisan test

📂 Struktur Folder Penting

app/Services → Business logic (BookService, LoanService, AuthService).

app/Helpers/ApiResponse.php → helper untuk response standar.

app/Mail → email template (BookBorrowedMail).

app/Jobs → job queue (SendBookBorrowedEmail).

resources/views/emails → email blade templates.


---


✅ Dengan README ini, project sudah benar-benar siap untuk dikirim sebagai hasil tes teknikal.  

👉 Mau saya lanjut tambahkan **role admin vs user** (supaya hanya admin yang bisa CRUD buku, user biasa hanya bisa pinjam) biar jadi nilai plus tambahan?
