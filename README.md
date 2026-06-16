# Sistem Manajemen Parkir

Aplikasi ini adalah sistem manajemen parkir berbasis web yang dibangun menggunakan framework Laravel. Sistem ini menangani pencatatan kendaraan masuk dan keluar secara otomatis beserta kalkulasi biaya parkirnya secara dinamis.

## Penjelasan Alur Sistem

### 1. Arsitektur Data (Master Data)
Sebelum sistem bisa mencatat kendaraan masuk dan keluar, sistem memerlukan **Data Master** sebagai pondasi aturan. Ada dua data utama yang dikelola oleh admin:

*   **Data Lokasi (Locations)**
    *   Berfungsi untuk mencatat area parkir beserta **kapasitas maksimalnya**.
    *   Setiap lokasi memiliki batasan jumlah kendaraan untuk masing-masing jenis (maksimal motor, maksimal mobil, dan lainnya).
    *   Tujuannya agar sistem tahu kapan harus menolak kendaraan jika kapasitas di lokasi tersebut sudah penuh.
*   **Data Tarif Kendaraan (Vehicle Types)**
    *   Berfungsi untuk mengatur **aturan tarif** berdasarkan jenis kendaraan (Motor, Mobil, Lainnya).
    *   Aturan tarif dibagi menjadi tiga komponen utama:
        1.  Tarif per jam pertama (Flat awal).
        2.  Tarif per jam berikutnya (Progresif).
        3.  Tarif maksimal per hari (Batas tarif mentok dalam 1 hari agar tidak overcharge).

### 2. Alur Transaksi Utama (Core Flow)

Setelah Data Master siap, aplikasi akan menjalankan alur transaksi sehari-hari seperti berikut:

#### A. Alur Kendaraan Masuk (Check-In)
1.  **Input Petugas:** Kendaraan datang, petugas akan memilih *Lokasi Parkir*, *Jenis Kendaraan*, dan menginput *Plat Nomor*.
2.  **Validasi Kapasitas:** Sistem akan mengecek ketersediaan tempat di lokasi tersebut berdasarkan *Data Lokasi*. Jika jumlah kendaraan yang sedang parkir sudah mencapai batas maksimal, sistem menolak transaksi.
3.  **Perekaman Data:** Jika kapasitas tersedia, sistem akan:
    *   Mencatat waktu masuk (Timestamp saat ini).
    *   Menyalin aturan tarif (tarif jam pertama, jam berikutnya, maks harian) ke dalam tabel transaksi. (Ini dilakukan agar jika besok tarif master berubah, tarif kendaraan yang sudah masuk tidak ikut berubah/tetap sesuai tarif saat masuk).
    *   Menerbitkan **Nomor Tiket (Barcode/QR Code)** unik sebagai bukti parkir.

#### B. Alur Kendaraan Keluar (Check-Out)
1.  **Identifikasi Tiket:** Kendaraan keluar, petugas memindai Nomor Tiket (atau mencari berdasarkan Plat Nomor).
2.  **Kalkulasi Waktu:** Sistem menghitung selisih antara *Waktu Masuk* dan *Waktu Keluar* (sekarang) untuk mendapatkan durasi parkir dalam satuan menit dan hari.
3.  **Kalkulasi Tarif (Logika Bisnis Utama):**
    *   Sistem menghitung total biaya berdasarkan durasi dikali dengan tarif (per jam pertama + per jam berikutnya).
    *   Jika kendaraan menginap berhari-hari, sistem akan menerapkan logika tarif *Maksimal Per Hari* jika hitungan per jam melebihi batas tersebut.
4.  **Penyelesaian:** Transaksi ditandai selesai, sistem mencatat total tagihan.
5.  **Cetak Struk:** Sistem meng-generate struk pembayaran parkir (bisa dalam format PDF) untuk diberikan ke pengendara.

### 3. Pelaporan & Monitoring (Reporting)
*   Sistem menyediakan menu riwayat/laporan yang menampilkan **semua data transaksi**.
*   Dari data tersebut, pengelola (admin/manajer) dapat memonitor:
    *   Kendaraan yang masih berada di dalam area parkir (Status *Belum Keluar*).
    *   Total pendapatan parkir dalam periode tertentu.
    *   Tingkat kepadatan lahan parkir (Occupancy rate).

---

> **Elevator Pitch:**
> Aplikasi ini adalah sistem parkir berbasis web yang dinamis. Dimulai dari admin yang mengatur kapasitas lahan dan aturan tarif progresif. Saat kendaraan masuk, sistem mengecek ketersediaan lahan secara real-time dan membuat tiket. Saat keluar, sistem otomatis menghitung tarif berdasarkan durasi parkir dan aturan maksimal per-hari, lalu mencetak struk digital PDF. Semua data ini terekam aman di database untuk keperluan laporan pendapatan harian.
