create table if not exists users_details
(
	id_users_details serial not null
		constraint users_details_pk
			primary key,
	first_name varchar(250) not null,
	last_name varchar(250) not null
);

create table if not exists users
(
	id_users serial not null
		constraint users_pk
			primary key,
	id_users_details integer not null
		constraint users_users_details_id_users_details_fk
			references users_details
				on update cascade on delete cascade,
	email varchar(250) not null,
	password varchar(250),
	enabled boolean default true not null,
	creation_date date not null
);

create unique index if not exists users_id_users_uindex
	on users (id_users);

create unique index if not exists users_email_uindex
	on users (email);

create unique index if not exists users_details_id_users_details_uindex
	on users_details (id_users_details);

create table if not exists groups
(
	id_groups serial not null
		constraint groups_pk
			primary key,
	full_name varchar(250) not null,
	short_name varchar(32) not null,
	access_password varchar(250) not null,
	salt varchar(250)
);

create unique index if not exists groups_id_groups_uindex
	on groups (id_groups);

create table if not exists subgroups
(
	id_subgroups serial not null
		constraint subgroups_pk
			primary key,
	id_groups integer not null
		constraint subgroups_groups_id_groups_fk
			references groups
				on update cascade on delete cascade,
	full_name varchar(250) not null,
	short_name varchar(15) not null
);

create unique index if not exists subgroups_id_subgroups_uindex
	on subgroups (id_subgroups);

create table if not exists threads
(
	id_threads serial not null
		constraint threads_pk
			primary key,
	id_subgroups integer not null
		constraint threads_subgroups_id_subgroups_fk
			references subgroups
				on update cascade on delete cascade,
	name varchar(50) not null
);

create unique index if not exists threads_id_threads_uindex
	on threads (id_threads);

create table if not exists users_types
(
	id_users_types serial not null
		constraint users_types_pk
			primary key,
	name varchar(50) not null
);

create unique index if not exists users_types_id_users_types_uindex
	on users_types (id_users_types);

create table if not exists users_types_in_groups
(
	id_users integer not null
		constraint users_types_in_groups_users_id_users_fk
			references users
				on update cascade on delete cascade,
	id_groups integer
		constraint users_types_in_groups_groups_id_groups_fk
			references groups
				on update cascade on delete cascade,
	id_users_types integer
		constraint users_types_in_groups_users_types_id_users_types_fk
			references users_types
				on update cascade on delete cascade,
	constraint users_types_in_groups_pk
		unique (id_users, id_groups)
);

create table if not exists statements
(
	id_statements serial not null
		constraint statements_pk
			primary key,
	title varchar(250) not null,
	content text not null,
	creation_date timestamp with time zone not null,
	id_creation_user integer not null
		constraint statements_users_id_users_fk
			references users
				on update cascade on delete cascade,
	approve_date timestamp with time zone,
	id_approve_user integer
		constraint statements_users_id_users_fk_2
			references users
				on update cascade on delete cascade,
	source_url varchar(2000)
);

create unique index if not exists statements_id_statements_uindex
	on statements (id_statements);

create table if not exists links
(
	id_links serial not null
		constraint links_pk
			primary key,
	title varchar(50) not null,
	url varchar(1000) not null,
	note varchar(500) not null
);

create unique index if not exists links_id_links_uindex
	on links (id_links);

create table if not exists attachments
(
	id_attachments serial not null
		constraint attachments_pk
			primary key,
	filename varchar(250) not null,
	type varchar(250)
);

create unique index if not exists attachments_id_attachments_uindex
	on attachments (id_attachments);

create table if not exists statements_threads
(
	id_statements integer not null
		constraint statements_threads_statements_id_statements_fk
			references statements
				on update cascade on delete cascade,
	id_threads integer not null
		constraint statements_threads_threads_id_threads_fk
			references threads
);

create table if not exists links_threads
(
	id_links integer not null
		constraint links_threads_links_id_links_fk
			references links
				on update cascade on delete set default,
	id_threads integer not null
		constraint links_threads_threads_id_threads_fk
			references threads
				on update cascade on delete cascade
);

