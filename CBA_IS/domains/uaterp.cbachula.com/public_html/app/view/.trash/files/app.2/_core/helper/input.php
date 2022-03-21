<?php

namespace _core\helper;

class input {

    public static function post($key){
        if(!empty(filter_input(INPUT_POST,$key))){
            return filter_input(INPUT_POST,$key);
        } else {
            return null;
        }
    }

    public static function postArray($key){
        if(!empty($_POST[$key])){
            return $_POST[$key];
        } else {
            return array();
        }
    }
    
}