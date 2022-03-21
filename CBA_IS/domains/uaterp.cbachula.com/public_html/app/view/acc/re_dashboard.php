
<!DOCTYPE html>

<html>
   
<body>
    
    
    <h1 class="text-center text-primary" style="padding:10px 0 0 0;">RE DASHBOARD</h1>
   
    <div class="container mt-3" >

    <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
        <div class="card-body">
            <div class="row mx-0 mt-2">
            <?php 
                if (count($this->REreport)==0)
                { 
                    echo'<h5 class="text-center text-warning" >ยังไม่มี RE/h5>';
                }else
                {
                ?>
                <h3  style="color: #6aa8d9;">ใบคืนสินค้าที่ออกมาแล้วทั้งหมด</h3>
                <table class="table table-hover my-1">
                    <tr>
                        <th>เลขที่ใบ RE</th>
                        <th>วันที่ออก</th>
                        <th>Supplier</th>
                        <th>ราคา</th>
                        
                       
            
                    </tr>
                    <?php
                    foreach($this->REreport as $row){
                        ?>
                        <tr> 
                        <?php
                            
                            echo '<td><a href="/file/re/'.$row['re_no'].'" target="_blank">'.$row['re_no'].'</a></th>';
                            echo "<td>".$row['re_date']."</td>";
                            echo "<td>".$row['re_supplier']."</td>";
                            echo "<td>".$row['re_total']."</td>";
                            
                            
                            
                        echo "</tr>";
                    }
                    ?>
                </table>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>
    
            
  
    
    


</body>
</html>
<script>

</script>
