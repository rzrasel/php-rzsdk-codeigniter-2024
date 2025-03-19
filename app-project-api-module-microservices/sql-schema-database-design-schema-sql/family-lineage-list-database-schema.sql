

-- SQLite Database DATE CREATED: 2025-03-10, DATE MODIFIED: 2025-03-10 - VERSION: v-1.1.1
-- DATABASE NAME: Family Tree Lineage List Database Schema


CREATE DATABASE IF NOT EXISTS family_tree_lineage_list_database_schema;
USE family_tree_lineage_list_database_schema;


DROP TABLE IF EXISTS tbl_family_relationship;
DROP TABLE IF EXISTS tbl_family_address;
DROP TABLE IF EXISTS tbl_family_contact;
DROP TABLE IF EXISTS tbl_friendship;
DROP TABLE IF EXISTS tbl_family_member;


CREATE TABLE IF NOT EXISTS tbl_family_member (
    member_id             BIGINT(20)       NOT NULL,
    first_name            VARCHAR(255)     NOT NULL,
    last_name             VARCHAR(255)     NULL,
    gender                TEXT             NOT NULL CHECK (gender IN ('Male', 'Female', 'Other')),
    birth_date            DATE             NULL,
    death_date            DATE             NULL,
    generation            INT              NULL DEFAULT 1,
    created_at            TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT pk_family_member_member_id PRIMARY KEY(member_id)
);

CREATE TABLE IF NOT EXISTS tbl_family_relationship (
    member_id             BIGINT(20)       NOT NULL,
    related_member_id     BIGINT(20)       NOT NULL,
    relationship_id       BIGINT(20)       NOT NULL,
    relation_type         TEXT             NOT NULL CHECK (relation_type IN ('Parent', 'Child', 'Spouse', 'Sibling', 'Other')),
    CONSTRAINT pk_family_relationship_relationship_id PRIMARY KEY(relationship_id),
    CONSTRAINT fk_family_relationship_member_id_family_member_member_id FOREIGN KEY(member_id) REFERENCES tbl_family_member(member_id),
    CONSTRAINT fk_family_relationship_related_member_id_family_member_member_id FOREIGN KEY(related_member_id) REFERENCES tbl_family_member(member_id)
);

CREATE TABLE IF NOT EXISTS tbl_family_address (
    member_id             BIGINT(20)       NOT NULL,
    address_id            BIGINT(20)       NOT NULL,
    address_line1         VARCHAR(255)     NULL,
    address_line2         VARCHAR(255)     NULL,
    address_type          TEXT             NOT NULL CHECK (address_type IN ('Permanent', 'Present')),
    city                  VARCHAR(100)     NULL,
    state                 VARCHAR(100)     NULL,
    country               VARCHAR(100)     NULL,
    postal_code           VARCHAR(20)      NULL,
    CONSTRAINT pk_family_address_address_id PRIMARY KEY(address_id),
    CONSTRAINT fk_family_address_member_id_family_member_member_id FOREIGN KEY(member_id) REFERENCES tbl_family_member(member_id)
);

CREATE TABLE IF NOT EXISTS tbl_family_contact (
    member_id             BIGINT(20)       NOT NULL,
    contact_id            BIGINT(20)       NOT NULL,
    contact_type          TEXT             NOT NULL CHECK (contact_type IN ('Phone', 'Email', 'Social')),
    contact_value         VARCHAR(255)     NOT NULL,
    CONSTRAINT pk_family_contact_contact_id PRIMARY KEY(contact_id),
    CONSTRAINT fk_family_contact_member_id_family_member_member_id FOREIGN KEY(member_id) REFERENCES tbl_family_member(member_id)
);

CREATE TABLE IF NOT EXISTS tbl_friendship (
    member_id             BIGINT(20)       NOT NULL,
    friend_id             BIGINT(20)       NOT NULL,
    friendship_id         BIGINT(20)       NOT NULL,
    status                TEXT             NOT NULL DEFAULT 'Pending' CHECK (status IN ('Pending', 'Accepted', 'Rejected', 'Blocked')),
    created_at            TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT pk_friendship_friendship_id PRIMARY KEY(friendship_id),
    CONSTRAINT uk_friendship_member_id UNIQUE(member_id, friend_id),
    CONSTRAINT fk_friendship_friend_id_family_member_member_id FOREIGN KEY(friend_id) REFERENCES tbl_family_member(member_id),
    CONSTRAINT fk_friendship_member_id_family_member_member_id FOREIGN KEY(member_id) REFERENCES tbl_family_member(member_id)
);


DELETE FROM tbl_family_relationship;
DELETE FROM tbl_family_address;
DELETE FROM tbl_family_contact;
DELETE FROM tbl_friendship;
DELETE FROM tbl_family_member;


-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174161749411988040, 'family_lineage_list_database_schema', '1.1.1', 'tbl_', NULL, '2025-03-10 15:38:14', '2025-03-10 15:38:14');

