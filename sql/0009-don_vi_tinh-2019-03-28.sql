BEGIN TRANSACTION;
ALTER TABLE don_vi_tinh ADD COLUMN don_vi_quy_doi varchar(50);
ALTER TABLE don_vi_tinh ADD COLUMN he_so_quy_doi integer;
ALTER TABLE don_vi_tinh ADD COLUMN don_vi_co_ban integer;
ALTER TABLE don_vi_tinh DROP COLUMN he_so_le_1;
ALTER TABLE don_vi_tinh DROP COLUMN he_so_le_2;
END TRANSACTION;