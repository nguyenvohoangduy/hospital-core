CREATE TABLE public.auth_groups_has_permissions
(
  id integer NOT NULL,
  group_id integer,
  permission_id integer,
  CONSTRAINT auth_groups_has_permissions_pk PRIMARY KEY (id)
)
WITH (
  OIDS=FALSE
);