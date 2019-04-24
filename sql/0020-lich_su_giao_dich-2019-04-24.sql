CREATE TABLE public.lich_su_giao_dich(
    id integer NOT NULL DEFAULT nextval('lich_su_giao_dich_id_seq'::regclass),
    benh_nhan_id integer NOT NULL,
    so_tien TEXT NOT NULL,
    noi_dung TEXT NOT NULL,
    ngay_giao_dich TIMESTAMP
);