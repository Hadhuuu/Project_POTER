CREATE DATABASE DB_POTER;
USE DB_POTER;

-- Tabel untuk Dosen
CREATE TABLE dosen (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nidn VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);

CREATE TABLE kelas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL,
    prodi VARCHAR(255),
    angkatan INT, -- Menggunakan INT untuk menyimpan tahun
    id_dpa INT NULL, -- Harus NULL agar ON DELETE SET NULL dapat diterapkan
    FOREIGN KEY (id_dpa) REFERENCES dosen(id) ON DELETE SET NULL
);

-- Tabel untuk Mahasiswa
CREATE TABLE mahasiswa (
    id INT IDENTITY(1,1) PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(255) NOT NULL,
    ttl DATE,                      -- Tanggal Lahir
    email VARCHAR(255),
    id_kelas INT NOT NULL,          -- Kelas yang Diikuti
    FOREIGN KEY (id_kelas) REFERENCES kelas(id) ON DELETE CASCADE
);

-- Tabel untuk Akun
CREATE TABLE akun (
    id INT IDENTITY(1,1) PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'dosen', 'mahasiswa')),
    id_mahasiswa INT NULL,        -- ID Mahasiswa jika role mahasiswa
    id_dosen INT NULL,            -- ID Dosen jika role dosen
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id),
    FOREIGN KEY (id_dosen) REFERENCES dosen(id)
);

-- Tabel untuk Jenis Pelanggaran
CREATE TABLE jenis_pelanggaran (
    id INT IDENTITY(1,1) PRIMARY KEY,
    keterangan TEXT,
    tingkatan VARCHAR(3) CHECK (tingkatan IN ('V', 'IV', 'III', 'II', 'I'))
);
ALTER TABLE jenis_pelanggaran
ALTER COLUMN tingkatan VARCHAR(5);

ALTER TABLE jenis_pelanggaran
ALTER COLUMN keterangan VARCHAR(MAX);

-- Tabel untuk Jenis Sanksi
CREATE TABLE jenis_sanksi (
    id INT IDENTITY(1,1) PRIMARY KEY,
    keterangan TEXT,
    tingkatan VARCHAR(3) CHECK (tingkatan IN ('V', 'IV', 'III', 'II', 'I'))
);

-- Tabel untuk Pelanggaran
CREATE TABLE pelanggaran (
    id INT IDENTITY(1,1) PRIMARY KEY,
    keterangan TEXT,
    tanggal DATE,
    id_mahasiswa INT,
    id_pelapor INT,
    tingkatan_pelanggaran INT,
    id_sanksi INT,
    status VARCHAR(20) CHECK (status IN ('resolved', 'unresolved', 'innocent', '')) NOT NULL,
    foto_bukti_pelanggaran VARCHAR(255) NULL,
    foto_bukti_sanksi VARCHAR(255) NULL,
    document_sp VARCHAR(255) NULL,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id),
    FOREIGN KEY (id_pelapor) REFERENCES dosen(id),
    FOREIGN KEY (tingkatan_pelanggaran) REFERENCES jenis_pelanggaran(id),
    FOREIGN KEY (id_sanksi) REFERENCES jenis_sanksi(id)
);


ALTER TABLE pelanggaran
ALTER COLUMN foto_bukti_pelanggaran VARCHAR(255) NULL;

ALTER TABLE pelanggaran
ALTER COLUMN foto_bukti_sanksi VARCHAR(255) NULL;

ALTER TABLE pelanggaran
ALTER COLUMN document_sp VARCHAR(255) NULL;

-- Tabel untuk Aju Banding
CREATE TABLE ajubanding (
    id INT IDENTITY(1,1) PRIMARY KEY,
    id_pelanggaran INT NOT NULL,  -- Referensi ke tabel pelanggaran
    keterangan TEXT NOT NULL,    -- Deskripsi dari pengajuan banding
    tanggal_pengajuan DATE DEFAULT GETDATE(), -- Tanggal pengajuan otomatis menggunakan tanggal saat ini
    status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'accepted', 'rejected')), -- Status banding
    FOREIGN KEY (id_pelanggaran) REFERENCES pelanggaran(id) ON DELETE CASCADE
);

