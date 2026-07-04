# ☕ Rusyad Coffee - Sistem Manajemen Point of Sales (POS)

Sebuah aplikasi web *full-stack* sederhana dan responsif untuk mengelola kasir dan inventaris kafe. Dibangun menggunakan PHP *Native*, MySQL, dan antarmuka yang menarik dengan Bootstrap 5.

## 📝 Deskripsi Aplikasi

**Rusyad Coffee POS** dirancang untuk memudahkan pencatatan transaksi harian pada kedai kopi. Aplikasi ini menggunakan konsep 3 CRUD (Create, Read, Update, Delete) yang saling berelasi, memastikan data yang tersimpan terstruktur dengan baik. 

Fitur utama meliputi manajemen kategori menu, pendataan produk beserta harganya, hingga halaman kasir interaktif yang secara otomatis menghitung *subtotal* pesanan dan *grand total* keseluruhan.

## ✨ Fitur Utama

* **Kelola Kategori (CRUD):** Tambah dan hapus kategori menu (contoh: Coffee, Non-Coffee, Snack).
* **Kelola Produk (CRUD):** Tambah produk baru dengan mengaitkannya pada kategori yang sudah ada beserta penentuan harga.
* **Sistem Kasir/Transaksi:**
  * Pencatatan pesanan secara dinamis.
  * Perhitungan *Grand Total* secara otomatis.
  * Fitur "Selesaikan Pesanan" untuk menyelesaikan alur transaksi.
  * Tombol "Orderan Baru" untuk mengosongkan keranjang pesanan dengan cepat.
* **UI/UX Menarik:** Menggunakan tema warna bernuansa kopi yang elegan dan 100% responsif (mendukung layar HP maupun PC).

## 🛠️ Teknologi yang Digunakan

* **Frontend:** HTML5, CSS3, Bootstrap 5 (CDN)
* **Backend:** PHP Native (Procedural)
* **Database:** MySQL
* **Server Lokal:** XAMPP / Laragon (Apache & MariaDB)

---

## 🚀 Panduan Instalasi Singkat

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek di komputer lokal Anda:

### 1. Persiapan Lingkungan (Environment)
Pastikan Anda sudah menginstal aplikasi server lokal seperti **XAMPP** atau **MAMP** yang mendukung PHP dan MySQL.

### 2. Kloning / Salin Proyek
Buat folder baru bernama `rusyad_coffee` di dalam direktori server Anda:
* Jika menggunakan XAMPP: `C:\xampp\htdocs\rusyad_coffee`
* Jika menggunakan Laragon: `C:\laragon\www\rusyad_coffee`

Salin semua file proyek (`index.php`, `koneksi.php`, dll.) ke dalam folder tersebut.

### 3. Konfigurasi Database
1. Buka `http://localhost/phpmyadmin` di browser Anda.
2. Buat database baru dengan nama `rusyad_coffee`.
3. Jalankan sintaks SQL yang ada di dalam file `database.sql` atau *import* file tersebut untuk membuat tabel `categories`, `products`, dan `transactions`.

### 4. Sesuaikan Koneksi (Jika Perlu)
Buka file `koneksi.php` menggunakan *code editor* dan pastikan kredensial database sudah sesuai dengan pengaturan server lokal Anda.
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "rusyad_coffee";