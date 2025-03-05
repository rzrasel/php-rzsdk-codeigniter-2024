
-- SQLite Database DATE CREATED: 2025-02-23, DATE MODIFIED: 2025-03-05 - VERSION: v-1.1.1
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
DROP TABLE IF EXISTS tbl_subject_data;
DROP TABLE IF EXISTS tbl_language_data;
DROP TABLE IF EXISTS tbl_media_data;
DROP TABLE IF EXISTS tbl_quiz_attempt;


CREATE TABLE IF NOT EXISTS tbl_language_data (
    id                   BIGINT(20)     NOT NULL,
    name                 TEXT           NOT NULL,
    iso_code_2           TEXT           NOT NULL,
    iso_code_3           TEXT           NOT NULL,
    CONSTRAINT pk_language_data_id PRIMARY KEY(id),
    CONSTRAINT uk_language_data_iso_code_2 UNIQUE(iso_code_2),
    CONSTRAINT uk_language_data_iso_code_3 UNIQUE(iso_code_3)
);

CREATE TABLE IF NOT EXISTS tbl_subject_data (
    language_id          BIGINT(20)     NOT NULL,
    id                   BIGINT(20)     NOT NULL,
    name                 TEXT           NOT NULL,
    description          TEXT           NULL,
    subject_code         TEXT           NOT NULL,
    CONSTRAINT pk_subject_data_id PRIMARY KEY(id),
    CONSTRAINT uk_subject_data_subject_code UNIQUE(subject_code),
    CONSTRAINT fk_subject_data_language_id_language_data_id FOREIGN KEY(language_id) REFERENCES tbl_language_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_chapter_data (
    subject_id           BIGINT(20)     NOT NULL,
    id                   BIGINT(20)     NOT NULL,
    name                 TEXT           NOT NULL,
    index_order          INTEGER        NOT NULL,
    chapter_identity     TEXT           NOT NULL,
    CONSTRAINT pk_chapter_data_id PRIMARY KEY(id),
    CONSTRAINT uk_chapter_data_chapter_identity UNIQUE(chapter_identity),
    CONSTRAINT fk_chapter_data_subject_id_subject_data_id FOREIGN KEY(subject_id) REFERENCES tbl_subject_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_class_data (
    id                   BIGINT(20)     NOT NULL,
    class_name           TEXT           NOT NULL,
    class_code           TEXT           NOT NULL UNIQUE,
    description          TEXT           NULL,
    index_order          INTEGER        NOT NULL,
    CONSTRAINT pk_class_data_id PRIMARY KEY(id),
    CONSTRAINT uk_class_data_class_code UNIQUE(class_code)
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
DELETE FROM tbl_subject_data;
DELETE FROM tbl_language_data;
DELETE FROM tbl_media_data;
DELETE FROM tbl_quiz_attempt;


-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174029832702120927, 'quiz_manager_database_schema', '1.1.1', 'tbl_', NULL, '2025-02-23 09:12:07', '2025-02-23 09:12:07');

-- Database Schema: Table Schema
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116438623546406, 1, 'language_data', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116438658799983, 2, 'subject_data', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116438661510387, 3, 'chapter_data', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116438664227481, 4, 'media_data', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116439968378361, 5, 'question_bank', NULL, NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116440005433800, 6, 'media_question_mapping', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116440008681185, 7, 'answer_bank', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116441347026136, 8, 'mcq_option', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116441381427746, 9, 'quiz_data', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116441385679190, 10, 'quiz_question', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116441388735360, 11, 'quiz_scoring', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116442519283028, 12, 'quiz_attempt', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116442547417137, 13, 'quiz_attempt_detail', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174116442561925377, 14, 'quiz_reward', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');

-- Database Schema: Column Schema
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438623546406, 174116438638557894, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438623546406, 174116438642278284, 2, 'name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438623546406, 174116438642788828, 3, 'iso_code_2', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438623546406, 174116438643149389, 4, 'iso_code_3', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438658799983, 174116438659245888, 1, 'language_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438658799983, 174116438659721240, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438658799983, 174116438660148250, 3, 'subject_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438661510387, 174116438661919667, 1, 'subject_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438661510387, 174116438662482266, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438661510387, 174116438662875155, 3, 'chapter_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438664227481, 174116438664655376, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438664227481, 174116438665052597, 2, 'media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116438664227481, 174116438665588549, 3, 'media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439982050292, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439985899576, 2, 'subject_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439986387405, 3, 'chapter_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439986892070, 4, 'media_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439987262285, 5, 'question_text', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439987761178, 6, 'question_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (question_media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439988251576, 7, 'question_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439988641962, 8, 'difficulty_level', 'TEXT', 'FALSE', 'FALSE', 'CHECK (difficulty_level IN (''Easy'', ''Medium'', ''Hard''))', NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439989167227, 9, 'experience_level', 'TEXT', 'FALSE', 'FALSE', 'CHECK (experience_level IN (''Beginner'', ''Experienced'', ''Advanced''))', NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116439968378361, 174116439989699228, 10, 'question_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (question_type IN (''MCQ'', ''Short Answer'', ''Broad Answer'', ''Paragraph Answer''))', NULL, '2025-03-05 09:46:39', '2025-03-05 09:46:39');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440005433800, 174116440005912917, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440005433800, 174116440006451598, 2, 'media_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440005433800, 174116440006864640, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440008681185, 174116440009120674, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440008681185, 174116440009655760, 2, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440008681185, 174116440010194218, 3, 'answer_text', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440008681185, 174116440010644607, 4, 'answer_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (answer_media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440008681185, 174116440011069509, 5, 'answer_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116440008681185, 174116440011541620, 6, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-05 09:46:40', '2025-03-05 09:46:40');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441347026136, 174116441361033392, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441347026136, 174116441364825324, 2, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441347026136, 174116441365238690, 3, 'option_text', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441347026136, 174116441365745918, 4, 'option_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (option_media_type IN (''Text'', ''Image'', ''Video'', ''Audio'', ''Link''))', NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441347026136, 174116441366195348, 5, 'option_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441347026136, 174116441366533447, 6, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441381427746, 174116441381929143, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441381427746, 174116441382473943, 2, 'subject_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441381427746, 174116441382814016, 3, 'chapter_id', 'BIGINT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441381427746, 174116441383354911, 4, 'quiz_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441381427746, 174116441383852112, 5, 'quiz_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (quiz_type IN (''Mixed'', ''Single Subject'', ''Single Chapter''))', NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441385679190, 174116441386063523, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441385679190, 174116441386596220, 2, 'quiz_id', 'BIGINT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441385679190, 174116441386950543, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441388735360, 174116441389274828, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441388735360, 174116441389777256, 2, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441388735360, 174116441390195195, 3, 'points_per_correct', 'INT', 'FALSE', 'TRUE', 1, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441388735360, 174116441390611623, 4, 'time_bonus_per_sec', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441388735360, 174116441391086795, 5, 'time_penalty_per_sec', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441388735360, 174116441391579407, 6, 'total_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116441388735360, 174116441391949852, 7, 'passing_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442519283028, 174116442533363777, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442519283028, 174116442537292727, 2, 'user_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442519283028, 174116442537786435, 3, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442519283028, 174116442538675587, 4, 'total_score', 'INTEGER', 'FALSE', 'TRUE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442519283028, 174116442539879835, 5, 'total_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442519283028, 174116442540373474, 6, 'total_time_taken', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442547417137, 174116442548077845, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442547417137, 174116442548417073, 2, 'attempt_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442547417137, 174116442548923955, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442547417137, 174116442549326664, 4, 'selected_option_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442547417137, 174116442549936257, 5, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442547417137, 174116442550462833, 6, 'time_taken', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442547417137, 174116442550820343, 7, 'points_awarded', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442561925377, 174116442562552208, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442561925377, 174116442562995537, 2, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442561925377, 174116442563454111, 3, 'min_score', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442561925377, 174116442563959344, 4, 'reward_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174116442561925377, 174116442564321417, 5, 'reward_description', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-03-05 09:47:05', '2025-03-05 09:47:05');