DELETE FROM dosen;
DELETE FROM kelas;
DELETE FROM mahasiswa;
DELETE FROM akun;
DELETE FROM jenis_pelanggaran;
DELETE FROM jenis_sanksi;
DELETE FROM pelanggaran;
DELETE FROM ajubanding;
-- mereset id agar memulai dari 1 lagi
DBCC CHECKIDENT (dosen, RESEED, 0);
DBCC CHECKIDENT (kelas, RESEED, 0);
DBCC CHECKIDENT (mahasiswa, RESEED, 0);
DBCC CHECKIDENT (akun, RESEED, 0);
DBCC CHECKIDENT (jenis_pelanggaran, RESEED, 0);
DBCC CHECKIDENT (jenis_sanksi, RESEED, 0);
DBCC CHECKIDENT (pelanggaran, RESEED, 0);
DBCC CHECKIDENT (ajubanding, RESEED, 0);




-- Data Dummy untuk Tabel dosen
INSERT INTO dosen (nidn, nama, email) VALUES
('M001', 'Ari Widodo', 'ari.widodo@student.polinema.ac.id'),
('M002', 'Budi Santoso', 'budi.santoso@student.polinema.ac.id'),
('M003', 'Citra Ananda', 'citra.ananda@student.polinema.ac.id'),
('M004', 'Dimas Prasetyo', 'dimas.prasetyo@student.polinema.ac.id'),
('M005', 'Eka Purnama', 'eka.purnama@student.polinema.ac.id'),
('M006', 'Fadilah Suryani', 'fadilah.suryani@student.polinema.ac.id'),
('M007', 'Galih Rahman', 'galih.rahman@student.polinema.ac.id'),
('M008', 'Hendra Kusuma', 'hendra.kusuma@student.polinema.ac.id'),
('M009', 'Intan Permata', 'intan.permata@student.polinema.ac.id'),
('M010', 'Jaka Prasetya', 'jaka.prasetya@student.polinema.ac.id'),
('M011', 'Kirana Maulani', 'kirana.maulani@student.polinema.ac.id'),
('M012', 'Lukman Hakim', 'lukman.hakim@student.polinema.ac.id'),
('M013', 'Muhammad Rizki', 'muhammad.rizki@student.polinema.ac.id'),
('M014', 'Nining Widodo', 'nining.widodo@student.polinema.ac.id'),
('M015', 'Omar Budiarto', 'omar.budiarto@student.polinema.ac.id'),
('M016', 'Putri Wulandari', 'putri.wulandari@student.polinema.ac.id'),
('M017', 'Qori Lestari', 'qori.lestari@student.polinema.ac.id'),
('M018', 'Rudi Hartono', 'rudi.hartono@student.polinema.ac.id'),
('M019', 'Santi Puspita', 'santi.puspita@student.polinema.ac.id'),
('M020', 'Tari Anjani', 'tari.anjani@student.polinema.ac.id'),
('M021', 'Umi Kalsum', 'umi.kalsum@student.polinema.ac.id'),
('M022', 'Vita Anjani', 'vita.anjani@student.polinema.ac.id'),
('M023', 'Wawan Junaidi', 'wawan.junaidi@student.polinema.ac.id'),
('M024', 'Yani Astuti', 'yani.astuti@student.polinema.ac.id'),
('M025', 'Zulfikar Rahman', 'zulfikar.rahman@student.polinema.ac.id'),
('M026', 'Arif Rahman', 'arif.rahman@student.polinema.ac.id'),
('M027', 'Bina Puspita', 'bina.puspita@student.polinema.ac.id'),
('M028', 'Citra Permata', 'citra.permata@student.polinema.ac.id'),
('M029', 'Dwi Anwar', 'dwi.anwar@student.polinema.ac.id'),
('M030', 'Eka Lestari', 'eka.lestari@student.polinema.ac.id'),
('M031', 'Fadilah Dewi', 'fadilah.dewi@student.polinema.ac.id'),
('M032', 'Galih Wicaksono', 'galih.wicaksono@student.polinema.ac.id'),
('M033', 'Hendri Anwar', 'hendri.anwar@student.polinema.ac.id'),
('M034', 'Intan Lestari', 'intan.lestari@student.polinema.ac.id'),
('M035', 'Jamilah Safitri', 'jamilah.safitri@student.polinema.ac.id'),
('M036', 'Kirana Ayu', 'kirana.ayu@student.polinema.ac.id'),
('M037', 'Lukman Setiawan', 'lukman.setiawan@student.polinema.ac.id'),
('M038', 'Muhammad Fadilah', 'muhammad.fadilah@student.polinema.ac.id'),
('M039', 'Nining Lestari', 'nining.lestari@student.polinema.ac.id'),
('M040', 'Omar Wibowo', 'omar.wibowo@student.polinema.ac.id'),
('M041', 'Putri Safitri', 'putri.safitri@student.polinema.ac.id'),
('M042', 'Qori Anggraini', 'qori.anggraini@student.polinema.ac.id'),
('M043', 'Rudi Rahardjo', 'rudi.rahardjo@student.polinema.ac.id'),
('M044', 'Santi Anjani', 'santi.anjani@student.polinema.ac.id'),
('M045', 'Tari Puspita', 'tari.puspita@student.polinema.ac.id'),
('M046', 'Umi Setiawati', 'umi.setiawati@student.polinema.ac.id'),
('M047', 'Vita Wulandari', 'vita.wulandari@student.polinema.ac.id'),
('M048', 'Wawan Lestari', 'wawan.lestari@student.polinema.ac.id'),
('M049', 'Yani Safitri', 'yani.safitri@student.polinema.ac.id'),
('M050', 'Zulfikar Wibowo', 'zulfikar.wibowo@student.polinema.ac.id'),
('D051', 'Muhammad Afif Hendrawan, S.Kom.', 'muhammad.afif.hendrawan@polinema.ac.id'),
('D052', 'Muhammad Shulhan Khairy, SKom., MKom.', 'muhammad.shulhan.khairy@polinema.ac.id'),
('D053', 'Muhammad Unggul Pamenang, SSt., MT.', 'muhammad.unggul.pamenang@polinema.ac.id'),
('D054', 'Mungki Astiningrum, ST., MKom.', 'mungki.astiningrum@polinema.ac.id'),
('D055', 'Mustika Mentari, SKom., MKom.', 'mustika.mentari@polinema.ac.id'),
('D056', 'Noprianto', 'noprianto@polinema.ac.id'),
('D057', 'Odhitya Desta Triswidrananta, SPd., MPd.', 'odhitya.desta.triswidrananta@polinema.ac.id'),
('D058', 'Pramana Yoga Saputra, SKom., MMT.', 'pramana.yoga.saputra@polinema.ac.id'),
('D059', 'Putra Prima A., ST., MKom.', 'putra.prima.a@polinema.ac.id'),
('D060', 'Rakhmat Arianto SST., MKom.', 'rakhmat.arianto@polinema.ac.id'),
('D061', 'Rawansyah., Drs., MPd.', 'rawansyah@polinema.ac.id'),
('D062', 'Retno Damayanti, SPd.', 'retno.damayanti@polinema.ac.id'),
('D063', 'Ridwan Rismanto, SST., MKom.', 'ridwan.rismanto@polinema.ac.id'),
('D064', 'Rizki Putri Ramadhani, S.S., M.Pd.', 'rizki.putri.ramadhani@polinema.ac.id'),
('D065', 'Rizky Ardiansyah, SKom., MT.', 'rizky.ardiansyah@polinema.ac.id'),
('D066', 'Robby Anggriawan SE., ME.', 'robby.anggriawan@polinema.ac.id'),
('D067', 'Rokhimatul Wakhidah SPd. MT.', 'rokhimatul.wakhidah@polinema.ac.id'),
('D068', 'Rosa Andrie Asmara, ST., MT., Dr. Eng.', 'rosa.andrie.asmara@polinema.ac.id'),
('D069', 'Rudy Ariyanto, ST., MCs.', 'rudy.ariyanto@polinema.ac.id'),
('D070', 'Satrio Binusa S, SS, M.Pd', 'satrio.binusa@polinema.ac.id'),
('D071', 'Septian Enggar Sukmana, SPd., MT.', 'septian.enggar.sukmana@polinema.ac.id'),
('D072', 'Shohib Muslim', 'shohib.muslim@polinema.ac.id'),
('D073', 'Siti Romlah, Dra., M.M.', 'siti.romlah@polinema.ac.id'),
('D074', 'Sofyan Noor Arief, S.ST., M.Kom.', 'sofyan.noor.arief@polinema.ac.id'),
('D075', 'Ulla Delfiana Rosiani, ST., MT.', 'ulla.delfiana.rosiani@polinema.ac.id'),
('D076', 'Usman Nurhasan, S.Kom., MT.', 'usman.nurhasan@polinema.ac.id'),
('D077', 'Very Sugiarto, SPd., MKom.', 'very.sugiarto@polinema.ac.id'),
('D078', 'Vipkas Al Hadid Firdaus, ST.,MT.', 'vipkas.al.hadid.firdaus@polinema.ac.id'),
('D079', 'Vivi Nur Wijayaningrum, S.Kom, M.Kom', 'vivi.nur.wijayaningrum@polinema.ac.id'),
('D080', 'Vivin Ayu Lestari, SPd.', 'vivin.ayu.lestari@polinema.ac.id'),
('D081', 'Widaningsih Condrowardhani, SH., MH.', 'widaningsih.condrowardhani@polinema.ac.id'),
('D082', 'Wilda Imama Sabilla, S.Kom., M.Kom.', 'wilda.imama.sabilla@polinema.ac.id'),
('D083', 'Yoppy Yunhasnawa, SST., MSc.', 'yoppy.yunhasnawa@polinema.ac.id'),
('D084', 'Yuri Ariyanto, SKom., MKom.', 'yuri.ariyanto@polinema.ac.id'),
('D085', 'Zulmy Faqihuddin Putera, S.Pd., M.Pd', 'zulmy.faqihuddin.putera@polinema.ac.id');


