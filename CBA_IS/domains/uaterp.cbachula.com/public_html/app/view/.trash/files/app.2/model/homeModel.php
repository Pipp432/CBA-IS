<?php

namespace model;

use _core\model;
use _core\helper\input;
use _core\helper\session;
use PDO;

class homeModel extends model {

    public function get_courses() {
        $sql = $this->prepare("select * from batches join courses on courses.course_no = batches.course_no");
        $sql->execute();
        return  json_encode($sql->rowCount() > 0 ? $sql->fetchAll(PDO::FETCH_ASSOC) : [], JSON_UNESCAPED_UNICODE);
    }

}