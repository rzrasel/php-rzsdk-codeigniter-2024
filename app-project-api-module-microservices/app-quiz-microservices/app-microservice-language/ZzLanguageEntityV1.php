<?php
namespace Domain\Models;
?>
<?php
class LanguageEntity {
    private $id;
    private $iso_code_2;
    private $iso_code_3;
    private $name;

    // Getters and Setters
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function getIsoCode2(): string {
        return $this->iso_code_2;
    }

    public function setIsoCode2(string $iso_code_2): void {
        $this->iso_code_2 = $iso_code_2;
    }

    public function getIsoCode3(): string {
        return $this->iso_code_3;
    }

    public function setIsoCode3(string $iso_code_3): void {
        $this->iso_code_3 = $iso_code_3;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }
}
?>