CREATE TABLE public.auth_groups
(
  id integer NOT NULL DEFAULT nextval('auth_groups_id_seq'::regclass),
  name character varying(40),
  description character varying(255),
  meta_data text,
  benh_vien_id integer,
  CONSTRAINT auth_groups_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.auth_groups_has_roles
(
  group_id integer NOT NULL DEFAULT nextval('auth_groups_has_roles_group_id_seq'::regclass),
  role_id integer NOT NULL DEFAULT nextval('auth_groups_has_roles_role_id_seq'::regclass)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.auth_permissions
(
  id integer NOT NULL DEFAULT nextval('auth_permissions_id_seq'::regclass),
  name character varying(100),
  description character varying(255),
  authorized_uri character varying(255)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.auth_roles
(
  id integer NOT NULL DEFAULT nextval('auth_roles_id_seq'::regclass),
  name character varying(100),
  description character varying(255)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.auth_roles_has_permissions
(
  role_id integer NOT NULL DEFAULT nextval('auth_roles_has_permissions_role_id_seq'::regclass),
  permission_id integer NOT NULL DEFAULT nextval('auth_roles_has_permissions_permission_id_seq'::regclass)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.auth_users
(
  id integer NOT NULL DEFAULT nextval('auth_users_id_seq'::regclass),
  name character varying,
  email character varying(191) NOT NULL,
  password character varying(191) NOT NULL,
  remember_token character varying(100),
  created_at timestamp without time zone,
  updated_at timestamp without time zone,
  fullname text,
  userstatus integer,
  khoa text,
  chuc_vu text
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.auth_users_groups
(
  group_id integer NOT NULL DEFAULT nextval('auth_users_groups_group_id_seq'::regclass),
  user_id integer NOT NULL DEFAULT nextval('auth_users_groups_user_id_seq'::regclass),
  khoa_id integer,
  phong_id text,
  benh_vien_id integer
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.benh_nhan
(
  id integer NOT NULL DEFAULT nextval('patient_patientid_seq'::regclass),
  ho_va_ten text,
  ngay_sinh timestamp without time zone,
  nam_sinh integer,
  gioi_tinh text,
  nghe_nghiep_id text,
  dan_toc_id text,
  quoc_tich_id text,
  so_nha text,
  duong_thon text,
  phuong_xa_id text,
  quan_huyen_id text,
  tinh_thanh_pho_id text,
  noi_lam_viec text,
  loai_nguoi_than text,
  ten_nguoi_than text,
  dien_thoai_nguoi_than text,
  url_hinh_anh text,
  dien_thoai_benh_nhan text,
  email_benh_nhan text,
  dia_chi_lien_he text,
  gioi_tinh_id integer,
  nguoi_than text,
  CONSTRAINT patient_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.benh_vien
(
  id integer NOT NULL DEFAULT nextval('benh_vien_id_seq'::regclass),
  ma integer,
  ten text,
  dia_chi text,
  thiet_lap text,
  CONSTRAINT benh_vien_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.bhyt
(
  id integer DEFAULT nextval('bhyt_seq'::regclass),
  benh_nhan_id integer,
  hsba_id integer,
  hsba_khoa_phong_id integer,
  ms_bhyt text,
  ma_cskcbbd text,
  thoi_gian_tao timestamp without time zone DEFAULT (now())::timestamp without time zone,
  tu_ngay timestamp without time zone,
  den_ngay timestamp without time zone,
  image_url bytea,
  ma_noi_song text,
  du5nam6thangluongcoban integer,
  dtcbh_luyke6thang integer,
  tuyen_bhyt integer
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.chi_tiet_phieu_kho
(
  id integer NOT NULL DEFAULT nextval('chi_tiet_phieu_kho_id_seq'::regclass),
  phieu_kho_id integer,
  danh_muc_thuoc_vat_tu_id integer,
  chi_dinh_id integer,
  the_kho_id integer,
  so_luong_yeu_cau integer,
  so_luong_nhap integer,
  vat_gia_nhap double precision,
  gia_nhap double precision,
  trang_thai integer,
  CONSTRAINT chi_tiet_phieu_kho_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.chuyen_vien
(
  id integer NOT NULL DEFAULT nextval('chuyen_vien_chuyenvienid_seq'::regclass),
  hsba_khoa_phong_id integer DEFAULT 0,
  benh_nhan_id integer DEFAULT 0,
  so_chuyen_vien integer DEFAULT 0,
  khoa_id integer DEFAULT 0,
  noi_chuyen_vien_id integer DEFAULT 0,
  loai_chuyen_vien_id integer DEFAULT 0,
  thoi_gian_chuyen_vien timestamp without time zone,
  ma_chuyen_vien text,
  ma_benh_vien_chuyen_toi text,
  tinh_trang_nguoi_benh text,
  ly_do_chuyen_vien text,
  phuong_tien_van_chuyen text,
  nguoi_van_chuyen text,
  dau_hieu_lam_sang text,
  thuoc text,
  xet_nghiem text,
  hinh_thuc_chuyen_vien_id integer DEFAULT 0,
  huong_dieu_tri text,
  chan_doan text,
  ly_do_chuyen_vien_id integer DEFAULT 0,
  chan_doan_tuyen_duoi_code text,
  chan_doan_tuyen_duoi_text text,
  tuyen_id integer,
  hsba_don_vi_id integer
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.danh_muc_benh_vien
(
  id integer NOT NULL DEFAULT nextval('benhvien_benhvienid_seq'::regclass),
  ma_kcbbd text,
  ma text,
  ten text,
  dia_chi text,
  hang text,
  loai text,
  tuyen text,
  ghi_chu text,
  ma_tinh text,
  ma_huyen text,
  ma_xa text,
  CONSTRAINT benhvien_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.danh_muc_dich_vu
(
  id integer NOT NULL DEFAULT nextval('danh_muc_dich_vu_id_seq'::regclass),
  ten_nhom text,
  loai_nhom integer,
  ma text,
  ma_nhom_bhyt text,
  ten text,
  ten_bhyt text,
  ten_nuoc_ngoai text,
  don_vi_tinh text,
  gia double precision,
  gia_bhyt double precision,
  gia_nuoc_ngoai double precision,
  trang_thai integer,
  nguoi_cap_nhat_id integer,
  thoi_gian_cap_nhat timestamp without time zone,
  ngoai_gio integer,
  phong_thuc_hien text,
  CONSTRAINT danh_muc_dich_vu_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.danh_muc_ket_qua_y_lenh
(
  id integer NOT NULL DEFAULT nextval('service_ref_servicerefid_seq'::regclass),
  ma_nhom text,
  loai integer,
  loai_nhom integer,
  ma text,
  ten text,
  max text,
  min text,
  don_vi_tinh text,
  CONSTRAINT service_ref_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.danh_muc_thuoc_vat_tu
(
  id integer,
  nhom_danh_muc_id integer,
  ten character varying(500),
  ten_bhyt character varying(500),
  ten_nuoc_ngoai character varying(500),
  ma character varying(50),
  ma_bhyt character varying(50),
  don_vi_tinh_id integer,
  so_luong integer,
  nhan_vien_tao integer,
  nhan_vien_cap_nhat integer,
  thoi_gian_tao timestamp without time zone,
  thoi_gian_cap_nhat timestamp without time zone,
  hoat_chat_id integer,
  biet_duoc_id integer,
  nong_do character varying(100),
  duong_dung character varying(100),
  dong_goi character varying(500),
  hang_san_xuat character varying(500),
  nuoc_san_xuat character varying(50),
  trang_thai integer,
  loai_nhom integer,
  gia double precision,
  gia_bhyt double precision,
  gia_nuoc_ngoai double precision
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.danh_muc_tong_hop
(
  id integer NOT NULL DEFAULT nextval('danh_muc_tong_hop_id_seq'::regclass),
  khoa character varying(250),
  gia_tri character varying(250),
  dien_giai character varying(250),
  parent_id integer,
  CONSTRAINT danh_muc_tong_hop_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.danh_muc_trang_thai
(
  id integer NOT NULL DEFAULT nextval('danh_muc_trang_thai_id_seq'::regclass),
  khoa character varying(250),
  gia_tri character varying(250),
  dien_giai character varying(250),
  CONSTRAINT danh_muc_trang_thai_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.dieu_tri
(
  id bigint NOT NULL DEFAULT nextval('dieu_tri_id_seq'::regclass),
  hsba_khoa_phong_id integer,
  hsba_id integer NOT NULL,
  khoa_id integer NOT NULL,
  phong_id integer NOT NULL,
  phong_benh character varying(20),
  auth_users_id integer,
  benh_nhan_id integer NOT NULL,
  ten_benh_nhan character varying(250),
  nam_sinh integer,
  gioi_tinh_id integer,
  thoi_gian_chi_dinh timestamp without time zone DEFAULT (now())::timestamp without time zone,
  dien_bien_benh text,
  che_do_an text,
  che_do_cham_soc text,
  nuoc_nhap text,
  nuoc_xuat text,
  mach text,
  nhiet_do text,
  nhip_tho text,
  sp_o2 text,
  can_nang text,
  chieu_cao text,
  kham_toan_than text,
  kham_bo_phan text,
  ket_qua_can_lam_san text,
  huong_xu_ly text,
  thi_luc_mat_trai text,
  thi_luc_mat_phai text,
  kl_thi_luc_mat_trai text,
  kl_thi_luc_mat_phai text,
  nhan_ap_mat_trai text,
  nhan_ap_mat_phai text,
  huyet_ap_thap text,
  huyet_ap_cao text,
  hsba_don_vi_id integer,
  giuong_benh character varying(20),
  cdbd_icd10_code character varying(10),
  cdbd_icd10_text character varying(250),
  CONSTRAINT dieu_tri_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.don_vi_tinh
(
  id integer NOT NULL DEFAULT nextval('don_vi_tinh_id_seq'::regclass),
  ten character varying(50),
  he_so_le_1 double precision,
  he_so_le_2 double precision,
  CONSTRAINT don_vi_tinh_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.gioi_han
(
  id integer,
  kho_id integer,
  danh_muc_thuoc_vat_tu_id integer,
  ton_toi_thieu integer,
  han_su_dung_toi_thieu timestamp without time zone,
  co_so integer,
  sl_kha_dung double precision
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.giuong_benh
(
  id integer NOT NULL,
  phong_id integer,
  stt integer,
  tinh_trang integer,
  ten_benh_nhan character varying(255),
  benh_nhan_id integer,
  CONSTRAINT giuong_benh_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.hanh_chinh
(
  ma_tinh character varying(10),
  kyhieu_tinh character varying(10),
  ten_tinh character varying(50),
  ma_huyen character varying(10),
  ten_huyen character varying(50),
  kyhieu_huyen character varying(10),
  huyen_matinh character varying(10),
  ma_xa character varying(10),
  ten_xa character varying(50),
  kyhieu_xa character varying(10),
  xa_mahuyen character varying(10),
  xa_matinh character varying(10),
  index character varying(15)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.hoat_chat
(
  id integer,
  ten character varying(500),
  ky_hieu character varying(50),
  trang_thai integer
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.hsba
(
  id integer NOT NULL DEFAULT nextval('hosobenhan_hosobenhanid_seq'::regclass),
  so_luu_tru text DEFAULT ''::text,
  so_vao_vien text DEFAULT ''::text,
  mo_ta_so_luu_tru text,
  vi_tri_so_luu_tru text,
  nguoi_luu_so_luu_tru integer DEFAULT 0,
  loai_benh_an integer,
  auth_users_id integer,
  khoa_id integer,
  phong_id integer,
  hinh_thuc_vao_vien integer,
  ket_qua_dieu_tri integer,
  xu_tri_kham_benh integer,
  hinh_thuc_ra_vien integer,
  trang_thai_hsba integer,
  benh_nhan_id integer,
  ngay_tao timestamp without time zone DEFAULT (now())::timestamp without time zone,
  ngay_ra_vien timestamp without time zone DEFAULT '0001-01-01 00:00:00'::timestamp without time zone,
  cdrv_icd10_code text,
  cdrv_icd10_text text,
  cdrv_kem_theo_icd10_code text,
  cdrv_kem_theo_icd10_text text,
  ten_benh_nhan text,
  ngay_sinh timestamp without time zone,
  nam_sinh integer,
  nghe_nghiep_id text,
  dan_toc_id text,
  quoc_tich_id text,
  so_nha text,
  duong_thon text,
  phuong_xa_id text,
  quan_huyen_id text,
  tinh_thanh_pho_id text,
  noi_lam_viec text,
  loai_nguoi_than text,
  ten_nguoi_than text,
  dien_thoai_nguoi_than text,
  ten_nghe_nghiep text,
  ten_dan_toc text,
  ten_quoc_tich text,
  ten_phuong_xa text,
  ten_quan_huyen text,
  ten_tinh_thanh_pho text,
  url_hinh_anh text,
  ms_bhyt text,
  can_nang double precision,
  is_dang_ky_truoc text,
  ten_benh_nhan_khong_dau text,
  dien_thoai_benh_nhan text,
  email_benh_nhan text,
  benh_vien_id integer,
  dia_chi_lien_he text,
  gioi_tinh_id integer,
  thx_gplace_json text,
  nguoi_than text,
  CONSTRAINT hosobenhan_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.hsba_don_vi
(
  id integer NOT NULL DEFAULT nextval('hsba_don_vi_id_seq'::regclass),
  vien_phi_id integer,
  hsba_id integer,
  trang_thai integer,
  khoa_hien_tai integer,
  phong_hien_tai integer,
  giuong_hien_tai integer,
  loai_benh_an integer,
  auth_users_id integer,
  benh_nhan_id integer,
  doi_tuong_benh_nhan integer,
  bhyt_id integer,
  yeu_cau_kham_id integer,
  thoi_gian_vao_vien timestamp without time zone,
  hinh_thuc_vao_vien_id integer,
  thoi_gian_ra_vien timestamp without time zone,
  hinh_thuc_ra_vien integer,
  cdrv_icd10_text character varying(255),
  cdrv_icd10_code character varying(255),
  xu_tri_kham_benh integer,
  cdtd_icd10_text character varying(255),
  cdtd_icd10_code character varying(255),
  noi_gioi_thieu_id integer,
  phong_truoc_do integer,
  ket_qua_dieu_tri integer,
  phong_chuyen_toi integer,
  benh_vien_chuyen_toi integer,
  benh_vien_id integer,
  trang_thai_cls integer,
  cdvv_icd10_text character varying(255),
  cdvv_icd10_code character varying(255),
  loai_vien_phi integer,
  khoa_chuyen_den integer,
  phong_chuyen_den integer,
  CONSTRAINT hsba_don_vi_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.hsba_phong_kham
(
  id integer NOT NULL DEFAULT nextval('hsba_phong_kham_id_seq'::regclass),
  hsba_khoa_phong_id integer,
  auth_users_id integer,
  benh_nhan_id integer,
  ly_do_vao_vien text,
  qua_trinh_benh_ly text,
  tien_su_benh_ban_than text,
  tien_su_benh_gia_dinh text,
  kham_toan_than text,
  kham_bo_phan text,
  ket_qua_can_lam_san text,
  huong_xu_ly text,
  cdbd_icd10_code text,
  cdbd_icd10_text text,
  mach text,
  nhiet_do text,
  huyet_ap_thap text,
  huyet_ap_cao text,
  nhip_tho text,
  can_nang text,
  chieu_cao text,
  sp_o2 text,
  thi_luc_mat_trai text,
  thi_luc_mat_phai text,
  kl_thi_luc_mat_trai text,
  kl_thi_luc_mat_phai text,
  nhan_ap_mat_trai text,
  nhan_ap_mat_phai text,
  upload_file_hoi_benh text,
  upload_file_kham_benh text,
  ten_benh_nhan character varying(150),
  phong_id integer,
  khoa_id integer,
  hsba_id integer,
  benh_vien_id integer,
  cdvv_icd10_code text,
  cdvv_icd10_text text,
  hsba_don_vi_id integer,
  CONSTRAINT hsba_phong_kham_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);


--BEGIN
CREATE TABLE public.hsba_phong_kham_partition
(
  id integer NOT NULL DEFAULT nextval('hsba_phong_kham_partition_id_seq'::regclass),
  hsba_khoa_phong_id integer,
  auth_users_id integer,
  benh_nhan_id integer,
  ly_do_vao_vien text,
  qua_trinh_benh_ly text,
  tien_su_benh_ban_than text,
  tien_su_benh_gia_dinh text,
  kham_toan_than text,
  kham_bo_phan text,
  ket_qua_can_lam_san text,
  huong_xu_ly text,
  cdbd_icd10_code text,
  cdbd_icd10_text text,
  mach text,
  nhiet_do text,
  huyet_ap_thap text,
  huyet_ap_cao text,
  nhip_tho text,
  can_nang text,
  chieu_cao text,
  sp_o2 text,
  thi_luc_mat_trai text,
  thi_luc_mat_phai text,
  kl_thi_luc_mat_trai text,
  kl_thi_luc_mat_phai text,
  nhan_ap_mat_trai text,
  nhan_ap_mat_phai text,
  upload_file_hoi_benh text,
  upload_file_kham_benh text,
  ten_benh_nhan character varying(150),
  phong_id integer,
  khoa_id integer,
  hsba_id integer,
  benh_vien_id integer,
  cdvv_icd10_code text,
  cdvv_icd10_text text,
  CONSTRAINT hsba_phong_kham_partition_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.hsba_phong_kham_partition
  OWNER TO robustaeng;

-- Trigger: insert_hsba_phong_kham_p_trigger on public.hsba_phong_kham_partition

-- DROP TRIGGER insert_hsba_phong_kham_p_trigger ON public.hsba_phong_kham_partition;

CREATE TRIGGER insert_hsba_phong_kham_p_trigger
  BEFORE INSERT
  ON public.hsba_phong_kham_partition
  FOR EACH ROW
  EXECUTE PROCEDURE public.f_hsba_phong_kham_partition_insert_trigger();

-- Trigger: insert_hsba_phong_kham_partition_trigger on public.hsba_phong_kham_partition

-- DROP TRIGGER insert_hsba_phong_kham_partition_trigger ON public.hsba_phong_kham_partition;

CREATE TRIGGER insert_hsba_phong_kham_partition_trigger
  BEFORE INSERT
  ON public.hsba_phong_kham_partition
  FOR EACH ROW
  EXECUTE PROCEDURE public.f_hsba_phong_kham_partition_insert_trigger();

--END

--BEGIN
-- Table: public.hsba_phong_kham_partition_cu_chi

-- DROP TABLE public.hsba_phong_kham_partition_cu_chi;

CREATE TABLE public.hsba_phong_kham_partition_cu_chi
(
-- Inherited from table hsba_phong_kham_partition:  id integer NOT NULL DEFAULT nextval('hsba_phong_kham_partition_id_seq'::regclass),
-- Inherited from table hsba_phong_kham_partition:  hsba_khoa_phong_id integer,
-- Inherited from table hsba_phong_kham_partition:  auth_users_id integer,
-- Inherited from table hsba_phong_kham_partition:  benh_nhan_id integer,
-- Inherited from table hsba_phong_kham_partition:  ly_do_vao_vien text,
-- Inherited from table hsba_phong_kham_partition:  qua_trinh_benh_ly text,
-- Inherited from table hsba_phong_kham_partition:  tien_su_benh_ban_than text,
-- Inherited from table hsba_phong_kham_partition:  tien_su_benh_gia_dinh text,
-- Inherited from table hsba_phong_kham_partition:  kham_toan_than text,
-- Inherited from table hsba_phong_kham_partition:  kham_bo_phan text,
-- Inherited from table hsba_phong_kham_partition:  ket_qua_can_lam_san text,
-- Inherited from table hsba_phong_kham_partition:  huong_xu_ly text,
-- Inherited from table hsba_phong_kham_partition:  cdbd_icd10_code text,
-- Inherited from table hsba_phong_kham_partition:  cdbd_icd10_text text,
-- Inherited from table hsba_phong_kham_partition:  mach text,
-- Inherited from table hsba_phong_kham_partition:  nhiet_do text,
-- Inherited from table hsba_phong_kham_partition:  huyet_ap_thap text,
-- Inherited from table hsba_phong_kham_partition:  huyet_ap_cao text,
-- Inherited from table hsba_phong_kham_partition:  nhip_tho text,
-- Inherited from table hsba_phong_kham_partition:  can_nang text,
-- Inherited from table hsba_phong_kham_partition:  chieu_cao text,
-- Inherited from table hsba_phong_kham_partition:  sp_o2 text,
-- Inherited from table hsba_phong_kham_partition:  thi_luc_mat_trai text,
-- Inherited from table hsba_phong_kham_partition:  thi_luc_mat_phai text,
-- Inherited from table hsba_phong_kham_partition:  kl_thi_luc_mat_trai text,
-- Inherited from table hsba_phong_kham_partition:  kl_thi_luc_mat_phai text,
-- Inherited from table hsba_phong_kham_partition:  nhan_ap_mat_trai text,
-- Inherited from table hsba_phong_kham_partition:  nhan_ap_mat_phai text,
-- Inherited from table hsba_phong_kham_partition:  upload_file_hoi_benh text,
-- Inherited from table hsba_phong_kham_partition:  upload_file_kham_benh text,
-- Inherited from table hsba_phong_kham_partition:  ten_benh_nhan character varying(150),
-- Inherited from table hsba_phong_kham_partition:  phong_id integer,
-- Inherited from table hsba_phong_kham_partition:  khoa_id integer,
-- Inherited from table hsba_phong_kham_partition:  hsba_id integer,
-- Inherited from table hsba_phong_kham_partition:  benh_vien_id integer,
-- Inherited from table hsba_phong_kham_partition:  cdvv_icd10_code text,
-- Inherited from table hsba_phong_kham_partition:  cdvv_icd10_text text,
  CONSTRAINT hsba_phong_kham_partition_cu_chi_benh_vien_id_check CHECK (benh_vien_id = 1)
)
INHERITS (public.hsba_phong_kham_partition)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.hsba_phong_kham_partition_cu_chi
  OWNER TO robustaeng;

-- Index: public.i_hsba_phong_kham_partition_cu_chi

-- DROP INDEX public.i_hsba_phong_kham_partition_cu_chi;

CREATE INDEX i_hsba_phong_kham_partition_cu_chi
  ON public.hsba_phong_kham_partition_cu_chi
  USING btree
  (benh_vien_id);


--END

--BEGIN
-- Table: public.hsba_phong_kham_partition_vinh_long

-- DROP TABLE public.hsba_phong_kham_partition_vinh_long;

CREATE TABLE public.hsba_phong_kham_partition_vinh_long
(
-- Inherited from table hsba_phong_kham_partition:  id integer NOT NULL DEFAULT nextval('hsba_phong_kham_partition_id_seq'::regclass),
-- Inherited from table hsba_phong_kham_partition:  hsba_khoa_phong_id integer,
-- Inherited from table hsba_phong_kham_partition:  auth_users_id integer,
-- Inherited from table hsba_phong_kham_partition:  benh_nhan_id integer,
-- Inherited from table hsba_phong_kham_partition:  ly_do_vao_vien text,
-- Inherited from table hsba_phong_kham_partition:  qua_trinh_benh_ly text,
-- Inherited from table hsba_phong_kham_partition:  tien_su_benh_ban_than text,
-- Inherited from table hsba_phong_kham_partition:  tien_su_benh_gia_dinh text,
-- Inherited from table hsba_phong_kham_partition:  kham_toan_than text,
-- Inherited from table hsba_phong_kham_partition:  kham_bo_phan text,
-- Inherited from table hsba_phong_kham_partition:  ket_qua_can_lam_san text,
-- Inherited from table hsba_phong_kham_partition:  huong_xu_ly text,
-- Inherited from table hsba_phong_kham_partition:  cdbd_icd10_code text,
-- Inherited from table hsba_phong_kham_partition:  cdbd_icd10_text text,
-- Inherited from table hsba_phong_kham_partition:  mach text,
-- Inherited from table hsba_phong_kham_partition:  nhiet_do text,
-- Inherited from table hsba_phong_kham_partition:  huyet_ap_thap text,
-- Inherited from table hsba_phong_kham_partition:  huyet_ap_cao text,
-- Inherited from table hsba_phong_kham_partition:  nhip_tho text,
-- Inherited from table hsba_phong_kham_partition:  can_nang text,
-- Inherited from table hsba_phong_kham_partition:  chieu_cao text,
-- Inherited from table hsba_phong_kham_partition:  sp_o2 text,
-- Inherited from table hsba_phong_kham_partition:  thi_luc_mat_trai text,
-- Inherited from table hsba_phong_kham_partition:  thi_luc_mat_phai text,
-- Inherited from table hsba_phong_kham_partition:  kl_thi_luc_mat_trai text,
-- Inherited from table hsba_phong_kham_partition:  kl_thi_luc_mat_phai text,
-- Inherited from table hsba_phong_kham_partition:  nhan_ap_mat_trai text,
-- Inherited from table hsba_phong_kham_partition:  nhan_ap_mat_phai text,
-- Inherited from table hsba_phong_kham_partition:  upload_file_hoi_benh text,
-- Inherited from table hsba_phong_kham_partition:  upload_file_kham_benh text,
-- Inherited from table hsba_phong_kham_partition:  ten_benh_nhan character varying(150),
-- Inherited from table hsba_phong_kham_partition:  phong_id integer,
-- Inherited from table hsba_phong_kham_partition:  khoa_id integer,
-- Inherited from table hsba_phong_kham_partition:  hsba_id integer,
-- Inherited from table hsba_phong_kham_partition:  benh_vien_id integer,
-- Inherited from table hsba_phong_kham_partition:  cdvv_icd10_code text,
-- Inherited from table hsba_phong_kham_partition:  cdvv_icd10_text text,
  CONSTRAINT hsba_phong_kham_partition_vinh_long_benh_vien_id_check CHECK (benh_vien_id = 2)
)
INHERITS (public.hsba_phong_kham_partition)
WITH (
  OIDS=FALSE
);
ALTER TABLE public.hsba_phong_kham_partition_vinh_long
  OWNER TO robustaeng;

-- Index: public.i_hsba_phong_kham_partition_vinh_long

-- DROP INDEX public.i_hsba_phong_kham_partition_vinh_long;

CREATE INDEX i_hsba_phong_kham_partition_vinh_long
  ON public.hsba_phong_kham_partition_vinh_long
  USING btree
  (benh_vien_id);


--END

CREATE TABLE public.icd10
(
  icd10id integer NOT NULL DEFAULT nextval('icd10_icd10id_seq'::regclass),
  icd10code text,
  icd10name text,
  icd10name_en text,
  icd10name_thuonggoi text,
  icd10chapter integer,
  icd10group integer,
  icd10type integer,
  thanhtoanngoaidinhsuat integer,
  version timestamp without time zone,
  sync_flag integer,
  update_flag integer,
  icd10disable integer,
  isremove integer,
  lastuserupdated integer,
  lasttimeupdated timestamp without time zone,
  botinhngaygiuongcongkham integer,
  danhsachmadichvu text,
  danhsachmathuoc text,
  CONSTRAINT icd10_pkey PRIMARY KEY (icd10id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.ket_qua_y_lenh
(
  id integer NOT NULL DEFAULT nextval('serviceref4price_serviceref4priceid_seq'::regclass),
  ma_y_lenh text,
  ma_ket_qua_y_lenh text,
  CONSTRAINT serviceref4price_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.ket_qua_y_lenh_chi_tiet
(
  id integer NOT NULL DEFAULT nextval('service_serviceid_seq'::regclass),
  hsba_id integer,
  vien_phi_id integer,
  hsba_khoa_phong_id integer,
  dieu_tri_id integer,
  y_lenh_id integer,
  ma_y_lenh text,
  ma text,
  ten text,
  gia_tri text,
  ngay_tao timestamp without time zone,
  trang_thai integer,
  ghi_chu text,
  so_luong double precision,
  ket_qua text,
  da_xoa integer DEFAULT 0,
  ngay_xoa timestamp without time zone,
  nguoi_xoa text,
  nguoi_tao text,
  CONSTRAINT service_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.kho
(
  id integer NOT NULL DEFAULT nextval('kho_id_seq'::regclass),
  kho_cha_id integer,
  ten_kho character varying(250),
  ky_hieu character varying(50),
  duoc_ban integer DEFAULT 0,
  nhap_tu_ncc integer DEFAULT 0,
  tu_truc integer DEFAULT 0,
  trang_thai integer DEFAULT 0,
  phong_duoc_nhin_thay character varying(250),
  stt integer DEFAULT 0,
  benh_vien_id integer,
  CONSTRAINT kho_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.khoa
(
  id integer NOT NULL DEFAULT nextval('departmentgroup_departmentgroupid_seq'::regclass),
  ma_khoa text,
  ten_khoa text,
  loai_khoa integer,
  ma_khoa_byt text,
  benh_vien_id integer,
  kho_thuoc character varying(30),
  kho_vat_tu character varying(30),
  CONSTRAINT departmentgroup_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.khu_vuc
(
  id integer NOT NULL DEFAULT nextval('khu_vuc_id_seq'::regclass),
  ten character varying(100),
  loai integer,
  benh_vien_id integer,
  CONSTRAINT khu_vuc_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.mau_hoi_benh
(
  id integer NOT NULL DEFAULT nextval('mau_hoi_benh_id_seq'::regclass),
  user_id integer NOT NULL,
  chuc_nang character varying(255),
  ten_mau_hoi_benh character varying(255),
  ly_do_vao_vien character varying(255),
  qua_trinh_benh_ly character varying(255),
  tien_su_benh_ban_than character varying(255),
  tien_su_benh_gia_dinh character varying(255),
  cdbd_icd10_code character varying(255),
  cdbd_icd10_text character varying(255),
  kham_toan_than character varying(255),
  kham_bo_phan character varying(255),
  ket_qua_can_lam_san character varying(255),
  huong_xu_ly character varying(255),
  mach character varying(255),
  nhiet_do character varying(255),
  huyet_ap_thap character varying(255),
  huyet_ap_cao character varying(255),
  nhip_tho character varying(255),
  can_nang character varying(255),
  chieu_cao character varying(255),
  bmi character varying(255),
  sp_o2 character varying(255),
  thi_luc_mat_trai character varying(255),
  thi_luc_mat_phai character varying(255),
  kl_thi_luc_mat_trai character varying(255),
  kl_thi_luc_mat_phai character varying(255),
  nhan_ap_mat_trai character varying(255),
  nhan_ap_mat_phai character varying(255),
  CONSTRAINT mau_hoi_benh_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.muc_huong
(
  id integer NOT NULL DEFAULT nextval('muc_huong_id_seq'::regclass),
  ma_doi_tuong text,
  he_so integer,
  muc_huong_dung_tuyen double precision
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.nha_cung_cap
(
  id integer NOT NULL DEFAULT nextval('nha_cung_cap_id_seq'::regclass),
  ten_nha_cung_cap character varying(250),
  ky_hieu character varying(25),
  dia_chi character varying(250),
  so_dien_thoai integer,
  fax character varying(50),
  stt integer,
  trang_thai_su_dung integer,
  CONSTRAINT nha_cung_cap_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.nhom_danh_muc
(
  id integer NOT NULL DEFAULT nextval('nhom_danh_muc_id_seq'::regclass),
  ky_hieu character varying(25),
  ten_danh_muc character varying(250),
  parent_id integer,
  trang_thai_su_dung integer,
  CONSTRAINT nhom_danh_muc_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.password_resets
(
  id integer NOT NULL DEFAULT nextval('password_resets_id_seq'::regclass),
  email character varying(191) NOT NULL,
  token character varying(191) NOT NULL,
  created_at timestamp(0) without time zone,
  updated_at timestamp(0) without time zone,
  CONSTRAINT password_resets_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.phac_do_dieu_tri
(
  id integer NOT NULL DEFAULT nextval('phac_do_dieu_tri_id_seq'::regclass),
  id integer NOT NULL DEFAULT nextval('phac_do_dieu_tri_id_seq'::regclass),
  icd10id integer NOT NULL,
  xet_nghiem text,
  chan_doan_hinh_anh text,
  chuyen_khoa text,
  hoat_chat text,
  vat_tu text,
  loai_nhom integer,
  giai_trinh text,
  giai_trinh_tmp text,
  CONSTRAINT phac_do_dieu_tri_pkey1 PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.phieu_cham_soc
(
  id integer NOT NULL DEFAULT nextval('phieu_cham_soc_id_seq'::regclass),
  hsba_id integer NOT NULL,
  hsba_don_vi_id integer NOT NULL,
  benh_nhan_id integer NOT NULL,
  ten_benh_nhan text,
  nam_sinh integer,
  gioi_tinh integer,
  nguoi_tao_id integer,
  ngay_tao timestamp without time zone,
  dien_bien_benh text,
  nuoc_nhap character varying(50),
  nuoc_xuat character varying(50),
  mach character varying(10),
  nhiet_do character varying(5),
  huyet_ap_tren character varying(5),
  huyet_ap_duoi character varying(5),
  nhip_tho character varying(5),
  spo2 character varying(5),
  can_nang character varying(5),
  chieu_cao character varying(5),
  cd_icd10_code character varying(10),
  cd_icd10_text character varying(250),
  khoa_id integer,
  phong_id integer,
  CONSTRAINT phieu_cham_soc_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.phieu_kho
(
  id integer NOT NULL DEFAULT nextval('phieu_kho_id_seq'::regclass),
  phong_id integer,
  kho_id integer,
  kho_id_xu_ly integer,
  ten_kho_xu_ly character varying(50),
  loai_phieu integer,
  trang_thai integer,
  dien_giai character varying(500),
  nhan_vien_yeu_cau integer,
  nhan_vien_duyet integer,
  thoi_gian_yeu_cau timestamp without time zone,
  thoi_gian_duyet timestamp without time zone,
  so_chung_tu character varying(50),
  nguoi_giao integer,
  dia_chi_giao character varying(500),
  ghi_chu character varying(5000),
  ncc_id integer,
  phieu_kho_yeu_cau_id integer,
  ma_phieu character varying(50),
  CONSTRAINT phieu_kho_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.phieu_thu
(
  id integer NOT NULL DEFAULT nextval('phieu_thu_id_seq'::regclass),
  so_phieu_thu_id integer,
  ma_so character varying(255),
  hsba_khoa_phong_id integer,
  benh_nhan_id integer,
  vien_phi_id integer,
  hsba_id integer,
  loai_phieu_thu_id integer,
  auth_users_id integer,
  ngay_tao timestamp without time zone,
  khoa_id integer,
  phong_id integer,
  tong_tien double precision,
  da_tra double precision,
  con_no double precision,
  ten_benh_nhan character varying(255),
  da_huy_phieu integer,
  auth_users_huy_id integer,
  thoi_gian_huy timestamp without time zone,
  ly_do_huy character varying(255),
  ghi_chu character varying(255),
  auth_users_in_id character varying(255),
  hinh_thuc_thanh_toan integer,
  mien_giam double precision,
  ly_do_mien_giam text,
  CONSTRAINT phieu_thu_pkey PRIMARY KEY (id),
  CONSTRAINT phieu_thu_so_phieu_thu_id_fkey FOREIGN KEY (so_phieu_thu_id)
      REFERENCES public.so_phieu_thu (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.phieu_y_lenh
(
  id bigint NOT NULL DEFAULT nextval('phieu_y_lenh_id_seq'::regclass),
  benh_nhan_id integer NOT NULL,
  vien_phi_id integer NOT NULL,
  hsba_id integer NOT NULL,
  da_thu_tien integer,
  da_tam_ung integer,
  dieu_tri_id integer NOT NULL,
  khoa_id integer NOT NULL,
  phong_id integer NOT NULL,
  auth_users_id integer,
  loai_phieu_y_lenh integer,
  trang_thai integer,
  thoi_gian_chi_dinh timestamp without time zone,
  CONSTRAINT phieu_y_lenh_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.phong
(
  id integer NOT NULL DEFAULT nextval('department_departmentid_seq'::regclass),
  khoa_id integer,
  so_phong integer,
  ma_nhom text,
  ten_phong text,
  loai_phong integer,
  loai_benh_an integer,
  trang_thai integer,
  ten_nhom character varying(250),
  CONSTRAINT department_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.phong_benh
(
  id integer NOT NULL,
  khoa_id integer,
  ten character varying(255),
  loai_phong integer,
  so_luong_giuong integer,
  con_trong integer,
  CONSTRAINT phong_benh_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.phong_giuong_chi_tiet
(
  id integer NOT NULL DEFAULT nextval('phong_giuong_chi_tiet_id_seq'::regclass),
  hsba_id integer,
  hsbadv_id integer,
  benh_nhan_id integer,
  phong_benh_id integer,
  giuong_benh_id integer,
  thoi_gian_bat_dau timestamp without time zone,
  thoi_gian_ket_thuc timestamp without time zone,
  CONSTRAINT phong_giuong_chi_tiet_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.quay_so
(
  id integer NOT NULL DEFAULT nextval('quay_so_id_seq'::regclass),
  ten character varying(100),
  khu_vuc_id integer,
  benh_vien_id integer,
  trang_thai integer,
  CONSTRAINT quay_so_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.ra_vien
(
  id integer NOT NULL DEFAULT nextval('ra_vien_id_seq'::regclass),
  hsba_khoa_phong_id integer DEFAULT 0,
  benh_nhan_id integer DEFAULT 0,
  thoi_gian_ra_vien timestamp without time zone,
  tinh_trang text,
  phuong_phap_dieu_tri text,
  huong_dieu_tri_tiep_theo text,
  lich_hen timestamp without time zone,
  loi_dan_bac_si text,
  hsba_don_vi_id integer,
  CONSTRAINT ravien_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.so_phieu_thu
(
  id integer NOT NULL DEFAULT nextval('so_phieu_thu_id_seq'::regclass),
  ma_so character varying(255),
  trang_thai integer,
  loai_so integer,
  auth_users_id integer,
  ngay_tao timestamp without time zone,
  tong_so_phieu integer,
  so_phieu_su_dung integer DEFAULT 0,
  so_phieu_tu integer,
  so_phieu_den integer,
  billgroupremark character varying(255),
  sophieudahoantien integer DEFAULT 0,
  hinh_thuc_thanh_toan integer DEFAULT 0,
  tong_tien_thu double precision DEFAULT 0,
  tien_tam_ung double precision DEFAULT 0,
  tong_hoan_ung double precision DEFAULT 0,
  deleted_at timestamp(6) without time zone,
  CONSTRAINT so_phieu_thu_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.so_thu_ngan
(
  id integer NOT NULL DEFAULT nextval('billgroup_billgroupid_seq'::regclass),
  ma_so text,
  da_khoa integer DEFAULT 0,
  loai_so integer DEFAULT 0,
  nguoi_lap integer DEFAULT 0,
  ngay_lap timestamp without time zone,
  tong_so_phieu_thu integer DEFAULT 0,
  so_phieu_su_dung integer DEFAULT 0,
  so_phieu_from integer DEFAULT 0,
  so_phieu_to integer DEFAULT 0,
  hinh_thuc_thanh_toan integer,
  mode integer,
  ghi_chu text,
  trang_thai integer,
  CONSTRAINT billgroup_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.the_kho
(
  id integer NOT NULL DEFAULT nextval('the_kho_id_seq'::regclass),
  kho_id integer,
  danh_muc_thuoc_vat_tu_id integer,
  ma_con character varying(50),
  sl_dau_ky double precision DEFAULT 0,
  sl_kha_dung double precision DEFAULT 0,
  sl_ton_kho_chan integer DEFAULT 0,
  sl_ton_kho_le_1 integer DEFAULT 0,
  gia_nhap double precision,
  vat_nhap double precision,
  gia double precision,
  gia_bhyt double precision,
  gia_nuoc_ngoai double precision,
  han_su_dung timestamp without time zone,
  ky_ke_toan character varying(50),
  trang_thai integer,
  sl_ton_kho_le_2 integer DEFAULT 0,
  CONSTRAINT the_kho_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=TRUE
);

CREATE TABLE public.vien_phi
(
  id integer NOT NULL DEFAULT nextval('vien_phi_id_seq'::regclass),
  loai_vien_phi integer,
  trang_thai integer,
  khoa_id integer,
  phong_id integer,
  hsba_id integer,
  doi_tuong_benh_nhan integer,
  bhyt_id integer,
  benh_nhan_id integer,
  thoi_gian_tao timestamp without time zone DEFAULT (now())::timestamp without time zone,
  thoi_gian_thu_tien timestamp without time zone,
  nguoi_thu_tien integer,
  thoi_gian_thu_tien_theo_quy text,
  trang_thai_thanh_toan_bh integer,
  thoi_gian_thu_tien_bh timestamp without time zone,
  nguoi_thu_tien_bh integer,
  CONSTRAINT vien_phi_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);

CREATE TABLE public.y_lenh
(
  id bigint NOT NULL DEFAULT nextval('y_lenh_id_seq'::regclass),
  vien_phi_id integer NOT NULL,
  phieu_y_lenh_id integer NOT NULL,
  doi_tuong_benh_nhan integer,
  khoa_id integer NOT NULL,
  phong_id integer NOT NULL,
  ma character varying(100),
  ten character varying(250),
  ten_bhyt character varying(250),
  ten_nuoc_ngoai character varying(250),
  thoi_gian_chi_dinh timestamp without time zone,
  trang_thai integer,
  gia double precision,
  gia_bhyt double precision,
  gia_nuoc_ngoai double precision,
  bhyt_tra double precision,
  mien_giam double precision,
  da_nop double precision,
  loai_gia_mien_giam integer,
  so_luong double precision,
  so_luong_bac_sy double precision,
  huong_dan_su_dung text,
  loai_y_lenh integer,
  phieu_thu_id integer,
  muc_huong double precision,
  vien_phi double precision,
  loai_thanh_toan_cu integer,
  loai_thanh_toan_moi integer,
  ly_do_thay_loai_thanh_toan text,
  nguoi_chuyen_loai_thanh_toan integer,
  ms_bhyt text,
  CONSTRAINT y_lenh_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);
