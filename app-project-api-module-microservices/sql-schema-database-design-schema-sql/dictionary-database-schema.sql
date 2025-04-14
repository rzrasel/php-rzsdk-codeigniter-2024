-- SQLite Database DATE CREATED: 2025-03-24, DATE MODIFIED: 2025-03-24 - VERSION: v-1.1.0
-- DATABASE NAME: Dictionary Database Schema



CREATE DATABASE IF NOT EXISTS dictionary_database_schema;
USE dictionary_database_schema;

DROP TABLE IF EXISTS tbl_word_pos_mapping;
DROP TABLE IF EXISTS tbl_pronunciation;
DROP TABLE IF EXISTS tbl_meaning;
DROP TABLE IF EXISTS tbl_translation;
DROP TABLE IF EXISTS tbl_usages;
DROP TABLE IF EXISTS tbl_synonyms;
DROP TABLE IF EXISTS tbl_antonyms;
DROP TABLE IF EXISTS tbl_word_data;
DROP TABLE IF EXISTS tbl_language_data;
DROP TABLE IF EXISTS tbl_part_of_speech;


CREATE TABLE IF NOT EXISTS tbl_language_data (
    id                BIGINT(20)     NOT NULL,
    name              TEXT           NOT NULL,
    iso_code_2        TEXT           NOT NULL,
    iso_code_3        TEXT           NOT NULL,
    slug              TEXT           NOT NULL,
    created_date      DATETIME       NOT NULL,
    modified_date     DATETIME       NOT NULL,
    created_by        BIGINT(20)     NOT NULL,
    modified_by       BIGINT(20)     NOT NULL,
    CONSTRAINT pk_language_data_id PRIMARY KEY(id),
    CONSTRAINT uk_language_data_iso_code_2 UNIQUE(iso_code_2),
    CONSTRAINT uk_language_data_iso_code_3 UNIQUE(iso_code_3),
    CONSTRAINT uk_language_data_slug UNIQUE(slug)
    );

CREATE TABLE IF NOT EXISTS tbl_word_data (
    language_id            BIGINT(20)       NOT NULL,
    word_id                BIGINT(20)       NOT NULL,
    word                   TEXT             NOT NULL,
    created_at             TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_word_data_word_id PRIMARY KEY(word_id),
    CONSTRAINT fk_word_data_language_id_language_data_id FOREIGN KEY(language_id) REFERENCES tbl_language_data(id)
);

CREATE TABLE IF NOT EXISTS tbl_part_of_speech (
    pos_id                 BIGINT(20)       NOT NULL,
    part_of_speech         VARCHAR(255)     NOT NULL,
    short_from             VARCHAR(50)      NOT NULL,
    CONSTRAINT pk_part_of_speech_pos_id PRIMARY KEY(pos_id),
    CONSTRAINT uk_part_of_speech_part_of_speech UNIQUE(part_of_speech)
);

CREATE TABLE IF NOT EXISTS tbl_word_pos_mapping (
    word_id                BIGINT(20)       NOT NULL,
    pos_id                 BIGINT(20)       NOT NULL,
    CONSTRAINT pk_word_pos_mapping_word_id PRIMARY KEY(word_id, pos_id),
    CONSTRAINT fk_word_pos_mapping_pos_id_part_of_speech_pos_id FOREIGN KEY(pos_id) REFERENCES tbl_part_of_speech(pos_id),
    CONSTRAINT fk_word_pos_mapping_word_id_word_data_word_id FOREIGN KEY(word_id) REFERENCES tbl_word_data(word_id)
);

CREATE TABLE IF NOT EXISTS tbl_pronunciation (
    word_id                BIGINT(20)       NOT NULL,
    pronunciation_id       BIGINT(20)       NOT NULL,
    accent                 TEXT             NOT NULL,
    pronunciation_type     TEXT             NOT NULL DEFAULT 'none' CHECK (pronunciation_type IN ('none', 'uk', 'us', 'au', 'other')),
    ipa                    TEXT             NOT NULL,
    audio_url              TEXT             NULL,
    CONSTRAINT pk_pronunciation_pronunciation_id PRIMARY KEY(pronunciation_id),
    CONSTRAINT fk_pronunciation_word_id_word_data_word_id FOREIGN KEY(word_id) REFERENCES tbl_word_data(word_id)
);

