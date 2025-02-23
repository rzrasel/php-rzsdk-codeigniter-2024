-- DATE CREATED: 2024-12-26, DATE MODIFIED: 2024-12-30 VERSION: 0.0.1


DROP TABLE IF EXISTS tbl_parent_child_info;

CREATE TABLE IF NOT EXISTS tbl_parent_child_info (
    id           BIGINT(20) NOT NULL,
    parent_id    BIGINT(20) NULL,
    name         TEXT NOT NULL,
    CONSTRAINT pk_parent_child_info_id PRIMARY KEY(id)
);

DELETE FROM tbl_parent_child_info;