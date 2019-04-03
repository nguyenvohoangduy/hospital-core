CREATE TABLE public.auth_groups_has_permissions
(
  id integer NOT NULL DEFAULT nextval('auth_groups_has_permissions_id_seq'::regclass),
  group_id integer,
  permission_id text,
  CONSTRAINT _auth_groups_has_permissions_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);