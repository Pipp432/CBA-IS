<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยืนยันใบส่งสินค้า / Confirm Inventory Report Delivery</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING PO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="irdNoTextbox">เลขที่ใบส่งสินค้า (IRD)</label>
                        <input type="text" class="form-control" id="irdNoTextbox" ng-model="filterIrd">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="irds.length == 0">
                        <tr>
                            <th>ไม่มี IRD ที่ยังไม่ยืนยัน</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="irds.length != 0">
                        <tr>
                            <th >เลข IRD</th>
                            <th>วันที่ IRD</th>
							<!--<th>SO : SOX (IV)</th>-->
                            <th>ราคาซื้อ</th>
							<th>ราคาขาย</th>
							<th>IRD File</th>
                        </tr>
                        <tr ng-repeat="ird in irds | unique:'ird_no' | filter:{ird_no:filterIrd}">
                            <td ng-click="addCirdItem(ird)" style="text-align: center;">{{ird.ird_no}}</td>
                            <td ng-click="addCirdIteam(ird)" style="text-align: center;">{{ird.ird_date}}</td>
                            <!--<td><ul class="my-0">
                                <li ng-repeat="ird_item in irds" ng-show="ird_item.ird_no===ird.ird_no">{{ird_item.doc_no}}</li>
                            </ul></td>-->
                            <td ng-click="addCirdIteam(ird)" style="text-align: center;">{{ird.ird_total_purchase | number:2}}</td>
                            <td ng-click="addCirdIteam(ird)" style="text-align: center;">{{ird.ird_total_sales | number:2}}</td>
							
							<td  style="text-align: center;"><a href="/acc/confirm_ird/ird_file/{{ird.ird_no}}" target="_blank">คลิกเลย!</a></td>
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
                    <h4 class="my-1">รายละเอียดการยืนยันใบส่งสินค้า</h4>
                    <table class="table table-hover my-1" ng-show="cirdItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม IRD</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="cirdItems.length != 0">
                        <tr>
                            <th colspan="2">เลข IRD</th>
                            <th>วันที่ IRD</th>
							<th>SO : SOX (IV)</th>
                            <th>ราคาซื้อ</th>
							<th>ราคาขาย</th>
                        </tr>
                        <tr ng-repeat="ird in cirdItems | orderBy:'ird_no' | unique:'ird_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCirdItem(ird)"></i></td>
                            <td>{{ird.ird_no}}</td>
                            <td style="text-align: center;">{{ird.ird_date}}</td>
							<td><ul class="my-0">
                                <li ng-repeat="ird_item in cirdItems | unique:'so_no'" ng-show="ird_item.ird_no===ird.ird_no">{{ird_item.so_no}} : {{ird_item.sox_no}} ({{ird_item.invoice_no}}) </li>
                            </ul></td>
                            <td style="text-align: center;">{{ird.ird_total_purchase | number:2}}</td>
                            <td style="text-align: center;">{{ird.ird_total_sales | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="5">ราคาซื้อรวม</th>
                            <th style="text-align: right;">{{totalPurchasePrice | number:2}}</th>
                        </tr>
						<tr>
                            <th style="text-align: right;" colspan="5">ราคาขายรวม</th>
                            <th style="text-align: right;">{{totalSalesPrice | number:2}}</th>
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ยืนยันใบส่งสินค้า</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ยืนยันใบส่งสินค้า / Confirm IRD', 'ยังไม่ได้เพิ่มเลข IRD');
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

        $scope.cirdItems = [];
        $scope.totalPurchasePrice = 0;
        $scope.totalSalesPrice = 0;
        
        $scope.irds = <?php echo $this->irds; ?>;
        
        $scope.addCirdItem = function(ird) {
            var newIrd = true;
            angular.forEach($scope.cirdItems, function (value, key) {
                if(value.ird_no == ird.ird_no) {
                    newIrd = false;
                }
            });
            if(newIrd) {
                angular.forEach($scope.irds, function (value, key) {
                    if(value.ird_no == ird.ird_no) {
                        $scope.cirdItems.push(value);
                    }
                });
            }
            $scope.calculateTotalPurchasePrice();
            $scope.calculateTotalSalesPrice();
        }
        
        $scope.dropCirdItem = function(ird) {
            var tempRemoved = [];
            angular.forEach($scope.cirdItems, function (value, key) {
                if(value.ird_no != ird.ird_no) {
                    tempRemoved.push(value);
                }
            });
            $scope.cirdItems = tempRemoved;
            $scope.calculateTotalPurchasePrice();
            $scope.calculateTotalSalesPrice();
        }
		
		$scope.calculateTotalPurchasePrice = function() {
            $scope.totalPurchasePrice = 0;
            angular.forEach($scope.cirdItems, function(value, key) {
                $scope.totalPurchasePrice += parseFloat(value.total_purchase);
            });
        }
		
		$scope.calculateTotalSalesPrice = function() {
            $scope.totalSalesPrice = 0;
            angular.forEach($scope.cirdItems, function(value, key) {
                $scope.totalSalesPrice += parseFloat(value.total_sales);
            });
        }
        
		
        
        $scope.formValidate = function() {
            if($scope.cirdItems.length == 0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันใบส่งสินค้า / Confirm IRD', 'ยืนยันใบส่งสินค้า', 'postCirdItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postCirdItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/acc/confirm_ird/post_cird_items", {
                cirdItems : JSON.stringify(angular.toJson($scope.cirdItems))
				
				
            }, function(data) {
                addModal('successModal', 'ยืนยันใบส่งสินค้า / Confirm IRD', 'ยืนยันใบส่งสินค้าสำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });
            });
        }

  	});

</script>
