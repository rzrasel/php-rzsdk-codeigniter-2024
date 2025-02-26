
-- SQLite Database DATE CREATED: 2025-02-23, DATE MODIFIED: 2025-02-26 - VERSION: v-1.1.1
-- DATABASE NAME: Book Manager Database Schema


CREATE DATABASE IF NOT EXISTS book_manager_database_schema;
USE book_manager_database_schema;


DROP TABLE IF EXISTS tbl_publisher_book;
DROP TABLE IF EXISTS tbl_publisher_data;
DROP TABLE IF EXISTS tbl_book_author;
DROP TABLE IF EXISTS tbl_author_data;
DROP TABLE IF EXISTS tbl_book_content;
DROP TABLE IF EXISTS tbl_book_sectioning;
DROP TABLE IF EXISTS tbl_book_data;


CREATE TABLE IF NOT EXISTS tbl_publisher_data (
    id                    BIGINT(20)     NOT NULL,
    name                  TEXT           NOT NULL,
    country               TEXT           NULL,
    established_year      DATE           NULL,
    CONSTRAINT pk_publisher_data_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_author_data (
    id                    BIGINT(20)     NOT NULL,
    name                  TEXT           NOT NULL,
    birth_year            DATE           NULL,
    nationality           TEXT           NULL,
    CONSTRAINT pk_author_data_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_data (
    id                    BIGINT         NOT NULL,
    title                 TEXT           NOT NULL,
    original_language     TEXT           NULL,
    publication_year      DATE           NULL,
    CONSTRAINT pk_book_data_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_author (
    book_id               BIGINT(20)     NOT NULL,
    author_id             BIGINT(20)     NOT NULL,
    CONSTRAINT pk_book_author_book_id, author_id PRIMARY KEY(book_id),
    CONSTRAINT fk_book_author_author_id_author_data_id FOREIGN KEY(author_id) REFERENCES tbl_author_data(id),
    CONSTRAINT fk_book_author_book_id_book_data_id FOREIGN KEY(book_id) REFERENCES tbl_book_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_publisher_book (
    publisher_id          BIGINT(20)     NOT NULL,
    book_id               BIGINT(20)     NOT NULL,
    CONSTRAINT pk_publisher_book_publisher_id, book_id PRIMARY KEY(publisher_id),
    CONSTRAINT fk_publisher_book_book_id_book_data_id FOREIGN KEY(book_id) REFERENCES tbl_book_data(id),
    CONSTRAINT fk_publisher_book_publisher_id_publisher_data_id FOREIGN KEY(publisher_id) REFERENCES tbl_publisher_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_sectioning (
    id                    BIGINT(20)     NOT NULL,
    book_id               BIGINT(20)     NOT NULL,
    parent_id             BIGINT(20)     NULL,
    title                 TEXT           NOT NULL,
    order_index           INT(2)         NOT NULL,
    CONSTRAINT pk_book_sectioning_id PRIMARY KEY(id),
    CONSTRAINT fk_book_sectioning_book_id_book_data_id FOREIGN KEY(book_id) REFERENCES tbl_book_data(id),
    CONSTRAINT fk_book_sectioning_parent_id_book_sectioning_id FOREIGN KEY(parent_id) REFERENCES tbl_book_sectioning(id)
);

CREATE TABLE IF NOT EXISTS tbl_book_content (
    section_id            BIGINT(20)     NOT NULL,
    id                    BIGINT(20)     NOT NULL,
    title                 TEXT           NULL,
    content_details       TEXT           NULL,
    order_index           INT(3)         NOT NULL,
    content_type          TEXT           NOT NULL CHECK(content_type IN ('text', 'image', 'video', 'link', 'reference')),
    reference_book_id     BIGINT(20)     NULL,
    CONSTRAINT pk_book_content_id PRIMARY KEY(id),
    CONSTRAINT fk_book_content_reference_book_id_book_data_id FOREIGN KEY(reference_book_id) REFERENCES tbl_book_data(id),
    CONSTRAINT fk_book_content_section_id_book_sectioning_id FOREIGN KEY(section_id) REFERENCES tbl_book_sectioning(id)
);


DELETE FROM tbl_publisher_book;
DELETE FROM tbl_publisher_data;
DELETE FROM tbl_book_author;
DELETE FROM tbl_author_data;
DELETE FROM tbl_book_content;
DELETE FROM tbl_book_sectioning;
DELETE FROM tbl_book_data;

-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174030631877720513, 'book_manager_database_schema', '1.1.1', 'tbl_', NULL, '2025-02-23 11:25:18', '2025-02-23 11:25:18');

-- Database Schema: Table Schema
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174030641837643143, 1, 'publisher_data', NULL, NULL, '2025-02-23 11:26:58', '2025-02-23 11:26:58');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174030643191767882, 2, 'author_data', NULL, NULL, '2025-02-23 11:27:11', '2025-02-23 11:27:11');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174030644272297324, 3, 'book_data', NULL, NULL, '2025-02-23 11:27:22', '2025-02-23 11:27:22');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174030645821179830, 4, 'book_author', NULL, NULL, '2025-02-23 11:27:38', '2025-02-23 11:27:38');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174030653233781388, 5, 'publisher_book', NULL, NULL, '2025-02-23 11:28:52', '2025-02-23 11:28:52');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174030655348579135, 6, 'book_sectioning', NULL, NULL, '2025-02-23 11:29:13', '2025-02-23 11:29:13');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174030631877720513, 174030658345783223, 7, 'book_content', NULL, NULL, '2025-02-23 11:29:43', '2025-02-23 11:29:43');

