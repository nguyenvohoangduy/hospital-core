BEGIN TRANSACTION;
ALTER TABLE hsba DROP COLUMN loai_nguoi_than;
ALTER TABLE hsba DROP COLUMN ten_nguoi_than;
ALTER TABLE hsba DROP COLUMN dien_thoai_nguoi_than;
ALTER TABLE hsba DROP COLUMN thx_gplace_json;
END;