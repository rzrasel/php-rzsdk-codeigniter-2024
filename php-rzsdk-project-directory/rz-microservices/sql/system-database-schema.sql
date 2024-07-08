
-- DATE: 2024-06-14, VERSION: 0.0.2

DROP TABLE IF EXISTS user_registration;
DROP TABLE IF EXISTS user_info;
DROP TABLE IF EXISTS user_password;
DROP TABLE IF EXISTS user_login_auth_log;

CREATE TABLE IF NOT EXISTS user_registration (
    user_regi_id    BIGINT(20) NOT NULL,
    email           TEXT NOT NULL,
    status          BOOLEAN NOT NULL DEFAULT TRUE,
    is_verified     BOOLEAN NOT NULL DEFAULT FALSE,
    regi_date       DATETIME NOT NULL,
    device_type     VARCHAR(32) NOT NULL,
    auth_type       VARCHAR(32) NOT NULL,
    agent_type      VARCHAR(32) NOT NULL,
    regi_os         VARCHAR(32) NULL,
    regi_device     VARCHAR(32) NULL,
    regi_browser    VARCHAR(32) NULL,
    regi_ip         VARCHAR(32) NOT NULL,
    regi_http_agent TEXT NOT NULL,
    modified_by     BIGINT(20) NOT NULL,
    created_by      BIGINT(20) NOT NULL,
    modified_date   DATETIME NOT NULL,
    created_date    DATETIME NOT NULL,
    CONSTRAINT pk_user_registration_user_regi_id PRIMARY KEY (user_regi_id)
);

-- INSERT INTO user_registration VALUES("171187607072497731", "email@gmail.com", TRUE, "171187607072497731", "171187607072497731", "2024-03-31 15:35:31", "2024-03-31 15:35:31");

CREATE TABLE IF NOT EXISTS user_info (
    user_id         BIGINT(20) NOT NULL,
    email           TEXT NOT NULL,
    status          BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by     BIGINT(20) NOT NULL,
    created_by      BIGINT(20) NOT NULL,
    modified_date   DATETIME NOT NULL,
    created_date    DATETIME NOT NULL,
    CONSTRAINT pk_user_info_user_id PRIMARY KEY (user_id)
);

-- INSERT INTO user VALUES("171187607072497731", "email@gmail.com", TRUE, "171187607072497731", "171187607072497731", "2024-03-31 15:35:31", "2024-03-31 15:35:31");

