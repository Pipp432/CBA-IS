<!DOCTYPE html>

<html>



<body>



    <div class="container mt-3" ng-controller="moduleAppController">

		

		<div class="row mx-0">
			<div class="col-md-4">
				<h2 class="mt-4">Check Stock (IRD)  </h2> 
			</div>
		
			<div class="col-md-2" style ="align-items: center; display:flex;">
				<a type="button" class="btn btn-default btn-block" id="buttonDownLoadStockIRD" href="/scm/view_stock_ird"><i class="fa fa-download"></i> Download</a>
			</div>
		</div>



        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- SELLER DETAIL & CUSTOMER DETAIL & SELECTING SUPPLIER AND PRODUCT TYPE -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">

            <div class="card-body">

               <div class="row mx-0">

                    <div class="col-md-5">

                        <label for="dropdownSupplier">Supplier</label>

                        <select class="form-control" ng-model="selectedSupplier" id="dropdownSupplier">

                            <option value="">เลือก Supplier</option>
							<option ng-repeat="supplier in allstocks | unique:'supplier_no' | orderBy:'supplier_no'" value="{{supplier}}">

                                {{supplier.supplier_no}} : {{supplier.supplier_name}}

                            </option>

                        </select>

                    </div>
					
					<div class="col-md-5">

                        <label for="dropdownProductType">ประเภทสินค้า</label>

                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType" value = "{{type}}">

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

        <!-- SHOWING PRODUCT -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;" ng-show="showAfterSubmit">

            <div class="card-body">


                <div class="row mx-0 mt-2"  >

                    <table class="table table-hover my-1">

                        <tr>

                            <th colspan="2">รหัสสินค้า</th>

                            <th colspan="4">ชื่อสินค้า</th>
                            <th colspan="1">In</th>
                            <th colspan="1">Out</th>
							<th colspan="1">Left</th>
							<th colspan="3">RR</th>

                        </tr>

                        <tr ng-repeat="stocks in allstocks | unique:'product_no' | filter:{supplier_no:selectedSupplierNo, product_type:selectedProductType} ">

                            <td colspan="2">{{stocks.product_no}}</td>

                            <td colspan="4">{{stocks.product_name}}</td>

                            <td colspan="1">{{stocks.stock_in}}</td>
                            <td colspan="1">{{stocks.stock_out}}</td>
                            <td colspan="1">{{stocks.stock_left}}</td>
							<td ng-show = "stocks.product_type == 'Stock'" colspan="3">{{stocks.rr_no}}</td>
                            <td style="text-align:center;" colspan="3" ng-show="stocks.product_type == 'Order'">-</td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>
		</br></br>



        

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- FORM VALIDATION -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        

        <script>

            addModal('sellerNotFoundAlert', 'ใบสั่งขาย / Sales Order (SO)', 'ไม่เจอรหัสพนักงานนี้');

            addModal('customerNotFoundAlert', 'ใบสั่งขาย / Sales Order (SO)', 'ไม่เจอลูกค้าคนนี้');

            addModal('formValidate1', 'ใบสั่งขาย / Sales Order (SO)', 'เพิ่มรหัสผู้ขายก่อนนะครับผม');

            addModal('formValidate2', 'ใบสั่งขาย / Sales Order (SO)', 'เพิ่มลูกค้าก่อนนะครับผม');

            addModal('formValidate3', 'Check Stock (IRD)', 'เลือก Supplier ก่อนนะครับผม');

            addModal('formValidate4', 'Check Stock (IRD)', 'เลือกประเภทสินค้าก่อนนะครับผม');

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
		



        $scope.selectedSupplier = '';
		$scope.selectedProductType = '';
		$scope.showAfterSubmit = false;
		$scope.step = '';

        $scope.allstocks = <?php echo $this->Stocks; ?>;
		
		$scope.confirmDetail = function() {

            if($scope.selectedSupplier === '') {

                $('#formValidate3').modal('toggle');

            }  else if($scope.selectedProductType === '') {

                $('#formValidate4').modal('toggle');

            }  else {

                $scope.showAfterSubmit = true;

                $scope.selectedSupplierNo = JSON.parse($scope.selectedSupplier).supplier_no;
				$scope.stocks = $scope.allstocks.filter(function filterSupplier(stock){return stock.supplier_no == $scope.selectedSupplierNo;});

            }

        }



    


    });



</script>