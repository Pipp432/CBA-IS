<?php

namespace _core\helper;

use PDO;
use PDOException;

class database extends PDO {

    function __construct()
    {
        try {
            parent::__construct(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
            $this->exec("SET NAME UTF8");
        } catch(PDOException $exc) {
            echo $exc->getMessage();
        }
    }

}