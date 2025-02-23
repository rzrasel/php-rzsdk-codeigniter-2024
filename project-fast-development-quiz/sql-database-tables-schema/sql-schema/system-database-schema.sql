-- DATE CREATED: 2024-12-26, DATE MODIFIED: 2024-12-25 VERSION: 0.0.1


DROP TABLE IF EXISTS tbl_answer_info;
DROP TABLE IF EXISTS tbl_question_info;
DROP TABLE IF EXISTS tbl_subject_info;

CREATE TABLE IF NOT EXISTS tbl_subject_info (
    subject_id       BIGINT(20) NOT NULL,
    name_bn          TEXT NOT NULL,
    name_en          TEXT NOT NULL,
    order            INT(3) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_subject_info_subject_id PRIMARY KEY(subject_id)
);

CREATE TABLE IF NOT EXISTS tbl_question_info (
    subject_id       BIGINT(20) NOT NULL,
    question_id      BIGINT(20) NOT NULL,
    question_bn      TEXT NOT NULL,
    question_en      TEXT NULL,
    order            INT(8) NOT NULL,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_question_info_question_id PRIMARY KEY(question_id),
    CONSTRAINT fk_question_info_subject_id FOREIGN KEY(subject_id) REFERENCES tbl_subject_info(subject_id)
);

CREATE TABLE IF NOT EXISTS tbl_answer_info (
    question_id      BIGINT(20) NOT NULL,
    answer_id        BIGINT(20) NOT NULL,
    answer_bn        TEXT NOT NULL,
    answer_en        TEXT NULL,
    is_correct       BOOLEAN NOT NULL DEFAULT FALSE,
    status           BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by      BIGINT(20) NOT NULL,
    created_by       BIGINT(20) NOT NULL,
    modified_date    DATETIME NOT NULL,
    created_date     DATETIME NOT NULL,
    CONSTRAINT pk_answer_info_answer_id PRIMARY KEY(answer_id),
    CONSTRAINT fk_answer_info_question_id FOREIGN KEY(question_id) REFERENCES tbl_question_info(question_id)
);

DELETE FROM tbl_answer_info;
DELETE FROM tbl_question_info;
DELETE FROM tbl_subject_info;