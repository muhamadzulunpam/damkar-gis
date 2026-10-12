# Damkar GIS — Sistem Informasi Geografis Pemadam Kebakaran

Aplikasi Sistem Informasi Geografis (GIS) untuk mendukung pengelolaan data kejadian kebakaran dan penyelamatan serta analisis tingkat kerawanan wilayah di Kota Tangerang Selatan.

Proyek ini dikembangkan menggunakan Laravel dengan integrasi pemetaan wilayah dan algoritma **K-Means Clustering untuk mengelompokkan tingkat kerawanan menjadi tiga kategori: Tinggi, Sedang, dan Rendah.

## Tentang Proyek

Damkar GIS dirancang untuk membantu pengelolaan informasi kejadian kebakaran dan penyelamatan, memvisualisasikan persebaran kejadian berdasarkan wilayah, serta menyajikan hasil analisis kerawanan dalam bentuk yang lebih mudah dipahami.

Sistem ini juga menyediakan pengelolaan data berdasarkan peran pengguna dan laporan analisis berdasarkan periode yang dipilih.

## Fitur Utama

- Dashboard — Menampilkan ringkasan informasi kejadian dan data sistem.
- Manajemen Pengguna — Mengelola akun dan hak akses pengguna.
- Manajemen Wilayah — Mengelola data kecamatan dan kelurahan.
- Manajemen Jenis Kejadian — Mengelola kategori kejadian kebakaran dan penyelamatan.
- Manajemen Kejadian — Mengelola data kejadian kebakaran dan penyelamatan.
- Peta Kerawanan — Memvisualisasikan tingkat kerawanan berdasarkan wilayah geografis.
- Analisis K-Means — Mengelompokkan kelurahan ke dalam kategori kerawanan Tinggi, Sedang, dan Rendah.
- Laporan Analisis — Menampilkan ringkasan hasil analisis berdasarkan periode yang dipilih.
- Ekspor Laporan — Mengunduh laporan dalam format PDF dan Excel.
- Activity Log — Mencatat aktivitas pengguna untuk membantu penelusuran aktivitas sistem.

## Metode Analisis

Aplikasi menggunakan algoritma **K-Means Clustering** untuk mengelompokkan wilayah berdasarkan data yang digunakan dalam proses analisis.

Hasil pengelompokan disajikan dalam tiga tingkat kerawanan:

| Kategori | Keterangan |
| Tinggi | Kelurahan yang masuk ke kelompok kerawanan tinggi |
| Sedang | Kelurahan yang masuk ke kelompok kerawanan sedang |
| Rendah | Kelurahan yang masuk ke kelompok kerawanan rendah |

Hasil analisis digunakan untuk mendukung visualisasi peta kerawanan dan penyusunan laporan. Tingkat kerawanan merupakan hasil pengelompokan relatif terhadap data yang dianalisis, bukan prediksi pasti terjadinya kejadian.

## Hak Akses Pengguna

| Role | Hak Akses Utama |
| Admin | Mengelola pengguna, data master, data kejadian, peta, dan melihat hasil analisis |
| Petugas | Mengelola data kejadian serta melihat informasi wilayah dan peta |
| Pimpinan | Melihat informasi wilayah, peta kerawanan, hasil analisis, dan laporan |

Hak akses mengikuti konfigurasi otorisasi yang diterapkan di dalam aplikasi.

## Teknologi yang Digunakan

- Backend: PHP, Laravel
- Database: MySQL
- Frontend: Blade, HTML, CSS, JavaScript, Bootstrap
- Geographic Information System: Peta wilayah dan visualisasi tingkat kerawanan
- Data Mining: Algoritma K-Means Clustering
- Ekspor Dokumen: DomPDF dan Laravel Excel
- Version Control: Git dan GitHub

## Persyaratan Sistem

Pastikan perangkat pengembangan sudah memiliki:

- PHP 8.3 atau versi yang kompatibel dengan proyek
- Composer
- MySQL
- Git

Versi dependency yang digunakan mengikuti konfigurasi proyek di `composer.json` dan `package.json`.

## Instalasi dan Menjalankan Aplikasi

### 1. Clone repository

git clone https://github.com/muhamadzulunpam/damkar-gis.git
cd damkar-gis


### 2. Install dependency backend

composer install


### 3. Siapkan file environment

Salin file .env.example menjadi .env.

cp .env.example .env


Pada Windows, kamu juga bisa menyalin file tersebut secara manual melalui File Explorer.

### 4. Buat application key

php artisan key:generate


### 5. Konfigurasi database

Buat database MySQL untuk proyek ini, kemudian sesuaikan konfigurasi berikut di file .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=damkar_gis
DB_USERNAME=root
DB_PASSWORD=

Sesuaikan nama database, username, dan password dengan konfigurasi MySQL lokal kamu.

### 6. Jalankan migrasi database

php artisan migrate


Jika proyek memerlukan data awal, jalankan seeder yang tersedia sesuai konfigurasi proyek.

Catatan: Pastikan database sudah dibuat dan struktur tabel yang dibutuhkan tersedia sebelum menjalankan aplikasi.

### 8. Jalankan aplikasi

Buka terminal:

php artisan serve


Akses aplikasi melalui:

http://127.0.0.1:8000

## aporan dan Visualisasi

Hasil analisis K-Means yang telah tersimpan dapat digunakan untuk menampilkan peta kerawanan dan menyusun laporan berdasarkan periode yang dipilih.

Laporan menyediakan ringkasan analisis untuk kejadian kebakaran dan penyelamatan, serta fasilitas ekspor PDF dan Excel.

## Keamanan

- Jangan mengunggah file .env atau kredensial rahasia ke repository publik.
- Gunakan konfigurasi environment untuk menyimpan informasi sensitif.
- Terapkan hak akses sesuai role pengguna.
- Gunakan data yang sesuai dan valid saat melakukan analisis.

## Tujuan Pengembangan

Proyek ini dikembangkan sebagai implementasi pengembangan aplikasi web yang menggabungkan:

- Pengelolaan data operasional.
- Integrasi informasi geografis.
- Penerapan algoritma data mining.
- Visualisasi data untuk mendukung pengambilan keputusan.
- Pembuatan laporan yang dapat digunakan sebagai dokumentasi analisis.

## Developer

Muhamad Zul Sa'ban

Web Developer | Programmer

- GitHub: [@muhamadzulunpam](https://github.com/muhamadzulunpam)

Damkar GIS — Sistem Informasi Geografis untuk mendukung analisis kerawanan kebakaran dan penyelamatan di Kota Tangerang Selatan.