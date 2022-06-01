<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        
        <div class="row row-cols-2 row-cols-md-3 mt-2 p-0">

            <div class="col">
                <div class="card text-white bg-info m-2" >
                    <div class="card-body" ng-click="selectPVC()">
                        <h5 class="card-title my-0">ใบ PVC (PVC)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" >
                    <div class="card-body" ng-click="selectReReq()">
                        <h5 class="card-title my-0">ใบขอเบิกค่าใช้จ่าย (Re-Req)</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT pvc -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'PVC'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-C</th>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>Invoice</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="pvcs.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-C to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="pvc in pvcs" ng-click="viewFilepvc(pvc)">
                    <td>{{pvc.pv_no}}</td>
                    <td>{{pvc.pv_date}} {{pvc.pv_time}}</td>
                    <td>{{pvc.product_names}}</td>
                    <td>{{pvc.total_paid}}</td>
                    <td>
                        <span ng-show="pvc.pv_status < 4">fin ยังไม่ upload slip</span>
                        <a ng-show = "pvc.pv_status >= 4" href="/acc/confirm_payment_voucher/get_pvcslip/{{pvc.pv_no}}" target="_blank" ng-click="stopEvent($event)">slip</a> 

                    </td>
                    <td>
                        <span ng-show="pvc.pv_status < 4">fin ยังไม่ upload ใบ iv</span>
                        <a ng-show = "pvc.pv_status >= 4" href="/acc/confirm_payment_voucher/get_pvcslip/{{pvc.pv_no}}" target="_blank" ng-click="stopEvent($event)">iv</a> 

                    </td>
                  
                    <td>{{pvc.pv_status}}</td>
                </tr>
            </table>
            
        </div>
        <div ng-show = "temp == 'ReReq'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข EXC</th>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>Invoice</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="pvcs.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no Re-Req to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="pvc in pvcs" ng-click="viewFilepvc(pvc)">
                    <td>{{pvc.pv_no}}</td>
                    <td>{{pvc.pv_date}} {{pvc.pv_time}}</td>
                    <td>{{pvc.product_names}}</td>
                    <td>{{pvc.total_paid}}</td>
                    <td>
                        <span ng-show="pvc.pv_status < 4">fin ยังไม่ upload slip</span>
                        <a ng-show = "pvc.pv_status >= 4" href="/acc/confirm_payment_voucher/get_pvcslip/{{pvc.pv_no}}" target="_blank" ng-click="stopEvent($event)">slip</a> 

                    </td>
                    <td>
                        <span ng-show="pvc.pv_status < 4">fin ยังไม่ upload ใบ iv</span>
                        <a ng-show = "pvc.pv_status >= 4" href="/acc/confirm_payment_voucher/get_pvcslip/{{pvc.pv_no}}" target="_blank" ng-click="stopEvent($event)">iv</a> 

                    </td>
                  
                    <td>{{pvc.pv_status}}</td>
                </tr>
            </table>
            
        </div>

        
            
     

    

    </div>

</body> 

</html>

<style>
    td { border-bottom: 1px solid lightgray; }
    th { border-bottom: 1px solid lightgray; text-align: left; }
    .card:hover { transform: translate(0,-4px); box-shadow: 0 4px 8px lightgrey; }
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.doc = '';
        $scope.pvcs = <?php echo $this->pvcs; ?>;
        $scope.pvType = '';
        $scope.temp=''
        $scope.selectPVC = function(){
            $scope.temp='PVC'
            console.log("Hello")

        }
        $scope.selectReReq = function(){
            $scope.temp='ReReq'

        }
        


      


    
        
    });

</script>