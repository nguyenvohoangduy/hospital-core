begin transaction;
ALTER TABLE benh_nhan ADD COLUMN ma_so_thue varchar(50);
ALTER TABLE benh_nhan ADD COLUMN so_cmnd varchar(50);
ALTER TABLE benh_nhan ADD COLUMN ma_tiem_chung varchar(50);
end;