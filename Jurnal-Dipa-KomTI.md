Pengembangan Sistem Presensi Siswa Dan Guru Berbasis Barcode Menggunakan Metode Waterfall Pada Sekolah Dasar dan Menengah

**Muh Fadhil hakim**

<sup>1</sup>Teknik Informatika, UNDIPA, Makassar, Indonesia

e-mail: [<sup>1</sup>](mailto:1)[fhakim331@gmail.com](mailto:1xxxx@xxxx.xxx)

**_Abstrak_**

_…\_**\_**

**_Kata kunci_**_—3-5 kata kunci ….._

**_Abstract_**

&nbsp;_-\_**\_**_

**_Keywords—_**_3-5 keywords, …._

# 1. PENDAHULUAN

Presensi merupakan salah satu aspek penting dalam administrasi pendidikan karena berkaitan dengan pencatatan dan pemantauan kehadiran siswa maupun guru selama kegiatan pembelajaran. Data kehadiran tidak hanya digunakan untuk mengetahui tingkat kedisiplinan, tetapi juga menjadi bagian dari evaluasi pembelajaran dan penyusunan laporan administrasi sekolah. Perkembangan teknologi informasi memberikan peluang bagi sekolah untuk mengelola presensi secara digital melalui sistem informasi berbasis web. Salah satu teknologi yang dapat dimanfaatkan adalah barcode, termasuk _Quick Response_ (QR) Code, yang memungkinkan proses identifikasi dilakukan dengan memindai kode tertentu. Penggunaan teknologi ini dapat menjadi alternatif untuk mempermudah pencatatan kehadiran sekaligus mendukung pengelolaan data presensi di lingkungan sekolah dasar dan menengah.

Meskipun demikian, pencatatan presensi secara manual masih dapat menimbulkan sejumlah kendala. Kesalahan dalam mencatat kehadiran, keterlambatan dalam merekap data, hingga risiko kehilangan catatan merupakan beberapa masalah yang kerap terjadi dalam proses konvensional. Selain itu, pengelolaan presensi siswa dan guru membutuhkan data yang tersusun dengan baik agar informasi kehadiran mudah ditelusuri ketika diperlukan. Jika masih dilakukan secara manual, proses pengolahan data dan penyusunan laporan dapat menyita waktu serta tenaga. Kondisi ini menunjukkan perlunya sistem informasi yang dapat membantu sekolah mencatat kehadiran, mengelola data presensi, dan menghasilkan laporan secara lebih praktis dan terstruktur.

Pemanfaatan teknologi barcode dan QR Code dalam sistem presensi telah dibuktikan efektivitasnya oleh sejumlah penelitian terdahulu. Murni dan Sabaruddin menunjukkan bahwa penggunaan QR Code berbasis web mampu mempercepat proses perekapan data kehadiran serta meningkatkan keamanan data melalui penyimpanan basis data terpusat \[1\]. Fahlevi dan Erlansyah menemukan bahwa penerapan QR Code berbasis Android di tingkat sekolah menengah efektif meminimalisir manipulasi absensi dan mempermudah pencatatan kehadiran siswa \[2\]. Penelitian Prasetyo dkk. juga menyimpulkan bahwa absensi berbasis QR Code secara signifikan mempercepat proses presensi dan menghemat penggunaan kertas administrasi \[3\]. Sementara itu, Winarti dan Pribadi berhasil menerapkan _barcode scanner_ untuk mempermudah pembuatan laporan kehadiran secara _real-time_ \[4\]. Di tingkat sekolah dasar, Roihan dkk. membuktikan bahwa portal presensi berbasis QR Code memperoleh tingkat kelayakan dari ahli media sebesar 92% dan pendidik sebesar 90,67%, yang mengonfirmasi bahwa teknologi pemindaian sangat layak diterapkan pada jenjang pendidikan dasar \[5\]. Selain itu, pemindaian QR Code terbukti sangat efisien dengan kecepatan respon kurang dari 1 detik hingga 1,1 detik per transaksi serta tingkat akurasi pencatatan data mencapai 100% \[6\], \[7\].

Meskipun berbagai penelitian telah membuktikan keunggulan teknologi ini, sebagian besar studi terdahulu masih berfokus secara terpisah pada presensi siswa saja \[2\], \[3\], \[5\] atau presensi guru saja \[8\]. Masih terbatas penelitian yang mengintegrasikan pengolahan data kehadiran siswa dan guru secara bersamaan dalam satu portal terpadu untuk skala sekolah dasar dan menengah. Padahal, tata kelola sekolah yang efektif membutuhkan sinkronisasi data presensi antara tenaga pendidik dan peserta didik guna mendukung transparansi dan akuntabilitas administrasi sekolah secara menyeluruh.

