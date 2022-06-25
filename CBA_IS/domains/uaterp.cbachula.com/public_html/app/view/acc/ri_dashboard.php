
<!DOCTYPE html>

<html>
   
<body>
    
    
    <h1 class="text-center text-primary" style="padding:10px 0 0 0;">RI DASHBOARD</h1>
   
    <div class="container mt-3" >

    <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
        <div class="card-body">
            <div class="row mx-0 mt-2">
            <?php 
                if (count($this->RIreport)==0)
                { 
                    echo'<h5 class="text-center text-warning"> ยังไม่มี RI ';
                }else
                {
                ?>
                <h3  style="color: #6aa8d9;">ใบคืนสินค้าที่ออกมาแล้วทั้งหมด</h3>
                <table class="table table-hover my-1">
                    <tr>
                        <th>เลขที่ใบ RI</th>
                        <th>วันที่ออก</th>
                        <th>Supplier</th>
                        <th>ราคา</th>
                        
                       
            
                    </tr>
                    <?php
                    foreach($this->RIreport as $row){
                        ?>
                        <tr> 
                        <?php
                            
                            echo '<td><a href="/file/ri/'.$row['ri_no'].'" target="_blank">'.$row['ri_no'].'</a></th>';
                            echo "<td>".$row['ri_date']."</td>";
                            echo "<td>".$row['ri_supplier']."</td>";
                            echo "<td>".$row['ri_total']."</td>";
                            
                            
                            
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
