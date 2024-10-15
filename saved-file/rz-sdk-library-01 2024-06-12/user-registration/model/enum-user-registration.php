<?php
namespace RzSDK\Model\User\Registration;

enum UserRegistrationEnum: string {
    case REGI_ID        = "user_regi_id";
    case REGI_STATUS    = "user_regi_status";
    case MODIFIED_BY    = "modified_by";
    case CREATED_BY     = "created_by";
    case MODIFIED_DATE  = "modified_date";
    case CREATED_DATE   = "created_date";
}
?>
<?php
/*

CREATE TABLE IF NOT EXISTS user_registration (
    user_regi_id            BIGINT(20) NOT NULL,
    user_regi_status        BOOLEAN NOT NULL DEFAULT TRUE,
    modified_by             BIGINT(20) NOT NULL,
    created_by              BIGINT(20) NOT NULL,
    modified_date           DATETIME NOT NULL,
    created_date            DATETIME NOT NULL,
    CONSTRAINT pk_user_registration_user_regi_id PRIMARY KEY (user_regi_id)
);

*/
?>