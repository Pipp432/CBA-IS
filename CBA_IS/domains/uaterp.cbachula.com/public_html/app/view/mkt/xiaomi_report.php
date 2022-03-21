<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="calculateTotalPrice()">

        <h2 class="mt-3">Xiaomi Report</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="items.length == 0">
                        <tr>
                            <th>ไม่มี FO สำหรับส่งให้ Xiaomi</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="items.length != 0">
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า (Supplier)</th>
                            <th>จำนวนรวม</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="item in items">
                            <td>{{item.product_no}}</td>
                            <td>{{item.product_description}}</td>
                            <td style="text-align: right;">{{item.quantity}}</td>
                            <td style="text-align: right;">{{item.purchase_price * item.quantity | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="3">ราคารวม</th>
                            <th style="text-align: right;">{{totalPrice | number:2}}</th>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึก Xiaomi Report</button>
                </div>
             </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'Xiaomi Report', 'ไม่มีรายการที่ต้องสร้าง Xiaomi Report');
            addModal('formValidate2', 'Xiaomi Report', 'ยอดยังไม่ถึงเลยนะะ');
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

        $scope.items = <?php echo $this->report; ?>;
        $scope.totalPrice = 0;
            
        $scope.calculateTotalPrice = function(po) {
            angular.forEach($scope.items, function (value, key) {
               $scope.totalPrice += parseFloat(value.purchase_price * value.quantity);
            });
        }
        
        $scope.formValidate = function() {
            if($scope.items.length===0) {
                $('#formValidate1').modal('toggle');
            } else if ($scope.totalPrice < 10) {
                $('#formValidate2').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'Xiaomi Report', 'ยืนยันการออก Xiaomi Report', 'postXrItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.clicked = false;
        
        $scope.postXrItems = function() {
            
            $('#confirmModal').modal('hide');
            
            if(!$scope.clicked){
                $scope.clicked = true;
                $.post("/mkt/xiaomi_report/post_xr_items", {
                    post : true
                }, function(data) {
                    addModal('successModal', 'Xiaomi Report', 'บันทึก Xiaomi Report สำเร็จ');
                    $('#successModal').modal('toggle');
                    $('#successModal').on('hide.bs.modal', function (e) {
                        location.assign('/mkt/xiaomi_report/download');
                    });
                });   
            }
            
        }

    });

</script>