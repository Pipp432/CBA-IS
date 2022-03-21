<?php

namespace _core\helper;

class session {

    public static function start() {
        session_start();
    }

    public static function set($key, $value) {
        $_SESSION[$key]=$value;
    }

    public static function get($key) {
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }else{
            return null;
        }
    }

    public static function delete($key) {
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        session_destroy();
    }

}