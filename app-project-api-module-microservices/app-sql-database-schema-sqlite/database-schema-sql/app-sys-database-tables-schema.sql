
-- SQLite Database DATE CREATED: 2025-02-21, DATE MODIFIED: 2025-02-26 - VERSION: v-1.1.1
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
    CONSTRAINT uk_table_data_schema_id, table_name UNIQUE(schema_id),
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
    CONSTRAINT uk_column_data_table_id, column_name UNIQUE(table_id),
    CONSTRAINT fk_column_data_table_id_column_data_id FOREIGN KEY(table_id) REFERENCES tbl_column_data(id)
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
    modified_date        BIGINT(20)     NOT NULL,
    created_date         BIGINT(20)     NOT NULL,
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
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174014882064916708, 'app_sys_database_tables_schema', '1.1.1', 'tbl_', NULL, '2025-02-21 15:40:20', '2025-02-21 15:40:20');

-- Database Schema: Table Schema
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174014889470361491, 1, 'database_schema', NULL, NULL, '2025-02-21 15:41:34', '2025-02-21 15:41:34');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174014890922675288, 2, 'table_data', NULL, NULL, '2025-02-21 15:41:49', '2025-02-21 15:41:49');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174014892027133792, 3, 'column_data', NULL, NULL, '2025-02-21 15:42:00', '2025-02-21 15:42:00');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174014893022177300, 4, 'column_key', NULL, NULL, '2025-02-21 15:42:10', '2025-02-21 15:42:10');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174014882064916708, 174014894109899623, 5, 'composite_key', NULL, NULL, '2025-02-21 15:42:21', '2025-02-21 15:42:21');

-- Database Schema: Column Schema
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014889470361491, 174014899824882160, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 08:09:15', '2025-02-21 15:43:18');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014889470361491, 174014931181734504, 2, 'schema_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:48:31', '2025-02-21 15:48:31');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014889470361491, 174014942236816640, 3, 'schema_version', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 15:50:22', '2025-02-21 15:50:22');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014889470361491, 174014944348716144, 4, 'table_prefix', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 15:50:43', '2025-02-21 15:50:43');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014889470361491, 174014946828038847, 5, 'database_comment', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 15:51:08', '2025-02-21 15:51:08');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014889470361491, 174014949130678452, 6, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:51:31', '2025-02-21 15:51:31');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014889470361491, 174014950703994505, 7, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:51:47', '2025-02-21 15:51:47');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014958686966697, 1, 'schema_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:53:06', '2025-02-21 15:53:06');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014960210334464, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:53:22', '2025-02-21 15:53:22');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014986119713813, 3, 'table_order', 'INT(6)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:57:41', '2025-02-21 15:57:41');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014987802497294, 4, 'table_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:57:58', '2025-02-21 15:57:58');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014989148989580, 5, 'table_comment', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 15:58:11', '2025-02-21 15:58:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014991669750708, 6, 'column_prefix', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 15:58:36', '2025-02-21 15:58:36');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014993134039902, 7, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:58:51', '2025-02-21 15:58:51');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014890922675288, 174014994447850835, 8, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 15:59:04', '2025-02-21 15:59:04');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015006706570623, 1, 'table_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:01:07', '2025-02-21 16:01:07');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015008248238218, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:01:22', '2025-02-21 16:01:22');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015011918437827, 3, 'column_order', 'INT(3)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:01:59', '2025-02-21 16:01:59');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015013482471373, 4, 'column_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:02:14', '2025-02-21 16:02:14');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015015256392490, 5, 'data_type', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:02:32', '2025-02-21 16:02:32');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015020008796574, 6, 'is_nullable', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-02-21 16:03:20', '2025-02-21 16:03:20');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015024601987290, 7, 'have_default', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-02-21 16:04:06', '2025-02-21 16:04:06');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015027362451547, 8, 'default_value', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:04:33', '2025-02-21 16:04:33');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015029082991153, 9, 'column_comment', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:04:50', '2025-02-21 16:04:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015030576910830, 10, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:05:05', '2025-02-21 16:05:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014892027133792, 174015031935983821, 11, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:05:19', '2025-02-21 16:05:19');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015138131696501, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:23:01', '2025-02-21 16:23:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015140646075688, 2, 'working_table', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:23:26', '2025-02-21 16:23:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015142708493784, 3, 'main_column', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:23:47', '2025-02-21 16:23:47');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015144417795095, 4, 'key_type', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:24:04', '2025-02-21 16:24:04');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015147021859978, 5, 'reference_column', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:24:30', '2025-02-21 16:24:30');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015148721226040, 6, 'key_name', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:24:47', '2025-02-21 16:24:47');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015151100428886, 7, 'unique_name', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:25:11', '2025-02-21 16:25:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015152779221891, 8, 'modified_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:25:27', '2025-02-21 16:25:27');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014893022177300, 174015154299280705, 9, 'created_date', 'DATETIME', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:25:42', '2025-02-21 16:25:42');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014894109899623, 174015161551661710, 1, 'key_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:26:55', '2025-02-21 16:26:55');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014894109899623, 174015163178970763, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:27:11', '2025-02-21 16:27:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014894109899623, 174015165127572142, 3, 'primary_column', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:27:31', '2025-02-21 16:27:31');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014894109899623, 174015166971935485, 4, 'composite_column', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:27:49', '2025-02-21 16:27:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014894109899623, 174015168884590600, 5, 'key_name', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-21 16:28:08', '2025-02-21 16:28:08');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014894109899623, 174015170900013400, 6, 'modified_date', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:28:29', '2025-02-21 16:28:29');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174014894109899623, 174015171962364606, 7, 'created_date', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-21 16:28:39', '2025-02-21 16:28:39');


