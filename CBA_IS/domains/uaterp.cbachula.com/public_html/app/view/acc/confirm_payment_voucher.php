<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CI/RR -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-6">
                        <label for="pvNoTextbox">เลขที่ PV</label>
                        <input type="text" class="form-control" id="pvNoTextbox" ng-model="filterPVNo">
                    </div>
                    <div class="col-md-6">
                        <label for="dropdownPVType">ประเภทการสั่งจ่าย</label>
                        <select class="form-control" ng-model="filterPVType" id="dropdownPVType">
                            <option value="">เลือกประเภทการสั่งจ่าย</option>
                            <option value="เงินรองจ่าย">เงินรองจ่าย</option>
                            <option value="Supplier">จ่าย Supplier</option>
                            <option value="Expense">ค่าใช้จ่าย</option>
                            <option value="เงินมัดจำ">เงินมัดจำ</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="pvs.length == 0">
                        <tr>
                            <th>ไม่มีใบสั่งจ่ายที่มีการชำระเงิน</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pvs.length != 0">
                        <tr>
                            <th>เลข PV</th>
                            <th>วันที่</th>
                            <th>ประเภทการสั่งจ่าย</th>
                            <th>รายการการสั่งจ่าย</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                        <tr ng-repeat="pv in pvs | unique:'pv_no' | filter:{pv_no:filterPVNo, pv_type:filterPVType}" ng-click="addCpvItem(pv)">
                            <td>{{pv.pv_no}}</td>
                            <td>{{pv.pv_date}}</td>
                            <td>{{pv.pv_type}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="pv_item in pvs" ng-show="pv_item.pv_no===pv.pv_no">{{pv_item.detail}} ({{pv_item.paid_total | number:2}})</li>
                            </ul></td>
                            <td style="text-align: right;">
                                {{pv.total_paid | number:2}}<br>
                                <a href="/acc/confirm_payment_voucher/get_receipt/{{pv.pv_no}}" target="_blank">{{pv.receipt_name}}</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING IVRC ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดการชำระเงิน</h4>
                    <table class="table table-hover my-1" ng-show="cpvItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มรายละเอียดการชำระเงิน</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="cpvItems.length != 0">
                        <tr>
                            <th colspan="2">เลข PV</th>
                            <th>วันที่</th>
                            <th>ประเภทการสั่งจ่าย</th>
                            <th>รายการการสั่งจ่าย</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                        <tr ng-repeat="cpvItem in cpvItems | unique:'pv_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCpvItem(cpvItem)"></i></td>
                            <td>{{cpvItem.pv_no}}</td>
                            <td>{{cpvItem.pv_date}}</td>
                            <td>{{cpvItem.pv_type}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="cpv_item in cpvItems" ng-show="cpv_item.pv_no===cpvItem.pv_no">{{cpv_item.detail}} ({{cpv_item.paid_total | number:2}})</li>
                            </ul></td>
                            <td style="text-align: right;">{{cpvItem.total_paid | number:2}}</td>
                        </tr>
                    </table>
                </div>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ยืนยันการชำระเงินตามใบสั่งจ่าย</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', 'ยังไม่ได้เพิ่มรายละเอียดการชำระเงิน');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>

</body>

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: center; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.cpvItems = [];
        $scope.filterPVNo = '';
        $scope.filterPVType = '';
        
        $http.get('/acc/confirm_payment_voucher/get_rr_ci_pv').then(function(response){
            $scope.pvs = response.data;
        });
        
        $scope.addCpvItem = function(rrcipv) {
            angular.forEach($scope.pvs, function (value, key) {
                if(value.ci_no == rrci.ci_no) {
                    $scope.cpvItems.push(value);
                    $scope.total_purchase_no_vat = value.confirm_subtotal;
                    $scope.total_purchase_vat = value.confirm_vat;
                    $scope.total_purchase_price = value.confirm_total;
                }
            });
        }
        
        $scope.addCpvItem = function(pv) {
            var newPv = true;
            angular.forEach($scope.cpvItems, function (value, key) {
                if(value.pv_no == pv.pv_no) {
                    newPv = false;
                }
            });
            if(newPv) {
                angular.forEach($scope.pvs, function (value, key) {
                    if(value.pv_no == pv.pv_no) {
                        $scope.cpvItems.push(value);
                    }
                });
            }
        }
        
        $scope.dropCpvItem = function(cpvItem) {
            var tempCpvItem = [];
            angular.forEach($scope.cpvItems, function (value, key) {
                if(value.pv_no != cpvItem.pv_no) {
                    tempCpvItem.push(value);
                }
            });
            $scope.cpvItems = tempCpvItem;
        }
        
        $scope.formValidate = function() {
            if($scope.cpvItems.length===0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', 'ยืนยันการชำระเงินตามใบสั่งจ่าย', 'postCpvItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postCpvItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/acc/confirm_payment_voucher/post_cpv_items", {
                post : true,
                cpvItems : JSON.stringify(angular.toJson($scope.cpvItems))
            }, function(data) {
                addModal('successModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', 'ยืนยันการชำระเงินตามใบสั่งจ่ายเลขที่ ' + data + 'สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });
            });
        }
        
        // $scope.postcpvItems = function() {
            
        //     if($scope.cpvItems.length === 0) {
        //         $('#formValidate1').modal('toggle');
        //     } else if ($scope.iv === '') {
        //         $('#formValidate2').modal('toggle');
        //     } else {
                
        //         var data = new FormData();
        //         data.append('ivFile', $('#ivFile')[0].files[0]);
        //         data.append('iv', $scope.iv);
        //         data.append('cpvItems', JSON.stringify(angular.toJson($scope.cpvItems)));
                
        //         $.ajax({
        //             url: '/acc/invoice_receipt_confirm/post_ivrc',
        //             data: data,
        //             cache: false,
        //             contentType: false,
        //             processData: false,
        //             method: 'POST',
        //             type: 'POST',
        //             success: function () {
        //                 addModal('successModal', 'ยืนยันการวางบิลจาก Supplier / Invoice Receipt Confirm (IVRC)', 'บันทึกการวางบิลเรียบร้อยแล้ว');
        //                 $('#successModal').modal('toggle');
        //                 $('#successModal').on('hide.bs.modal', function (e) {
        //                     window.location.assign('/');
        //                 });
        //             }
        //         });
                
        //     }
            
        // }

  	});

</script>