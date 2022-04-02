<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบลดหนี้ / Credit Note </h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING using IV -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <!-- <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-8">
                        <label for="ivNoTextbox">เลขที่ IV</label>
                        <input type="text" class="form-control" id="ivNoTextbox" ng-model="ivNo" style="text-transform:uppercase">
                    </div>
                    <div class="col-md-4">
                        <label for="submitButton" style="color:white;">.</label>
                        <button type="button" class="btn btn-default btn-block" id="submitButton" ng-click="formValidate1()">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div> -->


        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING using PVDs -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="filter_anything">PVD no</label>
                        <input type="text" class="form-control" id="filter_anything" ng-model="filter_anything" style="text-transform:uppercase">
                    </div>
                    <div class="col-md-8">
                    <h4 class="my-1" ng-show="PVDs.length != 0">รายละเอียดใบลดหนี้</h4>
                    </div>
                    <table class="table table-hover my-1" ng-show="PVDs.length == 0">
                        <tr>
                            <th>ยังไม่มีการขอใบลดหนี้</th>
                        </tr>
                    </table>

                    <table class="table table-hover my-1" ng-show="PVDs.length != 0">
                        <tr>
                            <th colspan="2">edit</th>
                            <th >PVD no</th>
                            <th>employee id</th>
                            <th>employee line</th>
                            <th>total_amount</th>
                            <th>vat id</th>
                            <th>sox no</th>
                            <th>invoice id</th>
                            <th>note</th>
                            
                            
                        </tr>

                        <tr ng-repeat = "PVD in PVDs | unique:'pvd_no'  | filter:{pvd_no:filter_anything}" ng-click="selectPVD(PVD)">
                            <td colspan="2" ><input type="checkbox" ng-change = 'update(PVD)' ng-click="stopEvent($event)" ng-model = 'PVD.editing' ng-disabled="pvd_selected"/></td>
                            <!-- <td><input type="text" ng-model=PVD.pvd_no ng-disabled="!PVD.editing" style = "width : 10ch"/></td> -->
                            <td>{{PVD.pvd_no}}</td>
                            <!-- <td ><input type="text" ng-model=PVD.employee_id ng-disabled="!PVD.editing"></td> -->
                            <td>{{PVD.employee_id}}</td>
                            <!-- <td ><input class = "short_input" type="text" ng-model=PVD.employee_line ng-disabled="!PVD.editing"></td> -->
                            <td>{{PVD.employee_line}}</td>
                            <td ><input class = "short_input" type="text" ng-model=PVD.total_amount ng-disabled="!PVD.editing"></td>
                            <!-- <td>{{PVD.total_amount}}</td> -->
                            <td ><input class = "short_input" type="text" ng-model=PVD.vat_id ng-disabled="!PVD.editing"></td>
                            <!-- <td>{{PVD.vat_id}}</td> -->
                            <td ><input class = "short_input" type="text" ng-model=PVD.sox_no ng-disabled="!PVD.editing"></td>
                            <!-- <td>{{PVD.sox_no}}</td> -->
                            <td ><input class = "short_input" type="text" ng-model=PVD.invoice_no ng-disabled="!PVD.editing"></td>
                            <!-- <td>{{PVD.invoice_no}}</td> -->
                            <td ><input type="text" ng-model=PVD.note ng-disabled="!PVD.editing" onkeypress="this.style.width = ((this.value.length + 1)) + 'ch';"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- <button type="button" class="btn btn-default btn-block my-1" ng-click="test()">test</button> -->
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING IVRC ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="cnItems.length != 0">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดใบลดหนี้</h4>
                    <table class="table table-hover my-1" ng-show="cnItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มใบลดหนี้</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="cnItems.length != 0">
                        <tr>
                            <th colspan="2">รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคาต่อหน่วย</th>
                            <th>จำนวน</th>
                            <th>ราคา</th>
                        </tr>
                        <tr ng-repeat="cnItem in cnItems">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCnItem(cnItem)"></i></td>
                            <td>{{cnItem.product_no}}</td>
                            <td>{{cnItem.product_name}}</td>
                            <td style="text-align: right;">{{cnItem.sales_price | number:2}}</td>
                            <td style="text-align: right;">{{cnItem.quantity}}</td>
                            <td style="text-align: right;">{{cnItem.total_sales_price | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">มูลค่าตามเอกสารเดิม</th>
                            <th id="totalPrice" style="text-align: right;">{{iv_total_sales_price | number:2}}</th>
                        </tr>  
                        <tr>
                            <th style="text-align: right;" colspan="5">มูลค่าที่ถูกต้อง</th>
                            <th id="totalPrice" style="text-align: right;">{{new_total_sales_price | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">ราคารวมทั้งสิ้น</th>
                            <th id="totalPrice" style="text-align: right;">{{diff_total_sales_price | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">จำนวนภาษีมูลค่าเพิ่ม</th>
                            <th id="totalPrice" style="text-align: right;">{{vat_total_sales_price | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">จำนวนเงินรวมทั้งสิ้น</th>
                            <th id="totalPrice" style="text-align: right;">{{sum_total_sales_price | number:2}}</th>
                        </tr>
                    </table>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <div class="col-md-12 mx-0 mt-2" >
                        <label for="textBoxCnDetail">สาเหตุการลดหนี้</label>
                        <input type="text" class="form-control" id="textBoxCnDetail" ng-model="cnDetail" placeholder="สาเหตุการลดหนี้" >
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate2()">บันทึกใบลดหนี้</button>
                </div>
            </div>
            <!-- <button type="button" class="btn btn-default btn-block my-1" ng-click="test()">test</button> -->
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
         
        <script>
            addModal('formValidate5', 'ใบลดหนี้ / Credit Note', 'ไม่มีเลขใบกำกับภาษี');
            addModal('formValidate2', 'ใบลดหนี้ / Credit Note', 'ไม่มีเลขใบกำกับภาษีนี้');
            addModal('formValidate3', 'ใบลดหนี้ / Credit Note', 'ยังไม่ได้เพิ่มรายละเอียดใบลดหนี้');
            addModal('formValidate4', 'ใบลดหนี้ / Credit Note', 'ยังไม่ได้เพิ่มสาเหตุการลดหนี้');
            addModal('formValidate5', 'ใบลดหนี้ / Credit Note', 'ยังไม่ได้เพิ่มสาเหตุการลดหนี้');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
    .short_input { width : 11ch }
}
</style>




<script>





    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.ivNo = '';
        $scope.cnDetail = '';
		$scope.cnItems = [];
        $scope.pvd_selected = false;
        $scope.PVDs = eval(<?php echo json_encode($this->PVDs); ?>);

        // $scope.test = function(){
        //     console.log($scope.PVDs); 
        // }
        
        $scope.selectPVD = function($PVD) {
            if(!$PVD.editing) {
                $scope.ivNo = $PVD.invoice_no;
                $scope.cnDetail = $PVD.note;
                $scope.pvd_no = $PVD.pvd_no;
                $scope.formValidate1();
                if($scope.cnItems.length > 0) {
                    $scope.pvd_selected = true;
                }
            }
        }

        $scope.update = function($PVD) {
            if(!$PVD.editing) {
                $.post("/acc/credit_note/update_PVD", {
                    post : true,
                    pvd_no : $PVD.pvd_no,
                    employee_id : $PVD.employee_id,
                    employee_line : $PVD.employee_line,
                    total_amount : $PVD.total_amount,
                    vat_id : $PVD.vat_id,
                    sox_no : $PVD.sox_no,
                    invoice_no : $PVD.invoice_no,
                    note : $PVD.note,
                }, function(data) {
                    addModal('successModalupdate', 'ใบลดหนี้ / Credit Note', 'update ' + $PVD.pvd_no.toUpperCase() + ' update ' +  data);
                    $('#successModalupdate').modal('toggle');
                });
            }
        }

        $scope.stopEvent = function(e){
            e.stopPropagation();
        }

        $scope.formValidate1 = function() {  //get iv info and put pvd into SHOWING IVRC ITEMS section
            if($scope.ivNo==='') {
                $('#formValidate5').modal('toggle');
            } else {
                $http.post('/acc/credit_note/post_iv', 
                    JSON.stringify({iv_no : $scope.ivNo.toUpperCase()})
                ).then(function(response) {
                    if(response.data === '') {
                        $('#formValidate2').modal('toggle');
                        $scope.cnItems = [];
                        // $scope.calculateCn();
                    } else {
                        $scope.cnItems = response.data;
                        // $scope.calculateCn();
                    }
                });
            }
        }
        
        $scope.dropCnItem = function(cnItem) {
            //var tempcnItem = [];
            // angular.forEach($scope.cnItems, function (value, key) {
            //     if(value.product_no != cnItem.product_no) {
            //         tempcnItem.push(value);
            // });
            //$scope.cnItems = tempcnItem;
            $scope.cnItems = [];
            // $scope.calculateCn();
            // console.log($scope.cnItems.length);
            //if($scope.cnItems.length == 0) {
                $scope.pvd_selected = false;
            //}
        }
        // move to end of pvd process        
        // $scope.calculateCn = function() {
            
        //     $scope.diff_total_sales_no_vat = 0;
        //     $scope.diff_total_sales_vat = 0;
        //     $scope.diff_total_sales_price = 0;
        //     $scope.sum_total_sales_no_vat = 0;
        //     $scope.vat_total_sales_no_vat = 0;
            
        //     if($scope.cnItems.length != 0) {
        //         $scope.iv_total_sales_price = $scope.cnItems[0].iv_total_sales_price;
        //         angular.forEach($scope.cnItems, function(value, key) {
        //             $scope.diff_total_sales_vat += (parseFloat(value.sales_vat) * parseFloat(value.quantity));
        //             $scope.diff_total_sales_price += (parseFloat(value.sales_price) * parseFloat(value.quantity));
        //         });
        //         $scope.diff_total_sales_no_vat = (parseFloat($scope.diff_total_sales_vat) == 0) ? 0 : parseFloat($scope.diff_total_sales_price) / 1.07;
        //         $scope.diff_total_sales_vat = (parseFloat($scope.diff_total_sales_vat) == 0) ? 0 : (parseFloat($scope.diff_total_sales_price) / 107) * 7;
        //         $scope.new_total_sales_price = parseFloat($scope.iv_total_sales_price) - parseFloat($scope.diff_total_sales_price);
        //         $scope.sum_total_sales_no_vat = (parseFloat($scope.diff_total_sales_vat) == 0) ? 0 : parseFloat($scope.diff_total_sales_price) * 1.07;
        //         $scope.vat_total_sales_no_vat = parseFloat($scope.diff_total_sales_price) - parseFloat($scope.sum_total_sales_no_vat);
                
        //     }
            
        // }
        
        $scope.formValidate2 = function() {
            if($scope.cnItems.length == 0) {
                $('#formValidate3').modal('toggle');
            } else if($scope.cnDetail == '') {
                $('#formValidate4').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ใบลดหนี้ / Credit Note', 'ยืนยันการออกใบลดหนี้', 'postCnItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        

        //comment to prevent accidental creation of pvd remove

        $scope.postCnItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/acc/credit_note/post_cn", { 
                post : true,
                // diff_total_sales_no_vat : $scope.diff_total_sales_no_vat,
                // diff_total_sales_vat : $scope.diff_total_sales_vat,
                // diff_total_sales_price : $scope.diff_total_sales_price,
                // diff_total_sales_price_thai : NumToThai(parseFloat($scope.diff_total_sales_price)),
                // file_no : $scope.ivNo.toUpperCase(),
                // cn_detail : $scope.cnDetail,
                // cnItems : JSON.stringify(angular.toJson($scope.cnItems)),
                pvd_no : $scope.pvd_no,
            }, function(data) {
                addModal('successModal', 'ใบลดหนี้ / Credit Note', 'ออกใบลดหนี้ของ ' + $scope.ivNo.toUpperCase() + data );
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.open('/file/cn/' + $scope.ivNo);
                    window.location.assign('/acc/credit_note');
                });
            });
        }

  	});

</script>