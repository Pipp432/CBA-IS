<!DOCTYPE html>
<html>
<body>
    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Statement</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-4">
                        <label for="dropdownProductType">ประเภทรายงาน</label>
                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">
                            <option value="">-</option>
                            <option value="Stm1">งบการเงิน โครงการ 1</option>
                            <option value="Stm2">งบการเงิน โครงการ 2</option>
                            <option value="Stm3">งบการเงิน โครงการ 3</option>
                            <option value="Stmspe1">งบการเงินโครงการพิเศษ โครงการ 1</option>
                            <option value="Stmspe2">งบการเงินโครงการพิเศษ โครงการ 2</option>
                            <option value="Stmprofit1">งบกำไรขาดทุน โครงการ 1</option>
                            <option value="Stmprofit2">งบกำไรขาดทุน โครงการ 2</option>
                            <option value="Stmprofit3">งบกำไรขาดทุน โครงการ 3</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="dropdownProductType">จนถึงวันที่</label>
                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">
                            <option value="">วันที่</option>
                            <option value="Apr">30/04/2022</option>
                            <option value="May">31/05/2022</option>
                            <option value="Jun">30/06/2022</option>
                            <option value="Jul">31/07/2022</option>
                            <option value="Aug">30/08/2022</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="buttonConfirmDetail" style="color:white;">.</label>
                        <button type="button" class="btn btn-default btn-block" id="buttonConfirmDetail" ng-click="confirmDetail()">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>
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


        // $scope.confirmDetail = function() {

        //     if($scope.selectedSupplier === '') {

        //         $('#formValidate3').modal('toggle');

        //     }  else if($scope.selectedProductType === '') {

        //         $('#formValidate4').modal('toggle');

        //     }  else {

        //         $scope.showAfterSubmit = true;

        //         $scope.vatType = JSON.parse($scope.selectedSupplier).vat_type;

        //         $scope.selectedSupplierNo = JSON.parse($scope.selectedSupplier).supplier_no;
		// 		$scope.products = $scope.allProducts.filter(function filterSupplier(product){return product.supplier_no == $scope.selectedSupplierNo;});

        //     }

        // }

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