<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        
        <div class="row row-cols-2 row-cols-md-3 mt-2 p-0">

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVD()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบ PVD (PVD)</h5>
                    </div>
                </div>
            </div>
<!-- 
            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPrePVD()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบลดหนี้</h5>
                    </div>
                </div>
            </div> -->
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT PVD -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'PV-D'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-D</th>
                    <th>วันที่</th>
                    <th>จำนวนเงิน</th>
                    <th>เอกสาร</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="pvds.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-D to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="pvd in pvds" ng-click="viewFilePVD(pvd)">
                    <td>{{pvd.pvd_no}}</td>
                    <td>{{pvd.pvd_date}} {{pvd.pvd_time}}</td>
                    <!-- <td>{{pvd.sox_no}}</td> -->
                    <td>{{pvd.total_amount}}</td>
                    <td>
                        <!-- <span ng-show="pvd.PVD_status == 0">acc ยังไม่สร้าง CN</span> -->
                        <!-- <a ng-show = "pvd.PVD_status > 0" href="/file/cn/{{pvd.invoice_no}}" target="_blank" ng-click="stopEvent($event)">CN</a>  -->
                        <!-- &ensp;  -->
                    
                        <!-- <span ng-show="pvd.PVD_status <= 1">acc ยังไม่ confirm</span> -->
                        <!-- <a ng-show = "pvd.PVD_status > 1" href="https://uaterp.cbachula.com/file/pvd/{{pvd.invoice_no}}" target="_blank" ng-click="stopEvent($event)">PV-D </a> -->
                        <a ng-show = "pvd.PVD_status > 2" href="/acc/confirm_payment_voucher/get_pvdslip/{{pvd.pvd_no}}" target="_blank" ng-click="stopEvent($event)">slip</a> 
                        &ensp;
                        <button type="button" class="btn btn-info" ng-click = "seePVD(pvd)">PV-D</button>
                        
                    
                        <!-- <span ng-show="pvd.slipName == null">fin ยังไม่ upload slip</span> -->
                        

                    </td>
                    <td>
                        <span ng-show="pvd.PVD_status == 0">0 ออกใบลดหนี้</span>
                        <span ng-show="pvd.PVD_status == 1">1 acc ยังไม่ออกใบ PV</span>
                        <span ng-show="pvd.PVD_status == 2">2 fin ยังไม่ upload slip</span>
                        <span ng-show="pvd.PVD_status == 3">3 acc ยังไม่ confirm PV</span>
                        <span ng-show="pvd.PVD_status == 4">4 acc confirmed</span>
                    </td>
                    
                </tr>
            </table>
            
        </div>

        <!-- <div ng-show = "temp == 'pre_PV-D'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-D</th>
                    <th>วันที่</th>
                    <th>SOX</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="prePvds.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-D to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="prePvd in prePvds">
                    <td>{{prePvd.pvd_no}}</td>
                    <td>{{prePvd.pvd_date}} {{prePvd.pvd_time}}</td>
                    <td>{{prePvd.sox_no}}</td> 
                    <td>{{prePvd.total_amount}}</td>
                    <td>
                        <a target = '_blank' href="/fin/validate_petty_cash_request/get_re/{{prePvd.internal_pva_no}}">Check reciept/invoice</a>
                        <a target = '_blank' href="/fin/validate_petty_cash_request/get_iv/{{prePvd.internal_pva_no}}">Check slip</a> 
                        <a ng-show="prePvd.PVD_status >= 1" href="/fin/create_pva/get_fin_slip/{{prePvd.internal_pva_no}}" target="_blank">สลิปโอนให้พนักงาน</a> 
                    </td>
    
                    <td>{{prePvd.PVD_status}}</td>
                </tr>
            </table>
            
        </div> -->

    

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
        $scope.pvds = <?php echo $this->pvds; ?>;
        // $scope.prePvds = <?php echo $this->prePvds; ?>;
        $scope.pvType = '';
        


        $scope.getDashboardPVD = function() {
            $scope.doc = 'PV';
            $scope.pvType = 'pvd';
            $scope.temp = 'PV-D';
        }

        // $scope.getDashboardPrePVD = function() {
        //     $scope.doc = 'PV';
        //     $scope.pvType = 'pvd';
        //     $scope.temp = 'pre_PV-D';
        // }


        $scope.stopEvent = function(e){
            e.stopPropagation();
        }
        
        $scope.viewFilePVD= function($dashboard) {
			window.open('/file/pvd/' + $dashboard.pvd_no);
		}
        
        
    });

</script>