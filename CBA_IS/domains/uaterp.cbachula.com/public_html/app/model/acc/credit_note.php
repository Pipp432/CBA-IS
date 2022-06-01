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
                        <input type="text" class="form-control" id="ivNoTextbox" ng-model="invoice_no" style="text-transform:uppercase">
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
                    <!-- <div class="col-md-12">
                        <label for="filter_anything">wsd no</label>
                        <input type="text" class="form-control" id="filter_anything" ng-model="filter_anything" style="text-transform:uppercase">
                    </div> -->
                    <div class="col-md-8">
                    <h4 class="my-1" ng-show="WSDs.length != 0">รายละเอียดใบลดหนี้</h4>
                    </div>
                    <table class="table table-hover my-1" ng-show="WSDs.length == 0">
                        <tr>
                            <th>ยังไม่มีการขอใบลดหนี้</th>
                        </tr>
                    </table>

                    <table class="table table-hover my-1" ng-show="WSDs.length != 0">
                        <tr>
                            <th colspan="2">edit</th>
                            <th>wsd no</th>
                            <th>employee id</th>
                            <th>employee line</th>
                            <th>total_amount</th>
                            <th>vat id</th>
                            <th>sox no</th>
                            <th>invoice id</th>
                            <th>note</th>
                            
                        </tr>
                        <tr ng-repeat = "WSD in WSDs | unique:'wsd_no'  | filter:{wsd_no:filter_anything}" ng-click="selectWSD(WSD)">
                            <td colspan="2" ><input type="checkbox" ng-change = 'update(WSD)' ng-click="stopEvent($event)" ng-model = 'WSD.editing' ng-disabled="pvd_selected"/></td>
                            <!-- <td><input type="text" ng-model=PVD.pvd_no ng-disabled="!PVD.editing" style = "width : 10ch"/></td> -->
                            <td>{{WSD.wsd_no}}</td>
                            <!-- <td ><input type="text" ng-model=WSD.employee_id ng-disabled="!WSD.editing"></td> -->
                            <td>{{WSD.employee_id}}</td>
                            <!-- <td ><input class = "short_input" type="text" ng-model=WSD.employee_line ng-disabled="!WSD.editing"></td> -->
                            <td>{{WSD.employee_line}}</td>
                            <td ><input class = "short_input" type="text" ng-model=WSD.total_amount ng-disabled="!WSD.editing"></td>
                            <!-- <td>{{WSD.total_amount}}</td> -->
                            <td ><input class = "short_input" type="text" ng-model=WSD.vat_id ng-disabled="!WSD.editing"></td>
                            <!-- <td>{{WSD.vat_id}}</td> -->
                            <td ><input class = "short_input" type="text" ng-model=WSD.sox_no ng-disabled="!WSD.editing"></td>
                            <!-- <td>{{WSD.sox_no}}</td> -->
                            <td ><input class = "short_input" type="text" ng-model=WSD.invoice_no ng-disabled="!WSD.editing"></td>
                            <!-- <td>{{WSD.invoice_no}}</td> -->
                            <td ><input type="text" ng-model=WSD.note ng-disabled="!WSD.editing" onkeypress="this.style.width = ((this.value.length + 1)) + 'ch';"></td>
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
                            <!-- <th >แก้ไขจำนวน</th> -->
                            <th colspan="2">จำนวน
                                <i class="fa fa-pencil" aria-hidden="true" ng-show="isEdit" ng-click="edit()"></i>
                                <i class="fa fa-check" aria-hidden="true" ng-show="isFinishEdit" ng-click="finishEdit()"></i>
                            </th>
                            <th>ราคา</th>
                        </tr>
                        <tr ng-repeat="cnItem in cnItems ">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCnItem(cnItem)"></i></td>
                            <td>{{cnItem.product_no}}</td>
                            <td>{{cnItem.product_name}}</td>
                            <td style="text-align: right;">{{cnItem.sales_price | number:2}}</td>
                            <!-- <td style="text-align: right;">{{cnItem.quantity}}</td> -->
                        
                            <td style="text-align: center;"><input type="checkbox" ng-change = 'updateCN(cnItem)' ng-click="stopEvent($event)" ng-model = 'cnItem.editing' /></td>
                            <!-- ng-disabled="pvd_selected" -->
                            <td><input class = "short_input" type="text" ng-model=cnItem.quantity ng-disabled="!cnItem.editing"></td>

                            <td style="text-align: right;">{{(cnItem.sales_price*cnItem.quantity) | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="6">มูลค่าตามเอกสารเดิม</th>
                            <th id="ivtotalPrice" style="text-align: right;">{{cnTotalPrice | number:2}}</th>
                        </tr>  
                        <tr>
                            <th style="text-align: right;" colspan="6">มูลค่าที่ถูกต้อง</th>
                            <th id="newtotalPrice" style="text-align: right;">{{new_total_sales_price | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="6">ผลต่าง</th>
                            <th id="difftotalPrice" style="text-align: right;">{{diff_total_sales_price | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="6">จำนวนภาษีมูลค่าเพิ่ม 7%</th>
                            <th id="cattotalPrice" style="text-align: right;">{{vat_total_sales_no_vat | number:2}}</th>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="6">จำนวนเงินรวมทั้งสิ้น</th>
                            <th id="sumtotalPrice" style="text-align: right;">{{sum_total_sales_no_vat | number:2}}</th>
                        </tr>
                    </table>
                </div>
                <hr>
                <!-- <div class="row mx-0 mt-2">
                    <div class="col-md-6 mx-0 mt-2" >
                        <label for="textBoxDebit">Debit</label>
                        <input type="text" class="form-control" id="textBoxDebit" ng-model="debit" placeholder="Debit" >
                    </div>
                </div>
                <div class="row mx-0 mt-2">
                    <div class="col-md-6 mx-0 mt-2" >
                        <label for="textBoxCredit">Credit</label>
                        <input type="text" class="form-control" id="textBoxCredit" ng-model="credit" placeholder="Credit" >
                    </div>
                </div> -->
                <hr>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postCnItems()">บันทึกใบลดหนี้</button>
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
</style>




<script>





    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.invoice_no = '';
        $scope.cnDetail = '';
        $scope.debit = '';
        $scope.credit = '';
        $scope.cnTotalPrice = '';
        $scope.cnItemPrice = '';
		$scope.cnItems = [];
        $scope.pvd_selected = false;
        $scope.WSDs = eval(<?php echo json_encode($this->WSDs); ?>);
        $scope.wsdNo = '';

        $scope.new_total_sales_price = '';
        $scope.vat_total_sales_no_vat = '';
        $scope.diff_total_sales_price = '';
        $scope.sum_total_sales_no_vat = '';

        $scope.isEdit = true;
        $scope.isFinishEdit = false;
        
        // old ver 
        // $scope.dropCnItem = function(cnItem) {
        //     var temp = [];
        //     angular.forEach($scope.cnItems, function (value, key) {
        //         if(!(value.product_no.substring(0,15) === cnItem.product_no.substring(0,15))) {
        //             temp.push(value);
        //         }
        //     });
        //     $scope.cnItems = temp;
        //     $scope.calculateCn();

        // }

        
        $scope.dropCnItem = function(product) {
            angular.forEach($scope.cnItems, function (value, key) {
                if(value.product_no == product.product_no) {
                    $scope.cnItems.splice($scope.cnItems.indexOf(value), 1);
                }
            });
            $scope.calculateCn();
        }

        // oldver
        // $scope.edit = function() {
        //     $scope.isEdit = false;
        //     $scope.isFinishEdit = true;
        //     angular.forEach($scope.cnItems, function(value, key) {
        //         $('#textboxQuantity' + value.product_no.substring(0,15)).prop('disabled', false);
        //     });
        // }

        $scope.edit = function() {
            $scope.isEdit = false;
            $scope.isFinishEdit = true;
            angular.forEach($scope.cnItems, function(value, key) {
                $('#textboxQuantity'+value.product_no).prop('disabled', false);
            });
        }

        // oldver
        // $scope.finishEdit = function() {
        //     $scope.isEdit = true;
        //     $scope.isFinishEdit = false;
        //     var isNotEnough = false;
        //     // angular.forEach($scope.cnItems, function(value, key) {
        //     //     if ($scope.hasDeposit) {
        //     //         value.quantity = $('#textboxQuantity' + value.product_no.substring(0,15)).val();
        //     //     } else if ($scope.selectedProductType == 'Stock' && parseInt($('#textboxQuantity' + value.product_no).val()) > parseInt(value.stock)) {
        //     //         value.quantity = value.stock;
        //     //         $('#textboxQuantity' + value.product_no).val(value.stock);
        //     //         isNotEnough = true;
        //     //     } else {
        //     //         value.quantity = $('#textboxQuantity' + value.product_no).val();
        //     //     }
        //     //     $('#textboxQuantity' + value.product_no).prop('disabled', true);
        //     // });
        //     // if(isNotEnough) $('#notEnoughProductInStock').modal('toggle');
        //     $scope.calculateCn();
        // }

        $scope.finishEdit = function() {
            $scope.isEdit = true;
            $scope.isFinishEdit = false;
            angular.forEach($scope.cnItems, function(value, key) {
                $('#textboxQuantity'+value.product_no).prop('disabled', true);
                $scope.cnItems[$scope.cnItems.indexOf(value)].quantity = $('#textboxQuantity'+value.product_no).val();
            });
            $scope.calculateCn();
        }


        $scope.calculateCn = function() {
            $scope.diff_total_sales_no_vat = 0;
            $scope.diff_total_sales_vat = 0;
            /////////////
            $scope.new_total_sales_price = 0;
            $scope.vat_total_sales_no_vat = 0;
            $scope.diff_total_sales_price = 0;
            $scope.sum_total_sales_no_vat = 0;
            $scope.totalPurchasePrice = 0;
            $scope.totalSalesPrice = 0;
            /////////////
            //$scope.new_total_purchase_price = 0;
            
            if($scope.cnItems.length != 0) {
                $scope.iv_total_sales_price = $scope.cnItems[0].iv_total_sales_price;
                angular.forEach($scope.cnItems, function(value, key) {
                    $scope.diff_total_sales_vat += (parseFloat(value.sales_vat) * parseFloat(value.quantity));
                    $scope.diff_total_sales_price += (parseFloat(value.sales_price) * parseFloat(value.quantity));
                    $scope.totalPurchasePrice += (value.quantity * value.purchase_price);
                    $scope.totalSalesPrice += (value.quantity * value.sales_price);
                });
                $scope.diff_total_sales_no_vat = (parseFloat($scope.diff_total_sales_vat) == 0) ? 0 : parseFloat($scope.diff_total_sales_price) / 1.07;
                $scope.diff_total_sales_vat = (parseFloat($scope.diff_total_sales_vat) == 0) ? 0 : (parseFloat($scope.diff_total_sales_price) / 107) * 7;
                $scope.new_total_sales_price = parseFloat($scope.iv_total_sales_price) - parseFloat($scope.diff_total_sales_price);
                $scope.sum_total_sales_no_vat = (parseFloat($scope.diff_total_sales_vat) == 0) ? 0 : parseFloat($scope.diff_total_sales_price) * 1.07;
                $scope.vat_total_sales_no_vat = Math.abs(parseFloat($scope.diff_total_sales_price) - parseFloat($scope.sum_total_sales_no_vat));
            }
        }

        
        // $scope.getcnDetail = function(ex) {
        //     // $("#pvItemRR").prop("disabled", true);
        //     // $("#pvItemIV").prop("disabled", true);
        //     //$("#cnTotalPrice").prop("disabled", true);
        //     // $scope.pvItemRR = ex.ci_no;
        //     // $scope.pvItemIV = ex.invoice_no;
        //     $scope.cnTotalPrice = ex.iv_total_sales_price;
        //     // $scope.ci_no = ex.ci_no;
        //     //console.log(cnTotalPrice);
        // }
        
        $scope.selectWSD = function($WSD) {
            if(!$WSD.editing) {
                $scope.invoice_no = $WSD.invoice_no;
                $scope.cnDetail = $WSD.note;
                $scope.wsd_no = $WSD.wsd_no;
                $scope.wsdNo = $WSD.wsd_no;
                $scope.formValidate1();
                if($scope.cnItems.length > 0) {
                    $scope.pvd_selected = true;
                }
            }
            $scope.cnTotalPrice = $WSD.iv_total_sales_price;
        }


        $scope.updateCN = function(x) {
            $scope.calculateCn();
        }

        $scope.stopEvent = function(e){
            e.stopPropagation();
        }

        $scope.formValidate1 = function() {  //get iv info and put pvd into SHOWING IVRC ITEMS section
            if($scope.invoice_no==='') {
                $('#formValidate5').modal('toggle');
            }   
             else {
                $http.post('/acc/credit_note/post_iv', 
                    JSON.stringify({iv_no : $scope.invoice_no.toUpperCase()})
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


        // move to end of pvd process        
        
        $scope.formValidate2 = function() {
            if($scope.cnItems.length == 0) {
                $('#formValidate3').modal('toggle');
            } 
            // else if($scope.isFinishEdit) {
            //     $scope.finishEdit();
            // }
            // else if($scope.cnDetail == '') {
            //     $('#formValidate4').modal('toggle');
            // }
            else {
                console.log();
                var confirmModal = addConfirmModal('confirmModal', 'ใบลดหนี้ / Credit Note', 'ยืนยันการออกใบลดหนี้', 'postCnItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
                console.log();
            }
        }
        

        //comment to prevent accidental creation of pvd remove

        $scope.postCnItems = function() {
            $scope.company = $scope.WSDs[0].invoice_no.substring(0,1);
            $scope.product_no = $scope
            $('#confirmModal').modal('hide');
            $.post("/acc/credit_note/post_cn", { 
                post : true,
                new_total_sales_price : $scope.new_total_sales_price,
                diff_total_sales_price : $scope.diff_total_sales_price,
                vat_total_sales_no_vat : $scope.vat_total_sales_no_vat,
                sum_total_sales_no_vat : $scope.sum_total_sales_no_vat,
                new_sales_price_thai : NumToThai(parseFloat($scope.sum_total_sales_no_vat)),

                totalPurchasePrice : $scope.totalPurchasePrice,
                totalSalesPrice : $scope.totalSalesPrice,
                cnItems : JSON.stringify(angular.toJson($scope.cnItems)),

                // file_no : $scope.invoice_no.toUpperCase(),
                //debit : $scope.debit,
                //credit : $scope.credit,
                // cnItems : JSON.stringify(angular.toJson($scope.cnItems)),
                invoice_no : $scope.invoice_no,
                wsd_no : $scope.wsdNo,
                company : $scope.company
            }, function(data) {
                addModal('successModal', 'ใบลดหนี้ / Credit Note', 'ออกใบลดหนี้ของ ' + $scope.wsdNo.toUpperCase() + data );
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.open('/file/cn/' + $scope.invoice_no);
                    window.location.reload();
                });
                // console.log();
            });
        }

  	});

</script>