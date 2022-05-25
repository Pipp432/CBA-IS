<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class hrModel extends model {

    // Add point
    // public function getemployeeid() {
    //     $sql = $this->prepare("select * from Employee where employee_id = ?");
    //     $sql->execute([input::postAngular('sp_no')]);
    //     if ($sql->rowCount() > 0) {
    //         return json_encode($sql->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
    //     }
    //     return json_encode([];)
    // }

    // // Add point
    // public function editPoint() {

    //     // insert point
    //     $sql = $this->prepare("insert into PointLog (date, time, employee_id, point, remark, note, type) values (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?");
    //     $sql->execute([
    //         json_decode()
    //     ])
    // }
}