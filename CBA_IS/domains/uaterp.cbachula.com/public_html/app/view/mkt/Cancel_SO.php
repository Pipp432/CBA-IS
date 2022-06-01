<!DOCTYPE html>

<html>



<body>



    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">ยกเลิก SO</h2> 

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- SELECTING CS AND CS DETAIL -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <div class="row mx-0">
                    <div class="col-md-12">
                        <label for="textboxSO">เลข SO</label>
                        <input type="text" class="form-control" id="textboxSO" ng-model="filterCS">
                    </div>
                </div>
                <hr>
                <div class="row mx-0 mt-2">
                    <table class="table table-hover my-1">
                        <tr>
                            <th>sox_no</th>
                            <th>so_no</th>
                            <th>product_no</th>
                            <th>product_name</th>                           
                            <th>total_sales</th>
                        </tr>
                        <tr ng-show="CSs.length == 0">
                            <th colspan="6">ไม่มีเลข CS ที่ต้องออก IV</th>
                        </tr>
                        <tr ng-repeat="cs in CSs | unique:'cs_no' | filter:{cs_no:filterCS} | orderBy:['cs_no', 'cs_date']" ng-click="addCrItem(cs)" ng-show="CSs.length > 0">
                            <td>{{cs.cs_no}}</td>
                            <td>{{cs.cs_date}}</td>
                            <td>{{cs.CE_nickname}}</td>
                            <!--<td ng-repeat="emp in cs | unique:'employee_id' | orderBy:['employee_id']">{{emp.Emp_nickname}} {{emp.employee_id}}</td>-->
                            <td>{{cs.location_name}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <!-- FORM VALIDATION -->

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        
        <script>

        addModal('SONotFoundAlert', 'Cancel SOX', 'ไม่เจอเลข SO นี้นะครับ');
        addModal('SlipAlert', 'Cancel SOX', 'SO นี้กำลังตรวจสลิปแล้วนะครับ ติดต่อ IS ด่วน!!');
        addModal('RangeAlert', 'Cancel SOX', 'พิมผิดป่าววววววววววว');
        addModal('InputAlert', 'Cancel SOX', 'กรอกเลข SO ก่อนน้า');


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
		
		
        $scope.addSox = function() {

            if($scope.so_no.length === 9) {

                $http.post('/mkt/Cancel_Sox/get_SOX', 

                    JSON.stringify({so_no : $scope.so_no})

                ).then(function(response) {
                    $scope.numbers = response.data
                    console.log(response.data[0].done)
                    console.log(response.data[0].slip_uploaded)
                   
                   
                    if($scope.so_no.toUpperCase() == response.data[0].so_no.toUpperCase() && (response.data[0].done != -1 
                     || response.data[0].slip_uploaded != 0 )){
                       
                        $('#SlipAlert').modal('toggle');
                                
                                          

                    } else if($scope.so_no.toUpperCase() == response.data[0].so_no.toUpperCase() &&  response.data[0].done == -1 
                     && response.data[0].slip_uploaded == 0 ){

                        $scope.sox_no= response.data[0].sox_no;
                        $scope.customer_tel = response.data[0].customer_tel;

                
                    }

                });

            } else if($scope.so_no.length > 9) {
                $('#RangeAlert').modal('toggle');
                
			}
            else{
                $scope.sox_no = '';
                $scope.customer_tel = '';
            }

        }

        $scope.CancelSox = function() {
            
            if($scope.so_no === '') {

                $('#InputAlert').modal('toggle');
            
            }else if($scope.sox_no == ''){
                $('#SONotFoundAlert').modal('toggle');
            }else if($scope.sox_no != ''){
                
                $.post('/mkt/Cancel_Sox/Change_Cancel',
                JSON.stringify({so_no : $scope.so_no}) )
            }
            
        }

    


    });



</script>