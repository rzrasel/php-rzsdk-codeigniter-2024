<?php
namespace Core\Data\Request;

abstract class BaseRequest {

    abstract public function rules(): array;

    public function authorize(): bool {
        return true;
    }

    public function validationData(): array {
        return (array) $this;
    }

    public function __get(string $name) {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        return null;
    }

    public function __set(string $name, $value): void {
        $this->{$name} = $value;
    }

    public function __call(string $method, array $parameters) {
        throw new \BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
?>