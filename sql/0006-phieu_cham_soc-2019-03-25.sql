CREATE TABLE public.phieu_cham_soc
(
  id integer NOT NULL DEFAULT nextval('phieu_cham_soc_id_seq'::regclass),
  hsba_id integer NOT NULL,
  hsba_don_vi_id integer NOT NULL,
  benh_nhan_id integer NOT NULL,
  auth_users_id integer,
  thoi_gian_tao timestamp without time zone,
  dieu_tri_id integer,
  y_lenh_thuc_hien text,
  ghi_chu text,
  CONSTRAINT phieu_cham_soc_id_pkey PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);