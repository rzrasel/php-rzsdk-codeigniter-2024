
-- SQLite Database DATE CREATED: 2025-02-21, DATE MODIFIED: 2025-03-06 - VERSION: v-1.1.2
-- DATABASE NAME: App Sys Database Tables Schema

CREATE DATABASE IF NOT EXISTS app_sys_database_tables_schema;
USE app_sys_database_tables_schema;

DROP TABLE IF EXISTS tbl_composite_key;
DROP TABLE IF EXISTS tbl_column_key;
DROP TABLE IF EXISTS tbl_column_data;
DROP TABLE IF EXISTS tbl_table_data;
DROP TABLE IF EXISTS tbl_database_schema;

CREATE TABLE IF NOT EXISTS tbl_database_schema (
    id                   BIGINT(20)     NOT NULL,
    schema_name          TEXT           NOT NULL,
    schema_version       TEXT           NULL,
    table_prefix         TEXT           NULL,
    database_comment     TEXT           NULL,
    modified_date        DATETIME       NOT NULL,
    created_date         DATETIME       NOT NULL,
    CONSTRAINT pk_database_schema_id PRIMARY KEY(id),
    CONSTRAINT uk_database_schema_schema_name UNIQUE(schema_name)
);

CREATE TABLE IF NOT EXISTS tbl_table_data (
    schema_id            BIGINT(20)     NOT NULL,
    id                   BIGINT(20)     NOT NULL,
    table_order          INT(6)         NOT NULL,
    table_name           TEXT           NOT NULL,
    table_comment        TEXT           NULL,
    column_prefix        TEXT           NULL,
    modified_date        DATETIME       NOT NULL,
    created_date         DATETIME       NOT NULL,
    CONSTRAINT pk_table_data_id PRIMARY KEY(id),
    CONSTRAINT uk_table_data_schema_id UNIQUE(schema_id, table_name),
    CONSTRAINT fk_table_data_schema_id_database_schema_id FOREIGN KEY(schema_id) REFERENCES tbl_database_schema(id)
);

