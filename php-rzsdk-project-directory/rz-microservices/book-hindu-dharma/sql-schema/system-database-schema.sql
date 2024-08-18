-- DATE CREATED: 2024-08-17, DATE MODIFIED: 2024-08-17 VERSION: 0.0.1

DROP TABLE IF EXISTS tbl_language;
DROP TABLE IF EXISTS tbl_religion;
DROP TABLE IF EXISTS tbl_author;
DROP TABLE IF EXISTS tbl_book;

CREATE TABLE IF NOT EXISTS tbl_language (
    lan_id           BIGINT(20) NOT NULL,
    lan_name_bn      VARCHAR(255) NOT NULL,
    lan_name_en      VARCHAR(255) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_language_lan_id PRIMARY KEY (lan_id)
);

CREATE TABLE IF NOT EXISTS tbl_religion (
    religion_id         BIGINT(20) NOT NULL,
    religion_name_bn    VARCHAR(255) NOT NULL,
    religion_name_en    VARCHAR(255) NOT NULL,
    status              BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by         BIGINT(20) NOT NULL,
    created_by          BIGINT(20) NOT NULL,
    modified_date       DATETIME NOT NULL,
    created_date        DATETIME NOT NULL,
    CONSTRAINT pk_religion_religion_id PRIMARY KEY (religion_id)
);

CREATE TABLE IF NOT EXISTS tbl_author (
    author_id         BIGINT(20) NOT NULL,
    author_name_bn    TEXT NOT NULL,
    author_name_en    TEXT NOT NULL,
    status            BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by       BIGINT(20) NOT NULL,
    created_by        BIGINT(20) NOT NULL,
    modified_date     DATETIME NOT NULL,
    created_date      DATETIME NOT NULL,
    CONSTRAINT pk_author_author_id PRIMARY KEY (author_id)
);

CREATE TABLE IF NOT EXISTS tbl_book (
    book_id          BIGINT(20) NOT NULL,
    book_name_bn     TEXT NOT NULL,
    book_name_en     TEXT NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_book_book_id PRIMARY KEY (book_id)
);

DELETE FROM tbl_language;
DELETE FROM tbl_religion;
DELETE FROM tbl_author;
DELETE FROM tbl_book;