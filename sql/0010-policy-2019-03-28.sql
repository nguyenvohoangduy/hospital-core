BEGIN TRANSACTION;
CREATE TABLE policy
(
  id serial NOT NULL ,
  service_id integer,
  name character varying(200),
  key character varying(200),
  uri character varying(200),
  method character varying(20),
  access_type integer,
  CONSTRAINT policy_pkey PRIMARY KEY (id)
);
END;