<!DOCTYPE html>

<html>



<body>



    <div class="container mt-3" ng-controller="moduleAppController">



        <h2 class="mt-3">ใบสั่งซื้อ / Purchase Order (PO)</h2>



        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- SELECTING SUPPLIER AND PRODUCT TYPE -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">

            <div class="card-body">

                <div class="row mx-0">

                    <div class="col-md-6">

                        <label for="dropdownSupplier">Supplier</label>

                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">

                            <option value="">เลือก Supplier</option>

                            <option ng-repeat="supplier in suppliers | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier.supplier_no}}">

                                {{supplier.supplier_no}} : {{supplier.supplier_name}}

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label for="dropdownProductType">ประเภทสินค้า</label>

                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">

                            <option value="">เลือกประเภทสินค้า</option>

                            <option value="Stock">Stock</option>

                            <option value="Order">Order</option>

                            <option value="Install">Install</option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label for="buttonConfirmDetail" style="color:white;">.</label>

                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="confirmDetail()">ยืนยัน</button>

                    </div>

                </div>

            </div>

        </div>

        

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- SELECTING STOCK PRODUCT -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;" ng-show="showIfStock">

            <div class="card-body">

                <div class="row mx-0">

                    <div class="col-md-6">

                        <label for="dropdownCategory">Category</label>

                        <select class="form-control" ng-model="selectedCategory" id="dropdownCategory">

                            <option value="">เลือก Category</option>

                            <option ng-repeat="product in products | unique:'category_name'" value="{{product.category_name}}">{{product.category_name}}</option>

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label for="textboxProduct">Product Number / Product Name</label>

                        <input type="text" class="form-control" id="textboxProduct" ng-model="filterProduct">

                    </div>

                </div>

                <hr>

                <div class="row mx-0 mt-2">

                    <table class="table table-hover my-1" ng-show="products.length == 0">

                        <tr>

                            <th>ไม่มีสินค้า Stock ใน Supplier นี้</th>

                        </tr>

                    </table>

                    <table class="table table-hover my-1" ng-show="products.length != 0">

                        <tr>

                            <th>รหัสสินค้า</th>

                            <th>ชื่อสินค้า</th>

                            <th>หมวดหมู่</th>

                            <th>ราคาซื้อ</th>

                        </tr>

                        <tr ng-repeat="product in products | filter:{category_name:selectedCategory} | filter:filterProduct" ng-click="addPoItemStock(product)">

                            <td>{{product.product_no}}</td>

                            <td>{{product.product_name}}</td>

                            <td>{{product.category_name}}</td>

                            <td style="text-align: right;">{{product.purchase_price | number:2}}</td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>



        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- SELECTING ORDER AND INSTALL PRODUCT -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;" ng-show="showIfOrderInstall">

            <div class="card-body">

                <div class="row mx-0">

                    <label for="soNoTextbox">เลขที SO</label>

                    <input type="text" class="form-control" id="soNoTextbox" ng-model="filterSO">

                </div>

                <hr>

                <div class="row mx-0 mt-2">

                    <table class="table table-hover my-1" ng-show="sos.length == 0">

                        <tr>

                            <th>ไม่มี SO ใน Supplier นี้</th>

                        </tr>

                    </table>

                    <table class="table table-hover my-1" ng-show="sos.length != 0">

                        <tr>

                            <th>เลข SO</th>

                            <th>วัน เวลา</th>

                            <!--<th>รายการสินค้า</th>-->

                            <th>orderer</th>

                            <th>approver</th>

                        </tr>

                        <tr ng-repeat="so in sos | unique:'so_no' | filter:{product_type:selectedProductType, so_no:filterSO}" ng-click="addPoItemOrderInstall(so)">

                            <td>{{so.so_no}}</td>

                            <td>{{so.so_date + ' ' + so.so_time}}</td>

                            <!--<td><ul class="my-0">

                                <li ng-repeat="so_item in sos" ng-show="so_item.so_no===so.so_no">{{so_item.product_name}} (x{{so_item.quantity}})</li>

                            </ul></td>-->

                            <td>{{so.orderer}}</td>

                            <td>{{so.approved}}</td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>



        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- SHOWING PO ITEMS -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="showIfStock || showIfOrderInstall">

            <div class="card-body">

                <div class="row mx-0">

                    <h4 class="my-1">รายละเอียดใบสั่งซื้อ</h4>

                    <table class="table table-hover my-1" ng-show="poItems.length == 0">

                        <tr>

                            <th>ยังไม่ได้เพิ่มสินค้า</th>

                        </tr>

                    </table>

                    <table class="table table-hover my-1" ng-show="poItems.length != 0">

                        <tr>

                            <th colspan="2">รหัสสินค้า</th>

                            <th ng-show="showIfOrderInstall">เลข SO</th>

                            <th>ชื่อสินค้า</th>

                            <th>ราคาซื้อ</th>

                            <th>

                                จำนวน

                                <i class="fa fa-pencil" aria-hidden="true" ng-show="showIfStock && isEdit" ng-click="edit()"></i>

                                <i class="fa fa-check" aria-hidden="true" ng-show="showIfStock && isFinishEdit" ng-click="finishEdit()"></i>

                            </th>

                            <th>ราคาซื้อรวม</th>

                        </tr>

                        <tr ng-repeat="product in poItems | orderBy:'product_no'">

                            <td>

                                <i class="fa fa-times-circle" aria-hidden="true" ng-show="showIfStock" ng-click="dropPoItemStock(product)"></i>

                                <i class="fa fa-times-circle" aria-hidden="true" ng-show="showIfOrderInstall" ng-click="dropPoItemOrderInstall(product)"></i>

                            </td>

                            <td>{{product.product_no}}</td>

                            <td ng-show="showIfOrderInstall">{{product.so_no}}</td>

                            <td>{{product.product_name}}</td>

                            <td style="text-align: right;">{{product.purchase_price | number:2}}</td>

                            <td style="text-align: right;">

                                <div class="row justify-content-md-center">

                                <div class="col-4">

                                    <input type="text" class="form-control" id="textboxQuantity{{product.product_no}}" value="{{product.quantity}}" disabled>

                                </div>

                                </div>

                            </td>

                            <td style="text-align: right;">{{product.purchase_price * product.quantity | number:2}}</td>

                        </tr>

                        <tr>

                            <td ng-show="showIfOrderInstall"></td>

                            <th style="text-align: right;" colspan="5">ราคาซื้อรวม</th>

                            <th style="text-align: right;">{{totalPrice | number:2}}</th>

                        </tr>

                    </table>

                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึก PO</button>

                </div>

            </div>

        </div>

        

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- FORM VALIDATION -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <script>

            addModal('formValidate1', 'ใบสั่งซื้อ / Purchase Order (PO)', 'เลือก Supplier ก่อนนะครับผม');

            addModal('formValidate2', 'ใบสั่งซื้อ / Purchase Order (PO)', 'เลือกประเภทสินค้าก่อนนะครับผม');

            addModal('formValidate3', 'ใบสั่งซื้อ / Purchase Order (PO)', 'ยังไม่ได้เพิ่มสินค้าเข้าใบสั่งซื้อ');

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

        $scope.poItems = [];

        $scope.selectedSupplier='';

        $scope.selectedProductType='';

        $scope.suppliers = <?php echo $this->suppliers; ?>;

        $scope.allProducts = <?php echo $this->products; ?>;

        $scope.allSos = <?php echo $this->sos; ?>;

        

        $scope.showIfOrderInstall = false;

        $scope.showIfStock = false;

        

        $scope.isEdit = true;

        $scope.isFinishEdit = false;

        

        $scope.confirmDetail = function() {

            if($scope.selectedSupplier === '') {

                $('#formValidate1').modal('toggle');

            } else if($scope.selectedProductType === '') {

                $('#formValidate2').modal('toggle');

            } else {

                

                $('#dropdownSupplier').prop('disabled', true);

                $('#dropdownProductType').prop('disabled', true);

                $('#buttonConfirmDetail').prop('disabled', true);

                

                if ($scope.selectedProductType === 'Stock') {

                    $scope.showIfStock = true;

                    $scope.products = $scope.allProducts.filter(function filter(product) {return product.supplier_no == $scope.selectedSupplier;});

                } else if ($scope.selectedProductType === 'Order' || $scope.selectedProductType === 'Install') {

                    $scope.showIfOrderInstall = true;

                    $scope.sos = $scope.allSos.filter(function filter(product) {return product.supplier_no == $scope.selectedSupplier;});

                }

                

            }

        }



        $scope.addPoItemStock = function(product) {

            var newProduct = true;

            angular.forEach($scope.poItems, function (value, key) {

                if(value.product_no == product.product_no) {

                    newProduct = false;

                }

            });

            if(newProduct) {

                $scope.poItems.push(product);

                Object.assign($scope.poItems[$scope.poItems.length - 1], {quantity: 1});

                Object.assign($scope.poItems[$scope.poItems.length - 1], {so_no: '-'});

            }

            $scope.calculateTotalPrice();

        }



        $scope.dropPoItemStock = function(product) {

            angular.forEach($scope.poItems, function (value, key) {

                if(value.product_no == product.product_no) {

                    $scope.poItems.splice($scope.poItems.indexOf(value), 1);

                }

            });

            $scope.calculateTotalPrice();

        }

        

        $scope.addPoItemOrderInstall = function(so) {

            var newSo = true;

            angular.forEach($scope.poItems, function (value, key) {

                if(value.so_no == so.so_no) {

                    newSo = false;

                }

            });

            if(newSo) {

                angular.forEach($scope.sos, function (value, key) {

                    if(value.so_no == so.so_no) {

                        $scope.poItems.push(value);

                    }

                });

            }

            $scope.calculateTotalPrice();

        }

        

        $scope.dropPoItemOrderInstall = function(product) {

            var tempRemoved = [];

            angular.forEach($scope.poItems, function (value, key) {

                if(value.so_no != product.so_no) {

                    tempRemoved.push(value);

                }

            });

            $scope.poItems = tempRemoved;

            $scope.calculateTotalPrice();

        }



        $scope.calculateTotalPrice = function() {

            $scope.totalNoVat = 0;

            $scope.totalVat = 0;

            $scope.totalPrice = 0;

            angular.forEach($scope.poItems, function(value, key) {

                $scope.totalNoVat += (value.quantity * value.purchase_no_vat);

                $scope.totalVat += (value.quantity * value.purchase_vat);

                $scope.totalPrice += (value.quantity * value.purchase_price);

            });

        }

        

        $scope.edit = function() {

            $scope.isEdit = false;

            $scope.isFinishEdit = true;

            angular.forEach($scope.poItems, function(value, key) {

                $('#textboxQuantity'+value.product_no).prop('disabled', false);

            });

        }

        

        $scope.finishEdit = function() {

            $scope.isEdit = true;

            $scope.isFinishEdit = false;

            angular.forEach($scope.poItems, function(value, key) {

                $('#textboxQuantity'+value.product_no).prop('disabled', true);

                $scope.poItems[$scope.poItems.indexOf(value)].quantity = $('#textboxQuantity'+value.product_no).val();

            });

            $scope.calculateTotalPrice();

        }

        

        $scope.formValidate = function() {

            if($scope.poItems.length===0) {

                $('#formValidate3').modal('toggle');

            } else if($scope.isFinishEdit) {

                $scope.finishEdit();

            } else {

                var confirmModal = addConfirmModal('confirmModal', 'ใบสั่งซื้อ / Purchase Order (PO)', 'ยืนยันการออกใบสั่งซื้อ', 'postPoItems()');

                $('body').append($compile(confirmModal)($scope));

                $('#confirmModal').modal('toggle');

            }

        }



        $scope.postPoItems = function() {

            $('#confirmModal').modal('hide');

            $.post("purchase_order/post_po_items", {

                post : true,

                supplierNo : $scope.selectedSupplier,

                productType : $scope.selectedProductType,

                totalNoVat : $scope.totalNoVat,

                totalVat : $scope.totalVat,

                totalPrice : $scope.totalPrice,

                poItems : JSON.stringify(angular.toJson($scope.poItems))

            }, function(data) {

                addModal('successModal', 'ใบสั่งซื้อ / Purchase Order (PO)', 'บันทึก ' + data + ' สำเร็จ');

                $('#successModal').modal('toggle');

                $('#successModal').on('hide.bs.modal', function (e) {

                    window.location.assign('/');

                });

            });

        }



    });



</script>