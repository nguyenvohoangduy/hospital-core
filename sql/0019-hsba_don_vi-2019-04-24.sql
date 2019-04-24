begin transaction;
ALTER TABLE hsba_don_vi ADD COLUMN trang_thai_thanh_toan integer;
alter table phieu_thu rename column hsba_khoa_phong_id to hsba_don_vi_id;
end;