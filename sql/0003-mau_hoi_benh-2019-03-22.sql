CREATE TABLE public.mau_hoi_benh
(
  id integer NOT NULL DEFAULT nextval('mau_hoi_benh_id_seq'::regclass),
  user_id integer,
  chuc_nang text,
  ten_mau_hoi_benh text,
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
  CONSTRAINT mau_hoi_benh_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);