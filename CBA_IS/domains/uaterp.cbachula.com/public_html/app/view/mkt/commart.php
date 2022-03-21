<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">CBA x COMMART</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING COMMART PRODUCT -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-6">
                        <label for="dropdownCategory">Category</label>
                        <select class="form-control" ng-model="selectedCategory" id="dropdownCategory">
                            <option value="">เลือก Category</option>
                            <option ng-repeat="product in allProducts | unique:'category_name'" value="{{product.category_name}}">{{product.category_name}}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="textboxProduct">Product Number / Product Name</label>
                        <input type="text" class="form-control" id="textboxProduct" ng-model="filterProduct">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="allProducts.length == 0">
                        <tr>
                            <th>ไม่มีสินค้า</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="allProducts.length != 0">
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>หมวดหมู่</th>
                            <th>ราคาขาย</th>
                        </tr>
                        <tr ng-repeat="product in allProducts | filter:{category_name:selectedCategory} | filter:filterProduct" ng-click="addItem(product)">
                            <td>{{product.product_no}}</td>
                            <td>{{product.product_name}}</td>
                            <td>{{product.category_name}}</td>
                            <td style="text-align: right;">{{product.sales_price | number:2}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียด Order</h4>
                    <table class="table table-hover my-1" ng-show="items.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มสินค้า</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="items.length != 0">
                        <tr>
                            <th colspan="2">ชื่อสินค้า</th>
                            <th>ราคาขาย</th>
                            <th>
                                จำนวน
                                <i class="fa fa-pencil" aria-hidden="true" ng-show="isEdit" ng-click="edit()"></i>
                                <i class="fa fa-check" aria-hidden="true" ng-show="isFinishEdit" ng-click="finishEdit()"></i>
                            </th>
                            <th>ราคาขายรวม</th>
                        </tr>
                        <tr ng-repeat="product in items | orderBy:'product_no'">
                            <td>
                                <i class="fa fa-times-circle" aria-hidden="true" ng-click="dropItem(product)"></i>
                            </td>
                            <td>{{product.product_name}}</td>
                            <td style="text-align: right;">{{product.sales_price | number:2}}</td>
                            <td style="text-align: right;">
                                <div class="row justify-content-md-center">
                                <div class="col-4">
                                    <input type="text" class="form-control" id="textboxQuantity{{product.product_no}}" value="{{product.quantity}}" disabled>
                                </div>
                                </div>
                            </td>
                            <td style="text-align: right;">{{product.sales_price * product.quantity | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="4">ราคาขายรวม</th>
                            <th style="text-align: right;">{{totalSalesPrice | number:2}}</th>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึก Order</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate3', 'CBA x COMMART', 'ยังไม่ได้เพิ่มสินค้าเข้าใบสั่งซื้อ');
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

        $scope.items = [];
        $scope.allProducts = <?php echo $this->products; ?>;
        
        $scope.isEdit = true;
        $scope.isFinishEdit = false;

        $scope.addItem = function(product) {
            var newProduct = true;
            angular.forEach($scope.items, function (value, key) {
                if(value.product_no == product.product_no) {
                    newProduct = false;
                }
            });
            if(newProduct) {
                $scope.items.push(product);
                Object.assign($scope.items[$scope.items.length - 1], {quantity: 1});
                Object.assign($scope.items[$scope.items.length - 1], {so_no: '-'});
            }
            $scope.calculateTotalPrice();
        }

        $scope.dropItem = function(product) {
            angular.forEach($scope.items, function (value, key) {
                if(value.product_no == product.product_no) {
                    $scope.items.splice($scope.items.indexOf(value), 1);
                }
            });
            $scope.calculateTotalPrice();
        }

        $scope.calculateTotalPrice = function() {
            $scope.totalPurchasePrice = 0;
            $scope.totalSalesPrice = 0;
            angular.forEach($scope.items, function(value, key) {
                $scope.totalPurchasePrice += (value.quantity * value.purchase_price);
                $scope.totalSalesPrice += (value.quantity * value.sales_price);
            });
        }
        
        $scope.edit = function() {
            $scope.isEdit = false;
            $scope.isFinishEdit = true;
            angular.forEach($scope.items, function(value, key) {
                $('#textboxQuantity'+value.product_no).prop('disabled', false);
            });
        }
        
        $scope.finishEdit = function() {
            $scope.isEdit = true;
            $scope.isFinishEdit = false;
            angular.forEach($scope.items, function(value, key) {
                $('#textboxQuantity'+value.product_no).prop('disabled', true);
                $scope.items[$scope.items.indexOf(value)].quantity = $('#textboxQuantity'+value.product_no).val();
            });
            $scope.calculateTotalPrice();
        }
        
        $scope.formValidate = function() {
            if($scope.items.length===0) {
                $('#formValidate3').modal('toggle');
            } else if($scope.isFinishEdit) {
                $scope.finishEdit();
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'CBA x COMMART', 'ยืนยัน Order', 'postItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }

        $scope.postItems = function() {
            $('#confirmModal').modal('hide');
            $.post("commart/post_items", {
                post : true,
                totalPurchasePrice : $scope.totalPurchasePrice,
                totalSalesPrice : $scope.totalSalesPrice,
                items : JSON.stringify(angular.toJson($scope.items))
            }, function(data) {
                // console.log(data);
                addModal('successModal', 'CBA x COMMART', 'บันทึก ' + data + ' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.open('/file/po2/' + data);
                    location.reload();
                });
            });
        }

    });

</script>