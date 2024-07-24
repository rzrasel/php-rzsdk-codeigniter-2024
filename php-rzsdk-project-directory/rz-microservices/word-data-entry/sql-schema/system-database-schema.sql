
-- DATE CREATED: 2024-07-20, DATE MODIFIED: 2024-07-23 VERSION: 0.0.1

DROP TABLE IF EXISTS dictionary_word_meaning;
DROP TABLE IF EXISTS dictionary_word;
DROP TABLE IF EXISTS tbl_language;

CREATE TABLE IF NOT EXISTS tbl_language (
    lan_id           BIGINT(20) NOT NULL,
    lan_name         VARCHAR(255) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_tbl_language_lan_id PRIMARY KEY (lan_id)
);

CREATE TABLE IF NOT EXISTS dictionary_word (
    lan_id             BIGINT(20) NOT NULL,
    word_id            BIGINT(20) NOT NULL,
    word               TEXT NOT NULL,
    pronunciation      TEXT NOT NULL,
    accent_us          TEXT NULL,
    accent_uk          TEXT NULL,
    parts_of_speech    VARCHAR(50) NOT NULL,
    syllable           VARCHAR(255) NOT NULL,
    status             BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by        BIGINT(20) NOT NULL,
    created_by         BIGINT(20) NOT NULL,
    modified_date      DATETIME NOT NULL,
    created_date       DATETIME NOT NULL,
    CONSTRAINT pk_dictionary_word_word_id PRIMARY KEY (word_id)
);

CREATE TABLE IF NOT EXISTS dictionary_word_meaning (
    lan_id           BIGINT(20) NOT NULL,
    word_id          BIGINT(20) NOT NULL,
    meaning_id       BIGINT(20) NOT NULL,
    meaning          TEXT NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_dictionary_word_meaning_meaning_id PRIMARY KEY (meaning_id)
);

DELETE FROM dictionary_word_meaning;
DELETE FROM dictionary_word;
DELETE FROM tbl_language;