-- Data Dummy untuk Tabel kelas
INSERT INTO kelas (nama_kelas, prodi, angkatan, id_dpa) VALUES
('TI-2A', 'Teknik Informatika', 2024, 1),
('TI-2B', 'Teknik Informatika', 2024, 2),
('TI-2C', 'Teknik Informatika', 2024, 3),
('TI-2D', 'Teknik Informatika', 2024, 4),
('TI-2E', 'Teknik Informatika', 2024, 5),
('TI-2F', 'Teknik Informatika', 2024, 6),
('TI-2G', 'Teknik Informatika', 2024, 7),
('TI-2H', 'Teknik Informatika', 2024, 8),
('TI-2I', 'Teknik Informatika', 2024, 9),
('SIB-2A', 'Sistem Informasi Bisnis', 2024, 10),
('SIB-2B', 'Sistem Informasi Bisnis', 2024, 11),
('SIB-2C', 'Sistem Informasi Bisnis', 2024, 12),
('SIB-2D', 'Sistem Informasi Bisnis', 2024, 13),
('SIB-2E', 'Sistem Informasi Bisnis', 2024, 14),
('SIB-2F', 'Sistem Informasi Bisnis', 2024, 15),
('SIB-2G', 'Sistem Informasi Bisnis', 2024, 16);


