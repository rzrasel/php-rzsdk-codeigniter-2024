CREATE DATABASE IF NOT EXISTS user_management;
USE user_management;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS language_manager;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS user_emails;
DROP TABLE IF EXISTS user_mobiles;
DROP TABLE IF EXISTS user_social_accounts;
DROP TABLE IF EXISTS open_users;
DROP TABLE IF EXISTS users_last_activity;
DROP TABLE IF EXISTS user_profile;
DROP TABLE IF EXISTS user_profile_image;
DROP TABLE IF EXISTS user_token;
DROP TABLE IF EXISTS user_sessions;
DROP TABLE IF EXISTS user_login_attempts;
DROP TABLE IF EXISTS user_password_resets;
DROP TABLE IF EXISTS user_mfa_settings;
DROP TABLE IF EXISTS user_preferences;
DROP TABLE IF EXISTS user_consents;
DROP TABLE IF EXISTS webhooks;

-- Language Table
CREATE TABLE IF NOT EXISTS language_manager (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    status ENUM('active', 'inactive', 'blocked', 'removed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    username VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'limited', 'blocked', 'removed') DEFAULT 'active',
    role_type ENUM('admin', 'user', 'moderator', 'guest') DEFAULT 'user',
    account_expiry TIMESTAMP NULL,
    is_deleted BOOLEAN DEFAULT FALSE
);

-- User Emails Table
CREATE TABLE IF NOT EXISTS user_emails (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    email VARCHAR(320) UNIQUE NOT NULL,
    provider VARCHAR(50) NOT NULL,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    verification_status ENUM('pending', 'verified', 'expired', 'blocked') DEFAULT 'pending',
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    verification_code VARCHAR(6),
    last_verification_sent_at TIMESTAMP NULL,
    verification_code_expiry TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Mobiles Table
CREATE TABLE IF NOT EXISTS user_mobiles (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    country_code VARCHAR(5) NOT NULL,
    provider VARCHAR(50) NOT NULL,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    verification_status ENUM('pending', 'verified', 'expired', 'blocked') DEFAULT 'pending',
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    verification_code VARCHAR(6),
    last_verification_sent_at TIMESTAMP NULL,
    verification_code_expiry TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (country_code, mobile),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Social Accounts Table
CREATE TABLE IF NOT EXISTS user_social_accounts (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    provider VARCHAR(50) NOT NULL,
    social_id VARCHAR(255) NOT NULL,
    auth_token TEXT NULL,
    is_verified BOOLEAN NOT NULL DEFAULT FALSE,
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE (provider, social_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Open Users Table (for tracking unauthenticated users)
CREATE TABLE IF NOT EXISTS open_users (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    session_id VARCHAR(255) UNIQUE NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    device VARCHAR(255),
    browser VARCHAR(255),
    user_token TEXT NOT NULL,
    os VARCHAR(255),
    first_seen_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_seen_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users Last Activity Table
CREATE TABLE IF NOT EXISTS users_last_activity (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    last_login TIMESTAMP NULL,
    last_active TIMESTAMP NULL,
    last_seen_at TIMESTAMP NULL,
    failed_attempts INT DEFAULT 0,
    lockout_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Profile Table
CREATE TABLE IF NOT EXISTS user_profile (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Profile Image Table
CREATE TABLE IF NOT EXISTS user_profile_image (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    image_url TEXT NOT NULL,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Token Table
CREATE TABLE IF NOT EXISTS user_token (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    token TEXT NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Sessions Table
CREATE TABLE IF NOT EXISTS user_sessions (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    session_id VARCHAR(255) UNIQUE NOT NULL,
    device VARCHAR(255),
    browser VARCHAR(255),
    os VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent TEXT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    logout_time TIMESTAMP NULL,
    session_duration INT DEFAULT 0,
    refresh_token TEXT,
    remember_me BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Login Attempts Table
CREATE TABLE IF NOT EXISTS user_login_attempts (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    attempt_count INT DEFAULT 0,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN NOT NULL DEFAULT FALSE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Password Resets Table
CREATE TABLE IF NOT EXISTS user_password_resets (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    reset_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiry_time TIMESTAMP NULL,
    reset_method ENUM('email', 'sms', 'manual') NOT NULL,
    request_ip VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User MFA Settings Table
CREATE TABLE IF NOT EXISTS user_mfa_settings (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    method ENUM('SMS', 'Email', 'TOTP', 'None') DEFAULT 'None',
    secret_key TEXT,
    is_enabled BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Preferences Table
CREATE TABLE IF NOT EXISTS user_preferences (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    dark_mode BOOLEAN NOT NULL DEFAULT FALSE,
    notifications_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    timezone VARCHAR(50) DEFAULT 'UTC',
    locale VARCHAR(10) DEFAULT 'en',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- User Consents Table
CREATE TABLE IF NOT EXISTS user_consents (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    terms_accepted BOOLEAN NOT NULL DEFAULT FALSE,
    marketing_opt_in BOOLEAN NOT NULL DEFAULT FALSE,
    privacy_policy_accepted BOOLEAN NOT NULL DEFAULT FALSE,
    accepted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Webhooks Table
CREATE TABLE IF NOT EXISTS webhooks (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    event_type ENUM('login', 'login_failed') NOT NULL,
    status ENUM('pending', 'delivered', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);





-- Table for storing roles
CREATE TABLE IF NOT EXISTS tbl_roles (
    id          VARCHAR(36) NOT NULL PRIMARY KEY,
    name        VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    status      TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'deleted')),
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Table for storing permissions
CREATE TABLE IF NOT EXISTS tbl_permissions (
    id          VARCHAR(36) NOT NULL PRIMARY KEY,
    name        VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL,
    status      TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'inactive', 'deleted')),
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Mapping table for role-permission relationships (Many-to-Many)
CREATE TABLE IF NOT EXISTS tbl_role_permissions (
    role_id      VARCHAR(36) NOT NULL,
    permission_id VARCHAR(36) NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES tbl_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES tbl_permissions(id) ON DELETE CASCADE
    );

-- Table for storing user-role relationships (Many-to-Many)
CREATE TABLE IF NOT EXISTS tbl_user_roles (
    user_id  VARCHAR(36) NOT NULL,
    role_id  VARCHAR(36) NOT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES tbl_user_data(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES tbl_roles(id) ON DELETE CASCADE
    );




-- Delete Tables
DELETE FROM language_manager;
DELETE FROM users;
DELETE FROM user_emails;
DELETE FROM user_mobiles;
DELETE FROM user_social_accounts;
DELETE FROM open_users;
DELETE FROM users_last_activity;
DELETE FROM user_profile;
DELETE FROM user_profile_image;
DELETE FROM user_token;
DELETE FROM user_sessions;
DELETE FROM user_login_attempts;
DELETE FROM user_password_resets;
DELETE FROM user_mfa_settings;
DELETE FROM user_preferences;
DELETE FROM user_consents;
DELETE FROM webhooks;



Language, User login, registration, user token, user can login with mobile, email, social media, user may have multiple email, multiple mobile, multiple social account, user verification, if user not verified a time limit user become limited and then time bings block or removed, user role type, user role, user verification, email verification, user role type, mobile verification, email mobile social account enable disabled or block, login verification code, registration verification code, user information, login attempts, multi-Factor Authentication, login attempts count in times if a time limit exceed login attempts temporary block a time limit, password reset, track last login date time, last active time, total active and use time daily monthly yearly tracking option, user mapping option if an user have multiple account using email mobile and social media, user preferences (mode, notification), user sessions device used (browser, os, device), user consent (terms, marketing, privacy), mfa settings TOTP base method (SMS, Email, TOTP, none), webhook (login, login failed) status (pending, delivered), remember me, user password resets tracking last reset information, database schema. Can handel open user or user without login. Need to handel open user or user without login, track them, trace them.

General Improvements:

* Add, remove, modify field.
* Add schema, table name, column, column data type if needed.
* Remove schema, table name, column, column data type if needed.
* Update schema, table name, column, column data type if needed.
* Modify schema, table name, column, column data type if needed.
* Give full MySql DROP TABLE sql query.
* Give full MySql CREATE TABLE sql query.
* Give full MySql DELETE TABLE sql query.



CREATE TABLE users (
    id TEXT PRIMARY KEY DEFAULT (lower(hex(randomblob(16)))),
    username TEXT NOT NULL UNIQUE,
    status TEXT NOT NULL CHECK(status IN ('active', 'limited', 'blocked', 'removed')),
    role_type TEXT NOT NULL CHECK(role_type IN ('admin', 'user', 'moderator', 'guest')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);