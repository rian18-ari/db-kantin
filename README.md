# RFID Kantin Management System — Backend API

Sistem backend berbasis Laravel 13 untuk pengelolaan pembayaran kantin menggunakan kartu RFID, terintegrasi dengan gateway pembayaran **Midtrans** untuk top-up saldo secara otomatis.

## 🚀 Fitur Utama

- **RFID Payment Interface**: Endpoint untuk alat scan RFID (ESP32/Raspberry Pi) memproses transaksi.
- **Automated Top-up**: Integrasi Midtrans Snap API untuk isi ulang saldo user.
- **Real-time Webhook**: Sinkronisasi saldo otomatis saat status pembayaran di Midtrans berubah menjadi `settlement`.
- **Transaction History**: Pencatatan riwayat 'Jajan' (Debit) dan 'Top Up' (Kredit).

---

## 🛠️ Persyaratan Sistem

- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Akun Sandbox [Midtrans](https://dashboard.midtrans.com/)

---

## ⚙️ Instalasi & Setup

1. **Clone repositori**
   ```bash
   git clone <repository-url>
   cd <project-folder>
   ```

2. **Instal dependensi**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin `.env.example` ke `.env` dan sesuaikan database serta kredensial Midtrans Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Kredensial Midtrans**
   Dapatkan Server Key di Dashboard Midtrans (Sandbox) dan masukkan ke `.env`:
   ```env
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxx
   MIDTRANS_IS_PRODUCTION=false
   ```

5. **Migrasi & Seeding**
   Jalankan migrasi untuk membuat tabel dan data dummy:
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

---

## 🔌 Dokumentasi Endpoint (API)

Base URL: `http://localhost:8000/api`

### 1. Scan Kartu RFID (Pembayaran)
Digunakan oleh hardware (ESP32) saat siswa menempelkan kartu di kantin.

- **URL:** `/scan-card`
- **Method:** `POST`
- **Request Body:**
```json
{
    "rfid_uid": "A1B2C3D4",
    "amount": 15000
}
```
- **Response (200 OK):**
```json
{
    "success": true,
    "message": "Pembayaran berhasil.",
    "data": {
        "id": "uuid",
        "type": "payment",
        "amount": 15000,
        "status": "success",
        "created_at": "..."
    }
}
```

### 2. Top Up Saldo (Midtrans Snap)
Digunakan oleh Frontend/Mobile untuk membuat transaksi isi ulang.

- **URL:** `/topup`
- **Method:** `POST`
- **Request Body:**
```json
{
    "user_id": 1,
    "amount": 50000
}
```
- **Response (201 Created):**
```json
{
    "success": true,
    "data": {
        "snap_token": "xxxx-xxxx-xxxx",
        "redirect_url": "https://app.sandbox.midtrans.com/snap/v2/vtweb/xxxx",
        "order_id": "TRX-17140001"
    }
}
```

### 3. Webhook Midtrans
Endpoint rahasia yang dipanggil secara otomatis oleh server Midtrans.

- **URL:** `/webhook/midtrans`
- **Method:** `POST`
- **Catatan:** Pastikan endpoint ini dapat diakses secara publik (Gunakan **Ngrok** jika di localhost).

### 4. Mode Registrasi RFID (Pendaftaran Kartu Baru)
Sistem memiliki fitur khusus untuk mendaftarkan kartu RFID baru ke dalam sistem.

- **Admin UI:** `/admin/rfid/registration`
- **Logic:** 
  1. Aktifkan **"Mode Registrasi"** di halaman admin.
  2. Saat kartu tidak dikenal di-scan di hardware, sistem akan menyimpannya sebagai kartu **'pending'** alih-alih menolak transaksi.
  3. Admin dapat menghubungkan kartu 'pending' tersebut ke User yang tersedia atau membuat User baru langsung dari halaman yang sama.

- **API Override (Hardware):**
  Untuk memaksa mode registrasi dari sisi hardware tanpa menunggu toggle admin, tambahkan parameter `mode`:
  ```json
  {
      "rfid_uid": "NEW-UID-123",
      "mode": "registration"
  }
  ```

---

## 🖥️ Fitur Admin & Pendaftaran
Sistem kini dilengkapi dengan Dashboard Admin untuk memantau aktivitas dan mengelola kartu:

1. **Dashboard Utama (`/`):** Ringkasan statistik (Total User, Saldo, Transaksi Hari Ini) dan riwayat transaksi terbaru.
2. **Registration Center (`/admin/rfid/registration`):**
    - **Toggle Mode:** Mengaktifkan/mematikan mode pendaftaran global.
    - **Quick Add User:** Fitur untuk menambah siswa/user baru secara instan tanpa pindah halaman.
    - **UID Mapping:** Antrian kartu baru yang tertangkap sistem siap dihubungkan ke identitas siswa.

---

## 📡 Integrasi Hardware (ESP32 Example)

Jika menggunakan ESP32, kirimkan request ke endpoint `/scan-card` dengan format JSON. Pastikan ESP32 terhubung ke WiFi dan memiliki akses ke IP Server Laravel Anda.

```cpp
// Contoh Header Request ESP32
http.begin(client, "http://192.168.1.10:8000/api/scan-card");
http.addHeader("Content-Type", "application/json");
int httpResponseCode = http.POST("{\"rfid_uid\":\"" + uidString + "\", \"amount\":5000}");
```

---

## 📝 Catatan Tambahan
- Data User default dapat dilihat di `DatabaseSeeder`.
- Password default user dummy biasanya `password`.
- Gunakan [Ngrok](https://ngrok.com/) untuk mencoba Webhook Midtrans di lingkungan lokal.
