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
                            <option value="pva">PV-A</option>
                            <option value="pvc">PV-C</option>
                            <option value="pvd">PV-D</option>
                            
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
                            <th>วันที่ออกใบ</th>
                            <th>ประเภทการสั่งจ่าย</th>
                            <th>รายการการสั่งจ่าย</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                        <tr ng-repeat="pv in pvs | unique:'pv_no' | filter:{pv_no:filterPVNo, pv_type:filterPVType}" ng-click="addCpvItem(pv)">
                            <td style="text-align: center;">{{pv.pv_no}}</td>
                            <td  style="text-align: center;">{{pv.pv_date}}</td>
                            <td  style="text-align: center;">
                                {{pv.pv_type}}
                            </td>
                            <td  style="text-align: center;"><ul class="my-0">
                                <li ng-show = "pv.pv_type != 'pvd' && pv.pv_type != 'pva' && pv.pv_type != 'pvc' && pv_item.pv_no===pv.pv_no" ng-repeat="pv_item in pvs track by $index">{{pv_item.detail}} ({{pv_item.paid_total | number:2}})</li>
                                <li ng-show = "pv.pv_type == 'pvd'">-</li>
                                <pre ng-show = "pv.pv_type == 'pva'">{{pv.product_names}}</pre>
                                <li ng-show = "pv.pv_type == 'pvc'"><a href = "/acc/confirm_payment_voucher/get_pvcslip/{{pv.pv_no}}"> Slip</a></li>
                                <li ng-show = "pv.pv_type == 'pvc'"><a href = "/acc/confirm_payment_voucher/get_pvciv/{{pv.pv_no}}"> IV</a></li>
                            </ul></td>
                            <td style="text-align: center;">
                                {{pv.total_paid | number:2}}<br>
                                <a ng-show = "pv.pv_type != 'pvd' && pv.pv_type != 'pva'" href="/acc/confirm_payment_voucher/get_receipt/{{pv.pv_no}}" target="_blank">{{pv.receipt_name}}</a>
                                <a ng-show = "pv.pv_type == 'Supplier'" href="/acc/dashboard/get_PVB_CR/{{pv.pv_no}}" target="_blank">{{pv.cr_name}}</a>
                                <a ng-show = "pv.pv_type == 'pvd'" href="/acc/confirm_payment_voucher/get_pvdslip/{{pv.pv_no}}" target="_blank">Slip</a>
                                &ensp;
                                <a ng-show = "pv.pv_type == 'pvd'" href="https://uaterp.cbachula.com/file/pvd/{{pv.cn_no}}" target="_blank">PV-D</a> 
                                <a ng-show = "pv.pv_type == 'pva'" href="/acc/confirm_payment_voucher/get_pvaslip/{{pv.pv_no}}" target="_blank">slip</a> 
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
                            <td style="text-align: center;">{{cpvItem.pv_no}}</td>
                            <td style="text-align: center;">{{cpvItem.pv_date}}</td>
                            <td style="text-align: center;">{{cpvItem.pv_type}}</td>
                            <td style="text-align: center;"><ul class="my-0">
                                <li ng-repeat="cpv_item in cpvItems" ng-show="cpv_item.pv_no===cpvItem.pv_no && cpvItem.pv_type != 'pvc' && cpvItem.pv_type != 'pva'">{{cpv_item.detail}} ({{cpv_item.paid_total | number:2}})</li>
                                <div ng-show = "cpvItem.pv_type == 'pvc'">
                                   <ul ng-repeat = "detail in addedPV track by $index">
                                    <li>{{detail.date}} {{detail.details}} {{detail.money}}</li>
                                    
                                    

                                   </ul>
                                </div>
                                <pre ng-show = "cpvItem.pv_type == 'pva'">{{cpvItem.product_names}}</pre>
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
    td { border-bottom: 1px solid lightgray; text-align: center;}
    th { border-bottom: 1px solid lightgray; text-align: center; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.cpvItems = [];
        $scope.filterPVNo = '';
        $scope.filterPVType = '';
        $scope.pvs = [];
        $scope.filesUploaded = true;
        
        $http.get('/acc/confirm_payment_voucher/get_rr_ci_pv').then(function(response){
            $scope.pvs = $scope.pvs.concat(response.data);
        });

        $http.get('/acc/confirm_payment_voucher/get_pvd').then(function(response){ 
            angular.forEach(response['data'], function (value) {
                value.pv_type = "pvd";
                $scope.pvs.push(value);
            });
        });

        $http.get('/acc/confirm_payment_voucher/get_pva').then(function(response){ 
            angular.forEach(response['data'], function (value) {
                value.pv_type = "pva";
                $scope.pvs.push(value);
            });
        });
        $http.get('/acc/confirm_payment_voucher/get_pvc').then(function(response){ 
            angular.forEach(response['data'], function (value) {
                value.pv_type = "pvc";
                $scope.pvs.push(value);
                
            });
        });
        
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
          
           
          $scope.addedPV = JSON.parse($scope.cpvItems[0].details);
          
           
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
            
            var cpvds = []; //pvd
            var cpvas = []; //pva
            var cpvcs = []; //pvc
            var cpvs = []; //other pv
            var respond = '';
            var respond_count = 0;
            angular.forEach($scope.cpvItems, function (value, key) {
                if(value.pv_type == "pvd") {
                    cpvds.push(value.pv_no);
                } else if(value.pv_type == "pva") {
                    cpvas.push(value.pv_no);
                }else if(value.pv_type == "pvc") {
                    cpvcs.push(value.pv_no);
                } else cpvs.push(value);
            });
           
           
            if(cpvs.length != 0) {
                
                $.post("/acc/confirm_payment_voucher/post_cpv_items", {
                    post : true,
                    cpvItems : JSON.stringify(angular.toJson(cpvs))
                }, function(data) {
                    respond = respond.concat(data);
                    respond_count++;

                    if(respond_count == 4) {
                        addModal('successModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', 'ยืนยันการชำระเงินตามใบสั่งจ่ายเลขที่ ' + respond + 'สำเร็จ');
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        });
                    }


                });
            } else respond_count++;

            if(cpvds.length != 0) {
                $.post("/acc/confirm_payment_voucher/post_cpvd_items", { //todo impliment this in controller and model
                    post : true,
                    cpvItems : JSON.stringify(angular.toJson(cpvds))
                }, function(data) {
                    respond = respond.concat(data);
                    respond_count++;

                    if(respond_count == 4) {
                        addModal('successModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', 'ยืนยันการชำระเงินตามใบสั่งจ่ายเลขที่ ' + respond + 'สำเร็จ');
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        });
                    }
                });
            } else respond_count++;

            if(cpvas.length != 0) {
                $.post("/acc/confirm_payment_voucher/post_cpva_items", {
                    post : true,
                    cpvItems : JSON.stringify(angular.toJson(cpvas))
                }, function(data) {
                    respond = respond.concat(data);
                    respond_count++;

                    if(respond_count == 4) {
                        addModal('successModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', 'ยืนยันการชำระเงินตามใบสั่งจ่ายเลขที่ ' + respond + 'สำเร็จ');
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        });
                    }
                });
            } else respond_count++;
            $scope.cpvItems=$scope.cpvItems.filter(entries=>entries.pv_no===cpvcs[0]);
            if($scope.cpvItems[0].iv_name==null && $scope.cpvItems[0].slip_name==null){
                addModal('IVAndSlipErrorModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', "IV and Slip are missing");
                $('#IVAndSlipErrorModal').modal('toggle');
              
               
            }
            else if($scope.cpvItems[0].iv_name==null) {
                addModal('IVErrorModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', "IV is missing");
                $('#IVErrorModal').modal('toggle');
                
            }
            else if($scope.cpvItems[0].slip_name==null) {
                addModal('SlipErrorModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', "Slip is missing");
                $('#SlipErrorModal').modal('toggle');
                

            }
            else if(cpvcs.length != 0) {
                $.post("/acc/confirm_payment_voucher/post_cpvc_items", {
                    post : true,
                    pv_no:  $scope.cpvItems[0]['pv_no'],
                    totalPaid:$scope.cpvItems[0]['total_paid'],
                    cpvItems : JSON.stringify(angular.toJson(cpvcs))
                }, function(data) {
                    respond.concat(data);
                    respond_count++;

                    if(respond_count == 4) {
                        addModal('successModal', 'ยืนยันการชำระเงินตามใบสั่งจ่าย / Confirm Payment Voucher', 'ยืนยันการชำระเงินตามใบสั่งจ่ายสำเร็จ');
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            // location.reload();
                        });
                    }
                });
            } else respond_count++;


            
        }


        

  	});

</script>