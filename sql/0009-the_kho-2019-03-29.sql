BEGIN TRANSACTION;
ALTER TABLE the_kho ADD COLUMN don_vi_co_ban varchar(50);
ALTER TABLE the_kho ADD COLUMN sl_nhap_chan integer SET DEFAULT 0;
ALTER TABLE the_kho ADD COLUMN sl_nhap_le float8 SET DEFAULT 0;
ALTER TABLE the_kho ADD COLUMN don_vi_nhap varchar(50);
ALTER TABLE the_kho ADD COLUMN he_so_quy_doi integer;
END TRANSACTION;