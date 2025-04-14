-- SQLite Database DATE CREATED: 2025-03-29, DATE MODIFIED: 2025-03-29 - VERSION: v-1.1.0
-- DATABASE NAME: User Manager Database Schema



CREATE DATABASE IF NOT EXISTS user_manager_database_schema;
USE user_manager_database_schema;




DROP TABLE IF EXISTS tbl_user_data_extension;
DROP TABLE IF EXISTS tbl_user_manual_account;
DROP TABLE IF EXISTS tbl_user_social_account;
DROP TABLE IF EXISTS tbl_user_password;
DROP TABLE IF EXISTS tbl_user_email;
DROP TABLE IF EXISTS tbl_user_mobile;
DROP TABLE IF EXISTS tbl_user_token;
DROP TABLE IF EXISTS tbl_user_sys_user_role;
DROP TABLE IF EXISTS tbl_user_session;
DROP TABLE IF EXISTS tbl_users_last_activity;
DROP TABLE IF EXISTS tbl_user_login_attempt;
DROP TABLE IF EXISTS tbl_user_password_reset;
DROP TABLE IF EXISTS tbl_user_mfa_settings;
DROP TABLE IF EXISTS tbl_user_preferences;
DROP TABLE IF EXISTS tbl_user_consents;
DROP TABLE IF EXISTS tbl_user_webhooks;
DROP TABLE IF EXISTS tbl_user_profile;
DROP TABLE IF EXISTS tbl_user_profile_image;
DROP TABLE IF EXISTS tbl_user_data;
DROP TABLE IF EXISTS tbl_user_open_account;
DROP TABLE IF EXISTS tbl_user_sys_role_permission;
DROP TABLE IF EXISTS tbl_user_sys_role;
DROP TABLE IF EXISTS tbl_user_sys_permission;


CREATE TABLE IF NOT EXISTS tbl_user_data (
    user_id                       VARCHAR(36)      NOT NULL,
    username                      TEXT             NOT NULL,
    account_expiry                TIMESTAMP        NULL,
    is_verified                   TEXT             NOT NULL DEFAULT 'pending' CHECK(is_verified IN ('pending', 'verified', 'expired', 'blocked')),
    is_deleted                    TEXT             NOT NULL DEFAULT 'enabled' CHECK(is_deleted IN ('enabled', 'disabled', 'deleted', 'removed')),
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'limited', 'blocked')),
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by                    VARCHAR(36)      NOT NULL,
    modified_by                   VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_data_user_id PRIMARY KEY(user_id),
    CONSTRAINT uk_user_data_username UNIQUE(username)
);

