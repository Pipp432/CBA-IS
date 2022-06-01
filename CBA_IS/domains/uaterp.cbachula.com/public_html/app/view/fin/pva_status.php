<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">Dashboard</h2>
        
        
        <div class="row row-cols-2 row-cols-md-3 mt-2 p-0">

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPVA()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบ PVA (PVA)</h5>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-info m-2" ng-click="getDashboardPrePVA()">
                    <div class="card-body">
                        <h5 class="card-title my-0">ใบขอเบิกเงินรองจ่าย</h5>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- DOCUMENT PVA -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        
        <div ng-show = "temp == 'PV-A'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-A</th>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="pvas.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-A to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="pva in pvas" ng-click="viewFilePVA(pva)">
                    <td>{{pva.pv_no}}</td>
                    <td>{{pva.pv_date}} {{pva.pv_time}}</td>
                    <td class = "newLine">{{pva.product_names}}</td>
                    <td>{{pva.total_paid}}</td>
                    <td>
                        <span ng-show="pva.pv_status < 4">fin ยังไม่ upload slip</span>
                        <a ng-show = "pva.pv_status >= 4" href="/acc/confirm_payment_voucher/get_pvaslip/{{pva.pv_no}}" target="_blank" ng-click="stopEvent($event)">slip</a> 

                    </td>
                    <!-- todo convert status to readable -->
                    <td>{{pva.pv_status}}</td>
                </tr>
            </table>
            
        </div>

        <div ng-show = "temp == 'pre_PV-A'" class="mt-2 p-0">
            
            <table class="table table-hover my-1">
                <tr>
                    <th>เลข PV-A</th>
                    <th>วันที่</th>
                    <th>รายการ</th>
                    <th>จำนวนเงิน</th>
                    <th>Slip</th>
                    <th>สถานะ</th>
                </tr>
                <tr ng-show="prePvas.length == 0">
                    <th colspan="5">
                        <h6 class="my-0" style="text-align:center;"> no PV-A to show</h6>
                    </th>
                </tr>
                <tr ng-repeat="prePva in prePvas">
                    <td>{{prePva.pv_no}}</td>
                    <td>{{prePva.pv_date}} {{prePva.pv_time}}</td>
                    <td>{{prePva.product_names}}</td>
                    <td>{{prePva.total_paid}}</td>
                    <td>
                        <a target = '_blank' href="/fin/validate_petty_cash_request/get_re/{{prePva.internal_pva_no}}">Check reciept/invoice</a>
                        <a target = '_blank' href="/fin/validate_petty_cash_request/get_iv/{{prePva.internal_pva_no}}">Check slip</a>
                        <a ng-show="prePva.pv_status >= 1" href="/fin/create_pva/get_fin_slip/{{prePva.internal_pva_no}}" target="_blank">สลิปโอนให้พนักงาน</a> 
                    </td>
                    <!-- todo convert status to readable -->
                    <td>{{prePva.pv_status}}</td>
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
    .newLine {white-space: pre}
</style>

<script>

    app.controller('moduleAppController', function($scope, $http, $compile) {
        
        $scope.doc = '';
        $scope.pvas = <?php echo $this->pvas; ?>;
        $scope.prePvas = <?php echo $this->prePvas; ?>;
        $scope.pvType = '';
        convert_pva_status = {
            '-1':"ยกเลิก",
            0:"รอ finance โอนให้พนักงาน",
            1:"รอ finance รวมใบขอ pva",
            2:"รอ account สร้าง pva",
            3:"รอ finance โอนเงินเข้าบัญชีเงินรองจ่าย",
            4:"รอ account confirm pva", 
            5:"เรียบร้อย",
        }
        angular.forEach($scope.pvas, function(value, key) {
            value["pv_status"] = convert_pva_status[value["pv_status"]];
        });

        angular.forEach($scope.prePvas, function(value, key) {
            value["pv_status"] = convert_pva_status[value["pv_status"]];
        });


        $scope.getDashboardPVA = function() {
            $scope.doc = 'PV';
            $scope.pvType = 'pva';
            $scope.temp = 'PV-A';
        }

        $scope.getDashboardPrePVA = function() {
            $scope.doc = 'PV';
            $scope.pvType = 'pva';
            $scope.temp = 'pre_PV-A';
        }


        $scope.stopEvent = function(e){
            e.stopPropagation();
        }
        
        $scope.viewFilePVA = function($dashboard) {
            window.open('/file/pva/' + $dashboard.pv_no);
        }
        
    });

</script>