-- Database Schema: Column Key Schema
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015183331054568, 174014889470361491, 174014899824882160, 'PRIMARY', NULL, NULL, 'pk>app-sys-database-tables-schema>database_schema>id', '2025-02-21 16:30:33', '2025-02-21 16:30:33');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015187446540512, 174014889470361491, 174014931181734504, 'UNIQUE', NULL, NULL, 'uk>app-sys-database-tables-schema>database_schema>schema_name', '2025-02-21 16:31:14', '2025-02-21 16:31:14');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015204780389338, 174014890922675288, 174014958686966697, 'FOREIGN', 174014899824882160, NULL, 'fk>app-sys-database-tables-schema>table_data>schema_id>database_schema>id', '2025-02-21 16:34:07', '2025-02-21 16:34:07');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015192784666443, 174014890922675288, 174014960210334464, 'PRIMARY', NULL, NULL, 'pk>app-sys-database-tables-schema>table_data>id', '2025-02-21 16:32:07', '2025-02-21 16:32:07');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015199366285681, 174014890922675288, 174014958686966697, 'UNIQUE', NULL, NULL, 'uk>app-sys-database-tables-schema>table_data>schema_id', '2025-02-21 16:33:13', '2025-02-21 16:33:13');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015541789850427, 174014892027133792, 174015006706570623, 'FOREIGN', 174015008248238218, NULL, 'fk>app-sys-database-tables-schema>column_data>table_id>column_data>id', '2025-02-21 17:30:17', '2025-02-21 17:30:17');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015521394546274, 174014892027133792, 174015008248238218, 'PRIMARY', NULL, NULL, 'pk>app-sys-database-tables-schema>column_data>id', '2025-02-21 17:26:53', '2025-02-21 17:26:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015536490839656, 174014892027133792, 174015006706570623, 'UNIQUE', NULL, NULL, 'uk>app-sys-database-tables-schema>column_data>table_id', '2025-02-21 17:29:24', '2025-02-21 17:29:24');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015581624297811, 174014893022177300, 174015142708493784, 'FOREIGN', 174015008248238218, NULL, 'fk>app-sys-database-tables-schema>column_key>main_column>column_data>id', '2025-02-21 17:36:56', '2025-02-21 17:36:56');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015600831167114, 174014893022177300, 174015147021859978, 'FOREIGN', 174015008248238218, NULL, 'fk>app-sys-database-tables-schema>column_key>reference_column>column_data>id', '2025-02-21 17:40:08', '2025-02-21 17:40:08');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015558956065202, 174014893022177300, 174015140646075688, 'FOREIGN', 174014960210334464, NULL, 'fk>app-sys-database-tables-schema>column_key>working_table>table_data>id', '2025-02-21 17:33:09', '2025-02-21 17:33:09');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015518193817655, 174014893022177300, 174015138131696501, 'PRIMARY', NULL, NULL, 'pk>app-sys-database-tables-schema>column_key>id', '2025-02-21 17:26:21', '2025-02-21 17:26:21');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015741467188820, 174014894109899623, 174015166971935485, 'FOREIGN', 174015008248238218, NULL, 'fk>app-sys-database-tables-schema>composite_key>composite_column>column_data>id', '2025-02-21 18:03:34', '2025-02-21 18:03:34');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015744410293242, 174014894109899623, 174015161551661710, 'FOREIGN', 174015138131696501, NULL, 'fk>app-sys-database-tables-schema>composite_key>key_id>column_key>id', '2025-02-21 18:04:04', '2025-02-21 18:04:04');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015737047712089, 174014894109899623, 174015165127572142, 'FOREIGN', 174015008248238218, NULL, 'fk>app-sys-database-tables-schema>composite_key>primary_column>column_data>id', '2025-02-21 18:02:50', '2025-02-21 18:02:50');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174015531348055770, 174014894109899623, 174015163178970763, 'PRIMARY', NULL, NULL, 'pk>app-sys-database-tables-schema>composite_key>id', '2025-02-21 17:28:33', '2025-02-21 17:28:33');

-- Database Schema: Composite Key Schema
INSERT INTO tbl_composite_key (key_id, id, primary_column, composite_column, key_name, modified_date, created_date) VALUES (174015199366285681, 174015212926532680, 174014987802497294, NULL, NULL, '2025-02-21 16:35:29', '2025-02-21 16:35:29');
INSERT INTO tbl_composite_key (key_id, id, primary_column, composite_column, key_name, modified_date, created_date) VALUES (174015536490839656, 174015549468946593, 174015013482471373, NULL, NULL, '2025-02-21 17:31:34', '2025-02-21 17:31:34');

