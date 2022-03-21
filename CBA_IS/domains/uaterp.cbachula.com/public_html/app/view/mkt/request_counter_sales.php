<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ขอออก Counter Sales / Request Counter Sales (RCS)</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELLER DETAIL & CUSTOMER DETAIL & SELECTING SUPPLIER AND PRODUCT TYPE -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-3">
                        <label for="datetime-input">วันที่ออก CS</label>
                        <input class="form-control" type="date" id="csDate" ng-model="csDate">
                    </div>
                    <div class="col-md-3">
                        <label for="dropdownLocation">สถานที่</label>
                        <select class="form-control" ng-model="selectedLocation" id="dropdownLocation">
                            <option value="">เลือกสถานที่</option>
                            <option ng-repeat="location in locations" value="{{location.location_no}}">
                                {{location.location_no}} : {{location.location_name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dropdownSupplier">Supplier</label>
                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">
                            <option value="">เลือก Supplier</option>
                            <option ng-repeat="supplier in allProducts | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier.supplier_no}}">
                                {{supplier.supplier_no}} : {{supplier.supplier_name}}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="buttonConfirmDetail" style="color:white;">.</label>
                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="confirmDetail()">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING PRODUCT -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;" ng-show="showAfterSubmit">
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
                        <label for="productTextbox">Product Number / Product Name</label>
                        <input type="text" class="form-control" id="productTextbox" ng-model="filterProduct">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1">
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>หมวดหมู่</th>
                            <th>ราคา</th>
                        </tr>
                        <tr ng-repeat="product in products | filter:{supplier_no:selectedSupplierNo, category_name:selectedCategory, product_type:'Stock'} | filter:filterProduct" ng-click="addCsItem(product)">
                            <td>{{product.product_no}}</td>
                            <td>
                                {{product.product_name}}
                                <span class="badge badge-pill badge-danger" ng-show="product.stock == 0 && selectedProductType == 'Stock'">สินค้าหมด</span>
                                <span class="badge badge-pill badge-warning" ng-show="product.stock < 10 && product.stock > 0 && selectedProductType == 'Stock'">เหลือ {{product.stock}} {{product.unit}}</span>
                                <span class="badge badge-pill badge-success" ng-show="product.stock >= 10 && selectedProductType == 'Stock'">เหลือ {{product.stock}} {{product.unit}}</span>
                            </td>
                            <td>{{product.category_name}}</td>
                            <td style="text-align:right;">{{product.sales_price | number:2}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING SO ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="showAfterSubmit">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียด Counter Sales</h4>
                    <table class="table table-hover my-1" ng-show="csItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มสินค้า</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="csItems.length != 0">
                        <tr>
                            <th colspan="2">รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>
                                จำนวน
                                <i class="fa fa-pencil" aria-hidden="true" ng-show="isEdit" ng-click="edit()"></i>
                                <i class="fa fa-check" aria-hidden="true" ng-show="isFinishEdit" ng-click="finishEdit()"></i>
                            </th>
                            <th>ราคารวม</th>
                        </tr>
                        <tr ng-repeat="product in csItems | orderBy:'product_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCsItem(product)"></i></td>
                            <td>{{product.product_no}}</td>
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
                            <th style="text-align: right;" colspan="5">ราคารวม</th>
                            <th style="text-align: right;">{{totalPrice | number:2}}</th>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">บันทึก CS</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate0', 'ขอออก Counter Sales / Request Counter Sales (RCS)', 'ยังไม่ได้เลือกสถานที่ไปออก Counter Sales');
            addModal('formValidate1', 'ขอออก Counter Sales / Request Counter Sales (RCS)', 'เลือก Supplier ก่อนนะครับผม');
            addModal('formValidate2', 'ขอออก Counter Sales / Request Counter Sales (RCS)', 'ยังไม่ได้เพิ่มสินค้าเข้าใบสั่งขาย');
            addModal('noProductInStock', 'ขอออก Counter Sales / Request Counter Sales (RCS)', 'สินค้าหมด ไปสั่งเพิ่มก่อนครับผม');
            addModal('notEnoughProductInStock', 'ขอออก Counter Sales / Request Counter Sales (RCS)', 'สินค้าที่สั่งจำนวนเยอะกว่าที่เหลือในคลัง ไปสั่งเพิ่มก่อนครับผม');
            addModal('depositProduct', 'ขอออก Counter Sales / Request Counter Sales (RCS)', 'สินค้านี้เป็นสินค้ามีมัดจำ');
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

        $scope.csItems = [];
        
        $scope.selectedLocation = '';
        $scope.selectedSupplier = '';
        $scope.selectedProductType = 'Stock';
        $scope.csDateStr = '';
        $scope.showAfterSubmit = false;
        
        $scope.isEdit = true;
        $scope.isFinishEdit = false;
        
        $scope.locations = <?php echo $this->locations; ?>;
        $scope.allProducts = <?php echo $this->products; ?>;
        
        $scope.confirmDetail = function() {
            if($scope.selectedLocation === '') {
                $('#formValidate0').modal('toggle');
            } else if($scope.selectedSupplier === '') {
                $('#formValidate1').modal('toggle');
            } else {
                
                $('#csDate').prop('disabled', true);
                $('#dropdownLocation').prop('disabled', true);
                $('#dropdownSupplier').prop('disabled', true);
                $('#dropdownProductType').prop('disabled', true);
                $('#buttonConfirmDetail').prop('disabled', true);
                $scope.showAfterSubmit = true;
                
                $scope.csDateStr = $scope.csDate.getFullYear() + '-' + 
                                (($scope.csDate.getMonth()+1) < 10 ? '0' : '') + ($scope.csDate.getMonth()+1) + '-' + 
                                ($scope.csDate.getDate() < 10 ? '0' : '') + $scope.csDate.getDate();
                                    
                $scope.products = $scope.allProducts.filter(function filterSupplier(product){return product.supplier_no == $scope.selectedSupplier;});
                
            }
        }

        $scope.addCsItem = function(product) {
            
            var newProduct = true;
            
            if($scope.selectedProductType == 'Stock' && product.stock == 0) {
                $('#noProductInStock').modal('toggle');
            } else {
                angular.forEach($scope.csItems, function (value, key) {
                    if(value.product_no == product.product_no) {
                        newProduct = false;
                    }
                });
                if(newProduct) {
                    var productTemp = JSON.parse(JSON.stringify(product));
                    Object.assign(productTemp, {quantity : 1});
                    $scope.csItems.push(productTemp);
                }
                $scope.calculateTotalPrice();
            }
            
        }
        
        $scope.dropCsItem = function(product) {
            var temp = [];
            angular.forEach($scope.csItems, function (value, key) {
                if(!(value.product_no.substring(0,15) === product.product_no.substring(0,15))) {
                    temp.push(value);
                }
            });
            $scope.csItems = temp;
            $scope.calculateTotalPrice();
        }
        
        $scope.edit = function() {
            $scope.isEdit = false;
            $scope.isFinishEdit = true;
            angular.forEach($scope.csItems, function(value, key) {
                $('#textboxQuantity' + value.product_no.substring(0,15)).prop('disabled', false);
            });
        }
        
        $scope.finishEdit = function() {
            $scope.isEdit = true;
            $scope.isFinishEdit = false;
            var isNotEnough = false;
            angular.forEach($scope.csItems, function(value, key) {
                if ($scope.hasDeposit) {
                    value.quantity = $('#textboxQuantity' + value.product_no.substring(0,15)).val();
                } else if ($scope.selectedProductType == 'Stock' && parseInt($('#textboxQuantity' + value.product_no).val()) > parseInt(value.stock)) {
                    value.quantity = value.stock;
                    $('#textboxQuantity' + value.product_no).val(value.stock);
                    isNotEnough = true;
                } else {
                    value.quantity = $('#textboxQuantity' + value.product_no).val();
                }
                $('#textboxQuantity' + value.product_no).prop('disabled', true);
            });
            if(isNotEnough) $('#notEnoughProductInStock').modal('toggle');
            $scope.calculateTotalPrice();
        }

        $scope.calculateTotalPrice = function() {
            $scope.totalNoVat = 0;
            $scope.totalVat = 0;
            $scope.totalPrice = 0;
            angular.forEach($scope.csItems, function(value, key) {
                $scope.totalVat += (value.quantity * value.sales_vat);
                $scope.totalPrice += (value.quantity * value.sales_price);
                // $scope.totalPoint += (value.quantity * value.point);
                // $scope.totalCommission += (value.quantity * value.commission);
                // $scope.totalWeight += (value.quantity * value.weight);
            });
            $scope.totalNoVat = ($scope.totalVat == 0) ? $scope.totalNoVat = $scope.totalPrice : $scope.totalNoVat = $scope.totalPrice / 1.07;
        }
        
        $scope.formValidate = function() {
            if($scope.csItems.length===0) {
                $('#formValidate2').modal('toggle');
            } else if($scope.isFinishEdit) {
                $scope.finishEdit();
            } else {
                console.log($scope.csItems);
                var confirmModal = addConfirmModal('confirmModal', 'ขอออก Counter Sales / Request Counter Sales (RCS)', 'ยืนยันการขอออก Counter Sales', 'postCsItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }

        $scope.postCsItems = function() {
            $('#confirmModal').modal('hide');
            $.post("request_counter_sales/post_cs_items", {
                post : true,
                location_no : $scope.selectedLocation,
                cs_date : $scope.csDateStr,
                productType : $scope.selectedProductType,
                csItems : JSON.stringify(angular.toJson($scope.csItems))
            }, function(data) {
                addModal('successModal', 'ขอออก Counter Sales / Request Counter Sales (RCS)', data);
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });
            });
        }

    });

</script>