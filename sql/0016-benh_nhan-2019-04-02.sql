begin transaction;
ALTER TABLE benh_nhan ADD COLUMN ma_so_thue varchar(50);
ALTER TABLE benh_nhan ADD COLUMN so_cmnd varchar(50);
ALTER TABLE benh_nhan ADD COLUMN ma_tiem_chung varchar(50);
ALTER TABLE benh_nhan ADD COLUMN ghi_chu text;
ALTER TABLE benh_nhan DROP COLUMN loai_nguoi_than;
ALTER TABLE benh_nhan DROP COLUMN ten_nguoi_than;
ALTER TABLE benh_nhan DROP COLUMN dien_thoai_nguoi_than;
end;