Berdasarkan permasalahan dan celah penelitian tersebut, penelitian ini bertujuan untuk merancang dan mengembangkan sistem presensi siswa dan guru berbasis web dengan memanfaatkan teknologi _barcode/QR Code_ menggunakan metode _Waterfall_. Metode _Waterfall_ dipilih karena menawarkan alur pengembangan perangkat lunak yang sistematis dan sekuensial mulai dari analisis kebutuhan, perancangan sistem, implementasi, pengujian, hingga pemeliharaan sehingga sangat cocok untuk sistem administrasi sekolah yang membutuhkan kejelasan spesifikasi data dan dokumentasi yang rapi \[4\], \[9\]. Sistem yang dibangun diharapkan dapat mempermudah proses pencatatan kehadiran, pengelolaan data terintegrasi, serta penyusunan laporan presensi siswa dan guru secara efisien.

# 2. METODE PENELITIAN

Penelitian ini menggunakan metode _Research and Development_ (R&D), yaitu pendekatan penelitian yang bertujuan untuk menghasilkan suatu produk dan menguji fungsionalitasnya. Produk yang dikembangkan dalam penelitian ini berupa sistem presensi siswa dan guru berbasis web dengan memanfaatkan teknologi _Barcode_. Pendekatan R&D dipilih karena penelitian tidak hanya berfokus pada identifikasi permasalahan presensi, tetapi juga mencakup proses perancangan, pengembangan, dan pengujian sistem berdasarkan kebutuhan pengguna. Adapun model pengembangan perangkat lunak yang digunakan adalah _Waterfall_, karena memiliki tahapan pengembangan yang sistematis dan berurutan sehingga memudahkan proses pengembangan serta dokumentasi sistem.

Penelitian ini dilaksanakan di Yayasan Pendidikan Ihwanul Hayyi, yang beralamat di Jl. Poros Tala Tala, Kalenna Bontongape, Kecamatan Galesong, Kabupaten Takalar, Sulawesi Selatan. Lokasi ini dipilih karena yayasan tersebut menyelenggarakan pendidikan pada jenjang Sekolah Dasar (SD) dan Sekolah Menengah Pertama (SMP), sehingga sesuai dengan tujuan penelitian untuk mengembangkan sistem presensi terintegrasi bagi siswa dan guru pada kedua jenjang pendidikan tersebut.

## 2.1 Model Pengembangan Waterfall

Model Waterfall merupakan model pengembangan perangkat lunak yang memiliki tahapan secara berurutan, di mana setiap tahap diselesaikan sebelum melanjutkan ke tahap berikutnya. Model ini digunakan karena proses pengembangan sistem presensi membutuhkan analisis kebutuhan yang jelas, perancangan terstruktur, serta pengujian untuk memastikan kesesuaian fungsi sistem. Tahapan model Waterfall dalam penelitian ini meliputi:

### a. Analisis Kebutuhan (_Requirement Analysis_)

Tahap ini dilakukan dengan mengidentifikasi permasalahan dan kebutuhan sistem presensi melalui observasi, wawancara, serta dokumentasi. Kebutuhan yang dianalisis meliputi pengelolaan data siswa dan guru, pencatatan kehadiran menggunakan _Barcode_, serta pengolahan laporan presensi.

### b. Perancangan Sistem (_System Design_)

Pada tahap ini dilakukan perancangan arsitektur sistem, alur proses presensi, basis data, serta antarmuka pengguna. Perancangan bertujuan memberikan gambaran mengenai struktur dan mekanisme kerja sistem sebelum proses implementasi dilakukan.

### c. Implementasi (_Implementation_)

Tahap implementasi merupakan proses menerjemahkan rancangan sistem ke dalam bentuk aplikasi berbasis web. Pengembangan dilakukan menggunakan PHP, MySQL, Bootstrap, dan teknologi pendukung lainnya sesuai kebutuhan sistem. Pada tahap ini juga diterapkan mekanisme pembuatan dan pemindaian _Barcode_ untuk pencatatan kehadiran siswa dan guru.

### d. Pengujian (_Testing_)

Pengujian dilakukan setelah proses implementasi untuk memastikan setiap fungsi sistem berjalan sesuai dengan kebutuhan. Metode pengujian yang digunakan adalah Black Box Testing, dengan menguji fungsi pengelolaan data, autentikasi pengguna, pemindaian QR Code, pencatatan kehadiran, dan pembuatan laporan presensi.