CREATE TABLE IF NOT EXISTS tbl_user_data_extension (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    verification_code             VARCHAR(8)       NULL,
    verification_code_expiry      TIMESTAMP        NULL,
    verification_status           TEXT             NOT NULL DEFAULT 'pending' CHECK(verification_status IN ('pending', 'verified', 'expired', 'blocked')),
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by                    VARCHAR(36)      NOT NULL,
    modified_by                   VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_data_extension_id PRIMARY KEY(id),
    CONSTRAINT fk_user_data_extension_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_manual_account (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    security_question             TEXT             NULL,
    security_answer               TEXT             NULL,
    is_email_verified             BOOLEAN          NOT NULL DEFAULT TRUE,
    is_mobile_verified            BOOLEAN          NOT NULL DEFAULT TRUE,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'limited', 'blocked', 'deleted', 'removed')),
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by                    VARCHAR(36)      NOT NULL,
    modified_by                   VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_manual_account_id PRIMARY KEY(id),
    CONSTRAINT fk_user_manual_account_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_social_account (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    social_id                     VARCHAR(255)     NOT NULL,
    provider                      VARCHAR(50)      NOT NULL CHECK(provider IN ('google', 'facebook')),
    auth_token                    TEXT             NULL,
    is_verified                   BOOLEAN          NOT NULL DEFAULT TRUE,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'limited', 'blocked', 'deleted', 'removed')),
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by                    VARCHAR(36)      NOT NULL,
    modified_by                   VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_social_account_id PRIMARY KEY(id),
    CONSTRAINT uk_user_social_account_social_id UNIQUE(social_id),
    CONSTRAINT fk_user_social_account_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_open_account (
    id                            VARCHAR(36)      NOT NULL,
    ip_address                    VARCHAR(45)      NULL,
    user_agent                    TEXT             NULL,
    device                        VARCHAR(255)     NULL,
    browser                       VARCHAR(255)     NULL,
    os                            VARCHAR(255)     NULL,
    first_seen_at                 TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP,
    last_seen_at                  TIMESTAMP        NULL DEFAULT CURRENT_TIMESTAMP,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'limited', 'blocked', 'deleted', 'removed')),
    CONSTRAINT pk_user_open_account_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_user_password (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    hash_type                     TEXT             NOT NULL DEFAULT 'password_hash' CHECK(hash_type IN ('password_hash', 'SHA256', 'bcrypt', 'argon2')),
    password_salt                 TEXT             NULL,
    password_hash                 TEXT             NOT NULL,
    expiry                        TIMESTAMP        NULL,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'expired')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_password_id PRIMARY KEY(id),
    CONSTRAINT fk_user_password_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_email (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    email                         VARCHAR(320)     NOT NULL,
    provider                      VARCHAR(255)     NOT NULL DEFAULT 'user' CHECK(provider IN ('user', 'google', 'facebook')),
    is_primary                    BOOLEAN          NOT NULL DEFAULT FALSE,
    verification_code             VARCHAR(8)       NULL,
    last_verification_sent_at     TIMESTAMP        NULL,
    verification_code_expiry      TIMESTAMP        NULL,
    verification_status           TEXT             NOT NULL DEFAULT 'pending' CHECK(verification_status IN ('pending', 'verified', 'expired', 'blocked')),
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'blocked', 'deleted', 'removed')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_email_id PRIMARY KEY(id),
    CONSTRAINT uk_user_email_email UNIQUE(email),
    CONSTRAINT fk_user_email_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_mobile (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    mobile                        VARCHAR(20)      NOT NULL,
    country_code                  VARCHAR(5)       NULL,
    provider                      VARCHAR(255)     NOT NULL DEFAULT 'user' CHECK(provider IN ('user', 'google', 'facebook')),
    is_primary                    BOOLEAN          NOT NULL DEFAULT FALSE,
    verification_code             VARCHAR(8)       NULL,
    last_verification_sent_at     TIMESTAMP        NULL,
    verification_code_expiry      TIMESTAMP        NULL,
    verification_status           TEXT             NOT NULL DEFAULT 'pending' CHECK(verification_status IN ('pending', 'verified', 'expired', 'blocked')),
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'blocked', 'deleted', 'removed')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_mobile_id PRIMARY KEY(id),
    CONSTRAINT uk_user_mobile_mobile UNIQUE(mobile),
    CONSTRAINT fk_user_mobile_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_token (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    hash_type                     TEXT             NOT NULL DEFAULT 'password_hash' CHECK(hash_type IN ('password_hash', 'SHA256', 'bcrypt', 'argon2')),
    token_salt                    TEXT             NOT NULL,
    token                         TEXT             NOT NULL,
    expires_at                    TIMESTAMP        NOT NULL,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'blocked', 'deleted', 'removed')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_token_id PRIMARY KEY(id),
    CONSTRAINT fk_user_token_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_sys_role (
    id                            VARCHAR(36)      NOT NULL,
    name                          VARCHAR(255)     NOT NULL,
    description                   TEXT             NULL,
    user_type                     TEXT             NOT NULL DEFAULT 'registered' CHECK(user_type IN ('open', 'guest', 'registered', 'admin')),
    priority                      INT              NOT NULL DEFAULT 0,
    is_default                    BOOLEAN          NOT NULL DEFAULT FALSE,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'blocked', 'inactive', 'deleted')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_sys_role_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_user_sys_permission (
    id                            VARCHAR(36)      NOT NULL,
    name                          VARCHAR(255)     NOT NULL,
    description                   TEXT             NULL,
    user_type                     TEXT             NOT NULL DEFAULT 'registered' CHECK(user_type IN ('open', 'guest', 'registered', 'admin')),
    scope                         TEXT             NOT NULL DEFAULT 'global' CHECK(scope IN ('global', 'organization', 'team', 'user')),
    is_public                     BOOLEAN          NOT NULL DEFAULT FALSE,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'blocked', 'inactive', 'deleted')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_sys_permission_id PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS tbl_user_sys_role_permission (
    role_id                       VARCHAR(36)      NOT NULL,
    permission_id                 VARCHAR(36)      NOT NULL,
    description                   TEXT             NULL,
    is_active                     BOOLEAN          NOT NULL DEFAULT TRUE,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT fk_user_sys_role_permission_permission_id_user_sys_permission_id FOREIGN KEY(permission_id) REFERENCES tbl_user_sys_permission(id),
    CONSTRAINT fk_user_sys_role_permission_role_id_user_sys_role_id FOREIGN KEY(role_id) REFERENCES tbl_user_sys_role(id)
);