CREATE TABLE IF NOT EXISTS tbl_column_data (
    table_id             BIGINT(20)     NOT NULL,
    id                   BIGINT(20)     NOT NULL,
    column_order         INT(3)         NOT NULL,
    column_name          TEXT           NOT NULL,
    data_type            TEXT           NOT NULL,
    is_nullable          BOOLEAN        NOT NULL DEFAULT FALSE,
    have_default         BOOLEAN        NOT NULL DEFAULT FALSE,
    default_value        TEXT           NULL,
    column_comment       TEXT           NULL,
    modified_date        DATETIME       NOT NULL,
    created_date         DATETIME       NOT NULL,
    CONSTRAINT pk_column_data_id PRIMARY KEY(id),
    CONSTRAINT uk_column_data_table_id UNIQUE(table_id, column_name),
    CONSTRAINT fk_column_data_table_id_table_data_id FOREIGN KEY(table_id) REFERENCES tbl_table_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_column_key (
    id                   BIGINT(20)     NOT NULL,
    working_table        BIGINT(20)     NOT NULL,
    main_column          BIGINT(20)     NOT NULL,
    key_type             TEXT           NOT NULL,
    reference_column     BIGINT(20)     NULL,
    key_name             TEXT           NULL,
    unique_name          TEXT           NULL,
    modified_date        DATETIME       NOT NULL,
    created_date         DATETIME       NOT NULL,
    CONSTRAINT pk_column_key_id PRIMARY KEY(id),
    CONSTRAINT fk_column_key_main_column_column_data_id FOREIGN KEY(main_column) REFERENCES tbl_column_data(id),
    CONSTRAINT fk_column_key_reference_column_column_data_id FOREIGN KEY(reference_column) REFERENCES tbl_column_data(id),
    CONSTRAINT fk_column_key_working_table_table_data_id FOREIGN KEY(working_table) REFERENCES tbl_table_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_composite_key (
    key_id               BIGINT(20)     NOT NULL,
    id                   BIGINT(20)     NOT NULL,
    primary_column       BIGINT(20)     NULL,
    composite_column     BIGINT(20)     NULL,
    key_name             TEXT           NULL,
    modified_date        DATETIME       NOT NULL,
    created_date         DATETIME       NOT NULL,
    CONSTRAINT pk_composite_key_id PRIMARY KEY(id),
    CONSTRAINT fk_composite_key_composite_column_column_data_id FOREIGN KEY(composite_column) REFERENCES tbl_column_data(id),
    CONSTRAINT fk_composite_key_key_id_column_key_id FOREIGN KEY(key_id) REFERENCES tbl_column_key(id),
    CONSTRAINT fk_composite_key_primary_column_column_data_id FOREIGN KEY(primary_column) REFERENCES tbl_column_data(id)
);

DELETE FROM tbl_composite_key;
DELETE FROM tbl_column_key;
DELETE FROM tbl_column_data;
DELETE FROM tbl_table_data;
DELETE FROM tbl_database_schema;

-- Delete All Schema Data

DELETE FROM tbl_database_schema
WHERE id <> '174030631877720513';

-- Delete All Table Data

DELETE FROM tbl_table_data
WHERE schema_id IN (
    SELECT id FROM tbl_database_schema
    WHERE id <> '174030631877720513'
);

-- Delete All Column Data

DELETE FROM tbl_column_data
WHERE table_id IN (
    SELECT td.id FROM tbl_table_data td
                          JOIN tbl_database_schema ds ON td.schema_id = ds.id
    WHERE ds.id <> '174030631877720513'
);

-- Delete All Column Key

DELETE FROM tbl_column_key
WHERE id IN (
    SELECT ck.id
    FROM tbl_column_key ck
             JOIN tbl_table_data td ON ck.working_table = td.id
             JOIN tbl_database_schema ds ON td.schema_id = ds.id
    WHERE ds.id <> '174030631877720513'
);

SELECT * FROM tbl_column_key
WHERE id IN (
    SELECT ck.id
    FROM tbl_column_key ck
             JOIN tbl_table_data td ON ck.working_table = td.id
             JOIN tbl_database_schema ds ON td.schema_id = ds.id
    WHERE ds.id <> '174030631877720513'
);

DELETE FROM tbl_column_key
WHERE id IN (
    SELECT ck.id
    FROM tbl_column_key ck
             JOIN tbl_table_data td ON ck.working_table = td.id
             JOIN tbl_database_schema ds ON td.schema_id = ds.id
    WHERE ds.id <> '174030631877720513'
);

-- Delete All Composite Key

DELETE FROM tbl_composite_key
WHERE key_id IN (
    SELECT ck.id
    FROM tbl_column_key ck
             JOIN tbl_table_data td ON ck.working_table = td.id
             JOIN tbl_database_schema ds ON td.schema_id = ds.id
    WHERE ds.id <> '174030631877720513'
);

SELECT * FROM tbl_composite_key
WHERE key_id IN (
    SELECT ck.id
    FROM tbl_composite_key ck
             JOIN tbl_column_key ck_key ON ck.key_id = ck_key.id
             JOIN tbl_table_data td ON ck_key.working_table = td.id
             JOIN tbl_database_schema ds ON td.schema_id = ds.id
    WHERE ds.id <> '174030631877720513'
);

DELETE FROM tbl_composite_key
WHERE key_id IN (
    SELECT ck.id
    FROM tbl_composite_key ck
             JOIN tbl_column_key ck_key ON ck.key_id = ck_key.id
             JOIN tbl_table_data td ON ck_key.working_table = td.id
             JOIN tbl_database_schema ds ON td.schema_id = ds.id
    WHERE ds.id <> '174030631877720513'
);


-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174014882064916708, 'app_sys_database_tables_schema', '1.1.2', 'tbl_', NULL, '2025-02-21 15:40:20', '2025-02-21 15:40:20');

-- Database Schema: Table Schema
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174127673320274090, 1, 'database_schema', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174127673367973233, 2, 'table_data', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174127781474978106, 3, 'column_data', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174127789525086095, 4, 'column_key', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174127792914213459, 5, 'composite_key', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');

-- Database Schema: Column Schema
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673320274090, 174127673336650408, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673320274090, 174127673340744273, 2, 'schema_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673320274090, 174127673341234227, 3, 'schema_version', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673320274090, 174127673341738179, 4, 'table_prefix', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673320274090, 174127673342193400, 5, 'database_comment', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673320274090, 174127673342787976, 6, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673320274090, 174127673343244019, 7, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673368528386, 1, 'schema_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673369340384, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673369878386, 3, 'table_order', 'INT(6)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673370576328, 4, 'table_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673371147503, 5, 'table_comment', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673371995406, 6, 'column_prefix', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673372659235, 7, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127673367973233, 174127673374141011, 8, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781489934910, 1, 'table_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781494156848, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781494590231, 3, 'column_order', 'INT(3)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781494961540, 4, 'column_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781495442289, 5, 'data_type', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781495860669, 6, 'is_nullable', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781496295465, 7, 'have_default', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781496621015, 8, 'default_value', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781497064068, 9, 'column_comment', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781497554771, 10, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127781474978106, 174127781497933798, 11, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:16:54', '2025-03-06 17:16:54');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789540454295, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789544686161, 2, 'working_table', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789545018922, 3, 'main_column', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789545559675, 4, 'key_type', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789545916678, 5, 'reference_column', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789546488496, 6, 'key_name', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789546842413, 7, 'unique_name', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789547385488, 8, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127789525086095, 174127789547872494, 9, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:15', '2025-03-06 17:18:15');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127792914213459, 174127792930286048, 1, 'key_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127792914213459, 174127792934598804, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127792914213459, 174127792934912140, 3, 'primary_column', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127792914213459, 174127792935374841, 4, 'composite_column', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127792914213459, 174127792935842594, 5, 'key_name', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127792914213459, 174127792936247489, 6, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127792914213459, 174127792936839588, 7, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:18:49', '2025-03-06 17:18:49');


