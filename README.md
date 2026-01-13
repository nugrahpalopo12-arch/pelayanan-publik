# Pelaporan Layanan Masyarakat

Solusi digital modern untuk meningkatkan responsivitas dan transparansi layanan publik.

## 🚀 Pitch Deck

**Masalah:**
Masyarakat sering kesulitan melaporkan masalah infrastruktur atau layanan publik (jalan rusak, lampu mati, sampah menumpuk). Laporan manual lambat, tidak transparan, dan sulit dilacak.

**Solusi Kami:**
**Pelaporan Layanan** adalah platform berbasis web yang memungkinkan warga melaporkan masalah secara real-time disertai dengan **lokasi presisi (Geo-tagging)** dan bukti foto.

**Value Proposition:**
- **Mudah & Cepat:** Lapor dalam hitungan detik.
- **Transparan:** Pantau status laporan (Baru -> Diproses -> Selesai).
- **Akurat:** Integrasi peta memastikan petugas tahu lokasi tepat kejadian.
- **Akuntabel:** Admin dapat mengelola dan menindaklanjuti laporan secara efisien.

---

## 📂 Struktur Folder

```
Pelaporan-layanan/
├── config/             # Konfigurasi database dan JWT
├── public/             
│   ├── api/            # [BARU] REST API Endpoints
│   │   ├── auth/       # Login & Register API
│   │   ├── users/      # CRUD Manajemen Pengguna
│   │   └── middleware.php # Auth Guard (JWT Check)
│   ├── css/            # Stylesheet (CSS)
│   ├── uploads/        # Penyimpanan foto laporan
│   ├── index.php       # Landing page (Dashboard)
│   ├── login.php       # Halaman Login (Fetch + JWT)
│   ├── register.php    # Halaman Register (Fetch + JWT)
│   ├── report_create.php   # Form pembuatan laporan (+ Peta)
│   ├── report_list.php     # Daftar laporan masyarakat
│   ├── report_map.php      # Visualisasi peta sebaran laporan
│   └── ...             
├── src/                
│   ├── auth.php        # Helper otentikasi (Legacy Session)
│   ├── jwt.php         # [BARU] Helper Manual JWT Class
│   ├── db.php          # Koneksi database (PDO)
│   └── functions.php   # Fungsi utilitas global
├── sql/                # Skema database
└── README.md           # Dokumentasi proyek
```

## 🛠 Tech Stack

Aplikasi ini dibangun dengan teknologi yang handal, cepat, dan mudah dipelihara:

- **Backend:** PHP Native (Modern PHP 8+)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3 (Custom Responsive Design)
- **Maps API:** OpenStreetMap & Leaflet.js (Gratis, Open Source, Ringan)
- **Sever:** Apache/Nginx (via Laragon/XAMPP)

## 🔌 API Documentation

Sistem ini kini mendukung **REST API** dengan autentikasi **JWT**. Semua endpoint berada di `/public/api/`.

### 🔐 Autentikasi (JWT)

#### 1. Login
- **URL**: `POST http://localhost/Pelaporan-layanan/public/api/auth/login.php`
- **Body** (JSON):
  ```json
  {
    "email": "admin@example.com",
    "password": "password123"
  }
  ```
- **Response**:
  ```json
  {
    "token": "eyJ0eXAiOiJKV1QiLCJhbG...",
    "user": { "id": 1, "name": "Admin", "role": "admin", "email": "..." }
  }
  ```

#### 2. Register
- **URL**: `POST http://localhost/Pelaporan-layanan/public/api/auth/register.php`
- **Body** (JSON):
  ```json
  {
    "name": "user",
    "email": "user@example.com",
    "password": "securepassword"
  }
  ```
- **Response**: Mengembalikan token dan data user baru.

---

### 👥 Manajemen Pengguna (CRUD)

> **Note**: Membutuhkan Header `Authorization: Bearer <TOKEN>`

#### 1. List Users (GET)
- **URL**: `GET http://localhost/Pelaporan-layanan/public/api/users/index.php`
- **Akses**: Admin Only
- **Response**: Array JSON daftar user.

#### 2. Create User (POST)
- **URL**: `POST http://localhost/Pelaporan-layanan/public/api/users/index.php`
- **Akses**: Admin Only
- **Body** (JSON):
  ```json
  {
    "name": "Staff Baru",
    "email": "staff@example.com",
    "password": "123",
    "role": "admin"
  }
  ```

#### 3. Update User (PUT)
- **URL**: `PUT http://localhost/Pelaporan-layanan/public/api/users/index.php`
- **Akses**: Admin Only
- **Body** (JSON):
  ```json
  {
    "id": 5,
    "name": "Staff Update",
    "role": "user"
  }
  ```

#### 4. Delete User (DELETE)
- **URL**: `DELETE http://localhost/Pelaporan-layanan/public/api/users/index.php?id=5`
- **Akses**: Admin Only

---

## 🛠 Cara Testing dengan Postman

1.  **Login**:
    -   Buat request `POST` ke `.../api/auth/login.php`.
    -   Masukkan email & password di **Body** -> **raw** -> **JSON**.
    -   Salin **token** dari response.

2.  **Akses Endpoint Terproteksi**:
    -   Buat request ke endpoint user (misal `GET .../api/users/index.php`).
    -   Ke tab **Auth**. Pilih Type: **Bearer Token**.
    -   Paste token yang disalin tadi.
    -   Send Request.

## 🌟 Fitur Baru: Integrasi Peta

Kami baru saja menambahkan fitur **Geo-tagging**:
1.  **Input Lokasi:** Pelapor dapat menandai lokasi kejadian langsung di peta saat membuat laporan.
2.  **Visualisasi Data:** Halaman khusus `Peta Sebaran` untuk melihat titik-titik masalah di seluruh wilayah.
3.  **Navigasi:** Integrasi langsung dengan Google Maps untuk memandu petugas ke lokasi.