-- Database Schema: Column Key Schema
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438654159131, 174116438623546406, 174116438638557894, 'PRIMARY', NULL, NULL, 'pk>language_data>id', '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438657974441, 174116438623546406, 174116438642788828, 'UNIQUE', NULL, NULL, 'uk>language_data>iso_code_2', '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438658350131, 174116438623546406, 174116438643149389, 'UNIQUE', NULL, NULL, 'uk>language_data>iso_code_3', '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438661089311, 174116438658799983, 174116438659245888, 'FOREIGN', 174116438638557894, NULL, 'fk>subject_data>language_id>language_data>id', '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438660626479, 174116438658799983, 174116438659721240, 'PRIMARY', NULL, NULL, 'pk>subject_data>id', '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438663755798, 174116438661510387, 174116438661919667, 'FOREIGN', 174116438659721240, NULL, 'fk>chapter_data>subject_id>subject_data>id', '2025-03-05 09:46:26', '2025-03-05 09:46:26');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438663334034, 174116438661510387, 174116438662482266, 'PRIMARY', NULL, NULL, 'pk>chapter_data>id', '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116438666024973, 174116438664227481, 174116438664655376, 'PRIMARY', NULL, NULL, 'pk>media_data>id', '2025-03-05 09:46:26', '2025-03-05 09:46:26');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440003971250, 174116439968378361, 174116439986387405, 'FOREIGN', 174116438662482266, NULL, 'fk>question_bank>chapter_id>chapter_data>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440004382273, 174116439968378361, 174116439986892070, 'FOREIGN', 174116438664655376, NULL, 'fk>question_bank>media_id>media_data>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440004995959, 174116439968378361, 174116439985899576, 'FOREIGN', 174116438659721240, NULL, 'fk>question_bank>subject_id>subject_data>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440000021871, 174116439968378361, 174116439982050292, 'PRIMARY', NULL, NULL, 'pk>question_bank>id', '2025-03-05 09:46:39', '2025-03-05 09:46:39');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440007770940, 174116440005433800, 174116440006451598, 'FOREIGN', 174116438664655376, NULL, 'fk>media_question_mapping>media_id>media_data>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440008267260, 174116440005433800, 174116440006864640, 'FOREIGN', 174116439982050292, NULL, 'fk>media_question_mapping>question_id>question_bank>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440007356008, 174116440005433800, 174116440005912917, 'PRIMARY', NULL, NULL, 'pk>media_question_mapping>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440012428185, 174116440008681185, 174116440009655760, 'FOREIGN', 174116439982050292, NULL, 'fk>answer_bank>question_id>question_bank>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116440012051708, 174116440008681185, 174116440009120674, 'PRIMARY', NULL, NULL, 'pk>answer_bank>id', '2025-03-05 09:46:40', '2025-03-05 09:46:40');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441380958047, 174116441347026136, 174116441364825324, 'FOREIGN', 174116439982050292, NULL, 'fk>mcq_option>question_id>question_bank>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441376962210, 174116441347026136, 174116441361033392, 'PRIMARY', NULL, NULL, 'pk>mcq_option>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441384641224, 174116441381427746, 174116441382814016, 'FOREIGN', 174116438662482266, NULL, 'fk>quiz_data>chapter_id>chapter_data>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441385130434, 174116441381427746, 174116441382473943, 'FOREIGN', 174116438659721240, NULL, 'fk>quiz_data>subject_id>subject_data>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441384279849, 174116441381427746, 174116441381929143, 'PRIMARY', NULL, NULL, 'pk>quiz_data>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441387899434, 174116441385679190, 174116441386950543, 'FOREIGN', 174116439982050292, NULL, 'fk>quiz_question>question_id>question_bank>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441388368456, 174116441385679190, 174116441386596220, 'FOREIGN', 174116441381929143, NULL, 'fk>quiz_question>quiz_id>quiz_data>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441387427186, 174116441385679190, 174116441386063523, 'PRIMARY', NULL, NULL, 'pk>quiz_question>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441392983680, 174116441388735360, 174116441389777256, 'FOREIGN', 174116441381929143, NULL, 'fk>quiz_scoring>quiz_id>quiz_data>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116441392485169, 174116441388735360, 174116441389274828, 'PRIMARY', NULL, NULL, 'pk>quiz_scoring>id', '2025-03-05 09:46:53', '2025-03-05 09:46:53');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116442559740967, 174116442547417137, 174116442548417073, 'FOREIGN', 174116442533363777, NULL, 'fk>quiz_attempt_detail>attempt_id>quiz_attempt>id', '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116442560641156, 174116442547417137, 174116442548923955, 'FOREIGN', 174116439982050292, NULL, 'fk>quiz_attempt_detail>question_id>question_bank>id', '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116442561128054, 174116442547417137, 174116442549326664, 'FOREIGN', 174116441361033392, NULL, 'fk>quiz_attempt_detail>selected_option_id>mcq_option>id', '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116442555656989, 174116442547417137, 174116442548077845, 'PRIMARY', NULL, NULL, 'pk>quiz_attempt_detail>id', '2025-03-05 09:47:05', '2025-03-05 09:47:05');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116442565466291, 174116442561925377, 174116442562995537, 'FOREIGN', 174116441381929143, NULL, 'fk>quiz_reward>quiz_id>quiz_data>id', '2025-03-05 09:47:05', '2025-03-05 09:47:05');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174116442564894387, 174116442561925377, 174116442562552208, 'PRIMARY', NULL, NULL, 'pk>quiz_reward>id', '2025-03-05 09:47:05', '2025-03-05 09:47:05');


