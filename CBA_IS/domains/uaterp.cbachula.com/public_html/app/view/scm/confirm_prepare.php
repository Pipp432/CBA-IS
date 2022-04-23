<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController" ng-init="onload()">

        <h2 class="mt-3">ยืนยันการจัดเตรียม / Confirm Preparation</h2>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CI/RR -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="soxx.length == 0" style="text-align: center;">
                        <tr>
                            <th>ไม่มี SOX ที่ต้องจัดส่ง</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="soxx.length != 0" style="text-align: center;">
                        <tr>
							<th>วันที่ SOX</th>
                            <th>เลข SOX</th>
							<th>ประเภท SOX</th>
							<th>special</th>
                            <th><i class="fa fa-check" aria-hidden="true"></i></th>							
                            <th>เลข IV</th>
                            <th>ขนาดกล่อง</th>
							<th>ขนส่ง</th>
							<th>ราคารวม</th>
							<th>รายละเอียด</th>
                        </tr>
                        <tr ng-repeat="sr in soxx | unique: 'sox_no'" ng-click="addIrdItem(sr)">
                            <td >{{sr.sox_datetime}}</td>
                            <td>{{sr.sox_no}}</td>
							<td>{{sr.sox_type}}</td>
							<td>
								<i class="fa fa-exclamation-circle" aria-hidden="true" ng-show="sr.special != null" style="color: red"></i>
							</td>
                            <td><i class="fa fa-check" aria-hidden="true" ng-show="sr.fin_form==1"></i></td>							
							<td>{{sr.invoice_no}}</td>
							<td >{{sr.box_size}}</td>
							<td >{{sr.note}}</td>
							<td style="text-align: center;">{{sr.total_sales_price | number:2}}</td>
							<td>
							  <div class="card card-body">
								  <table>
                                      <tr>
                                          <th>เลข SO</th>
                                          <th>เลขสินค้า</th>
                                          <th>ชื่อสินค้า</th>
                                          <th>จำนวน</th>

                                    </tr>
									  <tr ng-repeat="sr_item in soxx" ng-show="sr_item.sox_no===sr.sox_no">
										  <td>{{sr_item.so_no}}</td>
										  <td>{{sr_item.product_no}}</td>
										  <td>{{sr_item.product_name}}</td> 
										  <td>(x{{sr_item.quantity}})</td>
									  </tr>
								  </table>
							  </div>
                            </td>
						</tr>				
                    </table>
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SHOWING IVRC ITEMS -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดยืนยันการจัดเตรียม</h4>
                    <table class="table table-hover my-1" ng-show="irdItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่มเลข SOX</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="irdItems.length != 0">
                        <tr>
                            <th colspan="2">วันที่ SOX</th>
                            <th>เลข SOX</th>
							<th>ประเภท SOX</th>
							<th>special</th>
                            <th><i class="fa fa-check" aria-hidden="true"></i></th>							
                            <th>เลข IV</th>
                            <th>ขนาดกล่อง</th>
							<th>ราคารวม</th>
							
                        </tr>
                        <tr ng-repeat="irdItem in irdItems | unique: 'sox_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropIrdItem(irdItem)"></i></td>
							<td style="text-align: center;">{{irdItem.sox_datetime}}</td>
                            <td style="text-align: center;">{{irdItem.sox_no}}</td>
                            <td style="text-align: center;">{{irdItem.sox_type}}</td>
							<td style="text-align: center;">
								<i class="fa fa-exclamation-circle" aria-hidden="true" ng-show="irdItem.special != null" style="color: red"></i>
							</td>
							<td style="text-align: center"><i ng-show="irdItem.fin_form==1" class="bi bi-check2-circle"></i></td>
							<td style="text-align: center;">{{irdItem.invoice_no}}</td>
							<td style="text-align: center;">{{irdItem.box_size}}</td>
							<td style="text-align: center;">{{irdItem.total_sales_price | number:2}}</td>
                        </tr>
                        <tr>
                            <th style="text-align: right;" colspan="8">รวม</th>
                            <th style="text-align: left;">{{count}} &nbsp; &nbsp;&nbsp; &nbsp; กล่อง</th>
                    </table>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="postIrdItems()">ยืนยันการจัดเตรียม</button>
                </div>
                
            </div>
        </div>

        <div  style="position: fixed; right: 0; bottom: 0; margin-bottom: 1%; margin-right: 7%">
        <button class="btn btn-light" ng-click="scrollToTop()">เลื่อนขึ้น</button> &nbsp; &nbsp; 
		<button class="btn btn-light" ng-click="scrollToBottom()">เลื่อนลง</button>
		</div>
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <script>
            addModal('formValidate1', 'ใบนำสินค้าออกจากคลัง / Inventory Report Delivery (IRD)', 'ยังไม่ได้เพิ่ม SOX');
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
        
        $scope.selectedCompany = '';
        $scope.irdItems = [];

        $scope.scrollToTop = function() {
            
            window.scrollTo({ top: 0});
            
        }
		$scope.scrollToBottom = function() {
            
            window.scrollTo({ left: 0, top: document.body.scrollHeight});
            
        }
        $scope.current_no='';
        //ไม่ต้องแก้
		$scope.addIrdItem = function(sr) {
            var newSox = true;
            angular.forEach($scope.irdItems, function (value, key) {
                //console.log(value,key);
                if(value.sox_no == sr.sox_no) {
					newSox = false;
                }
            });
            if(newSox) {
                angular.forEach($scope.soxx, function (value, key) {
                    if(value.sox_no == sr.sox_no) {
                        $scope.irdItems.push(value);
                    }
                    //console.log($scope.irdItems);
                });
            }
            $scope.current_no=sr.sox_no;
			$scope.calculateTotalBox();
        }
		
		$scope.onload=function(){
            $http.get('/scm/confirm_prepare/get_sox').then(function(response){$scope.soxx = response.data; $scope.isLoad = false;console.log(response.data);});
        }

        $scope.dropIrdItem = function(sr) {
            var tempIrdItem = [];
            angular.forEach($scope.irdItems, function (value, key) {
                if(value.sox_no != sr.sox_no) {
                    tempIrdItem.push(value);
                }
            });
            $scope.irdItems = tempIrdItem;
            $scope.calculateTotalBox();
        }
        
        $scope.calculateTotalBox = function() {
            $scope.count = 0
			$scope.sox = []
            angular.forEach($scope.irdItems, function (value, key) {
                if(!($scope.sox.includes(value.sox_no))){
					$scope.sox.push(value.sox_no)					
				}
				
			$scope.count = $scope.sox.length
            });
        }
        //แก้ส่งsoxที่กดยืนยันไปให้ปุ่มird
		$scope.postIrdItems = function() {
            if($scope.irdItems.length === 0) {
                $('#formValidate1').modal('toggle');
            } else{
				$('#confirmModal').modal('hide');
            $.post("/scm/confirm_prepare/update_ird_items", {
                post : true,
                irdItems : JSON.stringify(angular.toJson($scope.irdItems))
            }, function(data) { 
				console.log(data);
                addModal('successModal', 'ยืนยันการตรวจสอบ / Confirm Preparation', 'บันทึก ' + data + ' สำเร็จ');
                $('#successModal').modal('toggle');
                $('#successModal').on('hide.bs.modal', function (e) {
                window.location.assign('/scm/confirm_prepare');
                });
                });   
		    }
        }	
        

  	});

</script>