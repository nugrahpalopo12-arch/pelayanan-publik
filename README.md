# Sistem Pelaporan Layanan (Service Reporting System)

Sistem Pelaporan Layanan adalah sebuah aplikasi berbasis web yang dibangun menggunakan **PHP Native** dan **MySQL**. Aplikasi ini dirancang untuk memfasilitasi pengguna dalam membuat, melacak, dan mengelola laporan atau pengaduan layanan.

## 📋 Fitur Utama

* [cite_start]**Autentikasi Pengguna:** Sistem registrasi dan login yang aman untuk pengguna[cite: 14, 16, 25].
* [cite_start]**Buat Laporan:** Pengguna dapat membuat laporan baru terkait masalah layanan[cite: 18].
* [cite_start]**Daftar Laporan:** Melihat riwayat dan daftar laporan yang telah masuk[cite: 21].
* [cite_start]**Update Status:** Fitur untuk memperbarui status laporan (misal: Pending, Sedang Diproses, Selesai)[cite: 23].
* [cite_start]**Manajemen Upload:** Dukungan penyimpanan file/bukti laporan dalam folder `storage`[cite: 30].
* [cite_start]**Desain Responsif:** Menggunakan CSS kustom untuk tampilan formulir dan laporan yang rapi[cite: 1, 8].

## 🛠️ Teknologi yang Digunakan

* **Bahasa Pemrograman:** PHP (Native)
* **Database:** MySQL
* **Frontend:** HTML5, CSS3, JavaScript
* **Server:** Apache (XAMPP/WAMP/Laragon)

## 📂 Struktur Folder

```text
Pelaporan-layanan/
├── config/             # Konfigurasi database
├── public/             # File yang dapat diakses publik (CSS, JS, PHP Pages)
├── sql/                # Skema database (schema.sql)
├── src/                # Logika backend (Auth, DB Connection, Functions)
├── storage/            # Tempat penyimpanan file upload
└── README.md           # Dokumentasi proyek
