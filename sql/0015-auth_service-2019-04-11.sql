begin transaction;
ALTER TABLE auth_service ADD COLUMN display_name varchar(255);
end;