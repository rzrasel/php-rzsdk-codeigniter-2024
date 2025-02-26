
-- SQLite Database DATE CREATED: 2025-02-23, DATE MODIFIED: 2025-02-26 - VERSION: v-1.1.1
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
DROP TABLE IF EXISTS tbl_quiz_attempt;
DROP TABLE IF EXISTS tbl_quiz_reward;
DROP TABLE IF EXISTS tbl_quiz_data;
DROP TABLE IF EXISTS tbl_chapter_data;
DROP TABLE IF EXISTS tbl_subject_data;
DROP TABLE IF EXISTS tbl_media_data;


CREATE TABLE IF NOT EXISTS tbl_subject_data (
    id                       BIGINT(20)     NOT NULL,
    subject_name             TEXT           NOT NULL,
    CONSTRAINT pk_subject_data_id PRIMARY KEY(id),
    CONSTRAINT uk_subject_data_subject_name UNIQUE(subject_name)
);

CREATE TABLE IF NOT EXISTS tbl_chapter_data (
    subject_id               BIGINT(20)     NOT NULL,
    id                       BIGINT(20)     NOT NULL,
    chapter_name             TEXT           NOT NULL,
    CONSTRAINT pk_chapter_data_id PRIMARY KEY(id),
    CONSTRAINT fk_chapter_data_subject_id_subject_data_id FOREIGN KEY(subject_id) REFERENCES tbl_subject_data(id)
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
    total_time_taken         INT            NOT NULL DEFAULT 0,
    status                   TEXT           NOT NULL CHECK (status IN ('Completed', 'In Progress', 'Failed', 'Passed') NOT NULL DEFAULT 'In Progress')),
    attempt_date             TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_quiz_attempt_id PRIMARY KEY(id),
    CONSTRAINT fk_quiz_attempt_user_id_quiz_data_id FOREIGN KEY(user_id) REFERENCES tbl_quiz_data(id)
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
DELETE FROM tbl_quiz_attempt;
DELETE FROM tbl_quiz_reward;
DELETE FROM tbl_quiz_data;
DELETE FROM tbl_chapter_data;
DELETE FROM tbl_subject_data;
DELETE FROM tbl_media_data;

-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174029832702120927, 'quiz_manager_database_schema', '1.1.1', 'tbl_', NULL, '2025-02-23 09:12:07', '2025-02-23 09:12:07');

-- Database Schema: Table Schema
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174029855576982878, 1, 'subject_data', NULL, NULL, '2025-02-23 09:15:55', '2025-02-23 09:15:55');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174029858675790319, 2, 'chapter_data', NULL, NULL, '2025-02-23 09:16:26', '2025-02-23 09:16:26');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174029862431920698, 3, 'media_data', NULL, NULL, '2025-02-23 09:17:04', '2025-02-23 09:17:04');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174030058870938192, 4, 'question_bank', NULL, NULL, '2025-02-23 09:49:48', '2025-02-23 09:49:48');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174030059749493648, 5, 'media_question_mapping', NULL, NULL, '2025-02-23 09:49:57', '2025-02-23 09:49:57');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174030300570752205, 6, 'answer_bank', NULL, NULL, '2025-02-23 10:30:05', '2025-02-23 10:30:05');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174030310687417088, 7, 'mcq_option', NULL, NULL, '2025-02-23 10:31:46', '2025-02-23 10:31:46');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174030311341675118, 8, 'quiz_data', NULL, NULL, '2025-02-23 10:31:53', '2025-02-23 10:31:53');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174030311847810831, 9, 'quiz_question', NULL, NULL, '2025-02-23 10:31:58', '2025-02-23 10:31:58');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174033494185523583, 10, 'quiz_scoring', NULL, NULL, '2025-02-23 19:22:21', '2025-02-23 19:22:21');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174033495266434466, 11, 'quiz_attempt', NULL, NULL, '2025-02-23 19:22:32', '2025-02-23 19:22:32');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174033496258769445, 12, 'quiz_attempt_detail', NULL, NULL, '2025-02-23 19:22:42', '2025-02-23 19:22:42');
INSERT INTO tbl_table_data (schema_id, id, table_order, table_name, column_prefix, table_comment, modified_date, created_date) VALUES (174029832702120927, 174033500238798999, 13, 'quiz_reward', NULL, NULL, '2025-02-23 19:23:22', '2025-02-23 19:23:22');