-- Database Schema: Column Schema
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030641837643143, 174030661396261006, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:30:13', '2025-02-23 11:30:13');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030641837643143, 174030664990458800, 2, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-24 17:44:26', '2025-02-23 11:30:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030641837643143, 174030666191349906, 3, 'country', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 11:32:28', '2025-02-23 11:31:01');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030641837643143, 174030671732833049, 4, 'established_year', 'DATE', 'TRUE', 'FALSE', NULL, NULL, '2025-02-24 17:52:49', '2025-02-23 11:31:57');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030643191767882, 174030683213659855, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:33:52', '2025-02-23 11:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030643191767882, 174030685252572557, 2, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:34:12', '2025-02-23 11:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030643191767882, 174030687381666929, 3, 'birth_year', 'DATE', 'TRUE', 'FALSE', NULL, NULL, '2025-02-24 17:53:54', '2025-02-23 11:34:33');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030643191767882, 174030688606093569, 4, 'nationality', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 11:34:46', '2025-02-23 11:34:46');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030644272297324, 174030692866272863, 1, 'id', 'BIGINT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:35:28', '2025-02-23 11:35:28');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030644272297324, 174030695130025719, 2, 'title', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:35:51', '2025-02-23 11:35:51');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030644272297324, 174030697545760343, 3, 'original_language', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 11:36:15', '2025-02-23 11:36:15');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030644272297324, 174030699249260510, 4, 'publication_year', 'DATE', 'TRUE', 'FALSE', NULL, NULL, '2025-02-24 17:54:43', '2025-02-23 11:36:32');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030645821179830, 174030705956694213, 1, 'book_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:37:39', '2025-02-23 11:37:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030645821179830, 174030707359935714, 2, 'author_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:37:53', '2025-02-23 11:37:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030653233781388, 174030711225950703, 1, 'publisher_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:39:03', '2025-02-23 11:38:32');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030653233781388, 174030709295347734, 2, 'book_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:39:15', '2025-02-23 11:38:12');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030655348579135, 174030723575269660, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:40:35', '2025-02-23 11:40:35');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030655348579135, 174030725271826152, 2, 'book_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:40:52', '2025-02-23 11:40:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030655348579135, 174030726941218075, 3, 'parent_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 11:41:09', '2025-02-23 11:41:09');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030655348579135, 174030728586862463, 4, 'title', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:41:25', '2025-02-23 11:41:25');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030655348579135, 174030731683638505, 5, 'order_index', 'INT(2)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 11:41:56', '2025-02-23 11:41:56');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030658345783223, 174030741702172628, 1, 'section_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-24 13:33:44', '2025-02-23 11:43:37');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030658345783223, 174030739608892870, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-24 13:33:56', '2025-02-23 11:43:16');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030658345783223, 174040032099813072, 3, 'title', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-24 17:47:53', '2025-02-24 13:32:00');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030658345783223, 174030746666157244, 4, 'content_details', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-24 17:56:55', '2025-02-23 11:44:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030658345783223, 174040029830373482, 5, 'order_index', 'INT(3)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-24 17:48:11', '2025-02-24 13:31:38');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030658345783223, 174030745221817914, 6, 'content_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK(content_type IN (''text'', ''image'', ''video'', ''link'', ''reference''))', NULL, '2025-02-24 13:36:06', '2025-02-23 11:44:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030658345783223, 174030749840218920, 7, 'reference_book_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-24 13:36:16', '2025-02-23 11:44:58');


