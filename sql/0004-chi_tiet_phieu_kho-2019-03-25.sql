BEGIN TRANSACTION;
ALTER TABLE chi_tiet_phieu_kho ALTER COLUMN so_luong_yeu_cau TYPE float8;
ALTER TABLE chi_tiet_phieu_kho ALTER COLUMN so_luong_nhap TYPE float8;
END TRANSACTION;