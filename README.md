📦 Inventory Management System
Sistem manajemen persediaan berbasis **Laravel (Backend)** dan **Vue.js (Frontend)** untuk mengelola Master Barang, Barang Masuk/Keluar, serta laporan.  
Aplikasi dilengkapi login, pagination, modal form, CRUD, validasi stok otomatis, dan proteksi route.

---

🚀 Fitur Utama

- Login & Logout (Token-based)
- Menu Master Barang (CRUD)
- Barang Masuk (CRUD + update stok otomatis)
- Barang Keluar (CRUD + validasi stok)
- Laporan Barang
- Pagination pada tabel
- Konfirmasi sebelum delete dan logout
- Vue Router + Proteksi Route (tidak bisa akses halaman tanpa login)
- UI dengan Bootstrap + Bootstrap Icons

---

🛠️ Teknologi yang Digunakan

Backend:
- Laravel v12.38.1
- PHP v8.2.12
- MySQL
- REST API

Frontend:
- Vue 3
- Vite
- Axios
- Bootstrap v5 + Bootstrap Icons  

---

📥 Instalasi Project

1.a. Clone Repository GitHub lewat CMD
- Buka CMD 
- Pilih path untuk simpan foldernya (User XAMPP biasanya  HTDOCS)
- cd PATH/YANG/INGIN/DITUJU
- Setelah itu masukan code ini (git clone https://github.com/RFOXgithub/Sistem_Pengaturan_Stock_Gudang)
 
1.b Clone Repository GitHub lewat VS Studi Code
- Buka aplikasi VS Studio Code
- Pada bagian Start atau Halaman Utama Pilih (Clone Git Repository)
- Masukan link repositorynya (https://github.com/RFOXgithub/Sistem_Pengaturan_Stock_Gudang)

2. Import Database dengan nama file elitechintern.sql

3. Instal Node.js v25.2.0, PHP 8.2.12, NPM 11.6.2

4. Install beberapa Depedency pada CMD (Jika diperlukan)
- composer install
- php artisan key:generate
- npm install

5. Jalankan perintah untuk hidupin server Laravel dan Vue.js pada dua CMD
- php artisan serve (Laravel)
- npm run dev (Vue.js)

6. Selesai, Sistem sudah bisa digunakan

-----------

Data Login: 
Email: admin@example.com
Password: password

-----------

## 📸 Tampilan Aplikasi

**Login Page**  
![Login Page](screenshot/Login-Page.png)

**Master Barang Page**  
![Master Barang Page](screenshot/Master-Barang-Page.png)

**Barang Masuk Page**  
![Barang Masuk Page](screenshot/Barang-Masuk-Page.png)

**Barang Keluar Page**  
![Barang Keluar Page](screenshot/Barang-Keluar-Page.png)

**Laporan Page**  
![Laporan Page](screenshot/Laporan-Page.png)

**Modal Tambah Barang**  
![Modal Tambah Barang](screenshot/Modal-Tambah-Barang.png)

**Modal Tambah Barang Masuk atau Keluar**  
![Modal Tambah Barang Masuk atau Keluar](screenshot/Modal-Tambah-Barang-Masuk-atau-Keluar.png)