create table if not exists statements_attachments
(
	id_statements integer not null
		constraint statements_attachments_statements_id_statements_fk
			references statements
				on update cascade on delete cascade,
	id_attachments integer not null
		constraint statements_attachments_attachments_id_attachments_fk
			references attachments
				on update cascade on delete cascade
);

create table if not exists users_subgroups
(
	id_users integer not null
		constraint users_subgroups_users_id_users_fk
			references users
				on update cascade on delete cascade,
	id_subgroups integer not null
		constraint users_subgroups_subgroups_id_subgroups_fk
			references subgroups
				on update cascade on delete cascade
);

create table if not exists users_threads
(
	id_users integer not null
		constraint users_threads_users_id_users_fk
			references users,
	id_threads integer not null
		constraint users_threads_threads_id_threads_fk
			references threads
);

create table if not exists session_auto_logins
(
	id_auto_logins varchar(256) not null
		constraint session_auto_login_pk
			primary key,
	id_users integer not null
		constraint session_auto_login_users_id_users_fk
			references users
				on update cascade on delete cascade,
	last_access timestamp with time zone not null
);

create unique index if not exists session_auto_login_auto_login_id_uindex
	on session_auto_logins (id_auto_logins);

create or replace function select_groups_for_user(user_id integer) returns SETOF groups
	language sql
as $$
SELECT
--            groups.*,
        groups.id_groups,
        groups.full_name,
        groups.short_name,
        groups.access_password,
        groups.salt

    FROM public.groups
        INNER JOIN public.users_types_in_groups
            USING (id_groups)
        INNER JOIN public.users
            USING (id_users)
    WHERE id_users = user_id;
$$;

create or replace function select_subgroups_for_user_and_group(user_id integer, group_id integer) returns SETOF subgroups
	language sql
as $$
SELECT
        subgroups.id_subgroups,
        id_groups,
        full_name,
        short_name
    FROM public.subgroups
        INNER JOIN public.users_subgroups
            USING (id_subgroups)
        INNER JOIN public.users
            USING (id_users)
    WHERE id_users = user_id AND id_groups = group_id;
$$;

create or replace function select_subgroup_for_user_and_subgroup_id(user_id integer, subgroup_id integer) returns SETOF subgroups
	language sql
as $$
SELECT
        subgroups.*
    FROM public.subgroups
        INNER JOIN public.users_subgroups
            USING (id_subgroups)
    WHERE id_users = user_id AND id_subgroups = subgroup_id;
$$;

create or replace function select_threads_for_subgroup(subgroup_id integer) returns SETOF threads
	language sql
as $$
SELECT
        threads.*
    FROM public.threads
    WHERE id_subgroups = subgroup_id;
$$;

create or replace function insert_statement(_title character varying, _content text, _creation_date timestamp with time zone, _id_creation_user integer, _source_url character varying) returns SETOF integer
	language sql
as $$
INSERT INTO public.statements
        (title, content, creation_date, id_creation_user, source_url)
    VALUES
        (_title, _content, _creation_date, _id_creation_user, _source_url)
    RETURNING id_statements;
$$;

create or replace function associate_statement_with_attachment(_id_statements integer, _id_attachments integer) returns SETOF integer
	language sql
as $$
INSERT INTO public.statements_attachments
        (id_statements, id_attachments)
    VALUES
        (_id_statements, _id_attachments)
    RETURNING id_attachments AS _id_attachments;
$$;

create or replace function associate_statement_with_thread(_id_statements integer, _id_threads integer) returns SETOF integer
	language sql
as $$
INSERT INTO public.statements_threads
        (id_statements, id_threads)
    VALUES
        (_id_statements, _id_threads)
    RETURNING id_statements AS _id_attachments;
$$;

create or replace function insert_user(_email character varying, _password character varying, _enabled boolean, _creation_date date, _first_name character varying, _last_name character varying) returns SETOF integer
	language plpgsql
as $$
DECLARE
    _id_users integer;
    _id_users_details integer;
