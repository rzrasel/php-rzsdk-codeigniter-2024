<?php
namespace RzSDK\Curl;
?>
<?php
use \ArrayAccess;
use \Iterator;
use \Countable;
?>
<?php
class CaseInsensitiveArray implements ArrayAccess, Countable, Iterator {
    private $data = [];
    private $keys = [];

    public function __construct(array $initial = null)
    {
        if ($initial !== null) {
            foreach ($initial as $key => $value) {
                $this->offsetSet($key, $value);
            }
        }
    }
    
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value): void {
        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $offsetlower = strtolower($offset);
            $this->data[$offsetlower] = $value;
            $this->keys[$offsetlower] = $offset;
        }
    }
    
    #[\ReturnTypeWillChange]
    public function offsetExists($offset): bool {
        return (bool) array_key_exists(strtolower($offset), $this->data);
    }
    
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset): void {
        $offsetlower = strtolower($offset);
        unset($this->data[$offsetlower]);
        unset($this->keys[$offsetlower]);
    }
    
    #[\ReturnTypeWillChange]
    public function offsetGet($offset): mixed {
        $offsetlower = strtolower($offset);
        return $this->data[$offsetlower] ?? null;
    }
    
    public function count(): int {
        return (int) count($this->data);
    }
    
    public function current(): mixed {
        return current($this->data);
    }
    
    public function next(): void {
        next($this->data);
    }
    
    public function key(): mixed {
        $key = key($this->data);
        return $this->keys[$key] ?? $key;
    }
    
    public function valid(): bool {
        return (bool) (key($this->data) !== null);
    }
    
    public function rewind(): void {
        reset($this->data);
    }
}
?>
