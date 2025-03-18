<?php

namespace Sntaks\Daraja;

use Closure;

class Config implements \ArrayAccess {
    public static array $items = [];

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config = []) {
        $config_file = __DIR__ . '/../config/mpesa.php';
        $default_config = [];
        if(is_file($config_file)){ $default_config = require $config_file; }
        self::$items = array_merge($default_config, $config);
    }

    /**
     * Determine if the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible(mixed $value): bool {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * Check if an item exists in an array using dot notation.
     *
     * @param \ArrayAccess|array $array
     * @param string $key
     * @return bool
     */
    public static function exists(\ArrayAccess|array $array, string $key): bool {
        if($array instanceof \ArrayAccess){ return $array->offsetExists($key); }
        return array_key_exists($key, $array);
    }

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key, mixed $default=null): mixed {
        $key = str_replace("mpesa.", "", $key);
        $array = self::$items;
        if(!static::accessible($array)){return self::value($default);}
        if(static::exists($array, $key)){return $array[$key];}
        if(!str_contains($key, '.')){return $array[$key]? : self::value($default);}
        foreach(explode('.', $key) as $segment){
            if(static::accessible($array) and static::exists($array,$segment)){$array = $array[$segment];}
            else{return self::value($default);}
        }
        return $array;
    }

    /**
     * Get all of the configuration items.
     *
     * @return array
     */
    public static function all(): array { return self::$items; }
    /**
     * Get a value from the configuration.
     *
     * If the value is a Closure, it will be executed and the result of the
     * execution will be returned. Otherwise the value will be returned as is.
     *
     * @param mixed $value
     * @return mixed
     */
    public static function value(mixed $value): mixed { return $value instanceof Closure ? $value() : $value; }

    /**
     * Check if a given configuration value exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool { return self::exists(self::$items, $offset); }
    /**
     * Get a configuration value by key.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed { return self::get($offset); }
    /**
     * Set a configuration value.
     *
     * @param string $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void { self::$items[$offset] = $value; }
    /**
     * Remove a configuration value by key.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void { unset(self::$items[$offset]); }
}