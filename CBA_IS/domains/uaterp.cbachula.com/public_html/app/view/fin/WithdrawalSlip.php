
<!DOCTYPE html>
<script>
    
    function sentivtype3(name,type,file,no){
         $.post("/fin/WithdrawalSlip/createivtype3", {
            post: true,
            filename : name,
            filetype : type,
            filedata : file,
            ivno : no
            
        }, function(result){
            if (result === "valid") {
                
                location.replace("/fin/WS");
                alert("อัพโหลดใบกำกับภาษีเรียบร้อยแล้วครับ");
            } else {
                location.replace("/fin/WS");
                alert("Not Success อัพใหม่นะครับบบ");
            }
        });
    }
    
    function sentslip(name1,type1,file1,no,no2){
         $.post("/fin/WithdrawalSlip/createslip", {
            post: true,
            filename : name1,
            filetype : type1,
            filedata : file1,
            pvno : no,
            wfno : no2
            
        }, function(result){
            if (result === "valid") {
                location.replace("/fin/WS");
                 alert("อัพโหลดหลักฐานการโอนเรียบร้อยแล้วครับ");
            }
            else{
                location.replace("/fin/WS");
                alert("Not Success อัพใหม่นะครับบบ");
            }
        });
        
       
    }
    function sentreceipt(name1,type1,file1,no){
         $.post("/fin/WithdrawalSlip/createreceipt", {
            post: true,
            filename : name1,
            filetype : type1,
            filedata : file1,
            pvno : no
            
        }, function(result){
            if (result === "valid") {
                location.replace("/fin/WS");
                 alert("อัพโหลดใบสำคัญรับเงินเรียบร้อยแล้วครับ");
            } 
            else{
                location.replace("/fin/WS");
                alert("Not Success อัพใหม่นะครับบบ");
            }
        });
    }
    function sentslipForSup(name1,type1,file1,no){
         $.post("/fin/WithdrawalSlip/createslipForSup", {
            post: true,
            filename : name1,
            filetype : type1,
            filedata : file1,
            pvno : no
            
        }, function(result){
            if (result === "valid") {
                location.replace("/fin/WS");
                 alert("อัพโหลดหลักฐานการโอนเงินเรียบร้อยแล้วครับ");
            } 
            else{
                location.replace("/fin/WS");
                alert("Not Success อัพใหม่นะครับบบ");
            }
        });
    }
    
    function sentwstype1(emid,type,formid,name1,type1,file1,name2,type2,file2,name3,type3,file3,name4,type4,file4,name5,type5,file5){
         $.post("/fin/WithdrawalSlip/createwstype1", {
            post: true,
            emid : emid,
            type : type,
            formid : formid,
            filename1 : name1,
            filetype1 : type1,
            filedata1 : file1,
            filename2 : name2,
            filetype2 : type2,
            filedata2 : file2,
            filename3 : name3,
            filetype3 : type3,
            filedata3 : file3,
            filename4 : name4,
            filetype4 : type4,
            filedata4 : file4,
            filename5 : name5,
            filetype5 : type5,
            filedata5 : file5
            
        }, function(result){
            if (result === "valid") {
                
                location.replace("/fin/WS");
                alert("อัพโหลดเอกสารเรียบร้อยแล้วครับ");
            } else {
                location.replace("/fin/WS");
                alert("Not Success อัพใหม่นะครับบบ");
            }
        });
    }
    function sentwstype2(emid,type,formid,name1,type1,file1,name2,type2,file2,name3,type3,file3){
         $.post("/fin/WithdrawalSlip/createwstype2", {
            post: true,
            emid : emid,
            type : type,
            formid : formid,
            filename1 : name1,
            filetype1 : type1,
            filedata1 : file1,
            filename2 : name2,
            filetype2 : type2,
            filedata2 : file2,
            filename3 : name3,
            filetype3 : type3,
            filedata3 : file3
            
        }, function(result){
            if (result === "valid") {
                
                location.replace("/fin/WS");
                alert("อัพโหลดเอกสารเรียบร้อยแล้วครับ");
            } else {
                location.replace("/fin/WS");
                alert("Not Success อัพใหม่นะครับบบ");
            }
        });
    }
    

</script>
<html>
    <meta charset="utf-8"/> 
