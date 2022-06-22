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

        $pointArr = json_decode($_POST["pointArray"]);
            // $statement = "  INSERT into PointLog 
            //                     (date,time,employee_id,point,remark,note,type,cancelled)
            //                 values ";
        echo $pointArr;
        foreach($pointArr as $value) {
            // $statement = $statement."(CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,\"".$value[0]."\",".$value[2].",\"".$value[1]."\",\"".$value[4]."\",\"".$value[3]."\",0),";;
            $sql = $this->prepare(" INSERT into PointLog 
                                        (date,time,employee_id,point,remark,note,type,cancelled)
                                    values 
                                        (CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?,?,?,?,0)");     
            $sql->execute([$value[0],$value[2],$value[1],$value[4],$value[3]]);
            print_r($value);
        }
        // $statement = substr($statement, 0, -1);
        // echo $statement;
        // $sql = $this->prepare($statement);
        // $success = $sql->execute([]);
        // echo $success;
        // if(!$success) {
            // print_r($sql->errorinfo());
        // }
        
    }
}