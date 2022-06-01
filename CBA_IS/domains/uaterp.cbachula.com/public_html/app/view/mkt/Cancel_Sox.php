<!DOCTYPE html>

<html>



<body>



    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยกเลิก SO/SOX</h2> 

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Choose type of cancel -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-3">
                        <label for="dropdownCancelType">ประเภทการยกเลิก</label>
                        <select class="form-control" ng-model="selectedCancelType" ng-change="selecteCancelType()" id="dropdownCancelType">
                            <option value="">เลือกประเภทการยกเลิก</option>
                            <option value="SOX">ยกเลิกทั้ง SOX </option>
                            <option value="SO">ยกเลิกเฉพาะ SO</option>
                        </select>
                    </div>                        
                </div>
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Select Sox -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;" ng-show="selectedCancelType == 'SOX'">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">
                        ยกเลิกทั้ง SOX
                    </h4>
                </div>
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="textboxSOX">เลข SOX</label>
                        <input type="text" class="form-control" id="textboxSOX" ng-model="filtersox">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1" ng-show="soxs.length == 0">
                        <tr>
                            <th colspan="6">SOX Not Found</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="soxs.length != 0">
                        <tr>
                            <th>เลข SOX</th>
                            <th>employee</th>
                            <th>เลข SO ทั้งหมด</th>
                           
                        </tr>
                      
                        
                        <tr ng-repeat="sox in soxs |unique:'sox_no' | filter:{sox_no:filtersox}" ng-click="addSOX(sox)" >
                            <td style="text-align: center;">{{sox.sox_no}}</td>
                            <td style="text-align: center;">{{sox.employee_id}}</td>
                            <td ><ul class="my-0" >
                                <li ng-repeat="soxe in soxs |unique:'so_no'" ng-show="soxe.sox_no===sox.sox_no" >{{soxe.so_no}} </li>
                            </ul></td>
                          
                        </tr>
                    </table>
                    
                </div>
            </div>
        </div>
         
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Show Sox -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="selectedCancelType == 'SOX'">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดการยกเลิก SOX</h4>
                </div>
                
                <div class="row mx-0">
                    <table class="table table-hover my-1" ng-show="soxItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม SOX</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="soxItems.length != 0">
                        <tr>
                            <th colspan="2">เลข SOX</th>
                            <th>เลข SO</th>
                            <th>เลข Product</th>
                            <th>ชื่อ Product</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>

                        </tr>

                        <tr ng-repeat="item in soxItems | orderBy:'sox_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropsoxItems(item)"></i></td>
                            <td style="text-align: center;">{{item.sox_no}}</td>
                            <td style="text-align: center;">{{item.so_no}}</td>
                            <td style="text-align: center;">{{item.product_no}}</td>
                            <td style="text-align: center;">{{item.product_name}}</td>
                            <td style="text-align: center;">{{item.quantity}}</td>
                            <td style="text-align: center;">{{item.total_sales}}</td>                           
                        </tr>

                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate()">ยกเลิก SOX</button>
                </div>
            </div>
        </div>
        
       
       
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Choose SO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;" ng-show="selectedCancelType == 'SO'">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">
                        ยกเลิกเฉพาะ SO ที่ยังไม่ออก SOX
                    </h4>
                </div>
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="textboxSO">เลข SO</label>
                        <input type="text" class="form-control" id="textboxSO" ng-model="filterSO">
                    </div>
                </div>
                <hr>
                <table class="table table-hover my-1" ng-show="onlysos.length == 0">
                        <tr>
                            <th colspan="6">SO Not Found</th>
                        </tr>
                </table>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1"ng-show="onlysos.length != 0" >
                        <tr>
                            <th>เลข SO</th>
                            <th>employee</th>
                            <th>ชื่อ product ทั้งหมด</th>                           
                        </tr>
                        
                        <tr ng-repeat="onlyso in onlysos | unique:'so_no' | filter:{so_no:filterSO}"  ng-click="addSO(onlyso)" >
                            <td style="text-align: center;">{{onlyso.so_no}}</td>
                            <td style="text-align: center;">{{onlyso.employee_id}}</td>
                            <td ><ul class="my-0" >
                                <li ng-repeat="soe in onlysos" ng-show="soe.so_no===onlyso.so_no" >{{soe.product_name}}(x{{soe.quantity}}) </li>
                            </ul></td>
                        </tr>
                    </table>
                </div>
                
            </div>
        </div>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- Show Selected SO -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3 mb-3" style="border:none; border-radius:10px;" ng-show="selectedCancelType == 'SO'">
            <div class="card-body">
                <div class="row mx-0">
                    <h4 class="my-1">รายละเอียดการยกเลิก SO</h4>
                </div>
                
                <div class="row mx-0">
                    <table class="table table-hover my-1" ng-show="soItems.length == 0">
                        <tr>
                            <th>ยังไม่ได้เพิ่ม SO</th>
                        </tr>
                    </table>
                    <table class="table table-hover my-1" ng-show="soItems.length != 0">
                        <tr>
                            <th colspan="2">เลข SO</th>
                            <th>เลข Product</th>
                            <th>ชื่อ Product</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>

                        </tr>

                        <tr ng-repeat="itemso in soItems | orderBy:'so_no'">
                            <td><i class="fa fa-times-circle" aria-hidden="true" ng-click="dropsoItems(itemso)"></i></td>
                            <td style="text-align: center;">{{itemso.so_no}}</td>
                            <td style="text-align: center;">{{itemso.product_no}}</td>
                            <td style="text-align: center;">{{itemso.product_name}}</td>
                            <td style="text-align: center;">{{itemso.quantity}}</td>
                            <td style="text-align: center;">{{itemso.total_sales}}</td>                           
                        </tr>

                    </table>
                    <button type="button" class="btn btn-default btn-block my-1" ng-click="formValidate2()">ยกเลิก SO</button>
                </div>
            </div>
        </div>
        
    
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- FORM VALIDATION -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        
        <script>

  ;
        addModal('InputSOXAlert', 'Cancel SOX', 'เลือก SOX ก่อนนะครับ');
        addModal('InputSOAlert', 'Cancel SOX', 'เลือก SO ก่อนนะครับ');


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
        $scope.soxs = <?php echo $this->soxs; ?>;
        $scope.onlysos = <?php echo $this->onlysos; ?>;  
        $scope.selectedCancelType = '';     
		$scope.filtersox='';
        $scope.filterSO = '';
        $scope.soxItems = [];
        $scope.soItems = [];

        $scope.addSOX = function(sox) {
            var newSox = true;
            angular.forEach($scope.soxItems, function (value, key) {
                if(value.sox_no == sox.sox_no) {
                    newSox = false;
                }
            });
            if(newSox) {
                angular.forEach($scope.soxs, function (value, key) {
                    if(value.sox_no == sox.sox_no) {
                        $scope.soxItems.push(value);
                    }
                });
            }
            

        }

        $scope.addSO = function(onlyso) {
            var newSO = true;
            angular.forEach($scope.soItems, function (value, key) {
                if(value.so_no == onlyso.so_no) {
                    newSO = false;
                }
            });
            if(newSO) {
                angular.forEach($scope.onlysos, function (value, key) {
                    if(value.so_no == onlyso.so_no) {
                        $scope.soItems.push(value);
                    }
                });
            }
            

        }

        $scope.dropsoxItems = function(item) {
            var tempRemoved = [];
            angular.forEach($scope.soxItems, function (value, key) {
                if(value.sox_no != item.sox_no) {
                    tempRemoved.push(value);
                }
            });
            $scope.soxItems = tempRemoved;
            
        }

        $scope.dropsoItems = function(itemso) {
            var tempRemoved = [];
            angular.forEach($scope.soItems, function (value, key) {
                if(value.so_no != itemso.so_no) {
                    tempRemoved.push(value);
                }
            });
            $scope.soItems = tempRemoved;
            
        }
        
    
         
        $scope.formValidate = function() {

            if($scope.soxItems.length===0) {

                $('#InputSOXAlert').modal('toggle');

        

            } else if($scope.soxItems.length > 0){
            

                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันการยกเลิก SOX/Cancel SOX', 'ยืนยันการยกเลิก SOX', 'CancelSOX()');

                $('body').append($compile(confirmModal)($scope));

                $('#confirmModal').modal('toggle');

            }

        }
        
        $scope.CancelSOX = function() {
            $('#confirmModal').modal('hide');
            $.post('/mkt/Cancel_Sox/Change_CancelSOX',{
                soxItems : JSON.stringify(angular.toJson($scope.soxItems)) },function(data) {

                addModal('successModal', 'Cancel SOX', 'ยกเลิกสำเร็จ');

                $('#successModal').modal('toggle');

                $('#successModal').on('hide.bs.modal', function (e) {

                    window.location.assign('/');

                });

                } )
            
        }

        $scope.formValidate2 = function() {
            console.log($scope.soItems)

             if($scope.soItems.length===0) {

                $('#InputSOAlert').modal('toggle');

        

            } else if($scope.soItems.length > 0){
            

                var confirmModal = addConfirmModal('confirmModal', 'ยืนยันการยกเลิก SO/Cancel SO', 'ยืนยันการยกเลิก SO', 'CancelSO()');

                $('body').append($compile(confirmModal)($scope));

                $('#confirmModal').modal('toggle');

            }

        }
        
        $scope.CancelSO = function() {
            $('#confirmModal').modal('hide');
            $.post('/mkt/Cancel_Sox/Change_CancelSO',{
                soItems : JSON.stringify(angular.toJson($scope.soItems)) },function(data) {

                addModal('successModal', 'Cancel SO', 'ยกเลิกสำเร็จ');

                $('#successModal').modal('toggle');

                $('#successModal').on('hide.bs.modal', function (e) {

                    window.location.assign('/');

                });

                } )
            
        }
        
    


    });



</script>