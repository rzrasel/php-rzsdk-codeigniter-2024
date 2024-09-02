-- DATE CREATED: 2024-08-24, DATE MODIFIED: 2024-09-02 VERSION: 0.0.1


DROP TABLE IF EXISTS tbl_language;
DROP TABLE IF EXISTS category_index;
DROP TABLE IF EXISTS book_index;
DROP TABLE IF EXISTS book_info;


CREATE TABLE IF NOT EXISTS tbl_language (
    lan_id           BIGINT(20) NOT NULL,
    lan_name         VARCHAR(255) NOT NULL,
    slug             VARCHAR(255) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_language_lan_id PRIMARY KEY (lan_id)
);

CREATE TABLE IF NOT EXISTS category_index (
    cat_token_id       BIGINT(20) NOT NULL,
    cat_token_name     TEXT NOT NULL,
    slug               TEXT NOT NULL,
    cat_token_order    INT(5) NOT NULL,
    status             BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by        BIGINT(20) NOT NULL,
    created_by         BIGINT(20) NOT NULL,
    modified_date      DATETIME NOT NULL,
    created_date       DATETIME NOT NULL,
    CONSTRAINT pk_category_index_cat_token_id PRIMARY KEY (cat_token_id)
);

CREATE TABLE IF NOT EXISTS book_index (
    book_token_id      BIGINT(20) NOT NULL,
    book_token_name    VARCHAR(255) NOT NULL,
    slug               TEXT NOT NULL,
    status             BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by        BIGINT(20) NOT NULL,
    created_by         BIGINT(20) NOT NULL,
    modified_date      DATETIME NOT NULL,
    created_date       DATETIME NOT NULL,
    CONSTRAINT pk_book_index_book_token_id PRIMARY KEY (book_token_id)
);

CREATE TABLE IF NOT EXISTS book_info (
    lan_id              BIGINT(20) NOT NULL,
    book_token_id       BIGINT(20) NOT NULL,
    book_info_id        BIGINT(20) NOT NULL,
    book_info_name      TEXT NOT NULL,
    book_name_prefix    TEXT NULL,
    book_name_suffix    TEXT NULL,
    slug                TEXT NOT NULL,
    status              BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by         BIGINT(20) NOT NULL,
    created_by          BIGINT(20) NOT NULL,
    modified_date       DATETIME NOT NULL,
    created_date        DATETIME NOT NULL,
    CONSTRAINT pk_book_info_book_token_id PRIMARY KEY (book_token_id)
);


DELETE FROM tbl_language;
DELETE FROM category_index;
DELETE FROM book_index;
DELETE FROM book_info;