CREATE TABLE IF NOT EXISTS user_password (
    user_id         BIGINT(20) NOT NULL,
    password        TEXT NOT NULL,
    status          BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by     BIGINT(20) NOT NULL,
    created_by      BIGINT(20) NOT NULL,
    modified_date   DATETIME NOT NULL,
    created_date    DATETIME NOT NULL,
    CONSTRAINT pk_user_password_user_id PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS user_login_auth_log (
    user_id            BIGINT(20) NOT NULL,
    status             BOOLEAN NOT NULL DEFAULT TRUE,
    assigned_date      DATETIME NOT NULL,
    expired_date       DATETIME NOT NULL,
    encrypt_type       VARCHAR(255) NOT NULL,
    mcrypt_key         TEXT NULL,
    mcrypt_iv          TEXT NULL,
    auth_token         TEXT NOT NULL,
    device_type        VARCHAR(32) NOT NULL,
    auth_type          VARCHAR(32) NOT NULL,
    agent_type         VARCHAR(32) NOT NULL,
    regi_os            VARCHAR(32) NULL,
    regi_device        VARCHAR(32) NULL,
    regi_browser       VARCHAR(32) NULL,
    regi_ip            VARCHAR(32) NOT NULL,
    regi_http_agent    TEXT NOT NULL,
    modified_by        BIGINT(20) NOT NULL,
    created_by         BIGINT(20) NOT NULL,
    modified_date      DATETIME NOT NULL,
    created_date       DATETIME NOT NULL,
    CONSTRAINT pk_user_login_auth_log_user_id PRIMARY KEY (user_id)
);

DELETE FROM user_registration;
DELETE FROM user_info;
DELETE FROM user_password;
DELETE FROM user_login_auth_log;















-- DATE: 2024-04-05, VERSION: 0.0.1

DROP TABLE IF EXISTS user_password;
DROP TABLE IF EXISTS user_password_meta;
DROP TABLE IF EXISTS user_login_key;
DROP TABLE IF EXISTS user_identity;
DROP TABLE IF EXISTS user_registration;
DROP TABLE IF EXISTS enrollment_type;


-- DROP TABLE IF EXISTS enrollment_type;

CREATE TABLE IF NOT EXISTS enrollment_type (
    enrollment_id           BIGINT(20) NOT NULL,
    enrollment_type         TEXT NOT NULL,
    enrollment_status       BOOLEAN NOT NULL DEFAULT TRUE,
    modified_date           DATETIME NOT NULL,
    created_date            DATETIME NOT NULL,
    CONSTRAINT pk_enrollment_type_enrollment_id PRIMARY KEY (enrollment_id),
    CONSTRAINT uk_enrollment_type_enrollment_type UNIQUE (enrollment_type)
);

INSERT INTO enrollment_type VALUES("171182948560812938", "registered", TRUE, "2024-03-31 15:33:05", "2024-03-31 15:33:05");
INSERT INTO enrollment_type VALUES("171187607072497731", "loggined", TRUE, "2024-03-31 15:35:31", "2024-03-31 15:35:31");

-- DROP TABLE IF EXISTS user_registration;

CREATE TABLE IF NOT EXISTS user_registration (
    user_regi_id            BIGINT(20) NOT NULL,
    user_regi_status        BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by             BIGINT(20) NOT NULL,
    created_by              BIGINT(20) NOT NULL,
    modified_date           DATETIME NOT NULL,
    created_date            DATETIME NOT NULL,
    CONSTRAINT pk_user_registration_user_regi_id PRIMARY KEY (user_regi_id)
);

-- DROP TABLE IF EXISTS user_identity;

CREATE TABLE IF NOT EXISTS user_identity (
    user_regi_id            BIGINT(20) NOT NULL,
    enrollment_id           BIGINT(20) NOT NULL,
    user_identity_id        BIGINT(20) NOT NULL,
    user_status             BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by             BIGINT(20) NOT NULL,
    created_by              BIGINT(20) NOT NULL,
    modified_date           DATETIME NOT NULL,
    created_date            DATETIME NOT NULL,
    CONSTRAINT pk_user_identity_user_identity_id PRIMARY KEY (user_identity_id),
    CONSTRAINT fk_user_identity_user_regi_id FOREIGN KEY (user_regi_id) REFERENCES user_registration(user_regi_id),
    CONSTRAINT fk_user_identity_enrollment_id FOREIGN KEY (enrollment_id) REFERENCES enrollment_type(enrollment_id)
);

-- DROP TABLE IF EXISTS user_password_meta;

CREATE TABLE IF NOT EXISTS user_password_meta (
    user_identity_id        BIGINT(20) NOT NULL,
    user_pass_meta_id       BIGINT(20) NOT NULL,
    hash_algorithom         TEXT NOT NULL,
    password_hash           TEXT NULL,
    password_salt           TEXT NULL,
    pasword_recovery_token  TEXT NULL,
    recovery_token_time     DATETIME NULL,
    user_pass_meta_status   BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by             BIGINT(20) NOT NULL,
    created_by              BIGINT(20) NOT NULL,
    modified_date           DATETIME NOT NULL,
    created_date            DATETIME NOT NULL,
    CONSTRAINT pk_user_password_meta_user_pass_meta_id PRIMARY KEY (user_pass_meta_id),
    CONSTRAINT fk_user_password_meta_user_identity_id FOREIGN KEY (user_identity_id) REFERENCES user_identity(user_identity_id)
);

-- DROP TABLE IF EXISTS user_password;

CREATE TABLE IF NOT EXISTS user_password (
    user_pass_meta_id       BIGINT(20) NOT NULL,
    user_password_id        BIGINT(20) NOT NULL,
    password                TEXT NOT NULL,
    user_password_status    BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by             BIGINT(20) NOT NULL,
    created_by              BIGINT(20) NOT NULL,
    modified_date           DATETIME NOT NULL,
    created_date            DATETIME NOT NULL,
    CONSTRAINT pk_user_password_user_password_id PRIMARY KEY (user_password_id),
    CONSTRAINT fk_user_password_user_pass_meta_id FOREIGN KEY (user_pass_meta_id) REFERENCES user_password_meta(user_pass_meta_id)
);

-- DROP TABLE IF EXISTS user_login_key;

CREATE TABLE IF NOT EXISTS user_login_key (
    user_identity_id        BIGINT(20) NOT NULL,
    user_login_key_id       BIGINT(20) NOT NULL,
    user_login_key          TEXT NOT NULL,
    user_login_key_status   BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by             BIGINT(20) NOT NULL,
    created_by              BIGINT(20) NOT NULL,
    modified_date           DATETIME NOT NULL,
    created_date            DATETIME NOT NULL,
    CONSTRAINT pk_user_login_key_user_login_key_id PRIMARY KEY (user_login_key_id),
    CONSTRAINT fk_user_login_key_user_identity_id FOREIGN KEY (user_identity_id) REFERENCES user_identity(user_identity_id)
);


/*

-- https://www.facebook.com/reel/971549021305245
-- https://www.facebook.com/reel/955959129506553

Android Compose: Create a simple MVVM project with basic four layers
https://medium.com/@anteprocess/android-compose-create-a-simple-mvvm-project-with-basic-four-layers-776b586d00af
Building an Android App with Jetpack Compose, Retrofit, and MVVM Architecture
https://medium.com/@jecky999/building-an-android-app-with-jetpack-compose-retrofit-and-mvvm-architecture-12a5e03eb03a
Jetpack Compose Android App with MVVM Architecture and Retrofit - API Integration
https://medium.com/@dheerubhadoria/jetpack-compose-android-app-with-mvvm-architecture-and-retrofit-api-integration-4eb61ca6fbf2

Retrofit with MVVM in Jetpack Compose
https://saurabhjadhavblogs.com/retrofit-with-mvvm-in-jetpack-compose
Compose-MVVM-Retrofit-ViewMode-LiveData-Complete-Example-Android-App
https://github.com/dheeraj-bhadoria/Compose-MVVM-Retrofit-ViewMode-LiveData-Complete-Example-Android-App
Android-Kotlin-Mvvm-Dagger-Retrofit
https://github.com/GeekySingh/Android-Kotlin-Mvvm-Dagger-Retrofit

*/