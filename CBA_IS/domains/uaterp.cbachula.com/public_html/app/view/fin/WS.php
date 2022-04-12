<!DOCTYPE html>

<html>
<body>


    <h1 class="text-center text-primary" style="padding:10px 0 0 0;">เบิกเงินรองจ่าย</h1>
    <div class="container mt-3">
        <div class="d-flex flex-row " style="padding: 10px 0 0 0;">
            <h2 class=" text-center " style="margin:10px 10px;">เลือกรายการการเบิกจ่าย</h2>
            <div class="dropdown" style="margin:15px 15px;">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuMenu"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">เลือกรายการ </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuMenu">
                    <button class="dropdown-item" type="button" data-toggle="collapse" data-target="#type1"
                        aria-expanded="true" aria-controls="type1">เบิกจ่ายประเภท A</button>
                    <button class="dropdown-item" type="button" data-toggle="collapse" data-target="#type3"
                        aria-expanded="false" aria-controls="type3">เบิกจ่ายประเภท C</button>
                    <button class="dropdown-item" type="button" data-toggle="collapse" data-target="#type4"
                        aria-expanded="false" aria-controls="type4">อัพโหลดหลักฐานการโอนเงิน (เบิกจ่าย)</button>
                    <button class="dropdown-item" type="button" data-toggle="collapse" data-target="#type7"
                        aria-expanded="false" aria-controls="type7">อัพโหลดหลักฐานการโอนเงิน (Supplier)</button>
                    <button class="dropdown-item" type="button" data-toggle="collapse" data-target="#type5"
                        aria-expanded="false" aria-controls="type5">อัพโหลดใบสำคัญรับเงิน (for PVB)</button>
                    <button class="dropdown-item" type="button" data-toggle="collapse" data-target="#type6"
                        aria-expanded="false" aria-controls="type6">อัพโหลด IV/CR (ประเภท C)</button>
                    <button class="dropdown-item" type="button" data-toggle="collapse" data-target="#pva"
                        aria-expanded="false" aria-controls="pva">อัพโหลด slip (PVA)</button>
                </div>

            </div>

        </div>
        <div id="accordion">

            <div class="collapse" id="type1" data-parent="#accordion" style="margin:10px 10px;">
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <h3 style="color: #6aa8d9;">เบิกจ่ายประเภท A</h3>
                        <form method="post" action="WithdrawalSlip" enctype="multipart/form-data">
                            เลขที่ใบเบิกรองจ่าย : <input type="text" name="formid"><br><br>
                            รหัสพนักงานที่ขอเบิก : <input style="text-transform:uppercase" type="text"
                                name="emid"><br><br>
                            <h5 style="color: #6aa8d9;">ใบเบิกเงินรองจ่าย</h5>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="1_file1" name="1_file1"><br><br>

                            <h5 style="color: #6aa8d9;">ใบกำกับภาษี/ใบเสร็จรับเงิน</h5>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="1_file2" name="1_file2"><br>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="1_file3" name="1_file3"><br>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="1_file4" name="1_file4"><br><br>

                            <h5 style="color: #6aa8d9;">สลิป</h5>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="1_file5" name="1_file5"><br><br>
                            <button class="btn btn-default " name="submittype1">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
            <!--<div class="collapse " id="type2" data-parent="#accordion" style="margin:10px 10px;">
             <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                <div class="card-body">
                    <h3  style="color: #6aa8d9;">เบิกจ่ายประเภท A2</h3>
                    <form  method="post" action="WithdrawalSlip" enctype="multipart/form-data">
                    เลขที่ใบเบิกรองจ่าย : <input  type="text" name="formid"><br><br>
                    รหัสพนักงานที่ขอเบิก : <input style="text-transform:uppercase" type="text" name="emid"><br><br>
                    <h5  style="color: #6aa8d9;">ใบเบิกเงินรองจ่าย</h5>
                    <label for="myfile">Select a file:</label>
                    <input type="file" id="2_file1" name="2_file1"><br>

                    <h5  style="color: #6aa8d9;">Ref. 1</h5>
                    <label for="myfile">Select a file:</label>
                    <input type="file" id="2_file2" name="2_file2"><br>
                    <h5  style="color: #6aa8d9;">Ref 2.</h5>
                    <label for="myfile">Select a file:</label>
                    <input type="file" id="2_file3" name="2_file3"><br><br>
                    <button class="btn btn-default " name="submittype2">Upload</button> 
                    </form>
                </div>
             </div>
        </div>-->
            <div class="collapse" id="type3" data-parent="#accordion" style="margin:10px 10px;">
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <h3 style="color: #6aa8d9;">เบิกจ่ายประเภท C</h3>
                        <form method="post" action="WithdrawalSlip" enctype="multipart/form-data">
                            เลขที่ใบเบิกค่าใช้จ่าย : <input type="text" name="formid"><br><br>
                            รหัสพนักงานที่ขอเบิก : <input style="text-transform:uppercase" type="text"
                                name="emid"><br><br>

                            <h5 style="color: #6aa8d9;">ใบเบิกเงินรองจ่าย</h5>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="3_file1" name="3_file1"><br><br>

                            <h5 style="color: #6aa8d9;">ใบเสนอราคา</h5>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="3_file2" name="3_file2"><br><br>
                            <button class="btn btn-default " name="submittype3">Upload</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="collapse" id="type4" data-parent="#accordion">
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <div class="row mx-0 mt-2">
                            <?php 
                        if (count($this->status2data)==0)
                        { 
                            echo'<h5 class="text-center text-warning" >ยังไม่มีรายการที่ต้องโอนเงิน</h5>';
                        }else
                        {
                        ?>
                            <h3 style="color: #6aa8d9;">รายการที่ต้องอัพโหลดหลักฐานการโอนเงิน</h3>
                            <table class="table table-hover my-1">
                                <tr>
                                    <th>เลขที่ใบเบิกจ่าย</th>
                                    <th>ใบเบิกจ่าย</th>
                                    <th>ใบกำกับภาษี/ใบเสนอราคา</th>
                                    <th>พนักงานขอเบิก</th>
                                    <th>จำนวนเงิน</th>
                                    <th>เลข PV</th>

                                </tr>
                                <?php
                            foreach($this->status2data as $row){
                                ?>
                                <tr
                                    onclick="changeNO('<?php echo $row['form_no']; ?>','<?php echo $row['pv_no']; ?>','<?php echo $row['totalpaid']; ?>')">
                                    <?php
                                    echo "<th>".$row['form_no']."</th>";  
                                    echo '<td><a href="/fin/WS/get_ws_form/'.$row['form_no'].'" target="_blank">'.$row['form_no'].'</a></th>';
                                    echo '<td><a href="/fin/WS/get_ws_iv/'.$row['iv_no'].'" target="_blank">'.$row['iv_no'].'</a></th>';
                                    //echo "<td><a target='_blank' href='data:".$row['form_type'].";base64,".base64_encode($row['form_data'])."'>Form-".$row['form_no']."</a></th>";
                                    //echo "<td><a target='_blank' href='data:".$row['iv_type'].";base64,".base64_encode($row['iv_data'])."'>IV-".$row['form_no']."</a></th>";
                                    echo "<td>".$row['employee']."</td>";
                                    echo "<td>".$row['totalpaid']."</td>";
                                    echo '<td><a href="/file/pv/'.$row['pv_no'].'" target="_blank">'.$row['pv_no'].'</a></th>';
                                   
                                    
                                echo "</tr>";
                            }
                            ?>
                            </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <h3 style="color: #6aa8d9;">อัพหลักฐานการโอนเงินสำหรับเบิกจ่าย</h3>
                        <form method="post" action="WithdrawalSlip" enctype="multipart/form-data">
                            เลขใบขอเบิกจ่าย : <input type="text" name="wfno" id="formno4" readonly><br><br>
                            เลข PV : <input type="text" name="pvno" id="pvno4" readonly><br><br>
                            จำนวนเงิน : <input type="text" name="money" id="money4" readonly><br><br>
                            <h5 style="color: #6aa8d9;">หลักฐานการโอนเงิน(สลิป)</h5>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="4_file1" name="4_file1"><br><br>
                            <button class="btn btn-default " name="submittype4">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="collapse" id="type5" data-parent="#accordion">
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <div class="row mx-0 mt-2">
                            <?php 
                        if (count($this->pvforreceipt)==0)
                        { 
                            echo'<h5 class="text-center text-warning" >ยังไม่มีรายการที่ต้องอัพโหลดใบสำคัญรับเงิน</h5>';
                        }else
                        {
                        ?>
                            <h3 style="color: #6aa8d9;">รายการที่ต้องอัพโหลดใบสำคัญรับเงิน</h3>
                            <table class="table table-hover my-1">
                                <tr>
                                    <th>เลขที่ PV </th>
                                    <th>ประเภท PV</th>
                                    <th>ผู้รับเงิน</th>
                                    <th>จำนวนเงิน</th>

                                </tr>
                                <?php
                            foreach($this->pvforreceipt as $row){
                                ?>
                                <tr
                                    onclick="setPVNO('<?php echo $row['pv_no']; ?>','<?php echo $row['total_paid']; ?>')">
                                    <?php
                                    echo '<td><a href="/file/pv/'.$row['pv_no'].'" target="_blank">'.$row['pv_no'].'</a></th>';
                                    echo "<td>".$row['pv_type']."</td>";
                                    echo "<td>".$row['pv_name']."</td>";
                                    echo "<td>".$row['total_paid']."</td>";
                                    
                                echo "</tr>";
                            }
                            ?>
                            </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <h3 style="color: #6aa8d9;">อัพใบสำคัญรับเงิน</h3>
                        <form method="post" action="WithdrawalSlip" enctype="multipart/form-data">
                            เลขใบขอเบิกจ่าย : <input type="text" name="pvno" id="pvno5" readonly><br><br>
                            จำนวนเงิน : <input type="text" name="money" id="money5" readonly><br><br>
                            <h5 style="color: #6aa8d9;">ใบสำคัญรับเงิน</h5>
                            <label for="myfile">Select a file:</label>
                            <input type="file" id="5_file1" name="5_file1"><br><br>
                            <button class="btn btn-default " name="submittype5">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="collapse" id="type6" data-parent="#accordion">
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <div class="row mx-0 mt-2">
                            <?php 
                        if (count($this->wstype3data)==0)
                        { 
                            echo'<h5 class="text-center text-warning" >ยังไม่มีรายการที่ต้องอัพโหลดใบกำกับภาษี</h5>';
                        }else
                        {
                        ?>
                            <h3 style="color: #6aa8d9;">รายการที่ต้องอัพโหลดใบกำกับภาษี</h3>
                            <table class="table table-hover my-1">
                                <tr>
                                    <th>เลขที่ใบเบิกจ่าย </th>
                                    <th>เลขที่ใบเสนอราคา</th>
                                    <th>ใบเสนอราคา</th>
                                    <th>พนักงานที่ขอเบิก</th>
                                    <th>จำนวนเงิน</th>

                                </tr>
                                <?php
                            foreach($this->wstype3data as $row){
                                ?>
                                <tr
                                    onclick="settype6('<?php echo $row['form_no']; ?>','<?php echo $row['iv_no']; ?>','<?php echo $row['totalpaid']; ?>')">
                                    <?php
                                    echo "<th>".$row['form_no']."</th>"; 
                                    echo "<th>".$row['iv_no']."</th>";   
                                    echo '<td><a href="/fin/WS/get_ws_iv2/'.$row['iv_no'].'" target="_blank">'.$row['iv_no'].'</a></th>';
                                    //echo "<td><a target='_blank' href='data:".$row['iv_type'].";base64,".base64_encode($row['iv_data'])."'>ใบเสนอราคา-".$row['form_no']."</a></th>";
                                    echo "<td>".$row['employee']."</td>";
                                    echo "<td>".$row['totalpaid']."</td>";
                                    
                                echo "</tr>";
                            }
                            ?>
                            </table>
                            <?php } ?>




                        </div>
                    </div>
                </div>

                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <h3 style="color: #6aa8d9;">อัพโหลด IV/CR (ประเภท C)</h5>
                            <form method="post" action="WithdrawalSlip" enctype="multipart/form-data">
                                เลขใบขอเบิกจ่าย : <input type="text" name="formno" id="formno6" readonly><br><br>
                                เลขใบเสนอราคา : <input type="text" name="ivno" id="ivno6" readonly><br><br>
                                จำนวนเงินที่ขอเบิก : <input type="text" name="money" id="money6" readonly><br><br>
                                <h5 style="color: #6aa8d9;">IV/CR</h5>
                                <label for="myfile">Select a file:</label>
                                <input type="file" id="6_file1" name="6_file1"><br><br>
                                <button class="btn btn-default " name="submittype6">Upload</button>
                            </form>
                    </div>
                </div>
            </div>
            <div class="collapse" id="type7" data-parent="#accordion">
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <div class="row mx-0 mt-2">
                            <?php 
                        if (count($this->pvfortranfer)==0)
                        { 
                            echo'<h5 class="text-center text-warning" >ยังไม่มีรายการที่ต้องโอนเงินให้ sup</h5>';
                        }else
                        {
                        ?>
                            <h3 style="color: #6aa8d9;">รายการที่ต้องอัพโหลดหลักฐานการโอนเงิน</h3>
                            <table class="table table-hover my-1">
                                <tr>
                                    <th>เลขที่ PV </th>
                                    <th>วันที่ออก PV </th>
                                    <th>ผู้รับเงิน</th>
                                    <th>จำนวนเงิน</th>

                                </tr>
                                <?php
                            foreach($this->pvfortranfer as $row){
                                ?>
                                <tr
                                    onclick="setPVNO2('<?php echo $row['pv_no']; ?>','<?php echo $row['total_paid']; ?>')">
                                    <?php
                                    echo '<td><a href="/file/pv/'.$row['pv_no'].'" target="_blank">'.$row['pv_no'].'</a></th>';
                                    echo "<th>".$row['pv_date']."</th>";  
                                    echo "<td>".$row['pv_name']."</td>";
                                    echo "<td>".$row['total_paid']."</td>";
                                    
                                echo "</tr>";
                            }
                            ?>
                            </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <h3 style="color: #6aa8d9;">อัพโหลดหลักฐานการโอนเงิน</h5>
                            <form method="post" action="WithdrawalSlip" enctype="multipart/form-data">
                                เลข PV : <input type="text" name="pvno" id="pvno7" readonly><br><br>
                                จำนวนเงิน : <input type="text" name="money" id="money7" readonly><br><br>
                                <h5 style="color: #6aa8d9;">สลิปโอนเงิน</h5>
                                <label for="myfile">Select a file:</label>
                                <input type="file" id="7_file1" name="7_file1"><br><br>
                                <h5 style="color: #6aa8d9;">สลิป CR</h5>
                                <label for="myfile">Select a file:</label>
                                <input type="file" id="7_file2" name="7_file2"><br><br>
                                <button class="btn btn-default " name="submittype7">Upload</button>
                            </form>
                    </div>
                </div>
            </div>

            <div class="collapse" id="pva" data-parent="#accordion" ng-controller="moduleAppController">
                <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
                    <div class="card-body">
                        <div class="row mx-0 mt-2">
                            <table class="table table-hover my-1">
                                <tr>
                                    <th>pv no</th>
                                    <th>รายการสินค้า</th>
                                    <th>ค่าใช้จ่าย</th>
                                    <th>upload slip</th>
                                    <th>confirm</th>
                                </tr>
                                <tr ng-show="pvas.length == 0">
                                    <th colspan="8">ไม่มีใบ PV-A ที่ยังไม่ได้โอน</th>
                                </tr>
                                <tr ng-repeat="pva in pvas | unique:'pv_no' | orderBy:'pv_no'"
                                    ng-show="pvas.length > 0">
                                    <td>{{pva.pv_no}}</td>
                                    <td style = "white-space: normal;">{{pva.product_names}}</td>
                                    <td>{{pva.total_paid | number:2}}</td>
                                    <td>
                                        <input id='{{pva.pv_no}}' type="file" class="form-control-file"
                                            name={{pva.pv_no}}>
                                    </td>
                                    <td style="text-align: right;">
                                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail"
                                            ng-click="upload_slip_pva(pva)">ยืนยันอัปสลิป</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>




</body>

</html>

<script>
    function changeNO(formno, pvno, money) {

        document.getElementById("formno4").value = formno;
        document.getElementById("pvno4").value = pvno;
        document.getElementById("money4").value = money;
    }

    function settype6(formno, ivno, money) {

        document.getElementById("formno6").value = formno;
        document.getElementById("ivno6").value = ivno;
        document.getElementById("money6").value = money;
    }

    function setPVNO(pvno, money) {

        document.getElementById("pvno5").value = pvno;
        document.getElementById("money5").value = money;
    }

    function setPVNO2(pvno, money) {

        document.getElementById("pvno7").value = pvno;
        document.getElementById("money7").value = money;
    }


</script>

<script>
    app.controller('moduleAppController', function ($scope, $http, $compile) {

        $scope.pvas = [];
        angular.forEach(<?php echo ($this -> pvas);?>, function (value, key) { 
            $scope.pvas.push(value); 
        });


        $scope.upload_slip_pva = function ($pva) {
            var upload = true;
            var formData = new FormData();
            formData.append('slip_file', $('#' + $pva['pv_no'])[0].files[0]);
            for (var pair of formData.entries()) {
                if (pair[1] === 'undefined') {
                    upload = false;
                }
            }
            formData.append('pv_no', $pva['pv_no']);

            if (upload) {
                $.ajax({
                    url: 'WS/PVA_slip',
                    type: "POST",
                    dataType: 'text',
                    method: 'POST',
                    data: formData,
                    cache: false,
                    processData: false, 
                    contentType: false,
                }).done(function (data) {
                    if (data == 'success') {
                        addModal('succModal', 'upload slip', 'success');
                        $('#succModal').modal('toggle');
                        $('#succModal').on('hide.bs.modal', function (e) {
                            //location.reload();
                            var tmp_pvas = [];
                            angular.forEach($scope.pvas, function (value, key) { 
                                if(value != $pva) tmp_pvas.push(value); 
                            });
                            $scope.pvas = tmp_pvas;
                            $scope.$apply(); //update ng-repeat (usually update after $scope variable change but idk why it don't update now)
                        });
                    } else {
                        console.log(data);
                        addModal('uploadFailModal', 'upload slip', 'fail' + data);
                        $('#uploadFailModal').modal('toggle');
                        $('#uploadFailModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        }); 
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log('ajax.fail');
                    addModal('uploadFailModal', 'upload slip', 'fail');
                    $('#uploadFailModal').modal('toggle');
                    $('#uploadFailModal').on('hide.bs.modal', function (e) {
                        location.reload();
                    });
                });
            } else {
                addModal('formValidate10', 'confirm เบิกเงินรองจ่าย', 'ยังไม่ได้อัปรูป');
                $('#formValidate10').modal('toggle');
            }
        }


    });
</script>