BEGIN
    INSERT INTO public.users_details
        (first_name, last_name)
    VALUES
        (_first_name, _last_name)
    RETURNING id_users_details INTO _id_users_details;

    INSERT INTO public.users
        (id_users_details, email, password, enabled, creation_date)
    VALUES
        (_id_users_details, _email, _password, _enabled, _creation_date)
    RETURNING id_users INTO _id_users;
--     SELECT email FROM users WHERE id_users = _id_users;
END;
$$;

create or replace function select_user_for_email(_email character varying) returns TABLE(id_users_details integer, id_users integer, email character varying, password character varying, enabled boolean, creation_date date, first_name character varying, last_name character varying)
	language plpgsql
as $$
BEGIN
    RETURN QUERY
    SELECT
        users.id_users_details,
        users.id_users,
        users.email,
        users.password,
        users.enabled,
        users.creation_date,
        users_details.first_name,
        users_details.last_name
    FROM public.users
    INNER JOIN public.users_details
        USING (id_users_details)
    WHERE users.email = _email;
END;
$$;

create or replace function select_subgroups_for_group(group_id integer) returns SETOF subgroups
	language sql
as $$
SELECT
        subgroups.id_subgroups,
        id_groups,
        full_name,
        short_name
    FROM public.subgroups
    WHERE id_groups = group_id;
$$;

create or replace function select_threads_for_user_and_subgroup(_id_users integer, _id_subgroups integer) returns SETOF threads
	language sql
as $$
SELECT
        threads.*
    FROM public.threads
        INNER JOIN public.users_threads
            USING (id_threads)
    WHERE id_users = _id_users AND id_subgroups = _id_subgroups;
$$;

create or replace function opt_out_user_from_thread(_id_users integer, _id_threads integer) returns SETOF integer
	language sql
as $$
DELETE FROM public.users_threads
    WHERE id_users = _id_users AND id_threads = _id_threads
    RETURNING _id_threads;
$$;

create or replace function opt_in_user_to_thread(_id_users integer, _id_threads integer) returns SETOF integer
	language sql
as $$
INSERT INTO public.users_threads
        (id_users, id_threads)
    VALUES
        (_id_users, _id_threads)
    RETURNING _id_threads;
$$;

create or replace function change_email_for_user_id(_id_users integer, _email character varying) returns SETOF integer
	language sql
as $$
UPDATE public.users
    SET email = _email
    WHERE id_users = _id_users
    RETURNING id_users;
$$;

create or replace function change_password_for_user_id(_id_users integer, _password character varying) returns SETOF integer
	language sql
as $$
UPDATE public.users
    SET password = _password
    WHERE id_users = _id_users
    RETURNING id_users;
$$;

create or replace function change_first_name_for_user_id(_id_users integer, _first_name character varying) returns SETOF integer
	language sql
as $$
UPDATE public.users_details
    SET first_name = _first_name
    FROM public.users
    WHERE users_details.id_users_details = users.id_users_details AND id_users = _id_users
    RETURNING id_users;
$$;

create or replace function change_last_name_for_user_id(_id_users integer, _last_name character varying) returns SETOF integer
	language sql
as $$
UPDATE public.users_details
    SET last_name = _last_name
    FROM public.users
    WHERE users_details.id_users_details = users.id_users_details AND id_users = _id_users
    RETURNING id_users;
$$;

create or replace function select_user_for_user_id(_id_users integer) returns TABLE(id_users_details integer, id_users integer, email character varying, password character varying, enabled boolean, creation_date date, first_name character varying, last_name character varying)
	language plpgsql
as $$
BEGIN
    RETURN QUERY
    SELECT
        users.id_users_details,
        users.id_users,
        users.email,
        users.password,
        users.enabled,
        users.creation_date,
        users_details.first_name,
        users_details.last_name
    FROM public.users
    INNER JOIN public.users_details
        USING (id_users_details)
    WHERE users.id_users = _id_users;
END;
$$;

create or replace function opt_in_user_to_subgroup(_id_users integer, _id_subgroups integer) returns SETOF integer
	language sql
as $$
INSERT INTO public.users_subgroups
        (id_users, id_subgroups)
    VALUES
        (_id_users, _id_subgroups)
    RETURNING _id_subgroups;
$$;

