<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ใบคืนสินค้า / Return Inventory (RI)</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING SUPPLIER AND PRODUCT TYPE -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                <div class="col-md-3">
                        <label for="dropdownProductLine">Product Line</label>
                        <select class="form-control" ng-model="selectedProductLine" id="dropdownProductLine">
                            <option value="">เลือก Line</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="0">10</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dropdownSupplier">Supplier</label>
                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">
                            <option value="">เลือก Supplier</option>
                            <option ng-repeat="supplier in allProducts | filter:{product_line:selectedProductLine} | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier}}">
                                {{supplier.supplier_no}} : {{supplier.supplier_name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
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
                        <tr ng-repeat="product in products | filter:{supplier_no:selectedSupplierNo, category_name:selectedCategory, product_type:selectedProductType}  | filter:filterProduct " ng-click="addRIItemStock(product)">
                            <td>{{product.product_no}}</td>
                            <td>{{product.product_name}}
                                <span class="badge badge-pill badge-danger" ng-show="product.stock == 0 && (selectedProductType == 'Stock' || product.product_line == 'X')">สินค้าหมด</span>
                                <span class="badge badge-pill badge-warning" ng-show="product.stock < 10 && product.stock > 0 && (selectedProductType == 'Stock' || product.product_line == 'X')">เหลือ {{product.stock}} {{product.unit}}</span>
                                <span class="badge badge-pill badge-success" ng-show="product.stock >= 10 && (selectedProductType == 'Stock' || product.product_line == 'X')">เหลือ {{product.stock}} {{product.unit}}</span>
                            </td>
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
        
      

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING PO ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="showIfStock || showIfOrderInstall">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดใบคืนสินค้า</h4>
                    <table class="table table-hover my-1" ng-show="riItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มสินค้า</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="riItems.length != 0">
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
                        <tr ng-repeat="product in riItems | orderBy:'product_no'">
                            <td>
                                <i class="fa fa-times-circle" aria-hidden="true" ng-show="showIfStock" ng-click="dropRIItemStock(product)"></i>
                                <i class="fa fa-times-circle" aria-hidden="true" ng-show="showIfOrderInstall" ng-click="dropRIItemOrderInstall(product)"></i>
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
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึก RI</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ใบคืนสินค้า / Return Inventory (RI)', 'เลือก Supplier ก่อนนะครับผม');
            addModal('formValidate2', 'ใบคืนสินค้า / Return Inventory (RI)', 'เลือกประเภทสินค้าก่อนนะครับผม');
            addModal('formValidate3', 'ใบคืนสินค้า / Return Inventory (RI)', 'ยังไม่ได้เพิ่มสินค้าเข้าใบคืนสินค้า');
            addModal('noProductInStock', 'ใบคืนสินค้า / Return Inventory (RI)', 'ไม่มีสินค้านี้เหลือในระบบนะครับ');
            addModal('notEnoughProductInStock', 'ใบคืนสินค้า / Return Inventory (RI)', 'จำนวนที่จะคืนเยอะกว่าที่เหลือในคลังครับ');
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

        $scope.riItems = [];
        $scope.selectedSupplier='';
        $scope.selectedProductLine='';
        $scope.selectedProductType='';
        $scope.suppliers = <?php echo $this->suppliers; ?>;
        $scope.allProducts = <?php echo $this->Riproducts; ?>;
       
        
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
                $('#dropdownProductLine').prop('disabled', true);
                $('#dropdownProductType').prop('disabled', true);
                $('#buttonConfirmDetail').prop('disabled', true);
                
                if ($scope.selectedProductType === 'Stock') {
                    $scope.showIfStock = true;
                    $scope.products = $scope.allProducts.filter(product=>product.supplier_no == JSON.parse($scope.selectedSupplier).supplier_no)
                    console.log($scope.selectedSupplier.supplier_no);
                    console.log($scope.allProducts);
                    console.log($scope.products);
                } else if ($scope.selectedProductType === 'Order' || $scope.selectedProductType === 'Install') {
                    $scope.showIfOrderInstall = true;
                    $scope.sos = $scope.allSos.filter(function filter(product) {return product.supplier_no == $scope.selectedSupplier.supplier_no;});
                }
                
            }
        }

        $scope.addRIItemStock = function(product) {
            var newProduct = true;

            if($scope.selectedProductType == 'Stock' && product.stock == 0) {
                $('#noProductInStock').modal('toggle');
            } else
            {
                angular.forEach($scope.riItems, function (value, key) {
                if(value.product_no == product.product_no) {
                    newProduct = false;
                }
            });
            if(newProduct) {
                $scope.riItems.push(product);
                Object.assign($scope.riItems[$scope.riItems.length - 1], {quantity: 1});
                Object.assign($scope.riItems[$scope.riItems.length - 1], {so_no: '-'});
            }
            $scope.calculateTotalPrice();
            }
            
        }

        $scope.dropRIItemStock = function(product) {
            angular.forEach($scope.riItems, function (value, key) {
                if(value.product_no == product.product_no) {
                    $scope.riItems.splice($scope.riItems.indexOf(value), 1);
                }
            });
            $scope.calculateTotalPrice();
        }
        
        /* $scope.addPoItemOrderInstall = function(so) {
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
        } */

        $scope.calculateTotalPrice = function() {
            $scope.totalNoVat = 0;
            $scope.totalVat = 0;
            $scope.totalPrice = 0;
            angular.forEach($scope.riItems, function(value, key) {
                $scope.totalNoVat += (value.quantity * value.purchase_no_vat);
                $scope.totalVat += (value.quantity * value.purchase_vat);
                $scope.totalPrice += (value.quantity * value.purchase_price);
            });
        }
        
        $scope.edit = function() {
            $scope.isEdit = false;
            $scope.isFinishEdit = true;
            angular.forEach($scope.riItems, function(value, key) {
                $('#textboxQuantity'+value.product_no).prop('disabled', false);
            });
        }
        
        $scope.finishEdit = function() {
            $scope.isEdit = true;
            $scope.isFinishEdit = false;
            var isNotEnough = false;
            angular.forEach($scope.riItems, function(value, key) {
                if ($scope.selectedProductType == 'Stock' && parseInt($('#textboxQuantity' + value.product_no).val()) > parseInt(value.stock)) {
                    value.quantity = value.stock;
                    $('#textboxQuantity' + value.product_no).val(value.stock);
                    isNotEnough = true;
                } else {
                    value.quantity = $('#textboxQuantity' + value.product_no).val();
                }
                $('#textboxQuantity'+value.product_no).prop('disabled', true);

            });
            if(isNotEnough) $('#notEnoughProductInStock').modal('toggle');
            $scope.calculateTotalPrice();
            


        }
        
        $scope.formValidate = function() {
            if($scope.riItems.length===0) {
                $('#formValidate3').modal('toggle');
            } else if($scope.isFinishEdit) {
                $scope.finishEdit();
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ใบคืนสินค้า / Return Inventory (RI)', 'ยืนยันการออกใบคืนสินค้า', 'postRIItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postRIItems = function() {
            $('#confirmModal').modal('hide');
            $.post("return_inventory/post_ri_items", {
                post : true,
                supplierNo : $scope.selectedSupplier,
                productType : $scope.selectedProductType,
                productLine : $scope.selectedProductLine,
                totalNoVat : $scope.totalNoVat,
                totalVat : $scope.totalVat,
                totalPrice : $scope.totalPrice,
                ThaiPrice : NumToThai($scope.totalPrice),
                riItems : JSON.stringify(angular.toJson($scope.riItems))
            }, function(data) {
                addModal('successModal', 'ใบคืนสินค้า / Return Inventory (RI)', 'บันทึก ' + data + ' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });
            });
        }

    });

</script>