-- Data Dummy untuk Tabel mahasiswa
INSERT INTO mahasiswa (nim, nama, ttl, email, id_kelas) VALUES
('2341760182', 'Abhinaya NuzuluZzuHDi', '2005-01-01', 'abhinaya@polinema.ac.id', 14),
('2341760191', 'Alvi ChoirinNikmah', '2004-02-05', 'alvi@polinema.ac.id', 14),
('2341760119', 'Alya Ajeng Ayu', '2005-03-10', 'alya@polinema.ac.id', 14),
('2341760124', 'Ardhe Lia Putri Maharani', '2004-04-15', 'ardhe@polinema.ac.id', 14),
('2341760003', 'Bagas Nusa Tama', '2004-05-20', 'bagas@polinema.ac.id', 14),
('2341760162', 'BobY RozaK Saputra', '2004-01-22', 'boby@polinema.ac.id', 14),
('2341760187', 'Deanissa Sherly Sabilla', '2005-03-28', 'deanissa@polinema.ac.id', 14),
('2341760107', 'Eka Putri Natalya Kabelen', '2004-06-12', 'eka@polinema.ac.id', 14),
('2341760098', 'Firman Dzaki Rahman', '2004-07-08', 'firman@polinema.ac.id', 14),
('2341760101', 'Fransiska Widya Krisanti', '2004-08-03', 'fransiska@polinema.ac.id', 14),
('2341760057', 'Hudha Aji Saputra', '2005-09-19', 'hudha@polinema.ac.id', 14),
('2341760026', 'Indi Warda Ramadhani', '2005-12-30', 'indi@polinema.ac.id', 14),
('2341760036', 'Ismi Atika', '2004-11-15', 'ismi@polinema.ac.id', 14),
('2341760086', 'Isnaeny Tri Larassati', '2005-05-25', 'isnaeny@polinema.ac.id', 14),
('2341760010', 'Izzatir Rofiah', '2004-01-01', 'izzatir@polinema.ac.id', 14),
('2341760048', 'Khoir Karol Nurzuraidah', '2004-02-18', 'khoir@polinema.ac.id', 14),
('2341760030', 'M. Zidna Billah Faza', '2005-07-21', 'm.zidna@polinema.ac.id', 14),
('2241760014', 'Mikhael Tarigan', '2004-08-14', 'mikhael@polinema.ac.id', 14),
('2341760138', 'Moch Haikal Putra Muhajir', '2005-03-23', 'mochhaikal@polinema.ac.id', 14),
('2341760196', 'Muhammad Kemal Syahru Ramadhan', '2004-04-15', 'muhammad@polinema.ac.id', 14),
('2341760076', 'Muhammad Satria Rahmad David', '2004-06-12', 'muhammad@polinema.ac.id', 14),
('2341760085', 'Naafi'' Ridho Athallah', '2005-11-10', 'naafi@polinema.ac.id', 14),
('2341760179', 'Nadya Hapsari Putri', '2004-02-23', 'nadya@polinema.ac.id', 14),
('2341760125', 'Ramadhan Maulana Arrachman', '2005-07-30', 'ramadhan@polinema.ac.id', 14),
('2341760056', 'Revani Nanda Putri', '2004-04-11', 'revani@polinema.ac.id', 14),
('2341760052', 'Septian Tito Hidayahtullah', '2004-12-15', 'septian@polinema.ac.id', 14),
('2341760100', 'Sharlyf Shaquille Syani', '2005-08-17', 'sharlyf@polinema.ac.id', 14),
('2341760019', 'Siti Alifia Azzahra Mustofa', '2004-10-19', 'siti@polinema.ac.id', 14),
('2341760095', 'Susilowati Syafa Adilah', '2004-11-22', 'susilowati@polinema.ac.id', 14);