create or replace function opt_out_user_from_subgroup(_id_users integer, _id_subgroups integer) returns SETOF integer
	language sql
as $$
DELETE FROM public.users_subgroups
    WHERE id_users = _id_users AND id_subgroups = _id_subgroups
    RETURNING _id_subgroups;
$$;

create or replace function opt_out_user_from_group(_id_users integer, _id_groups integer) returns SETOF integer
	language sql
as $$
DELETE FROM public.users_types_in_groups
    WHERE id_users = _id_users AND id_groups = _id_groups
    RETURNING _id_groups;
$$;

create or replace function opt_in_user_to_group(_id_users integer, _access_password character varying) returns integer
	language plpgsql
as $$
DECLARE
    _id_groups integer;
BEGIN
    SELECT id_groups INTO _id_groups
    FROM groups
    WHERE access_password = _access_password;
    raise notice '%', _id_groups;

    INSERT INTO public.users_types_in_groups
        (id_users, id_groups, id_users_types)
    VALUES
        (_id_users, _id_groups, 2);
    RETURN _id_groups;
END;
$$;

create or replace function change_statement(_id_statements integer, _title character varying, _content text, _creation_date timestamp with time zone, _id_creation_user integer, _source_url character varying) returns SETOF integer
	language sql
as $$
UPDATE public.statements
    SET
        title = _title,
        content = _content,
        creation_date = _creation_date,
        id_creation_user = _id_creation_user,
        source_url = _source_url
    WHERE statements.id_statements = _id_statements
    RETURNING id_statements;
$$;

create or replace function insert_group(_full_name character varying, _short_name character varying, _access_password character varying) returns SETOF integer
	language sql
as $$
INSERT INTO public.groups
        (full_name, short_name, access_password)
    VALUES
        (_full_name, _short_name, _access_password)
    RETURNING id_groups;
$$;

create or replace function insert_subgroup(_id_groups integer, _full_name character varying, _short_name character varying) returns SETOF integer
	language sql
as $$
INSERT INTO public.subgroups
        (id_groups, full_name, short_name)
    VALUES
        (_id_groups, _full_name, _short_name)
    RETURNING id_subgroups;
$$;

create or replace function insert_thread(_id_subgroups integer, _name character varying) returns SETOF integer
	language sql
as $$
INSERT INTO public.threads
        (id_subgroups, name)
    VALUES
        (_id_subgroups, _name)
    RETURNING id_threads;
$$;

create or replace function select_permissions_for_user(_id_users integer) returns TABLE(id_groups integer, id_users_types integer)
	language plpgsql
as $$
BEGIN
    RETURN QUERY
    SELECT
        users_types_in_groups.id_groups,
        users_types_in_groups.id_users_types
    FROM public.users_types_in_groups
    WHERE users_types_in_groups.id_users = _id_users;
END;
$$;

create or replace function select_user_id_for_auto_login_id(_id_auto_logins character varying) returns TABLE(id_users integer)
	language plpgsql
as $$
BEGIN
    RETURN QUERY
    SELECT
        session_auto_logins.id_users
    FROM public.session_auto_logins
    WHERE
          session_auto_logins.id_auto_logins = _id_auto_logins
          AND session_auto_logins.last_access > now() - interval '1 day';
END;
$$;

create or replace function insert_auto_login(_id_users integer, _id_auto_logins character varying, _last_access timestamp with time zone) returns SETOF integer
	language sql
as $$
INSERT INTO public.session_auto_logins
        (id_users, id_auto_logins, last_access)
    VALUES
        (_id_users, _id_auto_logins, _last_access)
    RETURNING id_users;
$$;

create or replace function update_auto_login(_id_users integer, _id_auto_logins character varying, _last_access timestamp with time zone) returns SETOF timestamp with time zone
	language sql
as $$
UPDATE public.session_auto_logins
    SET
        last_access = _last_access
    WHERE session_auto_logins.id_users = _id_users
          AND session_auto_logins.id_auto_logins = _id_auto_logins
    RETURNING last_access;
$$;

create or replace function confirm_statement(_id_users integer, _id_statements integer, _approve_date timestamp with time zone) returns SETOF integer
	language sql
