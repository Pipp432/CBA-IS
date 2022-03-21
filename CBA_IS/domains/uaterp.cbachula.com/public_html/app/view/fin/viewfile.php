
<!DOCTYPE html>
<html>
<body>
<?php
    $data = $this->getuploaddata[0];
    echo $data['type'];
    header('Content-Type:'.$data['type']);
    echo base64_encode($data['data']);
    


?>


</body>
</html>


