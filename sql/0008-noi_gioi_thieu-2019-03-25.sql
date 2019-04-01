begin transaction;
CREATE SEQUENCE public.noi_gioi_thieu_id_seq
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 9223372036854775807
  START 1
  CACHE 1;
  
create table noi_gioi_thieu
(
  id integer NOT NULL DEFAULT nextval('noi_gioi_thieu_id_seq'::regclass),
  ten character varying(255),
  dia_chi character varying(255),
  loai bit,
  CONSTRAINT noi_gioi_thieu_pkey PRIMARY KEY (id)
);
end;

begin transaction;
ALTER TABLE hsba ADD COLUMN noi_gioi_thieu_id integer;
ALTER TABLE hsba ADD COLUMN ghi_chu varchar(255);
end;
