
-- SQLite Database DATE CREATED: 2025-02-23, DATE MODIFIED: 2025-03-04 - VERSION: v-1.1.1
-- DATABASE NAME: Book Manager Database Schema



CREATE DATABASE IF NOT EXISTS book_manager_database_schema;
USE book_manager_database_schema;




DROP TABLE IF EXISTS tbl_book_author;
DROP TABLE IF EXISTS tbl_publisher_book;
DROP TABLE IF EXISTS tbl_book_content;
DROP TABLE IF EXISTS tbl_book_content_data;
DROP TABLE IF EXISTS tbl_book_sectioning;
DROP TABLE IF EXISTS tbl_book_data;
DROP TABLE IF EXISTS tbl_language_data;
DROP TABLE IF EXISTS tbl_publisher_data;
DROP TABLE IF EXISTS tbl_author_data;


CREATE TABLE IF NOT EXISTS tbl_language_data (
    id                      BIGINT(20)     NOT NULL,
    iso_code_2              TEXT           NOT NULL,
    iso_code_3              TEXT           NOT NULL,
    name                    TEXT           NOT NULL,
    CONSTRAINT pk_language_data_id PRIMARY KEY(id),
    CONSTRAINT uk_language_data_iso_code_2 UNIQUE(iso_code_2),
    CONSTRAINT uk_language_data_iso_code_3 UNIQUE(iso_code_3)
);