as $$
UPDATE public.statements
    SET
        id_approve_user = _id_users,
        approve_date = _approve_date
    WHERE statements.id_statements = _id_statements
    RETURNING id_statements;
$$;

create or replace function undo_confirm_statement(_id_users integer, _id_statements integer) returns SETOF integer
	language sql
as $$
UPDATE public.statements
    SET
        id_approve_user = NULL,
        approve_date = NULL
    WHERE statements.id_statements = _id_statements
    RETURNING id_statements;
$$;

create or replace function insert_admin_into_users_types_in_groups(_id_users integer, _id_groups integer) returns SETOF integer
	language sql
as $$
INSERT INTO public.users_types_in_groups
        (id_users, id_groups, id_users_types)
    VALUES
        (_id_users, _id_groups, 1)
    RETURNING id_users;
$$;

create or replace function delete_group(_id_groups integer) returns SETOF integer
	language sql
as $$
--     TODO: delete linki, komunikaty wątki, podgrupy
    DELETE FROM public.groups
    WHERE id_groups = _id_groups
    RETURNING _id_groups;
$$;

create or replace function delete_subgroup(_id_subgroups integer) returns SETOF integer
	language sql
as $$
--     TODO: delete linki, komunikaty, wątki, podgrupy
    DELETE FROM public.subgroups
    WHERE id_subgroups = _id_subgroups
    RETURNING _id_subgroups;
$$;

create or replace function delete_thread(_id_threads integer) returns SETOF integer
	language sql
as $$
--     TODO: delete linki, komunikaty wątki, podgrupy
    DELETE FROM public.users_threads
    WHERE id_threads = _id_threads
    RETURNING _id_threads;

    DELETE FROM public.threads
    WHERE id_threads = _id_threads
    RETURNING _id_threads;
$$;

create or replace function select_statements_for_user_and_subgroup(_id_users integer, _id_subgroups integer) returns TABLE(id_statements integer, title character varying, content character varying, creation_date timestamp with time zone, id_creation_user integer, approve_date timestamp with time zone, id_approve_user integer, source_url character varying, email_creation_user character varying, email_approve_user character varying)
	language sql
as $$
SELECT
        DISTINCT
        id_statements,
        title,
        content,
        statements.creation_date,
        id_creation_user,
        approve_date,
        id_approve_user,
        source_url,
        cu.email,
        au.email
    FROM public.statements
    INNER JOIN public.statements_threads USING (id_statements)
    INNER JOIN public.threads USING (id_threads)
    INNER JOIN public.users_threads USING (id_threads)
    LEFT JOIN public.users cu ON statements.id_creation_user = cu.id_users
    LEFT JOIN public.users au ON statements.id_approve_user = au.id_users
    WHERE users_threads.id_users=_id_users AND id_subgroups=_id_subgroups
    ORDER BY creation_date DESC;
$$;

create or replace function select_statements_for_user_and_last_week(_id_users integer) returns TABLE(id_statements integer, title character varying, content character varying, creation_date timestamp with time zone, id_creation_user integer, approve_date timestamp with time zone, id_approve_user integer, source_url character varying, email_creation_user character varying, email_approve_user character varying)
	language sql
as $$
SELECT DISTINCT
        id_statements,
        title,
        content,
        statements.creation_date,
        id_creation_user,
        approve_date,
        id_approve_user,
        source_url,
        cu.email,
        au.email
    FROM public.statements
    INNER JOIN public.statements_threads USING (id_statements)
    INNER JOIN public.users_threads USING (id_threads)
    LEFT JOIN public.users cu ON statements.id_creation_user = cu.id_users
    LEFT JOIN public.users au ON statements.id_approve_user = au.id_users
    WHERE users_threads.id_users = _id_users AND statements.creation_date > now() - interval '1 week'
    ORDER BY statements.creation_date DESC;
$$;

create or replace function insert_link(_title character varying, _url character varying, _note character varying) returns SETOF integer
	language sql
as $$
INSERT INTO public.links
        (title, url, note)
    VALUES
        (_title, _url, _note)
    RETURNING id_links;
