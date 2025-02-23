



CREATE DATABASE IF NOT EXISTS app_sys_database_tables_schema;
USE app_sys_database_tables_schema;




DROP TABLE IF EXISTS column_data;
DROP TABLE IF EXISTS database_schema;
DROP TABLE IF EXISTS column_key;
DROP TABLE IF EXISTS table_data;
DROP TABLE IF EXISTS composite_key;


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
    CONSTRAINT uk_table_data_schema_id_table_name UNIQUE(schema_id, table_name),
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
    CONSTRAINT uk_column_data_table_id_column_name UNIQUE(table_id, column_name),
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



DELETE FROM column_data;
DELETE FROM database_schema;
DELETE FROM column_key;
DELETE FROM table_data;
DELETE FROM composite_key;






