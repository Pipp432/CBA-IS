<?php

namespace model;

use _core\model;
use _core\helper\session;
use _core\helper\input;
use _core\helper\uri;
use _core\helper\thaiNum;
use PDO;

class hrModel extends model {

    public function postPoint() {
        foreach($_POST["pointArray"] as $value) {
            $sql = $this->prepare("insert into PointLog (date,time,employee_id,point,remark,note,type,cancelled)
                                values (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?,?,?,?,0)"); 
            $sql->execute([$value[0],$value[2],$value[1],$value[4],$value[3]]);
        }

        echo "yes";
    }
}