$$;

create or replace function associate_link_with_thread(_id_links integer, _id_threads integer) returns void
	language sql
as $$
INSERT INTO public.links_threads
        (id_links, id_threads)
    VALUES
        (_id_links, _id_threads);
$$;

create or replace function select_links_for_user_and_subgroup(_id_users integer, _id_subgroups integer) returns TABLE(id_links integer, title character varying, url character varying, note character varying)
	language sql
as $$
SELECT DISTINCT
        id_links,
        title,
        url,
        note
    FROM public.links
    INNER JOIN public.links_threads USING (id_links)
    INNER JOIN public.threads USING (id_threads)
    INNER JOIN public.users_threads USING (id_threads)
    WHERE users_threads.id_users=_id_users AND id_subgroups=_id_subgroups;
$$;

create or replace function delete_statement(_id_statements integer) returns void
	language sql
as $$
DELETE FROM public.attachments
    USING statements_attachments
    WHERE attachments.id_attachments = statements_attachments.id_attachments
        AND id_statements = _id_statements;

    DELETE FROM public.statements
    WHERE id_statements = _id_statements;

--     DELETE FROM public.attachments
--     USING statements_attachments
--     WHERE attachments.id_attachments = statements_attachments.id_attachments
--         AND id_statements = _id_statements;
$$;

create or replace function delete_links(_id_links integer) returns void
	language sql
as $$
DELETE FROM public.links_threads
    WHERE id_links = _id_links;

    DELETE FROM public.links
    WHERE id_links = _id_links;
$$;

create or replace function select_statement_for_id(_id_statements integer) returns TABLE(id_statements integer, title character varying, content character varying, creation_date timestamp with time zone, id_creation_user integer, approve_date timestamp with time zone, id_approve_user integer, source_url character varying, email_creation_user character varying, email_approve_user character varying)
	language sql
as $$
SELECT
        statements.id_statements,
        title,
        content,
        statements.creation_date,
        id_creation_user,
        approve_date,
        id_approve_user,
        source_url,
        cu.email,
        au.email
    FROM public.statements
    LEFT JOIN public.users cu ON statements.id_creation_user = cu.id_users
    LEFT JOIN public.users au ON statements.id_approve_user = au.id_users
    WHERE id_statements = _id_statements
    ORDER BY statements.creation_date DESC;
$$;

create or replace function insert_attachment(_filename character varying, _type character varying) returns SETOF integer
	language sql
as $$
INSERT INTO public.attachments
        (filename, type)
    VALUES
        (_filename, _type)
    RETURNING id_attachments;
$$;

create or replace function select_attachments_for_statement(_id_statements integer) returns SETOF attachments
	language sql
as $$
SELECT
        id_attachments,
        filename,
        type
    FROM
        public.attachments
    WHERE id_attachments IN
        (SELECT id_attachments
        FROM statements_attachments
        WHERE id_statements = _id_statements
        );
$$;

create or replace function select_statements_for_search_string_user_and_subgroup(_searched_string character varying, _id_users integer, _id_subgroups integer) returns TABLE(id_statements integer, title character varying, content character varying, creation_date timestamp with time zone, id_creation_user integer, approve_date timestamp with time zone, id_approve_user integer, source_url character varying, email_creation_user character varying, email_approve_user character varying)
	language sql
as $$
SELECT
        DISTINCT
        id_statements,
        title,
        content,
        statements.creation_date,
        id_creation_user,
        approve_date,
        id_approve_user,
        source_url,
        cu.email,
        au.email
    FROM public.statements
    INNER JOIN public.statements_threads USING (id_statements)
    INNER JOIN public.threads USING (id_threads)
    INNER JOIN public.users_threads USING (id_threads)
    LEFT JOIN public.users cu ON statements.id_creation_user = cu.id_users
    LEFT JOIN public.users au ON statements.id_approve_user = au.id_users
    WHERE users_threads.id_users=_id_users
        AND id_subgroups=_id_subgroups
        AND (LOWER(title) LIKE _searched_string
            OR LOWER(content) LIKE _searched_string)
    ORDER BY creation_date DESC;
$$;