-- Data Dummy untuk Tabel akun
-- Membuat akun untuk admin
INSERT INTO akun (username, password, role, id_mahasiswa, id_dosen) VALUES
('admin1', 'admin123', 'admin', NULL, NULL),
('admin3', 'admin123', 'admin', NULL, NULL),
('admin2', 'admin123', 'admin', NULL, NULL);
-- Membuat akun untuk semua dosen
INSERT INTO akun (username, password, role, id_mahasiswa, id_dosen)
SELECT 
    CONCAT('dosen', id), -- Membuat username dosen seperti "dosen1", "dosen2", dll
    'dosen123', 
    'dosen',
    NULL, 
    id
FROM dosen;

-- Membuat akun untuk semua mahasiswa
INSERT INTO akun (username, password, role, id_mahasiswa, id_dosen)
SELECT 
    CONCAT('mahasiswa', id), -- Membuat username mahasiswa seperti "mahasiswa1", "mahasiswa2", dll
    'mahasiswa123', 
    'mahasiswa',
    id, 
    NULL
FROM mahasiswa;



-- Data Dummy untuk Tabel jenis_pelanggaran
INSERT INTO jenis_pelanggaran (keterangan, tingkatan) VALUES
('Berkomunikasi dengan tidak sopan, baik tertulis atau tidak tertulis kepada mahasiswa, dosen, karyawan, atau orang lain', 'V'),
('Berbusana tidak sopan dan tidak rapi. Yaitu antara lain adalah: berpakaian ketat, transparan, memakai t-shirt (baju kaos tidak berkerah), tank top, hipster, you can see, rok mini, backless, atau baju koyak, sandal, sepatu sandal di lingkungan kampus', 'IV'),
('Mahasiswa laki-laki berambut tidak rapi, gondrong yaitu panjang rambutnya melewati batas alis mata di bagian depan, telinga di bagian samping atau menyentuh kerah baju di bagian leher', 'IV'),
('Mahasiswa berambut dengan model punk, dicat selain hitam dan/atau skinned.', 'IV'),
('Makan, atau minum di dalam ruang kuliah/ laboratorium/ bengkel.', 'IV'),
('Melanggar peraturan/ ketentuan yang berlaku di Polinema baik di Jurusan/ Program Studi', 'III'),
('Tidak menjaga kebersihan di seluruh area Polinema', 'III'),
('Membuat kegaduhan yang mengganggu pelaksanaan perkuliahan atau praktikum yang sedang berlangsung.', 'III'),
('Merokok di luar area kawasan merokok', 'III'),
('Bermain kartu, game online di area kampus', 'III'),
('Mengotori atau mencoret-coret meja, kursi, tembok, dan lain-lain di lingkungan Polinema', 'III'),
('Bertingkah laku kasar atau tidak sopan kepada mahasiswa, dosen, dan/atau karyawan.', 'III'),
('Merusak sarana dan prasarana yang ada di area Polinema', 'II'),
('Tidak menjaga ketertiban dan keamanan di seluruh area Polinema (misalnya: parkir tidak pada tempatnya, konvoi selebrasi wisuda dll)', 'II'),
('Melakukan pengotoran/ pengrusakan barang milik orang lain termasuk milik Politeknik Negeri Malang', 'II'),
('Mengakses materi pornografi di kelas atau area kampus', 'II'),
('Membawa dan/atau menggunakan senjata tajam dan/atau senjata api untuk hal kriminal', 'II'),
('Melakukan perkelahian, serta membentuk geng/ kelompok yang bertujuan negatif.', 'II'),
('Melakukan kegiatan politik praktis di dalam kampus', 'II'),
('Melakukan tindakan kekerasan atau perkelahian di dalam kampus', 'II'),
('Melakukan penyalahgunaan identitas untuk perbuatan negatif', 'II'),
('Mengancam, baik tertulis atau tidak tertulis kepada mahasiswa, dosen, dan/atau karyawan.', 'II'),
('Mencuri dalam bentuk apapun', 'II'),
('Melakukan kecurangan dalam bidang akademik, administratif, dan keuangan.', 'II'),
('Melakukan pemerasan dan/atau penipuan', 'II'),
('Melakukan pelecehan dan/atau tindakan asusila dalam segala bentuk di dalam dan di luar kampus', 'II'),
('Berjudi, mengkonsumsi minum-minuman keras, dan/ atau bermabuk-mabukan di lingkungan dan di luar lingkungan Kampus POLINEMA', 'II'),
('Mengikuti organisasi dan atau menyebarkan faham-faham yang dilarang oleh Pemerintah.', 'II'),
('Melakukan pemalsuan data / dokumen / tanda tangan.', 'II'),
('Melakukan plagiasi (copy paste) dalam tugas-tugas atau karya ilmiah', 'II'),
('Tidak menjaga nama baik Polinema di masyarakat dan/ atau mencemarkan nama baik Polinema melalui media apapun', 'I'),
('Melakukan kegiatan atau sejenisnya yang dapat menurunkan kehormatan atau martabat Negara, Bangsa dan POLINEMA.', 'I'),
('Menggunakan barang-barang psikotropika dan/ atau zat-zat Adiktif lainnya', 'I'),
('Mengedarkan serta menjual barang-barang psikotropika dan/atau zat-zat Adiktif lainnya', 'I'),
('Terlibat dalam tindakan kriminal dan dinyatakan bersalah oleh Pengadilan', 'I');

