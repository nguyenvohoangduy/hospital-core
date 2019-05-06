begin transaction;
  alter table gioi_han add sl_ton_kho float8;
  alter table gioi_han drop column ton_toi_thieu;
  alter table gioi_han drop column han_su_dung_toi_thieu;
	alter table danh_muc_thuoc_vat_tu add canh_bao_het_han integer;
end;