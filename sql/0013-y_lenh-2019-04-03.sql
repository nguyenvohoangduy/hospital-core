BEGIN TRANSACTION;
ALTER TABLE y_lenh ADD COLUMN don_vi_tinh varchar(20);
ALTER TABLE y_lenh ADD COLUMN kho_id integer;
ALTER TABLE y_lenh ADD COLUMN danh_muc_id integer;
END TRANSACTION;