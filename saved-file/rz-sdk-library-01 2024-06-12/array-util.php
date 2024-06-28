<?php
namespace RzSDK\Curl;
?>
<?php
class ArrayUtil {
    public static function isArrayAssoc($array) {
        return (
            $array instanceof CaseInsensitiveArray ||
            (bool)count(array_filter(array_keys($array), 'is_string'))
        );
    }
    
    public static function is_array_assoc($array) {
        return static::isArrayAssoc($array);
    }
    
    public static function isArrayMultidim($array) {
        if (!is_array($array)) {
            return false;
        }

        return (bool)count(array_filter($array, 'is_array'));
    }

    public static function is_array_multidim($array) {
        return static::isArrayMultidim($array);
    }
    
    public static function arrayFlattenMultidim($array, $prefix = false) {
        $return = [];
        if (is_array($array) || is_object($array)) {
            if (empty($array)) {
                $return[$prefix] = '';
            } else {
                $arrays_to_merge = [];

                foreach ($array as $key => $value) {
                    if (is_scalar($value)) {
                        if ($prefix) {
                            $arrays_to_merge[] = [
                                $prefix . '[' . $key . ']' => $value,
                            ];
                        } else {
                            $arrays_to_merge[] = [
                                $key => $value,
                            ];
                        }
                    } elseif ($value instanceof \CURLFile) {
                        $arrays_to_merge[] = [
                            $key => $value,
                        ];
                    } elseif ($value instanceof \CURLStringFile) {
                        $arrays_to_merge[] = [
                            $key => $value,
                        ];
                    } else {
                        $arrays_to_merge[] = self::arrayFlattenMultidim(
                            $value,
                            $prefix ? $prefix . '[' . $key . ']' : $key
                        );
                    }
                }

                $return = array_merge($return, ...$arrays_to_merge);
            }
        } elseif ($array === null) {
            $return[$prefix] = $array;
        }
        return $return;
    }
    
    public static function array_flatten_multidim($array, $prefix = false) {
        return static::arrayFlattenMultidim($array, $prefix);
    }
    
    public static function arrayRandom($array) {
        return $array[static::arrayRandomIndex($array)];
    }
    
    public static function arrayRandomIndex($array) {
        return mt_rand(0, count($array) - 1);
    }
    
    public static function array_random($array) {
        return static::arrayRandom($array);
    }
}
?>