CREATE TABLE IF NOT EXISTS tbl_publisher_data (
    id                      BIGINT(20)     NOT NULL,
    name                    TEXT           NOT NULL,
    country                 TEXT           NULL,
    established_year        DATE           NULL,
    CONSTRAINT pk_publisher_data_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_author_data (
    id                      BIGINT(20)     NOT NULL,
    name                    TEXT           NOT NULL,
    birth_year              DATE           NULL,
    nationality             TEXT           NULL,
    CONSTRAINT pk_author_data_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_data (
    language_id             BIGINT(20)     NOT NULL,
    id                      BIGINT         NOT NULL,
    title                   TEXT           NOT NULL,
    details                 TEXT           NOT NULL,
    original_book_id        BIGINT(20)     NULL,
    publication_year        DATE           NULL,
    CONSTRAINT pk_book_data_id PRIMARY KEY(id),
    CONSTRAINT fk_book_data_language_id_language_data_id FOREIGN KEY(language_id) REFERENCES tbl_language_data(id),
    CONSTRAINT fk_book_data_original_book_id_book_data_id FOREIGN KEY(original_book_id) REFERENCES tbl_book_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_author (
    book_id                 BIGINT(20)     NOT NULL,
    author_id               BIGINT(20)     NOT NULL,
    is_translator           BOOLEAN        NOT NULL DEFAULT FALSE,
    CONSTRAINT fk_book_author_author_id_author_data_id FOREIGN KEY(author_id) REFERENCES tbl_author_data(id),
    CONSTRAINT fk_book_author_book_id_book_data_id FOREIGN KEY(book_id) REFERENCES tbl_book_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_publisher_book (
    publisher_id            BIGINT(20)     NOT NULL,
    book_id                 BIGINT(20)     NOT NULL,
    CONSTRAINT fk_publisher_book_book_id_book_data_id FOREIGN KEY(book_id) REFERENCES tbl_book_data(id),
    CONSTRAINT fk_publisher_book_publisher_id_publisher_data_id FOREIGN KEY(publisher_id) REFERENCES tbl_publisher_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_sectioning (
    book_id                 BIGINT(20)     NOT NULL,
    id                      BIGINT(20)     NOT NULL,
    parent_id               BIGINT(20)     NULL,
    title                   TEXT           NOT NULL,
    order_index             INTEGER        NOT NULL CHECK (order_index >= 1),
    CONSTRAINT pk_book_sectioning_id PRIMARY KEY(id),
    CONSTRAINT fk_book_sectioning_book_id_book_data_id FOREIGN KEY(book_id) REFERENCES tbl_book_data(id),
    CONSTRAINT fk_book_sectioning_parent_id_book_sectioning_id FOREIGN KEY(parent_id) REFERENCES tbl_book_sectioning(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_content_data (
    section_id              BIGINT(20)     NOT NULL,
    id                      BIGINT(20)     NOT NULL,
    title                   TEXT           NULL,
    order_index             INTEGER        NOT NULL CHECK (order_index >= 1),
    printed_page_number     INTEGER        NULL CHECK (page_number >= 1),
    content_type            TEXT           NOT NULL CHECK(content_type IN ('text', 'image', 'video', 'link', 'reference')),
    CONSTRAINT pk_book_content_data_id PRIMARY KEY(id),
    CONSTRAINT fk_book_content_data_section_id_book_sectioning_id FOREIGN KEY(section_id) REFERENCES tbl_book_sectioning(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_content (
    content_data_id         BIGINT(20)     NOT NULL,
    id                      BIGINT(20)     NOT NULL,
    title                   TEXT           NULL,
    content_details         TEXT           NULL,
    order_index             INTEGER        NOT NULL CHECK (order_index >= 1),
    content_type            TEXT           NOT NULL CHECK(content_type IN ('text', 'image', 'video', 'link', 'reference')),
    reference_book_id       BIGINT(20)     NULL,
    CONSTRAINT pk_book_content_id PRIMARY KEY(id),
    CONSTRAINT fk_book_content_content_data_id_book_content_data_id FOREIGN KEY(content_data_id) REFERENCES tbl_book_content_data(id),
    CONSTRAINT fk_book_content_reference_book_id_book_data_id FOREIGN KEY(reference_book_id) REFERENCES tbl_book_data(id)
);



DELETE FROM tbl_book_author;
DELETE FROM tbl_publisher_book;
DELETE FROM tbl_book_content;
DELETE FROM tbl_book_content_data;
DELETE FROM tbl_book_sectioning;
DELETE FROM tbl_book_data;
DELETE FROM tbl_language_data;
DELETE FROM tbl_publisher_data;
DELETE FROM tbl_author_data;

-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174030631877720513, 'book_manager_database_schema', '1.1.1', 'tbl_', NULL, '2025-02-23 11:25:18', '2025-02-23 11:25:18');

-- Database Schema: Table Schema
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076622800313385, 1, 'language_data', NULL, NULL, '2025-02-28 19:10:28', '2025-02-28 19:10:28');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076626121870229, 2, 'publisher_data', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076626151154972, 3, 'author_data', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076626154414906, 4, 'book_data', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076626159786080, 5, 'book_author', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076630991561163, 6, 'publisher_book', NULL, NULL, '2025-02-28 19:11:49', '2025-02-28 19:11:49');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076631024184850, 7, 'book_sectioning', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076631031979978, 8, 'book_content_data', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174076631040416379, 9, 'book_content', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');

-- Database Schema: Column Schema
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076622800313385, 174076622812129908, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:10:28', '2025-02-28 19:10:28');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076622800313385, 174076622815446863, 2, 'iso_code_2', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:10:28', '2025-02-28 19:10:28');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076622800313385, 174076622815967318, 3, 'iso_code_3', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:10:28', '2025-02-28 19:10:28');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076622800313385, 174076622816322457, 4, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:10:28', '2025-02-28 19:10:28');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626121870229, 174076626134472029, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626121870229, 174076626137734962, 2, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626121870229, 174076626138238819, 3, 'country', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626121870229, 174076626138693572, 4, 'established_year', 'DATE', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626151154972, 174076626151531259, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626151154972, 174076626152044863, 2, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626151154972, 174076626152542128, 3, 'birth_year', 'DATE', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626151154972, 174076626153057789, 4, 'nationality', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626154414906, 174076626154867825, 1, 'language_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626154414906, 174076626155365603, 2, 'id', 'BIGINT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626154414906, 174076626156190890, 3, 'title', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626154414906, 174076626156621728, 4, 'details', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626154414906, 174076626157172802, 5, 'original_book_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626154414906, 174076626157695934, 6, 'publication_year', 'DATE', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626159786080, 174076626160237655, 1, 'book_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626159786080, 174076626161121816, 2, 'author_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076626159786080, 174076626161669521, 3, 'is_translator', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076630991561163, 174076631004252571, 1, 'publisher_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076630991561163, 174076631008193965, 2, 'book_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631024184850, 174076631024697220, 1, 'book_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631024184850, 174076631025118121, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631024184850, 174076631025843377, 3, 'parent_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631024184850, 174076631026391223, 4, 'title', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631024184850, 174076631027224211, 5, 'order_index', 'INTEGER', 'FALSE', 'FALSE', 'CHECK (order_index >= 1)', NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631031979978, 174076631033359647, 1, 'section_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631031979978, 174076631036296003, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631031979978, 174076631037113864, 3, 'title', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631031979978, 174076631037644088, 4, 'order_index', 'INTEGER', 'FALSE', 'FALSE', 'CHECK (order_index >= 1)', NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631031979978, 174076631038165485, 5, 'printed_page_number', 'INTEGER', 'TRUE', 'FALSE', 'CHECK (page_number >= 1)', NULL, '2025-03-04 20:20:37', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631031979978, 174076631038854792, 6, 'content_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK(content_type IN (''text'', ''image'', ''video'', ''link'', ''reference''))', NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631040416379, 174076631040919472, 1, 'content_data_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631040416379, 174076631041547304, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631040416379, 174076631042013848, 3, 'title', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631040416379, 174076631042597463, 4, 'content_details', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631040416379, 174076631043260180, 5, 'order_index', 'INTEGER', 'FALSE', 'FALSE', 'CHECK (order_index >= 1)', NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631040416379, 174076631043789292, 6, 'content_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK(content_type IN (''text'', ''image'', ''video'', ''link'', ''reference''))', NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174076631040416379, 174076631044214351, 7, 'reference_book_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-28 19:11:50', '2025-02-28 19:11:50');


-- Database Schema: Column Key Schema
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076622825385399, 174076622800313385, 174076622812129908, 'PRIMARY', NULL, NULL, 'pk>language_data>id', '2025-02-28 19:10:28', '2025-02-28 19:10:28');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076622828535382, 174076622800313385, 174076622815446863, 'UNIQUE', NULL, NULL, 'uk>language_data>iso_code_2', '2025-02-28 19:10:28', '2025-02-28 19:10:28');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076622829079223, 174076622800313385, 174076622815967318, 'UNIQUE', NULL, NULL, 'uk>language_data>iso_code_3', '2025-02-28 19:10:28', '2025-02-28 19:10:28');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076626147817304, 174076626121870229, 174076626134472029, 'PRIMARY', NULL, NULL, 'pk>publisher_data>id', '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076626153966341, 174076626151154972, 174076626151531259, 'PRIMARY', NULL, NULL, 'pk>author_data>id', '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076626158763737, 174076626154414906, 174076626154867825, 'FOREIGN', 174076622812129908, NULL, 'fk>book_data>language_id>language_data>id', '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076626159249834, 174076626154414906, 174076626157172802, 'FOREIGN', 174076626155365603, NULL, 'fk>book_data>original_book_id>book_data>id', '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076626158368429, 174076626154414906, 174076626155365603, 'PRIMARY', NULL, NULL, 'pk>book_data>id', '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076626162136251, 174076626159786080, 174076626161121816, 'FOREIGN', 174076626151531259, NULL, 'fk>book_author>author_id>author_data>id', '2025-02-28 19:11:01', '2025-02-28 19:11:01');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076626162629772, 174076626159786080, 174076626160237655, 'FOREIGN', 174076626155365603, NULL, 'fk>book_author>book_id>book_data>id', '2025-02-28 19:11:01', '2025-02-28 19:11:01');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631019795888, 174076630991561163, 174076631008193965, 'FOREIGN', 174076626155365603, NULL, 'fk>publisher_book>book_id>book_data>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631023679095, 174076630991561163, 174076631004252571, 'FOREIGN', 174076626134472029, NULL, 'fk>publisher_book>publisher_id>publisher_data>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631029776174, 174076631024184850, 174076631024697220, 'FOREIGN', 174076626155365603, NULL, 'fk>book_sectioning>book_id>book_data>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631030270025, 174076631024184850, 174076631025843377, 'FOREIGN', 174076631025118121, NULL, 'fk>book_sectioning>parent_id>book_sectioning>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631027778902, 174076631024184850, 174076631025118121, 'PRIMARY', NULL, NULL, 'pk>book_sectioning>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631039911340, 174076631031979978, 174076631033359647, 'FOREIGN', 174076631025118121, NULL, 'fk>book_content_data>section_id>book_sectioning>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631039490656, 174076631031979978, 174076631036296003, 'PRIMARY', NULL, NULL, 'pk>book_content_data>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631045522012, 174076631040416379, 174076631040919472, 'FOREIGN', 174076631036296003, NULL, 'fk>book_content>content_data_id>book_content_data>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631046028720, 174076631040416379, 174076631044214351, 'FOREIGN', 174076626155365603, NULL, 'fk>book_content>reference_book_id>book_data>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174076631044922962, 174076631040416379, 174076631041547304, 'PRIMARY', NULL, NULL, 'pk>book_content>id', '2025-02-28 19:11:50', '2025-02-28 19:11:50');