CREATE TABLE IF NOT EXISTS tbl_user_sys_user_role (
    user_id                       VARCHAR(36)      NOT NULL,
    role_id                       VARCHAR(36)      NOT NULL,
    assigned_by                   VARCHAR(36)      NOT NULL,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT fk_user_sys_user_role_role_id_user_sys_role_id FOREIGN KEY(role_id) REFERENCES tbl_user_sys_role(id),
    CONSTRAINT fk_user_sys_user_role_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_session (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    hash_type                     TEXT             NOT NULL DEFAULT 'password_hash' CHECK(hash_type IN ('password_hash', 'SHA256', 'bcrypt', 'argon2')),
    session_salt                  TEXT             NOT NULL,
    session_id                    TEXT             NOT NULL,
    session_duration              INT(4)           NULL DEFAULT 0,
    expires_at                    TIMESTAMP        NOT NULL,
    refresh_token                 TEXT             NULL,
    ip_address                    VARCHAR(255)     NULL,
    user_agent                    VARCHAR(255)     NULL,
    device                        VARCHAR(255)     NULL,
    browser                       VARCHAR(255)     NULL,
    os                            VARCHAR(255)     NULL,
    login_time                    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    logout_time                   TIMESTAMP        NULL,
    remember_me                   BOOLEAN          NOT NULL DEFAULT FALSE,
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'blocked', 'deleted', 'removed')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_session_id PRIMARY KEY(id),
    CONSTRAINT uk_user_session_session_id UNIQUE(session_id),
    CONSTRAINT fk_user_session_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_users_last_activity (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    last_login                    TIMESTAMP        NULL,
    last_active                   TIMESTAMP        NULL,
    last_seen_at                  TIMESTAMP        NULL,
    failed_attempts               INT              NULL DEFAULT 0,
    lockout_until                 TIMESTAMP        NULL,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_users_last_activity_id PRIMARY KEY(id),
    CONSTRAINT fk_users_last_activity_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_login_attempt (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    attempt_count                 INT              NULL DEFAULT 0,
    attempt_time                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    success                       BOOLEAN          NOT NULL DEFAULT FALSE,
    ip_address                    VARCHAR(45)      NOT NULL,
    reason                        TEXT             NULL DEFAULT,
    CONSTRAINT pk_user_login_attempt_id PRIMARY KEY(id),
    CONSTRAINT fk_user_login_attempt_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_password_reset (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    reset_time                    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expiry_time                   TIMESTAMP        NULL,
    reset_method                  TEXT             NOT NULL CHECK(reset_method IN ('email', 'sms', 'manual')),
    request_ip                    VARCHAR(45)      NOT NULL,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_password_reset_id PRIMARY KEY(id),
    CONSTRAINT fk_user_password_reset_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_mfa_settings (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    method                        TEXT             NOT NULL DEFAULT 'None' CHECK(method IN ('SMS', 'Email', 'TOTP', 'None')),
    secret_key                    TEXT             NULL,
    is_enabled                    BOOLEAN          NOT NULL DEFAULT FALSE,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_mfa_settings_id PRIMARY KEY(id),
    CONSTRAINT fk_user_mfa_settings_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_preferences (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    dark_mode                     BOOLEAN          NOT NULL DEFAULT FALSE,
    notifications_enabled         BOOLEAN          NOT NULL DEFAULT TRUE,
    timezone                      VARCHAR(50)      NOT NULL DEFAULT 'UTC',
    locale                        VARCHAR(10)      NOT NULL DEFAULT 'en',
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_preferences_id PRIMARY KEY(id),
    CONSTRAINT fk_user_preferences_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_consents (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    terms_accepted                BOOLEAN          NOT NULL DEFAULT FALSE,
    marketing_opt_in              BOOLEAN          NOT NULL DEFAULT FALSE,
    privacy_policy_accepted       BOOLEAN          NOT NULL DEFAULT FALSE,
    accepted_at                   TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_consents_id PRIMARY KEY(id),
    CONSTRAINT fk_user_consents_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_webhooks (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    event_type                    TEXT             NOT NULL CHECK(event_type IN ('login', 'login_failed')),
    status                        TEXT             NOT NULL DEFAULT 'pending' CHECK(status IN ('pending', 'delivered', 'failed')),
    created_at                    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_webhooks_id PRIMARY KEY(id),
    CONSTRAINT fk_user_webhooks_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_profile (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    first_name                    VARCHAR(255)     NULL,
    last_name                     VARCHAR(255)     NULL,
    date_of_birth                 DATE             NULL,
    gender                        TEXT             NULL CHECK(gender IN ('male', 'female', 'other')),
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'limited', 'blocked')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_profile_id PRIMARY KEY(id),
    CONSTRAINT fk_user_profile_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);

CREATE TABLE IF NOT EXISTS tbl_user_profile_image (
    user_id                       VARCHAR(36)      NOT NULL,
    id                            VARCHAR(36)      NOT NULL,
    image_url                     TEXT             NOT NULL,
    is_primary                    BOOLEAN          NOT NULL DEFAULT FALSE,
    is_deleted                    TEXT             NOT NULL DEFAULT 'enabled' CHECK(is_deleted IN ('enabled', 'disabled', 'deleted', 'removed')),
    status                        TEXT             NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'limited', 'blocked')),
    modified_date                 TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_date                  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    modified_by                   VARCHAR(36)      NOT NULL,
    created_by                    VARCHAR(36)      NOT NULL,
    CONSTRAINT pk_user_profile_image_id PRIMARY KEY(id),
    CONSTRAINT fk_user_profile_image_user_id_user_data_user_id FOREIGN KEY(user_id) REFERENCES tbl_user_data(user_id)
);



DELETE FROM tbl_user_data_extension;
DELETE FROM tbl_user_manual_account;
DELETE FROM tbl_user_social_account;
DELETE FROM tbl_user_password;
DELETE FROM tbl_user_email;
DELETE FROM tbl_user_mobile;
DELETE FROM tbl_user_token;
DELETE FROM tbl_user_sys_user_role;
DELETE FROM tbl_user_session;
DELETE FROM tbl_users_last_activity;
DELETE FROM tbl_user_login_attempt;
DELETE FROM tbl_user_password_reset;
DELETE FROM tbl_user_mfa_settings;
DELETE FROM tbl_user_preferences;
DELETE FROM tbl_user_consents;
DELETE FROM tbl_user_webhooks;
DELETE FROM tbl_user_profile;
DELETE FROM tbl_user_profile_image;
DELETE FROM tbl_user_data;
DELETE FROM tbl_user_open_account;
DELETE FROM tbl_user_sys_role_permission;
DELETE FROM tbl_user_sys_role;
DELETE FROM tbl_user_sys_permission;


-- Database Schema: Database Schema
INSERT INTO tbl_database_schema (id, schema_name, schema_version, table_prefix, database_comment, modified_date, created_date) VALUES (174327749944965401, 'user_manager_database_schema', '1.1.0', 'tbl_', NULL, '2025-03-29 20:44:59', '2025-03-29 20:44:59');