-- Database Schema: Column Key Schema
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030757644194680, 174030641837643143, 174030661396261006, 'PRIMARY', NULL, NULL, 'pk>book_manager_database_schema>publisher_data>id', '2025-02-23 11:46:16', '2025-02-23 11:46:16');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030760851243575, 174030643191767882, 174030683213659855, 'PRIMARY', NULL, NULL, 'pk>book_manager_database_schema>author_data>id', '2025-02-23 11:46:48', '2025-02-23 11:46:48');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030764215784859, 174030644272297324, 174030692866272863, 'PRIMARY', NULL, NULL, 'pk>book_manager_database_schema>book_data>id', '2025-02-23 11:47:22', '2025-02-23 11:47:22');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030794412017640, 174030645821179830, 174030707359935714, 'FOREIGN', 174030683213659855, NULL, 'fk>book_manager_database_schema>book_author>author_id>author_data>id', '2025-02-23 11:52:24', '2025-02-23 11:52:24');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030788567928341, 174030645821179830, 174030705956694213, 'FOREIGN', 174030692866272863, NULL, 'fk>book_manager_database_schema>book_author>book_id>book_data>id', '2025-02-23 11:51:25', '2025-02-23 11:51:25');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030769833152419, 174030645821179830, 174030705956694213, 'PRIMARY', NULL, NULL, 'pk>book_manager_database_schema>book_author>book_id', '2025-02-23 11:48:18', '2025-02-23 11:48:18');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030821118137651, 174030653233781388, 174030709295347734, 'FOREIGN', 174030692866272863, NULL, 'fk>book_manager_database_schema>publisher_book>book_id>book_data>id', '2025-02-23 11:56:51', '2025-02-23 11:56:51');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030824317560889, 174030653233781388, 174030711225950703, 'FOREIGN', 174030661396261006, NULL, 'fk>book_manager_database_schema>publisher_book>publisher_id>publisher_data>id', '2025-02-23 11:57:23', '2025-02-23 11:57:23');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030799908239686, 174030653233781388, 174030711225950703, 'PRIMARY', NULL, NULL, 'pk>book_manager_database_schema>publisher_book>publisher_id', '2025-02-23 11:53:19', '2025-02-23 11:53:19');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030832886953828, 174030655348579135, 174030725271826152, 'FOREIGN', 174030692866272863, NULL, 'fk>book_manager_database_schema>book_sectioning>book_id>book_data>id', '2025-02-23 11:58:48', '2025-02-23 11:58:48');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030836261260588, 174030655348579135, 174030726941218075, 'FOREIGN', 174030723575269660, NULL, 'fk>book_manager_database_schema>book_sectioning>parent_id>book_sectioning>id', '2025-02-23 11:59:22', '2025-02-23 11:59:22');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030829842694561, 174030655348579135, 174030723575269660, 'PRIMARY', NULL, NULL, 'pk>book_manager_database_schema>book_sectioning>id', '2025-02-23 11:58:18', '2025-02-23 11:58:18');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030846291858111, 174030658345783223, 174030749840218920, 'FOREIGN', 174030692866272863, NULL, 'fk>book_manager_database_schema>book_content>reference_book_id>book_data>id', '2025-02-23 12:01:02', '2025-02-23 12:01:02');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030842244970849, 174030658345783223, 174030741702172628, 'FOREIGN', 174030723575269660, NULL, 'fk>book_manager_database_schema>book_content>section_id>book_sectioning>id', '2025-02-23 12:00:22', '2025-02-23 12:00:22');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030839407214586, 174030658345783223, 174030739608892870, 'PRIMARY', NULL, NULL, 'pk>book_manager_database_schema>book_content>id', '2025-02-23 11:59:54', '2025-02-23 11:59:54');

-- Database Schema: Composite Key Schema
INSERT INTO tbl_composite_key (key_id, id, primary_column, composite_column, key_name, modified_date, created_date) VALUES (174030769833152419, 174030778126412953, 174030707359935714, NULL, NULL, '2025-02-23 11:49:41', '2025-02-23 11:49:41');
INSERT INTO tbl_composite_key (key_id, id, primary_column, composite_column, key_name, modified_date, created_date) VALUES (174030799908239686, 174030810058430171, 174030709295347734, NULL, NULL, '2025-02-23 11:55:00', '2025-02-23 11:55:00');

