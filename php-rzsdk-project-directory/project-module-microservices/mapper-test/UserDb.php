<?php
class UserDb {
    private $id;
    private $name;
    private $emails;

    public function __construct($id = null, $name = '', $emails = []) {
        $this->id = $id;
        $this->name = $name;
        $this->emails = $emails;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmails() {
        return $this->emails;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmails($emails) {
        $this->emails = $emails;
    }
}

class EmailDb {
    private $email;

    public function __construct($email = '') {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
}

class User {
    private $id;
    private $name;
    private $emails;

    public function __construct($id = null, $name = '', $emails = []) {
        $this->id = $id;
        $this->name = $name;
        $this->emails = $emails;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmails() {
        return $this->emails;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmails($emails) {
        $this->emails = $emails;
    }
}

class Email {
    private $email;

    public function __construct($email = '') {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
}

class UserMapper {
    public static function mapDbToDomain(UserDb $userDb): User {
        $emails = [];
        foreach ($userDb->getEmails() as $emailDb) {
            $emails[] = new Email($emailDb->getEmail());
        }

        return new User(
            $userDb->getId(),
            $userDb->getName(),
            $emails
        );
    }

    public static function mapDomainToDb(User $user): UserDb {
        $emails = [];
        foreach ($user->getEmails() as $email) {
            $emails[] = new EmailDb($email->getEmail());
        }

        return new UserDb(
            $user->getId(),
            $user->getName(),
            $emails
        );
    }
}

// Example Usage

// Create UserDb object
$userDb = new UserDb(
    1,
    'John Doe',
    [new EmailDb('john@example.com'), new EmailDb('doe@example.com')]
);

// Map UserDb to User
$user = UserMapper::mapDbToDomain($user);
echo "User Domain:\n";
echo "ID: " . $user->getId() . "\n";
echo "Name: " . $user->getName() . "\n";
echo "Emails:\n";
foreach ($user->getEmails() as $email) {
    echo "- " . $email->getEmail() . "\n";
}

// Map User back to UserDb
$userDbMapped = UserMapper::mapDomainToDb($user);
echo "\nUserDb Mapped Back:\n";
echo "ID: " . $userDbMapped->getId() . "\n";
echo "Name: " . $userDbMapped->getName() . "\n";
echo "Emails:\n";
foreach ($userDbMapped->getEmails() as $emailDb) {
    echo "- " . $emailDb->getEmail() . "\n";
}
?>