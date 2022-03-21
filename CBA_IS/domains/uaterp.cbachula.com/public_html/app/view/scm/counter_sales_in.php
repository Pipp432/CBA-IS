<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Counter Sales (CS) - In</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="soxNoTextbox">เลขที่ CS</label>
                        <input type="text" class="form-control" id="soxNoTextbox" ng-model="filterCs">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="css.length == 0">
                        <tr>
                            <th>ไม่มี CS ที่ยังไม่ได้เอาของกลับเข้าคลัง</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="css.length != 0">
                        <tr>
                            <th>เลข CS</th>
                            <th>วันที่ขาย</th>
                            <th>รหัส CE</th>
                            <th>สถานที่</th>
							<th>ใบ CS</th>
                            <!--<th>รายการสินค้า</th>-->
                        </tr>
                        <tr ng-repeat="cs in css | unique:'cs_no' | filter:{cs_no:filterCs}" ng-click="addCsItem(cs)">
                            <td>{{cs.cs_no}}</td>
                            <td>{{cs.cs_date}}</td>
                            <td>{{cs.employee_id}} {{cs.employee_nickname_thai}}</td>
                            <td>{{cs.location_name}}</td>
							<!--<td><a href="/file/cs/scm/{{cs.cs_no}}" target="_blank">ดู</a></td>-->
                            <td><a href="#" ng-click="openFile(cs.cs_no);">ดู</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING CS ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดการเอาของกลับเข้าคลัง</h4>
                    <table class="table table-hover my-1" ng-show="csItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม CS ที่จะเอาของกลับเข้าคลัง</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="csItems.length != 0">
                        <tr>
                            <th colspan="2">เลข CS</th>
                            <th>สินค้า</th>
                            <th>จำนวน (ออก)</th>
                            <th>
                                จำนวน (เข้า)
                                <i class="fa fa-pencil" aria-hidden="true" ng-show="isEdit" ng-click="edit()"></i>
                                <i class="fa fa-check" aria-hidden="true" ng-show="isFinishEdit" ng-click="finishEdit()"></i>
                            </th>
                            <th>จำนวน (ขาย)</th>
                        </tr>
                        <tr ng-repeat="product in csItems | orderBy:'cs_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCsItem(product)"></i></td>
                            <td>{{product.cs_no}}</td>
                            <td>{{product.product_name}}</td>
                            <td style="text-align: right;">{{product.quantity}}</td>
                            <td style="text-align: right;">
                                <div class="row justify-content-md-center">
                                <div class="col-4">
                                    <input type="text" class="form-control" id="textboxQuantity{{product.product_no}}" value="{{product.quantity_in}}" disabled>
                                </div>
                                </div>
                            </td>
                            <td style="text-align: right;">{{product.quantity - product.quantity_in}}</td>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ยืนยันการนำสินค้าเข้าคลัง</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'Counter Sales (CS)', 'ยังไม่ได้เลือกเลข CS');
            addModal('formValidate2', 'Counter Sales (CS)', 'ของเข้าเยอะกว่าของที่เอาออกไปจ้าา');
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
    
    function openFile(cs_no) {
        window.open('/file/cs/scm/' + cs_no);
        window.open('/file/cs/mkt/' + cs_no);
    }

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.csItems = [];
        $scope.filterSox = '';
        $scope.selectedProductType = '';
        
        $scope.isEdit = true;
        $scope.isFinishEdit = false;
        
        $scope.css = <?php echo $this->css; ?>;
        
        $scope.openFile = function(cs_no) {
            window.open('/file/cs/scm/' + cs_no);
            window.open('/file/cs/mkt/' + cs_no);
        }
            
        $scope.addCsItem = function(cs) {
            $scope.csItems = [];
            angular.forEach($scope.css, function (value, key) {
                if(value.cs_no == cs.cs_no) {
                    $scope.csItems.push(value);
                    Object.assign(value, {quantity_in : value.quantity});
                }
            });
        }
        
        $scope.dropCsItem = function(product) {
            var tempRemoved = [];
            angular.forEach($scope.csItems, function (value, key) {
                if(value.cs_no != product.cs_no) {
                   tempRemoved.push(value);
                }
            });
            $scope.csItems = tempRemoved;
        }
        
        $scope.edit = function() {
            $scope.isEdit = false;
            $scope.isFinishEdit = true;
            angular.forEach($scope.csItems, function(value, key) {
                $('#textboxQuantity'+value.product_no).prop('disabled', false);
            });
        }
        
        $scope.finishEdit = function() {
            var moreThanOut = false;
            $scope.isEdit = true;
            $scope.isFinishEdit = false;
            angular.forEach($scope.csItems, function(value, key) {
                $('#textboxQuantity'+value.product_no).prop('disabled', true);
                $scope.csItems[$scope.csItems.indexOf(value)].quantity_in = $('#textboxQuantity'+value.product_no).val();
                if (parseInt($('#textboxQuantity' + value.product_no).val()) > parseInt(value.quantity)) {
                    value.quantity_in = value.quantity;
                    $('#textboxQuantity' + value.product_no).val(value.quantity);
                    moreThanOut = true;
                } 
            });
            if(moreThanOut) $('#formValidate2').modal('toggle');
        }
        
        $scope.formValidate = function() {
            if($scope.csItems.length == 0) {
                $('#formValidate1').modal('toggle');
            } else {
                $scope.finishEdit();
                var confirmModal = addConfirmModal('confirmModal', 'Counter Sales (CS)', 'ยืนยันการนำสินค้าเข้าระบบ', 'postCsItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postCsItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/scm/counter_sales_in/post_cs_items", {
                cs_no : $scope.csItems[0]['cs_no'],
                csItems : JSON.stringify(angular.toJson($scope.csItems))
            }, function(data) {
                addModal('successModal', 'Counter Sales (CS)', 'ยืนยันการนำสินค้าเข้าระบบเลขที่ ' + $scope.csItems[0]['cs_no'] + ' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });
            });
         }

  	});

</script>