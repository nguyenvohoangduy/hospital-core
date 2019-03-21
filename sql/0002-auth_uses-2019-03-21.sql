begin transaction;
alter table auth_users add login_at timestamp without time zone;
end;