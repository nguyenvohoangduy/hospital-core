CREATE TABLE public.auth_permissions
(
  id integer NOT NULL DEFAULT nextval('auth_permissions_id_seq'::regclass),
  policy_id integer,
  benh_vien_id integer,
  khoa integer,
  ma_nhom_phong text,
  key character varying(100),
  service_id integer,
  name character varying(200)
)
WITH (
  OIDS=FALSE
);