CREATE TABLE IF NOT EXISTS tbl_meaning (
    word_id                BIGINT(20)       NOT NULL,
    pos_id                 BIGINT(20)       NOT NULL,
    meaning_id             BIGINT(20)       NOT NULL,
    definition             TEXT             NOT NULL,
    meaning_word_id        BIGINT(20)       NULL,
    CONSTRAINT pk_meaning_meaning_id PRIMARY KEY(meaning_id),
    CONSTRAINT fk_meaning_meaning_word_id_word_data_word_id FOREIGN KEY(meaning_word_id) REFERENCES tbl_word_data(word_id),
    CONSTRAINT fk_meaning_pos_id_part_of_speech_pos_id FOREIGN KEY(pos_id) REFERENCES tbl_part_of_speech(pos_id),
    CONSTRAINT fk_meaning_word_id_word_data_word_id FOREIGN KEY(word_id) REFERENCES tbl_word_data(word_id)
);

CREATE TABLE IF NOT EXISTS tbl_translation (
    target_language_id     BIGINT(20)       NOT NULL,
    word_id                BIGINT(20)       NOT NULL,
    translation_id         BIGINT(20)       NOT NULL,
    translated_word        TEXT             NOT NULL,
    CONSTRAINT pk_translation_translation_id PRIMARY KEY(translation_id),
    CONSTRAINT fk_translation_target_language_id_language_data_id FOREIGN KEY(target_language_id) REFERENCES tbl_language_data(id),
    CONSTRAINT fk_translation_word_id_word_data_word_id FOREIGN KEY(word_id) REFERENCES tbl_word_data(word_id)
);

CREATE TABLE IF NOT EXISTS tbl_usages (
    word_id                BIGINT(20)       NOT NULL,
    usage_id               BIGINT(20)       NOT NULL,
    usage_sentence         TEXT             NOT NULL,
    CONSTRAINT pk_usages_usage_id PRIMARY KEY(usage_id),
    CONSTRAINT fk_usages_word_id_word_data_word_id FOREIGN KEY(word_id) REFERENCES tbl_word_data(word_id)
);

CREATE TABLE IF NOT EXISTS tbl_synonyms (
    word_id                BIGINT(20)       NOT NULL,
    synonym_id             BIGINT(20)       NOT NULL,
    synonym_word_id        BIGINT(20)       NOT NULL,
    CONSTRAINT pk_synonyms_synonym_id PRIMARY KEY(synonym_id),
    CONSTRAINT fk_synonyms_synonym_word_id_word_data_word_id FOREIGN KEY(synonym_word_id) REFERENCES tbl_word_data(word_id),
    CONSTRAINT fk_synonyms_word_id_word_data_word_id FOREIGN KEY(word_id) REFERENCES tbl_word_data(word_id)
);

CREATE TABLE IF NOT EXISTS tbl_antonyms (
    word_id                BIGINT(20)       NOT NULL,
    antonym_id             BIGINT(20)       NOT NULL,
    antonym_word_id        BIGINT(20)       NOT NULL,
    CONSTRAINT pk_antonyms_antonym_id PRIMARY KEY(antonym_id),
    CONSTRAINT fk_antonyms_antonym_word_id_word_data_word_id FOREIGN KEY(antonym_word_id) REFERENCES tbl_word_data(word_id),
    CONSTRAINT fk_antonyms_word_id_word_data_word_id FOREIGN KEY(word_id) REFERENCES tbl_word_data(word_id)
);



DELETE FROM tbl_word_pos_mapping;
DELETE FROM tbl_pronunciation;
DELETE FROM tbl_meaning;
DELETE FROM tbl_translation;
DELETE FROM tbl_usages;
DELETE FROM tbl_synonyms;
DELETE FROM tbl_antonyms;
DELETE FROM tbl_word_data;
DELETE FROM tbl_language_data;
DELETE FROM tbl_part_of_speech;

-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174282467121447746, 'dictionary_database_schema', '1.1.0', 'tbl_', NULL, '2025-03-24 14:57:51', '2025-03-24 14:57:51');

database schema design for dictionary sql for sqlite database. if need any modify do it. Bidirectional dictionary (word to meaning & meaning to word) both side dictionary like word to meaning definition and meaning definition to word. one word may have multiple parts of speech. different type of pronunciation like us, uk.