-- Database Schema: Column Key Schema
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127673355288012, 174127673320274090, 174127673336650408, 'PRIMARY', NULL, NULL, 'pk>database_schema>id', '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127673367419758, 174127673320274090, 174127673340744273, 'UNIQUE', NULL, NULL, 'uk>database_schema>schema_name', '2025-03-06 16:58:53', '2025-03-06 16:58:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127673388317739, 174127673367973233, 174127673368528386, 'FOREIGN', 174127673336650408, NULL, 'fk>table_data>schema_id>database_schema>id', '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127673375153533, 174127673367973233, 174127673369340384, 'PRIMARY', NULL, NULL, 'pk>table_data>id', '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127673376035940, 174127673367973233, 174127673368528386, 'UNIQUE', NULL, NULL, 'uk>table_data>schema_id+table_name', '2025-03-06 16:58:53', '2025-03-06 16:58:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127781533567748, 174127781474978106, 174127781489934910, 'FOREIGN', 174127673369340384, NULL, 'fk>column_data>table_id>table_data>id', '2025-03-06 17:16:55', '2025-03-06 17:16:55');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127781509252615, 174127781474978106, 174127781494156848, 'PRIMARY', NULL, NULL, 'pk>column_data>id', '2025-03-06 17:16:55', '2025-03-06 17:16:55');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127781520621903, 174127781474978106, 174127781489934910, 'UNIQUE', NULL, NULL, 'uk>column_data>table_id+column_name', '2025-03-06 17:16:55', '2025-03-06 17:16:55');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127789564613444, 174127789525086095, 174127789545018922, 'FOREIGN', 174127781494156848, NULL, 'fk>column_key>main_column>column_data>id', '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127789565140301, 174127789525086095, 174127789545916678, 'FOREIGN', 174127781494156848, NULL, 'fk>column_key>reference_column>column_data>id', '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127789564115260, 174127789525086095, 174127789544686161, 'FOREIGN', 174127673369340384, NULL, 'fk>column_key>working_table>table_data>id', '2025-03-06 17:18:15', '2025-03-06 17:18:15');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127789559638253, 174127789525086095, 174127789540454295, 'PRIMARY', NULL, NULL, 'pk>column_key>id', '2025-03-06 17:18:15', '2025-03-06 17:18:15');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127792953736829, 174127792914213459, 174127792935374841, 'FOREIGN', 174127781494156848, NULL, 'fk>composite_key>composite_column>column_data>id', '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127792954284229, 174127792914213459, 174127792930286048, 'FOREIGN', 174127789540454295, NULL, 'fk>composite_key>key_id>column_key>id', '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127792953213558, 174127792914213459, 174127792934912140, 'FOREIGN', 174127781494156848, NULL, 'fk>composite_key>primary_column>column_data>id', '2025-03-06 17:18:49', '2025-03-06 17:18:49');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127792949067094, 174127792914213459, 174127792934598804, 'PRIMARY', NULL, NULL, 'pk>composite_key>id', '2025-03-06 17:18:49', '2025-03-06 17:18:49');

-- Database Schema: Composite Key Schema
INSERT INTO tbl_composite_key (key_id, id, primary_column, composite_column, key_name, modified_date, created_date) VALUES (174127673376035940, 174127673380295242, 174127673370576328, NULL, NULL, '2025-03-06 16:58:53', '2025-03-06 16:58:53');
INSERT INTO tbl_composite_key (key_id, id, primary_column, composite_column, key_name, modified_date, created_date) VALUES (174127781520621903, 174127781525646627, 174127781494961540, NULL, NULL, '2025-03-06 17:16:55', '2025-03-06 17:16:55');