### e. Pemeliharaan (_Maintenance_)

Tahap pemeliharaan dilakukan untuk memperbaiki kesalahan yang ditemukan setelah sistem diterapkan serta menyesuaikan sistem apabila terdapat kebutuhan baru. Tahap ini bertujuan menjaga agar sistem tetap berjalan sesuai dengan kebutuhan operasional sekolah.

## 2.2 Populasi dan sampel

Populasi dalam penelitian ini adalah seluruh pihak yang terlibat dalam kegiatan presensi di Yayasan Pendidikan Ihwanul Hayyi, meliputi siswa, guru, dan tenaga administrasi pada jenjang SD dan SMP. Populasi tersebut dipilih karena berkaitan langsung dengan proses pencatatan dan pengelolaan data kehadiran.

Teknik pengambilan sampel menggunakan purposive sampling, yaitu pemilihan sampel berdasarkan pertimbangan tertentu yang sesuai dengan tujuan penelitian. Sampel meliputi tenaga administrasi, guru, serta perwakilan siswa yang terlibat dalam proses presensi. Pemilihan sampel ini bertujuan memperoleh informasi mengenai kebutuhan sistem, alur presensi, serta masukan terhadap sistem yang dikembangkan. Jumlah sampel disesuaikan dengan kondisi dan data aktual dari pihak yayasan.

## 2.3 Teknik pengumpulan data

Teknik pengumpulan data dalam penelitian ini dilakukan melalui beberapa metode sebagai berikut:

### a. Observasi, dilakukan dengan mengamati secara langsung proses pencatatan kehadiran siswa dan guru di Yayasan Pendidikan Ihwanul Hayyi untuk mengetahui alur presensi serta permasalahan yang terjadi dalam proses administrasi

### b. Wawancara, dilakukan dengan pihak sekolah, seperti guru dan tenaga administrasi, untuk memperoleh informasi mengenai kebutuhan sistem, mekanisme pencatatan kehadiran, serta pengelolaan laporan presensi

### c. Dokumentasi, dilakukan dengan mengumpulkan dokumen pendukung yang berkaitan dengan kegiatan presensi, seperti data siswa, data guru, format rekapitulasi absensi, dan tata tertib sekolah. Data tersebut digunakan sebagai bahan analisis kebutuhan dan perancangan sistem

## 2.4 Bahan dan Alat penelitian

Bahan penelitian merupakan data dan dokumen yang digunakan sebagai dasar dalam pengembangan sistem presensi. Bahan yang digunakan dalam penelitian ini disajikan pada Tabel 2.1.

Tabel 2.1. Bahan Penelitian

| **Bahan**        | **Detail**                                                                                                   |
| ---------------- | ------------------------------------------------------------------------------------------------------------ |
| Data Master      | NIS, nama siswa, kelas, nama orang tua/wali, nomor WhatsApp orang tua/wali, nama guru, dan kontak wali kelas |
| Dokumentasi      | Format rekap absensi Excel dan tata tertib sekolah                                                           |
| Referensi Teknis | PHP 7.4+, MySQL 8.0+, WhatsApp Business API, dan Bootstrap 5                                                 |

Adapun alat yang digunakan dalam proses pengembangan sistem terdiri atas perangkat keras dan perangkat lunak. Rincian alat penelitian disajikan pada Tabel 2.2.

Tabel 2.2. Alat Penelitian

| **Alat**                | **Detail**                                                     |
| ----------------------- | -------------------------------------------------------------- |
| Perangkat Keras         | Komputer AMD Ryzen 5 5600X, RAM 32 GB, dan SSD 512 GB          |
| Sistem Operasi          | Windows 11                                                     |
| Development Environment | Laragon dan Visual Studio Code                                 |
| Database                | MySQL 8.0 dan phpMyAdmin                                       |
| Web Browser             | Google Chrome                                                  |
| Framework dan Library   | PHP 7.4, Bootstrap 5, jQuery, WhatsApp API, dan PHPSpreadsheet |
| Scanner                 | USB wired barcode scanner                                      |

## 2.5 Metode pengujian

Metode pengujian yang digunakan dalam penelitian ini adalah _Black Box Testing_, yaitu metode pengujian perangkat lunak yang berfokus pada pemeriksaan fungsi sistem berdasarkan masukan (_input_) dan keluaran (_output_) tanpa memeriksa struktur kode program secara langsung. Metode ini dipilih untuk memastikan bahwa setiap fitur sistem telah berjalan sesuai dengan kebutuhan yang ditentukan pada tahap analisis.

