BEGIN TRANSACTION;
CREATE TABLE service
(
  id serial NOT NULL ,
  name character varying(200),
  has_khoa_level_scope integer,
  has_phong_level_scope integer,
  CONSTRAINT _service_pkey PRIMARY KEY (id)
);
END;