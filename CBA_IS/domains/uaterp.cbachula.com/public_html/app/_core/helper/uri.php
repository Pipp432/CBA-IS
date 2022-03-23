<?php

namespace _core\helper;

class uri {

    private static $uri;

    public static function setUri() {
        self::$uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
        // Trim right & left
        self::$uri = ltrim(self::$uri, '/');
        self::$uri = rtrim(self::$uri, '/');
        // Creates array out of the string using the first parameter as delimiter
        self::$uri = explode('/', self::$uri);
        foreach (self::$uri as $i) {
            $i = htmlspecialchars($i);
        }
    }

    public static function set($index, $value) {
        self::$uri[$index] = $value;
    }

    public static function get($index) {
        if (self::isnull($index)) {
            return null;
        } else {
            return self::$uri[$index];
        }
    }

    public static function isnull($index) {
        return empty(self::$uri[$index]);
    }

}