Pengujian dilakukan terhadap beberapa fungsi utama sistem, meliputi autentikasi pengguna, pengelolaan data siswa dan guru, pemindaian _Barcode_, pencatatan kehadiran, serta pengolahan dan penyusunan laporan presensi. Setiap fungsi diuji berdasarkan skenario yang telah ditentukan dengan membandingkan hasil aktual dan hasil yang diharapkan. Hasil pengujian kemudian digunakan untuk mengidentifikasi kesalahan dan melakukan perbaikan apabila ditemukan fungsi yang tidak berjalan sesuai dengan rancangan.

DAFTAR PUSTAKA

\[1\] S. Murni and R. Sabaruddin, "PEMANFAATAN QR CODE DALAM PENGEMBANGAN SISTEM INFORMASI KEHADIRAN SISWA BERBASIS WEB," _J. Teknol. Dan Manaj. Inform._, vol. 4, no. 2, Jun. 2018, doi: 10.26905/jtmi.v4i2.2144.

\[2\] F. Fahlevi, "Sistem Informasi Kehadiran Siswa menggunakan QR Code Berbasis Android (Studi Kasus SMK Negeri 3 Lubuklinggau)," _Jupit. J. Penelit. Ilmu Dan Teknol. Komput._, vol. 14, no. 2-b, pp. 317–327, 2022.

\[3\] Y. agus Prasetyo, T. Andriyanto, and R. Indriati, "Pengembangan Sistem Informasi Absensi Siswa Dengan Model Barcode," _Pros. SEMNAS INOTEK Semin. Nas. Inov. Teknol._, vol. 7, no. 1, pp. 277–284, Jul. 2023, doi: 10.29407/inotek.v7i1.3435.

\[4\] W. Winarti and D. A. Pribadi, "Penerapan Absensi Digital Berbasis Barcode Scanner Pada Mts Mathla'ul Anwar," _Fakt. Exacta_, vol. 18, no. 1, pp. 54–62, Jul. 2025, doi: 10.30998/faktorexacta.v18i1.26977.

\[5\] H. F. Roihan, H. H. S. Suryanti, and Y. P. Widhoyoko, "RANCANG BANGUN PORTAL PRESENSI ONLINE BERBASIS QR (QUICK RESPONSE) UNTUK SISWA KELAS V SD NEGERI 1 GEDONG KECAMATAN PRACIMANTORO," _Pendas J. Ilm. Pendidik. Dasar_, vol. 11, no. 03, pp. 290–300, Jul. 2026, doi: 10.23969/jp.v11i03.61954.

\[6\] A. Ridoh, Y. I. Putra, F. Yanti, D. H. Pitra, and N. Selpiani, "RANCANG BANGUN SISTEM INFORMASI PRESENSI MAHASISWA BERBASIS QR CODE DENGAN INTEGRASI NOTIFIKASI REAL-TIME BERBASIS WEB," _J. Inov. Pendidik. Dan Teknol. Inf. JIPTI_, vol. 7, no. 1, pp. 476–488, Apr. 2026, doi: 10.52060/jipti.v7i1.4147.

\[7\] E. W. Septiano and A. Pramono, "PENGEMBANGAN APLIKASI BERBASIS WEB PADA ABSENSI SISWA DENGAN MEMANFAATKAN QR CODE DI SMK IPEMS SURABAYASI BERBASIS WEB PADA ABSENSI SISWA DENGAN MEMANFAATKAN QR CODE DI SMK IPEMS SURABAYA," _JATI J. Mhs. Tek. Inform._, vol. 8, no. 5, pp. 8679–8683, Sep. 2024, doi: 10.36040/jati.v8i5.10712.

\[8\] E. Suryati, P. G. J. Mooy, D. A. Dopongtonung, and N. D. Rumlaklak, "PERANCANGAN SISTEM INFORMASI PRESENSI GURU MENGGUNAKAN QR-CODE DI SMPN 6 KUPANG TENGAH," _J. Pengabdi. Kpd. Masy._, vol. 4, no. 2, pp. 30–39, Dec. 2024.

\[9\] K. A. Ma'ruf and J. Aryanto, "PENGARUH PENERAPAN GEOLOCATION PRESENSI SISWA SMK NEGERI 1 NANGA PINOH MENGGUNAKAN QR CODE TERHADAP KETEPATAN WAKTU PRESENSI," _INTECOMS J. Inf. Technol. Comput. Sci._, vol. 8, no. 5, pp. 1657–1664, Oct. 2025, doi: 10.31539/1pyf3s87.