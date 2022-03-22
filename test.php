<?php
echo nl2br ("Hello World \n");
echo "Hi <br> hello";
$date = date("H");
if($date>20){
    echo "<br>It is late at night";
}else{
    echo "<br>",$date;
}
    

?>