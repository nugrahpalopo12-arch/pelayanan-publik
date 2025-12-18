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
├── config/             # Konfigurasi database dan lingkungan
├── public/             
│   ├── css/            # Stylesheet (CSS)
│   ├── uploads/        # Penyimpanan foto laporan
│   ├── index.php       # Landing page / Redirect
│   ├── report_create.php   # Form pembuatan laporan (+ Peta)
│   ├── report_list.php     # Daftar laporan masyarakat
│   ├── report_map.php      # [BARU] Visualisasi peta sebaran laporan
│   └── ...             # File publik lainnya
├── src/                
│   ├── auth.php        # Helper otentikasi
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
- **Server:** Apache/Nginx (via Laragon/XAMPP)

## 🌟 Fitur Baru: Integrasi Peta

Kami baru saja menambahkan fitur **Geo-tagging**:
1.  **Input Lokasi:** Pelapor dapat menandai lokasi kejadian langsung di peta saat membuat laporan.
2.  **Visualisasi Data:** Halaman khusus `Peta Sebaran` untuk melihat titik-titik masalah di seluruh wilayah.
3.  **Navigasi:** Integrasi langsung dengan Google Maps untuk memandu petugas ke lokasi.