<body>
    <?php

    if(isset($_POST['submittype1']))
    {
        $formid=$_POST['formid'];
        $emid=$_POST['emid'];
        $type='1';
        //echo"<script>createws('$emid','$type','$formid');</script>";
        
        $name1 = $_FILES['1_file1']['name'];
        $file1 = file_get_contents($_FILES['1_file1']['tmp_name']);
        $file1 = base64_encode($file1);
        $type1 = $_FILES['1_file1']['type'];
        
        
        
        //echo"<script>sentform('$name1','$type1','$file1','$formid');</script>"; 
        
        $name2 = $_FILES['1_file2']['name'];
        $file2 = file_get_contents($_FILES['1_file2']['tmp_name']);
        $file2 = base64_encode($file2);
        $type2 = $_FILES['1_file2']['type'];

        $name3 = $_FILES['1_file3']['name'];
        $file3 = file_get_contents($_FILES['1_file3']['tmp_name']);
        $file3 = base64_encode($file3);
        $type3 = $_FILES['1_file3']['type'];

        $name4 = $_FILES['1_file4']['name'];
        $file4 = file_get_contents($_FILES['1_file4']['tmp_name']);
        $file4 = base64_encode($file4);
        $type4 = $_FILES['1_file4']['type'];

        $name5 = $_FILES['1_file5']['name'];
        $file5 = file_get_contents($_FILES['1_file5']['tmp_name']);
        $file5 = base64_encode($file5);
        $type5 = $_FILES['1_file5']['type'];
        
        
       // echo"<script>sentiv('$name2','$type2','$file2');</script>";
        echo"<script>sentwstype1('$emid','$type','$formid','$name1','$type1','$file1','$name2','$type2','$file2','$name3','$type3','$file3','$name4','$type4','$file4','$name5','$type5','$file5');</script>";
        
        
    } 
    /*else if(isset($_POST['submittype2']))
    {
        $formid=$_POST['formid'];
        $emid=$_POST['emid'];
        $type='2';
       //echo"<script>createws('$emid','$type','$formid');</script>";
        
          $name1 = $_FILES['2_file1']['name'];
        $file1 = file_get_contents($_FILES['2_file1']['tmp_name']);
        $file1=base64_encode($file1);
        $type1 = $_FILES['2_file1']['type'];
        
       //echo"<script>sentform('$name1','$type1','$file1','$formid');</script>";
       
        $name2 = $_FILES['2_file2']['name'];
        $file2 = file_get_contents($_FILES['2_file2']['tmp_name']);
        $file2=base64_encode($file2);
        $type2 = $_FILES['2_file2']['type'];

        $name3 = $_FILES['2_file3']['name'];
        $file3 = file_get_contents($_FILES['2_file3']['tmp_name']);
        $file3=base64_encode($file3);
        $type3 = $_FILES['2_file3']['type'];
        
       
        //echo"<script>sentivtype2('$name2','$type2','$file2','$name3','$type3','$file3');</script>";
        echo"<script>sentwstype2('$emid','$type','$formid','$name1','$type1','$file1','$name2','$type2','$file2','$name3','$type3','$file3');</script>";

      
      
       
    } */
    else if(isset($_POST['submittype3']))
    {
        $formid=$_POST['formid'];
        $emid=$_POST['emid'];
        $type='3';
        //echo"<script>createws('$emid','$type','$formid');</script>";
        
        $name1 = $_FILES['3_file1']['name'];
        $file1 = file_get_contents($_FILES['3_file1']['tmp_name']);
        $file1 = base64_encode($file1);
        $type1 = $_FILES['3_file1']['type'];

        //echo"<script>sentform('$name1','$type1','$file1','$formid');</script>";
        
        $name2 = $_FILES['3_file2']['name'];
        $file2 = file_get_contents($_FILES['3_file2']['tmp_name']);
        $file2 = base64_encode($file2);
        $type2 = $_FILES['3_file2']['type'];
		
		$name3 = NULL;
        $file3 = NULL;
        $type3 = NULL;
		
		$name4 = NULL;
        $file4 = NULL;
        $type4 = NULL;
		
		$name5 = NULL;
        $file5 = NULL;
        $type5 = NULL;
       
        
        //echo"<script>sentiv('$name2','$type2','$file2');</script>";
        echo"<script>sentwstype1('$emid','$type','$formid','$name1','$type1','$file1','$name2','$type2','$file2','$name3','$type3','$file3','$name4','$type4','$file4','$name5','$type5','$file5');</script>";

       
        
    }
     else if(isset($_POST['submittype4']))
    {
        

        $name1 = $_FILES['4_file1']['name'];
        $file1 = file_get_contents($_FILES['4_file1']['tmp_name']);
        $file1=base64_encode($file1);
        $type1 = $_FILES['4_file1']['type'];
        $pvno=$_POST['pvno'];
        $wfno=$_POST['wfno'];
        echo"<script>sentslip('$name1','$type1','$file1','$pvno','$wfno');</script>";
        
    }
    else if(isset($_POST['submittype5']))
    {
        

        $name1 = $_FILES['5_file1']['name'];
        $file1 = file_get_contents($_FILES['5_file1']['tmp_name']);
        $file1=base64_encode($file1);
        $type1 = $_FILES['5_file1']['type'];
        $pvno=$_POST['pvno'];
        echo"<script>sentreceipt('$name1','$type1','$file1','$pvno');</script>";
        
    }
    
    else if(isset($_POST['submittype6']))
    {
        

        $name1 = $_FILES['6_file1']['name'];
        $file1 = file_get_contents($_FILES['6_file1']['tmp_name']);
        $file1=base64_encode($file1);
        $type1 = $_FILES['6_file1']['type'];
        $ivno=$_POST['ivno'];
        echo"<script>sentivtype3('$name1','$type1','$file1','$ivno');</script>";
        
    }
    else if(isset($_POST['submittype7']))
    {
        

        $name1 = $_FILES['7_file1']['name'];
        $file1 = file_get_contents($_FILES['7_file1']['tmp_name']);
        $file1=base64_encode($file1);
        $type1 = $_FILES['7_file1']['type'];
        $pvno=$_POST['pvno'];
        echo"<script>sentslipForSup('$name1','$type1','$file1','$pvno');</script>";
        
    }


    ?>
    
    
    
    


</body>
</html>
