<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="init()">

        <h2 class="mt-3">ยืนยันการไปรับของ / Confirm Pick Up</h2>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING SOX -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-6">
                        <label for="soxNoTextbox">เลขที่ SOX</label>
                        <input type="text" class="form-control" id="soxNoTextbox" ng-model="filterSox">
                    </div>
                    <div class="col-md-6">
                        <label for="dropdownProductType">ประเภทสินค้า</label>
                        <select class="form-control" ng-model="selectedProductType" id="dropdownProductType">
                            <option value="">เลือกประเภทสินค้า</option>
                            <option value="Stock">Stock</option>
                            <option value="Order">Order</option>
                        </select>
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="soxs.length == 0">
                        <tr>
                            <th>ไม่มี SOX ที่ยังไม่ยืนยัน</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="soxs.length != 0">
                        <tr>
                            <th>เลข SOX</th>
                            <th>วันที่</th>
                            <th>ผู้ขาย</th>
							<th>เลข IRD</th>
                            <th>รายการสินค้า</th>
                            <!-- <th>ราคาซื้อ</th> -->
                        </tr>
                        <tr ng-repeat="sox in soxs | unique:'sox_no' | filter:{sox_no:filterSox, product_type:selectedProductType}">
                            <td  ng-click="addCsItem(sox)">{{sox.sox_no}}</td>
                            <td  ng-click="addCsItem(sox)">{{sox.sox_datetime}}</td>
                            <td  ng-click="addCsItem(sox)">{{sox.employee_id}} {{sox.employee_nickname_thai}}</td>
							<td  ng-click="addCsItem(sox)">{{sox.ird_no}}</td>
                            <td  ng-click="addCsItem(sox)"><ul class="my-0">
                                <li ng-repeat="sox_item in soxs" ng-show="sox_item.sox_no == sox.sox_no">{{sox_item.product_name}} (x{{sox_item.quantity}})</li>
                            </ul></td>
                            <!-- <td style="text-align: right;"  ng-click="addCsItem(sox)">{{sox.sox_total_sales_price | number:2}}</td> -->

                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING CS ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดการจัดส่ง </h4>
                    <table class="table table-hover my-1" ng-show="csItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม SOX</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="csItems.length != 0">
                        <tr>
                            <th colspan="2">เลข SOX</th>
                            <th>วันที่</th>
							<th>เลข IRD</th>
                            <th>ประเภทสินค้า</th>
                            <th>รายการสินค้า</th>
                            <!-- <th>ราคาซื้อ</th> -->
                            
                        </tr>
                        <tr ng-repeat="product in csItems | orderBy:'sox_no' | unique:'sox_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropCsItem(product)"></i></td>
                            <td>{{product.sox_no}}</td>
                            <td>{{product.sox_datetime}}</td>
							<td>{{product.ird_no}}</td>
                            <td>{{product.product_type}}</td>
                            <td><ul class="my-0">
                                <li ng-repeat="sox_item in csItems" ng-show="sox_item.sox_no == product.sox_no">{{sox_item.product_name}} (x{{sox_item.quantity}})</li>
                            </ul></td>
                            <!-- <td style="text-align: right;">{{product.sox_total_sales_price | number:2}}</td>
                        </tr>
                         <tr>
                            <th style="text-align: right;" colspan="6">ราคารวม</th>
                            <th style="text-align: right;">{{totalPrice | number:2}}</th> -->
                        </tr>
                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ยืนยันการจัดส่ง</button>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <div  style="position: fixed; right: 0; bottom: 0; margin-bottom: 1%; margin-right: 7%">
        <button class="btn btn-light" ng-click="scrollToTop()">เลื่อนขึ้น</button> &nbsp; &nbsp; 
		<button class="btn btn-light" ng-click="scrollToBottom()">เลื่อนลง</button>
		</div>
        <script>
            addModal('formValidate1', 'ยืนยันการจัดส่ง / Confirm Shipping (CS)', 'ยังไม่ได้เพิ่มเลข SOX');
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
        $scope.filterSox = '';
        $scope.selectedProductType = '';
        
        $scope.soxs = <?php echo $this->soxs; ?>;
        $scope.scrollToTop = function() {
            
            window.scrollTo({ top: 0});
            
        }
		$scope.scrollToBottom = function() {
            
            window.scrollTo({ left: 0, top: document.body.scrollHeight});
            
        }
        $scope.init = function() {
            
            var barcode = [];
            var soxList = [];
            
            angular.forEach($scope.soxs, function (value, key) {
                if(!soxList.includes(value.sox_no)) {
                    soxList.push(value.sox_no);
                    barcode.push(value.tracking_number);
                }
            });
            
            console.log(barcode);
            
            var settings = {
                "url": "https://trackapi.thailandpost.co.th/post/api/v1/track",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Authorization": "Token eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJzZWN1cmUtYXBpIiwiYXVkIjoic2VjdXJlLWFwcCIsInN1YiI6IkF1dGhvcml6YXRpb24iLCJleHAiOjE1OTU1NjQ4MjcsInJvbCI6WyJST0xFX1VTRVIiXSwiZCpzaWciOnsicCI6InpXNzB4IiwicyI6bnVsbCwidSI6IjdiNmE1ZmM1NzU2YzMzZTE1NzNkZDgxNGQzZTU3MTIyIiwiZiI6InhzeiM5In19.mczKyA5vx_RQeEmIgOG0R9Fdn-fy5dmcqPLGf8V3ug4-Y28GW-VCZB2dd2PlOA9IHY8c3_q5eDSEJ8O3UB4rBQ",
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({"status":"all", "language":"TH", "barcode":barcode})
            };
            
            var trackingNumberList = [];
            var trackingListFromThaiPost = [];
                
            // $.ajax(settings).done(function (response) {
            //     trackingListFromThaiPost = response.items;
            // });
            
            for (i = 0; i < trackingListFromThaiPost.length; i++) {
                trackingNumberList.push({"tracking_number":trackingListFromThaiPost[i], "status":trackingListFromThaiPost[i].status_description})
            }
            
            angular.forEach($scope.soxs, function (value, key) {
                angular.forEach(trackingNumberList, function (value2, key2) {
                    if(value2.tracking_number == value.tracking_number) {
                        Object.assign(value, {status: value2.status});
                    }
                });
            });
            
        }
            
        $scope.addCsItem = function(sox) {
            var newSox = true;
            angular.forEach($scope.csItems, function (value, key) {
                if(value.sox_no == sox.sox_no) {
                    newSox = false;
                }
            });
            if(newSox) {
                angular.forEach($scope.soxs, function (value, key) {
                    if(value.sox_no == sox.sox_no) {
                        $scope.csItems.push(value);
                    }
                });
            }
            $scope.calculateTotalPrice();
        }
        
        $scope.dropCsItem = function(product) {
            var tempRemoved = [];
            angular.forEach($scope.csItems, function (value, key) {
                if(value.sox_no != product.sox_no) {
                   tempRemoved.push(value);
                }
            });
            $scope.csItems = tempRemoved;
            $scope.calculateTotalPrice();
        }
         
        $scope.calculateTotalPrice = function() {
            $scope.totalPrice = 0;
            var soxList = [];
            angular.forEach($scope.csItems, function(value, key) {
                if(!soxList.includes(value.sox_no)) {
                    $scope.totalPrice += parseFloat(value.sox_total_sales_price);
                    soxList.push(value.sox_no);
                }
            });
        }
        
        $scope.formValidate = function() {
            if($scope.csItems.length == 0) {
                $('#formValidate1').modal('toggle');
            } else {
                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันการจัดส่ง / Confirm Shipping (CS)', 'ยืนยันการจัดส่ง', 'postCsItems()');
                $('body').append($compile(confirmModal)($scope));
                $('#confirmModal').modal('toggle');
            }
        }
        
        $scope.postCsItems = function() {
            $('#confirmModal').modal('hide');
            $.post("/scm/confirm_shipping/post_cs_items", {
                csItems : JSON.stringify(angular.toJson($scope.csItems))
            }, function(data) {
                addModal('successModal', 'ยืนยันการจัดส่ง / Confirm Shipping (CS)', 'ยืนยันการจัดส่งสำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                    window.location.assign('/');
                });
            });
         }

  	});

</script>