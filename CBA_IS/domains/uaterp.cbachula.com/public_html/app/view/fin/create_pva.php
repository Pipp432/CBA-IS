<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ออกใบเติมเงินรองจ่าย</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING pre pva -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-6">
                        <label for="filterInternalPvaNo">เลขที่ใบเบิกเงินรองจ่าย</label>
                        <input type="text" class="form-control" id="filterInternalPvaNo" ng-model="filterInternalPvaNo">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="pvas.length == 0">
                        <tr>
                            <th>ไม่มีใบสั่งจ่ายที่มีการชำระเงิน</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pvas.length != 0" >
                        <tr>
                            <th>เลขที่ใบเบิกเงินรองจ่าย</th>
                            <th>วันที่</th>
                            <th>รายการ</th>
                            <th>จำนวนเงิน</th>
                            <th>เอกสาร</th>
                        </tr>
                        <tr ng-repeat="pva in pvas | unique:'internal_pva_no' | filter:{internal_pva_no:filterInternalPvaNo}" ng-click="addCpvItem(pva)" style="text-align: center;">
                            <td>{{pva.internal_pva_no}}</td>
                            <td>{{pva.pv_date}} {{pva.pv_time}}</td>
                            <td>{{pva.product_names}}</td>
                            <td>{{pva.total_paid | number:2}}</td>
                            <td ng-click="stopEvent($event)">
                                <a href="/fin/validate_petty_cash_request/get_re/{{pva.internal_pva_no}}" target="_blank">{{pva.ivrc_name}}</a>
                                <a href="/fin/validate_petty_cash_request/get_iv/{{pva.internal_pva_no}}" target="_blank">{{pva.slip_name}}</a>
                                <a href="/fin/create_pva/get_fin_slip/{{pva.internal_pva_no}}" target="_blank">{{pva.fin_slip_name}}</a> 
                            </td>
                        </tr>
                    </table>
                </div>
            </div> 
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING pva ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียด PV-A</h4>
                        <!-- <select class="form-control" ng-model="program">
                            <option value=''>สั่งจ่ายในนาม</option>
                            <option value='1'>โครงการ 1</option>
                            <option value='2'>โครงการ 2</option>
                            <option value='3'>โครงการ 3</option>
                            <option value='8'>โครงการพิเศษ 1</option>
                            <option value='9'>โครงการพิเศษ 2</option>
                        </select> -->
                    <table class="table table-hover my-1" ng-show="cpvas.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม PV-A</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="cpvas.length != 0">
                        <tr>
                            <th></th>
                            <th>เลขที่ใบเบิกเงินรองจ่าย</th>
                            <th>วันที่</th>
                            <th>รายการ</th>
                            <th>จำนวนเงิน</th>
                        </tr>
                        <tr ng-repeat="cpvItem in cpvas | unique:'internal_pva_no'" style="text-align: center;">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCpvItem(cpvItem)"></i></td>
                            <td>{{cpvItem.internal_pva_no}}</td>
                            <td>{{cpvItem.pv_date}} {{pva.pv_time}}</td>
                            <td>{{cpvItem.product_names}}</td>
                            <td>{{cpvItem.total_paid | number:2}}</td>
                        </tr>
                    </table>
                </div>

                <br>

                <div class="row mx-0">
                    <label for="petty_cash_statement">Petty cash statement</label><br>
                    <input id='petty_cash_statement' type="file" class="form-control-file" name='petty_cash_statement'>
                </div>
                <div class="row-md-4">
                    <label for="additional_cash">จำนวนที่ต้องการเติมเพิ่ม</label><br>
                    <input id='additional_cash' type="number" name='additional_cash' ng-model = 'additionalCash'>
                </div>
                <div class="row-md-4">
                    <label for="why_more_cash">เหตุผล</label><br>
                    <input id='why_more_cash' type="text" name='why_more_cash' ng-model = "whyMoreCash">
                </div>

                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">สร้างใบเติมเงินรองจ่าย</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ยืนยันการสร้าง PV-A / Confirm PV-A creation', 'กรอกข้อมูลไม่ครบ', '');
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
        
        $scope.cpvas = [];
        $scope.filterPVNo = '';
        $scope.filterPVType = '';
        $scope.pvas = [];
        $scope.additionalCash = 0;
        $scope.whyMoreCash = '';
        //$scope.program = '';
        

        $http.get('/fin/create_pva/get_pva').then(function(response){
            $scope.pvas = response['data'];
        });

        $scope.stopEvent = function(e){ //stop function
            e.stopPropagation();
        }
        
        $scope.addCpvItem = function(pva) {
            var newPv = true;
            angular.forEach($scope.cpvas, function (value, key) {
                if(value.internal_pva_no == pva.internal_pva_no) {
                    newPv = false;
                }
            });

            if(newPv) $scope.cpvas.push(pva);
        }
        
        $scope.dropCpvItem = function(cpvItem) {
            for( var i = 0; i < $scope.cpvas.length; i++){ 
                if ( $scope.cpvas[i].internal_pva_no == cpvItem.internal_pva_no) { 
                    $scope.cpvas.splice(i, 1); 
                }
            }
        }
        
        $scope.formValidate = function() {
            if($scope.cpvas.length===0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันการสร้าง ใบเติมเงินรองจ่าย / Confirm ใบเติมเงินรองจ่าย creation', 'ยืนยันการสร้าง ใบเติมเงินรองจ่าย', 'postcpvas()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postcpvas = function() {
            $('#confirmModal').modal('hide');


            var formData = new FormData();
            formData.append('pettyCashStatement', $('#petty_cash_statement')[0].files[0]);
            formData.append('additionalCash', $scope.additionalCash);
            formData.append('whyMoreCash', $scope.whyMoreCash);
            formData.append('cpvItems' , $scope.cpvas);

            $.ajax({ 
                url: '/fin/create_pva/create_pva',  
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
            }).done(function (data) {
                if(data.length == 9){
                        addModal('successModal', 'สร้างใบเติมเงินรองจ่ายสำเร็จ', data);
                        $('#successModal').modal('toggle');
                        $('#successModal').on('hide.bs.modal', function (e) {
                            location.reload();
                        });
                    } else {
                        addModal('failedModal', 'สร้างใบเติมเงินรองจ่าย มีความผิกพลาด', 'บอก is หน่อย');
                        $('#failedModal').modal('toggle');
                        console.log(data);
                    }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log('ajax.fail');
                addModal('uploadFailModal', 'สร้างใบเติมเงินรองจ่าย มีความผิกพลาด', 'บอก is หน่อย');
                $('#uploadFailModal').modal('toggle');
            });



        }

        

  	});

</script>