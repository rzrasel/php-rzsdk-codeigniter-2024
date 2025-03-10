
-- SQLite Database DATE CREATED: 2025-02-23, DATE MODIFIED: 2025-03-06 - VERSION: v-1.1.1
-- DATABASE NAME: Quiz Manager Database Schema



CREATE DATABASE IF NOT EXISTS quiz_manager_database_schema;
USE quiz_manager_database_schema;




DROP TABLE IF EXISTS tbl_media_question_mapping;
DROP TABLE IF EXISTS tbl_answer_bank;
DROP TABLE IF EXISTS tbl_quiz_attempt_detail;
DROP TABLE IF EXISTS tbl_mcq_option;
DROP TABLE IF EXISTS tbl_quiz_question;
DROP TABLE IF EXISTS tbl_question_bank;
DROP TABLE IF EXISTS tbl_quiz_scoring;
DROP TABLE IF EXISTS tbl_quiz_reward;
DROP TABLE IF EXISTS tbl_quiz_data;
DROP TABLE IF EXISTS tbl_chapter_data;
DROP TABLE IF EXISTS tbl_class_subject_mapping;
DROP TABLE IF EXISTS tbl_subject_data;
DROP TABLE IF EXISTS tbl_language_data;
DROP TABLE IF EXISTS tbl_class_data;
DROP TABLE IF EXISTS tbl_media_data;
DROP TABLE IF EXISTS tbl_quiz_attempt;


CREATE TABLE IF NOT EXISTS tbl_language_data (
    id                       BIGINT(20)     NOT NULL,
    name                     TEXT           NOT NULL,
    iso_code_2               TEXT           NOT NULL,
    iso_code_3               TEXT           NOT NULL,
    CONSTRAINT pk_language_data_id PRIMARY KEY(id),
    CONSTRAINT uk_language_data_iso_code_2 UNIQUE(iso_code_2),
    CONSTRAINT uk_language_data_iso_code_3 UNIQUE(iso_code_3)
);