-- Data Dummy untuk Tabel jenis_sanksi
INSERT INTO jenis_sanksi (keterangan, tingkatan) VALUES
('Penonaktifan (cuti akademik/terminal) selama dua semester atau pemberhentian sebagai mahasiswa.', 'I'),
('Penggantian kerugian, tugas layanan sosial, atau pemberian nilai D pada mata kuliah terkait.', 'II'),
('Mahasiswa wajib membuat surat pernyataan tertulis yang dibubuhi materai dan tanda tangan yang menyatakan tidak akan mengulangi perbuatan tersebut, serta melaksanakan tugas khusus yang ditugaskan, misalnya memperbaiki atau membersihkan fasilitas kampus.', 'III'),
('Teguran tertulis disertai dengan surat pernyataan tidak mengulangi perbuatan tersebut, dibubuhi materai, ditandatangani mahasiswa yang bersangkutan dan DPA', 'IV'),
('Teguran lisan disertai dengan surat pernyataan tidak mengulangi perbuatan tersebut, dibubuhi materai, ditandatangani mahasiswa yang bersangkutan dan DPA', 'V');

-- Data Dummy untuk Tabel pelanggaran
INSERT INTO pelanggaran (
    keterangan, 
    tanggal, 
    id_mahasiswa, 
    id_pelapor, 
    tingkatan_pelanggaran, 
    id_sanksi, 
    status, 
    foto_bukti_pelanggaran, 
    foto_bukti_sanksi, 
    document_sp
) VALUES
('Merokok di ruang kelas', '2024-12-09', 10, 55, 9, 3, 'Resolved', 'bukti_pelanggaran_101.jpg', 'bukti_sanksi_101.jpg', 'dokumen_sp_101.pdf'),
('Mengkonsumsi Alkohol di lingkungan kampus', '2024-12-08', 2, 1, 27, 2, 'Unresolved', NULL, NULL, NULL),
('Kecurangan saat ujian', '2024-12-07', 7, 20, 24, 2, 'Unresolved', NULL, NULL, NULL),
('Pembuangan sampah sembarangan di area kampus', '2024-12-06', 18, 25, 7, 3, 'Innocent', NULL, NULL, NULL),
('Menggunakan kaos saat perkuliahan', '2024-12-05', 6, 35, 2, 4, 'Resolved', 'bukti_pelanggaran_105.jpg', 'bukti_sanksi_105.jpg', 'dokumen_sp_105.pdf'),
('Melanggar ketertiban di kelas', '2024-12-04', 3, 15, 8, 3, 'Unresolved', NULL, NULL, NULL),
('Rambut sampai pundak', '2024-12-03', 9, 30, 3, 4, 'Resolved', 'bukti_pelanggaran_107.jpg', 'bukti_sanksi_107.jpg', 'dokumen_sp_107.pdf'),
('Berkata Kasar ke dosen', '2024-12-02', 12, 5, 1, 5, 'Unresolved', NULL, NULL, NULL),
('Bermain game saat perkuliahan', '2024-12-01', 4, 20, 10, 3, 'Innocent', NULL, NULL, NULL),
('Berkelahi di lingkungan kampus', '2024-11-30', 10, 22, 18, 2, 'Unresolved', NULL, NULL, NULL);



-- Data Dummy untuk Tabel Aju Banding
INSERT INTO ajubanding (id_pelanggaran, keterangan) VALUES
(2, 'Banding karena sanksi dinilai terlalu berat'),
(3, 'Tidak merasa melakukan pelanggaran yang dituduhkan'),
(6, 'Ada kesalahpahaman dalam pelaporan pelanggaran'),
(8, 'Banding karena terdapat bukti baru yang mendukung'),
(10, 'Banding atas dasar kelonggaran jadwal');