-- Database Schema: Column Schema
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029855576982878, 174029890765212747, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:21:47', '2025-02-23 09:21:47');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029855576982878, 174029893875676178, 2, 'subject_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:53:51', '2025-02-23 09:22:18');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029858675790319, 174029917383731636, 1, 'subject_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:26:50', '2025-02-23 09:26:13');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029858675790319, 174029915458692592, 2, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:27:04', '2025-02-23 09:25:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029858675790319, 174029919413566909, 3, 'chapter_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:26:34', '2025-02-23 09:26:34');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029862431920698, 174030016319981032, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:42:43', '2025-02-23 09:42:43');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029862431920698, 174030020536670259, 2, 'media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link'))', NULL, '2025-02-23 09:56:46', '2025-02-23 09:43:25');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174029862431920698, 174030024779851524, 3, 'media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 09:44:07', '2025-02-23 09:44:07');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030063316983516, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:50:33', '2025-02-23 09:50:33');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030065032064084, 2, 'subject_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:50:50', '2025-02-23 09:50:50');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030067677455179, 3, 'chapter_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 09:51:16', '2025-02-23 09:51:16');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030069020823531, 4, 'media_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 09:51:30', '2025-02-23 09:51:30');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030070862520743, 5, 'question_text', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:51:48', '2025-02-23 09:51:48');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030075947199424, 6, 'question_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (question_media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link'))', NULL, '2025-02-23 09:52:39', '2025-02-23 09:52:39');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030077507828996, 7, 'question_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 09:52:55', '2025-02-23 09:52:55');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030079880767249, 8, 'difficulty_level', 'TEXT', 'FALSE', 'FALSE', 'CHECK (difficulty_level IN ('Easy', 'Medium', 'Hard'))', NULL, '2025-02-23 09:53:18', '2025-02-23 09:53:18');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030083253933382, 9, 'experience_level', 'TEXT', 'FALSE', 'FALSE', 'CHECK (experience_level IN ('Beginner', 'Experienced', 'Advanced'))', NULL, '2025-02-23 09:53:52', '2025-02-23 09:53:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030058870938192, 174030091893833833, 10, 'question_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (question_type IN ('MCQ', 'Short Answer', 'Broad Answer', 'Paragraph Answer'))', NULL, '2025-02-23 11:05:08', '2025-02-23 09:55:18');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030059749493648, 174030113895516362, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:58:58', '2025-02-23 09:58:58');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030059749493648, 174030115678328167, 2, 'media_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:59:16', '2025-02-23 09:59:16');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030059749493648, 174030117488491619, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 09:59:34', '2025-02-23 09:59:34');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030300570752205, 174030315292616747, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:32:32', '2025-02-23 10:32:32');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030300570752205, 174030316990556675, 2, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:32:49', '2025-02-23 10:32:49');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030300570752205, 174030318652047680, 3, 'answer_text', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 10:33:06', '2025-02-23 10:33:06');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030300570752205, 174030321460428903, 4, 'answer_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (answer_media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link'))', NULL, '2025-02-23 10:33:34', '2025-02-23 10:33:34');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030300570752205, 174030323400832146, 5, 'answer_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 10:33:54', '2025-02-23 10:33:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030300570752205, 174030325369923939, 6, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-02-23 10:55:54', '2025-02-23 10:34:13');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030310687417088, 174030328229399436, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:34:42', '2025-02-23 10:34:42');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030310687417088, 174030330963493734, 2, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:35:09', '2025-02-23 10:35:09');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030310687417088, 174030333123278284, 3, 'option_text', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:35:31', '2025-02-23 10:35:31');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030310687417088, 174030335300415406, 4, 'option_media_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (option_media_type IN ('Text', 'Image', 'Video', 'Audio', 'Link'))', NULL, '2025-02-23 10:35:53', '2025-02-23 10:35:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030310687417088, 174030337717078481, 5, 'option_media_url', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 10:36:17', '2025-02-23 10:36:17');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030310687417088, 174030341297037181, 6, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-02-23 10:36:52', '2025-02-23 10:36:52');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311341675118, 174030343971249751, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:37:19', '2025-02-23 10:37:19');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311341675118, 174030352060623613, 2, 'subject_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 10:48:09', '2025-02-23 10:38:40');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311341675118, 174030353441053234, 3, 'chapter_id', 'BIGINT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 10:48:25', '2025-02-23 10:38:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311341675118, 174030345411357108, 4, 'quiz_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:50:24', '2025-02-23 10:37:34');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311341675118, 174030349873490477, 5, 'quiz_type', 'TEXT', 'FALSE', 'FALSE', 'CHECK (quiz_type IN ('Mixed', 'Single Subject', 'Single Chapter'))', NULL, '2025-02-23 10:50:48', '2025-02-23 10:38:18');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311847810831, 174030355454393582, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:40:18', '2025-02-23 10:39:14');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311847810831, 174030357743819509, 2, 'quiz_id', 'BIGINT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:39:37', '2025-02-23 10:39:37');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174030311847810831, 174030359490493198, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 10:39:54', '2025-02-23 10:39:54');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033494185523583, 174033506751727958, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 19:24:27', '2025-02-23 19:24:27');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033494185523583, 174033508653083947, 2, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 19:24:46', '2025-02-23 19:24:46');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033494185523583, 174033515423322749, 3, 'points_per_correct', 'INT', 'FALSE', 'TRUE', 1, NULL, '2025-02-23 19:25:54', '2025-02-23 19:25:54');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033494185523583, 174033517673325722, 4, 'time_bonus_per_sec', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-02-23 20:28:36', '2025-02-23 19:26:16');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033494185523583, 174033519693724759, 5, 'time_penalty_per_sec', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-02-23 19:26:36', '2025-02-23 19:26:36');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033494185523583, 174033521332758131, 6, 'total_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 19:26:53', '2025-02-23 19:26:53');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033494185523583, 174033523537933340, 7, 'passing_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 19:27:15', '2025-02-23 19:27:15');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033943461970881, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:37:14', '2025-02-23 20:37:14');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033950141899434, 2, 'user_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:38:21', '2025-02-23 20:38:21');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033951644883811, 3, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:38:36', '2025-02-23 20:38:36');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033957592435872, 4, 'total_score', 'INTEGER', 'FALSE', 'TRUE', NULL, NULL, '2025-02-23 20:39:35', '2025-02-23 20:39:35');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033959936998088, 5, 'total_marks', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:39:59', '2025-02-23 20:39:59');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033962182782187, 6, 'total_time_taken', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-02-23 20:40:21', '2025-02-23 20:40:21');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033969493594968, 7, 'status', 'TEXT', 'FALSE', 'FALSE', 'CHECK (status IN ('Completed', 'In Progress', 'Failed', 'Passed') NOT NULL DEFAULT 'In Progress'))', NULL, '2025-02-23 20:41:34', '2025-02-23 20:41:34');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033495266434466, 174033972754240140, 8, 'attempt_date', 'TIMESTAMP', 'FALSE', 'TRUE', 'CURRENT_TIMESTAMP', NULL, '2025-02-23 20:42:07', '2025-02-23 20:42:07');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033496258769445, 174033984940558546, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:44:09', '2025-02-23 20:44:09');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033496258769445, 174033989246789607, 2, 'attempt_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:44:52', '2025-02-23 20:44:52');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033496258769445, 174033990215640216, 3, 'question_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:45:02', '2025-02-23 20:45:02');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033496258769445, 174033993549620585, 4, 'selected_option_id', 'BIGINT(20)', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 20:45:35', '2025-02-23 20:45:35');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033496258769445, 174033996625068600, 5, 'is_correct', 'BOOLEAN', 'FALSE', 'TRUE', 'FALSE', NULL, '2025-02-23 20:46:06', '2025-02-23 20:46:06');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033496258769445, 174034000137638580, 6, 'time_taken', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:46:41', '2025-02-23 20:46:41');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033496258769445, 174034002428463373, 7, 'points_awarded', 'INT', 'FALSE', 'TRUE', NULL, NULL, '2025-02-23 20:47:04', '2025-02-23 20:47:04');

INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033500238798999, 174034008050784705, 1, 'id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:48:00', '2025-02-23 20:48:00');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033500238798999, 174034010640134391, 2, 'quiz_id', 'BIGINT(20)', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:48:26', '2025-02-23 20:48:26');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033500238798999, 174034012150567862, 3, 'min_score', 'INT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:48:41', '2025-02-23 20:48:41');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033500238798999, 174034014698095081, 4, 'reward_name', 'TEXT', 'FALSE', 'FALSE', NULL, NULL, '2025-02-23 20:49:06', '2025-02-23 20:49:06');
INSERT INTO tbl_column_data (table_id, id, column_order, column_name, data_type, is_nullable, have_default, default_value, column_comment, modified_date, created_date) VALUES (174033500238798999, 174034015949176742, 5, 'reward_description', 'TEXT', 'TRUE', 'FALSE', NULL, NULL, '2025-02-23 20:49:19', '2025-02-23 20:49:19');


-- Database Schema: Column Key Schema
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174029896155788994, 174029855576982878, 174029890765212747, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>subject_data>id', '2025-02-23 09:22:41', '2025-02-23 09:22:41');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174029897064494857, 174029855576982878, 174029893875676178, 'UNIQUE', NULL, NULL, 'uk>quiz_manager_database_schema>subject_data>subject_name', '2025-02-23 09:22:50', '2025-02-23 09:22:50');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174029946618116844, 174029858675790319, 174029917383731636, 'FOREIGN', 174029890765212747, NULL, 'fk>quiz_manager_database_schema>chapter_data>subject_id>subject_data>id', '2025-02-23 09:31:06', '2025-02-23 09:31:06');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174029942583148816, 174029858675790319, 174029915458692592, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>chapter_data>id', '2025-02-23 09:30:25', '2025-02-23 09:30:25');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030027099649824, 174029862431920698, 174030016319981032, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>media_data>id', '2025-02-23 09:44:30', '2025-02-23 09:44:30');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030128517427502, 174030058870938192, 174030067677455179, 'FOREIGN', 174029915458692592, NULL, 'fk>quiz_manager_database_schema>question_bank>chapter_id>chapter_data>id', '2025-02-23 10:01:25', '2025-02-23 10:01:25');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030130638889651, 174030058870938192, 174030069020823531, 'FOREIGN', 174030016319981032, NULL, 'fk>quiz_manager_database_schema>question_bank>media_id>media_data>id', '2025-02-23 10:01:46', '2025-02-23 10:01:46');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030125584281784, 174030058870938192, 174030065032064084, 'FOREIGN', 174029890765212747, NULL, 'fk>quiz_manager_database_schema>question_bank>subject_id>subject_data>id', '2025-02-23 10:00:55', '2025-02-23 10:00:55');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030122121881302, 174030058870938192, 174030063316983516, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>question_bank>id', '2025-02-23 10:00:21', '2025-02-23 10:00:21');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030145056772949, 174030059749493648, 174030115678328167, 'FOREIGN', 174030016319981032, NULL, 'fk>quiz_manager_database_schema>media_question_mapping>media_id>media_data>id', '2025-02-23 10:04:10', '2025-02-23 10:04:10');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030146761075782, 174030059749493648, 174030117488491619, 'FOREIGN', 174030063316983516, NULL, 'fk>quiz_manager_database_schema>media_question_mapping>question_id>question_bank>id', '2025-02-23 10:04:27', '2025-02-23 10:04:27');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030142581079729, 174030059749493648, 174030113895516362, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>media_question_mapping>id', '2025-02-23 10:03:45', '2025-02-23 10:03:45');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030377366365785, 174030300570752205, 174030316990556675, 'FOREIGN', 174030063316983516, NULL, 'fk>quiz_manager_database_schema>answer_bank>question_id>question_bank>id', '2025-02-23 10:42:53', '2025-02-23 10:42:53');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030373226398593, 174030300570752205, 174030315292616747, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>answer_bank>id', '2025-02-23 10:42:12', '2025-02-23 10:42:12');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030384214584407, 174030310687417088, 174030330963493734, 'FOREIGN', 174030063316983516, NULL, 'fk>quiz_manager_database_schema>mcq_option>question_id>question_bank>id', '2025-02-23 10:44:02', '2025-02-23 10:44:02');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030379118071178, 174030310687417088, 174030328229399436, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>mcq_option>id', '2025-02-23 10:43:11', '2025-02-23 10:43:11');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030391251329237, 174030311341675118, 174030353441053234, 'FOREIGN', 174029915458692592, NULL, 'fk>quiz_manager_database_schema>quiz_data>chapter_id>chapter_data>id', '2025-02-23 10:45:12', '2025-02-23 10:45:12');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030388887555636, 174030311341675118, 174030352060623613, 'FOREIGN', 174029890765212747, NULL, 'fk>quiz_manager_database_schema>quiz_data>subject_id>subject_data>id', '2025-02-23 10:44:48', '2025-02-23 10:44:48');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030461546551011, 174030311341675118, 174030343971249751, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>quiz_data>id', '2025-02-23 10:56:55', '2025-02-23 10:56:55');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030397450481328, 174030311847810831, 174030359490493198, 'FOREIGN', 174030063316983516, NULL, 'fk>quiz_manager_database_schema>quiz_question>question_id>question_bank>id', '2025-02-23 10:46:14', '2025-02-23 10:46:14');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030394814760197, 174030311847810831, 174030357743819509, 'FOREIGN', 174030343971249751, NULL, 'fk>quiz_manager_database_schema>quiz_question>quiz_id>quiz_data>id', '2025-02-23 10:45:48', '2025-02-23 10:45:48');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174030464586156127, 174030311847810831, 174030355454393582, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>quiz_question>id', '2025-02-23 10:57:25', '2025-02-23 10:57:25');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034043812172352, 174033494185523583, 174033508653083947, 'FOREIGN', 174030343971249751, NULL, 'fk>quiz_manager_database_schema>quiz_scoring>quiz_id>quiz_data>id', '2025-02-23 20:53:58', '2025-02-23 20:53:58');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034039544148556, 174033494185523583, 174033506751727958, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>quiz_scoring>id', '2025-02-23 20:53:15', '2025-02-23 20:53:15');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034050363192784, 174033495266434466, 174033950141899434, 'FOREIGN', 174030343971249751, NULL, 'fk>quiz_manager_database_schema>quiz_attempt>user_id>quiz_data>id', '2025-02-23 20:55:03', '2025-02-23 20:55:03');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034047555186821, 174033495266434466, 174033943461970881, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>quiz_attempt>id', '2025-02-23 20:54:35', '2025-02-23 20:54:35');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034055218044575, 174033496258769445, 174033989246789607, 'FOREIGN', 174033943461970881, NULL, 'fk>quiz_manager_database_schema>quiz_attempt_detail>attempt_id>quiz_attempt>id', '2025-02-23 20:55:52', '2025-02-23 20:55:52');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034057186287197, 174033496258769445, 174033990215640216, 'FOREIGN', 174030063316983516, NULL, 'fk>quiz_manager_database_schema>quiz_attempt_detail>question_id>question_bank>id', '2025-02-23 20:56:11', '2025-02-23 20:56:11');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034059820977706, 174033496258769445, 174033993549620585, 'FOREIGN', 174030328229399436, NULL, 'fk>quiz_manager_database_schema>quiz_attempt_detail>selected_option_id>mcq_option>id', '2025-02-23 20:56:38', '2025-02-23 20:56:38');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034052464771704, 174033496258769445, 174033984940558546, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>quiz_attempt_detail>id', '2025-02-23 20:55:24', '2025-02-23 20:55:24');

INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034063787899931, 174033500238798999, 174034010640134391, 'FOREIGN', 174030343971249751, NULL, 'fk>quiz_manager_database_schema>quiz_reward>quiz_id>quiz_data>id', '2025-02-23 20:57:17', '2025-02-23 20:57:17');
INSERT INTO tbl_column_key (id, working_table, main_column, key_type, reference_column, key_name, unique_name, modified_date, created_date) VALUES (174034061486083625, 174033500238798999, 174034008050784705, 'PRIMARY', NULL, NULL, 'pk>quiz_manager_database_schema>quiz_reward>id', '2025-02-23 20:56:54', '2025-02-23 20:56:54');
