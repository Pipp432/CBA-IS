<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยืนยันใบสั่งซื้อ / Confirm Purchase Order</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="poNoTextbox">เลขที่ใบสั่งซื้อ (PO)</label>
                        <input type="text" class="form-control" id="poNoTextbox" ng-model="filterPo">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="pos.length == 0">
                        <tr>
                            <th>ไม่มี PO ที่ยังไม่ยืนยัน</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="pos.length != 0">
                        <tr>
                            <th>เลข PO</th>
                            <th>วันที่</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="po in pos | unique:'po_no' | filter:{po_no:filterPo}" ng-click="addCpoItem(po)">
                            <td>{{po.po_no}}</td>
                            <td>{{po.po_date}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="po_item in pos" ng-show="po_item.po_no===po.po_no">{{po_item.product_name}} (x{{po_item.quantity}})</li>
                            </ul></td>
                            <td style="text-align: right;">{{po.po_total_purchase_price | number:2}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING CONFIRM PURCHASE ORDER ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดการยืนยันใบสั่งซื้อ</h4>
                    <table class="table table-hover my-1" ng-show="cpoItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม PO</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="cpoItems.length != 0">
                        <tr>
                            <th colspan="2">เลข PO</th>
                            <th>วันที่</th>
                            <th>รายการสินค้า</th>
                            <th>ราคาซื้อ</th>
                        </tr>
                        <tr ng-repeat="product in cpoItems | orderBy:'po_no' | unique:'po_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCpoItem(product)"></i></td>
                            <td>{{product.po_no}}</td>
                            <td>{{product.po_date}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="po_item in cpoItems" ng-show="po_item.po_no===product.po_no">{{po_item.product_name}} (x{{po_item.quantity}})</li>
                            </ul></td>
                            <td style="text-align: right;">{{product.po_total_purchase_price | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">ราคารวม</th>
                            <th style="text-align: right;">{{totalPrice | number:2}}</th>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ยืนยันใบสั่งซื้อ</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ยืนยันใบสั่งซื้อ / Confirm Purchase Order', 'ยังไม่ได้เพิ่มเลข PO');
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

        $scope.cpoItems = [];
        $scope.totalPrice = 0;
        
        $scope.pos = <?php echo $this->pos; ?>;
        
        $scope.addCpoItem = function(po) {
            var newPo = true;
            angular.forEach($scope.cpoItems, function (value, key) {
                if(value.po_no == po.po_no) {
                    newPo = false;
                }
            });
            if(newPo) {
                angular.forEach($scope.pos, function (value, key) {
                    if(value.po_no == po.po_no) {
                        $scope.cpoItems.push(value);
                    }
                });
            }
            $scope.calculateTotalPrice();
        }
        
        $scope.dropCpoItem = function(product) {
            var tempRemoved = [];
            angular.forEach($scope.cpoItems, function (value, key) {
                if(value.po_no != product.po_no) {
                    tempRemoved.push(value);
                }
            });
            $scope.cpoItems = tempRemoved;
            $scope.calculateTotalPrice();
        }
        
        $scope.calculateTotalPrice = function() {
            $scope.totalPrice = 0;
            angular.forEach($scope.cpoItems, function(value, key) {
                $scope.totalPrice += parseFloat(value.total_purchase_price);
            });
        }
        
        $scope.formValidate = function() {
            if($scope.cpoItems.length == 0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันใบสั่งซื้อ / Confirm Purchase Order', 'ยืนยันใบสั่งซื้อ', 'postCpoItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postCpoItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/scm/confirm_purchase_order/post_cpo_items", {
                cpoItems : JSON.stringify(angular.toJson($scope.cpoItems))
            }, function(data) {
                addModal('successModal', 'ยืนยันใบสั่งซื้อ / Confirm Purchase Order', 'ยืนยันใบสั่งซื้อเลขที่ ' + data + 'สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });
            });
        }

  	});

</script>