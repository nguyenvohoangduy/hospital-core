BEGIN TRANSACTION;
ALTER TABLE chi_tiet_phieu_kho ALTER COLUMN so_luong_yeu_cau TYPE float8;
ALTER TABLE chi_tiet_phieu_kho ALTER COLUMN so_luong_nhap TYPE float8;
ALTER TABLE chi_tiet_phieu_kho ADD COLUMN don_vi_nhap varchar(50);
ALTER TABLE chi_tiet_phieu_kho ADD COLUMN he_so_quy_doi integer;
ALTER TABLE chi_tiet_phieu_kho ADD COLUMN don_vi_co_ban varchar(50);
ALTER TABLE chi_tiet_phieu_kho RENAME COLUMN chi_dinh_id TO phieu_y_lenh_id;
END TRANSACTION;