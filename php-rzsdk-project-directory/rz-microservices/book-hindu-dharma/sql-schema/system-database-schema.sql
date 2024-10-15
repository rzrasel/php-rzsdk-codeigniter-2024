-- DATE CREATED: 2024-08-17, DATE MODIFIED: 2024-08-20 VERSION: 0.0.1


DROP TABLE IF EXISTS tbl_language;
DROP TABLE IF EXISTS tbl_religion;
DROP TABLE IF EXISTS tbl_author;
DROP TABLE IF EXISTS tbl_book;
DROP TABLE IF EXISTS tbl_section;
DROP TABLE IF EXISTS tbl_section_info;

CREATE TABLE IF NOT EXISTS tbl_language (
    lan_id           BIGINT(20) NOT NULL,
    lan_name         VARCHAR(255) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_language_lan_id PRIMARY KEY (lan_id)
);

CREATE TABLE IF NOT EXISTS tbl_religion (
    lan_id           BIGINT(20) NOT NULL,
    religion_id      BIGINT(20) NOT NULL,
    religion_name    VARCHAR(255) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_religion_religion_id PRIMARY KEY (religion_id)
);

CREATE TABLE IF NOT EXISTS tbl_author (
    lan_id           BIGINT(20) NOT NULL,
    author_id        BIGINT(20) NOT NULL,
    author_token     TEXT NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
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

CREATE TABLE IF NOT EXISTS tbl_section (
    lan_id           BIGINT(20) NOT NULL,
    section_id       BIGINT(20) NOT NULL,
    section_token    TEXT NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_section_section_id PRIMARY KEY (section_id)
);

CREATE TABLE IF NOT EXISTS tbl_section_info (
    lan_id               BIGINT(20) NOT NULL,
    section_id           BIGINT(20) NOT NULL,
    section_parent_id    BIGINT(20) NULL,
    section_info_id      BIGINT(20) NOT NULL,
    section_name         TEXT NOT NULL,
    section_level        INT(6) NOT NULL,
    status               BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by          BIGINT(20) NOT NULL,
    created_by           BIGINT(20) NOT NULL,
    modified_date        DATETIME NOT NULL,
    created_date         DATETIME NOT NULL,
    CONSTRAINT pk_section_info_section_info_id PRIMARY KEY (section_info_id)
);

DELETE FROM tbl_language;
DELETE FROM tbl_religion;
DELETE FROM tbl_author;
DELETE FROM tbl_book;
DELETE FROM tbl_section;
DELETE FROM tbl_section_info;