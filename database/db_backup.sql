CREATE TABLE peserta_didik (
  id                     INT            AUTO_INCREMENT PRIMARY KEY,
  ppdb_id                INT            NOT NULL,
  jurusan_id             INT            NOT NULL,
  
  nama_lengkap           VARCHAR(255)   NOT NULL,
  nisn                   VARCHAR(50),
  nik                    VARCHAR(50),
  tempat_lahir           VARCHAR(100),
  tanggal_lahir          DATE,
  jenis_kelamin          VARCHAR(20),
  agama                  VARCHAR(50),

  alamat_lengkap         TEXT,
  provinsi               VARCHAR(100),
  kabupaten              VARCHAR(100),
  kecamatan              VARCHAR(100),
  kelurahan              VARCHAR(100),
  kode_pos               VARCHAR(20),

  hobi                   VARCHAR(255),
  cita_cita              VARCHAR(255),

  no_hp                  VARCHAR(50),
  email                  VARCHAR(255),

  anak_ke                INT,
  jumlah_saudara_kandung INT,
  jumlah_saudara_tiri    INT,
  jumlah_saudara_angkat  INT,

  status_tempat_tinggal  VARCHAR(100),
  jarak_rumah_km         DECIMAL(10,2),
  alat_transportasi      VARCHAR(100),
  waktu_tempuh_menit     INT,

  info_sekolah_dari      VARCHAR(255),
  asal_sekolah           VARCHAR(255),
  alamat_asal_sekolah    TEXT,
  rencana_setelah_lulus  VARCHAR(255),

  prestasi               TEXT,
  pelajaran_favorit      VARCHAR(255),

  password               VARCHAR(255),
  file_ktp               VARCHAR(255),
  file_ijazah            VARCHAR(255),

  nama_ayah              VARCHAR(255),
  status_ayah            VARCHAR(50),
  ttl_ayah               DATE,
  no_ktp_ayah            VARCHAR(50),
  pendidikan_ayah        VARCHAR(100),
  alamat_ayah            TEXT,
  profesi_ayah           VARCHAR(100),
  pendapatan_ayah        DECIMAL(15,2),
  no_hp_ayah             VARCHAR(50),
  email_ayah             VARCHAR(255),

  nama_ibu               VARCHAR(255),
  status_ibu             VARCHAR(50),
  ttl_ibu                DATE,
  no_ktp_ibu             VARCHAR(50),
  pendidikan_ibu         VARCHAR(100),
  alamat_ibu             TEXT,
  profesi_ibu            VARCHAR(100),
  pendapatan_ibu         DECIMAL(15,2),
  no_hp_ibu              VARCHAR(50),
  email_ibu              VARCHAR(255),

  nama_wali              VARCHAR(255),
  status_wali            VARCHAR(50),
  ttl_wali               DATE,
  no_ktp_wali            VARCHAR(50),
  pendidikan_wali        VARCHAR(100),
  alamat_wali            TEXT,
  profesi_wali           VARCHAR(100),
  pendapatan_wali        DECIMAL(15,2),
  no_hp_wali             VARCHAR(50),
  email_wali             VARCHAR(255),

  created_at             TIMESTAMP      NULL DEFAULT NULL,
  updated_at             TIMESTAMP      NULL DEFAULT NULL,

  CONSTRAINT fk_ppdb FOREIGN KEY (ppdb_id) REFERENCES ppdb(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




CREATE TABLE jurusan (
  id           INT            AUTO_INCREMENT PRIMARY KEY,
  nama         VARCHAR(255)   NOT NULL UNIQUE,
  keterangan   TEXT,
  created_at   TIMESTAMP      NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP      NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- Tabel Nilai Tahap Seleksi
CREATE TABLE nilai_tahap_seleksi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    peserta_id BIGINT UNSIGNED,
    tahap_seleksi_id BIGINT UNSIGNED,
    nilai DECIMAL(5,2), -- bisa juga INT kalau tanpa koma
    status_lulus ENUM('LULUS', 'TIDAK LULUS') DEFAULT 'TIDAK LULUS',
    keterangan TEXT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (peserta_id) REFERENCES peserta(id) ON DELETE CASCADE,
    FOREIGN KEY (tahap_seleksi_id) REFERENCES tahap_seleksi_ppdb(id) ON DELETE CASCADE
);