CREATE TABLE IF NOT EXISTS tbl_subject_data (
    language_id              BIGINT(20)     NOT NULL,
    id                       BIGINT(20)     NOT NULL,
    name                     TEXT           NOT NULL,
    description              TEXT           NULL,
    subject_code             TEXT           NOT NULL,
    subject_identity         TEXT           NOT NULL,
    CONSTRAINT pk_subject_data_id PRIMARY KEY(id),
    CONSTRAINT uk_subject_data_subject_code UNIQUE(subject_code),
    CONSTRAINT uk_subject_data_subject_identity UNIQUE(subject_identity),
    CONSTRAINT fk_subject_data_language_id_language_data_id FOREIGN KEY(language_id) REFERENCES tbl_language_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_chapter_data (
    subject_id               BIGINT(20)     NOT NULL,
    id                       BIGINT(20)     NOT NULL,
    name                     TEXT           NOT NULL,
    index_order              INTEGER        NOT NULL,
    chapter_identity         TEXT           NOT NULL,
    CONSTRAINT pk_chapter_data_id PRIMARY KEY(id),
    CONSTRAINT uk_chapter_data_chapter_identity UNIQUE(chapter_identity),
    CONSTRAINT fk_chapter_data_subject_id_subject_data_id FOREIGN KEY(subject_id) REFERENCES tbl_subject_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_class_data (
    id                       BIGINT(20)     NOT NULL,
    class_name               TEXT           NOT NULL,
    class_code               TEXT           NOT NULL,
    description              TEXT           NULL,
    index_order              INTEGER        NOT NULL,
    CONSTRAINT pk_class_data_id PRIMARY KEY(id),
    CONSTRAINT uk_class_data_class_code UNIQUE(class_code)
);

CREATE TABLE IF NOT EXISTS tbl_class_subject_mapping (
    id                       BIGINT(20)     NOT NULL,
    class_id                 BIGINT(20)     NOT NULL,
    subject_id               BIGINT(20)     NOT NULL,
    CONSTRAINT pk_class_subject_mapping_id PRIMARY KEY(id),
    CONSTRAINT uk_class_subject_mapping_class_id UNIQUE(class_id, subject_id),
    CONSTRAINT fk_class_subject_mapping_class_id_class_data_id FOREIGN KEY(class_id) REFERENCES tbl_class_data(id),
    CONSTRAINT fk_class_subject_mapping_subject_id_subject_data_id FOREIGN KEY(subject_id) REFERENCES tbl_subject_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_media_data (
    id                       BIGINT(20)     NOT NULL,
    media_type               TEXT           NOT NULL CHECK (media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link')),
    media_url                TEXT           NULL,
    CONSTRAINT pk_media_data_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_question_bank (
    id                       BIGINT(20)     NOT NULL,
    subject_id               BIGINT(20)     NOT NULL,
    chapter_id               BIGINT(20)     NULL,
    media_id                 BIGINT(20)     NULL,
    question_text            TEXT           NOT NULL,
    question_media_type      TEXT           NOT NULL CHECK (question_media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link')),
    question_media_url       TEXT           NULL,
    difficulty_level         TEXT           NOT NULL CHECK (difficulty_level IN ('Easy', 'Medium', 'Hard')),
    experience_level         TEXT           NOT NULL CHECK (experience_level IN ('Beginner', 'Experienced', 'Advanced')),
    question_type            TEXT           NOT NULL CHECK (question_type IN ('MCQ', 'Short Answer', 'Broad Answer', 'Paragraph Answer')),
    CONSTRAINT pk_question_bank_id PRIMARY KEY(id),
    CONSTRAINT fk_question_bank_chapter_id_chapter_data_id FOREIGN KEY(chapter_id) REFERENCES tbl_chapter_data(id),
    CONSTRAINT fk_question_bank_media_id_media_data_id FOREIGN KEY(media_id) REFERENCES tbl_media_data(id),
    CONSTRAINT fk_question_bank_subject_id_subject_data_id FOREIGN KEY(subject_id) REFERENCES tbl_subject_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_media_question_mapping (
    id                       BIGINT(20)     NOT NULL,
    media_id                 BIGINT(20)     NOT NULL,
    question_id              BIGINT(20)     NOT NULL,
    CONSTRAINT pk_media_question_mapping_id PRIMARY KEY(id),
    CONSTRAINT fk_media_question_mapping_media_id_media_data_id FOREIGN KEY(media_id) REFERENCES tbl_media_data(id),
    CONSTRAINT fk_media_question_mapping_question_id_question_bank_id FOREIGN KEY(question_id) REFERENCES tbl_question_bank(id)
);

CREATE TABLE IF NOT EXISTS tbl_answer_bank (
    id                       BIGINT(20)     NOT NULL,
    question_id              BIGINT(20)     NOT NULL,
    answer_text              TEXT           NULL,
    answer_media_type        TEXT           NOT NULL CHECK (answer_media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link')),
    answer_media_url         TEXT           NULL,
    is_correct               BOOLEAN        NOT NULL DEFAULT FALSE,
    CONSTRAINT pk_answer_bank_id PRIMARY KEY(id),
    CONSTRAINT fk_answer_bank_question_id_question_bank_id FOREIGN KEY(question_id) REFERENCES tbl_question_bank(id)
);

CREATE TABLE IF NOT EXISTS tbl_mcq_option (
    id                       BIGINT(20)     NOT NULL,
    question_id              BIGINT(20)     NOT NULL,
    option_text              TEXT           NOT NULL,
    option_media_type        TEXT           NOT NULL CHECK (option_media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link')),
    option_media_url         TEXT           NULL,
    is_correct               BOOLEAN        NOT NULL DEFAULT FALSE,
    CONSTRAINT pk_mcq_option_id PRIMARY KEY(id),
    CONSTRAINT fk_mcq_option_question_id_question_bank_id FOREIGN KEY(question_id) REFERENCES tbl_question_bank(id)
);

CREATE TABLE IF NOT EXISTS tbl_quiz_data (
    id                       BIGINT(20)     NOT NULL,
    subject_id               BIGINT(20)     NULL,
    chapter_id               BIGINT         NULL,
    quiz_name                TEXT           NOT NULL,
    quiz_type                TEXT           NOT NULL CHECK (quiz_type IN ('Mixed', 'Single Subject', 'Single Chapter')),
    CONSTRAINT pk_quiz_data_id PRIMARY KEY(id),
    CONSTRAINT fk_quiz_data_chapter_id_chapter_data_id FOREIGN KEY(chapter_id) REFERENCES tbl_chapter_data(id),
    CONSTRAINT fk_quiz_data_subject_id_subject_data_id FOREIGN KEY(subject_id) REFERENCES tbl_subject_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_quiz_question (
    id                       BIGINT(20)     NOT NULL,
    quiz_id                  BIGINT         NOT NULL,
    question_id              BIGINT(20)     NOT NULL,
    CONSTRAINT pk_quiz_question_id PRIMARY KEY(id),
    CONSTRAINT fk_quiz_question_question_id_question_bank_id FOREIGN KEY(question_id) REFERENCES tbl_question_bank(id),
    CONSTRAINT fk_quiz_question_quiz_id_quiz_data_id FOREIGN KEY(quiz_id) REFERENCES tbl_quiz_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_quiz_scoring (
    id                       BIGINT(20)     NOT NULL,
    quiz_id                  BIGINT(20)     NOT NULL,
    points_per_correct       INT            NOT NULL DEFAULT 1,
    time_bonus_per_sec       INT            NOT NULL DEFAULT 0,
    time_penalty_per_sec     INT            NOT NULL DEFAULT 0,
    total_marks              INT            NOT NULL,
    passing_marks            INT            NOT NULL,
    CONSTRAINT pk_quiz_scoring_id PRIMARY KEY(id),
    CONSTRAINT fk_quiz_scoring_quiz_id_quiz_data_id FOREIGN KEY(quiz_id) REFERENCES tbl_quiz_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_quiz_attempt (
    id                       BIGINT(20)     NOT NULL,
    user_id                  BIGINT(20)     NOT NULL,
    quiz_id                  BIGINT(20)     NOT NULL,
    total_score              INTEGER        NOT NULL DEFAULT 0,
    total_marks              INT            NOT NULL,
    total_time_taken         INT            NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS tbl_quiz_attempt_detail (
    id                       BIGINT(20)     NOT NULL,
    attempt_id               BIGINT(20)     NOT NULL,
    question_id              BIGINT(20)     NOT NULL,
    selected_option_id       BIGINT(20)     NULL,
    is_correct               BOOLEAN        NOT NULL DEFAULT FALSE,
    time_taken               INT            NOT NULL,
    points_awarded           INT            NOT NULL DEFAULT 0,
    CONSTRAINT pk_quiz_attempt_detail_id PRIMARY KEY(id),
    CONSTRAINT fk_quiz_attempt_detail_attempt_id_quiz_attempt_id FOREIGN KEY(attempt_id) REFERENCES tbl_quiz_attempt(id),
    CONSTRAINT fk_quiz_attempt_detail_question_id_question_bank_id FOREIGN KEY(question_id) REFERENCES tbl_question_bank(id),
    CONSTRAINT fk_quiz_attempt_detail_selected_option_id_mcq_option_id FOREIGN KEY(selected_option_id) REFERENCES tbl_mcq_option(id)
);

CREATE TABLE IF NOT EXISTS tbl_quiz_reward (
    id                       BIGINT(20)     NOT NULL,
    quiz_id                  BIGINT(20)     NOT NULL,
    min_score                INT            NOT NULL,
    reward_name              TEXT           NOT NULL,
    reward_description       TEXT           NULL,
    CONSTRAINT pk_quiz_reward_id PRIMARY KEY(id),
    CONSTRAINT fk_quiz_reward_quiz_id_quiz_data_id FOREIGN KEY(quiz_id) REFERENCES tbl_quiz_data(id)
);



DELETE FROM tbl_media_question_mapping;
DELETE FROM tbl_answer_bank;
DELETE FROM tbl_quiz_attempt_detail;
DELETE FROM tbl_mcq_option;
DELETE FROM tbl_quiz_question;
DELETE FROM tbl_question_bank;
DELETE FROM tbl_quiz_scoring;
DELETE FROM tbl_quiz_reward;
DELETE FROM tbl_quiz_data;
DELETE FROM tbl_chapter_data;
DELETE FROM tbl_class_subject_mapping;
DELETE FROM tbl_subject_data;
DELETE FROM tbl_language_data;
DELETE FROM tbl_class_data;
DELETE FROM tbl_media_data;
DELETE FROM tbl_quiz_attempt;


-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174029832702120927, 'quiz_manager_database_schema', '1.1.1', 'tbl_', NULL, '2025-02-23 09:12:07', '2025-02-23 09:12:07');

-- Database Schema: Table Schema
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883234124406, 1, 'language_data', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883273567132, 2, 'subject_data', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883279871252, 3, 'chapter_data', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883285017287, 4, 'class_data', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883289362015, 5, 'class_subject_mapping', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883306470710, 6, 'media_data', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883309340035, 7, 'question_bank', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883318035409, 8, 'media_question_mapping', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127883321924167, 9, 'answer_bank', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127885141822821, 10, 'mcq_option', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127885180368596, 11, 'quiz_data', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127885185891316, 12, 'quiz_question', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127885189861468, 13, 'quiz_scoring', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127885196021038, 14, 'quiz_attempt', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127885199811141, 15, 'quiz_attempt_detail', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174127885206924068, 16, 'quiz_reward', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');

-- Database Schema: Column Schema
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883234124406, 174127883249830411, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883234124406, 174127883254444178, 2, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883234124406, 174127883255030104, 3, 'iso_code_2', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883234124406, 174127883255534099, 4, 'iso_code_3', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883273567132, 174127883274080943, 1, 'language_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883273567132, 174127883274539311, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883273567132, 174127883275122040, 3, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883273567132, 174127883275662405, 4, 'description', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883273567132, 174127883276333374, 5, 'subject_code', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883273567132, 174127883276965626, 6, 'subject_identity', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883279871252, 174127883280363900, 1, 'subject_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883279871252, 174127883280899756, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883279871252, 174127883281725086, 3, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883279871252, 174127883282268795, 4, 'index_order', 'INTEGER', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883279871252, 174127883282757113, 5, 'chapter_identity', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883285017287, 174127883285649675, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883285017287, 174127883286266224, 2, 'class_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883285017287, 174127883286856630, 3, 'class_code', 'TEXT', 'FALSE', 'FALSE', 'UNIQUE', NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883285017287, 174127883287397857, 4, 'description', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883285017287, 174127883287811544, 5, 'index_order', 'INTEGER', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883289362015, 174127883290195027, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883289362015, 174127883290686229, 2, 'class_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883289362015, 174127883291226933, 3, 'subject_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883306470710, 174127883307081972, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883306470710, 174127883307655739, 2, 'media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883306470710, 174127883308243033, 3, 'media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883310011923, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883310674696, 2, 'subject_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883311178293, 3, 'chapter_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883311936209, 4, 'media_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883312513348, 5, 'question_text', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883313299501, 6, 'question_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (question_media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883313770273, 7, 'question_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883314221994, 8, 'difficulty_level', 'TEXT', 'FALSE', 'FALSE', 'CHECK (difficulty_level IN (''Easy'', ''Medium'', ''Hard''))', NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883314873888, 9, 'experience_level', 'TEXT', 'FALSE', 'FALSE', 'CHECK (experience_level IN (''Beginner'', ''Experienced'', ''Advanced''))', NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883309340035, 174127883315395050, 10, 'question_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (question_type IN (''MCQ'', ''Short Answer'', ''Broad Answer'', ''Paragraph Answer''))', NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883318035409, 174127883318544504, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883318035409, 174127883319172993, 2, 'media_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883318035409, 174127883319768882, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883321924167, 174127883322498476, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883321924167, 174127883323236718, 2, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883321924167, 174127883323719892, 3, 'answer_text', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883321924167, 174127883324391665, 4, 'answer_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (answer_media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883321924167, 174127883324884755, 5, 'answer_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127883321924167, 174127883325245123, 6, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885141822821, 174127885156813475, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885141822821, 174127885161140912, 2, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885141822821, 174127885161868214, 3, 'option_text', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885141822821, 174127885162491381, 4, 'option_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (option_media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885141822821, 174127885162952187, 5, 'option_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885141822821, 174127885163625373, 6, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885180368596, 174127885180984629, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885180368596, 174127885181429064, 2, 'subject_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885180368596, 174127885182315273, 3, 'chapter_id', 'BIGINT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885180368596, 174127885182869566, 4, 'quiz_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885180368596, 174127885183495470, 5, 'quiz_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (quiz_type IN (''Mixed'', ''Single Subject'', ''Single Chapter''))', NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885185891316, 174127885186320339, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885185891316, 174127885186898454, 2, 'quiz_id', 'BIGINT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885185891316, 174127885187663383, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885189861468, 174127885190354421, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885189861468, 174127885190867900, 2, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885189861468, 174127885191386489, 3, 'points_per_correct', 'INT', 'FALSE', 'TRUE', 1, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885189861468, 174127885192269200, 4, 'time_bonus_per_sec', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885189861468, 174127885192627868, 5, 'time_penalty_per_sec', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885189861468, 174127885193568827, 6, 'total_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885189861468, 174127885194017472, 7, 'passing_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885196021038, 174127885196650293, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885196021038, 174127885197158157, 2, 'user_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885196021038, 174127885197897108, 3, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885196021038, 174127885198329094, 4, 'total_score', 'INTEGER', 'FALSE', 'TRUE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885196021038, 174127885198882738, 5, 'total_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885196021038, 174127885199331544, 6, 'total_time_taken', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885199811141, 174127885200611916, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885199811141, 174127885201048056, 2, 'attempt_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885199811141, 174127885201521609, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885199811141, 174127885202133498, 4, 'selected_option_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885199811141, 174127885202662214, 5, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885199811141, 174127885203436958, 6, 'time_taken', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885199811141, 174127885204063309, 7, 'points_awarded', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885206924068, 174127885207771399, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885206924068, 174127885208276392, 2, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885206924068, 174127885208870608, 3, 'min_score', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885206924068, 174127885209322875, 4, 'reward_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174127885206924068, 174127885209714159, 5, 'reward_description', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-06 17:34:12', '2025-03-06 17:34:12');


-- Database Schema: Column Key Schema
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883267547934, 174127883234124406, 174127883249830411, 'PRIMARY', NULL, NULL, 'pk>language_data>id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883272348591, 174127883234124406, 174127883255030104, 'UNIQUE', NULL, NULL, 'uk>language_data>iso_code_2', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883272832917, 174127883234124406, 174127883255534099, 'UNIQUE', NULL, NULL, 'uk>language_data>iso_code_3', '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883279283604, 174127883273567132, 174127883274080943, 'FOREIGN', 174127883249830411, NULL, 'fk>subject_data>language_id>language_data>id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883277688434, 174127883273567132, 174127883274539311, 'PRIMARY', NULL, NULL, 'pk>subject_data>id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883278163032, 174127883273567132, 174127883276333374, 'UNIQUE', NULL, NULL, 'uk>subject_data>subject_code', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883278745365, 174127883273567132, 174127883276965626, 'UNIQUE', NULL, NULL, 'uk>subject_data>subject_identity', '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883284543625, 174127883279871252, 174127883280363900, 'FOREIGN', 174127883274539311, NULL, 'fk>chapter_data>subject_id>subject_data>id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883283374601, 174127883279871252, 174127883280899756, 'PRIMARY', NULL, NULL, 'pk>chapter_data>id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883284163048, 174127883279871252, 174127883282757113, 'UNIQUE', NULL, NULL, 'uk>chapter_data>chapter_identity', '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883288342890, 174127883285017287, 174127883285649675, 'PRIMARY', NULL, NULL, 'pk>class_data>id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883288814104, 174127883285017287, 174127883286856630, 'UNIQUE', NULL, NULL, 'uk>class_data>class_code', '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883305348448, 174127883289362015, 174127883290686229, 'FOREIGN', 174127883285649675, NULL, 'fk>class_subject_mapping>class_id>class_data>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883305944232, 174127883289362015, 174127883291226933, 'FOREIGN', 174127883274539311, NULL, 'fk>class_subject_mapping>subject_id>subject_data>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883291799254, 174127883289362015, 174127883290195027, 'PRIMARY', NULL, NULL, 'pk>class_subject_mapping>id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883292228123, 174127883289362015, 174127883290686229, 'UNIQUE', NULL, NULL, 'uk>class_subject_mapping>class_id+subject_id', '2025-03-06 17:33:52', '2025-03-06 17:33:52');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883308885590, 174127883306470710, 174127883307081972, 'PRIMARY', NULL, NULL, 'pk>media_data>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883316426320, 174127883309340035, 174127883311178293, 'FOREIGN', 174127883280899756, NULL, 'fk>question_bank>chapter_id>chapter_data>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883316933000, 174127883309340035, 174127883311936209, 'FOREIGN', 174127883307081972, NULL, 'fk>question_bank>media_id>media_data>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883317449928, 174127883309340035, 174127883310674696, 'FOREIGN', 174127883274539311, NULL, 'fk>question_bank>subject_id>subject_data>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883315980229, 174127883309340035, 174127883310011923, 'PRIMARY', NULL, NULL, 'pk>question_bank>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883321073276, 174127883318035409, 174127883319172993, 'FOREIGN', 174127883307081972, NULL, 'fk>media_question_mapping>media_id>media_data>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883321577013, 174127883318035409, 174127883319768882, 'FOREIGN', 174127883310011923, NULL, 'fk>media_question_mapping>question_id>question_bank>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883320211365, 174127883318035409, 174127883318544504, 'PRIMARY', NULL, NULL, 'pk>media_question_mapping>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883326530765, 174127883321924167, 174127883323236718, 'FOREIGN', 174127883310011923, NULL, 'fk>answer_bank>question_id>question_bank>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127883326025062, 174127883321924167, 174127883322498476, 'PRIMARY', NULL, NULL, 'pk>answer_bank>id', '2025-03-06 17:33:53', '2025-03-06 17:33:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885179837929, 174127885141822821, 174127885161140912, 'FOREIGN', 174127883310011923, NULL, 'fk>mcq_option>question_id>question_bank>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885175582276, 174127885141822821, 174127885156813475, 'PRIMARY', NULL, NULL, 'pk>mcq_option>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885184693867, 174127885180368596, 174127885182315273, 'FOREIGN', 174127883280899756, NULL, 'fk>quiz_data>chapter_id>chapter_data>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885185128597, 174127885180368596, 174127885181429064, 'FOREIGN', 174127883274539311, NULL, 'fk>quiz_data>subject_id>subject_data>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885184162585, 174127885180368596, 174127885180984629, 'PRIMARY', NULL, NULL, 'pk>quiz_data>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885188874127, 174127885185891316, 174127885187663383, 'FOREIGN', 174127883310011923, NULL, 'fk>quiz_question>question_id>question_bank>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885189354686, 174127885185891316, 174127885186898454, 'FOREIGN', 174127885180984629, NULL, 'fk>quiz_question>quiz_id>quiz_data>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885188165617, 174127885185891316, 174127885186320339, 'PRIMARY', NULL, NULL, 'pk>quiz_question>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885195295733, 174127885189861468, 174127885190867900, 'FOREIGN', 174127885180984629, NULL, 'fk>quiz_scoring>quiz_id>quiz_data>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885194774394, 174127885189861468, 174127885190354421, 'PRIMARY', NULL, NULL, 'pk>quiz_scoring>id', '2025-03-06 17:34:11', '2025-03-06 17:34:11');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885205471034, 174127885199811141, 174127885201048056, 'FOREIGN', 174127885196650293, NULL, 'fk>quiz_attempt_detail>attempt_id>quiz_attempt>id', '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885205941777, 174127885199811141, 174127885201521609, 'FOREIGN', 174127883310011923, NULL, 'fk>quiz_attempt_detail>question_id>question_bank>id', '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885206481541, 174127885199811141, 174127885202133498, 'FOREIGN', 174127885156813475, NULL, 'fk>quiz_attempt_detail>selected_option_id>mcq_option>id', '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885204548674, 174127885199811141, 174127885200611916, 'PRIMARY', NULL, NULL, 'pk>quiz_attempt_detail>id', '2025-03-06 17:34:12', '2025-03-06 17:34:12');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885211188059, 174127885206924068, 174127885208276392, 'FOREIGN', 174127885180984629, NULL, 'fk>quiz_reward>quiz_id>quiz_data>id', '2025-03-06 17:34:12', '2025-03-06 17:34:12');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174127885210689842, 174127885206924068, 174127885207771399, 'PRIMARY', NULL, NULL, 'pk>quiz_reward>id', '2025-03-06 17:34:12', '2025-03-06 17:34:12');

-- Database Schema: Composite Key Schema
INSERT INTO tbl_composite_key (key_id, id, primary_column, composite_column, key_name, modified_date, created_date) VALUES (174127883292228123, 174127883296681817, 174127883291226933, NULL, NULL, '2025-03-06 17:33:52', '2025-03-06 17:33:52');



