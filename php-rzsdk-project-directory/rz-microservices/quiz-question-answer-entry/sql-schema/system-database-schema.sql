-- DATE CREATED: 2024-08-24, DATE MODIFIED: 2024-08-24 VERSION: 0.0.1


DROP TABLE IF EXISTS tbl_language;
DROP TABLE IF EXISTS quiz_category;
DROP TABLE IF EXISTS quiz_subject;

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

CREATE TABLE IF NOT EXISTS quiz_category (
    lan_id            BIGINT(20) NOT NULL,
    category_id       BIGINT(20) NOT NULL,
    category_name     TEXT NOT NULL,
    slug              TEXT NOT NULL,
    category_order    INT(5) NOT NULL,
    status            BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by       BIGINT(20) NOT NULL,
    created_by        BIGINT(20) NOT NULL,
    modified_date     DATETIME NOT NULL,
    created_date      DATETIME NOT NULL,
    CONSTRAINT pk_quiz_category_lan_id PRIMARY KEY (lan_id)
);

CREATE TABLE IF NOT EXISTS quiz_subject (
    lan_id           BIGINT(20) NOT NULL,
    subject_id       BIGINT(20) NOT NULL,
    subject_name     TEXT NOT NULL,
    slug             TEXT NOT NULL,
    subject_order    INT(5) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    is_quiz_mode     BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_quiz_subject_lan_id PRIMARY KEY (lan_id)
);

DELETE FROM tbl_language;
DELETE FROM quiz_category;
DELETE FROM quiz_subject;