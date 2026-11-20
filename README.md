# E-Learning Sederhana (Laravel 10)

Admin mengelola course dan lesson (video). Student melihat course, membuka detail, dan menonton lesson.
Desain sistem, ERD, diagram alur, UI/UX, asumsi, dan keterbatasan: [`docs/DESIGN.md`](docs/DESIGN.md).

## Requirement
- PHP 8.1+ (ekstensi `fileinfo`, `mbstring`, `pdo_sqlite` atau `pdo_mysql`)
- Composer 2
- SQLite atau MySQL
- Internet saat demo (Tailwind CDN dan Google Fonts)

## Instalasi
```bash
composer create-project laravel/laravel:^10.0 elearning
cd elearning
```
Salin folder `app`, `database`, `resources`, `routes`, `docs` dari arsip ini ke dalam project, timpa file yang sama.

## Konfigurasi
**1. Database (`.env`)**
- SQLite: `DB_CONNECTION=sqlite`, hapus baris `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, lalu `touch database/database.sqlite`
- MySQL: isi `DB_*` seperti biasa

**2. Batas upload (`php.ini`, lalu restart PHP)**
```
upload_max_filesize = 512M
post_max_size       = 520M
max_execution_time  = 300
```
Aplikasi membatasi video maksimal 500 MB. Video disimpan privat di `storage/app/videos`.

## Menjalankan
```bash
php artisan migrate --seed
php artisan serve
```
Buka http://127.0.0.1:8000

## Akun demo (password: `password`)
Role : Email
Admin : admin@example.com
Student : student@example.com
