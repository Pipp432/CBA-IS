<!DOCTYPE html>

<html>



<body>



    <div class="container mt-3" ng-controller="moduleAppController">



        <h2 class="mt-3">Calculate Transportation Price</h2>



        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- SELLER DETAIL & CUSTOMER DETAIL & SELECTING SUPPLIER AND PRODUCT TYPE -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">

            <div class="card-body">

                

                <div class="row mx-0">

                    <div class="col-md-6">

                        <label for="dropdownSupplier">Supplier</label>

                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">

                            <option value="">เลือก Supplier</option>

                            <option ng-repeat="supplier in allProducts | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier}}">

                                {{supplier.supplier_no}} : {{supplier.supplier_name}}

                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label for="dropdownProductType">ประเภทสินค้า</label>

                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">

                            <option value="">เลือกประเภทสินค้า</option>

                            <option value="Stock">Stock</option>

                            <option value="Order">Order</option>


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

                        <tr ng-repeat="product in products | filter:{supplier_no:selectedSupplierNo, category_name:selectedCategory, product_type:selectedProductType} | filter:filterProduct" ng-click="addSoItem(product)">

                            <td>{{product.product_no}}</td>

                            <td>

                                {{product.product_name}}

                                <span class="badge badge-pill badge-danger" ng-show="product.stock == 0 && (selectedProductType == 'Stock' || product.product_line == 'X')">สินค้าหมด</span>

                                <span class="badge badge-pill badge-warning" ng-show="product.stock < 10 && product.stock > 0 && (selectedProductType == 'Stock' || product.product_line == 'X')">เหลือ {{product.stock}} {{product.unit}}</span>

                                <span class="badge badge-pill badge-success" ng-show="product.stock >= 10 && (selectedProductType == 'Stock' || product.product_line == 'X')">เหลือ {{product.stock}} {{product.unit}}</span>

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

                    <h4 class="my-1">รายละเอียดใบสั่งขาย</h4>

                    <table class="table table-hover my-1" ng-show="soItems.length == 0">

                        <tr>

                            <th>ยังไม่ได้เพิ่มสินค้า</th>

                        </tr>

                    </table>

                    <table class="table table-hover my-1" ng-show="soItems.length != 0">

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

                        <tr ng-repeat="product in soItems | orderBy:'product_no'">

                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropSoItem(product)"></i></td>

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
						
						<tr ng-show="selectedProductType == 'Install'">
							
							<th style="text-align: right;" colspan="6">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="payment" name="payment" value="0" ng-click="payment_check()">
									<label class="custom-control-label" for="payment">ชำระเงินหลังรับสินค้า</label>
								</div>
							</th>
							<!--<div class="custom-control custom-checkbox mt-2">
								<input type="checkbox" class="custom-control-input" id="fin_form" name="fin_form" value="0" ng-click="fin_form_check()">
								<label class="custom-control-label" for="fin_form">กรอกฟอร์มแล้ว</label>
							</div>-->
						</tr>
						
                        <tr>

                            <th style="text-align: right;" colspan="5">ราคารวม</th>

                            <th style="text-align: right;">{{totalPrice | number:2}}</th>

                        </tr>

                        <tr>

                            <th style="text-align: right;" colspan="5">point</th>

                            <th style="text-align: right;">{{totalPoint | number:2}}</th>

                        </tr>

                      <tr ng-show="selectedProductType != 'Install'">
							
                            <th style="text-align: right;"  colspan="6">
								<button type="button" class="btn btn-default btn-block" style="text-align:center;" ng-click="showTransport()">คำนวณค่าจัดส่ง</button>
								<div class="px-0" id="chooseTranport"></div>
							</th>


                        </tr>

                    </table>


                </div>

            </div>

        </div>

        

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- FORM VALIDATION -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <script>

            addModal('sellerNotFoundAlert', 'ใบสั่งขาย / Sales Order (SO)', 'ไม่เจอรหัสพนักงานนี้');

            addModal('customerNotFoundAlert', 'ใบสั่งขาย / Sales Order (SO)', 'ไม่เจอลูกค้าคนนี้');

            addModal('formValidate1', 'ใบสั่งขาย / Sales Order (SO)', 'เพิ่มรหัสผู้ขายก่อนนะครับผม');

            addModal('formValidate2', 'ใบสั่งขาย / Sales Order (SO)', 'เพิ่มลูกค้าก่อนนะครับผม');

            addModal('formValidate3', 'ใบสั่งขาย / Sales Order (SO)', 'เลือก Supplier ก่อนนะครับผม');

            addModal('formValidate4', 'ใบสั่งขาย / Sales Order (SO)', 'เลือกประเภทสินค้าก่อนนะครับผม');

            addModal('formValidate5', 'ใบสั่งขาย / Sales Order (SO)', 'ยังไม่ได้เพิ่มสินค้าเข้าใบสั่งขาย');

            addModal('noProductInStock', 'ใบสั่งขาย / Sales Order (SO)', 'สินค้าหมด ไปสั่งเพิ่มก่อนครับผม');

            addModal('notEnoughProductInStock', 'ใบสั่งขาย / Sales Order (SO)', 'สินค้าที่สั่งจำนวนเยอะกว่าที่เหลือในคลัง ไปสั่งเพิ่มก่อนครับผม');

            addModal('depositProduct', 'ใบสั่งขาย / Sales Order (SO)', 'สินค้านี้เป็นสินค้ามีมัดจำ');

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
		



        $scope.soItems = [];

        
		$scope.t_price=0;
        $scope.seller_employee_name='';

        $scope.customer_name='';

        $scope.selectedSupplier='';

        $scope.selectedProductType='';

        $scope.showAfterSubmit = false;

        

        $scope.isEdit = true;

        $scope.isFinishEdit = false;

        $scope.discount = 0;

        $scope.vatType = 0;

        $scope.hasDeposit = false;

        

        $scope.allProducts = <?php echo $this->products; ?>;



        

        $scope.confirmDetail = function() {

            if($scope.selectedSupplier === '') {

                $('#formValidate3').modal('toggle');

            }  else if($scope.selectedProductType === '') {

                $('#formValidate4').modal('toggle');

            }  else {

                $scope.showAfterSubmit = true;

                $scope.vatType = JSON.parse($scope.selectedSupplier).vat_type;

                $scope.selectedSupplierNo = JSON.parse($scope.selectedSupplier).supplier_no;
				$scope.products = $scope.allProducts.filter(function filterSupplier(product){return product.supplier_no == $scope.selectedSupplierNo;});

            }

        }



        $scope.addSoItem = function(product) {

            

            var newProduct = true;

            

            if ($scope.hasDeposit) {

                $scope.hasDeposit = false;

                $scope.soItems = [];

            }

            

            if (product.sd_sales_price!=0) {

                $scope.hasDeposit = true;

                $scope.soItems = [];

                $('#depositProduct').modal('toggle');

            }

            

            if($scope.selectedProductType == 'Stock' && product.stock == -999999) {

                $('#noProductInStock').modal('toggle');

            } else {

                angular.forEach($scope.soItems, function (value, key) {

                    if(value.product_no == product.product_no) {

                        newProduct = false;

                    }

                });

                if(newProduct) {

                    var productTemp = JSON.parse(JSON.stringify(product));

                    Object.assign(productTemp, {quantity : 1});

                    Object.assign(productTemp, {deposit : false});

                    $scope.soItems.push(productTemp);

                    if($scope.hasDeposit) {

                        productTemp.sales_no_vat = product.sales_no_vat - product.sd_sales_no_vat;

                        productTemp.sales_vat = product.sales_vat - product.sd_sales_vat;

                        productTemp.sales_price = product.sales_price - product.sd_sales_price;

                        $scope.soItems.push({

                            product_no : product.product_no + '-มัดจำ',

                            product_name : 'ค่ามัดจำ',

                            sales_no_vat : product.sd_sales_no_vat,

                            sales_vat : product.sd_sales_vat,

                            sales_price : product.sd_sales_price,

                            quantity : 1,

                            point : 0,

                            commission : 0,

                            deposit : true

                        });

                        productTemp = null;

                    }

                }

                $scope.calculateTotalPrice();

            }

            

        }

        

        $scope.dropSoItem = function(product) {

            var temp = [];

            angular.forEach($scope.soItems, function (value, key) {

                if(!(value.product_no.substring(0,15) === product.product_no.substring(0,15))) {

                    temp.push(value);

                }

            });

            $scope.soItems = temp;

            $scope.calculateTotalPrice();

        }

        

        $scope.edit = function() {

            $scope.isEdit = false;

            $scope.isFinishEdit = true;

            angular.forEach($scope.soItems, function(value, key) {

                $('#textboxQuantity' + value.product_no.substring(0,15)).prop('disabled', false);

            });

        }

        

        $scope.finishEdit = function() {

            $scope.isEdit = true;

            $scope.isFinishEdit = false;

            var isNotEnough = false;

            angular.forEach($scope.soItems, function(value, key) {

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

            $scope.totalPoint = 0;

            $scope.totalCommission = 0;

            $scope.totalWeight = 0;

            angular.forEach($scope.soItems, function(value, key) {

                $scope.totalNoVat += (value.quantity * value.sales_no_vat);

                $scope.totalVat += (value.quantity * value.sales_vat);

                $scope.totalPrice += (value.quantity * value.sales_price);

                $scope.totalPoint += (value.quantity * value.point);

                $scope.totalCommission += (value.quantity * value.commission);

                $scope.totalWeight += (value.quantity * value.weight);

            });

            // $scope.transportPrice = $scope.totalWeight;

            console.log($scope.totalWeight);

        }


        

         $scope.calculateTransportPrice = function() {
			
			var temp = [];
            angular.forEach($scope.soItems, function (value, key) {
				temp.push({
					product_no : value.product_no,
					height : value.height,
					length : value.length,
					width : value.width,
					quantity : value.quantity,
				});
            });
			$.post('/mkt/calculateTransportation', {
				post : true,
				sos : JSON.stringify(temp)
			}, function(response) {
				$scope.t_price = response.data;
				}
			);          
		}
		 
		$scope.showTransport = function (){
        
			$('#chooseTranport').html('');
            $.post("/mkt/calculateTransportation", {
                post : true,
                sos : JSON.stringify($scope.soItems),
				weight : $scope.totalWeight
            }, function(data) {
                $('#chooseTranport').append(data);
                $('body, html').animate({ scrollTop: $("#chooseTranport").offset().top }, 600);
            });
            
        }
        
    


    });



</script>